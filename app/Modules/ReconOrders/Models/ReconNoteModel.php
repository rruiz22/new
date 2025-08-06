<?php

namespace Modules\ReconOrders\Models;

use CodeIgniter\Model;

class ReconNoteModel extends Model
{
    protected $table = 'recon_notes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'order_id', 'user_id', 'parent_id', 'content', 'mentions', 'attachments', 'metadata', 'is_edited'
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
        'content' => 'required'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'Order ID is required'
        ],
        'user_id' => [
            'required' => 'User ID is required'
        ],
        'content' => [
            'required' => 'Note content is required'
        ]
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['content'])) {
            $data['data']['is_edited'] = 1;
        }
        return $data;
    }

    public function getNotesForOrder($orderId, $limit = 50)
    {
        $notes = $this->select('
            recon_notes.*,
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
        ->join('users', 'users.id = recon_notes.user_id', 'left')
        ->where('recon_notes.order_id', $orderId)
        ->where('recon_notes.parent_id IS NULL')
        ->orderBy('recon_notes.created_at', 'DESC')
        ->limit($limit)
        ->findAll();

        // Load replies for each note
        foreach ($notes as &$note) {
            $note['mentions'] = !empty($note['mentions']) ? json_decode($note['mentions'], true) : [];
            $note['attachments'] = $this->processAttachmentsJson($note['attachments'], $orderId);
            
            // Create author name from available fields
            $firstName = $note['first_name'] ?? '';
            $lastName = $note['last_name'] ?? '';
            $username = $note['username'] ?? '';
            
            if (!empty($firstName) || !empty($lastName)) {
                $note['author_name'] = trim($firstName . ' ' . $lastName);
            } else {
                $note['author_name'] = $username ?: 'Unknown User';
            }

            // Map user_id to author_id for frontend compatibility
            $note['author_id'] = $note['user_id'];

            // Load replies for this note
            $replies = $this->getRepliesForNote($note['id']);
            $note['replies'] = is_array($replies) ? $replies : [];
        }

        return $notes;
    }

    /**
     * Get replies for a specific note
     */
    public function getRepliesForNote($noteId)
    {
        $builder = $this->db->table($this->table . ' n')
            ->select('n.*, u.first_name, u.last_name, u.username, u.avatar, 
                      (SELECT ai.secret FROM auth_identities ai 
                       WHERE ai.user_id = u.id AND ai.type = "email_password" 
                       LIMIT 1) as email')
            ->join('users u', 'n.user_id = u.id', 'left')
            ->where('n.parent_id', $noteId)
            ->where('n.deleted_at IS NULL')
            ->groupBy('n.id') // Group by note ID to avoid duplicates
            ->orderBy('n.created_at', 'ASC');

        $results = $builder->get()->getResultArray();

        // Ensure we always return an array
        if (!is_array($results)) {
            return [];
        }

        // Process replies
        foreach ($results as &$reply) {
            $reply['mentions'] = !empty($reply['mentions']) ? json_decode($reply['mentions'], true) : [];
            // Get order_id from parent note for attachment processing
            $parentNote = $this->find($reply['parent_id']);
            $orderId = $parentNote ? $parentNote['order_id'] : 0;
            $reply['attachments'] = $this->processAttachmentsJson($reply['attachments'], $orderId);
            
            // Create author name from available fields
            $firstName = $reply['first_name'] ?? '';
            $lastName = $reply['last_name'] ?? '';
            $username = $reply['username'] ?? '';
            
            if (!empty($firstName) || !empty($lastName)) {
                $reply['author_name'] = trim($firstName . ' ' . $lastName);
            } else {
                $reply['author_name'] = $username ?: 'Unknown User';
            }

            // Map fields for frontend compatibility
            $reply['author_id'] = $reply['user_id'];
            $reply['note'] = $reply['content'];
        }

        return $results;
    }

    public function getNoteCount($orderId)
    {
        return $this->where('order_id', $orderId)->countAllResults();
    }

    /**
     * Get notes for order with pagination and filters
     */
    public function getNotesForOrderPaginated($orderId, $limit = 5, $offset = 0, $search = '', $author = '')
    {
        $builder = $this->select('
            recon_notes.*,
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
        ->join('users', 'users.id = recon_notes.user_id', 'left')
        ->where('recon_notes.order_id', $orderId)
        ->where('recon_notes.parent_id IS NULL');

        // Apply search filter
        if (!empty($search)) {
            $builder->like('recon_notes.content', $search);
        }

        // Apply author filter
        if (!empty($author)) {
            $builder->where('recon_notes.user_id', $author);
        }

        $notes = $builder->orderBy('recon_notes.created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();

        // Load replies for each note
        foreach ($notes as &$note) {
            $note['mentions'] = !empty($note['mentions']) ? json_decode($note['mentions'], true) : [];
            $note['attachments'] = $this->processAttachmentsJson($note['attachments'], $orderId);
            
            // Create author name from available fields
            $firstName = $note['first_name'] ?? '';
            $lastName = $note['last_name'] ?? '';
            $username = $note['username'] ?? '';
            
            if (!empty($firstName) || !empty($lastName)) {
                $note['author_name'] = trim($firstName . ' ' . $lastName);
            } else {
                $note['author_name'] = $username ?: 'Unknown User';
            }

            // Map user_id to author_id for frontend compatibility
            $note['author_id'] = $note['user_id'];

            // Load replies for this note
            $replies = $this->getRepliesForNote($note['id']);
            $note['replies'] = is_array($replies) ? $replies : [];
        }

        return $notes;
    }

    /**
     * Get total count of notes with filters applied
     */
    public function getNoteCountWithFilters($orderId, $search = '', $author = '')
    {
        $builder = $this->where('order_id', $orderId)
            ->where('parent_id IS NULL');

        // Apply search filter
        if (!empty($search)) {
            $builder->like('content', $search);
        }

        // Apply author filter
        if (!empty($author)) {
            $builder->where('user_id', $author);
        }

        return $builder->countAllResults();
    }

    public function addNote($orderId, $userId, $content, $mentions = null, $attachments = null)
    {
        $data = [
            'order_id' => $orderId,
            'user_id' => $userId,
            'content' => $content,
            'mentions' => $mentions ? json_encode($mentions) : null,
            'attachments' => $attachments ? json_encode($attachments) : null
        ];

        return $this->insert($data);
    }

    /**
     * Add a reply to an existing note
     */
    public function addReply($noteId, $userId, $content, $mentions = null, $attachments = null)
    {
        // Get the parent note to get the order_id
        $parentNote = $this->find($noteId);
        if (!$parentNote) {
            return false;
        }

        $data = [
            'order_id' => $parentNote['order_id'],
            'user_id' => $userId,
            'parent_id' => $noteId,
            'content' => $content,
            'mentions' => $mentions ? json_encode($mentions) : json_encode([]),
            'attachments' => $attachments ? json_encode($attachments) : json_encode([])
        ];

        return $this->insert($data);
    }

    /**
     * Process file attachments for notes
     */
    public function processAttachments($files, $orderId)
    {
        $attachments = [];
        $uploadPath = FCPATH . 'uploads/recon_orders/' . $orderId . '/notes/';
        
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
                        'url' => base_url('uploads/recon_orders/' . $orderId . '/notes/' . $fileName)
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
            $thumbnailPath = FCPATH . 'uploads/recon_orders/' . $orderId . '/notes/thumbnails/';
            
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
            
            $fileInfo['thumbnail'] = base_url('uploads/recon_orders/' . $orderId . '/notes/thumbnails/' . $thumbnailName);
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
                $attachment['url'] = base_url('uploads/recon_orders/' . $orderId . '/notes/' . $attachment['filename']);
                
                // Add thumbnail URL if it exists
                if (isset($attachment['thumbnail'])) {
                    // Thumbnail URL is already set correctly
                }
            }
        }

        return $attachments;
    }
} 