<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\CustomUserModel;
use App\Models\CustomRoleModel;

class CreateAdmin extends BaseCommand
{
    protected $group       = 'Admin';
    protected $name        = 'admin:create';
    protected $description = 'Create a new admin user with full permissions';

    protected $usage = 'admin:create [options]';
    protected $options = [
        '--username' => 'Username for the admin user',
        '--email'    => 'Email address for the admin user',
        '--password' => 'Password for the admin user',
        '--firstname' => 'First name of the admin user',
        '--lastname'  => 'Last name of the admin user',
        '--interactive' => 'Run in interactive mode (asks for input)'
    ];

    public function run(array $params)
    {
        CLI::write('Creating Admin User...', 'green');
        CLI::newLine();

        $interactive = CLI::getOption('interactive') || empty($params);
        
        // Get user input
        if ($interactive) {
            $username = CLI::prompt('Username', null, 'required|min_length[3]|max_length[30]');
            $email = CLI::prompt('Email', null, 'required|valid_email');
            $password = CLI::prompt('Password', null, 'required|min_length[8]');
            $firstName = CLI::prompt('First Name', null, 'required');
            $lastName = CLI::prompt('Last Name (optional)', '');
        } else {
            $username = CLI::getOption('username') ?: 'admin';
            $email = CLI::getOption('email') ?: 'admin@example.com';
            $password = CLI::getOption('password') ?: 'admin123456';
            $firstName = CLI::getOption('firstname') ?: 'Administrator';
            $lastName = CLI::getOption('lastname') ?: '';
        }

        try {
            // Check if user already exists
            $userProvider = auth()->getProvider();
            $existingUser = $userProvider->findByCredentials(['email' => $email]);
            
            if ($existingUser) {
                CLI::error('User with this email already exists!');
                return;
            }

            // Create the user using Shield
            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'user_type' => 'staff',
                'active' => 1,
            ];

            // Create user
            $user = $userProvider->create($userData);
            
            if (!$user) {
                CLI::error('Failed to create user!');
                return;
            }

            // Activate the user
            $user->activate();

            // Add to admin group
            $user->addGroup('admin');
            
            // Also add to superadmin if needed
            $user->addGroup('superadmin');

            // Find and assign admin role from custom_roles table
            $customRoleModel = new CustomRoleModel();
            $adminRole = $customRoleModel->where('name', 'admin')->first();
            
            if ($adminRole) {
                // Update user with admin role_id
                $customUserModel = new CustomUserModel();
                $customUserModel->update($user->id, ['role_id' => $adminRole['id']]);
                
                CLI::write("✓ Admin role assigned (ID: {$adminRole['id']})", 'green');
            }

            CLI::newLine();
            CLI::write('✓ Admin user created successfully!', 'green');
            CLI::write("User ID: {$user->id}", 'yellow');
            CLI::write("Username: {$username}", 'yellow');
            CLI::write("Email: {$email}", 'yellow');
            CLI::write("Groups: admin, superadmin", 'yellow');
            
            if ($adminRole) {
                CLI::write("Custom Role: {$adminRole['title']} (ID: {$adminRole['id']})", 'yellow');
            }

            CLI::newLine();
            CLI::write('You can now login with these credentials.', 'cyan');

        } catch (\Exception $e) {
            CLI::error('Error creating admin user: ' . $e->getMessage());
            CLI::write($e->getTraceAsString(), 'red');
        }
    }
}