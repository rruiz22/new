<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffRoleModel extends Model
{
    protected $table         = 'staff_roles';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'name', 'description', 'color', 'icon', 'is_active', 
        'sort_order', 'level', 'created_by'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'name'        => 'required|min_length[3]|max_length[100]|is_unique[staff_roles.name,id,{id}]',
        'description' => 'permit_empty|max_length[500]',
        'color'       => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
        'icon'        => 'permit_empty|max_length[50]',
        'is_active'   => 'permit_empty|in_list[0,1]',
        'sort_order'  => 'permit_empty|integer',
        'level'       => 'permit_empty|integer|greater_than[0]|less_than_equal_to[10]',
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Role name is required',
            'is_unique' => 'Role name already exists'
        ],
        'color' => [
            'regex_match' => 'Color must be a valid hex color (e.g., #3577f1)'
        ],
        'level' => [
            'greater_than' => 'Level must be greater than 0',
            'less_than_equal_to' => 'Level must be 10 or less'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get all active roles ordered by level and sort_order
     *
     * @return array
     */
    public function getActiveRoles()
    {
        return $this->where('is_active', 1)
                   ->orderBy('level', 'DESC')
                   ->orderBy('sort_order', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get role with permissions
     *
     * @param int $roleId
     * @return array|null
     */
    public function getRoleWithPermissions($roleId)
    {
        $role = $this->find($roleId);
        if (!$role) {
            return null;
        }
        
        $permissionModel = new StaffPermissionModel();
        $role['permissions'] = $permissionModel->getPermissionsByRole($roleId);
        
        return $role;
    }
    
    /**
     * Get roles with user count
     *
     * @return array
     */
    public function getRolesWithUserCount()
    {
        return $this->select('staff_roles.*, COUNT(user_staff_roles.user_id) as user_count')
                   ->join('user_staff_roles', 'user_staff_roles.role_id = staff_roles.id', 'left')
                   ->groupBy('staff_roles.id')
                   ->orderBy('staff_roles.level', 'DESC')
                   ->orderBy('staff_roles.sort_order', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get user count for a specific role
     *
     * @param int $roleId
     * @return int
     */
    public function getRoleUserCount($roleId)
    {
        return $this->db->table('user_staff_roles')
                       ->where('role_id', $roleId)
                       ->countAllResults();
    }
    
    /**
     * Get users in a role
     *
     * @param int $roleId
     * @return array
     */
    public function getRoleUsers($roleId)
    {
        $userModel = new UserModel();
        
        return $userModel->select('users.*, user_staff_roles.assigned_at, user_staff_roles.assigned_by, 
                                  auth_identities.secret as email')
                        ->join('user_staff_roles', 'user_staff_roles.user_id = users.id')
                        ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                        ->where('user_staff_roles.role_id', $roleId)
                        ->where('users.user_type', 'staff')
                        ->orderBy('users.first_name', 'ASC')
                        ->findAll();
    }
    
    /**
     * Assign user to role
     *
     * @param int $userId
     * @param int $roleId
     * @param int|null $assignedBy
     * @return bool
     */
    public function assignUserToRole($userId, $roleId, $assignedBy = null)
    {
        $data = [
            'user_id' => $userId,
            'role_id' => $roleId,
            'assigned_by' => $assignedBy,
            'assigned_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Check if already assigned
        $existing = $this->db->table('user_staff_roles')
                           ->where('user_id', $userId)
                           ->where('role_id', $roleId)
                           ->get()
                           ->getRowArray();
        
        if ($existing) {
            return true; // Already assigned
        }
        
        return $this->db->table('user_staff_roles')->insert($data);
    }
    
    /**
     * Remove user from role
     *
     * @param int $userId
     * @param int $roleId
     * @return bool
     */
    public function removeUserFromRole($userId, $roleId)
    {
        return $this->db->table('user_staff_roles')
                       ->where('user_id', $userId)
                       ->where('role_id', $roleId)
                       ->delete();
    }
    
    /**
     * Remove all roles from user (useful for role reassignment)
     *
     * @param int $userId
     * @return bool
     */
    public function removeAllUserRoles($userId)
    {
        return $this->db->table('user_staff_roles')
                       ->where('user_id', $userId)
                       ->delete();
    }
    
    /**
     * Assign permissions to role
     *
     * @param int $roleId
     * @param array $permissionIds
     * @return bool
     */
    public function assignPermissions($roleId, $permissionIds)
    {
        // First, remove existing permissions
        $this->db->table('staff_role_permissions')
                ->where('role_id', $roleId)
                ->delete();
        
        // Then add new permissions
        if (!empty($permissionIds)) {
            $data = [];
            foreach ($permissionIds as $permissionId) {
                $data[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            
            return $this->db->table('staff_role_permissions')->insertBatch($data);
        }
        
        return true;
    }
    
    /**
     * Get user's roles
     *
     * @param int $userId
     * @return array
     */
    public function getUserRoles($userId)
    {
        return $this->select('staff_roles.*')
                   ->join('user_staff_roles', 'user_staff_roles.role_id = staff_roles.id')
                   ->where('user_staff_roles.user_id', $userId)
                   ->where('staff_roles.is_active', 1)
                   ->orderBy('staff_roles.level', 'DESC')
                   ->orderBy('staff_roles.sort_order', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get user's highest role (by level)
     *
     * @param int $userId
     * @return array|null
     */
    public function getUserHighestRole($userId)
    {
        return $this->select('staff_roles.*')
                   ->join('user_staff_roles', 'user_staff_roles.role_id = staff_roles.id')
                   ->where('user_staff_roles.user_id', $userId)
                   ->where('staff_roles.is_active', 1)
                   ->orderBy('staff_roles.level', 'DESC')
                   ->first();
    }
    
    /**
     * Check if user has permission
     *
     * @param int $userId
     * @param string $permissionSlug
     * @return bool
     */
    public function userHasPermission($userId, $permissionSlug)
    {
        $result = $this->db->table('staff_permissions')
                          ->select('staff_permissions.id')
                          ->join('staff_role_permissions', 'staff_role_permissions.permission_id = staff_permissions.id')
                          ->join('user_staff_roles', 'user_staff_roles.role_id = staff_role_permissions.role_id')
                          ->where('user_staff_roles.user_id', $userId)
                          ->where('staff_permissions.slug', $permissionSlug)
                          ->where('staff_permissions.is_active', 1)
                          ->get()
                          ->getRowArray();
        
        return !empty($result);
    }
    
    /**
     * Check if user has super admin permission
     *
     * @param int $userId
     * @return bool
     */
    public function userIsSuperAdmin($userId)
    {
        return $this->userHasPermission($userId, 'super_admin');
    }
    
    /**
     * Get user's permissions
     *
     * @param int $userId
     * @return array
     */
    public function getUserPermissions($userId)
    {
        return $this->db->table('staff_permissions')
                       ->select('staff_permissions.*')
                       ->join('staff_role_permissions', 'staff_role_permissions.permission_id = staff_permissions.id')
                       ->join('user_staff_roles', 'user_staff_roles.role_id = staff_role_permissions.role_id')
                       ->where('user_staff_roles.user_id', $userId)
                       ->where('staff_permissions.is_active', 1)
                       ->orderBy('staff_permissions.category', 'ASC')
                       ->orderBy('staff_permissions.name', 'ASC')
                       ->get()
                       ->getResultArray();
    }
    
    /**
     * Get users available for role assignment (staff users not in this role)
     *
     * @param int|null $roleId
     * @return array
     */
    public function getAvailableUsers($roleId = null)
    {
        $userModel = new UserModel();
        
        $query = $userModel->select('users.id, users.first_name, users.last_name, users.username, 
                                    auth_identities.secret as email')
                          ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                          ->where('users.user_type', 'staff')
                          ->where('users.active', 1);
        
        if ($roleId) {
            // Exclude users already in this role
            $query->join('user_staff_roles usr', 'usr.user_id = users.id AND usr.role_id = ' . (int)$roleId, 'left')
                  ->where('usr.user_id IS NULL');
        }
        
        return $query->orderBy('users.first_name', 'ASC')->findAll();
    }
    
    /**
     * Update sort order for multiple roles
     *
     * @param array $roleOrders [['id' => 1, 'sort_order' => 1], ...]
     * @return bool
     */
    public function updateSortOrders($roleOrders)
    {
        $this->db->transStart();
        
        foreach ($roleOrders as $order) {
            $this->update($order['id'], ['sort_order' => $order['sort_order']]);
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }
    
    /**
     * Check if user can manage other user based on role hierarchy
     *
     * @param int $managerId
     * @param int $targetUserId
     * @return bool
     */
    public function canManageUser($managerId, $targetUserId)
    {
        $managerRole = $this->getUserHighestRole($managerId);
        $targetRole = $this->getUserHighestRole($targetUserId);
        
        // Super admin can manage anyone
        if ($this->userIsSuperAdmin($managerId)) {
            return true;
        }
        
        // Can't manage users with same or higher level roles
        if (!$managerRole || !$targetRole) {
            return false;
        }
        
        return $managerRole['level'] > $targetRole['level'];
    }
} 