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
        'order_number',
        'client_id',
        'contact_id',
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
    protected $beforeInsert = ['generateOrderNumber', 'setCreatedBy'];
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
        $userId = auth()->id() ?? session()->get('user_id');
        if ($userId) {
            $data['data']['created_by'] = $userId;
        }
        
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        // Si hay un usuario logueado, establecer updated_by
        $userId = auth()->id() ?? session()->get('user_id');
        if ($userId) {
            $data['data']['updated_by'] = $userId;
        }
        
        return $data;
    }

    protected function generateOrderNumber(array $data)
    {
        // Solo generar si no se proporciona order_number
        if (empty($data['data']['order_number'])) {
            $prefix = 'SAL-';
            $attempts = 0;
            $maxAttempts = 100;
            
            do {
                // Generate order number based on current timestamp and random number
                $timestamp = date('ymdHis');
                $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                $orderNumber = $prefix . $timestamp . $random;
                
                // Check if this order number already exists
                $existing = $this->where('order_number', $orderNumber)->first();
                
                if (!$existing) {
                    $data['data']['order_number'] = $orderNumber;
                    return $data;
                }
                
                $attempts++;
                
                // Add small delay to ensure different timestamp
                usleep(10000); // 10ms
                
            } while ($attempts < $maxAttempts);
            
            // Fallback: use timestamp with microseconds
            $fallback = $prefix . date('ymdHis') . substr(microtime(), 2, 6);
            log_message('warning', 'Order number generation reached max attempts, using fallback: ' . $fallback);
            $data['data']['order_number'] = $fallback;
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
                    ->join('users', 'users.id = sales_orders.contact_id', 'left')
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
                    ->join('users', 'users.id = sales_orders.contact_id', 'left')
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
                    ->join('users', 'users.id = sales_orders.contact_id', 'left')
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
                    ->join('users', 'users.id = sales_orders.contact_id', 'left')
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
                    ->join('users', 'users.id = sales_orders.contact_id', 'left')
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
            'updated_by' => auth()->id() ?? session()->get('user_id') ?? null,
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
            'updated_by' => auth()->id() ?? session()->get('user_id') ?? null,
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
            'updated_by' => auth()->id() ?? session()->get('user_id') ?? null,
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
            'updated_by' => auth()->id() ?? session()->get('user_id') ?? null,
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
        try {
            // Start with basic order data
            $order = $this->select('sales_orders.*')
                          ->where('sales_orders.deleted', 0)
                          ->where('sales_orders.id', $id)
                          ->first();
            
            if (!$order) {
                return null;
            }
            
            // Manually add related data to avoid complex JOIN issues
            $order = (array) $order;
            
            // Get client data
            if ($order['client_id']) {
                $clientModel = model('App\Models\ClientModel');
                $client = $clientModel->find($order['client_id']);
                if ($client) {
                    $order['client_name'] = $client['name'];
                    $order['client_email'] = $client['email'] ?? '';
                    $order['client_phone'] = $client['phone'] ?? '';
                    $order['client_address'] = $client['address'] ?? '';
                }
            }
            
            // Get assigned user data from contact_id field (the user assigned to this order)
            if ($order['contact_id']) {
                $userModel = model('App\Models\UserModel');
                $assignedUser = $userModel->find($order['contact_id']);
                if ($assignedUser) {
                    $order['contact_name'] = ($assignedUser['first_name'] ?? '') . ' ' . ($assignedUser['last_name'] ?? '');
                    $order['contact_name'] = trim($order['contact_name']); 
                    $order['contact_phone'] = $assignedUser['phone'] ?? '';
                    $order['contact_first_name'] = $assignedUser['first_name'] ?? '';
                    $order['contact_last_name'] = $assignedUser['last_name'] ?? '';
                    
                    // Also set as salesperson for backward compatibility
                    $order['salesperson_name'] = $order['contact_name'];
                    $order['salesperson_phone'] = $order['contact_phone'];
                    
                    // Get email from auth_identities table (CI4 Shield)
                    $db = \Config\Database::connect();
                    $authIdentity = $db->table('auth_identities')
                                      ->where('user_id', $order['contact_id'])
                                      ->where('type', 'email_password')
                                      ->get()
                                      ->getRowArray();
                    $order['contact_email'] = $authIdentity['secret'] ?? '';
                    $order['salesperson_email'] = $order['contact_email'];
                }
            }
            
            // Get service data
            if ($order['service_id']) {
                $serviceModel = model('Modules\SalesOrders\Models\SalesOrderServiceModel');
                $service = $serviceModel->find($order['service_id']);
                if ($service) {
                    $order['service_name'] = $service['service_name'];
                    $order['service_price'] = $service['service_price'] ?? 0;
                }
            }
            
            // Get creator data (user who created the order)
            if ($order['created_by']) {
                $creator = $userModel->find($order['created_by']);
                if ($creator) {
                    $order['created_by_name'] = trim(($creator['first_name'] ?? '') . ' ' . ($creator['last_name'] ?? ''));
                    $order['created_by_username'] = $creator['username'] ?? '';
                    $order['created_by_first_name'] = $creator['first_name'] ?? '';
                    $order['created_by_last_name'] = $creator['last_name'] ?? '';
                    $order['created_by_user_type'] = $creator['user_type'] ?? '';
                    
                    // Get email from auth_identities table (CI4 Shield) for creator
                    $creatorAuthIdentity = $this->db->table('auth_identities')
                                             ->where('user_id', $order['created_by'])
                                             ->where('type', 'email_password')
                                             ->get()
                                             ->getRowArray();
                    $order['created_by_email'] = $creatorAuthIdentity['secret'] ?? '';
                }
            }
            
            // Get contact data (contact_id field) - buscar en tabla users
            if (isset($order['contact_id']) && $order['contact_id']) {
                $userModel = model('App\Models\UserModel');
                $contact = $userModel->find($order['contact_id']);
                if ($contact) {
                    $order['contact_name'] = trim(($contact['first_name'] ?? '') . ' ' . ($contact['last_name'] ?? ''));
                    $order['contact_phone'] = $contact['phone'] ?? '';
                    
                    // Get email from auth_identities table (CI4 Shield) for contact
                    $db = \Config\Database::connect();
                    $contactAuthIdentity = $db->table('auth_identities')
                                             ->where('user_id', $order['contact_id'])
                                             ->where('type', 'email_password')
                                             ->get()
                                             ->getRowArray();
                    $order['contact_email'] = $contactAuthIdentity['secret'] ?? '';
                }
            }
            
            // Ensure contact and salesperson fields are always set to avoid undefined key errors
            if (!isset($order['contact_name']) || empty(trim($order['contact_name']))) {
                $order['contact_name'] = '';
            }
            if (!isset($order['contact_phone'])) {
                $order['contact_phone'] = '';
            }
            if (!isset($order['contact_email'])) {
                $order['contact_email'] = '';
            }
            if (!isset($order['salesperson_name']) || empty(trim($order['salesperson_name']))) {
                $order['salesperson_name'] = '';
            }
            if (!isset($order['salesperson_phone'])) {
                $order['salesperson_phone'] = '';
            }
            if (!isset($order['salesperson_email'])) {
                $order['salesperson_email'] = '';
            }
            
            return $order;
            
        } catch (Exception $e) {
            log_message('error', 'Error in getOrderWithDetails: ' . $e->getMessage());
            return null;
        }
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

    /**
     * Get activities for an order
     */
    public function getOrderActivities($orderId, $limit = 10, $offset = 0)
    {
        $activityModel = new \Modules\SalesOrders\Models\OrderActivityModel();
        return $activityModel->getOrderActivities($orderId, $limit, $offset);
    }

    /**
     * Get comments for an order
     */
    public function getOrderComments($orderId, $limit = 10, $offset = 0)
    {
        $commentsModel = model('Modules\SalesOrders\Models\SalesOrderCommentModel');
        if ($commentsModel === null) {
            return [];
        }
        return $commentsModel->getCommentsWithUsers($orderId, $limit, $offset);
    }

    /**
     * Count total comments for an order
     */
    public function countOrderComments($orderId)
    {
        $commentsModel = model('Modules\SalesOrders\Models\SalesOrderCommentModel');
        if ($commentsModel === null) {
            return 0;
        }
        return $commentsModel->getCommentsCount($orderId);
    }

    /**
     * Count total activities for an order
     */
    public function countOrderActivities($orderId)
    {
        $activityModel = new \Modules\SalesOrders\Models\OrderActivityModel();
        return $activityModel->countOrderActivities($orderId);
    }

} 