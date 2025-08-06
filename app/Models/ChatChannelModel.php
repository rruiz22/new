<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatChannelModel extends Model
{
    protected $table = 'chat_channels';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'description', 'creator_id'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Crear un nuevo canal y añadir al creador como miembro administrador
     */
    public function createChannel($name, $description, $creatorId)
    {
        $db = \Config\Database::connect();
        
        $db->transStart();
        
        $channelId = $this->insert([
            'name' => $name,
            'description' => $description,
            'creator_id' => $creatorId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Añadir creador como miembro administrador
        $channelMemberModel = new \App\Models\ChatChannelMemberModel();
        $channelMemberModel->insert([
            'channel_id' => $channelId,
            'user_id' => $creatorId,
            'is_admin' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return false;
        }
        
        return $channelId;
    }
    
    /**
     * Obtener canales a los que pertenece un usuario
     */
    public function getUserChannels($userId)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('chat_channels c');
        $builder->select('c.*, cm.is_admin');
        $builder->join('chat_channel_members cm', 'c.id = cm.channel_id');
        $builder->where('cm.user_id', $userId);
        $builder->where('COALESCE(cm.archived, 0)', 0); // Exclude archived channels
        $builder->orderBy('c.name', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Obtener información de un canal con su número de miembros
     */
    public function getChannelWithMemberCount($channelId)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('chat_channels c');
        $builder->select('c.*, (SELECT COUNT(*) FROM chat_channel_members WHERE channel_id = c.id) as member_count');
        $builder->where('c.id', $channelId);
        
        return $builder->get()->getRowArray();
    }
} 