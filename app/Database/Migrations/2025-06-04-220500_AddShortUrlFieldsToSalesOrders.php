<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShortUrlFieldsToSalesOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('sales_orders', [
            'short_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'total_amount'
            ],
            'short_url_slug' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'after' => 'short_url'
            ],
            'lima_link_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'short_url_slug'
            ],
            'qr_generated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'lima_link_id'
            ]
        ]);
        
        // Add index for short URL slug for quick lookups
        $this->forge->addKey('short_url_slug');
    }

    public function down()
    {
        $this->forge->dropColumn('sales_orders', [
            'short_url',
            'short_url_slug', 
            'lima_link_id',
            'qr_generated_at'
        ]);
    }
} 