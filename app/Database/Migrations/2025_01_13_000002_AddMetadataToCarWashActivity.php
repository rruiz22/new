<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMetadataToCarWashActivity extends Migration
{
    public function up()
    {
        // Add metadata column to car_wash_activity table
        $this->forge->addColumn('car_wash_activity', [
            'metadata' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'new_value'
            ]
        ]);
        
        echo "Added metadata field to car_wash_activity table.\n";
    }

    public function down()
    {
        // Remove metadata column from car_wash_activity table
        $this->forge->dropColumn('car_wash_activity', 'metadata');
        
        echo "Removed metadata field from car_wash_activity table.\n";
    }
} 