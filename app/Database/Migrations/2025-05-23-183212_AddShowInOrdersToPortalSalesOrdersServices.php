<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShowInOrdersToPortalSalesOrdersServices extends Migration
{
    public function up()
    {
        // Agregar el campo show_in_orders a la tabla sales_orders_services
        $this->forge->addColumn('sales_orders_services', [
            'show_in_orders' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'service_status'
            ]
        ]);
    }

    public function down()
    {
        // Eliminar el campo show_in_orders
        $this->forge->dropColumn('sales_orders_services', 'show_in_orders');
    }
}
