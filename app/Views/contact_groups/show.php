<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.contact_group_details') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= esc($group['name']) ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.contact_group_details') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><?= esc($group['name']) ?></h4>
                
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
                        <li class="breadcrumb-item active"><?= esc($group['name']) ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Group Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><?= lang('App.contact_group_details') ?></h5>
                </div>
                <div class="card-body">
                    <!-- Group Header -->
                    <div class="d-flex align-items-center mb-3">
                        <span class="group-color-indicator me-2" 
                              style="width: 24px; height: 24px; border-radius: 50%; background-color: <?= esc($group['color']) ?>; border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></span>
                        <i data-feather="<?= esc($group['icon']) ?>" style="width: 20px; height: 20px; color: <?= esc($group['color']) ?>;" class="me-2"></i>
                        <h5 class="mb-0"><?= esc($group['name']) ?></h5>
                    </div>

                    <!-- Group Description -->
                    <p class="text-muted mb-3">
                        <?= esc($group['description'] ?: 'Sin descripción') ?>
                    </p>

                    <!-- Group Stats -->
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-primary mb-1"><?= count($users) ?></h4>
                                <p class="text-muted mb-0 small">Usuarios Asignados</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-info mb-1"><?= count($group['permissions'] ?? []) ?></h4>
                                <p class="text-muted mb-0 small">Permisos Activos</p>
                            </div>
                        </div>
                    </div>

                    <!-- Group Status -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted"><?= lang('App.status') ?>:</span>
                        <span class="badge <?= $group['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $group['is_active'] ? lang('App.active') : lang('App.inactive') ?>
                        </span>
                    </div>

                    <!-- Group Actions -->
                    <div class="d-grid gap-2">
                        <a href="<?= base_url("contact-groups/{$group['id']}/edit") ?>" class="btn btn-primary">
                            <i data-feather="edit" class="me-1"></i>
                            <?= lang('App.edit_contact_group') ?>
                        </a>
                        <button type="button" class="btn btn-success" onclick="openInvitationModal(<?= $group['id'] ?>, '<?= esc($group['name']) ?>')">
                            <i data-feather="mail" class="me-1"></i>
                            Enviar Invitación
                        </button>
                        <div class="btn-group" role="group">
                            <a href="<?= base_url('contact-groups') ?>" class="btn btn-light">
                                <i data-feather="arrow-left" class="me-1"></i>
                                <?= lang('App.back') ?>
                            </a>
                            <button type="button" class="btn btn-danger" onclick="deleteGroup(<?= $group['id'] ?>, '<?= esc($group['name']) ?>')">
                                <i data-feather="trash-2" class="me-1"></i>
                                <?= lang('App.delete') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions Card -->
            <?php if (!empty($group['permissions'])): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= lang('App.group_permissions') ?></h5>
                    </div>
                    <div class="card-body">
                        <?php 
                        $permissionsByCategory = [];
                        foreach ($group['permissions'] as $permission) {
                            $permissionsByCategory[$permission['category']][] = $permission;
                        }
                        ?>
                        <?php foreach ($permissionsByCategory as $category => $perms): ?>
                            <div class="mb-3">
                                <h6 class="text-primary mb-2"><?= esc(ucfirst($category)) ?></h6>
                                <?php foreach ($perms as $permission): ?>
                                    <div class="d-flex align-items-center mb-1">
                                        <i data-feather="check" class="text-success me-2" style="width: 14px; height: 14px;"></i>
                                        <small><?= esc($permission['name']) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Users in Group -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><?= lang('App.group_users') ?></h5>
                    <span class="badge bg-primary"><?= count($users) ?> usuarios</span>
                </div>
                <div class="card-body">
                    <?php if (!empty($users)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><?= lang('App.name') ?></th>
                                        <th><?= lang('App.email') ?></th>
                                        <th><?= lang('App.username') ?></th>
                                        <th>Cliente</th>
                                        <th>Asignado el</th>
                                        <th><?= lang('App.actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                            <?= strtoupper(substr($user['first_name'], 0, 1)) ?>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h6>
                                                        <small class="text-muted"><?= esc($user['username']) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= esc($user['email']) ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?= esc($user['username']) ?></span>
                                            </td>
                                            <td>
                                                <?php if (!empty($user['client_name'])): ?>
                                                    <span class="text-primary"><?= esc($user['client_name']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin cliente</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y', strtotime($user['assigned_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical" style="width: 14px; height: 14px;"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="<?= base_url("contacts/edit/{$user['id']}") ?>">
                                                            <i data-feather="edit" class="me-2" style="width: 14px; height: 14px;"></i>
                                                            Editar Contacto
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger" href="#" onclick="removeUserFromGroup(<?= $user['id'] ?>, <?= $group['id'] ?>, '<?= esc($user['first_name'] . ' ' . $user['last_name']) ?>')">
                                                            <i data-feather="user-minus" class="me-2" style="width: 14px; height: 14px;"></i>
                                                            Remover del Grupo
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i data-feather="users" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                            <h5 class="text-muted"><?= lang('App.no_users_assigned') ?></h5>
                            <p class="text-muted">Este grupo no tiene usuarios asignados actualmente.</p>
                            <a href="<?= base_url('contacts') ?>" class="btn btn-primary">
                                <i data-feather="plus" class="me-1"></i>
                                Asignar Usuarios
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invitation Modal -->
<div class="modal fade" id="invitationModal" tabindex="-1" aria-labelledby="invitationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invitationModalLabel">
                    <i data-feather="mail" class="me-2"></i>
                    Enviar Invitación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="invitationForm" action="<?= base_url('contact-invitations/send') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" id="assigned_group_id" name="assigned_group_id" value="">
                
                <div class="modal-body">
                    <!-- Group Info -->
                    <div class="alert alert-info d-flex align-items-center" id="groupInfo" style="display: none;">
                        <div class="group-color-indicator me-2" id="modalGroupColor" 
                             style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></div>
                        <i data-feather="users" id="modalGroupIcon" class="me-2"></i>
                        <div>
                            <strong>Grupo seleccionado:</strong> <span id="modalGroupName"></span>
                            <br>
                            <small class="text-muted">El contacto será asignado automáticamente a este grupo</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required 
                                       placeholder="contacto@ejemplo.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_id" class="form-label">Cliente</label>
                                <select class="form-select" id="client_id" name="client_id">
                                    <option value="">Seleccionar cliente (opcional)</option>
                                    <!-- Clients will be loaded via AJAX -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       placeholder="Nombre (opcional)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       placeholder="Apellido (opcional)">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje personal (opcional)</label>
                        <textarea class="form-control" id="message" name="message" rows="3" 
                                  placeholder="Agrega un mensaje personalizado para la invitación..."></textarea>
                        <div class="form-text">Este mensaje aparecerá en el email de invitación</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i data-feather="send" class="me-1"></i>
                        Enviar Invitación
                    </button>
                </div>
            </form>
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
    
    // Load clients for dropdown
    loadClients();
});

function openInvitationModal(groupId, groupName) {
    // Set group info
    document.getElementById('assigned_group_id').value = groupId;
    document.getElementById('modalGroupName').textContent = groupName;
    
    // Set group color and icon (get from current page)
    const groupColorElement = document.querySelector('.group-color-indicator');
    if (groupColorElement) {
        const groupColor = groupColorElement.style.backgroundColor;
        document.getElementById('modalGroupColor').style.backgroundColor = groupColor;
    }
    
    // Show group info
    document.getElementById('groupInfo').style.display = 'flex';
    
    // Reset form
    document.getElementById('invitationForm').reset();
    document.getElementById('assigned_group_id').value = groupId;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('invitationModal'));
    modal.show();
    
    // Refresh feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function loadClients() {
    fetch('<?= base_url('clients/get_clients_json') ?>')
        .then(response => response.json())
        .then(data => {
            const clientSelect = document.getElementById('client_id');
            clientSelect.innerHTML = '<option value="">Seleccionar cliente (opcional)</option>';
            
            data.forEach(client => {
                const option = document.createElement('option');
                option.value = client.id;
                option.textContent = client.name;
                clientSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading clients:', error);
        });
}

function deleteGroup(groupId, groupName) {
    Swal.fire({
        title: '<?= lang('App.are_you_sure') ?>',
        text: `¿Quieres eliminar el grupo "${groupName}"? Esta acción no se puede deshacer.`,
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
            groupInput.value = groupId;
            form.appendChild(groupInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function removeUserFromGroup(userId, groupId, userName) {
    Swal.fire({
        title: '¿Remover usuario del grupo?',
        text: `¿Quieres remover a "${userName}" de este grupo?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, remover',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX call to remove user
            fetch('<?= base_url('contact-groups/remove-user') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    user_id: userId,
                    group_id: groupId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    Swal.fire('Removido', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Error al procesar la solicitud', 'error');
            });
        }
    });
}
</script>
<?= $this->endSection() ?> 