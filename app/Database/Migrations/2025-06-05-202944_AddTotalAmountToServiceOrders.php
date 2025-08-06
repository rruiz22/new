<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTotalAmountToServiceOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('service_orders', [
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'status'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('service_orders', 'total_amount');
    }
}
