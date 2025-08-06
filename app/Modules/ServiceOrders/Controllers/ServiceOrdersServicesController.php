<?php

namespace Modules\ServiceOrders\Controllers;

use App\Controllers\BaseController;
use Modules\ServiceOrders\Models\ServiceOrderServiceModel;
use App\Models\ClientModel;
use CodeIgniter\API\ResponseTrait;

class ServiceOrdersServicesController extends BaseController
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
        $this->serviceModel = new ServiceOrderServiceModel();
    }

    public function index()
    {
        // Load clients for the dropdown filter
        $clientModel = new \App\Models\ClientModel();
        $clients = $clientModel->getActiveClients();
        
        $data = [
            'title' => 'Service Orders Services',
            'clients' => $clients
        ];

        return view('Modules\ServiceOrders\Views\service_orders\services_content', $data);
    }

    public function list_data()
    {
        try {
            // DataTables parameters
            $draw = intval($this->request->getPost('draw') ?? 1);
            $start = intval($this->request->getPost('start') ?? 0);
            $length = intval($this->request->getPost('length') ?? 10);
            
            // Safe search value extraction
            $searchArray = $this->request->getPost('search') ?? [];
            $searchValue = isset($searchArray['value']) ? trim($searchArray['value']) : '';
            
            // Filters
            $clientFilter = $this->request->getPost('client_filter') ?? '';
            $statusFilter = $this->request->getPost('status_filter') ?? '';
            $ordersFilter = $this->request->getPost('orders_filter') ?? '';
            
            // Get total records without any filters
            $totalRecords = $this->serviceModel->where('deleted', 0)->countAllResults();
            
            // Start building the main query
            $db = \Config\Database::connect();
            $builder = $db->table('service_orders_services')
                         ->select('service_orders_services.*, clients.name as client_name')
                                       ->join('clients', 'clients.id = service_orders_services.client_id', 'left')
                         ->where('service_orders_services.deleted', 0);
            
            // Apply filters
            if (!empty($clientFilter)) {
                $builder->where('service_orders_services.client_id', $clientFilter);
            }
            
            if (!empty($statusFilter)) {
                $builder->where('service_orders_services.service_status', $statusFilter);
            }
            
            if (!empty($ordersFilter)) {
                $builder->where('service_orders_services.show_in_orders', $ordersFilter);
            }
            
            // Apply search
            if (!empty($searchValue)) {
                $builder->groupStart()
                       ->like('service_orders_services.service_name', $searchValue)
                       ->orLike('service_orders_services.service_description', $searchValue)
                       ->orLike('clients.name', $searchValue)
                       ->groupEnd();
            }
            
            // Get filtered records count
            $filteredRecords = $builder->countAllResults(false);
            
            // Apply ordering and pagination
            $builder->orderBy('service_orders_services.service_name', 'ASC')
                   ->limit($length, $start);
            
            $services = $builder->get()->getResultArray();
        
        $data = [];
        foreach ($services as $service) {
            $data[] = [
                'id' => $service['id'],
                    'service_name' => $service['service_name'] ?? '',
                'service_description' => $service['service_description'] ?? '',
                    'client_name' => $service['client_name'] ?? 'General',
                    'service_price' => number_format(floatval($service['service_price'] ?? 0), 2),
                'notes' => $service['notes'] ?? '',
                    'service_status' => $service['service_status'] ?? 'inactive',
                    'show_in_orders' => intval($service['show_in_orders'] ?? 0)
            ];
        }
        
            return $this->response->setJSON([
                'draw' => $draw,
                'recordsTotal' => intval($totalRecords),
                'recordsFiltered' => intval($filteredRecords),
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in ServiceOrdersServicesController::list_data: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'draw' => intval($this->request->getPost('draw') ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Error loading services data'
            ]);
        }
    }

    public function modal_form()
    {
        // Load clients for the dropdown
        $clientModel = new \App\Models\ClientModel();
        $clients = $clientModel->where('status', 'active')
                              ->orderBy('name', 'ASC')
                              ->findAll();
        
        $data = [
            'service_id' => $this->request->getGet('id') ?? null,
            'clients' => $clients,
        ];

        if ($data['service_id']) {
            // Obtener servicio con información del cliente si está asignado
            $data['service'] = $this->serviceModel->select('service_orders_services.*, clients.name as client_name, clients.email as client_email')
                                                  ->join('clients', 'clients.id = service_orders_services.client_id', 'left')
                                                  ->where('service_orders_services.id', $data['service_id'])
                                                  ->where('service_orders_services.deleted', 0)
                                                  ->first();
            
            if (!$data['service']) {
                return redirect()->to('service_orders_services')->with('error', 'Service not found.');
            }
        }

        return view('Modules\ServiceOrders\Views\service_orders\services\modal_form', $data);
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
            'service_status' => $this->request->getPost('is_active') ? 'active' : 'inactive',
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
                    return redirect()->to('service_orders_services')->with('success', 'Service updated successfully');
                }
            } else {
                // Crear nuevo - verificar duplicados por nombre y cliente
                $duplicateQuery = $this->serviceModel->where('service_name', $data['service_name'])
                                                    ->where('deleted', 0);
                
                // Si client_id es NULL, verificar que no exista otro servicio general con el mismo nombre
                // Si client_id tiene valor, verificar que no exista para ese cliente específico
                if ($data['client_id'] === null) {
                    $duplicateQuery->where('client_id', null);
                } else {
                    $duplicateQuery->where('client_id', $data['client_id']);
                }
                
                $duplicateCheck = $duplicateQuery->first();
                
                if ($duplicateCheck) {
                    $errorMessage = $data['client_id'] === null ? 
                        'A general service with this name already exists' : 
                        'A service with this name already exists for this client';
                    
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => $errorMessage
                        ]);
                    }
                    return redirect()->back()->withInput()->with('error', $errorMessage);
                }
                
                if ($this->serviceModel->insert($data)) {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Service created successfully'
                        ]);
                    }
                    return redirect()->to('service_orders_services')->with('success', 'Service created successfully');
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

    public function delete($id = null)
    {
        // Get ID from parameter or POST data
        if (!$id) {
            $id = $this->request->getPost('id');
        }
        
        if (!$id) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid service ID'
                ]);
            }
            return redirect()->to('service_orders_services')->with('error', 'Invalid service ID');
        }

        // Check if it's an AJAX request
        if ($this->request->isAJAX()) {
            try {
                // First, check if service exists
                $service = $this->serviceModel->find($id);
                if (!$service) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Service not found'
                    ]);
                }
                
                // Check if service is in use in active orders (temporarily disabled due to DB structure issue)
                // TODO: Re-enable this check once DB structure is verified
                /*
                try {
                    $db = \Config\Database::connect();
                    $ordersUsingService = $db->table('service_orders')
                                            ->where('service_id', $id)
                                            ->where('deleted', 0)
                                            ->countAllResults();
                    
                    if ($ordersUsingService > 0) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => "Cannot delete service. It is being used in {$ordersUsingService} active order(s). Please remove it from those orders first."
                        ]);
                    }
                } catch (\Exception $e) {
                    // If there's an error checking usage, log it but don't prevent deletion
                    log_message('warning', 'Could not check service usage in orders: ' . $e->getMessage());
                }
                */
                
                // Perform soft delete
                if ($this->serviceModel->delete($id)) {
                    // Log the deletion
                    log_message('info', "Service deleted successfully. ID: {$id}, Name: {$service['service_name']}, User: " . (session()->get('user_id') ?? 'unknown'));
                    
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Service deleted successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to delete service'
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', 'Error deleting service: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error deleting service: ' . $e->getMessage()
                ]);
            }
        }

        // Non-AJAX request
        try {
            // First, check if service exists
            $service = $this->serviceModel->find($id);
            if (!$service) {
                return redirect()->to('service_orders_services')->with('error', 'Service not found');
            }
            
            // Check if service is in use in active orders (temporarily disabled due to DB structure issue)
            // TODO: Re-enable this check once DB structure is verified
            /*
            try {
                $db = \Config\Database::connect();
                $ordersUsingService = $db->table('service_orders')
                                        ->where('service_id', $id)
                                        ->where('deleted', 0)
                                        ->countAllResults();
                
                if ($ordersUsingService > 0) {
                    return redirect()->to('service_orders_services')->with('error', "Cannot delete service. It is being used in {$ordersUsingService} active order(s). Please remove it from those orders first.");
                }
            } catch (\Exception $e) {
                // If there's an error checking usage, log it but don't prevent deletion
                log_message('warning', 'Could not check service usage in orders: ' . $e->getMessage());
            }
            */
            
            if ($this->serviceModel->delete($id)) {
                // Log the deletion
                log_message('info', "Service deleted successfully. ID: {$id}, Name: {$service['service_name']}, User: " . (session()->get('user_id') ?? 'unknown'));
                
                return redirect()->to('service_orders_services')->with('success', 'Service deleted successfully');
            } else {
                return redirect()->to('service_orders_services')->with('error', 'Failed to delete service');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting service: ' . $e->getMessage());
            return redirect()->to('service_orders_services')->with('error', 'Error deleting service: ' . $e->getMessage());
        }
    }

    public function toggle_show_in_orders($id)
    {
        try {
            $service = $this->serviceModel->find($id);
            
            if (!$service) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service not found'
                ]);
            }
            
            $newValue = $service['show_in_orders'] ? 0 : 1;
            
            if ($this->serviceModel->update($id, ['show_in_orders' => $newValue])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service visibility updated successfully',
                    'new_value' => $newValue
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service visibility'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating service visibility: ' . $e->getMessage()
            ]);
        }
    }

    public function toggle_status($id)
    {
        try {
            $service = $this->serviceModel->find($id);
            
            if (!$service) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service not found'
                ]);
            }
            
            $newStatus = $service['service_status'] === 'active' ? 'inactive' : 'active';
            
            if ($this->serviceModel->update($id, ['service_status' => $newStatus])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service status updated successfully',
                    'new_status' => $newStatus
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service status'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating service status: ' . $e->getMessage()
            ]);
        }
    }
} 