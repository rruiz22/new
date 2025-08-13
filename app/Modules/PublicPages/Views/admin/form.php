<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('public_pages') ?>">Páginas Públicas</a></li>
<li class="breadcrumb-item active"><?= isset($page['id']) ? 'Editar' : 'Crear' ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Quill Editor CSS -->
<link href="<?= base_url('assets/libs/quill/quill.core.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/libs/quill/quill.snow.css') ?>" rel="stylesheet" type="text/css" />


<style>
.form-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #0d6efd;
}
.form-section h5 {
    color: #0d6efd;
    margin-bottom: 1rem;
}
.editor-container {
    min-height: 300px;
}
.preview-container {
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 1rem;
    background: white;
    max-height: 400px;
    overflow-y: auto;
}
.file-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s ease;
}
.file-upload-area:hover {
    border-color: #0d6efd;
    background: #e7f1ff;
}
.uploaded-files {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}
.file-item {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}
.file-item img {
    width: 100%;
    height: 100px;
    object-fit: cover;
}
.file-item .file-info {
    padding: 0.5rem;
    font-size: 0.8rem;
}
.file-item .remove-file {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
}
.slug-preview {
    font-family: monospace;
    color: #6c757d;
    margin-top: 0.25rem;
}
.version-history {
    max-height: 200px;
    overflow-y: auto;
}
.version-item {
    border-bottom: 1px solid #dee2e6;
    padding: 0.5rem 0;
}
.version-item:last-child {
    border-bottom: none;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <form id="pageForm" method="POST" action="<?= isset($page['id']) ? base_url('public_pages/update/' . $page['id']) : base_url('public_pages/store') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="form-section">
                    <h5><i class="bx bx-edit"></i> Información Básica</h5>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Título *</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?= esc($page['title'] ?? '') ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (URL)</label>
                        <input type="text" class="form-control" id="slug" name="slug" 
                               value="<?= esc($page['slug'] ?? '') ?>">
                        <div class="slug-preview">
                            URL: <?= base_url('p/') ?><span id="slugPreview"><?= esc($page['slug'] ?? '') ?></span>
                        </div>
                        <small class="form-text text-muted">Deja vacío para generar automáticamente desde el título</small>
                    </div>

                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Resumen</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="3"
                                  placeholder="Breve descripción de la página"><?= esc($page['excerpt'] ?? '') ?></textarea>
                        <small class="form-text text-muted">Usado en listados y compartir en redes sociales</small>
                    </div>
                </div>

                <!-- Content Editor -->
                <div class="form-section">
                    <h5><i class="bx bx-text"></i> Contenido</h5>
                    
                    <div class="mb-3">
                        <div id="editor" class="editor-container"></div>
                        <textarea name="content" id="content" style="display:none;"><?= esc($page['content'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="form-section">
                    <h5><i class="bx bx-cloud-upload"></i> Archivos Multimedia</h5>
                    
                    <div class="file-upload-area" id="fileUploadArea">
                        <i class="bx bx-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
                        <p class="mb-2">Arrastra archivos aquí o haz clic para seleccionar</p>
                        <p class="text-muted small">Soporta imágenes, videos y documentos (máx. 50MB)</p>
                        <input type="file" id="fileInput" name="files[]" multiple accept="image/*,video/*,.pdf,.doc,.docx" style="display: none;">
                    </div>

                    <div id="uploadedFiles" class="uploaded-files">
                        <?php if (isset($page['files'])): ?>
                            <?php foreach ($page['files'] as $file): ?>
                                <div class="file-item" data-file-id="<?= $file['id'] ?>">
                                    <?php if ($file['file_type'] === 'image'): ?>
                                        <img src="<?= $file['url'] ?>" alt="<?= esc($file['original_name']) ?>">
                                    <?php else: ?>
                                        <div class="file-placeholder d-flex align-items-center justify-content-center" style="height: 100px; background: #f8f9fa;">
                                            <i class="bx bx-file" style="font-size: 2rem; color: #6c757d;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="file-info">
                                        <div class="text-truncate"><?= esc($file['original_name']) ?></div>
                                        <small class="text-muted"><?= $file['file_type'] ?></small>
                                    </div>
                                    <button type="button" class="remove-file" onclick="removeFile(<?= $file['id'] ?>)">×</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Custom Code -->
                <div class="form-section">
                    <h5><i class="bx bx-code"></i> Código Personalizado (Opcional)</h5>
                    
                    <div class="mb-3">
                        <label for="custom_css" class="form-label">CSS Personalizado</label>
                        <textarea class="form-control font-monospace" id="custom_css" name="custom_css" rows="5"
                                  placeholder="/* CSS personalizado para esta página */"><?= esc($page['custom_css'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="custom_js" class="form-label">JavaScript Personalizado</label>
                        <textarea class="form-control font-monospace" id="custom_js" name="custom_js" rows="5"
                                  placeholder="// JavaScript personalizado para esta página"><?= esc($page['custom_js'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Publish Settings -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bx bx-cog"></i> Configuración</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="draft" <?= ($page['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Borrador</option>
                                <option value="published" <?= ($page['status'] ?? 'draft') === 'published' ? 'selected' : '' ?>>Publicado</option>
                                <option value="archived" <?= ($page['status'] ?? 'draft') === 'archived' ? 'selected' : '' ?>>Archivado</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="template" class="form-label">Plantilla</label>
                            <select class="form-select" id="template" name="template" required>
                                <option value="default" <?= ($page['template'] ?? 'default') === 'default' ? 'selected' : '' ?>>Por Defecto</option>
                                <option value="minimal" <?= ($page['template'] ?? 'default') === 'minimal' ? 'selected' : '' ?>>Minimalista</option>
                                <option value="full-width" <?= ($page['template'] ?? 'default') === 'full-width' ? 'selected' : '' ?>>Ancho Completo</option>
                                <option value="blog" <?= ($page['template'] ?? 'default') === 'blog' ? 'selected' : '' ?>>Blog</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Opciones de Visualización</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_author" name="show_author" 
                                       <?= ($page['show_author'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="show_author">Mostrar autor</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_date" name="show_date" 
                                       <?= ($page['show_date'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="show_date">Mostrar fecha</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="social_sharing" name="social_sharing" 
                                       <?= ($page['social_sharing'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="social_sharing">Permitir compartir</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="comments_enabled" name="comments_enabled" 
                                       <?= ($page['comments_enabled'] ?? false) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="comments_enabled">Permitir comentarios</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bx bx-shield"></i> Privacidad</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="privacy_level" class="form-label">Nivel de Acceso</label>
                            <select class="form-select" id="privacy_level" name="privacy_level" required>
                                <option value="public" <?= ($page['privacy_level'] ?? 'public') === 'public' ? 'selected' : '' ?>>Público</option>
                                <option value="password" <?= ($page['privacy_level'] ?? 'public') === 'password' ? 'selected' : '' ?>>Protegido con contraseña</option>
                                <option value="users_only" <?= ($page['privacy_level'] ?? 'public') === 'users_only' ? 'selected' : '' ?>>Solo usuarios registrados</option>
                                <option value="roles" <?= ($page['privacy_level'] ?? 'public') === 'roles' ? 'selected' : '' ?>>Roles específicos</option>
                                <option value="private" <?= ($page['privacy_level'] ?? 'public') === 'private' ? 'selected' : '' ?>>Privado</option>
                            </select>
                        </div>

                        <div id="passwordField" class="mb-3" style="display: none;">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="form-text text-muted">Deja vacío para mantener la contraseña actual</small>
                        </div>

                        <div id="rolesField" class="mb-3" style="display: none;">
                            <label class="form-label">Roles Permitidos</label>
                            <?php 
                            $allowedRoles = [];
                            if (isset($page['allowed_roles']) && $page['allowed_roles']) {
                                $allowedRoles = is_string($page['allowed_roles']) ? json_decode($page['allowed_roles'], true) : $page['allowed_roles'];
                                if (!is_array($allowedRoles)) $allowedRoles = [];
                            }
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="role_admin" name="allowed_roles[]" value="admin" 
                                       <?= in_array('admin', $allowedRoles) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="role_admin">Administrador</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="role_staff" name="allowed_roles[]" value="staff" 
                                       <?= in_array('staff', $allowedRoles) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="role_staff">Staff</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="role_user" name="allowed_roles[]" value="user" 
                                       <?= in_array('user', $allowedRoles) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="role_user">Usuario</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> <?= isset($page['id']) ? 'Actualizar' : 'Crear' ?> Página
                            </button>
                            
                            <?php if (isset($page['id'])): ?>
                                <a href="<?= base_url('public_pages/preview/' . $page['id']) ?>" 
                                   class="btn btn-outline-info" target="_blank">
                                    <i class="bx bx-show"></i> Previsualizar
                                </a>
                                
                                <a href="<?= base_url('public_pages/analytics/' . $page['id']) ?>" 
                                   class="btn btn-outline-success">
                                    <i class="bx bx-bar-chart"></i> Ver Estadísticas
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?= base_url('public_pages') ?>" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Version History (if editing) -->
                <?php if (isset($page['id']) && isset($versions)): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bx bx-history"></i> Historial de Versiones</h5>
                    </div>
                    <div class="card-body">
                        <div class="version-history">
                            <?php foreach ($versions as $version): ?>
                                <div class="version-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>v<?= $version['version_number'] ?></strong>
                                            <small class="text-muted d-block">
                                                <?= date('d/m/Y H:i', strtotime($version['created_at'])) ?>
                                                <?php if ($version['first_name']): ?>
                                                    por <?= esc($version['first_name'] . ' ' . $version['last_name']) ?>
                                                <?php endif; ?>
                                            </small>
                                            <?php if ($version['changes_summary']): ?>
                                                <small class="text-muted"><?= esc($version['changes_summary']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="restoreVersion(<?= $version['page_id'] ?>, <?= $version['version_number'] ?>)">
                                            Restaurar
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/libs/quill/quill.min.js') ?>"></script>


<script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>

<script>
// Initialize Quill Editor
const quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            [{ 'align': [] }],
            ['link', 'image', 'video'],
            ['blockquote', 'code-block'],
            ['clean']
        ]
    }
});

// Set initial content
const initialContent = document.getElementById('content').value;
if (initialContent) {
    quill.root.innerHTML = initialContent;
}

// Update hidden textarea when content changes
quill.on('text-change', function() {
    document.getElementById('content').value = quill.root.innerHTML;
});

// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    
    if (!document.getElementById('slug').value || document.getElementById('slug').dataset.autoGenerated !== 'false') {
        document.getElementById('slug').value = slug;
        document.getElementById('slug').dataset.autoGenerated = 'true';
        document.getElementById('slugPreview').textContent = slug;
    }
});

// Manual slug editing
document.getElementById('slug').addEventListener('input', function() {
    this.dataset.autoGenerated = 'false';
    document.getElementById('slugPreview').textContent = this.value;
});

// Privacy level change handler
document.getElementById('privacy_level').addEventListener('change', function() {
    const passwordField = document.getElementById('passwordField');
    const rolesField = document.getElementById('rolesField');
    
    // Hide all fields first
    passwordField.style.display = 'none';
    rolesField.style.display = 'none';
    
    // Show relevant field
    if (this.value === 'password') {
        passwordField.style.display = 'block';
    } else if (this.value === 'roles') {
        rolesField.style.display = 'block';
    }
});

// Trigger privacy level change on load
document.getElementById('privacy_level').dispatchEvent(new Event('change'));

// File upload handling
const fileUploadArea = document.getElementById('fileUploadArea');
const fileInput = document.getElementById('fileInput');
const uploadedFiles = document.getElementById('uploadedFiles');

fileUploadArea.addEventListener('click', () => fileInput.click());

fileUploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    fileUploadArea.style.borderColor = '#0d6efd';
    fileUploadArea.style.background = '#e7f1ff';
});

fileUploadArea.addEventListener('dragleave', (e) => {
    e.preventDefault();
    fileUploadArea.style.borderColor = '#dee2e6';
    fileUploadArea.style.background = '#f8f9fa';
});

fileUploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    fileUploadArea.style.borderColor = '#dee2e6';
    fileUploadArea.style.background = '#f8f9fa';
    
    const files = Array.from(e.dataTransfer.files);
    handleFileUpload(files);
});

fileInput.addEventListener('change', (e) => {
    const files = Array.from(e.target.files);
    handleFileUpload(files);
});

function handleFileUpload(files) {
    <?php if (isset($page['id'])): ?>
    const formData = new FormData();
    files.forEach(file => formData.append('files[]', file));
    formData.append('page_id', '<?= $page['id'] ?>');
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('public_pages/upload-files') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            data.files.forEach(file => addFileToDisplay(file));
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Error al subir archivos', 'error');
    });
    <?php else: ?>
    Swal.fire('Información', 'Guarda la página primero para poder subir archivos', 'info');
    <?php endif; ?>
}

function addFileToDisplay(file) {
    const fileItem = document.createElement('div');
    fileItem.className = 'file-item';
    fileItem.dataset.fileId = file.id;

    let filePreview = '';
    if (file.file_type === 'image') {
        filePreview = `<img src="${file.url}" alt="${file.original_name}">`;
    } else {
        filePreview = `
            <div class="file-placeholder d-flex align-items-center justify-content-center" style="height: 100px; background: #f8f9fa;">
                <i class="bx bx-file" style="font-size: 2rem; color: #6c757d;"></i>
            </div>
        `;
    }

    fileItem.innerHTML = `
        ${filePreview}
        <div class="file-info">
            <div class="text-truncate">${file.original_name}</div>
            <small class="text-muted">${file.file_type}</small>
        </div>
        <button type="button" class="remove-file" onclick="removeFile(${file.id})">×</button>
    `;

    uploadedFiles.appendChild(fileItem);
}

function removeFile(fileId) {
    Swal.fire({
        title: '¿Eliminar archivo?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url('public_pages/delete-file') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `file_id=${fileId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`[data-file-id="${fileId}"]`).remove();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}

function restoreVersion(pageId, versionNumber) {
    Swal.fire({
        title: '¿Restaurar versión?',
        text: `¿Quieres restaurar la versión ${versionNumber}? Los cambios actuales se perderán.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url('public_pages/restore-version') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `page_id=${pageId}&version_number=${versionNumber}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Éxito', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}

// Form submission
document.getElementById('pageForm').addEventListener('submit', function(e) {
    // Update content from Quill editor
    document.getElementById('content').value = quill.root.innerHTML;
});

// No additional JavaScript needed for native HTML checkboxes
</script>
<?= $this->endSection() ?>
