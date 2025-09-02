<?php

namespace Modules\SalesOrders\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Modules\SalesOrders\Services\SecureFileUploadService;
use Modules\SalesOrders\Services\ChunkedUploadService;

/**
 * Secure attachments API controller with comprehensive security measures
 */
class SecureAttachmentsController extends ResourceController
{
    protected $format = 'json';
    protected SecureFileUploadService $uploadService;
    protected ChunkedUploadService $chunkedService;
    
    public function __construct()
    {
        $this->uploadService = new SecureFileUploadService();
        $this->chunkedService = new ChunkedUploadService();
    }

    /**
     * Upload attachments securely (standard upload)
     */
    public function upload()
    {
        try {
            // Validate authentication and authorization
            $userId = $this->validateUser();
            $orderId = $this->request->getPost('order_id');
            
            if (!$orderId) {
                return $this->failValidationError('Order ID is required');
            }

            // Validate user has access to this order
            if (!$this->validateOrderAccess($userId, $orderId)) {
                return $this->failForbidden('Access denied to this order');
            }

            // CSRF protection
            if (!$this->validateCSRF()) {
                return $this->failUnauthorized('Invalid CSRF token');
            }

            // Rate limiting
            if (!$this->checkRateLimit($userId)) {
                return $this->failTooManyRequests('Rate limit exceeded');
            }

            // Get uploaded files
            $files = $this->request->getFiles();
            if (empty($files['attachments'])) {
                return $this->failValidationError('No files uploaded');
            }

            // Process uploads securely
            $results = $this->uploadService->processSecureUploads(
                $files['attachments'], 
                (int)$orderId, 
                $userId
            );

            // Log upload activity
            $this->logUploadActivity($userId, $orderId, $results);

            return $this->respond([
                'status' => 'success',
                'data' => $results,
                'message' => $this->generateUploadMessage($results)
            ]);

        } catch (\InvalidArgumentException $e) {
            return $this->failValidationError($e->getMessage());
        } catch (\Exception $e) {
            log_message('error', 'Secure upload failed: ' . $e->getMessage());
            return $this->failServerError('Upload failed');
        }
    }

    /**
     * Initialize chunked upload session
     */
    public function initChunkedUpload()
    {
        try {
            $userId = $this->validateUser();
            $input = $this->request->getJSON(true);
            
            $orderId = $input['order_id'] ?? null;
            if (!$orderId) {
                return $this->failValidationError('Order ID is required');
            }

            if (!$this->validateOrderAccess($userId, $orderId)) {
                return $this->failForbidden('Access denied to this order');
            }

            if (!$this->validateCSRF()) {
                return $this->failUnauthorized('Invalid CSRF token');
            }

            $fileInfo = [
                'filename' => $input['filename'] ?? null,
                'size' => $input['size'] ?? null,
                'mimeType' => $input['mimeType'] ?? null,
                'checksum' => $input['checksum'] ?? null
            ];

            $session = $this->chunkedService->initializeUploadSession(
                $fileInfo,
                (int)$orderId,
                $userId
            );

            return $this->respond([
                'status' => 'success',
                'data' => $session
            ]);

        } catch (\InvalidArgumentException $e) {
            return $this->failValidationError($e->getMessage());
        } catch (\Exception $e) {
            log_message('error', 'Chunked upload initialization failed: ' . $e->getMessage());
            return $this->failServerError('Upload initialization failed');
        }
    }

    /**
     * Upload file chunk
     */
    public function uploadChunk()
    {
        try {
            $userId = $this->validateUser();
            
            $sessionId = $this->request->getPost('session_id');
            $chunkIndex = (int)$this->request->getPost('chunk_index');
            
            if (!$sessionId) {
                return $this->failValidationError('Session ID is required');
            }

            // Get chunk data
            $chunkFile = $this->request->getFile('chunk');
            if (!$chunkFile || !$chunkFile->isValid()) {
                return $this->failValidationError('Invalid chunk data');
            }

            $chunkData = file_get_contents($chunkFile->getTempName());

            $result = $this->chunkedService->processChunk(
                $sessionId,
                $chunkIndex,
                $chunkData
            );

            return $this->respond([
                'status' => 'success',
                'data' => $result
            ]);

        } catch (\InvalidArgumentException $e) {
            return $this->failValidationError($e->getMessage());
        } catch (\Exception $e) {
            log_message('error', 'Chunk upload failed: ' . $e->getMessage());
            return $this->failServerError('Chunk upload failed');
        }
    }

    /**
     * Get upload status
     */
    public function getUploadStatus($sessionId = null)
    {
        try {
            $userId = $this->validateUser();
            
            if (!$sessionId) {
                return $this->failValidationError('Session ID is required');
            }

            $status = $this->chunkedService->getUploadStatus($sessionId);
            if (!$status) {
                return $this->failNotFound('Upload session not found');
            }

            return $this->respond([
                'status' => 'success',
                'data' => $status
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Get upload status failed: ' . $e->getMessage());
            return $this->failServerError('Failed to get upload status');
        }
    }

    /**
     * Resume chunked upload
     */
    public function resumeUpload($sessionId = null)
    {
        try {
            $userId = $this->validateUser();
            
            if (!$sessionId) {
                return $this->failValidationError('Session ID is required');
            }

            $result = $this->chunkedService->resumeUpload($sessionId);

            return $this->respond([
                'status' => 'success',
                'data' => $result
            ]);

        } catch (\InvalidArgumentException $e) {
            return $this->failValidationError($e->getMessage());
        } catch (\Exception $e) {
            log_message('error', 'Resume upload failed: ' . $e->getMessage());
            return $this->failServerError('Resume upload failed');
        }
    }

    /**
     * Cancel chunked upload
     */
    public function cancelUpload($sessionId = null)
    {
        try {
            $userId = $this->validateUser();
            
            if (!$sessionId) {
                return $this->failValidationError('Session ID is required');
            }

            $cancelled = $this->chunkedService->cancelUpload($sessionId);

            return $this->respond([
                'status' => 'success',
                'data' => ['cancelled' => $cancelled]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Cancel upload failed: ' . $e->getMessage());
            return $this->failServerError('Cancel upload failed');
        }
    }

    /**
     * Secure file download with token validation
     */
    public function download($orderId = null, $filename = null)
    {
        try {
            $userId = $this->validateUser();
            
            if (!$orderId || !$filename) {
                return $this->failValidationError('Order ID and filename are required');
            }

            // Validate access
            if (!$this->validateOrderAccess($userId, $orderId)) {
                return $this->failForbidden('Access denied to this order');
            }

            // Validate download token
            $token = $this->request->getGet('token');
            if (!$token || !$this->uploadService->validateDownloadToken($token, $filename, $userId)) {
                return $this->failUnauthorized('Invalid download token');
            }

            // Decode filename
            $filename = urldecode($filename);
            
            // Security check - prevent path traversal
            if (strpos($filename, '..') !== false || strpos($filename, '/') !== false) {
                return $this->failForbidden('Invalid filename');
            }

            // Build secure file path
            $filePath = WRITEPATH . 'secure_storage/sales_orders/' . $orderId . '/comments/' . $filename;
            
            if (!file_exists($filePath)) {
                return $this->failNotFound('File not found');
            }

            // Log download activity
            $this->logDownloadActivity($userId, $orderId, $filename);

            // Get file info
            $mimeType = mime_content_type($filePath);
            $originalName = $this->getOriginalFilename($filePath, $filename);
            $action = $this->request->getGet('action') ?? 'download';

            // Security headers
            $this->response->setHeader('X-Content-Type-Options', 'nosniff')
                          ->setHeader('X-Frame-Options', 'DENY')
                          ->setHeader('Cache-Control', 'private, no-cache');

            if ($action === 'view' && $this->isViewable($mimeType)) {
                return $this->response
                    ->setHeader('Content-Type', $mimeType)
                    ->setHeader('Content-Disposition', 'inline; filename="' . $originalName . '"')
                    ->setBody(file_get_contents($filePath));
            } else {
                return $this->response->download($filePath, null, true)->setFileName($originalName);
            }

        } catch (\Exception $e) {
            log_message('error', 'Secure download failed: ' . $e->getMessage());
            return $this->failServerError('Download failed');
        }
    }

    /**
     * Delete attachment securely
     */
    public function delete($orderId = null, $attachmentId = null)
    {
        try {
            $userId = $this->validateUser();
            
            if (!$orderId || !$attachmentId) {
                return $this->failValidationError('Order ID and attachment ID are required');
            }

            if (!$this->validateOrderAccess($userId, $orderId)) {
                return $this->failForbidden('Access denied to this order');
            }

            if (!$this->validateCSRF()) {
                return $this->failUnauthorized('Invalid CSRF token');
            }

            // Validate user can delete (owner or admin)
            if (!$this->canDeleteAttachment($userId, $attachmentId)) {
                return $this->failForbidden('Cannot delete this attachment');
            }

            // Get attachment info before deletion
            $attachmentInfo = $this->getAttachmentInfo($attachmentId);
            if (!$attachmentInfo) {
                return $this->failNotFound('Attachment not found');
            }

            // Delete physical files
            $this->deletePhysicalFiles($orderId, $attachmentInfo);

            // Remove from database (you'll need to implement this)
            $this->deleteAttachmentRecord($attachmentId);

            // Log deletion
            $this->logDeletionActivity($userId, $orderId, $attachmentInfo);

            return $this->respond([
                'status' => 'success',
                'message' => 'Attachment deleted successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Secure delete failed: ' . $e->getMessage());
            return $this->failServerError('Delete failed');
        }
    }

    /**
     * Validation and helper methods
     */
    private function validateUser(): int
    {
        $session = session();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            throw new \UnexpectedValueException('User not authenticated');
        }
        
        return (int)$userId;
    }

    private function validateOrderAccess(int $userId, int $orderId): bool
    {
        // Implement your access control logic here
        // Check if user has permission to access this order
        return true; // Placeholder
    }

    private function validateCSRF(): bool
    {
        $token = $this->request->getHeaderLine('X-CSRF-Token') 
                 ?? $this->request->getPost('csrf_token');
        
        if (!$token) {
            return false;
        }
        
        return hash_equals(csrf_hash(), $token);
    }

    private function checkRateLimit(int $userId): bool
    {
        $cache = \Config\Services::cache();
        $key = 'rate_limit_upload_' . $userId;
        
        $attempts = $cache->get($key) ?? 0;
        if ($attempts >= 10) { // 10 uploads per minute
            return false;
        }
        
        $cache->save($key, $attempts + 1, 60);
        return true;
    }

    private function isViewable(string $mimeType): bool
    {
        $viewableTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'text/plain'
        ];
        
        return in_array($mimeType, $viewableTypes);
    }

    private function generateUploadMessage(array $results): string
    {
        $successCount = count($results['success']);
        $errorCount = count($results['errors']);
        $quarantinedCount = count($results['quarantined']);
        
        if ($successCount > 0 && $errorCount === 0 && $quarantinedCount === 0) {
            return "{$successCount} file(s) uploaded successfully";
        }
        
        $messages = [];
        if ($successCount > 0) {
            $messages[] = "{$successCount} uploaded successfully";
        }
        if ($errorCount > 0) {
            $messages[] = "{$errorCount} failed";
        }
        if ($quarantinedCount > 0) {
            $messages[] = "{$quarantinedCount} quarantined for security review";
        }
        
        return implode(', ', $messages);
    }

    private function getOriginalFilename(string $filePath, string $secureFilename): string
    {
        // You would implement logic to retrieve the original filename
        // from your database based on the secure filename
        return $secureFilename; // Placeholder
    }

    private function canDeleteAttachment(int $userId, string $attachmentId): bool
    {
        // Implement logic to check if user can delete this attachment
        // (e.g., owner, admin, or has specific permissions)
        return true; // Placeholder
    }

    private function getAttachmentInfo(string $attachmentId): ?array
    {
        // Retrieve attachment information from database
        return null; // Placeholder
    }

    private function deletePhysicalFiles(int $orderId, array $attachmentInfo): void
    {
        $basePath = WRITEPATH . 'secure_storage/sales_orders/' . $orderId . '/comments/';
        
        // Delete main file
        if (isset($attachmentInfo['secure_filename'])) {
            $mainFile = $basePath . $attachmentInfo['secure_filename'];
            if (file_exists($mainFile)) {
                unlink($mainFile);
            }
        }

        // Delete thumbnail if exists
        if (isset($attachmentInfo['thumbnail']['filename'])) {
            $thumbnailFile = $basePath . 'thumbnails/' . $attachmentInfo['thumbnail']['filename'];
            if (file_exists($thumbnailFile)) {
                unlink($thumbnailFile);
            }
        }
    }

    private function deleteAttachmentRecord(string $attachmentId): void
    {
        // Implement database deletion logic
        // This would remove the attachment reference from the comment
    }

    private function logUploadActivity(int $userId, int $orderId, array $results): void
    {
        $activity = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'file_upload',
            'user_id' => $userId,
            'order_id' => $orderId,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'results' => [
                'success_count' => count($results['success']),
                'error_count' => count($results['errors']),
                'quarantined_count' => count($results['quarantined'])
            ]
        ];

        log_message('info', 'Upload activity: ' . json_encode($activity));
    }

    private function logDownloadActivity(int $userId, int $orderId, string $filename): void
    {
        $activity = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'file_download',
            'user_id' => $userId,
            'order_id' => $orderId,
            'filename' => $filename,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ];

        log_message('info', 'Download activity: ' . json_encode($activity));
    }

    private function logDeletionActivity(int $userId, int $orderId, array $attachmentInfo): void
    {
        $activity = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type' => 'file_deletion',
            'user_id' => $userId,
            'order_id' => $orderId,
            'attachment_info' => $attachmentInfo,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ];

        log_message('info', 'Deletion activity: ' . json_encode($activity));
    }
}