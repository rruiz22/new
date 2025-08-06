<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Database\Database;

class SetupController extends BaseController
{
    public function createAdminGroup()
    {
        $db = \Config\Database::connect();
        
        // Check if there are any users to assign as admin
        $users = $db->table('users')->get()->getResult();
        if (empty($users)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No users found to assign as admin.'
            ]);
        }
        
        // Get the first user to make them admin
        $firstUser = $users[0];
        
        // Check if the first user is already an admin
        $existingAdmin = $db->table('auth_groups_users')
                           ->where('user_id', $firstUser->id)
                           ->where('group', 'admin')
                           ->get()
                           ->getRow();
        
        if ($existingAdmin) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Admin group already assigned to user ID: ' . $firstUser->id
            ]);
        }
        
        // Make the first user an admin
        $db->table('auth_groups_users')->insert([
            'user_id' => $firstUser->id,
            'group' => 'admin',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Admin group created and assigned to user ID: ' . $firstUser->id
        ]);
    }
    
    public function assignAdminGroup($userId = null)
    {
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID is required.'
            ]);
        }
        
        $db = \Config\Database::connect();
        
        // Check if user exists
        $user = $db->table('users')
                  ->where('id', $userId)
                  ->get()
                  ->getRow();
        
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
        
        // Check if user is already in admin group
        $existingAssignment = $db->table('auth_groups_users')
                               ->where('user_id', $userId)
                               ->where('group', 'admin')
                               ->get()
                               ->getRow();
        
        if ($existingAssignment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User is already in the admin group.'
            ]);
        }
        
        // Add the user to the admin group
        $db->table('auth_groups_users')->insert([
            'user_id' => $userId,
            'group' => 'admin',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'User added to admin group successfully.'
        ]);
    }
    
    public function listUsers()
    {
        $db = \Config\Database::connect();
        $users = $db->table('users')
                   ->select('users.id, users.username, auth_identities.secret as email')
                   ->join('auth_identities', 'users.id = auth_identities.user_id', 'left')
                   ->where('auth_identities.type', 'email')
                   ->get()
                   ->getResult();
        
        $data = [];
        foreach ($users as $user) {
            // Get groups for this user
            $groups = $db->table('auth_groups_users')
                        ->where('user_id', $user->id)
                        ->get()
                        ->getResult();
            
            $groupNames = [];
            foreach ($groups as $group) {
                $groupNames[] = $group->group;
            }
            
            $data[] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'groups' => $groupNames
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'users' => $data
        ]);
    }
} 