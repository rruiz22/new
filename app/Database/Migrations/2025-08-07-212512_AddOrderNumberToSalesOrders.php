<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOrderNumberToSalesOrders extends Migration
{
    public function up()
    {
        // Add order_number field to sales_orders table
        $fields = [
            'order_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
                'after' => 'id'
            ]
        ];
        
        $this->forge->addColumn('sales_orders', $fields);
        
        // Create unique index for order_number
        $this->forge->addUniqueKey('order_number', 'idx_sales_orders_order_number');
        
        // Update existing records with generated order numbers
        $db = \Config\Database::connect();
        $orders = $db->table('sales_orders')->where('order_number IS NULL OR order_number = ""')->get()->getResultArray();
        
        foreach ($orders as $order) {
            $prefix = 'SAL-';
            $timestamp = date('ymdHis', strtotime($order['created_at']));
            $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $orderNumber = $prefix . $timestamp . $random;
            
            // Ensure uniqueness
            $attempts = 0;
            while ($db->table('sales_orders')->where('order_number', $orderNumber)->countAllResults() > 0 && $attempts < 100) {
                $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                $orderNumber = $prefix . $timestamp . $random;
                $attempts++;
            }
            
            $db->table('sales_orders')->where('id', $order['id'])->update(['order_number' => $orderNumber]);
        }
    }

    public function down()
    {
        // Remove the order_number field
        $this->forge->dropColumn('sales_orders', 'order_number');
    }
}
