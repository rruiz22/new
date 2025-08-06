<?php

namespace App\Controllers;

use App\Models\ContactGroupModel;
use App\Models\ContactPermissionModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ContactGroupController extends BaseController
{
    protected $groupModel;
    protected $permissionModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->groupModel = new ContactGroupModel();
        $this->permissionModel = new ContactPermissionModel();
        $this->userModel = new UserModel();
        
        // Load form helper for all methods
        helper(['form', 'url']);
    }
    
    /**
     * Display a listing of contact groups
     *
     * @return string
     */
    public function index()
    {
        $groups = $this->groupModel->getGroupsWithUserCount();
        
        $data = [
            'title' => 'Contact Groups Management',
            'groups' => $groups,
        ];
        
        return view('contact_groups/index', $data);
    }
    
    /**
     * Show the form for creating a new group
     *
     * @return string
     */
    public function create()
    {
        $permissions = $this->permissionModel->getPermissionsGroupedByCategory();
        
        $data = [
            'title' => 'Create Contact Group',
            'permissions' => $permissions,
        ];
        
        return view('contact_groups/create', $data);
    }
    
    /**
     * Store a newly created group
     *
     * @return ResponseInterface
     */
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]|is_unique[contact_groups.name]',
            'description' => 'permit_empty|max_length[500]',
            'color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            'icon' => 'permit_empty|max_length[50]',
            'sort_order' => 'permit_empty|integer',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $groupData = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'color' => $this->request->getPost('color') ?: '#3577f1',
            'icon' => $this->request->getPost('icon') ?: 'users',
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => 1,
            'created_by' => session()->get('user_id'),
        ];
        
        $groupId = $this->groupModel->insert($groupData);
        
        if ($groupId) {
            // Assign permissions if provided
            $permissions = $this->request->getPost('permissions') ?: [];
            if (!empty($permissions)) {
                $this->groupModel->assignPermissions($groupId, $permissions);
            }
            
            session()->setFlashdata('success', 'Contact group created successfully.');
            return redirect()->to('contact-groups');
        } else {
            session()->setFlashdata('error', 'Failed to create contact group.');
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Display the specified group
     *
     * @param int $id
     * @return string
     */
    public function show($id = null)
    {
        if (!$id) {
            session()->setFlashdata('error', 'Group not found.');
            return redirect()->to('contact-groups');
        }
        
        $group = $this->groupModel->getGroupWithPermissions($id);
        if (!$group) {
            session()->setFlashdata('error', 'Group not found.');
            return redirect()->to('contact-groups');
        }
        
        $users = $this->groupModel->getGroupUsers($id);
        
        $data = [
            'title' => 'Group Details - ' . $group['name'],
            'group' => $group,
            'users' => $users,
        ];
        
        return view('contact_groups/show', $data);
    }
    
    /**
     * Show the form for editing the specified group
     *
     * @param int $id
     * @return string
     */
    public function edit($id = null)
    {
        if (!$id) {
            session()->setFlashdata('error', 'Group not found.');
            return redirect()->to('contact-groups');
        }
        
        $group = $this->groupModel->getGroupWithPermissions($id);
        if (!$group) {
            session()->setFlashdata('error', 'Group not found.');
            return redirect()->to('contact-groups');
        }
        
        $permissions_by_category = $this->permissionModel->getPermissionsGroupedByCategory();
        $group_permissions = array_column($group['permissions'] ?? [], 'id');
        
        // Add user count
        $group['user_count'] = $this->groupModel->getGroupUserCount($id);
        
        $data = [
            'title' => 'Edit Group - ' . $group['name'],
            'group' => $group,
            'permissions_by_category' => $permissions_by_category,
            'group_permissions' => $group_permissions,
        ];
        
        return view('contact_groups/edit', $data);
    }
    
    /**
     * Update the specified group
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        if (!$id) {
            session()->setFlashdata('error', 'Group not found.');
            return redirect()->to('contact-groups');
        }
        
        $group = $this->groupModel->find($id);
        if (!$group) {
            session()->setFlashdata('error', 'Group not found.');
            return redirect()->to('contact-groups');
        }
        
        $rules = [
            'name' => "required|min_length[3]|max_length[100]|is_unique[contact_groups.name,id,{$id}]",
            'description' => 'permit_empty|max_length[500]',
            'color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            'icon' => 'permit_empty|max_length[50]',
            'sort_order' => 'permit_empty|integer',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $groupData = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'color' => $this->request->getPost('color') ?: '#3577f1',
            'icon' => $this->request->getPost('icon') ?: 'users',
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];
        
        if ($this->groupModel->update($id, $groupData)) {
            // Update permissions
            $permissions = $this->request->getPost('permissions') ?: [];
            $this->groupModel->assignPermissions($id, $permissions);
            
            session()->setFlashdata('success', 'Contact group updated successfully.');
            return redirect()->to("contact-groups/{$id}");
        } else {
            session()->setFlashdata('error', 'Failed to update contact group.');
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Remove the specified group
     *
     * @return ResponseInterface
     */
    public function delete()
    {
        $id = $this->request->getPost('id');
        
        if (!$id) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid group ID'
            ]);
        }
        
        $group = $this->groupModel->find($id);
        if (!$group) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Group not found'
            ]);
        }
        
        // Check if group has users assigned
        $users = $this->groupModel->getGroupUsers($id);
        if (!empty($users)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Cannot delete group with assigned users. Please reassign users first.'
            ]);
        }
        
        if ($this->groupModel->delete($id)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Group deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to delete group'
            ]);
        }
    }
    
    /**
     * Assign user to group
     *
     * @return ResponseInterface
     */
    public function assignUser()
    {
        $userId = $this->request->getPost('user_id');
        $groupId = $this->request->getPost('group_id');
        
        if (!$userId || !$groupId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User ID and Group ID are required'
            ]);
        }
        
        // Verify user exists and is a client
        $user = $this->userModel->find($userId);
        if (!$user || $user['user_type'] !== 'client') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid user or user is not a client'
            ]);
        }
        
        // Verify group exists
        $group = $this->groupModel->find($groupId);
        if (!$group) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Group not found'
            ]);
        }
        
        if ($this->groupModel->assignUserToGroup($userId, $groupId, session()->get('user_id'))) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'User assigned to group successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to assign user to group'
            ]);
        }
    }
    
    /**
     * Remove user from group
     *
     * @return ResponseInterface
     */
    public function removeUser()
    {
        $userId = $this->request->getPost('user_id');
        $groupId = $this->request->getPost('group_id');
        
        if (!$userId || !$groupId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User ID and Group ID are required'
            ]);
        }
        
        if ($this->groupModel->removeUserFromGroup($userId, $groupId)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'User removed from group successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to remove user from group'
            ]);
        }
    }
    
    /**
     * Get users for a specific group (AJAX)
     *
     * @return ResponseInterface
     */
    public function getGroupUsers()
    {
        $groupId = $this->request->getGet('group_id');
        
        if (!$groupId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Group ID is required'
            ]);
        }
        
        $users = $this->groupModel->getGroupUsers($groupId);
        
        return $this->response->setJSON([
            'status' => true,
            'data' => $users
        ]);
    }
    
    /**
     * Get available users (not in group) for assignment
     *
     * @return ResponseInterface
     */
    public function getAvailableUsers()
    {
        $groupId = $this->request->getGet('group_id');
        
        $query = $this->userModel->select('users.id, users.first_name, users.last_name, users.username, clients.name as client_name')
                                ->join('clients', 'clients.id = users.client_id', 'left')
                                ->where('users.user_type', 'client')
                                ->where('users.active', 1);
        
        if ($groupId) {
            // Exclude users already in this group using a LEFT JOIN approach
            $query->join('user_contact_groups ucg', 'ucg.user_id = users.id AND ucg.group_id = ' . (int)$groupId, 'left')
                  ->where('ucg.user_id IS NULL');
        }
        
        $users = $query->orderBy('users.first_name', 'ASC')->findAll();
        
        return $this->response->setJSON([
            'status' => true,
            'data' => $users
        ]);
    }
    
    /**
     * Update group sort orders
     *
     * @return ResponseInterface
     */
    public function updateSortOrder()
    {
        $orders = $this->request->getPost('orders');
        
        if (!$orders || !is_array($orders)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid sort order data'
            ]);
        }
        
        if ($this->groupModel->updateSortOrders($orders)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Sort order updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to update sort order'
            ]);
        }
    }
    
    /**
     * Get user's groups (AJAX)
     *
     * @return ResponseInterface
     */
    public function getUserGroups()
    {
        $userId = $this->request->getGet('user_id');
        
        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User ID is required'
            ]);
        }
        
        $groups = $this->groupModel->getUserGroups($userId);
        
        return $this->response->setJSON([
            'status' => true,
            'data' => $groups
        ]);
    }
    
    /**
     * Get available groups for user assignment (excluding already assigned)
     *
     * @return ResponseInterface
     */
    public function getAvailableGroups()
    {
        $userId = $this->request->getGet('user_id');
        
        $query = $this->groupModel->select('contact_groups.*')
                                 ->where('contact_groups.is_active', 1);
        
        if ($userId) {
            // Exclude groups already assigned to this user using a LEFT JOIN approach
            $query->join('user_contact_groups ucg2', 'ucg2.group_id = contact_groups.id AND ucg2.user_id = ' . (int)$userId, 'left')
                  ->where('ucg2.group_id IS NULL');
        }
        
        $groups = $query->orderBy('contact_groups.sort_order', 'ASC')
                       ->orderBy('contact_groups.name', 'ASC')
                       ->findAll();
        
        return $this->response->setJSON([
            'status' => true,
            'data' => $groups
        ]);
    }
} 