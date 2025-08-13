<?php
/**
 * Configuración del Sistema de Inventario
 * Archivo de configuración centralizada
 */

return [
    // Configuración de Google Apps Script
    'google_apps_script' => [
        'url' => 'https://script.google.com/a/macros/limawebstudios.com/s/AKfycbzwJWKYnlz2oaJhixIu4nSaG7OJVOaHMTbpsKqqSmz1OcIe5_YfjgnRSq-LEsnJEsu-/exec',
        'timeout' => 30,
        'max_retries' => 3,
        'user_agent' => 'InventoryManager/3.0 (PHP/' . PHP_VERSION . ')'
    ],
    
    
    
    // Configuración de webhooks
    'webhooks' => [
        'enabled' => true,
        'secret_key' => 'Kx9mP2nQ7vR8sT4wY6uI0oP3aS5dF7gH9jK1lM', // CAMBIAR ESTO!
        'allowed_ips' => [
            '0.0.0.0', // IP de Google Apps Script (cualquiera por ahora)
            '127.0.0.1',
            '::1'
        ],
        'log_file' => __DIR__ . '/logs/webhooks.log',
        'max_payload_size' => 1048576 // 1MB
    ],
    
    // Configuración de WebSockets (opcional para futuro)
    'websockets' => [
        'enabled' => false,
        'port' => 8080,
        'host' => 'localhost'
    ],
    
    // Configuración de base de datos (opcional)
    'database' => [
        'enabled' => false,
        'type' => 'sqlite', // sqlite, mysql, postgresql
        'file' => __DIR__ . '/data/inventory.db',
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'inventory',
        'username' => '',
        'password' => ''
    ],
    
    // Configuración de logs
    'logging' => [
        'enabled' => true,
        'level' => 'INFO', // DEBUG, INFO, WARNING, ERROR
        'directory' => __DIR__ . '/logs/',
        'max_file_size' => 10485760, // 10MB
        'max_files' => 5
    ],
    
    // Configuración de seguridad
    'security' => [
        'api_key_required' => false,
        'rate_limiting' => [
            'enabled' => true,
            'requests_per_minute' => 60,
            'requests_per_hour' => 1000
        ],
        'cors' => [
            'allowed_origins' => ['*'],
            'allowed_methods' => ['GET', 'POST', 'OPTIONS'],
            'allowed_headers' => ['Content-Type', 'Authorization', 'X-API-Key']
        ]
    ],
    
    // Configuración de desarrollo
    'development' => [
        'debug_mode' => true, // Cambiar a false en producción
        'show_errors' => true,
        'log_queries' => true,
        'mock_data' => false
    ],
    
    // Configuración de notificaciones
    'notifications' => [
        'email' => [
            'enabled' => false,
            'smtp_host' => '',
            'smtp_port' => 587,
            'username' => '',
            'password' => '',
            'from_email' => 'noreply@tudominio.com',
            'admin_email' => 'admin@tudominio.com'
        ],
        'slack' => [
            'enabled' => false,
            'webhook_url' => '',
            'channel' => '#inventory'
        ]
    ],
    
    // Configuración de formato de datos
    'data_format' => [
        'date_format' => 'n/j', // Formato de fecha para mostrar
        'timezone' => 'America/New_York',
        'encoding' => 'UTF-8',
        'decimal_separator' => '.',
        'thousands_separator' => ','
    ],
    
    // Configuración de exportación
    'export' => [
        'max_records' => 10000,
        'allowed_formats' => ['csv', 'excel', 'json', 'pdf'],
        'temp_directory' => __DIR__ . '/temp/',
        'cleanup_temp_files' => true
    ],
    
    // URLs del sistema
    'urls' => [
        'base_url' => '',
        'api_endpoint' => '/api/',
        'webhook_endpoint' => '/webhook.php',
        'assets_url' => '/assets/'
    ],
    
    // Configuración de features
    'features' => [
        'real_time_updates' => true,
        'offline_mode' => true,
        'export_functionality' => true,
        'advanced_search' => true,
        'user_preferences' => true,
        'audit_log' => true
    ]
];
?>