<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCarWashNotesTable extends Migration
{
    public function up()
    {
        // Create car_wash_notes table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'car_wash_order_id' => [
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
            ],
            'attachments' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'parent_note_id' => [
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('car_wash_order_id');
        $this->forge->addKey('author_id');
        $this->forge->addKey('parent_note_id');
        $this->forge->addKey('created_at');
        $this->forge->addKey('deleted_at');

        $this->forge->createTable('car_wash_notes');

        // Create car_wash_note_mentions table
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
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('note_id');
        $this->forge->addKey('mentioned_user_id');
        $this->forge->addKey('read_at');

        $this->forge->createTable('car_wash_note_mentions');

        // Add foreign key constraints
        $this->forge->addForeignKey('car_wash_order_id', 'car_wash_orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('author_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('parent_note_id', 'car_wash_notes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->processIndexes('car_wash_notes');

        $this->forge->addForeignKey('note_id', 'car_wash_notes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('mentioned_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->processIndexes('car_wash_note_mentions');
    }

    public function down()
    {
        $this->forge->dropTable('car_wash_note_mentions');
        $this->forge->dropTable('car_wash_notes');
    }
} 