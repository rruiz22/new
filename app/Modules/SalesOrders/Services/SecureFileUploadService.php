<?php

namespace Modules\SalesOrders\Services;

use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Enterprise-level secure file upload service
 * Handles validation, sanitization, and secure storage
 */
class SecureFileUploadService
{
    private array $config;
    private array $allowedMimeTypes;
    private array $allowedExtensions;
    private int $maxFileSize;
    private string $uploadPath;
    private string $secureStoragePath;

    public function __construct()
    {
        $this->loadConfig();
    }

    private function loadConfig(): void
    {
        $this->config = [
            'max_file_size' => 50 * 1024 * 1024, // 50MB
            'max_files_per_upload' => 10,
            'chunk_size' => 1024 * 1024, // 1MB chunks
            'quarantine_duration' => 3600, // 1 hour
        ];

        $this->allowedMimeTypes = [
            'image/jpeg',
            'image/png', 
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'text/csv'
        ];

        $this->allowedExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'csv'
        ];

        $this->maxFileSize = $this->config['max_file_size'];
        $this->uploadPath = WRITEPATH . 'uploads/sales_orders/';
        $this->secureStoragePath = WRITEPATH . 'secure_storage/sales_orders/';
    }

    /**
     * Process multiple file uploads securely
     */
    public function processSecureUploads(array $files, int $orderId, int $userId): array
    {
        $results = [
            'success' => [],
            'errors' => [],
            'quarantined' => []
        ];

        if (count($files) > $this->config['max_files_per_upload']) {
            throw new \InvalidArgumentException('Too many files. Maximum ' . $this->config['max_files_per_upload'] . ' files allowed.');
        }

        foreach ($files as $file) {
            try {
                $result = $this->processSecureUpload($file, $orderId, $userId);
                
                if ($result['status'] === 'success') {
                    $results['success'][] = $result['data'];
                } elseif ($result['status'] === 'quarantined') {
                    $results['quarantined'][] = $result['data'];
                } else {
                    $results['errors'][] = $result['error'];
                }
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'file' => $file->getClientName(),
                    'error' => $e->getMessage()
                ];
                log_message('error', 'File upload error: ' . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Process single file upload with comprehensive security checks
     */
    private function processSecureUpload(UploadedFile $file, int $orderId, int $userId): array
    {
        // Basic validation
        if (!$file->isValid() || $file->hasMoved()) {
            throw new \InvalidArgumentException('Invalid file upload');
        }

        // Security validation
        $this->validateFileSecurely($file);

        // Generate secure paths
        $secureFileName = $this->generateSecureFileName($file);
        $quarantinePath = $this->getQuarantinePath($orderId);
        $finalPath = $this->getFinalPath($orderId);

        // Ensure directories exist
        $this->ensureDirectoryExists($quarantinePath);
        $this->ensureDirectoryExists($finalPath);

        // Move to quarantine first
        $quarantineFullPath = $quarantinePath . $secureFileName;
        $file->move($quarantinePath, $secureFileName);

        // Perform security scans
        $scanResults = $this->performSecurityScans($quarantineFullPath);

        if (!$scanResults['safe']) {
            // Keep in quarantine, log incident
            $this->logSecurityIncident($file->getClientName(), $scanResults['threats'], $userId);
            
            return [
                'status' => 'quarantined',
                'data' => [
                    'original_name' => $file->getClientName(),
                    'threats_detected' => $scanResults['threats'],
                    'quarantine_id' => $this->generateQuarantineId()
                ]
            ];
        }

        // Move to final location if safe
        $finalFullPath = $finalPath . $secureFileName;
        rename($quarantineFullPath, $finalFullPath);

        // Generate metadata
        $metadata = $this->generateFileMetadata($file, $finalFullPath, $secureFileName, $userId);

        // Create thumbnail for images
        if ($this->isImage($file)) {
            $metadata['thumbnail'] = $this->createSecureThumbnail($finalFullPath, $orderId, $secureFileName);
        }

        return [
            'status' => 'success',
            'data' => $metadata
        ];
    }

    /**
     * Comprehensive file validation
     */
    private function validateFileSecurely(UploadedFile $file): void
    {
        // Size validation
        if ($file->getSize() > $this->maxFileSize) {
            throw new \InvalidArgumentException('File too large. Maximum size: ' . $this->formatBytes($this->maxFileSize));
        }

        if ($file->getSize() === 0) {
            throw new \InvalidArgumentException('Empty file not allowed');
        }

        // Extension validation
        $extension = strtolower($file->getClientExtension());
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new \InvalidArgumentException('File extension not allowed: ' . $extension);
        }

        // MIME type validation (double-check)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedMimeType = finfo_file($finfo, $file->getTempName());
        finfo_close($finfo);

        if (!in_array($detectedMimeType, $this->allowedMimeTypes)) {
            throw new \InvalidArgumentException('File type not allowed: ' . $detectedMimeType);
        }

        // Cross-validation of extension and MIME type
        if (!$this->validateMimeExtensionMatch($detectedMimeType, $extension)) {
            throw new \InvalidArgumentException('File extension does not match content type');
        }

        // Filename validation
        $originalName = $file->getClientName();
        if (!$this->isSecureFilename($originalName)) {
            throw new \InvalidArgumentException('Invalid filename detected');
        }
    }

    /**
     * Generate cryptographically secure filename
     */
    private function generateSecureFileName(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientExtension());
        $hash = hash('sha256', random_bytes(32) . time() . $file->getClientName());
        return substr($hash, 0, 32) . '.' . $extension;
    }

    /**
     * Perform comprehensive security scans
     */
    private function performSecurityScans(string $filePath): array
    {
        $threats = [];
        $safe = true;

        // Magic bytes validation
        if (!$this->validateMagicBytes($filePath)) {
            $threats[] = 'Invalid magic bytes detected';
            $safe = false;
        }

        // Malware signature detection (basic)
        if ($this->detectMalwareSignatures($filePath)) {
            $threats[] = 'Malware signature detected';
            $safe = false;
        }

        // Script injection detection
        if ($this->detectScriptInjection($filePath)) {
            $threats[] = 'Script injection attempt detected';
            $safe = false;
        }

        // Metadata analysis
        if ($this->analyzeMetadataThreats($filePath)) {
            $threats[] = 'Suspicious metadata detected';
            $safe = false;
        }

        return [
            'safe' => $safe,
            'threats' => $threats
        ];
    }

    /**
     * Validate magic bytes match file type
     */
    private function validateMagicBytes(string $filePath): bool
    {
        $handle = fopen($filePath, 'rb');
        if (!$handle) return false;

        $bytes = fread($handle, 16);
        fclose($handle);

        $magicSignatures = [
            'pdf' => ['%PDF'],
            'jpg' => ["\xFF\xD8\xFF"],
            'png' => ["\x89PNG\r\n\x1A\n"],
            'gif' => ['GIF87a', 'GIF89a'],
            'doc' => ["\xD0\xCF\x11\xE0"],
            'docx' => ['PK\x03\x04'],
        ];

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        if (!isset($magicSignatures[$extension])) {
            return true; // Allow if no signature defined
        }

        foreach ($magicSignatures[$extension] as $signature) {
            if (strpos($bytes, $signature) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect basic malware signatures
     */
    private function detectMalwareSignatures(string $filePath): bool
    {
        $content = file_get_contents($filePath, false, null, 0, 8192); // First 8KB
        
        $malwarePatterns = [
            '/(<\?php|<%|<script|javascript:)/i',
            '/(eval\(|exec\(|system\(|shell_exec\()/i',
            '/(base64_decode|gzinflate|str_rot13)/i',
            '/(\$_GET|\$_POST|\$_REQUEST|\$_SERVER|\$_COOKIE)/i'
        ];

        foreach ($malwarePatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect script injection attempts
     */
    private function detectScriptInjection(string $filePath): bool
    {
        $mimeType = mime_content_type($filePath);
        
        // Check for executable content in non-executable files
        if (strpos($mimeType, 'image/') === 0 || $mimeType === 'application/pdf') {
            $content = file_get_contents($filePath, false, null, 0, 4096);
            
            $scriptPatterns = [
                '/<script/i',
                '/<iframe/i',
                '/<object/i',
                '/<embed/i',
                '/javascript:/i',
                '/vbscript:/i'
            ];

            foreach ($scriptPatterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Analyze metadata for threats
     */
    private function analyzeMetadataThreats(string $filePath): bool
    {
        // Check for suspicious EXIF data in images
        if ($this->isImageFile($filePath)) {
            $exifData = @exif_read_data($filePath);
            if ($exifData) {
                foreach ($exifData as $key => $value) {
                    if (is_string($value) && $this->containsSuspiciousContent($value)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Check if content contains suspicious patterns
     */
    private function containsSuspiciousContent(string $content): bool
    {
        $suspiciousPatterns = [
            '/<script/i',
            '/javascript:/i',
            '/data:text\/html/i',
            '/<\?php/i'
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate comprehensive file metadata
     */
    private function generateFileMetadata(UploadedFile $file, string $finalPath, string $secureFileName, int $userId): array
    {
        return [
            'id' => uniqid('att_'),
            'original_name' => $this->sanitizeFilename($file->getClientName()),
            'secure_filename' => $secureFileName,
            'size' => $file->getSize(),
            'formatted_size' => $this->formatBytes($file->getSize()),
            'mime_type' => mime_content_type($finalPath),
            'file_type' => $this->getFileType(mime_content_type($finalPath)),
            'extension' => strtolower($file->getClientExtension()),
            'uploaded_by' => $userId,
            'uploaded_at' => date('Y-m-d H:i:s'),
            'checksum' => hash_file('sha256', $finalPath),
            'download_token' => $this->generateDownloadToken($secureFileName, $userId),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 year')),
            'security_scan_passed' => true,
            'scan_timestamp' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Create secure thumbnail with validation
     */
    private function createSecureThumbnail(string $originalPath, int $orderId, string $secureFileName): ?string
    {
        try {
            if (!extension_loaded('gd')) {
                log_message('warning', 'GD extension not available for thumbnail creation');
                return null;
            }

            $thumbnailDir = $this->getFinalPath($orderId) . 'thumbnails/';
            $this->ensureDirectoryExists($thumbnailDir);

            $thumbnailName = 'thumb_' . $secureFileName;
            $thumbnailPath = $thumbnailDir . $thumbnailName;

            $image = \Config\Services::image();
            $image->withFile($originalPath)
                  ->resize(150, 150, true, 'center')
                  ->save($thumbnailPath, 85); // 85% quality

            return [
                'filename' => $thumbnailName,
                'path' => 'thumbnails/' . $thumbnailName,
                'size' => filesize($thumbnailPath)
            ];

        } catch (\Exception $e) {
            log_message('error', 'Thumbnail creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate secure download token
     */
    private function generateDownloadToken(string $filename, int $userId): string
    {
        $payload = $filename . '|' . $userId . '|' . time();
        return hash_hmac('sha256', $payload, env('app.encryption.key'));
    }

    /**
     * Validate download token
     */
    public function validateDownloadToken(string $token, string $filename, int $userId): bool
    {
        $expectedToken = $this->generateDownloadToken($filename, $userId);
        return hash_equals($expectedToken, $token);
    }

    /**
     * Helper methods
     */
    private function getQuarantinePath(int $orderId): string
    {
        return WRITEPATH . 'quarantine/sales_orders/' . $orderId . '/';
    }

    private function getFinalPath(int $orderId): string
    {
        return $this->secureStoragePath . $orderId . '/comments/';
    }

    private function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0750, true);
        }
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < 3) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    private function isImage(UploadedFile $file): bool
    {
        return strpos($file->getClientMimeType(), 'image/') === 0;
    }

    private function isImageFile(string $filePath): bool
    {
        $mimeType = mime_content_type($filePath);
        return strpos($mimeType, 'image/') === 0;
    }

    private function getFileType(string $mimeType): string
    {
        if (strpos($mimeType, 'image/') === 0) return 'image';
        if (strpos($mimeType, 'video/') === 0) return 'video';
        if ($mimeType === 'application/pdf') return 'pdf';
        if (in_array($mimeType, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) return 'document';
        return 'file';
    }

    private function validateMimeExtensionMatch(string $mimeType, string $extension): bool
    {
        $validMappings = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/gif' => ['gif'],
            'application/pdf' => ['pdf'],
            'application/msword' => ['doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
        ];

        return isset($validMappings[$mimeType]) && in_array($extension, $validMappings[$mimeType]);
    }

    private function isSecureFilename(string $filename): bool
    {
        // Check for path traversal
        if (strpos($filename, '..') !== false) return false;
        if (strpos($filename, '/') !== false) return false;
        if (strpos($filename, '\\') !== false) return false;
        
        // Check for null bytes
        if (strpos($filename, "\0") !== false) return false;
        
        // Check length
        if (strlen($filename) > 255) return false;
        
        return true;
    }

    private function sanitizeFilename(string $filename): string
    {
        // Remove potentially dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        return substr($filename, 0, 255);
    }

    private function generateQuarantineId(): string
    {
        return 'Q' . date('Ymd') . '_' . uniqid();
    }

    private function logSecurityIncident(string $filename, array $threats, int $userId): void
    {
        $incident = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'file_upload_security_violation',
            'filename' => $filename,
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'threats_detected' => $threats
        ];

        log_message('error', 'Security incident: ' . json_encode($incident));
        
        // You might want to send alerts to security team here
    }
}