<?php

namespace Modules\ReconOrders\Controllers;

use App\Controllers\BaseController;
use Modules\ReconOrders\Models\ReconServiceModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReconServicesController extends BaseController
{
    protected $reconServiceModel;

    public function __construct()
    {
        $this->reconServiceModel = new ReconServiceModel();
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
            'category' => $this->request->getPost('category'),
            'search' => $searchValue
        ];

        try {
            // Check if table exists first
            $db = \Config\Database::connect();
            if (!$db->tableExists('recon_services')) {
                return $this->response->setJSON([
                    'draw' => $draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => []
                ]);
            }

            // Build query with LEFT JOIN to clients table
            $builder = $db->table('recon_services');
            $builder->select('recon_services.id, recon_services.name, recon_services.description, recon_services.price, recon_services.duration, recon_services.category, recon_services.client_id, recon_services.is_active, recon_services.visibility_type, recon_services.sort_order, recon_services.color, recon_services.created_at, recon_services.updated_at, clients.name as client_name');
            $builder->join('clients', 'clients.id = recon_services.client_id', 'left');
            
            // Apply filters
            if (!empty($filters['client_id'])) {
                $builder->where('recon_services.client_id', $filters['client_id']);
            }

            if ($filters['is_active'] !== '' && $filters['is_active'] !== null) {
                $builder->where('recon_services.is_active', $filters['is_active']);
            }

            if ($filters['visibility_type'] !== '' && $filters['visibility_type'] !== null) {
                $builder->where('recon_services.visibility_type', $filters['visibility_type']);
            }

            if (!empty($filters['category'])) {
                $builder->where('recon_services.category', $filters['category']);
            }

            // Search
            if (!empty($searchValue)) {
                $builder->groupStart()
                        ->like('recon_services.name', $searchValue)
                        ->orLike('recon_services.description', $searchValue)
                        ->orLike('clients.name', $searchValue)
                        ->groupEnd();
            }

            // Count total records
            $totalRecords = $builder->countAllResults(false);

            // Get filtered records
            $services = $builder->orderBy('recon_services.sort_order', 'ASC')
                               ->orderBy('recon_services.name', 'ASC')
                               ->limit($length, $start)
                               ->get()
                               ->getResultArray();

            // Format data for DataTables
            $data = [];
            foreach ($services as $service) {
                $data[] = [
                    'id' => $service['id'],
                    'name' => $service['name'],
                    'description' => $service['description'] ?? '',
                    'price' => number_format($service['price'], 2),
                    'duration' => $service['duration'] . ' min',
                    'category' => ucfirst($service['category']),
                    'client_name' => $service['client_name'] ?? 'Global',
                    'is_active' => $service['is_active'],
                    'visibility_type' => ucfirst(str_replace('_', ' ', $service['visibility_type'])),
                    'color' => $service['color'] ?? '#6c757d',
                    'created_at' => $service['created_at'],
                    'updated_at' => $service['updated_at']
                ];
            }

            return $this->response->setJSON([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Recon Services Data Error: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
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
            log_message('debug', 'Recon Service Store - Incoming data: ' . json_encode($data));
            
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
            
            // Set sort_order if not provided
            if (empty($data['sort_order'])) {
                $data['sort_order'] = 0;
            }
            
            // Set duration if not provided
            if (empty($data['duration'])) {
                $data['duration'] = 60;
            }
            
            // Set category if not provided
            if (empty($data['category'])) {
                $data['category'] = 'inspection';
            }
            
            // Log processed data
            log_message('debug', 'Recon Service Store - Processed data: ' . json_encode($data));
            
            // Custom validation rules for store
            $validation = \Config\Services::validation();
            $validation->setRules([
                'name' => 'required|max_length[255]',
                'price' => 'required|decimal'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                log_message('error', 'Recon Service Store - Validation failed: ' . json_encode($validation->getErrors()));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Disable model validation temporarily to avoid conflicts
            $this->reconServiceModel->skipValidation(true);
            $serviceId = $this->reconServiceModel->insert($data);
            
            if ($serviceId) {
                log_message('debug', 'Recon Service Store - Service created successfully with ID: ' . $serviceId);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service created successfully',
                    'data' => ['id' => $serviceId]
                ]);
            } else {
                $errors = $this->reconServiceModel->errors();
                log_message('error', 'Recon Service Store - Model insert failed: ' . json_encode($errors));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create service',
                    'errors' => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Recon Service Store Error: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error creating service: ' . $e->getMessage()
            ]);
        }
    }

    public function update($id)
    {
        try {
            $data = $this->request->getPost();
            
            // Log the incoming data for debugging
            log_message('debug', 'Recon Service Update - Incoming data: ' . json_encode($data));
            
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
            
            // Custom validation rules for update
            $validation = \Config\Services::validation();
            $validation->setRules([
                'name' => 'required|max_length[255]',
                'price' => 'required|decimal'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                log_message('error', 'Recon Service Update - Validation failed: ' . json_encode($validation->getErrors()));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Disable model validation temporarily to avoid conflicts
            $this->reconServiceModel->skipValidation(true);
            $result = $this->reconServiceModel->update($id, $data);
            
            if ($result) {
                log_message('debug', 'Recon Service Update - Service updated successfully');
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service updated successfully'
                ]);
            } else {
                $errors = $this->reconServiceModel->errors();
                log_message('error', 'Recon Service Update - Model update failed: ' . json_encode($errors));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service',
                    'errors' => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Recon Service Update Error: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating service: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        try {
            $service = $this->reconServiceModel->find($id);
            
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
            log_message('error', 'Recon Service Show Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error retrieving service: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->reconServiceModel->safeDelete($id);
            
            return $this->response->setJSON([
                'success' => $result['success'],
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Recon Service Delete Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error deleting service: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $result = $this->reconServiceModel->toggleStatus($id);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service status updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service status'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Recon Service Toggle Status Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating service status: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleVisibilityType($id)
    {
        try {
            $result = $this->reconServiceModel->toggleVisibilityType($id);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service visibility updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service visibility'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Recon Service Toggle Visibility Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating service visibility: ' . $e->getMessage()
            ]);
        }
    }

    public function loadClients()
    {
        try {
            $clientModel = new \App\Models\ClientModel();
            $clients = $clientModel->where('status', 'active')
                                  ->orderBy('name', 'ASC')
                                  ->findAll();

            return $this->response->setJSON([
                'success' => true,
                'data' => $clients
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Recon Service Load Clients Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading clients: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function getServiceStats()
    {
        try {
            $stats = $this->reconServiceModel->getServiceStats();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Recon Service Stats Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error getting service stats: ' . $e->getMessage()
            ]);
        }
    }

    public function getPopularServices()
    {
        try {
            $services = $this->reconServiceModel->getPopularServices();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Recon Service Popular Services Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error getting popular services: ' . $e->getMessage()
            ]);
        }
    }

    public function getServicesByCategory($category)
    {
        try {
            $userType = session()->get('user_type') ?? 'client';
            $services = $this->reconServiceModel->getServicesByCategory($category, null, $userType);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Recon Service By Category Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error getting services by category: ' . $e->getMessage()
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
            log_message('error', 'Recon Service Active Services Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error getting active services: ' . $e->getMessage()
            ]);
        }
    }
} 