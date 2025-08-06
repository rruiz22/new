<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StaffRoleModel;
use App\Models\StaffPermissionModel;
use App\Models\UserModel;

class StaffRoleController extends BaseController
{
    protected $staffRoleModel;
    protected $staffPermissionModel;
    protected $userModel;

    public function __construct()
    {
        $this->staffRoleModel = new StaffRoleModel();
        $this->staffPermissionModel = new StaffPermissionModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display staff roles list
     */
    public function index()
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'view_roles')) {
            return redirect()->to('/')->with('error', 'Access denied. You don\'t have permission to view roles.');
        }

        $data = [
            'title' => 'Staff Roles',
            'roles' => $this->staffRoleModel->getRolesWithUserCount(),
            'permissions' => $this->staffPermissionModel->getPermissionsGroupedByCategory()
        ];

        return view('staff/roles/index', $data);
    }

    /**
     * Show specific role details
     */
    public function show($id)
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'view_roles')) {
            return redirect()->to('/')->with('error', 'Access denied.');
        }

        $role = $this->staffRoleModel->getRoleWithPermissions($id);
        if (!$role) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Role not found');
        }

        $data = [
            'title' => 'Role: ' . $role['name'],
            'role' => $role,
            'users' => $this->staffRoleModel->getRoleUsers($id),
            'availableUsers' => $this->staffRoleModel->getAvailableUsers($id),
            'allPermissions' => $this->staffPermissionModel->getPermissionsGroupedByCategory()
        ];

        return view('staff/roles/show', $data);
    }

    /**
     * Show create role form
     */
    public function create()
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'manage_roles')) {
            return redirect()->to('/staff-roles')->with('error', 'Access denied.');
        }

        $data = [
            'title' => 'Create New Role',
            'permissions' => $this->staffPermissionModel->getPermissionsGroupedByCategory()
        ];

        return view('staff/roles/create', $data);
    }

    /**
     * Store new role
     */
    public function store()
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'manage_roles')) {
            return redirect()->to('/staff-roles')->with('error', 'Access denied.');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[100]|is_unique[staff_roles.name]',
            'description' => 'permit_empty|max_length[500]',
            'color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            'icon' => 'permit_empty|max_length[50]',
            'level' => 'required|integer|greater_than[0]|less_than_equal_to[10]',
            'sort_order' => 'permit_empty|integer',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'color' => $this->request->getPost('color') ?: '#3577f1',
            'icon' => $this->request->getPost('icon') ?: 'shield',
            'level' => $this->request->getPost('level'),
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'created_by' => auth()->user()->id
        ];

        if ($roleId = $this->staffRoleModel->insert($data)) {
            // Assign permissions if provided
            $permissions = $this->request->getPost('permissions') ?: [];
            if (!empty($permissions)) {
                $this->staffRoleModel->assignPermissions($roleId, $permissions);
            }

            return redirect()->to('/staff-roles/' . $roleId)->with('success', 'Role created successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create role.');
    }

    /**
     * Show edit role form
     */
    public function edit($id)
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'manage_roles')) {
            return redirect()->to('/staff-roles')->with('error', 'Access denied.');
        }

        $role = $this->staffRoleModel->getRoleWithPermissions($id);
        if (!$role) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Role not found');
        }

        // Check hierarchy - can't edit roles with higher or equal level
        $currentUserRole = $this->staffRoleModel->getUserHighestRole(auth()->user()->id);
        if (!$this->staffRoleModel->userIsSuperAdmin(auth()->user()->id) && 
            $currentUserRole && $role['level'] >= $currentUserRole['level']) {
            return redirect()->to('/staff-roles')->with('error', 'You cannot edit roles at your level or higher.');
        }

        $data = [
            'title' => 'Edit Role: ' . $role['name'],
            'role' => $role,
            'permissions' => $this->staffPermissionModel->getPermissionsGroupedByCategory(),
            'rolePermissionIds' => array_column($role['permissions'], 'id')
        ];

        return view('staff/roles/edit', $data);
    }

    /**
     * Update role
     */
    public function update($id)
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'manage_roles')) {
            return redirect()->to('/staff-roles')->with('error', 'Access denied.');
        }

        $role = $this->staffRoleModel->find($id);
        if (!$role) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Role not found');
        }

        // Check hierarchy
        $currentUserRole = $this->staffRoleModel->getUserHighestRole(auth()->user()->id);
        if (!$this->staffRoleModel->userIsSuperAdmin(auth()->user()->id) && 
            $currentUserRole && $role['level'] >= $currentUserRole['level']) {
            return redirect()->to('/staff-roles')->with('error', 'You cannot edit roles at your level or higher.');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => "required|min_length[3]|max_length[100]|is_unique[staff_roles.name,id,{$id}]",
            'description' => 'permit_empty|max_length[500]',
            'color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            'icon' => 'permit_empty|max_length[50]',
            'level' => 'required|integer|greater_than[0]|less_than_equal_to[10]',
            'sort_order' => 'permit_empty|integer',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'color' => $this->request->getPost('color') ?: '#3577f1',
            'icon' => $this->request->getPost('icon') ?: 'shield',
            'level' => $this->request->getPost('level'),
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->staffRoleModel->update($id, $data)) {
            // Update permissions
            $permissions = $this->request->getPost('permissions') ?: [];
            $this->staffRoleModel->assignPermissions($id, $permissions);

            return redirect()->to('/staff-roles/' . $id)->with('success', 'Role updated successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update role.');
    }

    /**
     * Delete role
     */
    public function delete($id)
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'manage_roles')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $role = $this->staffRoleModel->find($id);
        if (!$role) {
            return $this->response->setJSON(['success' => false, 'message' => 'Role not found.']);
        }

        // Check hierarchy
        $currentUserRole = $this->staffRoleModel->getUserHighestRole(auth()->user()->id);
        if (!$this->staffRoleModel->userIsSuperAdmin(auth()->user()->id) && 
            $currentUserRole && $role['level'] >= $currentUserRole['level']) {
            return $this->response->setJSON(['success' => false, 'message' => 'You cannot delete roles at your level or higher.']);
        }

        // Check if role has users assigned
        $userCount = $this->staffRoleModel->getRoleUserCount($id);
        if ($userCount > 0) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => "Cannot delete role. It has {$userCount} users assigned."
            ]);
        }

        if ($this->staffRoleModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Role deleted successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete role.']);
    }

    /**
     * Assign user to role
     */
    public function assignUser()
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'assign_roles')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $userId = $this->request->getPost('user_id');
        $roleId = $this->request->getPost('role_id');

        if (!$userId || !$roleId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID and Role ID are required.']);
        }

        // Check if user exists and is staff
        $user = $this->userModel->find($userId);
        if (!$user || $user['user_type'] !== 'staff') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid staff user.']);
        }

        // Check if role exists
        $role = $this->staffRoleModel->find($roleId);
        if (!$role) {
            return $this->response->setJSON(['success' => false, 'message' => 'Role not found.']);
        }

        // Check hierarchy - can't assign roles higher than current user's level
        $currentUserRole = $this->staffRoleModel->getUserHighestRole(auth()->user()->id);
        if (!$this->staffRoleModel->userIsSuperAdmin(auth()->user()->id) && 
            $currentUserRole && $role['level'] >= $currentUserRole['level']) {
            return $this->response->setJSON(['success' => false, 'message' => 'You cannot assign roles at your level or higher.']);
        }

        if ($this->staffRoleModel->assignUserToRole($userId, $roleId, auth()->user()->id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'User assigned to role successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to assign user to role.']);
    }

    /**
     * Remove user from role
     */
    public function removeUser()
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'assign_roles')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $userId = $this->request->getPost('user_id');
        $roleId = $this->request->getPost('role_id');

        if (!$userId || !$roleId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User ID and Role ID are required.']);
        }

        // Check hierarchy
        if (!$this->staffRoleModel->canManageUser(auth()->user()->id, $userId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'You cannot manage this user.']);
        }

        if ($this->staffRoleModel->removeUserFromRole($userId, $roleId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'User removed from role successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to remove user from role.']);
    }

    /**
     * Get role data for AJAX
     */
    public function getRole($id)
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'view_roles')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $role = $this->staffRoleModel->getRoleWithPermissions($id);
        if (!$role) {
            return $this->response->setJSON(['success' => false, 'message' => 'Role not found.']);
        }

        return $this->response->setJSON(['success' => true, 'role' => $role]);
    }

    /**
     * Update sort order
     */
    public function updateSortOrder()
    {
        // Check permission
        if (!auth()->user() || !$this->staffRoleModel->userHasPermission(auth()->user()->id, 'manage_roles')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $orders = $this->request->getPost('orders');
        if (!$orders || !is_array($orders)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid data provided.']);
        }

        if ($this->staffRoleModel->updateSortOrders($orders)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Sort order updated successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update sort order.']);
    }
} 