<?php

namespace Modules\ServiceOrders\Models;

use CodeIgniter\Model;

class ServiceOrderFollowerModel extends Model
{
    protected $table = 'service_order_followers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'service_order_id',
        'user_id',
        'added_by',
        'follower_type',
        'notification_preferences',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'service_order_id' => 'required|integer',
        'user_id' => 'required|integer',
        'added_by' => 'required|integer',
        'follower_type' => 'required|in_list[staff,client_contact]',
        'status' => 'permit_empty|in_list[active,paused,removed]'
    ];

    protected $validationMessages = [
        'service_order_id' => [
            'required' => 'Service order ID is required',
            'integer' => 'Service order ID must be a valid integer'
        ],
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be a valid integer'
        ],
        'follower_type' => [
            'required' => 'Follower type is required',
            'in_list' => 'Follower type must be either staff or client_contact'
        ]
    ];

    /**
     * Get followers for a service order with user details
     */
    public function getFollowersWithDetails($serviceOrderId)
    {
        return $this->select('
                service_order_followers.*,
                users.first_name,
                users.last_name,
                users.username,
                auth_identities.secret as email,
                users.phone,
                users.avatar,
                users.avatar_style,
                CONCAT(users.first_name, " ", users.last_name) as full_name,
                added_by_user.first_name as added_by_first_name,
                added_by_user.last_name as added_by_last_name,
                CONCAT(added_by_user.first_name, " ", added_by_user.last_name) as added_by_name
            ')
            ->join('users', 'users.id = service_order_followers.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->join('users as added_by_user', 'added_by_user.id = service_order_followers.added_by', 'left')
            ->where('service_order_followers.service_order_id', $serviceOrderId)
            ->where('service_order_followers.status !=', 'removed')
            ->groupBy('service_order_followers.id')
            ->orderBy('service_order_followers.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get available users to add as followers (same client only)
     */
    public function getAvailableFollowers($serviceOrderId, $clientId)
    {
        // Get users already following this order
        $existingFollowers = $this->select('user_id')
            ->where('service_order_id', $serviceOrderId)
            ->where('status !=', 'removed')
            ->findColumn('user_id');

        $userModel = new \App\Models\UserModel();
        $query = $userModel->select('
                users.id,
                users.first_name,
                users.last_name,
                users.username,
                auth_identities.secret as email,
                users.phone,
                CONCAT(users.first_name, " ", users.last_name) as full_name,
                "client_contact" as suggested_type
            ')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->where('users.client_id', $clientId)
            ->where('users.active', 1)
            ->where('users.user_type', 'client')
            ->groupBy('users.id');

        // Exclude existing followers
        if (!empty($existingFollowers)) {
            $query->whereNotIn('users.id', $existingFollowers);
        }

        $clientContacts = $query->findAll();

        // Also get staff users (they can follow any order)
        $staffQuery = $userModel->select('
                users.id,
                users.first_name,
                users.last_name,
                users.username,
                auth_identities.secret as email,
                users.phone,
                CONCAT(users.first_name, " ", users.last_name) as full_name,
                "staff" as suggested_type
            ')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->where('users.user_type', 'staff')
            ->where('users.active', 1)
            ->groupBy('users.id');

        if (!empty($existingFollowers)) {
            $staffQuery->whereNotIn('users.id', $existingFollowers);
        }

        $staffUsers = $staffQuery->findAll();

        return [
            'client_contacts' => $clientContacts,
            'staff_users' => $staffUsers
        ];
    }

    /**
     * Add a follower to a service order
     */
    public function addFollower($serviceOrderId, $userId, $addedBy, $followerType = 'client_contact', $notificationPreferences = null)
    {
        // Check if user is already a follower
        $existing = $this->where('service_order_id', $serviceOrderId)
                        ->where('user_id', $userId)
                        ->first();

        if ($existing) {
            if ($existing['status'] === 'removed') {
                // Reactivate removed follower
                return $this->update($existing['id'], [
                    'status' => 'active',
                    'added_by' => $addedBy,
                    'follower_type' => $followerType,
                    'notification_preferences' => $notificationPreferences ? json_encode($notificationPreferences) : null,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                throw new \Exception('User is already following this order');
            }
        }

        // Add new follower
        $data = [
            'service_order_id' => $serviceOrderId,
            'user_id' => $userId,
            'added_by' => $addedBy,
            'follower_type' => $followerType,
            'notification_preferences' => $notificationPreferences ? json_encode($notificationPreferences) : json_encode($this->getDefaultNotificationPreferences()),
            'status' => 'active'
        ];

        $followerId = $this->insert($data);

        if ($followerId) {
            // Log the activity
            $this->logFollowerActivity($serviceOrderId, $followerId, 'added', $addedBy, [
                'follower_type' => $followerType,
                'notification_preferences' => $notificationPreferences
            ]);
        }

        return $followerId;
    }

    /**
     * Remove a follower from a service order
     */
    public function removeFollower($serviceOrderId, $userId, $removedBy)
    {
        $follower = $this->where('service_order_id', $serviceOrderId)
                        ->where('user_id', $userId)
                        ->where('status !=', 'removed')
                        ->first();

        if (!$follower) {
            throw new \Exception('Follower not found');
        }

        $result = $this->update($follower['id'], [
            'status' => 'removed',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($result) {
            // Log the activity
            $this->logFollowerActivity($serviceOrderId, $follower['id'], 'removed', $removedBy);
        }

        return $result;
    }

    /**
     * Update follower notification preferences
     */
    public function updateNotificationPreferences($serviceOrderId, $userId, $preferences)
    {
        $follower = $this->where('service_order_id', $serviceOrderId)
                        ->where('user_id', $userId)
                        ->where('status', 'active')
                        ->first();

        if (!$follower) {
            throw new \Exception('Active follower not found');
        }

        return $this->update($follower['id'], [
            'notification_preferences' => json_encode($preferences),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get default notification preferences
     */
    private function getDefaultNotificationPreferences()
    {
        return [
            'status_changes' => true,
            'new_comments' => true,
            'mentions' => true,
            'assignments' => true,
            'email_notifications' => true,
            'sms_notifications' => false,
            'push_notifications' => true
        ];
    }

    /**
     * Log follower activity
     */
    private function logFollowerActivity($serviceOrderId, $followerId, $action, $performedBy, $details = [])
    {
        $db = \Config\Database::connect();
        
        $data = [
            'service_order_id' => $serviceOrderId,
            'follower_id' => $followerId,
            'action' => $action,
            'performed_by' => $performedBy,
            'details' => json_encode($details),
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $db->table('service_order_follower_activity')->insert($data);
    }

    /**
     * Get followers for notification (active only)
     */
    public function getFollowersForNotification($serviceOrderId, $notificationType = null)
    {
        $query = $this->select('
                service_order_followers.*,
                users.first_name,
                users.last_name,
                users.email,
                users.phone,
                CONCAT(users.first_name, " ", users.last_name) as full_name
            ')
            ->join('users', 'users.id = service_order_followers.user_id', 'left')
            ->where('service_order_followers.service_order_id', $serviceOrderId)
            ->where('service_order_followers.status', 'active')
            ->where('users.active', 1);

        $followers = $query->findAll();

        // Filter by notification preferences if specified
        if ($notificationType && !empty($followers)) {
            $filtered = [];
            foreach ($followers as $follower) {
                $preferences = json_decode($follower['notification_preferences'] ?? '{}', true);
                if (isset($preferences[$notificationType]) && $preferences[$notificationType]) {
                    $filtered[] = $follower;
                }
            }
            return $filtered;
        }

        return $followers;
    }

    /**
     * Check if user is following an order
     */
    public function isUserFollowing($serviceOrderId, $userId)
    {
        return $this->where('service_order_id', $serviceOrderId)
                   ->where('user_id', $userId)
                   ->where('status', 'active')
                   ->countAllResults() > 0;
    }

    /**
     * Get follower count for an order
     */
    public function getFollowerCount($serviceOrderId)
    {
        return $this->where('service_order_id', $serviceOrderId)
                   ->where('status', 'active')
                   ->countAllResults();
    }
} 