<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ContactModel;
use App\Models\UserModel;
use App\Models\ContactGroupModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Models\UserIdentityModel;

class ContactController extends BaseController
{
    protected $clientModel;
    protected $contactModel;
    protected $userModel;
    protected $groupModel;
    
    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->contactModel = new ContactModel();
        $this->userModel = new UserModel();
        $this->groupModel = new ContactGroupModel();
    }
    
    /**
     * Display a listing of contacts
     *
     * @return string
     */
    public function index()
    {
        // Get client users from users table instead of contacts table
        $contacts = $this->userModel->select('users.*, clients.name as client_name, 
                                            CONCAT(users.first_name, " ", users.last_name) as name,
                                            users.id as contact_id,
                                            users.phone as contact_info,
                                            auth_identities.secret as email,
                                            users.active')
                              ->join('clients', 'clients.id = users.client_id', 'left')
                              ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                              ->where('users.user_type', 'client')
                              ->orderBy('users.first_name', 'ASC')
                              ->findAll();
        
        // Check if each user has a corresponding contact record
        foreach ($contacts as $key => $contact) {
            $contactRecord = $this->contactModel->where('user_id', $contact['id'])->first();
            $contacts[$key]['has_contact_record'] = !empty($contactRecord);
            $contacts[$key]['is_primary'] = $contactRecord['is_primary'] ?? 0;
            $contacts[$key]['status'] = $contact['active'] == 1 ? 'active' : 'inactive';
            if ($contactRecord) {
                $contacts[$key]['contact_record'] = $contactRecord;
            }
        }
        
        $data = [
            'title' => lang('App.contact_list'),
            'contacts' => $contacts,
        ];
        
        return view('contacts/index', $data);
    }
    
    /**
     * Get contacts data for DataTables AJAX
     */
    public function getContactsData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }
        
        // Get client users from users table
        $contacts = $this->userModel->select('users.*, clients.name as client_name, 
                                            CONCAT(users.first_name, " ", users.last_name) as name,
                                            users.id as contact_id,
                                            users.phone as contact_info,
                                            auth_identities.secret as email,
                                            users.active')
                              ->join('clients', 'clients.id = users.client_id', 'left')
                              ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                              ->where('users.user_type', 'client')
                              ->orderBy('users.first_name', 'ASC')
                              ->findAll();
        
        // Check if each user has a corresponding contact record
        foreach ($contacts as $key => $contact) {
            $contactRecord = $this->contactModel->where('user_id', $contact['id'])->first();
            $contacts[$key]['has_contact_record'] = !empty($contactRecord);
            $contacts[$key]['is_primary'] = $contactRecord['is_primary'] ?? 0;
            $contacts[$key]['status'] = $contact['active'] == 1 ? 'active' : 'inactive';
            if ($contactRecord) {
                $contacts[$key]['contact_record'] = $contactRecord;
                $contacts[$key]['position'] = $contactRecord['position'] ?? '';
            } else {
                $contacts[$key]['position'] = '';
            }
        }
        
        // Format data for DataTables
        $data = [];
        foreach ($contacts as $contact) {
            $contactInfo = '';
            if (!empty($contact['email'])) {
                $contactInfo .= '<div><i class="ri-mail-line me-1"></i>' . esc($contact['email']) . '</div>';
            }
            if (!empty($contact['phone'])) {
                $contactInfo .= '<div><i class="ri-phone-line me-1"></i>' . esc($contact['phone']) . '</div>';
            }
            if (!empty($contact['position'])) {
                $contactInfo .= '<div><i class="ri-user-line me-1"></i>' . esc($contact['position']) . '</div>';
            }
            
            // Use new status indicator style
            $statusClass = $contact['status'] === 'active' ? 'status-active' : 'status-inactive';
            $statusText = $contact['status'] === 'active' ? lang('App.active') : lang('App.inactive');
            $statusBadge = '<span class="status-indicator ' . $statusClass . '">' . $statusText . '</span>';
            
            // Use new primary badge style
            $primaryBadge = $contact['is_primary'] 
                ? '<span class="badge bg-primary">Primary</span>' 
                : '<span class="badge bg-light text-muted">Secondary</span>';
            
            $actions = '
                <div class="d-flex gap-2">
                    <a href="' . base_url('contacts/' . $contact['id']) . '" class="btn btn-sm btn-outline-primary" title="View">
                        <i class="ri-eye-line"></i>
                    </a>
                    <a href="' . base_url('contacts/edit/' . $contact['id']) . '" class="btn btn-sm btn-outline-success" title="Edit">
                        <i class="ri-edit-line"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-contact-btn" data-id="' . $contact['id'] . '" title="Delete">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            ';
            
            $data[] = [
                'name' => esc($contact['name']),
                'client_name' => esc($contact['client_name'] ?? 'N/A'),
                'contact_info' => $contactInfo,
                'is_primary' => $primaryBadge,
                'status' => $statusBadge,
                'actions' => $actions
            ];
        }
        
        return $this->response->setJSON([
            'data' => $data
        ]);
    }
    
    /**
     * Show the form for creating a new contact
     *
     * @return string
     */
    public function create()
    {
        $clients = $this->clientModel->where('status', 'active')->findAll();
        
        $data = [
            'title' => lang('App.create_contact'),
            'clients' => $clients,
            'create_user' => true, // Flag to indicate we're creating a user along with contact
        ];
        
        return view('contacts/create', $data);
    }
    
    /**
     * Store a newly created contact
     *
     * @return ResponseInterface
     */
    public function store()
    {
        $rules = [
            'client_id' => 'required|integer',
            'position' => 'permit_empty',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty',
        ];
        
        // Añadir reglas para campos de usuario si se está creando uno
        if ($this->request->getPost('create_user')) {
            $rules['password'] = 'required|min_length[6]';
            $rules['password_confirm'] = 'required|matches[password]';
            $rules['first_name'] = 'required|min_length[2]|max_length[255]';
            $rules['last_name'] = 'permit_empty|max_length[255]';
            $rules['username'] = 'permit_empty|min_length[3]|max_length[30]|is_unique[users.username]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $isPrimary = $this->request->getPost('is_primary') ? 1 : 0;
        $createUser = $this->request->getPost('create_user') ? true : false;
        
        // If this is a primary contact, update all other contacts for this client to not be primary
        if ($isPrimary) {
            $this->contactModel->where('client_id', $this->request->getPost('client_id'))
                              ->set(['is_primary' => 0])
                              ->update();
        }
        
        // Generate name from first_name and last_name if user account is being created
        $contactName = '';
        if ($createUser) {
            $firstName = $this->request->getPost('first_name');
            $lastName = $this->request->getPost('last_name');
            $contactName = trim($firstName . ' ' . $lastName);
        } else {
            // If not creating a user, use email as name
            $contactName = $this->request->getPost('email');
        }
        
        $contactData = [
            'client_id' => $this->request->getPost('client_id'),
            'name' => $contactName,
            'position' => $this->request->getPost('position'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'is_primary' => $isPrimary,
            'status' => $this->request->getPost('status') ?? 'active',
        ];
        
        // Create user account if requested
        if ($createUser) {
            // First check if a user with this email already exists
            $existingUser = $this->userModel->getUserByEmail($contactData['email']);
            
            if ($existingUser) {
                // If user exists, associate the contact with that user
                $contactData['user_id'] = $existingUser['id'];
            } else {
                // Add user-specific fields to contactData for user creation
                $contactData['first_name'] = $this->request->getPost('first_name');
                $contactData['last_name'] = $this->request->getPost('last_name');
                $contactData['username'] = $this->request->getPost('username');
                $contactData['password'] = $this->request->getPost('password');
                
                // Create a new user
                $userId = $this->userModel->createClientUser($contactData);
                if ($userId) {
                    $contactData['user_id'] = $userId;
                    
                    // Si tenemos un password, debemos crear una identidad de usuario usando Shield
                    if (!empty($contactData['password'])) {
                        // Crear una identidad de correo electrónico/contraseña para este usuario
                        $identityModel = model('UserIdentityModel');
                        $identityModel->create([
                            'user_id' => $userId,
                            'type' => 'email_password',
                            'name' => null,
                            'secret' => $contactData['email'],
                            'secret2' => password_hash($contactData['password'], PASSWORD_DEFAULT),
                            'expires' => null,
                            'force_reset' => 0,
                        ]);
                    }
                }
            }
        }
        
        if ($this->contactModel->insert($contactData)) {
            session()->setFlashdata('success', lang('App.contact_created'));
            return redirect()->to('contacts');
        } else {
            session()->setFlashdata('error', lang('App.something_wrong'));
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Display the specified contact
     *
     * @param int $id User ID (not contact ID)
     * @return string|ResponseInterface
     */
    public function show($id = null)
    {
        if (!$id) {
            session()->setFlashdata('error', lang('App.contact_not_found'));
            return redirect()->to('contacts');
        }
        
        // Get user information first (since ID passed is user ID)
        $user = $this->userModel->select('users.*, clients.name as client_name, 
                                         CONCAT(users.first_name, " ", users.last_name) as full_name,
                                         auth_identities.secret as email')
                                ->join('clients', 'clients.id = users.client_id', 'left')
                                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                                ->where('users.id', $id)
                                ->where('users.user_type', 'client')
                                ->first();
        
        if (!$user) {
            session()->setFlashdata('error', lang('App.contact_not_found'));
            return redirect()->to('contacts');
        }
        
        // Get contact record if it exists
        $contactRecord = $this->contactModel->where('user_id', $id)->first();
        
        // Get client information - handle null client_id
        $client = null;
        if (!empty($user['client_id'])) {
            $client = $this->clientModel->find($user['client_id']);
        }
        
        // Get contact groups
        $contactGroups = $this->groupModel->getUserGroups($id);
        
        // Combine all information
        $contact = [
            'id' => $user['id'],
            'user_id' => $user['id'],
            'client_id' => $user['client_id'],
            'name' => $user['full_name'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'username' => $user['username'] ?? '',
            'email' => $user['email'],
            'phone' => $user['phone'],
            'status' => $user['active'] == 1 ? 'active' : 'inactive',
            'position' => $contactRecord['position'] ?? '',
            'is_primary' => $contactRecord['is_primary'] ?? 0,
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at'],
            'client' => $client,
            'client_name' => $user['client_name'],
            'has_contact_record' => !empty($contactRecord),
            'groups' => $contactGroups
        ];
        
        $data = [
            'title' => lang('App.contact_details'),
            'contact' => $contact,
            'client' => $client,
            'contact_groups' => $contactGroups
        ];
        
        return view('contacts/show', $data);
    }
    
    /**
     * Show the form for editing the contact
     *
     * @param int $id User ID (not contact ID)
     * @return string|ResponseInterface
     */
    public function edit($id = null)
    {
        if (!$id) {
            session()->setFlashdata('error', lang('App.contact_not_found'));
            return redirect()->to('contacts');
        }
        
        // Get user information first (since ID passed is user ID)
        $user = $this->userModel->select('users.*, clients.name as client_name, 
                                         CONCAT(users.first_name, " ", users.last_name) as full_name,
                                         auth_identities.secret as email')
                                ->join('clients', 'clients.id = users.client_id', 'left')
                                ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                                ->where('users.id', $id)
                                ->where('users.user_type', 'client')
                                ->first();
        
        if (!$user) {
            session()->setFlashdata('error', lang('App.contact_not_found'));
            return redirect()->to('contacts');
        }
        
        // Get contact record if it exists
        $contactRecord = $this->contactModel->where('user_id', $id)->first();
        
        // Get client information
        $client = $this->clientModel->find($user['client_id']);
        
        // Combine all information into contact array for the view
        $contact = [
            'id' => $contactRecord['id'] ?? null,  // Contact record ID for updates
            'user_id' => $user['id'],              // User ID
            'client_id' => $user['client_id'],
            'name' => $user['full_name'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'status' => $user['active'] == 1 ? 'active' : 'inactive',
            'position' => $contactRecord['position'] ?? '',
            'is_primary' => $contactRecord['is_primary'] ?? 0,
            'has_contact_record' => !empty($contactRecord)
        ];
        
        $clients = $this->clientModel->where('status', 'active')->findAll();
        
        // Check if contact has a user account (always true in this case)
        $hasUser = true;
        $contact['user'] = $user;
        
        $data = [
            'title' => lang('App.edit_contact'),
            'contact' => $contact,
            'clients' => $clients,
            'has_user' => $hasUser,
            'create_user' => false, // Always false since user already exists
        ];
        
        return view('contacts/edit', $data);
    }
    
    /**
     * Update the contact
     *
     * @param int $id User ID
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        log_message('debug', 'ContactController::update called with ID: ' . $id);
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
        
        if (!$id) {
            log_message('debug', 'No ID provided to update method');
            session()->setFlashdata('error', lang('App.contact_not_found'));
            return redirect()->to('contacts');
        }
        
        // Get user information first (since ID passed is user ID)
        $user = $this->userModel->find($id);
        
        if (!$user || $user['user_type'] !== 'client') {
            log_message('debug', 'User not found or not client type. User: ' . json_encode($user));
            session()->setFlashdata('error', lang('App.contact_not_found'));
            return redirect()->to('contacts');
        }
        
        // Get contact record if it exists
        $contactRecord = $this->contactModel->where('user_id', $id)->first();
        
        $rules = [
            'client_id' => 'required|integer',
            'position' => 'permit_empty',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty',
            'first_name' => 'required|min_length[2]|max_length[255]',
            'last_name' => 'permit_empty|max_length[255]',
        ];
        
        // Rules for password change if requested
        if ($this->request->getPost('change_password')) {
            $rules['password'] = 'required|min_length[6]';
            $rules['password_confirm'] = 'required|matches[password]';
        }
        
        log_message('debug', 'Validation rules: ' . json_encode($rules));
        
        if (!$this->validate($rules)) {
            log_message('debug', 'Validation failed. Errors: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        log_message('debug', 'Validation passed, proceeding with update');
        
        $isPrimary = $this->request->getPost('is_primary') ? 1 : 0;
        
        // If this is a primary contact, update all other contacts for this client to not be primary
        if ($isPrimary && $contactRecord) {
            $this->contactModel->where('client_id', $this->request->getPost('client_id'))
                              ->where('id !=', $contactRecord['id'])
                              ->set(['is_primary' => 0])
                              ->update();
        } elseif ($isPrimary && !$contactRecord) {
            // If no contact record exists but they want this to be primary, clear other primaries
            $this->contactModel->where('client_id', $this->request->getPost('client_id'))
                              ->set(['is_primary' => 0])
                              ->update();
        }
        
        // Update user information
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $fullName = trim($firstName . ' ' . $lastName);
        $status = $this->request->getPost('status');
        
        $userData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $this->request->getPost('phone'),
            'active' => $status === 'active' ? 1 : 0,
            'client_id' => $this->request->getPost('client_id'),
        ];
        
        // Update user record
        $this->userModel->update($id, $userData);
        
        // Update email in auth_identities if it changed
        $newEmail = $this->request->getPost('email');
        $identityModel = model('UserIdentityModel');
        $identity = $identityModel->where('user_id', $id)
                                  ->where('type', 'email_password')
                                  ->first();
        
        if ($identity && $identity->secret !== $newEmail) {
            $identityModel->update($identity->id, ['secret' => $newEmail]);
        }
        
        // Handle password change if requested
        if ($this->request->getPost('change_password')) {
            if ($identity) {
                $identityModel->update($identity->id, [
                    'secret2' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
                ]);
            } else {
                // Create new identity if it doesn't exist
                $identityModel->insert([
                    'user_id' => $id,
                    'type' => 'email_password',
                    'name' => null,
                    'secret' => $newEmail,
                    'secret2' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'expires' => null,
                    'force_reset' => 0,
                ]);
            }
        }
        
        // Update or create contact record
        $contactData = [
            'client_id' => $this->request->getPost('client_id'),
            'user_id' => $id,
            'name' => $fullName,
            'position' => $this->request->getPost('position'),
            'email' => $newEmail,
            'phone' => $this->request->getPost('phone'),
            'is_primary' => $isPrimary,
            'status' => $status,
        ];
        
        if ($contactRecord) {
            // Update existing contact record
            $this->contactModel->update($contactRecord['id'], $contactData);
        } else {
            // Create new contact record
            $this->contactModel->insert($contactData);
        }
        
        log_message('debug', 'Contact update completed successfully');
        session()->setFlashdata('success', lang('App.contact_updated'));
        return redirect()->to("contacts/$id");
    }
    
    /**
     * Delete the contact
     *
     * @return ResponseInterface
     */
    public function delete()
    {
        $userId = $this->request->getPost('id');
        
        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => lang('App.invalid_id')
            ]);
        }
        
        // Get user first to verify it exists and is a client
        $user = $this->userModel->find($userId);
        
        if (!$user || $user['user_type'] !== 'client') {
            return $this->response->setJSON([
                'status' => false,
                'message' => lang('App.contact_not_found')
            ]);
        }
        
        // Get contact record if it exists
        $contactRecord = $this->contactModel->where('user_id', $userId)->first();
        
        // Delete contact record if it exists
        if ($contactRecord) {
            if (!$this->contactModel->delete($contactRecord['id'])) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => lang('App.delete_error')
                ]);
            }
        }
        
        // Optionally, you might want to deactivate the user instead of deleting
        // Or keep the user but remove the contact association
        // For now, we'll just delete the contact record and deactivate the user
        $this->userModel->update($userId, ['active' => 0]);
        
        return $this->response->setJSON([
            'status' => true,
            'message' => lang('App.contact_deleted')
        ]);
    }
    
    /**
     * Get contacts by client ID - for AJAX calls
     *
     * @return ResponseInterface
     */
    public function getContactsByClient()
    {
        $clientId = $this->request->getGet('client_id');
        
        if (!$clientId) {
            return $this->response->setJSON([
                'status' => false,
                'data' => []
            ]);
        }
        
        $contacts = $this->contactModel->where('client_id', $clientId)->findAll();
        
        return $this->response->setJSON([
            'status' => true,
            'data' => $contacts
        ]);
    }
    
    /**
     * Get contacts by client ID in JSON format for dropdown lists
     *
     * @param int $clientId
     * @return ResponseInterface
     */
    public function get_contacts_by_client_json($clientId = null)
    {
        try {
            // Verificar si hay una sesión activa de manera más simple
            if (!session()->has('isLoggedIn') && !session()->has('user')) {
                log_message('warning', 'Unauthorized access attempt to get_contacts_by_client_json from IP: ' . $this->request->getIPAddress());
                
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON(['error' => 'Authentication required']);
            }
            
            if (!$clientId) {
                return $this->response->setJSON([]);
            }
            
            $contacts = $this->contactModel->where('client_id', $clientId)
                                          ->where('status', 'active')
                                          ->orderBy('name', 'ASC')
                                          ->findAll();
            
            log_message('info', 'Found ' . count($contacts) . ' active contacts for client ' . $clientId . ' by session user');
            
            return $this->response->setJSON($contacts);
        } catch (\Exception $e) {
            log_message('error', 'Error in get_contacts_by_client_json: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => $e->getMessage()]);
        }
    }
} 