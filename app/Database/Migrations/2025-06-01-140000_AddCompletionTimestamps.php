<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompletionTimestamps extends Migration
{
    public function up()
    {
        // Add completion timestamp fields to sales_orders
        $this->forge->addColumn('sales_orders', [
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Timestamp when order status was changed to completed'
            ],
            'cancelled_at' => [
                'type' => 'DATETIME', 
                'null' => true,
                'comment' => 'Timestamp when order status was changed to cancelled'
            ]
        ]);
    }

    public function down()
    {
        // Remove the completion timestamp fields
        $this->forge->dropColumn('sales_orders', ['completed_at', 'cancelled_at']);
    }
} 