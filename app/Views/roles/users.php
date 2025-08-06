<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Portal - My Detail Area<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= esc($role['title']) ?> - Users<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>Role Management<?= $this->endSection() ?>

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
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1">
                    <i class="ri-shield-user-line me-2"></i>
                    Users in Role: <?= esc($role['title']) ?>
                </h4>
                <div class="flex-shrink-0">
                    <a href="<?= base_url('roles/add-user/' . $role['id']) ?>" class="btn btn-primary btn-sm me-2">
                        <i class="ri-user-add-line align-bottom me-1"></i> Add User
                    </a>
                    <a href="<?= base_url('roles') ?>" class="btn btn-light btn-sm">
                        <i class="ri-arrow-left-line align-bottom me-1"></i> Back to Roles
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($users)): ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ri-user-line display-4 text-muted"></i>
                        </div>
                        <h5 class="text-muted">No users found in this role</h5>
                        <p class="text-muted mb-3">Start by adding users to this role.</p>
                        <a href="<?= base_url('roles/add-user/' . $role['id']) ?>" class="btn btn-primary btn-sm">
                            <i class="ri-user-add-line me-1"></i> Add First User
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">User</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                        <?= strtoupper(substr($user->username ?? 'U', 0, 1)) ?>
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="fw-medium"><?= esc($user->username ?? 'N/A') ?></span>
                                                    <div class="text-muted small">ID: <?= esc($user->id) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?= esc($user->email ?? 'N/A') ?></span>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?= esc(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('roles/remove-user/' . $role['id'] . '/' . $user->id) ?>" 
                                               class="link-danger fs-15 remove-user-btn" 
                                               data-user-name="<?= esc($user->username ?? 'User') ?>"
                                               data-role-name="<?= esc($role['title']) ?>"
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="Remove user from role">
                                                <i class="ri-user-unfollow-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Total users in this role: <strong><?= count($users) ?></strong>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Role-specific styling */
.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--bs-body-color);
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

.bg-primary-subtle {
    background-color: rgba(64, 81, 137, 0.1) !important;
}

.text-primary {
    color: #405189 !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.04);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Handle remove user links with confirmation
    document.querySelectorAll('.remove-user-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const userName = this.getAttribute('data-user-name');
            const roleName = this.getAttribute('data-role-name');
            const url = this.getAttribute('href');
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Remove User from Role?',
                    text: `Are you sure you want to remove "${userName}" from the "${roleName}" role?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f06548',
                    cancelButtonColor: '#74788d',
                    confirmButtonText: 'Yes, remove user!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            } else {
                // Fallback confirmation
                if (confirm(`Are you sure you want to remove "${userName}" from the "${roleName}" role?`)) {
                    window.location.href = url;
                }
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
