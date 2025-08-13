<?php

namespace Modules\PublicPages\Controllers;

use App\Controllers\BaseController;
use Modules\PublicPages\Models\PublicPageModel;
use Modules\PublicPages\Models\PublicPageViewModel;
use Modules\PublicPages\Models\PublicPageLikeModel;

class PublicViewController extends BaseController
{
    protected $pageModel;
    protected $viewModel;
    protected $likeModel;

    public function __construct()
    {
        $this->pageModel = new PublicPageModel();
        $this->viewModel = new PublicPageViewModel();
        $this->likeModel = new PublicPageLikeModel();
    }

    /**
     * Display public page by slug
     */
    public function view($slug)
    {
        $page = $this->pageModel->getBySlug($slug);

        if (!$page || $page['status'] !== 'published') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Página no encontrada');
        }

        // Check access permissions
        $user = auth()->user();
        $userId = $user ? $user->id : null;
        $userRoles = $user ? [$user->user_type] : [];

        // Handle password protection
        if ($page['privacy_level'] === 'password') {
            $sessionKey = 'page_password_' . $page['id'];
            
            if ($this->request->getMethod() === 'post') {
                $inputPassword = $this->request->getPost('password');
                if (password_verify($inputPassword, $page['password'])) {
                    session()->set($sessionKey, true);
                } else {
                    return view('Modules\PublicPages\Views\public\password', [
                        'page' => $page,
                        'error' => 'Contraseña incorrecta'
                    ]);
                }
            } elseif (!session()->get($sessionKey)) {
                return view('Modules\PublicPages\Views\public\password', [
                    'page' => $page
                ]);
            }
        } elseif (!$this->pageModel->canUserAccess($page, $userId, $userRoles)) {
            // Check other access levels
            switch ($page['privacy_level']) {
                case 'users_only':
                    return redirect()->to('/login')->with('message', 'Debes iniciar sesión para ver esta página');
                
                case 'roles':
                    if (!$user) {
                        return redirect()->to('/login')->with('message', 'Debes iniciar sesión para ver esta página');
                    } else {
                        throw new \CodeIgniter\Exceptions\PageNotFoundException('No tienes permisos para ver esta página');
                    }
                
                case 'private':
                    throw new \CodeIgniter\Exceptions\PageNotFoundException('Página no encontrada');
                
                default:
                    throw new \CodeIgniter\Exceptions\PageNotFoundException('Acceso denegado');
            }
        }

        // Track view (avoid spam by checking recent views)
        $ipAddress = $this->request->getIPAddress();
        if (!$this->viewModel->hasViewedRecently($page['id'], $userId, $ipAddress, 5)) {
            $this->pageModel->incrementViews(
                $page['id'],
                $userId,
                $ipAddress,
                $this->request->getUserAgent() ? $this->request->getUserAgent()->__toString() : '',
                $this->request->getServer('HTTP_REFERER') ?? ''
            );
        }

        // Check if user has liked this page
        $hasLiked = false;
        if ($userId || $ipAddress) {
            $hasLiked = $this->likeModel->hasUserLiked($page['id'], $userId, $ipAddress);
        }

        // Get template
        $template = $page['template'] ?: 'default';
        $templatePath = 'Modules\PublicPages\Views\templates\\' . $template;

        // Check if template file exists, fallback to default
        $templateFile = APPPATH . 'Modules/PublicPages/Views/templates/' . $template . '.php';
        if (!file_exists($templateFile)) {
            $templatePath = 'Modules\PublicPages\Views\templates\default';
        }

        $data = [
            'page' => $page,
            'hasLiked' => $hasLiked,
            'user' => $user,
            'showComments' => $page['comments_enabled'] && $this->isCommentsEnabled()
        ];

        return view($templatePath, $data);
    }

    /**
     * Toggle like for a page
     */
    public function toggleLike()
    {
        $pageId = $this->request->getPost('page_id');
        $page = $this->pageModel->find($pageId);

        if (!$page || $page['status'] !== 'published') {
            return $this->response->setJSON(['success' => false, 'message' => 'Página no encontrada']);
        }

        $user = auth()->user();
        $userId = $user ? $user->id : null;
        $ipAddress = $this->request->getIPAddress();

        // Check if user can access the page
        $userRoles = $user ? [$user->user_type] : [];
        if (!$this->pageModel->canUserAccess($page, $userId, $userRoles)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tienes permisos']);
        }

        $liked = $this->pageModel->toggleLike($pageId, $userId, $ipAddress);

        // Get updated like count
        $updatedPage = $this->pageModel->find($pageId);

        return $this->response->setJSON([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $updatedPage['likes_count']
        ]);
    }

    /**
     * Get page content via AJAX (for dynamic loading)
     */
    public function getPageContent($slug)
    {
        $page = $this->pageModel->getBySlug($slug);

        if (!$page || $page['status'] !== 'published') {
            return $this->response->setJSON(['success' => false, 'message' => 'Página no encontrada']);
        }

        $user = auth()->user();
        $userId = $user ? $user->id : null;
        $userRoles = $user ? [$user->user_type] : [];

        if (!$this->pageModel->canUserAccess($page, $userId, $userRoles)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Acceso denegado']);
        }

        return $this->response->setJSON([
            'success' => true,
            'page' => [
                'title' => $page['title'],
                'content' => $page['content'],
                'excerpt' => $page['excerpt'],
                'likes_count' => $page['likes_count'],
                'views_count' => $page['views_count'],
                'show_author' => $page['show_author'],
                'show_date' => $page['show_date'],
                'created_at' => $page['created_at'],
                'author' => $page['author'] ?? null
            ]
        ]);
    }

    /**
     * Share page on social media
     */
    public function share($slug, $platform)
    {
        $page = $this->pageModel->getBySlug($slug);

        if (!$page || $page['status'] !== 'published' || !$page['social_sharing']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Página no encontrada');
        }

        $pageUrl = base_url('p/' . $slug);
        $title = urlencode($page['title']);
        $description = urlencode($page['excerpt'] ?: substr(strip_tags($page['content']), 0, 160));

        $shareUrls = [
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$pageUrl}",
            'twitter' => "https://twitter.com/intent/tweet?url={$pageUrl}&text={$title}",
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$pageUrl}",
            'whatsapp' => "https://wa.me/?text={$title} {$pageUrl}",
            'telegram' => "https://t.me/share/url?url={$pageUrl}&text={$title}",
            'email' => "mailto:?subject={$title}&body={$description} {$pageUrl}"
        ];

        if (!isset($shareUrls[$platform])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Plataforma no soportada');
        }

        return redirect()->to($shareUrls[$platform]);
    }

    /**
     * Generate QR code for page
     */
    public function qrCode($slug)
    {
        $page = $this->pageModel->getBySlug($slug);

        if (!$page || $page['status'] !== 'published') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Página no encontrada');
        }

        $pageUrl = base_url('p/' . $slug);
        
        // Simple QR code generation using Google Charts API
        $qrCodeUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" . urlencode($pageUrl);

        // You might want to use a PHP QR code library instead
        return redirect()->to($qrCodeUrl);
    }

    /**
     * Get page list (public pages only)
     */
    public function pageList()
    {
        $pages = $this->pageModel->getPublicPages(20);

        $data = [
            'title' => 'Páginas Públicas',
            'pages' => $pages
        ];

        return view('Modules\PublicPages\Views\public\list', $data);
    }

    /**
     * Search pages
     */
    public function search()
    {
        $query = $this->request->getGet('q');
        $pages = [];

        if ($query && strlen($query) >= 3) {
            $pages = $this->pageModel->like('title', $query)
                                   ->orLike('content', $query)
                                   ->orLike('excerpt', $query)
                                   ->where('status', 'published')
                                   ->where('privacy_level', 'public')
                                   ->orderBy('created_at', 'DESC')
                                   ->limit(20)
                                   ->findAll();
        }

        $data = [
            'title' => 'Buscar Páginas',
            'query' => $query,
            'pages' => $pages
        ];

        return view('Modules\PublicPages\Views\public\search', $data);
    }

    /**
     * RSS Feed for public pages
     */
    public function rss()
    {
        $pages = $this->pageModel->getPublicPages(20);

        $rss = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $rss .= '<rss version="2.0">' . "\n";
        $rss .= '<channel>' . "\n";
        $rss .= '<title>' . htmlspecialchars(env('app.name', 'Mi Sitio')) . '</title>' . "\n";
        $rss .= '<link>' . base_url() . '</link>' . "\n";
        $rss .= '<description>Páginas públicas</description>' . "\n";

        foreach ($pages as $page) {
            $rss .= '<item>' . "\n";
            $rss .= '<title>' . htmlspecialchars($page['title']) . '</title>' . "\n";
            $rss .= '<link>' . base_url('p/' . $page['slug']) . '</link>' . "\n";
            $rss .= '<description>' . htmlspecialchars($page['excerpt'] ?: substr(strip_tags($page['content']), 0, 200)) . '</description>' . "\n";
            $rss .= '<pubDate>' . date('r', strtotime($page['published_at'] ?: $page['created_at'])) . '</pubDate>' . "\n";
            $rss .= '</item>' . "\n";
        }

        $rss .= '</channel>' . "\n";
        $rss .= '</rss>';

        return $this->response->setContentType('application/rss+xml')->setBody($rss);
    }

    /**
     * Check if comments are enabled globally
     */
    private function isCommentsEnabled(): bool
    {
        // This could be a setting in your app
        return true; // Or check from settings table
    }

    /**
     * Sitemap for public pages
     */
    public function sitemap()
    {
        $pages = $this->pageModel->getPublicPages();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($pages as $page) {
            $xml .= '<url>' . "\n";
            $xml .= '<loc>' . base_url('p/' . $page['slug']) . '</loc>' . "\n";
            $xml .= '<lastmod>' . date('c', strtotime($page['updated_at'] ?: $page['created_at'])) . '</lastmod>' . "\n";
            $xml .= '<changefreq>weekly</changefreq>' . "\n";
            $xml .= '<priority>0.8</priority>' . "\n";
            $xml .= '</url>' . "\n";
        }

        $xml .= '</urlset>';

        return $this->response->setContentType('application/xml')->setBody($xml);
    }
}
