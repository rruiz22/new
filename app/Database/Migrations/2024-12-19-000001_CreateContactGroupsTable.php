<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContactGroupsTable extends Migration
{
    public function up()
    {
        // Contact Groups Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'default'    => '#3577f1',
            ],
            'icon' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'users',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('name');
        $this->forge->addKey(['is_active', 'sort_order']);
        $this->forge->createTable('contact_groups');

        // Contact Group Permissions Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'general',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey(['category', 'is_active']);
        $this->forge->createTable('contact_permissions');

        // Contact Group Permissions Pivot Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'group_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'permission_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['group_id', 'permission_id']);
        $this->forge->addForeignKey('group_id', 'contact_groups', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permission_id', 'contact_permissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('contact_group_permissions');

        // User Contact Groups Pivot Table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'group_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'assigned_by' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'assigned_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'group_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('group_id', 'contact_groups', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_contact_groups');

        // Insert default permissions
        $this->insertDefaultPermissions();
        
        // Insert default groups
        $this->insertDefaultGroups();
    }

    public function down()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
        
        $this->forge->dropTable('user_contact_groups', true);
        $this->forge->dropTable('contact_group_permissions', true);
        $this->forge->dropTable('contact_permissions', true);
        $this->forge->dropTable('contact_groups', true);
        
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');
    }

    private function insertDefaultPermissions()
    {
        $permissions = [
            // Sales Orders
            [
                'name' => 'View Sales Orders',
                'slug' => 'view_sales_orders',
                'description' => 'Can view sales orders list and details',
                'category' => 'sales_orders'
            ],
            [
                'name' => 'Create Sales Orders',
                'slug' => 'create_sales_orders',
                'description' => 'Can create new sales orders',
                'category' => 'sales_orders'
            ],
            [
                'name' => 'Edit Sales Orders',
                'slug' => 'edit_sales_orders',
                'description' => 'Can edit existing sales orders',
                'category' => 'sales_orders'
            ],
            [
                'name' => 'Delete Sales Orders',
                'slug' => 'delete_sales_orders',
                'description' => 'Can delete sales orders',
                'category' => 'sales_orders'
            ],
            [
                'name' => 'Approve Sales Orders',
                'slug' => 'approve_sales_orders',
                'description' => 'Can approve sales orders',
                'category' => 'sales_orders'
            ],
            
            // Dashboard
            [
                'name' => 'View Dashboard',
                'slug' => 'view_dashboard',
                'description' => 'Can access main dashboard',
                'category' => 'dashboard'
            ],
            [
                'name' => 'View Reports',
                'slug' => 'view_reports',
                'description' => 'Can view reports and analytics',
                'category' => 'dashboard'
            ],
            
            // Profile
            [
                'name' => 'Edit Profile',
                'slug' => 'edit_profile',
                'description' => 'Can edit own profile information',
                'category' => 'profile'
            ],
            [
                'name' => 'Change Password',
                'slug' => 'change_password',
                'description' => 'Can change own password',
                'category' => 'profile'
            ],
            
            // Communication
            [
                'name' => 'Send Messages',
                'slug' => 'send_messages',
                'description' => 'Can send messages and notifications',
                'category' => 'communication'
            ],
            [
                'name' => 'Access Chat',
                'slug' => 'access_chat',
                'description' => 'Can access chat functionality',
                'category' => 'communication'
            ],
            
            // Documents
            [
                'name' => 'View Documents',
                'slug' => 'view_documents',
                'description' => 'Can view and download documents',
                'category' => 'documents'
            ],
            [
                'name' => 'Upload Documents',
                'slug' => 'upload_documents',
                'description' => 'Can upload new documents',
                'category' => 'documents'
            ],
        ];

        $this->db->table('contact_permissions')->insertBatch($permissions);
    }

    private function insertDefaultGroups()
    {
        $groups = [
            [
                'name' => 'Basic Access',
                'description' => 'Basic access for viewing information only',
                'color' => '#6c757d',
                'icon' => 'eye',
                'sort_order' => 1
            ],
            [
                'name' => 'Standard User',
                'description' => 'Standard access for most client operations',
                'color' => '#3577f1',
                'icon' => 'user',
                'sort_order' => 2
            ],
            [
                'name' => 'Advanced User',
                'description' => 'Advanced access with additional permissions',
                'color' => '#0ab39c',
                'icon' => 'star',
                'sort_order' => 3
            ],
            [
                'name' => 'Administrator',
                'description' => 'Full access to all client features',
                'color' => '#f06548',
                'icon' => 'shield',
                'sort_order' => 4
            ],
        ];

        foreach ($groups as $group) {
            $groupId = $this->db->table('contact_groups')->insert($group);
            
            // Assign permissions based on group type
            $this->assignDefaultPermissions($groupId, $group['name']);
        }
    }

    private function assignDefaultPermissions($groupId, $groupName)
    {
        $permissionSlugs = [];
        
        switch ($groupName) {
            case 'Basic Access':
                $permissionSlugs = [
                    'view_dashboard',
                    'edit_profile',
                    'view_documents'
                ];
                break;
                
            case 'Standard User':
                $permissionSlugs = [
                    'view_dashboard',
                    'view_sales_orders',
                    'edit_profile',
                    'change_password',
                    'view_documents',
                    'send_messages'
                ];
                break;
                
            case 'Advanced User':
                $permissionSlugs = [
                    'view_dashboard',
                    'view_reports',
                    'view_sales_orders',
                    'create_sales_orders',
                    'edit_sales_orders',
                    'edit_profile',
                    'change_password',
                    'view_documents',
                    'upload_documents',
                    'send_messages',
                    'access_chat'
                ];
                break;
                
            case 'Administrator':
                // Get all permission IDs for full access
                $permissions = $this->db->table('contact_permissions')
                                      ->select('id')
                                      ->where('is_active', 1)
                                      ->get()
                                      ->getResultArray();
                
                foreach ($permissions as $permission) {
                    $this->db->table('contact_group_permissions')->insert([
                        'group_id' => $groupId,
                        'permission_id' => $permission['id'],
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
                return;
        }
        
        // Get permission IDs for the selected slugs
        if (!empty($permissionSlugs)) {
            $permissions = $this->db->table('contact_permissions')
                                  ->select('id')
                                  ->whereIn('slug', $permissionSlugs)
                                  ->where('is_active', 1)
                                  ->get()
                                  ->getResultArray();
            
            foreach ($permissions as $permission) {
                $this->db->table('contact_group_permissions')->insert([
                    'group_id' => $groupId,
                    'permission_id' => $permission['id'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
} 