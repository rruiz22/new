<?php
/**
 * includes/WebhookHandler.php
 * Manejo de webhooks con validación y logging
 */

class WebhookHandler {
    private $config;
    private $logger;
    private $statsFile;
    
    public function __construct($config, $logger) {
        $this->config = $config;
        $this->logger = $logger;
        $this->statsFile = dirname($config['log_file']) . '/webhook_stats.json';
        $this->createLogDirectory();
    }
    
    public function handle() {
        try {
            // Verificar que webhooks estén habilitados
            if (!$this->config['enabled']) {
                throw new Exception('Webhooks are disabled');
            }
            
            // Verificar método
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Only POST method allowed');
            }
            
            // Verificar tamaño del payload
            $contentLength = $_SERVER['CONTENT_LENGTH'] ?? 0;
            if ($contentLength > $this->config['max_payload_size']) {
                throw new Exception('Payload too large');
            }
            
            // Verificar IP si está configurado
            if (!empty($this->config['allowed_ips'])) {
                $clientIp = $this->getClientIp();
                if (!in_array($clientIp, $this->config['allowed_ips']) && !in_array('0.0.0.0', $this->config['allowed_ips'])) {
                    throw new Exception('IP not allowed: ' . $clientIp);
                }
            }
            
            // Obtener payload
            $payload = file_get_contents('php://input');
            
            if (empty($payload)) {
                throw new Exception('Empty payload');
            }
            
            // Verificar firma si está configurada
            if (!empty($this->config['secret_key']) && $this->config['secret_key'] !== 'tu-clave-secreta-aqui-cambiar') {
                $this->verifySignature($payload);
            }
            
            // Parsear datos
            $data = json_decode($payload, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON payload: ' . json_last_error_msg());
            }
            
            // Procesar webhook
            $result = $this->processWebhook($data);
            
            // Log éxito
            $this->logger->info('Webhook processed successfully', [
                'type' => $data['type'] ?? 'unknown',
                'ip' => $this->getClientIp(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
            
            // Actualizar estadísticas
            $this->updateStats(true);
            
            return [
                'success' => true,
                'message' => 'Webhook processed successfully',
                'data' => $result,
                'timestamp' => date('c')
            ];
            
        } catch (Exception $e) {
            $this->logger->error('Webhook error: ' . $e->getMessage(), [
                'ip' => $this->getClientIp(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'payload_preview' => substr($payload ?? '', 0, 200)
            ]);
            
            $this->updateStats(false);
            
            http_response_code(400);
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => date('c')
            ];
        }
    }
    
    private function verifySignature($payload) {
        $signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';
        
        if (empty($signature)) {
            throw new Exception('Missing webhook signature');
        }
        
        // Soportar diferentes formatos de firma
        if (strpos($signature, 'sha256=') === 0) {
            $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $this->config['secret_key']);
        } else if (strpos($signature, 'sha1=') === 0) {
            $expectedSignature = 'sha1=' . hash_hmac('sha1', $payload, $this->config['secret_key']);
        } else {
            $expectedSignature = hash_hmac('sha256', $payload, $this->config['secret_key']);
        }
        
        if (!hash_equals($expectedSignature, $signature)) {
            throw new Exception('Invalid webhook signature');
        }
    }
    
    private function processWebhook($data) {
        // Determinar tipo de webhook
        $type = $data['type'] ?? 'data_update';
        
        switch ($type) {
            case 'data_update':
                return $this->handleDataUpdate($data);
            case 'ping':
                return $this->handlePing($data);
            case 'test':
                return $this->handleTest($data);
            default:
                throw new Exception('Unknown webhook type: ' . $type);
        }
    }
    
    private function handleDataUpdate($data) {
        // Validar estructura de datos
        if (!isset($data['data']) || !is_array($data['data'])) {
            throw new Exception('Invalid data structure');
        }
        
        $this->logger->info('Data update received', [
            'records_count' => count($data['data']),
            'source' => $data['source'] ?? 'unknown'
        ]);
        
        return [
            'type' => 'data_update',
            'records_processed' => count($data['data']),
            'data' => $data['data']
        ];
    }
    
    private function handlePing($data) {
        return [
            'type' => 'pong',
            'message' => 'Webhook endpoint is working',
            'server_time' => date('c')
        ];
    }
    
    private function handleTest($data) {
        return [
            'type' => 'test_response',
            'message' => 'Test webhook received successfully',
            'received_data' => $data
        ];
    }
    
    public function getStatus() {
        $stats = $this->getStats();
        
        return [
            'enabled' => $this->config['enabled'],
            'last_received' => $stats['last_received'] ?? null,
            'total_received' => $stats['total_received'] ?? 0,
            'total_errors' => $stats['total_errors'] ?? 0,
            'success_rate' => $this->calculateSuccessRate($stats)
        ];
    }
    
    public function getLastReceived() {
        $stats = $this->getStats();
        return $stats['last_received'] ?? null;
    }
    
    public function getTotalReceived() {
        $stats = $this->getStats();
        return $stats['total_received'] ?? 0;
    }
    
    private function updateStats($success) {
        $stats = $this->getStats();
        
        $stats['total_received'] = ($stats['total_received'] ?? 0) + 1;
        $stats['last_received'] = date('c');
        
        if ($success) {
            $stats['total_success'] = ($stats['total_success'] ?? 0) + 1;
        } else {
            $stats['total_errors'] = ($stats['total_errors'] ?? 0) + 1;
        }
        
        // Mantener estadísticas de los últimos 24 horas
        $stats['hourly'][] = [
            'hour' => date('Y-m-d H:00:00'),
            'success' => $success,
            'timestamp' => time()
        ];
        
        // Limpiar datos viejos (más de 24 horas)
        $stats['hourly'] = array_filter($stats['hourly'], function($entry) {
            return (time() - $entry['timestamp']) < 86400;
        });
        
        file_put_contents($this->statsFile, json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }
    
    private function getStats() {
        if (!file_exists($this->statsFile)) {
            return [];
        }
        
        $content = file_get_contents($this->statsFile);
        return json_decode($content, true) ?: [];
    }
    
    private function calculateSuccessRate($stats) {
        $total = $stats['total_received'] ?? 0;
        $success = $stats['total_success'] ?? 0;
        
        if ($total === 0) return 100;
        
        return round(($success / $total) * 100, 2);
    }
    
    private function getClientIp() {
        $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = trim(explode(',', $_SERVER[$key])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    private function createLogDirectory() {
        $dir = dirname($this->config['log_file']);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}