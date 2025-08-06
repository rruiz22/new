<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateCarWashStatusDefault extends Migration
{
    public function up()
    {
        // Update the default value for status column in car_wash_orders table
        $this->db->query("ALTER TABLE car_wash_orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'completed'");
        
        // Optionally, update existing records that have 'pending' status to 'completed'
        // Uncomment the line below if you want to update existing pending orders to completed
        // $this->db->query("UPDATE car_wash_orders SET status = 'completed' WHERE status = 'pending'");
    }

    public function down()
    {
        // Revert the default value back to 'pending'
        $this->db->query("ALTER TABLE car_wash_orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
} 