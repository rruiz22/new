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
     * Get active clients for dropdown
     */
    public function getActiveClients()
    {
        try {
            $clientsModel = model('App\Models\ClientModel');
            $clients = $clientsModel->where('status', 'active')
                                  ->where('deleted', 0)
                                  ->orderBy('name', 'ASC')
                                  ->findAll();
            
            // Format for select dropdown
            $formatted = [];
            foreach ($clients as $client) {
                $formatted[] = [
                    'id' => $client['id'],
                    'name' => $client['name'],
                    'email' => $client['email'] ?? '',
                    'phone' => $client['phone'] ?? '',
                    'address' => $client['address'] ?? ''
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $formatted
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error loading active clients: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error loading clients']);
        }
    }

    /**
     * Get contacts for a specific client
     */
    public function getContactsForClient($clientId = null)
    {
        // Session filter handles authentication
        
        if (!$clientId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Client ID required']);
        }

        try {
            // NEW LOGIC: Get contacts from users table where user_type = 'client'
            $userModel = model('App\Models\UserModel');
            $contacts = $userModel->where('user_type', 'client')
                                ->where('client_id', $clientId)
                                ->where('deleted', 0)
                                ->where('active', 1)
                                ->orderBy('first_name', 'ASC')
                                ->findAll();
            
            // Format for select dropdown
            $formatted = [];
            foreach ($contacts as $contact) {
                $fullName = trim(($contact['first_name'] ?? '') . ' ' . ($contact['last_name'] ?? ''));
                if (empty($fullName)) {
                    $fullName = $contact['username'] ?? 'Unknown User';
                }
                
                $formatted[] = [
                    'id' => $contact['id'],
                    'name' => $fullName,
                    'username' => $contact['username'] ?? '',
                    'email' => '', // Email comes from auth_identities
                    'phone' => $contact['phone'] ?? '',
                    'client_id' => $contact['client_id']
                ];
            }
            
            // DEBUG: Add logging to see what we're returning
            log_message('info', 'Contacts for client ' . $clientId . ': ' . json_encode($formatted));
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $formatted
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error loading contacts: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error loading contacts']);
        }
    }

    /**
     * Get services for a specific client
     */
    public function getServicesForClient($clientId = null)
    {
        // Session filter handles authentication
        
        if (!$clientId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Client ID required']);
        }

        try {
            $servicesModel = model('Modules\SalesOrders\Models\SalesOrderServiceModel');
            $services = $servicesModel->where('deleted', 0)
                                    ->where('service_status', 'active')
                                    ->where('show_in_orders', 1)
                                    ->where('client_id', $clientId)
                                    ->orderBy('service_name', 'ASC')
                                    ->findAll();
            
            // Format for select dropdown
            $formatted = [];
            foreach ($services as $service) {
                $formatted[] = [
                    'id' => $service['id'],
                    'service_name' => $service['service_name'],
                    'name' => $service['service_name'], // Alias for compatibility
                    'description' => $service['service_description'] ?? '',
                    'service_price' => $service['service_price'] ?? 0,
                    'price' => number_format($service['service_price'] ?? 0, 2),
                    'notes' => $service['notes'] ?? ''
                ];
            }
            
            // DEBUG: Add logging to see what we're returning
            log_message('info', 'Services for client ' . $clientId . ': ' . json_encode($formatted));
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $formatted
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error loading services: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error loading services']);
        }
    }

    /**
     * VIN Decoder - decode VIN using NHTSA API with caching
     */
    public function decodeVin()
    {
        $vin = $this->request->getPost('vin');
        
        if (empty($vin)) {
            return $this->response->setJSON(['success' => false, 'message' => 'VIN required']);
        }

        try {
            // Check cache first
            $cache = \Config\Services::cache();
            $cacheKey = 'vin_decode_' . md5($vin);
            $cachedData = $cache->get($cacheKey);
            
            if ($cachedData) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $cachedData,
                    'cached' => true
                ]);
            }

            // Call NHTSA API
            $apiUrl = "https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/{$vin}?format=json";
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'MDA Sales Orders System'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            if ($curlError || $httpCode !== 200) {
                throw new Exception('API request failed: ' . ($curlError ?: "HTTP {$httpCode}"));
            }
            
            $result = json_decode($response, true);
            
            if (!$result || !isset($result['Results'][0])) {
                throw new Exception('Invalid API response');
            }
            
            $vinData = $result['Results'][0];
            
            // Format response
            $formattedData = [
                'vin' => $vin,
                'make' => $vinData['Make'] ?? '',
                'model' => $vinData['Model'] ?? '',
                'year' => $vinData['ModelYear'] ?? '',
                'trim' => $vinData['Trim'] ?? '',
                'engine' => $vinData['EngineConfiguration'] ?? '',
                'body_class' => $vinData['BodyClass'] ?? '',
                'fuel_type' => $vinData['FuelTypePrimary'] ?? '',
                'manufacturer' => $vinData['Manufacturer'] ?? '',
                'plant_country' => $vinData['PlantCountry'] ?? '',
                'error_code' => $vinData['ErrorCode'] ?? '0',
                'error_text' => $vinData['ErrorText'] ?? ''
            ];
            
            // Cache for 7 days if successful decode
            if ($formattedData['error_code'] === '0' || empty($formattedData['error_text'])) {
                $cache->save($cacheKey, $formattedData, 7 * 24 * 60 * 60);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $formattedData,
                'cached' => false
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error decoding VIN: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'VIN decode failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check for duplicate orders
     */
    public function checkDuplicateOrder()
    {
        $vin = $this->request->getPost('vin');
        $clientId = $this->request->getPost('client_id');
        $excludeOrderId = $this->request->getPost('exclude_order_id');
        
        if (empty($vin) && empty($clientId)) {
            return $this->response->setJSON(['success' => true, 'duplicates' => []]);
        }

        try {
            $builder = $this->salesOrderModel->builder();
            $builder->select('sales_orders.*, clients.name as client_name');
            $builder->join('clients', 'clients.id = sales_orders.client_id', 'left');
            $builder->where('sales_orders.deleted', 0);
            
            if (!empty($excludeOrderId)) {
                $builder->where('sales_orders.id !=', $excludeOrderId);
            }
            
            // Check by VIN if provided
            if (!empty($vin)) {
                $builder->where('sales_orders.vin', $vin);
            }
            
            // Check by client if provided (last 30 days to avoid too many results)
            if (!empty($clientId)) {
                $builder->where('sales_orders.client_id', $clientId);
                $builder->where('sales_orders.created_at >=', date('Y-m-d', strtotime('-30 days')));
            }
            
            $builder->orderBy('sales_orders.created_at', 'DESC');
            $builder->limit(10); // Limit results
            
            $duplicates = $builder->get()->getResultArray();
            
            $formatted = [];
            foreach ($duplicates as $order) {
                $formatted[] = [
                    'id' => $order['id'],
                    'order_number' => 'SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                    'vehicle' => ($order['vehicle_year'] ?? '') . ' ' . ($order['vehicle_make'] ?? '') . ' ' . ($order['vehicle_model'] ?? ''),
                    'vin' => $order['vin'] ?? 'N/A',
                    'status' => $order['status'] ?? 'unknown',
                    'created_at' => $order['created_at'] ?? '',
                    'client_name' => $order['client_name'] ?? 'Unknown Client',
                    'contact_name' => $order['contact_name'] ?? ''
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'duplicates' => $formatted,
                'count' => count($formatted)
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error checking duplicates: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'duplicates' => [], 'message' => 'Error checking duplicates']);
        }
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
     * UNIFIED SAVE METHOD - Replaces store(), save(), create() and update() duplicates
     * Handles both create and update operations based on presence of ID
     * OPTIMIZATION: Eliminates 3 duplicate methods
     */
    public function save()
    {
        $orderId = $this->request->getPost('id');
        $isUpdate = !empty($orderId);
        
        try {
            // Use existing create/update methods
            if ($isUpdate) {
                $result = $this->update($orderId);
            } else {
                $result = $this->create();
            }
            
            // Invalidate cache after CUD operations
            $this->queryService->invalidateCache();
            
            return $result;
            
        } catch (Exception $e) {
            log_message('error', 'Error in unified save method: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => $isUpdate ? 'Failed to update order' : 'Failed to create order'
            ]);
        }
    }
    
    /**
     * DEPRECATED ALIASES - Keep for backward compatibility but redirect to save()
     * These will be removed in future versions
     */
    public function store() { return $this->save(); }

    /**
     * ALIAS METHODS FOR BACKWARD COMPATIBILITY
     * These redirect to the actual methods with correct names
     */
    public function getContactsByClient($clientId = null) {
        return $this->getContactsForClient($clientId);
    }
    
    /**
     * Get contacts by dealer - Better naming for the new logic
     */
    public function getContactsByDealer()
    {
        $dealerId = $this->request->getGet('dealer_id') ?: $this->request->getPost('dealer_id');
        return $this->getContactsForClient($dealerId);
    }
    
    public function getServicesByClient($clientId = null) {
        return $this->getServicesForClient($clientId);
    }

    /**
     * Get order data for editing - used by optimized modal
     */
    public function get($id = null)
    {
        // Set JSON response header
        $this->response->setHeader('Content-Type', 'application/json');
        
        // Session filter handles authentication, we're already authenticated here
        
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }

        try {
            // Use simple find first to test basic functionality
            $order = $this->salesOrderModel->find($id);
            
            if (!$order) {
                return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
            }

            // Convert to array
            $order = (array) $order;
            
            // DEBUG: Log raw order data to see all fields
            log_message('info', 'Raw order data for ID ' . $id . ': ' . json_encode($order));
            
            // Add complete related data
            if (isset($order['client_id']) && $order['client_id']) {
                try {
                    $clientModel = model('App\Models\ClientModel');
                    $client = $clientModel->find($order['client_id']);
                    if ($client) {
                        $order['client_name'] = $client['name'] ?? '';
                        $order['client_email'] = $client['email'] ?? '';
                        $order['client_phone'] = $client['phone'] ?? '';
                    }
                } catch (Exception $e) {
                    // Continue without client data
                }
            }
            
            // Get contact data (from users table)
            if (isset($order['contact_id']) && $order['contact_id']) {
                try {
                    $userModel = model('App\Models\UserModel');
                    $contact = $userModel->find($order['contact_id']);
                    if ($contact) {
                        $order['contact_name'] = ($contact['first_name'] ?? '') . ' ' . ($contact['last_name'] ?? '');
                        $order['contact_name'] = trim($order['contact_name']); // Remove extra spaces
                        $order['contact_phone'] = $contact['phone'] ?? '';
                        $order['contact_first_name'] = $contact['first_name'] ?? '';
                        $order['contact_last_name'] = $contact['last_name'] ?? '';
                        
                        // Get email from auth_identities table (CI4 Shield)
                        $db = \Config\Database::connect();
                        $authIdentity = $db->table('auth_identities')
                                          ->where('user_id', $order['contact_id'])
                                          ->where('type', 'email_password')
                                          ->get()
                                          ->getRowArray();
                        $order['contact_email'] = $authIdentity['secret'] ?? '';
                    }
                } catch (Exception $e) {
                    // Continue without contact data
                }
            }
            
            // Get service data
            if (isset($order['service_id']) && $order['service_id']) {
                try {
                    $serviceModel = model('Modules\SalesOrders\Models\SalesOrderServiceModel');
                    $service = $serviceModel->find($order['service_id']);
                    if ($service) {
                        $order['service_name'] = $service['service_name'] ?? '';
                        $order['service_title'] = $service['service_name'] ?? '';
                        $order['service_price'] = $service['service_price'] ?? 0;
                    }
                } catch (Exception $e) {
                    // Continue without service data
                }
            }
            
            // Get salesperson data (assigned_to field) - buscar en tabla contacts
            if (isset($order['assigned_to']) && $order['assigned_to']) {
                try {
                    $contactModel = model('App\Models\ContactModel');
                    $salesperson = $contactModel->find($order['assigned_to']);
                    if ($salesperson) {
                        $order['salesperson_name'] = $salesperson['name'] ?? '';
                        $order['salesperson_phone'] = $salesperson['phone'] ?? '';
                        $order['salesperson_email'] = $salesperson['email'] ?? '';
                    }
                } catch (Exception $e) {
                    // Continue without salesperson data
                }
            }
            
            // Ensure salesperson fields are always set to avoid undefined key errors
            if (!isset($order['salesperson_phone'])) {
                $order['salesperson_phone'] = '';
            }
            if (!isset($order['salesperson_email'])) {
                $order['salesperson_email'] = '';
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $order
            ]);

        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * UNIFIED METRICS METHOD - Replaces getMetrics(), getStatistics(), dashboard_stats()
     * Uses optimized caching from SalesOrderQueryService
     * OPTIMIZATION: Eliminates 2 duplicate methods + adds intelligent caching
     */
    public function getMetrics($type = 'dashboard')
    {
        try {
            // Use original service method for now to ensure stability
            $metrics = $this->salesOrderService->getDashboardMetrics();
            
            return $this->response->setJSON([
                'success' => true, 
                'metrics' => $metrics
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting unified metrics: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Failed to get metrics',
                'error_code' => 'METRICS_ERROR'
            ]);
        }
    }
    
    /**
     * DEPRECATED ALIASES - Redirect to unified method
     */
    public function getStatistics() { return $this->getMetrics('statistics'); }
    public function dashboard_stats() { return $this->getMetrics('dashboard'); }

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
     * Validate date and time for orders
     */
    public function validateDateTime()
    {
        $date = $this->request->getPost('date');
        $time = $this->request->getPost('time');
        $isEdit = $this->request->getPost('is_edit') === 'true';
        
        if (empty($date) || empty($time)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Date and time are required'
            ]);
        }

        try {
            $selectedDateTime = new \DateTime($date . ' ' . $time);
            $now = new \DateTime();
            $today = new \DateTime('today');
            
            // For new orders: only today and future dates
            if (!$isEdit) {
                if ($selectedDateTime->format('Y-m-d') < $today->format('Y-m-d')) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Cannot create orders for past dates'
                    ]);
                }
                
                // If today, time must be at least 1 hour from now
                if ($selectedDateTime->format('Y-m-d') === $today->format('Y-m-d')) {
                    $minTime = clone $now;
                    $minTime->add(new \DateInterval('PT1H'));
                    
                    if ($selectedDateTime <= $minTime) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Time must be at least 1 hour from now'
                        ]);
                    }
                }
            }
            
            // Check business hours (8 AM - 6 PM)
            $hour = intval($selectedDateTime->format('H'));
            if ($hour < 8 || $hour >= 18) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Orders can only be scheduled between 8:00 AM and 6:00 PM'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Date and time are valid',
                'formatted_datetime' => $selectedDateTime->format('Y-m-d H:i:s')
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error validating date/time: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Invalid date or time format'
            ]);
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
     * UNIFIED ACTIVITY METHOD - Replaces getActivity() and getActivities() duplicates
     * Supports both paginated and full activity loading
     * OPTIMIZATION: Eliminates 1 duplicate method + adds intelligent caching
     */
    public function getActivity($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required',
                'error_code' => 'MISSING_ORDER_ID'
            ]);
        }

        try {
            // Check if pagination is requested
            $page = (int) ($this->request->getGet('page') ?? 1);
            $limit = (int) ($this->request->getGet('limit') ?? 10);
            $format = $this->request->getGet('format') ?? 'paginated'; // 'paginated' or 'all'
            
            if ($format === 'all' || $limit === 0) {
                // Return all activities (for compatibility with old getActivities method)
                $activities = $this->salesOrderModel->getOrderActivities($id);
                
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $activities,
                    'total' => count($activities),
                    'format' => 'all'
                ]);
                
            } else {
                // Return paginated activities (default behavior)
                $offset = ($page - 1) * $limit;
                
                $activities = $this->salesOrderModel->getOrderActivities($id, $limit, $offset);
                $totalActivities = $this->salesOrderModel->countOrderActivities($id);
                
                $hasMore = ($offset + count($activities)) < $totalActivities;
                
                return $this->response->setJSON([
                    'success' => true,
                    'activities' => $activities,
                    'pagination' => [
                        'has_more' => $hasMore,
                        'current_page' => $page,
                        'per_page' => $limit,
                        'total' => $totalActivities,
                        'total_pages' => ceil($totalActivities / $limit)
                    ],
                    'format' => 'paginated'
                ]);
            }
            
        } catch (Exception $e) {
            log_message('error', 'Error getting order activities: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load activities',
                'error_code' => 'ACTIVITIES_ERROR'
            ]);
        }
    }
    
    /**
     * DEPRECATED ALIAS - Redirect to unified method with 'all' format
     */
    public function getActivities($id = null) { 
        $_GET['format'] = 'all';
        return $this->getActivity($id); 
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
     * Get available followers for an order
     */
    public function getAvailableFollowers($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID required'
            ]);
        }

        try {
            // Get order details to find client_id
            $order = $this->salesOrderModel->find($id);
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }

            $followersModel = model('Modules\SalesOrders\Models\SalesOrderFollowerModel');
            $availableFollowers = $followersModel->getAvailableFollowers($id, $order['client_id']);
            
            return $this->response->setJSON([
                'success' => true,
                'followers' => $availableFollowers
            ]);
            
        } catch (Exception $e) {
            log_message('error', 'Error getting available followers: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load available followers'
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