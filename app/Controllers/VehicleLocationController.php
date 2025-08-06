<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class VehicleLocationController extends ResourceController
{
    protected $db;
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Batch NFC tracker interface for multiple vehicles
     * URL: /location/batch
     */
    public function batchTracker()
    {
        // Get current user info if logged in
        $current_user = null;
        $user_type = null;
        
        if (auth()->loggedIn()) {
            $current_user = auth()->user();
            $groups = $current_user->getGroups();
            $user_type = !empty($groups) ? $groups[0] : null;
        }

        $data = [
            'page_title' => lang('App.batch_nfc_tracker'),
            'current_user' => $current_user,
            'user_type' => $user_type
        ];

        return view('location/batch_tracker', $data);
    }

    /**
     * Mobile interface for NFC tag scanning
     * URL: /location/{token}
     */
    public function track($token = null)
    {
        if (!$token) {
            return $this->fail('Token is required', 400);
        }

        // Validate token and get vehicle info
        $tokenData = $this->validateToken($token);
        if (!$tokenData) {
            return view('location/invalid_token');
        }

        // Get recent locations for this vehicle
        $recentLocations = $this->getRecentLocations($tokenData['vin_number'], 5);
        
        // Get available parking spots
        $parkingSpots = $this->getParkingSpots();

        // Get current user info if logged in
        $currentUser = null;
        $userType = null;
        if (auth()->loggedIn()) {
            $currentUser = auth()->user();
            $userType = $currentUser->user_type ?? null;
        }

        $data = [
            'token' => $token,
            'vehicle' => $tokenData,
            'recent_locations' => $recentLocations,  
            'parking_spots' => $parkingSpots,
            'page_title' => 'Vehicle Location Tracker',
            'current_user' => $currentUser,
            'user_type' => $userType
        ];

        return view('location/mobile_tracker', $data);
    }

    /**
     * API endpoint to save location
     */
    public function saveLocation()
    {
        $json = $this->request->getJSON(true);
        
        if (!$json) {
            return $this->fail('Invalid JSON data', 400);
        }

        // Validate required fields (only token is required)
        $required = ['token'];
        foreach ($required as $field) {
            if (!isset($json[$field]) || empty($json[$field])) {
                return $this->fail("Field '{$field}' is required", 400);
            }
        }
        
        // At least spot_number should be provided if no location coordinates
        if (empty($json['spot_number']) && (empty($json['latitude']) || empty($json['longitude']))) {
            return $this->fail('Either parking spot number or location coordinates must be provided', 400);
        }

        // Validate token
        $tokenData = $this->validateToken($json['token']);
        if (!$tokenData) {
            return $this->fail('Invalid token', 401);
        }

        // Get user info from session if available
        $userId = auth()->id() ?? null;
        $userName = auth()->user()->username ?? $json['user_name'] ?? 'Anonymous';

        // Prepare location data
        $locationData = [
            'vehicle_id' => $tokenData['vehicle_id'],
            'vin_number' => $tokenData['vin_number'],
            'spot_number' => !empty($json['spot_number']) ? $json['spot_number'] : 'UNSPECIFIED',
            'latitude' => $json['latitude'] ?? null,
            'longitude' => $json['longitude'] ?? null,
            'accuracy' => $json['accuracy'] ?? null,
            'user_id' => $userId,
            'user_name' => $userName,
            'notes' => $json['notes'] ?? null,
            'token_used' => $json['token'],
            'device_info' => json_encode([
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
                'ip_address' => $this->request->getIPAddress(),
                'timestamp' => date('Y-m-d H:i:s')
            ]),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Check if spot exists, if not create it (only if spot_number is provided)
        if (!empty($json['spot_number'])) {
        $spotId = $this->ensureParkingSpot($json['spot_number']);
        if ($spotId) {
            $locationData['spot_id'] = $spotId;
            }
        }

        // Save location
        try {
            // Log the data being inserted for debugging
            log_message('info', 'Attempting to save location data: ' . json_encode($locationData));
            
            $this->db->table('vehicle_locations')->insert($locationData);
            $locationId = $this->db->insertID();

            if (!$locationId) {
                throw new \Exception('Failed to insert location record - no ID returned');
            }

            return $this->respond([
                'success' => true,
                'message' => 'Location saved successfully',
                'location_id' => $locationId,
                'vehicle' => $tokenData['vin_number'],
                'spot' => $json['spot_number'] ?? null,
                'timestamp' => $locationData['created_at']
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Failed to save vehicle location: ' . $e->getMessage());
            log_message('error', 'Location data: ' . json_encode($locationData));
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->fail([
                'success' => false,
                'message' => 'Failed to save location: ' . $e->getMessage(),
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint to get vehicle info from token
     */
    public function getTokenInfo($token = null)
    {
        if (!$token) {
            return $this->fail('Token is required', 400);
        }

        // Validate token and get vehicle info
        $tokenData = $this->validateToken($token);
        if (!$tokenData) {
            return $this->fail('Invalid NFC code', 401);
        }

        // Get additional vehicle information
        $vehicle = $this->db->table('recon_orders')
            ->select('id, vin_number, vehicle, stock, order_number')
            ->where('vin_number', $tokenData['vin_number'])
            ->where('deleted_at IS NULL')
            ->get()
            ->getRowArray();

        if (!$vehicle) {
            // Fallback with basic info from token
            $vehicle = [
                'vin_number' => $tokenData['vin_number'],
                'vehicle' => 'Vehicle',
                'stock' => null,
                'order_number' => null
            ];
        }

        return $this->respond([
            'success' => true,
            'vehicle' => $vehicle,
            'token' => $token
        ]);
    }

    /**
     * Send email report with CSV data
     */
    public function sendEmailReport()
    {
        if (!auth()->loggedIn()) {
            return $this->fail('Authentication required', 401);
        }

        $user = auth()->user();
        $json = $this->request->getJSON(true);

        if (!$json || !isset($json['csv_data'])) {
            return $this->fail('CSV data is required', 400);
        }

        try {
            // Get user info
            $userName = trim($user->firstname . ' ' . $user->lastname);
            $userEmail = $user->email;

            if (empty($userEmail)) {
                return $this->fail('User email not found', 400);
            }

            // Validate email format
            if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                return $this->fail('Invalid email address', 400);
            }

            // Create CSV file content
            $csvContent = $json['csv_data'];
            $fileName = 'vehicle-scan-report-' . date('Y-m-d-His') . '.csv';
            
            // Debug: Log received data
            log_message('info', 'Email report - Raw JSON received: ' . json_encode($json));
            log_message('info', 'Email report - CSV content length: ' . strlen($csvContent) . ' chars');
            log_message('info', 'Email report - CSV first 500 chars: ' . substr($csvContent, 0, 500));
            
            // Validate CSV content - if empty or invalid, create sample data
            if (empty($csvContent) || strlen($csvContent) < 50) {
                log_message('error', 'Email report - CSV content is empty or too short, creating sample data');
                $csvContent = $this->generateSampleCSV();
            }
            
            // Ensure CSV has proper headers
            if (strpos($csvContent, 'Vehicle,VIN,Spot Number') === false) {
                log_message('error', 'Email report - CSV missing headers, prepending headers');
                $csvContent = "Vehicle,VIN,Spot Number,Notes,Latitude,Longitude,Address,Timestamp\n" . $csvContent;
            }

            // Email template data
            $totalVehicles = $json['total_vehicles'] ?? 0;
            $successfulScans = $json['successful_scans'] ?? 0;
            $errorCount = $json['error_count'] ?? 0;
            $sessionDate = $json['session_date'] ?? date('Y-m-d');
            $sessionTime = $json['session_time'] ?? date('H:i:s');

            // Generate email template based on user's language
            $userLanguage = $this->getUserLanguage($user);
            $message = $this->generateEmailTemplate($userLanguage, $userName, [
                'totalVehicles' => $totalVehicles,
                'successfulScans' => $successfulScans,
                'errorCount' => $errorCount,
                'sessionDate' => $sessionDate,
                'sessionTime' => $sessionTime
            ]);

            // Load email service (uses SMTP configuration from database settings)
            $email = \Config\Services::email();
            
            // Get SMTP settings directly from database
            $settingsModel = new \App\Models\SettingsModel();
            $smtpSettings = $settingsModel->getSmtpSettings();
            
            // Validate SMTP configuration
            if (empty($smtpSettings['smtp_user']) || empty($smtpSettings['smtp_pass'])) {
                log_message('error', 'SMTP configuration missing in database settings');
                return $this->fail('Email service not configured. Please check Settings > Email Settings tab.', 500);
            }

            // Set email details (use configured fromEmail or fallback)
            $fromEmail = !empty($smtpSettings['smtp_from']) ? $smtpSettings['smtp_from'] : 'noreply@mda.to';
            $fromName = 'MDA Vehicle Tracker';
            
            // Set email subject based on user's language
            $subjects = [
                'en' => 'Vehicle Scan Report - ' . date('Y-m-d H:i:s'),
                'es' => 'Reporte de Escaneo de Vehículos - ' . date('Y-m-d H:i:s'),
                'pt' => 'Relatório de Scanner de Veículos - ' . date('Y-m-d H:i:s')
            ];
            $subject = $subjects[$userLanguage] ?? $subjects['en'];
            
            $email->setFrom($fromEmail, $fromName);
            $email->setTo($userEmail);
            $email->setSubject($subject);
            $email->setMessage($message);
            
            // Use attach() method with CSV content directly as buffer string
            // This avoids temporary file issues and passes content directly
            log_message('info', 'Email report - Attaching CSV content directly as buffer string, size: ' . strlen($csvContent) . ' bytes');
            
            // Method: attach($buffer_content, $disposition, $filename, $mime)
            // When first parameter is string content (not file path), it treats it as buffer
            $email->attach($csvContent, 'attachment', $fileName, 'text/csv');
            
            log_message('info', 'Email report - CSV content attached successfully as buffer');

            // Send email
            $result = $email->send();
            
            log_message('info', 'Email report - Email send attempt completed, result: ' . ($result ? 'success' : 'failed'));
            
            if ($result) {
                log_message('info', "Email report sent successfully to: {$userEmail}");
                return $this->respond([
                    'success' => true,
                    'message' => 'Email sent successfully'
                ]);
            } else {
                $debugInfo = $email->printDebugger(['headers']);
                log_message('error', 'Email sending failed. Debug info: ' . $debugInfo);
                return $this->fail('Failed to send email. Please check server email configuration.', 500);
            }

        } catch (\Exception $e) {
            // Clean up temporary file if it exists
            if (isset($tempFile) && file_exists($tempFile)) {
                unlink($tempFile);
                log_message('info', 'Email report - Temporary file cleaned up after error: ' . $tempFile);
            }
            
            log_message('error', 'Email report error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return $this->fail('Email service error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generate sample CSV for testing/fallback
     */
    private function generateSampleCSV()
    {
        $sampleCSV = "Vehicle,VIN,Spot Number,Notes,Latitude,Longitude,Address,Timestamp\n";
        $sampleCSV .= "Honda Civic,1HGBH41JXMN109186,A-01,Sample test vehicle,25.761681,-80.191788,\"1234 Main St, Miami, FL\"," . date('Y-m-d H:i:s') . "\n";
        $sampleCSV .= "Toyota Camry,4T1BF1FK5CU123456,B-15,Another sample vehicle,-1.286389,-36.817223,\"Fortaleza, CE, Brazil\"," . date('Y-m-d H:i:s') . "\n";
        $sampleCSV .= "Ford Focus,1FADP3F20FL123456,C-20,Third sample vehicle,40.712776,-74.005974,\"New York, NY, USA\"," . date('Y-m-d H:i:s') . "\n";
        
        return $sampleCSV;
    }

    /**
     * Test CSV generation - for debugging email attachments
     */
    public function testCSVGeneration()
    {
        if (!auth()->loggedIn()) {
            return $this->fail('Authentication required', 401);
        }

        $sampleCSV = $this->generateSampleCSV();

        // Set headers to download as CSV
        $this->response->setHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="test-csv-generation.csv"');
        
        return $this->response->setBody($sampleCSV);
    }

    /**
     * Test email with CSV attachment - for debugging
     */
    public function testEmailWithCSV()
    {
        if (!auth()->loggedIn()) {
            return $this->fail('Authentication required', 401);
        }

        $user = auth()->user();
        $userEmail = $user->email;

        if (empty($userEmail)) {
            return $this->fail('User email not found', 400);
        }

        try {
            // Get SMTP settings directly from database
            $settingsModel = new \App\Models\SettingsModel();
            $smtpSettings = $settingsModel->getSmtpSettings();
            
            // Validate SMTP configuration
            if (empty($smtpSettings['smtp_user']) || empty($smtpSettings['smtp_pass'])) {
                return $this->fail('Email service not configured. Please check Settings > Email Settings tab.', 500);
            }

            // Generate sample CSV
            $csvContent = $this->generateSampleCSV();
            $fileName = 'test-email-csv-' . date('Y-m-d-His') . '.csv';

            // Create email
            $email = \Config\Services::email();
            
            // Get user's language for localized content
            $userLanguage = $this->getUserLanguage($user);
            
            $fromEmail = !empty($smtpSettings['smtp_from']) ? $smtpSettings['smtp_from'] : 'noreply@mda.to';
            $fromName = 'MDA Vehicle Tracker - TEST';
            
            // Set test email subject based on user's language
            $testSubjects = [
                'en' => 'TEST - CSV Email Attachment - ' . date('Y-m-d H:i:s'),
                'es' => 'PRUEBA - Adjunto CSV por Email - ' . date('Y-m-d H:i:s'),
                'pt' => 'TESTE - Anexo CSV por Email - ' . date('Y-m-d H:i:s')
            ];
            $testSubject = $testSubjects[$userLanguage] ?? $testSubjects['en'];
            
            $email->setFrom($fromEmail, $fromName);
            $email->setTo($userEmail);
            $email->setSubject($testSubject);
            
            // Generate test email template based on user's language
            $userName = trim($user->firstname . ' ' . $user->lastname);
            $testMessage = $this->generateTestEmailTemplate($userLanguage, $userName, $fileName, strlen($csvContent));
            
            $email->setMessage($testMessage);

            // Use attach() method with CSV content directly as buffer string (same as main method)
            log_message('info', 'Test CSV email - Attaching CSV content directly as buffer string, size: ' . strlen($csvContent) . ' bytes');
            
            // Method: attach($buffer_content, $disposition, $filename, $mime)
            $email->attach($csvContent, 'attachment', $fileName, 'text/csv');
            
            log_message('info', 'Test CSV email - CSV content attached successfully as buffer');
            
            // Send email
            $result = $email->send();
            
            log_message('info', 'Test CSV email - Email send attempt completed, result: ' . ($result ? 'success' : 'failed'));
            
            if ($result) {
                return $this->respond([
                    'success' => true,
                    'message' => 'Test email with CSV sent successfully to ' . $userEmail,
                    'details' => [
                        'file_name' => $fileName,
                        'csv_size' => strlen($csvContent) . ' chars',
                        'rows' => 4
                    ]
                ]);
            } else {
                $debugInfo = $email->printDebugger(['headers']);
                return $this->fail('Failed to send test email. Debug: ' . $debugInfo, 500);
            }

        } catch (\Exception $e) {
            if (isset($tempFile) && file_exists($tempFile)) {
                unlink($tempFile);
            }
            return $this->fail('Test email error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Test email configuration - shows current SMTP settings and connection status
     */
    public function testEmailConfig()
    {
        if (!auth()->loggedIn()) {
            return $this->fail('Authentication required', 401);
        }

        try {
            // Get email configuration from database
            $settingsModel = new \App\Models\SettingsModel();
            $smtpSettings = $settingsModel->getSmtpSettings();
            
            // Get current user for test email
            $user = auth()->user();
            $userEmail = $user->email;

            // Check configuration status
            $configStatus = [
                'smtp_host' => $smtpSettings['smtp_host'] ?: 'NOT SET',
                'smtp_port' => $smtpSettings['smtp_port'] ?: 'NOT SET',
                'smtp_user' => $smtpSettings['smtp_user'] ? '***configured***' : 'NOT SET',
                'smtp_pass' => $smtpSettings['smtp_pass'] ? '***configured***' : 'NOT SET',
                'smtp_encryption' => $smtpSettings['smtp_encryption'] ?: 'NOT SET',
                'smtp_from' => $smtpSettings['smtp_from'] ?: 'NOT SET'
            ];

            // Validate configuration
            $isConfigured = !empty($smtpSettings['smtp_host']) && 
                           !empty($smtpSettings['smtp_user']) && 
                           !empty($smtpSettings['smtp_pass']) && 
                           !empty($smtpSettings['smtp_from']);

            if (!$isConfigured) {
                return $this->respond([
                    'success' => false,
                    'message' => 'SMTP not configured properly',
                    'config' => $configStatus,
                    'instructions' => [
                        '1. Go to Settings > Email Settings tab',
                        '2. Configure SMTP settings:',
                        '   - SMTP Host (e.g., smtp.gmail.com)',
                        '   - SMTP Port (usually 587 for TLS)',
                        '   - SMTP Username (your email)',
                        '   - SMTP Password (your app password)',
                        '   - Encryption (TLS or SSL)',
                        '   - From Email (sender email)',
                        '3. Save settings and try again'
                    ]
                ]);
            }

            // Try to send a test email if user email is available
            if ($userEmail) {
                $email = \Config\Services::email();
                
                $email->setTo($userEmail);
                $email->setSubject('SMTP Test - MDA Vehicle Tracker');
                
                $testMessage = "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <h2>✅ SMTP Configuration Test</h2>
                    <p>Hello <strong>{$user->firstname} {$user->lastname}</strong>,</p>
                    <p>If you receive this email, your SMTP configuration is working correctly!</p>
                    <hr>
                    <p><small>Test sent at: " . date('Y-m-d H:i:s') . "</small></p>
                </body>
                </html>";
                
                $email->setMessage($testMessage);

                if ($email->send()) {
                    log_message('info', "Test email sent successfully to: {$userEmail}");
                    return $this->respond([
                        'success' => true,
                        'message' => 'SMTP configured correctly! Test email sent to ' . $userEmail,
                        'config' => $configStatus
                    ]);
                } else {
                    $debugInfo = $email->printDebugger(['headers']);
                    log_message('error', 'Test email failed. Debug: ' . $debugInfo);
                    return $this->respond([
                        'success' => false,
                        'message' => 'SMTP configuration error. Email could not be sent.',
                        'config' => $configStatus,
                        'debug' => $debugInfo
                    ]);
                }
            } else {
                return $this->respond([
                    'success' => false,
                    'message' => 'User email not found. Cannot send test email.',
                    'config' => $configStatus
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Email config test error: ' . $e->getMessage());
            return $this->fail('Email configuration test error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generate NFC token for a vehicle
     */
    public function generateToken($vinNumber = null)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'));
        }

        if (!$vinNumber) {
            return $this->fail('VIN number is required', 400);
        }

        // Get vehicle info
        $vehicle = $this->db->table('recon_orders')
            ->select('id, vin_number, vehicle')
            ->where('vin_number', strtoupper($vinNumber))
            ->where('deleted_at IS NULL')
            ->get()
            ->getRowArray();

        if (!$vehicle) {
            return $this->fail('Vehicle not found', 404);
        }

        // Check if token already exists
        $existingToken = $this->db->table('vehicle_location_tokens')
            ->where('vin_number', $vehicle['vin_number'])
            ->where('is_active', 1)
            ->get()
            ->getRowArray();

        if ($existingToken) {
            $token = $existingToken['token'];
        } else {
            // Generate new token
            $token = bin2hex(random_bytes(32));
            
            $tokenData = [
                'vehicle_id' => $vehicle['id'],
                'vin_number' => $vehicle['vin_number'],
                'token' => $token,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->table('vehicle_location_tokens')->insert($tokenData);
        }

        $nfcUrl = base_url("location/{$token}");

        return $this->respond([
            'success' => true,
            'vehicle' => $vehicle,
            'token' => $token,
            'nfc_url' => $nfcUrl,
            'qr_data' => $nfcUrl
        ]);
    }

    /**
     * Get location history for a vehicle
     */
    public function getLocationHistory($vinNumber = null)
    {
        if (!$vinNumber) {
            return $this->fail('VIN number is required', 400);
        }

        try {
            // First try to get locations with user info if users table exists
            $sql = "SELECT vl.id, vl.spot_number, vl.latitude, vl.longitude, vl.accuracy, vl.user_name, vl.notes, vl.created_at,
                           COALESCE(u.first_name, '') as first_name,
                           COALESCE(u.last_name, '') as last_name,
                           COALESCE(u.username, vl.user_name) as username,
                           CASE 
                               WHEN u.first_name IS NOT NULL OR u.last_name IS NOT NULL THEN
                                   CONCAT(COALESCE(u.first_name, ''), 
                                          CASE WHEN u.first_name IS NOT NULL AND u.last_name IS NOT NULL THEN ' ' ELSE '' END,
                                          COALESCE(u.last_name, ''))
                               ELSE vl.user_name
                           END as user_full_name
                    FROM vehicle_locations vl
                    LEFT JOIN users u ON u.username = vl.user_name
                    WHERE vl.vin_number = ?
                    ORDER BY vl.created_at DESC
                    LIMIT 50";
            
            log_message('debug', 'Executing location history query for VIN: ' . $vinNumber);
            $locations = $this->db->query($sql, [strtoupper($vinNumber)])->getResultArray();
            
            log_message('debug', 'Found ' . count($locations) . ' locations');
            
            // Clean up user full names - if empty, use username or fallback
            foreach ($locations as &$location) {
                if (empty(trim($location['user_full_name'] ?? ''))) {
                    $location['user_full_name'] = $location['username'] ?? $location['user_name'] ?? 'Anonymous';
                }
            }
            
                } catch (Exception $e) {
            // Fallback: get basic location data without user join
            log_message('error', 'Database error in getLocationHistory: ' . $e->getMessage());
            log_message('error', 'VIN: ' . $vinNumber . ', Error: ' . $e->getFile() . ':' . $e->getLine());
            
            try {
        $locations = $this->db->table('vehicle_locations')
            ->select('id, spot_number, latitude, longitude, accuracy, user_name, notes, created_at')
            ->where('vin_number', strtoupper($vinNumber))
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();
                    
                log_message('info', 'Fallback query successful, found ' . count($locations) . ' locations');
                    
                // Add fallback user_full_name
                foreach ($locations as &$location) {
                    $location['user_full_name'] = $location['user_name'] ?? 'Anonymous';
                    $location['first_name'] = '';
                    $location['last_name'] = '';
                    $location['username'] = $location['user_name'] ?? '';
                }
            } catch (Exception $fallbackError) {
                log_message('critical', 'Even fallback query failed: ' . $fallbackError->getMessage());
                return $this->fail('Database error: ' . $fallbackError->getMessage(), 500);
            }
        }

        return $this->respond([
            'success' => true,
            'vin_number' => strtoupper($vinNumber),
            'locations' => $locations,
            'total' => count($locations)
        ]);
    }

    /**
     * View detailed information about a specific location record
     */
    public function viewLocationDetails($locationId = null)
    {
        if (!$locationId) {
            return redirect()->back()->with('error', 'Location ID is required');
        }

        try {
            // Get detailed location information with user details
            $sql = "
                SELECT vl.*, ps.zone, ps.description as spot_description, 
                       ro.vehicle, ro.order_number, ro.status, ro.priority, ro.stock,
                       u.first_name, u.last_name, u.username, u.id as user_id,
                       ai.secret as user_email,
                       CASE 
                           WHEN u.first_name IS NOT NULL OR u.last_name IS NOT NULL THEN
                               CONCAT(COALESCE(u.first_name, ''), 
                                      CASE WHEN u.first_name IS NOT NULL AND u.last_name IS NOT NULL THEN ' ' ELSE '' END,
                                      COALESCE(u.last_name, ''))
                           ELSE COALESCE(u.username, vl.user_name)
                       END as user_full_name
                FROM vehicle_locations vl
                LEFT JOIN parking_spots ps ON ps.id = vl.spot_id
                LEFT JOIN recon_orders ro ON ro.vin_number COLLATE utf8mb4_general_ci = vl.vin_number COLLATE utf8mb4_general_ci
                LEFT JOIN users u ON u.username = vl.user_name
                LEFT JOIN auth_identities ai ON ai.user_id = u.id AND ai.type = 'email_password'
                WHERE vl.id = ?
            ";
            
            $location = $this->db->query($sql, [$locationId])->getRowArray();
        } catch (Exception $e) {
            log_message('error', 'Error getting location details: ' . $e->getMessage());
            
            // Fallback without user join
        $sql = "
            SELECT vl.*, ps.zone, ps.description as spot_description, 
                   ro.vehicle, ro.order_number, ro.status, ro.priority, ro.stock
            FROM vehicle_locations vl
            LEFT JOIN parking_spots ps ON ps.id = vl.spot_id
            LEFT JOIN recon_orders ro ON ro.vin_number COLLATE utf8mb4_general_ci = vl.vin_number COLLATE utf8mb4_general_ci
            WHERE vl.id = ?
        ";
        
        $location = $this->db->query($sql, [$locationId])->getRowArray();
            
            if ($location) {
                $location['user_full_name'] = $location['user_name'] ?? 'Anonymous';
                $location['first_name'] = '';
                $location['last_name'] = '';
                $location['username'] = $location['user_name'] ?? '';
                $location['user_email'] = '';
            }
        }

        if (!$location) {
            return redirect()->back()->with('error', 'Location record not found');
        }

        // Parse device info JSON
        $deviceInfo = [];
        if (!empty($location['device_info'])) {
            $deviceInfo = json_decode($location['device_info'], true) ?? [];
        }

        // User details are now included in the main query
        $userDetails = null;
        if (!empty($location['username'])) {
            $userDetails = [
                'id' => $location['user_id'] ?? null,
                'username' => $location['username'],
                'first_name' => $location['first_name'] ?? '',
                'last_name' => $location['last_name'] ?? '',
                'full_name' => $location['user_full_name'] ?? $location['user_name'] ?? 'Anonymous',
                'email' => $location['user_email'] ?? ''
            ];
        }

        // Get nearby locations for context (within 100m radius if GPS available)
        $nearbyLocations = [];
        if (!empty($location['latitude']) && !empty($location['longitude'])) {
            try {
                $nearbyLocationsSql = "
                    SELECT vl.id, vl.vin_number, vl.spot_number, vl.user_name, vl.created_at, vl.latitude, vl.longitude,
                           ro.stock, ro.vehicle, ro.order_number, ro.status as order_status, ro.priority,
                           u.first_name, u.last_name, u.username,
                           CASE 
                               WHEN u.first_name IS NOT NULL OR u.last_name IS NOT NULL THEN
                                   CONCAT(COALESCE(u.first_name, ''), 
                                          CASE WHEN u.first_name IS NOT NULL AND u.last_name IS NOT NULL THEN ' ' ELSE '' END,
                                          COALESCE(u.last_name, ''))
                               ELSE COALESCE(u.username, vl.user_name)
                           END as user_full_name,
                           (6371 * acos(cos(radians(" . $location['latitude'] . ")) 
                           * cos(radians(vl.latitude)) 
                           * cos(radians(vl.longitude) - radians(" . $location['longitude'] . ")) 
                           + sin(radians(" . $location['latitude'] . ")) 
                           * sin(radians(vl.latitude)))) AS distance
                    FROM vehicle_locations vl
                    LEFT JOIN recon_orders ro ON ro.vin_number COLLATE utf8mb4_general_ci = vl.vin_number COLLATE utf8mb4_general_ci
                    LEFT JOIN users u ON u.username = vl.user_name
                    WHERE vl.id != ? 
                    AND vl.vin_number != ?
                    HAVING distance <= 0.1
                    ORDER BY distance ASC
                    LIMIT 10
                ";
                $nearbyLocations = $this->db->query($nearbyLocationsSql, [$locationId, $location['vin_number']])->getResultArray();
            } catch (Exception $e) {
                log_message('error', 'Error getting nearby locations with vehicle info: ' . $e->getMessage());
                
                // Fallback to original query
                $nearbyLocations = $this->db->table('vehicle_locations')
                    ->select('id, vin_number, spot_number, user_name, created_at, 
                             (6371 * acos(cos(radians(' . $location['latitude'] . ')) 
                             * cos(radians(latitude)) 
                             * cos(radians(longitude) - radians(' . $location['longitude'] . ')) 
                             + sin(radians(' . $location['latitude'] . ')) 
                             * sin(radians(latitude)))) AS distance')
                    ->where('id !=', $locationId)
                    ->where('vin_number !=', $location['vin_number'])
                    ->having('distance <=', 0.1) // 100 meters
                    ->orderBy('distance', 'ASC')
                    ->limit(10)
                    ->get()
                    ->getResultArray();
                    
                // Ensure user_full_name is set for fallback data
                foreach ($nearbyLocations as &$nearby) {
                    $nearby['user_full_name'] = $nearby['user_name'] ?? 'Anonymous';
                }
            }
        }

        // Get location history for same vehicle with user full names
        try {
            $vehicleHistorySql = "
                SELECT vl.id, vl.spot_number, vl.created_at, vl.user_name,
                       u.first_name, u.last_name, u.username,
                       CASE 
                           WHEN u.first_name IS NOT NULL OR u.last_name IS NOT NULL THEN
                               CONCAT(COALESCE(u.first_name, ''), 
                                      CASE WHEN u.first_name IS NOT NULL AND u.last_name IS NOT NULL THEN ' ' ELSE '' END,
                                      COALESCE(u.last_name, ''))
                           ELSE COALESCE(u.username, vl.user_name)
                       END as user_full_name
                FROM vehicle_locations vl
                LEFT JOIN users u ON u.username = vl.user_name
                WHERE vl.vin_number = ? AND vl.id != ?
                ORDER BY vl.created_at DESC
                LIMIT 5
            ";
            $vehicleHistory = $this->db->query($vehicleHistorySql, [$location['vin_number'], $locationId])->getResultArray();
        } catch (Exception $e) {
            log_message('error', 'Error getting vehicle history with user names: ' . $e->getMessage());
            
            // Fallback without user join
        $vehicleHistory = $this->db->table('vehicle_locations')
            ->select('id, spot_number, created_at, user_name')
            ->where('vin_number', $location['vin_number'])
            ->where('id !=', $locationId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
                
            // Ensure user_full_name is set for fallback data
            foreach ($vehicleHistory as &$history) {
                $history['user_full_name'] = $history['user_name'] ?? 'Anonymous';
            }
        }

        $data = [
            'location' => $location,
            'device_info' => $deviceInfo,
            'user_details' => $userDetails,
            'nearby_locations' => $nearbyLocations,
            'vehicle_history' => $vehicleHistory,
            'page_title' => 'Location Details - ' . $location['spot_number']
        ];

        return view('location/location_details', $data);
    }

    /**
     * API endpoint to get location details as JSON
     */
    public function getLocationDetails($locationId = null)
    {
        if (!$locationId) {
            return $this->fail('Location ID is required', 400);
        }

        // Use raw query to handle collation differences
        $sql = "
            SELECT vl.*, ps.zone, ps.description as spot_description, 
                   ro.vehicle, ro.order_number, ro.status, ro.priority, ro.stock
            FROM vehicle_locations vl
            LEFT JOIN parking_spots ps ON ps.id = vl.spot_id
            LEFT JOIN recon_orders ro ON ro.vin_number COLLATE utf8mb4_general_ci = vl.vin_number COLLATE utf8mb4_general_ci
            WHERE vl.id = ?
        ";
        
        $location = $this->db->query($sql, [$locationId])->getRowArray();

        if (!$location) {
            return $this->fail('Location record not found', 404);
        }

        // Parse device info
        $deviceInfo = [];
        if (!empty($location['device_info'])) {
            $deviceInfo = json_decode($location['device_info'], true) ?? [];
        }
        $location['parsed_device_info'] = $deviceInfo;

        return $this->respond([
            'success' => true,
            'location' => $location
        ]);
    }

    /**
     * Validate NFC token
     */
    private function validateToken($token)
    {
        return $this->db->table('vehicle_location_tokens')
            ->select('vehicle_id, vin_number, token')
            ->where('token', $token)
            ->where('is_active', 1)
            ->get()
            ->getRowArray();
    }

    /**
     * Get recent locations for a vehicle
     */
    private function getRecentLocations($vinNumber, $limit = 5)
    {
        return $this->db->table('vehicle_locations')
            ->select('spot_number, user_name, created_at')
            ->where('vin_number', $vinNumber)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get available parking spots
     */
    private function getParkingSpots()
    {
        return $this->db->table('parking_spots')
            ->select('id, spot_number, zone, description')
            ->where('is_active', 1)
            ->orderBy('spot_number', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ensure parking spot exists
     */
    private function ensureParkingSpot($spotNumber)
    {
        $spot = $this->db->table('parking_spots')
            ->where('spot_number', $spotNumber)
            ->get()
            ->getRowArray();

        if (!$spot) {
            $spotData = [
                'spot_number' => $spotNumber,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->table('parking_spots')->insert($spotData);
            return $this->db->insertID();
        }

        return $spot['id'];
    }
    
    /**
     * Get user's preferred language
     */
    private function getUserLanguage($user)
    {
        // Priority order for language detection:
        
        // 1. Session language (user explicitly set in current session)
        if (session('locale')) {
            $locale = session('locale');
            if (in_array($locale, ['en', 'es', 'pt'])) {
                return $locale;
            }
        }
        
        // 2. Browser language preference (Accept-Language header)
        $acceptLanguage = $this->request->getHeaderLine('Accept-Language');
        if ($acceptLanguage) {
            // Parse Accept-Language header to get preferred languages
            $languages = explode(',', $acceptLanguage);
            foreach ($languages as $lang) {
                $langCode = strtolower(trim(explode(';', $lang)[0]));
                
                // Check for exact matches first
                if (in_array($langCode, ['en', 'es', 'pt'])) {
                    return $langCode;
                }
                
                // Check for language-country codes and extract language part
                if (strpos($langCode, '-') !== false) {
                    $langPart = explode('-', $langCode)[0];
                    if (in_array($langPart, ['en', 'es', 'pt'])) {
                        return $langPart;
                    }
                }
                
                // Map common language variations
                $langMappings = [
                    'pt-br' => 'pt',
                    'pt-pt' => 'pt',
                    'es-es' => 'es',
                    'es-mx' => 'es',
                    'es-ar' => 'es',
                    'en-us' => 'en',
                    'en-gb' => 'en'
                ];
                
                if (isset($langMappings[$langCode])) {
                    return $langMappings[$langCode];
                }
            }
        }
        
        // 3. Default to English if no preference found
        return 'en';
    }
    
    /**
     * Generate email template based on language
     */
    private function generateEmailTemplate($language, $userName, $data)
    {
        $templates = $this->getEmailTemplates();
        $template = $templates[$language] ?? $templates['en']; // Fallback to English
        
        // Replace placeholders
        $template = str_replace('{userName}', $userName, $template);
        $template = str_replace('{totalVehicles}', $data['totalVehicles'], $template);
        $template = str_replace('{successfulScans}', $data['successfulScans'], $template);
        $template = str_replace('{errorCount}', $data['errorCount'], $template);
        $template = str_replace('{sessionDate}', $data['sessionDate'], $template);
        $template = str_replace('{sessionTime}', $data['sessionTime'], $template);
        
        return $template;
    }
    
    /**
     * Get all email templates in different languages
     */
    private function getEmailTemplates()
    {
        return [
            'en' => $this->getEnglishTemplate(),
            'es' => $this->getSpanishTemplate(),
            'pt' => $this->getPortugueseTemplate()
        ];
    }
    
    /**
     * English email template
     */
    private function getEnglishTemplate()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                .header { background-color: #ffffff; padding: 32px 24px 16px; text-align: center; border-bottom: 2px solid #e9ecef; }
                .header h1 { margin: 0; font-size: 24px; color: #2c3e50; font-weight: 600; }
                .header p { margin: 8px 0 0; color: #6c757d; font-size: 14px; }
                .content { padding: 24px; }
                .greeting { font-size: 16px; margin-bottom: 20px; }
                .summary-box { background-color: #f8f9fa; border-radius: 8px; padding: 24px; margin: 20px 0; border-left: 4px solid #405189; }
                .summary-title { font-size: 18px; font-weight: 600; margin-bottom: 16px; color: #2c3e50; }
                .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px; }
                .stat-item { text-align: center; padding: 12px; background-color: #ffffff; border-radius: 6px; border: 1px solid #e9ecef; }
                .stat-number { font-size: 24px; font-weight: bold; color: #405189; margin-bottom: 4px; }
                .stat-label { font-size: 11px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }
                .details-section { margin: 24px 0; }
                .details-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f3f4; }
                .details-label { font-weight: 500; color: #495057; }
                .details-value { color: #6c757d; }
                .info-list { background-color: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; }
                .info-list h4 { margin: 0 0 12px; color: #2c3e50; font-size: 16px; }
                .info-list ul { margin: 0; padding-left: 20px; }
                .info-list li { margin-bottom: 6px; color: #495057; }
                .footer { background-color: #f8f9fa; padding: 20px 24px; text-align: center; border-top: 1px solid #e9ecef; }
                .footer p { margin: 4px 0; font-size: 12px; color: #6c757d; }
                @media (max-width: 600px) {
                    .stats-grid { grid-template-columns: 1fr; }
                    .details-row { flex-direction: column; gap: 4px; }
                    .container { margin: 0; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🚗 Vehicle Scan Report</h1>
                    <p>Batch NFC Tracking Session</p>
                </div>
                
                <div class="content">
                    <div class="greeting">
                        Hello <strong>{userName}</strong>,
                    </div>
                    
                    <p>Your vehicle scan session has been completed. Please find the detailed report attached as a CSV file.</p>
                    
                    <div class="summary-box">
                        <div class="summary-title">📊 Session Summary</div>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">{totalVehicles}</div>
                                <div class="stat-label">Total Scanned</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{successfulScans}</div>
                                <div class="stat-label">Successful</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{errorCount}</div>
                                <div class="stat-label">Errors</div>
                            </div>
                        </div>
                        
                        <div class="details-section">
                            <div class="details-row">
                                <span class="details-label">📅 Session Date:</span>
                                <span class="details-value">{sessionDate}</span>
                            </div>
                            <div class="details-row">
                                <span class="details-label">⏰ Session Time:</span>
                                <span class="details-value">{sessionTime}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-list">
                        <h4>📋 CSV File Contains:</h4>
                        <ul>
                            <li>Vehicle identification and VIN numbers</li>
                            <li>Parking spot assignments</li>
                            <li>GPS coordinates and addresses</li>
                            <li>Scan timestamps and notes</li>
                        </ul>
                    </div>
                    
                    <p>You can open this file in Excel, Google Sheets, or any spreadsheet application for further analysis.</p>
                </div>
                
                <div class="footer">
                    <p>📱 Generated by MDA Vehicle Tracker System</p>
                    <p>This is an automated email. Please do not reply.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Spanish email template
     */
    private function getSpanishTemplate()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                .header { background-color: #ffffff; padding: 32px 24px 16px; text-align: center; border-bottom: 2px solid #e9ecef; }
                .header h1 { margin: 0; font-size: 24px; color: #2c3e50; font-weight: 600; }
                .header p { margin: 8px 0 0; color: #6c757d; font-size: 14px; }
                .content { padding: 24px; }
                .greeting { font-size: 16px; margin-bottom: 20px; }
                .summary-box { background-color: #f8f9fa; border-radius: 8px; padding: 24px; margin: 20px 0; border-left: 4px solid #405189; }
                .summary-title { font-size: 18px; font-weight: 600; margin-bottom: 16px; color: #2c3e50; }
                .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px; }
                .stat-item { text-align: center; padding: 12px; background-color: #ffffff; border-radius: 6px; border: 1px solid #e9ecef; }
                .stat-number { font-size: 24px; font-weight: bold; color: #405189; margin-bottom: 4px; }
                .stat-label { font-size: 11px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }
                .details-section { margin: 24px 0; }
                .details-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f3f4; }
                .details-label { font-weight: 500; color: #495057; }
                .details-value { color: #6c757d; }
                .info-list { background-color: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; }
                .info-list h4 { margin: 0 0 12px; color: #2c3e50; font-size: 16px; }
                .info-list ul { margin: 0; padding-left: 20px; }
                .info-list li { margin-bottom: 6px; color: #495057; }
                .footer { background-color: #f8f9fa; padding: 20px 24px; text-align: center; border-top: 1px solid #e9ecef; }
                .footer p { margin: 4px 0; font-size: 12px; color: #6c757d; }
                @media (max-width: 600px) {
                    .stats-grid { grid-template-columns: 1fr; }
                    .details-row { flex-direction: column; gap: 4px; }
                    .container { margin: 0; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🚗 Reporte de Escaneo de Vehículos</h1>
                    <p>Sesión de Rastreo NFC por Lotes</p>
                </div>
                
                <div class="content">
                    <div class="greeting">
                        Hola <strong>{userName}</strong>,
                    </div>
                    
                    <p>Su sesión de escaneo de vehículos ha sido completada. Por favor encuentre el reporte detallado adjunto como archivo CSV.</p>
                    
                    <div class="summary-box">
                        <div class="summary-title">📊 Resumen de Sesión</div>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">{totalVehicles}</div>
                                <div class="stat-label">Total Escaneados</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{successfulScans}</div>
                                <div class="stat-label">Exitosos</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{errorCount}</div>
                                <div class="stat-label">Errores</div>
                            </div>
                        </div>
                        
                        <div class="details-section">
                            <div class="details-row">
                                <span class="details-label">📅 Fecha de Sesión:</span>
                                <span class="details-value">{sessionDate}</span>
                            </div>
                            <div class="details-row">
                                <span class="details-label">⏰ Hora de Sesión:</span>
                                <span class="details-value">{sessionTime}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-list">
                        <h4>📋 El Archivo CSV Contiene:</h4>
                        <ul>
                            <li>Identificación de vehículos y números VIN</li>
                            <li>Asignaciones de espacios de estacionamiento</li>
                            <li>Coordenadas GPS y direcciones</li>
                            <li>Marcas de tiempo de escaneo y notas</li>
                        </ul>
                    </div>
                    
                    <p>Puede abrir este archivo en Excel, Google Sheets, o cualquier aplicación de hojas de cálculo para análisis adicional.</p>
                </div>
                
                <div class="footer">
                    <p>📱 Generado por el Sistema de Rastreo de Vehículos MDA</p>
                    <p>Este es un email automatizado. Por favor no responda.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Portuguese email template
     */
    private function getPortugueseTemplate()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                .header { background-color: #ffffff; padding: 32px 24px 16px; text-align: center; border-bottom: 2px solid #e9ecef; }
                .header h1 { margin: 0; font-size: 24px; color: #2c3e50; font-weight: 600; }
                .header p { margin: 8px 0 0; color: #6c757d; font-size: 14px; }
                .content { padding: 24px; }
                .greeting { font-size: 16px; margin-bottom: 20px; }
                .summary-box { background-color: #f8f9fa; border-radius: 8px; padding: 24px; margin: 20px 0; border-left: 4px solid #405189; }
                .summary-title { font-size: 18px; font-weight: 600; margin-bottom: 16px; color: #2c3e50; }
                .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px; }
                .stat-item { text-align: center; padding: 12px; background-color: #ffffff; border-radius: 6px; border: 1px solid #e9ecef; }
                .stat-number { font-size: 24px; font-weight: bold; color: #405189; margin-bottom: 4px; }
                .stat-label { font-size: 11px; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }
                .details-section { margin: 24px 0; }
                .details-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f3f4; }
                .details-label { font-weight: 500; color: #495057; }
                .details-value { color: #6c757d; }
                .info-list { background-color: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; }
                .info-list h4 { margin: 0 0 12px; color: #2c3e50; font-size: 16px; }
                .info-list ul { margin: 0; padding-left: 20px; }
                .info-list li { margin-bottom: 6px; color: #495057; }
                .footer { background-color: #f8f9fa; padding: 20px 24px; text-align: center; border-top: 1px solid #e9ecef; }
                .footer p { margin: 4px 0; font-size: 12px; color: #6c757d; }
                @media (max-width: 600px) {
                    .stats-grid { grid-template-columns: 1fr; }
                    .details-row { flex-direction: column; gap: 4px; }
                    .container { margin: 0; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🚗 Relatório de Scanner de Veículos</h1>
                    <p>Sessão de Rastreamento NFC em Lote</p>
                </div>
                
                <div class="content">
                    <div class="greeting">
                        Olá <strong>{userName}</strong>,
                    </div>
                    
                    <p>Sua sessão de scanner de veículos foi concluída. Por favor, encontre o relatório detalhado em anexo como arquivo CSV.</p>
                    
                    <div class="summary-box">
                        <div class="summary-title">📊 Resumo da Sessão</div>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">{totalVehicles}</div>
                                <div class="stat-label">Total Escaneados</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{successfulScans}</div>
                                <div class="stat-label">Bem-sucedidos</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{errorCount}</div>
                                <div class="stat-label">Erros</div>
                            </div>
                        </div>
                        
                        <div class="details-section">
                            <div class="details-row">
                                <span class="details-label">📅 Data da Sessão:</span>
                                <span class="details-value">{sessionDate}</span>
                            </div>
                            <div class="details-row">
                                <span class="details-label">⏰ Hora da Sessão:</span>
                                <span class="details-value">{sessionTime}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-list">
                        <h4>📋 O Arquivo CSV Contém:</h4>
                        <ul>
                            <li>Identificação de veículos e números VIN</li>
                            <li>Atribuições de vagas de estacionamento</li>
                            <li>Coordenadas GPS e endereços</li>
                            <li>Timestamps de scanner e notas</li>
                        </ul>
                    </div>
                    
                    <p>Você pode abrir este arquivo no Excel, Google Sheets, ou qualquer aplicação de planilha para análise adicional.</p>
                </div>
                
                <div class="footer">
                    <p>📱 Gerado pelo Sistema de Rastreamento de Veículos MDA</p>
                    <p>Este é um email automatizado. Por favor, não responda.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Generate test email template based on language
     */
    private function generateTestEmailTemplate($language, $userName, $fileName, $csvSize)
    {
        $testTemplates = $this->getTestEmailTemplates();
        $template = $testTemplates[$language] ?? $testTemplates['en']; // Fallback to English
        
        // Replace placeholders
        $template = str_replace('{userName}', $userName, $template);
        $template = str_replace('{fileName}', $fileName, $template);
        $template = str_replace('{csvSize}', $csvSize, $template);
        $template = str_replace('{timestamp}', date('Y-m-d H:i:s'), $template);
        
        return $template;
    }
    
    /**
     * Get test email templates in different languages
     */
    private function getTestEmailTemplates()
    {
        return [
            'en' => $this->getEnglishTestTemplate(),
            'es' => $this->getSpanishTestTemplate(),
            'pt' => $this->getPortugueseTestTemplate()
        ];
    }
    
    /**
     * English test email template
     */
    private function getEnglishTestTemplate()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                .header { background-color: #ffffff; padding: 24px; text-align: center; border-bottom: 2px solid #e9ecef; }
                .header h1 { margin: 0; font-size: 20px; color: #2c3e50; font-weight: 600; }
                .content { padding: 24px; }
                .test-box { background-color: #e1f5fe; border-radius: 8px; padding: 20px; margin: 16px 0; border-left: 4px solid #0288d1; }
                .details-list { background-color: #f8f9fa; padding: 16px; border-radius: 6px; margin: 16px 0; }
                .details-list h3 { margin: 0 0 12px; color: #2c3e50; font-size: 16px; }
                .details-list ul { margin: 0; padding-left: 20px; }
                .details-list li { margin-bottom: 6px; color: #495057; }
                .footer { background-color: #f8f9fa; padding: 16px 24px; text-align: center; border-top: 1px solid #e9ecef; }
                .footer p { margin: 4px 0; font-size: 11px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🧪 CSV Email Attachment Test</h1>
                </div>
                
                <div class="content">
                    <p>Hello <strong>{userName}</strong>,</p>
                    
                    <div class="test-box">
                        <p><strong>✅ Testing CSV email attachments functionality</strong></p>
                        <p>This email verifies that CSV attachments are working correctly in the Vehicle Tracker system.</p>
                    </div>
                    
                    <div class="details-list">
                        <h3>📊 CSV File Details:</h3>
                        <ul>
                            <li><strong>File:</strong> {fileName}</li>
                            <li><strong>Size:</strong> {csvSize} characters</li>
                            <li><strong>Rows:</strong> 4 (1 header + 3 data rows)</li>
                        </ul>
                    </div>
                    
                    <p>If you can open the attached CSV file and see vehicle data, the email system is working correctly!</p>
                </div>
                
                <div class="footer">
                    <p>🧪 Test sent at: {timestamp}</p>
                    <p>Generated by MDA Vehicle Tracker System</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Spanish test email template
     */
    private function getSpanishTestTemplate()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                .header { background-color: #ffffff; padding: 24px; text-align: center; border-bottom: 2px solid #e9ecef; }
                .header h1 { margin: 0; font-size: 20px; color: #2c3e50; font-weight: 600; }
                .content { padding: 24px; }
                .test-box { background-color: #e1f5fe; border-radius: 8px; padding: 20px; margin: 16px 0; border-left: 4px solid #0288d1; }
                .details-list { background-color: #f8f9fa; padding: 16px; border-radius: 6px; margin: 16px 0; }
                .details-list h3 { margin: 0 0 12px; color: #2c3e50; font-size: 16px; }
                .details-list ul { margin: 0; padding-left: 20px; }
                .details-list li { margin-bottom: 6px; color: #495057; }
                .footer { background-color: #f8f9fa; padding: 16px 24px; text-align: center; border-top: 1px solid #e9ecef; }
                .footer p { margin: 4px 0; font-size: 11px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🧪 Prueba de Adjunto CSV por Email</h1>
                </div>
                
                <div class="content">
                    <p>Hola <strong>{userName}</strong>,</p>
                    
                    <div class="test-box">
                        <p><strong>✅ Probando funcionalidad de adjuntos CSV por email</strong></p>
                        <p>Este email verifica que los adjuntos CSV estén funcionando correctamente en el sistema de Rastreo de Vehículos.</p>
                    </div>
                    
                    <div class="details-list">
                        <h3>📊 Detalles del Archivo CSV:</h3>
                        <ul>
                            <li><strong>Archivo:</strong> {fileName}</li>
                            <li><strong>Tamaño:</strong> {csvSize} caracteres</li>
                            <li><strong>Filas:</strong> 4 (1 encabezado + 3 filas de datos)</li>
                        </ul>
                    </div>
                    
                    <p>¡Si puede abrir el archivo CSV adjunto y ver los datos de vehículos, el sistema de email está funcionando correctamente!</p>
                </div>
                
                <div class="footer">
                    <p>🧪 Prueba enviada el: {timestamp}</p>
                    <p>Generado por el Sistema de Rastreo de Vehículos MDA</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Portuguese test email template
     */
    private function getPortugueseTestTemplate()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                .header { background-color: #ffffff; padding: 24px; text-align: center; border-bottom: 2px solid #e9ecef; }
                .header h1 { margin: 0; font-size: 20px; color: #2c3e50; font-weight: 600; }
                .content { padding: 24px; }
                .test-box { background-color: #e1f5fe; border-radius: 8px; padding: 20px; margin: 16px 0; border-left: 4px solid #0288d1; }
                .details-list { background-color: #f8f9fa; padding: 16px; border-radius: 6px; margin: 16px 0; }
                .details-list h3 { margin: 0 0 12px; color: #2c3e50; font-size: 16px; }
                .details-list ul { margin: 0; padding-left: 20px; }
                .details-list li { margin-bottom: 6px; color: #495057; }
                .footer { background-color: #f8f9fa; padding: 16px 24px; text-align: center; border-top: 1px solid #e9ecef; }
                .footer p { margin: 4px 0; font-size: 11px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🧪 Teste de Anexo CSV por Email</h1>
                </div>
                
                <div class="content">
                    <p>Olá <strong>{userName}</strong>,</p>
                    
                    <div class="test-box">
                        <p><strong>✅ Testando funcionalidade de anexos CSV por email</strong></p>
                        <p>Este email verifica se os anexos CSV estão funcionando corretamente no sistema de Rastreamento de Veículos.</p>
                    </div>
                    
                    <div class="details-list">
                        <h3>📊 Detalhes do Arquivo CSV:</h3>
                        <ul>
                            <li><strong>Arquivo:</strong> {fileName}</li>
                            <li><strong>Tamanho:</strong> {csvSize} caracteres</li>
                            <li><strong>Linhas:</strong> 4 (1 cabeçalho + 3 linhas de dados)</li>
                        </ul>
                    </div>
                    
                    <p>Se você conseguir abrir o arquivo CSV anexado e ver os dados dos veículos, o sistema de email está funcionando corretamente!</p>
                </div>
                
                <div class="footer">
                    <p>🧪 Teste enviado em: {timestamp}</p>
                    <p>Gerado pelo Sistema de Rastreamento de Veículos MDA</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * API endpoint to get vehicle history with pagination
     */
    public function getVehicleHistory($vinNumber = null)
    {
        if (!$vinNumber) {
            return $this->fail('VIN number is required', 400);
        }
        
        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = (int) ($this->request->getGet('limit') ?? 5);
        $offset = ($page - 1) * $limit;
        $excludeId = $this->request->getGet('exclude_id');
        
        try {
            // Get vehicle history with user full names
            $sql = "
                SELECT vl.id, vl.spot_number, vl.created_at, vl.user_name,
                       u.first_name, u.last_name, u.username,
                       CASE 
                           WHEN u.first_name IS NOT NULL OR u.last_name IS NOT NULL THEN
                               CONCAT(COALESCE(u.first_name, ''), 
                                      CASE WHEN u.first_name IS NOT NULL AND u.last_name IS NOT NULL THEN ' ' ELSE '' END,
                                      COALESCE(u.last_name, ''))
                           ELSE COALESCE(u.username, vl.user_name)
                       END as user_full_name
                FROM vehicle_locations vl
                LEFT JOIN users u ON u.username = vl.user_name
                WHERE vl.vin_number = ?
            ";
            
            $params = [strtoupper($vinNumber)];
            
            if ($excludeId) {
                $sql .= " AND vl.id != ?";
                $params[] = $excludeId;
            }
            
            $sql .= " ORDER BY vl.created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            
            $vehicleHistory = $this->db->query($sql, $params)->getResultArray();
            
            // Get total count for pagination info
            $countSql = "SELECT COUNT(*) as total FROM vehicle_locations vl WHERE vl.vin_number = ?";
            $countParams = [strtoupper($vinNumber)];
            
            if ($excludeId) {
                $countSql .= " AND vl.id != ?";
                $countParams[] = $excludeId;
            }
            
            $totalCount = $this->db->query($countSql, $countParams)->getRow()->total;
            
        } catch (Exception $e) {
            log_message('error', 'Error getting vehicle history: ' . $e->getMessage());
            
            // Fallback without user join
            $builder = $this->db->table('vehicle_locations')
                ->select('id, spot_number, created_at, user_name')
                ->where('vin_number', strtoupper($vinNumber));
                
            if ($excludeId) {
                $builder->where('id !=', $excludeId);
            }
            
            $vehicleHistory = $builder
                ->orderBy('created_at', 'DESC')
                ->limit($limit, $offset)
                ->get()
                ->getResultArray();
                
            // Ensure user_full_name is set for fallback data
            foreach ($vehicleHistory as &$history) {
                $history['user_full_name'] = $history['user_name'] ?? 'Anonymous';
            }
            
            // Get total count for fallback
            $countBuilder = $this->db->table('vehicle_locations')
                ->where('vin_number', strtoupper($vinNumber));
                
            if ($excludeId) {
                $countBuilder->where('id !=', $excludeId);
            }
            
            $totalCount = $countBuilder->countAllResults();
        }
        
        return $this->respond([
            'success' => true,
            'data' => $vehicleHistory,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => (int) $totalCount,
                'has_more' => ($offset + count($vehicleHistory)) < $totalCount
            ]
        ]);
    }
} 