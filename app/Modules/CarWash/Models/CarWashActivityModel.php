<?php

namespace Modules\CarWash\Models;

use CodeIgniter\Model;

class CarWashActivityModel extends Model
{
    protected $table = 'car_wash_activity';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id', 'user_id', 'activity_type', 'description',
        'field_name', 'old_value', 'new_value', 'metadata', 'created_at'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getActivityForOrder($orderId, $limit = 50)
    {
        return $this->select('
            car_wash_activity.*,
            users.first_name,
            users.last_name,
            (SELECT auth_identities.secret 
             FROM auth_identities 
             WHERE auth_identities.user_id = users.id 
             AND auth_identities.type = "email_password" 
             LIMIT 1) as email
        ')
        ->join('users', 'users.id = car_wash_activity.user_id', 'left')
        ->where('car_wash_activity.order_id', $orderId)
        ->orderBy('car_wash_activity.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }

    public function logActivity($orderId, $userId, $activityType, $description, $fieldName = null, $oldValue = null, $newValue = null, $metadata = null)
    {
        $data = [
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => $activityType,
            'description' => $description,
            'field_name' => $fieldName,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'metadata' => $metadata ? json_encode($metadata) : null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    public function logFieldChange($orderId, $userId, $fieldName, $oldValue, $newValue, $fieldLabel)
    {
        $description = "Updated {$fieldLabel}";
        
        // Get display values for the field
        $oldDisplayValue = $this->getDisplayValue($fieldName, $oldValue);
        $newDisplayValue = $this->getDisplayValue($fieldName, $newValue);
        
        // Create enhanced metadata for frontend display
        $metadata = [
            'field_name' => $fieldName,
            'field_label' => $fieldLabel,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'old_display_value' => $oldDisplayValue,
            'new_display_value' => $newDisplayValue
        ];
        
        return $this->logActivity(
            $orderId,
            $userId,
            'field_change',
            $description,
            $fieldName,
            $oldValue,
            $newValue,
            $metadata
        );
    }

    public function logStatusChange($orderId, $userId, $oldStatus, $newStatus)
    {
        $oldStatusLabel = ucfirst(str_replace('_', ' ', $oldStatus));
        $newStatusLabel = ucfirst(str_replace('_', ' ', $newStatus));
        
        $description = "Changed status from {$oldStatusLabel} to {$newStatusLabel}";
        
        $metadata = [
            'field_name' => 'status',
            'field_label' => 'Status',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'old_status_label' => $oldStatusLabel,
            'new_status_label' => $newStatusLabel
        ];
        
        return $this->logActivity(
            $orderId,
            $userId,
            'status_change',
            $description,
            'status',
            $oldStatus,
            $newStatus,
            $metadata
        );
    }

    public function logOrderCreated($orderId, $userId)
    {
        return $this->logActivity(
            $orderId,
            $userId,
            'order_created',
            'Car wash order created',
            null,
            null,
            null,
            ['action' => 'created']
        );
    }

    public function logOrderUpdated($orderId, $userId)
    {
        return $this->logActivity(
            $orderId,
            $userId,
            'order_updated',
            'Car wash order updated',
            null,
            null,
            null,
            ['action' => 'updated']
        );
    }

    public function logOrderDeleted($orderId, $userId)
    {
        return $this->logActivity(
            $orderId,
            $userId,
            'order_deleted',
            'Car wash order deleted',
            null,
            null,
            null,
            ['action' => 'deleted']
        );
    }

    public function logOrderRestored($orderId, $userId)
    {
        return $this->logActivity(
            $orderId,
            $userId,
            'order_restored',
            'Car wash order restored',
            null,
            null,
            null,
            ['action' => 'restored']
        );
    }

    public function logCommentAdded($orderId, $userId)
    {
        return $this->logActivity(
            $orderId,
            $userId,
            'comment_added',
            'Added a comment'
        );
    }

    public function logCommentUpdated($orderId, $userId)
    {
        return $this->logActivity(
            $orderId,
            $userId,
            'comment_updated',
            'Updated a comment'
        );
    }

    public function logCommentDeleted($orderId, $userId)
    {
        return $this->logActivity(
            $orderId,
            $userId,
            'comment_deleted',
            'Deleted a comment'
        );
    }

    public function logEmailSent($orderId, $userId, $emailType = 'notification')
    {
        return $this->logActivity(
            $orderId,
            $userId,
            'email_sent',
            "Sent {$emailType} email",
            null,
            null,
            null,
            ['email_type' => $emailType]
        );
    }

    public function logSMSSent($orderId, $userId)
    {
        return $this->logActivity(
            $orderId,
            $userId,
            'sms_sent',
            'Sent SMS notification',
            null,
            null,
            null,
            ['notification_type' => 'sms']
        );
    }

    public function getDisplayValue($fieldName, $value)
    {
        if (empty($value)) {
            return $value;
        }

        // Convert IDs to readable names
        switch ($fieldName) {
            case 'client_id':
                if ($value) {
                    $clientModel = new \App\Models\ClientModel();
                    $client = $clientModel->find($value);
                    return $client ? $client['name'] : $value;
                }
                break;
            
            case 'contact_id':
                if ($value) {
                    $contactModel = new \App\Models\ContactModel();
                    $contact = $contactModel->find($value);
                    return $contact ? $contact['first_name'] . ' ' . $contact['last_name'] : $value;
                }
                break;
            
            case 'service_id':
                if ($value) {
                    $serviceModel = new \Modules\CarWash\Models\CarWashServiceModel();
                    $service = $serviceModel->find($value);
                    return $service ? $service['name'] : $value;
                }
                break;
            
            case 'assigned_to':
                if ($value) {
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->find($value);
                    return $user ? $user['first_name'] . ' ' . $user['last_name'] : $value;
                }
                break;
            
            case 'status':
                return ucfirst(str_replace('_', ' ', $value));
            
            case 'priority':
                return ucfirst($value);
            
            case 'service_type':
                return ucfirst(str_replace('_', ' ', $value));
            
            default:
                return $value;
        }
        
        return $value;
    }

    public function getRecentActivity($limit = 10)
    {
        return $this->select('
            car_wash_activity.*,
            users.first_name,
            users.last_name,
            car_wash_orders.order_number,
            clients.name as client_name
        ')
        ->join('users', 'users.id = car_wash_activity.user_id', 'left')
        ->join('car_wash_orders', 'car_wash_orders.id = car_wash_activity.order_id', 'left')
        ->join('clients', 'clients.id = car_wash_orders.client_id', 'left')
        ->orderBy('car_wash_activity.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }

    /**
     * Log comment activity (enhanced version for replies, mentions, etc.) - Same as Sales Orders
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
        
        $data = [
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => $action,
            'description' => $description,
            'metadata' => json_encode($metadata),
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    /**
     * Log internal note activity - Same as Sales Orders
     */
    public function logInternalNoteActivity($orderId, $userId, $action, $description, $metadata = [])
    {
        $titles = [
            'internal_note_added' => 'Internal Note Added',
            'internal_note_updated' => 'Internal Note Updated',
            'internal_note_deleted' => 'Internal Note Deleted',
            'internal_note_reply_added' => 'Internal Note Reply Added'
        ];
        
        $title = $titles[$action] ?? ucfirst(str_replace('_', ' ', $action));
        
        $data = [
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => $action,
            'description' => $description,
            'metadata' => json_encode($metadata),
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    /**
     * Log internal note added
     */
    public function logInternalNoteAdded($orderId, $userId, $contentPreview = '', $fullContent = '')
    {
        $shortPreview = substr(trim($contentPreview), 0, 15) . (strlen(trim($contentPreview)) > 15 ? '...' : '');
        $description = "Added internal note: \"{$shortPreview}\"";
        
        return $this->logInternalNoteActivity($orderId, $userId, 'internal_note_added', $description, [
            'action' => 'internal_note_added',
            'content_preview' => substr(trim($contentPreview), 0, 100) . (strlen(trim($contentPreview)) > 100 ? '...' : ''),
            'full_content' => $fullContent ?: $contentPreview
        ]);
    }

    /**
     * Log internal note updated
     */
    public function logInternalNoteUpdated($orderId, $userId, $contentPreview = '', $fullContent = '')
    {
        $shortPreview = substr(trim($contentPreview), 0, 15) . (strlen(trim($contentPreview)) > 15 ? '...' : '');
        $description = "Updated internal note: \"{$shortPreview}\"";
        
        return $this->logInternalNoteActivity($orderId, $userId, 'internal_note_updated', $description, [
            'action' => 'internal_note_updated',
            'content_preview' => substr(trim($contentPreview), 0, 100) . (strlen(trim($contentPreview)) > 100 ? '...' : ''),
            'full_content' => $fullContent ?: $contentPreview
        ]);
    }

    /**
     * Log internal note deleted
     */
    public function logInternalNoteDeleted($orderId, $userId, $contentPreview = '', $fullContent = '')
    {
        $shortPreview = substr(trim($contentPreview), 0, 15) . (strlen(trim($contentPreview)) > 15 ? '...' : '');
        $description = "Deleted internal note: \"{$shortPreview}\"";
        
        return $this->logInternalNoteActivity($orderId, $userId, 'internal_note_deleted', $description, [
            'action' => 'internal_note_deleted',
            'content_preview' => substr(trim($contentPreview), 0, 100) . (strlen(trim($contentPreview)) > 100 ? '...' : ''),
            'full_content' => $fullContent ?: $contentPreview,
            'deleted_note' => $fullContent ?: $contentPreview
        ]);
    }

    /**
     * Log internal note reply added
     */
    public function logInternalNoteReplyAdded($orderId, $userId, $contentPreview = '', $fullContent = '')
    {
        $shortPreview = substr(trim($contentPreview), 0, 15) . (strlen(trim($contentPreview)) > 15 ? '...' : '');
        $description = "Added reply to internal note: \"{$shortPreview}\"";
        
        return $this->logInternalNoteActivity($orderId, $userId, 'internal_note_reply_added', $description, [
            'action' => 'internal_note_reply_added',
            'content_preview' => substr(trim($contentPreview), 0, 100) . (strlen(trim($contentPreview)) > 100 ? '...' : ''),
            'full_content' => $fullContent ?: $contentPreview
        ]);
    }
} 