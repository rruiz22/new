<?php

namespace Modules\SalesOrders\Models;

use CodeIgniter\Model;

class SalesOrderStatusHistoryModel extends Model
{
    protected $table = 'sales_orders_status_history';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id',
        'from_status',
        'to_status',
        'changed_by',
        'changed_at',
        'change_type',
        'field_name',
        'old_value',
        'new_value'
    ];

    // Dates
    protected $useTimestamps = false; // Gestionaremos las fechas manualmente
    protected $dateFormat = 'datetime';
    protected $createdField = '';
    protected $updatedField = '';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|numeric',
        'to_status' => 'required',
        'changed_by' => 'required|numeric'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'The order ID is required.',
            'numeric' => 'The order ID must be a valid number.'
        ],
        'to_status' => [
            'required' => 'The new status is required.'
        ],
        'changed_by' => [
            'required' => 'The user ID is required.',
            'numeric' => 'The user ID must be a valid number.'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setChangedAt'];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function setChangedAt(array $data)
    {
        // Set changed_at timestamp if not already set
        if (!isset($data['data']['changed_at'])) {
            $data['data']['changed_at'] = date('Y-m-d H:i:s');
        }
        
        return $data;
    }

    // Record status change
    public function recordStatusChange($orderId, $fromStatus, $toStatus, $changedBy = null)
    {
        // If changedBy is not provided, get it from session
        if ($changedBy === null && session()->has('user_id')) {
            $changedBy = session()->get('user_id');
        }
        
        $data = [
            'order_id' => $orderId,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => $changedBy,
            'change_type' => 'status_change'
        ];
        
        return $this->insert($data);
    }
    
    // Record field change
    public function recordFieldChange($orderId, $fieldName, $oldValue, $newValue, $changedBy = null)
    {
        // If changedBy is not provided, get it from session
        if ($changedBy === null && session()->has('user_id')) {
            $changedBy = session()->get('user_id');
        }
        
        $data = [
            'order_id' => $orderId,
            'from_status' => null,
            'to_status' => 'modified',
            'changed_by' => $changedBy,
            'change_type' => 'field_change',
            'field_name' => $fieldName,
            'old_value' => $oldValue,
            'new_value' => $newValue
        ];
        
        return $this->insert($data);
    }
    
    // Get history for a specific order
    public function getOrderHistory($orderId)
    {
        return $this->select('sales_orders_status_history.*, users.name as changed_by_name')
                   ->join('users', 'users.id = sales_orders_status_history.changed_by', 'left')
                   ->where('sales_orders_status_history.order_id', $orderId)
                   ->orderBy('sales_orders_status_history.changed_at', 'DESC')
                   ->findAll();
    }

    public function getStatusHistoryByOrderId($orderId)
    {
        return $this->select('sales_orders_status_history.*, users.name as changed_by_name')
                    ->join('users', 'users.id = sales_orders_status_history.changed_by', 'left')
                    ->where('sales_orders_status_history.order_id', $orderId)
                    ->orderBy('sales_orders_status_history.changed_at', 'DESC')
                    ->findAll();
    }
} 