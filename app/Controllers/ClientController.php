<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ContactModel;
use App\Models\CustomUserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ClientController extends BaseController
{
    protected $clientModel;
    protected $contactModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->contactModel = new ContactModel();
        $this->userModel = new CustomUserModel();
    }
    
    /**
     * Display a listing of clients
     *
     * @return string
     */
    public function index()
    {
        $data = [
            'title' => lang('App.client_list'),
            'clients' => $this->clientModel->findAll(),
        ];
        
        return view('clients/index', $data);
    }
    
    /**
     * Show the form for creating a new client
     *
     * @return string
     */
    public function create()
    {
        $data = [
            'title' => lang('App.create_client'),
        ];
        
        return view('clients/create', $data);
    }
    
    /**
     * Store a newly created client
     *
     * @return ResponseInterface
     */
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'email' => 'permit_empty|valid_email',
            'phone' => 'permit_empty',
            'address' => 'permit_empty',
            'website' => 'permit_empty|valid_url_strict',
            'tax_number' => 'permit_empty',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'website' => $this->request->getPost('website'),
            'tax_number' => $this->request->getPost('tax_number'),
            'status' => $this->request->getPost('status') ?? 'active',
        ];
        
        if ($this->clientModel->insert($data)) {
            session()->setFlashdata('success', lang('App.client_created'));
            return redirect()->to('clients');
        } else {
            session()->setFlashdata('error', lang('App.something_wrong'));
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Display the specified client
     *
     * @param int $id
     * @return string|ResponseInterface
     */
    public function show($id = null)
    {
        $client = $this->clientModel->getClientWithContacts($id);
        
        if (!$client) {
            session()->setFlashdata('error', lang('App.client_not_found'));
            return redirect()->to('clients');
        }
        
        // Get assigned staff users for this client
        $db = \Config\Database::connect();
        $assignedStaffUsers = $db->table('users')
            ->select('users.id, users.first_name, users.last_name, users.username, users.active, users.last_seen, users.role_id, users.user_type, custom_roles.title as role_title, custom_roles.color as role_color, auth_identities.secret as email')
            ->join('custom_roles', 'custom_roles.id = users.role_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->where('users.client_id', $id)
            ->where('users.user_type', 'staff')
            ->where('users.active', 1)
            ->where('users.deleted_at', null)
            ->get()
            ->getResultArray();
            
        // Get assigned client users for this client
        $assignedClientUsers = $db->table('users')
            ->select('users.id, users.first_name, users.last_name, users.username, users.active, users.last_seen, users.user_type, auth_identities.secret as email')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->where('users.client_id', $id)
            ->where('users.user_type', 'client')
            ->where('users.active', 1)
            ->where('users.deleted_at', null)
            ->get()
            ->getResultArray();
        
        $data = [
            'title' => lang('App.client_details'),
            'client' => $client,
            'assignedStaffUsers' => $assignedStaffUsers,
            'assignedClientUsers' => $assignedClientUsers,
        ];
        
        return view('clients/show', $data);
    }
    
    /**
     * Show the form for editing the client
     *
     * @param int $id
     * @return string|ResponseInterface
     */
    public function edit($id = null)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            session()->setFlashdata('error', lang('App.client_not_found'));
            return redirect()->to('clients');
        }
        
        $data = [
            'title' => lang('App.edit_client'),
            'client' => $client,
        ];
        
        return view('clients/edit', $data);
    }
    
    /**
     * Update the client
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            session()->setFlashdata('error', lang('App.client_not_found'));
            return redirect()->to('clients');
        }
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'email' => 'permit_empty|valid_email',
            'phone' => 'permit_empty',
            'address' => 'permit_empty',
            'website' => 'permit_empty|valid_url_strict',
            'tax_number' => 'permit_empty',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'website' => $this->request->getPost('website'),
            'tax_number' => $this->request->getPost('tax_number'),
            'status' => $this->request->getPost('status'),
        ];
        
        if ($this->clientModel->update($id, $data)) {
            session()->setFlashdata('success', lang('App.client_updated'));
            return redirect()->to("clients/$id");
        } else {
            session()->setFlashdata('error', lang('App.something_wrong'));
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Get all active clients in JSON format for dropdown lists
     *
     * @return ResponseInterface
     */
    public function get_clients_json()
    {
        try {
            // Verificación básica de autenticación
            if (!session()->has('isLoggedIn') && !session()->has('user_id')) {
                log_message('warning', 'Unauthorized access attempt to get_clients_json from IP: ' . $this->request->getIPAddress());
                
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON(['error' => 'Authentication required']);
            }
            
            // Obtener clientes activos
            $clients = $this->clientModel->where('status', 'active')
                                         ->orderBy('name', 'ASC')
                                         ->findAll();
            
            // Registrar la cantidad de clientes encontrados
            log_message('info', 'Found ' . count($clients) . ' active clients for user: ' . (session()->get('user_id') ?? 'unknown'));
            
            // Retornar respuesta JSON
            return $this->response
                ->setJSON($clients);
                
        } catch (\Exception $e) {
            // Registrar el error
            log_message('error', 'Error in get_clients_json: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Delete the client
     *
     * @return ResponseInterface
     */
    public function delete()
    {
        // Log para debugging
        log_message('info', 'Delete method called. POST data: ' . json_encode($this->request->getPost()));
        
        $id = $this->request->getPost('id');
        
        if (!$id) {
            log_message('error', 'No ID provided for client deletion');
            return $this->response->setJSON([
                'status' => false,
                'message' => lang('App.client_not_found') . ' (No ID provided)'
            ]);
        }
        
        log_message('info', 'Attempting to delete client with ID: ' . $id);
        
        // Use direct database query to avoid soft delete issues in search
        $db = \Config\Database::connect();
        $client = $db->table('clients')
                     ->where('id', $id)
                     ->where('deleted_at', null)
                     ->get()
                     ->getRowArray();
        
        if (!$client) {
            log_message('error', 'Client not found with ID: ' . $id);
            return $this->response->setJSON([
                'status' => false,
                'message' => lang('App.client_not_found') . ' (ID: ' . $id . ')'
            ]);
        }
        
        log_message('info', 'Client found: ' . json_encode($client));
        
        try {
            // Use the model's delete method which will handle soft delete
            if ($this->clientModel->delete($id)) {
                log_message('info', 'Client deleted successfully with ID: ' . $id);
                return $this->response->setJSON([
                    'status' => true,
                    'message' => lang('App.client_deleted')
                ]);
            } else {
                log_message('error', 'Failed to delete client with ID: ' . $id);
                return $this->response->setJSON([
                    'status' => false,
                    'message' => lang('App.something_wrong')
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during client deletion: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => lang('App.something_wrong') . ': ' . $e->getMessage()
            ]);
        }
    }
} 