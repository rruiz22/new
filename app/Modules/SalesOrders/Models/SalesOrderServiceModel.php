<?php

namespace Modules\SalesOrders\Models;

use CodeIgniter\Model;

class SalesOrderServiceModel extends Model
{
    protected $table = 'sales_orders_services';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false; // No usar softDeletes, usamos un campo 'deleted' en su lugar
    protected $protectFields = true;
    protected $allowedFields = [
        'service_name',
        'service_description',
        'service_price',
        'notes',
        'client_id',
        'service_status',
        'show_in_orders',
        'created_by',
        'updated_by',
        'deleted'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = ''; // No usamos este campo, usamos 'deleted' como TINYINT

    // Validation
    protected $validationRules = [
        'service_name' => 'required',
        'service_price' => 'required|decimal'
    ];

    protected $validationMessages = [
        'service_name' => [
            'required' => 'The service name is required.'
        ],
        'service_price' => [
            'required' => 'The price is required.',
            'decimal' => 'The price must be a valid decimal number.'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setCreatedBy', 'logHistory'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['setUpdatedBy', 'logHistory'];
    protected $afterUpdate = [];
    protected $beforeFind = ['excludeDeleted'];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function setCreatedBy(array $data)
    {
        // Si hay un usuario logueado, establecer created_by
        if (session()->has('user_id')) {
            $data['data']['created_by'] = session()->get('user_id');
        }
        
        return $data;
    }
    
    protected function setUpdatedBy(array $data)
    {
        // Si hay un usuario logueado, establecer updated_by
        if (session()->has('user_id')) {
            $data['data']['updated_by'] = session()->get('user_id');
        }
        
        return $data;
    }
    
    protected function excludeDeleted(array $data)
    {
        // Exclude deleted records
        if (!isset($data['options']['excludeDeleted']) || $data['options']['excludeDeleted'] !== false) {
            if (!isset($data['builder'])) {
                return $data;
            }
            
            $data['builder']->where('deleted', 0);
        }
        
        return $data;
    }

    protected function logHistory(array $data)
    {
        // No realizar el registro si es una inserción (no hay id aún)
        if (empty($data['id'])) {
            return $data;
        }
        
        $userId = session()->has('user_id') ? session()->get('user_id') : 0;
        $serviceId = $data['id'];
        $changes = json_encode(['action' => !empty($data['data']['deleted']) ? 'deleted' : 'updated']);
        
        // Registrar el cambio en el historial
        $db = \Config\Database::connect();
        $db->table('sales_orders_services_history')->insert([
            'service_id' => $serviceId,
            'changes' => $changes,
            'changed_by' => $userId,
            'changed_at' => date('Y-m-d H:i:s')
        ]);
        
        return $data;
    }

    protected function logToHistory($serviceId, $action, $data)
    {
        $db = \Config\Database::connect();
        
        $historyData = [
            'service_id' => $serviceId,
            'action' => $action,
            'old_data' => isset($data['old']) ? json_encode($data['old']) : null,
            'new_data' => isset($data['new']) ? json_encode($data['new']) : null,
            'changed_by' => session()->get('user_id'),
            'changed_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('sales_orders_services_history')->insert($historyData);
    }

    // Get active services
    public function getActiveServices()
    {
        return $this->select('sales_orders_services.*, clients.name as client_name')
                   ->join('clients', 'clients.id = sales_orders_services.client_id', 'left')
                   ->where('sales_orders_services.deleted', 0)
                   ->where('sales_orders_services.service_status', 'active')
                   ->findAll();
    }

    // Get services for a specific client
    public function getClientServices($clientId)
    {
        return $this->select('sales_orders_services.*')
                   ->where('sales_orders_services.deleted', 0)
                   ->where('sales_orders_services.client_id', $clientId)
                   ->findAll();
    }

    // Activate service
    public function activateService($id)
    {
        return $this->update($id, ['service_status' => 'active']);
    }

    // Deactivate service
    public function deactivateService($id)
    {
        return $this->update($id, ['service_status' => 'deactivated']);
    }

    public function getActiveServicesWithClients()
    {
        return $this->select('sales_orders_services.*, clients.name as client_name')
                    ->join('clients', 'clients.id = sales_orders_services.client_id', 'left')
                    ->where('sales_orders_services.deleted', 0)
                    ->where('sales_orders_services.service_status', 'active')
                    ->orderBy('service_name', 'ASC')
                    ->findAll();
    }

    public function getServicesByClient($clientId)
    {
        return $this->select('sales_orders_services.*')
                    ->where('sales_orders_services.deleted', 0)
                    ->where('sales_orders_services.client_id', $clientId)
                    ->orderBy('service_name', 'ASC')
                    ->findAll();
    }
} 