<?php

namespace Modules\SalesOrders\Models;

use CodeIgniter\Model;

class SalesOrderModel extends Model
{
    protected $table = 'sales_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false; // No usar softDeletes, usamos un campo 'deleted' en su lugar
    protected $protectFields = true;
    protected $allowedFields = [
        'client_id',
        'contact_id',
        'salesperson_id',
        'stock',
        'vin',
        'vehicle',
        'service_id',
        'date',
        'time',
        'status',
        'instructions',
        'notes',
        'short_url',
        'short_url_slug',
        'lima_link_id',
        'qr_generated_at',
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
        'created_by' => [
            'required' => 'The salesperson is required.',
            'numeric' => 'The salesperson must be a valid ID.'
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

    // Get orders with client info
    public function getAllWithDetails()
    {
        return $this->select('sales_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            sales_orders_services.service_name,
                            sales_orders_services.service_price')
                    ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                    ->join('users', 'users.id = sales_orders.salesperson_id', 'left')
                    ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                    ->where('sales_orders.deleted', 0)
                    ->orderBy('sales_orders.created_at', 'DESC')
                    ->findAll();
    }

    // Get today's orders
    public function getTodayOrders()
    {
        return $this->select('sales_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            sales_orders_services.service_name,
                            sales_orders_services.service_price')
                    ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                    ->join('users', 'users.id = sales_orders.salesperson_id', 'left')
                    ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                    ->where('sales_orders.deleted', 0)
                    ->where('sales_orders.date', date('Y-m-d'))
                    ->orderBy('sales_orders.time', 'ASC')
                    ->findAll();
    }

    // Get tomorrow's orders
    public function getTomorrowOrders()
    {
        return $this->select('sales_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            sales_orders_services.service_name,
                            sales_orders_services.service_price')
                    ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                    ->join('users', 'users.id = sales_orders.salesperson_id', 'left')
                    ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                    ->where('sales_orders.deleted', 0)
                    ->where('sales_orders.date', date('Y-m-d', strtotime('+1 day')))
                    ->orderBy('sales_orders.time', 'ASC')
                    ->findAll();
    }

    // Get pending orders
    public function getPendingOrders()
    {
        return $this->select('sales_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            sales_orders_services.service_name,
                            sales_orders_services.service_price')
                    ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                    ->join('users', 'users.id = sales_orders.salesperson_id', 'left')
                    ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                    ->where('sales_orders.deleted', 0)
                    ->where('sales_orders.status', 'pending')
                    ->orderBy('sales_orders.date', 'ASC')
                    ->orderBy('sales_orders.time', 'ASC')
                    ->findAll();
    }

    // Get this week's orders
    public function getWeekOrders()
    {
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        
        return $this->select('sales_orders.*,
                            clients.name as client_name,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            sales_orders_services.service_name,
                            sales_orders_services.service_price')
                    ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                    ->join('users', 'users.id = sales_orders.salesperson_id', 'left')
                    ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                    ->where('sales_orders.deleted', 0)
                    ->where('sales_orders.date >=', $startOfWeek)
                    ->where('sales_orders.date <=', $endOfWeek)
                    ->orderBy('sales_orders.date', 'ASC')
                    ->orderBy('sales_orders.time', 'ASC')
                    ->findAll();
    }

    /**
     * Override delete method to implement soft delete
     */
    public function delete($id = null, bool $purge = false)
    {
        if ($purge) {
            // Hard delete - permanently remove from database
            return parent::delete($id, true);
        }
        
        // Soft delete - mark as deleted
        if ($id === null) {
            return false;
        }
        
        // Update the deleted field to 1
        $data = [
            'deleted' => 1,
            'updated_by' => session()->get('user_id') ?? null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($id, $data);
    }

    /**
     * Restore a soft deleted record
     */
    public function restore($id)
    {
        $data = [
            'deleted' => 0,
            'updated_by' => session()->get('user_id') ?? null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->set($data)
                    ->where('id', $id)
                    ->update();
    }

    /**
     * Get only deleted records
     */
    public function onlyDeleted()
    {
        // Create a new instance to avoid affecting the main model instance
        $newInstance = new static();
        // Disable the excludeDeleted callback for this query
        $newInstance->allowCallbacks = false;
        return $newInstance->where('deleted', 1);
    }

    /**
     * Get all records including deleted ones
     */
    public function includeDeleted()
    {
        // Create a new query builder instance that bypasses the beforeFind callback
        $db = \Config\Database::connect();
        return $db->table($this->table);
    }

    /**
     * Permanently delete a record (hard delete)
     */
    public function forceDelete($id)
    {
        return parent::delete($id, true);
    }

    /**
     * Check if a record is soft deleted
     */
    public function isDeleted($id)
    {
        $record = $this->includeDeleted()->where('id', $id)->get()->getRowArray();
        return $record && $record['deleted'] == 1;
    }

    /**
     * Get count of deleted records
     */
    public function getDeletedCount()
    {
        return $this->includeDeleted()->where('deleted', 1)->countAllResults();
    }

    /**
     * Get count of active records
     */
    public function getActiveCount()
    {
        return $this->where('deleted', 0)->countAllResults();
    }

    /**
     * Bulk restore multiple records
     */
    public function bulkRestore(array $ids)
    {
        if (empty($ids)) {
            return false;
        }

        $data = [
            'deleted' => 0,
            'updated_by' => session()->get('user_id') ?? null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->set($data)
                    ->whereIn('id', $ids)
                    ->update();
    }

    /**
     * Bulk soft delete multiple records
     */
    public function bulkDelete(array $ids)
    {
        if (empty($ids)) {
            return false;
        }

        $data = [
            'deleted' => 1,
            'updated_by' => session()->get('user_id') ?? null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->set($data)
                    ->whereIn('id', $ids)
                    ->update();
    }

    /**
     * Get single order with all details for PDF generation
     */
    public function getOrderWithDetails($id)
    {
        return $this->select('sales_orders.*, 
                             clients.name as client_name,
                             clients.email as client_email,
                             clients.phone as client_phone,
                             clients.address as client_address,
                             CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                             auth_identities.secret as salesperson_email,
                             users.phone as salesperson_phone,
                             sales_orders_services.service_name,
                             sales_orders_services.service_price')
                   ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                   ->join('users', 'users.id = sales_orders.contact_id', 'left')
                   ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                   ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                   ->where('sales_orders.deleted', 0)
                   ->where('sales_orders.id', $id)
                   ->first();
    }

    public function getOrderById($id)
    {
        return $this->select('sales_orders.*,
                            clients.name as client_name, 
                            clients.phone as client_phone,
                            clients.email as client_email,
                            clients.address as client_address,
                            clients.city as client_city,
                            clients.state as client_state,
                            clients.country as client_country,
                            clients.zip as client_zip,
                            CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                            sales_orders_services.service_name,
                            sales_orders_services.service_price')
                    ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                    ->join('users', 'users.id = sales_orders.contact_id', 'left')
                    // Use left join for sales_orders_services
                    ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                    ->where('sales_orders.deleted', 0)
                    ->where('sales_orders.id', $id)
                    ->first();
    }
} 