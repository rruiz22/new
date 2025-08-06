<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationToAuditTrail extends Migration
{
    public function up()
    {
        $fields = [
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'user_agent'
            ],
            'country_code' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'null' => true,
                'after' => 'country'
            ],
            'region' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'country_code'
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'region'
            ],
            'latitude' => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => true,
                'after' => 'city'
            ],
            'longitude' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => true,
                'after' => 'latitude'
            ],
            'timezone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'longitude'
            ]
        ];

        $this->forge->addColumn('audit_trail', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('audit_trail', ['country', 'country_code', 'region', 'city', 'latitude', 'longitude', 'timezone']);
    }
} 