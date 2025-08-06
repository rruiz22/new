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
    }

    public function index()
    {
        $data = [
            'title' => 'Services',
        ];

        return view('sales_orders/services/index', $data);
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
            'service_id' => $this->request->getGet('id') ?? null,
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
            'client_id' => 'permit_empty|numeric',
            'service_name' => 'required',
            'service_price' => 'required|decimal',
            'service_description' => 'permit_empty',
            'notes' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'client_id' => $this->request->getPost('client_id') ?: null,
            'service_name' => $this->request->getPost('service_name'),
            'service_description' => $this->request->getPost('service_description'),
            'service_price' => $this->request->getPost('service_price'),
            'notes' => $this->request->getPost('notes'),
            'service_status' => $this->request->getPost('is_active') ? 'active' : 'deactivated',
            'show_in_orders' => $this->request->getPost('show_in_orders') ? 1 : 0,
            'created_by' => session()->get('user_id') ?? 1,
            'updated_by' => session()->get('user_id') ?? 1,
        ];

        $serviceId = $this->request->getPost('id');
        
        try {
            if ($serviceId) {
                // Actualizar
                if ($this->serviceModel->update($serviceId, $data)) {
                    if ($this->request->isAJAX()) {
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
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Service created successfully'
                        ]);
                    }
                    return redirect()->to('sales_orders_services')->with('success', 'Service created successfully');
                }
            }
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error saving service: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Error saving service: ' . $e->getMessage());
        }

        if ($this->request->isAJAX()) {
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

        return view('sales_orders/services/view', $data);
    }

    public function delete($id)
    {
        // Check if it's an AJAX request
        if ($this->request->isAJAX()) {
            try {
                if ($this->serviceModel->delete($id)) {
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
        if ($this->serviceModel->delete($id)) {
            return redirect()->to('sales_orders_services')->with('success', 'Service deleted successfully');
        }
        
        return redirect()->to('sales_orders_services')->with('error', 'Error deleting service');
    }

    /**
     * Get all active services in JSON format for dropdown lists
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function get_services_json()
    {
        $services = $this->serviceModel->where('deleted', 0)
                                      ->where('service_status', 'active')
                                      ->where('show_in_orders', 1)
                                      ->orderBy('service_name', 'ASC')
                                      ->findAll();
        
        return $this->response->setJSON($services);
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
            // Verificar si hay una sesión activa de manera más simple
            if (!session()->has('isLoggedIn') && !session()->has('user')) {
                log_message('warning', 'Unauthorized access attempt to get_services_by_client_json from IP: ' . $this->request->getIPAddress());
                
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON(['error' => 'Authentication required']);
            }
            
            if (!$clientId) {
                return $this->response->setJSON([]);
            }

            $services = $this->serviceModel->where('deleted', 0)
                                          ->where('client_id', $clientId)
                                          ->where('service_status', 'active')
                                          ->where('show_in_orders', 1)
                                          ->orderBy('service_name', 'ASC')
                                          ->findAll();
            
            log_message('info', 'Found ' . count($services) . ' active services for client ' . $clientId . ' by session user');
            
            return $this->response->setJSON($services);
        } catch (\Exception $e) {
            log_message('error', 'Error in get_services_by_client_json: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => $e->getMessage()]);
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
        $service = $this->serviceModel->find($id);
        
        if (!$service) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Service not found'
            ]);
        }
        
        $showInOrders = $this->request->getPost('show_in_orders') == 1;
        
        $data = [
            'show_in_orders' => $showInOrders ? 1 : 0
        ];
        
        if ($this->serviceModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Service visibility updated'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update service visibility'
        ]);
    }
    
    /**
     * Toggle the active status of a service
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function toggle_status($id)
    {
        $service = $this->serviceModel->find($id);
        
        if (!$service) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Service not found'
            ]);
        }
        
        $isActive = $this->request->getPost('is_active') == 1;
        
        $data = [
            'service_status' => $isActive ? 'active' : 'deactivated'
        ];
        
        if ($this->serviceModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Service status updated'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update service status'
        ]);
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