<?php

namespace Modules\AuditTrail\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditTrailTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'record_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'old_values' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'new_values' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'session_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => true,
            ],
            'request_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'created_at']);
        $this->forge->addKey(['module', 'action']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('audit_trail');

        // Insert sample audit data
        $this->insertSampleData();
    }

    public function down()
    {
        $this->forge->dropTable('audit_trail');
    }

    private function insertSampleData()
    {
        $db = \Config\Database::connect();
        
        // Get real users from the database
        $users = $db->table('users')
                   ->select('id, first_name, last_name')
                   ->where('deleted_at', null)
                   ->limit(5)
                   ->get()
                   ->getResultArray();

        if (empty($users)) {
            return; // No users to create sample data
        }

        $sampleData = [];
        $actions = ['CREATE', 'UPDATE', 'DELETE', 'LOGIN', 'LOGOUT'];
        $modules = ['Sales Orders', 'Service Orders', 'Clients', 'Contacts', 'Users', 'Settings'];
        $ips = ['192.168.1.100', '192.168.1.101', '192.168.1.102', '10.0.0.1', '172.16.0.1'];
        
        for ($i = 0; $i < 50; $i++) {
            $user = $users[array_rand($users)];
            $action = $actions[array_rand($actions)];
            $module = $modules[array_rand($modules)];
            $ip = $ips[array_rand($ips)];
            
            $sampleData[] = [
                'user_id' => $user['id'],
                'action' => $action,
                'module' => $module,
                'record_id' => $this->generateRecordId($module, $i),
                'description' => $this->generateDescription($action, $module, $user['first_name']),
                'old_values' => $action === 'UPDATE' ? json_encode(['status' => 'pending', 'amount' => '100.00']) : null,
                'new_values' => in_array($action, ['CREATE', 'UPDATE']) ? json_encode(['status' => 'completed', 'amount' => '150.00']) : null,
                'ip_address' => $ip,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'session_id' => 'sess_' . bin2hex(random_bytes(16)),
                'request_id' => 'req_' . bin2hex(random_bytes(8)),
                'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days +' . rand(0, 23) . ' hours +' . rand(0, 59) . ' minutes')),
            ];
        }

        $db->table('audit_trail')->insertBatch($sampleData);
    }

    private function generateRecordId($module, $index)
    {
        $prefixes = [
            'Sales Orders' => 'SO',
            'Service Orders' => 'SV',
            'Clients' => 'CL',
            'Contacts' => 'CT',
            'Users' => 'US',
            'Settings' => 'SET'
        ];
        
        $prefix = $prefixes[$module] ?? 'REC';
        return $prefix . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
    }

    private function generateDescription($action, $module, $userName)
    {
        $descriptions = [
            'CREATE' => [
                'Sales Orders' => "Created new sales order for client ABC Company",
                'Service Orders' => "Created service order for vehicle maintenance",
                'Clients' => "Added new client to the system",
                'Contacts' => "Created new contact for existing client",
                'Users' => "Created new user account",
                'Settings' => "Updated system configuration"
            ],
            'UPDATE' => [
                'Sales Orders' => "Updated sales order status and amount",
                'Service Orders' => "Modified service order details",
                'Clients' => "Updated client information",
                'Contacts' => "Modified contact details",
                'Users' => "Updated user profile information",
                'Settings' => "Changed application settings"
            ],
            'DELETE' => [
                'Sales Orders' => "Deleted sales order",
                'Service Orders' => "Removed service order",
                'Clients' => "Deleted client record",
                'Contacts' => "Removed contact from system",
                'Users' => "Deactivated user account",
                'Settings' => "Reset configuration to default"
            ],
            'LOGIN' => [
                'Sales Orders' => "User logged into the system",
                'Service Orders' => "User logged into the system",
                'Clients' => "User logged into the system",
                'Contacts' => "User logged into the system",
                'Users' => "User logged into the system",
                'Settings' => "User logged into the system"
            ],
            'LOGOUT' => [
                'Sales Orders' => "User logged out of the system",
                'Service Orders' => "User logged out of the system",
                'Clients' => "User logged out of the system",
                'Contacts' => "User logged out of the system",
                'Users' => "User logged out of the system",
                'Settings' => "User logged out of the system"
            ]
        ];

        return $descriptions[$action][$module] ?? "Performed $action on $module";
    }
} 