<?php
/**
 * includes/CacheManager.php
 * Manejo de caché con TTL y limpieza automática
 */

class CacheManager {
    private $config;
    private $cacheDir;
    
    public function __construct($config) {
        $this->config = $config;
        $this->cacheDir = $config['directory'];
        $this->createCacheDirectory();
    }
    
    public function get($key) {
        if (!$this->config['enabled']) return null;
        
        $cacheFile = $this->getCacheFile($key);
        
        if (!file_exists($cacheFile)) return null;
        
        $cacheData = json_decode(file_get_contents($cacheFile), true);
        
        if (!$cacheData || !isset($cacheData['expires_at'])) {
            unlink($cacheFile);
            return null;
        }
        
        if (time() > $cacheData['expires_at']) {
            unlink($cacheFile);
            return null;
        }
        
        return $cacheData['data'];
    }
    
    public function set($key, $data, $ttl = null) {
        if (!$this->config['enabled']) return false;
        
        $ttl = $ttl ?? $this->config['duration'];
        $cacheFile = $this->getCacheFile($key);
        
        $cacheData = [
            'data' => $data,
            'created_at' => time(),
            'expires_at' => time() + $ttl,
            'key' => $key
        ];
        
        return file_put_contents($cacheFile, json_encode($cacheData, JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
    }
    
    public function delete($key) {
        $cacheFile = $this->getCacheFile($key);
        if (file_exists($cacheFile)) {
            return unlink($cacheFile);
        }
        return true;
    }
    
    public function clear() {
        $files = glob($this->cacheDir . '*.cache');
        $cleared = 0;
        foreach ($files as $file) {
            if (unlink($file)) {
                $cleared++;
            }
        }
        return $cleared;
    }
    
    public function getStats() {
        $files = glob($this->cacheDir . '*.cache');
        $totalSize = 0;
        $validFiles = 0;
        $expiredFiles = 0;
        
        foreach ($files as $file) {
            $size = filesize($file);
            $totalSize += $size;
            
            $cacheData = json_decode(file_get_contents($file), true);
            if ($cacheData && isset($cacheData['expires_at'])) {
                if (time() <= $cacheData['expires_at']) {
                    $validFiles++;
                } else {
                    $expiredFiles++;
                }
            }
        }
        
        return [
            'total_files' => count($files),
            'valid_files' => $validFiles,
            'expired_files' => $expiredFiles,
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize),
            'cache_directory' => $this->cacheDir
        ];
    }
    
    public function cleanup() {
        $files = glob($this->cacheDir . '*.cache');
        $cleaned = 0;
        
        foreach ($files as $file) {
            $shouldDelete = false;
            
            $cacheData = json_decode(file_get_contents($file), true);
            
            // Eliminar archivos expirados o corruptos
            if (!$cacheData || !isset($cacheData['expires_at'])) {
                $shouldDelete = true;
            } elseif (time() > $cacheData['expires_at']) {
                $shouldDelete = true;
            }
            
            // Eliminar archivos muy viejos
            if (time() - filemtime($file) > $this->config['max_file_age']) {
                $shouldDelete = true;
            }
            
            if ($shouldDelete) {
                unlink($file);
                $cleaned++;
            }
        }
        
        return $cleaned;
    }
    
    public function has($key) {
        return $this->get($key) !== null;
    }
    
    public function touch($key, $ttl = null) {
        $data = $this->get($key);
        if ($data !== null) {
            return $this->set($key, $data, $ttl);
        }
        return false;
    }
    
    private function getCacheFile($key) {
        return $this->cacheDir . md5($key) . '.cache';
    }
    
    private function createCacheDirectory() {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }
    
    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}