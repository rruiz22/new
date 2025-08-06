<?php

namespace Modules\ServiceOrders\Models;

use CodeIgniter\Model;

class ServiceOrderCommentModel extends Model
{
    protected $table = 'service_orders_comments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id',
        'parent_id',
        'description',
        'created_by',
        'attachments',
        'mentions',
        'metadata',
        'created_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        // Temporarily disabled due to mixed data types in database
        // 'attachments' => 'json',
        // 'mentions' => 'json',
        // 'metadata' => 'json'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|integer',
        'description' => 'required|string|max_length[5000]',
        'created_by' => 'required|integer'
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
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get comments for a specific service order with user information
     */
    public function getCommentsWithUsers($orderId, $limit = 10, $offset = 0)
    {
        return $this->select('
                service_orders_comments.*,
                users.first_name,
                users.last_name,
                users.username,
                users.avatar,
                users.avatar_style,
                users.id as user_id
            ')
            ->join('users', 'users.id = service_orders_comments.created_by', 'left')
            ->where('service_orders_comments.order_id', $orderId)
            ->where('service_orders_comments.parent_id IS NULL') // Only get parent comments
            ->orderBy('service_orders_comments.created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();
    }

    /**
     * Get replies for a specific comment
     */
    public function getRepliesForComment($commentId)
    {
        return $this->select('
                service_orders_comments.*,
                users.first_name,
                users.last_name,
                users.username,
                users.avatar,
                users.avatar_style,
                users.id as user_id
            ')
            ->join('users', 'users.id = service_orders_comments.created_by', 'left')
            ->where('service_orders_comments.parent_id', $commentId)
            ->orderBy('service_orders_comments.created_at', 'ASC') // Replies in chronological order
            ->findAll();
    }

    /**
     * Get comments with their replies for a service order
     */
    public function getCommentsWithReplies($orderId, $limit = 10, $offset = 0)
    {
        // Get parent comments
        $parentComments = $this->getCommentsWithUsers($orderId, $limit, $offset);
        
        // Get replies for each parent comment
        foreach ($parentComments as &$comment) {
            $comment['replies'] = $this->getRepliesForComment($comment['id']);
        }
        
        return $parentComments;
    }

    /**
     * Get total count of parent comments for a service order (excluding replies)
     */
    public function getCommentsCount($orderId)
    {
        return $this->where('order_id', $orderId)
                   ->where('parent_id IS NULL')
                   ->countAllResults();
    }

    /**
     * Get a single comment with user information
     */
    public function getCommentWithUser($commentId)
    {
        return $this->select('
                service_orders_comments.*,
                users.first_name,
                users.last_name,
                users.username,
                users.avatar,
                users.avatar_style,
                users.id as user_id
            ')
            ->join('users', 'users.id = service_orders_comments.created_by', 'left')
            ->where('service_orders_comments.id', $commentId)
            ->first();
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
     * Process file attachments
     */
    public function processAttachments($files, $orderId)
    {
        $attachments = [];
        $uploadPath = WRITEPATH . 'uploads/service_orders/' . $orderId . '/comments/';
        
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
                        'url' => base_url('writable/uploads/service_orders/' . $orderId . '/comments/' . $fileName),
                        'type' => $this->getFileType($file->getClientMimeType()),
                        'uploaded_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Process images
                    if (strpos($file->getClientMimeType(), 'image/') === 0) {
                        $fileInfo = $this->processImage($uploadPath . $fileName, $fileInfo, $orderId);
    }

                    $attachments[] = $fileInfo;
                    
                } catch (\Exception $e) {
                    log_message('error', 'Error processing attachment: ' . $e->getMessage());
                }
            }
        }
        
        return $attachments;
    }

    /**
     * Process image files (create thumbnails, etc.)
     */
    private function processImage($filePath, $fileInfo, $orderId)
    {
        try {
            $image = \Config\Services::image();
            $thumbnailPath = WRITEPATH . 'uploads/service_orders/' . $orderId . '/comments/thumbnails/';
            
            if (!is_dir($thumbnailPath)) {
                mkdir($thumbnailPath, 0755, true);
            }
            
            // Create thumbnail (150x150)
            $thumbnailName = 'thumb_' . $fileInfo['filename'];
            $image->withFile($filePath)
                  ->resize(150, 150, true, 'center')
                  ->save($thumbnailPath . $thumbnailName);
            
            $fileInfo['thumbnail'] = base_url('writable/uploads/service_orders/' . $orderId . '/comments/thumbnails/' . $thumbnailName);
            
            // Create medium size (800x600)
            $mediumPath = WRITEPATH . 'uploads/service_orders/' . $orderId . '/comments/medium/';
            if (!is_dir($mediumPath)) {
                mkdir($mediumPath, 0755, true);
            }
            
            $mediumName = 'medium_' . $fileInfo['filename'];
            $image->withFile($filePath)
                  ->resize(800, 600, true, 'center')
                  ->save($mediumPath . $mediumName);
            
            $fileInfo['medium'] = base_url('writable/uploads/service_orders/' . $orderId . '/comments/medium/' . $mediumName);
            
        } catch (\Exception $e) {
            log_message('error', 'Error processing image: ' . $e->getMessage());
        }
        
        return $fileInfo;
    }

    /**
     * Determine file type based on MIME type
     */
    private function getFileType($mimeType)
    {
        if (strpos($mimeType, 'image/') === 0) {
            return 'image';
        } elseif (strpos($mimeType, 'video/') === 0) {
            return 'video';
        } elseif (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ])) {
            return 'document';
        }
        
        return 'other';
    }

    /**
     * Get comments by order ID (legacy method for compatibility)
     */
    public function getCommentsByOrderId($orderId, $limit = 10, $offset = 0)
    {
        return $this->getCommentsWithUsers($orderId, $limit, $offset);
    }
} 