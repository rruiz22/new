<?php

namespace Modules\SalesOrders\Services;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Cache\CacheInterface;

/**
 * High-performance chunked file upload service
 * Handles large file uploads with progress tracking and resume capability
 */
class ChunkedUploadService
{
    private CacheInterface $cache;
    private array $config;
    private string $tempPath;
    private SecureFileUploadService $secureUploadService;

    public function __construct()
    {
        $this->cache = \Config\Services::cache();
        $this->secureUploadService = new SecureFileUploadService();
        $this->loadConfig();
    }

    private function loadConfig(): void
    {
        $this->config = [
            'chunk_size' => 1024 * 1024, // 1MB chunks
            'max_file_size' => 500 * 1024 * 1024, // 500MB
            'session_timeout' => 3600, // 1 hour
            'cleanup_interval' => 86400, // 24 hours
        ];

        $this->tempPath = WRITEPATH . 'temp/chunked_uploads/';
        if (!is_dir($this->tempPath)) {
            mkdir($this->tempPath, 0750, true);
        }
    }

    /**
     * Initialize chunked upload session
     */
    public function initializeUploadSession(array $fileInfo, int $orderId, int $userId): array
    {
        // Validate file information
        $this->validateFileInfo($fileInfo);

        // Generate unique session ID
        $sessionId = $this->generateSessionId();
        
        // Calculate expected chunks
        $totalSize = (int) $fileInfo['size'];
        $chunkSize = $this->config['chunk_size'];
        $totalChunks = ceil($totalSize / $chunkSize);

        // Create session data
        $sessionData = [
            'session_id' => $sessionId,
            'order_id' => $orderId,
            'user_id' => $userId,
            'filename' => $fileInfo['filename'],
            'mime_type' => $fileInfo['mimeType'],
            'total_size' => $totalSize,
            'chunk_size' => $chunkSize,
            'total_chunks' => $totalChunks,
            'uploaded_chunks' => [],
            'status' => 'initialized',
            'created_at' => time(),
            'last_activity' => time(),
            'temp_file_path' => $this->getTempFilePath($sessionId),
            'checksum' => $fileInfo['checksum'] ?? null
        ];

        // Store session in cache
        $this->cache->save(
            $this->getSessionKey($sessionId), 
            $sessionData, 
            $this->config['session_timeout']
        );

        return [
            'session_id' => $sessionId,
            'chunk_size' => $chunkSize,
            'total_chunks' => $totalChunks,
            'upload_url' => base_url('api/sales-orders/upload-chunk'),
            'status_url' => base_url('api/sales-orders/upload-status/' . $sessionId)
        ];
    }

    /**
     * Process uploaded chunk
     */
    public function processChunk(string $sessionId, int $chunkIndex, string $chunkData): array
    {
        // Retrieve session data
        $session = $this->getSession($sessionId);
        if (!$session) {
            throw new \InvalidArgumentException('Invalid or expired upload session');
        }

        // Validate chunk
        $this->validateChunk($session, $chunkIndex, $chunkData);

        // Save chunk to temporary file
        $this->saveChunk($session, $chunkIndex, $chunkData);

        // Update session data
        $session['uploaded_chunks'][] = $chunkIndex;
        $session['uploaded_chunks'] = array_unique($session['uploaded_chunks']);
        $session['last_activity'] = time();
        
        // Calculate progress
        $progress = (count($session['uploaded_chunks']) / $session['total_chunks']) * 100;
        $session['progress'] = round($progress, 2);

        // Check if upload is complete
        $isComplete = count($session['uploaded_chunks']) === $session['total_chunks'];
        
        if ($isComplete) {
            $session['status'] = 'assembling';
            $finalResult = $this->assembleFile($session);
            
            if ($finalResult['success']) {
                $session['status'] = 'completed';
                $session['final_file'] = $finalResult['file_data'];
                $session['completed_at'] = time();
            } else {
                $session['status'] = 'failed';
                $session['error'] = $finalResult['error'];
            }
        }

        // Update session
        $this->updateSession($sessionId, $session);

        return [
            'chunk_index' => $chunkIndex,
            'progress' => $progress,
            'status' => $session['status'],
            'is_complete' => $isComplete,
            'file_data' => $session['final_file'] ?? null,
            'error' => $session['error'] ?? null
        ];
    }

    /**
     * Get upload session status
     */
    public function getUploadStatus(string $sessionId): ?array
    {
        $session = $this->getSession($sessionId);
        if (!$session) {
            return null;
        }

        return [
            'session_id' => $sessionId,
            'status' => $session['status'],
            'progress' => $session['progress'] ?? 0,
            'uploaded_chunks' => count($session['uploaded_chunks']),
            'total_chunks' => $session['total_chunks'],
            'file_data' => $session['final_file'] ?? null,
            'error' => $session['error'] ?? null,
            'created_at' => $session['created_at'],
            'last_activity' => $session['last_activity']
        ];
    }

    /**
     * Resume incomplete upload
     */
    public function resumeUpload(string $sessionId): array
    {
        $session = $this->getSession($sessionId);
        if (!$session) {
            throw new \InvalidArgumentException('Invalid or expired upload session');
        }

        if ($session['status'] === 'completed') {
            return [
                'status' => 'completed',
                'file_data' => $session['final_file']
            ];
        }

        // Find missing chunks
        $allChunks = range(0, $session['total_chunks'] - 1);
        $uploadedChunks = $session['uploaded_chunks'];
        $missingChunks = array_diff($allChunks, $uploadedChunks);

        return [
            'status' => $session['status'],
            'progress' => $session['progress'] ?? 0,
            'missing_chunks' => array_values($missingChunks),
            'next_chunk' => empty($missingChunks) ? null : min($missingChunks)
        ];
    }

    /**
     * Cancel upload session
     */
    public function cancelUpload(string $sessionId): bool
    {
        $session = $this->getSession($sessionId);
        if ($session) {
            // Clean up temporary files
            $this->cleanupTempFiles($session);
            
            // Remove session
            $this->cache->delete($this->getSessionKey($sessionId));
            
            return true;
        }
        
        return false;
    }

    /**
     * Validate file information
     */
    private function validateFileInfo(array $fileInfo): void
    {
        $required = ['filename', 'size', 'mimeType'];
        foreach ($required as $field) {
            if (!isset($fileInfo[$field])) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
            }
        }

        if ($fileInfo['size'] > $this->config['max_file_size']) {
            throw new \InvalidArgumentException('File too large');
        }

        if ($fileInfo['size'] <= 0) {
            throw new \InvalidArgumentException('Invalid file size');
        }
    }

    /**
     * Validate uploaded chunk
     */
    private function validateChunk(array $session, int $chunkIndex, string $chunkData): void
    {
        if ($chunkIndex < 0 || $chunkIndex >= $session['total_chunks']) {
            throw new \InvalidArgumentException('Invalid chunk index');
        }

        if (in_array($chunkIndex, $session['uploaded_chunks'])) {
            throw new \InvalidArgumentException('Chunk already uploaded');
        }

        $expectedSize = ($chunkIndex === $session['total_chunks'] - 1) 
            ? $session['total_size'] % $session['chunk_size'] ?: $session['chunk_size']
            : $session['chunk_size'];

        if (strlen($chunkData) !== $expectedSize) {
            throw new \InvalidArgumentException('Invalid chunk size');
        }
    }

    /**
     * Save chunk to temporary file
     */
    private function saveChunk(array $session, int $chunkIndex, string $chunkData): void
    {
        $tempFile = $session['temp_file_path'];
        $chunkFile = $tempFile . '.chunk_' . $chunkIndex;

        if (file_put_contents($chunkFile, $chunkData) === false) {
            throw new \RuntimeException('Failed to save chunk');
        }

        // Verify chunk integrity if checksum provided
        if (isset($session['chunk_checksums'][$chunkIndex])) {
            $actualChecksum = hash('sha256', $chunkData);
            if ($actualChecksum !== $session['chunk_checksums'][$chunkIndex]) {
                unlink($chunkFile);
                throw new \RuntimeException('Chunk checksum mismatch');
            }
        }
    }

    /**
     * Assemble final file from chunks
     */
    private function assembleFile(array $session): array
    {
        try {
            $tempFile = $session['temp_file_path'];
            $finalFile = $tempFile . '.final';

            // Create final file
            $finalHandle = fopen($finalFile, 'wb');
            if (!$finalHandle) {
                throw new \RuntimeException('Cannot create final file');
            }

            // Assemble chunks in order
            for ($i = 0; $i < $session['total_chunks']; $i++) {
                $chunkFile = $tempFile . '.chunk_' . $i;
                
                if (!file_exists($chunkFile)) {
                    throw new \RuntimeException("Missing chunk: {$i}");
                }

                $chunkData = file_get_contents($chunkFile);
                if ($chunkData === false) {
                    throw new \RuntimeException("Cannot read chunk: {$i}");
                }

                fwrite($finalHandle, $chunkData);
                unlink($chunkFile); // Clean up chunk file
            }

            fclose($finalHandle);

            // Verify final file
            if (filesize($finalFile) !== $session['total_size']) {
                throw new \RuntimeException('Final file size mismatch');
            }

            // Verify checksum if provided
            if ($session['checksum']) {
                $actualChecksum = hash_file('sha256', $finalFile);
                if ($actualChecksum !== $session['checksum']) {
                    throw new \RuntimeException('Final file checksum mismatch');
                }
            }

            // Process through secure upload service
            $uploadedFile = new \CodeIgniter\HTTP\Files\UploadedFile(
                $finalFile,
                $session['filename'],
                $session['mime_type'],
                filesize($finalFile),
                UPLOAD_ERR_OK
            );

            $result = $this->secureUploadService->processSecureUploads(
                [$uploadedFile], 
                $session['order_id'], 
                $session['user_id']
            );

            // Clean up temporary file
            unlink($finalFile);

            if (!empty($result['success'])) {
                return [
                    'success' => true,
                    'file_data' => $result['success'][0]
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $result['errors'][0]['error'] ?? 'Upload processing failed'
                ];
            }

        } catch (\Exception $e) {
            log_message('error', 'Chunked upload assembly failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Clean up old upload sessions
     */
    public function cleanupExpiredSessions(): int
    {
        $cleaned = 0;
        $pattern = $this->getSessionKey('*');
        
        // This would need to be implemented based on your cache driver
        // For now, we'll clean temp directory
        $tempFiles = glob($this->tempPath . '*');
        $cutoffTime = time() - $this->config['cleanup_interval'];
        
        foreach ($tempFiles as $file) {
            if (filemtime($file) < $cutoffTime) {
                unlink($file);
                $cleaned++;
            }
        }
        
        return $cleaned;
    }

    /**
     * Helper methods
     */
    private function generateSessionId(): string
    {
        return 'upload_' . uniqid() . '_' . bin2hex(random_bytes(8));
    }

    private function getSessionKey(string $sessionId): string
    {
        return 'chunked_upload_session_' . $sessionId;
    }

    private function getTempFilePath(string $sessionId): string
    {
        return $this->tempPath . $sessionId;
    }

    private function getSession(string $sessionId): ?array
    {
        return $this->cache->get($this->getSessionKey($sessionId));
    }

    private function updateSession(string $sessionId, array $session): void
    {
        $this->cache->save(
            $this->getSessionKey($sessionId), 
            $session, 
            $this->config['session_timeout']
        );
    }

    private function cleanupTempFiles(array $session): void
    {
        $tempFile = $session['temp_file_path'];
        
        // Remove chunk files
        for ($i = 0; $i < $session['total_chunks']; $i++) {
            $chunkFile = $tempFile . '.chunk_' . $i;
            if (file_exists($chunkFile)) {
                unlink($chunkFile);
            }
        }

        // Remove final file if exists
        if (file_exists($tempFile . '.final')) {
            unlink($tempFile . '.final');
        }
    }
}