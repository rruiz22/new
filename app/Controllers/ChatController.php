<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ChatConversationModel;
use App\Models\ChatMessageModel;
use App\Models\ChatChannelModel;
use App\Models\ChatChannelMemberModel;

class ChatController extends BaseController
{
    protected $chatConversationModel;
    protected $chatMessageModel;
    protected $chatChannelModel;
    protected $chatChannelMemberModel;
    protected $currentUserId;
    
    public function __construct()
    {
        $this->chatConversationModel = new ChatConversationModel();
        $this->chatMessageModel = new ChatMessageModel();
        $this->chatChannelModel = new ChatChannelModel();
        $this->chatChannelMemberModel = new ChatChannelMemberModel();
        
        // Obtener el ID de usuario actual usando CodeIgniter Shield
        $this->currentUserId = auth()->id() ?? session()->get('user_id') ?? 1;
    }
    
    public function index()
    {
        // Configurar host del WebSocket
        $wsHost = 'localhost';
        if (!is_cli() && isset($_SERVER['HTTP_HOST'])) {
            // Si estamos en desarrollo local, usar localhost
            if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
                strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
                $wsHost = '127.0.0.1';
            } else {
                $wsHost = $_SERVER['HTTP_HOST'];
            }
        }
        
        $data = [
            'title' => lang('App.chat'),
            'wsHost' => $wsHost,
            'wsPort' => '8080', // Puerto del servidor WebSocket
            'currentUserId' => $this->currentUserId,
            'wsToken' => $this->generateWebSocketToken() // Generar token para conexión segura
        ];
        
        return view('chat/index', $data);
    }
    
    /**
     * Obtener contactos (todos los usuarios del sistema) y canales
     */
    public function getContacts()
    {
        // Load avatar helper
        helper('avatar');
        
        try {
            // Obtener todas las conversaciones activas de este usuario
            $conversations = $this->chatConversationModel->getActiveConversations($this->currentUserId);
            
            // Obtener todos los canales del usuario
            $channels = $this->chatChannelModel->getUserChannels($this->currentUserId);
            
            // Obtener TODOS los usuarios del sistema (excepto el actual)
            $userModel = new \App\Models\CustomUserModel();
            $allUsers = $userModel->where('id !=', $this->currentUserId)
                                 ->where('active', 1)
                                 ->orderBy('username', 'ASC')
                                 ->findAll();
            
            $contacts = [];
            
            // Si no hay usuarios reales, crear algunos de ejemplo para probar
            if (empty($allUsers)) {
                $exampleUsers = [
                    (object)[
                        'id' => 2,
                        'username' => 'arruiz',
                        'email' => 'admin@example.com',
                        'user_type' => 'client',
                        'avatar' => null,
                        'avatar_style' => 'initials'
                    ],
                    (object)[
                        'id' => 3,
                        'username' => 'bill',
                        'email' => 'bill@example.com',
                        'user_type' => 'client',
                        'avatar' => null,
                        'avatar_style' => 'initials'
                    ],
                    (object)[
                        'id' => 4,
                        'username' => 'david.brown',
                        'email' => 'david.brown@example.com',
                        'user_type' => 'client',
                        'avatar' => null,
                        'avatar_style' => 'initials'
                    ],
                    (object)[
                        'id' => 5,
                        'username' => 'jane.smith',
                        'email' => 'jane.smith@example.com',
                        'user_type' => 'client',
                        'avatar' => null,
                        'avatar_style' => 'initials'
                    ],
                    (object)[
                        'id' => 6,
                        'username' => 'john.doe',
                        'email' => 'john.doe@example.com',
                        'user_type' => 'client',
                        'avatar' => null,
                        'avatar_style' => 'initials'
                    ]
                ];
                $allUsers = $exampleUsers;
            }
            
            // Mapear conversaciones existentes
            $existingConversations = [];
            foreach ($conversations as $conv) {
                $otherUserId = ($conv['user_one'] == $this->currentUserId) ? $conv['user_two'] : $conv['user_one'];
                $existingConversations[$otherUserId] = $conv['id'];
            }
            
            // Procesar TODOS los usuarios para mostrarlos en la lista de contactos
            foreach ($allUsers as $user) {
                // Obtener avatar usando el sistema de avatares
                $avatarUrl = getAvatarUrl($user, 45, $user->avatar_style ?? 'initials');
                
                $contact = [
                    'id' => $user->id,
                    'user_id' => $user->id,
                    'name' => $user->username,
                    'email' => $user->email ?? '',
                    'avatar' => $avatarUrl,
                    'status' => rand(0, 1) ? 'online' : 'offline', // Simulado - implementar sistema real
                    'user_type' => $user->user_type ?? 'user', // Mostrar tipo de usuario
                    'is_client' => ($user->user_type ?? '') === 'client',
                    'is_online' => rand(0, 1) ? true : false, // Simulado
                ];
                
                // Si es un cliente, obtener información de la empresa
                if ($contact['is_client']) {
                    // Buscar en la tabla de contactos para obtener el cliente asociado
                    $db = \Config\Database::connect();
                    $contactQuery = $db->table('contacts')
                        ->select('contacts.*, clients.name as client_name, clients.id as client_id')
                        ->join('clients', 'clients.id = contacts.client_id', 'left')
                        ->where('contacts.user_id', $user->id)
                        ->get()
                        ->getRowArray();
                    
                    if ($contactQuery && $contactQuery['client_name']) {
                        $contact['client_name'] = $contactQuery['client_name'];
                        $contact['client_id'] = $contactQuery['client_id'];
                        $contact['position'] = $contactQuery['position'] ?? '';
                    } else {
                        // Si no se encuentra en contacts, usar un cliente por defecto
                        $contact['client_name'] = 'Cliente Individual';
                        $contact['client_id'] = 0;
                        $contact['position'] = '';
                    }
                } else {
                    $contact['client_name'] = null;
                    $contact['client_id'] = null;
                    $contact['position'] = '';
                }
                
                // Si existe conversación previa, agregar información
                if (isset($existingConversations[$user->id])) {
                    $conversationId = $existingConversations[$user->id];
                    
                    // Obtener último mensaje
                    $lastMessage = $this->chatMessageModel->where('conversation_id', $conversationId)
                                                       ->orderBy('created_at', 'DESC')
                                                       ->first();
                    
                    if ($lastMessage) {
                        $contact['last_message'] = $lastMessage['message'];
                        $contact['last_time'] = $this->formatMessageTime($lastMessage['created_at']);
                        $contact['conversation_id'] = $conversationId;
                        
                        // Contar mensajes no leídos
                        $contact['unread_count'] = $this->chatMessageModel->countUnreadMessages($conversationId, $this->currentUserId);
                    } else {
                        $contact['last_message'] = '';
                        $contact['last_time'] = '';
                        $contact['conversation_id'] = $conversationId;
                        $contact['unread_count'] = 0;
                    }
                } else {
                    // No hay conversación previa
                    $contact['last_message'] = '';
                    $contact['last_time'] = '';
                    $contact['conversation_id'] = null;
                    $contact['unread_count'] = 0;
                }
                
                $contacts[] = $contact;
            }
            
            // Procesar canales (group chats)
            $groupChats = [];
            foreach ($channels as $channel) {
                // Obtener avatar del grupo (usar icono por defecto)
                $groupAvatarUrl = base_url('assets/images/groups/default.svg');
                
                $groupChat = [
                    'id' => 'group_' . $channel['id'],
                    'channel_id' => $channel['id'],
                    'name' => $channel['name'],
                    'description' => $channel['description'] ?? '',
                    'avatar' => $groupAvatarUrl,
                    'status' => 'group',
                    'is_group' => true,
                    'member_count' => $this->chatChannelMemberModel->where('channel_id', $channel['id'])->countAllResults()
                ];
                
                // Use consistent group ID format for conversation_id
                $conversationId = 'group_' . $channel['id'];
                $lastMessage = $this->chatMessageModel->where('conversation_id', $conversationId)
                                                   ->orderBy('created_at', 'DESC')
                                                   ->first();
                
                if ($lastMessage) {
                    $groupChat['last_message'] = $lastMessage['message'];
                    $groupChat['last_time'] = $this->formatMessageTime($lastMessage['created_at']);
                    $groupChat['conversation_id'] = $conversationId;
                    
                    // Contar mensajes no leídos
                    $groupChat['unread_count'] = $this->chatMessageModel->countUnreadMessages($conversationId, $this->currentUserId);
                } else {
                    $groupChat['last_message'] = '';
                    $groupChat['last_time'] = '';
                    $groupChat['conversation_id'] = $conversationId;
                    $groupChat['unread_count'] = 0;
                }
                
                $groupChats[] = $groupChat;
            }
            
            return $this->response->setJSON([
                'success' => true,
                'contacts' => $contacts,
                'groups' => $groupChats,
                'total_contacts' => count($contacts),
                'total_groups' => count($groupChats)
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getContacts: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'contacts' => [],
                'groups' => [],
                'error' => 'Error al cargar contactos'
            ]);
        }
    }
    
    /**
     * Obtener canales a los que pertenece el usuario
     */
    public function getChannels()
    {
        $channels = $this->chatChannelModel->getUserChannels($this->currentUserId);
        
        foreach ($channels as &$channel) {
            // Obtener último mensaje y conteo de mensajes no leídos
            $conversationId = 'group_' . $channel['id']; // Use consistent group format
            $lastMessage = $this->chatMessageModel->where('conversation_id', $conversationId)
                                               ->orderBy('created_at', 'DESC')
                                               ->first();
            
            if ($lastMessage) {
                $channel['last_message'] = $lastMessage['message'];
                $channel['last_time'] = $this->formatMessageTime($lastMessage['created_at']);
                
                // Contar mensajes no leídos
                $channel['unread_count'] = $this->chatMessageModel->countUnreadMessages($conversationId, $this->currentUserId);
            } else {
                $channel['last_message'] = '';
                $channel['last_time'] = '';
                $channel['unread_count'] = 0;
            }
            
            // Obtener conteo de miembros
            $channel['members'] = $this->chatChannelMemberModel->where('channel_id', $channel['id'])->countAllResults();
        }
        
        return $this->response->setJSON(['channels' => $channels]);
    }
    
    /**
     * Obtener mensajes de una conversación directa
     */
    public function getMessages($conversationId = null)
    {
        if (!$conversationId) {
            return $this->response->setJSON(['messages' => []]);
        }
        
        // Verificar si el usuario pertenece a esta conversación
        $conversation = $this->chatConversationModel->find($conversationId);
        if (!$conversation || ($conversation['user_one'] != $this->currentUserId && $conversation['user_two'] != $this->currentUserId)) {
            return $this->response->setJSON(['error' => 'No tienes acceso a esta conversación']);
        }
        
        // Marcar mensajes como leídos
        $this->chatMessageModel->markAllAsRead($conversationId, $this->currentUserId);
        
        // Obtener mensajes
        $messages = $this->chatMessageModel->getConversationMessages($conversationId, 50);
        
        // Formatear mensajes para la respuesta
        $formattedMessages = [];
        foreach ($messages as $message) {
            $formattedMessages[] = [
                'id' => $message['id'],
                'sender_id' => $message['sender_id'],
                'is_own' => $message['sender_id'] == $this->currentUserId,
                'message' => $message['message'],
                'created_at' => $message['created_at'],
                'is_read' => $message['is_read']
            ];
        }
        
        // Invertir para mostrar más recientes al final
        $formattedMessages = array_reverse($formattedMessages);
        
        return $this->response->setJSON(['messages' => $formattedMessages]);
    }
    
    /**
     * Obtener mensajes de un canal
     */
    public function getChannelMessages($channelId = null)
    {
        if (!$channelId) {
            return $this->response->setJSON(['messages' => []]);
        }
        
        // Verificar si el usuario pertenece a este canal
        if (!$this->chatChannelMemberModel->isMember($channelId, $this->currentUserId)) {
            return $this->response->setJSON(['error' => 'No tienes acceso a este canal']);
        }
        
        // Use consistent group ID format
        $conversationId = 'group_' . $channelId;
        
        // Marcar mensajes como leídos
        $this->chatMessageModel->markAllAsRead($conversationId, $this->currentUserId);
        
        // Obtener mensajes
        $messages = $this->chatMessageModel->getConversationMessages($conversationId, 50);
        
        // Load avatar helper
        helper('avatar');
        
        // Obtener información de los remitentes
        $senderIds = array_unique(array_column($messages, 'sender_id'));
        
        $userInfo = [];
        
        if (!empty($senderIds)) {
            $userModel = new \App\Models\CustomUserModel();
            $users = $userModel->whereIn('id', $senderIds)->findAll();
            
            foreach ($users as $user) {
                // Use avatar system for consistent avatar URLs
                $avatarUrl = getAvatarUrl($user, 35, $user->avatar_style ?? 'initials');
                
                $userInfo[$user->id] = [
                    'name' => $user->username, 
                    'avatar' => $avatarUrl
                ];
            }
        }
        
        // Formatear mensajes para la respuesta
        $formattedMessages = [];
        foreach ($messages as $message) {
            $senderInfo = $userInfo[$message['sender_id']] ?? ['name' => 'Usuario', 'avatar' => getDefaultAvatar(35, 'initials')];
            
            $formattedMessages[] = [
                'id' => $message['id'],
                'sender_id' => $message['sender_id'],
                'sender_name' => $senderInfo['name'],
                'sender_avatar' => $senderInfo['avatar'],
                'is_own' => $message['sender_id'] == $this->currentUserId,
                'message' => $message['message'],
                'created_at' => $message['created_at'],
                'is_read' => $message['is_read']
            ];
        }
        
        // Invertir para mostrar más recientes al final
        $formattedMessages = array_reverse($formattedMessages);
        
        return $this->response->setJSON(['messages' => $formattedMessages]);
    }
    
    /**
     * Crear nuevo canal
     */
    public function createChannel()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|max_length[255]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        // Obtener datos del POST (FormData)
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $members = $this->request->getPost('members') ? json_decode($this->request->getPost('members'), true) : [];
        
        $channelId = $this->chatChannelModel->createChannel($name, $description, $this->currentUserId);
        
        if (!$channelId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear el canal'
            ]);
        }
        
        // Add members to the channel if provided
        if (!empty($members)) {
            foreach ($members as $memberId) {
                $this->chatChannelMemberModel->insert([
                    'channel_id' => $channelId,
                    'user_id' => $memberId
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'channel' => $this->chatChannelModel->find($channelId)
        ]);
    }
    
    /**
     * Obtener o crear una conversación
     */
    public function getOrCreateConversation($otherUserId = null)
    {
        // Get user_id from POST data if not provided as parameter
        if (!$otherUserId) {
            $otherUserId = $this->request->getPost('user_id');
        }
        
        if (!$otherUserId) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'ID de usuario no válido'
            ]);
        }
        
        // Verificar si el otro usuario existe
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('id, username, COALESCE(avatar, "assets/images/users/avatar-1.jpg") as avatar');
        $builder->where('id', $otherUserId);
        $builder->where('active', 1);
        $user = $builder->get()->getRowArray();
        
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }
        
        try {
            // Obtener o crear conversación
            $conversation = $this->chatConversationModel->getOrCreateConversation($this->currentUserId, $otherUserId);
            
            if (!$conversation) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al crear la conversación'
                ]);
            }
            
            // Rename username field to name for frontend compatibility
            $user['name'] = $user['username'];
            unset($user['username']);
            
            // Añadir status temporal (en producción se obtendría de una tabla de usuarios online)
            $user['status'] = 'online';
            
            return $this->response->setJSON([
                'success' => true,
                'conversation_id' => $conversation['id'],
                'conversation' => $conversation,
                'user_info' => $user,
                'message' => 'Conversación creada exitosamente'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error creating conversation: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear la conversación: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Obtener información de un canal
     */
    public function getChannelInfo($channelId = null)
    {
        if (!$channelId) {
            return $this->response->setJSON(['error' => 'ID de canal no válido']);
        }
        
        // Verificar si el usuario pertenece a este canal
        if (!$this->chatChannelMemberModel->isMember($channelId, $this->currentUserId)) {
            return $this->response->setJSON(['error' => 'No tienes acceso a este canal']);
        }
        
        // Obtener información del canal
        $channel = $this->chatChannelModel->find($channelId);
        
        if (!$channel) {
            return $this->response->setJSON(['error' => 'Canal no encontrado']);
        }
        
        // Obtener número de miembros
        $channel['members'] = $this->chatChannelMemberModel->where('channel_id', $channelId)->countAllResults();
        
        return $this->response->setJSON([
            'channel' => $channel
        ]);
    }
    
    /**
     * Enviar un mensaje directo (fallback si WebSocket no está disponible)
     */
    public function sendMessage()
    {
        // Log para depuración
        log_message('info', 'ChatController::sendMessage - Iniciando envío de mensaje');
        log_message('info', 'Current User ID: ' . $this->currentUserId);
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));
        
        $rules = [
            'conversation_id' => 'required',
            'message' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        // Obtener datos del POST (FormData)
        $conversationId = $this->request->getPost('conversation_id');
        $message = $this->request->getPost('message');
        
        log_message('info', "Conversation ID: {$conversationId}, Message: {$message}");
        
        // Verificar que el usuario pertenece a esta conversación
        $conversation = $this->chatConversationModel->find($conversationId);
        if (!$conversation) {
            log_message('error', "Conversation not found: {$conversationId}");
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Conversación no encontrada'
            ]);
        }
        
        if ($conversation['user_one'] != $this->currentUserId && $conversation['user_two'] != $this->currentUserId) {
            log_message('error', "User {$this->currentUserId} does not have access to conversation {$conversationId}");
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes acceso a esta conversación'
            ]);
        }
        
        try {
            // Guardar mensaje
            $messageId = $this->chatMessageModel->insert([
                'conversation_id' => $conversationId,
                'sender_id' => $this->currentUserId,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            log_message('info', "Message inserted with ID: {$messageId}");
            
            // Actualizar hora de actualización de la conversación
            $this->chatConversationModel->update($conversationId, [
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            log_message('info', 'Message sent successfully');
            
            return $this->response->setJSON([
                'success' => true,
                'message_id' => $messageId,
                'message' => [
                    'id' => $messageId,
                    'sender_id' => $this->currentUserId,
                    'message' => $message,
                    'created_at' => date('Y-m-d H:i:s'),
                    'conversation_id' => $conversationId
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error sending message: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al enviar el mensaje: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Enviar un mensaje a un canal (fallback si WebSocket no está disponible)
     */
    public function sendChannelMessage()
    {
        $rules = [
            'channel_id' => 'required',
            'message' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        // Obtener datos del POST (FormData)
        $channelId = $this->request->getPost('channel_id');
        $message = $this->request->getPost('message');
        
        // Verificar si el usuario pertenece al canal
        if (!$this->chatChannelMemberModel->isMember($channelId, $this->currentUserId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes acceso a este canal'
            ]);
        }
        
        // Guardar mensaje (usando conversationId como channelId con prefijo group_)
        $conversationId = 'group_' . $channelId;
        $messageId = $this->chatMessageModel->insert([
            'conversation_id' => $conversationId,
            'sender_id' => $this->currentUserId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Get the saved message with full data for response
        $savedMessage = $this->chatMessageModel->find($messageId);
        
        return $this->response->setJSON([
            'success' => true,
            'message_id' => $messageId,
            'message' => [
                'id' => $messageId,
                'sender_id' => $this->currentUserId,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s'),
                'conversation_id' => $conversationId
            ]
        ]);
    }
    
    /**
     * Marcar mensajes como leídos
     */
    public function markAsRead()
    {
        $conversationId = $this->request->getPost('conversation_id');
        
        if (!$conversationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID de conversación requerido'
            ]);
        }
        
        $this->chatMessageModel->markAllAsRead($conversationId, $this->currentUserId);
        
        return $this->response->setJSON([
            'success' => true
        ]);
    }
    
    /**
     * Generar token para WebSocket usando JWT
     */
    private function generateWebSocketToken()
    {
        $key = getenv('JWT_SECRET_KEY') ?: 'chatSecretKey12345';
        $issuedAt = time();
        $expiration = $issuedAt + 3600; // Token válido por 1 hora
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expiration,
            'user_id' => $this->currentUserId,
            'random' => uniqid()
        ];
        
        return \Firebase\JWT\JWT::encode($payload, $key, 'HS256');
    }
    
    /**
     * Formatear tiempo de mensaje
     */
    private function formatMessageTime($datetime)
    {
        $timestamp = strtotime($datetime);
        $now = time();
        $today = strtotime('today');
        $yesterday = strtotime('yesterday');
        
        if ($timestamp >= $today) {
            return date('h:i a', $timestamp);
        } elseif ($timestamp >= $yesterday) {
            return 'Ayer';
        } else {
            return date('d/m/Y', $timestamp);
        }
    }

    /**
     * Añadir un nuevo contacto
     */
    public function contactAdd()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        // Obtener datos del POST (FormData)
        $email = $this->request->getPost('email');
        $message = $this->request->getPost('message');
        
        // Buscar usuario por email
        $db = \Config\Database::connect();
        $builder = $db->table('auth_identities');
        $builder->select('user_id');
        $builder->where('secret', $email);
        $builder->where('type', 'email_password');
        $user = $builder->get()->getRowArray();
        
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuario no encontrado con ese email'
            ]);
        }
        
        $otherUserId = $user['user_id'];
        
        // Verificar que no sea el mismo usuario
        if ($otherUserId == $this->currentUserId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No puedes agregarte a ti mismo como contacto'
            ]);
        }
        
        // Crear o obtener conversación
        $conversation = $this->chatConversationModel->getOrCreateConversation($this->currentUserId, $otherUserId);
        
        // Si hay un mensaje inicial, enviarlo
        if (!empty($message)) {
            $this->chatMessageModel->insert([
                'conversation_id' => $conversation['id'],
                'sender_id' => $this->currentUserId,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Contacto añadido correctamente',
            'conversation_id' => $conversation['id']
        ]);
    }

    /**
     * Subir archivo adjunto
     */
    public function uploadAttachment()
    {
        $rules = [
            'conversation_id' => 'required',
            'message_id' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        $conversationId = $this->request->getPost('conversation_id');
        $messageId = $this->request->getPost('message_id');
        
        // Verificar que el mensaje pertenezca al usuario actual
        $message = $this->chatMessageModel->find($messageId);
        if (!$message || $message['sender_id'] != $this->currentUserId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No tienes permiso para adjuntar archivos a este mensaje'
            ]);
        }
        
        // Verificar que se haya enviado un archivo
        $file = $this->request->getFile('file');
        if (!$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo no válido'
            ]);
        }
        
        // Verificar tamaño y tipo de archivo
        if ($file->getSize() > 10 * 1024 * 1024) { // Máximo 10MB
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El archivo supera el tamaño máximo permitido de 10MB'
            ]);
        }
        
        // Crear directorio si no existe
        $uploadPath = WRITEPATH . 'uploads/chat';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Generar un nombre único para el archivo
        $newName = $file->getRandomName();
        
        // Mover el archivo
        if (!$file->move($uploadPath, $newName)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al subir el archivo'
            ]);
        }
        
        // Guardar información en la base de datos
        $chatAttachmentModel = new \App\Models\ChatAttachmentModel();
        $attachmentId = $chatAttachmentModel->insert([
            'message_id' => $messageId,
            'file_name' => $newName,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Actualizar mensaje para indicar que tiene archivos adjuntos
        $this->chatMessageModel->update($messageId, [
            'has_attachment' => 1
        ]);
        
        return $this->response->setJSON([
            'success' => true,
            'attachment' => [
                'id' => $attachmentId,
                'file_name' => $newName,
                'original_name' => $file->getClientName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'url' => base_url('uploads/chat/' . $newName)
            ]
        ]);
    }
    
    /**
     * Descargar archivo adjunto
     */
    public function downloadAttachment($attachmentId)
    {
        $chatAttachmentModel = new \App\Models\ChatAttachmentModel();
        $attachment = $chatAttachmentModel->find($attachmentId);
        
        if (!$attachment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo no encontrado'
            ]);
        }
        
        $filePath = WRITEPATH . 'uploads/chat/' . $attachment['file_name'];
        
        if (!file_exists($filePath)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo no encontrado en el servidor'
            ]);
        }
        
        return $this->response->download($filePath, null);
    }

    /**
     * Archive a conversation
     */
    public function archiveConversation()
    {
        // Get data from request
        $json = $this->request->getJSON();
        $conversationId = $json->id ?? null;

        if (!$conversationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Conversation ID is required'
            ]);
        }

        // Check if the user belongs to this conversation
        $conversation = $this->chatConversationModel->find($conversationId);
        if (!$conversation || ($conversation['user_one'] != $this->currentUserId && $conversation['user_two'] != $this->currentUserId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have access to this conversation'
            ]);
        }

        // Update conversation to set archived flag
        $this->chatConversationModel->update($conversationId, [
            'archived_by_user_' . ($conversation['user_one'] == $this->currentUserId ? 'one' : 'two') => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Conversation archived successfully'
        ]);
    }

    /**
     * Archive a channel
     */
    public function archiveChannel()
    {
        // Get data from request
        $json = $this->request->getJSON();
        $channelId = $json->id ?? null;

        if (!$channelId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Channel ID is required'
            ]);
        }

        // Check if user is a member of the channel
        if (!$this->chatChannelMemberModel->isMember($channelId, $this->currentUserId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have access to this channel'
            ]);
        }

        // Update the member record to set archived flag
        $this->chatChannelMemberModel->where('channel_id', $channelId)
                                    ->where('user_id', $this->currentUserId)
                                    ->set(['archived' => 1])
                                    ->update();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Channel archived successfully'
        ]);
    }

    /**
     * Mute a conversation
     */
    public function muteConversation()
    {
        // Get data from request
        $json = $this->request->getJSON();
        $conversationId = $json->id ?? null;

        if (!$conversationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Conversation ID is required'
            ]);
        }

        // Check if the user belongs to this conversation
        $conversation = $this->chatConversationModel->find($conversationId);
        if (!$conversation || ($conversation['user_one'] != $this->currentUserId && $conversation['user_two'] != $this->currentUserId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have access to this conversation'
            ]);
        }

        // Update conversation to set muted flag
        $this->chatConversationModel->update($conversationId, [
            'muted_by_user_' . ($conversation['user_one'] == $this->currentUserId ? 'one' : 'two') => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Conversation muted successfully'
        ]);
    }

    /**
     * Mute a channel
     */
    public function muteChannel()
    {
        // Get data from request
        $json = $this->request->getJSON();
        $channelId = $json->id ?? null;

        if (!$channelId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Channel ID is required'
            ]);
        }

        // Check if user is a member of the channel
        if (!$this->chatChannelMemberModel->isMember($channelId, $this->currentUserId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have access to this channel'
            ]);
        }

        // Update the member record to set muted flag
        $this->chatChannelMemberModel->where('channel_id', $channelId)
                                    ->where('user_id', $this->currentUserId)
                                    ->set(['muted' => 1])
                                    ->update();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Channel muted successfully'
        ]);
    }

    /**
     * Delete a conversation
     */
    public function deleteConversation()
    {
        // Get data from request
        $json = $this->request->getJSON();
        $conversationId = $json->id ?? null;

        if (!$conversationId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Conversation ID is required'
            ]);
        }

        // Check if the user belongs to this conversation
        $conversation = $this->chatConversationModel->find($conversationId);
        if (!$conversation || ($conversation['user_one'] != $this->currentUserId && $conversation['user_two'] != $this->currentUserId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have access to this conversation'
            ]);
        }

        // Registrar acción para depuración
        log_message('debug', "Eliminando conversación {$conversationId} por usuario {$this->currentUserId}");

        // Delete the messages
        $messagesDeleted = $this->chatMessageModel->where('conversation_id', $conversationId)->delete();
        
        // Delete the conversation
        $conversationDeleted = $this->chatConversationModel->delete($conversationId);

        // Registrar resultado para depuración
        log_message('debug', "Resultado: Mensajes eliminados: {$messagesDeleted}, Conversación eliminada: {$conversationDeleted}");

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Conversation deleted successfully'
        ]);
    }

    /**
     * Delete a channel (or leave it if not the owner)
     */
    public function deleteChannel()
    {
        // Get data from request
        $json = $this->request->getJSON();
        $channelId = $json->id ?? null;

        if (!$channelId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Channel ID is required'
            ]);
        }

        // Get the channel info
        $channel = $this->chatChannelModel->find($channelId);
        if (!$channel) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Channel not found'
            ]);
        }

        // Check if user is a member of the channel
        if (!$this->chatChannelMemberModel->isMember($channelId, $this->currentUserId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have access to this channel'
            ]);
        }

        // If user is the owner (creator), delete the entire channel
        if ($channel['created_by'] == $this->currentUserId) {
            // Delete all messages
            $this->chatMessageModel->where('conversation_id', 'c_' . $channelId)->delete();
            
            // Delete all members
            $this->chatChannelMemberModel->where('channel_id', $channelId)->delete();
            
            // Delete the channel
            $this->chatChannelModel->delete($channelId);
            
            $message = 'Channel deleted successfully';
        } else {
            // If not the owner, just remove the user from the channel
            $this->chatChannelMemberModel->where('channel_id', $channelId)
                                        ->where('user_id', $this->currentUserId)
                                        ->delete();
            
            $message = 'You have left the channel';
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message
        ]);
    }

    // Método para obtener una conexión fresca a la base de datos
    protected function getDb()
    {
        try {
            return \Config\Database::connect();
        } catch (\Exception $e) {
            log_message('error', 'Error de conexión a la base de datos: ' . $e->getMessage());
            return null;
        }
    }
    
    // Método para ejecutar una operación de base de datos con reintentos
    protected function executeDbOperation($callback, $maxRetries = 3)
    {
        $retries = 0;
        while ($retries < $maxRetries) {
            try {
                $db = $this->getDb();
                if ($db === null) {
                    throw new \Exception('No se pudo conectar a la base de datos');
                }
                return $callback($db);
            } catch (\Exception $e) {
                $retries++;
                log_message('error', 'Error en operación de BD (intento ' . $retries . '): ' . $e->getMessage());
                
                if ($retries >= $maxRetries) {
                    // Si fallaron todos los reintentos, lanzar la excepción
                    throw $e;
                }
                
                // Esperar un momento antes de reintentar
                sleep(1);
            }
        }
    }

    /**
     * Obtener todos los usuarios disponibles para añadir a un grupo
     */
    public function getAvailableUsers()
    {
        helper('avatar');
        
        try {
            // Obtener todos los usuarios excepto el actual
            $userModel = new \App\Models\CustomUserModel();
            $users = $userModel->where('id !=', $this->currentUserId)
                              ->where('active', 1)
                              ->orderBy('username', 'ASC')
                              ->findAll();
            
            $availableUsers = [];
            foreach ($users as $user) {
                $avatarUrl = getAvatarUrl($user, 32, $user->avatar_style ?? 'initials');
                
                $availableUsers[] = [
                    'id' => $user->id,
                    'name' => $user->username,
                    'email' => $user->email ?? '',
                    'avatar' => $avatarUrl,
                    'user_type' => $user->user_type ?? 'user',
                    'is_client' => ($user->user_type ?? '') === 'client'
                ];
            }
            
            return $this->response->setJSON([
                'users' => $availableUsers,
                'total' => count($availableUsers)
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getAvailableUsers: ' . $e->getMessage());
            return $this->response->setJSON([
                'users' => [],
                'error' => 'Error al cargar usuarios'
            ]);
        }
    }

    /**
     * Crear un nuevo grupo/canal
     */
    public function createGroup()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Solicitud inválida']);
        }
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[50]',
            'description' => 'permit_empty|max_length[200]',
            'members' => 'required' // IDs de usuarios separados por coma
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validation->getErrors()
            ]);
        }
        
        try {
            $name = $this->request->getPost('name');
            $description = $this->request->getPost('description') ?? '';
            $memberIds = explode(',', $this->request->getPost('members'));
            
            // Filtrar IDs válidos
            $memberIds = array_filter($memberIds, 'is_numeric');
            
            if (empty($memberIds)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Debe seleccionar al menos un miembro'
                ]);
            }
            
            // Crear el canal/grupo
            $channelData = [
                'name' => $name,
                'description' => $description,
                'created_by' => $this->currentUserId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $channelId = $this->chatChannelModel->insert($channelData);
            
            if (!$channelId) {
                throw new \Exception('Error al crear el grupo');
            }
            
            // Añadir al creador como administrador
            $this->chatChannelMemberModel->insert([
                'channel_id' => $channelId,
                'user_id' => $this->currentUserId,
                'role' => 'admin',
                'joined_at' => date('Y-m-d H:i:s')
            ]);
            
            // Añadir miembros seleccionados
            foreach ($memberIds as $memberId) {
                if ($memberId != $this->currentUserId) { // No añadir al creador dos veces
                    $this->chatChannelMemberModel->insert([
                        'channel_id' => $channelId,
                        'user_id' => $memberId,
                        'role' => 'member',
                        'joined_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Grupo creado exitosamente',
                'group_id' => $channelId,
                'conversation_id' => 'c_' . $channelId
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error al crear grupo: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear el grupo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener información detallada de un grupo
     */
    public function getGroupInfo($groupId = null)
    {
        if (!$groupId) {
            return $this->response->setJSON(['error' => 'ID de grupo requerido']);
        }
        
        try {
            // Verificar que el usuario pertenece al grupo
            $membership = $this->chatChannelMemberModel->where('channel_id', $groupId)
                                                      ->where('user_id', $this->currentUserId)
                                                      ->first();
            
            if (!$membership) {
                return $this->response->setJSON(['error' => 'No tienes acceso a este grupo']);
            }
            
            // Obtener información del grupo
            $group = $this->chatChannelModel->find($groupId);
            if (!$group) {
                return $this->response->setJSON(['error' => 'Grupo no encontrado']);
            }
            
            // Obtener miembros del grupo
            helper('avatar');
            $userModel = new \App\Models\CustomUserModel();
            
            $members = $this->chatChannelMemberModel->select('chat_channel_members.*, users.username, users.email, users.avatar_style, users.user_type')
                                                   ->join('users', 'users.id = chat_channel_members.user_id')
                                                   ->where('channel_id', $groupId)
                                                   ->orderBy('role', 'DESC') // Admins primero
                                                   ->orderBy('username', 'ASC')
                                                   ->findAll();
            
            $membersList = [];
            foreach ($members as $member) {
                $avatarUrl = getAvatarUrl((object)$member, 32, $member['avatar_style'] ?? 'initials');
                
                $membersList[] = [
                    'id' => $member['user_id'],
                    'name' => $member['username'],
                    'email' => $member['email'] ?? '',
                    'avatar' => $avatarUrl,
                    'role' => $member['role'],
                    'is_admin' => $member['role'] === 'admin',
                    'joined_at' => $member['joined_at'],
                    'user_type' => $member['user_type'] ?? 'user'
                ];
            }
            
            return $this->response->setJSON([
                'group' => [
                    'id' => $group['id'],
                    'name' => $group['name'],
                    'description' => $group['description'],
                    'created_by' => $group['created_by'],
                    'created_at' => $group['created_at'],
                    'members_count' => count($membersList),
                    'conversation_id' => 'c_' . $groupId
                ],
                'members' => $membersList,
                'user_role' => $membership['role']
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error en getGroupInfo: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error al obtener información del grupo']);
        }
    }
} 