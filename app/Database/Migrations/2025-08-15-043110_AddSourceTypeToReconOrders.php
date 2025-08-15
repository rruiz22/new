<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSourceTypeToReconOrders extends Migration
{
    public function up()
    {
        // Add source_type field to recon_orders table if it exists and field doesn't exist
        if ($this->db->tableExists('recon_orders')) {
            if (!$this->db->fieldExists('source_type', 'recon_orders')) {
                $fields = [
                    'source_type' => [
                        'type' => 'VARCHAR',
                        'constraint' => 20,
                        'default' => 'manual',
                        'null' => false,
                        'comment' => 'Source type: manual, inventory, api, etc.'
                    ]
                ];
                
                $this->forge->addColumn('recon_orders', $fields);
                echo "Added source_type field to recon_orders table.\n";
            } else {
                echo "source_type field already exists in recon_orders table.\n";
            }
        } else {
            echo "recon_orders table does not exist. Skipping migration.\n";
        }
    }

    public function down()
    {
        // Remove the source_type column if it exists
        if ($this->db->tableExists('recon_orders')) {
            if ($this->db->fieldExists('source_type', 'recon_orders')) {
                $this->forge->dropColumn('recon_orders', 'source_type');
                echo "Removed source_type field from recon_orders table.\n";
            }
        }
    }
}
