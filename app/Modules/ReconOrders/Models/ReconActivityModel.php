<?php

namespace Modules\ReconOrders\Models;

use CodeIgniter\Model;

class ReconActivityModel extends Model
{
    protected $table = 'recon_activity';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id', 'user_id', 'action', 'description',
        'old_values', 'new_values', 'metadata', 'created_at'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = false;

    public function getActivityForOrder($orderId, $limit = 50)
    {
        return $this->select('
            recon_activity.*,
            users.first_name,
            users.last_name,
            (SELECT auth_identities.secret 
             FROM auth_identities 
             WHERE auth_identities.user_id = users.id 
             AND auth_identities.type = "email_password" 
             LIMIT 1) as email
        ')
        ->join('users', 'users.id = recon_activity.user_id', 'left')
        ->where('recon_activity.order_id', $orderId)
        ->orderBy('recon_activity.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }

    public function logActivity($orderId, $userId, $action, $description, $oldValues = null, $newValues = null, $metadata = null)
    {
        $data = [
            'order_id' => $orderId,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
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
        
        // Create old and new values arrays
        $oldValues = [$fieldName => $oldValue];
        $newValues = [$fieldName => $newValue];
        
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
            $oldValues,
            $newValues,
            $metadata
        );
    }

    public function logStatusChange($orderId, $userId, $oldStatus, $newStatus)
    {
        $oldStatusLabel = ucfirst(str_replace('_', ' ', $oldStatus));
        $newStatusLabel = ucfirst(str_replace('_', ' ', $newStatus));
        
        $description = "Changed status from {$oldStatusLabel} to {$newStatusLabel}";
        
        $oldValues = ['status' => $oldStatus];
        $newValues = ['status' => $newStatus];
        
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
            $oldValues,
            $newValues,
            $metadata
        );
    }

    public function logPicturesChange($orderId, $userId, $oldPictures, $newPictures)
    {
        $oldLabel = $oldPictures ? 'Pictures Done' : 'No pictures';
        $newLabel = $newPictures ? 'Pictures Done' : 'No pictures';
        
        $description = "Changed pictures status from {$oldLabel} to {$newLabel}";
        
        $oldValues = ['pictures' => $oldPictures];
        $newValues = ['pictures' => $newPictures];
        
        $metadata = [
            'field_name' => 'pictures',
            'field_label' => 'Pictures',
            'old_value' => $oldPictures,
            'new_value' => $newPictures,
            'old_pictures_label' => $oldLabel,
            'new_pictures_label' => $newLabel
        ];
        
        return $this->logActivity(
            $orderId,
            $userId,
            'pictures_change',
            $description,
            $oldValues,
            $newValues,
            $metadata
        );
    }

    public function logOrderCreated($orderId, $userId)
    {
        return $this->logActivity(
            $orderId,
            $userId,
            'order_created',
            'Recon order created',
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
            'Recon order updated',
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
            'Recon order deleted',
            null,
            null,
            null,
            ['action' => 'deleted']
        );
    }

    private function getDisplayValue($fieldName, $value)
    {
        // Convert IDs to readable names
        switch ($fieldName) {
            case 'client_id':
                if ($value) {
                    $clientModel = new \App\Models\ClientModel();
                    $client = $clientModel->find($value);
                    return $client ? $client['name'] : $value;
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
            
            case 'pictures':
                return $value ? 'Pictures Done' : 'No pictures';
            
            default:
                return $value;
        }
        
        return $value;
    }
} 