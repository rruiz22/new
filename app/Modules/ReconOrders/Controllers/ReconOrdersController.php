<?php

namespace Modules\ReconOrders\Controllers;

use App\Controllers\BaseController;
use Modules\ReconOrders\Models\ReconOrderModel;
use Modules\ReconOrders\Models\ReconCommentModel;
use Modules\ReconOrders\Models\ReconNoteModel;
use Modules\ReconOrders\Models\ReconActivityModel;
use Modules\ReconOrders\Models\ReconServiceModel;
use App\Models\ClientModel;
use App\Models\UserModel;
use Exception;

class ReconOrdersController extends BaseController
{
    protected $reconOrderModel;
    protected $reconCommentModel;
    protected $reconNoteModel;
    protected $reconActivityModel;
    protected $reconServiceModel;
    protected $clientModel;
    protected $userModel;

    public function __construct()
    {
        $this->reconOrderModel = new ReconOrderModel();
        $this->reconCommentModel = new ReconCommentModel();
        $this->reconNoteModel = new ReconNoteModel();
        $this->reconActivityModel = new ReconActivityModel();
        $this->reconServiceModel = new ReconServiceModel();
        $this->clientModel = new ClientModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Recon Orders',
            'orders' => [],
            'clients' => $this->clientModel->findAll(),
            'stats' => $this->reconOrderModel->getDashboardStats()
        ];

        return view('Modules\ReconOrders\Views\recon_orders\index', $data);
    }

    public function view($id = null)
    {
        if (!$id) {
            return redirect()->to(base_url('recon_orders'))->with('error', 'Order not found');
        }

        $order = $this->reconOrderModel->getOrderWithDetails($id);
        
        if (!$order) {
            return redirect()->to(base_url('recon_orders'))->with('error', 'Order not found');
        }

        // Generate QR Code data using simplified method
        $qr_data = $this->getQRDataForOrderSimple($id);

        // Initialize data arrays (these will be loaded via AJAX)
        $comments = [];
        $notes = [];
        $activity = [];
        $followers = [];

        $data = [
            'title' => 'Recon Order ' . $order['order_number'],
            'order' => $order,
            'comments' => $comments,
            'notes' => $notes,
            'activity' => $activity,
            'followers' => $followers,
            'qr_data' => $qr_data,
            'clients' => $this->clientModel->findAll(),
            'users' => $this->userModel->findAll()
        ];

        return view('Modules\ReconOrders\Views\recon_orders\view', $data);
    }

    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->to(base_url('recon_orders'))->with('error', 'Order not found');
        }

        $order = $this->reconOrderModel->find($id);
        
        if (!$order) {
            return redirect()->to(base_url('recon_orders'))->with('error', 'Order not found');
        }

        $data = [
            'title' => 'Edit Recon Order ' . $order['order_number'],
            'order' => $order,
            'clients' => $this->clientModel->where('status', 'active')->orderBy('name', 'ASC')->findAll(),
            'services' => $this->reconServiceModel->getActiveServices()
        ];

        return view('Modules\ReconOrders\Views\recon_orders\edit', $data);
    }

    public function store()
    {
        // Log that the store method is being called
        log_message('debug', 'ReconOrdersController::store() called');
        log_message('debug', 'Auth status: ' . (auth()->loggedIn() ? 'logged in' : 'not logged in'));
        log_message('debug', 'Session data: ' . json_encode(session()->get()));
        
        if (!$this->request->isAJAX()) {
            log_message('debug', 'ReconOrdersController::store() - Not AJAX request');
            return redirect()->back();
        }

        try {
            $formData = $this->request->getPost();
            
            // Log incoming data for debugging
            log_message('debug', 'Recon Order Store - Incoming data: ' . json_encode($formData));
            
            // Validate required fields (including new required fields)
            $requiredFields = ['client_id', 'stock', 'vin_number', 'vehicle', 'service_id'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (empty($formData[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Missing required fields: ' . implode(', ', $missingFields),
                    'missing_fields' => $missingFields
                ]);
            }

            // Get user ID from authentication
            $userId = null;
            try {
                $userId = auth()->user()->id ?? session()->get('user_id') ?? 1;
            } catch (\Exception $e) {
                log_message('error', 'Error getting user ID: ' . $e->getMessage());
                $userId = 1; // Default fallback
            }

            // Prepare order data
            $orderData = [
                'client_id' => (int) $formData['client_id'],
                'stock' => trim($formData['stock']),
                'vin_number' => strtoupper(trim($formData['vin_number'])),
                'vehicle' => trim($formData['vehicle']),
                'service_id' => (int) $formData['service_id'],
                'service_date' => !empty($formData['service_date']) ? $formData['service_date'] : null,
                'pictures' => isset($formData['pictures']) ? 1 : 0,
                'status' => $formData['status'] ?? 'pending',
                'notes' => !empty($formData['notes']) ? trim($formData['notes']) : null,
                'created_by' => $userId
            ];
            
            // Log processed data
            log_message('debug', 'Recon Order Store - Processed data: ' . json_encode($orderData));

            // Check for duplicate orders (same client, stock, VIN within last 5 minutes)
            $duplicateCheck = $this->reconOrderModel
                ->where('client_id', $orderData['client_id'])
                ->where('stock', $orderData['stock'])
                ->where('vin_number', $orderData['vin_number'])
                ->where('vehicle', $orderData['vehicle'])
                ->where('created_at >', date('Y-m-d H:i:s', strtotime('-5 minutes')))
                ->where('deleted_at IS NULL')
                ->first();
            
            if ($duplicateCheck) {
                log_message('warning', 'Duplicate order attempt detected: ' . json_encode($orderData));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'A similar order was already created recently. Order ID: ' . $duplicateCheck['order_number']
                ]);
            }

            // Insert the order
            $orderId = $this->reconOrderModel->insert($orderData);

            if ($orderId) {
                // Log order creation
                try {
                    $this->reconActivityModel->logOrderCreated($orderId, $userId);
                } catch (\Exception $e) {
                    log_message('error', 'Error logging order creation: ' . $e->getMessage());
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Recon order created successfully',
                    'order_id' => $orderId
                ]);
            } else {
                $errors = $this->reconOrderModel->errors();
                log_message('error', 'Recon Order Store - Model insert failed: ' . json_encode($errors));
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create recon order',
                    'errors' => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Recon Order Store Error: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error creating recon order: ' . $e->getMessage()
            ]);
        }
    }

    public function update($id = null)
    {
        if (!$id) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID is required'
                ]);
            }
            return redirect()->back()->with('error', 'Order ID is required');
        }

        $order = $this->reconOrderModel->find($id);
        if (!$order) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
            return redirect()->back()->with('error', 'Order not found');
        }

        $formData = $this->request->getPost();
        
        // Validate required fields
        if (empty($formData['client_id']) || empty($formData['vehicle'])) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Client and Vehicle are required fields'
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Client and Vehicle are required fields');
        }

        // Get user ID from authentication
        $userId = auth()->user()->id ?? session()->get('user_id') ?? 1;

        $updateData = [
            'client_id' => $formData['client_id'],
            'stock' => trim(strtoupper($formData['stock'] ?? '')),
            'vin_number' => trim(strtoupper($formData['vin_number'] ?? '')),
            'vehicle' => trim($formData['vehicle']),
            'service_id' => $formData['service_id'] ?? null,
            'service_date' => !empty($formData['service_date']) ? $formData['service_date'] : null,
            'pictures' => isset($formData['pictures']) ? 1 : 0,
            'status' => $formData['status'] ?? $order['status'],
            'notes' => trim($formData['notes'] ?? ''),
            'updated_by' => $userId
        ];

        // Log changes
        foreach ($updateData as $field => $newValue) {
            if (isset($order[$field]) && $order[$field] != $newValue && $field !== 'updated_by') {
                $fieldLabels = [
                    'client_id' => 'Client',
                    'stock' => 'Stock',
                    'vin_number' => 'VIN Number',
                    'vehicle' => 'Vehicle',
                    'service_id' => 'Service',
                    'pictures' => 'Pictures',
                    'status' => 'Status',
                    'notes' => 'Notes'
                ];
                
                $this->reconActivityModel->logFieldChange(
                    $id, 
                    $userId, 
                    $field, 
                    $order[$field], 
                    $newValue, 
                    $fieldLabels[$field]
                );
            }
        }

        if ($this->reconOrderModel->update($id, $updateData)) {
            // Log order update
            $this->reconActivityModel->logOrderUpdated($id, $userId);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Recon order updated successfully'
                ]);
            } else {
                return redirect()->to(base_url('recon_orders'))->with('success', 'Recon order updated successfully');
            }
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update recon order',
                    'errors' => $this->reconOrderModel->errors()
                ]);
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update recon order');
            }
        }
    }

    public function delete($id = null)
    {
        if (!$id) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID is required'
                ]);
            }
            return redirect()->back()->with('error', 'Order ID is required');
        }

        $order = $this->reconOrderModel->find($id);
        if (!$order) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
            return redirect()->back()->with('error', 'Order not found');
        }

        // Get user ID from authentication
        $userId = auth()->user()->id ?? session()->get('user_id') ?? 1;

        // Soft delete
        $this->reconOrderModel->update($id, ['deleted_by' => $userId]);
        
        if ($this->reconOrderModel->delete($id)) {
            // Log order deletion
            $this->reconActivityModel->logOrderDeleted($id, $userId);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Recon order deleted successfully'
                ]);
            } else {
                return redirect()->to(base_url('recon_orders'))->with('success', 'Recon order deleted successfully');
            }
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete recon order'
                ]);
            } else {
                return redirect()->back()->with('error', 'Failed to delete recon order');
            }
        }
    }

    // Data endpoints for DataTables
    public function getTodayOrders()
    {
        try {
            // Get filters from request
            $clientFilter = $this->request->getGet('client_filter') ?: $this->request->getPost('client_filter');
            $statusFilter = $this->request->getGet('status_filter') ?: $this->request->getPost('status_filter');
            $dateFromFilter = $this->request->getGet('date_from_filter') ?: $this->request->getPost('date_from_filter');
            $dateToFilter = $this->request->getGet('date_to_filter') ?: $this->request->getPost('date_to_filter');
            
            $db = \Config\Database::connect();
            $builder = $db->table('recon_orders')
                ->select('recon_orders.*, clients.name as client_name,
                         (SELECT COUNT(*) FROM recon_comments WHERE order_id = recon_orders.id) as comments_count,
                         (SELECT COUNT(*) FROM recon_notes WHERE order_id = recon_orders.id AND deleted_at IS NULL) as internal_notes_count')
                ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                ->where('recon_orders.deleted_at IS NULL')
                ->where('DATE(recon_orders.created_at)', date('Y-m-d')); // Today's orders
            
            // Apply filters
            if (!empty($clientFilter)) {
                $builder->where('recon_orders.client_id', $clientFilter);
            }
            
            if (!empty($statusFilter)) {
                $builder->where('recon_orders.status', $statusFilter);
            }
            
            if (!empty($dateFromFilter)) {
                $builder->where('DATE(recon_orders.created_at) >=', $dateFromFilter);
            }
            
            if (!empty($dateToFilter)) {
                $builder->where('DATE(recon_orders.created_at) <=', $dateToFilter);
            }
            
            $orders = $builder->orderBy('recon_orders.created_at', 'DESC')
                ->get()->getResultArray();
            
            // Format order numbers and add duplicate information for each order
            foreach ($orders as &$order) {
                if (empty($order['order_number'])) {
                    $order['order_number'] = 'RO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
                }
                
                // Add duplicate information
                $order['duplicates'] = $this->getDuplicateInfo($order);
            }
            
        return $this->response->setJSON(['data' => $orders]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getTodayOrders: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getAllActiveOrders()
    {
        try {
            // Get filters from request
            $clientFilter = $this->request->getGet('client_filter') ?: $this->request->getPost('client_filter');
            $statusFilter = $this->request->getGet('status_filter') ?: $this->request->getPost('status_filter');
            $dateFromFilter = $this->request->getGet('date_from_filter') ?: $this->request->getPost('date_from_filter');
            $dateToFilter = $this->request->getGet('date_to_filter') ?: $this->request->getPost('date_to_filter');
            
            $db = \Config\Database::connect();
            $builder = $db->table('recon_orders')
                ->select('recon_orders.*, clients.name as client_name,
                         (SELECT COUNT(*) FROM recon_comments WHERE order_id = recon_orders.id) as comments_count,
                         (SELECT COUNT(*) FROM recon_notes WHERE order_id = recon_orders.id AND deleted_at IS NULL) as internal_notes_count')
                ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                ->where('recon_orders.deleted_at IS NULL');
            
            // Apply filters
            if (!empty($clientFilter)) {
                $builder->where('recon_orders.client_id', $clientFilter);
            }
            
            if (!empty($statusFilter)) {
                $builder->where('recon_orders.status', $statusFilter);
            }
            
            if (!empty($dateFromFilter)) {
                $builder->where('DATE(recon_orders.created_at) >=', $dateFromFilter);
            }
            
            if (!empty($dateToFilter)) {
                $builder->where('DATE(recon_orders.created_at) <=', $dateToFilter);
            }
            
            $orders = $builder->orderBy('recon_orders.created_at', 'DESC')
                ->get()->getResultArray();
            
            // Format order numbers and add duplicate information for each order
            foreach ($orders as &$order) {
                if (empty($order['order_number'])) {
                    $order['order_number'] = 'RO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
                }
                
                // Add duplicate information
                $order['duplicates'] = $this->getDuplicateInfo($order);
            }
            
        return $this->response->setJSON(['data' => $orders]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['data' => []]);
        }
    }

    // Update order status
    public function updateStatus($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }

        $newStatus = $this->request->getPost('status');
        
        if (!$newStatus) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status is required'
            ]);
        }

        // Validate status
        $allowedStatuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        if (!in_array($newStatus, $allowedStatuses)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid status provided'
            ]);
        }

        try {
            $order = $this->reconOrderModel->find($id);
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }

            $userId = auth()->user()->id ?? session()->get('user_id') ?? 1;
            $currentTimestamp = date('Y-m-d H:i:s');

            $data = [
                'status' => $newStatus,
                'updated_by' => $userId,
                'updated_at' => $currentTimestamp
            ];

            if ($this->reconOrderModel->update($id, $data)) {
                // Log the status change activity (if activity model works)
                try {
                    $this->reconActivityModel->logStatusChange($id, $userId, $order['status'], $newStatus);
                } catch (\Exception $e) {
                    log_message('error', "Error logging activity: " . $e->getMessage());
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Status updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update status'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating status: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ]);
        }
    }



    // Helper function to get duplicate information
    private function getDuplicateInfo($order)
    {
        $duplicates = [
            'has_duplicates' => false,
            'stock_duplicates' => [],
            'vin_duplicates' => []
        ];

        $db = \Config\Database::connect();

        // Check for stock duplicates
        if (!empty($order['stock'])) {
            $stockDuplicates = $db->table('recon_orders')
                ->select('id, order_number, vehicle, created_at')
                ->where('stock', $order['stock'])
                ->where('id !=', $order['id'])
                ->where('deleted_at IS NULL')
                ->get()
                ->getResultArray();

            if (!empty($stockDuplicates)) {
                $duplicates['has_duplicates'] = true;
                $duplicates['stock_duplicates'] = $stockDuplicates;
            }
        }

        // Check for VIN duplicates
        if (!empty($order['vin_number'])) {
            $vinDuplicates = $db->table('recon_orders')
                ->select('id, order_number, vehicle, created_at')
                ->where('vin_number', $order['vin_number'])
                ->where('id !=', $order['id'])
                ->where('deleted_at IS NULL')
                ->get()
                ->getResultArray();

            if (!empty($vinDuplicates)) {
                $duplicates['has_duplicates'] = true;
                $duplicates['vin_duplicates'] = $vinDuplicates;
            }
        }

        return $duplicates;
    }

    // Get clients for dropdown
    public function getClients()
    {
        try {
            $clients = $this->clientModel->select('id, name, status')
                                        ->where('deleted_at IS NULL')
                                        ->where('status', 'active')
                                        ->orderBy('name', 'ASC')
                                        ->findAll();
            return $this->response->setJSON([
                'success' => true,
                'clients' => $clients
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting clients: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'clients' => []
            ]);
        }
    }

    // Dashboard content
    public function dashboard_content()
    {
        // Log the request details for debugging
        log_message('debug', 'Dashboard content called. Method: ' . $this->request->getMethod() . 
                   ', AJAX: ' . ($this->request->isAJAX() ? 'Yes' : 'No') . 
                   ', Headers: ' . json_encode($this->request->headers()));
        
        // Check if it's an AJAX request for DataTables
        if (strtolower($this->request->getMethod()) === 'post' && 
            ($this->request->isAJAX() || $this->request->hasHeader('X-Requested-With'))) {
            
            $draw = $this->request->getPost('draw') ?? 1;
            $start = (int) ($this->request->getPost('start') ?? 0);
            $length = (int) ($this->request->getPost('length') ?? 10);
            $searchValue = $this->request->getPost('search')['value'] ?? '';
            
            log_message('debug', 'Processing AJAX request for dashboard data');
            
            try {
                $db = \Config\Database::connect();
                
                // Check if tables exist
                if (!$db->tableExists('recon_orders')) {
                    log_message('error', 'Table recon_orders does not exist');
                    return $this->response->setJSON([
                        'draw' => $draw,
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => []
                    ]);
                }
                
                // Simple query first
                $builder = $db->table('recon_orders');
                
                // Join with clients and services if tables exist
                if ($db->tableExists('clients') && $db->tableExists('recon_services')) {
                    $builder->select('recon_orders.*, clients.name as client_name, recon_services.name as service_name, recon_services.color as service_color')
                           ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                           ->join('recon_services', 'recon_services.id = recon_orders.service_id', 'left');
                } else if ($db->tableExists('clients')) {
                    $builder->select('recon_orders.*, clients.name as client_name, "" as service_name, "" as service_color')
                           ->join('clients', 'clients.id = recon_orders.client_id', 'left');
                } else {
                    $builder->select('recon_orders.*, "" as client_name, "" as service_name, "" as service_color');
                }
                
                $builder->where('recon_orders.deleted_at IS NULL');
                
                // Apply search filter
                if (!empty($searchValue)) {
                    $builder->groupStart()
                        ->like('recon_orders.vehicle', $searchValue)
                        ->orLike('recon_orders.stock', $searchValue)
                        ->groupEnd();
                }
                
                // Get total count
                $totalRecords = $builder->countAllResults(false);
                
                // Apply pagination
                $builder->orderBy('recon_orders.id', 'DESC')
                    ->limit($length, $start);
                
                $orders = $builder->get()->getResultArray();
                
                // Format data for DataTables
                $data = [];
                foreach ($orders as $order) {
                    $data[] = [
                        'DT_RowData' => ['service-color' => $order['service_color'] ?? '#007bff'],
                        'id' => $order['id'],
                        'order_id' => 'RO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                        'client_name' => $order['client_name'] ?? 'N/A',
                        'stock' => $order['stock'] ?? 'N/A',
                        'service_name' => $order['service_name'] ?? 'N/A',
                        'vehicle' => $order['vehicle'] ?? 'N/A',
                        'vin_number' => $order['vin_number'] ?? 'N/A',
                        'date' => date('M d, Y', strtotime($order['created_at'] ?? 'now')),
                        'status' => '<span class="badge bg-' . $this->getStatusColor($order['status'] ?? 'pending') . '">' . ucfirst($order['status'] ?? 'pending') . '</span>',
                        'service_color' => $order['service_color'] ?? '#007bff'
                    ];
                }
                
                log_message('debug', 'Returning JSON response with ' . count($data) . ' records');
                
                return $this->response->setJSON([
                    'draw' => $draw,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $totalRecords,
                    'data' => $data
                ]);
                
            } catch (\Exception $e) {
                log_message('error', 'Dashboard content error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'draw' => $draw ?? 1,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Return view for GET requests
        log_message('debug', 'Returning HTML view for dashboard');
        try {
            $data = [
                'stats' => []
            ];
            
            return view('Modules\ReconOrders\Views\recon_orders\dashboard_content', $data);
        } catch (\Exception $e) {
            log_message('error', 'Dashboard view error: ' . $e->getMessage());
            return view('Modules\ReconOrders\Views\recon_orders\dashboard_content', ['stats' => []]);
        }
    }
    
    // Get dashboard orders data
    public function getDashboardOrders()
    {
        try {
            // Get orders summary for dashboard
            $stats = $this->reconOrderModel->getDashboardStats();
            $recentOrders = $this->reconOrderModel->getRecentOrders(10);
            
            $data = [
                'stats' => $stats,
                'recent_orders' => $recentOrders
            ];
            
            return $this->response->setJSON(['data' => $data]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getDashboardOrders: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }
    
    // Today's orders content
    public function today_content()
    {
        // Check if it's an AJAX request for DataTables
        $isPost = strtolower($this->request->getMethod()) === 'post';
        $isAjax = $this->request->isAJAX() || 
                  $this->request->hasHeader('X-Requested-With') || 
                  $this->request->getPost('ajax') === true ||
                  $this->request->getPost('ajax') === 'true';
        
        if ($isPost && $isAjax) {
            $draw = $this->request->getPost('draw') ?? 1;
            $start = (int) ($this->request->getPost('start') ?? 0);
            $length = (int) ($this->request->getPost('length') ?? 10);
            $searchValue = $this->request->getPost('search')['value'] ?? '';
            
            try {
                $db = \Config\Database::connect();
                
                // Check if tables exist
                if (!$db->tableExists('recon_orders')) {
                    return $this->response->setJSON([
                        'draw' => $draw,
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => []
                    ]);
                }
                
                // Simple query first
                $builder = $db->table('recon_orders');
                
                // Join with clients and services if tables exist
                if ($db->tableExists('clients') && $db->tableExists('recon_services')) {
                    $builder->select('recon_orders.*, clients.name as client_name, recon_services.name as service_name, recon_services.color as service_color')
                           ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                           ->join('recon_services', 'recon_services.id = recon_orders.service_id', 'left');
                } else if ($db->tableExists('clients')) {
                    $builder->select('recon_orders.*, clients.name as client_name, "" as service_name, "" as service_color')
                           ->join('clients', 'clients.id = recon_orders.client_id', 'left');
                } else {
                    $builder->select('recon_orders.*, "" as client_name, "" as service_name, "" as service_color');
                }
                
                $builder->where('recon_orders.deleted_at IS NULL')
                       ->where('DATE(recon_orders.created_at)', date('Y-m-d'));
                
                // Apply search filter
                if (!empty($searchValue)) {
                    $builder->groupStart()
                        ->like('recon_orders.vehicle', $searchValue)
                        ->orLike('recon_orders.stock', $searchValue)
                        ->groupEnd();
                }
                
                // Get total count
                $totalRecords = $builder->countAllResults(false);
                
                // Apply pagination
                $builder->orderBy('recon_orders.id', 'DESC')
                    ->limit($length, $start);
                
                $orders = $builder->get()->getResultArray();
                
                // Format data for DataTables
                $data = [];
                foreach ($orders as $order) {
                    $data[] = [
                        'DT_RowData' => ['service-color' => $order['service_color'] ?? '#007bff'],
                        'id' => $order['id'],
                        'order_id' => 'RO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                        'client_name' => $order['client_name'] ?? 'N/A',
                        'stock' => $order['stock'] ?? 'N/A',
                        'service_name' => $order['service_name'] ?? 'N/A',
                        'vehicle' => $order['vehicle'] ?? 'N/A',
                        'vin_number' => $order['vin_number'] ?? 'N/A',
                        'date' => date('M d, Y', strtotime($order['created_at'] ?? 'now')),
                        'status' => '<span class="badge bg-' . $this->getStatusColor($order['status'] ?? 'pending') . '">' . ucfirst($order['status'] ?? 'pending') . '</span>',
                        'service_color' => $order['service_color'] ?? '#007bff'
                    ];
                }
                
                return $this->response->setJSON([
                    'draw' => $draw,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $totalRecords,
                    'data' => $data
                ]);
                
            } catch (\Exception $e) {
                log_message('error', 'Today content error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'draw' => $draw ?? 1,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Return view for GET requests
        try {
            // Load clients and services for the quick form
            $clients = [];
            $services = [];
            
            try {
                $clients = $this->clientModel->where('deleted_at IS NULL')->where('status', 'active')->findAll();
            } catch (\Exception $e) {
                log_message('error', 'Error loading clients for today content: ' . $e->getMessage());
            }
            
            try {
                $userType = session()->get('user_type') ?? 'staff';
                $services = $this->reconServiceModel->getServicesForUser($userType);
            } catch (\Exception $e) {
                log_message('error', 'Error loading services for today content: ' . $e->getMessage());
            }
            
            $data = [
                'clients' => $clients,
                'services' => $services
            ];
            
            return view('Modules\ReconOrders\Views\recon_orders\today_content', $data);
        } catch (\Exception $e) {
            log_message('error', 'Today view error: ' . $e->getMessage());
            return view('Modules\ReconOrders\Views\recon_orders\today_content', ['clients' => [], 'services' => []]);
        }
    }
    
    // All orders content
    public function all_orders_content()
    {
        // Check if it's an AJAX request for DataTables
        $isPost = strtolower($this->request->getMethod()) === 'post';
        $isAjax = $this->request->isAJAX() || 
                  $this->request->hasHeader('X-Requested-With') || 
                  $this->request->getPost('ajax') === true ||
                  $this->request->getPost('ajax') === 'true';
        
        if ($isPost && $isAjax) {
            $draw = $this->request->getPost('draw') ?? 1;
            $start = (int) ($this->request->getPost('start') ?? 0);
            $length = (int) ($this->request->getPost('length') ?? 10);
            $searchValue = $this->request->getPost('search')['value'] ?? '';
            
            try {
                $db = \Config\Database::connect();
                
                // Check if tables exist
                if (!$db->tableExists('recon_orders')) {
                    return $this->response->setJSON([
                        'draw' => $draw,
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => []
                    ]);
                }
                
                // Simple query first
                $builder = $db->table('recon_orders');
                
                // Join with clients and services if tables exist
                if ($db->tableExists('clients') && $db->tableExists('recon_services')) {
                    $builder->select('recon_orders.*, clients.name as client_name, recon_services.name as service_name, recon_services.color as service_color')
                           ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                           ->join('recon_services', 'recon_services.id = recon_orders.service_id', 'left');
                } else if ($db->tableExists('clients')) {
                    $builder->select('recon_orders.*, clients.name as client_name, "" as service_name, "" as service_color')
                           ->join('clients', 'clients.id = recon_orders.client_id', 'left');
                } else {
                    $builder->select('recon_orders.*, "" as client_name, "" as service_name, "" as service_color');
                }
                
                $builder->where('recon_orders.deleted_at IS NULL');
                
                // Apply search filter
                if (!empty($searchValue)) {
                    $builder->groupStart()
                        ->like('recon_orders.vehicle', $searchValue)
                        ->orLike('recon_orders.stock', $searchValue)
                        ->groupEnd();
                }
                
                // Get total count
                $totalRecords = $builder->countAllResults(false);
                
                // Apply pagination
                $builder->orderBy('recon_orders.id', 'DESC')
                    ->limit($length, $start);
                
                $orders = $builder->get()->getResultArray();
                
                // Format data for DataTables
                $data = [];
                foreach ($orders as $order) {
                    $data[] = [
                        'DT_RowData' => ['service-color' => $order['service_color'] ?? '#007bff'],
                        'id' => $order['id'],
                        'order_id' => 'RO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                        'client_name' => $order['client_name'] ?? 'N/A',
                        'stock' => $order['stock'] ?? 'N/A',
                        'service_name' => $order['service_name'] ?? 'N/A',
                        'vehicle' => $order['vehicle'] ?? 'N/A',
                        'vin_number' => $order['vin_number'] ?? 'N/A',
                        'date' => date('M d, Y', strtotime($order['created_at'] ?? 'now')),
                        'status' => '<span class="badge bg-' . $this->getStatusColor($order['status'] ?? 'pending') . '">' . ucfirst($order['status'] ?? 'pending') . '</span>',
                        'service_color' => $order['service_color'] ?? '#007bff'
                    ];
                }
                
                return $this->response->setJSON([
                    'draw' => $draw,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $totalRecords,
                    'data' => $data
                ]);
                
            } catch (\Exception $e) {
                log_message('error', 'All orders content error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'draw' => $draw ?? 1,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Return view for GET requests
        return view('Modules\ReconOrders\Views\recon_orders\all_orders_content');
    }
    
    // Deleted orders content
    public function deleted_content()
    {
        // Check if it's an AJAX request for DataTables
        $isPost = strtolower($this->request->getMethod()) === 'post';
        $isAjax = $this->request->isAJAX() || 
                  $this->request->hasHeader('X-Requested-With') || 
                  $this->request->getPost('ajax') === true ||
                  $this->request->getPost('ajax') === 'true';
        
        if ($isPost && $isAjax) {
            $draw = $this->request->getPost('draw') ?? 1;
            $start = (int) ($this->request->getPost('start') ?? 0);
            $length = (int) ($this->request->getPost('length') ?? 10);
            $searchValue = $this->request->getPost('search')['value'] ?? '';
            
            try {
                $db = \Config\Database::connect();
                
                // Check if tables exist
                if (!$db->tableExists('recon_orders')) {
                    return $this->response->setJSON([
                        'draw' => $draw,
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => []
                    ]);
                }
                
                // Simple query first
                $builder = $db->table('recon_orders');
                
                // Join with clients only if table exists
                if ($db->tableExists('clients')) {
                    $builder->select('recon_orders.*, clients.name as client_name')
                           ->join('clients', 'clients.id = recon_orders.client_id', 'left');
                } else {
                    $builder->select('recon_orders.*, "" as client_name');
                }
                
                $builder->where('recon_orders.deleted_at IS NOT NULL');
                
                // Apply search filter
                if (!empty($searchValue)) {
                    $builder->groupStart()
                        ->like('recon_orders.vehicle', $searchValue)
                        ->orLike('recon_orders.stock', $searchValue)
                        ->groupEnd();
                }
                
                // Get total count
                $totalRecords = $builder->countAllResults(false);
                
                // Apply pagination
                $builder->orderBy('recon_orders.deleted_at', 'DESC')
                    ->limit($length, $start);
                
                $orders = $builder->get()->getResultArray();
                
                // Format data for DataTables
                $data = [];
                foreach ($orders as $order) {
                    $data[] = [
                        'id' => $order['id'],
                        'order_id' => 'RO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                        'client_name' => $order['client_name'] ?? 'N/A',
                        'vehicle' => $order['vehicle'] ?? 'N/A',
                        'date' => date('M d, Y', strtotime($order['created_at'] ?? 'now')),
                        'deleted_at' => date('M d, Y', strtotime($order['deleted_at'] ?? 'now'))
                    ];
                }
                
                return $this->response->setJSON([
                    'draw' => $draw,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $totalRecords,
                    'data' => $data
                ]);
                
            } catch (\Exception $e) {
                log_message('error', 'Deleted content error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'draw' => $draw ?? 1,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Return view for GET requests
        return view('Modules\ReconOrders\Views\recon_orders\deleted_content');
    }
    
    // Get deleted orders
    public function getDeletedOrders()
    {
        try {
            $orders = $this->reconOrderModel
                ->select('recon_orders.*, clients.name as client_name')
                ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                ->where('recon_orders.deleted_at IS NOT NULL')
                ->orderBy('recon_orders.deleted_at', 'DESC')
                ->findAll();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $orders
            ]);
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error retrieving deleted orders: ' . $e->getMessage()
            ]);
        }
    }
    
    // Restore order
    public function restore($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }
        
        try {
            $order = $this->reconOrderModel->find($id);
            
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
            
            // Get user ID from authentication
            $userId = auth()->user()->id ?? session()->get('user_id') ?? 1;
            
            // Restore the order (set deleted_at to null)
            $this->reconOrderModel->update($id, [
                'deleted_at' => null,
                'updated_by' => $userId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            // Log activity
            $this->reconActivityModel->insert([
                'order_id' => $id,
                'user_id' => $userId,
                'action' => 'restored',
                'description' => 'Order restored from deleted state',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Order restored successfully'
            ]);
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error restoring order: ' . $e->getMessage()
            ]);
        }
    }
    
    // Permanent delete
    public function permanent_delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }
        
        try {
            $order = $this->reconOrderModel->find($id);
            
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
            
            // Delete related records first
            $this->reconCommentModel->where('order_id', $id)->delete();
            $this->reconNoteModel->where('order_id', $id)->delete();
            $this->reconActivityModel->where('order_id', $id)->delete();
            
            // Permanently delete the order
            $this->reconOrderModel->delete($id, true);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Order permanently deleted'
            ]);
            
        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error permanently deleting order: ' . $e->getMessage()
            ]);
        }
    }
    
    // Modal form
    public function modal_form()
    {
        $data = [
            'clients' => $this->clientModel->where('status', 'active')->orderBy('name', 'ASC')->findAll(),
            'services' => $this->reconServiceModel->getActiveServices()
        ];
        
        return view('Modules\ReconOrders\Views\recon_orders\modal_form', $data);
    }
    
    // Modal edit form
    public function modal_edit($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('recon_orders');
        }
        
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }

        try {
            // Get order with complete information
            $order = $this->reconOrderModel->select('recon_orders.*, clients.name as client_name')
                                         ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                                         ->where('recon_orders.id', $id)
                                         ->where('recon_orders.deleted_at IS NULL')
                                         ->first();
            
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
            
            $data = [
                'order' => $order,
                'clients' => $this->clientModel->where('status', 'active')->orderBy('name', 'ASC')->findAll(),
                'services' => $this->reconServiceModel->getActiveServices()
            ];
            
            return view('Modules\ReconOrders\Views\recon_orders\modal_edit', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in modal_edit: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading order for editing'
            ]);
        }
    }
    


    public function services_content()
    {
        // Check if it's an AJAX request for DataTables
        $isPost = strtolower($this->request->getMethod()) === 'post';
        $isAjax = $this->request->isAJAX() || 
                  $this->request->hasHeader('X-Requested-With') || 
                  $this->request->getPost('ajax') === true ||
                  $this->request->getPost('ajax') === 'true';
        
        if ($isPost && $isAjax) {
            $draw = $this->request->getPost('draw') ?? 1;
            $start = (int) ($this->request->getPost('start') ?? 0);
            $length = (int) ($this->request->getPost('length') ?? 10);
            $searchValue = $this->request->getPost('search')['value'] ?? '';
            
            // Filters
            $filters = [
                'client_id' => $this->request->getPost('client_id'),
                'is_active' => $this->request->getPost('is_active'),
                'show_in_form' => $this->request->getPost('show_in_form')
            ];
            
            try {
                $db = \Config\Database::connect();
                
                // Check if table exists
                if (!$db->tableExists('recon_services')) {
                    return $this->response->setJSON([
                        'draw' => $draw,
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => []
                    ]);
                }
                
                $builder = $db->table('recon_services');
                
                // Join with clients only if table exists
                if ($db->tableExists('clients')) {
                    $builder->select('recon_services.*, clients.name as client_name')
                           ->join('clients', 'clients.id = recon_services.client_id', 'left');
                } else {
                    $builder->select('recon_services.*, "" as client_name');
                }
                
                $builder->where('recon_services.deleted_at IS NULL');
                
                // Apply filters
                if (!empty($filters['client_id'])) {
                    $builder->where('recon_services.client_id', $filters['client_id']);
                }
                
                if ($filters['is_active'] !== '' && $filters['is_active'] !== null) {
                    $builder->where('recon_services.is_active', $filters['is_active']);
                }
                
                if ($filters['show_in_form'] !== '' && $filters['show_in_form'] !== null) {
                    $builder->where('recon_services.show_in_form', $filters['show_in_form']);
                }
                
                // Apply search filter
                if (!empty($searchValue)) {
                    $builder->groupStart()
                        ->like('recon_services.name', $searchValue)
                        ->orLike('recon_services.description', $searchValue)
                        ->orLike('clients.name', $searchValue)
                        ->groupEnd();
                }
                
                // Get total count
                $totalRecords = $builder->countAllResults(false);
                
                // Apply pagination
                $builder->orderBy('recon_services.name', 'ASC')
                    ->limit($length, $start);
                
                $services = $builder->get()->getResultArray();
                
                // Format data for DataTables
                $data = [];
                foreach ($services as $service) {
                    $clientName = $service['client_name'] ?? 'Global';
                    $statusBadge = ($service['is_active'] ?? 0) ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>';
                    $visibilityBadge = ($service['show_in_form'] ?? 1) ? 
                        '<span class="badge bg-info">Visible</span>' : 
                        '<span class="badge bg-secondary">Hidden</span>';
                    
                    // Color indicator
                    $color = $service['color'] ?? '#007bff';
                    
                    $row = [
                        'DT_RowData' => [
                            'color' => $color
                        ],
                        'id' => $service['id'],
                        'client_name' => $clientName,
                        'service_name' => $service['name'] ?? 'N/A',
                        'description' => $service['description'] ?? 'N/A',
                        'color' => $color,
                        'price' => number_format($service['price'] ?? 0, 2),
                        'status' => $statusBadge,
                        'visibility' => ($service['show_in_form'] ?? 1)
                    ];
                    
                    $data[] = $row;
                }
                
                return $this->response->setJSON([
                    'draw' => $draw,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $totalRecords,
                    'data' => $data
                ]);
                
            } catch (\Exception $e) {
                log_message('error', 'Services content error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'draw' => $draw ?? 1,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Return view for GET requests
        try {
            $data = [
                'clients' => []
            ];
            
            return view('Modules\ReconOrders\Views\recon_orders\services_content', $data);
        } catch (\Exception $e) {
            log_message('error', 'Services view error: ' . $e->getMessage());
            return view('Modules\ReconOrders\Views\recon_orders\services_content', ['clients' => []]);
        }
    }

    public function getServices()
    {
        try {
            $userType = session()->get('user_type') ?? 'staff';
            $services = $this->reconServiceModel->getServicesForUser($userType);
            return $this->response->setJSON([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Recon getServices - Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'data' => []
            ]);
        }
    }

    public function getServicesForClient($clientId)
    {
        try {
            $userType = session()->get('user_type') ?? 'client';
            $services = $this->reconServiceModel->getServicesForUser($userType, $clientId);
            return $this->response->setJSON([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Recon getServicesForClient - Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'data' => []
            ]);
        }
    }



    public function getActiveServices()
    {
        try {
            $userType = session()->get('user_type') ?? 'client';
            $services = $this->reconServiceModel->getActiveServices(null, $userType);
            return $this->response->setJSON([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Recon getActiveServices - Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'data' => []
            ]);
        }
    }

    /**
     * Save order services (many-to-many relationship)
     */
    private function saveOrderServices($orderId, $services)
    {
        $db = \Config\Database::connect();
        
        // Delete existing services for this order
        $db->table('recon_order_services')->where('order_id', $orderId)->delete();
        
        // Insert new services
        foreach ($services as $serviceData) {
            $data = [
                'order_id' => $orderId,
                'service_id' => $serviceData['service_id'],
                'quantity' => $serviceData['quantity'] ?? 1,
                'price' => $serviceData['price'] ?? 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $db->table('recon_order_services')->insert($data);
        }
    }

    /**
     * Get current user ID with fallback
     */
    private function getCurrentUserId()
    {
        return auth()->user()->id ?? session()->get('user_id') ?? 1;
    }

    // Add force_delete method to match the route
    public function force_delete($id = null)
    {
        return $this->permanent_delete($id);
    }

    /**
     * Services view page
     */
    public function servicesView($id = null)
    {
        if (!$id) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Service not found');
        }

        try {
            $service = $this->reconServiceModel->find($id);
            
            if (!$service) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Service not found');
            }

            // Get client name if service has client_id
            if ($service['client_id']) {
                $db = \Config\Database::connect();
                if ($db->tableExists('clients')) {
                    $client = $db->table('clients')->where('id', $service['client_id'])->get()->getRowArray();
                    $service['client_name'] = $client['name'] ?? 'Unknown Client';
                }
            }

            // Get usage statistics
            $stats = $this->getServiceStats($id);

            // Get recent orders using this service
            $recentOrders = $this->getRecentOrdersForService($id);

            $data = [
                'title' => $service['name'],
                'service' => $service,
                'stats' => $stats,
                'recentOrders' => $recentOrders
            ];

            return view('Modules\ReconOrders\Views\recon_orders\services_view', $data);

        } catch (\Exception $e) {
            log_message('error', 'Service view error: ' . $e->getMessage());
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Service not found');
        }
    }

    /**
     * Get service data for AJAX requests
     */
    public function servicesShow($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Service ID required']);
        }

        try {
            $service = $this->reconServiceModel->find($id);
            
            if (!$service) {
                return $this->response->setJSON(['success' => false, 'message' => 'Service not found']);
            }

            return $this->response->setJSON(['success' => true, 'service' => $service]);

        } catch (\Exception $e) {
            log_message('error', 'Service show error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error loading service']);
        }
    }

    /**
     * Store new service
     */
    public function servicesStore()
    {
        try {
            $data = $this->request->getPost();
            
            // Convert checkbox values
            $data['is_active'] = isset($data['is_active']) ? 1 : 0;
            $data['show_in_form'] = isset($data['show_in_form']) ? 1 : 0;
            
            // Set client_id to null if empty
            if (empty($data['client_id'])) {
                $data['client_id'] = null;
            }

            if ($this->reconServiceModel->save($data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Service created successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to create service']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Service store error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error creating service']);
        }
    }

    /**
     * Update service
     */
    public function servicesUpdate($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Service ID required']);
        }

        try {
            $data = $this->request->getPost();
            
            // Convert checkbox values
            $data['is_active'] = isset($data['is_active']) ? 1 : 0;
            $data['show_in_form'] = isset($data['show_in_form']) ? 1 : 0;
            
            // Set client_id to null if empty
            if (empty($data['client_id'])) {
                $data['client_id'] = null;
            }

            if ($this->reconServiceModel->update($id, $data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Service updated successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update service']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Service update error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error updating service']);
        }
    }

    /**
     * Delete service
     */
    public function servicesDelete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Service ID required']);
        }

        try {
            if ($this->reconServiceModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Service deleted successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete service']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Service delete error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error deleting service']);
        }
    }

    /**
     * Toggle service status
     */
    public function servicesToggleStatus($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Service ID required']);
        }

        try {
            $newStatus = $this->request->getPost('is_active');
            
            if ($this->reconServiceModel->update($id, ['is_active' => $newStatus])) {
                return $this->response->setJSON(['success' => true, 'message' => 'Service status updated successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update service status']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Service toggle status error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error updating service status']);
        }
    }

    /**
     * Toggle service visibility
     */
    public function servicesToggleVisibility($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Service ID required']);
        }

        try {
            $newVisibility = $this->request->getPost('show_in_form');
            
            if ($this->reconServiceModel->update($id, ['show_in_form' => $newVisibility])) {
                return $this->response->setJSON(['success' => true, 'message' => 'Service visibility updated successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update service visibility']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Service toggle visibility error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error updating service visibility']);
        }
    }

    /**
     * Get service usage statistics
     */
    private function getServiceStats($serviceId)
    {
        $db = \Config\Database::connect();
        
        $stats = [
            'total_orders' => 0,
            'completed_orders' => 0,
            'total_revenue' => 0
        ];

        try {
            if ($db->tableExists('recon_orders')) {
                // Get total orders using this service
                $totalOrders = $db->table('recon_orders')
                    ->where('service_id', $serviceId)
                    ->where('deleted_at IS NULL')
                    ->countAllResults();
                
                $stats['total_orders'] = $totalOrders;

                // Get completed orders
                $completedOrders = $db->table('recon_orders')
                    ->where('service_id', $serviceId)
                    ->where('status', 'completed')
                    ->where('deleted_at IS NULL')
                    ->countAllResults();
                
                $stats['completed_orders'] = $completedOrders;

                // Calculate total revenue (if there's a price field)
                $service = $this->reconServiceModel->find($serviceId);
                if ($service && isset($service['price'])) {
                    $stats['total_revenue'] = $completedOrders * $service['price'];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error getting service stats: ' . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Get recent orders for a service
     */
    private function getRecentOrdersForService($serviceId, $limit = 10)
    {
        $db = \Config\Database::connect();
        $orders = [];

        try {
            if ($db->tableExists('recon_orders') && $db->tableExists('clients')) {
                $orders = $db->table('recon_orders')
                    ->select('recon_orders.id, recon_orders.stock, recon_orders.vehicle, recon_orders.status, recon_orders.created_at, clients.name as client_name')
                    ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                    ->where('recon_orders.service_id', $serviceId)
                    ->where('recon_orders.deleted_at IS NULL')
                    ->orderBy('recon_orders.created_at', 'DESC')
                    ->limit($limit)
                    ->get()
                    ->getResultArray();

                // Add order number for each order
                foreach ($orders as &$order) {
                    $order['order_number'] = 'RO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error getting recent orders for service: ' . $e->getMessage());
        }

        return $orders;
    }

    // Helper method to get status color
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return 'warning';
            case 'in_progress':
                return 'info';
            case 'completed':
                return 'success';
            case 'cancelled':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    // ========================================
    // COMMENTS ENDPOINTS
    // ========================================

        /**
     * Get comments for an order with pagination
     */
    public function getComments($id = null)
    {
        log_message('info', '🔍 getComments method called with ID: ' . ($id ?? 'NULL'));
        
        if (!$id) {
            log_message('error', 'getComments: No order ID provided');
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }

        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 5; // Show 5 comments per page for infinite scroll

        log_message('info', "getComments: Loading comments for order {$id}, page {$page}");

        try {
            // Get total comments first
            $totalComments = $this->reconCommentModel->getCommentsCount($id);
            $offset = ($page - 1) * $perPage;
            
            // Get comments with replies and pagination
            $comments = $this->reconCommentModel->getCommentsWithReplies($id, $perPage, $offset);
            
            // Simple and reliable pagination logic (same as Sales Orders)
            $hasMore = ($offset + $perPage) < $totalComments;
            
            // Add debug logging
            $loadedCount = count($comments);
            $totalLoaded = $offset + $loadedCount;
            log_message('info', "Comments pagination - Page: {$page}, Offset: {$offset}, PerPage: {$perPage}, TotalComments: {$totalComments}, LoadedComments: {$loadedCount}, TotalLoaded: {$totalLoaded}, HasMore: " . ($hasMore ? 'true' : 'false'));

            // Process comments data (including replies)
            foreach ($comments as &$comment) {
                // Format user name
                if (!empty($comment['first_name']) || !empty($comment['last_name'])) {
                    $comment['user_name'] = trim($comment['first_name'] . ' ' . $comment['last_name']);
                } else {
                    $comment['user_name'] = 'User ' . ($comment['user_id'] ?? 'Unknown');
                }
                
                // Add display fields
                $comment['user_avatar'] = null;
                $comment['created_at_relative'] = $this->formatRelativeTime($comment['created_at']);
                $comment['created_at_formatted'] = date('M j, Y g:i A', strtotime($comment['created_at']));
                
                // Parse mentions
                $comment['mentions'] = json_decode($comment['mentions'] ?? '[]', true);
                
                // Process replies
                if (isset($comment['replies']) && is_array($comment['replies'])) {
                    foreach ($comment['replies'] as &$reply) {
                        // Format user name for reply
                        if (!empty($reply['first_name']) || !empty($reply['last_name'])) {
                            $reply['user_name'] = trim($reply['first_name'] . ' ' . $reply['last_name']);
                        } else {
                            $reply['user_name'] = 'User ' . ($reply['user_id'] ?? 'Unknown');
                        }
                        
                        $reply['user_avatar'] = null;
                        $reply['created_at_relative'] = $this->formatRelativeTime($reply['created_at']);
                        $reply['created_at_formatted'] = date('M j, Y g:i A', strtotime($reply['created_at']));
                        
                        // Parse mentions
                        $reply['mentions'] = json_decode($reply['mentions'] ?? '[]', true);
                    }
                }
            }

            log_message('info', "getComments: Found {$totalComments} total comments, returning " . count($comments) . " for page {$page}");

            return $this->response->setJSON([
                'success' => true,
                'comments' => $comments,
                'pagination' => [
                    'current_page' => $page,
                    'total' => $totalComments,
                    'has_more' => $hasMore
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting comments: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error loading comments']);
        }
    }

    /**
     * Test method to verify routes are working
     */
    public function testComments($id = null)
    {
        log_message('info', '🧪 testComments method called with ID: ' . ($id ?? 'NULL'));
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Test method working',
            'order_id' => $id,
            'timestamp' => date('Y-m-d H:i:s'),
            'user_logged_in' => auth()->loggedIn()
        ]);
    }

    /**
     * Add a comment to an order
     */
    public function addComment($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }

        helper('auth');
        $userId = auth()->id();
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        $comment = trim($this->request->getPost('comment'));
        if (empty($comment)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comment content is required']);
        }

        try {
            // Process mentions
            $mentions = $this->reconCommentModel->processMentions($comment);
            
            // Handle file uploads
            $attachments = [];
            $uploadedFiles = $this->request->getFiles();
            
            if (!empty($uploadedFiles['attachments'])) {
                $attachments = $this->reconCommentModel->processAttachments($uploadedFiles['attachments'], $id);
            }
            
            // Prepare comment data
            $commentData = [
                'order_id' => $id,
                'user_id' => $userId,
                'comment' => $comment,
                'attachments' => !empty($attachments) ? json_encode($attachments) : json_encode([]),
                'mentions' => !empty($mentions) ? json_encode($mentions) : json_encode([]),
                'metadata' => json_encode([
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => $this->request->getUserAgent()->getAgentString(),
                    'timestamp' => date('Y-m-d H:i:s')
                ])
            ];

            $commentId = $this->reconCommentModel->insert($commentData);

            if ($commentId) {
                // Get the complete comment with user info for response
                $newComment = $this->reconCommentModel->getCommentWithUser($commentId);
                
                // Process attachments and mentions for display
                $newComment['attachments'] = $this->reconCommentModel->processAttachmentsJson($newComment['attachments'], $id);
                $newComment['mentions'] = json_decode($newComment['mentions'] ?? '[]', true);
                $newComment['created_at_relative'] = $this->formatRelativeTime($newComment['created_at']);
                $newComment['created_at_formatted'] = date('M j, Y g:i A', strtotime($newComment['created_at']));
                
                // Log detailed activity with preview
                try {
                    $preview = strlen($comment) > 100 ? substr($comment, 0, 100) . '...' : $comment;
                    $description = "Added comment: \"{$preview}\"";
                    
                    $metadata = [
                        'type' => 'comment',
                        'action' => 'added',
                        'preview' => $preview,
                        'comment_id' => $commentId,
                        'has_attachments' => !empty($attachments),
                        'has_mentions' => !empty($mentions),
                        'attachment_count' => count($attachments),
                        'mention_count' => count($mentions)
                    ];
                    
                    $oldValues = []; // No old values for new comment
                    $newValues = [
                        'comment' => $comment,
                        'attachments' => $attachments,
                        'mentions' => $mentions
                    ];
                    
                    $this->reconActivityModel->logActivity(
                        $id, 
                        $userId, 
                        'comment_added', 
                        $description,
                        $oldValues,
                        $newValues,
                        $metadata
                    );
                } catch (\Exception $e) {
                    log_message('error', 'Error logging comment activity: ' . $e->getMessage());
                }

                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Comment added successfully',
                    'comment' => $newComment
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add comment']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error adding comment: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error adding comment: ' . $e->getMessage()]);
        }
    }

    /**
     * Add a reply to a comment
     */
    public function addReply()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        helper('auth');
        $userId = auth()->id();
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        try {
            $orderId = $this->request->getPost('order_id');
            $parentCommentId = $this->request->getPost('parent_id') ?: $this->request->getPost('parent_comment_id');
            $comment = trim($this->request->getPost('comment'));
            
            if (empty($comment)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Reply content is required']);
            }

            // Verify parent comment exists
            $parentComment = $this->reconCommentModel->find($parentCommentId);
            if (!$parentComment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Parent comment not found']);
            }

            // Process mentions
            $mentions = $this->reconCommentModel->processMentions($comment);

            $data = [
                'order_id' => $orderId,
                'parent_id' => $parentCommentId,
                'comment' => $comment,
                'user_id' => $userId,
                'attachments' => json_encode([]), // For now, replies don't support attachments
                'mentions' => !empty($mentions) ? json_encode($mentions) : json_encode([]),
                'metadata' => json_encode([])
            ];

            $replyId = $this->reconCommentModel->insert($data);

            if ($replyId) {
                // Get the complete reply with user info for response
                $newReply = $this->reconCommentModel->getCommentWithUser($replyId);
                
                // Process for display
                $newReply['mentions'] = json_decode($newReply['mentions'] ?? '[]', true);
                $newReply['created_at_relative'] = $this->formatRelativeTime($newReply['created_at']);
                $newReply['created_at_formatted'] = date('M j, Y g:i A', strtotime($newReply['created_at']));

                // Log detailed activity with preview
                try {
                    $preview = strlen($comment) > 100 ? substr($comment, 0, 100) . '...' : $comment;
                    $description = "Added reply to comment: \"{$preview}\"";
                    
                    $metadata = [
                        'type' => 'reply',
                        'action' => 'added',
                        'preview' => $preview,
                        'comment_id' => $replyId,
                        'parent_comment_id' => $parentCommentId,
                        'has_mentions' => !empty($mentions),
                        'mention_count' => count($mentions)
                    ];
                    
                    $oldValues = []; // No old values for new reply
                    $newValues = [
                        'comment' => $comment,
                        'mentions' => $mentions,
                        'parent_id' => $parentCommentId
                    ];
                    
                    $this->reconActivityModel->logActivity(
                        $orderId, 
                        $userId, 
                        'reply_added', 
                        $description,
                        $oldValues,
                        $newValues,
                        $metadata
                    );
                } catch (\Exception $e) {
                    log_message('error', 'Error logging reply activity: ' . $e->getMessage());
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Reply added successfully',
                    'comment' => $newReply
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add reply']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error adding reply: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error adding reply: ' . $e->getMessage()]);
        }
    }

    // ========================================
    // FOLLOWERS ENDPOINTS
    // ========================================

    /**
     * Get followers for an order
     */
    public function getFollowers($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }

        try {
            $db = \Config\Database::connect();
            
            if (!$db->tableExists('recon_followers')) {
                return $this->response->setJSON([
                    'success' => true,
                    'followers' => [],
                    'count' => 0
                ]);
            }

            $followers = $db->table('recon_followers')
                ->select('recon_followers.*, users.first_name, users.last_name, users.avatar, auth_identities.secret as email')
                ->join('users', 'users.id = recon_followers.user_id AND users.deleted_at IS NULL', 'left')
                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                ->where('recon_followers.order_id', $id)
                ->where('recon_followers.deleted_at IS NULL')
                ->get()
                ->getResultArray();

            // Format followers
            foreach ($followers as &$follower) {
                $follower['user_name'] = ($follower['first_name'] ?? '') . ' ' . ($follower['last_name'] ?? '');
                $follower['type'] = $follower['type'] ?? 'staff';
            }

            return $this->response->setJSON([
                'success' => true,
                'followers' => $followers,
                'count' => count($followers)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting followers: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error loading followers']);
        }
    }

    /**
     * Get available users to add as followers
     */
    public function getAvailableFollowers($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }

        try {
            $db = \Config\Database::connect();
            
            // Get current followers
            $currentFollowers = [];
            if ($db->tableExists('recon_followers')) {
                $currentFollowers = $db->table('recon_followers')
                    ->select('user_id')
                    ->where('order_id', $id)
                    ->where('deleted_at IS NULL')
                    ->get()
                    ->getResultArray();
                $currentFollowers = array_column($currentFollowers, 'user_id');
            }

            // Get available users (excluding current followers)
            $builder = $db->table('users')
                ->select('users.id, users.first_name, users.last_name, auth_identities.secret as email')
                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                ->where('users.deleted_at IS NULL');

            if (!empty($currentFollowers)) {
                $builder->whereNotIn('id', $currentFollowers);
            }

            $users = $builder->get()->getResultArray();

            // Format users
            foreach ($users as &$user) {
                $user['name'] = ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '');
            }

            return $this->response->setJSON([
                'success' => true,
                'users' => $users
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting available followers: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error loading users']);
        }
    }

    /**
     * Add a follower to an order
     */
    public function addFollower()
    {
        $input = json_decode($this->request->getBody(), true);
        
        if (!isset($input['order_id']) || !isset($input['user_id'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID and User ID required']);
        }

        try {
            $db = \Config\Database::connect();
            
            // Create followers table if it doesn't exist
            if (!$db->tableExists('recon_followers')) {
                $forge = \Config\Database::forge();
                $forge->addField([
                    'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                    'order_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                    'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                    'type' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'staff'],
                    'notifications' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                    'created_at' => ['type' => 'DATETIME'],
                    'updated_at' => ['type' => 'DATETIME', 'null' => true],
                    'deleted_at' => ['type' => 'DATETIME', 'null' => true]
                ]);
                $forge->addPrimaryKey('id');
                $forge->createTable('recon_followers');
            }

            $data = [
                'order_id' => $input['order_id'],
                'user_id' => $input['user_id'],
                'type' => $input['type'] ?? 'staff',
                'notifications' => $input['notifications'] ?? 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $db->table('recon_followers')->insert($data);

            return $this->response->setJSON(['success' => true, 'message' => 'Follower added successfully']);

        } catch (\Exception $e) {
            log_message('error', 'Error adding follower: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error adding follower']);
        }
    }

    /**
     * Remove a follower from an order
     */
    public function removeFollower($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Follower ID required']);
        }

        try {
            $db = \Config\Database::connect();
            
            if ($db->tableExists('recon_followers')) {
                $db->table('recon_followers')
                    ->where('id', $id)
                    ->update(['deleted_at' => date('Y-m-d H:i:s')]);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Follower removed successfully']);

        } catch (\Exception $e) {
            log_message('error', 'Error removing follower: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error removing follower']);
        }
    }

    // ========================================
    // ACTIVITY ENDPOINTS
    // ========================================

    /**
     * Get recent activity for an order with enhanced infinite scroll support
     */
    public function getActivity($id = null)
    {
        log_message('info', '🔍 getActivity method called with ID: ' . ($id ?? 'NULL'));
        
        if (!$id) {
            log_message('error', 'getActivity: No order ID provided');
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }

        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10; // Show 10 activities per page for infinite scroll

        log_message('info', "getActivity: Loading activities for order {$id}, page {$page}");

        try {
            $db = \Config\Database::connect();
            
            if (!$db->tableExists('recon_activity')) {
                return $this->response->setJSON([
                    'success' => true,
                    'activities' => [],
                    'pagination' => [
                        'current_page' => 1,
                        'total' => 0,
                        'has_more' => false
                    ]
                ]);
            }

            // Get total count
            $total = $db->table('recon_activity')
                ->where('order_id', $id)
                ->countAllResults();

            $offset = ($page - 1) * $perPage;

            // Get activities with user info and enhanced formatting
            $activities = $db->table('recon_activity')
                ->select('recon_activity.*, users.first_name, users.last_name, users.avatar')
                ->join('users', 'users.id = recon_activity.user_id', 'left')
                ->where('recon_activity.order_id', $id)
                ->orderBy('recon_activity.created_at', 'DESC')
                ->limit($perPage, $offset)
                ->get()
                ->getResultArray();

            // Enhanced formatting for activities with preview support
            foreach ($activities as &$activity) {
                // Format user name
                $activity['user_name'] = trim(($activity['first_name'] ?? '') . ' ' . ($activity['last_name'] ?? ''));
                if (empty($activity['user_name'])) {
                    $activity['user_name'] = 'System';
                }
                
                // Parse metadata if exists
                $metadata = json_decode($activity['metadata'] ?? '{}', true);
                $oldValues = json_decode($activity['old_values'] ?? '{}', true);
                $newValues = json_decode($activity['new_values'] ?? '{}', true);
                
                // Enhanced title and description with preview
                $activity['title'] = $this->getActivityTitle($activity['action'], $metadata);
                $activity['description'] = $this->getActivityDescription($activity, $metadata, $oldValues, $newValues);
                $activity['activity_type'] = $this->getActivityType($activity['action']);
                
                // Add formatted timestamp
                $activity['created_at_relative'] = $this->formatRelativeTime($activity['created_at']);
                $activity['created_at_formatted'] = date('M j, Y g:i A', strtotime($activity['created_at']));
                
                // Add preview information
                $activity['preview'] = $metadata['preview'] ?? $metadata['old_preview'] ?? $metadata['new_preview'] ?? null;
                
                // Add change details for display
                if (!empty($oldValues) || !empty($newValues)) {
                    $activity['has_changes'] = true;
                    $activity['old_values_formatted'] = $this->formatValuesForDisplay($oldValues);
                    $activity['new_values_formatted'] = $this->formatValuesForDisplay($newValues);
                }
            }

            // Simple and reliable pagination logic
            $hasMore = ($offset + $perPage) < $total;
            
            // Add debug logging
            $loadedCount = count($activities);
            $totalLoaded = $offset + $loadedCount;
            log_message('info', "Activities pagination - Page: {$page}, Offset: {$offset}, PerPage: {$perPage}, TotalActivities: {$total}, LoadedActivities: {$loadedCount}, TotalLoaded: {$totalLoaded}, HasMore: " . ($hasMore ? 'true' : 'false'));

            log_message('info', "getActivity: Found {$total} total activities, returning " . count($activities) . " for page {$page}");

            return $this->response->setJSON([
                'success' => true,
                'activities' => $activities,
                'pagination' => [
                    'current_page' => $page,
                    'total' => $total,
                    'has_more' => $hasMore,
                    'per_page' => $perPage,
                    'loaded_count' => $loadedCount
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting activity: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error loading activity: ' . $e->getMessage()]);
        }
    }

    /**
     * Get activity title based on action and metadata
     */
    private function getActivityTitle($action, $metadata = [])
    {
        $titles = [
            'comment_added' => 'Comment Added',
            'reply_added' => 'Reply Added',
            'comment_updated' => 'Comment Updated',
            'comment_deleted' => 'Comment Deleted',
            'status_change' => 'Status Changed',
            'field_change' => 'Field Updated',
            'pictures_change' => 'Pictures Updated',
            'order_created' => 'Order Created',
            'order_updated' => 'Order Updated',
            'order_deleted' => 'Order Deleted'
        ];
        
        return $titles[$action] ?? ucwords(str_replace('_', ' ', $action));
    }

    /**
     * Get activity type for icon display
     */
    private function getActivityType($action)
    {
        $typeMap = [
            'comment_added' => 'comment',
            'reply_added' => 'comment',
            'comment_updated' => 'comment',
            'comment_deleted' => 'comment',
            'status_change' => 'status',
            'field_change' => 'edit',
            'pictures_change' => 'picture',
            'order_created' => 'created',
            'order_updated' => 'updated',
            'order_deleted' => 'deleted'
        ];
        
        return $typeMap[$action] ?? 'general';
    }

    /**
     * Get enhanced activity description with preview and changes
     */
    private function getActivityDescription($activity, $metadata = [], $oldValues = [], $newValues = [])
    {
        $description = $activity['description'] ?? '';
        
        // Check if this is a comment-related activity and apply truncation logic
        $commentActions = ['comment_added', 'reply_added', 'comment_updated', 'comment_deleted'];
        if (in_array($activity['action'], $commentActions)) {
            // For comment activities, always regenerate description with proper truncation
            // Don't rely on existing description field
        } else {
            // For non-comment activities, use existing description if available
            if (!empty($description) && strlen($description) > 10) {
                return $description;
            }
        }
        
        // Generate description based on action and metadata
        switch ($activity['action']) {
            case 'comment_added':
                if (isset($metadata['preview'])) {
                    $preview = $metadata['preview'];
                    // For comment descriptions, truncate to last 25 characters with tooltip support
                    if (strlen($preview) > 25) {
                        return [
                            'text' => 'Added comment: "...' . substr($preview, -25) . '"',
                            'full' => 'Added comment: "' . $preview . '"',
                            'is_truncated' => true
                        ];
                    }
                    return "Added comment: \"{$preview}\"";
                }
                return "Added a new comment";
                    
            case 'reply_added':
                if (isset($metadata['preview'])) {
                    $preview = $metadata['preview'];
                    // For reply descriptions, truncate to last 25 characters with tooltip support
                    if (strlen($preview) > 25) {
                        return [
                            'text' => 'Added reply: "...' . substr($preview, -25) . '"',
                            'full' => 'Added reply: "' . $preview . '"',
                            'is_truncated' => true
                        ];
                    }
                    return "Added reply: \"{$preview}\"";
                }
                return "Added a reply to a comment";
                    
            case 'comment_updated':
                if (isset($metadata['old_preview']) && isset($metadata['new_preview'])) {
                    $oldPreview = $metadata['old_preview'];
                    $newPreview = $metadata['new_preview'];
                    
                    // Truncate both old and new previews if needed
                    if (strlen($oldPreview) > 25) {
                        $oldPreview = '...' . substr($oldPreview, -25);
                    }
                    if (strlen($newPreview) > 25) {
                        $newPreview = '...' . substr($newPreview, -25);
                    }
                    
                    $description = "Updated comment from: \"{$oldPreview}\" to: \"{$newPreview}\"";
                    
                    // If either preview was truncated, provide full tooltip
                    if (strlen($metadata['old_preview']) > 25 || strlen($metadata['new_preview']) > 25) {
                        return [
                            'text' => $description,
                            'full' => "Updated comment from: \"{$metadata['old_preview']}\" to: \"{$metadata['new_preview']}\"",
                            'is_truncated' => true
                        ];
                    }
                    
                    return $description;
                }
                return "Updated a comment";
                
            case 'comment_deleted':
                if (isset($metadata['preview'])) {
                    $preview = $metadata['preview'];
                    // For deleted comment descriptions, truncate to last 25 characters with tooltip support
                    if (strlen($preview) > 25) {
                        return [
                            'text' => 'Deleted comment: "...' . substr($preview, -25) . '"',
                            'full' => 'Deleted comment: "' . $preview . '"',
                            'is_truncated' => true
                        ];
                    }
                    return "Deleted comment: \"{$preview}\"";
                }
                return "Deleted a comment";
                
            case 'status_change':
                if (!empty($oldValues['status']) && !empty($newValues['status'])) {
                    return "Changed status from " . ucwords(str_replace('_', ' ', $oldValues['status'])) . 
                           " to " . ucwords(str_replace('_', ' ', $newValues['status']));
                }
                return "Status was changed";
                
            default:
                // For any other activity, check if description is too long and truncate if needed
                if (!empty($description)) {
                    // Check if this looks like a comment description (contains quotes)
                    if (preg_match('/.*[""""](.+)[""""].*/', $description, $matches) && isset($matches[1])) {
                        $commentText = $matches[1];
                        if (strlen($commentText) > 25) {
                            // Extract the pattern and truncate the comment part
                            $pattern = str_replace($matches[1], '...PLACEHOLDER...', $description);
                            $truncatedComment = '...' . substr($commentText, -25);
                            $finalDescription = str_replace('...PLACEHOLDER...', $truncatedComment, $pattern);
                            
                            return [
                                'text' => $finalDescription,
                                'full' => $description,
                                'is_truncated' => true
                            ];
                        }
                    }
                    return $description;
                }
                return "Performed action: " . ucwords(str_replace('_', ' ', $activity['action']));
        }
    }

    /**
     * Format values for display in activity
     */
    private function formatValuesForDisplay($values)
    {
        if (empty($values) || !is_array($values)) {
            return [];
        }
        
        $formatted = [];
        foreach ($values as $key => $value) {
            // Only show comments field in the changes section
            if ($key !== 'comment') {
                continue;
            }
            
            if (is_array($value)) {
                $formatted[$key] = count($value) . ' items';
            } else {
                $displayValue = $this->getDisplayValueForField($key, $value);
                
                // For comment fields, create truncated version with full content for tooltip
                if ($key === 'comment' && is_string($displayValue) && strlen($displayValue) > 25) {
                    $formatted[$key] = [
                        'truncated' => substr($displayValue, 0, 25) . '...',
                        'full' => $displayValue,
                        'is_truncated' => true
                    ];
                } elseif (is_string($displayValue) && strlen($displayValue) > 50) {
                    // For other long fields, use the original truncation
                    $formatted[$key] = substr($displayValue, 0, 50) . '...';
                } else {
                    $formatted[$key] = $displayValue;
                }
            }
        }
        
        return $formatted;
    }

    /**
     * Get display value for a specific field
     */
    private function getDisplayValueForField($fieldName, $value)
    {
        // Convert IDs to readable names and format special fields
        switch ($fieldName) {
            case 'client_id':
                if ($value) {
                    $client = $this->clientModel->find($value);
                    return $client ? $client['name'] : $value;
                }
                break;
            
            case 'assigned_to':
                if ($value) {
                    $user = $this->userModel->find($value);
                    return $user ? trim($user['first_name'] . ' ' . $user['last_name']) : $value;
                }
                break;
            
            case 'status':
                return ucfirst(str_replace('_', ' ', $value));
            
            case 'pictures':
                return $value ? 'Pictures Done' : 'No pictures';
            
            case 'service_id':
                if ($value) {
                    $service = $this->reconServiceModel->find($value);
                    return $service ? $service['name'] : $value;
                }
                break;
            
            default:
                return $value;
        }
        
        return $value;
    }

    // ========================================
    // QR CODE ENDPOINTS
    // ========================================

    /**
     * Regenerate QR code for an order
     */
    public function regenerateQR($orderId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            if (!$orderId) {
                $orderId = $this->request->getPost('order_id');
            }

            if (!$orderId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID is required'
                ]);
            }

            log_message('info', "Regenerating QR for recon order ID: {$orderId}");

            // Check if order exists
            $order = $this->reconOrderModel->find($orderId);
            if (!$order) {
                log_message('error', "Recon order {$orderId} not found for QR regeneration");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Recon order not found'
                ]);
            }

            log_message('info', "Recon order {$orderId} found, clearing existing QR data");

            // Clear existing QR data to force regeneration
            $updateResult = $this->reconOrderModel->update($orderId, [
                'short_url' => null,
                'short_url_slug' => null,
                'lima_link_id' => null,
                'qr_generated_at' => null
            ]);

            if (!$updateResult) {
                log_message('error', "Failed to clear QR data for recon order {$orderId}");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to clear existing QR data'
                ]);
            }

            log_message('info', "QR data cleared for recon order {$orderId}, generating new QR");

            // Generate new QR code with simplified method
            $qrData = $this->getQRDataForOrderSimple($orderId);

            if ($qrData) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'QR Code regenerated successfully with MDA Links!',
                    'qr_data' => $qrData
                ]);
            } else {
                log_message('error', "QR generation failed for recon order {$orderId}");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to generate QR code. Please check MDA Links configuration.'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error regenerating QR code: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error regenerating QR code: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate QR code for an order
     */
    public function generateQRCode($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }

        try {
            // Use dynamic Lima Links API to generate QR
            $qr_data = $this->getQRDataForOrder($id);
            
            if ($qr_data) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'QR Code generated successfully',
                    'qr_url' => $qr_data['qr_url'],
                    'short_url' => $qr_data['short_url']
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Could not generate QR code. Please check MDA Links configuration.'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error generating QR code: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error generating QR code']);
        }
    }

    /**
     * Get QR Code data for an order with MDA Links integration (Simplified)
     */
    private function getQRDataForOrderSimple($orderId)
    {
        try {
            $order = $this->reconOrderModel->find($orderId);
            if (!$order) {
                log_message('error', "Recon Order {$orderId} not found");
                return null;
            }
            
            $orderUrl = base_url("recon_orders/view/{$orderId}");
            $shortUrl = $orderUrl; // Default fallback
            
            // Check if we already have a short URL
            if (!empty($order['short_url'])) {
                $shortUrl = $order['short_url'];
                log_message('info', "Using existing short URL for recon order {$orderId}: {$shortUrl}");
            }
            
            // Always generate QR using external service for reliability
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($shortUrl);
            
            // Update timestamp
            $this->reconOrderModel->update($orderId, [
                'qr_generated_at' => date('Y-m-d H:i:s')
            ]);
            
            return [
                'qr_url' => $qrUrl,
                'short_url' => $shortUrl,
                'shortener' => $shortUrl !== $orderUrl ? 'MDA Links' : 'Direct URL',
                'is_static' => $shortUrl !== $orderUrl
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting QR data (simple): ' . $e->getMessage());
            
            // Emergency fallback
            $orderUrl = base_url("recon_orders/view/{$orderId}");
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($orderUrl);
            
            return [
                'qr_url' => $qrUrl,
                'short_url' => $orderUrl,
                'shortener' => 'Direct URL (Emergency)',
                'is_static' => false
            ];
        }
    }

    /**
     * Get QR Code data for an order with Lima Links integration
     */
    private function getQRDataForOrder($orderId)
    {
        try {
            $order = $this->reconOrderModel->find($orderId);
            if (!$order) {
                log_message('error', "Recon Order {$orderId} not found");
                return null;
            }
            
            $shortUrl = null;
            $linkId = null;
            $orderUrl = base_url("recon_orders/view/{$orderId}");
            
            // Initialize settings
            $settingsModel = new \App\Models\SettingsModel();
            $apiKey = $settingsModel->getSetting('lima_api_key');
            $brandedDomain = $settingsModel->getSetting('lima_branded_domain');
            
            // Check if we already have a short URL for this order
            if (isset($order['short_url']) && isset($order['short_url_slug']) && isset($order['lima_link_id']) && 
                $order['short_url'] && $order['short_url_slug'] && $order['lima_link_id']) {
                $shortUrl = $order['short_url'];
                $linkId = $order['lima_link_id'];
                log_message('info', "Using existing static short URL for recon order {$orderId}: {$shortUrl} (ID: {$linkId})");
            }
            
            // Test if API key is valid by checking if it's properly configured (move outside the else block)
            $isValidApiKey = isset($apiKey) && $apiKey && $apiKey !== 'your_lima_links_api_key_here' && strlen($apiKey) >= 5;
            
            // Only try to create short URL if we don't have one already
            if (!$shortUrl) {
                
                if ($isValidApiKey) {
                    log_message('info', "Creating NEW static short URL via MDA Links API for recon order {$orderId}...");
                    
                    try {
                        // Create short URL with MDA Links
                        $shortUrlData = $this->createShortUrl($apiKey, $orderUrl, null, $brandedDomain);
                        if ($shortUrlData && isset($shortUrlData['shorturl'])) {
                            $shortUrl = $shortUrlData['shorturl'];
                            $linkId = $shortUrlData['id'] ?? null;
                            
                            // Extract the slug from the short URL
                            $shortUrlSlug = null;
                            $defaultDomain = 'mda.to'; // Use mda.to directly
                            if (preg_match('/mda\.to\/([^\/\?]+)/', $shortUrl, $matches)) {
                                $shortUrlSlug = $matches[1];
                            }
                            
                            // Save the short URL data to make it static/persistent
                            $updateData = [
                                'short_url' => $shortUrl,
                                'short_url_slug' => $shortUrlSlug,
                                'lima_link_id' => $linkId,
                                'qr_generated_at' => date('Y-m-d H:i:s')
                            ];
                            
                            $this->reconOrderModel->update($orderId, $updateData);
                            log_message('info', "MDA Links short URL created and SAVED as static for recon order {$orderId}: {$shortUrl} (ID: {$linkId}, Slug: {$shortUrlSlug})");
                        } else {
                            log_message('warning', "MDA Links API returned invalid response for recon order {$orderId}");
                            $shortUrl = $orderUrl; // Fallback to original URL
                        }
                    } catch (Exception $e) {
                        log_message('warning', "Failed to create MDA Links short URL for recon order {$orderId}, using original: " . $e->getMessage());
                        $shortUrl = $orderUrl; // Fallback to original URL
                    }
                } else {
                    log_message('warning', "No valid MDA Links API key configured (current: " . substr($apiKey ?? '', 0, 5) . "...), using original URL for recon order {$orderId}");
                    $shortUrl = $orderUrl; // Fallback to original URL
                    
                    // Still save QR generation timestamp for tracking
                    $updateData = [
                        'qr_generated_at' => date('Y-m-d H:i:s')
                    ];
                    $this->reconOrderModel->update($orderId, $updateData);
                }
            }
            
            // Ensure we have a URL to work with
            if (!$shortUrl) {
                $shortUrl = $orderUrl; // Final fallback
            }
            
            // Generate QR code URL using MDA.to API or fallback service
            $qrUrl = null;
            
            // First try to use MDA.to QR API if we have a valid API key and short URL
            if ($isValidApiKey && $shortUrl !== $orderUrl) {
                $qrUrl = $this->generateQRCodeViaMDA($apiKey, $shortUrl);
                if ($qrUrl) {
                    log_message('info', "MDA QR code generated successfully: {$qrUrl}");
                }
            }
            
            if (!$qrUrl) {
                // Fallback to external QR service
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($shortUrl);
                log_message('info', "Using fallback QR service: {$qrUrl}");
            }
            
            return [
                'qr_url' => $qrUrl,
                'short_url' => $shortUrl,
                'shortener' => $shortUrl !== $orderUrl ? 'MDA Links (5-digit slug, STATIC)' : 'Direct URL',
                'is_static' => $shortUrl !== $orderUrl
            ];
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting QR data for recon order: ' . $e->getMessage());
            
            // Emergency fallback - always return something
            $orderUrl = base_url("recon_orders/view/{$orderId}");
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($orderUrl);
            
            return [
                'qr_url' => $qrUrl,
                'short_url' => $orderUrl,
                'shortener' => 'Direct URL (Emergency Fallback)',
                'is_static' => false
            ];
        }
    }

    /**
     * Create short URL using Lima Links API
     */
    private function createShortUrl($apiKey, $url, $customAlias = null, $brandedDomain = null)
    {
        try {
            // Generate a unique slug if no custom alias provided
            if (!$customAlias) {
                $customAlias = $this->generateShortSlug(5); // Start with 5 digits, will expand if needed
            }
            
            $payload = [
                'url' => $url,
                'custom' => $customAlias,
                'domain' => $brandedDomain ?: 'mda.to',
                'expiry' => null, // No expiration
                'description' => 'Recon Order QR Code'
            ];
            
            log_message('info', "Lima Links payload with {$customAlias}: " . json_encode($payload));
            
            // Try to create the short URL with MDA Links
            $result = $this->callMDALinksAPI($apiKey, $payload);
            
            // If successful, return the result
            if ($result['success']) {
                return $result['data'];
            }
            
            // If it failed due to slug collision on MDA Links side, 
            // try with a new unique slug (max 3 retries)
            for ($i = 1; $i <= 3; $i++) {
                $newAlias = $this->generateShortSlug(5 + $i); // Increase length each retry
                $payload['custom'] = $newAlias;
                
                log_message('info', "MDA Links retry #{$i} with slug: {$newAlias}");
                $result = $this->callMDALinksAPI($apiKey, $payload);
                
                if ($result['success']) {
                    return $result['data'];
                }
            }
            
            // If all retries failed, try without custom alias
            $payload = [
                'url' => $url,
                'domain' => $brandedDomain ?: 'mda.to',
                'expiry' => null,
                'description' => 'Recon Order QR Code'
            ];
            log_message('info', "MDA Links final attempt without custom alias");
            $result = $this->callMDALinksAPI($apiKey, $payload);
            
            if ($result['success']) {
                return $result['data'];
            }
            
            log_message('error', "All MDA Links attempts failed: " . $result['error']);
            return null;
            
        } catch (Exception $e) {
            log_message('error', "Lima Links createShortUrl exception: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Generate a unique short slug
     */
    private function generateShortSlug($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $slug = '';
        for ($i = 0; $i < $length; $i++) {
            $slug .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $slug;
    }

    /**
     * Call MDA Links API for URL shortening
     */
    private function callMDALinksAPI($apiKey, $payload)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://mda.to/api/url/add',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            return [
                'success' => false,
                'error' => 'CURL Error: ' . $curlError
            ];
        }
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => "HTTP Error: {$httpCode}"
            ];
        }
        
        $data = json_decode($response, true);
        
        if (!$data) {
            return [
                'success' => false,
                'error' => 'Invalid JSON response'
            ];
        }
        
        if (isset($data['error']) && $data['error'] !== 0) {
            $errorMessage = $data['message'] ?? 'Unknown error';
            return [
                'success' => false,
                'error' => $errorMessage
            ];
        }
        
        if (!isset($data['shorturl'])) {
            return [
                'success' => false,
                'error' => 'No short URL returned'
            ];
        }
        
        return [
            'success' => true,
            'data' => $data
        ];
    }

    /**
     * Generate QR Code using MDA.to API
     */
    private function generateQRCodeViaMDA($apiKey, $shortUrl)
    {
        $payload = [
            'type' => 'link',
            'data' => $shortUrl,
            'name' => 'Recon Order QR Code'
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://mda.to/api/qr/add',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError || $httpCode !== 200) {
            log_message('error', "MDA QR API error - HTTP: {$httpCode}, CURL: {$curlError}");
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (!$data || (isset($data['error']) && $data['error'] !== 0)) {
            log_message('error', "MDA QR API response error: " . json_encode($data));
            return null;
        }
        
        return $data['link'] ?? null;
    }

    /**
     * Generate QR URL using Lima Links or fallback
     */
    private function generateQRUrl($apiKey, $linkId, $shortUrl, $size, $format)
    {
        // Try to use Lima Links QR endpoint if we have link ID
        if ($linkId) {
            $qrUrl = \App\Helpers\LimaLinksHelper::buildQrUrl($linkId, $size, $format);
            
            // Verify QR endpoint is accessible
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $qrUrl,
                CURLOPT_NOBODY => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true
            ]);
            
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200) {
                log_message('info', "Lima Links QR endpoint working: {$qrUrl}");
                return $qrUrl;
            } else {
                log_message('warning', "Lima Links QR endpoint failed, using fallback");
            }
        }
        
        // Fallback: try to extract ID from shorturl if possible
        $defaultDomain = \App\Helpers\LimaLinksHelper::getDefaultDomain();
        $escapedDomain = preg_quote($defaultDomain, '/');
        if (preg_match('/' . $escapedDomain . '\/([^\/\?]+)/', $shortUrl, $matches)) {
            $extractedId = $matches[1];
            $fallbackQrUrl = \App\Helpers\LimaLinksHelper::buildQrUrl($extractedId, $size, $format);
            log_message('info', "Generated fallback QR URL from extracted ID: {$fallbackQrUrl}");
            return $fallbackQrUrl;
        }
        
        // Last resort: use alternative QR service with the short URL
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($shortUrl);
    }

    // ========================================
    // STATUS UPDATE ENDPOINTS
    // ========================================

    /**
     * Update pictures status
     */
    public function updatePicturesStatus($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID required']);
        }

        $pictures = $this->request->getPost('pictures') ? 1 : 0;

        try {
            $updated = $this->reconOrderModel->update($id, [
                'pictures' => $pictures,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($updated) {
                // Log activity
                try {
                    $this->reconActivityModel->logActivity($id, auth()->user()->id ?? 0, 'picture', 'Pictures status updated', [], ['pictures' => $pictures]);
                } catch (\Exception $e) {
                    log_message('error', 'Error logging pictures activity: ' . $e->getMessage());
                }

                return $this->response->setJSON(['success' => true, 'message' => 'Pictures status updated successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update pictures status']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error updating pictures status: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error updating pictures status']);
        }
    }

    /**
     * Get users for mentions - similar to other modules
     */
    public function getUsersForMentions()
    {
        try {
            $search = $this->request->getGet('search') ?? '';
            
            $userModel = new \App\Models\UserModel();
            $users = $userModel->select('id, first_name, last_name, username')
                              ->where('deleted_at IS NULL')
                              ->where('active', 1);
            
            if (!empty($search)) {
                $users = $users->groupStart()
                              ->like('first_name', $search)
                              ->orLike('last_name', $search)
                              ->orLike('username', $search)
                              ->groupEnd();
            }
            
            $users = $users->orderBy('first_name', 'ASC')
                          ->limit(10)
                          ->findAll();
            
            // Format users for mention dropdown
            $formattedUsers = [];
            foreach ($users as $user) {
                $fullName = trim($user['first_name'] . ' ' . $user['last_name']);
                if (empty($fullName)) {
                    $fullName = $user['username'] ?? 'User';
                }
                
                $formattedUsers[] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'name' => $fullName,
                    'full_name' => $fullName
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $formattedUsers
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting users for mentions: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error loading users: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update a comment
     */
    public function updateComment($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comment ID required']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        helper('auth');
        $userId = auth()->id();
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        try {
            $comment = trim($this->request->getPost('comment'));
            
            if (empty($comment)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Comment content is required']);
            }

            // Check if user can edit this comment
            $existingComment = $this->reconCommentModel->find($id);
            if (!$existingComment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Comment not found']);
            }

            if ($userId != $existingComment['user_id']) {
                return $this->response->setJSON(['success' => false, 'message' => 'You can only edit your own comments']);
            }

            // Process mentions
            $mentions = $this->reconCommentModel->processMentions($comment);

            // Update comment
            $data = [
                'comment' => $comment,
                'mentions' => !empty($mentions) ? json_encode($mentions) : json_encode([]),
                'is_edited' => 1
            ];

            if ($this->reconCommentModel->update($id, $data)) {
                // Log detailed activity with old/new values
                try {
                    $oldPreview = strlen($existingComment['comment']) > 100 ? substr($existingComment['comment'], 0, 100) . '...' : $existingComment['comment'];
                    $newPreview = strlen($comment) > 100 ? substr($comment, 0, 100) . '...' : $comment;
                    
                    $description = "Updated comment from: \"{$oldPreview}\" to: \"{$newPreview}\"";
                    
                    $metadata = [
                        'type' => 'comment',
                        'action' => 'updated',
                        'old_preview' => $oldPreview,
                        'new_preview' => $newPreview,
                        'comment_id' => $id,
                        'has_mentions' => !empty($mentions),
                        'mention_count' => count($mentions)
                    ];
                    
                    $oldValues = [
                        'comment' => $existingComment['comment'],
                        'mentions' => json_decode($existingComment['mentions'] ?? '[]', true),
                        'is_edited' => $existingComment['is_edited'] ?? 0
                    ];
                    
                    $newValues = [
                        'comment' => $comment,
                        'mentions' => $mentions,
                        'is_edited' => 1
                    ];
                    
                    $this->reconActivityModel->logActivity(
                        $existingComment['order_id'], 
                        $userId, 
                        'comment_updated', 
                        $description,
                        $oldValues,
                        $newValues,
                        $metadata
                    );
                } catch (\Exception $e) {
                    log_message('error', 'Error logging comment update activity: ' . $e->getMessage());
                }
                
                // Return updated comment data
                $updatedComment = $this->reconCommentModel->getCommentWithUser($id);
                
                // Process for display
                $updatedComment['attachments'] = $this->reconCommentModel->processAttachmentsJson($updatedComment['attachments'], $updatedComment['order_id']);
                $updatedComment['mentions'] = json_decode($updatedComment['mentions'] ?? '[]', true);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Comment updated successfully',
                    'comment' => $updatedComment
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update comment']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error updating comment: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error updating comment: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete a comment
     */
    public function deleteComment($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comment ID required']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        helper('auth');
        $userId = auth()->id();
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        try {
            // Check if user can delete this comment
            $existingComment = $this->reconCommentModel->find($id);
            if (!$existingComment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Comment not found']);
            }

            if ($userId != $existingComment['user_id']) {
                return $this->response->setJSON(['success' => false, 'message' => 'You can only delete your own comments']);
            }

            // Soft delete the comment (and any replies)
            if ($this->reconCommentModel->delete($id)) {
                // Also delete any replies to this comment
                $this->reconCommentModel->where('parent_id', $id)->delete();
                
                // Log detailed activity with preview
                try {
                    $preview = strlen($existingComment['comment']) > 100 ? substr($existingComment['comment'], 0, 100) . '...' : $existingComment['comment'];
                    $description = "Deleted comment: \"{$preview}\"";
                    
                    $metadata = [
                        'type' => 'comment',
                        'action' => 'deleted',
                        'preview' => $preview,
                        'comment_id' => $id,
                        'had_attachments' => !empty(json_decode($existingComment['attachments'] ?? '[]', true)),
                        'had_mentions' => !empty(json_decode($existingComment['mentions'] ?? '[]', true))
                    ];
                    
                    $oldValues = [
                        'comment' => $existingComment['comment'],
                        'attachments' => json_decode($existingComment['attachments'] ?? '[]', true),
                        'mentions' => json_decode($existingComment['mentions'] ?? '[]', true)
                    ];
                    
                    $newValues = []; // No new values for deleted comment
                    
                    $this->reconActivityModel->logActivity(
                        $existingComment['order_id'], 
                        $userId, 
                        'comment_deleted', 
                        $description,
                        $oldValues,
                        $newValues,
                        $metadata
                    );
                } catch (\Exception $e) {
                    log_message('error', 'Error logging comment deletion activity: ' . $e->getMessage());
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Comment deleted successfully'
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete comment']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error deleting comment: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error deleting comment: ' . $e->getMessage()]);
        }
    }

    /**
     * Format relative time (e.g., "2 hours ago")
     */
    private function formatRelativeTime($datetime)
    {
        $time = time();
        $time_difference = $time - strtotime($datetime);

        if ($time_difference < 1) {
            return 'just now';
        }

        $condition = array(
            12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
            }
        }

        return 'just now';
    }

    /**
     * Serve attachment files with view/download functionality
     */
    public function serveAttachment($orderId, $filename)
    {
        helper('auth');
        
        if (!auth()->loggedIn()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }

        // Verify order exists and user has access
        $order = $this->reconOrderModel->find($orderId);
        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }

        // Sanitize filename
        $filename = basename($filename);
        if (empty($filename)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid filename');
        }

        // Check both comments and thumbnails directories
        $possiblePaths = [
            FCPATH . 'uploads/recon_orders/' . $orderId . '/comments/' . $filename,
            FCPATH . 'uploads/recon_orders/' . $orderId . '/comments/thumbnails/' . $filename
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $filePath = $path;
                break;
            }
        }

        if (!$filePath) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        // Determine action (view or download)
        $action = $this->request->getGet('action') ?? 'view';
        
        // Get file info
        $fileInfo = pathinfo($filePath);
        $extension = strtolower($fileInfo['extension'] ?? '');
        $mimeType = $this->getFileMimeType($extension);
        
        // Set appropriate headers
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Length', filesize($filePath));
        
        if ($action === 'download') {
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } else {
            $this->response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        }
        
        $this->response->setHeader('Cache-Control', 'public, max-age=3600');
        
        // Send file
        return $this->response->setBody(file_get_contents($filePath));
    }

    /**
     * Get MIME type for file extension
     */
    private function getFileMimeType($extension)
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'txt' => 'text/plain',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Generate PDF for recon order
     */
    public function pdf($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }

        try {
            $order = $this->reconOrderModel->getFullOrderDetails($id);
            
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }

            // Check permissions
            if (!auth()->loggedIn()) {
                return redirect()->to('/login');
            }

            // For now, we'll redirect to a print-friendly view
            // In the future, this can be enhanced to generate actual PDFs using libraries like TCPDF or mPDF
            
            // Set headers for PDF download behavior
            $this->response->setHeader('Content-Type', 'text/html; charset=UTF-8');
            $this->response->setHeader('Content-Disposition', 'inline; filename="recon-order-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT) . '.pdf"');
            
            // Get additional data for the PDF view
            $statusColor = $this->getStatusColor($order['status']);
            
            $data = [
                'title' => 'Recon Order #' . $order['order_number'] . ' - PDF',
                'order' => $order,
                'statusColor' => $statusColor,
                'isPdfView' => true
            ];

            return view('Modules\ReconOrders\Views\recon_orders\pdf', $data);
            
        } catch (Exception $e) {
            log_message('error', 'Error generating PDF for recon order: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ]);
        }
    }
} 