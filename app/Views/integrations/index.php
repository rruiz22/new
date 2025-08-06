<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.manage_integrations') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.manage_integrations') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.settings') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Clean Velzon Integration Page Styling */
.integration-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--vz-border-color);
}

.integration-icon {
    width: 48px;
    height: 48px;
    background-color: var(--vz-primary);
    border-radius: var(--vz-border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.integration-icon.icon-s3 {
    background-color: #232f3e;
}

.integration-icon.icon-tinypng {
    background-color: #ff6600;
}

.integration-icon.icon-video {
    background-color: #299cdb;
}

.integration-icon.icon-external {
    background-color: #0ab39c;
}

.integration-icon i {
    color: #fff;
    font-size: 1.5rem;
}

.integration-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--vz-heading-color);
    margin: 0;
}

.integration-description {
    color: var(--vz-body-color);
    margin: 0;
    font-size: 0.95rem;
    opacity: 0.8;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 1rem 0;
}

.feature-list li {
    padding: 0.5rem 0;
    display: flex;
    align-items: center;
    color: var(--vz-body-color);
}

.feature-list li i {
    color: var(--vz-success);
    margin-right: 0.75rem;
    font-size: 1rem;
}

.integration-status {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: var(--vz-border-radius);
    font-size: 0.75rem;
    font-weight: 500;
    margin-left: auto;
}

.integration-status.active {
    background-color: var(--vz-success-bg-subtle);
    color: var(--vz-success-text-emphasis);
    border: 1px solid var(--vz-success-border-subtle);
}

.integration-status.inactive {
    background-color: var(--vz-danger-bg-subtle);
    color: var(--vz-danger-text-emphasis);
    border: 1px solid var(--vz-danger-border-subtle);
}

.integration-status i {
    margin-right: 0.5rem;
}

.quick-setup {
    background-color: var(--vz-info-bg-subtle);
    border: 1px solid var(--vz-info-border-subtle);
    border-radius: var(--vz-border-radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.quick-setup h5 {
    color: var(--vz-info-text-emphasis);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.quick-setup p {
    color: var(--vz-info-text-emphasis);
    margin: 0;
    font-size: 0.95rem;
    opacity: 0.8;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Quick Setup Guide -->
    <div class="quick-setup">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5><i class="ri-rocket-line me-2"></i><?= lang('App.integration_settings') ?></h5>
                <p><?= lang('App.manage_integrations') ?> - Configure APIs, webhooks and external service connections</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="<?= base_url('settings') ?>" class="btn btn-outline-primary">
                    <i class="ri-arrow-left-line me-1"></i>
                    <?= lang('App.settings') ?>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- AWS S3 Storage -->
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-body">
                    <div class="integration-header">
                        <div class="integration-icon icon-s3">
                            <i class="ri-cloud-line"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="integration-title">AWS S3 Storage</h5>
                            <p class="integration-description">Cloud storage and file management</p>
                        </div>
                        <div class="integration-status inactive">
                            <i class="ri-close-circle-line"></i>
                            Inactive
                        </div>
                    </div>

                    <div class="integration-content">
                        <ul class="feature-list">
                            <li><i class="ri-check-line"></i> File upload and storage</li>
                            <li><i class="ri-check-line"></i> CDN distribution via CloudFront</li>
                            <li><i class="ri-check-line"></i> Automated backup solutions</li>
                            <li><i class="ri-check-line"></i> Secure access controls</li>
                        </ul>

                        <div class="mt-4">
                            <a href="<?= base_url('integrations/storage') ?>" class="btn btn-primary">
                                <i class="ri-settings-3-line me-1"></i>
                                Configure S3
                            </a>
                            <button type="button" class="btn btn-outline-secondary ms-2" onclick="testS3Connection()">
                                <i class="ri-pulse-line me-1"></i>
                                Test Connection
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TinyPNG/Media Optimization -->
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-body">
                    <div class="integration-header">
                        <div class="integration-icon icon-tinypng">
                            <i class="ri-image-line"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="integration-title">Image Optimization</h5>
                            <p class="integration-description">Smart image compression and optimization</p>
                        </div>
                        <?php 
                        $tinypngActive = isset($services_status['tinypng']) && $services_status['tinypng']['is_configured'];
                        ?>
                        <div class="integration-status <?= $tinypngActive ? 'active' : 'inactive' ?>">
                            <?php if ($tinypngActive): ?>
                                <i class="ri-check-circle-line"></i>
                                Active
                            <?php else: ?>
                                <i class="ri-close-circle-line"></i>
                                Inactive
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="integration-content">
                        <ul class="feature-list">
                            <li><i class="ri-check-line"></i> TinyPNG API integration</li>
                            <li><i class="ri-check-line"></i> Automatic image compression</li>
                            <li><i class="ri-check-line"></i> Multiple format support</li>
                            <li><i class="ri-check-line"></i> Cloudinary alternative</li>
                        </ul>

                        <div class="mt-4">
                            <a href="<?= base_url('integrations/media') ?>" class="btn btn-primary">
                                <i class="ri-settings-3-line me-1"></i>
                                Configure Media
                            </a>
                            <button type="button" class="btn btn-outline-secondary ms-2" onclick="testMediaConnection()">
                                <i class="ri-pulse-line me-1"></i>
                                Test Compression
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Video Processing -->
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-body">
                    <div class="integration-header">
                        <div class="integration-icon icon-video">
                            <i class="ri-video-line"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="integration-title">Video Processing</h5>
                            <p class="integration-description">FFmpeg video encoding and processing</p>
                        </div>
                        <?php 
                        $ffmpegActive = isset($services_status['ffmpeg']) && $services_status['ffmpeg']['is_configured'];
                        ?>
                        <div class="integration-status <?= $ffmpegActive ? 'active' : 'inactive' ?>">
                            <?php if ($ffmpegActive): ?>
                                <i class="ri-check-circle-line"></i>
                                Active
                            <?php else: ?>
                                <i class="ri-close-circle-line"></i>
                                Inactive
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="integration-content">
                        <ul class="feature-list">
                            <li><i class="ri-check-line"></i> Video format conversion</li>
                            <li><i class="ri-check-line"></i> Quality optimization</li>
                            <li><i class="ri-check-line"></i> Thumbnail generation</li>
                            <li><i class="ri-check-line"></i> Batch processing</li>
                        </ul>

                        <div class="mt-4">
                            <a href="<?= base_url('integrations/video') ?>" class="btn btn-primary">
                                <i class="ri-settings-3-line me-1"></i>
                                Configure Video
                            </a>
                            <button type="button" class="btn btn-outline-secondary ms-2" onclick="testVideoConnection()">
                                <i class="ri-pulse-line me-1"></i>
                                Test Processing
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- External Services -->
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-body">
                    <div class="integration-header">
                        <div class="integration-icon icon-external">
                            <i class="ri-links-line"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="integration-title">External Services</h5>
                            <p class="integration-description">Third-party API integrations</p>
                        </div>
                        <div class="integration-status inactive">
                            <i class="ri-close-circle-line"></i>
                            Inactive
                        </div>
                    </div>

                    <div class="integration-content">
                        <ul class="feature-list">
                            <li><i class="ri-check-line"></i> Stripe payment processing</li>
                            <li><i class="ri-check-line"></i> Mailgun email delivery</li>
                            <li><i class="ri-check-line"></i> Twilio SMS services</li>
                            <li><i class="ri-check-line"></i> Monitoring and alerts</li>
                        </ul>

                        <div class="mt-4">
                            <a href="<?= base_url('integrations/external') ?>" class="btn btn-primary">
                                <i class="ri-settings-3-line me-1"></i>
                                Configure Services
                            </a>
                            <button type="button" class="btn btn-outline-secondary ms-2" onclick="testExternalConnection()">
                                <i class="ri-pulse-line me-1"></i>
                                Test Services
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Integration Status Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Integration Status</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <i class="ri-cloud-line fs-2 text-muted mb-2 d-block"></i>
                                <h6 class="mb-1">Storage</h6>
                                <span class="badge bg-danger-subtle text-danger">Not Configured</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <i class="ri-image-line fs-2 text-muted mb-2 d-block"></i>
                                <h6 class="mb-1">Media</h6>
                                <?php $tinypngActive = isset($services_status['tinypng']) && $services_status['tinypng']['is_configured']; ?>
                                <?php if ($tinypngActive): ?>
                                    <span class="badge bg-success-subtle text-success">Configured</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">Not Configured</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <i class="ri-video-line fs-2 text-muted mb-2 d-block"></i>
                                <h6 class="mb-1">Video</h6>
                                <?php $ffmpegActive = isset($services_status['ffmpeg']) && $services_status['ffmpeg']['is_configured']; ?>
                                <?php if ($ffmpegActive): ?>
                                    <span class="badge bg-success-subtle text-success">Configured</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">Not Configured</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 text-center">
                                <i class="ri-links-line fs-2 text-muted mb-2 d-block"></i>
                                <h6 class="mb-1">External</h6>
                                <span class="badge bg-danger-subtle text-danger">Not Configured</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testS3Connection() {
    // Test S3 connection
    fetch('<?= base_url('integrations/test-connection') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'service_name=s3'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success!', data.message, 'success');
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', 'Connection test failed', 'error');
    });
}

function testMediaConnection() {
    // Test media connection
    fetch('<?= base_url('integrations/test-connection') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'service_name=tinypng'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success!', data.message, 'success');
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', 'Connection test failed', 'error');
    });
}

function testVideoConnection() {
    // Test video connection
    fetch('<?= base_url('integrations/test-connection') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'service_name=ffmpeg'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success!', data.message, 'success');
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', 'Connection test failed', 'error');
    });
}

function testExternalConnection() {
    // Test external services connection
    fetch('<?= base_url('integrations/test-connection') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'service_name=stripe'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success!', data.message, 'success');
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error!', 'Connection test failed', 'error');
    });
}
</script>
<?= $this->endSection() ?> 