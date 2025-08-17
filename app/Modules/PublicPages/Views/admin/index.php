<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item active"><?= $title ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS -->
<?= $this->include('partials/datatables-styles') ?>
<style>
.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
}
.privacy-badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
}
.page-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}
.quick-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
}
.quick-stats .stat-item {
    text-align: center;
    flex: 1;
}
.quick-stats .stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #495057;
}
.quick-stats .stat-label {
    color: #6c757d;
    font-size: 0.9rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Quick Stats -->
    <div class="row">
        <div class="col-12">
            <div class="quick-stats">
                <div class="stat-item">
                    <div class="stat-number"><?= count(array_filter($pages, fn($p) => $p['status'] === 'published')) ?></div>
                    <div class="stat-label"><?= lang('PublicPages.published_pages') ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= count(array_filter($pages, fn($p) => $p['status'] === 'draft')) ?></div>
                    <div class="stat-label"><?= lang('PublicPages.draft_pages') ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= array_sum(array_column($pages, 'views_count')) ?></div>
                    <div class="stat-label"><?= lang('PublicPages.total_views_stat') ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= array_sum(array_column($pages, 'likes_count')) ?></div>
                    <div class="stat-label"><?= lang('PublicPages.total_likes_stat') ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><?= lang('PublicPages.public_pages') ?></h4>
                    <div>
                        <a href="<?= base_url('public_pages/create') ?>" class="btn btn-primary">
                            <i class="bx bx-plus"></i> <?= lang('PublicPages.new_page') ?>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table id="pagesTable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th><?= lang('PublicPages.title') ?></th>
                                    <th><?= lang('PublicPages.slug') ?></th>
                                    <th><?= lang('PublicPages.status') ?></th>
                                    <th><?= lang('PublicPages.privacy_level') ?></th>
                                    <th><?= lang('PublicPages.views') ?></th>
                                    <th><?= lang('PublicPages.likes') ?></th>
                                    <th><?= lang('Common.created') ?></th>
                                    <th><?= lang('Common.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pages as $page): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($page['featured_image']): ?>
                                                <img src="<?= $page['featured_image'] ?>" alt="<?= esc($page['title']) ?>" 
                                                     class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= esc($page['title']) ?></strong>
                                                <?php if ($page['excerpt']): ?>
                                                    <br><small class="text-muted"><?= esc(substr($page['excerpt'], 0, 50)) ?>...</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code><?= esc($page['slug']) ?></code>
                                        <?php if ($page['status'] === 'published'): ?>
                                            <br>                                            <a href="<?= base_url('p/' . $page['slug']) ?>" target="_blank" class="text-primary">
                                                <i class="bx bx-link-external"></i> <?= lang('PublicPages.view_page') ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClasses = [
                                            'published' => 'bg-success',
                                            'draft' => 'bg-warning',
                                            'archived' => 'bg-secondary'
                                        ];
                                        $statusLabels = [
                                            'published' => lang('PublicPages.published'),
                                            'draft' => lang('PublicPages.draft'),
                                            'archived' => lang('PublicPages.archived')
                                        ];
                                        ?>
                                        <span class="badge status-badge <?= $statusClasses[$page['status']] ?>">
                                            <?= $statusLabels[$page['status']] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $privacyClasses = [
                                            'public' => 'bg-success',
                                            'password' => 'bg-warning',
                                            'users_only' => 'bg-info',
                                            'roles' => 'bg-primary',
                                            'private' => 'bg-danger'
                                        ];
                                        $privacyLabels = [
                                            'public' => lang('PublicPages.public_label'),
                                            'password' => lang('PublicPages.password_label'),
                                            'users_only' => lang('PublicPages.users_only_label'),
                                            'roles' => lang('PublicPages.roles_label'),
                                            'private' => lang('PublicPages.private_label')
                                        ];
                                        ?>
                                        <span class="badge privacy-badge <?= $privacyClasses[$page['privacy_level']] ?>">
                                            <?= $privacyLabels[$page['privacy_level']] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <i class="bx bx-show"></i> <?= number_format($page['views_count']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <i class="bx bx-heart"></i> <?= number_format($page['likes_count']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            <?= date('d/m/Y H:i', strtotime($page['created_at'])) ?>
                                            <?php if (isset($page['author'])): ?>
                                                <br>por <?= esc($page['author']['first_name'] . ' ' . $page['author']['last_name']) ?>
                                            <?php endif; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="page-actions">
                                            <a href="<?= base_url('public_pages/edit/' . $page['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary" title="<?= lang('PublicPages.edit_page') ?>">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            
                                            <a href="<?= base_url('public_pages/preview/' . $page['id']) ?>" 
                                               class="btn btn-sm btn-outline-info" title="<?= lang('PublicPages.preview_page') ?>" target="_blank">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            
                                            <a href="<?= base_url('public_pages/analytics/' . $page['id']) ?>" 
                                               class="btn btn-sm btn-outline-success" title="<?= lang('PublicPages.analytics') ?>">
                                                <i class="bx bx-bar-chart"></i>
                                            </a>
                                            
                                            <a href="<?= base_url('public_pages/duplicate/' . $page['id']) ?>" 
                                               class="btn btn-sm btn-outline-secondary" title="<?= lang('PublicPages.duplicate_page') ?>">
                                                <i class="bx bx-copy"></i>
                                            </a>
                                            
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deletePage(<?= $page['id'] ?>, '<?= esc($page['title']) ?>')" 
                                                    title="<?= lang('PublicPages.delete_page') ?>">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/libs/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/datatables/responsive.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>

<script>
$(document).ready(function() {
    $('#pagesTable').DataTable({
        responsive: true,
        language: {
            url: '<?= base_url('assets/js/datatables-lang/es.json') ?>'
        },
        order: [[6, 'desc']], // Order by created date
        columnDefs: [
            { orderable: false, targets: [7] } // Disable ordering on actions column
        ]
    });
});

function deletePage(pageId, pageTitle) {
    Swal.fire({
        title: '<?= lang('Common.are_you_sure') ?>',
        text: `<?= lang('PublicPages.delete_confirm') ?>`.replace('{title}', pageTitle),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<?= lang('Common.yes_delete') ?>',
        cancelButtonText: '<?= lang('Common.cancel') ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('public_pages/delete') ?>',
                type: 'POST',
                data: {
                    id: pageId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            '<?= lang('Common.deleted') ?>',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            '<?= lang('Common.error') ?>',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        '<?= lang('Common.error') ?>',
                        '<?= lang('PublicPages.delete_error') ?>',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?>
