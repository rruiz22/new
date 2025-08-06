<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.edit_contact_group') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.edit_contact_group') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.edit_contact_group') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><?= lang('App.edit_contact_group') ?></h4>
                
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('dashboard') ?>"><?= lang('App.dashboard') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('contacts') ?>"><?= lang('App.contacts') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('contact-groups') ?>"><?= lang('App.contact_groups') ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?= lang('App.edit') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0"><?= lang('App.edit_contact_group') ?></h4>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?= form_open('contact-groups/update/' . $group['id'], ['id' => 'groupForm']) ?>
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Basic Information -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?= lang('App.contact_group_details') ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label"><?= lang('App.group_name') ?> <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" 
                                                       value="<?= esc($group['name']) ?>" required>
                                                <div class="form-text">Nombre único para identificar el grupo</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sort_order" class="form-label">Orden</label>
                                                <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                                       value="<?= esc($group['sort_order']) ?>" min="0">
                                                <div class="form-text">Orden de visualización (menor número = mayor prioridad)</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label"><?= lang('App.group_description') ?></label>
                                        <textarea class="form-control" id="description" name="description" rows="3" 
                                                  placeholder="Descripción del grupo y sus responsabilidades..."><?= esc($group['description']) ?></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="color" class="form-label"><?= lang('App.group_color') ?></label>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color" id="color" name="color" 
                                                           value="<?= esc($group['color']) ?>" title="Seleccionar color">
                                                    <input type="text" class="form-control" id="color_text" 
                                                           value="<?= esc($group['color']) ?>" readonly>
                                                </div>
                                                <div class="form-text">Color para identificar visualmente el grupo</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="icon" class="form-label"><?= lang('App.group_icon') ?></label>
                                                <select class="form-select" id="icon" name="icon">
                                                    <option value="shield" <?= $group['icon'] == 'shield' ? 'selected' : '' ?>>🛡️ Shield</option>
                                                    <option value="users" <?= $group['icon'] == 'users' ? 'selected' : '' ?>>👥 Users</option>
                                                    <option value="eye" <?= $group['icon'] == 'eye' ? 'selected' : '' ?>>👁️ Eye</option>
                                                    <option value="star" <?= $group['icon'] == 'star' ? 'selected' : '' ?>>⭐ Star</option>
                                                    <option value="settings" <?= $group['icon'] == 'settings' ? 'selected' : '' ?>>⚙️ Settings</option>
                                                    <option value="lock" <?= $group['icon'] == 'lock' ? 'selected' : '' ?>>🔒 Lock</option>
                                                    <option value="key" <?= $group['icon'] == 'key' ? 'selected' : '' ?>>🔑 Key</option>
                                                    <option value="crown" <?= $group['icon'] == 'crown' ? 'selected' : '' ?>>👑 Crown</option>
                                                </select>
                                                <div class="form-text">Ícono representativo del grupo</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                                   <?= $group['is_active'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="is_active">
                                                <?= lang('App.active') ?>
                                            </label>
                                            <div class="form-text">Los grupos inactivos no pueden ser asignados a nuevos usuarios</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permissions -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?= lang('App.group_permissions') ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php if (!empty($permissions_by_category)): ?>
                                            <?php foreach ($permissions_by_category as $category => $perms): ?>
                                                <div class="col-md-6 mb-4">
                                                    <h6 class="text-primary mb-3"><?= esc(ucfirst($category)) ?></h6>
                                                    <?php foreach ($perms as $permission): ?>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   id="permission_<?= $permission['id'] ?>" 
                                                                   name="permissions[]" 
                                                                   value="<?= $permission['id'] ?>"
                                                                   <?= in_array($permission['id'], $group_permissions ?? []) ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="permission_<?= $permission['id'] ?>">
                                                                <strong><?= esc($permission['name']) ?></strong>
                                                                <br>
                                                                <small class="text-muted"><?= esc($permission['description']) ?></small>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <i data-feather="info" class="me-2"></i>
                                                    No hay permisos disponibles para configurar.
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex gap-2 mt-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllPermissions()">
                                            <?= lang('App.select_all') ?>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAllPermissions()">
                                            <?= lang('App.clear_all') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Preview Card -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?= lang('App.preview') ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3" id="group-preview">
                                        <span class="group-color-indicator me-2" id="preview-color" 
                                              style="width: 20px; height: 20px; border-radius: 50%; background-color: <?= esc($group['color']) ?>; border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                                        <i data-feather="<?= esc($group['icon']) ?>" id="preview-icon" style="width: 18px; height: 18px; color: <?= esc($group['color']) ?>;" class="me-2"></i>
                                        <h6 class="mb-0" id="preview-name"><?= esc($group['name']) ?></h6>
                                    </div>
                                    <p class="text-muted small" id="preview-description">
                                        <?= esc($group['description'] ?: 'Sin descripción') ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-light text-dark border">
                                            <i data-feather="users" style="width: 12px; height: 12px;" class="me-1"></i>
                                            <?= $group['user_count'] ?? 0 ?> usuarios
                                        </span>
                                        <span id="preview-status">
                                            <?= $group['is_active'] ? '<i data-feather="check-circle" style="width: 14px; height: 14px; color: #28a745;"></i> Activo' : '<i data-feather="x-circle" style="width: 14px; height: 14px; color: #dc3545;"></i> Inactivo' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Group Statistics -->
                            <?php if (!empty($group['user_count'])): ?>
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><?= lang('App.group_users') ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <h3 class="text-primary"><?= $group['user_count'] ?></h3>
                                            <p class="text-muted mb-0">Usuarios asignados actualmente</p>
                                        </div>
                                        <div class="mt-3">
                                            <a href="<?= base_url("contact-groups/{$group['id']}") ?>" class="btn btn-outline-primary btn-sm w-100">
                                                <i data-feather="users" class="me-1" style="width: 14px; height: 14px;"></i>
                                                Ver usuarios del grupo
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('contact-groups') ?>" class="btn btn-light">
                                    <i data-feather="arrow-left" class="me-1"></i>
                                    <?= lang('App.back') ?>
                                </a>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-danger" onclick="deleteGroup()">
                                        <i data-feather="trash-2" class="me-1"></i>
                                        <?= lang('App.delete') ?>
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="save" class="me-1"></i>
                                        <?= lang('App.save') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Update preview when inputs change
    document.getElementById('name').addEventListener('input', updatePreview);
    document.getElementById('description').addEventListener('input', updatePreview);
    document.getElementById('color').addEventListener('change', updatePreview);
    document.getElementById('icon').addEventListener('change', updatePreview);
    document.getElementById('is_active').addEventListener('change', updatePreview);

    // Color sync
    document.getElementById('color').addEventListener('change', function() {
        document.getElementById('color_text').value = this.value;
    });
});

function updatePreview() {
    const name = document.getElementById('name').value || 'Nombre del Grupo';
    const description = document.getElementById('description').value || 'Sin descripción';
    const color = document.getElementById('color').value;
    const icon = document.getElementById('icon').value;
    const isActive = document.getElementById('is_active').checked;

    // Update preview elements
    document.getElementById('preview-name').textContent = name;
    document.getElementById('preview-description').textContent = description;
    document.getElementById('preview-color').style.backgroundColor = color;
    
    // Update icon
    const iconElement = document.getElementById('preview-icon');
    iconElement.setAttribute('data-feather', icon);
    iconElement.style.color = color;
    
    // Update status
    const statusElement = document.getElementById('preview-status');
    if (isActive) {
        statusElement.innerHTML = '<i data-feather="check-circle" style="width: 14px; height: 14px; color: #28a745;"></i> Activo';
    } else {
        statusElement.innerHTML = '<i data-feather="x-circle" style="width: 14px; height: 14px; color: #dc3545;"></i> Inactivo';
    }

    // Refresh feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function selectAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(cb => cb.checked = true);
}

function clearAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(cb => cb.checked = false);
}

function deleteGroup() {
    Swal.fire({
        title: '<?= lang('App.are_you_sure') ?>',
        text: '¿Quieres eliminar este grupo? Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<?= lang('App.yes_delete') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= base_url('contact-groups/delete') ?>';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);
            
            // Add group ID
            const groupInput = document.createElement('input');
            groupInput.type = 'hidden';
            groupInput.name = 'id';
            groupInput.value = '<?= $group['id'] ?>';
            form.appendChild(groupInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
<?= $this->endSection() ?> 