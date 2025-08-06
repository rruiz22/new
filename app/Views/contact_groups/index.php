<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.contact_groups_management') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.contact_groups_management') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.contact_groups') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .group-color-indicator {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        border: 2px solid #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    
    .group-icon {
        width: 18px;
        height: 18px;
        margin-right: 8px;
    }
    
    .group-stats {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .badge-users {
        background: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
    }
    
    .btn-action {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .group-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #e3e6f0;
    }
    
    .group-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>

<div class="container-fluid">
    <!-- Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1"><?= lang('App.contact_groups') ?></h5>
                    <p class="text-muted mb-0"><?= lang('App.manage_contact_groups_desc') ?></p>
                </div>
                <div>
                    <a href="<?= base_url('contact-groups/create') ?>" class="btn btn-primary">
                        <i data-feather="plus" class="icon-dual-sm me-1"></i>
                        <?= lang('App.create_contact_group') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups Grid -->
    <div class="row">
        <?php if (!empty($groups)): ?>
            <?php foreach ($groups as $group): ?>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card group-card h-100">
                        <div class="card-body">
                            <!-- Group Header -->
                            <div class="d-flex align-items-center mb-3">
                                <span class="group-color-indicator" style="background-color: <?= esc($group['color']) ?>"></span>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 d-flex align-items-center">
                                        <i data-feather="<?= esc($group['icon']) ?>" class="group-icon" style="color: <?= esc($group['color']) ?>"></i>
                                        <?= esc($group['name']) ?>
                                    </h6>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                        <i data-feather="more-vertical" style="width: 14px; height: 14px;"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?= base_url("contact-groups/{$group['id']}") ?>">
                                            <i data-feather="eye" class="me-2" style="width: 14px; height: 14px;"></i>
                                            <?= lang('App.view_details') ?>
                                        </a>
                                        <a class="dropdown-item" href="<?= base_url("contact-groups/{$group['id']}/edit") ?>">
                                            <i data-feather="edit" class="me-2" style="width: 14px; height: 14px;"></i>
                                            <?= lang('App.edit') ?>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="#" onclick="deleteGroup(<?= $group['id'] ?>, '<?= esc($group['name']) ?>')">
                                            <i data-feather="trash-2" class="me-2" style="width: 14px; height: 14px;"></i>
                                            <?= lang('App.delete') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Group Description -->
                            <p class="text-muted small mb-3">
                                <?= esc($group['description'] ?? lang('App.no_description')) ?>
                            </p>

                            <!-- Group Stats -->
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge badge-users">
                                    <i data-feather="users" style="width: 12px; height: 12px;" class="me-1"></i>
                                    <?= $group['user_count'] ?? 0 ?> <?= lang('App.users') ?>
                                </span>
                                <span class="group-stats">
                                    <?= $group['is_active'] ? '<i data-feather="check-circle" style="width: 14px; height: 14px; color: #28a745;"></i>' : '<i data-feather="x-circle" style="width: 14px; height: 14px; color: #dc3545;"></i>' ?>
                                    <?= $group['is_active'] ? lang('App.active') : lang('App.inactive') ?>
                                </span>
                            </div>
                        </div>

                        <!-- Group Actions -->
                        <div class="card-footer bg-light border-top-0">
                            <div class="d-flex gap-2">
                                <a href="<?= base_url("contact-groups/{$group['id']}") ?>" class="btn btn-sm btn-outline-primary flex-fill">
                                    <i data-feather="eye" style="width: 14px; height: 14px;" class="me-1"></i>
                                    <?= lang('App.view') ?>
                                </a>
                                <a href="<?= base_url("contact-groups/{$group['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary flex-fill">
                                    <i data-feather="edit" style="width: 14px; height: 14px;" class="me-1"></i>
                                    <?= lang('App.edit') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="empty-state">
                            <i data-feather="shield" class="mb-3"></i>
                            <h5 class="mb-3"><?= lang('App.no_contact_groups') ?></h5>
                            <p class="mb-4"><?= lang('App.create_first_contact_group_desc') ?></p>
                            <a href="<?= base_url('contact-groups/create') ?>" class="btn btn-primary">
                                <i data-feather="plus" class="me-1"></i>
                                <?= lang('App.create_contact_group') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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
});

function deleteGroup(groupId, groupName) {
    Swal.fire({
        title: '<?= lang('App.are_you_sure') ?>',
        text: `<?= lang('App.delete_group_confirmation') ?> "${groupName}"? <?= lang('App.action_irreversible') ?>`,
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
</script>
<?= $this->endSection() ?>

<?= $this->include('partials/footer') ?> 