<?php

namespace Modules\ReconOrders\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReconActivityTable extends Migration
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
            'activity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'field_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'old_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'new_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'metadata' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('activity_type');
        $this->forge->addKey('created_at');
        
        $this->forge->createTable('recon_activity');
    }

    public function down()
    {
        $this->forge->dropTable('recon_activity');
    }
} 