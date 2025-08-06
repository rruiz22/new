<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingsModel;

class DebugController extends BaseController
{
    /**
     * Test general del sistema
     */
    public function index()
    {
        $data = [
            'title' => 'System Debug Test',
            'tests' => []
        ];
        
        // Test 1: Información básica del servidor
        $data['tests']['server'] = [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'current_time' => date('Y-m-d H:i:s'),
            'base_url' => base_url(),
            'environment' => ENVIRONMENT
        ];
        
        // Test 2: Database connection
        try {
            $db = \Config\Database::connect();
            $data['tests']['database'] = [
                'status' => 'Connected',
                'database' => $db->getDatabase()
            ];
        } catch (\Exception $e) {
            $data['tests']['database'] = [
                'status' => 'Error',
                'error' => $e->getMessage()
            ];
        }
        
        // Test 3: Settings table
        try {
            $db = \Config\Database::connect();
            $query = $db->query("SHOW TABLES LIKE 'settings'");
            if ($query->getNumRows() > 0) {
                $count = $db->query("SELECT COUNT(*) as count FROM settings")->getRow()->count;
                $data['tests']['settings_table'] = [
                    'status' => 'Exists',
                    'count' => $count
                ];
            } else {
                $data['tests']['settings_table'] = [
                    'status' => 'Not found'
                ];
            }
        } catch (\Exception $e) {
            $data['tests']['settings_table'] = [
                'status' => 'Error',
                'error' => $e->getMessage()
            ];
        }
        
        // Test 4: SettingsModel
        try {
            $model = new SettingsModel();
            $settings = $model->getSettings();
            $data['tests']['settings_model'] = [
                'status' => 'Working',
                'settings_count' => count($settings),
                'sample_settings' => array_slice($settings, 0, 5, true)
            ];
        } catch (\Exception $e) {
            $data['tests']['settings_model'] = [
                'status' => 'Error',
                'error' => $e->getMessage()
            ];
        }
        
        return view('debug/index', $data);
    }
    
    /**
     * Test AJAX Settings Save
     */
    public function testAjaxSave()
    {
        try {
            // Configuramos la respuesta como JSON
            $this->response->setContentType('application/json');
            
            $model = new SettingsModel();
            $data = $this->request->getPost();
            
            // Verificar que se recibieron datos
            if (empty($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No data received'
                ]);
            }
            
            // Guardar configuraciones de prueba
            $allowed = ['app_name', 'app_description', 'app_email'];
            $saved = [];
            
            foreach ($allowed as $key) {
                if (isset($data[$key])) {
                    $model->setSetting($key, $data[$key]);
                    $saved[$key] = $data[$key];
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Test settings saved successfully',
                'saved' => $saved
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Test AJAX SMTP
     */
    public function testAjaxSmtp()
    {
        try {
            // Configuramos la respuesta como JSON
            $this->response->setContentType('application/json');
            
            $data = $this->request->getPost();
            
            // Verificar que se recibieron los datos SMTP
            $required = ['smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_from'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Missing required field: {$field}"
                    ]);
                }
            }
            
            // Simular prueba SMTP (sin conectar realmente)
            return $this->response->setJSON([
                'success' => true,
                'message' => 'SMTP test simulation successful',
                'config' => [
                    'host' => $data['smtp_host'],
                    'port' => $data['smtp_port'],
                    'user' => $data['smtp_user'],
                    'encryption' => $data['smtp_encryption'] ?? 'none'
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Create settings table if not exists
     */
    public function createSettingsTable()
    {
        try {
            $db = \Config\Database::connect();
            
            // Check if table exists
            $query = $db->query("SHOW TABLES LIKE 'settings'");
            if ($query->getNumRows() > 0) {
                return redirect()->to('debug')->with('message', 'Settings table already exists');
            }
            
            // Create table
            $createTableSQL = "
            CREATE TABLE `settings` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `key` varchar(255) NOT NULL,
                `value` text,
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `key` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";
            
            $db->query($createTableSQL);
            
            // Insert default settings
            $defaultSettings = [
                ['key' => 'app_name', 'value' => 'My Application'],
                ['key' => 'app_description', 'value' => 'Application Description'],
                ['key' => 'app_email', 'value' => 'admin@example.com'],
                ['key' => 'app_lang', 'value' => 'en'],
                ['key' => 'smtp_host', 'value' => ''],
                ['key' => 'smtp_port', 'value' => '587'],
                ['key' => 'smtp_user', 'value' => ''],
                ['key' => 'smtp_pass', 'value' => ''],
                ['key' => 'smtp_encryption', 'value' => 'tls'],
                ['key' => 'smtp_from', 'value' => ''],
            ];
            
            foreach ($defaultSettings as $setting) {
                $db->query("INSERT INTO settings (`key`, `value`) VALUES (?, ?)", [$setting['key'], $setting['value']]);
            }
            
            return redirect()->to('debug')->with('message', 'Settings table created successfully with default values');
            
        } catch (\Exception $e) {
            return redirect()->to('debug')->with('error', 'Error creating table: ' . $e->getMessage());
        }
    }
} 