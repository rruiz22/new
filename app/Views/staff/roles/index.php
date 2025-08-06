<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Staff Roles' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">
                    <i data-feather="shield"></i> Staff Roles Management
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Staff Roles</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->has('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-all me-2"></i>
        <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="mdi mdi-block-helper me-2"></i>
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="row">
        <!-- Roles List -->
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">
                            <i data-feather="shield" class="me-2"></i>Staff Roles
                        </h4>
                        <a href="<?= base_url('/staff-roles/create') ?>" class="btn btn-primary">
                            <i data-feather="plus" class="me-1"></i>Create Role
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($roles)): ?>
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-4">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-20">
                                <i data-feather="shield"></i>
                            </div>
                        </div>
                        <h5 class="font-size-15">No Roles Found</h5>
                        <p class="text-muted mb-3">Create your first staff role to manage permissions and access levels.</p>
                        <a href="<?= base_url('/staff-roles/create') ?>" class="btn btn-primary">
                            <i data-feather="plus" class="me-1"></i>Create First Role
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Role</th>
                                    <th>Level</th>
                                    <th>Users</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roles as $role): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <div class="avatar-title rounded-circle font-size-16" 
                                                     style="background-color: <?= $role['color'] ?>; color: white;">
                                                    <i data-feather="<?= $role['icon'] ?>"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="font-size-14 mb-1">
                                                    <a href="<?= base_url('/staff-roles/' . $role['id']) ?>" 
                                                       class="text-body"><?= esc($role['name']) ?></a>
                                                </h6>
                                                <p class="text-muted mb-0 font-size-13">
                                                    <?= esc($role['description']) ?: 'No description' ?>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info font-size-12">
                                            Level <?= $role['level'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary font-size-12">
                                            <?= $role['user_count'] ?? 0 ?> users
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($role['is_active']): ?>
                                            <span class="badge bg-success font-size-12">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary font-size-12">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-muted font-size-13">
                                            <?= date('M j, Y', strtotime($role['created_at'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('/staff-roles/' . $role['id']) ?>" 
                                               class="btn btn-sm btn-soft-primary" title="View">
                                                <i data-feather="eye"></i>
                                            </a>
                                            <a href="<?= base_url('/staff-roles/' . $role['id'] . '/edit') ?>" 
                                               class="btn btn-sm btn-soft-info" title="Edit">
                                                <i data-feather="edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-soft-danger delete-role" 
                                                    data-role-id="<?= $role['id'] ?>" 
                                                    data-role-name="<?= esc($role['name']) ?>"
                                                    title="Delete">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i data-feather="bar-chart-2" class="me-2"></i>Quick Stats
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="text-center p-3 border rounded">
                                <h5 class="font-size-18 mb-1"><?= count($roles) ?></h5>
                                <p class="text-muted mb-0">Total Roles</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-center p-3 border rounded">
                                <h5 class="font-size-18 mb-1">
                                    <?= array_sum(array_column($roles, 'user_count')) ?>
                                </h5>
                                <p class="text-muted mb-0">Total Assignments</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="font-size-14 mb-3">Permission Categories</h6>
                        <?php if (!empty($permissions)): ?>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($permissions as $category => $categoryPermissions): ?>
                            <span class="badge bg-light text-dark">
                                <?= ucfirst($category) ?> (<?= count($categoryPermissions) ?>)
                            </span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Role Hierarchy -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i data-feather="layers" class="me-2"></i>Role Hierarchy
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($roles)): ?>
                    <div class="hierarchy-list">
                        <?php 
                        // Sort roles by level descending
                        $sortedRoles = $roles;
                        usort($sortedRoles, function($a, $b) {
                            return $b['level'] - $a['level'];
                        });
                        ?>
                        <?php foreach ($sortedRoles as $role): ?>
                        <div class="d-flex align-items-center mb-3 p-2 border rounded" 
                             style="border-left: 4px solid <?= $role['color'] ?> !important;">
                            <div class="avatar-xs me-3">
                                <div class="avatar-title rounded-circle font-size-12" 
                                     style="background-color: <?= $role['color'] ?>; color: white;">
                                    <i data-feather="<?= $role['icon'] ?>"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h6 class="font-size-13 mb-1"><?= esc($role['name']) ?></h6>
                                <p class="text-muted mb-0 font-size-12">
                                    Level <?= $role['level'] ?> • <?= $role['user_count'] ?? 0 ?> users
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">No roles defined yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-danger-subtle text-danger rounded-circle font-size-20">
                            <i data-feather="alert-triangle"></i>
                        </div>
                    </div>
                    <h5 class="font-size-15 mb-2">Are you sure?</h5>
                    <p class="text-muted mb-0">
                        Do you really want to delete the role "<span id="roleNameToDelete"></span>"? 
                        This action cannot be undone.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteRole">
                    <i data-feather="trash-2" class="me-1"></i>Delete Role
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete role functionality
    let roleToDelete = null;
    
    document.querySelectorAll('.delete-role').forEach(button => {
        button.addEventListener('click', function() {
            roleToDelete = this.dataset.roleId;
            document.getElementById('roleNameToDelete').textContent = this.dataset.roleName;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteRoleModal'));
            modal.show();
        });
    });
    
    document.getElementById('confirmDeleteRole').addEventListener('click', function() {
        if (roleToDelete) {
            // Show loading state
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Deleting...';
            this.disabled = true;
            
            fetch(`<?= base_url('/staff-roles/delete/') ?>${roleToDelete}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error deleting role');
                    // Reset button
                    this.innerHTML = '<i data-feather="trash-2" class="me-1"></i>Delete Role';
                    this.disabled = false;
                    feather.replace();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting role');
                // Reset button
                this.innerHTML = '<i data-feather="trash-2" class="me-1"></i>Delete Role';
                this.disabled = false;
                feather.replace();
            });
        }
    });
    
    // Initialize Feather icons
    feather.replace();
});
</script>

<?= $this->endSection() ?> 