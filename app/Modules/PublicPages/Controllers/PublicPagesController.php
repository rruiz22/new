<?php

namespace Modules\PublicPages\Controllers;

use App\Controllers\BaseController;
use Modules\PublicPages\Models\PublicPageModel;
use Modules\PublicPages\Models\PublicPageFileModel;
use Modules\PublicPages\Models\PublicPageVersionModel;

class PublicPagesController extends BaseController
{
    protected $pageModel;
    protected $fileModel;
    protected $versionModel;

    public function __construct()
    {
        $this->pageModel = new PublicPageModel();
        $this->fileModel = new PublicPageFileModel();
        $this->versionModel = new PublicPageVersionModel();
        
        // Load security helper
        helper('Modules\PublicPages\Helpers\security');
    }

    /**
     * Admin dashboard - list all pages
     */
    public function index()
    {
        $data = [
            'title' => 'Páginas Públicas',
            'pages' => $this->pageModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('Modules\PublicPages\Views\admin\index', $data);
    }

    /**
     * Create new page form
     */
    public function create()
    {
        $data = [
            'title' => 'Crear Nueva Página',
            'page' => [
                'title' => '',
                'slug' => '',
                'content' => '',
                'excerpt' => '',
                'privacy_level' => 'public',
                'template' => 'default',
                'status' => 'draft',
                'comments_enabled' => false,
                'social_sharing' => true,
                'show_author' => true,
                'show_date' => true
            ]
        ];

        return view('Modules\PublicPages\Views\admin\form', $data);
    }

    /**
     * Store new page
     */
    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[10]',
            'privacy_level' => 'required|in_list[public,password,users_only,roles,private]',
            'status' => 'required|in_list[draft,published,archived]',
            'template' => 'required'
        ];

        if ($this->request->getPost('privacy_level') === 'password') {
            $rules['password'] = 'required|min_length[4]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug') ?: $this->pageModel->createSlug($this->request->getPost('title')),
            'content' => $this->sanitizeHtmlContent($this->request->getPost('content')),
            'excerpt' => strip_tags($this->request->getPost('excerpt')),
            'privacy_level' => $this->request->getPost('privacy_level'),
            'template' => $this->request->getPost('template'),
            'custom_css' => $this->sanitizeCss($this->request->getPost('custom_css')),
            'custom_js' => $this->sanitizeJs($this->request->getPost('custom_js')),
            'status' => $this->request->getPost('status'),
            'comments_enabled' => $this->request->getPost('comments_enabled') === 'on' ? 1 : 0,
            'social_sharing' => $this->request->getPost('social_sharing') === 'on' ? 1 : 0,
            'show_author' => $this->request->getPost('show_author') === 'on' ? 1 : 0,
            'show_date' => $this->request->getPost('show_date') === 'on' ? 1 : 0,
            'created_by' => auth()->user()->id
        ];

        // Handle password only if privacy level is password
        if ($this->request->getPost('privacy_level') === 'password' && $this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Handle allowed roles only if privacy level is roles
        if ($this->request->getPost('privacy_level') === 'roles' && $this->request->getPost('allowed_roles')) {
            $data['allowed_roles'] = json_encode($this->request->getPost('allowed_roles'));
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        // Debug: log the data being inserted
        log_message('debug', 'PublicPages: Attempting to insert data: ' . json_encode($data));
        
        $pageId = $this->pageModel->insert($data);
        
        // Debug: check for database errors
        if (!$pageId) {
            $errors = $this->pageModel->errors();
            log_message('error', 'PublicPages: Insert failed with errors: ' . json_encode($errors));
            return redirect()->back()->withInput()->with('error', 'Error al crear la página: ' . implode(', ', $errors));
        }

        if ($pageId) {
            // Handle file uploads
            $files = $this->request->getFiles();
            if (isset($files['files']) && !empty($files['files'])) {
                $this->fileModel->processUpload($files['files'], $pageId);
            }

            return redirect()->to('/public_pages')->with('success', 'Página creada exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al crear la página');
        }
    }

    /**
     * Edit page form
     */
    public function edit($id)
    {
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Página no encontrada');
        }

        // Check permissions
        if (!$this->canUserEditPage($page)) {
            return redirect()->to('/public_pages')->with('error', 'No tienes permisos para editar esta página');
        }

        $data = [
            'title' => 'Editar Página: ' . $page['title'],
            'page' => $page,
            'versions' => $this->versionModel->getPageVersions($id, 10)
        ];

        return view('Modules\PublicPages\Views\admin\form', $data);
    }

    /**
     * Update page
     */
    public function update($id)
    {
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Página no encontrada');
        }

        if (!$this->canUserEditPage($page)) {
            return redirect()->to('/public_pages')->with('error', 'No tienes permisos para editar esta página');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'slug' => "permit_empty|max_length[255]|is_unique[public_pages.slug,id,{$id}]",
            'content' => 'required|min_length[10]',
            'privacy_level' => 'required|in_list[public,password,users_only,roles,private]',
            'status' => 'required|in_list[draft,published,archived]',
            'template' => 'required'
        ];

        if ($this->request->getPost('privacy_level') === 'password') {
            $rules['password'] = 'required|min_length[4]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'slug' => $this->request->getPost('slug') ?: $this->pageModel->createSlug($this->request->getPost('title')),
            'content' => $this->sanitizeHtmlContent($this->request->getPost('content')),
            'excerpt' => strip_tags($this->request->getPost('excerpt')),
            'privacy_level' => $this->request->getPost('privacy_level'),
            'template' => $this->request->getPost('template'),
            'custom_css' => $this->sanitizeCss($this->request->getPost('custom_css')),
            'custom_js' => $this->sanitizeJs($this->request->getPost('custom_js')),
            'status' => $this->request->getPost('status'),
            'comments_enabled' => $this->request->getPost('comments_enabled') === 'on' ? 1 : 0,
            'social_sharing' => $this->request->getPost('social_sharing') === 'on' ? 1 : 0,
            'show_author' => $this->request->getPost('show_author') === 'on' ? 1 : 0,
            'show_date' => $this->request->getPost('show_date') === 'on' ? 1 : 0,
            'updated_by' => auth()->user()->id
        ];

        // Handle password
        if ($this->request->getPost('privacy_level') === 'password') {
            if ($this->request->getPost('password')) {
                $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }
        } else {
            // Clear password if privacy level is not password
            $data['password'] = null;
        }

        // Handle allowed roles
        if ($this->request->getPost('privacy_level') === 'roles') {
            $data['allowed_roles'] = json_encode($this->request->getPost('allowed_roles') ?: []);
        } else {
            // Clear allowed roles if privacy level is not roles
            $data['allowed_roles'] = null;
        }

        // Handle published_at
        if ($data['status'] === 'published' && $page['status'] !== 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        // Temporarily disable model validation since we're doing it in the controller
        $this->pageModel->skipValidation(true);
        
        if ($this->pageModel->update($id, $data)) {
            // Re-enable model validation
            $this->pageModel->skipValidation(false);
            // Handle file uploads
            $files = $this->request->getFiles();
            if (isset($files['files']) && !empty($files['files'])) {
                $this->fileModel->processUpload($files['files'], $id);
            }

            return redirect()->to('/public_pages')->with('success', 'Página actualizada exitosamente');
        } else {
            // Re-enable model validation
            $this->pageModel->skipValidation(false);
            // Get model errors for debugging
            $errors = $this->pageModel->errors();
            $errorMessage = 'Error al actualizar la página';
            if (!empty($errors)) {
                $errorMessage .= ': ' . implode(', ', $errors);
            }
            log_message('error', 'Error updating page ID ' . $id . ': ' . json_encode($errors));
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    /**
     * Delete page
     */
    public function delete()
    {
        $id = $this->request->getPost('id');
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            return $this->response->setJSON(['success' => false, 'message' => 'Página no encontrada']);
        }

        if (!$this->canUserEditPage($page)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tienes permisos para eliminar esta página']);
        }

        if ($this->pageModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Página eliminada exitosamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la página']);
        }
    }

    /**
     * View page analytics
     */
    public function analytics($id)
    {
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Página no encontrada');
        }

        if (!$this->canUserEditPage($page)) {
            return redirect()->to('/public_pages')->with('error', 'No tienes permisos para ver las estadísticas de esta página');
        }

        $analytics = $this->pageModel->getAnalytics($id, 30);
        
        // Get recent views and likes records for the tables
        $viewModel = new \Modules\PublicPages\Models\PublicPageViewModel();
        $likeModel = new \Modules\PublicPages\Models\PublicPageLikeModel();
        
        $recentViews = $viewModel->getRecentViews($id, 20);
        $recentLikes = $likeModel->getRecentLikes($id, 20);

        $data = [
            'title' => 'Estadísticas: ' . $page['title'],
            'page' => $page,
            'analytics' => $analytics,
            'recentViews' => $recentViews,
            'recentLikes' => $recentLikes
        ];

        return view('Modules\PublicPages\Views\admin\analytics', $data);
    }

    /**
     * Upload files via AJAX
     */
    public function uploadFiles()
    {
        $pageId = $this->request->getPost('page_id');
        $files = $this->request->getFiles();

        if (!$pageId || !isset($files['files'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        $page = $this->pageModel->find($pageId);
        if (!$page || !$this->canUserEditPage($page)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tienes permisos']);
        }

        // Rate limiting for file uploads
        $user = auth()->user();
        $identifier = 'user_' . $user->id;
        if (!check_rate_limit('fileUploads', $identifier)) {
            log_security_event('rate_limit_exceeded', 'File upload rate limit exceeded', [
                'page_id' => $pageId,
                'user_id' => $user->id
            ]);
            return $this->response->setJSON(['success' => false, 'message' => 'Demasiados archivos subidos. Intenta más tarde.']);
        }

        // Validate each file
        foreach ($files['files'] as $file) {
            [$isValid, $reason] = validate_file_upload($file);
            if (!$isValid) {
                log_security_event('malicious_file_upload', 'Blocked file upload', [
                    'reason' => $reason,
                    'filename' => $file->getClientName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'user_id' => $user->id
                ]);
                return $this->response->setJSON(['success' => false, 'message' => $reason]);
            }
        }

        $uploadedFiles = $this->fileModel->processUpload($files['files'], $pageId);

        if (!empty($uploadedFiles)) {
            return $this->response->setJSON(['success' => true, 'files' => $uploadedFiles]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al subir archivos']);
        }
    }

    /**
     * Delete file via AJAX
     */
    public function deleteFile()
    {
        $fileId = $this->request->getPost('file_id');
        $file = $this->fileModel->find($fileId);

        if (!$file) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo no encontrado']);
        }

        $page = $this->pageModel->find($file['page_id']);
        if (!$page || !$this->canUserEditPage($page)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tienes permisos']);
        }

        if ($this->fileModel->delete($fileId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Archivo eliminado']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar archivo']);
        }
    }

    /**
     * Restore version
     */
    public function restoreVersion()
    {
        $pageId = $this->request->getPost('page_id');
        $versionNumber = $this->request->getPost('version_number');

        $page = $this->pageModel->find($pageId);
        if (!$page || !$this->canUserEditPage($page)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No tienes permisos']);
        }

        if ($this->versionModel->restoreVersion($pageId, $versionNumber, auth()->user()->id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Versión restaurada exitosamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al restaurar versión']);
        }
    }

    /**
     * Check if user can edit page
     */
    private function canUserEditPage($page): bool
    {
        $user = auth()->user();
        
        // Admin can edit all
        if ($user->user_type === 'admin') {
            return true;
        }
        
        // Creator can edit their own
        if ($page['created_by'] == $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Preview page
     */
    public function preview($id)
    {
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Página no encontrada');
        }

        if (!$this->canUserEditPage($page)) {
            return redirect()->to('/public_pages')->with('error', 'No tienes permisos para previsualizar esta página');
        }

        $data = [
            'page' => $page,
            'preview' => true,
            'hasLiked' => false, // Preview mode - no likes
            'user' => auth()->user(),
            'showComments' => false // Preview mode - no comments
        ];

        // Use the template specified for the page or default
        $template = $page['template'] ?: 'default';
        $templatePath = 'Modules\PublicPages\Views\templates\\' . $template;

        return view($templatePath, $data);
    }

    /**
     * Duplicate page
     */
    public function duplicate($id)
    {
        $originalPage = $this->pageModel->find($id);
        
        if (!$originalPage) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Página no encontrada');
        }

        if (!$this->canUserEditPage($originalPage)) {
            return redirect()->to('/public_pages')->with('error', 'No tienes permisos para duplicar esta página');
        }

        // Prepare data for new page
        $newPageData = $originalPage;
        unset($newPageData['id'], $newPageData['created_at'], $newPageData['updated_at'], $newPageData['published_at']);
        
        $newPageData['title'] = $originalPage['title'] . ' (Copia)';
        $newPageData['slug'] = $originalPage['slug'] . '-copia';
        $newPageData['status'] = 'draft';
        $newPageData['created_by'] = auth()->user()->id;
        $newPageData['updated_by'] = null;
        $newPageData['views_count'] = 0;
        $newPageData['likes_count'] = 0;

        $newPageId = $this->pageModel->insert($newPageData);

        if ($newPageId) {
            // Copy files
            $originalFiles = $this->fileModel->where('page_id', $id)->findAll();
            foreach ($originalFiles as $file) {
                $newFileData = $file;
                unset($newFileData['id'], $newFileData['created_at'], $newFileData['updated_at']);
                $newFileData['page_id'] = $newPageId;
                
                // TODO: Copy physical files
                
                $this->fileModel->insert($newFileData);
            }

            return redirect()->to('/public_pages/edit/' . $newPageId)->with('success', 'Página duplicada exitosamente');
        } else {
            return redirect()->to('/public_pages')->with('error', 'Error al duplicar la página');
        }
    }

    /**
     * Sanitize HTML content using security helper
     */
    private function sanitizeHtmlContent($content)
    {
        if (empty($content)) {
            return '';
        }
        
        // PASO 1: Decodificar entidades HTML que el editor Quill está codificando
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // PASO 2: Limpiar espaciado doble y formato del editor Quill
        // Quill envuelve todo en <p> y agrega espacios innecesarios
        $content = preg_replace('/<p><br><\/p>/i', '', $content); // Eliminar párrafos vacíos con <br>
        $content = preg_replace('/<p>&nbsp;<\/p>/i', '', $content); // Eliminar párrafos con espacios
        $content = preg_replace('/<p>\s*<\/p>/i', '', $content); // Eliminar párrafos vacíos
        $content = preg_replace('/<p><br\s*\/?><\/p>/i', '', $content); // Eliminar párrafos con <br /> auto-cerrado
        $content = preg_replace('/(<p><br><\/p>\s*)+/i', '', $content); // Eliminar múltiples párrafos vacíos consecutivos
        $content = preg_replace('/&nbsp;/', ' ', $content); // Convertir &nbsp; a espacios normales
        
        // Si después de limpiar solo quedan espacios o está vacío, devolver cadena vacía
        if (trim(strip_tags($content)) === '') {
            return '';
        }
        
        // PASO 3: Sanitización básica - eliminar solo elementos peligrosos
        $dangerousTags = ['script', 'iframe', 'object', 'embed', 'form', 'input', 'textarea', 'select', 'button', 'link', 'meta'];
        foreach ($dangerousTags as $tag) {
            $content = preg_replace("/<\/?{$tag}[^>]*>/i", '', $content);
        }
        
        // PASO 4: Eliminar atributos de eventos peligrosos
        $content = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $content);
        
        // PASO 5: Eliminar URLs maliciosas
        $content = preg_replace('/javascript:/i', '', $content);
        $content = preg_replace('/vbscript:/i', '', $content);
        
        // PASO 6: Limpiar solo espacios excesivos SIN destruir la estructura HTML
        // Solo limpiar múltiples espacios consecutivos dentro del texto, NO los saltos de línea
        $content = preg_replace('/[ \t]{2,}/', ' ', $content); // Solo espacios y tabs múltiples, no \n
        
        // NO eliminar espacios entre tags - esto destruye el formato HTML
        // Mantener la estructura y formato del HTML
        
        return trim($content);
    }

    /**
     * Sanitize CSS content using security helper
     */
    private function sanitizeCss($css)
    {
        $user = auth()->user();
        return validate_css_content($css, $user->user_type);
    }

    /**
     * Sanitize JavaScript content using security helper
     */
    private function sanitizeJs($js)
    {
        $user = auth()->user();
        return validate_js_content($js, $user->user_type);
    }
}
