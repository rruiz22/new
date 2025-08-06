<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIntegrationSettings extends Migration
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
            'service_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'configuration_key' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'configuration_value' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'is_encrypted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'last_tested_at' => [
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

        $this->forge->addKey('id', true);
        $this->forge->addKey(['service_name'], false, false, 'idx_service_name');
        $this->forge->addKey(['service_name', 'configuration_key'], false, false, 'idx_service_key');
        $this->forge->createTable('integration_settings');
    }

    public function down()
    {
        $this->forge->dropTable('integration_settings');
    }
}
