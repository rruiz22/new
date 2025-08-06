<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Portal - My Detail Area<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>App.role_management<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>App.roles<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center"><?= lang('App.role_list') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshTable" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                    <a href="<?= base_url('roles/create') ?>" class="btn btn-primary">
                        <i data-feather="plus" class="icon-sm me-1"></i> <?= lang('App.create_role') ?>
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center"><?= lang('App.role') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.role_description') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.users') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.status') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.show_in_staff_form') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($roles)): ?>
                                <?php foreach ($roles as $role): ?>
                                    <tr>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle" style="background-color: <?= esc($role['color']) ?>20; color: <?= esc($role['color']) ?>;">
                                                        <i data-feather="shield" class="icon-sm"></i>
                                                    </span>
                                                </div>
                                                <div class="text-start">
                                                    <span class="fw-medium text-primary"><?= esc($role['title']) ?></span>
                                                    <div class="text-muted small"><?= esc($role['description']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-start">
                                            <span class="text-muted"><?= esc($role['description'] ?: 'No description') ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('roles/users/' . $role['id']) ?>" class="text-decoration-none">
                                                <span class="badge bg-info-subtle text-info">
                                                    <?= $role['user_count'] ?> users
                                                </span>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch form-switch-md d-flex justify-content-center">
                                                <input class="form-check-input status-toggle" type="checkbox" 
                                                       data-id="<?= $role['id'] ?>" 
                                                       <?= $role['is_active'] ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch form-switch-md d-flex justify-content-center">
                                                <input class="form-check-input staff-form-toggle" type="checkbox" 
                                                       data-id="<?= $role['id'] ?>" 
                                                       <?= $role['show_in_staff_form'] ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="<?= base_url('roles/users/' . $role['id']) ?>" 
                                                   class="link-primary fs-15" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="<?= lang('App.view_users') ?>">
                                                    <i class="ri-team-fill"></i>
                                                </a>
                                                <a href="<?= base_url('roles/edit/' . $role['id']) ?>" 
                                                   class="link-success fs-15" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="<?= lang('App.edit_role') ?>">
                                                    <i class="ri-edit-fill"></i>
                                                </a>
                                                <a href="#" 
                                                   class="link-danger fs-15 delete-role-btn" 
                                                   data-id="<?= $role['id'] ?>" 
                                                   data-name="<?= esc($role['title']) ?>"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="<?= lang('App.delete_role') ?>">
                                                    <i class="ri-delete-bin-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <div class="mb-3">
                                                <i data-feather="shield" class="icon-lg text-muted"></i>
                                            </div>
                                            <h5 class="text-muted">No roles found</h5>
                                            <p class="text-muted mb-3">Start by creating your first custom role.</p>
                                            <a href="<?= base_url('roles/create') ?>" class="btn btn-primary btn-sm">
                                                <i data-feather="plus" class="icon-sm me-1"></i> Create Role
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Clean Velzon card title styling - Same as all_content.php */
.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--bs-body-color);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 767.98px) {
    .card-title {
        font-size: 1.25rem;
    }
}

@media (max-width: 575.98px) {
    .card-title {
        font-size: 1.1rem;
    }
}

/* Action Links - Same style as all_content.php */
.link-primary {
    color: #405189 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-primary:hover {
    color: #2c3e50 !important;
}

.link-success {
    color: #0ab39c !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-success:hover {
    color: #087f69 !important;
}

.link-danger {
    color: #f06548 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-danger:hover {
    color: #d63384 !important;
}

.fs-15 {
    font-size: 15px !important;
}

.gap-2 {
    gap: 0.5rem !important;
}

/* Center table headers - Same as all_content.php */
table thead th {
    text-align: center !important;
}

/* Cursor pointer for tooltip elements */
.cursor-pointer {
    cursor: pointer !important;
}

/* Info badge styling - Same as all_content.php */
.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.text-info {
    color: #0dcaf0 !important;
}

/* Tooltip content styling */
.tooltip-inner {
    max-width: 300px !important;
    text-align: left !important;
}

/* Avatar styling consistency */
.avatar-xs {
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Switch styling consistency */
.form-switch .form-check-input {
    cursor: pointer;
}

.form-switch .form-check-input:focus {
    border-color: #405189;
    box-shadow: 0 0 0 0.25rem rgba(64, 81, 137, 0.25);
}

/* Icon styling consistency */
.icon-sm {
    width: 16px;
    height: 16px;
    stroke-width: 2;
}

.icon-lg {
    width: 48px;
    height: 48px;
    stroke-width: 1.5;
}

/* Table hover effects */
.table-hover tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.04);
}

/* Badge hover effects */
.badge {
    transition: all 0.15s ease;
}

.badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Initialize tooltips
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                html: true,
                trigger: 'hover focus',
                delay: { show: 500, hide: 100 }
            });
        });
    }

    // Refresh table functionality
    const refreshBtn = document.getElementById('refreshTable');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            location.reload();
        });
    }

    // Status toggle functionality
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const roleId = this.getAttribute('data-id');
            const isActive = this.checked;
            
            fetch(`<?= base_url('roles/toggle_status') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: roleId,
                    is_active: isActive,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message || 'Status updated successfully');
                } else {
                    // Revert toggle if failed
                    this.checked = !isActive;
                    showToast('error', data.message || 'Error updating status');
                }
            })
            .catch(error => {
                // Revert toggle if failed
                this.checked = !isActive;
                showToast('error', 'Error updating status');
                console.error('Error:', error);
            });
        });
    });

    // Staff form toggle functionality
    document.querySelectorAll('.staff-form-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const roleId = this.getAttribute('data-id');
            const showInStaffForm = this.checked;
            
            fetch(`<?= base_url('roles/toggle_staff_form') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: roleId,
                    show_in_staff_form: showInStaffForm,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message || 'Setting updated successfully');
                } else {
                    // Revert toggle if failed
                    this.checked = !showInStaffForm;
                    showToast('error', data.message || 'Error updating setting');
                }
            })
            .catch(error => {
                // Revert toggle if failed
                this.checked = !showInStaffForm;
                showToast('error', 'Error updating setting');
                console.error('Error:', error);
            });
        });
    });

    // Delete role functionality
    document.querySelectorAll('.delete-role-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const roleId = this.getAttribute('data-id');
            const roleName = this.getAttribute('data-name');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to delete the role "${roleName}"? This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f06548',
                    cancelButtonColor: '#74788d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch(`<?= base_url('roles/delete/') ?>${roleId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.close();
                                showToast('success', data.message || 'Role deleted successfully');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message || 'Error deleting role',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting role:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error deleting role',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                    }
                });
            } else {
                // Fallback without SweetAlert
                if (confirm(`Are you sure you want to delete the role "${roleName}"?`)) {
                    fetch(`<?= base_url('roles/delete/') ?>${roleId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message || 'Role deleted successfully');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showToast('error', data.message || 'Error deleting role');
                        }
                    })
                    .catch(error => {
                        showToast('error', 'Error deleting role');
                        console.error('Error:', error);
                    });
                }
            }
        });
    });

    // Toast function - Same as all_content.php
    function showToast(type, message) {
        if (typeof Toastify !== 'undefined') {
            const colors = {
                success: "#28a745",
                error: "#dc3545", 
                info: "#17a2b8",
                warning: "#ffc107"
            };
            
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                style: {
                    background: colors[type] || colors.info
                },
            }).showToast();
        } else {
            // Fallback
            alert(message);
        }
    }

    // Make showToast globally available
    window.showToast = showToast;
});
</script>
<?= $this->endSection() ?>
