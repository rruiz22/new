<?php

namespace App\Services;

use Exception;

class VehicleMDAService
{
    protected $db;
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    
    /**
     * Generate MDA.to shortlink and QR for vehicle using last 6 VIN digits
     */
    public function generateVehicleShortlink($vinNumber, $vehicleId = null)
    {
        try {
            $vinNumber = strtoupper(trim($vinNumber));
            $vinLast6 = substr($vinNumber, -6);
            
            // Get API configuration from environment
            $apiKey = env('MDA_API_KEY');
            $brandedDomain = env('MDA_BRANDED_DOMAIN') ?: 'mda.to';
            $apiBaseUrl = env('MDA_API_BASE_URL') ?: 'https://mda.to';
            
            $isValidApiKey = $apiKey && $apiKey !== 'your_mda_api_key_here' && strlen($apiKey) >= 5;
            
            if (!$isValidApiKey) {
                log_message('warning', "No valid MDA API key configured for vehicle VIN: {$vinNumber}");
                return $this->generateFallbackResponse($vinNumber, $vinLast6);
            }
            
            // Check if we already have a shortlink for this VIN (only if table exists)
            $existing = null;
            if ($this->db->tableExists('vehicle_shortlinks')) {
                $existing = $this->db->table('vehicle_shortlinks')
                    ->where('vin_number', $vinNumber)
                    ->where('is_active', 1)
                    ->get()
                    ->getRowArray();
                    
                if ($existing && !empty($existing['short_url'])) {
                    log_message('info', "Using existing shortlink for VIN {$vinNumber}: {$existing['short_url']}");
                    
                    // Generate QR for existing shortlink
                    $qrData = $this->generateQRCode($existing['short_url'], $apiKey, $apiBaseUrl);
                    
                    return [
                        'success' => true,
                        'short_url' => $existing['short_url'],
                        'qr_url' => $qrData['qr_url'],
                        'qr_image' => $qrData['qr_image'] ?? null,
                        'custom_slug' => $vinLast6,
                        'is_existing' => true
                    ];
                }
            }
            
            // Generate vehicle URL
            $vehicleUrl = base_url("vehicles/v/{$vinLast6}");
            
            // Try to create shortlink with custom slug (VIN last 6)
            $shortUrlData = $this->createShortUrl($apiKey, $vehicleUrl, $vinLast6, $brandedDomain, $apiBaseUrl);
            
            if ($shortUrlData && isset($shortUrlData['shorturl'])) {
                $shortUrl = $shortUrlData['shorturl'];
                $linkId = $shortUrlData['id'] ?? null;
                
                // Generate QR code
                $qrData = $this->generateQRCode($shortUrl, $apiKey, $apiBaseUrl);
                
                // Save the shortlink data (only if table exists)
                if ($this->db->tableExists('vehicle_shortlinks')) {
                    $linkData = [
                        'vin_number' => $vinNumber,
                        'vehicle_id' => $vehicleId,
                        'short_url' => $shortUrl,
                        'short_url_slug' => $vinLast6,
                        'mda_link_id' => $linkId,
                        'target_url' => $vehicleUrl,
                        'qr_url' => $qrData['qr_url'],
                        'qr_image' => $qrData['qr_image'] ?? null,
                        'is_active' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Insert or update
                    if ($existing) {
                        $this->db->table('vehicle_shortlinks')
                            ->where('id', $existing['id'])
                            ->update($linkData);
                    } else {
                        $this->db->table('vehicle_shortlinks')->insert($linkData);
                    }
                }
                
                log_message('info', "MDA shortlink created for VIN {$vinNumber}: {$shortUrl} (Slug: {$vinLast6})");
                
                return [
                    'success' => true,
                    'short_url' => $shortUrl,
                    'qr_url' => $qrData['qr_url'],
                    'qr_image' => $qrData['qr_image'] ?? null,
                    'custom_slug' => $vinLast6,
                    'is_existing' => false
                ];
            }
            
            log_message('warning', "Failed to create MDA shortlink for VIN {$vinNumber}");
            return $this->generateFallbackResponse($vinNumber, $vinLast6);
            
        } catch (Exception $e) {
            log_message('error', "Error generating vehicle shortlink: " . $e->getMessage());
            return $this->generateFallbackResponse($vinNumber, $vinLast6);
        }
    }
    
    /**
     * Create short URL using MDA Links API
     */
    private function createShortUrl($apiKey, $url, $customSlug, $brandedDomain, $apiBaseUrl)
    {
        try {
            $payload = [
                'url' => $url,
                'custom' => $customSlug,
                'domain' => $brandedDomain,
                'expiry' => null,
                'description' => "Vehicle Profile - VIN: {$customSlug}"
            ];
            
            log_message('info', "Creating MDA shortlink with payload: " . json_encode($payload));
            
            $result = $this->callMDALinksAPI($apiKey, $payload, $apiBaseUrl . '/api/url/add');
            
            if ($result['success']) {
                return $result['data'];
            }
            
            // If custom slug failed, try without it
            unset($payload['custom']);
            $payload['description'] = "Vehicle Profile (Auto-generated)";
            
            log_message('info', "Retrying MDA shortlink without custom slug");
            $result = $this->callMDALinksAPI($apiKey, $payload, $apiBaseUrl . '/api/url/add');
            
            return $result['success'] ? $result['data'] : null;
            
        } catch (Exception $e) {
            log_message('error', "Error creating MDA shortlink: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Generate QR Code using MDA.to API
     */
    private function generateQRCode($shortUrl, $apiKey, $apiBaseUrl)
    {
        try {
            // Try MDA.to QR API first
            $payload = [
                'type' => 'link',
                'data' => $shortUrl,
                'name' => 'Vehicle QR Code',
                'size' => 300,
                'format' => 'png'
            ];

            $result = $this->callMDALinksAPI($apiKey, $payload, $apiBaseUrl . '/api/qr/add');
            
            if ($result['success'] && isset($result['data']['link'])) {
                return [
                    'qr_url' => $result['data']['link'],
                    'qr_image' => $result['data']['image'] ?? null
                ];
            }
            
            // Fallback to external QR service
            $fallbackUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($shortUrl);
            log_message('info', "Using fallback QR service: {$fallbackUrl}");
            
            return [
                'qr_url' => $fallbackUrl,
                'qr_image' => null
            ];
            
        } catch (Exception $e) {
            log_message('error', "Error generating QR code: " . $e->getMessage());
            
            // Emergency fallback
            return [
                'qr_url' => "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($shortUrl),
                'qr_image' => null
            ];
        }
    }
    
    /**
     * Call MDA Links API
     */
    private function callMDALinksAPI($apiKey, $payload, $endpoint)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            return [
                'success' => false,
                'error' => 'CURL Error: ' . $curlError
            ];
        }
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => "HTTP Error: {$httpCode}",
                'response' => $response
            ];
        }
        
        $data = json_decode($response, true);
        
        if (!$data) {
            return [
                'success' => false,
                'error' => 'Invalid JSON response'
            ];
        }
        
        if (isset($data['error']) && $data['error'] !== 0) {
            return [
                'success' => false,
                'error' => $data['message'] ?? 'Unknown error'
            ];
        }
        
        return [
            'success' => true,
            'data' => $data
        ];
    }
    
    /**
     * Generate fallback response when MDA API is not available
     */
    private function generateFallbackResponse($vinNumber, $vinLast6)
    {
        $vehicleUrl = base_url("vehicles/v/{$vinLast6}");
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($vehicleUrl);
        
        return [
            'success' => true,
            'short_url' => $vehicleUrl,
            'qr_url' => $qrUrl,
            'qr_image' => null,
            'custom_slug' => $vinLast6,
            'is_fallback' => true
        ];
    }
    
    /**
     * Get shortlink data for a VIN
     */
    public function getVehicleShortlink($vinNumber)
    {
        $vinNumber = strtoupper(trim($vinNumber));
        
        // Only proceed if table exists
        if (!$this->db->tableExists('vehicle_shortlinks')) {
            return [
                'success' => false,
                'message' => 'Vehicle shortlinks table not available'
            ];
        }
        
        $shortlink = $this->db->table('vehicle_shortlinks')
            ->where('vin_number', $vinNumber)
            ->where('is_active', 1)
            ->get()
            ->getRowArray();
            
        if ($shortlink) {
            return [
                'success' => true,
                'short_url' => $shortlink['short_url'],
                'qr_url' => $shortlink['qr_url'],
                'qr_image' => $shortlink['qr_image'],
                'custom_slug' => $shortlink['short_url_slug'],
                'created_at' => $shortlink['created_at']
            ];
        }
        
        return [
            'success' => false,
            'message' => 'No shortlink found for this VIN'
        ];
    }
}
