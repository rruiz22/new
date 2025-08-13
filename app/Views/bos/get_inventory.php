<?php
/**
 * get_inventory.php - Versión SIN caché interno para evitar conflictos
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Headers CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');

// Manejar preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ⚠️ IMPORTANTE: Tu URL de Google Apps Script
$GOOGLE_SCRIPT_URL = 'https://script.google.com/macros/s/AKfycbycaZE0kKPy0wlqs7X2XI3PIDvr_nZvY1yyNX0NeJxrDJSXaVZs6IwzNlvEB6JK0k-U/exec';

function logMessage($message) {
    $logFile = __DIR__ . '/get_inventory_simple.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND | LOCK_EX);
}

function getData() {
    global $GOOGLE_SCRIPT_URL;
    
    logMessage("=== FETCHING FRESH DATA ALWAYS ===");
    logMessage("URL: $GOOGLE_SCRIPT_URL");
    
    try {
        $context = stream_context_create([
            'http' => [
                'timeout' => 30,
                'user_agent' => 'InventorySystem/NoCache',
                'method' => 'GET',
                'header' => [
                    'Accept: application/json',
                    'Cache-Control: no-cache',
                    'Pragma: no-cache'
                ]
            ]
        ]);
        
        $response = @file_get_contents($GOOGLE_SCRIPT_URL, false, $context);
        
        if ($response === false) {
            $error = error_get_last();
            throw new Exception('Failed to fetch data: ' . ($error['message'] ?? 'Unknown error'));
        }
        
        logMessage("Response received, length: " . strlen($response));
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response: ' . json_last_error_msg());
        }
        
        // Si el response tiene estructura de éxito, extraer los datos
        if (isset($data['success']) && $data['success'] && isset($data['data'])) {
            logMessage("Extracting data from success response");
            $data = $data['data'];
        }
        
        if (!is_array($data)) {
            throw new Exception('Invalid data format received: ' . gettype($data));
        }
        
        logMessage("Data processed successfully, rows: " . count($data));
        
        return [
            'success' => true,
            'data' => $data,
            'meta' => [
                'count' => count($data),
                'cached' => false, // SIEMPRE FRESCO
                'timestamp' => date('c'),
                'cache_age' => 0,
                'source' => 'google_apps_script_direct'
            ]
        ];
        
    } catch (Exception $e) {
        logMessage("ERROR: " . $e->getMessage());
        throw $e;
    }
}

// Manejar requests
try {
    $action = $_GET['action'] ?? 'getData';
    
    logMessage("Request action: $action");
    
    switch ($action) {
        case 'getData':
            $result = getData(); // SIEMPRE OBTIENE DATOS FRESCOS
            break;
            
        case 'getStatus':
            $result = [
                'success' => true,
                'status' => 'healthy',
                'mode' => 'no_cache_direct_fetch',
                'google_script_url' => $GOOGLE_SCRIPT_URL,
                'timestamp' => date('c')
            ];
            break;
            
        case 'clearCache':
            // Limpiar cualquier archivo de caché que pueda existir
            $cleared = 0;
            $cachePatterns = [
                __DIR__ . '/cache/*',
                __DIR__ . '/*cache*',
                __DIR__ . '/*inventory*'
            ];
            
            foreach ($cachePatterns as $pattern) {
                $files = glob($pattern);
                foreach ($files as $file) {
                    if (is_file($file) && 
                        !strpos(basename($file), '.php') && 
                        !strpos(basename($file), '.html')) {
                        if (unlink($file)) {
                            $cleared++;
                            logMessage("Deleted: $file");
                        }
                    }
                }
            }
            
            $result = [
                'success' => true,
                'message' => 'All cache files cleared (if any existed)',
                'files_cleared' => $cleared,
                'mode' => 'no_cache_mode',
                'timestamp' => date('c')
            ];
            break;
            
        case 'stream':
            // SSE no soportado - respuesta simple
            header('Content-Type: text/plain');
            echo "data: " . json_encode([
                'type' => 'error',
                'message' => 'SSE not supported - using polling fallback'
            ]) . "\n\n";
            exit();
            
        default:
            throw new Exception('Unknown action: ' . $action);
    }
    
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    logMessage("FATAL ERROR: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('c'),
        'mode' => 'no_cache_direct_fetch'
    ], JSON_PRETTY_PRINT);
}
?>