<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            // Servicios para BMW of Sudbury (client_id: 4)
            [
                'client_id' => 4,
                'service_name' => 'BMW Ceramic Coating',
                'service_description' => 'Premium ceramic coating for BMW vehicles',
                'service_price' => 1200.00,
                'notes' => 'Includes 5-year warranty',
                'service_status' => 'active',
                'show_in_orders' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'client_id' => 4,
                'service_name' => 'BMW Paint Protection Film',
                'service_description' => 'Full front PPF installation for BMW',
                'service_price' => 2500.00,
                'notes' => 'Covers hood, bumper, and fenders',
                'service_status' => 'active',
                'show_in_orders' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Servicios para Toyota of Boston (client_id: 5)
            [
                'client_id' => 5,
                'service_name' => 'Toyota Interior Protection',
                'service_description' => 'Complete interior detail and protection',
                'service_price' => 450.00,
                'notes' => 'Includes fabric protection',
                'service_status' => 'active',
                'show_in_orders' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'client_id' => 5,
                'service_name' => 'Toyota Hybrid Special',
                'service_description' => 'Special coating for hybrid vehicles',
                'service_price' => 800.00,
                'notes' => 'Eco-friendly products only',
                'service_status' => 'active',
                'show_in_orders' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Servicios para Audi Boston (client_id: 7)
            [
                'client_id' => 7,
                'service_name' => 'Audi Premium Detail',
                'service_description' => 'Luxury detailing service for Audi vehicles',
                'service_price' => 650.00,
                'notes' => 'Premium products only',
                'service_status' => 'active',
                'show_in_orders' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'client_id' => 7,
                'service_name' => 'Audi Paint Correction',
                'service_description' => 'Multi-stage paint correction service',
                'service_price' => 1800.00,
                'notes' => 'Removes swirl marks and scratches',
                'service_status' => 'active',
                'show_in_orders' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Servicios para Mercedes-Benz of Cambridge (client_id: 6)
            [
                'client_id' => 6,
                'service_name' => 'Mercedes Paint Protection',
                'service_description' => 'Premium paint protection for Mercedes vehicles',
                'service_price' => 1500.00,
                'notes' => 'German engineering standards',
                'service_status' => 'active',
                'show_in_orders' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Servicio general (sin cliente específico)
            [
                'client_id' => null,
                'service_name' => 'General Wash & Wax',
                'service_description' => 'Basic wash and wax service',
                'service_price' => 150.00,
                'notes' => 'Available for all clients',
                'service_status' => 'active',
                'show_in_orders' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insertar los servicios
        $builder = $this->db->table('portal_sales_orders_services');
        
        foreach ($services as $service) {
            $builder->insert($service);
        }
    }
} 