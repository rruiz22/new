<?php

namespace Modules\CarWash\Controllers;

use App\Controllers\BaseController;
use Modules\CarWash\Models\CarWashServiceModel;
use CodeIgniter\HTTP\ResponseInterface;

class CarWashServicesController extends BaseController
{
    protected $carWashServiceModel;

    public function __construct()
    {
        $this->carWashServiceModel = new CarWashServiceModel();
    }

    public function getServicesData()
    {
        $draw = $this->request->getPost('draw');
        $start = (int) $this->request->getPost('start');
        $length = (int) $this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'] ?? '';

        // Filters
        $filters = [
            'client_id' => $this->request->getPost('client_id'),
            'is_active' => $this->request->getPost('is_active'),
            'visibility_type' => $this->request->getPost('visibility_type'),
            'search' => $searchValue
        ];

        try {
            // Check if table exists first
            $db = \Config\Database::connect();
            if (!$db->tableExists('car_wash_services')) {
                return $this->response->setJSON([
                    'draw' => $draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => []
                ]);
            }

            // Build query with LEFT JOIN to clients table
            $builder = $db->table('car_wash_services');
            $builder->select('car_wash_services.id, car_wash_services.name, car_wash_services.description, car_wash_services.price, car_wash_services.duration, car_wash_services.category, car_wash_services.client_id, car_wash_services.is_active, car_wash_services.visibility_type, car_wash_services.sort_order, car_wash_services.color, car_wash_services.created_at, car_wash_services.updated_at, clients.name as client_name');
            $builder->join('clients', 'clients.id = car_wash_services.client_id', 'left');
            
            // Apply filters
            if (!empty($filters['client_id'])) {
                $builder->where('car_wash_services.client_id', $filters['client_id']);
            }

            if ($filters['is_active'] !== '' && $filters['is_active'] !== null) {
                $builder->where('car_wash_services.is_active', $filters['is_active']);
            }

            if ($filters['visibility_type'] !== '' && $filters['visibility_type'] !== null) {
                $builder->where('car_wash_services.visibility_type', $filters['visibility_type']);
            }

            if (!empty($filters['search'])) {
                $builder->groupStart()
                        ->like('car_wash_services.name', $filters['search'])
                        ->orLike('car_wash_services.description', $filters['search'])
                        ->orLike('clients.name', $filters['search'])
                        ->groupEnd();
            }

            // Order by
            $builder->orderBy('car_wash_services.sort_order', 'ASC')
                    ->orderBy('car_wash_services.name', 'ASC');
            
            // Get all services with filters (without pagination) to count them properly
            $allServices = $builder->get()->getResultArray();
            $totalRecords = count($allServices);
            
            // Apply pagination manually to the results
            $services = array_slice($allServices, $start, $length);
            
            $data = [];
            foreach ($services as $service) {
                $clientName = $service['client_name'] ?? 'Global';
                $statusBadge = $service['is_active'] ? 
                    '<span class="badge bg-success">Active</span>' : 
                    '<span class="badge bg-danger">Inactive</span>';
                $visibilityBadge = $service['visibility_type'] === 'all' ? 
                    '<span class="badge bg-info">Todos</span>' : 
                    '<span class="badge bg-warning">Solo Staff</span>';

                // Color indicator
                $color = $service['color'] ?? '#007bff';
                $colorIndicator = '<div class="d-flex align-items-center justify-content-center">
                    <div class="color-indicator" style="width: 24px; height: 24px; border-radius: 50%; background-color: ' . $color . '; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
                </div>';

                $data[] = [
                    'DT_RowData' => [
                        'color' => $color
                    ],
                    $clientName,
                    $service['name'],
                    $service['description'] ?? '',
                    $colorIndicator,
                    '$' . number_format($service['price'], 2),
                    $statusBadge,
                    $visibilityBadge,
                    '<div class="d-flex justify-content-center gap-1 action-buttons">
                        <a href="#" class="link-success fs-15 edit-service" data-id="' . $service['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                            <i class="ri-edit-fill"></i>
                        </a>
                        <a href="#" class="link-primary fs-15 toggle-status" data-id="' . $service['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Toggle Status">
                            <i class="ri-toggle-line"></i>
                        </a>
                        <a href="#" class="link-info fs-15 toggle-visibility-type" data-id="' . $service['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Toggle Visibility Type">
                            <i class="ri-eye-line"></i>
                        </a>
                        <a href="#" class="link-danger fs-15 delete-service" data-id="' . $service['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class="ri-delete-bin-line"></i>
                        </a>
                    </div>'
                ];
            }

            return $this->response->setJSON([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            log_message('error', 'CarWash Services Data Error: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store()
    {
        try {
            $data = $this->request->getPost();
            
            // Log the incoming data for debugging
            log_message('debug', 'CarWash Service Store - Incoming data: ' . json_encode($data));
            
            // No additional required fields to set
            
            // Handle checkboxes and selects properly
            $data['is_active'] = (isset($data['is_active']) && $data['is_active']) ? 1 : 0;
            
            // Handle visibility_type - ensure it's set to a valid value
            if (empty($data['visibility_type']) || !in_array($data['visibility_type'], ['all', 'staff_only'])) {
                $data['visibility_type'] = 'all';
            }
            
            // Clean up empty client_id
            if (empty($data['client_id'])) {
                $data['client_id'] = null;
            }
            
            // Log processed data
            log_message('debug', 'CarWash Service Store - Processed data: ' . json_encode($data));
            
            // Custom validation rules for store (excluding created_by as it's auto-set)
            $validation = \Config\Services::validation();
            $validation->setRules([
                'name' => 'required|max_length[255]',
                'price' => 'required|decimal'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                log_message('error', 'CarWash Service Store - Validation failed: ' . json_encode($validation->getErrors()));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Disable model validation temporarily to avoid conflicts
            $this->carWashServiceModel->skipValidation(true);
            $serviceId = $this->carWashServiceModel->insert($data);
            
            if ($serviceId) {
                log_message('debug', 'CarWash Service Store - Service created successfully with ID: ' . $serviceId);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service created successfully',
                    'data' => ['id' => $serviceId]
                ]);
            } else {
                $errors = $this->carWashServiceModel->errors();
                log_message('error', 'CarWash Service Store - Model insert failed: ' . json_encode($errors));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create service',
                    'errors' => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'CarWash Service Store Error: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error creating service: ' . $e->getMessage()
            ]);
        }
    }

    public function update($id)
    {
        try {
            $service = $this->carWashServiceModel->find($id);
            if (!$service) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service not found'
                ]);
            }

            $validation = \Config\Services::validation();
            $validation->setRules([
                'name' => 'required|max_length[255]',
                'price' => 'required|decimal'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }

            $data = $this->request->getPost();
            
            // Handle checkboxes and selects
            $data['is_active'] = isset($data['is_active']) ? 1 : 0;
            
            // Handle visibility_type - ensure it's set to a valid value
            if (empty($data['visibility_type']) || !in_array($data['visibility_type'], ['all', 'staff_only'])) {
                $data['visibility_type'] = 'all';
            }

            if ($this->carWashServiceModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service',
                    'errors' => $this->carWashServiceModel->errors()
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'CarWash Service Update Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating service: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $service = $this->carWashServiceModel->find($id);
        if (!$service) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Service not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'service' => $service
        ]);
    }

    public function delete($id)
    {
        $service = $this->carWashServiceModel->find($id);
        if (!$service) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Service not found'
            ]);
        }

        if ($this->carWashServiceModel->delete($id)) {
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
    }

    public function toggleStatus($id)
    {
        if ($this->carWashServiceModel->toggleStatus($id)) {
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
    }

    public function toggleVisibilityType($id)
    {
        if ($this->carWashServiceModel->toggleVisibilityType($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Visibility type updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update visibility type'
            ]);
        }
    }

    public function loadClients()
    {
        try {
            $clientModel = new \App\Models\ClientModel();
            $clients = $clientModel->select('id, name')
                                  ->where('status', 'active')
                                  ->orderBy('name', 'ASC')
                                  ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'clients' => $clients ?? []
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading clients: ' . $e->getMessage(),
                'clients' => []
            ]);
        }
    }
} 