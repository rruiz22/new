<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.webhook_settings') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.webhook_settings') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.manage_integrations') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.webhook-container {
    background: #f8f9fa;
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

.webhook-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
}

.webhook-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.webhook-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.webhook-icon i {
    color: #fff;
    font-size: 1.5rem;
}

.nav-back {
    display: inline-flex;
    align-items: center;
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.nav-back:hover {
    color: #0056b3;
    text-decoration: none;
}

.nav-back i {
    margin-right: 0.5rem;
}

.webhook-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.webhook-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.webhook-status {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.webhook-status.active {
    background: #d4edda;
    color: #155724;
}

.webhook-status.inactive {
    background: #f8d7da;
    color: #721c24;
}

.webhook-status i {
    margin-right: 0.5rem;
}

.event-badge {
    background: #e3f2fd;
    color: #1976d2;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    display: inline-block;
}

.delivery-log {
    max-height: 300px;
    overflow-y: auto;
}

.log-item {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.log-status {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 1rem;
}

.log-status.success {
    background: #28a745;
}

.log-status.failed {
    background: #dc3545;
}

.log-status.pending {
    background: #ffc107;
}

.btn-test {
    background: linear-gradient(135deg, #17a2b8, #138496);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-test:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
}

.code-display {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    overflow-x: auto;
    white-space: pre-wrap;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius: 16px;">
                <div class="card-header">
                    <h4 class="card-title fw-bold">
                        <i data-feather="link" class="icon-dual me-2"></i>
                        <?= lang('App.webhook_settings') ?>
                    </h4>
                    <p class="text-muted mb-0">Configure webhooks for real-time data synchronization</p>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                                                    <i data-feather="link" style="width: 64px; height: 64px;" class="text-muted mb-3"></i>
                        <h5 class="text-muted">Webhook Configuration</h5>
                        <p class="text-muted">This section is under development. Webhook functionality will be available soon.</p>
                        <a href="<?= base_url('integrations') ?>" class="btn btn-primary">
                            <i data-feather="arrow-left" class="icon-dual-sm me-1"></i>
                            Back to Integrations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 