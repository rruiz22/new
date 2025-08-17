<?php

namespace Modules\PublicPages\Models;

use CodeIgniter\Model;

class PublicPageModel extends Model
{
    protected $table = 'public_pages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'title',
        'slug',
        'content',
        'excerpt',
        'privacy_level',
        'password',
        'allowed_roles',
        'template',
        'custom_css',
        'custom_js',
        'status',
        'featured_image',
        'views_count',
        'likes_count',
        'comments_enabled',
        'social_sharing',
        'show_author',
        'show_date',
        'version',
        'created_by',
        'updated_by',
        'published_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'allowed_roles' => '?json',
        'comments_enabled' => 'boolean',
        'social_sharing' => 'boolean',
        'show_author' => 'boolean',
        'show_date' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'version' => 'integer',
        'created_by' => 'integer',
        'updated_by' => '?integer'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $dates = ['published_at'];

    // Validation
    protected $validationRules = [
        'title' => 'required|string|max_length[255]|min_length[3]',
        'slug' => 'required|string|max_length[255]|min_length[3]|is_unique[public_pages.slug,id,{id}]|alpha_dash',
        'content' => 'required|string|min_length[10]',
        'excerpt' => 'permit_empty|string|max_length[500]',
        'privacy_level' => 'required|in_list[public,password,users_only,roles,private]',
        'status' => 'required|in_list[draft,published,archived]',
        'template' => 'required|string|max_length[100]|alpha_dash',
        'custom_css' => 'permit_empty|string|max_length[10000]',
        'custom_js' => 'permit_empty|string|max_length[10000]',
        'created_by' => 'required|integer|greater_than[0]',
        'updated_by' => 'permit_empty|integer|greater_than[0]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'El título es requerido',
            'min_length' => 'El título debe tener al menos 3 caracteres',
            'max_length' => 'El título no puede exceder 255 caracteres'
        ],
        'slug' => [
            'required' => 'El slug es requerido',
            'min_length' => 'El slug debe tener al menos 3 caracteres',
            'max_length' => 'El slug no puede exceder 255 caracteres',
            'is_unique' => 'Este slug ya está en uso',
            'alpha_dash' => 'El slug solo puede contener letras, números, guiones y guiones bajos'
        ],
        'content' => [
            'required' => 'El contenido es requerido',
            'min_length' => 'El contenido debe tener al menos 10 caracteres'
        ],
        'excerpt' => [
            'max_length' => 'El resumen no puede exceder 500 caracteres'
        ],
        'privacy_level' => [
            'required' => 'El nivel de privacidad es requerido',
            'in_list' => 'Nivel de privacidad inválido'
        ],
        'status' => [
            'required' => 'El estado es requerido',
            'in_list' => 'Estado inválido'
        ],
        'template' => [
            'required' => 'La plantilla es requerida',
            'alpha_dash' => 'Nombre de plantilla inválido'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['generateSlug', 'setVersion'];
    protected $afterInsert = ['createVersion'];
    protected $beforeUpdate = ['generateSlug', 'incrementVersion'];
    protected $afterUpdate = ['createVersion'];
    protected $beforeFind = [];
    protected $afterFind = ['loadRelations'];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Generate slug from title if not provided
     */
    protected function generateSlug(array $data)
    {
        if (isset($data['data']['title']) && empty($data['data']['slug'])) {
            // Generate slug from title when no slug provided
            $excludeId = $data['id'] ?? null;
            $data['data']['slug'] = $this->createSlug($data['data']['title'], $excludeId);
        } elseif (isset($data['data']['slug'])) {
            // Clean and ensure uniqueness of provided slug
            $excludeId = $data['id'] ?? null;
            $data['data']['slug'] = $this->createSlug($data['data']['slug'], $excludeId);
        }
        return $data;
    }

    /**
     * Set initial version
     */
    protected function setVersion(array $data)
    {
        if (!isset($data['data']['version'])) {
            $data['data']['version'] = 1;
        }
        return $data;
    }

    /**
     * Increment version on update
     */
    protected function incrementVersion(array $data)
    {
        if (isset($data['id'])) {
            $current = $this->find($data['id']);
            if ($current) {
                $data['data']['version'] = ($current['version'] ?? 1) + 1;
            }
        }
        return $data;
    }

    /**
     * Create version record after insert/update
     */
    protected function createVersion(array $data)
    {
        // Only create versions for content updates, not for stats updates
        if ((isset($data['id']) || isset($data['result'])) && 
            (isset($data['data']['title']) || isset($data['data']['content']))) {
            
            $pageId = $data['id'] ?? $data['result'];
            
            // Get fresh data from database to ensure we have all fields
            $page = $this->db->table($this->table)->where('id', $pageId)->get()->getRowArray();
            
            if ($page && isset($page['title']) && isset($page['content'])) {
                $versionModel = new PublicPageVersionModel();
                
                // Get next version number
                $lastVersion = $versionModel->where('page_id', $pageId)
                                          ->orderBy('version_number', 'DESC')
                                          ->first();
                $nextVersion = $lastVersion ? ($lastVersion['version_number'] + 1) : 1;
                
                $versionModel->insert([
                    'page_id' => $pageId,
                    'version_number' => $nextVersion,
                    'title' => $page['title'],
                    'content' => $page['content'],
                    'created_by' => $page['updated_by'] ?? $page['created_by'] ?? null
                ]);
            }
        }
        return $data;
    }

    /**
     * Load related data after find
     */
    protected function loadRelations(array $data)
    {
        if (isset($data['data']) && is_array($data['data'])) {
            // Load single record relations
            if (isset($data['data']['id'])) {
                $data['data'] = $this->loadSingleRelations($data['data']);
            }
            // Load multiple records relations
            elseif (is_array($data['data']) && isset($data['data'][0])) {
                foreach ($data['data'] as &$record) {
                    $record = $this->loadSingleRelations($record);
                }
            }
        }
        return $data;
    }

    /**
     * Load relations for a single record
     */
    protected function loadSingleRelations(array $record)
    {
        // Load author info
        if (isset($record['created_by'])) {
            $userModel = new \App\Models\UserModel();
            $record['author'] = $userModel->find($record['created_by']);
        }

        // Load files
        $fileModel = new PublicPageFileModel();
        $record['files'] = $fileModel->where('page_id', $record['id'])->orderBy('sort_order')->findAll();

        return $record;
    }

    /**
     * Create URL-friendly slug
     */
    public function createSlug(string $text, $excludeId = null): string
    {
        // Convert to lowercase
        $slug = strtolower($text);
        
        // Replace spaces and special characters with hyphens
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        
        // Remove leading/trailing hyphens
        $slug = trim($slug, '-');
        
        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        
        $builder = $this->where('slug', $slug);
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        while ($builder->countAllResults() > 0) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            
            // Reset builder for next iteration
            $builder = $this->where('slug', $slug);
            if ($excludeId) {
                $builder->where('id !=', $excludeId);
            }
        }
        
        return $slug;
    }

    /**
     * Get published pages
     */
    public function getPublished($limit = null, $offset = 0)
    {
        $builder = $this->where('status', 'published');
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->orderBy('published_at', 'DESC')->findAll();
    }

    /**
     * Get page by slug
     */
    public function getBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Get public pages (accessible to everyone)
     */
    public function getPublicPages($limit = null, $offset = 0)
    {
        $builder = $this->where('status', 'published')
                       ->where('privacy_level', 'public');
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->orderBy('published_at', 'DESC')->findAll();
    }

    /**
     * Check if user can access page
     */
    public function canUserAccess(array $page, $userId = null, $userRoles = []): bool
    {
        // Check if page is published
        if ($page['status'] !== 'published') {
            return false;
        }

        switch ($page['privacy_level']) {
            case 'public':
                return true;
                
            case 'password':
                // Password check is handled in controller
                return true;
                
            case 'users_only':
                return $userId !== null;
                
            case 'roles':
                if (empty($userId) || empty($userRoles)) {
                    return false;
                }
                
                $allowedRoles = $page['allowed_roles'] ?? [];
                return !empty(array_intersect($userRoles, $allowedRoles));
                
            case 'private':
                // Only creator and admins can access
                return $userId && ($userId == $page['created_by'] || in_array('admin', $userRoles));
                
            default:
                return false;
        }
    }

    /**
     * Increment view count
     */
    public function incrementViews(int $pageId, $userId = null, string $ipAddress = '', string $userAgent = '', string $referrer = '')
    {
        // Update views count
        $this->set('views_count', 'views_count + 1', false)
             ->where('id', $pageId)
             ->update();

        // Log the view
        $viewModel = new PublicPageViewModel();
        $viewModel->insert([
            'page_id' => $pageId,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'referrer' => $referrer,
            'session_id' => session_id(),
            'viewed_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Toggle like for a page
     */
    public function toggleLike(int $pageId, $userId = null, string $ipAddress = '')
    {
        $likeModel = new PublicPageLikeModel();
        
        $conditions = ['page_id' => $pageId];
        if ($userId) {
            $conditions['user_id'] = $userId;
        } else {
            $conditions['ip_address'] = $ipAddress;
        }
        
        $existingLike = $likeModel->where($conditions)->first();
        
        if ($existingLike) {
            // Remove like
            $likeModel->delete($existingLike['id']);
            $this->set('likes_count', 'likes_count - 1', false)
                 ->where('id', $pageId)
                 ->update();
            return false;
        } else {
            // Add like
            $likeModel->insert([
                'page_id' => $pageId,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'reaction_type' => 'like'
            ]);
            $this->set('likes_count', 'likes_count + 1', false)
                 ->where('id', $pageId)
                 ->update();
            return true;
        }
    }

    /**
     * Get page analytics
     */
    public function getAnalytics(int $pageId, int $days = 30)
    {
        $viewModel = new PublicPageViewModel();
        $likeModel = new PublicPageLikeModel();
        
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        return [
            'total_views' => $viewModel->where('page_id', $pageId)->countAllResults(),
            'recent_views' => $viewModel->where('page_id', $pageId)
                                      ->where('viewed_at >=', $startDate)
                                      ->countAllResults(),
            'total_likes' => $likeModel->where('page_id', $pageId)->countAllResults(),
            'recent_likes' => $likeModel->where('page_id', $pageId)
                                       ->where('created_at >=', $startDate)
                                       ->countAllResults(),
            'daily_views' => $viewModel->select('DATE(viewed_at) as date, COUNT(*) as views')
                                      ->where('page_id', $pageId)
                                      ->where('viewed_at >=', $startDate)
                                      ->groupBy('DATE(viewed_at)')
                                      ->orderBy('date')
                                      ->findAll()
        ];
    }
}
