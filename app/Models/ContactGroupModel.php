<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactGroupModel extends Model
{
    protected $table         = 'contact_groups';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'name', 'description', 'color', 'icon', 'is_active', 
        'sort_order', 'created_by'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'name'        => 'required|min_length[3]|max_length[100]|is_unique[contact_groups.name,id,{id}]',
        'description' => 'permit_empty|max_length[500]',
        'color'       => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
        'icon'        => 'permit_empty|max_length[50]',
        'is_active'   => 'permit_empty|in_list[0,1]',
        'sort_order'  => 'permit_empty|integer',
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Group name is required',
            'is_unique' => 'Group name already exists'
        ],
        'color' => [
            'regex_match' => 'Color must be a valid hex color (e.g., #3577f1)'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get all active groups ordered by sort_order
     *
     * @return array
     */
    public function getActiveGroups()
    {
        return $this->where('is_active', 1)
                   ->orderBy('sort_order', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get group with permissions
     *
     * @param int $groupId
     * @return array|null
     */
    public function getGroupWithPermissions($groupId)
    {
        $group = $this->find($groupId);
        if (!$group) {
            return null;
        }
        
        $permissionModel = new ContactPermissionModel();
        $group['permissions'] = $permissionModel->getPermissionsByGroup($groupId);
        
        return $group;
    }
    
    /**
     * Get groups with user count
     *
     * @return array
     */
    public function getGroupsWithUserCount()
    {
        return $this->select('contact_groups.*, COUNT(user_contact_groups.user_id) as user_count')
                   ->join('user_contact_groups', 'user_contact_groups.group_id = contact_groups.id', 'left')
                   ->groupBy('contact_groups.id')
                   ->orderBy('contact_groups.sort_order', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get user count for a specific group
     *
     * @param int $groupId
     * @return int
     */
    public function getGroupUserCount($groupId)
    {
        return $this->db->table('user_contact_groups')
                       ->where('group_id', $groupId)
                       ->countAllResults();
    }
    
    /**
     * Get users in a group
     *
     * @param int $groupId
     * @return array
     */
    public function getGroupUsers($groupId)
    {
        $userModel = new UserModel();
        
        return $userModel->select('users.*, user_contact_groups.assigned_at, user_contact_groups.assigned_by, 
                                  clients.name as client_name, auth_identities.secret as email')
                        ->join('user_contact_groups', 'user_contact_groups.user_id = users.id')
                        ->join('clients', 'clients.id = users.client_id', 'left')
                        ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                        ->where('user_contact_groups.group_id', $groupId)
                        ->where('users.user_type', 'client')
                        ->orderBy('users.first_name', 'ASC')
                        ->findAll();
    }
    
    /**
     * Assign user to group
     *
     * @param int $userId
     * @param int $groupId
     * @param int|null $assignedBy
     * @return bool
     */
    public function assignUserToGroup($userId, $groupId, $assignedBy = null)
    {
        $data = [
            'user_id' => $userId,
            'group_id' => $groupId,
            'assigned_by' => $assignedBy,
            'assigned_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Check if already assigned
        $existing = $this->db->table('user_contact_groups')
                           ->where('user_id', $userId)
                           ->where('group_id', $groupId)
                           ->get()
                           ->getRowArray();
        
        if ($existing) {
            return true; // Already assigned
        }
        
        return $this->db->table('user_contact_groups')->insert($data);
    }
    
    /**
     * Remove user from group
     *
     * @param int $userId
     * @param int $groupId
     * @return bool
     */
    public function removeUserFromGroup($userId, $groupId)
    {
        return $this->db->table('user_contact_groups')
                       ->where('user_id', $userId)
                       ->where('group_id', $groupId)
                       ->delete();
    }
    
    /**
     * Assign permissions to group
     *
     * @param int $groupId
     * @param array $permissionIds
     * @return bool
     */
    public function assignPermissions($groupId, $permissionIds)
    {
        // First, remove existing permissions
        $this->db->table('contact_group_permissions')
                ->where('group_id', $groupId)
                ->delete();
        
        // Then add new permissions
        if (!empty($permissionIds)) {
            $data = [];
            foreach ($permissionIds as $permissionId) {
                $data[] = [
                    'group_id' => $groupId,
                    'permission_id' => $permissionId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            
            return $this->db->table('contact_group_permissions')->insertBatch($data);
        }
        
        return true;
    }
    
    /**
     * Get user's groups
     *
     * @param int $userId
     * @return array
     */
    public function getUserGroups($userId)
    {
        return $this->select('contact_groups.*')
                   ->join('user_contact_groups', 'user_contact_groups.group_id = contact_groups.id')
                   ->where('user_contact_groups.user_id', $userId)
                   ->where('contact_groups.is_active', 1)
                   ->orderBy('contact_groups.sort_order', 'ASC')
                   ->findAll();
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
        $result = $this->db->table('contact_permissions')
                          ->select('contact_permissions.id')
                          ->join('contact_group_permissions', 'contact_group_permissions.permission_id = contact_permissions.id')
                          ->join('user_contact_groups', 'user_contact_groups.group_id = contact_group_permissions.group_id')
                          ->where('user_contact_groups.user_id', $userId)
                          ->where('contact_permissions.slug', $permissionSlug)
                          ->where('contact_permissions.is_active', 1)
                          ->get()
                          ->getRowArray();
        
        return !empty($result);
    }
    
    /**
     * Get user's permissions
     *
     * @param int $userId
     * @return array
     */
    public function getUserPermissions($userId)
    {
        return $this->db->table('contact_permissions')
                       ->select('contact_permissions.*')
                       ->join('contact_group_permissions', 'contact_group_permissions.permission_id = contact_permissions.id')
                       ->join('user_contact_groups', 'user_contact_groups.group_id = contact_group_permissions.group_id')
                       ->where('user_contact_groups.user_id', $userId)
                       ->where('contact_permissions.is_active', 1)
                       ->orderBy('contact_permissions.category', 'ASC')
                       ->orderBy('contact_permissions.name', 'ASC')
                       ->get()
                       ->getResultArray();
    }
    
    /**
     * Update sort order for multiple groups
     *
     * @param array $groupOrders [['id' => 1, 'sort_order' => 1], ...]
     * @return bool
     */
    public function updateSortOrders($groupOrders)
    {
        $this->db->transStart();
        
        foreach ($groupOrders as $order) {
            $this->update($order['id'], ['sort_order' => $order['sort_order']]);
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }
} 