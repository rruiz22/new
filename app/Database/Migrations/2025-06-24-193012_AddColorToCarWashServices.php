<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColorToCarWashServices extends Migration
{
    public function up()
    {
        $this->forge->addColumn('car_wash_services', [
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 7,
                'default' => '#007bff',
                'after' => 'category'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('car_wash_services', 'color');
    }
}
