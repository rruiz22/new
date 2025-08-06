<?php

namespace Modules\ReconOrders\Models;

use CodeIgniter\Model;

class ReconServiceModel extends Model
{
    protected $table = 'recon_services';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'description', 'price', 'duration', 'category', 'color', 'client_id',
        'is_active', 'visibility_type', 'show_in_form', 'sort_order', 'created_by', 'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
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
        log_message('debug', 'Recon Service Model - beforeInsert called with data: ' . json_encode($data));
        
        // Set default values for fields not in the form
        if (!isset($data['data']['duration'])) {
            $data['data']['duration'] = 60; // Default 60 minutes
        }
        if (!isset($data['data']['category'])) {
            $data['data']['category'] = 'inspection'; // Default category
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
        if (!isset($data['data']['show_in_form'])) {
            $data['data']['show_in_form'] = 1; // Default show in form
        }
        
        // Set created_by
        if (!isset($data['data']['created_by'])) {
            $data['data']['created_by'] = auth()->user()->id ?? session()->get('user_id') ?? 1;
        }
        
        log_message('debug', 'Recon Service Model - beforeInsert final data: ' . json_encode($data));
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        // Set updated_by
        if (!isset($data['data']['updated_by'])) {
            $data['data']['updated_by'] = auth()->user()->id ?? session()->get('user_id') ?? 1;
        }
        
        return $data;
    }

    public function getServicesWithDetails($filters = [])
    {
        $builder = $this->select('
            recon_services.*,
            clients.name as client_name,
            creator.first_name as creator_first_name,
            creator.last_name as creator_last_name
        ')
        ->join('clients', 'clients.id = recon_services.client_id', 'left')
        ->join('users as creator', 'creator.id = recon_services.created_by', 'left');

        // Apply filters
        if (!empty($filters['category'])) {
            $builder->where('recon_services.category', $filters['category']);
        }

        if (!empty($filters['client_id'])) {
            $builder->where('recon_services.client_id', $filters['client_id']);
        }

        if (isset($filters['is_active'])) {
            $builder->where('recon_services.is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('recon_services.name', $filters['search'])
                    ->orLike('recon_services.description', $filters['search'])
                    ->orLike('clients.name', $filters['search'])
                    ->groupEnd();
        }

        return $builder->orderBy('recon_services.sort_order', 'ASC')
                       ->orderBy('recon_services.name', 'ASC');
    }

    public function getActiveServices($clientId = null, $userType = null)
    {
        $builder = $this->where('is_active', 1)
                        ->where('show_in_form', 1);
        
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
                        ->where('is_active', 1)
                        ->where('show_in_form', 1);
        
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
            'inspection' => 'Inspection',
            'repair' => 'Repair',
            'maintenance' => 'Maintenance',
            'detailing' => 'Detailing',
            'additional' => 'Additional Services'
        ];
    }

    public function getPopularServices($limit = 10)
    {
        $db = \Config\Database::connect();
        return $db->table('recon_services')
                  ->select('recon_services.*, COUNT(recon_order_services.service_id) as usage_count')
                  ->join('recon_order_services', 'recon_order_services.service_id = recon_services.id', 'left')
                  ->where('recon_services.is_active', 1)
                  ->groupBy('recon_services.id')
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
        $builder = $this->where('is_active', 1)
                        ->where('show_in_form', 1);
        
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
     * Get service with order count
     */
    public function getServiceWithOrderCount($id)
    {
        $db = \Config\Database::connect();
        return $db->table('recon_services')
                  ->select('recon_services.*, COUNT(recon_order_services.service_id) as order_count')
                  ->join('recon_order_services', 'recon_order_services.service_id = recon_services.id', 'left')
                  ->where('recon_services.id', $id)
                  ->groupBy('recon_services.id')
                  ->get()
                  ->getFirstRow('array');
    }

    /**
     * Get service statistics
     */
    public function getServiceStats()
    {
        $db = \Config\Database::connect();
        
        $total = $this->countAll();
        $active = $this->where('is_active', 1)->countAllResults();
        $inactive = $total - $active;
        
        $byCategory = $db->table('recon_services')
                         ->select('category, COUNT(*) as count')
                         ->groupBy('category')
                         ->get()
                         ->getResultArray();
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'by_category' => $byCategory
        ];
    }

    /**
     * Get recently created services
     */
    public function getRecentServices($limit = 5)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Delete service if it's not being used
     */
    public function safeDelete($id)
    {
        // Check if service is being used
        $db = \Config\Database::connect();
        $usage = $db->table('recon_order_services')
                    ->where('service_id', $id)
                    ->countAllResults();
        
        if ($usage > 0) {
            return ['success' => false, 'message' => 'Cannot delete service that is being used in orders'];
        }
        
        return ['success' => $this->delete($id), 'message' => 'Service deleted successfully'];
    }
} 