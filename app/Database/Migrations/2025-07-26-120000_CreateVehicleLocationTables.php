<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVehicleLocationTables extends Migration
{
    public function up()
    {
        // Table for NFC tokens
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'vehicle_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'vin_number' => [
                'type' => 'VARCHAR',
                'constraint' => 17,
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'unique' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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
        $this->forge->addKey('vehicle_id');
        $this->forge->addKey('token');
        $this->forge->createTable('vehicle_location_tokens');

        // Table for parking spots
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,  
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'spot_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'zone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'latitude' => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => true,
            ],
            'longitude' => [
                'type' => 'DECIMAL', 
                'constraint' => '11,8',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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
        $this->forge->addKey('spot_number');
        $this->forge->createTable('parking_spots');

        // Table for vehicle location history
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true, 
                'auto_increment' => true,
            ],
            'vehicle_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'vin_number' => [
                'type' => 'VARCHAR',
                'constraint' => 17,
            ],
            'spot_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'spot_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'latitude' => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => true,
            ],
            'longitude' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8', 
                'null' => true,
            ],
            'accuracy' => [
                'type' => 'FLOAT',
                'null' => true,
            ],
            'device_info' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'user_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'token_used' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('vehicle_id');
        $this->forge->addKey('vin_number');
        $this->forge->addKey('created_at');
        $this->forge->createTable('vehicle_locations');
    }

    public function down()
    {
        $this->forge->dropTable('vehicle_locations');
        $this->forge->dropTable('parking_spots');
        $this->forge->dropTable('vehicle_location_tokens');
    }
} 