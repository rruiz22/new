<?php

namespace Modules\CarWash\Models;

use CodeIgniter\Model;

class CarWashServiceModel extends Model
{
    protected $table = 'car_wash_services';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'description', 'price', 'duration', 'category', 'color', 'client_id',
        'is_active', 'visibility_type', 'sort_order'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation (Simplified)
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'price' => 'required|decimal'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Service name is required'
        ],
        'price' => [
            'required' => 'Price is required',
            'decimal' => 'Price must be a valid decimal number'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data)
    {
        log_message('debug', 'CarWash Service Model - beforeInsert called with data: ' . json_encode($data));
        
        // Set default values for fields not in the form
        if (!isset($data['data']['duration'])) {
            $data['data']['duration'] = 30; // Default 30 minutes
        }
        if (!isset($data['data']['category'])) {
            $data['data']['category'] = 'additional'; // Default category
        }
        if (!isset($data['data']['sort_order'])) {
            $data['data']['sort_order'] = 0; // Default sort order
        }
        if (!isset($data['data']['is_active'])) {
            $data['data']['is_active'] = 1; // Default active
        }
        if (!isset($data['data']['visibility_type'])) {
            $data['data']['visibility_type'] = 'all'; // Default visible to all
        }
        
        log_message('debug', 'CarWash Service Model - beforeInsert final data: ' . json_encode($data));
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        // No additional processing needed for updates
        return $data;
    }

    public function getServicesWithDetails($filters = [])
    {
        $builder = $this->select('
            car_wash_services.*,
            clients.name as client_name,
            creator.first_name as creator_first_name,
            creator.last_name as creator_last_name
        ')
        ->join('clients', 'clients.id = car_wash_services.client_id', 'left')
        ->join('users as creator', 'creator.id = car_wash_services.created_by', 'left');

        // Apply filters
        if (!empty($filters['category'])) {
            $builder->where('car_wash_services.category', $filters['category']);
        }

        if (!empty($filters['client_id'])) {
            $builder->where('car_wash_services.client_id', $filters['client_id']);
        }

        if (isset($filters['is_active'])) {
            $builder->where('car_wash_services.is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('car_wash_services.name', $filters['search'])
                    ->orLike('car_wash_services.description', $filters['search'])
                    ->orLike('clients.name', $filters['search'])
                    ->groupEnd();
        }

        return $builder->orderBy('car_wash_services.sort_order', 'ASC')
                       ->orderBy('car_wash_services.name', 'ASC');
    }

    public function getActiveServices($clientId = null, $userType = null)
    {
        $builder = $this->where('is_active', 1);
        
        // Filter by visibility type based on user type
        if ($userType === 'client') {
            $builder->where('visibility_type', 'all');
        }
        // For staff (admin, manager, staff), show all services
        
        if ($clientId) {
            $builder->groupStart()
                    ->where('client_id', $clientId)
                    ->orWhere('client_id', null)
                    ->groupEnd();
        } else {
            $builder->where('client_id', null);
        }

        return $builder->orderBy('sort_order', 'ASC')
                       ->orderBy('name', 'ASC')
                       ->findAll();
    }

    public function getServicesByCategory($category, $clientId = null, $userType = null)
    {
        $builder = $this->where('category', $category)
                        ->where('is_active', 1);
        
        // Filter by visibility type based on user type
        if ($userType === 'client') {
            $builder->where('visibility_type', 'all');
        }
        // For staff (admin, manager, staff), show all services
        
        if ($clientId) {
            $builder->groupStart()
                    ->where('client_id', $clientId)
                    ->orWhere('client_id', null)
                    ->groupEnd();
        } else {
            $builder->where('client_id', null);
        }

        return $builder->orderBy('sort_order', 'ASC')
                       ->orderBy('name', 'ASC')
                       ->findAll();
    }

    public function toggleStatus($id)
    {
        $service = $this->find($id);
        if ($service) {
            $newStatus = $service['is_active'] ? 0 : 1;
            return $this->update($id, ['is_active' => $newStatus]);
        }
        return false;
    }



    public function updateSortOrder($id, $sortOrder)
    {
        return $this->update($id, ['sort_order' => $sortOrder]);
    }

    public function getServiceCategories()
    {
        return [
            'exterior' => 'Exterior Wash',
            'interior' => 'Interior Cleaning',
            'full_service' => 'Full Service',
            'detailing' => 'Detailing',
            'additional' => 'Additional Services'
        ];
    }

    public function getPopularServices($limit = 10)
    {
        $db = \Config\Database::connect();
        return $db->table('car_wash_services')
                  ->select('car_wash_services.*, COUNT(car_wash_order_services.service_id) as usage_count')
                  ->join('car_wash_order_services', 'car_wash_order_services.service_id = car_wash_services.id', 'left')
                  ->where('car_wash_services.is_active', 1)
                  ->groupBy('car_wash_services.id')
                  ->orderBy('usage_count', 'DESC')
                  ->limit($limit)
                  ->get()
                  ->getResultArray();
    }

    /**
     * Get services filtered by user type for dropdown/form selection
     */
    public function getServicesForUser($userType, $clientId = null)
    {
        $builder = $this->where('is_active', 1);
        
        // Filter by visibility type based on user type
        if ($userType === 'client') {
            $builder->where('visibility_type', 'all');
        }
        // For staff (admin, manager, staff), show all services
        
        if ($clientId) {
            $builder->groupStart()
                    ->where('client_id', $clientId)
                    ->orWhere('client_id', null)
                    ->groupEnd();
        } else {
            $builder->where('client_id', null);
        }

        return $builder->orderBy('sort_order', 'ASC')
                       ->orderBy('name', 'ASC')
                       ->findAll();
    }

    /**
     * Toggle visibility type between 'all' and 'staff_only'
     */
    public function toggleVisibilityType($id)
    {
        $service = $this->find($id);
        if ($service) {
            $newVisibilityType = $service['visibility_type'] === 'all' ? 'staff_only' : 'all';
            return $this->update($id, ['visibility_type' => $newVisibilityType]);
        }
        return false;
    }

    /**
     * Get visibility type options for forms
     */
    public function getVisibilityTypeOptions()
    {
        return [
            'all' => 'Visible para todos los usuarios',
            'staff_only' => 'Solo para staff (admin, manager, staff)'
        ];
    }

    /**
     * Check if service is visible to specific user type
     */
    public function isVisibleToUser($serviceId, $userType)
    {
        $service = $this->find($serviceId);
        if (!$service) {
            return false;
        }
        
        // Staff can see all services
        if (in_array($userType, ['admin', 'manager', 'staff'])) {
            return true;
        }
        
        // Clients can only see services with 'all' visibility
        if ($userType === 'client') {
            return $service['visibility_type'] === 'all';
        }
        
        return false;
    }
} 