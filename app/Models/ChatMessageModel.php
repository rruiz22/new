<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatMessageModel extends Model
{
    protected $table = 'chat_messages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'conversation_id', 'sender_id', 'message', 'has_attachment', 'is_read'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Configuración para problemas de conexión
    protected $connectRetries = 3;
    protected $connectRetryDelay = 1; // segundos

    /**
     * Obtener una conexión a la base de datos con reintentos
     */
    protected function getDbConnection()
    {
        try {
            // Usar el método mejorado de la clase MySQL para obtener una conexión garantizada
            return \Config\MySQL::ensureWorkingConnection();
        } catch (\Exception $e) {
            log_message('error', 'Error crítico conectando a la base de datos: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Ejecutar una operación con manejo de errores y reintentos
     */
    protected function executeWithRetry($callback)
    {
        $attempt = 0;
        $lastError = null;
        
        while ($attempt < $this->connectRetries) {
            try {
                // Si no es el primer intento, verificar conexión
                if ($attempt > 0) {
                    // Forzar una conexión verificada para próximas operaciones
                    // No podemos acceder directamente a $this->db, así que usamos
                    // técnicas alternativas para garantizar la conexión
                    \Config\Database::closeConnections();
                    // La próxima vez que se use builder() o query(), se usará una nueva conexión
                    $this->getDbConnection()->query("SELECT 1");
                }
                
                return $callback();
                
            } catch (\Exception $e) {
                $attempt++;
                $lastError = $e;
                $errorMessage = $e->getMessage();
                
                // Verificar si el error es por conexión perdida
                $isConnectionError = 
                    strpos($errorMessage, 'MySQL server has gone away') !== false ||
                    strpos($errorMessage, 'Lost connection') !== false ||
                    strpos($errorMessage, 'Error connecting to MySQL') !== false ||
                    strpos($errorMessage, 'Server has gone away') !== false;
                    
                if ($isConnectionError) {
                    log_message('error', "Error de conexión a la base de datos (intento {$attempt}/{$this->connectRetries}): {$errorMessage}");
                    
                    if ($attempt < $this->connectRetries) {
                        // Resetear la conexión para el próximo intento
                        \Config\Database::closeConnections();
                        
                        // Incrementar el tiempo de espera para cada intento
                        $waitTime = $this->connectRetryDelay * $attempt;
                        sleep($waitTime);
                        
                        // Registrar intento de reconexión
                        log_message('info', "Intentando reconectar (intento {$attempt}, espera {$waitTime}s)");
                    } else {
                        log_message('critical', "Agotados los intentos de reconexión. Último error: {$errorMessage}");
                        throw $e;
                    }
                } else {
                    // Si es otro tipo de error, lanzarlo directamente
                    log_message('error', "Error de base de datos (no de conexión): {$errorMessage}");
                    throw $e;
                }
            }
        }
        
        // Si llegamos aquí, todos los intentos fallaron
        throw new \Exception("Operación fallida después de {$this->connectRetries} intentos. Último error: " . 
            ($lastError ? $lastError->getMessage() : "Desconocido"));
    }

    /**
     * Obtener mensajes de una conversación
     */
    public function getConversationMessages($conversationId, $limit = 50, $offset = 0)
    {
        return $this->executeWithRetry(function () use ($conversationId, $limit, $offset) {
            return $this->where('conversation_id', $conversationId)
                        ->orderBy('created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->findAll();
        });
    }

    /**
     * Marcar mensaje como leído
     */
    public function markAsRead($messageId)
    {
        return $this->executeWithRetry(function () use ($messageId) {
            return $this->update($messageId, ['is_read' => 1]);
        });
    }
    
    /**
     * Marcar todos los mensajes de una conversación como leídos para un usuario
     */
    public function markAllAsRead($conversationId, $userId)
    {
        return $this->executeWithRetry(function () use ($conversationId, $userId) {
            return $this->where('conversation_id', $conversationId)
                        ->where('sender_id !=', $userId)
                        ->where('is_read', 0)
                        ->set(['is_read' => 1])
                        ->update();
        });
    }
    
    /**
     * Contar mensajes no leídos para un usuario
     */
    public function countUnreadMessages($conversationId, $userId)
    {
        return $this->executeWithRetry(function () use ($conversationId, $userId) {
            return $this->where('conversation_id', $conversationId)
                        ->where('sender_id !=', $userId)
                        ->where('is_read', 0)
                        ->countAllResults();
        });
    }
    
    /**
     * Sobrescribir el método insert para agregar manejo de errores
     */
    public function insert($data = null, bool $returnID = true)
    {
        return $this->executeWithRetry(function () use ($data, $returnID) {
            return parent::insert($data, $returnID);
        });
    }
} 