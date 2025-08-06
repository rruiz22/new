<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Media Optimization Configuration<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Media Optimization<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.manage_integrations') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.media-container {
    background: #f8f9fa;
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

.media-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
}

.media-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.media-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #4caf50, #45a049);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.media-icon.tinypng {
    background: linear-gradient(135deg, #ff6b35, #ff4500);
}

.media-icon.cloudinary {
    background: linear-gradient(135deg, #3448c5, #2e3cc4);
}

.media-icon i {
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

.compression-demo {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    background: #f8f9fa;
    margin: 1rem 0;
}

.compression-demo:hover {
    border-color: #007bff;
    background: #e3f2fd;
    cursor: pointer;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin: 1rem 0;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">
                    <i class="ri-image-line me-2"></i>Media Optimization Configuration
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('settings') ?>"><?= lang('App.settings') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('integrations') ?>"><?= lang('App.manage_integrations') ?></a></li>
                        <li class="breadcrumb-item active">Media Optimization</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- TinyPNG Configuration -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0 me-3">
                            <div class="avatar-title bg-orange rounded fs-18">
                                <i class="ri-image-2-line text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">TinyPNG Configuration</h5>
                            <p class="text-muted mb-0">Smart image compression and optimization</p>
                        </div>
                        <div class="flex-shrink-0">
                            <?php 
                            // Check if TinyPNG is configured and active
                            $isConfigured = isset($tinypng_config['tinypng_api_key']) && 
                                          !empty($tinypng_config['tinypng_api_key']['value']) &&
                                          $tinypng_config['tinypng_api_key']['is_active'];
                            ?>
                            <?php if ($isConfigured): ?>
                            <span class="badge bg-success-subtle text-success">
                                <i class="ri-check-circle-line me-1"></i>ACTIVE
                            </span>
                            <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger">
                                <i class="ri-close-circle-line me-1"></i>INACTIVE
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="tinypngForm">
                        <input type="hidden" name="service_name" value="tinypng">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="tinypng_api_key" class="form-label fw-semibold">
                                        <i class="ri-key-2-line me-2"></i>TinyPNG API Key
                                    </label>
                                    <input type="text" class="form-control" id="tinypng_api_key" name="tinypng_api_key" 
                                           value="<?= isset($tinypng_config['tinypng_api_key']) ? esc($tinypng_config['tinypng_api_key']['value']) : '' ?>"
                                           placeholder="Enter your TinyPNG API key">
                                    <div class="form-text">Get your free API key from TinyPNG</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="quality_level" class="form-label fw-semibold">
                                        <i class="ri-settings-3-line me-2"></i>Quality Level
                                    </label>
                                    <select class="form-select" id="quality_level" name="quality_level">
                                        <option value="smart" <?= (isset($tinypng_config['quality_level']) && $tinypng_config['quality_level']['value'] === 'smart') ? 'selected' : '' ?>>Smart (Recommended)</option>
                                        <option value="high" <?= (isset($tinypng_config['quality_level']) && $tinypng_config['quality_level']['value'] === 'high') ? 'selected' : '' ?>>High Quality</option>
                                        <option value="medium" <?= (isset($tinypng_config['quality_level']) && $tinypng_config['quality_level']['value'] === 'medium') ? 'selected' : '' ?>>Medium Quality</option>
                                        <option value="low" <?= (isset($tinypng_config['quality_level']) && $tinypng_config['quality_level']['value'] === 'low') ? 'selected' : '' ?>>Low Quality</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_file_size" class="form-label fw-semibold">
                                        <i class="ri-file-3-line me-2"></i>Max File Size (MB)
                                    </label>
                                    <input type="number" class="form-control" id="max_file_size" name="max_file_size" 
                                           value="<?= isset($tinypng_config['max_file_size']) ? esc($tinypng_config['max_file_size']['value']) : '5' ?>"
                                           placeholder="5" min="1" max="32">
                                    <div class="form-text">Maximum file size for compression</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="ri-image-line me-2"></i>Supported Formats
                                    </label>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-success">JPEG</span>
                                        <span class="badge bg-success">PNG</span>
                                        <span class="badge bg-success">WebP</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="auto_optimize" name="auto_optimize" 
                                           <?= (isset($tinypng_config['auto_optimize']) && $tinypng_config['auto_optimize']['value'] === '1') ? 'checked' : 'checked' ?>>
                                    <label class="form-check-label fw-semibold" for="auto_optimize">
                                        Auto-optimize on Upload
                                    </label>
                                    <div class="form-text">Automatically compress images when uploaded</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="preserve_metadata" name="preserve_metadata"
                                           <?= (isset($tinypng_config['preserve_metadata']) && $tinypng_config['preserve_metadata']['value'] === '1') ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-semibold" for="preserve_metadata">
                                        Preserve Metadata
                                    </label>
                                    <div class="form-text">Keep EXIF and other metadata information</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-info me-2" id="testTinyPNG">
                                <i class="ri-play-line me-1"></i>Test Compression
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>Save Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Test Compression & Setup Guide -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-test-tube-line me-2"></i>Test Compression
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light rounded fs-2">
                                <i class="ri-image-add-line text-muted"></i>
                            </div>
                        </div>
                        <h6>Click to upload a test image</h6>
                        <p class="text-muted mb-0">Upload an image to test compression</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-information-line me-2"></i>TinyPNG Setup
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
                            <h6 class="mb-1">Create Account</h6>
                            <p class="text-muted mb-0">Sign up at TinyPNG.com for free</p>
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
                            <h6 class="mb-1">Get API Key</h6>
                            <p class="text-muted mb-0">Access your developer dashboard</p>
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
                            <h6 class="mb-1">Free Tier</h6>
                            <p class="text-muted mb-0">500 compressions per month</p>
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
                            <h6 class="mb-1">Configure Settings</h6>
                            <p class="text-muted mb-0">Set quality and file size limits</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="https://tinypng.com/developers" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                            <i class="ri-external-link-line me-1"></i>Get API Key
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cloudinary Alternative -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0 me-3">
                            <div class="avatar-title bg-primary rounded fs-18">
                                <i class="ri-cloud-line text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Cloudinary (Alternative)</h5>
                            <p class="text-muted mb-0">Comprehensive media management platform</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-danger-subtle text-danger">
                                <i class="ri-close-circle-line me-1"></i>INACTIVE
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Features</h6>
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-center mb-2">
                                    <i class="ri-check-line text-success me-2"></i>
                                    Image and video optimization
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <i class="ri-check-line text-success me-2"></i>
                                    Real-time transformations
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <i class="ri-check-line text-success me-2"></i>
                                    CDN delivery
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <i class="ri-check-line text-success me-2"></i>
                                    AI-powered features
                                </li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="mb-3">Comparison</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Feature</th>
                                            <th>TinyPNG</th>
                                            <th>Cloudinary</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Compression</td>
                                            <td><i class="ri-check-line text-success"></i></td>
                                            <td><i class="ri-check-line text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Video Support</td>
                                            <td><i class="ri-close-line text-danger"></i></td>
                                            <td><i class="ri-check-line text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Transformations</td>
                                            <td><i class="ri-close-line text-danger"></i></td>
                                            <td><i class="ri-check-line text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Free Tier</td>
                                            <td>500/month</td>
                                            <td>25k/month</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-primary" onclick="alert('Cloudinary integration coming soon!')">
                            <i class="ri-settings-3-line me-1"></i>Configure Cloudinary
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('tinypngForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Form submission started');
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Validate required fields
    const apiKey = document.getElementById('tinypng_api_key').value.trim();
    if (!apiKey) {
        Swal.fire({
            title: 'Validation Error!',
            text: 'Please enter your TinyPNG API key',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Create URL-encoded data instead of FormData
    const formParams = new URLSearchParams();
    
    // Add service name explicitly
    formParams.append('service_name', 'tinypng');
    
    // Add all form fields
    formParams.append('tinypng_api_key', document.getElementById('tinypng_api_key').value.trim());
    formParams.append('quality_level', document.getElementById('quality_level').value);
    formParams.append('max_file_size', document.getElementById('max_file_size').value);
    formParams.append('auto_optimize', document.getElementById('auto_optimize').checked ? '1' : '0');
    formParams.append('preserve_metadata', document.getElementById('preserve_metadata').checked ? '1' : '0');
    
    // Debug log form data
    console.log('Form data being sent:');
    for (let [key, value] of formParams.entries()) {
        console.log(`${key}: ${value}`);
    }
    
    // Show loading state
    submitBtn.innerHTML = '<i class="ri-loader-2-line me-1"></i>Saving...';
    submitBtn.disabled = true;
    
    fetch('<?= base_url('integrations/save') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: formParams.toString()
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response URL:', response.url);
        
        // Check if we were redirected (likely to login)
        if (response.redirected || response.status === 303 || response.status === 302) {
            throw new Error('Session expired. Please refresh the page and try again.');
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text(); // Get as text first to check for parsing errors
    })
    .then(text => {
        console.log('Raw response:', text);
        try {
            const data = JSON.parse(text);
            console.log('Parsed response data:', data);
            
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message || 'Configuration saved successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Update status indicator - find badge regardless of current state
                    const statusBadge = document.querySelector('.badge.bg-danger-subtle, .badge.bg-success-subtle');
                    if (statusBadge) {
                        statusBadge.className = 'badge bg-success-subtle text-success';
                        statusBadge.innerHTML = '<i class="ri-check-circle-line me-1"></i>ACTIVE';
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Failed to save configuration',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (parseError) {
            console.error('JSON parse error:', parseError);
            console.error('Response that failed to parse:', text);
            Swal.fire({
                title: 'Error!',
                text: 'Invalid response from server',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while saving configuration. Please check the console for details.',
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

document.getElementById('testTinyPNG').addEventListener('click', function() {
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
        body: 'service_name=tinypng'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Test Successful!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire({
                title: 'Test Failed!',
                text: data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Test Failed!',
            text: 'Unable to connect to TinyPNG service',
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