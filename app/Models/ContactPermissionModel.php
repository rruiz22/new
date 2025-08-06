<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactPermissionModel extends Model
{
    protected $table         = 'contact_permissions';
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
        'slug'        => 'required|min_length[3]|max_length[100]|is_unique[contact_permissions.slug,id,{id}]',
        'description' => 'permit_empty|max_length[500]',
        'category'    => 'permit_empty|max_length[50]',
        'is_active'   => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Permission name is required'
        ],
        'slug' => [
            'required' => 'Permission slug is required',
            'is_unique' => 'Permission slug already exists'
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
     * Get permissions for a specific group
     *
     * @param int $groupId
     * @return array
     */
    public function getPermissionsByGroup($groupId)
    {
        return $this->select('contact_permissions.*')
                   ->join('contact_group_permissions', 'contact_group_permissions.permission_id = contact_permissions.id')
                   ->where('contact_group_permissions.group_id', $groupId)
                   ->where('contact_permissions.is_active', 1)
                   ->orderBy('contact_permissions.category', 'ASC')
                   ->orderBy('contact_permissions.name', 'ASC')
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
                   ->where('is_active', 1)
                   ->groupBy('category')
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
        $permission = $this->where('slug', $slug)->first();
        return !empty($permission);
    }
    
    /**
     * Create permission from array
     *
     * @param array $data
     * @return int|false
     */
    public function createPermission($data)
    {
        // Auto-generate slug if not provided
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        return $this->insert($data);
    }
    
    /**
     * Generate slug from name
     *
     * @param string $name
     * @return string
     */
    private function generateSlug($name)
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9]+/', '_', $slug);
        $slug = trim($slug, '_');
        
        // Ensure uniqueness
        $baseSlug = $slug;
        $counter = 1;
        while ($this->permissionExists($slug)) {
            $slug = $baseSlug . '_' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Get permissions with group assignments
     *
     * @return array
     */
    public function getPermissionsWithGroups()
    {
        $permissions = $this->getActivePermissions();
        
        foreach ($permissions as &$permission) {
            $permission['groups'] = $this->getGroupsForPermission($permission['id']);
        }
        
        return $permissions;
    }
    
    /**
     * Get groups that have a specific permission
     *
     * @param int $permissionId
     * @return array
     */
    public function getGroupsForPermission($permissionId)
    {
        $groupModel = new ContactGroupModel();
        
        return $groupModel->select('contact_groups.*')
                         ->join('contact_group_permissions', 'contact_group_permissions.group_id = contact_groups.id')
                         ->where('contact_group_permissions.permission_id', $permissionId)
                         ->where('contact_groups.is_active', 1)
                         ->orderBy('contact_groups.sort_order', 'ASC')
                         ->findAll();
    }
} 