<?php

namespace Modules\ServiceOrders\Models;

use CodeIgniter\Model;

class ServiceOrderStatusHistoryModel extends Model
{
    protected $table = 'service_orders_services_history';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id',
        'status',
        'status_changed_by',
        'status_changed_at',
        'reason'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = '';
    protected $updatedField = '';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|numeric',
        'status' => 'required',
        'status_changed_by' => 'required|numeric'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'The order ID is required.',
            'numeric' => 'The order ID must be a valid number.'
        ],
        'status' => [
            'required' => 'The status is required.'
        ],
        'status_changed_by' => [
            'required' => 'The user who changed the status is required.',
            'numeric' => 'The user ID must be a valid number.'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setStatusChangedAt'];

    protected function setStatusChangedAt(array $data)
    {
        $data['data']['status_changed_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    // Log status change
    public function logStatusChange($orderId, $status, $changedBy, $reason = null)
    {
        $data = [
            'order_id' => $orderId,
            'status' => $status,
            'status_changed_by' => $changedBy,
            'reason' => $reason
        ];

        return $this->insert($data);
    }

    // Get status history for an order
    public function getStatusHistory($orderId)
    {
        return $this->select('service_orders_services_history.*,
                            CONCAT(users.first_name, " ", users.last_name) as changed_by_name')
                    ->join('users', 'users.id = service_orders_services_history.status_changed_by', 'left')
                    ->where('service_orders_services_history.order_id', $orderId)
                    ->orderBy('service_orders_services_history.status_changed_at', 'DESC')
                    ->findAll();
    }

    // Get latest status change for an order
    public function getLatestStatusChange($orderId)
    {
        return $this->select('service_orders_services_history.*,
                            CONCAT(users.first_name, " ", users.last_name) as changed_by_name')
                    ->join('users', 'users.id = service_orders_services_history.status_changed_by', 'left')
                    ->where('service_orders_services_history.order_id', $orderId)
                    ->orderBy('service_orders_services_history.status_changed_at', 'DESC')
                    ->first();
    }
} 