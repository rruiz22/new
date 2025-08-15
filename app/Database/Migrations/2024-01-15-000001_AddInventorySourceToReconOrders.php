<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInventorySourceToReconOrders extends Migration
{
    public function up()
    {
        // Add inventory source fields to recon_orders table if it exists
        if ($this->db->tableExists('recon_orders')) {
            $fields = [
                'from_inventory' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'null' => false,
                    'comment' => 'Flag to indicate if order was created from inventory system'
                ],
                'source_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'default' => 'manual',
                    'null' => false,
                    'comment' => 'Source type: manual, inventory, api, etc.'
                ],
                'inventory_data' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'comment' => 'JSON data from original inventory record'
                ]
            ];
            
            $this->forge->addColumn('recon_orders', $fields);
            
            // Add index for better performance
            $this->forge->addKey('from_inventory');
            
            echo "Added inventory source fields to recon_orders table.\n";
        } else {
            echo "recon_orders table does not exist. Skipping migration.\n";
        }
    }

    public function down()
    {
        // Remove the added columns if the table exists
        if ($this->db->tableExists('recon_orders')) {
            if ($this->db->fieldExists('from_inventory', 'recon_orders')) {
                $this->forge->dropColumn('recon_orders', 'from_inventory');
            }
            
            if ($this->db->fieldExists('source_type', 'recon_orders')) {
                $this->forge->dropColumn('recon_orders', 'source_type');
            }
            
            if ($this->db->fieldExists('inventory_data', 'recon_orders')) {
                $this->forge->dropColumn('recon_orders', 'inventory_data');
            }
            
            echo "Removed inventory source fields from recon_orders table.\n";
        }
    }
}
