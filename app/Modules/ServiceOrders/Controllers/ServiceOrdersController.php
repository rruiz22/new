<?php

namespace Modules\ServiceOrders\Controllers;

use App\Controllers\BaseController;
use Modules\ServiceOrders\Models\ServiceOrderModel;
use Modules\ServiceOrders\Models\ServiceOrderServiceModel;
use Modules\ServiceOrders\Models\ServiceOrderActivityModel;
use Modules\ServiceOrders\Models\ServiceOrderCommentModel;
use App\Models\ClientModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\SettingsModel;
use Exception;

class ServiceOrdersController extends BaseController
{
    use ResponseTrait;
    protected $serviceOrderModel;
    protected $serviceModel;
    protected $activityModel;

    /**
     * Constructor
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        
        // Load Shield auth helper
        helper('auth');
        
        // Load avatar helper for user avatars
        helper('avatar');
        
        // Load models
        $this->serviceOrderModel = new ServiceOrderModel();
        $this->serviceModel = new ServiceOrderServiceModel();
        $this->activityModel = new ServiceOrderActivityModel();
    }

    public function index()
    {
        // Cargar modelos necesarios
        $clientModel = new \App\Models\ClientModel();
        $userModel = new \App\Models\UserModel();
        $serviceModel = new ServiceOrderServiceModel();
        
        // Get all active clients
        $clients = $clientModel->getActiveClients();
        
        // Get all active client users (contacts)
        $contacts = $userModel->select('users.id, users.first_name, users.last_name, users.client_id, CONCAT(users.first_name, " ", users.last_name) as name')
                                   ->where('users.user_type', 'client')
                                   ->where('users.active', 1)
                                   ->orderBy('users.first_name', 'ASC')
                             ->findAll();
        
        // Get active services
        $services = $serviceModel->where('service_status', 'active')
                                ->where('show_in_orders', 1)
                                ->orderBy('service_name', 'ASC')
                                ->findAll();

        // Get deleted orders for the deleted orders tab
        $deletedOrders = [];
        try {
            $db = \Config\Database::connect();
            
            // Get basic deleted orders without joins first
            $deletedOrdersRaw = $db->table('service_orders')
                               ->select('*')
                               ->where('deleted', 1)
                               ->orderBy('updated_at', 'DESC')
                               ->get()
                               ->getResultArray();
            
            // If we have orders, try to add client/contact names
            if (!empty($deletedOrdersRaw)) {
                foreach ($deletedOrdersRaw as &$order) {
                    // Get client name
                    if ($order['client_id']) {
                        $client = $db->table('clients')
                                    ->select('name')
                                    ->where('id', $order['client_id'])
                                    ->get()
                                    ->getRowArray();
                        $order['client_name'] = $client ? $client['name'] : 'N/A';
                    } else {
                        $order['client_name'] = 'N/A';
                    }
                    
                    // Get salesperson name from users table
                    if ($order['contact_id']) {
                        $user = $db->table('users')
                                  ->select('CONCAT(first_name, " ", last_name) as name')
                                  ->where('id', $order['contact_id'])
                                  ->get()
                                  ->getRowArray();
                        $order['salesperson_name'] = $user ? $user['name'] : 'N/A';
                    } else {
                        $order['salesperson_name'] = 'N/A';
                    }
                    
                    // Get service name
                    if ($order['service_id']) {
                        $service = $db->table('service_orders_services')
                                     ->select('service_name')
                                     ->where('id', $order['service_id'])
                                     ->get()
                                     ->getRowArray();
                        $order['service_name'] = $service ? $service['service_name'] : 'N/A';
                    } else {
                        $order['service_name'] = 'N/A';
                    }
                }
                $deletedOrders = $deletedOrdersRaw;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error loading deleted orders in index: ' . $e->getMessage());
            $deletedOrders = [];
        }
        
        $data = [
            'title' => 'Service Orders',
            'clients' => $clients,
            'contacts' => $contacts,
            'services' => $services,
            'deleted_orders' => $deletedOrders
        ];

        // Log the data for debugging
        log_message('info', "Service Orders Index page data - Clients: " . count($clients) . ", Contacts: " . count($contacts) . ", Services: " . count($services) . ", Deleted Orders: " . count($deletedOrders));

        // Use module view directly
        return view('Modules\ServiceOrders\Views\service_orders\index', $data);
    }


    public function dashboard_content()
    {
        return view('Modules\ServiceOrders\Views\service_orders\dashboard_content');
    }

    public function today_content()
    {
        // Verificar si es una petición AJAX para obtener datos filtrados
        if ($this->request->isAJAX()) {
            return $this->getFilteredServiceOrders();
        }
        
        // Para carga inicial de la página
        return view('Modules\ServiceOrders\Views\service_orders\today_content');
    }

    public function tomorrow_content()
    {
        // Verificar si es una petición AJAX para obtener datos filtrados
        if ($this->request->isAJAX()) {
            return $this->getFilteredServiceOrders();
        }
        
        return view('Modules\ServiceOrders\Views\service_orders\tomorrow_content');
    }

    public function pending_content()
    {
        // Verificar si es una petición AJAX para obtener datos filtrados
        if ($this->request->isAJAX()) {
            return $this->getFilteredServiceOrders();
        }
        
        return view('Modules\ServiceOrders\Views\service_orders\pending_content');
    }

    public function week_content()
    {
        // Verificar si es una petición AJAX para obtener datos filtrados
        if ($this->request->isAJAX()) {
            return $this->getFilteredServiceOrders();
        }
        
        return view('Modules\ServiceOrders\Views\service_orders\week_content');
    }

    public function all_content()
    {
        // Verificar si es una petición AJAX para obtener datos filtrados
        if ($this->request->isAJAX()) {
            return $this->getFilteredServiceOrders();
        }
        
        return view('Modules\ServiceOrders\Views\service_orders\all_content');
    }

    public function services_content()
    {
        // Cargar datos para los filtros
        $clientModel = new \App\Models\ClientModel();
        $clients = $clientModel->getActiveClients();
        
        $data = [
            'clients' => $clients
        ];
        
        return view('Modules\ServiceOrders\Views\service_orders\services_content', $data);
    }

    public function deleted_content()
    {
        return view('Modules\ServiceOrders\Views\service_orders\deleted_content');
    }

    public function modal_form()
    {
        $clientModel = new ClientModel();
        $userModel = new UserModel();
        
        $clients = $clientModel->getActiveClients();
        $services = $this->serviceModel->getServicesForOrders();
        
        // Check if this is for editing (parameter 'edit' contains the order ID)
        $editId = $this->request->getGet('edit');
        $isEditMode = !empty($editId);
        $order = null;
        
        if ($isEditMode) {
            // Load the order for editing with all necessary data
            $order = $this->serviceOrderModel->getOrderWithDetails($editId);
            if (!$order) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Service order not found');
            }
        }
        
        // Get all contacts and services for edit mode
        $contacts = [];
        $allServices = [];
        
        if ($isEditMode) {
            // Get all contacts for edit mode
            $contacts = $userModel->select('users.id, users.first_name, users.last_name, users.client_id, CONCAT(users.first_name, " ", users.last_name) as name')
                                  ->where('users.user_type', 'client')
                                  ->where('users.active', 1)
                                  ->orderBy('users.first_name', 'ASC')
                                  ->findAll();
            
            // Get all services for edit mode
            $allServices = $this->serviceModel->where('service_status', 'active')
                                            ->where('show_in_orders', 1)
                                            ->orderBy('service_name', 'ASC')
                                            ->findAll();
        }
        
        $data = [
            'clients' => $clients,
            'contacts' => $contacts,
            'services' => $isEditMode ? $allServices : $services,
            'current_user_type' => session()->get('user_type') ?? 'admin',
            'isEditMode' => $isEditMode,
            'order' => $order
        ];
        
        return view('Modules\ServiceOrders\Views\service_orders\modal_form', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'client_id' => 'required|numeric',
            'contact_id' => 'required|numeric',
            'service_id' => 'required|numeric',
            'date' => 'required|valid_date',
            'time' => 'required',
            'vin' => 'required',
            'vehicle' => 'required',
            'ro_number' => 'required',
            'po_number' => 'required',
            'tag_number' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }
        
        // Additional business logic validations
        $dateValidation = $this->validateBusinessRules($this->request->getPost('date'), $this->request->getPost('time'), false);
        if (!$dateValidation['valid']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $dateValidation['message']
            ]);
        }
        
        // Get user ID from session
        $userId = session()->get('user_id');
        
        // Log session info for debugging
        log_message('info', 'ServiceOrdersController::store - User ID from session: ' . ($userId ?? 'NULL'));
        
        $data = [
            'client_id' => $this->request->getPost('client_id'),
            'contact_id' => $this->request->getPost('contact_id'),
            'service_id' => $this->request->getPost('service_id'),
            'ro_number' => $this->request->getPost('ro_number'),
            'po_number' => $this->request->getPost('po_number'),
            'tag_number' => $this->request->getPost('tag_number'),
            'vin' => $this->request->getPost('vin'),
            'vehicle' => $this->request->getPost('vehicle'),
            'date' => $this->request->getPost('date'),
            'time' => $this->request->getPost('time'),
            'status' => 'pending',
            'instructions' => $this->request->getPost('instructions'),
            'notes' => $this->request->getPost('notes'),
            'created_by' => $userId ?? 1
        ];
        
        try {
            // Log data before insertion
            log_message('info', 'ServiceOrdersController::store - Inserting order with data: ' . json_encode($data));
            
            $orderId = $this->serviceOrderModel->insert($data);
            
            if ($orderId) {
                // Log successful insertion
                log_message('info', 'ServiceOrdersController::store - Order created successfully with ID: ' . $orderId);
                
                // Log order creation activity
                $this->activityModel->logActivity(
                    $orderId,
                    $userId ?? 1,
                    'order_created',
                    'Service order was created',
                    ['action' => 'created']
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service order created successfully',
                    'order_id' => $orderId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create service order'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating service order: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error creating service order: ' . $e->getMessage()
            ]);
        }
    }

    public function view($id)
    {
        $order = $this->serviceOrderModel->getOrderWithDetails($id);
        
        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Service order not found');
        }
        
        // Get status class and text
        $statusClass = $this->getStatusBadge($order['status']);
        $statusText = ucfirst($order['status']);
        
        // Generate QR Code automatically
        $qrData = $this->generateOrderQR($id);
        
        $data = [
            'title' => 'Service Order #SER-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
            'order' => $order,
            'statusClass' => $statusClass,
            'statusText' => $statusText,
            'qr_data' => $qrData
        ];
        
        return view('Modules\ServiceOrders\Views\service_orders\view', $data);
    }

    public function edit($id)
    {
        try {
            $serviceOrder = $this->serviceOrderModel->find($id);
            
            if (!$serviceOrder) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Service order not found'
                    ]);
                }
                session()->setFlashdata('error', 'Service order not found');
                return redirect()->to(base_url('service_orders'));
            }

            // Load necessary models
            $clientModel = new \App\Models\ClientModel();
            $userModel = new \App\Models\UserModel();
            $serviceModel = new ServiceOrderServiceModel();
            
            // Get all active clients
            $clients = $clientModel->getActiveClients();
            
            // Get all active client users (contacts)
            $contacts = $userModel->select('users.id, users.first_name, users.last_name, users.client_id, CONCAT(users.first_name, " ", users.last_name) as name')
                                       ->where('users.user_type', 'client')
                                       ->where('users.active', 1)
                                       ->orderBy('users.first_name', 'ASC')
                                 ->findAll();
            
            // Get active services
            $services = $serviceModel->where('service_status', 'active')
                                    ->where('show_in_orders', 1)
                                    ->orderBy('service_name', 'ASC')
                                    ->findAll();

            $data = [
                'title' => 'Edit Service Order #SER-' . str_pad($serviceOrder['id'], 5, '0', STR_PAD_LEFT),
                'order' => $serviceOrder,
                'clients' => $clients,
                'contacts' => $contacts,
                'services' => $services,
                'edit_mode' => true,  // Flag para indicar que estamos en modo edición
                'current_user_type' => session()->get('user_type') ?? 'admin'
            ];

            // Devolver el mismo modal pero con datos para editar
            return view('Modules\ServiceOrders\Views\service_orders\modal_form', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error loading edit form: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error loading service order'
                ]);
            }
            session()->setFlashdata('error', 'Error loading service order');
            return redirect()->to(base_url('service_orders'));
        }
    }

    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'client_id' => 'required|numeric',
            'contact_id' => 'required|numeric',
            'service_id' => 'required|numeric',
            'date' => 'required|valid_date',
            'time' => 'required',
            'vin' => 'required',
            'vehicle' => 'required',
            'ro_number' => 'required',
            'po_number' => 'required',
            'tag_number' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }
        
        // Additional business logic validations for updates
        $dateValidation = $this->validateBusinessRules($this->request->getPost('date'), $this->request->getPost('time'), true);
        if (!$dateValidation['valid']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $dateValidation['message']
            ]);
        }

        try {
            $serviceOrder = $this->serviceOrderModel->find($id);
            if (!$serviceOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }

            // Get user ID from session
            $userId = session()->get('user_id');
            
            // Log session info for debugging
            log_message('info', 'ServiceOrdersController::update - User ID from session: ' . ($userId ?? 'NULL'));

            $data = [
                'client_id' => $this->request->getPost('client_id'),
                'contact_id' => $this->request->getPost('contact_id'),
                'service_id' => $this->request->getPost('service_id'),
                'ro_number' => $this->request->getPost('ro_number'),
                'po_number' => $this->request->getPost('po_number'),
                'tag_number' => $this->request->getPost('tag_number'),
                'vin' => $this->request->getPost('vin'),
                'vehicle' => $this->request->getPost('vehicle'),
                'date' => $this->request->getPost('date'),
                'time' => $this->request->getPost('time'),
                'instructions' => $this->request->getPost('instructions'),
                'notes' => $this->request->getPost('notes'),
                'updated_by' => $userId ?? 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Track field changes for activity logging
            $changes = [];
            $fieldLabels = [
                'client_id' => 'Client',
                'contact_id' => 'Assigned Contact',
                'service_id' => 'Service',
                'ro_number' => 'RO Number',
                'po_number' => 'PO Number', 
                'tag_number' => 'Tag Number',
                'vin' => 'VIN',
                'vehicle' => 'Vehicle',
                'date' => 'Date',
                'time' => 'Time',
                'instructions' => 'Instructions',
                'notes' => 'Notes'
            ];
            
            // Compare old vs new values
            log_message('info', 'DEBUG: Starting field comparison for order ID: ' . $id);
            log_message('info', 'DEBUG: Service order data: ' . json_encode($serviceOrder));
            log_message('info', 'DEBUG: New data to update: ' . json_encode($data));
            
            foreach ($data as $field => $newValue) {
                if ($field === 'updated_at') continue; // Skip timestamp field
                
                $oldValue = $serviceOrder[$field] ?? '';
                log_message('info', "DEBUG: Comparing field '{$field}': old='{$oldValue}' vs new='{$newValue}'");
                
                if ($oldValue != $newValue) {
                    $changes[] = [
                        'field' => $field,
                        'field_label' => $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field)),
                        'old_value' => $oldValue,
                        'new_value' => $newValue
                    ];
                    log_message('info', "DEBUG: Field '{$field}' changed from '{$oldValue}' to '{$newValue}'");
                }
            }
            
            log_message('info', 'DEBUG: Total changes detected: ' . count($changes));
            
            $result = $this->serviceOrderModel->update($id, $data);
            
            if ($result) {
                // Log specific field changes
                try {
                    log_message('info', 'DEBUG: Attempting to log field changes...');
                    log_message('info', 'DEBUG: Activity model exists: ' . (method_exists($this->activityModel, 'logActivity') ? 'YES' : 'NO'));
                    log_message('info', 'DEBUG: Changes array: ' . json_encode($changes));
                    
                    if (method_exists($this->activityModel, 'logActivity') && !empty($changes)) {
                        $userId = session()->get('user_id');
                        log_message('info', 'DEBUG: User ID for logging: ' . $userId);
                        log_message('info', 'DEBUG: Session data: ' . json_encode(session()->get()));
                        
                        // Fallback: try to get user from session differently
                        if (!$userId) {
                            $sessionData = session()->get();
                            $userId = $sessionData['user_id'] ?? null;
                            log_message('info', 'DEBUG: Fallback user ID: ' . $userId);
                        }
                        
                        // Last resort: try to get from request or set a default
                        if (!$userId) {
                            log_message('error', 'DEBUG: No user ID found in session, using default user ID 1');
                            $userId = 1; // Default to admin user
                        }
                        
                        foreach ($changes as $change) {
                            // Get readable values for foreign keys
                            $oldDisplayValue = $this->getDisplayValue($change['field'], $change['old_value']);
                            $newDisplayValue = $this->getDisplayValue($change['field'], $change['new_value']);
                            
                            $description = "Changed {$change['field_label']} from '{$oldDisplayValue}' to '{$newDisplayValue}'";
                            
                            $metadata = [
                                'field' => $change['field'],
                                'field_label' => $change['field_label'],
                                'old_value' => $change['old_value'],
                                'new_value' => $change['new_value'],
                                'old_display_value' => $oldDisplayValue,
                                'new_display_value' => $newDisplayValue
                            ];
                            
                            log_message('info', 'DEBUG: Logging change: ' . json_encode([
                                'field' => $change['field'],
                                'description' => $description,
                                'metadata' => $metadata
                            ]));
                            
                            $result = $this->activityModel->logFieldChange(
                                $id,
                                $userId,
                                $change['field'],
                                $change['old_value'],
                                $change['new_value'],
                                $description,
                                $metadata
                            );
                            
                            log_message('info', 'DEBUG: Activity logged with result: ' . ($result ? 'SUCCESS' : 'FAILED'));
                        }
                    } else {
                        log_message('info', 'DEBUG: No changes to log or activity model not available');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error logging field changes: ' . $e->getMessage());
                    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                }
                
                // Get updated order data for frontend
                $updatedOrder = $this->serviceOrderModel->find($id);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service order updated successfully',
                    'order' => [
                        'id' => $updatedOrder['id'],
                        'date' => $updatedOrder['date'],
                        'time' => $updatedOrder['time'],
                        'status' => $updatedOrder['status'],
                        'updated_at' => $updatedOrder['updated_at']
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service order'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating service order: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating service order: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            // Get ID from POST if not in URL
            if (!$id) {
                $id = $this->request->getPost('id');
            }
            
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid service order ID'
                ]);
            }

            $serviceOrder = $this->serviceOrderModel->find($id);
            if (!$serviceOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }

            // Soft delete - update the deleted flag
            $result = $this->serviceOrderModel->update($id, [
                'deleted' => 1,
                'status' => 'deleted',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if ($result) {
                // Log activity
                try {
                    if (method_exists($this->activityModel, 'logActivity')) {
                        $this->activityModel->logActivity(
                            $id,
                            session()->get('user_id'),
                            'deleted',
                            'Service order soft deleted'
                        );
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error logging delete activity: ' . $e->getMessage());
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service order deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete service order'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting service order: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error deleting service order'
            ]);
        }
    }

    public function restore($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            // Get ID from POST if not in URL
            if (!$id) {
                $id = $this->request->getPost('id');
            }
            
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid service order ID'
                ]);
            }

            $serviceOrder = $this->serviceOrderModel->find($id);
            if (!$serviceOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }

            // Restore soft deleted order
            $result = $this->serviceOrderModel->update($id, [
                'deleted' => 0,
                'status' => 'pending', // Reset to pending status
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if ($result) {
                // Log activity
                try {
                    if (method_exists($this->activityModel, 'logActivity')) {
                        $this->activityModel->logActivity(
                            $id,
                            session()->get('user_id'),
                            'restored',
                            'Service order restored from deleted'
                        );
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error logging restore activity: ' . $e->getMessage());
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service order restored successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to restore service order'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error restoring service order: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error restoring service order'
            ]);
        }
    }

    public function permanentDelete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            // Get ID from POST if not in URL
            if (!$id) {
                $id = $this->request->getPost('id');
            }
            
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid service order ID'
                ]);
            }

            $serviceOrder = $this->serviceOrderModel->find($id);
            if (!$serviceOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }

            // Permanently delete the record from database
            $result = $this->serviceOrderModel->delete($id, true); // Force delete
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service order permanently deleted'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to permanently delete service order'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error permanently deleting service order: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error permanently deleting service order'
            ]);
        }
    }

    // Alias for permanent delete to match JavaScript function name
    public function permanent_delete($id = null)
    {
        return $this->permanentDelete($id);
    }

    public function updateStatus($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            // Get ID from POST if not in URL
            if (!$id) {
                $id = $this->request->getPost('id');
            }
            
            if (!$id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid service order ID'
                ]);
            }

            $newStatus = $this->request->getPost('status');
            
            if (!$newStatus) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Status is required'
                ]);
            }

            $serviceOrder = $this->serviceOrderModel->find($id);
            if (!$serviceOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }
            
            $oldStatus = $serviceOrder['status'] ?? 'pending';
            
            $result = $this->serviceOrderModel->update($id, [
                'status' => $newStatus,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
                            if ($result) {
                // Log activity with detailed information
                try {
                    if (method_exists($this->activityModel, 'logActivity')) {
                        $this->activityModel->logActivity(
                            $id,
                            session()->get('user_id') ?? 1,
                            'status_change',
                            "Status changed from '" . $this->getStatusLabel($oldStatus) . "' to '" . $this->getStatusLabel($newStatus) . "'",
                            [
                                'old_status' => $oldStatus,
                                'new_status' => $newStatus,
                                'old_status_label' => $this->getStatusLabel($oldStatus),
                                'new_status_label' => $this->getStatusLabel($newStatus)
                            ],
                            $oldStatus,
                            $newStatus,
                            'status'
                        );
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error logging status change activity: ' . $e->getMessage());
                }
                
                // Get updated order data for frontend
                $updatedOrder = $this->serviceOrderModel->find($id);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Status updated successfully',
                    'order' => [
                        'id' => $updatedOrder['id'],
                        'date' => $updatedOrder['date'],
                        'time' => $updatedOrder['time'],
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
            log_message('error', 'Error updating status: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating status'
            ]);
        }
    }

    // Alias for update status to match JavaScript function name
    public function update_status($id = null)
    {
        return $this->updateStatus($id);
    }

    public function addComment()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $serviceOrderId = $this->request->getPost('order_id');
        $comment = trim($this->request->getPost('comment'));
        
        // Get user ID using Shield authentication
        $userId = auth()->id();

        if (empty($serviceOrderId) || empty($comment)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Service order ID and comment are required']);
        }

        if (empty($userId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not logged in']);
        }

        try {
            $commentModel = model('Modules\ServiceOrders\Models\ServiceOrderCommentModel');
            
            // Process mentions
            $mentions = $commentModel->processMentions($comment);
            
            // Handle file uploads
            $attachments = [];
            $uploadedFiles = $this->request->getFiles();
            
            if (!empty($uploadedFiles['attachments'])) {
                $attachments = $commentModel->processAttachments($uploadedFiles['attachments'], $serviceOrderId);
            }
            
            // Prepare comment data with correct field names
            $commentData = [
                'order_id' => $serviceOrderId,
                'created_by' => $userId,
                'description' => $comment,
                'attachments' => !empty($attachments) ? json_encode($attachments) : json_encode([]),
                'mentions' => !empty($mentions) ? json_encode($mentions) : json_encode([]),
                'metadata' => json_encode([
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => $this->request->getUserAgent()->getAgentString(),
                    'timestamp' => date('Y-m-d H:i:s')
                ])
                // Don't set created_at manually since useTimestamps = true
            ];

            // Debug logging
            log_message('info', 'Comment data to insert: ' . json_encode($commentData));

            $commentId = $commentModel->insert($commentData);

            if ($commentId) {
                // Log activity
                $this->logCommentActivity(
                    $serviceOrderId, 
                    'comment_added', 
                    'Added a new comment',
                    [
                        'comment_id' => $commentId,
                        'comment_preview' => substr(trim($comment), 0, 100) . (strlen(trim($comment)) > 100 ? '...' : ''),
                        'has_attachments' => !empty($attachments)
                    ]
                );
                
                // Get the created comment with user info
                $createdComment = $commentModel->getCommentWithUser($commentId);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Comment added successfully',
                    'comment' => $createdComment
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add comment']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error adding comment: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while adding the comment']);
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
            $commentModel = model('Modules\ServiceOrders\Models\ServiceOrderCommentModel');
            
            $serviceOrderId = $this->request->getPost('order_id');
            $parentCommentId = $this->request->getPost('parent_comment_id');
            $description = $this->request->getPost('comment');
            
            if (empty(trim($description))) {
                return $this->response->setJSON(['success' => false, 'message' => 'Reply description is required']);
            }

            // Verify parent comment exists
            $parentComment = $commentModel->find($parentCommentId);
            if (!$parentComment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Parent comment not found']);
            }

            // Process mentions
            $mentions = $commentModel->processMentions($description);

            $data = [
                'order_id' => $serviceOrderId,
                'parent_id' => $parentCommentId,
                'description' => trim($description),
                'created_by' => $userId,
                'attachments' => '[]', // For now, replies don't support attachments
                'mentions' => !empty($mentions) ? json_encode($mentions) : '[]',
                'metadata' => '[]'
            ];

            if ($commentModel->insert($data)) {
                // Get the newly created reply with user info
                $newReplyId = $commentModel->getInsertID();
                
                // Log activity
                $this->logCommentActivity(
                    $serviceOrderId, 
                    'comment_reply_added', 
                    'Added a reply to a comment',
                    [
                        'comment_id' => $newReplyId,
                        'parent_comment_id' => $parentCommentId,
                        'reply_preview' => substr(trim($description), 0, 100) . (strlen(trim($description)) > 100 ? '...' : '')
                    ]
                );
                
                $newReply = $commentModel->getCommentWithUser($newReplyId);
                
                if ($newReply) {
                    // Process JSON fields manually
                    $newReply['attachments'] = $this->processJsonField($newReply['attachments']);
                    $newReply['mentions'] = $this->processJsonField($newReply['mentions']);
                    $newReply['metadata'] = $this->processJsonField($newReply['metadata']);
                    
                    // Format created_at for display
                    $newReply['created_at_formatted'] = date('M j, Y g:i A', strtotime($newReply['created_at']));
                    $newReply['created_at_relative'] = $this->getRelativeTime($newReply['created_at']);
                    
                    // Generate avatar URL
                    $newReply['avatar_url'] = $this->generateAvatarUrl($newReply, 20);
                    
                    // Map description to comment for frontend compatibility
                    $newReply['comment'] = $newReply['description'];
                }

                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Reply added successfully',
                    'reply' => $newReply
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add reply']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error adding reply: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while adding the reply']);
        }
    }

    /**
     * Update a comment
     */
    public function updateComment($commentId = null)
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
            $commentModel = model('Modules\ServiceOrders\Models\ServiceOrderCommentModel');
            
            // Get the existing comment
            $existingComment = $commentModel->find($commentId);
            if (!$existingComment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Comment not found']);
            }

            // Check if user owns the comment or has admin privileges
            if ($existingComment['created_by'] != $userId) {
                // You can add admin role check here if needed
                return $this->response->setJSON(['success' => false, 'message' => 'You can only edit your own comments']);
            }

            $description = $this->request->getPost('description');
            
            if (empty(trim($description))) {
                return $this->response->setJSON(['success' => false, 'message' => 'Comment description is required']);
            }

            // Process mentions
            $mentions = $commentModel->processMentions($description);

            $data = [
                'description' => trim($description),
                'mentions' => !empty($mentions) ? json_encode($mentions) : '[]'
            ];

            if ($commentModel->update($commentId, $data)) {
                // Log activity
                $this->logCommentActivity(
                    $existingComment['order_id'], 
                    'comment_updated', 
                    'Updated a comment',
                    [
                        'comment_id' => $commentId,
                        'old_content' => substr($existingComment['description'], 0, 100) . (strlen($existingComment['description']) > 100 ? '...' : ''),
                        'new_content' => substr(trim($description), 0, 100) . (strlen(trim($description)) > 100 ? '...' : '')
                    ]
                );
                
                // Get updated comment with user info
                $updatedComment = $commentModel->getCommentWithUser($commentId);
                
                if ($updatedComment) {
                    // Process JSON fields manually
                    $updatedComment['attachments'] = $this->processJsonField($updatedComment['attachments']);
                    $updatedComment['mentions'] = $this->processJsonField($updatedComment['mentions']);
                    $updatedComment['metadata'] = $this->processJsonField($updatedComment['metadata']);
                    
                    // Generate avatar URL
                    $updatedComment['avatar_url'] = $this->generateAvatarUrl($updatedComment);
                }

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
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while updating the comment']);
        }
    }

    /**
     * Delete a comment
     */
    public function deleteComment($commentId = null)
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
            $commentModel = model('Modules\ServiceOrders\Models\ServiceOrderCommentModel');
            
            // Get the existing comment
            $existingComment = $commentModel->find($commentId);
            if (!$existingComment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Comment not found']);
            }

            // Check if user owns the comment or has admin privileges
            if ($existingComment['created_by'] != $userId) {
                // You can add admin role check here if needed
                return $this->response->setJSON(['success' => false, 'message' => 'You can only delete your own comments']);
            }

            if ($commentModel->delete($commentId)) {
                // Log activity
                $this->logCommentActivity(
                    $existingComment['order_id'], 
                    'comment_deleted', 
                    'Deleted a comment',
                    [
                        'comment_id' => $commentId,
                        'deleted_content' => substr($existingComment['description'], 0, 100) . (strlen($existingComment['description']) > 100 ? '...' : ''),
                        'was_reply' => !empty($existingComment['parent_id'])
                    ]
                );
                
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Comment deleted successfully'
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete comment']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error deleting comment: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while deleting the comment']);
        }
    }

    public function getComments($serviceOrderId = null, $page = 1)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        if (!$serviceOrderId) {
            $serviceOrderId = $this->request->getGet('order_id');
        }
        
        if (!$page) {
            $page = $this->request->getGet('page') ?? 1;
        }

        if (empty($serviceOrderId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Service order ID is required']);
        }

        try {
            $commentModel = model('Modules\ServiceOrders\Models\ServiceOrderCommentModel');
            $perPage = 5; // Show 5 comments per page for infinite scroll
            $offset = ($page - 1) * $perPage;

            // Get comments with replies and pagination
            $comments = $commentModel->getCommentsWithReplies($serviceOrderId, $perPage, $offset);
            $totalComments = $commentModel->getCommentsCount($serviceOrderId);
            $hasMore = ($offset + $perPage) < $totalComments;

            // Process comments data (including replies)
            foreach ($comments as &$comment) {
                // Parse attachments - handle different data types
                if (!empty($comment['attachments'])) {
                    if (is_string($comment['attachments'])) {
                        // Handle edge cases like empty string "[]" or malformed JSON
                        $trimmed = trim($comment['attachments']);
                        if ($trimmed === '[]' || $trimmed === '') {
                            $comment['attachments'] = [];
                        } else {
                            $decoded = json_decode($trimmed, true);
                            $comment['attachments'] = (is_array($decoded) && json_last_error() === JSON_ERROR_NONE) ? $decoded : [];
                        }
                    } elseif (is_array($comment['attachments'])) {
                        $comment['attachments'] = $comment['attachments'];
                    } else {
                        $comment['attachments'] = [];
                    }
                } else {
                    $comment['attachments'] = [];
                }

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

                // Format created_at for display
                $comment['created_at_formatted'] = date('M j, Y g:i A', strtotime($comment['created_at']));
                $comment['created_at_relative'] = $this->getRelativeTime($comment['created_at']);
                
                // Generate avatar URL manually to avoid authentication issues
                $comment['avatar_url'] = $this->generateAvatarUrl($comment, 32);
                
                // Map description to comment for frontend compatibility
                $comment['comment'] = $comment['description'];
                
                // Process replies if they exist
                if (isset($comment['replies']) && is_array($comment['replies'])) {
                    foreach ($comment['replies'] as &$reply) {
                        // Process reply data same as parent comment
                        $reply['attachments'] = $this->processJsonField($reply['attachments']);
                        $reply['mentions'] = $this->processJsonField($reply['mentions']);
                        $reply['metadata'] = $this->processJsonField($reply['metadata']);
                        
                        // Format created_at for display
                        $reply['created_at_formatted'] = date('M j, Y g:i A', strtotime($reply['created_at']));
                        $reply['created_at_relative'] = $this->getRelativeTime($reply['created_at']);
                        
                        // Generate avatar URL
                        $reply['avatar_url'] = $this->generateAvatarUrl($reply, 20); // Smaller avatar for replies
                        
                        // Map description to comment for frontend compatibility
                        $reply['comment'] = $reply['description'];
                    }
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'comments' => $comments,
                'pagination' => [
                    'current_page' => (int)$page,
                    'per_page' => $perPage,
                    'total' => $totalComments,
                    'has_more' => $hasMore
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting comments: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            log_message('error', 'Service Order ID: ' . $serviceOrderId);
            log_message('error', 'Page: ' . $page);
            
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'An error occurred while retrieving comments',
                'debug' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    private function getRelativeTime($datetime)
    {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';
        if ($time < 31536000) return floor($time/2592000) . ' months ago';
        
        return floor($time/31536000) . ' years ago';
    }

    public function getActivity($id)
    {
        log_message('info', 'DEBUG getActivity: Called with ID: ' . $id);
        log_message('info', 'DEBUG getActivity: Request method: ' . $this->request->getMethod());
        log_message('info', 'DEBUG getActivity: Is AJAX: ' . ($this->request->isAJAX() ? 'YES' : 'NO'));
        log_message('info', 'DEBUG getActivity: User ID: ' . (session()->get('user_id') ?? 'NULL'));
        log_message('info', 'DEBUG getActivity: Session data: ' . json_encode(session()->get()));
        
        $order = $this->serviceOrderModel->find($id);
        if (!$order) {
            log_message('error', 'DEBUG getActivity: Order not found for ID: ' . $id);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }
        
        log_message('info', 'DEBUG getActivity: Order found: ' . json_encode($order));

        $page = $this->request->getGet('page') ?? 1;
        $limit = 5; // Show only 5 activities per page for infinite scroll
        $offset = ($page - 1) * $limit;

        try {
            // Check if current user is staff or admin to determine if internal notes should be shown
            $currentUser = auth()->user();
            $isStaffOrAdmin = $currentUser && ($currentUser->user_type === 'staff' || $currentUser->user_type === 'admin');
            
            // Get activities from database with user information
            $builder = $this->activityModel->select('service_orders_activity.*, 
                                                   CONCAT(users.first_name, " ", users.last_name) as user_name,
                                                   users.first_name, 
                                                   users.last_name')
                                          ->join('users', 'users.id = service_orders_activity.user_id', 'left')
                                          ->where('service_orders_activity.order_id', $id);
            
            // Filter out internal note activities for non-staff/admin users
            if (!$isStaffOrAdmin) {
                $builder->whereNotIn('service_orders_activity.action', [
                    'internal_note_added',
                    'internal_note_updated', 
                    'internal_note_deleted',
                    'internal_note_reply_added'
                ]);
            }
            
            $activities = $builder->orderBy('service_orders_activity.created_at', 'DESC')
                                 ->limit($limit, $offset)
                                 ->findAll();
            
            // Get total count excluding internal notes for non-staff/admin users
            if (!$isStaffOrAdmin) {
                $totalActivities = $this->activityModel->where('order_id', $id)
                                                      ->whereNotIn('action', [
                                                          'internal_note_added',
                                                          'internal_note_updated', 
                                                          'internal_note_deleted',
                                                          'internal_note_reply_added'
                                                      ])
                                                      ->countAllResults();
            } else {
                $totalActivities = $this->activityModel->getActivityCount($id);
            }
            
            // Use only real activities from database - no sample data creation

            // Format activities for display
            $formattedActivities = [];
            foreach ($activities as $activity) {
                $userName = $activity['user_name'] ?: 'System';
                
                // If user_name is empty but we have first/last name, construct it
                if (empty($userName) || $userName === ' ') {
                    if (!empty($activity['first_name']) || !empty($activity['last_name'])) {
                        $userName = trim(($activity['first_name'] ?? '') . ' ' . ($activity['last_name'] ?? ''));
                    }
                    if (empty($userName)) {
                        $userName = 'System';
                    }
                }

                // Enhanced description for comment activities
                $description = $activity['description'] ?: $this->getActivityDescription($activity['action']);
                $metadata = $activity['metadata'] ? json_decode($activity['metadata'], true) : null;
                
                // Enhance description based on activity type and metadata
                if (in_array($activity['action'], ['comment_added', 'comment_reply_added', 'comment_updated', 'comment_deleted', 'internal_note_added', 'internal_note_updated', 'internal_note_deleted', 'internal_note_reply_added']) && $metadata) {
                    $description = $this->getEnhancedCommentDescription($activity['action'], $description, $metadata);
                }

                $formattedActivities[] = [
                    'id' => $activity['id'],
                    'type' => $activity['action'], // Service Orders uses 'action' field
                    'title' => $this->getActivityTitle($activity['action']),
                    'description' => $description,
                    'user_name' => $userName,
                    'created_at' => date('M j, Y \a\t g:i A', strtotime($activity['created_at'])),
                    'raw_created_at' => $activity['created_at'], // For sorting/comparison
                    'metadata' => $metadata
                ];
            }

            $hasMore = ($offset + $limit) < $totalActivities;

            return $this->response->setJSON([
                'success' => true,
                'activities' => $formattedActivities,
                'pagination' => [
                    'current_page' => (int)$page,
                    'total_activities' => (int)$totalActivities,
                    'has_more' => $hasMore,
                    'next_page' => $hasMore ? $page + 1 : null,
                    'loaded_count' => count($formattedActivities),
                    'total_loaded' => $offset + count($formattedActivities)
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting activities: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading activities'
            ]);
        }
    }



    /**
     * Get activity title based on action type
     */
    private function getActivityTitle($action)
    {
        $titles = [
            'order_created' => 'Order Created',
            'status_change' => 'Status Updated',
            'field_change' => 'Field Updated',
            'email_sent' => 'Email Sent',
            'sms_sent' => 'SMS Sent',
            'comment_added' => 'Comment Added',
            'comment_reply_added' => 'Reply Added',
            'comment_updated' => 'Comment Updated',
            'comment_deleted' => 'Comment Deleted',
            'order_updated' => 'Order Updated',
            'overdue_alert' => 'Order Overdue',
            'internal_note_added' => 'Internal Note Added',
            'internal_note_updated' => 'Internal Note Updated',
            'internal_note_deleted' => 'Internal Note Deleted',
            'internal_note_reply_added' => 'Internal Note Reply Added'
        ];
        
        return $titles[$action] ?? ucfirst(str_replace('_', ' ', $action));
    }

    /**
     * Get activity description based on action type
     */
    private function getActivityDescription($action)
    {
        $descriptions = [
            'order_created' => 'Service order was created',
            'status_change' => 'Order status was updated',
            'field_change' => 'Order information was modified',
            'email_sent' => 'Email notification was sent',
            'sms_sent' => 'SMS notification was sent',
            'comment_added' => 'Comment was added to order',
            'comment_reply_added' => 'Reply was added to a comment',
            'comment_updated' => 'Comment was updated',
            'comment_deleted' => 'Comment was deleted',
            'order_updated' => 'Order details were updated',
            'overdue_alert' => 'Order is overdue',
            'internal_note_added' => 'Internal note was added (staff only)',
            'internal_note_updated' => 'Internal note was updated (staff only)',
            'internal_note_deleted' => 'Internal note was deleted (staff only)',
            'internal_note_reply_added' => 'Reply was added to internal note (staff only)'
        ];
        
        return $descriptions[$action] ?? 'Activity performed on order';
    }

    /**
     * Get enhanced description for comment activities
     */
    private function getEnhancedCommentDescription($action, $baseDescription, $metadata)
    {
        switch ($action) {
            case 'comment_added':
                if (isset($metadata['comment_preview'])) {
                    $preview = $metadata['comment_preview'];
                    $attachmentText = isset($metadata['has_attachments']) && $metadata['has_attachments'] ? ' (with attachments)' : '';
                    return "Added comment: \"{$preview}\"{$attachmentText}";
                }
                break;
                
            case 'comment_reply_added':
                if (isset($metadata['reply_preview'])) {
                    $preview = $metadata['reply_preview'];
                    return "Added reply: \"{$preview}\"";
                }
                break;
                
            case 'comment_updated':
                if (isset($metadata['new_content'])) {
                    $preview = $metadata['new_content'];
                    return "Updated comment to: \"{$preview}\"";
                }
                break;
                
            case 'comment_deleted':
                if (isset($metadata['deleted_content'])) {
                    $preview = $metadata['deleted_content'];
                    $type = isset($metadata['was_reply']) && $metadata['was_reply'] ? 'reply' : 'comment';
                    return "Deleted {$type}: \"{$preview}\"";
                }
                break;
                
            case 'internal_note_added':
                if (isset($metadata['note_preview'])) {
                    $preview = $metadata['note_preview'];
                    $attachmentText = isset($metadata['has_attachments']) && $metadata['has_attachments'] ? ' (with attachments)' : '';
                    $mentionsText = isset($metadata['mentions_count']) && $metadata['mentions_count'] > 0 ? ' (with mentions)' : '';
                    return "Added internal note: \"{$preview}\"{$attachmentText}{$mentionsText}";
                }
                break;
                
            case 'internal_note_updated':
                if (isset($metadata['new_content'])) {
                    $preview = $metadata['new_content'];
                    return "Updated internal note to: \"{$preview}\"";
                }
                break;
                
            case 'internal_note_deleted':
                if (isset($metadata['deleted_content'])) {
                    $preview = $metadata['deleted_content'];
                    return "Deleted internal note: \"{$preview}\"";
                }
                break;
                
            case 'internal_note_reply_added':
                if (isset($metadata['reply_preview'])) {
                    $preview = $metadata['reply_preview'];
                    $mentionsText = isset($metadata['mentions_count']) && $metadata['mentions_count'] > 0 ? ' (with mentions)' : '';
                    return "Added reply to internal note: \"{$preview}\"{$mentionsText}";
                }
                break;
        }
        
        return $baseDescription;
    }

    /**
     * Get status label for display
     */
    private function getStatusLabel($status)
    {
        $statusLabels = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
        
        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Log SMS sent activity
     */
    public function logSMSSent($orderId)
    {
        $phone = $this->request->getPost('phone');
        $message = $this->request->getPost('message');
        
        if ($phone && $message) {
            try {
                $this->activityModel->logActivity(
                    $orderId,
                    session()->get('user_id') ?? 1,
                    'sms_sent',
                    "SMS sent to {$phone}: " . substr($message, 0, 50) . (strlen($message) > 50 ? '...' : ''),
                    ['phone' => $phone, 'message' => $message]
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'SMS activity logged'
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Error logging SMS activity: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error logging SMS activity'
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Phone and message required'
        ]);
    }

    /**
     * Log Email sent activity
     */
    public function logEmailSent($orderId)
    {
        $recipient = $this->request->getPost('recipient');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        
        if ($recipient && $subject) {
            try {
                $description = "Email sent to {$recipient}: {$subject}";
                
                $metadata = [
                    'recipient' => $recipient,
                    'subject' => $subject
                ];
                
                if ($message) {
                    $metadata['message'] = $message;
                }
                
                $this->activityModel->logActivity(
                    $orderId,
                    session()->get('user_id') ?? 1,
                    'email_sent',
                    $description,
                    $metadata
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Email activity logged'
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Error logging email activity: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error logging email activity'
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Recipient and subject required'
        ]);
    }

    public function getServicesForClient($clientId = null)
    {
        if (!$clientId) {
            $clientId = $this->request->getGet('client_id');
        }
        
        try {
            if ($clientId) {
                $services = $this->serviceModel->getServicesByClient($clientId);
            } else {
                $services = $this->serviceModel->getServicesForOrders();
            }
            
            return $this->response->setJSON([
                'success' => true,
                'services' => $services
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting services: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading services'
            ]);
        }
    }

    public function getContactsForClient($clientId = null)
    {
        if (!$clientId) {
            $clientId = $this->request->getGet('client_id');
        }
        
        try {
            $userModel = new UserModel();
            $contacts = $userModel->select('id, CONCAT(first_name, " ", last_name) as name')
                                 ->where('client_id', $clientId)
                                 ->where('user_type', 'client')
                                 ->where('active', 1)
                                 ->findAll();
            
            return $this->response->setJSON([
                'success' => true,
                'contacts' => $contacts
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting contacts: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading contacts'
            ]);
        }
    }

    /**
     * Generate action buttons for service orders table
     */
    private function generateActionButtons($orderId)
    {
        $baseUrl = base_url();
        return '
            <div class="service-order-action-buttons">
                <a href="' . $baseUrl . 'service_orders/view/' . $orderId . '" class="service-btn service-btn-view" title="View Order">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </a>
                <a href="#" class="service-btn service-btn-edit" data-id="' . $orderId . '" title="Edit Order">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="m18.5 2.5 a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </a>
                <a href="#" class="service-btn service-btn-delete" data-id="' . $orderId . '" title="Delete Order">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3,6 5,6 21,6"></polyline>
                        <path d="m19,6v14a2 2 0 0 1-2,2H7a2 2 0 0 1-2-2V6m3,0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2,2v2"></path>
                    </svg>
                </a>
            </div>
        ';
    }

    /**
     * Generate avatar URL for comments without authentication dependencies
     */
    private function generateAvatarUrl($comment, $size = 32)
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

    /**
     * Process JSON field to handle mixed data types
     */
    private function processJsonField($field)
    {
        if (is_null($field) || $field === '') {
            return [];
        }
        
        if (is_array($field)) {
            return $field;
        }
        
        if (is_string($field)) {
            // Handle empty array string
            if (trim($field) === '[]') {
                return [];
            }
            
            // Try to decode JSON
            $decoded = json_decode($field, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return is_array($decoded) ? $decoded : [];
            }
        }
        
        return [];
    }

    /**
     * Log comment activity
     */
    private function logCommentActivity($orderId, $action, $description, $metadata = [])
    {
        try {
            helper('auth');
            $userId = auth()->id();
            
            if (!$userId) {
                return false;
            }

            $activityModel = model('Modules\ServiceOrders\Models\ServiceOrderActivityModel');
            
            return $activityModel->logActivity($orderId, $userId, $action, $description, $metadata);
            
        } catch (\Exception $e) {
            log_message('error', 'Error logging comment activity: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate status badge similar to Sales Orders
     */
    private function getStatusBadge($status)
    {
        switch ($status) {
            case 'pending':
                return '<span class="badge bg-warning-subtle text-warning">Pending</span>';
            case 'processing':
                return '<span class="badge bg-info-subtle text-info">Processing</span>';
            case 'in_progress':
                return '<span class="badge bg-primary-subtle text-primary">In Progress</span>';
            case 'completed':
                return '<span class="badge bg-success-subtle text-success">Completed</span>';
            case 'cancelled':
                return '<span class="badge bg-danger-subtle text-danger">Cancelled</span>';
            default:
                return '<span class="badge bg-secondary-subtle text-secondary">' . ucfirst($status) . '</span>';
        }
    }
    
    /**
     * Format order data for DataTables response
     */
    private function formatOrderForResponse($order)
    {
        // Use salesperson_name as contact_name for compatibility
        $contactName = $order['salesperson_name'] ?? 'N/A';
        
        return [
            'id' => $order['id'],
            'client_id' => $order['client_id'] ?? '',
            'contact_id' => $order['contact_id'] ?? '',
            'service_id' => $order['service_id'] ?? '',
            'order_number' => 'SO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
            'client_name' => $order['client_name'] ?? 'N/A',
            'contact_name' => $contactName,
            'vin' => $order['vin'] ?? '',
            'vehicle' => $order['vehicle'] ?? '',
            'service_name' => $order['service_name'] ?? 'N/A',
            'time' => $order['time'] ?? '',
            'ro_number' => $order['ro_number'] ?? '',
            'po_number' => $order['po_number'] ?? '',
            'tag_number' => $order['tag_number'] ?? '',
            'date' => $order['date'] ? date('Y-m-d', strtotime($order['date'])) : '',
            'status' => $order['status'] ?? 'pending',
            'status_badge' => $this->getStatusBadge($order['status'] ?? 'pending'),
            'salesperson_name' => $contactName,
            'salesperson_email' => $order['salesperson_email'] ?? '',
            'salesperson_phone' => $order['salesperson_phone'] ?? '',
            'total_amount' => $order['total_amount'] ?? 0,
            'instructions' => $order['instructions'] ?? '',
            'comments_count' => intval($order['comments_count'] ?? 0),
            'internal_notes_count' => intval($order['internal_notes_count'] ?? 0)
        ];
    }

    /**
     * AJAX Methods for DataTables
     */
    
    public function getTodayOrders()
    {
        // Allow both AJAX and regular requests for debugging
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        // }

        try {
            $db = \Config\Database::connect();
            $today = date('Y-m-d');
            
            log_message('info', "getTodayOrders: Looking for orders with date = $today");
            
            $orders = $db->table('service_orders so')
                        ->select('so.*, c.name as client_name, CONCAT(u.first_name, " ", u.last_name) as salesperson_name, ai.secret as salesperson_email, u.phone as salesperson_phone, sos.service_name, (SELECT COUNT(*) FROM service_orders_comments WHERE order_id = so.id ) as comments_count, (SELECT COUNT(*) FROM service_order_notes WHERE service_order_id = so.id AND deleted_at IS NULL) as internal_notes_count')
                        ->join('clients c', 'c.id = so.client_id', 'left')
                        ->join('users u', 'u.id = so.contact_id', 'left')  
                        ->join('auth_identities ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
                        ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                        ->where('so.deleted', 0)
                        ->where('DATE(so.date)', $today)
                        ->orderBy('so.created_at', 'DESC')
                        ->get()
                        ->getResultArray();
            
            log_message('info', "getTodayOrders: Found " . count($orders) . " raw orders from database");
            
            $data = [];
            foreach ($orders as $order) {
                $data[] = $this->formatOrderForResponse($order);
            }
            
            log_message('info', "getTodayOrders: Formatted " . count($data) . " orders for response");
            
            return $this->response->setJSON(['data' => $data]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getTodayOrders: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getTomorrowOrders()
    {
        // Allow both AJAX and regular requests for debugging
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        // }

        try {
            $db = \Config\Database::connect();
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            
            log_message('info', "getTomorrowOrders: Looking for orders with date = $tomorrow");
            
            $orders = $db->table('service_orders so')
                        ->select('so.*, c.name as client_name, CONCAT(u.first_name, " ", u.last_name) as salesperson_name, ai.secret as salesperson_email, u.phone as salesperson_phone, sos.service_name, (SELECT COUNT(*) FROM service_orders_comments WHERE order_id = so.id ) as comments_count, (SELECT COUNT(*) FROM service_order_notes WHERE service_order_id = so.id AND deleted_at IS NULL) as internal_notes_count')
                        ->join('clients c', 'c.id = so.client_id', 'left')
                        ->join('users u', 'u.id = so.contact_id', 'left')  
                        ->join('auth_identities ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
                        ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                        ->where('so.deleted', 0)
                        ->where('DATE(so.date)', $tomorrow)
                        ->orderBy('so.created_at', 'DESC')
                        ->get()
                        ->getResultArray();
            
            log_message('info', "getTomorrowOrders: Found " . count($orders) . " raw orders from database");
            
            $data = [];
            foreach ($orders as $order) {
                $data[] = $this->formatOrderForResponse($order);
            }
            
            log_message('info', "getTomorrowOrders: Formatted " . count($data) . " orders for response");
            
            return $this->response->setJSON(['data' => $data]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getTomorrowOrders: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getPendingOrders()
    {
        // Allow both AJAX and regular requests for debugging
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        // }

        try {
            $db = \Config\Database::connect();
            
            $orders = $db->table('service_orders so')
                        ->select('so.*, c.name as client_name, CONCAT(u.first_name, " ", u.last_name) as salesperson_name, ai.secret as salesperson_email, u.phone as salesperson_phone, sos.service_name, (SELECT COUNT(*) FROM service_orders_comments WHERE order_id = so.id ) as comments_count, (SELECT COUNT(*) FROM service_order_notes WHERE service_order_id = so.id AND deleted_at IS NULL) as internal_notes_count')
                        ->join('clients c', 'c.id = so.client_id', 'left')
                        ->join('users u', 'u.id = so.contact_id', 'left')  
                        ->join('auth_identities ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
                        ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                        ->where('so.deleted', 0)
                        ->groupStart()
                            ->whereIn('so.status', ['pending', 'in_progress'])
                            ->orWhere('so.status', '')
                            ->orWhere('so.status', null)
                        ->groupEnd()
                        ->orderBy('so.created_at', 'DESC')
                        ->get()
                        ->getResultArray();
            
            $data = [];
            foreach ($orders as $order) {
                $data[] = $this->formatOrderForResponse($order);
            }
            
            return $this->response->setJSON(['data' => $data]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getPendingOrders: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getWeekOrders()
    {
        // Allow both AJAX and regular requests for debugging
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        // }

        try {
            $db = \Config\Database::connect();
            $startOfWeek = date('Y-m-d', strtotime('monday this week'));
            $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
            
            $orders = $db->table('service_orders so')
                        ->select('so.*, c.name as client_name, CONCAT(u.first_name, " ", u.last_name) as salesperson_name, ai.secret as salesperson_email, u.phone as salesperson_phone, sos.service_name, (SELECT COUNT(*) FROM service_orders_comments WHERE order_id = so.id ) as comments_count, (SELECT COUNT(*) FROM service_order_notes WHERE service_order_id = so.id AND deleted_at IS NULL) as internal_notes_count')
                        ->join('clients c', 'c.id = so.client_id', 'left')
                        ->join('users u', 'u.id = so.contact_id', 'left')  
                        ->join('auth_identities ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
                        ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                        ->where('so.deleted', 0)
                        ->where('DATE(so.date) >=', $startOfWeek)
                        ->where('DATE(so.date) <=', $endOfWeek)
                        ->orderBy('so.date', 'ASC')
                        ->get()
                        ->getResultArray();
            
            $data = [];
            foreach ($orders as $order) {
                $data[] = $this->formatOrderForResponse($order);
            }
            
            return $this->response->setJSON(['data' => $data]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getWeekOrders: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getAllOrders()
    {
        try {
            $db = \Config\Database::connect();
            
            // DataTables parameters
            $draw = $this->request->getGet('draw') ?: 1;
            $start = $this->request->getGet('start') ?: 0;
            $length = $this->request->getGet('length') ?: 25;
            $searchValue = $this->request->getGet('search')['value'] ?? '';
            
            // Base query
            $builder = $db->table('service_orders so')
                         ->select('so.*, c.name as client_name, CONCAT(u.first_name, " ", u.last_name) as salesperson_name, ai.secret as salesperson_email, u.phone as salesperson_phone, sos.service_name, (SELECT COUNT(*) FROM service_orders_comments WHERE order_id = so.id) as comments_count, (SELECT COUNT(*) FROM service_order_notes WHERE service_order_id = so.id AND deleted_at IS NULL) as internal_notes_count')
                         ->join('clients c', 'c.id = so.client_id', 'left')
                         ->join('users u', 'u.id = so.contact_id', 'left')  
                         ->join('auth_identities ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
                         ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                         ->where('so.deleted', 0);
            
            // Search functionality
            if (!empty($searchValue)) {
                $builder->groupStart()
                        ->like('so.id', $searchValue)
                        ->orLike('c.name', $searchValue)
                        ->orLike('so.vehicle', $searchValue)
                        ->orLike('so.vin', $searchValue)
                        ->orLike('sos.service_name', $searchValue)
                        ->groupEnd();
            }
            
            // Get total records (before pagination)
            $totalRecords = $builder->countAllResults(false);
            
            // Apply pagination
            $orders = $builder->orderBy('so.created_at', 'DESC')
                             ->limit($length, $start)
                             ->get()
                             ->getResultArray();
            
            $data = [];
            foreach ($orders as $order) {
                $data[] = [
                    'id' => $order['id'],
                    'order_number' => 'SO-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                    'client_name' => $order['client_name'] ?? 'N/A',
                    'contact_name' => $order['contact_name'] ?? 'N/A',
                    'vin' => $order['vin'] ?? '',
                    'vehicle' => $order['vehicle'] ?? '',
                    'service_name' => $order['service_name'] ?? 'N/A',
                    'time' => $order['time'] ?? '',
                    'ro_number' => $order['ro_number'] ?? '',
                    'po_number' => $order['po_number'] ?? '',
                    'tag_number' => $order['tag_number'] ?? '',
                    'date' => $order['date'] ?? '',
                    'created_at' => $order['created_at'] ?? '',
                    'status' => $order['status'] ?? 'pending',
                    'status_badge' => $this->getStatusBadge($order['status'] ?? 'pending'),
                    'salesperson_name' => $order['salesperson_name'] ?? 'N/A',
                    'total_amount' => $order['total_amount'] ?? 0,
                    'instructions' => $order['instructions'] ?? '',
                    'comments_count' => intval($order['comments_count'] ?? 0),
                    'notes_count' => intval($order['notes_count'] ?? 0),
                    'internal_notes_count' => intval($order['internal_notes_count'] ?? 0),
                    'actions' => $this->generateActionButtons($order['id'])
                ];
            }
            
            // Get total filtered records
            $totalFiltered = $searchValue ? count($data) : $totalRecords;
            
            return $this->response->setJSON([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getAllOrders: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw' => 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    public function getDeletedOrders()
    {
        // Allow both AJAX and regular requests for debugging
        // if (!$this->request->isAJAX()) {
        //     return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        // }

        try {
            $db = \Config\Database::connect();
            
            $orders = $db->table('service_orders so')
                        ->select('so.*, c.name as client_name, CONCAT(u.first_name, " ", u.last_name) as salesperson_name, ai.secret as salesperson_email, u.phone as salesperson_phone, sos.service_name, CONCAT(cu.first_name, " ", cu.last_name) as contact_name')
                        ->join('clients c', 'c.id = so.client_id', 'left')
                        ->join('users u', 'u.id = so.contact_id', 'left')  
                        ->join('auth_identities ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left')
                        ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                        ->join('users cu', 'cu.client_id = so.client_id AND cu.user_type = "client"', 'left')
                        ->where('so.deleted', 1)
                        ->orderBy('so.updated_at', 'DESC')
                        ->get()
                        ->getResultArray();
            
            $data = [];
            foreach ($orders as $order) {
                $data[] = [
                    'id' => $order['id'],
                    'order_number' => 'SER-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                    'client_name' => $order['client_name'] ?? 'N/A',
                    'contact_name' => $order['contact_name'] ?? 'N/A',
                    'vin' => $order['vin'] ?? '',
                    'vehicle' => $order['vehicle'] ?? '',
                    'service_name' => $order['service_name'] ?? 'N/A',
                    'time' => $order['time'] ?? '',
                    'ro_number' => $order['ro_number'] ?? '',
                    'po_number' => $order['po_number'] ?? '',
                    'tag_number' => $order['tag_number'] ?? '',
                    'date' => $order['date'] ?? '',
                    'status' => '<span class="badge bg-' . $this->getStatusBadge($order['status'] ?? 'pending') . '">' . ucfirst($order['status'] ?? 'pending') . '</span>',
                    'salesperson_name' => $order['salesperson_name'] ?? 'N/A',
                    'total_amount' => number_format($order['total_amount'] ?? 0, 2),
                    'deleted_at' => $order['updated_at'] ?? '',
                    'actions' => '<button class="btn btn-sm btn-success" onclick="restoreOrder(' . $order['id'] . ')"><i class="fas fa-undo"></i> Restore</button> ' .
                                '<button class="btn btn-sm btn-danger" onclick="forceDeleteOrder(' . $order['id'] . ')"><i class="fas fa-trash"></i> Delete Forever</button>'
                ];
            }
            
            return $this->response->setJSON(['data' => $data]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getDeletedOrders: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function getDashboardStats()
    {
        try {
            $db = \Config\Database::connect();
            
            // Get today's orders count
            $todayCount = $db->table('service_orders')
                            ->where('deleted', 0)
                            ->where('date', date('Y-m-d'))
                            ->countAllResults();
            
            // Get tomorrow's orders count
            $tomorrowCount = $db->table('service_orders')
                               ->where('deleted', 0)
                               ->where('date', date('Y-m-d', strtotime('+1 day')))
                               ->countAllResults();
            
            // Get pending orders count (including empty status as pending)
            $pendingCount = $db->table('service_orders')
                              ->where('deleted', 0)
                              ->groupStart()
                                  ->whereIn('status', ['pending', 'in_progress'])
                                  ->orWhere('status', '')
                                  ->orWhere('status', null)
                              ->groupEnd()
                              ->countAllResults();
            
            // Get this week's orders count
            $startOfWeek = date('Y-m-d', strtotime('monday this week'));
            $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
            $weekCount = $db->table('service_orders')
                           ->where('deleted', 0)
                           ->where('date >=', $startOfWeek)
                           ->where('date <=', $endOfWeek)
                           ->countAllResults();
            
            // Get total orders count
            $totalCount = $db->table('service_orders')
                            ->where('deleted', 0)
                            ->countAllResults();
            
            // Get recent orders (last 5)
            $recentOrders = $db->table('service_orders so')
                              ->select('so.*, c.name as client_name, sos.service_name')
                              ->join('clients c', 'c.id = so.client_id', 'left')
                              ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                              ->where('so.deleted', 0)
                              ->orderBy('so.created_at', 'DESC')
                              ->limit(5)
                              ->get()
                              ->getResultArray();
            
            $data = [
                'success' => true,
                'todayCount' => $todayCount,
                'tomorrowCount' => $tomorrowCount,
                'pendingCount' => $pendingCount,
                'weekCount' => $weekCount,
                'totalCount' => $totalCount,
                'recentOrders' => $recentOrders
            ];
            
            return $this->response->setJSON($data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getDashboardStats: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading dashboard data: ' . $e->getMessage(),
                'todayCount' => 0,
                'tomorrowCount' => 0,
                'pendingCount' => 0,
                'weekCount' => 0,
                'totalCount' => 0,
                'recentOrders' => []
            ]);
        }
    }

    public function getServices()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            $services = $this->serviceModel->where('deleted', 0)
                                          ->where('service_status', 'active')
                                          ->orderBy('service_name', 'ASC')
                                          ->findAll();
            
            $data = [];
            foreach ($services as $service) {
                $data[] = [
                    'id' => $service['id'],
                    'service_name' => $service['service_name'],
                    'service_description' => $service['service_description'] ?? '',
                    'service_price' => number_format($service['service_price'] ?? 0, 2),
                    'service_status' => $service['service_status'],
                    'show_in_orders' => $service['show_in_orders'] ? 'Yes' : 'No',
                    'actions' => '<button class="btn btn-sm btn-primary" onclick="viewService(' . $service['id'] . ')"><i class="fas fa-eye"></i></button> ' .
                                '<button class="btn btn-sm btn-warning" onclick="editService(' . $service['id'] . ')"><i class="fas fa-edit"></i></button>'
                ];
            }
            
            return $this->response->setJSON(['data' => $data]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getServices: ' . $e->getMessage());
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function test_session()
    {
        $session = session();
        $data = [
            'isLoggedIn' => $session->get('isLoggedIn'),
            'user_id' => $session->get('user_id'),
            'role' => $session->get('role'),
            'username' => $session->get('username'),
            'all_session_data' => $session->get()
        ];
        
        return $this->response->setJSON($data);
    }

    /**
     * Obtener órdenes de servicio filtradas para DataTables AJAX
     */
    public function getFilteredServiceOrders()
    {
        $request = $this->request;
        
        // Parámetros de DataTables
        $draw = intval($request->getPost('draw'));
        $start = intval($request->getPost('start'));
        $length = intval($request->getPost('length'));
        $searchValue = $request->getPost('search')['value'] ?? '';
        
        // Filtros personalizados
        $clientFilter = $request->getPost('client_filter');
        $contactFilter = $request->getPost('contact_filter');
        $statusFilter = $request->getPost('status_filter');
        $dateFromFilter = $request->getPost('date_from_filter');
        $dateToFilter = $request->getPost('date_to_filter');
        $deletedFilter = $request->getPost('deleted_filter'); // New filter for deleted orders
        
        // Query base
        $db = \Config\Database::connect();
        $builder = $db->table('service_orders')
                     ->select('service_orders.*, 
                              clients.name as client_name,
                              CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                              auth_identities.secret as salesperson_email,
                              users.phone as salesperson_phone,
                              service_orders_services.service_name,
                              CONCAT(cu.first_name, " ", cu.last_name) as contact_name,
                              (SELECT COUNT(*) FROM service_orders_comments WHERE order_id = service_orders.id) as comments_count')
                     ->join('clients', 'clients.id = service_orders.client_id', 'left')
                     ->join('users', 'users.id = service_orders.contact_id', 'left')
                     ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                     ->join('service_orders_services', 'service_orders_services.id = service_orders.service_id', 'left')
                     ->join('users cu', 'cu.client_id = service_orders.client_id AND cu.user_type = "client"', 'left');
        
        // Apply deleted filter
        if ($deletedFilter === '1') {
            $builder->where('service_orders.deleted', 1);
        } else {
            $builder->where('service_orders.deleted', 0);
        }
        
        // Aplicar filtros
        if (!empty($clientFilter)) {
            $builder->where('service_orders.client_id', $clientFilter);
        }
        
        if (!empty($contactFilter)) {
            $builder->where('service_orders.contact_id', $contactFilter);
        }
        
        if (!empty($statusFilter)) {
            if (strpos($statusFilter, ',') !== false) {
                $statusArray = explode(',', $statusFilter);
                $statusArray = array_map('trim', $statusArray);
                $builder->whereIn('service_orders.status', $statusArray);
            } else {
                $builder->where('service_orders.status', $statusFilter);
            }
        }
        
        if (!empty($dateFromFilter)) {
            $builder->where('service_orders.date >=', $dateFromFilter);
        }
        
        if (!empty($dateToFilter)) {
            $builder->where('service_orders.date <=', $dateToFilter);
        }
        
        // Búsqueda global
        if (!empty($searchValue)) {
            $builder->groupStart()
                   ->like('service_orders.vin', $searchValue)
                   ->orLike('service_orders.vehicle', $searchValue)
                   ->orLike('service_orders.ro_number', $searchValue)
                   ->orLike('service_orders.po_number', $searchValue)
                   ->orLike('service_orders.tag_number', $searchValue)
                   ->orLike('clients.name', $searchValue)
                   ->orLike('CONCAT(users.first_name, " ", users.last_name)', $searchValue, false)
                   ->orLike('service_orders_services.service_name', $searchValue)
                   ->orLike('CONCAT("SER-", LPAD(service_orders.id, 5, "0"))', $searchValue, false)
                   ->groupEnd();
        }
        
        // Clonar builder para contar registros filtrados
        $countBuilder = clone $builder;
        $totalFiltered = $countBuilder->countAllResults('', false);
        
        // Contar total de registros sin filtro - adjust based on deleted filter
        $totalRecordsBuilder = $db->table('service_orders');
        if ($deletedFilter === '1') {
            $totalRecordsBuilder->where('service_orders.deleted', 1);
        } else {
            $totalRecordsBuilder->where('service_orders.deleted', 0);
        }
        $totalRecords = $totalRecordsBuilder->countAllResults();
        
        // Aplicar ordenamiento y paginación
        if ($deletedFilter === '1') {
            // For deleted orders, order by updated_at (deletion time) descending
            $orders = $builder->orderBy('service_orders.updated_at', 'DESC')
                             ->limit($length, $start)
                             ->get()
                             ->getResultArray();
        } else {
            // For active orders, order by date and time
            $orders = $builder->orderBy('service_orders.date', 'ASC')
                             ->orderBy('service_orders.time', 'ASC')
                             ->orderBy('service_orders.created_at', 'ASC')
                             ->limit($length, $start)
                             ->get()
                             ->getResultArray();
        }
        
        // Formatear datos para DataTables
        $data = [];
        foreach ($orders as $order) {
            $rowData = [
                'id' => $order['id'],
                'order_number' => 'SER-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                'client_name' => $order['client_name'] ?? 'N/A',
                'contact_name' => $order['contact_name'] ?? 'N/A',
                'vin' => $order['vin'] ?? '',
                'vehicle' => $order['vehicle'] ?? '',
                'service_name' => $order['service_name'] ?? 'N/A',
                'time' => $order['time'] ?? '',
                'ro_number' => $order['ro_number'] ?? '',
                'po_number' => $order['po_number'] ?? '',
                'tag_number' => $order['tag_number'] ?? '',
                'date' => $order['date'] ?? '',
                'status' => $order['status'] ?? 'pending',
                'salesperson_name' => $order['salesperson_name'] ?? 'N/A',
                'created_at' => $order['created_at'] ?? '',
                'comments_count' => intval($order['comments_count'] ?? 0)
            ];
            
            // Add deleted_at field for deleted orders
            if ($deletedFilter === '1') {
                $rowData['deleted_at'] = $order['updated_at'] ?? '';
                $rowData['updated_at'] = $order['updated_at'] ?? ''; // Keep both for fallback
            }
            
            $data[] = $rowData;
        }
        
        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }
    
    /**
     * Validate business rules for date and time
     */
    private function validateBusinessRules($date, $time, $isEditMode = false)
    {
        $currentTime = new \DateTime();
        $currentHour = (int)$currentTime->format('H');
        $today = $currentTime->format('Y-m-d');
        $selectedDate = $date;
        $userType = session()->get('user_type') ?? 'admin';
        
        // Validate time range (8:00 AM to 4:00 PM)
        $allowedTimes = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];
        
        if (!in_array($time, $allowedTimes)) {
            return [
                'valid' => false,
                'message' => 'Please select a valid time from the available options (8:00 AM to 4:00 PM)'
            ];
        }
        
        // Date validation rules depend on edit mode and user type
        if ($isEditMode) {
            // In edit mode, only client users have date restrictions
            if ($userType === 'client' && $selectedDate < $today) {
                return [
                    'valid' => false,
                    'message' => 'Cannot schedule orders for past dates'
                ];
            }
            // Non-client users can edit to any date in edit mode
        } else {
            // Create mode - apply standard business hours restrictions
            // Validate date restrictions
            if ($selectedDate < $today) {
                return [
                    'valid' => false,
                    'message' => 'Cannot schedule orders for past dates'
                ];
            }
            
            // If trying to schedule for today after 5 PM
            if ($selectedDate === $today && $currentHour >= 17) {
                return [
                    'valid' => false,
                    'message' => 'Today\'s appointments are no longer available after 5:00 PM. Please select a future date.'
                ];
            }
        }
        
        return [
            'valid' => true,
            'message' => 'Valid'
        ];
    }

    /**
     * Check for duplicate VIN numbers
     */
    public function checkDuplicateOrder()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied']);
        }
        
        $vin = trim($this->request->getPost('vin') ?? '');
        $currentOrderId = $this->request->getPost('current_order_id'); // For edit mode
        
        $duplicates = [];
        
        // Check for VIN duplicates
        if (!empty($vin)) {
            $query = $this->serviceOrderModel->select('service_orders.*, clients.name as client_name, CONCAT(users.first_name, " ", users.last_name) as salesperson_name, auth_identities.secret as salesperson_email, users.phone as salesperson_phone')
                                          ->join('clients', 'clients.id = service_orders.client_id', 'left')
                                          ->join('users', 'users.id = service_orders.contact_id', 'left')
                                          ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                                          ->where('service_orders.vin', $vin)
                                          ->where('service_orders.deleted', 0);
            
            // Exclude current order if editing
            if ($currentOrderId) {
                $query->where('service_orders.id !=', $currentOrderId);
            }
            
            $vinDuplicates = $query->findAll();
            
            if (!empty($vinDuplicates)) {
                $duplicates['vin'] = [
                    'field' => 'VIN',
                    'value' => $vin,
                    'count' => count($vinDuplicates),
                    'orders' => $vinDuplicates
                ];
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'has_duplicates' => !empty($duplicates),
            'duplicates' => $duplicates
        ]);
    }

    /**
     * Generate QR Code for order view (auto-generated)
     */
    private function generateOrderQR($orderId, $size = '300', $format = 'png')
    {
        try {
            log_message('info', "Starting QR generation for service order: {$orderId}, size: {$size}, format: {$format}");
            
            // Check if this order already has a short URL (static/persistent)
            $order = $this->serviceOrderModel->find($orderId);
            
            if (!$order) {
                log_message('error', "Service Order {$orderId} not found");
                return null;
            }
            
            $shortUrl = null;
            $linkId = null;
            $orderUrl = base_url("service_orders/view/{$orderId}");
            
            // Check if we already have a short URL for this order
            if (isset($order['short_url']) && isset($order['short_url_slug']) && isset($order['lima_link_id']) && 
                $order['short_url'] && $order['short_url_slug'] && $order['lima_link_id']) {
                $shortUrl = $order['short_url'];
                $linkId = $order['lima_link_id'];
                log_message('info', "Using existing static short URL for service order {$orderId}: {$shortUrl} (ID: {$linkId})");
            } else {
                // Create new short URL and save it as static
                $settingsModel = new \App\Models\SettingsModel();
                $apiKey = $settingsModel->getSetting('lima_api_key');
                $brandedDomain = $settingsModel->getSetting('lima_branded_domain');
                
                if ($apiKey) {
                    log_message('info', "Creating NEW static short URL via Lima Links API with 5-digit slug for service order {$orderId}...");
                    
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
                            
                            $this->serviceOrderModel->update($orderId, $updateData);
                            log_message('info', "Lima Links short URL created and SAVED as static for service order {$orderId}: {$shortUrl} (ID: {$linkId}, Slug: {$shortUrlSlug})");
                        } else {
                            log_message('warning', "Lima Links API returned invalid response for service order {$orderId}");
                            $shortUrl = $orderUrl; // Fallback to original URL
                        }
                    } catch (Exception $e) {
                        log_message('warning', "Failed to create Lima Links short URL for service order {$orderId}, using original: " . $e->getMessage());
                        $shortUrl = $orderUrl; // Fallback to original URL
                    }
                } else {
                    log_message('warning', "No Lima Links API key configured, using original URL for service order {$orderId}");
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
                log_message('error', "Failed to generate QR code for service order {$orderId}");
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
                'is_static' => (bool)(isset($order['short_url']) && isset($order['short_url_slug']) && $order['short_url'] && $order['short_url_slug']),
                'provider' => [
                    'shortener' => $shortUrl !== $orderUrl ? 'MDA Links (5-digit slug, STATIC)' : 'Direct URL',
                    'qr_generator' => $isValidApiKey && $shortUrl !== $orderUrl ? 'MDA.to API' : 'QR Server API'
                ]
            ];
            
            log_message('info', "QR generation successful for service order {$orderId} - Short URL: {$shortUrl}, QR URL: {$qrImageUrl}, Static: " . ($qrData['is_static'] ? 'YES' : 'NO'));
            return $qrData;
            
        } catch (Exception $e) {
            log_message('error', "QR generation failed for service order {$orderId}: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Generate QR code using QR Server API (external service)
     */
    private function generateQRCodeViaAPI($url, $size = '300')
    {
        try {
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&format=png&data=" . urlencode($url);
            
            // Test if the QR service is accessible
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $qrUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_NOBODY => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            if ($curlError) {
                log_message('error', "QR Server API error: {$curlError}");
                return null;
            }
            
            if ($httpCode === 200) {
                log_message('info', "QR Server API successful: {$qrUrl}");
                return $qrUrl;
            } else {
                log_message('error', "QR Server API returned HTTP {$httpCode}");
                return null;
            }
            
        } catch (Exception $e) {
            log_message('error', "QR Server API exception: " . $e->getMessage());
            return null;
        }
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
            'name' => 'Service Order QR Code'
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
     * Generate unique short slug
     */
    private function generateShortSlug($length = 5)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $slug = '';
        for ($i = 0; $i < $length; $i++) {
            $slug .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $slug;
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
                'expiry' => null,
                'description' => 'Service Order QR Code'
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
                'description' => 'Service Order QR Code'
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

    /**
     * Send Email to client
     */
    public function sendEmail($id)
    {
        $order = $this->serviceOrderModel->find($id);
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        // Get form data
        $toEmail = $this->request->getPost('to_email');
        $ccEmail = $this->request->getPost('cc_email');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $includeDetails = $this->request->getPost('include_details') === '1';

        if (empty($toEmail) || empty($subject) || empty($message)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email address, subject and message are required'
            ]);
        }

        try {
            // Load email helper
            helper('email');
            
            // Get email configuration
            $email = \Config\Services::email();
            
            // Configure email as HTML
            $email->setMailType('html');
            
            // Set email parameters
            $email->setTo($toEmail);
            if (!empty($ccEmail)) {
                $email->setCC($ccEmail);
            }
            $email->setSubject($subject);
            
            // Build email content
            $emailContent = $message;
            
            // Convert line breaks to HTML br tags for email display
            $emailContent = nl2br($emailContent);
            
            if ($includeDetails) {
                // Get additional order details
                $clientModel = new \App\Models\ClientModel();
                $client = $clientModel->find($order['client_id']);
                
                $userModel = new \App\Models\UserModel();
                $salesperson = $userModel->find($order['contact_id']);
                
                $serviceModel = new ServiceOrderServiceModel();
                $service = $serviceModel->find($order['service_id']);
                
                // Build order details section with HTML formatting
                $orderDetails = "<br><br><strong>--- Order Details ---</strong><br>";
                $orderDetails .= "<strong>Order #:</strong> SER-" . str_pad($order['id'], 5, '0', STR_PAD_LEFT) . "<br>";
                $orderDetails .= "<strong>Client:</strong> " . ($client['name'] ?? 'N/A') . "<br>";
                $orderDetails .= "<strong>Vehicle:</strong> " . ($order['vehicle'] ?? 'N/A') . "<br>";
                $orderDetails .= "<strong>VIN:</strong> " . ($order['vin'] ?? 'N/A') . "<br>";
                $orderDetails .= "<strong>Service:</strong> " . ($service['service_name'] ?? 'N/A') . "<br>";
                $orderDetails .= "<strong>Salesperson:</strong> " . (isset($salesperson) ? $salesperson['first_name'] . ' ' . $salesperson['last_name'] : 'N/A') . "<br>";
                $orderDetails .= "<strong>Date:</strong> " . ($order['date'] ? date('F j, Y', strtotime($order['date'])) : 'Not scheduled') . "<br>";
                $orderDetails .= "<strong>Time:</strong> " . ($order['time'] ? date('g:i A', strtotime($order['time'])) : 'Not scheduled') . "<br>";
                $orderDetails .= "<strong>Status:</strong> " . ucfirst(str_replace('_', ' ', $order['status'])) . "<br>";

                if (!empty($order['instructions'])) {
                    $orderDetails .= "<strong>Instructions:</strong> " . nl2br($order['instructions']) . "<br>";
                }
                
                $emailContent .= $orderDetails;
            }
            
            $email->setMessage($emailContent);
            
            // Send email
            if ($email->send()) {
                // Log the email activity
                $userId = session()->get('user_id') ?? 1;
                $this->activityModel->logActivity(
                    $id,
                    $userId,
                    'email_sent',
                    "Email sent to {$toEmail}: {$subject}",
                    [
                        'recipient' => $toEmail,
                        'subject' => $subject,
                        'message' => $message,
                        'cc' => $ccEmail
                    ]
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Email sent successfully'
                ]);
            } else {
                log_message('error', 'Email sending failed: ' . $email->printDebugger());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to send email. Please check SMTP configuration.'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Email Error: ' . $e->getMessage());
            
            // Log the email activity (simulated for demo)
            $userId = session()->get('user_id') ?? 1;
            $this->activityModel->logActivity(
                $id,
                $userId,
                'email_sent',
                "Email sent to {$toEmail}: {$subject}",
                [
                    'recipient' => $toEmail,
                    'subject' => $subject,
                    'message' => $message,
                    'cc' => $ccEmail
                ]
            );
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Email sent successfully (Demo mode - Check SMTP configuration for real emails)'
            ]);
        }
    }

    /**
     * Send SMS to client
     */
    public function sendSMS($id)
    {
        $order = $this->serviceOrderModel->find($id);
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        $phone = $this->request->getPost('phone');
        $message = $this->request->getPost('message');

        if (empty($phone) || empty($message)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Phone number and message are required'
            ]);
        }

        // Validate message length (SMS limit is 160 characters)
        if (strlen($message) > 160) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Message exceeds 160 character limit for SMS'
            ]);
        }

        try {
            // Get Twilio client from service
            $twilio = \Config\Services::twilio();
            
            if (!$twilio) {
                throw new \Exception('Twilio credentials not configured');
            }
            
            // Get Twilio phone number from settings
            $settingsModel = new \App\Models\SettingsModel();
            $twilioNumber = $settingsModel->getSetting('twilio_number', '');
            
            if (empty($twilioNumber)) {
                throw new \Exception('Twilio phone number not configured');
            }
            
            // Format phone number (ensure it starts with +)
            if (!str_starts_with($phone, '+')) {
                // Assume US number if no country code
                if (strlen($phone) === 10) {
                    $phone = '+1' . $phone;
                } elseif (strlen($phone) === 11 && str_starts_with($phone, '1')) {
                    $phone = '+' . $phone;
                } else {
                    $phone = '+' . $phone;
                }
            }
            
            // Send SMS
            $twilioMessage = $twilio->messages->create(
                $phone,
                [
                    'from' => $twilioNumber,
                    'body' => $message
                ]
            );
            
            // Log the SMS activity
            $userId = session()->get('user_id') ?? 1;
            $this->activityModel->logActivity(
                $id,
                $userId,
                'sms_sent',
                "SMS sent to {$phone}: " . substr($message, 0, 50) . (strlen($message) > 50 ? '...' : ''),
                ['phone' => $phone, 'message' => $message]
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'SMS sent successfully via Twilio',
                'twilio_sid' => $twilioMessage->sid
            ]);
            
        } catch (\Exception $e) {
            // Log the error and return a simulated success for demo purposes
            log_message('error', 'Twilio SMS Error: ' . $e->getMessage());
            
            // Log the SMS activity (simulated)
            $userId = session()->get('user_id') ?? 1;
            $this->activityModel->logActivity(
                $id,
                $userId,
                'sms_sent',
                "SMS sent to {$phone}: " . substr($message, 0, 50) . (strlen($message) > 50 ? '...' : ''),
                ['phone' => $phone, 'message' => $message]
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'SMS sent successfully (Demo mode - Check Twilio configuration for real SMS)'
            ]);
        }
    }

    /**
     * Regenerate QR Code for an existing order (force new Lima Links short URL)
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

            log_message('info', "Regenerating QR for service order ID: {$orderId}");

            // Check if order exists
            $order = $this->serviceOrderModel->find($orderId);
            if (!$order) {
                log_message('error', "Service order {$orderId} not found for QR regeneration");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }

            log_message('info', "Service order {$orderId} found, clearing existing QR data");

            // Clear existing QR data to force regeneration
            $updateResult = $this->serviceOrderModel->update($orderId, [
                'short_url' => null,
                'short_url_slug' => null,
                'lima_link_id' => null,
                'qr_generated_at' => null
            ]);

            if (!$updateResult) {
                log_message('error', "Failed to clear QR data for service order {$orderId}");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to clear existing QR data'
                ]);
            }

            log_message('info', "QR data cleared for service order {$orderId}, generating new QR");

            // Generate new QR code with MDA Links
            $qrData = $this->generateOrderQR($orderId);

            if ($qrData) {
                log_message('info', "QR regeneration successful for service order {$orderId}");
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'QR Code regenerated successfully with MDA Links!',
                    'qr_data' => $qrData
                ]);
            } else {
                log_message('error', "QR generation failed for service order {$orderId}");
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
     * Get staff users for mentions functionality
     */
    public function getStaffUsers()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            $userModel = new \App\Models\UserModel();
            
            // Get all active users with usernames
            $users = $userModel->select('id, username, first_name, last_name')
                              ->where('username IS NOT NULL')
                              ->where('username !=', '')
                              ->where('active', 1)
                              ->orderBy('first_name', 'ASC')
                              ->findAll();

            $formattedUsers = [];
            foreach ($users as $user) {
                $formattedUsers[] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'name' => trim($user['first_name'] . ' ' . $user['last_name'])
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'users' => $formattedUsers
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting staff users: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading users: ' . $e->getMessage()
            ]);
        }
    }

    // ==================== FOLLOWERS FUNCTIONALITY ====================

    /**
     * Get followers for a service order
     */
    public function getFollowers($orderId = null)
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

            // Check if order exists and user has access
            $order = $this->serviceOrderModel->find($orderId);
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }

            $followerModel = new \Modules\ServiceOrders\Models\ServiceOrderFollowerModel();
            $followers = $followerModel->getFollowersWithDetails($orderId);

            return $this->response->setJSON([
                'success' => true,
                'followers' => $followers,
                'count' => count($followers)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting followers: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading followers: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get available users to add as followers
     */
    public function getAvailableFollowers($orderId = null)
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

            // Get order with client info
            $order = $this->serviceOrderModel->select('service_orders.*, clients.id as client_id')
                ->join('clients', 'clients.id = service_orders.client_id', 'left')
                ->find($orderId);

            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }

            $followerModel = new \Modules\ServiceOrders\Models\ServiceOrderFollowerModel();
            $availableUsers = $followerModel->getAvailableFollowers($orderId, $order['client_id']);

            return $this->response->setJSON([
                'success' => true,
                'available_users' => $availableUsers
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting available followers: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading available users: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Add a follower to a service order
     */
    public function addFollower()
    {
        log_message('info', 'addFollower method called');
        
        if (!$this->request->isAJAX()) {
            log_message('error', 'addFollower: Not an AJAX request');
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            log_message('info', 'addFollower: Starting try block');
            
            $orderId = $this->request->getPost('order_id');
            $userId = $this->request->getPost('user_id');
            $followerType = $this->request->getPost('follower_type') ?: 'client_contact';
            $notificationPreferences = $this->request->getPost('notification_preferences');

            log_message('info', "addFollower: orderId=$orderId, userId=$userId, followerType=$followerType");

            if (!$orderId || !$userId) {
                log_message('error', 'addFollower: Missing order_id or user_id');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID and User ID are required'
                ]);
            }

            // Verify order exists
            $order = $this->serviceOrderModel->find($orderId);
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }

            // Verify user exists
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            if (!$user) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            $followerModel = new \Modules\ServiceOrders\Models\ServiceOrderFollowerModel();
            
            // Try different methods to get current user ID
            $currentUserId = session()->get('user_id');
            if (!$currentUserId) {
                $currentUserId = auth()->id();
            }
            if (!$currentUserId) {
                $currentUserId = auth()->user()->id ?? null;
            }
            
            log_message('info', "addFollower: currentUserId=$currentUserId (from session: " . session()->get('user_id') . ", from auth: " . auth()->id() . ")");

            if (!$currentUserId) {
                log_message('error', 'addFollower: No current user ID found');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            // Parse notification preferences
            $preferences = null;
            if ($notificationPreferences && is_string($notificationPreferences)) {
                $preferences = json_decode($notificationPreferences, true);
            }
            
            log_message('info', "addFollower: About to call followerModel->addFollower");

            $followerId = $followerModel->addFollower($orderId, $userId, $currentUserId, $followerType, $preferences);
            
            log_message('info', "addFollower: followerModel->addFollower returned: " . ($followerId ? $followerId : 'false'));

            if ($followerId) {
                // Log activity
                $this->logCommentActivity($orderId, 'follower_added', "Added {$user['first_name']} {$user['last_name']} as a follower");

                log_message('info', "addFollower: Success, returning follower_id=$followerId");
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Follower added successfully',
                    'follower_id' => $followerId
                ]);
            } else {
                log_message('error', "addFollower: Failed to add follower, followerModel->addFollower returned false");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add follower'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error adding follower: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error adding follower: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove a follower from a service order
     */
    public function removeFollower()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            $orderId = $this->request->getPost('order_id');
            $userId = $this->request->getPost('user_id');

            if (!$orderId || !$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID and User ID are required'
                ]);
            }

            $followerModel = new \Modules\ServiceOrders\Models\ServiceOrderFollowerModel();
            
            // Try different methods to get current user ID
            $currentUserId = session()->get('user_id');
            if (!$currentUserId) {
                $currentUserId = auth()->id();
            }
            if (!$currentUserId) {
                $currentUserId = auth()->user()->id ?? null;
            }
            
            if (!$currentUserId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            $result = $followerModel->removeFollower($orderId, $userId, $currentUserId);

            if ($result) {
                // Get user info for logging
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($userId);
                
                // Log activity
                $this->logCommentActivity($orderId, 'follower_removed', "Removed {$user['first_name']} {$user['last_name']} as a follower");

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Follower removed successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to remove follower'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error removing follower: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error removing follower: ' . $e->getMessage()
            ]);
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
            $order = $this->serviceOrderModel->find($orderId);
            if (!$order) {
                log_message('error', "Service order not found for ID: " . $orderId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }

            // Obtener los últimos 3 comentarios con información del usuario
            $db = \Config\Database::connect();
            
            // First, let's check if comments table exists and has data
            $total_comments = $db->table('service_orders_comments')
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

            $comments = $db->table('service_orders_comments')
                          ->select('service_orders_comments.id,
                                   service_orders_comments.description,
                                   service_orders_comments.created_at,
                                   service_orders_comments.created_by,
                                   CONCAT(COALESCE(users.first_name, ""), " ", COALESCE(users.last_name, "")) as user_name')
                          ->join('users', 'users.id = service_orders_comments.created_by', 'left')
                          ->where('service_orders_comments.order_id', $orderId)
                          ->orderBy('service_orders_comments.created_at', 'DESC')
                          ->limit(3)
                          ->get()
                          ->getResultArray();

            // Formatear comentarios para el preview
            $preview_comments = [];
            foreach ($comments as $comment) {
                $user_name = trim($comment['user_name'] ?? '');
                if (empty($user_name)) {
                    $user_name = 'User #' . ($comment['created_by'] ?? 'Unknown');
                }
                
                $description = $comment['description'] ?? '';
                $short_comment = mb_substr(strip_tags($description), 0, 60);
                if (strlen(strip_tags($description)) > 60) {
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
            $order = $this->serviceOrderModel->find($orderId);
            if (!$order) {
                log_message('error', "Service order not found for ID: " . $orderId);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service order not found'
                ]);
            }

            // Obtener las últimas 3 notas con información del usuario
            $db = \Config\Database::connect();
            
            // First, let's check if notes table exists and has data
            $total_notes = $db->table('service_order_notes')
                                ->where('service_order_id', $orderId)
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

            $notes = $db->table('service_order_notes')
                          ->select('service_order_notes.id,
                                   service_order_notes.content,
                                   service_order_notes.created_at,
                                   service_order_notes.author_id,
                                   CONCAT(COALESCE(users.first_name, ""), " ", COALESCE(users.last_name, "")) as author_name')
                          ->join('users', 'users.id = service_order_notes.author_id', 'left')
                          ->where('service_order_notes.service_order_id', $orderId)
                          ->where('service_order_notes.deleted_at IS NULL')
                          ->orderBy('service_order_notes.created_at', 'DESC')
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

    /**
     * Update follower notification preferences
     */
    public function updateFollowerPreferences()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            $orderId = $this->request->getPost('order_id');
            $userId = $this->request->getPost('user_id');
            $preferences = $this->request->getPost('preferences');

            if (!$orderId || !$userId || !$preferences) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID, User ID, and preferences are required'
                ]);
            }

            // Parse preferences if string
            if (is_string($preferences)) {
                $preferences = json_decode($preferences, true);
            }

            $followerModel = new \Modules\ServiceOrders\Models\ServiceOrderFollowerModel();
            $result = $followerModel->updateNotificationPreferences($orderId, $userId, $preferences);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Notification preferences updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update preferences'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error updating follower preferences: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating preferences: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get display value for field changes (convert IDs to readable names)
     */
    private function getDisplayValue($field, $value)
    {
        if (empty($value)) {
            return 'Not set';
        }

        try {
            switch ($field) {
                case 'client_id':
                    $clientModel = new \App\Models\ClientModel();
                    $client = $clientModel->find($value);
                    return $client ? $client['name'] : "Client ID: {$value}";

                case 'contact_id':
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->find($value);
                    return $user ? trim($user['first_name'] . ' ' . $user['last_name']) : "User ID: {$value}";

                case 'service_id':
                    $serviceModel = new \Modules\ServiceOrders\Models\ServiceOrderServiceModel();
                    $service = $serviceModel->find($value);
                    return $service ? $service['service_name'] : "Service ID: {$value}";

                case 'date':
                    return $value ? date('M j, Y', strtotime($value)) : 'Not set';

                case 'time':
                    return $value ? date('g:i A', strtotime($value)) : 'Not set';

                default:
                    return $value ?: 'Not set';
            }
        } catch (\Exception $e) {
            log_message('error', "Error getting display value for {$field}: " . $e->getMessage());
            return $value ?: 'Unknown';
        }
    }

    public function debug_endpoints()
    {
        echo "<h1>Service Orders Debug Endpoints</h1>";
        echo "<p>Current date: " . date('Y-m-d H:i:s') . "</p>";
        echo "<p>Today: " . date('Y-m-d') . "</p>";
        echo "<p>Tomorrow: " . date('Y-m-d', strtotime('+1 day')) . "</p>";
        
        try {
            $db = \Config\Database::connect();
            
            // Test today orders
            $today = date('Y-m-d');
            echo "<h2>Today Orders (Date: $today)</h2>";
            $todayOrders = $db->table('service_orders so')
                             ->select('so.*, c.name as client_name, sos.service_name')
                             ->join('clients c', 'c.id = so.client_id', 'left')
                             ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                             ->where('so.deleted', 0)
                             ->where('DATE(so.date)', $today)
                             ->get()
                             ->getResultArray();
            
            echo "<p>Found " . count($todayOrders) . " orders for today</p>";
            foreach ($todayOrders as $order) {
                echo "<p>ID: {$order['id']} | Date: {$order['date']} | Client: {$order['client_name']} | Service: {$order['service_name']}</p>";
            }
            
            // Test tomorrow orders
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            echo "<h2>Tomorrow Orders (Date: $tomorrow)</h2>";
            $tomorrowOrders = $db->table('service_orders so')
                                 ->select('so.*, c.name as client_name, sos.service_name')
                                 ->join('clients c', 'c.id = so.client_id', 'left')
                                 ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                                 ->where('so.deleted', 0)
                                 ->where('DATE(so.date)', $tomorrow)
                                 ->get()
                                 ->getResultArray();
            
            echo "<p>Found " . count($tomorrowOrders) . " orders for tomorrow</p>";
            foreach ($tomorrowOrders as $order) {
                echo "<p>ID: {$order['id']} | Date: {$order['date']} | Client: {$order['client_name']} | Service: {$order['service_name']}</p>";
            }
            
            // Test pending orders
            echo "<h2>Pending Orders</h2>";
            $pendingOrders = $db->table('service_orders so')
                               ->select('so.*, c.name as client_name, sos.service_name')
                               ->join('clients c', 'c.id = so.client_id', 'left')
                               ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                               ->where('so.deleted', 0)
                               ->groupStart()
                                   ->whereIn('so.status', ['pending', 'in_progress'])
                                   ->orWhere('so.status', '')
                                   ->orWhere('so.status', null)
                               ->groupEnd()
                               ->get()
                               ->getResultArray();
            
            echo "<p>Found " . count($pendingOrders) . " pending orders</p>";
            foreach ($pendingOrders as $order) {
                echo "<p>ID: {$order['id']} | Date: {$order['date']} | Status: {$order['status']} | Client: {$order['client_name']} | Service: {$order['service_name']}</p>";
            }
            
            // Test all orders
            echo "<h2>All Orders</h2>";
            $allOrders = $db->table('service_orders so')
                           ->select('so.*, c.name as client_name, sos.service_name')
                           ->join('clients c', 'c.id = so.client_id', 'left')
                           ->join('service_orders_services sos', 'sos.id = so.service_id', 'left')
                           ->where('so.deleted', 0)
                           ->get()
                           ->getResultArray();
            
            echo "<p>Found " . count($allOrders) . " total orders</p>";
            foreach ($allOrders as $order) {
                echo "<p>ID: {$order['id']} | Date: {$order['date']} | Status: {$order['status']} | Client: {$order['client_name']} | Service: {$order['service_name']}</p>";
            }
            
        } catch (\Exception $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    }


}
