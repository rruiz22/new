<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReconVehiclesTable extends Migration
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
            'vin_number' => [
                'type' => 'VARCHAR',
                'constraint' => 17,
                'unique' => true,
            ],
            'vehicle_info' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'make' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'model' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'year' => [
                'type' => 'YEAR',
                'null' => true,
            ],
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'first_order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'last_order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'total_orders' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 0,
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
        $this->forge->addUniqueKey('vin_number');
        $this->forge->addKey('created_at');
        $this->forge->addKey('deleted_at');
        
        $this->forge->createTable('recon_vehicles');
    }

    public function down()
    {
        $this->forge->dropTable('recon_vehicles');
    }
} 