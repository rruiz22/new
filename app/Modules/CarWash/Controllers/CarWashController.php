<?php

namespace Modules\CarWash\Controllers;

use App\Controllers\BaseController;
use Modules\CarWash\Models\CarWashOrderModel;
use Modules\CarWash\Models\CarWashServiceModel;
use Modules\CarWash\Models\CarWashActivityModel;
use Modules\CarWash\Models\CarWashCommentModel;
use App\Models\ClientModel;
use App\Models\ContactModel;
use App\Models\UserModel;
use Exception;

class CarWashController extends BaseController
{
    protected $carWashOrderModel;
    protected $carWashServiceModel;
    protected $carWashActivityModel;
    protected $carWashCommentModel;
    protected $clientModel;
    protected $contactModel;
    protected $userModel;

    public function __construct()
    {
        $this->carWashOrderModel = new CarWashOrderModel();
        $this->carWashServiceModel = new CarWashServiceModel();
        $this->carWashActivityModel = new CarWashActivityModel();
        $this->carWashCommentModel = new CarWashCommentModel();
        $this->clientModel = new ClientModel();
        $this->contactModel = new ContactModel();
        $this->userModel = new UserModel();
        
        // Load helpers
        helper(['avatar']);
    }

    /**
     * Get current user ID from session or use admin fallback
     */
    private function getCurrentUserId()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            // Use admin user (ID 3) as fallback
            $userId = 3;
            log_message('warning', 'CarWash: No user_id in session, using admin fallback (ID: 3)');
        }
        return $userId;
    }

    public function index()
    {
        $data = [
            'title' => 'Car Wash Orders',
            'stats' => $this->carWashOrderModel->getDashboardStats()
        ];

        return view('Modules\CarWash\Views\car_wash\index', $data);
    }

    public function view($id)
    {
        $order = $this->carWashOrderModel->getOrderWithDetails($id);
        
        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Car wash order not found');
        }

        // Generate QR code data
        $qrData = $this->generateOrderQR($id);

        // Get clients for edit modal - use fresh model instance to avoid query conflicts
        $clients = [];
        try {
            $clientModel = new \App\Models\ClientModel();
            $clients = $clientModel->where('status', 'active')
                                   ->orderBy('name', 'ASC')
                                   ->findAll();
            log_message('info', 'CarWash view: loaded ' . count($clients) . ' active clients');
        } catch (\Exception $e) {
            log_message('warning', 'CarWash view: failed to load active clients, trying fallback: ' . $e->getMessage());
            // Fallback if status field doesn't exist or has different values
            try {
                $clientModel = new \App\Models\ClientModel();
                $clients = $clientModel->orderBy('name', 'ASC')->findAll();
                log_message('info', 'CarWash view: loaded ' . count($clients) . ' clients via fallback');
            } catch (\Exception $e2) {
                log_message('warning', 'CarWash view: failed to load clients with orderBy, trying ultimate fallback: ' . $e2->getMessage());
                // Ultimate fallback - just get all clients
                try {
                    $clientModel = new \App\Models\ClientModel();
                    $clients = $clientModel->findAll();
                    log_message('info', 'CarWash view: loaded ' . count($clients) . ' clients via ultimate fallback');
                } catch (\Exception $e3) {
                    log_message('error', 'CarWash view: failed to load any clients: ' . $e3->getMessage());
                    $clients = [];
                }
            }
        }

        // Get CarWash services for edit modal - use fresh model instance, filtered by user type
        $carWashServices = [];
        try {
            $serviceModel = new \Modules\CarWash\Models\CarWashServiceModel();
            $userType = session()->get('user_type') ?? 'client';
            $carWashServices = $serviceModel->getServicesForUser($userType);
            log_message('info', 'CarWash view: loaded ' . count($carWashServices) . ' active services for user type: ' . $userType);
        } catch (\Exception $e) {
            log_message('warning', 'CarWash view: failed to load services for user, trying fallback: ' . $e->getMessage());
            // Fallback if fields don't exist
            try {
                $serviceModel = new \Modules\CarWash\Models\CarWashServiceModel();
                $carWashServices = $serviceModel->orderBy('name', 'ASC')->findAll();
                log_message('info', 'CarWash view: loaded ' . count($carWashServices) . ' services via fallback');
            } catch (\Exception $e2) {
                log_message('warning', 'CarWash view: failed to load services with orderBy, trying ultimate fallback: ' . $e2->getMessage());
                // Ultimate fallback - just get all services
                try {
                    $serviceModel = new \Modules\CarWash\Models\CarWashServiceModel();
                    $carWashServices = $serviceModel->findAll();
                    log_message('info', 'CarWash view: loaded ' . count($carWashServices) . ' services via ultimate fallback');
                } catch (\Exception $e3) {
                    log_message('error', 'CarWash view: failed to load any services: ' . $e3->getMessage());
                    $carWashServices = [];
                }
            }
        }

        // Ensure we have valid data arrays
        if (!is_array($clients)) {
            log_message('warning', 'CarWash view: clients is not an array, resetting to empty array');
            $clients = [];
        }
        
        if (!is_array($carWashServices)) {
            log_message('warning', 'CarWash view: carWashServices is not an array, resetting to empty array');
            $carWashServices = [];
        }

        // Debug logging
        log_message('info', 'CarWash view data prepared: clients=' . count($clients) . ', services=' . count($carWashServices));

        $data = [
            'title' => 'Car Wash Order #' . ($order['order_number'] ?? $id),
            'order' => $order,
            'qr_data' => $qrData,
            'clients' => $clients,
            'carWashServices' => $carWashServices
        ];

        return view('Modules\CarWash\Views\car_wash\view', $data);
    }

    public function edit($id)
    {
        $order = $this->carWashOrderModel->find($id);
        
        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Car wash order not found');
        }

        // Get contacts with proper field names
        $contacts = $this->contactModel->select('id, name, email, client_id')->findAll();
        
        // Get users with proper field names, ensuring first_name and last_name are available
        $users = $this->userModel->select('id, first_name, last_name, username')
                                ->where('deleted_at IS NULL')
                                ->where('active', 1)
                                ->findAll();
        
        // Ensure users have the required fields, fallback to username if names are missing
        foreach ($users as &$user) {
            if (empty($user['first_name']) && empty($user['last_name'])) {
                $user['first_name'] = $user['username'] ?? 'User';
                $user['last_name'] = '';
            }
        }

        $data = [
            'title' => 'Edit Car Wash Order #' . $order['order_number'],
            'order' => $order,
            'clients' => $this->clientModel->findAll(),
            'contacts' => $contacts,
            'users' => $users,
            'carWashServices' => $this->carWashServiceModel->getActiveServices($order['client_id'])
        ];

        return view('Modules\CarWash\Views\car_wash\edit', $data);
    }

    public function modal_edit($id)
    {
        $order = $this->carWashOrderModel->find($id);
        
        if (!$order) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Car wash order not found'
            ]);
        }

        // Get clients
        $clients = $this->clientModel->orderBy('name', 'ASC')->findAll();
        
        // Get CarWash services - get all active services, filtered by user type
        $userType = session()->get('user_type') ?? 'client';
        $carWashServices = $this->carWashServiceModel->getServicesForUser($userType);

        $data = [
            'order' => $order,
            'clients' => $clients,
            'carWashServices' => $carWashServices,
            'isEdit' => true
        ];

        return view('Modules\CarWash\Views\car_wash\modal_form', $data);
    }

    public function store()
    {
        // Custom validation rules for store (excluding order_number as it's auto-generated)
        $rules = [
            'client_id' => 'required|integer',
            'vehicle' => 'required|max_length[255]',
            'service_id' => 'required|integer',
            'status' => 'permit_empty|in_list[pending,confirmed,in_progress,completed,cancelled]',
            'tag_stock' => 'permit_empty|max_length[100]',
            'vin_number' => 'permit_empty|max_length[17]'
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        $formData = $this->request->getPost();
        
        // Get service price if service_id is provided
        $servicePrice = 0;
        if (!empty($formData['service_id'])) {
            $service = $this->carWashServiceModel->find($formData['service_id']);
            if ($service) {
                $servicePrice = $service['price'] ?? 0;
            }
        }
        
        // Map form data to table fields
        $data = [
            'client_id' => $formData['client_id'],
            'service_id' => $formData['service_id'],
            'tag_stock' => $formData['tag_stock'] ?? null,
            'vin_number' => $formData['vin_number'] ?? null,
            'vehicle' => $formData['vehicle'] ?? null,
            'status' => $formData['status'] ?? 'completed',
            'priority' => $formData['priority'] ?? 'normal',
            'date' => $formData['date'] ?? date('Y-m-d'),
            'time' => $formData['time'] ?? date('H:i:s'),
            'notes' => $formData['notes'] ?? null,
            'price' => $formData['price'] ?? $servicePrice, // Use provided price or service price
            'created_by' => $this->getCurrentUserId()
        ];
        
        // Handle vehicle parsing - if vehicle contains make/model info, try to parse it
        if (!empty($data['vehicle'])) {
            $vehicleParts = explode(' ', $data['vehicle'], 3);
            if (count($vehicleParts) >= 2) {
                $data['vehicle_make'] = $vehicleParts[0];
                $data['vehicle_model'] = $vehicleParts[1];
                if (count($vehicleParts) >= 3) {
                    // Try to extract year if it's a 4-digit number
                    if (preg_match('/\b(\d{4})\b/', $vehicleParts[2], $matches)) {
                        $data['vehicle_year'] = $matches[1];
                    }
                }
            }
        }

        try {
        $orderId = $this->carWashOrderModel->insert($data);

        if ($orderId) {
            // Handle services if provided
                if (!empty($formData['services']) && is_array($formData['services'])) {
                    $this->saveOrderServices($orderId, $formData['services']);
            }

                // Log order creation activity
                $this->carWashActivityModel->logOrderCreated($orderId, $this->getCurrentUserId());

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Car wash order created successfully',
                'data' => ['id' => $orderId]
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create car wash order',
                'errors' => $this->carWashOrderModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            log_message('error', 'CarWash Store Error: ' . $e->getMessage() . ' - Data: ' . json_encode($data));
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error creating car wash order: ' . $e->getMessage()
            ]);
        }
    }

    public function update($id)
    {
        $order = $this->carWashOrderModel->find($id);
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Car wash order not found'
            ]);
        }

        // Custom validation rules for update (matching the modal fields)
        $rules = [
            'client_id' => 'required|integer',
            'vehicle' => 'required|max_length[255]',
            'service_id' => 'required|integer',
            'status' => 'permit_empty|in_list[pending,in_progress,completed,cancelled]',
            'tag_stock' => 'permit_empty|max_length[100]',
            'vin_number' => 'permit_empty|max_length[17]'
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        $data = $this->request->getPost();
        $data['updated_by'] = $this->getCurrentUserId();
        $userId = $this->getCurrentUserId();

        // Filter out empty VIN numbers to avoid validation issues
        if (empty($data['vin_number'])) {
            unset($data['vin_number']);
        }

        // Handle price - if not provided and service changed, get service price
        if (!isset($data['price']) && isset($data['service_id'])) {
            $service = $this->carWashServiceModel->find($data['service_id']);
            if ($service) {
                $data['price'] = $service['price'] ?? 0;
            }
        } elseif (isset($data['service_id']) && $data['service_id'] != $order['service_id']) {
            // Service changed, update price if not explicitly provided
            if (!isset($data['price']) || $data['price'] == $order['price']) {
                $service = $this->carWashServiceModel->find($data['service_id']);
                if ($service) {
                    $data['price'] = $service['price'] ?? 0;
                }
            }
        }

        try {
            // Store the original order data for comparison
            $originalOrder = $order;
            
            if ($this->carWashOrderModel->update($id, $data)) {
                // Log specific field changes
                $this->logFieldChanges($id, $userId, $originalOrder, $data);
                
                // Get updated order data
                $updatedOrder = $this->carWashOrderModel->find($id);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Car wash order updated successfully',
                    'order' => [
                        'id' => $updatedOrder['id'],
                        'status' => $updatedOrder['status'],
                        'updated_at' => $updatedOrder['updated_at']
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update car wash order',
                    'errors' => $this->carWashOrderModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while updating the order: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Log field changes between original and new data
     */
    private function logFieldChanges($orderId, $userId, $originalOrder, $newData)
    {
        // Define field mappings with their display labels
        $fieldMappings = [
            'client_id' => 'Client',
            'vehicle' => 'Vehicle',
            'service_id' => 'Service',
            'status' => 'Status',
            'tag_stock' => 'Tag Stock',
            'vin_number' => 'VIN Number',
            'notes' => 'Notes',
            'date' => 'Date',
            'time' => 'Time',
            'contact_id' => 'Contact',
            'assigned_to' => 'Assigned To',
            'priority' => 'Priority',
            'price' => 'Price'
        ];

        // Check each field for changes
        foreach ($fieldMappings as $fieldName => $fieldLabel) {
            if (array_key_exists($fieldName, $newData)) {
                $oldValue = $originalOrder[$fieldName] ?? null;
                $newValue = $newData[$fieldName] ?? null;

                // Convert to string for comparison to handle nulls and empty strings
                $oldValueStr = (string)$oldValue;
                $newValueStr = (string)$newValue;

                // Only log if there's an actual change
                if ($oldValueStr !== $newValueStr) {
                    $this->carWashActivityModel->logFieldChange(
                        $orderId,
                        $userId,
                        $fieldName,
                        $oldValue,
                        $newValue,
                        $fieldLabel
                    );
                }
            }
        }
    }

    public function updateStatus($id = null)
    {
        if ($id === null) {
            $id = $this->request->getPost('id');
        }
        
        $status = $this->request->getPost('status');
        $order = $this->carWashOrderModel->find($id);
        
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Car wash order not found'
            ]);
        }

        $oldStatus = $order['status'];
        $userId = $this->getCurrentUserId(); // Fallback to admin user if no session

        try {
        if ($this->carWashOrderModel->updateStatus($id, $status, $userId)) {
                // Log activity for status change
            $this->carWashActivityModel->logStatusChange($id, $userId, $oldStatus, $status);

            // Get updated order data
            $updatedOrder = $this->carWashOrderModel->find($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status updated successfully',
                'order' => [
                    'id' => $updatedOrder['id'],
                    'status' => $updatedOrder['status'],
                    'updated_at' => $updatedOrder['updated_at']
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update status'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while updating status: ' . $e->getMessage()
            ]);
        }
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        $userId = $this->getCurrentUserId();
        $order = $this->carWashOrderModel->find($id);
        
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Car wash order not found'
            ]);
        }

        try {
            // Set who deleted the order before soft deleting
            $this->carWashOrderModel->update($id, [
                'deleted_by' => $userId,
                'updated_by' => $userId
            ]);
            
            // Now perform the soft delete
            if ($this->carWashOrderModel->delete($id)) {
                // Log activity
                $this->carWashActivityModel->logOrderDeleted($id, $userId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Car wash order deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete car wash order'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting car wash order: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the order'
            ]);
        }
    }

    public function restore()
    {
        $id = $this->request->getPost('id');
        $userId = $this->getCurrentUserId();
        
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid order ID'
            ]);
        }

        try {
            // Find the deleted order
        $order = $this->carWashOrderModel->withDeleted()->find($id);
        
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Car wash order not found'
            ]);
        }

            // Check if the order is actually deleted
            if (empty($order['deleted_at'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order is not deleted'
                ]);
            }

            // Restore the order using the proper method
            $db = \Config\Database::connect();
            $result = $db->table('car_wash_orders')
                ->where('id', $id)
                ->update([
                'deleted_at' => null,
                'deleted_by' => null,
                    'updated_by' => $userId,
                    'updated_at' => date('Y-m-d H:i:s')
            ]);

            if ($result) {
            // Log activity
            $this->carWashActivityModel->logOrderRestored($id, $userId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Car wash order restored successfully'
            ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to restore the order'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error restoring car wash order: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while restoring the order: ' . $e->getMessage()
            ]);
        }
    }

    public function permanentDelete()
    {
        $id = $this->request->getPost('id');
        $userId = $this->getCurrentUserId();
        
        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid order ID'
            ]);
        }

        try {
            // Find the deleted order
        $order = $this->carWashOrderModel->withDeleted()->find($id);
        
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Car wash order not found'
            ]);
        }

            // Check if the order is actually soft deleted
            if (empty($order['deleted_at'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order must be soft deleted before permanent deletion'
                ]);
            }

            // Log activity before permanent deletion
            $this->carWashActivityModel->logActivity(
                $id,
                $userId,
                'order_permanently_deleted',
                'Car wash order permanently deleted',
                null,
                null,
                null,
                ['action' => 'permanently_deleted', 'order_number' => $order['order_number']]
            );

            // Permanently delete the order
            $db = \Config\Database::connect();
            $result = $db->table('car_wash_orders')->where('id', $id)->delete();

            if ($result) {
            return $this->response->setJSON([
                'success' => true,
                    'message' => 'Car wash order permanently deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to permanently delete car wash order'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error permanently deleting car wash order: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while permanently deleting the order: ' . $e->getMessage()
            ]);
        }
    }

    private function saveOrderServices($orderId, $services)
    {
        $db = \Config\Database::connect();
        
        // Delete existing services for this order
        $db->table('car_wash_order_services')->where('order_id', $orderId)->delete();
        
        // Insert new services
        foreach ($services as $serviceData) {
            $data = [
                'order_id' => $orderId,
                'service_id' => $serviceData['service_id'],
                'quantity' => $serviceData['quantity'] ?? 1,
                'price' => $serviceData['price'] ?? 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $db->table('car_wash_order_services')->insert($data);
        }
    }

    // Data endpoints for DataTables
    public function getTodayOrders()
    {
        try {
            // Get filters from request
            $clientFilter = $this->request->getGet('client_filter') ?: $this->request->getPost('client_filter');
            $statusFilter = $this->request->getGet('status_filter') ?: $this->request->getPost('status_filter');
            $serviceFilter = $this->request->getGet('service_filter') ?: $this->request->getPost('service_filter');
            $dateFromFilter = $this->request->getGet('date_from_filter') ?: $this->request->getPost('date_from_filter');
            $dateToFilter = $this->request->getGet('date_to_filter') ?: $this->request->getPost('date_to_filter');
            
            $db = \Config\Database::connect();
            $builder = $db->table('car_wash_orders')
                ->select('car_wash_orders.*, clients.name as client_name, car_wash_services.name as service_name, car_wash_services.price as service_price, car_wash_services.color as service_color,
                         (SELECT COUNT(*) FROM car_wash_comments WHERE order_id = car_wash_orders.id) as comments_count,
                         (SELECT COUNT(*) FROM car_wash_notes WHERE car_wash_order_id = car_wash_orders.id AND deleted_at IS NULL) as internal_notes_count')
                ->join('clients', 'clients.id = car_wash_orders.client_id', 'left')
                ->join('car_wash_services', 'car_wash_services.id = car_wash_orders.service_id', 'left')
                ->where('car_wash_orders.deleted_at IS NULL')
                ->where('DATE(car_wash_orders.created_at)', date('Y-m-d')); // Today's orders
            
            // Apply filters
            if (!empty($clientFilter)) {
                $builder->where('car_wash_orders.client_id', $clientFilter);
            }
            
            if (!empty($statusFilter)) {
                $builder->where('car_wash_orders.status', $statusFilter);
            }
            
            if (!empty($serviceFilter)) {
                $builder->where('car_wash_orders.service_id', $serviceFilter);
            }
            
            if (!empty($dateFromFilter)) {
                $builder->where('DATE(car_wash_orders.created_at) >=', $dateFromFilter);
            }
            
            if (!empty($dateToFilter)) {
                $builder->where('DATE(car_wash_orders.created_at) <=', $dateToFilter);
            }
            
            $orders = $builder->orderBy('car_wash_orders.created_at', 'DESC')
                ->get()->getResultArray();
            
            // Format order numbers and add duplicate information for each order
            foreach ($orders as &$order) {
                if (empty($order['order_number'])) {
                    $order['order_number'] = 'CW-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
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

    public function getTomorrowOrders()
    {
        try {
            $orders = $this->carWashOrderModel->getTomorrowOrders()->get()->getResult();
        return $this->response->setJSON(['data' => $orders]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getTomorrowOrders: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getPendingOrders()
    {
        try {
            $orders = $this->carWashOrderModel->getPendingOrders()->get()->getResult();
        return $this->response->setJSON(['data' => $orders]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getPendingOrders: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getAllActiveOrders()
    {
        try {
            // Get filters from request
            $clientFilter = $this->request->getGet('client_filter') ?: $this->request->getPost('client_filter');
            $statusFilter = $this->request->getGet('status_filter') ?: $this->request->getPost('status_filter');
            $serviceFilter = $this->request->getGet('service_filter') ?: $this->request->getPost('service_filter');
            $dateFromFilter = $this->request->getGet('date_from_filter') ?: $this->request->getPost('date_from_filter');
            $dateToFilter = $this->request->getGet('date_to_filter') ?: $this->request->getPost('date_to_filter');
            
            $db = \Config\Database::connect();
            $builder = $db->table('car_wash_orders')
                ->select('car_wash_orders.*, clients.name as client_name, car_wash_services.name as service_name, car_wash_services.price as service_price, car_wash_services.color as service_color,
                         (SELECT COUNT(*) FROM car_wash_comments WHERE order_id = car_wash_orders.id) as comments_count,
                         (SELECT COUNT(*) FROM car_wash_notes WHERE car_wash_order_id = car_wash_orders.id AND deleted_at IS NULL) as internal_notes_count')
                ->join('clients', 'clients.id = car_wash_orders.client_id', 'left')
                ->join('car_wash_services', 'car_wash_services.id = car_wash_orders.service_id', 'left')
                ->where('car_wash_orders.deleted_at IS NULL');
            
            // Apply filters
            if (!empty($clientFilter)) {
                $builder->where('car_wash_orders.client_id', $clientFilter);
            }
            
            if (!empty($statusFilter)) {
                $builder->where('car_wash_orders.status', $statusFilter);
            }
            
            if (!empty($serviceFilter)) {
                $builder->where('car_wash_orders.service_id', $serviceFilter);
            }
            
            if (!empty($dateFromFilter)) {
                $builder->where('DATE(car_wash_orders.created_at) >=', $dateFromFilter);
            }
            
            if (!empty($dateToFilter)) {
                $builder->where('DATE(car_wash_orders.created_at) <=', $dateToFilter);
            }
            
            $orders = $builder->orderBy('car_wash_orders.created_at', 'DESC')
                ->get()->getResultArray();
            
            // Format order numbers and add duplicate information for each order
            foreach ($orders as &$order) {
                if (empty($order['order_number'])) {
                    $order['order_number'] = 'CW-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
                }
                
                // Add duplicate information
                $order['duplicates'] = $this->getDuplicateInfo($order);
            }
            
        return $this->response->setJSON(['data' => $orders]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getAllOrders()
    {
        try {
            $db = \Config\Database::connect();
            $orders = $db->table('car_wash_orders')
                ->select('car_wash_orders.*, clients.name as client_name, car_wash_services.name as service_name, car_wash_services.price as service_price, car_wash_services.color as service_color,
                         (SELECT COUNT(*) FROM car_wash_comments WHERE order_id = car_wash_orders.id) as comments_count,
                         (SELECT COUNT(*) FROM car_wash_notes WHERE car_wash_order_id = car_wash_orders.id AND deleted_at IS NULL) as internal_notes_count')
                ->join('clients', 'clients.id = car_wash_orders.client_id', 'left')
                ->join('car_wash_services', 'car_wash_services.id = car_wash_orders.service_id', 'left')
                ->where('car_wash_orders.deleted_at IS NULL')
                ->orderBy('car_wash_orders.created_at', 'DESC')
                ->get()->getResultArray();
            
            // Format order numbers and add duplicate information for each order
            foreach ($orders as &$order) {
                if (empty($order['order_number'])) {
                    $order['order_number'] = 'CW-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
                }
                
                // Add duplicate information
                $order['duplicates'] = $this->getDuplicateInfo($order);
            }
            
        return $this->response->setJSON(['data' => $orders]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getAllOrders: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    /**
     * Get comments preview for tooltip
     */
    public function getCommentsPreview($orderId = null)
    {
        // Ensure JSON response
        $this->response->setContentType('application/json');
        
        // Log para debug
        log_message('info', "getCommentsPreview called with orderId: " . $orderId);
        
        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }

        try {
            // Verificar que la orden existe
            $order = $this->carWashOrderModel->find($orderId);
            if (!$order) {
                log_message('error', "Car wash order not found for ID: " . $orderId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Car wash order not found'
                ]);
            }

            // Obtener los últimos 3 comentarios con información del usuario
            $db = \Config\Database::connect();
            
            // First, let's check if comments table exists and has data
            $total_comments = $db->table('car_wash_comments')
                                ->where('order_id', $orderId)
                                ->countAllResults();

            log_message('info', "Found {$total_comments} total comments for order {$orderId}");
            
            if ($total_comments === 0) {
                return $this->response->setJSON([
                    'success' => true,
                    'total_comments' => 0,
                    'preview_comments' => []
                ]);
            }

            $comments = $db->table('car_wash_comments')
                          ->select('car_wash_comments.id,
                                   car_wash_comments.description,
                                   car_wash_comments.created_at,
                                   car_wash_comments.user_id,
                                   CONCAT(COALESCE(users.first_name, ""), " ", COALESCE(users.last_name, "")) as user_name')
                          ->join('users', 'users.id = car_wash_comments.user_id', 'left')
                          ->where('car_wash_comments.order_id', $orderId)
                          ->orderBy('car_wash_comments.created_at', 'DESC')
                          ->limit(3)
                          ->get()
                          ->getResultArray();

            // Formatear comentarios para el preview
            $preview_comments = [];
            foreach ($comments as $comment) {
                $user_name = trim($comment['user_name'] ?? '');
                if (empty($user_name)) {
                    $user_name = 'User #' . ($comment['user_id'] ?? 'Unknown');
                }
                
                $commentText = $comment['description'] ?? '';
                $short_comment = mb_substr(strip_tags($commentText), 0, 60);
                if (strlen(strip_tags($commentText)) > 60) {
                    $short_comment .= '...';
                }
                
                $preview_comments[] = [
                    'user_name' => $user_name,
                    'comment' => $short_comment ?: 'No comment text',
                    'created_at' => $this->getRelativeTime($comment['created_at'])
                ];
            }

            log_message('info', "Returning " . count($preview_comments) . " preview comments");

            return $this->response->setJSON([
                'success' => true,
                'total_comments' => $total_comments,
                'preview_comments' => $preview_comments
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting comments preview: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading comments preview: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get notes preview for tooltip
     */
    public function getNotesPreview($orderId = null)
    {
        // Ensure JSON response
        $this->response->setContentType('application/json');
        
        // Log para debug
        log_message('info', "getNotesPreview called with orderId: " . $orderId);
        
        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }

        try {
            // Verificar que la orden existe
            $order = $this->carWashOrderModel->find($orderId);
            if (!$order) {
                log_message('error', "Car wash order not found for ID: " . $orderId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Car wash order not found'
                ]);
            }

            // Obtener las últimas 3 notas con información del usuario
            $db = \Config\Database::connect();
            
            // First, let's check if notes table exists and has data
            $total_notes = $db->table('car_wash_notes')
                                ->where('car_wash_order_id', $orderId)
                                ->where('deleted_at IS NULL')
                                ->countAllResults();

            log_message('info', "Found {$total_notes} total notes for order {$orderId}");
            
            if ($total_notes === 0) {
                return $this->response->setJSON([
                    'success' => true,
                    'total_notes' => 0,
                    'preview_notes' => []
                ]);
            }

            $notes = $db->table('car_wash_notes')
                          ->select('car_wash_notes.id,
                                   car_wash_notes.content,
                                   car_wash_notes.created_at,
                                   car_wash_notes.author_id,
                                   CONCAT(COALESCE(users.first_name, ""), " ", COALESCE(users.last_name, "")) as author_name')
                          ->join('users', 'users.id = car_wash_notes.author_id', 'left')
                          ->where('car_wash_notes.car_wash_order_id', $orderId)
                          ->where('car_wash_notes.deleted_at IS NULL')
                          ->orderBy('car_wash_notes.created_at', 'DESC')
                          ->limit(3)
                          ->get()
                          ->getResultArray();

            // Formatear notas para el preview
            $preview_notes = [];
            foreach ($notes as $note) {
                $author_name = trim($note['author_name'] ?? '');
                if (empty($author_name)) {
                    $author_name = 'User #' . ($note['author_id'] ?? 'Unknown');
                }
                
                $content = $note['content'] ?? '';
                $short_note = mb_substr(strip_tags($content), 0, 60);
                if (strlen(strip_tags($content)) > 60) {
                    $short_note .= '...';
                }
                
                $preview_notes[] = [
                    'author_name' => $author_name,
                    'note' => $short_note ?: 'No note content',
                    'created_at' => $this->getRelativeTime($note['created_at'])
                ];
            }

            log_message('info', "Returning " . count($preview_notes) . " preview notes");

            return $this->response->setJSON([
                'success' => true,
                'total_notes' => $total_notes,
                'preview_notes' => $preview_notes
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting notes preview: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading notes preview: ' . $e->getMessage()
            ]);
        }
    }



    public function getDeletedOrders()
    {
        try {
            $orders = $this->carWashOrderModel->getDeletedOrders()->get()->getResult();
            
            // Log debug info
            log_message('info', 'getDeletedOrders: Found ' . count($orders) . ' deleted orders');
            
            // Convert to array for JSON response
            $ordersArray = [];
            foreach ($orders as $order) {
                $ordersArray[] = (array) $order;
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $ordersArray,
                'count' => count($ordersArray)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getDeletedOrders: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getDashboardStats()
    {
        try {
            // Get filters from request
            $filters = [
                'client_filter' => $this->request->getGet('client_filter') ?: $this->request->getPost('client_filter'),
                'status_filter' => $this->request->getGet('status_filter') ?: $this->request->getPost('status_filter'),
                'service_filter' => $this->request->getGet('service_filter') ?: $this->request->getPost('service_filter'),
                'date_from_filter' => $this->request->getGet('date_from_filter') ?: $this->request->getPost('date_from_filter'),
                'date_to_filter' => $this->request->getGet('date_to_filter') ?: $this->request->getPost('date_to_filter')
            ];

            // Check if any filters are applied
            $hasFilters = !empty($filters['client_filter']) || !empty($filters['status_filter']) || 
                         !empty($filters['service_filter']) || !empty($filters['date_from_filter']) || 
                         !empty($filters['date_to_filter']);

            if ($hasFilters) {
                $stats = $this->carWashOrderModel->getFilteredDashboardStats($filters);
            } else {
                $stats = $this->carWashOrderModel->getDashboardStats();
            }

            return $this->response->setJSON($stats);
        } catch (\Exception $e) {
            // Return default stats if table doesn't exist
            $stats = [
                'total' => 0,
                'today' => 0,
                'tomorrow' => 0,
                'pending' => 0,
                'completed' => 0,
                'cancelled' => 0,
                'in_progress' => 0,
                'confirmed' => 0,
                'this_week' => 0,
                'this_month' => 0,
                'yesterday' => 0,
                'normal_priority' => 0,
                'waiter_priority' => 0,
                'revenue_today' => 0,
                'revenue_this_week' => 0,
                'revenue_this_month' => 0
            ];
            return $this->response->setJSON($stats);
        }
    }

    /**
     * Update missing prices for existing orders
     */
    public function updateMissingPrices()
    {
        try {
            $db = \Config\Database::connect();
            
            // Find orders with missing or zero prices that have a service_id
            $orders = $db->table('car_wash_orders')
                ->select('car_wash_orders.id, car_wash_orders.service_id, car_wash_services.price')
                ->join('car_wash_services', 'car_wash_services.id = car_wash_orders.service_id', 'left')
                ->where('car_wash_orders.deleted_at IS NULL')
                ->where('car_wash_orders.service_id IS NOT NULL')
                ->where('(car_wash_orders.price IS NULL OR car_wash_orders.price = 0)')
                ->where('car_wash_services.price > 0')
                ->get()
                ->getResultArray();

            $updatedCount = 0;
            
            foreach ($orders as $order) {
                $result = $db->table('car_wash_orders')
                    ->where('id', $order['id'])
                    ->update([
                        'price' => $order['price'],
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                
                if ($result) {
                    $updatedCount++;
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "Updated {$updatedCount} orders with missing prices",
                'updated_count' => $updatedCount
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error updating missing prices: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating missing prices: ' . $e->getMessage()
            ]);
        }
    }

    public function getPopularServices()
    {
        try {
            $services = $this->carWashOrderModel->getPopularServices();
            return $this->response->setJSON($services);
        } catch (\Exception $e) {
            return $this->response->setJSON([]);
        }
    }

    public function getTopClients()
    {
        try {
            $clients = $this->carWashOrderModel->getTopClients();
            return $this->response->setJSON($clients);
        } catch (\Exception $e) {
            return $this->response->setJSON([]);
        }
    }

    public function getOrdersByStatus()
    {
        try {
            $data = $this->carWashOrderModel->getOrdersByStatus();
            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return $this->response->setJSON([]);
        }
    }

    public function getOrdersByPriority()
    {
        try {
            $data = $this->carWashOrderModel->getOrdersByPriority();
            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return $this->response->setJSON([]);
        }
    }

    public function getDailyOrdersLast7Days()
    {
        try {
            $data = $this->carWashOrderModel->getDailyOrdersLast7Days();
            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return $this->response->setJSON([]);
        }
    }

    // Content endpoints for tabs
    public function dashboard_content()
    {
        try {
            // Get filters from request
            $filters = [
                'client_filter' => $this->request->getGet('client_filter') ?: $this->request->getPost('client_filter'),
                'status_filter' => $this->request->getGet('status_filter') ?: $this->request->getPost('status_filter'),
                'service_filter' => $this->request->getGet('service_filter') ?: $this->request->getPost('service_filter'),
                'date_from_filter' => $this->request->getGet('date_from_filter') ?: $this->request->getPost('date_from_filter'),
                'date_to_filter' => $this->request->getGet('date_to_filter') ?: $this->request->getPost('date_to_filter')
            ];

            // Check if any filters are applied
            $hasFilters = !empty($filters['client_filter']) || !empty($filters['status_filter']) || 
                         !empty($filters['service_filter']) || !empty($filters['date_from_filter']) || 
                         !empty($filters['date_to_filter']);

            if ($hasFilters) {
                $stats = $this->carWashOrderModel->getFilteredDashboardStats($filters);
            } else {
                $stats = $this->carWashOrderModel->getDashboardStats();
            }

            // Get additional data for dashboard
            $data = [
                'stats' => $stats,
                'popular_services' => $this->carWashOrderModel->getPopularServices(),
                'top_clients' => $this->carWashOrderModel->getTopClients(),
                'orders_by_status' => $this->carWashOrderModel->getOrdersByStatus(),
                'orders_by_priority' => $this->carWashOrderModel->getOrdersByPriority(),
                'daily_orders' => $this->carWashOrderModel->getDailyOrdersLast7Days(),
                'has_filters' => $hasFilters
            ];

            // Load clients and services for filters
            try {
                $data['clients'] = $this->clientModel
                    ->where('status', 'active')
                    ->orderBy('name', 'ASC')
                    ->findAll() ?? [];
            } catch (\Exception $e) {
                $data['clients'] = [];
            }

            try {
                $userType = session()->get('user_type') ?? 'client';
                $data['services'] = $this->carWashServiceModel
                    ->getServicesForUser($userType) ?? [];
            } catch (\Exception $e) {
                $data['services'] = [];
            }

        } catch (\Exception $e) {
            $data = [
                'stats' => [
                    'total' => 0,
                    'today' => 0,
                    'tomorrow' => 0,
                    'pending' => 0,
                    'completed' => 0,
                    'cancelled' => 0,
                    'in_progress' => 0,
                    'confirmed' => 0,
                    'this_week' => 0,
                    'this_month' => 0,
                    'yesterday' => 0,
                    'normal_priority' => 0,
                    'waiter_priority' => 0,
                    'revenue_today' => 0,
                    'revenue_this_week' => 0,
                    'revenue_this_month' => 0
                ],
                'popular_services' => [],
                'top_clients' => [],
                'orders_by_status' => [],
                'orders_by_priority' => [],
                'daily_orders' => [],
                'has_filters' => false,
                'clients' => [],
                'services' => []
            ];
        }

        return view('Modules\CarWash\Views\car_wash\dashboard_content', $data);
    }

    public function today_content()
    {
        try {
            $orders = $this->carWashOrderModel->getTodayOrders()->get()->getResult();
            $data = ['orders' => $orders];
        } catch (\Exception $e) {
            log_message('error', 'Error in today_content: ' . $e->getMessage());
            $data = ['orders' => []];
        }

        // Load clients and services for the form
        try {
            $data['clients'] = $this->clientModel
                ->where('status', 'active')
                ->orderBy('name', 'ASC')
                ->findAll() ?? [];
        } catch (\Exception $e) {
            log_message('error', 'CarWash today_content - Error loading clients: ' . $e->getMessage());
            $data['clients'] = [];
        }

        try {
            $userType = session()->get('user_type') ?? 'client';
            $data['services'] = $this->carWashServiceModel
                ->getServicesForUser($userType) ?? [];
        } catch (\Exception $e) {
            log_message('error', 'CarWash today_content - Error loading services: ' . $e->getMessage());
            $data['services'] = [];
        }

        return view('Modules\CarWash\Views\car_wash\today_content', $data);
    }

    public function tomorrow_content()
    {
        try {
            $orders = $this->carWashOrderModel->getTomorrowOrders()->get()->getResult();
            $data = ['orders' => $orders];
        } catch (\Exception $e) {
            log_message('error', 'Error in tomorrow_content: ' . $e->getMessage());
            $data = ['orders' => []];
        }
        return view('Modules\CarWash\Views\car_wash\tomorrow_content', $data);
    }

    public function pending_content()
    {
        try {
            $orders = $this->carWashOrderModel->getPendingOrders()->get()->getResult();
            $data = ['orders' => $orders];
        } catch (\Exception $e) {
            log_message('error', 'Error in pending_content: ' . $e->getMessage());
            $data = ['orders' => []];
        }
        return view('Modules\CarWash\Views\car_wash\pending_content', $data);
    }



    public function all_orders_content()
    {
        return view('Modules\CarWash\Views\car_wash\all_orders_content');
    }

    public function services_content()
    {
        return view('Modules\CarWash\Views\car_wash\services_content');
    }

    public function deleted_content()
    {
        try {
            $orders = $this->carWashOrderModel->getDeletedOrders()->get()->getResult();
            $data = ['orders' => $orders];
        } catch (\Exception $e) {
            log_message('error', 'Error in deleted_content: ' . $e->getMessage());
            $data = ['orders' => []];
        }
        return view('Modules\CarWash\Views\car_wash\deleted_content', $data);
    }

    public function modal_form()
    {
        // Simple data structure with fallbacks
        $data = [
            'clients' => [],
            'carWashServices' => []
        ];

        // Load clients
        try {
            $data['clients'] = $this->clientModel
                ->orderBy('name', 'ASC')
                ->findAll() ?? [];
        } catch (\Exception $e) {
            log_message('error', 'CarWash modal_form - Error loading clients: ' . $e->getMessage());
            $data['clients'] = [];
        }

        // Load car wash services - only active services, filtered by user type
        try {
            $db = \Config\Database::connect();
            if ($db->tableExists('car_wash_services')) {
                // Get current user type
                $userType = session()->get('user_type') ?? 'client';
                $data['carWashServices'] = $this->carWashServiceModel
                    ->getServicesForUser($userType)
                    ?? [];
            }
        } catch (\Exception $e) {
            log_message('error', 'CarWash modal_form - Error loading services: ' . $e->getMessage());
            $data['carWashServices'] = [];
        }

        return view('Modules\CarWash\Views\car_wash\modal_form', $data);
    }

    // Activity and comments
    public function getActivity($id)
    {
        try {
            $activities = $this->carWashActivityModel->getActivityForOrder($id);
            
            // Process activities for display
            foreach ($activities as &$activity) {
                $activity['created_at'] = $this->timeAgo($activity['created_at']);
                $activity['user_name'] = trim($activity['first_name'] . ' ' . $activity['last_name']);
                $activity['action'] = $activity['activity_type'];
                
                // Process metadata if it exists
                if (!empty($activity['metadata'])) {
                    try {
                        $activity['metadata'] = json_decode($activity['metadata'], true);
                    } catch (\Exception $e) {
                        $activity['metadata'] = null;
                    }
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'activities' => $activities
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error loading activities: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading activities'
            ]);
        }
    }

    public function addComment()
    {
        $orderId = $this->request->getPost('order_id');
        $comment = trim($this->request->getPost('comment'));
        $userId = $this->getCurrentUserId();

        if (empty($orderId) || empty($comment)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID and comment are required'
            ]);
        }

        try {
            // Process mentions
            $mentions = $this->carWashCommentModel->processMentions($comment);
            
            // Handle file uploads
            $attachments = [];
            $uploadedFiles = $this->request->getFiles();
            
            log_message('info', 'CarWash - Uploaded files data: ' . json_encode($uploadedFiles));
            
            if (!empty($uploadedFiles['attachments'])) {
                log_message('info', 'CarWash - Processing attachments: ' . count($uploadedFiles['attachments']) . ' files');
                $attachments = $this->carWashCommentModel->processAttachments($uploadedFiles['attachments'], $orderId);
                log_message('info', 'CarWash - Processed attachments: ' . json_encode($attachments));
            } else {
                log_message('info', 'CarWash - No attachments found in request');
            }
            
            // Prepare comment data
            $commentData = [
                'order_id' => $orderId,
                'user_id' => $userId,
                'description' => $comment,
                'attachments' => !empty($attachments) ? json_encode($attachments) : json_encode([]),
                'mentions' => !empty($mentions) ? json_encode($mentions) : json_encode([]),
                'metadata' => json_encode([
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => $this->request->getUserAgent()->getAgentString(),
                    'timestamp' => date('Y-m-d H:i:s')
                ])
            ];

            $commentId = $this->carWashCommentModel->insert($commentData);

        if ($commentId) {
                // Log activity with comment preview like Sales Orders
                $commentPreview = substr(trim($comment), 0, 15) . (strlen(trim($comment)) > 15 ? '...' : '');
                $this->carWashActivityModel->logCommentActivity(
                    $orderId, 
                    $userId,
                    'comment_added', 
                    "Added comment: \"{$commentPreview}\"",
                    [
                        'comment_id' => $commentId,
                        'comment' => $comment, // Full comment for tooltip
                        'comment_preview' => substr(trim($comment), 0, 100) . (strlen(trim($comment)) > 100 ? '...' : ''),
                        'has_attachments' => !empty($attachments)
                    ]
                );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment_id' => $commentId
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add comment'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'CarWash - Error adding comment: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while adding the comment'
            ]);
        }
    }

    public function addReply()
    {
        $commentId = $this->request->getPost('comment_id');
        $comment = $this->request->getPost('reply');
        $mentions = $this->request->getPost('mentions');
        $attachments = $this->request->getPost('attachments');
        $userId = $this->getCurrentUserId();

        $replyId = $this->carWashCommentModel->addReply($commentId, $userId, $comment, $mentions, $attachments);

        if ($replyId) {
            // Get order ID for activity log
            $parentComment = $this->carWashCommentModel->find($commentId);
            if ($parentComment) {
                // Log activity with reply preview and parent context like Sales Orders
                $parentPreview = substr(trim($parentComment['description']), 0, 30) . (strlen(trim($parentComment['description'])) > 30 ? '...' : '');
                $replyPreview = substr(trim($comment), 0, 15) . (strlen(trim($comment)) > 15 ? '...' : '');
                
                $this->carWashActivityModel->logCommentActivity(
                    $parentComment['order_id'], 
                    $userId,
                    'comment_reply_added', 
                    "Added reply to comment \"{$parentPreview}\": \"{$replyPreview}\"",
                    [
                        'comment_id' => $replyId,
                        'parent_comment_id' => $commentId,
                        'parent_comment_preview' => $parentPreview,
                        'reply_content' => $comment, // Full reply for tooltip
                        'reply_preview' => $replyPreview,
                        'action_type' => 'reply_added'
                    ]
                );
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Reply added successfully',
                'reply_id' => $replyId
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add reply'
            ]);
        }
    }

    public function updateComment($id)
    {
        $comment = $this->request->getPost('comment');
        $mentions = $this->request->getPost('mentions');
        $attachments = $this->request->getPost('attachments');

        if ($this->carWashCommentModel->updateComment($id, $comment, $mentions, $attachments)) {
            // Get comment for activity log
            $commentData = $this->carWashCommentModel->find($id);
            if ($commentData) {
                $this->carWashActivityModel->logCommentUpdated($commentData['order_id'], $this->getCurrentUserId());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Comment updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update comment'
            ]);
        }
    }

    public function deleteComment($id)
    {
        $commentData = $this->carWashCommentModel->find($id);
        
        if ($this->carWashCommentModel->delete($id)) {
            // Log activity with comment content
            if ($commentData) {
                $commentPreview = substr(trim($commentData['description']), 0, 50) . (strlen(trim($commentData['description'])) > 50 ? '...' : '');
                $this->carWashActivityModel->logCommentActivity(
                    $commentData['order_id'], 
                    $this->getCurrentUserId(),
                    'comment_deleted', 
                    "Deleted comment: \"{$commentPreview}\"",
                    [
                        'comment_id' => $id,
                        'deleted_comment' => $commentData['description'], // Full comment content for tooltip
                        'comment_preview' => $commentPreview,
                        'action_type' => 'comment_deleted'
                    ]
                );
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete comment'
            ]);
        }
    }

    public function getComments($id)
    {
        try {
        $comments = $this->carWashCommentModel->getCommentsWithReplies($id);
            
            // Process comments for display
            foreach ($comments as &$comment) {
                // Process attachments using the model method
                $comment['attachments'] = $this->carWashCommentModel->processAttachmentsJson($comment['attachments'], $id);

                // Parse mentions - handle different data types
                if (!empty($comment['mentions'])) {
                    if (is_string($comment['mentions'])) {
                        // Handle edge cases like empty string "[]" or malformed JSON
                        $trimmed = trim($comment['mentions']);
                        if ($trimmed === '[]' || $trimmed === '') {
                            $comment['mentions'] = [];
                        } else {
                            $decoded = json_decode($trimmed, true);
                            $comment['mentions'] = (is_array($decoded) && json_last_error() === JSON_ERROR_NONE) ? $decoded : [];
                        }
                    } elseif (is_array($comment['mentions'])) {
                        $comment['mentions'] = $comment['mentions'];
                    } else {
                        $comment['mentions'] = [];
                    }
                } else {
                    $comment['mentions'] = [];
                }

                // Parse metadata - handle different data types
                if (!empty($comment['metadata'])) {
                    if (is_string($comment['metadata'])) {
                        // Handle edge cases like empty string "[]" or malformed JSON
                        $trimmed = trim($comment['metadata']);
                        if ($trimmed === '[]' || $trimmed === '') {
                            $comment['metadata'] = [];
                        } else {
                            $decoded = json_decode($trimmed, true);
                            $comment['metadata'] = (is_array($decoded) && json_last_error() === JSON_ERROR_NONE) ? $decoded : [];
                        }
                    } elseif (is_array($comment['metadata'])) {
                        $comment['metadata'] = $comment['metadata'];
                    } else {
                        $comment['metadata'] = [];
                    }
                } else {
                    $comment['metadata'] = [];
                }

                // Generate avatar URL using system helper
                helper('avatar');
                $comment['avatar'] = getAvatarUrl($comment, 32);
                $comment['avatar_url'] = $comment['avatar']; // Add avatar_url alias for compatibility
                $comment['user_name'] = trim($comment['first_name'] . ' ' . $comment['last_name']);
                $comment['content'] = $comment['description'];
                $comment['created_at_relative'] = $this->timeAgo($comment['created_at']);
                
                // Process replies if any
                if (isset($comment['replies'])) {
                    foreach ($comment['replies'] as &$reply) {
                        // Process reply attachments, mentions and metadata same as parent comment
                        $reply['attachments'] = $this->carWashCommentModel->processAttachmentsJson($reply['attachments'], $id);
                        $reply['mentions'] = $this->processJsonField($reply['mentions']);
                        $reply['metadata'] = $this->processJsonField($reply['metadata']);
                        
                        // Generate avatar URL with fallback
                        try {
                            $reply['avatar'] = $this->generateAvatarUrl($reply);
                            $reply['avatar_url'] = $reply['avatar']; // Add avatar_url alias for compatibility
                        } catch (\Exception $e) {
                            log_message('error', 'Error generating reply avatar URL: ' . $e->getMessage());
                            // Fallback to helper function
                            helper('avatar');
                            $reply['avatar'] = getAvatarUrl($reply, 32);
                            $reply['avatar_url'] = $reply['avatar'];
                        }
                        $reply['user_name'] = trim($reply['first_name'] . ' ' . $reply['last_name']);
                        $reply['content'] = $reply['description'];
                        $reply['created_at_relative'] = $this->timeAgo($reply['created_at']);
                    }
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'comments' => $comments
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error loading comments: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading comments'
            ]);
        }
    }

    /**
     * Process JSON field - handle different data types
     */
    private function processJsonField($field)
    {
        if (empty($field)) {
            return [];
        }

        if (is_string($field)) {
            // Handle edge cases like empty string "[]" or malformed JSON
            $trimmed = trim($field);
            if ($trimmed === '[]' || $trimmed === '') {
                return [];
            } else {
                $decoded = json_decode($trimmed, true);
                return (is_array($decoded) && json_last_error() === JSON_ERROR_NONE) ? $decoded : [];
            }
        } elseif (is_array($field)) {
            return $field;
        } else {
            return [];
        }
    }

    /**
     * Generate avatar URL for comments
     */
    private function generateAvatarUrl($comment, $size = 32)
    {
        return $this->createAvatarUrl($comment, $size);
    }
    
    /**
     * Create avatar URL for comments (alternative method)
     */
    private function createAvatarUrl($comment, $size = 32)
    {
        // Check for uploaded avatar first
        if (!empty($comment['avatar']) && file_exists(FCPATH . 'assets/images/users/' . $comment['avatar'])) {
            return base_url('assets/images/users/' . $comment['avatar']);
        }
        
        // Generate initials-based avatar
        $initials = '';
        
        // Try to get from first_name and last_name
        if (!empty($comment['first_name']) && !empty($comment['last_name'])) {
            $initials = strtoupper(substr($comment['first_name'], 0, 1) . substr($comment['last_name'], 0, 1));
        }
        // Try to get from username
        elseif (!empty($comment['username'])) {
            $parts = explode('_', $comment['username']);
            if (count($parts) >= 2) {
                $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
            } else {
                $initials = strtoupper(substr($comment['username'], 0, 2));
            }
        }
        // Fallback to email
        elseif (!empty($comment['email'])) {
            $initials = strtoupper(substr($comment['email'], 0, 2));
        } else {
            $initials = 'U';
        }
        
        // Generate colors based on user ID for consistency
        $colors = [
            '3498db', '9b59b6', 'e74c3c', 'f39c12', 
            '2ecc71', '1abc9c', 'e67e22', 'f1c40f',
            '8e44ad', 'c0392b', 'd35400', '27ae60'
        ];
        $userId = $comment['user_id'] ?? $comment['created_by'] ?? 1;
        $colorIndex = $userId % count($colors);
        $backgroundColor = $colors[$colorIndex];
        
        // Use UI Avatars service
        $name = urlencode($initials);
        return "https://ui-avatars.com/api/?name={$name}&size={$size}&background={$backgroundColor}&color=ffffff&bold=true&format=png";
    }

    // Services management
    public function getServices()
    {
        try {
            $services = $this->carWashServiceModel->findAll();
        return $this->response->setJSON(['data' => $services]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getServicesForClient($clientId)
    {
        try {
            $userType = session()->get('user_type') ?? 'client';
            $services = $this->carWashServiceModel->getServicesForUser($userType, $clientId);
            return $this->response->setJSON([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            log_message('error', 'CarWash getServicesForClient - Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'data' => []
            ]);
        }
    }

    public function getContactsForClient($clientId)
    {
        try {
            $contacts = $this->contactModel->select('id, name, email, client_id')
                                          ->where('client_id', $clientId)
                                          ->findAll();
            return $this->response->setJSON([
                'success' => true,
                'data' => $contacts
            ]);
        } catch (\Exception $e) {
            log_message('error', 'CarWash getContactsForClient - Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'data' => []
            ]);
        }
    }

    public function getActiveClients()
    {
        try {
            $clients = $this->clientModel
                ->where('status', 'active')
                ->orderBy('name', 'ASC')
                ->findAll();
            return $this->response->setJSON(['data' => $clients]);
        } catch (\Exception $e) {
            log_message('error', 'CarWash getActiveClients - Error: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getFormData()
    {
        try {
            $clients = $this->clientModel
                ->where('status', 'active')
                ->orderBy('name', 'ASC')
                ->findAll();

            $userType = session()->get('user_type') ?? 'client';
            $services = $this->carWashServiceModel
                ->getServicesForUser($userType);

            return $this->response->setJSON([
                'success' => true,
                'clients' => $clients,
                'services' => $services
            ]);
        } catch (\Exception $e) {
            log_message('error', 'CarWash getFormData - Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'clients' => [],
                'services' => []
            ]);
        }
    }

    // Notifications
    public function sendEmail($id)
    {
        // Implementation for sending email notifications
        // This would integrate with your email service
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Email sent successfully'
        ]);
    }

    public function sendSMS($id)
    {
        // Implementation for sending SMS notifications
        // This would integrate with your SMS service
        return $this->response->setJSON([
            'success' => true,
            'message' => 'SMS sent successfully'
        ]);
    }

    // QR Code
    public function regenerateQR($id)
    {
        // Set JSON content type
        $this->response->setContentType('application/json');
        
        try {
            // Find the order
            $order = $this->carWashOrderModel->find($id);
            
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
            
            // Clear existing QR data to force regeneration
            $updateData = [
                'short_url' => null,
                'short_url_slug' => null,
                'lima_link_id' => null,
                'qr_generated_at' => null
            ];
            
            $this->carWashOrderModel->update($id, $updateData);
            
            // Generate new QR code
            $qrData = $this->generateOrderQR($id);
            
            if ($qrData) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'QR Code regenerated successfully with MDA Links!',
                    'qr_data' => $qrData
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to generate QR code'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'CarWash regenerateQR - Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while regenerating QR code'
            ]);
        }
    }

    /**
     * Generate QR code data for a car wash order
     */
    private function generateOrderQR($orderId, $size = '300', $format = 'png')
    {
        try {
            log_message('info', "Starting QR generation for car wash order: {$orderId}, size: {$size}, format: {$format}");
            
            // Check if this order already has a short URL (static/persistent)
            $order = $this->carWashOrderModel->find($orderId);
            
            if (!$order) {
                log_message('error', "Car wash order {$orderId} not found");
                return null;
            }
            
            $shortUrl = null;
            $linkId = null;
            $orderUrl = base_url("car_wash/view/{$orderId}");
            
            // Check if we already have a short URL for this order
            if ($order['short_url'] && $order['short_url_slug'] && $order['lima_link_id']) {
                $shortUrl = $order['short_url'];
                $linkId = $order['lima_link_id'];
                log_message('info', "Using existing static short URL for car wash order {$orderId}: {$shortUrl} (ID: {$linkId})");
            } else {
                // Create new short URL and save it as static
                $settingsModel = new \App\Models\SettingsModel();
                $apiKey = $settingsModel->getSetting('lima_api_key');
                $brandedDomain = $settingsModel->getSetting('lima_branded_domain');
                
                if ($apiKey) {
                    log_message('info', "Creating NEW static short URL via Lima Links API with 5-digit slug for car wash order {$orderId}...");
                    try {
                        // Create short URL with Lima Links using 5-digit slug
                        $shortUrlData = $this->createShortUrl($apiKey, $orderUrl, null, $brandedDomain);
                        if ($shortUrlData && isset($shortUrlData['shorturl'])) {
                            $shortUrl = $shortUrlData['shorturl'];
                            $linkId = $shortUrlData['id'] ?? null;
                                
                            // Extract the slug from the short URL
                            $shortUrlSlug = null;
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
                            
                            $this->carWashOrderModel->update($orderId, $updateData);
                            log_message('info', "Lima Links short URL created and SAVED as static for car wash order {$orderId}: {$shortUrl} (ID: {$linkId}, Slug: {$shortUrlSlug})");
                        } else {
                            log_message('warning', "Lima Links API returned invalid response for car wash order {$orderId}");
                            $shortUrl = $orderUrl; // Fallback to original URL
                        }
                    } catch (Exception $e) {
                        log_message('warning', "Failed to create Lima Links short URL for car wash order {$orderId}, using original: " . $e->getMessage());
                        $shortUrl = $orderUrl; // Fallback to original URL
                    }
                } else {
                    log_message('warning', "No Lima Links API key configured, using original URL for car wash order {$orderId}");
                    $shortUrl = $orderUrl; // Fallback to original URL
                }
            }
            
            // Generate QR code using MDA.to API or fallback service
            $qrImageUrl = null;
            
            // Test if API key is valid
            $isValidApiKey = !empty($apiKey) && $apiKey !== 'your_lima_links_api_key_here' && strlen($apiKey) >= 5;
            
            // First try to use MDA.to QR API if we have a valid API key and short URL
            if ($isValidApiKey && $shortUrl !== $orderUrl) {
                $qrImageUrl = $this->generateQRCodeViaMDA($apiKey, $shortUrl);
                if ($qrImageUrl) {
                    log_message('info', "MDA QR code generated successfully: {$qrImageUrl}");
                }
            }
            
            if (!$qrImageUrl) {
                // Fallback to external QR service
                $qrImageUrl = $this->generateQRCodeViaAPI($shortUrl, $size);
                log_message('info', "Using fallback QR service: {$qrImageUrl}");
            }
            
            if (!$qrImageUrl) {
                log_message('error', "Failed to generate QR code for car wash order {$orderId}");
                return null;
            }
            
            // Create the QR data array
            $qrData = [
                'short_url' => $shortUrl,
                'qr_url' => $qrImageUrl, // QR Server API URL
                'order_url' => $orderUrl,
                'size' => $size,
                'format' => $format,
                'link_id' => $linkId,
                'generated_at' => date('Y-m-d H:i:s'),
                'is_static' => (bool)($order['short_url'] && $order['short_url_slug']),
                'provider' => [
                    'shortener' => $shortUrl !== $orderUrl ? 'MDA Links (5-digit slug, STATIC)' : 'Direct URL',
                    'qr_generator' => $isValidApiKey && $shortUrl !== $orderUrl ? 'MDA.to API' : 'QR Server API'
                ]
            ];
            
            log_message('info', "QR generation successful for car wash order {$orderId} - Short URL: {$shortUrl}, QR URL: {$qrImageUrl}, Static: " . ($qrData['is_static'] ? 'YES' : 'NO'));
            return $qrData;
            
        } catch (Exception $e) {
            log_message('error', "QR generation failed for car wash order {$orderId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate QR code using QR Server API (external service)
     */
    private function generateQRCodeViaAPI($url, $size = '300')
    {
        try {
            $qrSize = min(max((int)$size, 100), 1000); // Clamp between 100-1000
            
            // Use QR Server API (free, reliable, and fast)
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$qrSize}x{$qrSize}&format=png&data=" . urlencode($url);
            
            log_message('info', "Generated QR using QR Server API: {$qrUrl}");
            return $qrUrl;
            
        } catch (Exception $e) {
            log_message('error', "QR Server API generation failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate a unique short alphanumeric slug with collision detection
     */
    private function generateShortSlug($length = 5, $maxAttempts = 10)
    {
        // Character set for generating short slugs: alphanumeric excluding confusing characters
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $slug = '';
            
            // Generate random slug of specified length
            for ($i = 0; $i < $length; $i++) {
                $slug .= $characters[rand(0, $charactersLength - 1)];
            }
            
            // Check if this slug already exists in our database
            $existingOrder = $this->carWashOrderModel->where('short_url_slug', $slug)->first();
            
            if (!$existingOrder) {
                log_message('info', "Generated unique {$length}-digit slug: {$slug} (attempt {$attempt})");
                return $slug;
            }
            
            log_message('warning', "Slug collision detected: {$slug} (attempt {$attempt})");
            
            // If we've tried 5 times with current length and still getting collisions,
            // automatically expand to 6 digits for more possible combinations
            if ($attempt == 5 && $length == 5) {
                $length = 6;
                log_message('info', "Expanding to 6-digit slugs due to collisions");
            }
        }
        
        // If we still can't generate a unique slug after all attempts, 
        // fall back to timestamp-based approach
        $fallbackSlug = substr(uniqid(), -$length);
        log_message('warning', "Using fallback slug: {$fallbackSlug} after {$maxAttempts} attempts");
        return $fallbackSlug;
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
            'name' => 'Car Wash Order QR Code'
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
     * Create short URL using Lima Links API with collision handling
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
                'expiry' => null,
                'description' => 'Car Wash Order QR Code'
            ];
            
            log_message('info', "Lima Links API payload: " . json_encode($payload));
            
            $response = $this->callMDALinksAPI($apiKey, $payload);
            
            if ($response['success']) {
                log_message('info', "Lima Links API success: " . json_encode($response['data']));
                return $response['data'];
            } else {
                log_message('error', "Lima Links API error: " . $response['error']);
                return null;
            }
            
        } catch (Exception $e) {
            log_message('error', "Create short URL failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Call Lima Links API
     */
    private function callLimaLinksAPI($apiKey, $payload)
    {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => \App\Helpers\LimaLinksHelper::buildApiUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
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
                'error' => 'HTTP Error: ' . $httpCode
            ];
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Invalid JSON response'
            ];
        }
        
        if (!isset($data['error']) || $data['error'] !== 0) {
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

    // Validation

    /**
     * DEPRECATED: This method has been disabled to allow duplicate orders
     * Only recent duplicates are now checked via JavaScript to prevent accidental double submissions
     */
    /*
    public function checkDuplicateOrder()
    {
        $clientId = $this->request->getPost('client_id');
        $date = $this->request->getPost('date');
        $time = $this->request->getPost('time');
        $excludeId = $this->request->getPost('exclude_id');

        $duplicate = $this->carWashOrderModel->checkDuplicateOrder($clientId, $date, $time, $excludeId);

        return $this->response->setJSON([
            'exists' => $duplicate ? true : false,
            'order' => $duplicate
        ]);
    }
    */

    /**
     * Get duplicate information for an order (VIN and Tag/Stock duplicates)
     */
    private function getDuplicateInfo($order)
    {
        $duplicates = [
            'has_duplicates' => false,
            'tag_stock_duplicates' => [],
            'vin_duplicates' => []
        ];

        try {
            $db = \Config\Database::connect();

            // Check for tag/stock duplicates (if tag_stock is not empty)
            if (!empty($order['tag_stock'])) {
                $tagStockDuplicates = $db->table('car_wash_orders')
                    ->select('id, order_number, vehicle, created_at, tag_stock')
                    ->where('tag_stock', $order['tag_stock'])
                    ->where('id !=', $order['id'])
                    ->where('deleted_at IS NULL')
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->getResultArray();

                if (!empty($tagStockDuplicates)) {
                    $duplicates['has_duplicates'] = true;
                    $duplicates['tag_stock_duplicates'] = $tagStockDuplicates;
                }
            }

            // Check for VIN duplicates (if vin_number is not empty)
            if (!empty($order['vin_number'])) {
                $vinDuplicates = $db->table('car_wash_orders')
                    ->select('id, order_number, vehicle, created_at, vin_number')
                    ->where('vin_number', $order['vin_number'])
                    ->where('id !=', $order['id'])
                    ->where('deleted_at IS NULL')
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->getResultArray();

                if (!empty($vinDuplicates)) {
                    $duplicates['has_duplicates'] = true;
                    $duplicates['vin_duplicates'] = $vinDuplicates;
                }
            }

        } catch (\Exception $e) {
            log_message('error', 'Error checking duplicates for order ' . $order['id'] . ': ' . $e->getMessage());
        }

        return $duplicates;
    }

    /**
     * Check for recent duplicates in tag/stock or vin_number (configurable time window)
     * This prevents accidental double submissions
     */
    public function checkRecentDuplicates()
    {
        $field = $this->request->getPost('field'); // 'tag_stock' or 'vin_number'
        $value = $this->request->getPost('value');
        $currentOrderId = $this->request->getPost('current_order_id'); // For edit mode
        $minutes = $this->request->getPost('minutes') ?: 5; // Default to 5 minutes if not provided

        if (!$field || !$value) {
            return $this->response->setJSON([
                'success' => false,
                'has_duplicates' => false,
                'message' => 'Missing required parameters'
            ]);
        }

        // Check if field is allowed
        if (!in_array($field, ['tag_stock', 'vin_number'])) {
            return $this->response->setJSON([
                'success' => false,
                'has_duplicates' => false,
                'message' => 'Invalid field'
            ]);
        }

        // Validate minutes parameter (only allow 5, 10, 15)
        if (!in_array((int)$minutes, [5, 10, 15])) {
            $minutes = 5; // Default to 5 if invalid value
        }

        try {
            // Look for duplicates in the specified time window
            $builder = $this->carWashOrderModel->builder();
            $builder->where($field, $value);
            $builder->where('created_at >=', date('Y-m-d H:i:s', strtotime("-{$minutes} minutes")));
            
            // Exclude current order if editing
            if ($currentOrderId) {
                $builder->where('id !=', $currentOrderId);
            }
            
            $duplicates = $builder->get()->getResultArray();

            if (!empty($duplicates)) {
                // Format duplicate information
                $duplicateInfo = [];
                foreach ($duplicates as $duplicate) {
                    $duplicateInfo[] = [
                        'id' => $duplicate['id'],
                        'order_number' => $duplicate['order_number'] ?? 'N/A',
                        'vehicle' => $duplicate['vehicle'] ?? 'N/A',
                        'created_at' => $duplicate['created_at'],
                        'minutes_ago' => round((time() - strtotime($duplicate['created_at'])) / 60, 1)
                    ];
                }

                return $this->response->setJSON([
                    'success' => true,
                    'has_duplicates' => true,
                    'duplicates' => [
                        $field => [
                            'value' => $value,
                            'count' => count($duplicates),
                            'orders' => $duplicateInfo
                        ]
                    ],
                    'message' => 'Recent duplicate found'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => true,
                    'has_duplicates' => false,
                    'message' => 'No recent duplicates found'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'CarWash checkRecentDuplicates - Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'has_duplicates' => false,
                'message' => 'Error checking duplicates'
            ]);
        }
    }

    /**
     * Temporary debug method to check duplicates in database
     */
    public function debugDuplicates()
    {
        $tagStock = '4567';
        $vin = '1N4AA6AP0JC368510';
        $minutes = 10;

        $db = \Config\Database::connect();
        
        $result = [
            'tag_stock_recent' => [],
            'vin_recent' => [],
            'tag_stock_all' => [],
            'vin_all' => []
        ];

        // Check tag_stock recent duplicates
        $tagStockRecent = $db->table('car_wash_orders')
            ->where('tag_stock', $tagStock)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime("-{$minutes} minutes")))
            ->get()
            ->getResultArray();

        foreach ($tagStockRecent as $order) {
            $minutesAgo = round((time() - strtotime($order['created_at'])) / 60, 1);
            $result['tag_stock_recent'][] = [
                'id' => $order['id'],
                'order_number' => $order['order_number'] ?? 'N/A',
                'minutes_ago' => $minutesAgo,
                'created_at' => $order['created_at']
            ];
        }

        // Check VIN recent duplicates
        $vinRecent = $db->table('car_wash_orders')
            ->where('vin_number', $vin)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime("-{$minutes} minutes")))
            ->get()
            ->getResultArray();

        foreach ($vinRecent as $order) {
            $minutesAgo = round((time() - strtotime($order['created_at'])) / 60, 1);
            $result['vin_recent'][] = [
                'id' => $order['id'],
                'order_number' => $order['order_number'] ?? 'N/A',
                'minutes_ago' => $minutesAgo,
                'created_at' => $order['created_at']
            ];
        }

        // Check all tag_stock duplicates
        $tagStockAll = $db->table('car_wash_orders')
            ->where('tag_stock', $tagStock)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($tagStockAll as $order) {
            $minutesAgo = round((time() - strtotime($order['created_at'])) / 60, 1);
            $result['tag_stock_all'][] = [
                'id' => $order['id'],
                'order_number' => $order['order_number'] ?? 'N/A',
                'minutes_ago' => $minutesAgo,
                'created_at' => $order['created_at']
            ];
        }

        // Check all VIN duplicates
        $vinAll = $db->table('car_wash_orders')
            ->where('vin_number', $vin)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($vinAll as $order) {
            $minutesAgo = round((time() - strtotime($order['created_at'])) / 60, 1);
            $result['vin_all'][] = [
                'id' => $order['id'],
                'order_number' => $order['order_number'] ?? 'N/A',
                'minutes_ago' => $minutesAgo,
                'created_at' => $order['created_at']
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'search_params' => [
                'tag_stock' => $tagStock,
                'vin' => $vin,
                'minutes' => $minutes,
                'current_time' => date('Y-m-d H:i:s')
            ],
            'results' => $result
        ]);
    }

    /**
     * Convert datetime to human readable format
     */
    private function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 604800) return floor($time/86400) . ' days ago';
        if ($time < 2629746) return floor($time/604800) . ' weeks ago';
        
        return date('M j, Y', strtotime($datetime));
    }

    /**
     * Serve attachment files
     */
    public function serveAttachment($orderId, $type, $filename)
    {
        // Debug logging
        log_message('debug', 'ServeAttachment called with: orderId=' . $orderId . ', type=' . $type . ', filename=' . $filename);
        
        // Validate order ID
        if (!is_numeric($orderId)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid order ID');
        }
        
        // For simple case, filename is just the filename
        // For complex case like thumbnails, we need to handle it differently
        $actualFilename = $filename;
        $fullType = $type;
        
        // Check if filename contains directory separators (like thumbnails/filename.jpg)
        if (strpos($filename, '/') !== false) {
            $pathParts = explode('/', $filename);
            $actualFilename = array_pop($pathParts);
            $fullType = $type . '/' . implode('/', $pathParts);
        }
        
        // Validate type (only allow specific types)
        $allowedTypes = ['comments', 'orders', 'attachments'];
        $allowedSubTypes = ['thumbnails'];
        
        // Check for subtypes (like comments/thumbnails)
        $typeParts = explode('/', $fullType);
        $mainType = $typeParts[0];
        $subType = isset($typeParts[1]) ? $typeParts[1] : null;
        
        if (!in_array($mainType, $allowedTypes)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid attachment type');
        }
        
        if ($subType && !in_array($subType, $allowedSubTypes)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid attachment subtype');
        }
        
        // Sanitize filename
        $actualFilename = basename($actualFilename);
        if (empty($actualFilename)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid filename');
        }
        
        // Construct file path
        $filePath = ROOTPATH . 'public/uploads/car_wash/' . $orderId . '/' . $fullType . '/' . $actualFilename;
        
        // Debug logging
        log_message('debug', 'Constructed file path: ' . $filePath);
        log_message('debug', 'File exists: ' . (file_exists($filePath) ? 'YES' : 'NO'));
        
        // Check if file exists
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found: ' . $actualFilename);
        }
        
        // Verify user has access to this order (basic security check)
        $order = $this->carWashOrderModel->find($orderId);
        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
        }
        
        // Get file info
        $fileInfo = pathinfo($filePath);
        $mimeType = $this->getMimeType($fileInfo['extension'] ?? '');
        
        // Set headers for file download/display
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Length', filesize($filePath));
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . $actualFilename . '"');
        $this->response->setHeader('Cache-Control', 'public, max-age=3600');
        
        // Send file
        return $this->response->setBody(file_get_contents($filePath));
    }

    /**
     * Get MIME type based on file extension
     */
    private function getMimeType($extension)
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg+xml',
            'txt' => 'text/plain',
            'rtf' => 'application/rtf',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            '7z' => 'application/x-7z-compressed',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'wmv' => 'video/x-ms-wmv',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'flac' => 'audio/flac',
        ];
        
        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }

    /**
     * Test route to verify routes are loading correctly
     */
    public function testRoute()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'CarWash module routes are working correctly!',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Test attachment route to debug parameters
     */
    public function testAttachment($orderId, $type, $filename)
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Attachment route test',
            'orderId' => $orderId,
            'type' => $type,
            'filename' => $filename,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Debug attachment serving
     */
    public function debugAttachment($orderId, $type, $filename)
    {
        // For simple case, filename is just the filename
        // For complex case like thumbnails, we need to handle it differently
        $actualFilename = $filename;
        $fullType = $type;
        
        // Check if filename contains directory separators (like thumbnails/filename.jpg)
        if (strpos($filename, '/') !== false) {
            $pathParts = explode('/', $filename);
            $actualFilename = array_pop($pathParts);
            $fullType = $type . '/' . implode('/', $pathParts);
        }
        
        // Construct file path
        $filePath = ROOTPATH . 'public/uploads/car_wash/' . $orderId . '/' . $fullType . '/' . $actualFilename;
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Debug attachment serving',
            'orderId' => $orderId,
            'type' => $type,
            'filename' => $filename,
            'actualFilename' => $actualFilename,
            'fullType' => $fullType,
            'filePath' => $filePath,
            'fileExists' => file_exists($filePath),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Simple test to serve a file directly
     */
    public function serveFileTest()
    {
        $filePath = ROOTPATH . 'public/uploads/car_wash/3/comments/1752690864_a674cb8689f93ff65540.pdf';
        
        if (!file_exists($filePath)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File not found',
                'path' => $filePath
            ]);
        }
        
        // Get file info
        $fileInfo = pathinfo($filePath);
        $mimeType = $this->getMimeType($fileInfo['extension'] ?? '');
        
        // Set headers for file display
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Length', filesize($filePath));
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"');
        $this->response->setHeader('Cache-Control', 'public, max-age=3600');
        
        // Send file
        return $this->response->setBody(file_get_contents($filePath));
    }

    /**
     * Get users for mentions
     */
    public function getUsersForMentions()
    {
        try {
            $search = $this->request->getGet('search') ?? '';
            
            $users = $this->userModel->select('id, first_name, last_name, username')
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
                'data' => []
            ]);
        }
    }

    /**
     * Get relative time for display
     */
    private function getRelativeTime($datetime)
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;
        
        if ($diff < 60) {
            return 'just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 2592000) {
            $weeks = floor($diff / 604800);
            return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
        } else {
            return date('M j, Y', $time);
        }
    }

}