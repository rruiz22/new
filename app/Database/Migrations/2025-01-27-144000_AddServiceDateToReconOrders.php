<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddServiceDateToReconOrders extends Migration
{
    public function up()
    {
        // Add service_date column to recon_orders table
        $this->forge->addColumn('recon_orders', [
            'service_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Scheduled service date',
                'after' => 'service_id'
            ]
        ]);
    }

    public function down()
    {
        // Remove the service_date column
        $this->forge->dropColumn('recon_orders', 'service_date');
    }
} 