<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomRolesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
                        'name' => [                'type'       => 'VARCHAR',                'constraint' => 50,            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'permissions' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'show_in_staff_form' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'null'       => true,
                'default'    => '#405189',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('name');
        $this->forge->addKey('is_active');
        $this->forge->addKey('show_in_staff_form');
        $this->forge->addKey('sort_order');
        $this->forge->addKey('deleted_at');
        
        $this->forge->createTable('custom_roles');

        // Insert default roles
        $data = [
            [
                'name' => 'admin',
                'title' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'permissions' => json_encode(['*']),
                'is_active' => 1,
                'show_in_staff_form' => 0, // Admins are typically not created through staff form
                'color' => '#dc3545',
                'sort_order' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'manager',
                'title' => 'Manager',
                'description' => 'Management level access with limited administrative permissions',
                'permissions' => json_encode([
                    'users.view', 'users.create', 'users.edit',
                    'clients.view', 'clients.create', 'clients.edit',
                    'contacts.view', 'contacts.create', 'contacts.edit',
                    'todo.view', 'todo.create', 'todo.edit', 'todo.delete',
                    'dashboard.view'
                ]),
                'is_active' => 1,
                'show_in_staff_form' => 1,
                'color' => '#f39c12',
                'sort_order' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'staff',
                'title' => 'Staff Member',
                'description' => 'Standard staff access with basic permissions',
                'permissions' => json_encode([
                    'todo.view', 'todo.create', 'todo.edit',
                    'contacts.view',
                    'dashboard.view'
                ]),
                'is_active' => 1,
                'show_in_staff_form' => 1,
                'color' => '#28a745',
                'sort_order' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'viewer',
                'title' => 'Viewer',
                'description' => 'Read-only access to basic information',
                'permissions' => json_encode([
                    'dashboard.view',
                    'todo.view',
                    'contacts.view'
                ]),
                'is_active' => 1,
                'show_in_staff_form' => 1,
                'color' => '#6c757d',
                'sort_order' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('custom_roles')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('custom_roles');
    }
}
