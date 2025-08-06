<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSalesOrdersTables extends Migration
{
    public function up()
    {
        // Drop existing tables if they exist
        $this->forge->dropTable('sales_orders', true);
        $this->forge->dropTable('sales_order_items', true);
        $this->forge->dropTable('sales_order_services', true);

        // Create sales_orders table with the new structure
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
            'stock' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->createTable('sales_orders');

        // Create sales_orders_comments table
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
        $this->forge->createTable('sales_orders_comments');

        // Create sales_orders_services table
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
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('sales_orders_services');

        // Create sales_orders_services_history table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'changes' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'changed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'changed_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('sales_orders_services_history');

        // Create sales_orders_status_history table
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
            'from_status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'to_status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'changed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'changed_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'change_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'status_change',
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
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('sales_orders_status_history');
    }

    public function down()
    {
        // Drop the new tables
        $this->forge->dropTable('sales_orders_status_history', true);
        $this->forge->dropTable('sales_orders_services_history', true);
        $this->forge->dropTable('sales_orders_services', true);
        $this->forge->dropTable('sales_orders_comments', true);
        $this->forge->dropTable('sales_orders', true);
        
        // Re-create the original tables (optional)
        // If you want to revert back to the original tables, you would need to call the original migration here
    }
} 