<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomUserModel;
use App\Models\CustomRoleModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Authentication\Passwords;
use App\Entities\User;

class StaffController extends BaseController
{
    protected $userModel;
    protected $identityModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new CustomUserModel();
        $this->identityModel = new UserIdentityModel();
        $this->roleModel = new CustomRoleModel();
    }

    /**
     * Display a listing of staff users
     */
    public function index()
    {
        // Use query builder directly to get arrays instead of entities
        $db = \Config\Database::connect();
        $staffUsers = $db->table('users')
                        ->select('users.*, custom_roles.title as role_title, custom_roles.color as role_color, clients.name as client_name, auth_identities.secret as email')
                        ->join('custom_roles', 'custom_roles.id = users.role_id', 'left')
                        ->join('clients', 'clients.id = users.client_id', 'left')
                        ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                        ->where('users.user_type', 'staff')
                        ->where('users.deleted_at', null)
                        ->orderBy('users.first_name', 'ASC')
                        ->get()
                        ->getResultArray();
        
        $data = [
            'title' => lang('App.staff_users'),
            'staffUsers' => $staffUsers,
        ];

        return view('staff/index', $data);
    }
    
    /**
     * Show the form for creating a new staff user
     */
    public function create()
    {
        // Get roles available for staff form
        $availableRoles = $this->roleModel->getStaffFormRoles();
        
        // Get active clients
        $clientModel = new \App\Models\ClientModel();
        $availableClients = $clientModel->getActiveClients();
        
        // Get pre-selected client ID from query parameter
        $preSelectedClientId = $this->request->getGet('client_id');
        
        $data = [
            'title' => lang('App.create_staff'),
            'availableRoles' => $availableRoles,
            'availableClients' => $availableClients,
            'preSelectedClientId' => $preSelectedClientId,
        ];

        return view('staff/create', $data);
    }
    
    /**
     * Store a newly created staff user
     */
    public function store()
    {
        // Debug: Log all POST data
        log_message('debug', 'STAFF CREATE - POST DATA: ' . print_r($this->request->getPost(), true));
        
        // Validation rules
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'first_name' => 'permit_empty|string|max_length[255]',
            'last_name' => 'permit_empty|string|max_length[255]',
            'phone' => 'permit_empty|string|max_length[20]',
            'role_id' => 'required|integer',
        ];
        
        if (!$this->validate($rules)) {
            log_message('debug', 'STAFF CREATE - Validation errors: ' . print_r($this->validator->getErrors(), true));
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Validate that the selected role exists and is available for staff form
        $selectedRoleId = $this->request->getPost('role_id');
        $selectedRole = $this->roleModel->find($selectedRoleId);
        
        if (!$selectedRole || !$selectedRole['is_active'] || !$selectedRole['show_in_staff_form']) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['role_id' => 'Invalid role selected.']);
        }
        
        // Create new user
        $users = $this->userModel;
        
        // Prepare user data for better tracking
        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'active' => $this->request->getPost('active') ? 1 : 0,
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'user_type' => 'staff',
            'role_id' => $selectedRoleId,
            'client_id' => $this->request->getPost('client_id') ?: null,
        ];
        
        log_message('debug', 'STAFF CREATE - User data before save: ' . print_r($userData, true));
        
        // Create the user entity
        $user = new User($userData);
        
        // Save the user first to get an ID
        if (!$users->save($user)) {
            log_message('debug', 'STAFF CREATE - Save failed, errors: ' . print_r($users->errors(), true));
            return redirect()->back()
                ->withInput()
                ->with('errors', $users->errors());
        }
        
        // Get the user with its ID from the database 
        $userId = $users->getInsertID();
        $user = $users->find($userId);
        
        log_message('debug', 'STAFF CREATE - User saved with ID: ' . $userId);
        
        // Check if the data was actually saved correctly
        if ($user) {
            log_message('debug', 'STAFF CREATE - Saved user data: ' . print_r($user->toArray(), true));
            $user->addGroup('user');
            
            // Double-check that our custom fields were saved
            $directUpdate = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'phone' => $this->request->getPost('phone'),
                'user_type' => 'staff',
                'role_id' => $selectedRoleId,
                'client_id' => $this->request->getPost('client_id') ?: null,
            ];
            
            // Filter out empty values
            $directUpdate = array_filter($directUpdate, function($value) {
                return $value !== null && $value !== '';
            });
            
            // Only update if we have values
            if (!empty($directUpdate)) {
                $users->update($userId, $directUpdate);
                log_message('debug', 'STAFF CREATE - Direct update of custom fields: ' . print_r($directUpdate, true));
            }
        }
        
        // Use toast notification instead of flash message
        set_toast_success(lang('App.staff_created'));
        return redirect()->to(base_url('staff'));
    }
    
    /**
     * Show the form for editing a staff user
     */
    public function edit($id = null)
    {
        $user = $this->userModel->find($id);
        
        if (!$user || $user->user_type !== 'staff') {
            set_toast_error(lang('App.staff_not_found'));
            return redirect()->to(base_url('staff'));
        }
        
        // Get roles available for staff form
        $availableRoles = $this->roleModel->getStaffFormRoles();
        
        // Get active clients
        $clientModel = new \App\Models\ClientModel();
        $availableClients = $clientModel->getActiveClients();
        
        $data = [
            'title' => lang('App.edit_staff'),
            'user' => $user,
            'availableRoles' => $availableRoles,
            'availableClients' => $availableClients,
        ];

        return view('staff/edit', $data);
    }
    
    /**
     * Update the specified staff user
     */
    public function update($id = null)
    {
        // Debug: Log all POST data
        log_message('debug', 'STAFF UPDATE - POST DATA: ' . print_r($this->request->getPost(), true));
        
        // Get the user and ensure it's our custom User entity
        $originalUser = $this->userModel->find($id);
        
        if (!$originalUser || $originalUser->user_type !== 'staff') {
            set_toast_error(lang('App.staff_not_found'));
            return redirect()->to(base_url('staff'));
        }
        
        // Build validation rules
        $rules = [
            'first_name' => 'permit_empty|string|max_length[255]',
            'last_name' => 'permit_empty|string|max_length[255]',
            'phone' => 'permit_empty|string|max_length[20]',
            'email' => 'required|valid_email',
            'role_id' => 'required|integer',
        ];
        
        // Check if email has changed
        if ($originalUser->email !== $this->request->getPost('email')) {
            $rules['email'] .= '|is_unique[auth_identities.secret,user_id,' . $originalUser->id . ']';
        }
        
        // If password is provided, add password rules
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
            $rules['password_confirm'] = 'matches[password]';
        }
        
        if (!$this->validate($rules)) {
            log_message('debug', 'STAFF UPDATE - Validation errors: ' . print_r($this->validator->getErrors(), true));
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Validate that the selected role exists and is available for staff form
        $selectedRoleId = $this->request->getPost('role_id');
        $selectedRole = $this->roleModel->find($selectedRoleId);
        
        if (!$selectedRole || !$selectedRole['is_active'] || !$selectedRole['show_in_staff_form']) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['role_id' => 'Invalid role selected.']);
        }
        
        // Update user data directly - don't try to use setters on the entity
        $updateData = [
            'email' => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'active' => $this->request->getPost('active') ? 1 : 0,
            'role_id' => $selectedRoleId,
            'client_id' => $this->request->getPost('client_id') ?: null,
        ];
        
        // Update password if provided
        if ($this->request->getPost('password')) {
            $updateData['password'] = $this->request->getPost('password');
        }
        
        log_message('debug', 'STAFF UPDATE - Update data: ' . print_r($updateData, true));
        
        // Use our save method directly
        if (!$this->userModel->update($id, $updateData)) {
            log_message('debug', 'STAFF UPDATE - Update failed, errors: ' . print_r($this->userModel->errors(), true));
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->userModel->errors());
        }
        
        // Handle email update for identities table
        if ($originalUser->email !== $this->request->getPost('email')) {
            $newEmail = $this->request->getPost('email');
            
            // Update the email identity
            $emailIdentity = $originalUser->getIdentity('email_password');
            if ($emailIdentity) {
                $emailIdentity->secret = $newEmail;
                $this->identityModel->save($emailIdentity);
                log_message('debug', 'STAFF UPDATE - Email identity updated to: ' . $newEmail);
            }
        }
        
        // Handle password update
        if ($this->request->getPost('password')) {
            $newPassword = $this->request->getPost('password');
            
            // Update the password identity
            $passwordIdentity = $originalUser->getIdentity('email_password');
            if ($passwordIdentity) {
                $passwordIdentity->secret2 = service('passwords')->hash($newPassword);
                $this->identityModel->save($passwordIdentity);
                log_message('debug', 'STAFF UPDATE - Password updated for user: ' . $originalUser->id);
            }
        }
        
        set_toast_success(lang('App.staff_updated'));
        return redirect()->to(base_url('staff'));
    }
    
    /**
     * Delete the specified staff user
     */
    public function delete($id = null)
    {
        $user = $this->userModel->find($id);
        
        if (!$user || $user->user_type !== 'staff') {
            set_toast_error(lang('App.staff_not_found'));
            return redirect()->to(base_url('staff'));
        }
        
        if ($this->userModel->delete($id)) {
            set_toast_success(lang('App.staff_deleted'));
        } else {
            set_toast_error(lang('App.staff_delete_error'));
        }
        
        return redirect()->to(base_url('staff'));
    }
    
    /**
     * AJAX delete method
     */
    public function ajaxDelete($id = null)
    {
        $user = $this->userModel->find($id);
        
        if (!$user || $user->user_type !== 'staff') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => lang('App.staff_not_found')
            ]);
        }
        
        if ($this->userModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => lang('App.staff_deleted')
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => lang('App.staff_delete_error')
            ]);
        }
    }

    /**
     * Get available roles for AJAX requests
     */
    public function getAvailableRoles()
    {
        $roles = $this->roleModel->getStaffFormRoles();
        
        return $this->response->setJSON([
            'status' => 'success',
            'roles' => $roles
        ]);
    }
} 