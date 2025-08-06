<?php

namespace Modules\SalesOrders\Models;

use CodeIgniter\Model;

class OrderActivityModel extends Model
{
    protected $table = 'sales_orders_activities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id',
        'user_id',
        'activity_type',
        'title',
        'description',
        'old_value',
        'new_value',
        'field_name',
        'metadata',
        'created_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    /**
     * Get activities for an order with pagination
     */
    public function getOrderActivities($orderId, $limit = 10, $offset = 0)
    {
        return $this->select('sales_orders_activities.*, 
                             CONCAT(users.first_name, " ", users.last_name) as user_name,
                             users.first_name, users.last_name')
                    ->join('users', 'users.id = sales_orders_activities.user_id', 'left')
                    ->where('order_id', $orderId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit, $offset)
                    ->findAll();
    }

    /**
     * Count total activities for an order
     */
    public function countOrderActivities($orderId)
    {
        return $this->where('order_id', $orderId)->countAllResults();
    }

    /**
     * Convert technical status names to user-friendly labels
     */
    private function getStatusLabel($status)
    {
        $statusLabels = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
        
        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Log a status change activity
     */
    public function logStatusChange($orderId, $userId, $oldStatus, $newStatus)
    {
        $oldStatusLabel = $this->getStatusLabel($oldStatus);
        $newStatusLabel = $this->getStatusLabel($newStatus);
        
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'status_change',
            'title' => 'Status Updated',
            'description' => "Status changed from '{$oldStatusLabel}' to '{$newStatusLabel}'",
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'field_name' => 'status',
            'metadata' => json_encode([
                'old_status' => $oldStatus, 
                'new_status' => $newStatus,
                'old_status_label' => $oldStatusLabel,
                'new_status_label' => $newStatusLabel
            ])
        ]);
    }

    /**
     * Log a comment activity
     */
    public function logComment($orderId, $userId, $comment)
    {
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'comment_added',
            'title' => 'Comment Added',
            'description' => substr($comment, 0, 100) . (strlen($comment) > 100 ? '...' : ''),
            'new_value' => $comment,
            'field_name' => 'comment',
            'metadata' => json_encode(['comment' => $comment])
        ]);
    }

    /**
     * Log an overdue status
     */
    public function logOverdue($orderId, $daysOverdue)
    {
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => 1, // System user
            'activity_type' => 'overdue_alert',
            'title' => 'Order Overdue',
            'description' => "Order is now {$daysOverdue} day(s) overdue",
            'new_value' => $daysOverdue,
            'field_name' => 'overdue_days',
            'metadata' => json_encode(['days_overdue' => $daysOverdue])
        ]);
    }

    /**
     * Log email sent activity
     */
    public function logEmailSent($orderId, $userId, $recipient, $subject, $message = '', $cc = '')
    {
        $description = "Email sent to {$recipient}: {$subject}";
        if (!empty($cc)) {
            $description .= " (CC: {$cc})";
        }
        
        $metadata = [
            'recipient' => $recipient,
            'subject' => $subject
        ];
        
        if (!empty($message)) {
            $metadata['message'] = $message;
        }
        
        if (!empty($cc)) {
            $metadata['cc'] = $cc;
        }
        
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'email_sent',
            'title' => 'Email Sent',
            'description' => $description,
            'new_value' => $recipient,
            'field_name' => 'email_recipient',
            'metadata' => json_encode($metadata)
        ]);
    }

    /**
     * Log SMS sent activity
     */
    public function logSMSSent($orderId, $userId, $phone, $message)
    {
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'sms_sent',
            'title' => 'SMS Sent',
            'description' => "SMS sent to {$phone}: " . substr($message, 0, 50) . (strlen($message) > 50 ? '...' : ''),
            'new_value' => $phone,
            'field_name' => 'sms_recipient',
            'metadata' => json_encode(['phone' => $phone, 'message' => $message])
        ]);
    }

    /**
     * Log comment activity (enhanced version for replies, mentions, etc.)
     */
    public function logCommentActivity($orderId, $userId, $action, $description, $metadata = [])
    {
        $titles = [
            'comment_added' => 'Comment Added',
            'comment_reply_added' => 'Reply Added',
            'comment_updated' => 'Comment Updated',
            'comment_reply_updated' => 'Reply Updated',
            'comment_deleted' => 'Comment Deleted',
            'comment_reply_deleted' => 'Reply Deleted'
        ];
        
        $title = $titles[$action] ?? ucfirst(str_replace('_', ' ', $action));
        
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => $action,
            'title' => $title,
            'description' => $description,
            'metadata' => json_encode($metadata)
        ]);
    }

    /**
     * Log field change activity
     */
    public function logFieldChange($orderId, $userId, $fieldName, $oldValue, $newValue, $title = null)
    {
        // Use friendly labels for status fields
        if ($fieldName === 'status') {
            $oldValueDisplay = $this->getStatusLabel($oldValue);
            $newValueDisplay = $this->getStatusLabel($newValue);
        } else {
            $oldValueDisplay = $oldValue;
            $newValueDisplay = $newValue;
        }
        
        $title = $title ?: ucfirst(str_replace('_', ' ', $fieldName)) . ' Updated';
        $fieldDisplayName = ucfirst(str_replace('_', ' ', $fieldName));
        
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'field_change',
            'title' => $title,
            'description' => "{$fieldDisplayName} changed from '{$oldValueDisplay}' to '{$newValueDisplay}'",
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'field_name' => $fieldName,
            'metadata' => json_encode([
                'field' => $fieldName, 
                'old' => $oldValue, 
                'new' => $newValue,
                'old_display' => $oldValueDisplay,
                'new_display' => $newValueDisplay
            ])
        ]);
    }

    /**
     * Log order creation
     */
    public function logOrderCreated($orderId, $userId)
    {
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'order_created',
            'title' => 'Order Created',
            'description' => 'Sales order was created',
            'field_name' => 'order_status',
            'metadata' => json_encode(['action' => 'created'])
        ]);
    }

    public function getActivitiesByOrderId($orderId, $limit = 10)
    {
        return $this->select('sales_orders_activities.*,
                              users.first_name,
                              users.last_name')
                    ->join('users', 'users.id = sales_orders_activities.user_id', 'left')
                    ->where('order_id', $orderId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Log internal note added activity
     */
    public function logInternalNoteAdded($orderId, $userId, $noteId, $contentPreview, $fullContent = '')
    {
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'internal_note_added',
            'title' => 'Internal Note Added',
            'description' => 'Internal note was added: ' . $contentPreview,
            'new_value' => $contentPreview,
            'field_name' => 'internal_note',
            'metadata' => json_encode([
                'note_id' => $noteId,
                'content_preview' => $contentPreview,
                'full_content' => $fullContent,
                'action' => 'added'
            ])
        ]);
    }

    /**
     * Log internal note updated activity
     */
    public function logInternalNoteUpdated($orderId, $userId, $noteId, $contentPreview, $fullContent = '')
    {
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'internal_note_updated',
            'title' => 'Internal Note Updated',
            'description' => 'Internal note was updated: ' . $contentPreview,
            'new_value' => $contentPreview,
            'field_name' => 'internal_note',
            'metadata' => json_encode([
                'note_id' => $noteId,
                'content_preview' => $contentPreview,
                'full_content' => $fullContent,
                'action' => 'updated'
            ])
        ]);
    }

    /**
     * Log internal note deleted activity
     */
    public function logInternalNoteDeleted($orderId, $userId, $noteId, $contentPreview, $fullContent = '')
    {
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'internal_note_deleted',
            'title' => 'Internal Note Deleted',
            'description' => 'Internal note was deleted: ' . $contentPreview,
            'old_value' => $contentPreview,
            'field_name' => 'internal_note',
            'metadata' => json_encode([
                'note_id' => $noteId,
                'content_preview' => $contentPreview,
                'full_content' => $fullContent,
                'action' => 'deleted'
            ])
        ]);
    }

    /**
     * Log internal note reply added activity
     */
    public function logInternalNoteReplyAdded($orderId, $userId, $noteId, $replyId, $contentPreview, $fullContent = '')
    {
        return $this->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'internal_note_reply_added',
            'title' => 'Internal Note Reply Added',
            'description' => 'Reply was added to internal note: ' . $contentPreview,
            'new_value' => $contentPreview,
            'field_name' => 'internal_note_reply',
            'metadata' => json_encode([
                'note_id' => $noteId,
                'reply_id' => $replyId,
                'content_preview' => $contentPreview,
                'full_content' => $fullContent,
                'action' => 'reply_added'
            ])
        ]);
    }
} 