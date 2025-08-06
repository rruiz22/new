<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Portal - My Detail Area<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>App.edit_role<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>App.roles<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row justify-content-center">
                        <div class="col-xxl-9">
                            <div class="card">
                                <div class="card-header bg-soft-success">
                                    <h4 class="card-title mb-0">
                                        <i data-feather="edit" class="icon-sm me-1"></i>
                                        <?= lang('App.edit_role') ?>: <?= esc($role['title']) ?>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('roles/update/' . $role['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <!-- Role Basic Info -->
                                                <div class="card border">
                                                    <div class="card-header">
                                                        <h5 class="card-title mb-0">
                                                            <i data-feather="info" class="icon-sm me-1"></i>
                                                            <?= lang('App.role_information') ?>
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label"><?= lang('App.role_name') ?> <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control <?= isset(session('errors')['name']) ? 'is-invalid' : '' ?>" id="name" name="name" 
                                                                   value="<?= old('name', $role['title']) ?>" required
                                                                   placeholder="e.g., manager, staff, viewer">
                                                            <?php if (isset(session('errors')['name'])): ?>
                                                                <div class="invalid-feedback">
                                                                    <?= session('errors')['name'] ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="form-text"><?= lang('App.role_name_help') ?></div>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="title" class="form-label"><?= lang('App.role_title') ?> <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="title" name="title" 
                                                                   value="<?= old('title', $role['title']) ?>" required
                                                                   placeholder="e.g., Manager, Staff Member, Viewer">
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="description" class="form-label"><?= lang('App.role_description') ?></label>
                                                            <textarea class="form-control" id="description" name="description" rows="3"
                                                                      placeholder="Brief description of this role's purpose"><?= old('description', $role['description']) ?></textarea>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="color" class="form-label"><?= lang('App.role_color') ?></label>
                                                                    <input type="color" class="form-control form-control-color" 
                                                                           id="color" name="color" value="<?= old('color', $role['color'] ?: '#405189') ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="sort_order" class="form-label">Sort Order</label>
                                                                    <input type="number" class="form-control" id="sort_order" 
                                                                           name="sort_order" value="<?= old('sort_order', $role['sort_order'] ?: 0) ?>" min="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Permissions -->
                                                <div class="card border">
                                                    <div class="card-header">
                                                        <h5 class="card-title mb-0">
                                                            <i data-feather="shield" class="icon-sm me-1"></i>
                                                            <?= lang('App.permissions') ?>
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <?php foreach ($availablePermissions as $module => $permissions): ?>
                                                                <div class="col-md-6 mb-4">
                                                                    <div class="border rounded p-3">
                                                                        <h6 class="text-uppercase fw-semibold mb-3">
                                                                            <?php 
                                                                                $moduleIcons = [
                                                                                    'dashboard' => 'home',
                                                                                    'users' => 'users',
                                                                                    'staff' => 'user-check',
                                                                                    'clients' => 'briefcase',
                                                                                    'contacts' => 'user',
                                                                                    'todo' => 'check-square',
                                                                                    'roles' => 'shield',
                                                                                    'settings' => 'settings'
                                                                                ];
                                                                                $icon = $moduleIcons[$module] ?? 'circle';
                                                                            ?>
                                                                            <i data-feather="<?= $icon ?>" class="icon-sm me-1"></i>
                                                                            <?= ucfirst($module) ?>
                                                                        </h6>
                                                                        <?php foreach ($permissions as $permission => $label): ?>
                                                                            <div class="form-check mb-2">
                                                                                <input class="form-check-input" type="checkbox" 
                                                                                       id="perm_<?= str_replace('.', '_', $permission) ?>" 
                                                                                       name="permissions[]" value="<?= $permission ?>"
                                                                                       <?= in_array($permission, old('permissions', $rolePermissions)) ? 'checked' : '' ?>>
                                                                                <label class="form-check-label" for="perm_<?= str_replace('.', '_', $permission) ?>">
                                                                                    <?= $label ?>
                                                                                </label>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllPermissions">
                                                                <i data-feather="check-square" class="icon-sm me-1"></i>
                                                                <?= lang('App.select_all') ?>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="clearAllPermissions">
                                                                <i data-feather="square" class="icon-sm me-1"></i>
                                                                <?= lang('App.clear_all') ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                                <!-- Settings -->
                                                <div class="card border">
                                                    <div class="card-header">
                                                        <h5 class="card-title mb-0">
                                                            <i data-feather="settings" class="icon-sm me-1"></i>
                                                            <?= lang('App.settings') ?>
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="mb-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="is_active" 
                                                                       name="is_active" value="1" <?= old('is_active', $role['is_active']) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="is_active">
                                                                    <strong><?= lang('App.active_role') ?></strong>
                                                                    <div class="text-muted small"><?= lang('App.role_active_desc') ?></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="show_in_staff_form" 
                                                                       name="show_in_staff_form" value="1" <?= old('show_in_staff_form', $role['show_in_staff_form']) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="show_in_staff_form">
                                                                    <strong><?= lang('App.show_in_staff_form') ?></strong>
                                                                    <div class="text-muted small"><?= lang('App.role_staff_form_desc') ?></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Preview -->
                                                <div class="card border">
                                                    <div class="card-header">
                                                        <h5 class="card-title mb-0">
                                                            <i data-feather="eye" class="icon-sm me-1"></i>
                                                            <?= lang('App.preview') ?>
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center" id="rolePreview">
                                                            <div class="avatar-xs me-2">
                                                                <span class="avatar-title rounded-circle" id="previewColor" 
                                                                      style="background-color: <?= $role['color'] ?: '#405189' ?>20; color: <?= $role['color'] ?: '#405189' ?>;">
                                                                    <i data-feather="shield" class="icon-sm"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <span class="fw-medium" id="previewTitle"><?= esc($role['title']) ?></span>
                                                                <div class="text-muted small" id="previewName"><?= esc($role['title']) ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            <small class="text-muted" id="previewDescription"><?= esc($role['description'] ?: 'No description') ?></small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Role Info -->
                                                <div class="card border">
                                                    <div class="card-header">
                                                        <h5 class="card-title mb-0">
                                                            <i data-feather="info" class="icon-sm me-1"></i>
                                                            <?= lang('App.role_information') ?>
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="mb-2">
                                                            <small class="text-muted"><?= lang('App.created') ?>:</small>
                                                            <div><?= date('M d, Y', strtotime($role['created_at'])) ?></div>
                                                        </div>
                                                        <div class="mb-2">
                                                            <small class="text-muted"><?= lang('App.last_updated') ?>:</small>
                                                            <div><?= date('M d, Y', strtotime($role['updated_at'])) ?></div>
                                                        </div>
                                                        <div class="mb-2">
                                                            <small class="text-muted"><?= lang('App.current_permissions') ?>:</small>
                                                            <div>
                                                                <span class="badge bg-soft-info text-info">
                                                                    <?= count($rolePermissions) ?> permissions
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <a href="<?= base_url('roles') ?>" class="btn btn-light">
                                                        <i data-feather="x" class="icon-sm me-1"></i>
                                                        <?= lang('App.cancel') ?>
                                                    </a>
                                                    <button type="submit" class="btn btn-success">
                                                        <i data-feather="save" class="icon-sm me-1"></i>
                                                        <?= lang('App.update') ?> <?= lang('App.role') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize feather icons
            feather.replace();
            
            // Live preview functionality
            function updatePreview() {
                const title = document.getElementById('title').value || '<?= esc($role['title']) ?>';
                const name = document.getElementById('name').value || '<?= esc($role['title']) ?>';
                const description = document.getElementById('description').value || '<?= esc($role['description'] ?: 'No description') ?>';
                const color = document.getElementById('color').value || '<?= $role['color'] ?: '#405189' ?>';
                
                document.getElementById('previewTitle').textContent = title;
                document.getElementById('previewName').textContent = name;
                document.getElementById('previewDescription').textContent = description;
                
                const previewColor = document.getElementById('previewColor');
                previewColor.style.backgroundColor = color + '20';
                previewColor.style.color = color;
            }
            
            // Add event listeners for live preview
            ['title', 'name', 'description', 'color'].forEach(id => {
                document.getElementById(id).addEventListener('input', updatePreview);
            });
            
            // Select/Clear all permissions
            document.getElementById('selectAllPermissions').addEventListener('click', function() {
                document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                    checkbox.checked = true;
                });
                feather.replace();
            });
            
            document.getElementById('clearAllPermissions').addEventListener('click', function() {
                document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                feather.replace();
            });
            
            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const name = document.getElementById('name').value;
                const title = document.getElementById('title').value;
                
                if (!name || !title) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }
                
                // Validate name format
                if (!/^[a-z0-9_]+$/.test(name)) {
                    e.preventDefault();
                    alert('Role name can only contain lowercase letters, numbers, and underscores.');
                    return false;
                }
            });
        });
</script>
<?= $this->endSection() ?>
