<?php

namespace Modules\ReconOrders\Models;

use CodeIgniter\Model;

class ReconCommentModel extends Model
{
    protected $table = 'recon_comments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id', 'user_id', 'parent_id', 'comment', 'mentions', 'attachments', 'metadata', 'is_edited'
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
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'order_id' => 'required|integer',
        'user_id' => 'required|integer',
        'comment' => 'required|string|max_length[5000]'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'Order ID is required'
        ],
        'user_id' => [
            'required' => 'User ID is required'
        ],
        'comment' => [
            'required' => 'Comment is required'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['comment'])) {
            $data['data']['is_edited'] = 1;
        }
        return $data;
    }

    /**
     * Get comments for a specific recon order with user information
     */
    public function getCommentsWithUsers($orderId, $limit = 10, $offset = 0)
    {
        $comments = $this->select('
                recon_comments.*,
                recon_comments.comment as description,
                recon_comments.user_id as created_by,
                users.first_name,
                users.last_name,
                users.username,
                users.avatar,
                users.avatar_style,
                users.id as user_id
            ')
            ->join('users', 'users.id = recon_comments.user_id', 'left')
            ->where('recon_comments.order_id', $orderId)
            ->where('recon_comments.parent_id IS NULL') // Only get parent comments
            ->orderBy('recon_comments.created_at', 'DESC')
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
                recon_comments.*,
                recon_comments.comment as description,
                recon_comments.user_id as created_by,
                users.first_name,
                users.last_name,
                users.username,
                users.avatar,
                users.avatar_style,
                users.id as user_id
            ')
            ->join('users', 'users.id = recon_comments.user_id', 'left')
            ->where('recon_comments.parent_id', $commentId)
            ->orderBy('recon_comments.created_at', 'ASC') // Replies in chronological order
            ->findAll();
        
        // Process attachments for each reply
        foreach ($replies as &$reply) {
            $reply['attachments'] = $this->processAttachmentsJson($reply['attachments'], $reply['order_id']);
        }
        
        return $replies;
    }

    /**
     * Get comments with their replies for a recon order
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
     * Get total count of parent comments for a recon order (excluding replies)
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
                recon_comments.*,
                recon_comments.comment as description,
                recon_comments.user_id as created_by,
                users.first_name,
                users.last_name,
                users.username,
                users.avatar,
                users.avatar_style,
                users.id as user_id
            ')
            ->join('users', 'users.id = recon_comments.user_id', 'left')
            ->where('recon_comments.id', $commentId)
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
        $uploadPath = FCPATH . 'uploads/recon_orders/' . $orderId . '/comments/';
        
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
                        'url' => base_url('uploads/recon_orders/' . $orderId . '/comments/' . $fileName)
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
    public function processImage($imagePath, $fileInfo, $orderId)
    {
        try {
            $thumbnailPath = FCPATH . 'uploads/recon_orders/' . $orderId . '/comments/thumbnails/';
            
            if (!is_dir($thumbnailPath)) {
                mkdir($thumbnailPath, 0755, true);
            }
            
            $thumbnailName = 'thumb_' . $fileInfo['filename'];
            $thumbnailFullPath = $thumbnailPath . $thumbnailName;
            
            // Create thumbnail using CodeIgniter's image library
            $image = \Config\Services::image();
            $image->withFile($imagePath)
                  ->resize(150, 150, true, 'center')
                  ->save($thumbnailFullPath);
            
            $fileInfo['thumbnail'] = base_url('uploads/recon_orders/' . $orderId . '/comments/thumbnails/' . $thumbnailName);
        } catch (\Exception $e) {
            log_message('error', 'Error creating thumbnail: ' . $e->getMessage());
            // Continue without thumbnail
        }
        
        return $fileInfo;
    }

    /**
     * Get file type based on MIME type
     */
    public function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain'
        ])) {
            return 'document';
        } else {
            return 'other';
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
                $attachment['url'] = base_url('uploads/recon_orders/' . $orderId . '/comments/' . $attachment['filename']);
                
                // Add thumbnail URL if it exists
                if (isset($attachment['thumbnail'])) {
                    // Thumbnail URL is already set correctly
                }
            }
        }

        return $attachments;
    }

    // Legacy methods for backward compatibility
    public function getCommentsForOrder($orderId, $limit = 50)
    {
        return $this->getCommentsWithReplies($orderId, $limit, 0);
    }

    public function addComment($orderId, $userId, $comment, $mentions = null, $attachments = null)
    {
        $data = [
            'order_id' => $orderId,
            'user_id' => $userId,
            'comment' => $comment,
            'mentions' => $mentions ? json_encode($mentions) : json_encode([]),
            'attachments' => $attachments ? json_encode($attachments) : json_encode([]),
            'metadata' => json_encode([])
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
            'comment' => $comment,
            'mentions' => $mentions ? json_encode($mentions) : json_encode([]),
            'attachments' => $attachments ? json_encode($attachments) : json_encode([]),
            'metadata' => json_encode([])
        ];

        return $this->insert($data);
    }

    public function updateComment($commentId, $comment, $mentions = null, $attachments = null)
    {
        $data = [
            'comment' => $comment,
            'mentions' => $mentions ? json_encode($mentions) : null,
            'attachments' => $attachments ? json_encode($attachments) : null,
            'is_edited' => 1
        ];

        return $this->update($commentId, $data);
    }

    public function getCommentCount($orderId)
    {
        return $this->getCommentsCount($orderId);
    }
} 