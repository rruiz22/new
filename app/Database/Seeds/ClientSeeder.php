<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $clients = [
            [
                'name' => 'BMW of Sudbury',
                'email' => 'info@bmwsudbury.com',
                'phone' => '+1 (978) 555-0100',
                'address' => '123 Boston Post Road, Sudbury, MA 01776',
                'website' => 'https://www.bmwsudbury.com',
                'tax_number' => 'TX-BMW-001',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Toyota of Boston',
                'email' => 'service@toyotaboston.com',
                'phone' => '+1 (617) 555-0200',
                'address' => '456 Commonwealth Avenue, Boston, MA 02215',
                'website' => 'https://www.toyotaboston.com',
                'tax_number' => 'TX-TOY-002',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Mercedes-Benz of Cambridge',
                'email' => 'contact@mercedesboston.com',
                'phone' => '+1 (617) 555-0300',
                'address' => '789 Memorial Drive, Cambridge, MA 02139',
                'website' => 'https://www.mercedesboston.com',
                'tax_number' => 'TX-MER-003',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Audi Boston',
                'email' => 'info@audiboston.com',
                'phone' => '+1 (617) 555-0400',
                'address' => '321 Newbury Street, Boston, MA 02116',
                'website' => 'https://www.audiboston.com',
                'tax_number' => 'TX-AUD-004',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Lexus of Newton',
                'email' => 'sales@lexusnewton.com',
                'phone' => '+1 (617) 555-0500',
                'address' => '654 Washington Street, Newton, MA 02458',
                'website' => 'https://www.lexusnewton.com',
                'tax_number' => 'TX-LEX-005',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insertar los clientes
        $builder = $this->db->table('clients');
        
        foreach ($clients as $client) {
            $builder->insert($client);
        }
    }
} 