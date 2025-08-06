<?php

namespace Modules\CarWash\Models;

use CodeIgniter\Model;

class CarWashCommentModel extends Model
{
    protected $table = 'car_wash_comments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id', 'user_id', 'parent_id', 'description', 'mentions', 'attachments', 'metadata', 'is_edited'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|integer',
        'user_id' => 'required|integer',
        'description' => 'required'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'Order ID is required'
        ],
        'user_id' => [
            'required' => 'User ID is required'
        ],
        'description' => [
            'required' => 'Comment is required'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['description'])) {
            $data['data']['is_edited'] = 1;
        }
        return $data;
    }

    public function getCommentsForOrder($orderId, $limit = 50)
    {
        return $this->select('
            car_wash_comments.*,
            users.first_name,
            users.last_name,
            users.username,
            users.avatar,
            users.avatar_style,
            users.id as user_id,
            (SELECT auth_identities.secret 
             FROM auth_identities 
             WHERE auth_identities.user_id = users.id 
             AND auth_identities.type = "email_password" 
             LIMIT 1) as email
        ')
        ->join('users', 'users.id = car_wash_comments.user_id', 'left')
        ->where('car_wash_comments.order_id', $orderId)
        ->where('car_wash_comments.parent_id', null)
        ->orderBy('car_wash_comments.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }

    public function getRepliesForComment($commentId)
    {
        return $this->select('
            car_wash_comments.*,
            users.first_name,
            users.last_name,
            users.username,
            users.avatar,
            users.avatar_style,
            users.id as user_id,
            (SELECT auth_identities.secret 
             FROM auth_identities 
             WHERE auth_identities.user_id = users.id 
             AND auth_identities.type = "email_password" 
             LIMIT 1) as email
        ')
        ->join('users', 'users.id = car_wash_comments.user_id', 'left')
        ->where('car_wash_comments.parent_id', $commentId)
        ->orderBy('car_wash_comments.created_at', 'DESC')
        ->findAll();
    }

    public function getCommentsWithReplies($orderId, $limit = 50)
    {
        $comments = $this->getCommentsForOrder($orderId, $limit);
        
        foreach ($comments as &$comment) {
            $comment['replies'] = $this->getRepliesForComment($comment['id']);
        }
        
        return $comments;
    }

    public function addComment($orderId, $userId, $comment, $mentions = null, $attachments = null)
    {
        $data = [
            'order_id' => $orderId,
            'user_id' => $userId,
            'description' => $comment,
            'mentions' => $mentions ? json_encode($mentions) : null,
            'attachments' => $attachments ? json_encode($attachments) : null
        ];

        return $this->insert($data);
    }

    public function addReply($commentId, $userId, $comment, $mentions = null, $attachments = null)
    {
        // Get the parent comment to get the order_id
        $parentComment = $this->find($commentId);
        if (!$parentComment) {
            return false;
        }

        $data = [
            'order_id' => $parentComment['order_id'],
            'user_id' => $userId,
            'parent_id' => $commentId,
            'description' => $comment,
            'mentions' => $mentions ? json_encode($mentions) : null,
            'attachments' => $attachments ? json_encode($attachments) : null
        ];

        return $this->insert($data);
    }

    public function updateComment($commentId, $comment, $mentions = null, $attachments = null)
    {
        $data = [
            'description' => $comment,
            'mentions' => $mentions ? json_encode($mentions) : null,
            'attachments' => $attachments ? json_encode($attachments) : null,
            'is_edited' => 1
        ];

        return $this->update($commentId, $data);
    }

    public function getCommentWithUser($commentId)
    {
        return $this->select('
            car_wash_comments.*,
            users.first_name,
            users.last_name,
            users.username,
            users.avatar,
            users.avatar_style,
            users.id as user_id,
            (SELECT auth_identities.secret 
             FROM auth_identities 
             WHERE auth_identities.user_id = users.id 
             AND auth_identities.type = "email_password" 
             LIMIT 1) as email
        ')
        ->join('users', 'users.id = car_wash_comments.user_id', 'left')
        ->where('car_wash_comments.id', $commentId)
        ->first();
    }

    public function getRecentComments($limit = 10)
    {
        return $this->select('
            car_wash_comments.*,
            users.first_name,
            users.last_name,
            car_wash_orders.order_number,
            clients.name as client_name
        ')
        ->join('users', 'users.id = car_wash_comments.user_id', 'left')
        ->join('car_wash_orders', 'car_wash_orders.id = car_wash_comments.order_id', 'left')
        ->join('clients', 'clients.id = car_wash_orders.client_id', 'left')
        ->orderBy('car_wash_comments.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }

    public function getCommentCount($orderId)
    {
        return $this->where('order_id', $orderId)->countAllResults();
    }

    public function getMentionedUsers($orderId)
    {
        $comments = $this->select('mentions')
                         ->where('order_id', $orderId)
                         ->where('mentions IS NOT NULL')
                         ->findAll();

        $mentionedUsers = [];
        foreach ($comments as $comment) {
            if ($comment['mentions']) {
                $mentions = json_decode($comment['mentions'], true);
                if (is_array($mentions)) {
                    $mentionedUsers = array_merge($mentionedUsers, $mentions);
                }
            }
        }

        return array_unique($mentionedUsers);
    }

    /**
     * Process file attachments
     */
    public function processAttachments($files, $orderId)
    {
        $attachments = [];
        $uploadPath = ROOTPATH . 'public/uploads/car_wash/' . $orderId . '/comments/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                try {
                    // Generate unique filename
                    $fileName = $file->getRandomName();
                    $file->move($uploadPath, $fileName);
                    
                    $fileInfo = [
                        'original_name' => $file->getClientName(),
                        'filename' => $fileName,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getClientMimeType(),
                        'type' => $this->getFileType($file->getClientMimeType()),
                        'uploaded_at' => date('Y-m-d H:i:s'),
                        'url' => base_url('car_wash/file/' . $orderId . '/comments/' . $fileName)
                    ];
                    
                    // Process images to create thumbnails
                    if (str_starts_with($file->getClientMimeType(), 'image/')) {
                        $fileInfo = $this->processImage($uploadPath . $fileName, $fileInfo, $orderId);
                    }

                    $attachments[] = $fileInfo;
                    
                } catch (\Exception $e) {
                    log_message('error', 'Error uploading file: ' . $e->getMessage());
                }
            }
        }
        
        log_message('debug', 'CarWash processAttachmentsJson - Final processed attachments: ' . json_encode($attachments));
        return $attachments;
    }

    /**
     * Process image files to create thumbnails
     */
    private function processImage($filePath, $fileInfo, $orderId)
    {
        try {
            // Check if GD extension is available
            if (!extension_loaded('gd')) {
                log_message('warning', 'GD extension not available, skipping thumbnail creation');
                $fileInfo['has_thumbnail'] = false;
                return $fileInfo;
            }

            // Optimize image with TinyPNG if available
            $optimizedPath = $this->optimizeImageWithTinyPNG($filePath, $fileInfo);
            
            $image = \Config\Services::image();
            
            // Create thumbnail
            $thumbnailPath = ROOTPATH . 'public/uploads/car_wash/' . $orderId . '/comments/thumbnails/';
            if (!is_dir($thumbnailPath)) {
                mkdir($thumbnailPath, 0755, true);
            }
            
            $thumbnailName = 'thumb_' . $fileInfo['filename'];
            
            // Use optimized image if available, otherwise use original
            $sourceImage = $optimizedPath ?: $filePath;
            
            $image->withFile($sourceImage)
                  ->resize(150, 150, true, 'center')
                  ->save($thumbnailPath . $thumbnailName);
            
                            $fileInfo['thumbnail'] = base_url('car_wash/file/' . $orderId . '/comments/thumbnails/' . $thumbnailName);
            $fileInfo['optimized'] = !empty($optimizedPath);
            
        } catch (\Exception $e) {
            log_message('error', 'Error creating thumbnail: ' . $e->getMessage());
            $fileInfo['has_thumbnail'] = false;
        }
        
        return $fileInfo;
    }

    /**
     * Optimize image using TinyPNG API
     */
    private function optimizeImageWithTinyPNG($filePath, $fileInfo)
    {
        try {
            // Check if this is an image that TinyPNG supports
            $supportedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($fileInfo['mime_type'], $supportedTypes)) {
                log_message('info', 'TinyPNG: File type not supported: ' . $fileInfo['mime_type']);
                return null;
            }

            // Get TinyPNG configuration
            $integrationModel = new \App\Models\IntegrationModel();
            $tinypngConfig = $integrationModel->getServiceConfiguration('tinypng');
            
            if (empty($tinypngConfig['tinypng_api_key']['value'])) {
                log_message('info', 'TinyPNG: API key not configured');
                return null;
            }

            $apiKey = $tinypngConfig['tinypng_api_key']['value'];
            
            // Check file size limit (TinyPNG has a 5MB limit)
            $maxSize = isset($tinypngConfig['max_file_size']['value']) ? 
                      $tinypngConfig['max_file_size']['value'] * 1024 * 1024 : 
                      5 * 1024 * 1024; // 5MB default
            
            if ($fileInfo['size'] > $maxSize) {
                log_message('info', 'TinyPNG: File too large: ' . $fileInfo['size'] . ' bytes');
                return null;
            }

            // Check if auto-optimization is enabled
            $autoOptimize = isset($tinypngConfig['auto_optimize']['value']) ? 
                           $tinypngConfig['auto_optimize']['value'] === '1' : true;
            
            if (!$autoOptimize) {
                log_message('info', 'TinyPNG: Auto-optimization disabled');
                return null;
            }

            log_message('info', 'TinyPNG: Starting optimization for: ' . $fileInfo['original_name']);

            // Make API request to TinyPNG
            $imageData = file_get_contents($filePath);
            
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.tinify.com/shrink',
                CURLOPT_USERPWD => 'api:' . $apiKey,
                CURLOPT_POSTFIELDS => $imageData,
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: ' . $fileInfo['mime_type']
                ]
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            
            if (curl_error($curl)) {
                log_message('error', 'TinyPNG: cURL error: ' . curl_error($curl));
                curl_close($curl);
                return null;
            }
            
            curl_close($curl);

            if ($httpCode !== 201) {
                $body = substr($response, $headerSize);
                log_message('error', 'TinyPNG: API error (HTTP ' . $httpCode . '): ' . $body);
                return null;
            }

            // Parse response headers to get optimized image URL
            $headers = substr($response, 0, $headerSize);
            preg_match('/Location:\s*(.+)/i', $headers, $matches);
            
            if (empty($matches[1])) {
                log_message('error', 'TinyPNG: No location header in response');
                return null;
            }

            $optimizedUrl = trim($matches[1]);

            // Download optimized image
            $optimizedData = file_get_contents($optimizedUrl);
            
            if ($optimizedData === false) {
                log_message('error', 'TinyPNG: Failed to download optimized image');
                return null;
            }

            // Save optimized image (replace original)
            $originalSize = $fileInfo['size'];
            $optimizedSize = strlen($optimizedData);
            
            if (file_put_contents($filePath, $optimizedData) === false) {
                log_message('error', 'TinyPNG: Failed to save optimized image');
                return null;
            }

            $compressionRatio = round((($originalSize - $optimizedSize) / $originalSize) * 100, 2);
            
            log_message('info', sprintf(
                'TinyPNG: Successfully optimized %s - Original: %d bytes, Optimized: %d bytes, Saved: %s%%',
                $fileInfo['original_name'],
                $originalSize,
                $optimizedSize,
                $compressionRatio
            ));

            return $filePath; // Return optimized file path
            
        } catch (\Exception $e) {
            log_message('error', 'TinyPNG: Optimization failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get file type based on MIME type
     */
    private function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (in_array($mimeType, ['application/pdf'])) {
            return 'pdf';
        } elseif (in_array($mimeType, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            return 'document';
        } else {
            return 'file';
        }
    }

    /**
     * Process mentions in comment text
     */
    public function processMentions($commentText)
    {
        $mentions = [];
        
        // Find all @username patterns
        preg_match_all('/@([a-zA-Z0-9_]+)/', $commentText, $matches);
        
        if (!empty($matches[1])) {
            $userModel = new \App\Models\UserModel();
            
            foreach ($matches[1] as $username) {
                // Try to find user by username
                $user = $userModel->where('username', $username)
                                 ->first();
                
                if ($user) {
                    $mentions[] = [
                        'user_id' => $user['id'],
                        'username' => $username,
                        'full_name' => trim($user['first_name'] . ' ' . $user['last_name'])
                    ];
                }
            }
        }
        
        return $mentions;
    }

    /**
     * Process attachments JSON field and add URLs
     */
    public function processAttachmentsJson($attachmentsJson, $orderId)
    {
        if (empty($attachmentsJson)) {
            return [];
        }

        // Handle both JSON strings and arrays
        if (is_string($attachmentsJson)) {
            $attachments = json_decode($attachmentsJson, true);
            if (!is_array($attachments) || json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }
        } elseif (is_array($attachmentsJson)) {
            $attachments = $attachmentsJson;
        } else {
            return [];
        }

        // Add URLs to attachments
        foreach ($attachments as &$attachment) {
            if (isset($attachment['filename'])) {
                $attachment['url'] = base_url('car_wash/file/' . $orderId . '/comments/' . $attachment['filename']);
                
                // Check if thumbnail exists and generate URL
                if (isset($attachment['thumbnail']) && !empty($attachment['thumbnail'])) {
                    // Extract thumbnail filename from original URL if it's a full URL
                    if (strpos($attachment['thumbnail'], 'http') === 0) {
                        $thumbnailName = basename($attachment['thumbnail']);
                    } else {
                        $thumbnailName = $attachment['thumbnail'];
                    }
                    
                    // Generate new thumbnail URL
                    $attachment['thumbnail'] = base_url('car_wash/file/' . $orderId . '/comments/thumbnails/' . $thumbnailName);
                    
                    // Verify thumbnail file exists
                    $thumbnailPath = ROOTPATH . 'public/uploads/car_wash/' . $orderId . '/comments/thumbnails/' . $thumbnailName;
                    
                    if (!file_exists($thumbnailPath)) {
                        // If thumbnail doesn't exist, remove thumbnail reference
                        unset($attachment['thumbnail']);
                    }
                }
            }
        }

        return $attachments;
    }

    public function hasAttachments($commentId)
    {
        $comment = $this->select('attachments')->find($commentId);
        return $comment && !empty($comment['attachments']);
    }

    public function getAttachments($commentId)
    {
        $comment = $this->select('attachments')->find($commentId);
        if ($comment && $comment['attachments']) {
            return json_decode($comment['attachments'], true);
        }
        return [];
    }
} 