<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVehicleShortlinksTable extends Migration
{
    public function up()
    {
        // Create table (will skip if exists)
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
                    'null' => false,
                ],
                'vehicle_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'comment' => 'Reference to recon_vehicles.id',
                ],
                'short_url' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => false,
                ],
                'short_url_slug' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'comment' => 'VIN last 6 digits',
                ],
                'mda_link_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'comment' => 'MDA Links API ID',
                ],
                'target_url' => [
                    'type' => 'VARCHAR',
                    'constraint' => 500,
                    'null' => false,
                    'comment' => 'Original vehicle URL',
                ],
                'qr_url' => [
                    'type' => 'VARCHAR',
                    'constraint' => 500,
                    'null' => true,
                    'comment' => 'QR code URL',
                ],
                'qr_image' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'comment' => 'Base64 QR image data',
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
            $this->forge->addUniqueKey('vin_number', 'unique_vin_shortlink');
            $this->forge->addKey('short_url_slug', false, false, 'idx_shortlinks_slug');
            $this->forge->addKey('vehicle_id', false, false, 'idx_shortlinks_vehicle');
            $this->forge->addKey('is_active', false, false, 'idx_shortlinks_active');
            
            $this->forge->createTable('vehicle_shortlinks', true);
    }

    public function down()
    {
        $this->forge->dropTable('vehicle_shortlinks', true);
    }
}
