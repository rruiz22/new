<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel;
use App\Entities\User;

class CustomUserModel extends UserModel
{
    protected $returnType     = User::class;
    protected $allowedFields = [
        'username',
        'status',
        'status_message',
        'active',
        'last_active',
        'deleted_at',
        'first_name',
        'last_name',
        'user_type',
        'role_id',
        'phone',
        'avatar',
        'avatar_style',
        'web_notifications',
        'email_notifications',
        'sms_notifications',
        'client_permissions',
        'deleted',
        'date_format',
        'timezone',
        'client_id',
    ];
    
    /**
     * Make sure custom fields are properly saved
     */
    public function save($data): bool
    {
        // If it's a User entity, we need special handling for custom fields
        if ($data instanceof User || $data instanceof \CodeIgniter\Shield\Entities\User) {
            $result = parent::save($data);
            
            // After saving through Shield, we need to directly update our custom fields
            if ($result && $data->id) {
                $customFields = [
                    'first_name' => $data->first_name ?? null,
                    'last_name' => $data->last_name ?? null,
                    'user_type' => $data->user_type ?? null,
                    'phone' => $data->phone ?? null,
                    'client_id' => $data->client_id ?? null
                ];
                
                // Filter out null values
                $customFields = array_filter($customFields, function($value) {
                    return $value !== null;
                });
                
                // Only update if we have custom fields to update
                if (!empty($customFields)) {
                    $this->builder()
                        ->where('id', $data->id)
                        ->update($customFields);
                    
                    // Log the custom field update
                    log_message('debug', 'CUSTOM FIELDS - Updated for user ID: ' . $data->id . ', Data: ' . print_r($customFields, true));
                }
            }
            
            return $result;
        }
        
        // Regular array save
        return parent::save($data);
    }

    /**
     * Get users that are in a specific group
     * 
     * @param string $group The group name
     * @return array
     */
    public function getUsersInGroup(string $group)
    {
        $groupUsers = $this->db->table('auth_groups_users')
            ->where('group', $group)
            ->get()
            ->getResultArray();
        
        if (empty($groupUsers)) {
            return [];
        }
        
        $userIds = array_column($groupUsers, 'user_id');
        
        return $this->whereIn('id', $userIds)->findAll();
    }
    
    /**
     * Get users that are not in a specific group
     * 
     * @param string $group The group name
     * @return array
     */
    public function getUsersNotInGroup(string $group)
    {
        $groupUsers = $this->db->table('auth_groups_users')
            ->where('group', $group)
            ->get()
            ->getResultArray();
        
        if (empty($groupUsers)) {
            return [];
        }
        
        $userIds = array_column($groupUsers, 'user_id');
        
        return $this->whereNotIn('id', $userIds)->findAll();
    }
} 