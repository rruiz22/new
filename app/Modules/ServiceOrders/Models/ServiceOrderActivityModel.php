<?php

namespace Modules\ServiceOrders\Models;

use CodeIgniter\Model;

class ServiceOrderActivityModel extends Model
{
    protected $table = 'service_orders_activity';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id',
        'user_id',
        'action',
        'description',
        'old_value',
        'new_value',
        'field_name',
        'metadata',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = false; // Gestionaremos las fechas manualmente
    protected $dateFormat = 'datetime';
    protected $createdField = '';
    protected $updatedField = '';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|numeric',
        'user_id' => 'permit_empty|numeric',
        'action' => 'required'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'The order ID is required.',
            'numeric' => 'The order ID must be a valid number.'
        ],
        'user_id' => [
            'required' => 'The user ID is required.',
            'numeric' => 'The user ID must be a valid number.'
        ],
        'action' => [
            'required' => 'The action is required.'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setCreatedAt'];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function setCreatedAt(array $data)
    {
        // Set created_at timestamp
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        
        return $data;
    }

    // Get activities for a specific order
    public function getActivitiesByOrderId($orderId, $limit = null, $offset = null)
    {
        $builder = $this->select('service_orders_activity.*, 
                                CONCAT(users.first_name, " ", users.last_name) as user_name,
                                users.avatar as user_avatar')
                    ->join('users', 'users.id = service_orders_activity.user_id', 'left')
                    ->where('service_orders_activity.order_id', $orderId)
                    ->orderBy('service_orders_activity.created_at', 'DESC');
        
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->findAll();
    }

    // Log an activity
    public function logActivity($orderId, $userId, $action, $description = null, $metadata = null, $oldValue = null, $newValue = null, $fieldName = null)
    {
        $data = [
            'order_id' => $orderId,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'field_name' => $fieldName,
            'metadata' => $metadata ? json_encode($metadata) : null
        ];

        log_message('info', "DEBUG logActivity: Attempting to insert data: " . json_encode($data));
        
        try {
            $result = $this->insert($data);
            log_message('info', "DEBUG logActivity: Insert result: " . ($result ? 'SUCCESS' : 'FAILED'));
            
            if (!$result) {
                $errors = $this->errors();
                log_message('error', "DEBUG logActivity: Validation errors: " . json_encode($errors));
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', "DEBUG logActivity: Exception: " . $e->getMessage());
            return false;
        }
    }

    // Log a field change activity (enhanced version)
    public function logFieldChange($orderId, $userId, $fieldName, $oldValue, $newValue, $description = null, $metadata = null)
    {
        log_message('info', "DEBUG logFieldChange: orderId={$orderId}, userId={$userId}, fieldName={$fieldName}");
        log_message('info', "DEBUG logFieldChange: oldValue={$oldValue}, newValue={$newValue}");
        log_message('info', "DEBUG logFieldChange: description={$description}");
        
        $result = $this->logActivity(
            $orderId,
            $userId,
            'field_change',
            $description,
            $metadata,
            $oldValue,
            $newValue,
            $fieldName
        );
        
        log_message('info', "DEBUG logFieldChange result: " . ($result ? 'SUCCESS' : 'FAILED'));
        
        return $result;
    }

    // Get recent activities across all orders
    public function getRecentActivities($limit = 10)
    {
        return $this->select('service_orders_activity.*, 
                            CONCAT(users.first_name, " ", users.last_name) as user_name,
                            users.avatar as user_avatar,
                            service_orders.id as order_number')
                    ->join('users', 'users.id = service_orders_activity.user_id', 'left')
                    ->join('service_orders', 'service_orders.id = service_orders_activity.order_id', 'left')
                    ->orderBy('service_orders_activity.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    // Get activity count for an order
    public function getActivityCount($orderId)
    {
        return $this->where('order_id', $orderId)->countAllResults();
    }
} 