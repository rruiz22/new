<?php

namespace Modules\CarWash\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CarWashSeeder extends Seeder
{
    public function run()
    {
        // Sample Car Wash Services
        $services = [
            [
                'name' => 'Basic Exterior Wash',
                'description' => 'Basic exterior wash with soap and rinse',
                'price' => 15.00,
                'duration' => 30,
                'category' => 'exterior',
                'client_id' => null, // Global service
                'is_active' => 1,
                'is_visible' => 1,
                'sort_order' => 1,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Premium Exterior Wash',
                'description' => 'Premium exterior wash with wax and tire shine',
                'price' => 25.00,
                'duration' => 45,
                'category' => 'exterior',
                'client_id' => null,
                'is_active' => 1,
                'is_visible' => 1,
                'sort_order' => 2,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Interior Vacuum & Clean',
                'description' => 'Complete interior vacuum and surface cleaning',
                'price' => 20.00,
                'duration' => 40,
                'category' => 'interior',
                'client_id' => null,
                'is_active' => 1,
                'is_visible' => 1,
                'sort_order' => 3,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Full Service Wash',
                'description' => 'Complete exterior and interior cleaning service',
                'price' => 40.00,
                'duration' => 75,
                'category' => 'full_service',
                'client_id' => null,
                'is_active' => 1,
                'is_visible' => 1,
                'sort_order' => 4,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Paint Protection',
                'description' => 'Ceramic coating and paint protection service',
                'price' => 150.00,
                'duration' => 180,
                'category' => 'detailing',
                'client_id' => null,
                'is_active' => 1,
                'is_visible' => 1,
                'sort_order' => 5,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Engine Bay Cleaning',
                'description' => 'Professional engine bay cleaning and degreasing',
                'price' => 35.00,
                'duration' => 60,
                'category' => 'additional',
                'client_id' => null,
                'is_active' => 1,
                'is_visible' => 1,
                'sort_order' => 6,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('car_wash_services')->insertBatch($services);

        // Sample Car Wash Orders (only if there are clients)
        $clientModel = new \App\Models\ClientModel();
        $clients = $clientModel->limit(3)->findAll();

        if (!empty($clients)) {
            $orders = [];
            $statuses = ['pending', 'confirmed', 'in_progress', 'completed'];
            $priorities = ['low', 'normal', 'high', 'urgent'];
            $serviceTypes = ['basic', 'premium', 'deluxe', 'custom'];

            $makes = ['Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes', 'Audi', 'Volkswagen', 'Nissan'];
            $models = ['Camry', 'Civic', 'F-150', 'X3', 'C-Class', 'A4', 'Jetta', 'Altima'];
            $colors = ['White', 'Black', 'Silver', 'Red', 'Blue', 'Gray', 'Green'];

            for ($i = 0; $i < 15; $i++) {
                $date = date('Y-m-d', strtotime('+' . rand(-7, 14) . ' days'));
                $hour = rand(8, 16);
                $time = sprintf('%02d:00', $hour);

                $orders[] = [
                    'order_number' => 'CW' . date('Ym') . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'client_id' => $clients[array_rand($clients)]['id'],
                    'vehicle_make' => $makes[array_rand($makes)],
                    'vehicle_model' => $models[array_rand($models)],
                    'vehicle_year' => rand(2015, 2024),
                    'vehicle_color' => $colors[array_rand($colors)],
                    'license_plate' => strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3)) . rand(100, 999),
                    'service_type' => $serviceTypes[array_rand($serviceTypes)],
                    'date' => $date,
                    'time' => $time,
                    'estimated_duration' => rand(30, 120),
                    'price' => rand(15, 150),
                    'status' => $statuses[array_rand($statuses)],
                    'priority' => $priorities[array_rand($priorities)],
                    'notes' => 'Sample order #' . ($i + 1),
                    'created_by' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            $this->db->table('car_wash_orders')->insertBatch($orders);

            // Add some sample activities
            $orderIds = $this->db->table('car_wash_orders')->select('id')->get()->getResultArray();
            $activities = [];

            foreach ($orderIds as $order) {
                $activities[] = [
                    'order_id' => $order['id'],
                    'user_id' => 1,
                    'activity_type' => 'order_created',
                    'description' => 'Car wash order created',
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            $this->db->table('car_wash_activity')->insertBatch($activities);
        }

        echo "Car Wash seeder completed successfully.\n";
    }
} 