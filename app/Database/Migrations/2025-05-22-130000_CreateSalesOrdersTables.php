<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesOrdersTables extends Migration
{
    public function up()
    {
        // Tabla de órdenes de venta
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
            'order_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'delivery_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'payment_status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'unpaid',
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'created_by' => [
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
        $this->forge->addKey('status');
        $this->forge->addKey('payment_status');
        $this->forge->addKey(['order_date', 'delivery_date']);
        $this->forge->createTable('sales_orders');

        // Tabla de detalles de órdenes
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
                'null' => true,
            ],
            'product_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
            'discount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'total' => [
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
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addKey('service_id');
        $this->forge->createTable('sales_order_items');

        // Tabla de servicios
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
                'null' => true,
            ],
            'service_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_by' => [
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
        $this->forge->addKey('is_active');
        $this->forge->createTable('sales_order_services');
    }

    public function down()
    {
        $this->forge->dropTable('sales_order_services', true);
        $this->forge->dropTable('sales_order_items', true);
        $this->forge->dropTable('sales_orders', true);
    }
} 