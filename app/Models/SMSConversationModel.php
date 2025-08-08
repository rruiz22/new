<?php

namespace App\Models;

use CodeIgniter\Model;

class SMSConversationModel extends Model
{
    protected $table = 'sms_conversations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'twilio_sid',
        'direction',
        'from_number',
        'to_number',
        'message',
        'status',
        'metadata',
        'sent_by_user_id',
        'error_message',
        'delivered_at',
        'read_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get conversation between two phone numbers
     */
    public function getConversation($phone1, $phone2, $limit = 50)
    {
        return $this->select('sms_conversations.*, 
                             users.first_name, 
                             users.last_name,
                             CONCAT(users.first_name, " ", users.last_name) as sender_name')
                   ->join('users', 'users.id = sms_conversations.sent_by_user_id', 'left')
                   ->groupStart()
                       ->where('from_number', $phone1)
                       ->where('to_number', $phone2)
                   ->groupEnd()
                   ->orGroupStart()
                       ->where('from_number', $phone2)
                       ->where('to_number', $phone1)
                   ->groupEnd()
                   ->orderBy('created_at', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get conversations for a specific order
     */
    public function getOrderConversation($orderId, $module, $limit = 50)
    {
        return $this->select('sms_conversations.*, 
                             users.first_name, 
                             users.last_name,
                             CONCAT(users.first_name, " ", users.last_name) as sender_name')
                   ->join('users', 'users.id = sms_conversations.sent_by_user_id', 'left')
                   ->where('JSON_EXTRACT(metadata, "$.order_id")', $orderId)
                   ->where('JSON_EXTRACT(metadata, "$.module")', $module)
                   ->orderBy('created_at', 'ASC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get recent conversations for a user
     */
    public function getUserRecentConversations($userId, $limit = 10)
    {
        return $this->select('DISTINCT to_number, 
                             MAX(created_at) as last_message_at,
                             COUNT(*) as message_count,
                             JSON_EXTRACT(metadata, "$.order_id") as order_id,
                             JSON_EXTRACT(metadata, "$.module") as module')
                   ->where('sent_by_user_id', $userId)
                   ->where('direction', 'outbound')
                   ->groupBy('to_number')
                   ->orderBy('last_message_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($conversationId)
    {
        return $this->update($conversationId, [
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update message status (from Twilio webhook)
     */
    public function updateMessageStatus($twilioSid, $status, $errorMessage = null)
    {
        $updateData = ['status' => $status];
        
        if ($status === 'delivered') {
            $updateData['delivered_at'] = date('Y-m-d H:i:s');
        }
        
        if ($errorMessage) {
            $updateData['error_message'] = $errorMessage;
        }
        
        return $this->where('twilio_sid', $twilioSid)->set($updateData)->update();
    }

    /**
     * Get unread message count for a user
     */
    public function getUnreadCount($userId)
    {
        return $this->where('direction', 'inbound')
                   ->where('read_at IS NULL')
                   ->where('JSON_EXTRACT(metadata, "$.sent_by_user_id")', $userId)
                   ->countAllResults();
    }

    /**
     * Search conversations by message content
     */
    public function searchConversations($query, $userId = null, $limit = 20)
    {
        $builder = $this->select('sms_conversations.*, 
                                 users.first_name, 
                                 users.last_name,
                                 CONCAT(users.first_name, " ", users.last_name) as sender_name')
                       ->join('users', 'users.id = sms_conversations.sent_by_user_id', 'left')
                       ->like('message', $query);
        
        if ($userId) {
            $builder->where('sent_by_user_id', $userId);
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->limit($limit)
                      ->findAll();
    }
}
