<?php

namespace Modules\SalesOrders\Services;

use CodeIgniter\Database\BaseBuilder;

/**
 * Sales Order Query Service - Database Query Layer
 * Optimizes and centralizes all database queries for Sales Orders
 */
class SalesOrderQueryService
{
    protected $db;
    protected $builder;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Get optimized base query for sales orders with all necessary JOINs
     */
    protected function getBaseQuery(): BaseBuilder
    {
        return $this->db->table('sales_orders')
            ->select('sales_orders.*, 
                      clients.name as client_name,
                      CONCAT(COALESCE(contact_user.first_name, ""), " ", COALESCE(contact_user.last_name, "")) as contact_name,
                      CONCAT(COALESCE(creator_user.first_name, ""), " ", COALESCE(creator_user.last_name, "")) as salesperson_name,
                      sales_orders_services.service_name,
                      sales_orders_services.service_price')
            ->join('clients', 'clients.id = sales_orders.client_id', 'left')
            ->join('users as contact_user', 'contact_user.id = sales_orders.contact_id', 'left')
            ->join('users as creator_user', 'creator_user.id = sales_orders.created_by', 'left')
            ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
            ->where('sales_orders.deleted', 0);
    }

    /**
     * Get enhanced query with counts and duplicate detection
     */
    protected function getEnhancedQuery(): BaseBuilder
    {
        return $this->db->table('sales_orders')
            ->select('sales_orders.*, 
                      clients.name as client_name,
                      CONCAT(COALESCE(contact_user.first_name, ""), " ", COALESCE(contact_user.last_name, "")) as contact_name,
                      CONCAT(COALESCE(creator_user.first_name, ""), " ", COALESCE(creator_user.last_name, "")) as salesperson_name,
                      sales_orders_services.service_name,
                      sales_orders_services.service_price,
                      COALESCE(comments_count.comment_count, 0) as comments_count,
                      COALESCE(internal_notes_count.notes_count, 0) as internal_notes_count,
                      COALESCE(stock_duplicates.stock_count, 0) as stock_duplicates,
                      COALESCE(client_duplicates.client_count, 0) as client_duplicates,
                      COALESCE(vin_duplicates.vin_count, 0) as vin_duplicates')
            ->join('clients', 'clients.id = sales_orders.client_id', 'left')
            ->join('users as contact_user', 'contact_user.id = sales_orders.contact_id', 'left')
            ->join('users as creator_user', 'creator_user.id = sales_orders.created_by', 'left')
            ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
            ->join('(SELECT order_id, COUNT(*) as comment_count FROM sales_orders_comments GROUP BY order_id) as comments_count', 
                    'comments_count.order_id = sales_orders.id', 'left')
            ->join('(SELECT order_id, COUNT(*) as notes_count FROM internal_notes WHERE deleted_at IS NULL GROUP BY order_id) as internal_notes_count', 
                    'internal_notes_count.order_id = sales_orders.id', 'left')
            ->join('(SELECT stock, COUNT(*) - 1 as stock_count FROM sales_orders WHERE deleted = 0 AND stock IS NOT NULL AND stock != "" GROUP BY stock HAVING COUNT(*) > 1) as stock_duplicates',
                    'stock_duplicates.stock = sales_orders.stock', 'left')
            ->join('(SELECT client_id, COUNT(*) - 1 as client_count FROM sales_orders WHERE deleted = 0 GROUP BY client_id HAVING COUNT(*) > 1) as client_duplicates',
                    'client_duplicates.client_id = sales_orders.client_id', 'left')
            ->join('(SELECT vin, COUNT(*) - 1 as vin_count FROM sales_orders WHERE deleted = 0 AND vin IS NOT NULL AND vin != "" GROUP BY vin HAVING COUNT(*) > 1) as vin_duplicates',
                    'vin_duplicates.vin = sales_orders.vin', 'left')
            ->where('sales_orders.deleted', 0);
    }

    /**
     * Apply filters to query builder
     */
    protected function applyFilters(BaseBuilder $builder, array $filters): BaseBuilder
    {
        // Client filter
        if (!empty($filters['client_id'])) {
            $builder->where('sales_orders.client_id', $filters['client_id']);
        }

        // Contact filter
        if (!empty($filters['contact_id'])) {
            $builder->where('sales_orders.contact_id', $filters['contact_id']);
        }

        // Status filter (can be comma-separated for multiple statuses)
        if (!empty($filters['status'])) {
            if (strpos($filters['status'], ',') !== false) {
                $statusArray = array_map('trim', explode(',', $filters['status']));
                $builder->whereIn('sales_orders.status', $statusArray);
            } else {
                $builder->where('sales_orders.status', $filters['status']);
            }
        }

        // Date range filters
        if (!empty($filters['date_from'])) {
            $builder->where('sales_orders.date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('sales_orders.date <=', $filters['date_to']);
        }

        return $builder;
    }

    /**
     * Apply search to query builder
     */
    protected function applySearch(BaseBuilder $builder, string $searchValue): BaseBuilder
    {
        if (!empty($searchValue)) {
            $builder->groupStart()
                // Primary identification fields
                ->like('sales_orders.stock', $searchValue)
                ->orLike('sales_orders.vin', $searchValue)
                ->orLike('sales_orders.vehicle', $searchValue)
                
                // Order numbers - both generated and stored
                ->orLike('sales_orders.order_number', $searchValue)
                ->orLike('CONCAT("SAL-", LPAD(sales_orders.id, 5, "0"))', $searchValue, false)
                
                // Client and contact information
                ->orLike('clients.name', $searchValue)
                ->orLike('CONCAT(COALESCE(users.first_name, ""), " ", COALESCE(users.last_name, ""))', $searchValue, false)
                
                // Service information
                ->orLike('sales_orders_services.service_name', $searchValue)
                
                // Order details
                ->orLike('sales_orders.instructions', $searchValue)
                ->orLike('sales_orders.notes', $searchValue)
                ->orLike('sales_orders.status', $searchValue)
                
                // Date fields (formatted for better searchability)
                ->orLike('sales_orders.date', $searchValue)
                ->orLike('sales_orders.time', $searchValue)
                ->orLike('DATE_FORMAT(sales_orders.date, "%Y-%m-%d")', $searchValue, false)
                ->orLike('DATE_FORMAT(sales_orders.date, "%M %d, %Y")', $searchValue, false)
                
                // Additional searchable fields
                ->orLike('CAST(sales_orders.id AS CHAR)', $searchValue, false)
                
                ->groupEnd();
        }
        
        return $builder;
    }

    /**
     * Determine ordering based on filter type
     */
    protected function applyOrdering(BaseBuilder $builder, array $filters): BaseBuilder
    {
        $orderingType = $this->determineOrderingType($filters);
        
        switch ($orderingType) {
            case 'today':
            case 'tomorrow':
                return $builder->orderBy('sales_orders.time', 'ASC')
                              ->orderBy('sales_orders.created_at', 'ASC');
                              
            case 'week':
            case 'pending':
                return $builder->orderBy('sales_orders.date', 'ASC')
                              ->orderBy('sales_orders.time', 'ASC')
                              ->orderBy('sales_orders.created_at', 'ASC');
                              
            case 'all':
            default:
                return $builder->orderBy('sales_orders.id', 'DESC');
        }
    }

    /**
     * Get filtered orders with pagination
     */
    public function getFilteredOrders(array $filters = [], int $start = 0, int $length = 10, string $search = ''): array
    {
        // Get enhanced query for full data
        $builder = $this->getEnhancedQuery();
        
        // Apply filters
        $builder = $this->applyFilters($builder, $filters);
        
        // Apply search
        $builder = $this->applySearch($builder, $search);
        
        // Count filtered results before pagination
        $countBuilder = clone $builder;
        $totalFiltered = $countBuilder->countAllResults('', false);
        
        // Apply ordering and pagination
        $builder = $this->applyOrdering($builder, $filters);
        $orders = $builder->limit($length, $start)->get()->getResultArray();
        
        // Get total count without filters
        $totalRecords = $this->db->table('sales_orders')->where('deleted', 0)->countAllResults();
        
        return [
            'orders' => $orders,
            'total' => $totalRecords,
            'filtered' => $totalFiltered
        ];
    }

    /**
     * Get orders count by date
     */
    public function getOrdersCountByDate(string $date): int
    {
        return $this->db->table('sales_orders')
            ->where('deleted', 0)
            ->where('date', $date)
            ->countAllResults();
    }

    /**
     * Get orders count by status(es)
     */
    public function getOrdersCountByStatus($statuses): int
    {
        $builder = $this->db->table('sales_orders')->where('deleted', 0);
        
        if (is_array($statuses)) {
            $builder->whereIn('status', $statuses);
        } else {
            $builder->where('status', $statuses);
        }
        
        return $builder->countAllResults();
    }

    /**
     * Get total orders count
     */
    public function getTotalOrdersCount(): int
    {
        return $this->db->table('sales_orders')->where('deleted', 0)->countAllResults();
    }

    /**
     * Get current week orders count
     */
    public function getWeekOrdersCount(): int
    {
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        
        return $this->db->table('sales_orders')
            ->where('deleted', 0)
            ->where('date >=', $startOfWeek)
            ->where('date <=', $endOfWeek)
            ->countAllResults();
    }

    /**
     * Get orders for today
     */
    public function getTodayOrders(): array
    {
        return $this->getBaseQuery()
            ->where('sales_orders.date', date('Y-m-d'))
            ->orderBy('sales_orders.time', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get orders for tomorrow
     */
    public function getTomorrowOrders(): array
    {
        return $this->getBaseQuery()
            ->where('sales_orders.date', date('Y-m-d', strtotime('+1 day')))
            ->orderBy('sales_orders.time', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get pending orders
     */
    public function getPendingOrders(): array
    {
        return $this->getBaseQuery()
            ->whereIn('sales_orders.status', ['pending', 'processing'])
            ->orderBy('sales_orders.date', 'ASC')
            ->orderBy('sales_orders.time', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get week orders
     */
    public function getWeekOrders(): array
    {
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        
        return $this->getBaseQuery()
            ->where('sales_orders.date >=', $startOfWeek)
            ->where('sales_orders.date <=', $endOfWeek)
            ->orderBy('sales_orders.date', 'ASC')
            ->orderBy('sales_orders.time', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get deleted orders
     */
    public function getDeletedOrders(): array
    {
        return $this->db->table('sales_orders')
            ->select('sales_orders.*, 
                      clients.name as client_name,
                      CONCAT(COALESCE(users.first_name, ""), " ", COALESCE(users.last_name, "")) as salesperson_name,
                      sales_orders_services.service_name')
            ->join('clients', 'clients.id = sales_orders.client_id', 'left')
            ->join('users', 'users.id = sales_orders.created_by', 'left')
            ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
            ->where('sales_orders.deleted', 1)
            ->orderBy('sales_orders.updated_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Determine ordering type based on filters
     */
    protected function determineOrderingType(array $filters): string
    {
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weekEnd = date('Y-m-d', strtotime('sunday this week'));

        // Check if it's today's orders
        if (isset($filters['date_from']) && isset($filters['date_to']) && 
            $filters['date_from'] === $today && $filters['date_to'] === $today) {
            return 'today';
        }

        // Check if it's tomorrow's orders
        if (isset($filters['date_from']) && isset($filters['date_to']) && 
            $filters['date_from'] === $tomorrow && $filters['date_to'] === $tomorrow) {
            return 'tomorrow';
        }

        // Check if it's week orders
        if (isset($filters['date_from']) && isset($filters['date_to']) && 
            $filters['date_from'] === $weekStart && $filters['date_to'] === $weekEnd) {
            return 'week';
        }

        // Check if it's pending orders
        if (isset($filters['status']) && 
            (strpos($filters['status'], 'pending') !== false || strpos($filters['status'], 'processing') !== false)) {
            return 'pending';
        }

        return 'all';
    }

    /**
     * Get duplicate orders by IDs
     */
    public function getDuplicateOrders(array $orderIds): array
    {
        $duplicates = [];
        
        foreach ($orderIds as $orderId) {
            $order = $this->db->table('sales_orders')
                            ->select('*')
                            ->where('id', $orderId)
                            ->where('deleted', 0)
                            ->get()
                            ->getRowArray();
            
            if ($order) {
                $orderDuplicates = [];
                
                // Find stock duplicates
                if (!empty($order['stock'])) {
                    $stockDups = $this->db->table('sales_orders')
                                        ->select('id, stock')
                                        ->where('stock', $order['stock'])
                                        ->where('deleted', 0)
                                        ->where('id !=', $orderId)
                                        ->get()
                                        ->getResultArray();
                    if (!empty($stockDups)) {
                        $orderDuplicates['stock'] = $stockDups;
                    }
                }
                
                // Find VIN duplicates
                if (!empty($order['vin'])) {
                    $vinDups = $this->db->table('sales_orders')
                                      ->select('id, vin')
                                      ->where('vin', $order['vin'])
                                      ->where('deleted', 0)
                                      ->where('id !=', $orderId)
                                      ->get()
                                      ->getResultArray();
                    if (!empty($vinDups)) {
                        $orderDuplicates['vin'] = $vinDups;
                    }
                }
                
                // Find client duplicates for same date
                $clientDups = $this->db->table('sales_orders')
                                     ->select('id, client_id')
                                     ->where('client_id', $order['client_id'])
                                     ->where('date', $order['date'])
                                     ->where('deleted', 0)
                                     ->where('id !=', $orderId)
                                     ->get()
                                     ->getResultArray();
                if (!empty($clientDups)) {
                    $orderDuplicates['client'] = $clientDups;
                }
                
                if (!empty($orderDuplicates)) {
                    $duplicates[$orderId] = $orderDuplicates;
                }
            }
        }
        
        return $duplicates;
    }

    /**
     * Get top clients by order count
     */
    public function getTopClients(int $limit = 5): array
    {
        return $this->db->table('sales_orders')
            ->select('clients.id, clients.name, COUNT(*) as order_count')
            ->join('clients', 'clients.id = sales_orders.client_id', 'left')
            ->where('sales_orders.deleted', 0)
            ->groupBy('clients.id, clients.name')
            ->orderBy('order_count', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get recent activity from sales orders
     */
    public function getRecentActivity(int $limit = 10): array
    {
        return $this->db->table('sales_orders_activities')
            ->select('sales_orders_activities.*, 
                      sales_orders.id as order_id,
                      CONCAT("SAL-", LPAD(sales_orders.id, 5, "0")) as order_number,
                      users.username,
                      CONCAT(COALESCE(users.first_name, ""), " ", COALESCE(users.last_name, "")) as user_name')
            ->join('sales_orders', 'sales_orders.id = sales_orders_activities.order_id', 'left')
            ->join('users', 'users.id = sales_orders_activities.user_id', 'left')
            ->where('sales_orders.deleted', 0)
            ->orderBy('sales_orders_activities.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}