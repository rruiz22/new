<?php

namespace Modules\SalesOrders\Services;

use Modules\SalesOrders\Models\SalesOrderModel;
use Modules\SalesOrders\Models\SalesOrderServiceModel;
use Modules\SalesOrders\Models\OrderActivityModel;
use Modules\SalesOrders\Models\SalesOrderCommentModel;
use App\Models\ClientModel;
use App\Models\UserModel;
use CodeIgniter\Database\BaseBuilder;

/**
 * Sales Order Service - Business Logic Layer
 * Centralizes all business logic for Sales Orders
 */
class SalesOrderService
{
    protected $salesOrderModel;
    protected $serviceModel;
    protected $activityModel;
    protected $commentModel;
    protected $clientModel;
    protected $userModel;
    protected $queryService;
    protected $db;

    public function __construct()
    {
        $this->salesOrderModel = new SalesOrderModel();
        $this->serviceModel = new SalesOrderServiceModel();
        $this->activityModel = new OrderActivityModel();
        $this->commentModel = new SalesOrderCommentModel();
        $this->clientModel = new ClientModel();
        $this->userModel = new UserModel();
        $this->queryService = new SalesOrderQueryService();
        $this->db = \Config\Database::connect();
    }

    /**
     * Get dashboard metrics
     */
    public function getDashboardMetrics(): array
    {
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        
        return [
            'today_count' => $this->queryService->getOrdersCountByDate($today),
            'tomorrow_count' => $this->queryService->getOrdersCountByDate($tomorrow),
            'pending_count' => $this->queryService->getOrdersCountByStatus(['pending', 'processing']),
            'total_count' => $this->queryService->getTotalOrdersCount(),
            'week_count' => $this->queryService->getWeekOrdersCount()
        ];
    }

    /**
     * Get orders for specific date range and filters
     */
    public function getOrdersForDataTable(array $params): array
    {
        // DataTables parameters
        $draw = intval($params['draw'] ?? 1);
        $start = intval($params['start'] ?? 0);
        $length = intval($params['length'] ?? 10);
        $search = $params['search']['value'] ?? '';

        // Custom filters
        $filters = [
            'client_id' => $params['client_filter'] ?? null,
            'contact_id' => $params['contact_filter'] ?? null,
            'status' => $params['status_filter'] ?? null,
            'date_from' => $params['date_from_filter'] ?? null,
            'date_to' => $params['date_to_filter'] ?? null
        ];

        // Get filtered orders
        $result = $this->queryService->getFilteredOrders($filters, $start, $length, $search);
        
        // Format for DataTables
        $data = [];
        foreach ($result['orders'] as $order) {
            $data[] = $this->formatOrderForDataTable($order);
        }

        return [
            'draw' => $draw,
            'recordsTotal' => $result['total'],
            'recordsFiltered' => $result['filtered'],
            'data' => $data
        ];
    }

    /**
     * Get orders by type (today, tomorrow, pending, week, all)
     */
    public function getOrdersByType(string $type, array $additionalFilters = []): array
    {
        $filters = $additionalFilters;
        
        switch ($type) {
            case 'today':
                $filters['date_from'] = $filters['date_to'] = date('Y-m-d');
                break;
            case 'tomorrow':
                $tomorrow = date('Y-m-d', strtotime('+1 day'));
                $filters['date_from'] = $filters['date_to'] = $tomorrow;
                break;
            case 'pending':
                $filters['status'] = 'pending,processing';
                break;
            case 'week':
                $filters['date_from'] = date('Y-m-d', strtotime('monday this week'));
                $filters['date_to'] = date('Y-m-d', strtotime('sunday this week'));
                break;
            // 'all' requires no additional filters
        }

        return $this->queryService->getFilteredOrders($filters);
    }

    /**
     * Create a new sales order
     */
    public function createOrder(array $data): array
    {
        try {
            // Validate required fields
            if (!$this->validateOrderData($data)) {
                return ['success' => false, 'message' => 'Invalid order data'];
            }

            // Start transaction
            $db = \Config\Database::connect();
            $db->transStart();

            // Create order
            $orderData = $this->prepareOrderData($data);
            $orderId = $this->salesOrderModel->insert($orderData);

            if (!$orderId) {
                $db->transRollback();
                return ['success' => false, 'message' => 'Failed to create order'];
            }

            // Log activity
            $this->logOrderActivity($orderId, 'created', 'Order created');

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return ['success' => false, 'message' => 'Transaction failed'];
            }

            return ['success' => true, 'order_id' => $orderId];

        } catch (\Exception $e) {
            log_message('error', 'Error creating sales order: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Internal error'];
        }
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(int $orderId, string $newStatus, string $notes = ''): array
    {
        try {
            $order = $this->salesOrderModel->find($orderId);
            if (!$order) {
                return ['success' => false, 'message' => 'Order not found'];
            }

            $oldStatus = $order['status'];
            
            // Update status
            $updated = $this->salesOrderModel->update($orderId, ['status' => $newStatus]);
            
            if ($updated) {
                // Log activity
                $activity = "Status changed from {$oldStatus} to {$newStatus}";
                if ($notes) {
                    $activity .= " - Notes: {$notes}";
                }
                $this->logOrderActivity($orderId, 'status_changed', $activity);
                
                return ['success' => true];
            }

            return ['success' => false, 'message' => 'Failed to update status'];

        } catch (\Exception $e) {
            log_message('error', 'Error updating order status: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Internal error'];
        }
    }

    /**
     * Get form data for creating/editing orders
     */
    public function getFormData(): array
    {
        return [
            'clients' => $this->clientModel->getActiveClients(),
            'contacts' => $this->getActiveContacts(),
            'services' => $this->getActiveServices()
        ];
    }

    /**
     * Get duplicate orders info
     */
    public function getDuplicateInfo(array $orders): array
    {
        $duplicateInfo = [];
        
        foreach ($orders as $order) {
            $duplicates = [];
            
            // Check for stock duplicates
            if (!empty($order['stock_duplicates'])) {
                $duplicates['stock'] = $order['stock_duplicates'];
            }
            
            // Check for client duplicates  
            if (!empty($order['client_duplicates'])) {
                $duplicates['client'] = $order['client_duplicates'];
            }
            
            // Check for VIN duplicates
            if (!empty($order['vin_duplicates'])) {
                $duplicates['vin'] = $order['vin_duplicates'];
            }
            
            if (!empty($duplicates)) {
                $duplicateInfo[$order['id']] = $duplicates;
            }
        }
        
        return $duplicateInfo;
    }

    // PRIVATE HELPER METHODS

    private function validateOrderData(array $data): bool
    {
        $required = ['client_id', 'service_id', 'date'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }

    private function prepareOrderData(array $data): array
    {
        return [
            'client_id' => $data['client_id'],
            'contact_id' => $data['contact_id'] ?? null,
            'service_id' => $data['service_id'],
            'date' => $data['date'],
            'time' => $data['time'] ?? null,
            'stock' => $data['stock'] ?? null,
            'vin' => $data['vin'] ?? null,
            'vehicle' => $data['vehicle'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'notes' => $data['notes'] ?? null,
            'instructions' => $data['instructions'] ?? null,
            'created_by' => auth()->id(),
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    private function logOrderActivity(int $orderId, string $activityType, string $description): void
    {
        $this->activityModel->insert([
            'order_id' => $orderId,
            'activity_type' => $activityType,
            'description' => $description,
            'created_by' => auth()->id(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function getActiveContacts(): array
    {
        return $this->userModel->select('users.id, users.first_name, users.last_name, users.client_id, CONCAT(users.first_name, " ", users.last_name) as name')
            ->where('users.user_type', 'client')
            ->where('users.active', 1)
            ->orderBy('users.first_name', 'ASC')
            ->findAll();
    }

    private function getActiveServices(): array
    {
        return $this->serviceModel->where('service_status', 'active')
            ->where('show_in_orders', 1)
            ->orderBy('service_name', 'ASC')
            ->findAll();
    }

    private function formatOrderForDataTable(array $order): array
    {
        return [
            'id' => $order['id'],
            'order_number' => 'SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
            'stock' => $order['stock'] ?? 'N/A',
            'client_name' => $order['client_name'] ?? 'N/A',
            'contact_name' => $order['salesperson_name'] ?? 'N/A',
            'service_name' => $order['service_name'] ?? 'N/A',
            'date' => $order['date'],
            'time' => $order['time'] ?? 'N/A',
            'status' => $order['status'],
            'vehicle' => $order['vehicle'] ?? 'N/A',
            'vin' => $order['vin'] ?? 'N/A',
            'comments_count' => $order['comments_count'] ?? 0,
            'notes_count' => $order['internal_notes_count'] ?? 0,
            'duplicates' => [
                'stock' => $order['stock_duplicates'] ?? 0,
                'client' => $order['client_duplicates'] ?? 0,
                'vin' => $order['vin_duplicates'] ?? 0
            ]
        ];
    }

    /**
     * Update existing order
     */
    public function updateOrder(int $id, array $data): array
    {
        try {
            if (!$this->validateOrderData($data)) {
                return ['success' => false, 'message' => 'Invalid order data'];
            }

            $order = $this->salesOrderModel->find($id);
            if (!$order) {
                return ['success' => false, 'message' => 'Order not found'];
            }

            // Start transaction
            $db = \Config\Database::connect();
            $db->transStart();

            // Update order
            $orderData = $this->prepareOrderData($data);
            $orderData['updated_by'] = auth()->id();
            
            $updated = $this->salesOrderModel->update($id, $orderData);

            if (!$updated) {
                $db->transRollback();
                return ['success' => false, 'message' => 'Failed to update order'];
            }

            // Log activity
            $this->logOrderActivity($id, 'updated', 'Order updated');

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return ['success' => false, 'message' => 'Transaction failed'];
            }

            return ['success' => true, 'order_id' => $id];

        } catch (\Exception $e) {
            log_message('error', 'Error updating sales order: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Internal error'];
        }
    }

    /**
     * Log activity (public method for controller access)
     */
    public function logActivity(int $orderId, string $activityType, string $description): void
    {
        $this->logOrderActivity($orderId, $activityType, $description);
    }

    /**
     * Export orders to various formats
     */
    public function exportOrders(array $filters, string $format = 'excel'): array
    {
        try {
            // Get orders based on filters
            $orders = $this->queryService->getFilteredOrders($filters);
            
            if (empty($orders['orders'])) {
                return ['success' => false, 'message' => 'No orders found to export'];
            }

            // Generate filename
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "sales_orders_export_{$timestamp}.{$format}";
            $filepath = WRITEPATH . "uploads/exports/{$filename}";

            // Ensure directory exists
            $dir = dirname($filepath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            if ($format === 'csv') {
                $this->exportToCsv($orders['orders'], $filepath);
            } else {
                // Default to Excel-like format (CSV for now)
                $this->exportToCsv($orders['orders'], $filepath);
            }

            return [
                'success' => true,
                'file_path' => $filepath,
                'filename' => $filename
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error exporting orders: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Export failed'];
        }
    }

    /**
     * Export orders to CSV format
     */
    private function exportToCsv(array $orders, string $filepath): void
    {
        $handle = fopen($filepath, 'w');
        
        // CSV Headers
        $headers = [
            'Order ID', 'Order Number', 'Client', 'Contact', 'Stock', 'VIN', 
            'Vehicle', 'Service', 'Date', 'Time', 'Status', 'Created At'
        ];
        fputcsv($handle, $headers);

        // CSV Data
        foreach ($orders as $order) {
            $row = [
                $order['id'],
                'SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                $order['client_name'] ?? '',
                $order['salesperson_name'] ?? '',
                $order['stock'] ?? '',
                $order['vin'] ?? '',
                $order['vehicle'] ?? '',
                $order['service_name'] ?? '',
                $order['date'],
                $order['time'] ?? '',
                $order['status'],
                $order['created_at']
            ];
            fputcsv($handle, $row);
        }

        fclose($handle);
    }

    /**
     * Get performance metrics for dashboard
     */
    public function getPerformanceMetrics(): array
    {
        $today = date('Y-m-d');
        $startOfMonth = date('Y-m-01');
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        
        // Get completion rate by status
        $totalOrders = $this->queryService->getTotalOrdersCount();
        $completedCount = $this->queryService->getOrdersCountByStatus(['completed', 'delivered']);
        $pendingCount = $this->queryService->getOrdersCountByStatus(['pending', 'processing']);
        
        $completionRate = $totalOrders > 0 ? round(($completedCount / $totalOrders) * 100, 1) : 0;
        
        // Get weekly performance
        $weeklyTotal = $this->queryService->getWeekOrdersCount();
        $monthlyTotal = $this->db->table('sales_orders')
            ->where('deleted', 0)
            ->where('date >=', $startOfMonth)
            ->countAllResults();
            
        return [
            'total_orders' => $totalOrders,
            'completed_orders' => $completedCount,
            'pending_orders' => $pendingCount,
            'completion_rate' => $completionRate,
            'weekly_orders' => $weeklyTotal,
            'monthly_orders' => $monthlyTotal,
            'average_daily' => $weeklyTotal > 0 ? round($weeklyTotal / 7, 1) : 0
        ];
    }
}