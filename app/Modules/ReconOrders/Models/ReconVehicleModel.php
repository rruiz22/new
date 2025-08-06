<?php

namespace Modules\ReconOrders\Models;

use CodeIgniter\Model;

class ReconVehicleModel extends Model
{
    protected $table = 'recon_vehicles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'vin_number', 'vehicle_info', 'make', 'model', 'year', 'color',
        'first_order_id', 'last_order_id', 'total_orders', 'photos_url'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'vin_number' => 'required|max_length[17]|is_unique[recon_vehicles.vin_number,id,{id}]',
        'vehicle_info' => 'required|max_length[255]',
        'year' => 'permit_empty|integer|greater_than[1900]|less_than[2100]'
    ];

    protected $validationMessages = [
        'vin_number' => [
            'required' => 'VIN number is required',
            'is_unique' => 'This VIN already exists in the system'
        ],
        'vehicle_info' => [
            'required' => 'Vehicle information is required'
        ]
    ];

    /**
     * Register or update a vehicle from order data
     */
    public function registerFromOrder($orderData, $orderId)
    {
        $vinNumber = strtoupper(trim($orderData['vin_number'] ?? ''));
        $vehicleInfo = trim($orderData['vehicle'] ?? '');
        
        if (empty($vinNumber) || empty($vehicleInfo)) {
            return false;
        }

        // Parse vehicle info to extract make, model, year
        $parsedInfo = $this->parseVehicleInfo($vehicleInfo);
        
        // Check if vehicle already exists
        $existingVehicle = $this->where('vin_number', $vinNumber)->first();
        
        if ($existingVehicle) {
            // Update existing vehicle
            return $this->updateVehicleOrder($existingVehicle['id'], $orderId);
        } else {
            // Create new vehicle
            $vehicleData = [
                'vin_number' => $vinNumber,
                'vehicle_info' => $vehicleInfo,
                'make' => $parsedInfo['make'],
                'model' => $parsedInfo['model'],
                'year' => $parsedInfo['year'],
                'color' => $parsedInfo['color'],
                'first_order_id' => $orderId,
                'last_order_id' => $orderId,
                'total_orders' => 1
            ];
            
            return $this->insert($vehicleData);
        }
    }

    /**
     * Update vehicle with new order information
     */
    private function updateVehicleOrder($vehicleId, $orderId)
    {
        $vehicle = $this->find($vehicleId);
        if (!$vehicle) {
            return false;
        }

        $updateData = [
            'last_order_id' => $orderId,
            'total_orders' => $vehicle['total_orders'] + 1
        ];

        return $this->update($vehicleId, $updateData);
    }

    /**
     * Parse vehicle information to extract components
     */
    private function parseVehicleInfo($vehicleInfo)
    {
        $result = [
            'make' => null,
            'model' => null,
            'year' => null,
            'color' => null
        ];

        // Try to extract year (4 digits)
        if (preg_match('/\b(19|20)\d{2}\b/', $vehicleInfo, $matches)) {
            $result['year'] = (int)$matches[0];
        }

        // Split into parts and try to identify make/model
        $parts = explode(' ', trim($vehicleInfo));
        if (count($parts) >= 2) {
            // Common patterns: "2024 BMW X5", "BMW X5 2024", etc.
            $nonYearParts = array_filter($parts, function($part) {
                return !preg_match('/^\d{4}$/', $part);
            });
            
            $nonYearParts = array_values($nonYearParts);
            
            if (count($nonYearParts) >= 1) {
                $result['make'] = $nonYearParts[0];
            }
            if (count($nonYearParts) >= 2) {
                $result['model'] = $nonYearParts[1];
            }
        }

        return $result;
    }

    /**
     * Get vehicles with order statistics
     */
    public function getVehiclesWithStats()
    {
        return $this->select('recon_vehicles.*, 
                             first_order.order_number as first_order_number,
                             first_order.created_at as first_service_date,
                             last_order.order_number as last_order_number,
                             last_order.created_at as last_service_date')
                    ->join('recon_orders as first_order', 'first_order.id = recon_vehicles.first_order_id', 'left')
                    ->join('recon_orders as last_order', 'last_order.id = recon_vehicles.last_order_id', 'left')
                    ->where('recon_vehicles.deleted_at IS NULL')
                    ->orderBy('recon_vehicles.updated_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get vehicle with complete order history
     */
    public function getVehicleWithHistory($id)
    {
        $vehicle = $this->find($id);
        if (!$vehicle) {
            return null;
        }

        // Get all orders for this vehicle
        $db = \Config\Database::connect();
        $orders = $db->table('recon_orders')
                     ->select('recon_orders.*, clients.name as client_name, recon_services.name as service_name')
                     ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                     ->join('recon_services', 'recon_services.id = recon_orders.service_id', 'left')
                     ->where('recon_orders.vin_number', $vehicle['vin_number'])
                     ->where('recon_orders.deleted_at IS NULL')
                     ->orderBy('recon_orders.created_at', 'DESC')
                     ->get()
                     ->getResultArray();

        $vehicle['orders'] = $orders;
        $vehicle['orders_count'] = count($orders);

        return $vehicle;
    }

    /**
     * Get vehicle statistics
     */
    public function getVehicleStats()
    {
        $stats = [];
        
        // Total vehicles
        $stats['total_vehicles'] = $this->countAllResults();
        
        // Most serviced vehicle
        $mostServiced = $this->orderBy('total_orders', 'DESC')->first();
        $stats['most_serviced'] = $mostServiced;
        
        // Recent vehicles (last 30 days)
        $stats['recent_vehicles'] = $this->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
                                         ->countAllResults();
        
        // Popular makes
        $popularMakes = $this->select('make, COUNT(*) as count')
                            ->where('make IS NOT NULL')
                            ->where('make !=', '')
                            ->groupBy('make')
                            ->orderBy('count', 'DESC')
                            ->limit(5)
                            ->findAll();
        $stats['popular_makes'] = $popularMakes;

        return $stats;
    }

    /**
     * Search vehicles by VIN, make, model, or vehicle info
     */
    public function searchVehicles($query)
    {
        return $this->select('recon_vehicles.*, 
                             first_order.order_number as first_order_number,
                             last_order.order_number as last_order_number')
                    ->join('recon_orders as first_order', 'first_order.id = recon_vehicles.first_order_id', 'left')
                    ->join('recon_orders as last_order', 'last_order.id = recon_vehicles.last_order_id', 'left')
                    ->groupStart()
                        ->like('recon_vehicles.vin_number', $query)
                        ->orLike('recon_vehicles.vehicle_info', $query)
                        ->orLike('recon_vehicles.make', $query)
                        ->orLike('recon_vehicles.model', $query)
                    ->groupEnd()
                    ->where('recon_vehicles.deleted_at IS NULL')
                    ->orderBy('recon_vehicles.updated_at', 'DESC')
                    ->findAll();
    }
} 