<?php

namespace App\Libraries;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use CodeIgniter\Config\Services;

class ChatServer implements MessageComponentInterface
{
    protected $clients;
    protected $userConnections = [];
    protected $db;
    protected $chatMessageModel;
    protected $chatConversationModel;
    protected $chatChannelModel;
    
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        // No inicializar una conexión a la base de datos en el constructor
        // para evitar que se mantenga inactiva durante mucho tiempo
        $this->chatMessageModel = new \App\Models\ChatMessageModel();
        $this->chatConversationModel = new \App\Models\ChatConversationModel();
        $this->chatChannelModel = new \App\Models\ChatChannelModel();
    }
    
    /**
     * Cuando se conecta un cliente
     */
    public function onOpen(ConnectionInterface $conn)
    {
        // Almacenar la nueva conexión
        $this->clients->attach($conn);
        
        echo "Nueva conexión: {$conn->resourceId}\n";
    }
    
    /**
     * Obtener una conexión fresca a la base de datos
     */
    protected function getDb()
    {
        // Crear una nueva conexión cada vez que se necesite
        return \Config\Database::connect();
    }
    
    /**
     * Cuando se recibe un mensaje
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        
        // Verificar el tipo de mensaje
        if (!isset($data['type'])) {
            return;
        }
        
        switch ($data['type']) {
            case 'auth':
                $this->handleAuthentication($from, $data);
                break;
                
            case 'direct_message':
                $this->handleDirectMessage($from, $data);
                break;
                
            case 'channel_message':
                $this->handleChannelMessage($from, $data);
                break;
                
            case 'typing':
                $this->handleTypingStatus($from, $data);
                break;
                
            case 'read':
                $this->handleReadStatus($from, $data);
                break;
        }
    }
    
    /**
     * Cuando se cierra una conexión
     */
    public function onClose(ConnectionInterface $conn)
    {
        // Eliminar la conexión
        $this->clients->detach($conn);
        
        // Encontrar y eliminar el usuario asociado con esta conexión
        foreach ($this->userConnections as $userId => $resourceId) {
            if ($resourceId == $conn->resourceId) {
                unset($this->userConnections[$userId]);
                
                // Actualizar el estado del usuario en la base de datos y notificar a otros
                $this->updateUserStatus($userId, 'offline');
                
                echo "Usuario {$userId} se ha desconectado\n";
                break;
            }
        }
        
        echo "Conexión {$conn->resourceId} cerrada\n";
    }
    
    /**
     * Cuando ocurre un error
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        
        $conn->close();
    }
    
    /**
     * Manejar la autenticación del usuario
     */
    protected function handleAuthentication($conn, $data)
    {
        if (!isset($data['userId']) || !isset($data['token'])) {
            $conn->send(json_encode([
                'type' => 'auth_error',
                'message' => 'Faltan datos de autenticación'
            ]));
            return;
        }
        
        $userId = $data['userId'];
        $token = $data['token'];
        
        // Verificar que el token JWT sea válido
        try {
            $key = getenv('JWT_SECRET_KEY') ?: 'chatSecretKey12345';
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));
            
            // Verificar que el token no haya expirado y que pertenezca al usuario correcto
            if ($decoded->exp < time() || $decoded->user_id != $userId) {
                $conn->send(json_encode([
                    'type' => 'auth_error',
                    'message' => 'Token inválido o expirado'
                ]));
                return;
            }
            
            // Asociar el ID de usuario con esta conexión
            $this->userConnections[$userId] = $conn->resourceId;
            
            // Almacenar el ID del usuario en la conexión para futuras referencias
            $conn->userId = $userId;
            
            echo "Usuario {$userId} autenticado\n";
            
            // Actualizar estado de usuario en la base de datos usando una conexión nueva
            try {
                $this->updateUserStatus($userId, 'online');
                
                // Confirmar al usuario que está autenticado
                $conn->send(json_encode([
                    'type' => 'auth_success',
                    'userId' => $userId
                ]));
            } catch (\Exception $dbError) {
                echo "Error al actualizar estado de usuario: " . $dbError->getMessage() . "\n";
                
                // Aún así confirmar la autenticación, pero registrar el error
                $conn->send(json_encode([
                    'type' => 'auth_success',
                    'userId' => $userId,
                    'warning' => 'Autenticado, pero hubo un problema al actualizar tu estado'
                ]));
                
                // Intentar reconectar a la base de datos para futuras operaciones
                \Config\Database::closeConnections();
            }
        } catch (\Exception $e) {
            echo "Error de autenticación: " . $e->getMessage() . "\n";
            
            // Determinar si es un error de conexión a la base de datos
            if (strpos($e->getMessage(), 'MySQL server has gone away') !== false || 
                strpos($e->getMessage(), 'Lost connection') !== false) {
                
                // Intentar cerrar y reconectar
                \Config\Database::closeConnections();
                
                $conn->send(json_encode([
                    'type' => 'auth_error',
                    'message' => 'Error temporal de conexión a la base de datos. Por favor, intenta de nuevo.'
                ]));
            } else {
                $conn->send(json_encode([
                    'type' => 'auth_error',
                    'message' => 'Error de autenticación: ' . $e->getMessage()
                ]));
            }
            return;
        }
    }
    
    /**
     * Manejar mensajes directos
     */
    protected function handleDirectMessage($from, $data)
    {
        if (!isset($data['to']) || !isset($data['message']) || !isset($from->userId)) {
            return;
        }
        
        $fromUserId = $from->userId;
        $toUserId = $data['to'];
        $message = $data['message'];
        
        // Obtener o crear la conversación
        $conversation = $this->chatConversationModel->getOrCreateConversation($fromUserId, $toUserId);
        $conversationId = $conversation['id'];
        
        // Preparar datos del mensaje
        $messageData = [
            'conversation_id' => $conversationId,
            'sender_id' => $fromUserId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Verificar si tiene archivos adjuntos
        if (isset($data['has_attachment']) && $data['has_attachment']) {
            $messageData['has_attachment'] = 1;
        }
        
        // Guardar el mensaje en la base de datos
        $messageId = $this->chatMessageModel->insert($messageData);
        
        // Preparar la respuesta para ambos usuarios
        $response = [
            'type' => 'new_message',
            'message' => [
                'id' => $messageId,
                'conversation_id' => $conversationId,
                'sender_id' => $fromUserId,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s'),
                'is_read' => 0
            ]
        ];
        
        // Agregar información de archivos adjuntos si existen
        if (isset($data['has_attachment']) && $data['has_attachment'] && isset($data['attachments'])) {
            $response['message']['has_attachment'] = 1;
            $response['message']['attachments'] = $data['attachments'];
        }
        
        // Enviar el mensaje al remitente (confirmación)
        $from->send(json_encode($response));
        
        // Enviar el mensaje al destinatario si está conectado
        if (isset($this->userConnections[$toUserId])) {
            $toResourceId = $this->userConnections[$toUserId];
            
            foreach ($this->clients as $client) {
                if ($client->resourceId == $toResourceId) {
                    $client->send(json_encode($response));
                    break;
                }
            }
        }
    }
    
    /**
     * Manejar mensajes en canales
     */
    protected function handleChannelMessage($from, $data)
    {
        if (!isset($data['channelId']) || !isset($data['message']) || !isset($from->userId)) {
            return;
        }
        
        $fromUserId = $from->userId;
        $channelId = $data['channelId'];
        $message = $data['message'];
        
        // Verificar que el usuario pertenezca al canal
        $channelMemberModel = new \App\Models\ChatChannelMemberModel();
        if (!$channelMemberModel->isMember($channelId, $fromUserId)) {
            return;
        }
        
        // Guardar el mensaje en la base de datos (usando conversationId como channelId con prefijo)
        $conversationId = 'c_' . $channelId;
        $messageId = $this->chatMessageModel->insert([
            'conversation_id' => $conversationId,
            'sender_id' => $fromUserId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Obtener miembros del canal
        $members = $channelMemberModel->where('channel_id', $channelId)->findAll();
        
        // Preparar la respuesta
        $response = [
            'type' => 'new_channel_message',
            'message' => [
                'id' => $messageId,
                'channel_id' => $channelId,
                'sender_id' => $fromUserId,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        // Enviar el mensaje a todos los miembros conectados
        foreach ($members as $member) {
            $memberId = $member['user_id'];
            
            if (isset($this->userConnections[$memberId])) {
                $memberResourceId = $this->userConnections[$memberId];
                
                foreach ($this->clients as $client) {
                    if ($client->resourceId == $memberResourceId) {
                        $client->send(json_encode($response));
                        break;
                    }
                }
            }
        }
    }
    
    /**
     * Manejar estado de escritura
     */
    protected function handleTypingStatus($from, $data)
    {
        if (!isset($data['to']) || !isset($from->userId)) {
            return;
        }
        
        $fromUserId = $from->userId;
        $toUserId = $data['to'];
        $isTyping = $data['typing'] ?? false;
        
        // Si el destinatario está conectado, enviar estado de escritura
        if (isset($this->userConnections[$toUserId])) {
            $toResourceId = $this->userConnections[$toUserId];
            
            foreach ($this->clients as $client) {
                if ($client->resourceId == $toResourceId) {
                    $client->send(json_encode([
                        'type' => 'typing_status',
                        'from' => $fromUserId,
                        'typing' => $isTyping
                    ]));
                    break;
                }
            }
        }
    }
    
    /**
     * Manejar estado de lectura de mensajes
     */
    protected function handleReadStatus($from, $data)
    {
        if (!isset($data['conversation_id']) || !isset($from->userId)) {
            return;
        }
        
        $userId = $from->userId;
        $conversationId = $data['conversation_id'];
        
        // Marcar todos los mensajes como leídos
        $this->chatMessageModel->markAllAsRead($conversationId, $userId);
        
        // Obtener el otro usuario de la conversación
        $otherUserId = $this->chatConversationModel->getOtherUser($conversationId, $userId);
        
        // Si el otro usuario está conectado, notificarle
        if ($otherUserId && isset($this->userConnections[$otherUserId])) {
            $otherResourceId = $this->userConnections[$otherUserId];
            
            foreach ($this->clients as $client) {
                if ($client->resourceId == $otherResourceId) {
                    $client->send(json_encode([
                        'type' => 'read_status',
                        'conversation_id' => $conversationId,
                        'by' => $userId
                    ]));
                    break;
                }
            }
        }
    }
    
    /**
     * Difundir cambio de estado de un usuario
     */
    protected function broadcastUserStatus($userId, $status)
    {
        $response = [
            'type' => 'user_status',
            'user_id' => $userId,
            'status' => $status
        ];
        
        foreach ($this->clients as $client) {
            if (isset($client->userId) && $client->userId != $userId) {
                $client->send(json_encode($response));
            }
        }
    }

    /**
     * Actualizar el estado del usuario en la base de datos y notificar a otros usuarios
     */
    protected function updateUserStatus($userId, $status)
    {
        try {
            // Usar la clase MySQL para verificar y reconectar si es necesario
            $db = \Config\MySQL::reconnectIfNeeded($this->getDb());
            
            // Ejecutar la consulta dentro de un bloque try-catch específico
            try {
                $result = $db->table('users')
                    ->where('id', $userId)
                    ->update([
                        'status' => $status,
                        'last_seen' => date('Y-m-d H:i:s')
                    ]);
                
                // Notificar a otros usuarios
                $this->broadcastUserStatus($userId, $status);
                
                return $result;
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'MySQL server has gone away') !== false ||
                    strpos($e->getMessage(), 'Lost connection') !== false) {
                    
                    // Registrar el error
                    echo "Error al actualizar estado (conexión perdida): {$e->getMessage()}\n";
                    
                    // Forzar una nueva conexión y reintento único
                    \Config\Database::closeConnections();
                    $db = \Config\MySQL::reconnectIfNeeded();
                    
                    if ($db) {
                        echo "Reconexión exitosa, reintentando operación\n";
                        
                        // Reintentar la actualización una vez más
                        $result = $db->table('users')
                            ->where('id', $userId)
                            ->update([
                                'status' => $status,
                                'last_seen' => date('Y-m-d H:i:s')
                            ]);
                        
                        // Notificar a otros usuarios
                        $this->broadcastUserStatus($userId, $status);
                        
                        return $result;
                    }
                }
                
                // Otros errores o si el reintento falló
                echo "Error irrecuperable al actualizar el estado del usuario: " . $e->getMessage() . "\n";
            }
        } catch (\Exception $e) {
            // Registrar el error pero no detener la ejecución
            echo "Error al actualizar el estado del usuario: " . $e->getMessage() . "\n";
        }
        
        return false;
    }
    
    /**
     * Verificar usuarios inactivos
     * Este método debería ejecutarse periódicamente
     */
    public function checkInactiveUsers()
    {
        // Obtener usuarios que no han enviado un heartbeat en los últimos 5 minutos
        $db = \Config\Database::connect();
        $inactiveUsers = $db->table('users')
                        ->where('status', 'online')
                        ->where('last_seen <', date('Y-m-d H:i:s', time() - 300))
                        ->get()
                        ->getResultArray();
                        
        foreach ($inactiveUsers as $user) {
            $this->updateUserStatus($user['id'], 'away');
        }
    }
} 