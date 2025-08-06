<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInternalNotesTable extends Migration
{
    public function up()
    {
        // Internal Notes Table
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
            'author_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'content' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'mentions' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Array of mentioned user IDs',
            ],
            'attachments' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Array of attachment file paths',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        // Removed foreign key constraint for order_id to allow flexibility between sales_orders and service_orders
        $this->forge->addForeignKey('author_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('order_id');
        $this->forge->addKey('author_id');
        $this->forge->addKey('created_at');
        $this->forge->createTable('internal_notes');

        // Note Mentions Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'note_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'mentioned_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'read_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('note_id', 'internal_notes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('mentioned_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['note_id', 'mentioned_user_id'], 'unique_mention');
        $this->forge->createTable('note_mentions');
    }

    public function down()
    {
        $this->forge->dropTable('note_mentions', true);
        $this->forge->dropTable('internal_notes', true);
    }
}
