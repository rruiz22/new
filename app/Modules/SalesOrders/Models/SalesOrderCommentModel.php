<?php

namespace Modules\SalesOrders\Models;

use CodeIgniter\Model;

class SalesOrderCommentModel extends Model
{
    protected $table = 'sales_orders_comments';
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
     * Get comments for a specific sales order with user information
     */
    public function getCommentsWithUsers($orderId, $limit = 10, $offset = 0)
    {
        $comments = $this->select('
                sales_orders_comments.*,
                users.first_name,
                users.last_name,
                users.username,
                users.avatar,
                users.avatar_style,
                users.id as user_id
            ')
            ->join('users', 'users.id = sales_orders_comments.created_by', 'left')
            ->where('sales_orders_comments.order_id', $orderId)
            ->where('sales_orders_comments.parent_id IS NULL') // Only get parent comments
            ->orderBy('sales_orders_comments.created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();
        
        // Process attachments for each comment
        foreach ($comments as &$comment) {
            $comment['attachments'] = $this->processAttachmentsJson($comment['attachments'], $orderId);
        }
        
        return $comments;
    }

    /**
     * Get replies for a specific comment
     */
    public function getRepliesForComment($commentId)
    {
        $replies = $this->select('
                sales_orders_comments.*,
                users.first_name,
                users.last_name,
                users.username,
                users.avatar,
                users.avatar_style,
                users.id as user_id
            ')
            ->join('users', 'users.id = sales_orders_comments.created_by', 'left')
            ->where('sales_orders_comments.parent_id', $commentId)
            ->orderBy('sales_orders_comments.created_at', 'ASC') // Replies in chronological order
            ->findAll();
        
        // Process attachments for each reply
        foreach ($replies as &$reply) {
            $reply['attachments'] = $this->processAttachmentsJson($reply['attachments'], $reply['order_id']);
        }
        
        return $replies;
    }

    /**
     * Get comments with their replies for a sales order
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
     * Get total count of parent comments for a sales order (excluding replies)
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
                sales_orders_comments.*,
                users.first_name,
                users.last_name,
                users.username,
                users.avatar,
                users.avatar_style,
                users.id as user_id
            ')
            ->join('users', 'users.id = sales_orders_comments.created_by', 'left')
            ->where('sales_orders_comments.id', $commentId)
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
        $uploadPath = FCPATH . 'uploads/sales_orders/' . $orderId . '/comments/';
        
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
                        'url' => base_url('uploads/sales_orders/' . $orderId . '/comments/' . $fileName)
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
            
            $image = \Config\Services::image();
            
            // Create thumbnail
            $thumbnailPath = FCPATH . 'uploads/sales_orders/' . $orderId . '/comments/thumbnails/';
            if (!is_dir($thumbnailPath)) {
                mkdir($thumbnailPath, 0755, true);
            }
            
            $thumbnailName = 'thumb_' . $fileInfo['filename'];
            $image->withFile($filePath)
                  ->resize(150, 150, true, 'center')
                  ->save($thumbnailPath . $thumbnailName);
            
            $fileInfo['thumbnail'] = base_url('uploads/sales_orders/' . $orderId . '/comments/thumbnails/' . $thumbnailName);
            
        } catch (\Exception $e) {
            log_message('error', 'Error creating thumbnail: ' . $e->getMessage());
            $fileInfo['has_thumbnail'] = false;
        }
        
        return $fileInfo;
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
                $attachment['url'] = base_url('uploads/sales_orders/' . $orderId . '/comments/' . $attachment['filename']);
                
                // Add thumbnail URL if it exists
                if (isset($attachment['thumbnail'])) {
                    // Thumbnail URL is already set correctly
                }
            }
        }

        return $attachments;
    }

    /**
     * Get comments for a specific order (legacy method for backward compatibility)
     */
    public function getCommentsByOrderId($orderId, $limit = 10, $offset = 0)
    {
        return $this->getCommentsWithReplies($orderId, $limit, $offset);
    }


} 