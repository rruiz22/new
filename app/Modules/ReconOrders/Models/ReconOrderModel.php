<?php

namespace Modules\ReconOrders\Models;

use CodeIgniter\Model;

class ReconOrderModel extends Model
{
    protected $table = 'recon_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_number', 'client_id', 'stock', 'vin_number', 'vehicle', 'service_id', 'service_date', 'services', 'pictures',
        'status', 'notes', 'internal_notes', 'assigned_to',
        'short_url', 'short_url_slug', 'lima_link_id', 'qr_generated_at',
        'created_by', 'updated_by', 'deleted_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'order_number' => 'permit_empty|max_length[50]|is_unique[recon_orders.order_number,id,{id}]',
        'client_id' => 'required|integer',
        'vehicle' => 'required|max_length[255]',
        'stock' => 'required|max_length[100]',
        'vin_number' => 'required|max_length[17]',
        'service_id' => 'required|integer',
        'status' => 'permit_empty|in_list[pending,in_progress,completed,cancelled]',
        'pictures' => 'permit_empty|in_list[0,1]',
        'created_by' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'order_number' => [
            'required' => 'Order number is required',
            'is_unique' => 'Order number already exists'
        ],
        'client_id' => [
            'required' => 'Client is required'
        ],
        'vehicle' => [
            'required' => 'Vehicle information is required'
        ],
        'stock' => [
            'required' => 'Stock is required'
        ],
        'vin_number' => [
            'required' => 'VIN number is required',
            'max_length' => 'VIN number cannot exceed 17 characters'
        ],
        'service_id' => [
            'required' => 'Service is required'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data)
    {
        $data = $this->generateOrderNumber($data);
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['updated_by'])) {
            $data['data']['updated_by'] = session()->get('user_id');
        }
        return $data;
    }

    protected function generateOrderNumber($data)
    {
        if (empty($data['data']['order_number'])) {
            $prefix = 'RO-';
            $attempts = 0;
            $maxAttempts = 5;
            
            while ($attempts < $maxAttempts) {
                // Get the last order number with a lock for race condition protection
                $db = \Config\Database::connect();
                $db->transBegin();
                
                try {
                    $lastOrder = $this->select('order_number')
                                     ->where('order_number LIKE', $prefix . '%')
                                     ->orderBy('id', 'DESC')
                                     ->first();

                    if ($lastOrder) {
                        // Extract the number part after RO-
                        $lastNumber = intval(substr($lastOrder['order_number'], 3));
                        $newNumber = $lastNumber + 1;
                    } else {
                        $newNumber = 1;
                    }

                    $proposedOrderNumber = $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
                    
                    // Check if this number already exists
                    $existing = $this->where('order_number', $proposedOrderNumber)->first();
                    
                    if (!$existing) {
                        $data['data']['order_number'] = $proposedOrderNumber;
                        $db->transCommit();
                        break;
                    }
                    
                    $db->transRollback();
                    $attempts++;
                    
                } catch (\Exception $e) {
                    $db->transRollback();
                    $attempts++;
                    
                    if ($attempts >= $maxAttempts) {
                        // Fallback to timestamp-based order number
                        $data['data']['order_number'] = $prefix . date('YmdHis') . '-' . mt_rand(100, 999);
                        break;
                    }
                }
            }
        }
        
        return $data;
    }

    /**
     * Get orders with client details
     */
    public function getOrdersWithDetails($filters = [])
    {
        $builder = $this->db->table($this->table)
            ->select('recon_orders.*, clients.name as client_name, recon_services.name as service_name')
            ->join('clients', 'clients.id = recon_orders.client_id', 'left')
            ->join('recon_services', 'recon_services.id = recon_orders.service_id', 'left')
            ->where('recon_orders.deleted_at IS NULL');

        if (!empty($filters['status'])) {
            $builder->where('recon_orders.status', $filters['status']);
        }

        if (!empty($filters['client_id'])) {
            $builder->where('recon_orders.client_id', $filters['client_id']);
        }

        if (!empty($filters['date'])) {
            $builder->where('DATE(recon_orders.created_at)', $filters['date']);
        }

        return $builder->orderBy('recon_orders.created_at', 'DESC');
    }

    /**
     * Get single order with client details
     */
    public function getOrderWithDetails($id)
    {
        return $this->db->table($this->table)
            ->select('recon_orders.*, clients.name as client_name, clients.email as client_email, 
                     clients.phone as client_phone, recon_services.name as service_name')
            ->join('clients', 'clients.id = recon_orders.client_id', 'left')
            ->join('recon_services', 'recon_services.id = recon_orders.service_id', 'left')
            ->where('recon_orders.id', $id)
            ->get()
            ->getFirstRow('array');
    }

    /**
     * Get today's orders
     */
    public function getTodayOrders()
    {
        return $this->getOrdersWithDetails(['date' => date('Y-m-d')]);
    }

    /**
     * Get dashboard stats
     */
    public function getDashboardStats()
    {
        $today = date('Y-m-d');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');

        // Basic counts
        $stats = [
            'today' => $this->where('DATE(created_at)', $today)->countAllResults(),
            'pending' => $this->where('status', 'pending')->countAllResults(),
            'this_week' => $this->where('DATE(created_at) >=', $weekStart)->where('DATE(created_at) <=', $weekEnd)->countAllResults(),
            'total' => $this->countAllResults()
        ];

        // Status statistics
        $stats['completed'] = $this->where('status', 'completed')->countAllResults();
        $stats['cancelled'] = $this->where('status', 'cancelled')->countAllResults();
        $stats['in_progress'] = $this->where('status', 'in_progress')->countAllResults();

        // Pictures statistics
        $stats['with_pictures'] = $this->where('pictures', 1)->countAllResults();
        $stats['without_pictures'] = $this->where('pictures', 0)->countAllResults();

        return $stats;
    }

    /**
     * Get orders by status for chart
     */
    public function getOrdersByStatus()
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
                  ->select('status, COUNT(*) as count')
                  ->where('deleted_at IS NULL')
                  ->groupBy('status')
                  ->get()
                  ->getResultArray();
    }

    /**
     * Get daily orders for the last 7 days
     */
    public function getDailyOrdersLast7Days()
    {
        $db = \Config\Database::connect();
        $result = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $count = $this->where('DATE(created_at)', $date)->countAllResults();
            $result[] = [
                'date' => $date,
                'count' => $count,
                'formatted_date' => date('M d', strtotime($date))
            ];
        }
        
        return $result;
    }

    /**
     * Update order status
     */
    public function updateStatus($id, $status, $userId = null)
    {
        $data = [
            'status' => $status,
            'updated_by' => $userId ?: session()->get('user_id')
        ];

        return $this->update($id, $data);
    }

    /**
     * Update pictures status
     */
    public function updatePicturesStatus($id, $pictures, $userId = null)
    {
        $data = [
            'pictures' => $pictures,
            'updated_by' => $userId ?: session()->get('user_id')
        ];

        return $this->update($id, $data);
    }

    /**
     * Get all active orders (not deleted)
     */
    public function getAllActiveOrders()
    {
        return $this->getOrdersWithDetails();
    }

    /**
     * Get recent orders for dashboard
     */
    public function getRecentOrders($limit = 10)
    {
        return $this->db->table($this->table)
            ->select('recon_orders.*, clients.name as client_name, recon_services.name as service_name')
            ->join('clients', 'clients.id = recon_orders.client_id', 'left')
            ->join('recon_services', 'recon_services.id = recon_orders.service_id', 'left')
            ->where('recon_orders.deleted_at IS NULL')
            ->orderBy('recon_orders.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get deleted orders
     */
    public function getDeletedOrders()
    {
        return $this->db->table($this->table)
            ->select('recon_orders.*, 
                     clients.name as client_name, 
                     recon_services.name as service_name,
                     recon_orders.created_at as date,
                     CONCAT(COALESCE(users.first_name, ""), " ", COALESCE(users.last_name, "")) as deleted_by_name')
            ->join('clients', 'clients.id = recon_orders.client_id', 'left')
            ->join('recon_services', 'recon_services.id = recon_orders.service_id', 'left')
            ->join('users', 'users.id = recon_orders.deleted_by', 'left')
            ->where('recon_orders.deleted_at IS NOT NULL')
            ->orderBy('recon_orders.deleted_at', 'DESC');
    }
} 