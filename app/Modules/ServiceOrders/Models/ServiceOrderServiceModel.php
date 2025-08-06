<?php

namespace Modules\ServiceOrders\Models;

use CodeIgniter\Model;

class ServiceOrderServiceModel extends Model
{
    protected $table = 'service_orders_services';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'service_name',
        'service_description',
        'service_price',
        'notes',
        'created_by',
        'updated_by',
        'deleted',
        'client_id',
        'service_status',
        'show_in_orders'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'service_name' => 'required|max_length[255]',
        'service_price' => 'permit_empty|decimal',
    ];

    protected $validationMessages = [
        'service_name' => [
            'required' => 'The service name is required.',
            'max_length' => 'The service name cannot exceed 255 characters.'
        ],
        'service_price' => [
            'decimal' => 'The service price must be a valid decimal number.'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setCreatedBy'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['setUpdatedBy'];
    protected $afterUpdate = [];
    protected $beforeFind = ['excludeDeleted'];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function setCreatedBy(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['created_by'] = session()->get('user_id');
        } else {
            $data['data']['created_by'] = 1; // Default fallback
        }
        
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        if (session()->has('user_id')) {
            $data['data']['updated_by'] = session()->get('user_id');
        }
        
        return $data;
    }

    protected function excludeDeleted(array $data)
    {
        if (!isset($data['options']['excludeDeleted']) || $data['options']['excludeDeleted'] !== false) {
            if (isset($data['builder'])) {
                $data['builder']->where('deleted', 0);
            }
        }
        
        return $data;
    }

    // Get all active services
    public function getActiveServices()
    {
        return $this->where('deleted', 0)
                    ->where('service_status', 'active')
                    ->orderBy('service_name', 'ASC')
                    ->findAll();
    }

    // Get services that should show in orders
    public function getServicesForOrders()
    {
        return $this->where('deleted', 0)
                    ->where('service_status', 'active')
                    ->where('show_in_orders', 1)
                    ->orderBy('service_name', 'ASC')
                    ->findAll();
    }

    // Get services by client
    public function getServicesByClient($clientId)
    {
        return $this->where('deleted', 0)
                    ->where('service_status', 'active')
                    ->where('show_in_orders', 1)
                    ->groupStart()
                        ->where('client_id', $clientId)
                        ->orWhere('client_id', null)
                    ->groupEnd()
                    ->orderBy('service_name', 'ASC')
                    ->findAll();
    }

    // Get services with details
    public function getAllWithDetails()
    {
        return $this->select('service_orders_services.*,
                            clients.name as client_name,
                            CONCAT(created_user.first_name, " ", created_user.last_name) as created_by_name,
                            CONCAT(updated_user.first_name, " ", updated_user.last_name) as updated_by_name')
                    ->join('clients', 'clients.id = service_orders_services.client_id', 'left')
                    ->join('users as created_user', 'created_user.id = service_orders_services.created_by', 'left')
                    ->join('users as updated_user', 'updated_user.id = service_orders_services.updated_by', 'left')
                    ->where('service_orders_services.deleted', 0)
                    ->orderBy('service_orders_services.service_name', 'ASC')
                    ->findAll();
    }

    // Soft delete
    public function delete($id = null, bool $purge = false)
    {
        if ($id !== null) {
            return $this->update($id, ['deleted' => 1]);
        }
        
        return false;
    }

    // Toggle service status
    public function toggleStatus($id)
    {
        $service = $this->find($id);
        if ($service) {
            $newStatus = $service['service_status'] === 'active' ? 'deactivated' : 'active';
            return $this->update($id, ['service_status' => $newStatus]);
        }
        
        return false;
    }

    // Toggle show in orders
    public function toggleShowInOrders($id)
    {
        $service = $this->find($id);
        if ($service) {
            $newValue = $service['show_in_orders'] === 1 ? 0 : 1;
            return $this->update($id, ['show_in_orders' => $newValue]);
        }
        
        return false;
    }

    // Get deleted services
    public function getDeleted()
    {
        return $this->select('service_orders_services.*,
                            clients.name as client_name')
                    ->join('clients', 'clients.id = service_orders_services.client_id', 'left')
                    ->where('service_orders_services.deleted', 1)
                    ->orderBy('service_orders_services.updated_at', 'DESC')
                    ->findAll();
    }

    // Restore deleted service
    public function restore($id)
    {
        return $this->update($id, ['deleted' => 0]);
    }
} 