<?php
/**
 * includes/Logger.php
 * Sistema de logging avanzado
 */

class Logger {
    private $config;
    private $logFile;
    
    const LEVELS = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3
    ];
    
    public function __construct($config) {
        $this->config = $config;
        $this->logFile = $config['directory'] . 'inventory_' . date('Y-m-d') . '.log';
        $this->createLogDirectory();
    }
    
    public function debug($message, $context = []) {
        $this->log('DEBUG', $message, $context);
    }
    
    public function info($message, $context = []) {
        $this->log('INFO', $message, $context);
    }
    
    public function warning($message, $context = []) {
        $this->log('WARNING', $message, $context);
    }
    
    public function error($message, $context = []) {
        $this->log('ERROR', $message, $context);
    }
    
    private function log($level, $message, $context = []) {
        if (!$this->config['enabled']) return;
        
        $configLevel = self::LEVELS[$this->config['level']] ?? 1;
        $messageLevel = self::LEVELS[$level] ?? 1;
        
        if ($messageLevel < $configLevel) return;
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logEntry = "[{$timestamp}] [{$level}] {$message}{$contextStr}" . PHP_EOL;
        
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Rotar logs si es necesario
        $this->rotateLogsIfNeeded();
    }
    
    private function createLogDirectory() {
        if (!is_dir($this->config['directory'])) {
            mkdir($this->config['directory'], 0755, true);
        }
    }
    
    private function rotateLogsIfNeeded() {
        if (file_exists($this->logFile) && filesize($this->logFile) > $this->config['max_file_size']) {
            $backupFile = $this->config['directory'] . 'inventory_' . date('Y-m-d_H-i-s') . '.log';
            rename($this->logFile, $backupFile);
            
            // Limpiar logs viejos
            $this->cleanupOldLogs();
        }
    }
    
    private function cleanupOldLogs() {
        $files = glob($this->config['directory'] . '*.log');
        if (count($files) > $this->config['max_files']) {
            usort($files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            $filesToDelete = array_slice($files, 0, count($files) - $this->config['max_files']);
            foreach ($filesToDelete as $file) {
                unlink($file);
            }
        }
    }
    
    public function getLogFile() {
        return $this->logFile;
    }
    
    public function getStats() {
        $logFiles = glob($this->config['directory'] . '*.log');
        $totalSize = array_sum(array_map('filesize', $logFiles));
        
        return [
            'total_files' => count($logFiles),
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize),
            'current_log' => basename($this->logFile),
            'directory' => $this->config['directory']
        ];
    }
    
    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}