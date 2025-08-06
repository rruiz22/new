<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddContactIdToServiceOrders extends Migration
{
    public function up()
    {
        // Add contact_id column to service_orders table
        $this->forge->addColumn('service_orders', [
            'contact_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'client_id'
            ]
        ]);
        
        // Add index for contact_id
        $this->forge->addKey('contact_id', false, false, 'idx_service_orders_contact_id');
        $this->forge->processIndexes('service_orders');
    }

    public function down()
    {
        // Remove the contact_id column
        $this->forge->dropColumn('service_orders', 'contact_id');
    }
} 