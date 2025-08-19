<?php

namespace App\Services;

use Exception;

/**
 * Unified Vehicle Token Service
 * Handles both location tracking tokens and MDA shortlinks
 */
class VehicleTokenService
{
    protected $db;
    protected $vehicleService;
    protected $mdaService;
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->vehicleService = new VehicleService();
        $this->mdaService = new VehicleMDAService();
    }

    /**
     * Generate unified token system for a vehicle
     * Creates both NFC location token and MDA shortlink
     */
    public function generateUnifiedVehicleToken($vinNumber, $vehicleId = null)
    {
        try {
            $vinNumber = strtoupper(trim($vinNumber));
            
            // Get or find vehicle
            if (!$vehicleId) {
                // Try to find in recon_orders
                $vehicle = $this->findVehicleInReconOrders($vinNumber);
                if (!$vehicle) {
                    throw new Exception('Vehicle not found');
                }
                $vehicleId = $vehicle['id'];
            } else {
                $vehicle = $this->getVehicleById($vehicleId);
            }

            // Generate/get NFC location token
            $locationToken = $this->generateLocationToken($vinNumber, $vehicleId);
            
            // Generate/get MDA shortlink
            $mdaShortlink = null;
            try {
                $mdaShortlink = $this->mdaService->generateVehicleShortlink($vinNumber, $vehicleId);
            } catch (Exception $e) {
                log_message('warning', 'Failed to generate MDA shortlink: ' . $e->getMessage());
            }
            
            // Create unified response
            return [
                'success' => true,
                'vehicle' => $vehicle,
                'location_token' => [
                    'token' => $locationToken['token'],
                    'nfc_url' => base_url("location/{$locationToken['token']}"),
                    'qr_url' => "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . 
                              urlencode(base_url("location/{$locationToken['token']}"))
                ],
                'mda_shortlink' => $mdaShortlink,
                'unified_urls' => [
                    'location_tracking' => base_url("location/{$locationToken['token']}"),
                    'vehicle_profile' => $mdaShortlink['short_url'] ?? base_url("vehicles/v/" . substr($vinNumber, -6)),
                    'vehicle_view' => base_url("vehicles/v/" . substr($vinNumber, -6))
                ]
            ];
            
        } catch (Exception $e) {
            log_message('error', 'VehicleTokenService::generateUnifiedVehicleToken error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate or get existing location tracking token
     */
    protected function generateLocationToken($vinNumber, $vehicleId)
    {
        // Check if token already exists
        $existingToken = $this->db->table('vehicle_location_tokens')
            ->where('vin_number', $vinNumber)
            ->where('is_active', 1)
            ->get()
            ->getRowArray();

        if ($existingToken) {
            return [
                'token' => $existingToken['token'],
                'existing' => true
            ];
        }

        // Generate new token
        $token = bin2hex(random_bytes(32));
        
        $tokenData = [
            'vehicle_id' => $vehicleId,
            'vin_number' => $vinNumber,
            'token' => $token,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('vehicle_location_tokens')->insert($tokenData);
        
        return [
            'token' => $token,
            'existing' => false
        ];
    }

    /**
     * Find vehicle in recon_orders as fallback
     */
    protected function findVehicleInReconOrders($vinNumber)
    {
        return $this->db->table('recon_orders')
            ->select('id, vin_number, vehicle')
            ->where('vin_number', $vinNumber)
            ->where('deleted_at IS NULL')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getRowArray();
    }

    /**
     * Get vehicle by ID from centralized system
     */
    protected function getVehicleById($vehicleId)
    {
        // Try recon_vehicles table first
        if ($this->db->tableExists('recon_vehicles')) {
            $vehicle = $this->db->table('recon_vehicles')
                ->where('id', $vehicleId)
                ->get()
                ->getRowArray();
                
            if ($vehicle) {
                return $vehicle;
            }
        }
        
        // Fallback to recon_orders
        return $this->db->table('recon_orders')
            ->where('id', $vehicleId)
            ->where('deleted_at IS NULL')
            ->get()
            ->getRowArray();
    }

    /**
     * Get all token data for a vehicle
     */
    public function getVehicleTokenData($vinNumber)
    {
        try {
            $vinNumber = strtoupper(trim($vinNumber));
            
            // Get vehicle info
            $vehicle = $this->findVehicleInReconOrders($vinNumber);
            
            if (!$vehicle) {
                return null;
            }

            // Get location token
            $locationToken = $this->db->table('vehicle_location_tokens')
                ->where('vin_number', $vinNumber)
                ->where('is_active', 1)
                ->get()
                ->getRowArray();

            // Get MDA shortlink
            $mdaShortlink = null;
            try {
                $mdaShortlink = $this->mdaService->getVehicleShortlink($vinNumber);
            } catch (Exception $e) {
                log_message('debug', 'No MDA shortlink found for VIN: ' . $vinNumber);
            }

            return [
                'vehicle' => $vehicle,
                'location_token' => $locationToken,
                'mda_shortlink' => $mdaShortlink,
                'has_location_token' => !empty($locationToken),
                'has_mda_shortlink' => !empty($mdaShortlink),
                'urls' => [
                    'location_tracking' => $locationToken ? base_url("location/{$locationToken['token']}") : null,
                    'vehicle_profile' => $mdaShortlink['short_url'] ?? null,
                    'vehicle_view' => base_url("vehicles/v/" . substr($vinNumber, -6))
                ]
            ];
            
        } catch (Exception $e) {
            log_message('error', 'VehicleTokenService::getVehicleTokenData error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sync existing location tokens with MDA shortlinks
     * Run this to migrate existing data
     */
    public function syncExistingTokens()
    {
        try {
            $tokens = $this->db->table('vehicle_location_tokens')
                ->where('is_active', 1)
                ->get()
                ->getResultArray();

            $synced = 0;
            $errors = 0;

            foreach ($tokens as $token) {
                try {
                    // Check if MDA shortlink exists
                    $existing = $this->mdaService->getVehicleShortlink($token['vin_number']);
                    
                    if (!$existing) {
                        // Generate MDA shortlink for this vehicle
                        $this->mdaService->generateVehicleShortlink($token['vin_number'], $token['vehicle_id']);
                        $synced++;
                    }
                } catch (Exception $e) {
                    log_message('error', "Failed to sync token for VIN {$token['vin_number']}: " . $e->getMessage());
                    $errors++;
                }
            }

            return [
                'success' => true,
                'synced' => $synced,
                'errors' => $errors,
                'total_tokens' => count($tokens)
            ];

        } catch (Exception $e) {
            log_message('error', 'VehicleTokenService::syncExistingTokens error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
