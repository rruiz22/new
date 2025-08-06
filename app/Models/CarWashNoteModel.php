<?php

namespace App\Models;

use CodeIgniter\Model;

class CarWashNoteModel extends Model
{
    protected $table = 'car_wash_notes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'car_wash_order_id',
        'author_id', 
        'content',
        'mentions',
        'attachments',
        'parent_note_id'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'car_wash_order_id' => 'required|integer',
        'author_id' => 'required|integer|is_not_unique[users.id]',
        'content' => 'required|min_length[3]|max_length[5000]'
    ];

    protected $validationMessages = [
        'car_wash_order_id' => [
            'required' => 'Car Wash Order ID is required'
        ],
        'author_id' => [
            'required' => 'Author ID is required',
            'is_not_unique' => 'Author does not exist'
        ],
        'content' => [
            'required' => 'Note content is required',
            'min_length' => 'Note must be at least 3 characters',
            'max_length' => 'Note cannot exceed 5000 characters'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $afterInsert = ['afterInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $afterUpdate = ['afterUpdate'];
    protected $beforeFind = [];
    protected $afterFind = ['afterFind'];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get notes for a specific car wash order with author information
     */
    public function getOrderNotes(int $carWashOrderId, array $filters = []): array
    {
        $builder = $this->db->table($this->table . ' n')
            ->select('n.*, u.first_name, u.last_name, u.username, u.avatar, 
                      (SELECT ai.secret FROM auth_identities ai 
                       WHERE ai.user_id = u.id AND ai.type = "email_password" 
                       LIMIT 1) as email')
            ->join('users u', 'n.author_id = u.id', 'left')
            ->where('n.car_wash_order_id', $carWashOrderId)
            ->where('n.deleted_at IS NULL')
            ->where('n.parent_note_id IS NULL') // Only get main notes, not replies
            ->groupBy('n.id') // Group by note ID to avoid duplicates
            ->orderBy('n.created_at', 'DESC');

        // Apply filters
        if (!empty($filters['author_id'])) {
            $builder->where('n.author_id', $filters['author_id']);
        }

        if (!empty($filters['search'])) {
            $builder->like('n.content', $filters['search']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('n.created_at >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('n.created_at <=', $filters['date_to']);
        }

        // Apply pagination if provided
        if (isset($filters['limit'])) {
            $builder->limit($filters['limit'], $filters['offset'] ?? 0);
        }

        $results = $builder->get()->getResultArray();

        // Process mentions and attachments JSON fields and load replies
        foreach ($results as &$note) {
            $note['mentions'] = !empty($note['mentions']) ? json_decode($note['mentions'], true) : [];
            $note['attachments'] = $this->processAttachments($note['attachments'] ?? '');
            
            // Create author name from available fields
            $firstName = $note['first_name'] ?? '';
            $lastName = $note['last_name'] ?? '';
            $username = $note['username'] ?? '';
            
            if (!empty($firstName) || !empty($lastName)) {
                $note['author_name'] = trim($firstName . ' ' . $lastName);
            } else {
                $note['author_name'] = $username ?: 'Unknown User';
            }

            // Load replies for this note
            $note['replies'] = $this->getRepliesForNote($note['id']);
        }

        return $results;
    }

    /**
     * Get total count of notes for pagination
     */
    public function getOrderNotesCount(int $carWashOrderId, array $filters = []): int
    {
        $builder = $this->db->table($this->table . ' n')
            ->where('n.car_wash_order_id', $carWashOrderId)
            ->where('n.deleted_at IS NULL')
            ->where('n.parent_note_id IS NULL'); // Only count main notes, not replies

        // Apply same filters as getOrderNotes (except pagination)
        if (!empty($filters['author_id'])) {
            $builder->where('n.author_id', $filters['author_id']);
        }

        if (!empty($filters['search'])) {
            $builder->like('n.content', $filters['search']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('n.created_at >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('n.created_at <=', $filters['date_to']);
        }

        return $builder->countAllResults();
    }

    /**
     * Get replies for a specific note
     */
    public function getRepliesForNote(int $noteId): array
    {
        $builder = $this->db->table($this->table . ' n')
            ->select('n.*, u.first_name, u.last_name, u.username, u.avatar, 
                      (SELECT ai.secret FROM auth_identities ai 
                       WHERE ai.user_id = u.id AND ai.type = "email_password" 
                       LIMIT 1) as email')
            ->join('users u', 'n.author_id = u.id', 'left')
            ->where('n.parent_note_id', $noteId)
            ->where('n.deleted_at IS NULL')
            ->groupBy('n.id') // Group by note ID to avoid duplicates
            ->orderBy('n.created_at', 'ASC');

        $results = $builder->get()->getResultArray();

        // Process replies
        foreach ($results as &$reply) {
            $reply['mentions'] = !empty($reply['mentions']) ? json_decode($reply['mentions'], true) : [];
            $reply['attachments'] = $this->processAttachments($reply['attachments'] ?? '');
            
            // Create author name from available fields
            $firstName = $reply['first_name'] ?? '';
            $lastName = $reply['last_name'] ?? '';
            $username = $reply['username'] ?? '';
            
            if (!empty($firstName) || !empty($lastName)) {
                $reply['author_name'] = trim($firstName . ' ' . $lastName);
            } else {
                $reply['author_name'] = $username ?: 'Unknown User';
            }
        }

        return $results;
    }

    /**
     * Get notes with unread mentions for a user
     */
    public function getUnreadMentions(int $userId): array
    {
        return $this->db->table('car_wash_note_mentions nm')
            ->select('nm.*, n.content, n.car_wash_order_id, u.first_name, u.last_name, u.username, ai.secret as email')
            ->join('car_wash_notes n', 'nm.note_id = n.id')
            ->join('users u', 'n.author_id = u.id', 'left')
            ->join('auth_identities ai', 'u.id = ai.user_id AND ai.type = "email_password"', 'left')
            ->where('nm.mentioned_user_id', $userId)
            ->where('nm.read_at IS NULL')
            ->where('n.deleted_at IS NULL')
            ->orderBy('nm.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Mark mention as read
     */
    public function markMentionAsRead(int $noteId, int $userId): bool
    {
        return $this->db->table('car_wash_note_mentions')
            ->where('note_id', $noteId)
            ->where('mentioned_user_id', $userId)
            ->update(['read_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get staff users for mentions
     */
    public function getStaffUsers(string $search = ''): array
    {
        $builder = $this->db->table('users u')
            ->select('u.id, u.first_name, u.last_name, u.username, u.avatar, 
                      (SELECT ai.secret FROM auth_identities ai 
                       WHERE ai.user_id = u.id AND ai.type = "email_password" 
                       LIMIT 1) as email')
            ->where('u.deleted_at IS NULL')
            ->where('u.user_type', 'staff'); // Only staff users

        if (!empty($search)) {
            $builder->groupStart()
                ->like('u.first_name', $search)
                ->orLike('u.last_name', $search)
                ->orLike('u.username', $search)
                ->groupEnd();
        }

        return $builder->orderBy('u.first_name', 'ASC')
            ->limit(10)
            ->get()
            ->getResultArray();
    }

    /**
     * Extract mentions from content
     */
    public function extractMentions(string $content): array
    {
        preg_match_all('/@(\w+)/', $content, $matches);
        $usernames = $matches[1];
        
        if (empty($usernames)) {
            return [];
        }

        $users = $this->db->table('users')
            ->select('id, username')
            ->whereIn('username', $usernames)
            ->where('deleted_at IS NULL')
            ->where('user_type', 'staff')
            ->get()
            ->getResultArray();

        return array_column($users, 'id');
    }

    /**
     * Process content to highlight mentions
     */
    public function processMentions(string $content): string
    {
        return preg_replace(
            '/@(\w+)/',
            '<span class="mention" data-username="$1">@$1</span>',
            $content
        );
    }

    /**
     * Before insert callback
     */
    protected function beforeInsert(array $data): array
    {
        if (isset($data['data']['content'])) {
            // Extract mentions from content
            $mentions = $this->extractMentions($data['data']['content']);
            $data['data']['mentions'] = !empty($mentions) ? json_encode($mentions) : null;
        }

        return $data;
    }

    /**
     * After insert callback - Create mention records
     */
    protected function afterInsert(array $data): array
    {
        if (isset($data['data']['mentions']) && !empty($data['data']['mentions'])) {
            $mentions = json_decode($data['data']['mentions'], true);
            $noteId = $data['id'];

            foreach ($mentions as $userId) {
                $this->db->table('car_wash_note_mentions')->insert([
                    'note_id' => $noteId,
                    'mentioned_user_id' => $userId,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return $data;
    }

    /**
     * Before update callback
     */
    protected function beforeUpdate(array $data): array
    {
        if (isset($data['data']['content'])) {
            // Re-extract mentions from updated content
            $mentions = $this->extractMentions($data['data']['content']);
            $data['data']['mentions'] = !empty($mentions) ? json_encode($mentions) : null;
        }

        return $data;
    }

    /**
     * After update callback - Update mention records
     */
    protected function afterUpdate(array $data): array
    {
        if (isset($data['data']['mentions']) && isset($data['id'])) {
            // Delete existing mentions
            $this->db->table('car_wash_note_mentions')->where('note_id', $data['id'])->delete();

            // Insert new mentions
            if (!empty($data['data']['mentions'])) {
                $mentions = json_decode($data['data']['mentions'], true);
                
                foreach ($mentions as $userId) {
                    $this->db->table('car_wash_note_mentions')->insert([
                        'note_id' => $data['id'],
                        'mentioned_user_id' => $userId,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        return $data;
    }

    /**
     * After find callback - Process JSON fields
     */
    protected function afterFind(array $data): array
    {
        if (isset($data['data'])) {
            foreach ($data['data'] as &$note) {
                if (isset($note['mentions'])) {
                    $note['mentions'] = !empty($note['mentions']) ? json_decode($note['mentions'], true) : [];
                }
                if (isset($note['attachments'])) {
                    $note['attachments'] = !empty($note['attachments']) ? json_decode($note['attachments'], true) : [];
                }
            }
        } elseif (isset($data['mentions'])) {
            $data['mentions'] = !empty($data['mentions']) ? json_decode($data['mentions'], true) : [];
            $data['attachments'] = !empty($data['attachments']) ? json_decode($data['attachments'], true) : [];
        }

        return $data;
    }

    /**
     * Process attachments to add URLs
     */
    private function processAttachments(string $attachmentsJson): array
    {
        if (empty($attachmentsJson)) {
            return [];
        }

        $attachments = json_decode($attachmentsJson, true);
        if (!is_array($attachments)) {
            return [];
        }

        // Add URL to each attachment
        foreach ($attachments as &$attachment) {
            if (isset($attachment['filename'])) {
                $attachment['url'] = base_url('car-wash-notes/download/' . urlencode($attachment['filename']));
            }
        }

        return $attachments;
    }
} 