<?php

namespace Modules\ServiceOrders\Models;

use CodeIgniter\Model;

class ServiceOrderModel extends Model
{
    protected $table = 'service_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false; // No usar softDeletes, usamos un campo 'deleted' en su lugar
    protected $protectFields = true;
    protected $allowedFields = [
        'client_id',
        'contact_id',
        'salesperson_id', // Legacy field - kept for compatibility
        'ro_number',
        'po_number',
        'tag_number',
        'vin',
        'vehicle',
        'service_id',
        'date',
        'time',
        'status',
        'total_amount',
        'short_url',
        'short_url_slug',
        'lima_link_id',
        'qr_generated_at',
        'instructions',
        'notes',
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
        'client_id' => 'required|numeric',
        'contact_id' => 'required|numeric',
        'service_id' => 'required|numeric',
        'date' => 'required|valid_date',
        'status' => 'required',
    ];

    protected $validationMessages = [
        'client_id' => [
            'required' => 'The client is required.',
            'numeric' => 'The client must be a valid client ID.'
        ],
        'contact_id' => [
            'required' => 'The assigned contact is required.',
            'numeric' => 'The contact must be a valid ID.'
        ],
        'service_id' => [
            'required' => 'The service is required.',
            'numeric' => 'The service must be a valid ID.'
        ],
        'date' => [
            'required' => 'The date is required.',
            'valid_date' => 'The date must be a valid date.'
        ],
        'status' => [
            'required' => 'The status is required.'
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
        // Si hay un usuario logueado, establecer created_by
        if (session()->has('user_id')) {
            $data['data']['created_by'] = session()->get('user_id');
        } else {
            // Si no hay sesión, usar 1 como default y log para debugging
            $data['data']['created_by'] = 1;
            log_message('warning', 'ServiceOrderModel::setCreatedBy - No user session found, using default created_by = 1');
        }
        
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        // Si hay un usuario logueado, establecer updated_by
        if (session()->has('user_id')) {
            $data['data']['updated_by'] = session()->get('user_id');
        } else {
            // Si no hay sesión, usar 1 como default y log para debugging
            $data['data']['updated_by'] = 1;
            log_message('warning', 'ServiceOrderModel::setUpdatedBy - No user session found, using default updated_by = 1');
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

    // Get orders with client info
    public function getAllWithDetails()
    {
        return $this->select('service_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            auth_identities.secret as salesperson_email,
                            users.phone as salesperson_phone,
                            service_orders_services.service_name,
                            service_orders_services.service_price')
                    ->join('clients', 'clients.id = service_orders.client_id', 'left')
                    ->join('users', 'users.id = service_orders.contact_id', 'left')
                    ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                    ->join('service_orders_services', 'service_orders_services.id = service_orders.service_id', 'left')
                    ->where('service_orders.deleted', 0)
                    ->orderBy('service_orders.created_at', 'DESC')
                    ->findAll();
    }

    // Get today's orders
    public function getTodayOrders()
    {
        return $this->select('service_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            service_orders_services.service_name,
                            service_orders_services.service_price')
                    ->join('clients', 'clients.id = service_orders.client_id', 'left')
                    ->join('users', 'users.id = service_orders.contact_id', 'left')
                    ->join('service_orders_services', 'service_orders_services.id = service_orders.service_id', 'left')
                    ->where('service_orders.deleted', 0)
                    ->where('service_orders.date', date('Y-m-d'))
                    ->orderBy('service_orders.time', 'ASC')
                    ->findAll();
    }

    // Get tomorrow's orders
    public function getTomorrowOrders()
    {
        return $this->select('service_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            service_orders_services.service_name,
                            service_orders_services.service_price')
                    ->join('clients', 'clients.id = service_orders.client_id', 'left')
                    ->join('users', 'users.id = service_orders.contact_id', 'left')
                    ->join('service_orders_services', 'service_orders_services.id = service_orders.service_id', 'left')
                    ->where('service_orders.deleted', 0)
                    ->where('service_orders.date', date('Y-m-d', strtotime('+1 day')))
                    ->orderBy('service_orders.time', 'ASC')
                    ->findAll();
    }

    // Get pending orders
    public function getPendingOrders()
    {
        return $this->select('service_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            service_orders_services.service_name,
                            service_orders_services.service_price')
                    ->join('clients', 'clients.id = service_orders.client_id', 'left')
                    ->join('users', 'users.id = service_orders.contact_id', 'left')
                    ->join('service_orders_services', 'service_orders_services.id = service_orders.service_id', 'left')
                    ->where('service_orders.deleted', 0)
                    ->where('service_orders.status', 'pending')
                    ->orderBy('service_orders.date', 'ASC')
                    ->orderBy('service_orders.time', 'ASC')
                    ->findAll();
    }

    // Get this week's orders
    public function getWeekOrders()
    {
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        
        return $this->select('service_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            service_orders_services.service_name,
                            service_orders_services.service_price')
                    ->join('clients', 'clients.id = service_orders.client_id', 'left')
                    ->join('users', 'users.id = service_orders.contact_id', 'left')
                    ->join('service_orders_services', 'service_orders_services.id = service_orders.service_id', 'left')
                    ->where('service_orders.deleted', 0)
                    ->where('service_orders.date >=', $startOfWeek)
                    ->where('service_orders.date <=', $endOfWeek)
                    ->orderBy('service_orders.date', 'ASC')
                    ->orderBy('service_orders.time', 'ASC')
                    ->findAll();
    }

    // Get order with full details
    public function getOrderWithDetails($id)
    {
        return $this->select('service_orders.*,
                            clients.name as client_name,
                            clients.email as client_email,
                            clients.phone as client_phone,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            auth_identities.secret as salesperson_email,
                            users.phone as salesperson_phone,
                            service_orders_services.service_name,
                            service_orders_services.service_price')
                    ->join('clients', 'clients.id = service_orders.client_id', 'left')
                    ->join('users', 'users.id = service_orders.contact_id', 'left')
                    ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                    ->join('service_orders_services', 'service_orders_services.id = service_orders.service_id', 'left')
                    ->where('service_orders.id', $id)
                    ->first();
    }

    // Get order by ID (simple)
    public function getOrderById($id)
    {
        return $this->where('id', $id)->first();
    }

    // Soft delete (mark as deleted)
    public function delete($id = null, bool $purge = false)
    {
        if ($id !== null) {
            return $this->where('id', $id)->set(['deleted' => 1])->update();
        }
        
        // For model-based deletion
        return $this->set(['deleted' => 1])->update();
    }

    // Restore a soft-deleted record
    public function restore($id)
    {
        return $this->where('id', $id)->set(['deleted' => 0])->update();
    }

    // Get only deleted records
    public function onlyDeleted()
    {
        return $this->where('deleted', 1);
    }

    // Include deleted records in results
    public function includeDeleted()
    {
        return $this->allowCallbacks(false);
    }

    // Force delete (permanent deletion)
    public function forceDelete($id)
    {
        return $this->where('id', $id)->purgeDeleted();
    }

    // Check if record is deleted
    public function isDeleted($id)
    {
        $record = $this->select('deleted')->where('id', $id)->first();
        return $record ? (bool) $record['deleted'] : false;
    }

    // Get count of deleted records
    public function getDeletedCount()
    {
        return $this->where('deleted', 1)->countAllResults();
    }

    // Get count of active records
    public function getActiveCount()
    {
        return $this->where('deleted', 0)->countAllResults();
    }

    // Bulk restore multiple records
    public function bulkRestore(array $ids)
    {
        if (empty($ids)) {
            return false;
        }
        
        return $this->whereIn('id', $ids)->set(['deleted' => 0])->update();
    }

    // Bulk delete multiple records
    public function bulkDelete(array $ids)
    {
        if (empty($ids)) {
            return false;
        }
        
        return $this->whereIn('id', $ids)->set(['deleted' => 1])->update();
    }

    // Get deleted records with details
    public function getDeletedWithDetails()
    {
        return $this->select('service_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            service_orders_services.service_name,
                            service_orders_services.service_price')
                    ->join('clients', 'clients.id = service_orders.client_id', 'left')
                    ->join('users', 'users.id = service_orders.contact_id', 'left')
                    ->join('service_orders_services', 'service_orders_services.id = service_orders.service_id', 'left')
                    ->where('service_orders.deleted', 1)
                    ->orderBy('service_orders.updated_at', 'DESC')
                    ->findAll();
    }
} 