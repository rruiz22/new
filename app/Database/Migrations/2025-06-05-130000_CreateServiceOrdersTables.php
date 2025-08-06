<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServiceOrdersTables extends Migration
{
    public function up()
    {
        // Create service_orders table with the new structure
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'salesperson_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'ro_number' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'po_number' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'tag_number' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'vin' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'vehicle' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'time' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'pending',
            ],
            'instructions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('service_orders');

        // Create service_orders_comments table
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
            'description' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('service_orders_comments');

        // Create service_orders_services table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'service_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'service_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'service_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'service_status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'deactivated'],
                'default' => 'active',
            ],
            'show_in_orders' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('service_orders_services');

        // Create service_orders_services_history table
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
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'status_changed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'status_changed_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('service_orders_services_history');

        // Create service_orders_activity table
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
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addKey('user_id');
        $this->forge->createTable('service_orders_activity');
    }

    public function down()
    {
        // Drop tables in reverse order due to dependencies
        $this->forge->dropTable('service_orders_activity', true);
        $this->forge->dropTable('service_orders_services_history', true);
        $this->forge->dropTable('service_orders_services', true);
        $this->forge->dropTable('service_orders_comments', true);
        $this->forge->dropTable('service_orders', true);
    }
} 