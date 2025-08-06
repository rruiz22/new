<?php

namespace Modules\CarWash\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCarWashTables extends Migration
{
    public function up()
    {
        // Car Wash Orders table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'order_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'contact_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'vehicle_make' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'vehicle_model' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'vehicle_year' => [
                'type' => 'YEAR',
                'null' => true,
            ],
            'vehicle_color' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'license_plate' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'service_type' => [
                'type' => 'ENUM',
                'constraint' => ['basic', 'premium', 'deluxe', 'custom'],
                'default' => 'basic',
            ],
            'services' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'date' => [
                'type' => 'DATE',
            ],
            'time' => [
                'type' => 'TIME',
            ],
            'estimated_duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 60,
                'comment' => 'Duration in minutes',
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'],
                'default' => 'completed',
            ],
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'normal', 'high', 'urgent'],
                'default' => 'normal',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'internal_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'assigned_to' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'po_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'qr_code' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'short_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
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
        $this->forge->addKey('client_id');
        $this->forge->addKey('contact_id');
        $this->forge->addKey('assigned_to');
        $this->forge->addKey('created_by');
        $this->forge->addKey('status');
        $this->forge->addKey('date');
        $this->forge->createTable('car_wash_orders');

        // Car Wash Services table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 60,
                'comment' => 'Duration in minutes',
            ],
            'category' => [
                'type' => 'ENUM',
                'constraint' => ['exterior', 'interior', 'full_service', 'detailing', 'additional'],
                'default' => 'exterior',
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'NULL for global services',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'is_visible' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
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
        $this->forge->addKey('client_id');
        $this->forge->addKey('category');
        $this->forge->addKey('is_active');
        $this->forge->createTable('car_wash_services');

        // Car Wash Order Services (many-to-many)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addKey('service_id');
        $this->forge->createTable('car_wash_order_services');

        // Car Wash Activity table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'activity_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'field_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'old_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'new_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('activity_type');
        $this->forge->createTable('car_wash_activity');

        // Car Wash Comments table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'parent_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'For replies',
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'mentions' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'attachments' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'metadata' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'is_edited' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
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
        $this->forge->addKey('order_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('parent_id');
        $this->forge->createTable('car_wash_comments');

        // Note: Foreign key constraints can be added later if needed
    }

    public function down()
    {
        $this->forge->dropTable('car_wash_comments');
        $this->forge->dropTable('car_wash_activity');
        $this->forge->dropTable('car_wash_order_services');
        $this->forge->dropTable('car_wash_services');
        $this->forge->dropTable('car_wash_orders');
    }
} 