<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatChannelMemberModel extends Model
{
    protected $table = 'chat_channel_members';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['channel_id', 'user_id', 'is_admin', 'archived', 'muted'];

    // Dates
    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    /**
     * Añadir usuario a un canal
     */
    public function addMember($channelId, $userId, $isAdmin = 0)
    {
        // Verificar si ya es miembro
        if ($this->isMember($channelId, $userId)) {
            return false;
        }
        
        return $this->insert([
            'channel_id' => $channelId,
            'user_id' => $userId,
            'is_admin' => $isAdmin,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Comprobar si un usuario es miembro de un canal
     */
    public function isMember($channelId, $userId)
    {
        return $this->where('channel_id', $channelId)
                    ->where('user_id', $userId)
                    ->where('COALESCE(archived, 0)', 0) // Exclude archived memberships
                    ->countAllResults() > 0;
    }
    
    /**
     * Comprobar si un usuario es administrador de un canal
     */
    public function isAdmin($channelId, $userId)
    {
        $member = $this->where('channel_id', $channelId)
                    ->where('user_id', $userId)
                    ->first();
                    
        return $member && $member['is_admin'] == 1;
    }
    
    /**
     * Eliminar miembro de un canal
     */
    public function removeMember($channelId, $userId)
    {
        return $this->where('channel_id', $channelId)
                    ->where('user_id', $userId)
                    ->delete();
    }
    
    /**
     * Obtener miembros de un canal
     */
    public function getChannelMembers($channelId)
    {
        $db = \Config\Database::connect();
        
        // Unir con la tabla de usuarios para obtener información adicional
        $builder = $db->table('chat_channel_members cm');
        $builder->select('cm.*, u.id, u.username as name, COALESCE(u.avatar, "assets/images/users/avatar-1.jpg") as avatar, cm.is_admin');
        $builder->join('users u', 'cm.user_id = u.id');
        $builder->where('cm.channel_id', $channelId);
        $builder->orderBy('cm.is_admin', 'DESC');
        $builder->orderBy('u.username', 'ASC');
        
        return $builder->get()->getResultArray();
    }
} 