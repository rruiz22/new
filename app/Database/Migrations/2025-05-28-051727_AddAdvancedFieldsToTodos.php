<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdvancedFieldsToTodos extends Migration
{
    public function up()
    {
        $fields = [
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'user_id'
            ],
            'tags' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'category'
            ],
            'estimated_hours' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'after' => 'tags'
            ],
            'actual_hours' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'after' => 'estimated_hours'
            ],
            'reminder_sent' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'actual_hours'
            ],
            'completion_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'reminder_sent'
            ],
            'assigned_to' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'completion_notes'
            ],
            'project_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'assigned_to'
            ]
        ];

        $this->forge->addColumn('todos', $fields);

        // Update status enum to include new values
        $this->db->query("ALTER TABLE todos MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
        
        // Update priority enum to include urgent
        $this->db->query("ALTER TABLE todos MODIFY COLUMN priority ENUM('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'medium'");

        // Add indexes for better performance
        $this->forge->addKey(['category'], false, false, 'idx_todos_category');
        $this->forge->addKey(['status', 'due_date'], false, false, 'idx_todos_status_due_date');
        $this->forge->addKey(['user_id', 'status'], false, false, 'idx_todos_user_status');
        $this->forge->addKey(['reminder_sent', 'due_date'], false, false, 'idx_todos_reminder_due');
    }

    public function down()
    {
        // Drop indexes
        $this->forge->dropKey('todos', 'idx_todos_category');
        $this->forge->dropKey('todos', 'idx_todos_status_due_date');
        $this->forge->dropKey('todos', 'idx_todos_user_status');
        $this->forge->dropKey('todos', 'idx_todos_reminder_due');

        // Drop columns
        $this->forge->dropColumn('todos', [
            'category', 'tags', 'estimated_hours', 'actual_hours', 
            'reminder_sent', 'completion_notes', 'assigned_to', 'project_id'
        ]);

        // Revert status enum
        $this->db->query("ALTER TABLE todos MODIFY COLUMN status ENUM('pending', 'completed') NOT NULL DEFAULT 'pending'");
        
        // Revert priority enum
        $this->db->query("ALTER TABLE todos MODIFY COLUMN priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium'");
    }
}
