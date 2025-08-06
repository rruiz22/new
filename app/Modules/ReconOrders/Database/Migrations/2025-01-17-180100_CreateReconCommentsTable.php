<?php

namespace Modules\ReconOrders\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReconCommentsTable extends Migration
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
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'mentions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'attachments' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'metadata' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_edited' => [
                'type'    => 'BOOLEAN',
                'default' => false,
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
        $this->forge->addKey('created_at');
        $this->forge->addKey('deleted_at');
        
        $this->forge->createTable('recon_comments');
    }

    public function down()
    {
        $this->forge->dropTable('recon_comments');
    }
} 