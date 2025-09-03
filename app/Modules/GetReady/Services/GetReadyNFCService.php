<?php

namespace Modules\GetReady\Services;

use CodeIgniter\Config\BaseService;

class GetReadyNFCService extends BaseService
{
    protected $orderModel;
    protected $stepModel;
    protected $timeModel;
    protected $activityModel;
    protected $vehicleLocationController;

    public function __construct()
    {
        $this->orderModel = model('Modules\GetReady\Models\GetReadyOrderModel');
        $this->stepModel = model('Modules\GetReady\Models\GetReadyStepModel');
        $this->timeModel = model('Modules\GetReady\Models\GetReadyTimeModel');
        $this->activityModel = model('Modules\GetReady\Models\GetReadyActivityModel');
        
        // Use existing vehicle location controller for NFC functionality
        $this->vehicleLocationController = new \App\Controllers\VehicleLocationController();
    }

    /**
     * Process NFC scan and update vehicle location/status
     */
    public function processScan($nfcToken, $locationData = null, $userId = null)
    {
        $order = $this->orderModel->findByNFCToken($nfcToken);
        if (!$order) {
            return [
                'success' => false,
                'message' => 'Invalid NFC token',
                'code' => 'INVALID_TOKEN'
            ];
        }

        $scanData = [
            'order_id' => $order['id'],
            'nfc_token' => $nfcToken,
            'scanned_by' => $userId ?? null,
            'scan_timestamp' => date('Y-m-d H:i:s'),
            'location_data' => $locationData,
            'ip_address' => $this->getClientIP(),
            'user_agent' => service('request')->getUserAgent()->getAgentString()
        ];

        // Update vehicle location if provided
        if ($locationData) {
            $this->updateVehicleLocation($order['id'], $locationData, $userId);
        }

        // Log NFC scan activity
        $this->activityModel->logActivity(
            $order['id'],
            'nfc_scanned',
            'Vehicle location updated via NFC scan',
            null,
            null,
            [
                'nfc_token' => $nfcToken,
                'location' => $locationData['location_name'] ?? 'Unknown',
                'scan_type' => 'nfc',
                'scanner_user_id' => $userId
            ],
            $userId
        );

        // Trigger NFC scan event
        \CodeIgniter\Events\Events::trigger('get_ready_nfc_scanned', [
            'order_id' => $order['id'],
            'nfc_token' => $nfcToken,
            'location' => $locationData['location_name'] ?? null,
            'user_id' => $userId
        ]);

        return [
            'success' => true,
            'message' => 'NFC scan processed successfully',
            'data' => [
                'order' => $order,
                'scan_data' => $scanData,
                'redirect_url' => base_url("get-ready/view/{$order['id']}")
            ]
        ];
    }

    /**
     * Generate NFC data for new vehicle
     */
    public function generateNFCData($orderId, $includeQR = true)
    {
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return null;
        }

        // Generate or get existing NFC token
        $nfcToken = $order['nfc_token'] ?: $this->orderModel->generateNFCToken($orderId);
        
        // Create NFC URL
        $nfcUrl = base_url("nfc/get-ready/{$nfcToken}");
        
        // Generate short URL if needed
        $shortUrl = $order['short_url'];
        if (empty($shortUrl)) {
            $shortUrl = $this->generateShortUrl($orderId);
        }

        $nfcData = [
            'token' => $nfcToken,
            'url' => $nfcUrl,
            'short_url' => $shortUrl,
            'vin' => $order['vin_number'],
            'vehicle_info' => trim($order['year'] . ' ' . $order['make'] . ' ' . $order['model']),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Generate QR code if requested
        if ($includeQR) {
            $nfcData['qr_code'] = $this->generateQRCode($nfcUrl);
            $nfcData['qr_url'] = $this->getQRCodeURL($nfcUrl);
        }

        return $nfcData;
    }

    /**
     * Handle mobile NFC interface
     */
    public function getMobileInterface($nfcToken)
    {
        $order = $this->orderModel->findByNFCToken($nfcToken);
        if (!$order) {
            return null;
        }

        // Get vehicle details
        $vehicleDetails = $this->orderModel->getVehicleDetails($order['id']);
        
        // Get current step info
        $currentStatus = $this->timeModel->getCurrentStepStatus($order['id']);
        
        // Get available steps for quick move
        $availableSteps = $this->stepModel->getActiveSteps();
        
        // Get recent activities
        $recentActivities = $this->activityModel->getOrderActivities($order['id'], 5);

        return [
            'vehicle' => $vehicleDetails,
            'current_status' => $currentStatus,
            'available_steps' => $availableSteps,
            'recent_activities' => $recentActivities,
            'nfc_token' => $nfcToken,
            'scan_url' => base_url("nfc/get-ready/{$nfcToken}"),
            'mobile_optimized' => true
        ];
    }

    /**
     * Quick move vehicle to next step via NFC
     */
    public function quickMoveToStep($nfcToken, $toStepId, $userId = null, $notes = null)
    {
        $order = $this->orderModel->findByNFCToken($nfcToken);
        if (!$order) {
            return [
                'success' => false,
                'message' => 'Invalid NFC token'
            ];
        }

        $toStep = $this->stepModel->find($toStepId);
        if (!$toStep) {
            return [
                'success' => false,
                'message' => 'Invalid step'
            ];
        }

        // Move vehicle to new step
        $moveResult = $this->orderModel->moveToStep($order['id'], $toStepId, $userId);
        
        if (!$moveResult) {
            return [
                'success' => false,
                'message' => 'Failed to move vehicle to new step'
            ];
        }

        // Add notes if provided
        if ($notes) {
            $this->activityModel->logActivity(
                $order['id'],
                'notes_added',
                "Notes added via NFC: {$notes}",
                null,
                null,
                ['notes' => $notes, 'added_via' => 'nfc'],
                $userId
            );
        }

        return [
            'success' => true,
            'message' => "Vehicle moved to {$toStep['name']} successfully",
            'data' => [
                'new_step' => $toStep,
                'vehicle' => $order
            ]
        ];
    }

    /**
     * Update vehicle location via NFC
     */
    protected function updateVehicleLocation($orderId, $locationData, $userId = null)
    {
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return false;
        }

        // Update current location in order
        $this->orderModel->update($orderId, [
            'current_location' => $locationData['location_name'] ?? $locationData['location'] ?? 'NFC Scanned Location',
            'updated_by' => $userId
        ]);

        // If we have coordinate data, save to vehicle_locations table
        if (isset($locationData['latitude']) && isset($locationData['longitude'])) {
            $vehicleLocationModel = model('App\Models\VehicleLocationModel');
            
            $locationRecord = [
                'vin_number' => $order['vin_number'],
                'latitude' => $locationData['latitude'],
                'longitude' => $locationData['longitude'],
                'location_name' => $locationData['location_name'] ?? 'NFC Scan Location',
                'notes' => 'Updated via NFC scan - Get Ready module',
                'created_by' => $userId,
                'scan_type' => 'nfc',
                'metadata' => json_encode([
                    'module' => 'get_ready',
                    'order_id' => $orderId,
                    'nfc_token' => $order['nfc_token'],
                    'accuracy' => $locationData['accuracy'] ?? null
                ])
            ];

            $vehicleLocationModel->insert($locationRecord);
        }

        return true;
    }

    /**
     * Generate short URL for vehicle
     */
    protected function generateShortUrl($orderId)
    {
        // Use MDA.to links service if available
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return null;
        }

        $fullUrl = base_url("get-ready/view/{$orderId}");
        $slug = "gr-{$order['vin_number']}-" . substr(md5($orderId), 0, 6);

        // Check if MDA Links Helper is available
        if (function_exists('createMDALink')) {
            $result = createMDALink($fullUrl, $slug, "Get Ready: {$order['vin_number']}");
            
            if ($result['success']) {
                $this->orderModel->update($orderId, [
                    'short_url' => $result['short_url'],
                    'short_url_slug' => $result['slug'],
                    'lima_link_id' => $result['link_id'] ?? null
                ]);
                
                return $result['short_url'];
            }
        }

        return $fullUrl;
    }

    /**
     * Generate QR code for NFC URL
     */
    protected function generateQRCode($url)
    {
        // Generate QR code data - this can be replaced with a proper QR library
        $qrData = [
            'url' => $url,
            'format' => 'PNG',
            'size' => '200x200',
            'generated_at' => date('Y-m-d H:i:s')
        ];

        return $qrData;
    }

    /**
     * Get QR code image URL
     */
    protected function getQRCodeURL($url)
    {
        $encodedUrl = urlencode($url);
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={$encodedUrl}";
    }

    /**
     * Validate NFC token format
     */
    public function validateNFCToken($token)
    {
        // Basic validation - token should be 32 character hex string
        return preg_match('/^[a-f0-9]{32}$/', $token);
    }

    /**
     * Get NFC scan statistics
     */
    public function getScanStatistics($days = 30)
    {
        $fromDate = date('Y-m-d', strtotime("-{$days} days"));
        
        $totalScans = $this->activityModel->where('action', 'nfc_scanned')
                                        ->where('DATE(created_at) >=', $fromDate)
                                        ->countAllResults();

        $uniqueVehicles = $this->activityModel->select('DISTINCT order_id')
                                            ->where('action', 'nfc_scanned')
                                            ->where('DATE(created_at) >=', $fromDate)
                                            ->countAllResults();

        $dailyScans = $this->activityModel->select('DATE(created_at) as date, COUNT(*) as count')
                                        ->where('action', 'nfc_scanned')
                                        ->where('DATE(created_at) >=', $fromDate)
                                        ->groupBy('DATE(created_at)')
                                        ->orderBy('date', 'ASC')
                                        ->findAll();

        // Get most scanned vehicles
        $topVehicles = $this->activityModel->select([
                'get_ready_orders.vin_number',
                'get_ready_orders.year',
                'get_ready_orders.make',
                'get_ready_orders.model',
                'COUNT(*) as scan_count'
            ])
            ->join('get_ready_orders', 'get_ready_orders.id = get_ready_activities.order_id')
            ->where('get_ready_activities.action', 'nfc_scanned')
            ->where('DATE(get_ready_activities.created_at) >=', $fromDate)
            ->groupBy('get_ready_activities.order_id')
            ->orderBy('scan_count', 'DESC')
            ->limit(5)
            ->findAll();

        return [
            'total_scans' => $totalScans,
            'unique_vehicles' => $uniqueVehicles,
            'average_scans_per_vehicle' => $uniqueVehicles > 0 ? round($totalScans / $uniqueVehicles, 1) : 0,
            'daily_scans' => $dailyScans,
            'top_vehicles' => $topVehicles,
            'period_days' => $days
        ];
    }

    /**
     * Generate batch NFC tokens for multiple vehicles
     */
    public function generateBatchNFCTokens($orderIds)
    {
        $results = [];
        
        foreach ($orderIds as $orderId) {
            $nfcData = $this->generateNFCData($orderId, true);
            if ($nfcData) {
                $results[] = [
                    'order_id' => $orderId,
                    'success' => true,
                    'nfc_data' => $nfcData
                ];
            } else {
                $results[] = [
                    'order_id' => $orderId,
                    'success' => false,
                    'error' => 'Failed to generate NFC data'
                ];
            }
        }

        return $results;
    }

    /**
     * Get client IP address
     */
    protected function getClientIP()
    {
        $request = service('request');
        
        if ($request->hasHeader('CF-Connecting-IP')) {
            return $request->getHeaderLine('CF-Connecting-IP');
        } elseif ($request->hasHeader('X-Forwarded-For')) {
            return $request->getHeaderLine('X-Forwarded-For');
        } elseif ($request->hasHeader('X-Real-IP')) {
            return $request->getHeaderLine('X-Real-IP');
        } else {
            return $request->getIPAddress();
        }
    }

    /**
     * Create PWA-compatible NFC interface
     */
    public function createPWAInterface($nfcToken)
    {
        $interfaceData = $this->getMobileInterface($nfcToken);
        if (!$interfaceData) {
            return null;
        }

        // Add PWA-specific data
        $interfaceData['pwa_enabled'] = true;
        $interfaceData['offline_capable'] = true;
        $interfaceData['install_prompt'] = true;
        $interfaceData['push_notifications'] = true;

        return $interfaceData;
    }
}