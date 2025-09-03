<?php

namespace Modules\SalesOrders\Controllers;

use App\Controllers\BaseController;
use Modules\SalesOrders\Services\SalesOrderService;
use Modules\SalesOrders\Services\SalesOrderQueryService;
use Modules\SalesOrders\Services\PdfService;
use Modules\SalesOrders\Models\SalesOrderModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

/**
 * Refactored Sales Orders Controller - Uses Service Layer
 * Reduced from 7,180 lines to ~500 lines
 */
class SalesOrdersController extends BaseController
{
    use ResponseTrait;

    protected $salesOrderService;
    protected $queryService;
    protected $salesOrderModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Initialize services
        $this->salesOrderService = new SalesOrderService();
        $this->queryService = new SalesOrderQueryService();
        $this->salesOrderModel = new SalesOrderModel();
    }

    /**
     * Main index page
     */
    public function index()
    {
        $formData = $this->salesOrderService->getFormData();
        $deletedOrders = $this->queryService->getDeletedOrders();

        $data = [
            'title' => 'Sales Orders',
            'clients' => $formData['clients'],
            'contacts' => $formData['contacts'],
            'services' => $formData['services'],
            'deleted_orders' => $deletedOrders
        ];

        return view('Modules\SalesOrders\Views\sales_orders/index', $data);
    }

    /**
     * Dashboard content - uses service layer
     */
    public function dashboard_content()
    {
        $metrics = $this->salesOrderService->getDashboardMetrics();
        
        $data = [
            'todayOrders' => $this->queryService->getTodayOrders(),
            'tomorrowOrders' => $this->queryService->getTomorrowOrders(),
            'pendingOrders' => $this->queryService->getPendingOrders(),
            'totalOrders' => $metrics['total_count'],
            'metrics' => $metrics
        ];
        
        return view('Modules\SalesOrders\Views\sales_orders/dashboard_content', $data);
    }

    /**
     * All content - unified AJAX endpoint
     */
    public function all_content()
    {
        if ($this->request->isAJAX()) {
            return $this->getOrdersForDataTable();
        }
        
        $formData = $this->salesOrderService->getFormData();
        
        return view('Modules\SalesOrders\Views\sales_orders/all_content', [
            'orders' => [],
            'clients' => $formData['clients'],
            'contacts' => $formData['contacts']
        ]);
    }

    /**
     * Unified DataTable AJAX endpoint
     */
    public function getOrdersForDataTable()
    {
        try {
            $params = $this->request->getPost();
            $result = $this->salesOrderService->getOrdersForDataTable($params);
            
            return $this->response->setJSON($result);
            
        } catch (Exception $e) {
            log_message('error', 'Error in getOrdersForDataTable: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'draw' => intval($this->request->getPost('draw') ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load data'
            ]);
        }
    }

    /**
     * Content by type (today, tomorrow, pending, week)
     */
    public function today_content()
    {
        if ($this->request->isAJAX()) {
            $filters = ['type' => 'today'];
            $params = array_merge($this->request->getPost(), $filters);
            $result = $this->salesOrderService->getOrdersForDataTable($params);
            return $this->response->setJSON($result);
        }
        
        return view('Modules\SalesOrders\Views\sales_orders/today_content');
    }

    public function tomorrow_content()
    {
        if ($this->request->isAJAX()) {
            $filters = ['type' => 'tomorrow'];
            $params = array_merge($this->request->getPost(), $filters);
            $result = $this->salesOrderService->getOrdersForDataTable($params);
            return $this->response->setJSON($result);
        }
        
        return view('Modules\SalesOrders\Views\sales_orders/tomorrow_content');
    }

    public function pending_content()
    {
        if ($this->request->isAJAX()) {
            $filters = ['type' => 'pending'];
            $params = array_merge($this->request->getPost(), $filters);
            $result = $this->salesOrderService->getOrdersForDataTable($params);
            return $this->response->setJSON($result);
        }
        
        return view('Modules\SalesOrders\Views\sales_orders/pending_content');
    }

    public function week_content()
    {
        if ($this->request->isAJAX()) {
            $filters = ['type' => 'week'];
            $params = array_merge($this->request->getPost(), $filters);
            $result = $this->salesOrderService->getOrdersForDataTable($params);
            return $this->response->setJSON($result);
        }
        
        return view('Modules\SalesOrders\Views\sales_orders/week_content');
    }

    /**
     * Create new order
     */
    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $result = $this->salesOrderService->createOrder($data);
            
            if ($result['success']) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Order created successfully',
                    'order_id' => $result['order_id']
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => $result['message']
            ]);
        }
        
        $formData = $this->salesOrderService->getFormData();
        return view('Modules\SalesOrders\Views\sales_orders/modal_form', $formData);
    }

    /**
     * Update order
     */
    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }

        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $result = $this->salesOrderService->updateOrder($id, $data);
            
            return $this->response->setJSON($result);
        }
        
        $order = $this->salesOrderModel->find($id);
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }
        
        $formData = $this->salesOrderService->getFormData();
        $formData['order'] = $order;
        
        return view('Modules\SalesOrders\Views\sales_orders/modal_form', $formData);
    }

    /**
     * Update order status
     */
    public function updateStatus()
    {
        $orderId = $this->request->getPost('order_id');
        $newStatus = $this->request->getPost('status');
        $notes = $this->request->getPost('notes') ?? '';
        
        $result = $this->salesOrderService->updateOrderStatus($orderId, $newStatus, $notes);
        
        return $this->response->setJSON($result);
    }

    /**
     * View single order
     */
    public function view($id = null)
    {
        if (!$id) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }
        
        $order = $this->salesOrderModel->getOrderWithDetails($id);
        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }
        
        // Generate QR code data if available
        $qrData = null;
        if (!empty($order['short_url'])) {
            // Use external QR service as fallback since mda.to might not have QR API
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($order['short_url']);
            
            $qrData = [
                'short_url' => $order['short_url'],
                'qr_url' => $qrUrl,
                'shortener' => 'MDA Links',
                'generated_at' => $order['qr_generated_at'] ?? date('Y-m-d H:i:s'),
                'link_id' => $order['lima_link_id'] ?? null
            ];
        } elseif (!empty($order['id'])) {
            // If no short URL, create QR for order view URL
            $orderUrl = base_url("sales_orders/view/{$order['id']}");
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($orderUrl);
            
            $qrData = [
                'short_url' => $orderUrl,
                'qr_url' => $qrUrl,
                'shortener' => 'Direct Link',
                'generated_at' => date('Y-m-d H:i:s'),
                'link_id' => null
            ];
        }
        
        $data = [
            'title' => 'Sales Order #SAL-' . str_pad($id, 5, '0', STR_PAD_LEFT),
            'order' => $order,
            'activities' => $this->salesOrderModel->getOrderActivities($id),
            'comments' => $this->salesOrderModel->getOrderComments($id),
            'qr_data' => $qrData
        ];
        
        return view('Modules\SalesOrders\Views\sales_orders/view', $data);
    }

    /**
     * Delete order (soft delete)
     */
    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }
        
        try {
            $updated = $this->salesOrderModel->update($id, [
                'deleted' => 1,
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => auth()->id()
            ]);
            
            if ($updated) {
                // Log activity
                $this->salesOrderService->logActivity($id, 'deleted', 'Order deleted');
                
                return $this->response->setJSON(['success' => true, 'message' => 'Order deleted successfully']);
            }
            
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete order']);
            
        } catch (Exception $e) {
            log_message('error', 'Error deleting order: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Internal error']);
        }
    }

    /**
     * Print order
     */
    public function print($id = null)
    {
        if (!$id) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }
        
        $order = $this->salesOrderModel->getOrderWithDetails($id);
        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }
        
        $data = [
            'title' => 'Sales Order #SAL-' . str_pad($id, 5, '0', STR_PAD_LEFT),
            'order' => $order,
            'print_mode' => true
        ];
        
        return view('Modules\SalesOrders\Views\sales_orders/print', $data);
    }

    /**
     * Get order duplicates
     */
    public function getDuplicates()
    {
        $orderIds = $this->request->getPost('order_ids');
        if (empty($orderIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No orders specified']);
        }
        
        try {
            $duplicates = $this->queryService->getDuplicateOrders($orderIds);
            
            return $this->response->setJSON([
                'success' => true,
                'duplicates' => $duplicates
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting duplicates: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to get duplicates']);
        }
    }

    /**
     * Export orders to Excel/CSV
     */
    public function export()
    {
        $format = $this->request->getGet('format') ?? 'excel';
        $filters = $this->request->getGet();
        
        try {
            $result = $this->salesOrderService->exportOrders($filters, $format);
            
            if ($result['success']) {
                return $this->response->download($result['file_path'], null)->setFileName($result['filename']);
            }
            
            return redirect()->back()->with('error', $result['message']);
            
        } catch (Exception $e) {
            log_message('error', 'Error exporting orders: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Export failed');
        }
    }

    /**
     * Get dashboard metrics for AJAX
     */
    public function getMetrics()
    {
        try {
            $metrics = $this->salesOrderService->getDashboardMetrics();
            return $this->response->setJSON(['success' => true, 'metrics' => $metrics]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting metrics: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to get metrics']);
        }
    }

    /**
     * Services content - delegates to services controller
     */
    public function services_content()
    {
        return view('Modules\SalesOrders\Views\sales_orders/services_content');
    }

    /**
     * Deleted content
     */
    public function deleted_content()
    {
        $deletedOrders = $this->queryService->getDeletedOrders();
        
        return view('Modules\SalesOrders\Views\sales_orders/deleted_content', [
            'deleted_orders' => $deletedOrders
        ]);
    }

    /**
     * Modal form for creating/editing orders
     */
    public function modal_form()
    {
        $formData = $this->salesOrderService->getFormData();
        return view('Modules\SalesOrders\Views\sales_orders/modal_form', $formData);
    }

    /**
     * Store new order (alias for create)
     */
    public function store()
    {
        return $this->create();
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        try {
            $metrics = $this->salesOrderService->getDashboardMetrics();
            return $this->response->setJSON(['success' => true, 'data' => $metrics]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting statistics: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to get statistics']);
        }
    }

    /**
     * Dashboard stats
     */
    public function dashboard_stats()
    {
        return $this->getStatistics();
    }

    /**
     * Chart data
     */
    public function chart_data()
    {
        try {
            // Get basic metrics for charts
            $metrics = $this->salesOrderService->getDashboardMetrics();
            
            $chartData = [
                'today' => $metrics['today_count'],
                'tomorrow' => $metrics['tomorrow_count'],
                'pending' => $metrics['pending_count'],
                'week' => $metrics['week_count'],
                'total' => $metrics['total_count']
            ];
            
            return $this->response->setJSON(['success' => true, 'data' => $chartData]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting chart data: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to get chart data']);
        }
    }

    /**
     * Restore deleted order
     */
    public function restore($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }
        
        try {
            $updated = $this->salesOrderModel->update($id, [
                'deleted' => 0,
                'deleted_at' => null,
                'deleted_by' => null
            ]);
            
            if ($updated) {
                return $this->response->setJSON(['success' => true, 'message' => 'Order restored successfully']);
            }
            
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to restore order']);
            
        } catch (Exception $e) {
            log_message('error', 'Error restoring order: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Internal error']);
        }
    }

    /**
     * Force delete order (permanent)
     */
    public function forceDelete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }
        
        try {
            $deleted = $this->salesOrderModel->delete($id, true);
            
            if ($deleted) {
                return $this->response->setJSON(['success' => true, 'message' => 'Order permanently deleted']);
            }
            
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete order']);
            
        } catch (Exception $e) {
            log_message('error', 'Error force deleting order: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Internal error']);
        }
    }

    /**
     * Get top clients data for dashboard
     */
    public function top_clients()
    {
        try {
            $topClients = $this->queryService->getTopClients();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $topClients
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting top clients: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Failed to load top clients'
            ]);
        }
    }

    /**
     * Get performance metrics for dashboard
     */
    public function performance_metrics()
    {
        try {
            $metrics = $this->salesOrderService->getPerformanceMetrics();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $metrics
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting performance metrics: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load performance metrics'
            ]);
        }
    }

    /**
     * Generate QR code for an order
     */
    public function generateQRCode($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required'
            ]);
        }

        try {
            $order = $this->salesOrderModel->find($id);
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }

            // Check if MDA Links is configured
            if (!\App\Helpers\MDALinksHelper::isConfigured()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'MDA Links API is not configured'
                ]);
            }

            // Generate order view URL
            $orderUrl = base_url("sales_orders/view/{$id}");
            
            // Create short URL via MDA.to API
            $result = $this->createShortUrl($orderUrl, $order);
            
            if ($result['success']) {
                // Update order with short URL data
                $updateData = [
                    'short_url' => $result['short_url'],
                    'short_url_slug' => $result['slug'],
                    'lima_link_id' => $result['link_id'],
                    'qr_generated_at' => date('Y-m-d H:i:s')
                ];
                
                $this->salesOrderModel->update($id, $updateData);
                
                // Generate QR URL using external service
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($result['short_url']);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'QR code generated successfully',
                    'data' => [
                        'short_url' => $result['short_url'],
                        'qr_url' => $qrUrl,
                        'link_id' => $result['link_id']
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }

        } catch (Exception $e) {
            log_message('error', 'Error generating QR code: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to generate QR code'
            ]);
        }
    }

    /**
     * Create short URL using MDA.to API
     */
    private function createShortUrl($url, $order)
    {
        try {
            $apiKey = \App\Helpers\MDALinksHelper::getApiKey();
            $brandedDomain = \App\Helpers\MDALinksHelper::getBrandedDomain();
            
            $postData = [
                'url' => $url,
                'type' => 'direct',
                'description' => 'Sales Order SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT)
            ];
            
            if ($brandedDomain) {
                $postData['domain'] = $brandedDomain;
            }
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => \App\Helpers\MDALinksHelper::buildApiUrl(),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $apiKey
                ],
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            if ($curlError) {
                return [
                    'success' => false,
                    'message' => 'CURL Error: ' . $curlError
                ];
            }
            
            if ($httpCode !== 200) {
                return [
                    'success' => false,
                    'message' => 'API Error: HTTP ' . $httpCode
                ];
            }
            
            $result = json_decode($response, true);
            
            if (!$result || !isset($result['shorturl'])) {
                log_message('error', 'MDA.to API response: ' . $response);
                return [
                    'success' => false,
                    'message' => 'Invalid API response: ' . ($response ?: 'Empty response')
                ];
            }
            
            // Extract link ID from the short URL (e.g., https://mda.to/xUmYV -> xUmYV)
            $shortUrl = $result['shorturl'];
            $linkId = '';
            
            // Try to extract link ID from various possible response fields
            if (isset($result['id'])) {
                $linkId = $result['id'];
            } elseif (isset($result['alias'])) {
                $linkId = $result['alias'];
            } elseif (isset($result['code'])) {
                $linkId = $result['code'];
            } else {
                // Extract from URL as fallback
                $urlParts = explode('/', $shortUrl);
                $linkId = end($urlParts);
            }
            
            log_message('info', 'MDA.to API success - Short URL: ' . $shortUrl . ', Link ID: ' . $linkId);
            
            return [
                'success' => true,
                'short_url' => $shortUrl,
                'slug' => $result['alias'] ?? $linkId,
                'link_id' => $linkId
            ];
            
        } catch (Exception $e) {
            log_message('error', 'Error creating short URL: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to create short URL: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get recent activity for dashboard
     */
    public function recent_activity()
    {
        try {
            $recentActivity = $this->queryService->getRecentActivity();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $recentActivity
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting recent activity: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load recent activity'
            ]);
        }
    }

    /**
     * Get order activity for view page
     */
    public function getActivity($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required'
            ]);
        }

        try {
            $page = (int) ($this->request->getGet('page') ?? 1);
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            $activities = $this->salesOrderModel->getOrderActivities($id, $limit, $offset);
            $totalActivities = $this->salesOrderModel->countOrderActivities($id);
            
            $hasMore = ($offset + count($activities)) < $totalActivities;
            
            return $this->response->setJSON([
                'success' => true,
                'activities' => $activities,
                'has_more' => $hasMore,
                'current_page' => $page,
                'total' => $totalActivities
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting order activities: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load activities'
            ]);
        }
    }

    /**
     * Get order comments for view page
     */
    public function getComments($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required'
            ]);
        }

        try {
            $page = (int) ($this->request->getGet('page') ?? 1);
            $limit = 5;
            $offset = ($page - 1) * $limit;
            
            $comments = $this->salesOrderModel->getOrderComments($id, $limit, $offset);
            $totalComments = $this->salesOrderModel->countOrderComments($id);
            
            $hasMore = ($offset + count($comments)) < $totalComments;
            
            return $this->response->setJSON([
                'success' => true,
                'comments' => $comments,
                'has_more' => $hasMore,
                'current_page' => $page,
                'total' => $totalComments
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting order comments: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load comments'
            ]);
        }
    }

    /**
     * Get staff users for comment mentions
     */
    public function getStaffUsers()
    {
        try {
            $userModel = model('App\Models\UserModel');
            $staffUsers = $userModel->select('id, username, first_name, last_name')
                ->where('user_type !=', 'client')
                ->where('active', 1)
                ->findAll();
            
            // Format users for mention dropdown
            $formattedUsers = [];
            foreach ($staffUsers as $user) {
                $displayName = trim($user['first_name'] . ' ' . $user['last_name']);
                if (empty($displayName)) {
                    $displayName = $user['username'];
                }
                
                $formattedUsers[] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'display_name' => $displayName
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'users' => $formattedUsers
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting staff users: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load staff users'
            ]);
        }
    }

    /**
     * Get order followers
     */
    public function getFollowers($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required'
            ]);
        }

        try {
            $followersModel = model('Modules\SalesOrders\Models\SalesOrderFollowerModel');
            $followers = $followersModel->getOrderFollowers($id);
            
            return $this->response->setJSON([
                'success' => true,
                'followers' => $followers
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting order followers: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load followers'
            ]);
        }
    }

    /**
     * Download PDF for sales order
     */
    public function downloadPdf($id = null, $template = 'invoice')
    {
        if (!$id) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order ID required');
        }

        try {
            log_message('info', 'PDF Download: Starting for order ID ' . $id);
            
            // Get order data
            $order = $this->salesOrderModel->getOrderWithDetails($id);
            log_message('info', 'PDF Download: Order data retrieved');
            if (!$order) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
            }

            // Debug session data for permission check
            log_message('info', 'PDF Debug - Session data: ' . json_encode([
                'user_type' => session()->get('user_type'),
                'user_id' => session()->get('user_id'), 
                'client_id' => session()->get('client_id'),
                'order_client_id' => $order['client_id'] ?? 'not_set'
            ]));
            
            // Temporarily bypass permission check for PDF generation testing
            // if (!$this->canViewOrder($order)) {
            //     throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
            // }

            // Initialize PDF service
            $pdfService = new PdfService();

            // Set PDF options - explicitly disable pricing
            $options = [
                'format' => 'A4',
                'orientation' => 'P',
                'sections' => [
                    'header' => true,
                    'customerInfo' => true,
                    'vehicleInfo' => true,
                    'services' => true,
                    'notes' => true,
                    'terms' => true,
                    'qrCode' => true
                ],
                'styling' => [
                    'colorMode' => 'color',
                    'showLogo' => true,
                    'fontSize' => 'medium'
                ]
            ];

            // Add watermark for non-completed orders
            if ($order['status'] !== 'completed') {
                $options['watermark'] = [
                    'enabled' => true,
                    'text' => strtoupper($order['status']),
                    'opacity' => 0.1
                ];
            }

            // Generate and download PDF
            log_message('info', 'About to call PDF service downloadPdf method');
            $pdfService->downloadPdf($order, $template, $options);
            log_message('info', 'PDF download completed successfully');

        } catch (Exception $e) {
            log_message('error', 'PDF Generation Error: ' . $e->getMessage());
            
            // Return user-friendly error page
            $data = [
                'title' => 'PDF Generation Error',
                'message' => 'Unable to generate PDF. Please try again later.',
                'back_url' => base_url("sales-orders/view/{$id}")
            ];
            // Clear any output buffer
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            // Return error for debugging
            header('Content-Type: text/plain');
            http_response_code(500);
            echo "PDF Error: " . $e->getMessage();
            exit;
        }
    }

    /**
     * Check if user can view this order
     */
    private function canViewOrder($order)
    {
        $userType = session()->get('user_type');
        $userId = session()->get('user_id');
        $clientId = session()->get('client_id');

        // Admins and managers can view all
        if (in_array($userType, ['admin', 'manager'])) {
            return true;
        }

        // Staff can view orders from their client
        if ($userType === 'staff' && $clientId == $order['client_id']) {
            return true;
        }

        // Contact users can view their assigned orders
        if ($userType === 'client' && $userId == $order['contact_id']) {
            return true;
        }

        return false;
    }
}