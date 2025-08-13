<?php

namespace Modules\PublicPages\Models;

use CodeIgniter\Model;

class PublicPageLikeModel extends Model
{
    protected $table = 'public_page_likes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'page_id',
        'user_id',
        'ip_address',
        'reaction_type'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'page_id' => 'integer',
        'user_id' => '?integer'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'page_id' => 'required|integer',
        'reaction_type' => 'required|string|max_length[50]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get like statistics for a page
     */
    public function getPageLikeStats(int $pageId)
    {
        return $this->select('reaction_type, COUNT(*) as count')
                   ->where('page_id', $pageId)
                   ->groupBy('reaction_type')
                   ->findAll();
    }

    /**
     * Check if user/IP has liked the page
     */
    public function hasUserLiked(int $pageId, $userId = null, string $ipAddress = ''): bool
    {
        $builder = $this->where('page_id', $pageId);
        
        if ($userId) {
            $builder->where('user_id', $userId);
        } else {
            $builder->where('ip_address', $ipAddress);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Get user's reaction to a page
     */
    public function getUserReaction(int $pageId, $userId = null, string $ipAddress = '')
    {
        $builder = $this->where('page_id', $pageId);
        
        if ($userId) {
            $builder->where('user_id', $userId);
        } else {
            $builder->where('ip_address', $ipAddress);
        }
        
        return $builder->first();
    }

    /**
     * Get recent likes for a page
     */
    public function getRecentLikes(int $pageId, int $limit = 20)
    {
        return $this->select('public_page_likes.*, users.first_name, users.last_name, users.username')
                   ->join('users', 'users.id = public_page_likes.user_id', 'left')
                   ->where('page_id', $pageId)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get top liked pages
     */
    public function getTopLikedPages(int $limit = 10, int $days = 30)
    {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        return $this->select('public_page_likes.page_id, public_pages.title, public_pages.slug, COUNT(*) as likes_count')
                   ->join('public_pages', 'public_pages.id = public_page_likes.page_id')
                   ->where('public_page_likes.created_at >=', $startDate)
                   ->where('public_pages.status', 'published')
                   ->groupBy('public_page_likes.page_id')
                   ->orderBy('likes_count', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}
