<?php

namespace Modules\SalesOrders\Models;

use CodeIgniter\Model;

/**
 * Model for managing secure file attachments in sales orders
 */
class SalesOrderAttachmentModel extends Model
{
    protected $table = 'sales_orders_attachments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'comment_id',
        'order_id',
        'original_filename',
        'secure_filename',
        'file_size',
        'mime_type',
        'file_extension',
        'checksum_sha256',
        'storage_path',
        'thumbnail_path',
        'download_token',
        'security_scan_status',
        'security_scan_details',
        'quarantine_reason',
        'download_count',
        'last_downloaded_at',
        'expires_at',
        'uploaded_by',
        'uploaded_at',
        'is_active',
        'metadata'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'security_scan_details' => 'json',
        'metadata' => 'json',
        'thumbnail_path' => 'json'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|integer',
        'original_filename' => 'required|string|max_length[255]',
        'secure_filename' => 'required|string|max_length[255]',
        'file_size' => 'required|integer',
        'mime_type' => 'required|string|max_length[100]',
        'checksum_sha256' => 'required|string|exact_length[64]',
        'download_token' => 'required|string|max_length[255]',
        'uploaded_by' => 'required|integer'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'Order ID is required',
            'integer' => 'Order ID must be an integer'
        ],
        'checksum_sha256' => [
            'exact_length' => 'Checksum must be 64 characters long'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['generateUploadedAt'];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get attachments for a specific comment
     */
    public function getAttachmentsByComment(int $commentId): array
    {
        return $this->where('comment_id', $commentId)
                   ->where('is_active', 1)
                   ->orderBy('uploaded_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get attachments for a specific order
     */
    public function getAttachmentsByOrder(int $orderId): array
    {
        return $this->select('sales_orders_attachments.*, users.first_name, users.last_name')
                   ->join('users', 'users.id = sales_orders_attachments.uploaded_by', 'left')
                   ->where('sales_orders_attachments.order_id', $orderId)
                   ->where('sales_orders_attachments.is_active', 1)
                   ->orderBy('sales_orders_attachments.uploaded_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get attachment by secure filename
     */
    public function getBySecureFilename(string $secureFilename): ?array
    {
        return $this->where('secure_filename', $secureFilename)
                   ->where('is_active', 1)
                   ->first();
    }

    /**
     * Get attachment by download token
     */
    public function getByDownloadToken(string $token): ?array
    {
        return $this->where('download_token', $token)
                   ->where('is_active', 1)
                   ->where('expires_at >', date('Y-m-d H:i:s'))
                   ->first();
    }

    /**
     * Update download statistics
     */
    public function recordDownload(int $attachmentId): bool
    {
        return $this->update($attachmentId, [
            'download_count' => 'download_count + 1',
            'last_downloaded_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get quarantined files
     */
    public function getQuarantinedFiles(int $limit = 50, int $offset = 0): array
    {
        return $this->select('sales_orders_attachments.*, users.first_name, users.last_name, sales_orders.id as order_number')
                   ->join('users', 'users.id = sales_orders_attachments.uploaded_by', 'left')
                   ->join('sales_orders', 'sales_orders.id = sales_orders_attachments.order_id', 'left')
                   ->where('sales_orders_attachments.security_scan_status', 'quarantined')
                   ->orderBy('sales_orders_attachments.uploaded_at', 'DESC')
                   ->limit($limit, $offset)
                   ->findAll();
    }

    /**
     * Get failed security scan files
     */
    public function getFailedScanFiles(int $limit = 50, int $offset = 0): array
    {
        return $this->select('sales_orders_attachments.*, users.first_name, users.last_name, sales_orders.id as order_number')
                   ->join('users', 'users.id = sales_orders_attachments.uploaded_by', 'left')
                   ->join('sales_orders', 'sales_orders.id = sales_orders_attachments.order_id', 'left')
                   ->where('sales_orders_attachments.security_scan_status', 'failed')
                   ->orderBy('sales_orders_attachments.uploaded_at', 'DESC')
                   ->limit($limit, $offset)
                   ->findAll();
    }

    /**
     * Get expired files that need cleanup
     */
    public function getExpiredFiles(): array
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))
                   ->where('is_active', 1)
                   ->findAll();
    }

    /**
     * Mark file as deleted (soft delete)
     */
    public function markAsDeleted(int $attachmentId): bool
    {
        return $this->update($attachmentId, [
            'is_active' => 0,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update security scan status
     */
    public function updateSecurityStatus(int $attachmentId, string $status, array $details = [], string $reason = null): bool
    {
        $data = [
            'security_scan_status' => $status,
            'security_scan_details' => json_encode($details)
        ];

        if ($reason) {
            $data['quarantine_reason'] = $reason;
        }

        return $this->update($attachmentId, $data);
    }

    /**
     * Get attachment statistics
     */
    public function getAttachmentStats(int $orderId = null): array
    {
        $builder = $this->builder();
        
        if ($orderId) {
            $builder->where('order_id', $orderId);
        }

        $stats = [
            'total_files' => $builder->where('is_active', 1)->countAllResults(false),
            'total_size' => $builder->selectSum('file_size')->get()->getRow()->file_size ?? 0,
            'by_status' => [],
            'by_type' => [],
            'recent_uploads' => 0
        ];

        // Get counts by security status
        $statusQuery = $this->select('security_scan_status, COUNT(*) as count')
                           ->where('is_active', 1);
        
        if ($orderId) {
            $statusQuery->where('order_id', $orderId);
        }
        
        $statusResults = $statusQuery->groupBy('security_scan_status')->findAll();
        
        foreach ($statusResults as $row) {
            $stats['by_status'][$row['security_scan_status']] = (int)$row['count'];
        }

        // Get counts by file type
        $typeQuery = $this->select('file_extension, COUNT(*) as count')
                         ->where('is_active', 1);
        
        if ($orderId) {
            $typeQuery->where('order_id', $orderId);
        }
        
        $typeResults = $typeQuery->groupBy('file_extension')->findAll();
        
        foreach ($typeResults as $row) {
            $stats['by_type'][$row['file_extension']] = (int)$row['count'];
        }

        // Get recent uploads (last 24 hours)
        $recentQuery = $this->where('is_active', 1)
                           ->where('uploaded_at >', date('Y-m-d H:i:s', strtotime('-24 hours')));
        
        if ($orderId) {
            $recentQuery->where('order_id', $orderId);
        }
        
        $stats['recent_uploads'] = $recentQuery->countAllResults();

        return $stats;
    }

    /**
     * Clean up expired files
     */
    public function cleanupExpiredFiles(): int
    {
        $expiredFiles = $this->getExpiredFiles();
        $cleanedCount = 0;

        foreach ($expiredFiles as $file) {
            // Delete physical file
            $this->deletePhysicalFile($file);
            
            // Mark as deleted in database
            $this->markAsDeleted($file['id']);
            
            $cleanedCount++;
        }

        return $cleanedCount;
    }

    /**
     * Regenerate download token
     */
    public function regenerateDownloadToken(int $attachmentId): string
    {
        $attachment = $this->find($attachmentId);
        if (!$attachment) {
            throw new \InvalidArgumentException('Attachment not found');
        }

        $newToken = $this->generateDownloadToken($attachment['secure_filename'], $attachment['uploaded_by']);
        
        $this->update($attachmentId, [
            'download_token' => $newToken,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 year'))
        ]);

        return $newToken;
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
     * Delete physical file from storage
     */
    private function deletePhysicalFile(array $file): void
    {
        $basePath = WRITEPATH . 'secure_storage/sales_orders/' . $file['order_id'] . '/comments/';
        
        // Delete main file
        $mainFile = $basePath . $file['secure_filename'];
        if (file_exists($mainFile)) {
            unlink($mainFile);
        }

        // Delete thumbnail if exists
        if ($file['thumbnail_path']) {
            $thumbnailData = is_string($file['thumbnail_path']) 
                ? json_decode($file['thumbnail_path'], true) 
                : $file['thumbnail_path'];
                
            if (is_array($thumbnailData) && isset($thumbnailData['filename'])) {
                $thumbnailFile = $basePath . 'thumbnails/' . $thumbnailData['filename'];
                if (file_exists($thumbnailFile)) {
                    unlink($thumbnailFile);
                }
            }
        }
    }

    /**
     * Callback to set uploaded_at timestamp
     */
    protected function generateUploadedAt(array $data): array
    {
        if (!isset($data['data']['uploaded_at'])) {
            $data['data']['uploaded_at'] = date('Y-m-d H:i:s');
        }
        
        return $data;
    }
}