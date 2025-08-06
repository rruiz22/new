<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\GroupModel;
use App\Models\CustomUserModel;
use App\Models\CustomRoleModel;
use CodeIgniter\Shield\Entities\Group;
use Config\AuthGroups;

class RoleController extends BaseController
{
    protected $groupModel;
    protected $userModel;
    protected $authGroups;
    protected $customRoleModel;

    public function __construct()
    {
        $this->groupModel = new GroupModel();
        $this->userModel = new CustomUserModel();
        $this->authGroups = new AuthGroups();
        $this->customRoleModel = new CustomRoleModel();
    }

    /**
     * Display a listing of custom roles
     */
    public function index()
    {
        $data = [
            'title' => 'Roles Management',
            'roles' => $this->customRoleModel->getRolesWithUserCount(),
        ];

        return view('roles/index', $data);
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $data = [
            'title' => 'Create New Role',
            'availablePermissions' => $this->getAvailablePermissions(),
        ];

        return view('roles/create', $data);
    }

    /**
     * Store a newly created role
     */
    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|max_length[100]|is_unique[custom_roles.title]', // This becomes the title
            'description' => 'max_length[255]',
            'color' => 'permit_empty|max_length[7]',
            'sort_order' => 'permit_empty|integer',
            'is_active' => 'in_list[0,1]',
            'show_in_staff_form' => 'in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $permissions = $this->request->getPost('permissions') ?: [];
        
        $data = [
            'title' => $this->request->getPost('name'), // Form sends 'name' but we store as 'title'
            'description' => $this->request->getPost('description'),
            'permissions' => json_encode($permissions),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'show_in_staff_form' => $this->request->getPost('show_in_staff_form') ? 1 : 0,
            'color' => $this->request->getPost('color') ?: '#405189',
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
        ];

        if ($this->customRoleModel->save($data)) {
            return redirect()->to('roles')->with('success', 'Role created successfully.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->customRoleModel->errors());
        }
    }

    /**
     * Show the form for editing a role
     */
    public function edit($id)
    {
        $role = $this->customRoleModel->find($id);
        
        if (!$role) {
            return redirect()->to('roles')->with('error', 'Role not found.');
        }

        $data = [
            'title' => 'Edit Role',
            'role' => $role,
            'rolePermissions' => json_decode($role['permissions'] ?: '[]', true),
            'availablePermissions' => $this->getAvailablePermissions(),
        ];

        return view('roles/edit', $data);
    }

    /**
     * Update a role
     */
    public function update($id)
    {
        $role = $this->customRoleModel->find($id);
        
        if (!$role) {
            return redirect()->to('roles')->with('error', 'Role not found.');
        }

        $validation = \Config\Services::validation();
        
        // Get the submitted title (used as name)
        $newTitle = $this->request->getPost('name'); // The form sends 'name' but we store it as 'title'
        
        // Check if another role with this title exists (excluding this role)
        if ($newTitle !== $role['title'] && $this->customRoleModel->roleTitleExists($newTitle, $id)) {
            return redirect()->back()->withInput()->with('errors', ['name' => lang('App.role_name_exists')]);
        }
        
        $rules = [
            'name' => 'required|max_length[100]', // This becomes the title
            'description' => 'max_length[255]',
            'color' => 'permit_empty|max_length[7]',
            'sort_order' => 'permit_empty|integer',
            'is_active' => 'in_list[0,1]',
            'show_in_staff_form' => 'in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $permissions = $this->request->getPost('permissions') ?: [];
        
        $data = [
            'title' => $this->request->getPost('name'), // Form sends 'name' but we store as 'title'
            'description' => $this->request->getPost('description'),
            'permissions' => json_encode($permissions),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'show_in_staff_form' => $this->request->getPost('show_in_staff_form') ? 1 : 0,
            'color' => $this->request->getPost('color') ?: '#405189',
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
        ];

        if ($this->customRoleModel->updateRole($id, $data)) {
            return redirect()->to('roles')->with('success', 'Role updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->customRoleModel->errors());
        }
    }

    /**
     * Delete a role
     */
    public function delete($id)
    {
        $role = $this->customRoleModel->find($id);
        
        if (!$role) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Role not found.']);
            }
            return redirect()->to('roles')->with('error', 'Role not found.');
        }

        // Check if role can be deleted
        if (!$this->customRoleModel->canBeDeleted($id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Cannot delete role. There are users assigned to this role.']);
            }
            return redirect()->to('roles')->with('error', 'Cannot delete role. There are users assigned to this role.');
        }

        if ($this->customRoleModel->delete($id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Role deleted successfully.']);
            }
            return redirect()->to('roles')->with('success', 'Role deleted successfully.');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete role.']);
            }
            return redirect()->to('roles')->with('error', 'Failed to delete role.');
        }
    }

    /**
     * Toggle role status via AJAX
     */
    public function toggle_status()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method.']);
        }

        $json = $this->request->getJSON();
        $roleId = $json->id ?? null;
        $isActive = $json->is_active ?? null;

        if (!$roleId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Role ID is required.']);
        }

        $role = $this->customRoleModel->find($roleId);
        if (!$role) {
            return $this->response->setJSON(['success' => false, 'message' => 'Role not found.']);
        }

        $data = ['is_active' => $isActive ? 1 : 0];
        
        if ($this->customRoleModel->update($roleId, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Status updated successfully.']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status.']);
    }

    /**
     * Toggle staff form visibility via AJAX
     */
    public function toggle_staff_form()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method.']);
        }

        $json = $this->request->getJSON();
        $roleId = $json->id ?? null;
        $showInStaffForm = $json->show_in_staff_form ?? null;

        if (!$roleId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Role ID is required.']);
        }

        $role = $this->customRoleModel->find($roleId);
        if (!$role) {
            return $this->response->setJSON(['success' => false, 'message' => 'Role not found.']);
        }

        $data = ['show_in_staff_form' => $showInStaffForm ? 1 : 0];
        
        if ($this->customRoleModel->update($roleId, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Setting updated successfully.']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update setting.']);
    }

    /**
     * Toggle role active status (alternative route for compatibility)
     */
    public function toggleActive($id)
    {
        $role = $this->customRoleModel->find($id);
        if (!$role) {
            return $this->response->setJSON(['success' => false, 'message' => 'Role not found.']);
        }

        $data = ['is_active' => $role['is_active'] ? 0 : 1];
        
        if ($this->customRoleModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Status updated successfully.']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update status.']);
    }

    /**
     * Toggle role staff form visibility (alternative route for compatibility)
     */
    public function toggleStaffForm($id)
    {
        $role = $this->customRoleModel->find($id);
        if (!$role) {
            return $this->response->setJSON(['success' => false, 'message' => 'Role not found.']);
        }

        $data = ['show_in_staff_form' => $role['show_in_staff_form'] ? 0 : 1];
        
        if ($this->customRoleModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Setting updated successfully.']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update setting.']);
    }

    /**
     * Show users in a specific role
     */
    public function users($roleId = null)
    {
        $role = $this->customRoleModel->find($roleId);
        
        if (!$role) {
            return redirect()->to('roles')->with('error', 'Role not found');
        }
        
        // Get users with this role - handle both integer and string role_id
        $usersInRole = $this->userModel->where('role_id', (int)$roleId)->findAll();
        
        $data = [
            'title' => 'Users in Role: ' . $role['title'],
            'role' => $role,
            'users' => $usersInRole,
        ];

        return view('roles/users', $data);
    }

    /**
     * Get available permissions
     */
    private function getAvailablePermissions()
    {
        return [
            'dashboard' => [
                'dashboard.view' => 'View Dashboard'
            ],
            'users' => [
                'users.view' => 'View Users',
                'users.create' => 'Create Users',
                'users.edit' => 'Edit Users',
                'users.delete' => 'Delete Users'
            ],
            'staff' => [
                'staff.view' => 'View Staff',
                'staff.create' => 'Create Staff',
                'staff.edit' => 'Edit Staff',
                'staff.delete' => 'Delete Staff'
            ],
            'clients' => [
                'clients.view' => 'View Clients',
                'clients.create' => 'Create Clients',
                'clients.edit' => 'Edit Clients',
                'clients.delete' => 'Delete Clients'
            ],
            'contacts' => [
                'contacts.view' => 'View Contacts',
                'contacts.create' => 'Create Contacts',
                'contacts.edit' => 'Edit Contacts',
                'contacts.delete' => 'Delete Contacts'
            ],
            'todo' => [
                'todo.view' => 'View Tasks',
                'todo.create' => 'Create Tasks',
                'todo.edit' => 'Edit Tasks',
                'todo.delete' => 'Delete Tasks'
            ],
            'roles' => [
                'roles.view' => 'View Roles',
                'roles.create' => 'Create Roles',
                'roles.edit' => 'Edit Roles',
                'roles.delete' => 'Delete Roles'
            ],
            'settings' => [
                'settings.view' => 'View Settings',
                'settings.edit' => 'Edit Settings'
            ]
        ];
    }

    /**
     * Get roles for staff form (AJAX endpoint)
     */
    public function getStaffFormRoles()
    {
        $roles = $this->customRoleModel->getStaffFormRoles();
        return $this->response->setJSON($roles);
    }

    /**
     * Remove user from role
     */
    public function removeUser($roleId, $userId)
    {
        $role = $this->customRoleModel->find($roleId);
        
        if (!$role) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Role not found.']);
            }
            return redirect()->to('roles')->with('error', 'Role not found.');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'User not found.']);
            }
            return redirect()->to("roles/users/{$roleId}")->with('error', 'User not found.');
        }

        // Remove role from user (set role_id to null or default role)
        $updateData = ['role_id' => null]; // or set to a default role ID if needed
        
        if ($this->userModel->update($userId, $updateData)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'User removed from role successfully.']);
            }
            return redirect()->to("roles/users/{$roleId}")->with('success', 'User removed from role successfully.');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to remove user from role.']);
            }
            return redirect()->to("roles/users/{$roleId}")->with('error', 'Failed to remove user from role.');
        }
    }

    /**
     * Show form to add user to role
     */
    public function addUser($roleId)
    {
        $role = $this->customRoleModel->find($roleId);
        
        if (!$role) {
            return redirect()->to('roles')->with('error', 'Role not found.');
        }

        // Get users that don't have this role assigned
        // This includes users with role_id = null or role_id != this roleId
        $availableUsers = $this->userModel
            ->groupStart()
                ->where('role_id !=', (int)$roleId)
                ->orWhere('role_id', null)
            ->groupEnd()
            ->findAll();
        
        $data = [
            'title' => 'Add User to Role: ' . $role['title'],
            'role' => $role,
            'availableUsers' => $availableUsers,
        ];

        return view('roles/add_user', $data);
    }

    /**
     * Assign role to user
     */
    public function assignRole()
    {
        $roleId = $this->request->getPost('role_id');
        $userId = $this->request->getPost('user_id');

        if (!$roleId || !$userId) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Role ID and User ID are required.']);
            }
            return redirect()->back()->with('error', 'Role ID and User ID are required.');
        }

        $role = $this->customRoleModel->find($roleId);
        $user = $this->userModel->find($userId);

        if (!$role || !$user) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Role or User not found.']);
            }
            return redirect()->back()->with('error', 'Role or User not found.');
        }

        // Assign role to user
        if ($this->userModel->update($userId, ['role_id' => $roleId])) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Role assigned successfully.']);
            }
            return redirect()->to("roles/users/{$roleId}")->with('success', 'Role assigned successfully.');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to assign role.']);
            }
            return redirect()->back()->with('error', 'Failed to assign role.');
        }
    }
} 