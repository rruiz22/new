<?php

namespace Modules\ServiceOrders\Services;

use Modules\ServiceOrders\Models\ServiceOrderFollowerModel;

class FollowerNotificationService
{
    protected $followerModel;
    protected $db;

    public function __construct()
    {
        $this->followerModel = new ServiceOrderFollowerModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Send notification to all followers of a service order
     */
    public function notifyFollowers($serviceOrderId, $notificationType, $title, $message, $data = [], $excludeUserId = null)
    {
        try {
            // Get followers who want this type of notification
            $followers = $this->followerModel->getFollowersForNotification($serviceOrderId, $notificationType);

            if (empty($followers)) {
                log_message('info', "No followers found for notification type '{$notificationType}' on order {$serviceOrderId}");
                return [];
            }

            $notifications = [];
            $sentVia = [];

            foreach ($followers as $follower) {
                // Skip the user who triggered the action
                if ($excludeUserId && $follower['user_id'] == $excludeUserId) {
                    continue;
                }

                $preferences = json_decode($follower['notification_preferences'] ?? '{}', true);
                $channels = [];

                // Determine which channels to use
                if ($preferences['email_notifications'] ?? true) {
                    $channels[] = 'email';
                }
                if ($preferences['sms_notifications'] ?? false) {
                    $channels[] = 'sms';
                }
                if ($preferences['push_notifications'] ?? true) {
                    $channels[] = 'push';
                }

                // Send notifications via selected channels
                $channelResults = [];
                foreach ($channels as $channel) {
                    try {
                        $result = $this->sendNotificationViaChannel($follower, $channel, $title, $message, $data);
                        $channelResults[$channel] = $result;
                    } catch (\Exception $e) {
                        log_message('error', "Failed to send {$channel} notification to user {$follower['user_id']}: " . $e->getMessage());
                        $channelResults[$channel] = false;
                    }
                }

                // Store notification record
                $notificationId = $this->storeNotification([
                    'service_order_id' => $serviceOrderId,
                    'follower_id' => $follower['id'],
                    'notification_type' => $notificationType,
                    'title' => $title,
                    'message' => $message,
                    'data' => json_encode($data),
                    'sent_via' => json_encode($channelResults),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $notifications[] = [
                    'notification_id' => $notificationId,
                    'follower_id' => $follower['id'],
                    'user_id' => $follower['user_id'],
                    'channels' => $channelResults
                ];
            }

            log_message('info', "Sent {$notificationType} notifications to " . count($notifications) . " followers for order {$serviceOrderId}");
            return $notifications;

        } catch (\Exception $e) {
            log_message('error', "Error sending follower notifications: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send notification via specific channel
     */
    private function sendNotificationViaChannel($follower, $channel, $title, $message, $data)
    {
        switch ($channel) {
            case 'email':
                return $this->sendEmailNotification($follower, $title, $message, $data);
            
            case 'sms':
                return $this->sendSMSNotification($follower, $title, $message, $data);
            
            case 'push':
                return $this->sendPushNotification($follower, $title, $message, $data);
            
            default:
                throw new \Exception("Unsupported notification channel: {$channel}");
        }
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification($follower, $title, $message, $data)
    {
        if (empty($follower['email'])) {
            return false;
        }

        try {
            $email = \Config\Services::email();
            
            $email->setTo($follower['email']);
            $email->setSubject($title);
            
            // Create HTML email content
            $htmlMessage = $this->createEmailTemplate($follower, $title, $message, $data);
            $email->setMessage($htmlMessage);
            
            $result = $email->send();
            
            if (!$result) {
                log_message('error', 'Email send failed: ' . $email->printDebugger());
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            log_message('error', 'Email notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS notification
     */
    private function sendSMSNotification($follower, $title, $message, $data)
    {
        if (empty($follower['phone'])) {
            return false;
        }

        try {
            // Use existing Twilio service
            $twilio = \Config\Services::twilio();
            
            if (!$twilio) {
                return false;
            }
            
            $settingsModel = new \App\Models\SettingsModel();
            $twilioNumber = $settingsModel->getSetting('twilio_number', '');
            
            if (empty($twilioNumber)) {
                return false;
            }
            
            // Create short SMS message
            $smsMessage = $this->createSMSMessage($title, $message, $data);
            
            // Format phone number
            $phone = $follower['phone'];
            if (!str_starts_with($phone, '+')) {
                $phone = '+1' . preg_replace('/[^0-9]/', '', $phone);
            }
            
            $twilioMessage = $twilio->messages->create(
                $phone,
                [
                    'from' => $twilioNumber,
                    'body' => $smsMessage
                ]
            );
            
            return !empty($twilioMessage->sid);
            
        } catch (\Exception $e) {
            log_message('error', 'SMS notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send push notification (placeholder for future implementation)
     */
    private function sendPushNotification($follower, $title, $message, $data)
    {
        // Placeholder for push notification implementation
        // Could integrate with Firebase, OneSignal, etc.
        log_message('info', "Push notification would be sent to user {$follower['user_id']}: {$title}");
        return true; // Simulate success for now
    }

    /**
     * Create email template
     */
    private function createEmailTemplate($follower, $title, $message, $data)
    {
        $orderUrl = isset($data['order_url']) ? $data['order_url'] : base_url("service_orders/view/{$data['service_order_id']}");
        $orderNumber = isset($data['order_number']) ? $data['order_number'] : "SER-" . str_pad($data['service_order_id'], 5, '0', STR_PAD_LEFT);
        
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #007bff; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f8f9fa; }
                .footer { padding: 20px; text-align: center; color: #666; }
                .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>{$title}</h2>
                </div>
                <div class='content'>
                    <p>Hello {$follower['full_name']},</p>
                    <p>{$message}</p>
                    <p><strong>Service Order:</strong> {$orderNumber}</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='{$orderUrl}' class='btn'>View Order Details</a>
                    </p>
                </div>
                <div class='footer'>
                    <p>You're receiving this because you're following this service order.</p>
                    <p><small>To manage your notification preferences, visit the order page.</small></p>
                </div>
            </div>
        </body>
        </html>";
    }

    /**
     * Create SMS message
     */
    private function createSMSMessage($title, $message, $data)
    {
        $orderNumber = isset($data['order_number']) ? $data['order_number'] : "SER-" . str_pad($data['service_order_id'], 5, '0', STR_PAD_LEFT);
        $orderUrl = isset($data['order_url']) ? $data['order_url'] : base_url("service_orders/view/{$data['service_order_id']}");
        
        // Keep SMS short (160 character limit)
        $smsMessage = "{$title}\n{$orderNumber}: {$message}\nView: {$orderUrl}";
        
        // Truncate if too long
        if (strlen($smsMessage) > 160) {
            $maxMessageLength = 160 - strlen($orderNumber) - strlen($orderUrl) - 20; // Buffer for formatting
            $truncatedMessage = substr($message, 0, $maxMessageLength) . '...';
            $smsMessage = "{$orderNumber}: {$truncatedMessage}\n{$orderUrl}";
        }
        
        return $smsMessage;
    }

    /**
     * Store notification in database
     */
    private function storeNotification($data)
    {
        return $this->db->table('service_order_follower_notifications')->insert($data);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId)
    {
        return $this->db->table('service_order_follower_notifications')
            ->where('id', $notificationId)
            ->join('service_order_followers', 'service_order_followers.id = service_order_follower_notifications.follower_id')
            ->where('service_order_followers.user_id', $userId)
            ->update(['read_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get unread notifications for a user
     */
    public function getUnreadNotifications($userId, $limit = 10)
    {
        return $this->db->table('service_order_follower_notifications')
            ->select('service_order_follower_notifications.*, service_orders.id as order_id')
            ->join('service_order_followers', 'service_order_followers.id = service_order_follower_notifications.follower_id')
            ->join('service_orders', 'service_orders.id = service_order_follower_notifications.service_order_id')
            ->where('service_order_followers.user_id', $userId)
            ->where('service_order_follower_notifications.read_at IS NULL')
            ->orderBy('service_order_follower_notifications.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Quick notification methods for common events
     */
    public function notifyStatusChange($serviceOrderId, $oldStatus, $newStatus, $changedBy)
    {
        $title = "Service Order Status Updated";
        $message = "Status changed from '{$oldStatus}' to '{$newStatus}'";
        
        return $this->notifyFollowers(
            $serviceOrderId,
            'status_changes',
            $title,
            $message,
            [
                'service_order_id' => $serviceOrderId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => $changedBy
            ],
            $changedBy
        );
    }

    public function notifyNewComment($serviceOrderId, $commentAuthor, $commentPreview, $authorId)
    {
        $title = "New Comment Added";
        $message = "{$commentAuthor} added a comment: \"{$commentPreview}\"";
        
        return $this->notifyFollowers(
            $serviceOrderId,
            'new_comments',
            $title,
            $message,
            [
                'service_order_id' => $serviceOrderId,
                'comment_author' => $commentAuthor,
                'comment_preview' => $commentPreview
            ],
            $authorId
        );
    }

    public function notifyMention($serviceOrderId, $mentionedBy, $context, $mentionedById)
    {
        $title = "You were mentioned";
        $message = "{$mentionedBy} mentioned you in a comment: \"{$context}\"";
        
        return $this->notifyFollowers(
            $serviceOrderId,
            'mentions',
            $title,
            $message,
            [
                'service_order_id' => $serviceOrderId,
                'mentioned_by' => $mentionedBy,
                'context' => $context
            ],
            $mentionedById
        );
    }
} 