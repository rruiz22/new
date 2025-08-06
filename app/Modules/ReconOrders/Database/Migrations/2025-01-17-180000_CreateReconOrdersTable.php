<?php

namespace Modules\ReconOrders\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReconOrdersTable extends Migration
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
            'order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'client_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'stock' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'vin_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 17,
                'null'       => true,
            ],
            'vehicle' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'pictures' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['normal', 'urgent'],
                'default'    => 'normal',
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
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'deleted_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addKey('stock');
        $this->forge->addKey('vin_number');
        $this->forge->addKey('status');
        $this->forge->addKey('created_at');
        $this->forge->addKey('deleted_at');
        
        $this->forge->createTable('recon_orders');
    }

    public function down()
    {
        $this->forge->dropTable('recon_orders');
    }
} 