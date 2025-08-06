<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffPermissionModel extends Model
{
    protected $table         = 'staff_permissions';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'slug', 'description', 'category', 'is_active'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    // Validation
    protected $validationRules = [
        'name'        => 'required|min_length[3]|max_length[100]',
        'slug'        => 'required|min_length[3]|max_length[100]|is_unique[staff_permissions.slug,id,{id}]',
        'description' => 'permit_empty|max_length[500]',
        'category'    => 'required|max_length[50]',
        'is_active'   => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Permission name is required'
        ],
        'slug' => [
            'required' => 'Permission slug is required',
            'is_unique' => 'Permission slug already exists'
        ],
        'category' => [
            'required' => 'Permission category is required'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get all active permissions
     *
     * @return array
     */
    public function getActivePermissions()
    {
        return $this->where('is_active', 1)
                   ->orderBy('category', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get permissions by category
     *
     * @param string $category
     * @return array
     */
    public function getPermissionsByCategory($category)
    {
        return $this->where('category', $category)
                   ->where('is_active', 1)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get permissions grouped by category
     *
     * @return array
     */
    public function getPermissionsGroupedByCategory()
    {
        $permissions = $this->getActivePermissions();
        $grouped = [];
        
        foreach ($permissions as $permission) {
            $category = $permission['category'];
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $permission;
        }
        
        return $grouped;
    }
    
    /**
     * Get permissions for a specific role
     *
     * @param int $roleId
     * @return array
     */
    public function getPermissionsByRole($roleId)
    {
        return $this->select('staff_permissions.*')
                   ->join('staff_role_permissions', 'staff_role_permissions.permission_id = staff_permissions.id')
                   ->where('staff_role_permissions.role_id', $roleId)
                   ->where('staff_permissions.is_active', 1)
                   ->orderBy('staff_permissions.category', 'ASC')
                   ->orderBy('staff_permissions.name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get permission by slug
     *
     * @param string $slug
     * @return array|null
     */
    public function getPermissionBySlug($slug)
    {
        return $this->where('slug', $slug)
                   ->where('is_active', 1)
                   ->first();
    }
    
    /**
     * Get all categories
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->select('category')
                   ->distinct()
                   ->where('is_active', 1)
                   ->orderBy('category', 'ASC')
                   ->findColumn('category');
    }
    
    /**
     * Check if permission exists by slug
     *
     * @param string $slug
     * @return bool
     */
    public function permissionExists($slug)
    {
        return $this->where('slug', $slug)->countAllResults() > 0;
    }
    
    /**
     * Get permission statistics
     *
     * @return array
     */
    public function getPermissionStats()
    {
        $total = $this->countAll();
        $active = $this->where('is_active', 1)->countAllResults();
        $categories = count($this->getCategories());
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $total - $active,
            'categories' => $categories
        ];
    }
    
    /**
     * Search permissions by name or description
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchPermissions($searchTerm)
    {
        return $this->groupStart()
                   ->like('name', $searchTerm)
                   ->orLike('description', $searchTerm)
                   ->orLike('slug', $searchTerm)
                   ->groupEnd()
                   ->where('is_active', 1)
                   ->orderBy('category', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get permissions that are not assigned to any role
     *
     * @return array
     */
    public function getUnassignedPermissions()
    {
        return $this->select('staff_permissions.*')
                   ->join('staff_role_permissions', 'staff_role_permissions.permission_id = staff_permissions.id', 'left')
                   ->where('staff_role_permissions.permission_id IS NULL')
                   ->where('staff_permissions.is_active', 1)
                   ->orderBy('staff_permissions.category', 'ASC')
                   ->orderBy('staff_permissions.name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get roles that have a specific permission
     *
     * @param int $permissionId
     * @return array
     */
    public function getRolesForPermission($permissionId)
    {
        $roleModel = new StaffRoleModel();
        
        return $roleModel->select('staff_roles.*')
                        ->join('staff_role_permissions', 'staff_role_permissions.role_id = staff_roles.id')
                        ->where('staff_role_permissions.permission_id', $permissionId)
                        ->where('staff_roles.is_active', 1)
                        ->orderBy('staff_roles.level', 'DESC')
                        ->orderBy('staff_roles.sort_order', 'ASC')
                        ->findAll();
    }
    
    /**
     * Get most used permissions (by role assignments)
     *
     * @param int $limit
     * @return array
     */
    public function getMostUsedPermissions($limit = 10)
    {
        return $this->select('staff_permissions.*, COUNT(staff_role_permissions.role_id) as role_count')
                   ->join('staff_role_permissions', 'staff_role_permissions.permission_id = staff_permissions.id', 'left')
                   ->where('staff_permissions.is_active', 1)
                   ->groupBy('staff_permissions.id')
                   ->orderBy('role_count', 'DESC')
                   ->orderBy('staff_permissions.name', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Batch update permission status
     *
     * @param array $permissionIds
     * @param int $status
     * @return bool
     */
    public function updatePermissionStatus($permissionIds, $status)
    {
        if (empty($permissionIds) || !in_array($status, [0, 1])) {
            return false;
        }
        
        return $this->whereIn('id', $permissionIds)
                   ->set(['is_active' => $status])
                   ->update();
    }
    
    /**
     * Create permission with auto-generated slug
     *
     * @param array $data
     * @return int|bool
     */
    public function createPermission($data)
    {
        // Auto-generate slug from name if not provided
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = url_title(strtolower($data['name']), '_');
            
            // Ensure uniqueness
            $counter = 1;
            $originalSlug = $data['slug'];
            while ($this->permissionExists($data['slug'])) {
                $data['slug'] = $originalSlug . '_' . $counter;
                $counter++;
            }
        }
        
        return $this->insert($data);
    }
} 