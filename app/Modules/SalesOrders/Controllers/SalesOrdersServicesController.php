<?php

namespace Modules\SalesOrders\Controllers;

use App\Controllers\BaseController;
use Modules\SalesOrders\Models\SalesOrderServiceModel;
use App\Models\ClientModel;
use CodeIgniter\API\ResponseTrait;

class SalesOrdersServicesController extends BaseController
{
    use ResponseTrait;
    
    protected $serviceModel;

    /**
     * Constructor
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        
        // Load models
        $this->serviceModel = new SalesOrderServiceModel();
        
        // Add custom validation rules
        $this->addCustomValidationRules();
    }
    
    /**
     * Add custom validation rules
     */
    private function addCustomValidationRules()
    {
        $validation = \Config\Services::validation();
        
        // Custom rule to validate client exists
        $validation->setRule('is_valid_client', 'Client Validation', function($value, $error = null) {
            if (empty($value)) {
                return true; // Allow empty values (permit_empty handles this)
            }
            
            $clientModel = new \App\Models\ClientModel();
            $client = $clientModel->where('id', $value)->where('status', 'active')->first();
            
            return $client !== null;
        });
    }

    public function index()
    {
        $data = [
            'title' => 'Services',
        ];

        return view('Modules\\SalesOrders\\Views\\sales_orders/services/index', $data);
    }

    public function list_data()
    {
        // Get all services with client names
        $services = $this->serviceModel->select('sales_orders_services.*, clients.name as client_name')
                                       ->join('clients', 'clients.id = sales_orders_services.client_id', 'left')
                                       ->where('sales_orders_services.deleted', 0)
                                       ->orderBy('service_name', 'ASC')
                                       ->findAll();
        
        $data = [];
        foreach ($services as $service) {
            $data[] = [
                'id' => $service['id'],
                'service_name' => $service['service_name'],
                'service_description' => $service['service_description'] ?? '',
                'client_name' => $service['client_name'] ?? null,
                'service_price' => $service['service_price'],
                'notes' => $service['notes'] ?? '',
                'service_status' => $service['service_status'],
                'show_in_orders' => $service['show_in_orders'] ?? 0
            ];
        }
        
        return $this->response->setJSON(['data' => $data]);
    }

    public function modal_form()
    {
        // Load clients for the dropdown
        $clientModel = new \App\Models\ClientModel();
        $clients = $clientModel->where('status', 'active')
                              ->orderBy('name', 'ASC')
                              ->findAll();
        
        // Log for debugging
        log_message('info', 'SalesOrdersServicesController::modal_form - Found ' . count($clients) . ' active clients');
        
        $data = [
            'service_id' => $this->request->getVar('id') ?? null,
            'clients' => $clients,
        ];

        if ($data['service_id']) {
            // Obtener servicio con información del cliente si está asignado
            $data['service'] = $this->serviceModel->select('sales_orders_services.*, clients.name as client_name, clients.email as client_email')
                                                  ->join('clients', 'clients.id = sales_orders_services.client_id', 'left')
                                                  ->where('sales_orders_services.id', $data['service_id'])
                                                  ->where('sales_orders_services.deleted', 0)
                                                  ->first();
            
            if (!$data['service']) {
                return redirect()->to('sales_orders_services')->with('error', 'Service not found.');
            }
        }

        return view('Modules\SalesOrders\Views\sales_orders/services/modal_form', $data);
    }

    public function store()
    {
        $rules = [
            'client_id' => 'permit_empty|numeric|is_valid_client',
            'service_name' => 'required|min_length[3]|max_length[255]',
            'service_price' => 'required|decimal|greater_than[0]',
            'service_description' => 'permit_empty|max_length[1000]',
            'notes' => 'permit_empty|max_length[1000]',
        ];
        
        // Custom validation messages
        $messages = [
            'client_id' => [
                'numeric' => 'The client must be a valid ID.',
                'is_valid_client' => 'The selected client does not exist.'
            ],
            'service_name' => [
                'required' => 'The service name is required.',
                'min_length' => 'The service name must be at least 3 characters long.',
                'max_length' => 'The service name cannot exceed 255 characters.'
            ],
            'service_price' => [
                'required' => 'The service price is required.',
                'decimal' => 'The service price must be a valid decimal number.',
                'greater_than' => 'The service price must be greater than 0.'
            ],
            'service_description' => [
                'max_length' => 'The service description cannot exceed 1000 characters.'
            ],
            'notes' => [
                'max_length' => 'The notes cannot exceed 1000 characters.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            if ($this->request->isAjax()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'client_id' => $this->request->getVar('client_id') ?: null,
            'service_name' => $this->request->getVar('service_name'),
            'service_description' => $this->request->getVar('service_description'),
            'service_price' => $this->request->getVar('service_price'),
            'notes' => $this->request->getVar('notes'),
            'service_status' => $this->request->getVar('is_active') ? 'active' : 'deactivated',
            'show_in_orders' => $this->request->getVar('show_in_orders') ? 1 : 0,
            'created_by' => session()->get('user_id') ?? 1,
            'updated_by' => session()->get('user_id') ?? 1,
        ];

        $serviceId = $this->request->getVar('id');
        
        try {
            if ($serviceId) {
                // Actualizar
                if ($this->serviceModel->update($serviceId, $data)) {
                    if ($this->request->isAjax()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Service updated successfully'
                        ]);
                    }
                    return redirect()->to('sales_orders_services')->with('success', 'Service updated successfully');
                }
            } else {
                // Crear nuevo
                if ($this->serviceModel->insert($data)) {
                    if ($this->request->isAjax()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Service created successfully'
                        ]);
                    }
                    return redirect()->to('sales_orders_services')->with('success', 'Service created successfully');
                }
            }
        } catch (\Exception $e) {
            if ($this->request->isAjax()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error saving service: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Error saving service: ' . $e->getMessage());
        }

        if ($this->request->isAjax()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error saving service'
            ]);
        }
        return redirect()->back()->withInput()->with('error', 'Error saving service');
    }

    public function view($id)
    {
        // Obtener servicio con información del cliente si está asignado
        $service = $this->serviceModel->select('sales_orders_services.*, clients.name as client_name, clients.email as client_email')
                                      ->join('clients', 'clients.id = sales_orders_services.client_id', 'left')
                                      ->where('sales_orders_services.id', $id)
                                      ->first();
        
        if (!$service) {
            return redirect()->to('sales_orders_services')->with('error', 'Service not found');
        }
        
        $data = [
            'title' => 'Service Details',
            'service' => $service
        ];

        return view('Modules\\SalesOrders\\Views\\sales_orders/services/view', $data);
    }

    public function delete($id)
    {
        // Verify service exists and is not already deleted
        $service = $this->serviceModel->where('id', $id)->where('deleted', 0)->first();
        
        if (!$service) {
            if ($this->request->isAjax()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service not found or already deleted'
                ]);
            }
            return redirect()->to('sales_orders_services')->with('error', 'Service not found or already deleted');
        }
        
        // Check if it's an AJAX request
        if ($this->request->isAjax()) {
            try {
                // Use soft delete by setting deleted = 1
                $data = [
                    'deleted' => 1,
                    'updated_by' => session()->get('user_id') ?? 1
                ];
                
                if ($this->serviceModel->update($id, $data)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Service deleted successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error deleting service'
                    ]);
                }
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error deleting service: ' . $e->getMessage()
                ]);
            }
        }
        
        // For non-AJAX requests, use redirect (legacy support)
        try {
            $data = [
                'deleted' => 1,
                'updated_by' => session()->get('user_id') ?? 1
            ];
            
            if ($this->serviceModel->update($id, $data)) {
                return redirect()->to('sales_orders_services')->with('success', 'Service deleted successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('sales_orders_services')->with('error', 'Error deleting service: ' . $e->getMessage());
        }
        
        return redirect()->to('sales_orders_services')->with('error', 'Error deleting service');
    }

    /**
     * Get service data by ID in JSON format
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function get_data($id)
    {
        try {
            // Verificar autenticación
            if (!session()->has('isLoggedIn') && !session()->has('user')) {
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Authentication required']);
            }
            
            // Obtener servicio con información del cliente si está asignado
            $service = $this->serviceModel->select('sales_orders_services.*, clients.name as client_name, clients.email as client_email')
                                          ->join('clients', 'clients.id = sales_orders_services.client_id', 'left')
                                          ->where('sales_orders_services.id', $id)
                                          ->where('sales_orders_services.deleted', 0)
                                          ->first();
            
            if (!$service) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service not found'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $service
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in get_data: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Error retrieving service data: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Get all active services in JSON format for dropdown lists
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function get_services_json()
    {
        try {
            // Verificar autenticación
            if (!session()->has('isLoggedIn') && !session()->has('user')) {
                log_message('warning', 'Unauthorized access attempt to get_services_json from IP: ' . $this->request->getIPAddress());
                
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Authentication required']);
            }
            
            $services = $this->serviceModel->where('deleted', 0)
                                          ->where('service_status', 'active')
                                          ->where('show_in_orders', 1)
                                          ->orderBy('service_name', 'ASC')
                                          ->findAll();
            
            log_message('info', 'Found ' . count($services) . ' active services for general use by session user');
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $services
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in get_services_json: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get services by client ID in JSON format for dropdown lists
     *
     * @param int $clientId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function get_services_by_client_json($clientId = null)
    {
        try {
            // Verificar autenticación
            if (!session()->has('isLoggedIn') && !session()->has('user')) {
                log_message('warning', 'Unauthorized access attempt to get_services_by_client_json from IP: ' . $this->request->getIPAddress());
                
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Authentication required']);
            }
            
            if (!$clientId) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => []
                ]);
            }

            $services = $this->serviceModel->where('deleted', 0)
                                          ->where('client_id', $clientId)
                                          ->where('service_status', 'active')
                                          ->where('show_in_orders', 1)
                                          ->orderBy('service_name', 'ASC')
                                          ->findAll();
            
            log_message('info', 'Found ' . count($services) . ' active services for client ' . $clientId . ' by session user');
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $services
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in get_services_by_client_json: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Toggle the visibility of a service in sales orders
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function toggle_show_in_orders($id)
    {
        try {
            // Verificar autenticación
            if (!session()->has('isLoggedIn') && !session()->has('user')) {
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Authentication required']);
            }
            
            $service = $this->serviceModel->where('id', $id)->where('deleted', 0)->first();
            
            if (!$service) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service not found'
                ]);
            }
            
            $showInOrders = $this->request->getVar('show_in_orders') == 1;
            
            $data = [
                'show_in_orders' => $showInOrders ? 1 : 0,
                'updated_by' => session()->get('user_id') ?? 1
            ];
            
            if ($this->serviceModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service visibility updated successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update service visibility'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in toggle_show_in_orders: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Error updating service visibility: ' . $e->getMessage()
                ]);
        }
    }
    
    /**
     * Toggle the active status of a service
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function toggle_status($id)
    {
        try {
            // Verificar autenticación
            if (!session()->has('isLoggedIn') && !session()->has('user')) {
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Authentication required']);
            }
            
            $service = $this->serviceModel->where('id', $id)->where('deleted', 0)->first();
            
            if (!$service) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service not found'
                ]);
            }
            
            $isActive = $this->request->getVar('is_active') == 1;
            
            $data = [
                'service_status' => $isActive ? 'active' : 'deactivated',
                'updated_by' => session()->get('user_id') ?? 1
            ];
            
            if ($this->serviceModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service status updated successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update service status'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in toggle_status: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Error updating service status: ' . $e->getMessage()
                ]);
        }
    }

    private function _make_options($id, $is_active)
    {
        $options = '<div class="hstack gap-2 flex-wrap justify-content-end">';
        
        // Ver
        $options .= '<a href="' . site_url("sales_orders_services/view/{$id}") . '" 
                       class="btn btn-sm btn-soft-info" title="View Service">
                       <i data-feather="eye" class="icon-sm"></i>
                    </a>';
        
        // Editar
        $options .= '<a href="#" data-bs-toggle="modal" data-bs-target="#serviceModal" data-service-id="' . $id . '" 
                       class="btn btn-sm btn-soft-success" title="Edit Service">
                       <i data-feather="edit" class="icon-sm"></i>
                    </a>';
        
        // Eliminar
        $options .= '<button type="button" class="btn btn-sm btn-soft-danger delete-service-btn" 
                       data-id="' . $id . '" data-name="Service #' . $id . '"
                       title="Delete Service">
                       <i data-feather="trash-2" class="icon-sm"></i>
                    </button>';
        
        $options .= '</div>';
        
        return $options;
    }
} 