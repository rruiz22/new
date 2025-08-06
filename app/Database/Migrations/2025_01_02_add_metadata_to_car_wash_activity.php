<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMetadataToCarWashActivity extends Migration
{
    public function up()
    {
        $this->forge->addColumn('car_wash_activity', [
            'metadata' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'new_value'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('car_wash_activity', 'metadata');
    }
} 