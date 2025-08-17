<?php

namespace Modules\PublicPages\Models;

use CodeIgniter\Model;

class PublicPageFileModel extends Model
{
    protected $table = 'public_page_files';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'page_id',
        'filename',
        'original_name',
        'file_type',
        'mime_type',
        'file_size',
        'file_path',
        'alt_text',
        'description',
        'sort_order'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'page_id' => 'integer',
        'file_size' => 'integer',
        'sort_order' => 'integer'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'page_id' => 'required|integer',
        'filename' => 'required|string|max_length[255]',
        'original_name' => 'required|string|max_length[255]',
        'file_type' => 'required|string|max_length[50]',
        'mime_type' => 'required|string|max_length[100]',
        'file_size' => 'required|integer',
        'file_path' => 'required|string|max_length[500]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = ['deletePhysicalFile'];
    protected $afterDelete = [];

    /**
     * Delete physical file before database record deletion
     */
    protected function deletePhysicalFile(array $data)
    {
        if (isset($data['id'])) {
            $file = $this->find($data['id']);
            if ($file && file_exists($file['file_path'])) {
                unlink($file['file_path']);
            }
        }
        return $data;
    }

    /**
     * Process uploaded files
     */
    public function processUpload($files, int $pageId, string $uploadPath = '')
    {
        if (empty($uploadPath)) {
            $uploadPath = WRITEPATH . 'uploads/public_pages/' . $pageId . '/';
        }

        // Create directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $uploadedFiles = [];
        // Allowed MIME types - strict whitelist
        $allowedTypes = [
            // Images
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            // Documents
            'application/pdf', 
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            // Videos (compressed formats only)
            'video/mp4', 'video/webm',
            // Audio
            'audio/mpeg', 'audio/wav', 'audio/ogg'
        ];

        // Dangerous extensions to block
        $dangerousExtensions = [
            'php', 'php3', 'php4', 'php5', 'phtml', 'asp', 'aspx', 'jsp', 'js', 'html', 'htm',
            'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'jar', 'sh', 'py', 'pl', 'rb'
        ];

        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                // Get file extension
                $extension = strtolower($file->getClientExtension());
                
                // Block dangerous extensions
                if (in_array($extension, $dangerousExtensions)) {
                    log_message('warning', "Blocked dangerous file extension: {$extension}");
                    continue;
                }

                // Validate file type
                if (!in_array($file->getClientMimeType(), $allowedTypes)) {
                    log_message('warning', "Blocked file type: {$file->getClientMimeType()}");
                    continue;
                }

                // Validate file size (max 50MB)
                if ($file->getSize() > 50 * 1024 * 1024) {
                    log_message('warning', "File too large: {$file->getSize()} bytes");
                    continue;
                }

                // Additional security: validate actual file content matches MIME type
                if (!$this->validateFileContent($file->getTempName(), $file->getClientMimeType())) {
                    log_message('warning', "File content doesn't match MIME type: {$file->getClientName()}");
                    continue;
                }

                try {
                    // Generate secure filename
                    $fileName = $this->generateSecureFileName($file->getClientExtension());
                    $file->move($uploadPath, $fileName);

                    $fileInfo = [
                        'page_id' => $pageId,
                        'filename' => $fileName,
                        'original_name' => $file->getClientName(),
                        'file_type' => $this->getFileType($file->getClientMimeType()),
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'file_path' => $uploadPath . $fileName,
                        'sort_order' => $this->getNextSortOrder($pageId)
                    ];

                    // Process images (create thumbnails, get dimensions)
                    if (strpos($file->getClientMimeType(), 'image/') === 0) {
                        $fileInfo = $this->processImage($uploadPath . $fileName, $fileInfo);
                    }

                    $fileId = $this->insert($fileInfo);
                    if ($fileId) {
                        $fileInfo['id'] = $fileId;
                        $fileInfo['url'] = $this->getFileUrl($fileInfo);
                        $uploadedFiles[] = $fileInfo;
                    }

                } catch (\Exception $e) {
                    log_message('error', 'Error processing file upload: ' . $e->getMessage());
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Get file type category
     */
    private function getFileType(string $mimeType): string
    {
        if (strpos($mimeType, 'image/') === 0) {
            return 'image';
        } elseif (strpos($mimeType, 'video/') === 0) {
            return 'video';
        } elseif (strpos($mimeType, 'audio/') === 0) {
            return 'audio';
        } elseif (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ])) {
            return 'document';
        } else {
            return 'other';
        }
    }

    /**
     * Process image files
     */
    private function processImage(string $filePath, array $fileInfo): array
    {
        try {
            $image = \Config\Services::image();
            
            // Get image dimensions
            $imageInfo = getimagesize($filePath);
            if ($imageInfo) {
                $fileInfo['width'] = $imageInfo[0];
                $fileInfo['height'] = $imageInfo[1];
            }

            // Create thumbnail (300x300)
            $thumbnailPath = dirname($filePath) . '/thumb_' . basename($filePath);
            $image->withFile($filePath)
                  ->resize(300, 300, true, 'center')
                  ->save($thumbnailPath);
            
            $fileInfo['thumbnail_path'] = $thumbnailPath;

            // Create medium size (800px width)
            $mediumPath = dirname($filePath) . '/medium_' . basename($filePath);
            $image->withFile($filePath)
                  ->resize(800, 600, true, 'center')
                  ->save($mediumPath);
            
            $fileInfo['medium_path'] = $mediumPath;

        } catch (\Exception $e) {
            log_message('error', 'Error processing image: ' . $e->getMessage());
        }

        return $fileInfo;
    }

    /**
     * Get next sort order for a page
     */
    private function getNextSortOrder(int $pageId): int
    {
        $maxOrder = $this->where('page_id', $pageId)->selectMax('sort_order')->first();
        return ($maxOrder['sort_order'] ?? 0) + 1;
    }

    /**
     * Get file URL
     */
    public function getFileUrl(array $file): string
    {
        $relativePath = str_replace(WRITEPATH, '', $file['file_path']);
        return base_url('writable' . $relativePath);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrl(array $file): string
    {
        if (isset($file['thumbnail_path'])) {
            $relativePath = str_replace(WRITEPATH, '', $file['thumbnail_path']);
            return base_url('writable' . $relativePath);
        }
        return $this->getFileUrl($file);
    }

    /**
     * Get medium size URL
     */
    public function getMediumUrl(array $file): string
    {
        if (isset($file['medium_path'])) {
            $relativePath = str_replace(WRITEPATH, '', $file['medium_path']);
            return base_url('writable' . $relativePath);
        }
        return $this->getFileUrl($file);
    }

    /**
     * Get files by type
     */
    public function getFilesByType(int $pageId, string $type)
    {
        return $this->where('page_id', $pageId)
                   ->where('file_type', $type)
                   ->orderBy('sort_order')
                   ->findAll();
    }

    /**
     * Get images for a page
     */
    public function getImages(int $pageId)
    {
        return $this->getFilesByType($pageId, 'image');
    }

    /**
     * Get videos for a page
     */
    public function getVideos(int $pageId)
    {
        return $this->getFilesByType($pageId, 'video');
    }

    /**
     * Get documents for a page
     */
    public function getDocuments(int $pageId)
    {
        return $this->getFilesByType($pageId, 'document');
    }

    /**
     * Update sort order
     */
    public function updateSortOrder(array $fileIds)
    {
        foreach ($fileIds as $index => $fileId) {
            $this->update($fileId, ['sort_order' => $index + 1]);
        }
        return true;
    }

    /**
     * Get file statistics
     */
    public function getFileStats(int $pageId)
    {
        $stats = $this->select('file_type, COUNT(*) as count, SUM(file_size) as total_size')
                     ->where('page_id', $pageId)
                     ->groupBy('file_type')
                     ->findAll();

        $result = [
            'total_files' => 0,
            'total_size' => 0,
            'by_type' => []
        ];

        foreach ($stats as $stat) {
            $result['total_files'] += $stat['count'];
            $result['total_size'] += $stat['total_size'];
            $result['by_type'][$stat['file_type']] = [
                'count' => $stat['count'],
                'size' => $stat['total_size']
            ];
        }

        return $result;
    }

    /**
     * Generate secure filename
     */
    private function generateSecureFileName(string $extension): string
    {
        // Generate cryptographically secure random name
        $randomName = bin2hex(random_bytes(16));
        return $randomName . '.' . $extension;
    }

    /**
     * Validate file content matches MIME type
     */
    private function validateFileContent(string $filePath, string $expectedMimeType): bool
    {
        // Use finfo to get actual MIME type
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $actualMimeType = finfo_file($finfo, $filePath);
            finfo_close($finfo);

            // Check if actual MIME type matches expected
            if ($actualMimeType !== $expectedMimeType) {
                // Allow some common variations
                $allowedVariations = [
                    'image/jpg' => 'image/jpeg',
                    'image/jpeg' => 'image/jpg',
                ];

                if (isset($allowedVariations[$expectedMimeType]) && 
                    $actualMimeType === $allowedVariations[$expectedMimeType]) {
                    return true;
                }

                return false;
            }
        }

        return true;
    }

    /**
     * Scan file for malicious content
     */
    private function scanFileContent(string $filePath): bool
    {
        // Basic malicious pattern detection
        $maliciousPatterns = [
            '/<\?php/i',
            '/<script/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onerror=/i',
            '/eval\(/i',
            '/base64_decode/i',
            '/shell_exec/i',
            '/system\(/i',
            '/exec\(/i',
            '/passthru/i'
        ];

        $content = file_get_contents($filePath, false, null, 0, 8192); // Read first 8KB
        
        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return false;
            }
        }

        return true;
    }
}
