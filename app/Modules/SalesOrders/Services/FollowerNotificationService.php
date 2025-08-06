<?php

namespace Modules\SalesOrders\Services;

use Modules\SalesOrders\Models\SalesOrderFollowerModel;

class FollowerNotificationService
{
    protected $followerModel;
    protected $db;

    public function __construct()
    {
        $this->followerModel = new SalesOrderFollowerModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Send notification to all followers of a sales order
     */
    public function notifyFollowers($salesOrderId, $notificationType, $title, $message, $data = [], $excludeUserId = null)
    {
        try {
            // Get followers who want this type of notification
            $followers = $this->followerModel->getFollowersForNotification($salesOrderId, $notificationType);

            if (empty($followers)) {
                log_message('info', "No followers found for notification type '{$notificationType}' on order {$salesOrderId}");
                return [];
            }

            $notifications = [];
            $sentVia = [];

            foreach ($followers as $follower) {
                // Skip the user who triggered the action
                if ($excludeUserId && $follower['user_id'] == $excludeUserId) {
                    continue;
                }

                $preferences = json_decode(
                    isset($follower['notification_preferences']) ? $follower['notification_preferences'] : '{}', 
                    true
                );
                $channels = [];

                // Determine which channels to use
                $emailNotif = isset($preferences['email_notifications']) ? $preferences['email_notifications'] : true;
                if ($emailNotif) {
                    $channels[] = 'email';
                }
                $smsNotif = isset($preferences['sms_notifications']) ? $preferences['sms_notifications'] : false;
                if ($smsNotif) {
                    $channels[] = 'sms';
                }
                $pushNotif = isset($preferences['push_notifications']) ? $preferences['push_notifications'] : true;
                if ($pushNotif) {
                    $channels[] = 'push';
                }

                if (empty($channels)) {
                    continue; // Skip if no channels are enabled
                }

                // Send notifications via each enabled channel
                foreach ($channels as $channel) {
                    try {
                        switch ($channel) {
                            case 'email':
                                $this->sendEmailNotification($follower, $title, $message, $data);
                                break;
                            case 'sms':
                                $this->sendSMSNotification($follower, $title, $message, $data);
                                break;
                            case 'push':
                                $this->sendPushNotification($follower, $title, $message, $data);
                                break;
                        }
                        $sentVia[] = $channel;
                    } catch (\Exception $e) {
                        log_message('error', "Failed to send {$channel} notification to user {$follower['user_id']}: " . $e->getMessage());
                    }
                }

                // Record the notification in the database
                $notificationData = [
                    'sales_order_id' => $salesOrderId,
                    'follower_id' => $follower['id'],
                    'notification_type' => $notificationType,
                    'title' => $title,
                    'message' => $message,
                    'metadata' => json_encode($data),
                    'sent_via' => json_encode(array_unique($sentVia)),
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $notificationId = $this->db->table('sales_order_follower_notifications')->insert($notificationData);
                if ($notificationId) {
                    $notifications[] = $notificationId;
                }
            }

            log_message('info', "Sent {$notificationType} notifications to " . count($notifications) . " followers for order {$salesOrderId}");
            return $notifications;

        } catch (\Exception $e) {
            log_message('error', "Error sending notifications to followers: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification($follower, $title, $message, $data = [])
    {
        if (empty($follower['email'])) {
            throw new \Exception("No email address for user {$follower['user_id']}");
        }

        $email = \Config\Services::email();
        
        $email->setTo($follower['email']);
        $email->setSubject($title);
        
        $emailBody = view('Modules\SalesOrders\Views\emails\follower_notification', [
            'follower' => $follower,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);
        
        $email->setMessage($emailBody);
        
        if (!$email->send()) {
            throw new \Exception("Failed to send email: " . $email->printDebugger(['headers']));
        }
        
        $orderId = isset($data['sales_order_id']) ? $data['sales_order_id'] : 'unknown';
        log_message('info', "Email notification sent to {$follower['email']} for sales order {$orderId}");
    }

    /**
     * Send SMS notification
     */
    private function sendSMSNotification($follower, $title, $message, $data = [])
    {
        if (empty($follower['phone'])) {
            throw new \Exception("No phone number for user {$follower['user_id']}");
        }

        // SMS implementation would depend on your SMS service provider
        // This is a placeholder for the actual SMS service integration
        $smsMessage = "{$title}: {$message}";
        
        // Example using a generic SMS service
        // $smsService = new \App\Libraries\SMSService();
        // $result = $smsService->send($follower['phone'], $smsMessage);
        
        $orderId = isset($data['sales_order_id']) ? $data['sales_order_id'] : 'unknown';
        log_message('info', "SMS notification would be sent to {$follower['phone']} for sales order {$orderId}");
    }

    /**
     * Send push notification
     */
    private function sendPushNotification($follower, $title, $message, $data = [])
    {
        // Push notification implementation would depend on your push service
        // This is a placeholder for the actual push notification service
        
        $orderId = isset($data['sales_order_id']) ? $data['sales_order_id'] : 'unknown';
        log_message('info', "Push notification would be sent to user {$follower['user_id']} for sales order {$orderId}");
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId)
    {
        return $this->db->table('sales_order_follower_notifications')
            ->join('sales_order_followers', 'sales_order_followers.id = sales_order_follower_notifications.follower_id')
            ->where('sales_order_follower_notifications.id', $notificationId)
            ->where('sales_order_followers.user_id', $userId)
            ->update(['read_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        return $this->db->table('sales_order_follower_notifications')
            ->join('sales_order_followers', 'sales_order_followers.id = sales_order_follower_notifications.follower_id')
            ->where('sales_order_followers.user_id', $userId)
            ->where('sales_order_follower_notifications.read_at IS NULL')
            ->update(['read_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get notifications for a user
     */
    public function getUserNotifications($userId, $limit = 50, $offset = 0)
    {
        return $this->db->table('sales_order_follower_notifications')
            ->select('sales_order_follower_notifications.*, sales_orders.id as order_id')
            ->join('sales_order_followers', 'sales_order_followers.id = sales_order_follower_notifications.follower_id')
            ->join('sales_orders', 'sales_orders.id = sales_order_follower_notifications.sales_order_id')
            ->where('sales_order_followers.user_id', $userId)
            ->orderBy('sales_order_follower_notifications.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }

    /**
     * Get unread notifications count for a user
     */
    public function getUnreadCount($userId)
    {
        return $this->db->table('sales_order_follower_notifications')
            ->join('sales_order_followers', 'sales_order_followers.id = sales_order_follower_notifications.follower_id')
            ->where('sales_order_followers.user_id', $userId)
            ->where('sales_order_follower_notifications.read_at IS NULL')
            ->countAllResults();
    }

    /**
     * Get unread notifications for a user
     */
    public function getUnreadNotifications($userId, $limit = 10)
    {
        return $this->db->table('sales_order_follower_notifications')
            ->select('sales_order_follower_notifications.*, sales_orders.id as order_id')
            ->join('sales_order_followers', 'sales_order_followers.id = sales_order_follower_notifications.follower_id')
            ->join('sales_orders', 'sales_orders.id = sales_order_follower_notifications.sales_order_id')
            ->where('sales_order_followers.user_id', $userId)
            ->where('sales_order_follower_notifications.read_at IS NULL')
            ->orderBy('sales_order_follower_notifications.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Quick notification methods for common events
     */
    public function notifyStatusChange($salesOrderId, $oldStatus, $newStatus, $changedBy)
    {
        $title = "Sales Order Status Updated";
        $message = "Status changed from '{$oldStatus}' to '{$newStatus}'";
        
        return $this->notifyFollowers(
            $salesOrderId,
            'status_changes',
            $title,
            $message,
            [
                'sales_order_id' => $salesOrderId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => $changedBy
            ],
            $changedBy
        );
    }

    public function notifyNewComment($salesOrderId, $commentAuthor, $commentPreview, $authorId)
    {
        $title = "New Comment Added";
        $message = "{$commentAuthor} added a comment: \"{$commentPreview}\"";
        
        return $this->notifyFollowers(
            $salesOrderId,
            'new_comments',
            $title,
            $message,
            [
                'sales_order_id' => $salesOrderId,
                'comment_author' => $commentAuthor,
                'comment_preview' => $commentPreview
            ],
            $authorId
        );
    }

    public function notifyMention($salesOrderId, $mentionedBy, $context, $mentionedById)
    {
        $title = "You were mentioned";
        $message = "{$mentionedBy} mentioned you in a comment: \"{$context}\"";
        
        return $this->notifyFollowers(
            $salesOrderId,
            'mentions',
            $title,
            $message,
            [
                'sales_order_id' => $salesOrderId,
                'mentioned_by' => $mentionedBy,
                'context' => $context
            ],
            $mentionedById
        );
    }
} 