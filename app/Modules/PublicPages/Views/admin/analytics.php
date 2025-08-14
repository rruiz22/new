<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('PublicPages.analytics') ?> - <?= esc($page['title']) ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('PublicPages.analytics') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('public_pages') ?>"><?= lang('PublicPages.public_pages') ?></a></li>
<li class="breadcrumb-item active"><?= lang('PublicPages.analytics') ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}
.analytics-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}
.metric-value {
    font-size: 2rem;
    font-weight: bold;
    color: #495057;
}
.metric-label {
    color: #6c757d;
    font-size: 0.9rem;
}
.chart-container {
    position: relative;
    height: 300px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1"><?= esc($page['title']) ?></h4>
                    <p class="text-muted mb-0">
                        <i class="bx bx-link-external"></i> 
                        <a href="<?= base_url('p/' . $page['slug']) ?>" target="_blank"><?= base_url('p/' . $page['slug']) ?></a>
                    </p>
                </div>
                <div>
                    <a href="<?= base_url('public_pages/edit/' . $page['id']) ?>" class="btn btn-primary">
                        <i class="bx bx-edit"></i> <?= lang('PublicPages.edit_page') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card analytics-card">
                <div class="card-body text-center">
                    <div class="metric-value text-primary"><?= number_format($page['views_count']) ?></div>
                    <div class="metric-label"><?= lang('PublicPages.total_views') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card analytics-card">
                <div class="card-body text-center">
                    <div class="metric-value text-success"><?= number_format($page['likes_count']) ?></div>
                    <div class="metric-label"><?= lang('PublicPages.total_likes') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card analytics-card">
                <div class="card-body text-center">
                    <div class="metric-value text-info"><?= count($recentViews) ?></div>
                    <div class="metric-label"><?= lang('PublicPages.recent_views') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card analytics-card">
                <div class="card-body text-center">
                    <div class="metric-value text-warning"><?= count($recentLikes) ?></div>
                    <div class="metric-label"><?= lang('PublicPages.recent_likes') ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Views -->
        <div class="col-md-6">
            <div class="card analytics-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-show"></i> <?= lang('PublicPages.recent_views') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentViews)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th><?= lang('Common.user') ?></th>
                                        <th><?= lang('Common.date') ?></th>
                                        <th>IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($recentViews, 0, 10) as $view): ?>
                                    <tr>
                                        <td>
                                            <?php if ($view['user_id']): ?>
                                                <?= esc($view['first_name'] . ' ' . $view['last_name']) ?>
                                            <?php else: ?>
                                                <span class="text-muted"><?= lang('Common.guest') ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?= date('d/m/Y H:i', strtotime($view['viewed_at'])) ?></small>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= esc($view['ip_address']) ?></small>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bx bx-show" style="font-size: 3rem; color: #dee2e6;"></i>
                            <p class="text-muted mt-2"><?= lang('PublicPages.no_views_yet') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Likes -->
        <div class="col-md-6">
            <div class="card analytics-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-heart"></i> <?= lang('PublicPages.recent_likes') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentLikes)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th><?= lang('Common.user') ?></th>
                                        <th><?= lang('Common.date') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($recentLikes, 0, 10) as $like): ?>
                                    <tr>
                                        <td>
                                            <?php if ($like['user_id']): ?>
                                                <?= esc($like['first_name'] . ' ' . $like['last_name']) ?>
                                            <?php else: ?>
                                                <span class="text-muted"><?= lang('Common.guest') ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?= date('d/m/Y H:i', strtotime($like['created_at'])) ?></small>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bx bx-heart" style="font-size: 3rem; color: #dee2e6;"></i>
                            <p class="text-muted mt-2"><?= lang('PublicPages.no_likes_yet') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card analytics-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-info-circle"></i> <?= lang('PublicPages.page_information') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong><?= lang('PublicPages.status') ?>:</strong></td>
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
                                        <span class="badge <?= $statusClasses[$page['status']] ?>">
                                            <?= $statusLabels[$page['status']] ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?= lang('PublicPages.privacy_level') ?>:</strong></td>
                                    <td>
                                        <?php
                                        $privacyLabels = [
                                            'public' => lang('PublicPages.public_label'),
                                            'password' => lang('PublicPages.password_label'),
                                            'users_only' => lang('PublicPages.users_only_label'),
                                            'roles' => lang('PublicPages.roles_label'),
                                            'private' => lang('PublicPages.private_label')
                                        ];
                                        ?>
                                        <?= $privacyLabels[$page['privacy_level']] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?= lang('PublicPages.template') ?>:</strong></td>
                                    <td><?= esc($page['template']) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong><?= lang('Common.created') ?>:</strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($page['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?= lang('Common.updated') ?>:</strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($page['updated_at'])) ?></td>
                                </tr>
                                <?php if ($page['published_at']): ?>
                                <tr>
                                    <td><strong><?= lang('PublicPages.published') ?>:</strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($page['published_at'])) ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Additional analytics functionality can be added here
console.log('Analytics page loaded for page ID: <?= $page['id'] ?>');
</script>
<?= $this->endSection() ?>

