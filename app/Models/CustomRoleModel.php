<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomRoleModel extends Model
{
    protected $table = 'custom_roles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'title', 
        'description',
        'permissions',
        'is_active',
        'show_in_staff_form',
        'color',
        'sort_order'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|alpha_dash|max_length[50]|is_unique[custom_roles.name,id,{id}]',
        'title' => 'required|max_length[100]',
        'description' => 'max_length[255]',
        'permissions' => 'permit_empty',
        'is_active' => 'in_list[0,1]',
        'show_in_staff_form' => 'in_list[0,1]',
        'color' => 'max_length[7]',
        'sort_order' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'The role name is required.',
            'alpha_dash' => 'The role name may only contain alpha-numeric characters, underscores, and dashes.',
            'is_unique' => 'This role name already exists.'
        ],
        'title' => [
            'required' => 'The role title is required.'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get all active roles
     */
    public function getActiveRoles()
    {
        return $this->where('is_active', 1)
                   ->orderBy('sort_order', 'ASC')
                   ->orderBy('title', 'ASC')
                   ->findAll();
    }

    /**
     * Get roles that can be shown in staff form
     */
    public function getStaffFormRoles()
    {
        return $this->where('is_active', 1)
                   ->where('show_in_staff_form', 1)
                   ->orderBy('sort_order', 'ASC')
                   ->orderBy('title', 'ASC')
                   ->findAll();
    }

    /**
     * Get role by name
     */
    public function getRoleByName($name)
    {
        return $this->where('name', $name)->first();
    }

    /**
     * Check if role name exists
     */
    public function roleTitleExists($title, $excludeId = null)
    {
        $builder = $this->where('title', $title);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Get roles with user count
     */
    public function getRolesWithUserCount()
    {
        return $this->select('custom_roles.*, COUNT(users.id) as user_count')
                   ->join('users', 'users.role_id = custom_roles.id', 'left')
                   ->where('custom_roles.deleted_at', null)
                   ->groupBy('custom_roles.id')
                   ->orderBy('custom_roles.sort_order', 'ASC')
                   ->orderBy('custom_roles.title', 'ASC')
                   ->findAll();
    }

    /**
     * Update sort order
     */
    public function updateSortOrder($id, $sortOrder)
    {
        return $this->update($id, ['sort_order' => $sortOrder]);
    }

    /**
     * Toggle active status
     */
    public function toggleActiveStatus($id)
    {
        $role = $this->find($id);
        if ($role) {
            return $this->update($id, ['is_active' => $role['is_active'] ? 0 : 1]);
        }
        return false;
    }

    /**
     * Toggle staff form visibility
     */
    public function toggleStaffFormVisibility($id)
    {
        $role = $this->find($id);
        if ($role) {
            return $this->update($id, ['show_in_staff_form' => $role['show_in_staff_form'] ? 0 : 1]);
        }
        return false;
    }

    /**
     * Custom update method that bypasses is_unique validation for the current ID
     */
    public function updateRole($id, $data)
    {
        // Store original validation rules
        $originalRules = $this->validationRules;
        
        // Remove is_unique from the name rule when updating
        $this->validationRules['name'] = 'required|alpha_dash|max_length[50]';
        
        // Update the role
        $result = $this->update($id, $data);
        
        // Restore original rules
        $this->validationRules = $originalRules;
        
        return $result;
    }

    /**
     * Get permissions array
     */
    public function getPermissionsArray($role)
    {
        if (is_array($role) && isset($role['permissions'])) {
            return !empty($role['permissions']) ? json_decode($role['permissions'], true) : [];
        }
        return [];
    }

    /**
     * Set permissions array
     */
    public function setPermissionsArray($permissions)
    {
        return json_encode($permissions ?: []);
    }

    /**
     * Check if role can be deleted
     */
    public function canBeDeleted($id)
    {
        // Check if role has users assigned
        $userModel = new \App\Models\CustomUserModel();
        $userCount = $userModel->where('role_id', $id)->countAllResults();
        
        return $userCount === 0;
    }
} 