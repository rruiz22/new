<?php

namespace Modules\CarWash\Models;

use CodeIgniter\Model;

class CarWashOrderModel extends Model
{
    protected $table = 'car_wash_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_number', 'client_id', 'contact_id', 'tag_stock', 'vin_number', 'vehicle', 
        'vehicle_make', 'vehicle_model', 'vehicle_year', 'vehicle_color', 'license_plate',
        'service_type', 'service_id', 'date', 'time', 'estimated_duration', 'price', 
        'status', 'priority', 'notes', 'internal_notes', 'assigned_to', 'po_number', 
        'qr_code', 'short_url', 'short_url_slug', 'lima_link_id', 'qr_generated_at', 
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
        'order_number' => 'permit_empty|max_length[50]|is_unique[car_wash_orders.order_number,id,{id}]',
        'client_id' => 'required|integer',
        'vehicle' => 'permit_empty|max_length[255]',
        'vehicle_make' => 'permit_empty|max_length[100]',
        'vehicle_model' => 'permit_empty|max_length[100]',
        'service_id' => 'permit_empty|integer',
        'status' => 'permit_empty|in_list[pending,confirmed,in_progress,completed,cancelled]',
        'priority' => 'permit_empty|in_list[normal,waiter]',
        'tag_stock' => 'permit_empty|max_length[100]',
        'vin_number' => 'permit_empty|max_length[17]',
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
        'vin_number' => [
            'max_length' => 'VIN number cannot exceed 17 characters'
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
            $prefix = 'CW-';
            
            // Get the last order number
            $lastOrder = $this->select('order_number')
                             ->where('order_number LIKE', $prefix . '%')
                             ->orderBy('id', 'DESC')
                             ->first();

            if ($lastOrder) {
                // Extract the number part after CW-
                $lastNumber = intval(substr($lastOrder['order_number'], 3));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $data['data']['order_number'] = $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        }
        
        return $data;
    }

    /**
     * Get orders with client and service details
     */
    public function getOrdersWithDetails($filters = [])
    {
        $builder = $this->db->table($this->table)
            ->select('car_wash_orders.*, clients.name as client_name, car_wash_services.name as service_name, 
                     car_wash_services.price as service_price, car_wash_services.color as service_color')
        ->join('clients', 'clients.id = car_wash_orders.client_id', 'left')
        ->join('car_wash_services', 'car_wash_services.id = car_wash_orders.service_id', 'left')
            ->where('car_wash_orders.deleted_at IS NULL');

        if (!empty($filters['status'])) {
            $builder->where('car_wash_orders.status', $filters['status']);
        }

        if (!empty($filters['client_id'])) {
            $builder->where('car_wash_orders.client_id', $filters['client_id']);
        }

        if (!empty($filters['date'])) {
            $builder->where('car_wash_orders.date', $filters['date']);
        }

        return $builder->orderBy('car_wash_orders.created_at', 'DESC');
        }

    /**
     * Get single order with client and service details
     */
    public function getOrderWithDetails($id)
    {
        return $this->db->table($this->table)
            ->select('car_wash_orders.*, clients.name as client_name, clients.email as client_email, 
                     clients.phone as client_phone, car_wash_services.name as service_name, 
                     car_wash_services.price as service_price, car_wash_services.color as service_color,
                     car_wash_services.description as service_description')
            ->join('clients', 'clients.id = car_wash_orders.client_id', 'left')
            ->join('car_wash_services', 'car_wash_services.id = car_wash_orders.service_id', 'left')
            ->where('car_wash_orders.id', $id)
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
     * Get tomorrow's orders
     */
    public function getTomorrowOrders()
    {
        return $this->getOrdersWithDetails(['date' => date('Y-m-d', strtotime('+1 day'))]);
    }

    /**
     * Get pending orders
     */
    public function getPendingOrders()
    {
        return $this->getOrdersWithDetails(['status' => 'pending']);
    }

    /**
     * Get dashboard stats
     */
    public function getDashboardStats()
    {
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');

        // Basic counts
        $stats = [
            'today' => $this->where('date', $today)->countAllResults(),
            'tomorrow' => $this->where('date', $tomorrow)->countAllResults(),
            'pending' => $this->where('status', 'pending')->countAllResults(),
            'this_week' => $this->where('date >=', $weekStart)->where('date <=', $weekEnd)->countAllResults(),
            'total' => $this->countAllResults()
        ];

        // Status statistics
        $stats['completed'] = $this->where('status', 'completed')->countAllResults();
        $stats['cancelled'] = $this->where('status', 'cancelled')->countAllResults();
        $stats['in_progress'] = $this->where('status', 'in_progress')->countAllResults();
        $stats['confirmed'] = $this->where('status', 'confirmed')->countAllResults();

        // Time-based statistics
        $stats['this_month'] = $this->where('date >=', $monthStart)->where('date <=', $monthEnd)->countAllResults();
        $stats['yesterday'] = $this->where('date', date('Y-m-d', strtotime('-1 day')))->countAllResults();

        // Priority statistics
        $stats['normal_priority'] = $this->where('priority', 'normal')->countAllResults();
        $stats['waiter_priority'] = $this->where('priority', 'waiter')->countAllResults();

        // Revenue statistics
        $stats['revenue_today'] = $this->getRevenueForDate($today);
        $stats['revenue_this_week'] = $this->getRevenueForPeriod($weekStart, $weekEnd);
        $stats['revenue_this_month'] = $this->getRevenueForPeriod($monthStart, $monthEnd);

        return $stats;
    }

    /**
     * Get revenue for a specific date
     */
    public function getRevenueForDate($date)
    {
        $result = $this->select('SUM(COALESCE(price, 0)) as total_revenue')
                       ->where('date', $date)
                       ->where('status !=', 'cancelled')
                       ->get()
                       ->getRow();

        return $result ? (float)$result->total_revenue : 0;
    }

    /**
     * Get revenue for a date range
     */
    public function getRevenueForPeriod($startDate, $endDate)
    {
        $result = $this->select('SUM(COALESCE(price, 0)) as total_revenue')
                       ->where('date >=', $startDate)
                       ->where('date <=', $endDate)
                       ->where('status !=', 'cancelled')
                       ->get()
                       ->getRow();

        return $result ? (float)$result->total_revenue : 0;
    }

    /**
     * Get popular services statistics
     */
    public function getPopularServices($limit = 5)
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
                  ->select('car_wash_services.name as service_name, COUNT(car_wash_orders.service_id) as usage_count, car_wash_services.color')
                  ->join('car_wash_services', 'car_wash_services.id = car_wash_orders.service_id', 'left')
                  ->where('car_wash_orders.deleted_at IS NULL')
                  ->where('car_wash_orders.service_id IS NOT NULL')
                  ->groupBy('car_wash_orders.service_id')
                  ->orderBy('usage_count', 'DESC')
                  ->limit($limit)
                  ->get()
                  ->getResultArray();
    }

    /**
     * Get top clients statistics
     */
    public function getTopClients($limit = 5)
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
                  ->select('clients.name as client_name, COUNT(car_wash_orders.client_id) as order_count')
                  ->join('clients', 'clients.id = car_wash_orders.client_id', 'left')
                  ->where('car_wash_orders.deleted_at IS NULL')
                  ->groupBy('car_wash_orders.client_id')
                  ->orderBy('order_count', 'DESC')
                  ->limit($limit)
                  ->get()
                  ->getResultArray();
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
     * Get orders by priority for chart
     */
    public function getOrdersByPriority()
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
                  ->select('priority, COUNT(*) as count')
                  ->where('deleted_at IS NULL')
                  ->groupBy('priority')
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
            $count = $this->where('date', $date)->countAllResults();
            $result[] = [
                'date' => $date,
                'count' => $count,
                'formatted_date' => date('M d', strtotime($date))
            ];
        }
        
        return $result;
    }

    /**
     * Get filtered dashboard stats
     */
    public function getFilteredDashboardStats($filters = [])
    {
        $builder = $this->where('deleted_at IS NULL');
        
        // Apply filters
        if (!empty($filters['client_filter'])) {
            $builder->where('client_id', $filters['client_filter']);
        }
        
        if (!empty($filters['status_filter'])) {
            $builder->where('status', $filters['status_filter']);
        }
        
        if (!empty($filters['service_filter'])) {
            $builder->where('service_id', $filters['service_filter']);
        }
        
        if (!empty($filters['date_from_filter'])) {
            $builder->where('date >=', $filters['date_from_filter']);
        }
        
        if (!empty($filters['date_to_filter'])) {
            $builder->where('date <=', $filters['date_to_filter']);
        }

        // Get filtered counts
        $total = $builder->countAllResults(false);
        $pending = $builder->where('status', 'pending')->countAllResults(false);
        $completed = $builder->where('status', 'completed')->countAllResults(false);
        $cancelled = $builder->where('status', 'cancelled')->countAllResults(false);
        $in_progress = $builder->where('status', 'in_progress')->countAllResults(false);

        // Get filtered revenue
        $revenueBuilder = $this->where('deleted_at IS NULL')->where('status !=', 'cancelled');
        if (!empty($filters['client_filter'])) {
            $revenueBuilder->where('client_id', $filters['client_filter']);
        }
        if (!empty($filters['service_filter'])) {
            $revenueBuilder->where('service_id', $filters['service_filter']);
        }
        if (!empty($filters['date_from_filter'])) {
            $revenueBuilder->where('date >=', $filters['date_from_filter']);
        }
        if (!empty($filters['date_to_filter'])) {
            $revenueBuilder->where('date <=', $filters['date_to_filter']);
        }

        $revenue = $revenueBuilder->select('SUM(COALESCE(price, 0)) as total_revenue')->get()->getRow();
        $totalRevenue = $revenue ? (float)$revenue->total_revenue : 0;

        return [
            'total' => $total,
            'pending' => $pending,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'in_progress' => $in_progress,
            'revenue' => $totalRevenue,
            'filtered' => true
        ];
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
     * Get week orders
     */
    public function getWeekOrders()
    {
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));

        return $this->getOrdersWithDetails()
            ->where('car_wash_orders.date >=', $weekStart)
            ->where('car_wash_orders.date <=', $weekEnd);
    }

    /**
     * Get all active orders (not deleted)
     */
    public function getAllActiveOrders()
    {
        return $this->getOrdersWithDetails();
    }

    /**
     * Get deleted orders
     */
    public function getDeletedOrders()
    {
        return $this->db->table($this->table)
            ->select('car_wash_orders.*, 
                     clients.name as client_name, 
                     car_wash_services.name as service_name,
                     car_wash_services.price as service_price,
                     car_wash_orders.created_at as date,
                     CONCAT(COALESCE(users.first_name, ""), " ", COALESCE(users.last_name, "")) as deleted_by_name')
            ->join('clients', 'clients.id = car_wash_orders.client_id', 'left')
            ->join('car_wash_services', 'car_wash_services.id = car_wash_orders.service_id', 'left')
            ->join('users', 'users.id = car_wash_orders.deleted_by', 'left')
            ->where('car_wash_orders.deleted_at IS NOT NULL')
            ->orderBy('car_wash_orders.deleted_at', 'DESC');
    }
} 