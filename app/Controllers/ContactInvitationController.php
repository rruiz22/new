<?php

namespace App\Controllers;

use App\Models\ContactInvitationModel;
use App\Models\ContactGroupModel;
use App\Models\ClientModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Models\UserIdentityModel;

class ContactInvitationController extends BaseController
{
    protected $invitationModel;
    protected $groupModel;
    protected $clientModel;
    protected $userModel;
    protected $identityModel;
    
    public function __construct()
    {
        $this->invitationModel = new ContactInvitationModel();
        $this->groupModel = new ContactGroupModel();
        $this->clientModel = new ClientModel();
        $this->userModel = new UserModel();
        $this->identityModel = new UserIdentityModel();
        
        helper(['form', 'url', 'email']);
    }
    
    /**
     * Display invitations management page
     */
    public function index()
    {
        $invitations = $this->invitationModel->getPendingInvitations(50);
        
        $data = [
            'title' => 'Gestión de Invitaciones',
            'invitations' => $invitations,
        ];
        
        return view('contact_invitations/index', $data);
    }
    
    /**
     * Show invitation form
     */
    public function create()
    {
        $groups = $this->groupModel->where('is_active', 1)->orderBy('sort_order')->findAll();
        $clients = $this->clientModel->where('active', 1)->orderBy('name')->findAll();
        
        $data = [
            'title' => 'Enviar Invitación',
            'groups' => $groups,
            'clients' => $clients,
        ];
        
        return view('contact_invitations/create', $data);
    }
    
    /**
     * Send invitation
     */
    public function send()
    {
        $rules = [
            'email' => 'required|valid_email',
            'first_name' => 'permit_empty|max_length[100]',
            'last_name' => 'permit_empty|max_length[100]',
            'client_id' => 'permit_empty|integer',
            'assigned_group_id' => 'permit_empty|integer',
            'message' => 'permit_empty|max_length[1000]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        
        // Check if email already has a pending invitation
        if ($this->invitationModel->hasPendingInvitation($email)) {
            session()->setFlashdata('error', 'Ya existe una invitación pendiente para este email.');
            return redirect()->back()->withInput();
        }
        
        // Check if user already exists in Shield's auth_identities
        $existingIdentity = $this->identityModel
            ->where('type', 'email_password')
            ->where('secret', $email)
            ->first();
            
        if ($existingIdentity) {
            session()->setFlashdata('error', 'Ya existe un usuario con este email.');
            return redirect()->back()->withInput();
        }
        
        $invitationData = [
            'email' => $email,
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'client_id' => $this->request->getPost('client_id') ?: null,
            'assigned_group_id' => $this->request->getPost('assigned_group_id') ?: null,
            'message' => $this->request->getPost('message'),
        ];
        
        $invitationId = $this->invitationModel->createInvitation($invitationData);
        
        if ($invitationId) {
            // Get the invitation with token
            $invitation = $this->invitationModel->find($invitationId);
            
            // Send email
            if ($this->sendInvitationEmail($invitation)) {
                // Update sent_at
                $this->invitationModel->update($invitationId, ['sent_at' => date('Y-m-d H:i:s')]);
                
                session()->setFlashdata('success', 'Invitación enviada correctamente.');
                return redirect()->to('contact-invitations');
            } else {
                session()->setFlashdata('error', 'Error al enviar el email de invitación.');
                return redirect()->back()->withInput();
            }
        } else {
            session()->setFlashdata('error', 'Error al crear la invitación.');
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Accept invitation page
     */
    public function accept($token = null)
    {
        if (!$token) {
            session()->setFlashdata('error', 'Token de invitación inválido.');
            return redirect()->to('login');
        }
        
        if (!$this->invitationModel->validateToken($token)) {
            session()->setFlashdata('error', 'La invitación ha expirado o no es válida.');
            return redirect()->to('login');
        }
        
        $invitation = $this->invitationModel->getByToken($token);
        
        $data = [
            'title' => 'Aceptar Invitación',
            'invitation' => $invitation,
            'token' => $token,
        ];
        
        return view('contact_invitations/accept', $data);
    }
    
    /**
     * Process invitation acceptance
     */
    public function processAcceptance()
    {
        $token = $this->request->getPost('token');
        
        if (!$this->invitationModel->validateToken($token)) {
            session()->setFlashdata('error', 'La invitación ha expirado o no es válida.');
            return redirect()->to('login');
        }
        
        $rules = [
            'first_name' => 'required|max_length[100]',
            'last_name' => 'required|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $invitation = $this->invitationModel->getByToken($token);
        
        // Create user using Shield
        $users = auth()->getProvider();
        
        $userData = [
            'username' => $this->request->getPost('username'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'user_type' => 'client',
            'client_id' => $invitation['client_id'],
            'active' => 1,
        ];
        
        $user = $users->create($userData);
        
        if ($user) {
            // Create email identity with Shield
            $user->createEmailIdentity([
                'email' => $invitation['email'],
                'password' => $this->request->getPost('password')
            ]);
            
            // Assign to group if specified
            if ($invitation['assigned_group_id']) {
                $this->groupModel->assignUserToGroup($user->id, $invitation['assigned_group_id'], $invitation['sent_by']);
            }
            
            // Mark invitation as accepted
            $this->invitationModel->acceptInvitation($token);
            
            // Auto-login the user using Shield
            auth()->login($user);
            
            session()->setFlashdata('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');
            return redirect()->to('dashboard');
        } else {
            session()->setFlashdata('error', 'Error al crear la cuenta.');
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Cancel invitation
     */
    public function cancel()
    {
        $id = $this->request->getPost('id');
        
        if ($this->invitationModel->cancelInvitation($id)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Invitación cancelada correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Error al cancelar la invitación'
            ]);
        }
    }
    
    /**
     * Resend invitation
     */
    public function resend()
    {
        $id = $this->request->getPost('id');
        $invitation = $this->invitationModel->find($id);
        
        if (!$invitation) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invitación no encontrada'
            ]);
        }
        
        if ($this->sendInvitationEmail($invitation)) {
            $this->invitationModel->resendInvitation($id);
            
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Invitación reenviada correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Error al enviar el email'
            ]);
        }
    }
    
    /**
     * Get invitations for a specific group
     */
    public function getGroupInvitations()
    {
        $groupId = $this->request->getGet('group_id');
        
        if (!$groupId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'ID de grupo requerido'
            ]);
        }
        
        $invitations = $this->invitationModel->getInvitationsByGroup($groupId);
        
        return $this->response->setJSON([
            'status' => true,
            'data' => $invitations
        ]);
    }
    
    /**
     * Send invitation email
     */
    private function sendInvitationEmail($invitation): bool
    {
        $email = \Config\Services::email();
        
        $senderName = session()->get('first_name') . ' ' . session()->get('last_name');
        $invitationUrl = base_url("invitations/accept/{$invitation['invitation_token']}");
        
        // Load email template
        $message = view('emails/contact_invitation', [
            'invitation' => $invitation,
            'senderName' => $senderName,
            'invitationUrl' => $invitationUrl,
        ]);
        
        $email->setTo($invitation['email']);
        $email->setSubject('Invitación para unirse como contacto');
        $email->setMessage($message);
        
        return $email->send();
    }
} 