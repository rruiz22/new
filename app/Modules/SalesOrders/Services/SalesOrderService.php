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
     * Get form data for creating/editing orders with auto-populate logic
     */
    public function getFormData(): array
    {
        $user = auth()->user();
        $isSuperAdmin = $this->isUserSuperAdmin($user);
        
        // Prepare default values
        $defaultClientId = $user->client_id ?? null;
        $defaultAssignedTo = ($user->user_type == 'client') ? $user->id : null;
        
        // Get available dealers based on user permissions
        $availableClients = $this->getAvailableClientsForUser($user, $isSuperAdmin);
        
        // Get all contacts for dynamic loading
        $allContacts = $this->getActiveContacts();
        
        return [
            'clients' => $availableClients,
            'contacts' => $allContacts,
            'services' => $this->getActiveServices(),
            
            // User context and defaults
            'currentUser' => $user,
            'isSuperAdmin' => $isSuperAdmin,
            'defaultClientId' => $defaultClientId,
            'defaultAssignedTo' => $defaultAssignedTo,
            
            // Form behavior flags
            'clientReadonly' => !$isSuperAdmin, // Only SuperAdmin can change client
            'autoPopulateContact' => ($user->user_type == 'client')
        ];
    }
    
    /**
     * Check if user is SuperAdmin
     */
    private function isUserSuperAdmin($user): bool
    {
        if (!$user) return false;
        
        // Check in auth_groups_users table
        $db = \Config\Database::connect();
        $isSuperAdmin = $db->table('auth_groups_users')
            ->where('user_id', $user->id)
            ->where('group', 'superadmin')
            ->countAllResults() > 0;
            
        return $isSuperAdmin;
    }
    
    /**
     * Get available clients based on user permissions
     */
    private function getAvailableClientsForUser($user, bool $isSuperAdmin): array
    {
        if ($isSuperAdmin) {
            // SuperAdmin sees all clients
            return $this->clientModel->getActiveClients();
        }
        
        // For now, all users see only their assigned client
        // TODO: In future, implement user_client_assignments for multi-dealer staff
        if ($user && $user->client_id) {
            return $this->clientModel->where('id', $user->client_id)
                                   ->where('status', 'active')
                                   ->where('deleted', 0)
                                   ->findAll();
        }
        
        return [];
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
            'salesperson_id' => $data['salesperson_id'] ?? auth()->id(),
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
        // Return associative array with both indexed and named keys for DataTables
        $orderNumber = $order['order_number'] ?: 'SAL-' . str_pad($order['id'], 4, '0', STR_PAD_LEFT);
        $stock = $order['stock'] ?: 'N/A';
        $clientName = $order['client_name'] ?: 'N/A';
        $dueDate = $order['date'] . ($order['time'] ? ' ' . $order['time'] : '');
        $status = $order['status'] ?: 'pending';
        $actions = $this->generateActionButtons($order['id'], $order['status']);
        
        return [
            // Indexed array for DataTables columns
            0 => $orderNumber,
            1 => $stock, 
            2 => $clientName,
            3 => $dueDate,
            4 => $status,
            5 => $actions,
            
            // Named keys for JavaScript access
            'id' => $order['id'],
            'order_id' => $orderNumber,
            'order_number' => $orderNumber,
            'stock' => $stock,
            'client_name' => $clientName,
            'contact_name' => $order['contact_name'] ?: 'N/A',
            'vehicle' => $order['vehicle'] ?: 'N/A',
            'vin' => $order['vin'] ?: '',
            'date' => $order['date'],
            'time' => $order['time'] ?: '',
            'due' => $dueDate,
            'status' => $status,
            'instructions' => $order['instructions'] ?: '',
            'salesperson_name' => $order['salesperson_name'] ?: 'N/A',
            'service_name' => $order['service_name'] ?: 'N/A',
            'comments_count' => $order['comments_count'] ?? 0,
            'internal_notes_count' => $order['internal_notes_count'] ?? 0
        ];
    }
    
    private function formatStatusBadge(string $status): string
    {
        $statusMap = [
            'pending' => ['class' => 'bg-warning', 'text' => 'Pending'],
            'processing' => ['class' => 'bg-info', 'text' => 'Processing'],
            'in_progress' => ['class' => 'bg-primary', 'text' => 'In Progress'],
            'completed' => ['class' => 'bg-success', 'text' => 'Completed'],
            'cancelled' => ['class' => 'bg-danger', 'text' => 'Cancelled']
        ];
        
        $config = $statusMap[$status] ?? ['class' => 'bg-secondary', 'text' => ucfirst($status)];
        return '<span class="badge ' . $config['class'] . '">' . $config['text'] . '</span>';
    }
    
    private function generateActionButtons(int $orderId, string $status): string
    {
        $buttons = [];
        
        // View button - always available
        $buttons[] = '<button class="btn btn-soft-info btn-sm btn-view" data-id="' . $orderId . '" title="View Order">
                        <i class="ri-eye-line"></i>
                      </button>';
        
        // Edit button - only for non-completed/cancelled orders
        if (!in_array($status, ['completed', 'cancelled'])) {
            $buttons[] = '<button class="btn btn-soft-primary btn-sm btn-edit" data-id="' . $orderId . '" title="Edit Order">
                            <i class="ri-pencil-line"></i>
                          </button>';
        }
        
        // Delete button - only for pending orders
        if ($status === 'pending') {
            $buttons[] = '<button class="btn btn-soft-danger btn-sm btn-delete" data-id="' . $orderId . '" title="Delete Order">
                            <i class="ri-delete-bin-line"></i>
                          </button>';
        }
        
        // More actions dropdown
        $buttons[] = '<div class="dropdown">
                        <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="printCurrentOrder(' . $orderId . ')">
                                <i class="ri-printer-line me-2"></i>Print
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="downloadCurrentOrderPDF(' . $orderId . ')">
                                <i class="ri-download-line me-2"></i>Download PDF
                            </a></li>
                        </ul>
                      </div>';
        
        return '<div class="d-flex gap-1 justify-content-center">' . implode('', $buttons) . '</div>';
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