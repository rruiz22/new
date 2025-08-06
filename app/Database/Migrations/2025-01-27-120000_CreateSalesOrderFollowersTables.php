<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesOrderFollowersTables extends Migration
{
    public function up()
    {
        // Create sales_order_followers table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sales_order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'added_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'follower_type' => [
                'type' => 'ENUM',
                'constraint' => ['staff', 'client_contact'],
                'default' => 'client_contact',
            ],
            'notification_preferences' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'paused', 'removed'],
                'default' => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('sales_order_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->createTable('sales_order_followers');

        // Create sales_order_follower_notifications table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sales_order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'follower_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'notification_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'sent_via' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Array of channels used: email, sms, push',
            ],
            'read_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('sales_order_id');
        $this->forge->addKey('follower_id');
        $this->forge->addKey('notification_type');
        $this->forge->addKey('read_at');
        $this->forge->createTable('sales_order_follower_notifications');

        // Create sales_order_follower_activity table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sales_order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'follower_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'performed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'details' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('sales_order_id');
        $this->forge->addKey('follower_id');
        $this->forge->addKey('action');
        $this->forge->createTable('sales_order_follower_activity');
    }

    public function down()
    {
        // Drop tables in reverse order due to dependencies
        $this->forge->dropTable('sales_order_follower_activity', true);
        $this->forge->dropTable('sales_order_follower_notifications', true);
        $this->forge->dropTable('sales_order_followers', true);
    }
} 