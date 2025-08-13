<?php
/**
 * webhook.php - Versión con limpieza agresiva de caché
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Headers CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Webhook-Signature');

// Manejar preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit('{"success":true,"message":"CORS preflight"}');
}

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('{"error":"Method not allowed - Use POST"}');
}

try {
    $timestamp = date('Y-m-d H:i:s');
    $logFile = __DIR__ . '/webhook_debug.log';
    
    // Log inicial
    file_put_contents($logFile, "[$timestamp] === WEBHOOK RECEIVED ===\n", FILE_APPEND | LOCK_EX);
    
    // Obtener datos del webhook
    $input = file_get_contents('php://input');
    file_put_contents($logFile, "[$timestamp] Input length: " . strlen($input) . "\n", FILE_APPEND | LOCK_EX);
    
    if (empty($input)) {
        throw new Exception('Empty payload');
    }
    
    // Parsear JSON
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON: ' . json_last_error_msg());
    }
    
    file_put_contents($logFile, "[$timestamp] Webhook type: " . ($data['type'] ?? 'unknown') . "\n", FILE_APPEND | LOCK_EX);
    file_put_contents($logFile, "[$timestamp] Data count: " . (isset($data['data']) ? count($data['data']) : 0) . "\n", FILE_APPEND | LOCK_EX);
    
    // *** LIMPIEZA AGRESIVA DE CACHÉ ***
    $cacheFilesDeleted = 0;
    $cacheLocations = [
        __DIR__ . '/cache/',
        __DIR__ . '/',
    ];
    
    $cachePatterns = [
        'inventory_data.json',
        'inventory_data.cache',
        'inventory_*.json',
        'inventory_*.cache',
        '*.cache',
        'cache_*'
    ];
    
    foreach ($cacheLocations as $location) {
        foreach ($cachePatterns as $pattern) {
            $files = glob($location . $pattern);
            foreach ($files as $file) {
                if (is_file($file)) {
                    $deleted = unlink($file);
                    if ($deleted) {
                        $cacheFilesDeleted++;
                        file_put_contents($logFile, "[$timestamp] DELETED: $file\n", FILE_APPEND | LOCK_EX);
                    }
                }
            }
        }
    }
    
    // También limpiar cualquier archivo que contenga "cache" o "inventory"
    $allFiles = array_merge(
        glob(__DIR__ . '/cache/*'),
        glob(__DIR__ . '/*cache*'),
        glob(__DIR__ . '/*inventory*')
    );
    
    foreach ($allFiles as $file) {
        if (is_file($file) && 
            (strpos(basename($file), 'cache') !== false || 
             strpos(basename($file), 'inventory') !== false) &&
            !strpos(basename($file), '.php') &&
            !strpos(basename($file), '.html')) {
            
            $deleted = unlink($file);
            if ($deleted) {
                $cacheFilesDeleted++;
                file_put_contents($logFile, "[$timestamp] EXTRA DELETED: $file\n", FILE_APPEND | LOCK_EX);
            }
        }
    }
    
    file_put_contents($logFile, "[$timestamp] Total cache files deleted: $cacheFilesDeleted\n", FILE_APPEND | LOCK_EX);
    
    // Respuesta exitosa
    $response = [
        'success' => true,
        'message' => 'Webhook received and cache cleared',
        'timestamp' => date('c'),
        'type' => $data['type'] ?? 'unknown',
        'data_count' => isset($data['data']) ? count($data['data']) : 0,
        'cache_files_deleted' => $cacheFilesDeleted,
        'cache_locations_checked' => count($cacheLocations),
        'cache_patterns_used' => count($cachePatterns)
    ];
    
    file_put_contents($logFile, "[$timestamp] Response: " . json_encode($response) . "\n", FILE_APPEND | LOCK_EX);
    file_put_contents($logFile, "[$timestamp] === WEBHOOK COMPLETED ===\n\n", FILE_APPEND | LOCK_EX);
    
    http_response_code(200);
    echo json_encode($response);
    
} catch (Exception $e) {
    // Log del error
    $errorLog = "[$timestamp] ERROR: " . $e->getMessage() . "\n";
    file_put_contents($logFile, $errorLog, FILE_APPEND | LOCK_EX);
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('c')
    ]);
}
?>