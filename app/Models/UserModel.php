<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'username', 'status', 'status_message', 'active', 'last_active',
        'first_name', 'last_name', 'user_type', 'role_id', 'phone',
        'web_notifications', 'email_notifications', 'sms_notifications',
        'client_permissions', 'deleted', 'avatar', 'date_format', 'timezone',
        'client_id'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'username'      => 'permit_empty|min_length[3]|max_length[30]|is_unique[users.username,id,{id}]',
        'first_name'    => 'required|min_length[2]|max_length[255]',
        'last_name'     => 'permit_empty|max_length[255]',
        'phone'         => 'permit_empty|max_length[20]',
        'user_type'     => 'permit_empty|max_length[50]',
        'active'        => 'permit_empty|integer|in_list[0,1]',
        'status'        => 'permit_empty|max_length[255]',
    ];
    
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
    /**
     * Create a new client user
     *
     * @param array $contactData Data for the contact
     * @return int|false User ID if successful, false otherwise
     */
    public function createClientUser(array $contactData)
    {
        // Generate a username from the email if not provided
        $username = isset($contactData['username']) && !empty($contactData['username'])
                    ? $contactData['username']
                    : substr(explode('@', $contactData['email'])[0], 0, 30);
        
        // Check if username already exists, if so, append a number
        $baseUsername = $username;
        $counter = 1;
        while ($this->where('username', $username)->first()) {
            $username = substr($baseUsername, 0, 27) . $counter;
            $counter++;
        }
        
        // Use provided first/last name or split the name field
        $firstName = $contactData['first_name'] ?? '';
        $lastName = $contactData['last_name'] ?? '';
        
        if (empty($firstName) && !empty($contactData['name'])) {
            $nameParts = explode(' ', $contactData['name'], 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
        }
        
        $userData = [
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $contactData['phone'] ?? null,
            'user_type' => 'client',
            'active' => $contactData['status'] === 'active' ? 1 : 0,
            'status' => $contactData['status'] ?? 'active',
            'client_id' => $contactData['client_id'] ?? null,
        ];
        
        return $this->insert($userData);
    }
    
    /**
     * Update a client user
     *
     * @param int $userId User ID to update
     * @param array $contactData Updated contact data
     * @return bool Success or failure
     */
    public function updateClientUser(int $userId, array $contactData)
    {
        if (!$userId) {
            return false;
        }
        
        // Use provided first/last name or split the name field
        $firstName = $contactData['first_name'] ?? '';
        $lastName = $contactData['last_name'] ?? '';
        
        if (empty($firstName) && !empty($contactData['name'])) {
            $nameParts = explode(' ', $contactData['name'], 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
        }
        
        $userData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $contactData['phone'] ?? null,
            'active' => $contactData['status'] === 'active' ? 1 : 0,
            'status' => $contactData['status'] ?? 'active',
            'client_id' => $contactData['client_id'] ?? null,
        ];
        
        // Update username if provided
        if (isset($contactData['username']) && !empty($contactData['username'])) {
            $userData['username'] = $contactData['username'];
        }
        
        return $this->update($userId, $userData);
    }
    
    /**
     * Get user by email (used for Auth)
     *
     * @param string $email
     * @return array|null
     */
    public function getUserByEmail(string $email)
    {
        // This would normally query the auth_identities table in Shield
        // This is a simplified example
        return $this->where('username', $email)->first();
    }
} 