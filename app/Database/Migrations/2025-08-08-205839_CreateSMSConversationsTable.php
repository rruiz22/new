<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSMSConversationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'twilio_sid' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'direction' => [
                'type' => 'ENUM',
                'constraint' => ['inbound', 'outbound'],
                'default' => 'outbound',
            ],
            'from_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'to_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'sent',
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'sent_by_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'delivered_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'read_at' => [
                'type' => 'DATETIME',
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('twilio_sid');
        $this->forge->addKey('from_number');
        $this->forge->addKey('to_number');
        $this->forge->addKey('sent_by_user_id');
        $this->forge->addKey(['from_number', 'to_number']);
        $this->forge->addKey('created_at');
        
        $this->forge->createTable('sms_conversations');
    }

    public function down()
    {
        $this->forge->dropTable('sms_conversations');
    }
}
