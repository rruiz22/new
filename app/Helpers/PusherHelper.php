<?php

namespace App\Helpers;

class PusherHelper
{
    private $pusher;
    
    public function __construct()
    {
        $this->pusher = \Config\Services::pusher();
    }
    
    /**
     * Send a real-time notification
     */
    public function sendNotification($channel, $event, $data)
    {
        if (!$this->pusher) {
            log_message('warning', 'Pusher not configured, notification not sent');
            return false;
        }
        
        try {
            $this->pusher->trigger($channel, $event, $data);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Pusher error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send order update notification
     */
    public function sendOrderUpdate($orderId, $status, $message = null)
    {
        $data = [
            'order_id' => $orderId,
            'status' => $status,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return $this->sendNotification('orders', 'order-updated', $data);
    }
    
    /**
     * Send new comment notification
     */
    public function sendNewComment($orderId, $comment, $user)
    {
        $data = [
            'order_id' => $orderId,
            'comment' => $comment,
            'user' => $user,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return $this->sendNotification('orders', 'new-comment', $data);
    }
    
    /**
     * Send activity notification
     */
    public function sendActivity($orderId, $type, $message, $user = null)
    {
        $data = [
            'order_id' => $orderId,
            'type' => $type,
            'message' => $message,
            'user' => $user,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return $this->sendNotification('orders', 'new-activity', $data);
    }
} 