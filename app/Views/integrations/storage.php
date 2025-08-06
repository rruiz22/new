<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>AWS S3 Storage Configuration<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>AWS S3 Storage<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.manage_integrations') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">
                    <i class="ri-cloud-line me-2"></i>AWS S3 Storage Configuration
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('settings') ?>"><?= lang('App.settings') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('integrations') ?>"><?= lang('App.manage_integrations') ?></a></li>
                        <li class="breadcrumb-item active">AWS S3 Storage</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Configuration -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0 me-3">
                            <div class="avatar-title bg-dark rounded fs-18">
                                <i class="ri-cloud-line text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">AWS S3 Configuration</h5>
                            <p class="text-muted mb-0">Configure Amazon S3 for file storage and CDN</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-danger-subtle text-danger">
                                <i class="ri-close-circle-line me-1"></i>INACTIVE
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="s3ConfigForm">
                        <input type="hidden" name="service_name" value="s3">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="aws_access_key" class="form-label fw-semibold">
                                        <i class="ri-key-2-line me-2"></i>AWS Access Key ID
                                    </label>
                                    <input type="text" class="form-control" id="aws_access_key" name="aws_access_key" 
                                           value="<?= isset($config['aws_access_key']) ? esc($config['aws_access_key']['value']) : '' ?>"
                                           placeholder="AKIA...">
                                    <div class="form-text">Your AWS access key for S3 access</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="aws_secret_key" class="form-label fw-semibold">
                                        <i class="ri-lock-line me-2"></i>AWS Secret Access Key
                                    </label>
                                    <input type="password" class="form-control" id="aws_secret_key" name="aws_secret_key" 
                                           value="<?= isset($config['aws_secret_key']) ? '••••••••••••••••' : '' ?>"
                                           placeholder="•••••••••••••••">
                                    <div class="form-text">Your AWS secret key (keep this secure)</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="aws_region" class="form-label fw-semibold">
                                        <i class="ri-global-line me-2"></i>AWS Region
                                    </label>
                                    <select class="form-select" id="aws_region" name="aws_region">
                                        <option value="">Select Region</option>
                                        <option value="us-east-1" <?= (isset($config['aws_region']) && $config['aws_region']['value'] == 'us-east-1') ? 'selected' : '' ?>>US East (N. Virginia)</option>
                                        <option value="us-east-2" <?= (isset($config['aws_region']) && $config['aws_region']['value'] == 'us-east-2') ? 'selected' : '' ?>>US East (Ohio)</option>
                                        <option value="us-west-1" <?= (isset($config['aws_region']) && $config['aws_region']['value'] == 'us-west-1') ? 'selected' : '' ?>>US West (N. California)</option>
                                        <option value="us-west-2" <?= (isset($config['aws_region']) && $config['aws_region']['value'] == 'us-west-2') ? 'selected' : '' ?>>US West (Oregon)</option>
                                        <option value="eu-west-1" <?= (isset($config['aws_region']) && $config['aws_region']['value'] == 'eu-west-1') ? 'selected' : '' ?>>Europe (Ireland)</option>
                                        <option value="eu-west-2" <?= (isset($config['aws_region']) && $config['aws_region']['value'] == 'eu-west-2') ? 'selected' : '' ?>>Europe (London)</option>
                                        <option value="eu-central-1" <?= (isset($config['aws_region']) && $config['aws_region']['value'] == 'eu-central-1') ? 'selected' : '' ?>>Europe (Frankfurt)</option>
                                        <option value="ap-southeast-1" <?= (isset($config['aws_region']) && $config['aws_region']['value'] == 'ap-southeast-1') ? 'selected' : '' ?>>Asia Pacific (Singapore)</option>
                                        <option value="ap-northeast-1" <?= (isset($config['aws_region']) && $config['aws_region']['value'] == 'ap-northeast-1') ? 'selected' : '' ?>>Asia Pacific (Tokyo)</option>
                                    </select>
                                    <div class="form-text">Choose the region closest to your users</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="s3_bucket" class="form-label fw-semibold">
                                        <i class="ri-database-line me-2"></i>S3 Bucket Name
                                    </label>
                                    <input type="text" class="form-control" id="s3_bucket" name="s3_bucket" 
                                           value="<?= isset($config['s3_bucket']) ? esc($config['s3_bucket']['value']) : '' ?>"
                                           placeholder="my-app-uploads">
                                    <div class="form-text">Unique bucket name for file storage</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cloudfront_url" class="form-label fw-semibold">
                                        <i class="ri-speed-line me-2"></i>CloudFront URL (Optional)
                                    </label>
                                    <input type="url" class="form-control" id="cloudfront_url" name="cloudfront_url" 
                                           value="<?= isset($config['cloudfront_url']) ? esc($config['cloudfront_url']['value']) : '' ?>"
                                           placeholder="https://d1234567890.cloudfront.net">
                                    <div class="form-text">CDN URL for faster file delivery</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="s3_path_prefix" class="form-label fw-semibold">
                                        <i class="ri-folder-line me-2"></i>Path Prefix (Optional)
                                    </label>
                                    <input type="text" class="form-control" id="s3_path_prefix" name="s3_path_prefix" 
                                           value="<?= isset($config['s3_path_prefix']) ? esc($config['s3_path_prefix']['value']) : '' ?>"
                                           placeholder="uploads/">
                                    <div class="form-text">Folder path within the bucket</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="s3_public_read" name="s3_public_read" 
                                           <?= (isset($config['s3_public_read']) && $config['s3_public_read']['value'] == '1') ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="s3_public_read">
                                        Public Read Access
                                    </label>
                                    <div class="form-text">Allow public access to uploaded files</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="s3_server_encryption" name="s3_server_encryption" 
                                           <?= (isset($config['s3_server_encryption']) && $config['s3_server_encryption']['value'] == '1') ? 'checked' : 'checked' ?>>
                                    <label class="form-check-label fw-semibold" for="s3_server_encryption">
                                        Server-Side Encryption
                                    </label>
                                    <div class="form-text">Encrypt files at rest (recommended)</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-info me-2" id="testS3Connection">
                                <i class="ri-pulse-line me-1"></i>Test Connection
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Setup Guide & Info -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-information-line me-2"></i>Setup Guide
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs">
                                <div class="avatar-title bg-success rounded-circle fs-16">
                                    <i class="ri-number-1 text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Create AWS Account</h6>
                            <p class="text-muted mb-0">Sign up for AWS if you don't have an account</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs">
                                <div class="avatar-title bg-info rounded-circle fs-16">
                                    <i class="ri-number-2 text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Create S3 Bucket</h6>
                            <p class="text-muted mb-0">Create a new bucket in your preferred region</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs">
                                <div class="avatar-title bg-warning rounded-circle fs-16">
                                    <i class="ri-number-3 text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Create IAM User</h6>
                            <p class="text-muted mb-0">Create IAM user with S3 permissions</p>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs">
                                <div class="avatar-title bg-primary rounded-circle fs-16">
                                    <i class="ri-number-4 text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Get Access Keys</h6>
                            <p class="text-muted mb-0">Generate access key and secret key</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="https://docs.aws.amazon.com/s3/latest/userguide/GetStartedWithS3.html" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                            <i class="ri-external-link-line me-1"></i>AWS S3 Documentation
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-shield-check-line me-2"></i>Security Best Practices
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-start mb-3">
                            <i class="ri-check-line text-success me-2 mt-1"></i>
                            <div>
                                <h6 class="mb-1">Use IAM Roles</h6>
                                <p class="text-muted mb-0 small">Create dedicated IAM user with minimal permissions</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start mb-3">
                            <i class="ri-check-line text-success me-2 mt-1"></i>
                            <div>
                                <h6 class="mb-1">Enable Encryption</h6>
                                <p class="text-muted mb-0 small">Use server-side encryption for sensitive data</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start mb-3">
                            <i class="ri-check-line text-success me-2 mt-1"></i>
                            <div>
                                <h6 class="mb-1">Bucket Policies</h6>
                                <p class="text-muted mb-0 small">Configure proper bucket policies and CORS</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="ri-check-line text-success me-2 mt-1"></i>
                            <div>
                                <h6 class="mb-1">Monitor Access</h6>
                                <p class="text-muted mb-0 small">Enable CloudTrail for access logging</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-money-dollar-circle-line me-2"></i>Pricing Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading">
                            <i class="ri-information-line me-2"></i>AWS Free Tier
                        </h6>
                        <p class="mb-2">New AWS accounts get:</p>
                        <ul class="mb-0">
                            <li>5 GB of S3 storage</li>
                            <li>20,000 GET requests</li>
                            <li>2,000 PUT requests</li>
                            <li>15 GB data transfer out</li>
                        </ul>
                    </div>
                    
                    <div class="text-center">
                        <a href="https://aws.amazon.com/s3/pricing/" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="ri-calculator-line me-1"></i>View S3 Pricing
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('s3ConfigForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Convert checkbox values
    const checkboxes = ['s3_public_read', 's3_server_encryption'];
    checkboxes.forEach(id => {
        const checkbox = document.getElementById(id);
        formData.set(id, checkbox.checked ? '1' : '0');
    });
    
    // Show loading state
    submitBtn.innerHTML = '<i class="ri-loader-2-line me-1"></i>Saving...';
    submitBtn.disabled = true;
    
    fetch('<?= base_url('integrations/save') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while saving configuration',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

document.getElementById('testS3Connection').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    // Show loading state
    btn.innerHTML = '<i class="ri-loader-2-line me-1"></i>Testing...';
    btn.disabled = true;
    
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
            Swal.fire({
                title: 'Connection Successful!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                title: 'Connection Failed!',
                text: data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Connection Failed!',
            text: 'Unable to connect to AWS S3 service',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    })
    .finally(() => {
        // Restore button state
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});
</script>
<?= $this->endSection() ?> 