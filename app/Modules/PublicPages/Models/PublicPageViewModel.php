<?php

namespace Modules\PublicPages\Models;

use CodeIgniter\Model;

class PublicPageViewModel extends Model
{
    protected $table = 'public_page_views';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'page_id',
        'user_id',
        'ip_address',
        'user_agent',
        'referrer',
        'session_id',
        'viewed_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'page_id' => 'integer',
        'user_id' => '?integer'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';
    
    protected $dates = ['viewed_at'];

    // Validation
    protected $validationRules = [
        'page_id' => 'required|integer',
        'ip_address' => 'required|string|max_length[45]',
        'viewed_at' => 'required'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get view statistics for a page
     */
    public function getPageStats(int $pageId, int $days = 30)
    {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        return [
            'total_views' => $this->where('page_id', $pageId)->countAllResults(),
            'recent_views' => $this->where('page_id', $pageId)
                                 ->where('viewed_at >=', $startDate)
                                 ->countAllResults(),
            'unique_visitors' => $this->where('page_id', $pageId)
                                     ->where('viewed_at >=', $startDate)
                                     ->distinct()
                                     ->countAllResults('ip_address'),
            'registered_users' => $this->where('page_id', $pageId)
                                      ->where('viewed_at >=', $startDate)
                                      ->where('user_id IS NOT NULL')
                                      ->distinct()
                                      ->countAllResults('user_id')
        ];
    }

    /**
     * Get daily view counts
     */
    public function getDailyViews(int $pageId, int $days = 30)
    {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        return $this->select('DATE(viewed_at) as date, COUNT(*) as views')
                   ->where('page_id', $pageId)
                   ->where('viewed_at >=', $startDate)
                   ->groupBy('DATE(viewed_at)')
                   ->orderBy('date')
                   ->findAll();
    }

    /**
     * Get hourly view distribution
     */
    public function getHourlyViews(int $pageId, int $days = 7)
    {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        return $this->select('HOUR(viewed_at) as hour, COUNT(*) as views')
                   ->where('page_id', $pageId)
                   ->where('viewed_at >=', $startDate)
                   ->groupBy('HOUR(viewed_at)')
                   ->orderBy('hour')
                   ->findAll();
    }

    /**
     * Get top referrers
     */
    public function getTopReferrers(int $pageId, int $days = 30, int $limit = 10)
    {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        return $this->select('referrer, COUNT(*) as views')
                   ->where('page_id', $pageId)
                   ->where('viewed_at >=', $startDate)
                   ->where('referrer IS NOT NULL')
                   ->where('referrer !=', '')
                   ->groupBy('referrer')
                   ->orderBy('views', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get recent views
     */
    public function getRecentViews(int $pageId, int $limit = 50)
    {
        return $this->select('public_page_views.*, users.first_name, users.last_name, users.username')
                   ->join('users', 'users.id = public_page_views.user_id', 'left')
                   ->where('page_id', $pageId)
                   ->orderBy('viewed_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Clean old view records
     */
    public function cleanOldViews(int $daysToKeep = 90)
    {
        $cutoffDate = date('Y-m-d', strtotime("-{$daysToKeep} days"));
        return $this->where('viewed_at <', $cutoffDate)->delete();
    }

    /**
     * Check if user/IP has viewed recently (for preventing spam)
     */
    public function hasViewedRecently(int $pageId, $userId = null, string $ipAddress = '', int $minutes = 5): bool
    {
        $cutoffTime = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));
        
        $builder = $this->where('page_id', $pageId)
                       ->where('viewed_at >=', $cutoffTime);
        
        if ($userId) {
            $builder->where('user_id', $userId);
        } else {
            $builder->where('ip_address', $ipAddress);
        }
        
        return $builder->countAllResults() > 0;
    }
}
