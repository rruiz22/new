<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatConversationModel extends Model
{
    protected $table = 'chat_conversations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id', 
        'user_one', 
        'user_two', 
        'archived_by_user_one',
        'archived_by_user_two',
        'muted_by_user_one',
        'muted_by_user_two',
        'created_at', 
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Verificar si existe una conversación entre dos usuarios
     */
    public function conversationExists($userOne, $userTwo)
    {
        return $this->where('(user_one = ' . $userOne . ' AND user_two = ' . $userTwo . ') OR (user_one = ' . $userTwo . ' AND user_two = ' . $userOne . ')')
                    ->countAllResults() > 0;
    }
    
    /**
     * Obtener o crear una conversación entre dos usuarios
     */
    public function getOrCreateConversation($userOne, $userTwo)
    {
        // Ordenar IDs para consistencia
        if ($userOne > $userTwo) {
            $temp = $userOne;
            $userOne = $userTwo;
            $userTwo = $temp;
        }
        
        // Intentar obtener la conversación existente
        $conversation = $this->where('user_one', $userOne)
                             ->where('user_two', $userTwo)
                             ->first();
        
        if (!$conversation) {
            // Crear nueva conversación
            $this->insert([
                'user_one' => $userOne,
                'user_two' => $userTwo
            ]);
            
            $conversation = $this->where('user_one', $userOne)
                                 ->where('user_two', $userTwo)
                                 ->first();
        }
        
        return $conversation;
    }
    
    /**
     * Obtener conversaciones recientes de un usuario
     */
    public function getUserConversations($userId, $limit = 10)
    {
        return $this->where('user_one', $userId)
                    ->orWhere('user_two', $userId)
                    ->orderBy('updated_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }
    
    /**
     * Obtener el otro usuario de una conversación
     */
    public function getOtherUser($conversationId, $userId)
    {
        $conversation = $this->find($conversationId);
        
        if (!$conversation) {
            return null;
        }
        
        return ($conversation['user_one'] == $userId) ? $conversation['user_two'] : $conversation['user_one'];
    }
    
    /**
     * Obtener todas las conversaciones activas de un usuario (no archivadas/eliminadas)
     */
    public function getActiveConversations($userId)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table($this->table);
        $builder->where("(user_one = {$userId} AND archived_by_user_one = 0) OR (user_two = {$userId} AND archived_by_user_two = 0)");
        
        return $builder->get()->getResultArray();
    }
} 