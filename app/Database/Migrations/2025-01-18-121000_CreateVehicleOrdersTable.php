<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVehicleOrdersTable extends Migration
{
    public function up()
    {
        // Only create if table doesn't exist
        if (!$this->db->tableExists('vehicle_orders')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'vehicle_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'comment' => 'Reference to recon_vehicles.id',
                ],
                'vin_number' => [
                    'type' => 'VARCHAR',
                    'constraint' => 17,
                    'null' => false,
                ],
                'order_type' => [
                    'type' => 'ENUM',
                    'constraint' => ['recon', 'sales', 'service', 'carwash'],
                    'null' => false,
                ],
                'order_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => false,
                ],
                'order_number' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                ],
                
                // Client information
                'client_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'client_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
                
                // Order specific information
                'stock' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'comment' => 'Stock number from recon orders',
                ],
                'service_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
                'service_date' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'service_color' => [
                    'type' => 'VARCHAR',
                    'constraint' => 7,
                    'null' => true,
                    'comment' => 'Hex color for service type',
                ],
                'order_status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                ],
                'order_date' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                
                // Metadata
                'from_inventory' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'comment' => '1 if created from inventory',
                ],
                'source_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'default' => 'manual',
                    'comment' => 'manual, inventory, etc.',
                ],
                'is_primary' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'comment' => 'Primary order for this vehicle',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('vehicle_id', false, false, 'idx_vehicle_orders_vehicle');
            $this->forge->addKey('vin_number', false, false, 'idx_vehicle_orders_vin');
            $this->forge->addKey(['order_type', 'order_id'], false, false, 'idx_vehicle_orders_type');
            $this->forge->addKey('client_id', false, false, 'idx_vehicle_orders_client');
            $this->forge->addKey('stock', false, false, 'idx_vehicle_orders_stock');
            $this->forge->addKey('order_status', false, false, 'idx_vehicle_orders_status');
            $this->forge->addKey('order_date', false, false, 'idx_vehicle_orders_date');
            $this->forge->addUniqueKey(['vehicle_id', 'order_type', 'order_id'], 'unique_vehicle_order');
            
            $this->forge->createTable('vehicle_orders');
        }
    }

    public function down()
    {
        if ($this->db->tableExists('vehicle_orders')) {
            $this->forge->dropTable('vehicle_orders');
        }
    }
}
