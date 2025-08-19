<?php

namespace App\Services;

use Exception;

class VehicleService
{
    protected $db;
    protected $vehiclesTable = 'recon_vehicles'; // Using existing table
    protected $vehicleOrdersTable = 'vehicle_orders';
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    
    /**
     * Find or create vehicle by VIN (using existing recon_vehicles table)
     */
    public function findOrCreateVehicle($vinNumber, $vehicleDescription = null, $isManualEntry = false)
    {
        $vinNumber = strtoupper(trim($vinNumber));
        
        // Try to find existing vehicle in recon_vehicles
        $vehicle = $this->db->table($this->vehiclesTable)
            ->where('vin_number', $vinNumber)
            ->where('deleted_at IS NULL')
            ->get()
            ->getRowArray();
            
        if ($vehicle) {
            // Update last_seen_at if column exists, otherwise just update updated_at
            $updateData = [
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Update vehicle_info if provided and different
            if ($vehicleDescription && $vehicle['vehicle_info'] !== $vehicleDescription) {
                $updateData['vehicle_info'] = $vehicleDescription;
            }
            
            $this->db->table($this->vehiclesTable)
                ->where('id', $vehicle['id'])
                ->update($updateData);
                
            return $vehicle;
        }
        
        // Create new vehicle in recon_vehicles table
        $newVehicle = [
            'vin_number' => $vinNumber,
            'vehicle_info' => $vehicleDescription ?: 'Manual Entry Vehicle',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $vehicleId = $this->db->table($this->vehiclesTable)->insert($newVehicle);
        $newVehicle['id'] = $vehicleId;
        
        return $newVehicle;
    }
    
    /**
     * Find or create vehicle and link to order with complete information
     */
    public function findOrCreateVehicleFromOrder($orderType, $orderData)
    {
        if (empty($orderData['vin_number'])) {
            throw new Exception('VIN number is required');
        }
        
        $vinNumber = strtoupper(trim($orderData['vin_number']));
        
        // Find or create vehicle
        $vehicle = $this->findOrCreateVehicle($vinNumber, $orderData['vehicle'] ?? null, false);
        
        // Link to order with complete information if vehicle_orders table exists
        if ($this->db->tableExists($this->vehicleOrdersTable)) {
            $this->linkVehicleToOrderComplete($vehicle['id'], $orderType, $orderData);
        }
        
        return $vehicle;
    }
    
    /**
     * Link vehicle to order with complete information
     */
    public function linkVehicleToOrderComplete($vehicleId, $orderType, $orderData)
    {
        if (!$this->db->tableExists($this->vehicleOrdersTable)) {
            return; // Skip if table doesn't exist yet
        }
        
        $linkData = [
            'vehicle_id' => $vehicleId,
            'vin_number' => $orderData['vin_number'],
            'order_type' => $orderType,
            'order_id' => $orderData['id'],
            'order_number' => $this->generateOrderNumber($orderType, $orderData),
            
            // Client information
            'client_id' => $orderData['client_id'] ?? null,
            'client_name' => $orderData['client_name'] ?? null,
            
            // Order specific information
            'stock' => $orderData['stock'] ?? null,
            'service_name' => $orderData['service_name'] ?? null,
            'service_date' => $orderData['service_date'] ?? null,
            'service_color' => $orderData['service_color'] ?? '#007bff',
            'order_status' => $orderData['status'] ?? 'pending',
            'order_date' => $orderData['created_at'] ?? date('Y-m-d H:i:s'),
            
            // Metadata
            'from_inventory' => $orderData['from_inventory'] ?? 0,
            'source_type' => $orderData['source_type'] ?? 'manual',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Check if link already exists
        $existing = $this->db->table($this->vehicleOrdersTable)
            ->where('vehicle_id', $vehicleId)
            ->where('order_type', $orderType)
            ->where('order_id', $orderData['id'])
            ->get()
            ->getRowArray();
            
        if ($existing) {
            // Update existing link
            $this->db->table($this->vehicleOrdersTable)
                ->where('id', $existing['id'])
                ->update($linkData);
        } else {
            // Create new link
            $this->db->table($this->vehicleOrdersTable)->insert($linkData);
        }
    }
    
    /**
     * Generate order number based on type and data
     */
    private function generateOrderNumber($orderType, $orderData)
    {
        switch ($orderType) {
            case 'recon':
                return 'RO-' . str_pad($orderData['id'], 5, '0', STR_PAD_LEFT);
            case 'sales':
                return $orderData['order_number'] ?? ('SO-' . str_pad($orderData['id'], 5, '0', STR_PAD_LEFT));
            case 'service':
                return 'SV-' . str_pad($orderData['id'], 5, '0', STR_PAD_LEFT);
            case 'carwash':
                return 'CW-' . str_pad($orderData['id'], 5, '0', STR_PAD_LEFT);
            default:
                return $orderType . '-' . $orderData['id'];
        }
    }
    
    /**
     * Get vehicle with all associated orders (enhanced view)
     */
    public function getVehicleWithOrdersEnhanced($vinNumber)
    {
        $vehicle = $this->db->table($this->vehiclesTable)
            ->where('vin_number', strtoupper($vinNumber))
            ->where('deleted_at IS NULL')
            ->get()
            ->getRowArray();
            
        if (!$vehicle) {
            return null;
        }
        
        $orders = [];
        $ordersByType = [];
        
        // Get all associated orders with complete information if table exists
        if ($this->db->tableExists($this->vehicleOrdersTable)) {
            $orders = $this->db->table($this->vehicleOrdersTable . ' vo')
                ->where('vo.vehicle_id', $vehicle['id'])
                ->orderBy('vo.order_date', 'DESC')
                ->get()
                ->getResultArray();
                
            // Group orders by type for better organization
            foreach ($orders as $order) {
                $ordersByType[$order['order_type']][] = $order;
            }
        }
        
        $vehicle['orders'] = $orders;
        $vehicle['orders_by_type'] = $ordersByType;
        $vehicle['total_orders'] = count($orders);
        $vehicle['order_types'] = array_keys($ordersByType);
        
        // Add compatibility fields for existing code
        $vehicle['vehicle_description'] = $vehicle['vehicle_info'];
        $vehicle['vin_last6'] = substr($vehicle['vin_number'], -6);
        
        return $vehicle;
    }
    
    /**
     * Search vehicles across all order types
     */
    public function searchVehicles($searchTerm, $clientId = null, $orderType = null)
    {
        $builder = $this->db->table($this->vehiclesTable . ' v')
            ->select('v.*')
            ->where('v.deleted_at IS NULL');
            
        // Add order information if vehicle_orders table exists
        if ($this->db->tableExists($this->vehicleOrdersTable)) {
            $builder->select('v.*, 
                             GROUP_CONCAT(DISTINCT vo.order_type) as order_types,
                             GROUP_CONCAT(DISTINCT vo.client_id) as client_ids,
                             GROUP_CONCAT(DISTINCT vo.stock) as stock_numbers,
                             COUNT(vo.id) as total_orders,
                             MAX(vo.order_date) as last_order_date,
                             GROUP_CONCAT(DISTINCT vo.client_name) as client_names')
                ->join($this->vehicleOrdersTable . ' vo', 'v.id = vo.vehicle_id', 'left')
                ->groupBy('v.id');
        }
            
        if ($searchTerm) {
            $builder->groupStart()
                ->like('v.vin_number', $searchTerm)
                ->orLike('v.vehicle_info', $searchTerm);
                
            // Add order-related search if table exists
            if ($this->db->tableExists($this->vehicleOrdersTable)) {
                $builder->orLike('vo.stock', $searchTerm)
                    ->orLike('vo.client_name', $searchTerm)
                    ->orLike('vo.order_number', $searchTerm);
            }
            
            $builder->groupEnd();
        }
        
        if ($clientId && $this->db->tableExists($this->vehicleOrdersTable)) {
            $builder->having('FIND_IN_SET(?, client_ids)', [$clientId]);
        }
        
        if ($orderType && $this->db->tableExists($this->vehicleOrdersTable)) {
            $builder->having('FIND_IN_SET(?, order_types)', [$orderType]);
        }
        
        $results = $builder->orderBy('v.updated_at', 'DESC')->get()->getResultArray();
        
        // Add compatibility fields
        foreach ($results as &$result) {
            $result['vehicle_description'] = $result['vehicle_info'];
            $result['vin_last6'] = substr($result['vin_number'], -6);
        }
        
        return $results;
    }
    
    /**
     * Sync existing orders to new vehicle system (enhanced)
     */
    public function syncExistingOrdersEnhanced()
    {
        if (!$this->db->tableExists($this->vehicleOrdersTable)) {
            log_message('warning', 'vehicle_orders table does not exist, skipping sync');
            return;
        }
        
        $orderTables = [
            'recon' => [
                'table' => 'recon_orders',
                'joins' => [
                    ['table' => 'clients c', 'on' => 'recon_orders.client_id = c.id', 'type' => 'left'],
                    ['table' => 'recon_services s', 'on' => 'recon_orders.service_id = s.id', 'type' => 'left']
                ],
                'select' => 'recon_orders.*, c.name as client_name, s.name as service_name, s.color as service_color'
            ],
            'sales' => [
                'table' => 'sales_orders',
                'joins' => [
                    ['table' => 'clients c', 'on' => 'sales_orders.client_id = c.id', 'type' => 'left']
                ],
                'select' => 'sales_orders.*, c.name as client_name'
            ],
            'service' => [
                'table' => 'service_orders',
                'joins' => [
                    ['table' => 'clients c', 'on' => 'service_orders.client_id = c.id', 'type' => 'left']
                ],
                'select' => 'service_orders.*, c.name as client_name'
            ],
            'carwash' => [
                'table' => 'car_wash_orders',
                'joins' => [
                    ['table' => 'clients c', 'on' => 'car_wash_orders.client_id = c.id', 'type' => 'left']
                ],
                'select' => 'car_wash_orders.*, c.name as client_name'
            ]
        ];
        
        foreach ($orderTables as $type => $config) {
            if (!$this->db->tableExists($config['table'])) continue;
            
            $builder = $this->db->table($config['table']);
            $builder->select($config['select']);
            
            // Add joins
            foreach ($config['joins'] as $join) {
                $builder->join($join['table'], $join['on'], $join['type']);
            }
            
            $builder->where($config['table'] . '.vin_number IS NOT NULL')
                   ->where($config['table'] . '.vin_number !=', '');
                   
            $orders = $builder->get()->getResultArray();
                
            foreach ($orders as $order) {
                try {
                    // Find or create vehicle
                    $vehicle = $this->findOrCreateVehicle($order['vin_number'], $order['vehicle'] ?? null);
                    
                    // Link to order with complete information
                    $this->linkVehicleToOrderComplete($vehicle['id'], $type, $order);
                } catch (Exception $e) {
                    log_message('error', "Error syncing {$type} order {$order['id']}: " . $e->getMessage());
                }
            }
        }
    }
    
    /**
     * Create manual vehicle entry (simplified)
     */
    public function createManualVehicle($vinNumber, $vehicleDescription)
    {
        if (empty($vinNumber)) {
            throw new Exception('VIN number is required');
        }
        
        return $this->findOrCreateVehicle($vinNumber, $vehicleDescription, true);
    }
}
