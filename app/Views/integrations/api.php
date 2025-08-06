<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.api_settings') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.api_settings') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.manage_integrations') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.api-container {
    background: #f8f9fa;
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

.api-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
}

.api-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.api-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.api-icon i {
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

.code-example {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    overflow-x: auto;
}

.api-key-input {
    position: relative;
}

.api-key-input .form-control {
    padding-right: 100px;
}

.btn-copy {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: #007bff;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
}

.btn-generate {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-generate:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.endpoint-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.endpoint-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.endpoint-item:hover {
    background: #e9ecef;
}

.endpoint-method {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-right: 1rem;
}

.endpoint-method.get {
    background: #d4edda;
    color: #155724;
}

.endpoint-method.post {
    background: #cce5ff;
    color: #004085;
}

.endpoint-method.put {
    background: #fff3cd;
    color: #856404;
}

.endpoint-method.delete {
    background: #f8d7da;
    color: #721c24;
}

.endpoint-url {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    color: #495057;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="api-container">
    <div class="container-fluid">
        <a href="<?= base_url('integrations') ?>" class="nav-back">
            <i class="ri-arrow-left-line"></i>
            <?= lang('App.manage_integrations') ?>
        </a>

        <div class="row">
            <div class="col-lg-8">
                <!-- API Configuration -->
                <div class="api-card">
                    <div class="api-header">
                        <div class="api-icon">
                            <i class="ri-code-s-slash-line"></i>
                        </div>
                        <div>
                            <h4><?= lang('App.api_settings') ?></h4>
                            <p class="text-muted mb-0">Configure your API keys and endpoints</p>
                        </div>
                    </div>

                    <form id="apiSettingsForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="api_enabled" class="form-label">
                                        <i class="ri-toggle-line text-primary me-2"></i>
                                        Enable API
                                    </label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="api_enabled" name="api_enabled">
                                        <label class="form-check-label" for="api_enabled">
                                            API Access Enabled
                                        </label>
                                    </div>
                                    <div class="form-text">Enable or disable API access to your application</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rate_limit" class="form-label">
                                        <i class="ri-speed-line text-primary me-2"></i>
                                        Rate Limit (requests/hour)
                                    </label>
                                    <input type="number" class="form-control" id="rate_limit" name="rate_limit" value="1000" min="1">
                                    <div class="form-text">Maximum number of requests per hour</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="api_key" class="form-label">
                                <i class="ri-key-line text-primary me-2"></i>
                                API Key
                            </label>
                            <div class="api-key-input">
                                <input type="text" class="form-control" id="api_key" name="api_key" value="sk_live_abc123def456ghi789jkl012mno345pqr678stu" readonly>
                                <button type="button" class="btn-copy" onclick="copyApiKey()">
                                    <i class="ri-file-copy-line"></i> Copy
                                </button>
                            </div>
                            <div class="form-text">Use this key to authenticate your API requests</div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-generate" onclick="generateNewKey()">
                                <i class="ri-refresh-line me-2"></i>
                                Generate New API Key
                            </button>
                            <small class="text-danger ms-3">
                                <i class="ri-warning-line"></i>
                                This will invalidate the current key
                            </small>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i>
                                Save API Settings
                            </button>
                        </div>
                    </form>
                </div>

                <!-- API Endpoints -->
                <div class="api-card">
                    <h5>
                        <i class="ri-route-line text-primary me-2"></i>
                        Available Endpoints
                    </h5>
                    <p class="text-muted">These are the available API endpoints for your application</p>

                    <ul class="endpoint-list">
                        <li class="endpoint-item">
                            <div class="d-flex align-items-center">
                                <span class="endpoint-method get">GET</span>
                                <span class="endpoint-url">/api/v1/clients</span>
                            </div>
                            <small class="text-muted">Retrieve all clients</small>
                        </li>
                        <li class="endpoint-item">
                            <div class="d-flex align-items-center">
                                <span class="endpoint-method post">POST</span>
                                <span class="endpoint-url">/api/v1/clients</span>
                            </div>
                            <small class="text-muted">Create a new client</small>
                        </li>
                        <li class="endpoint-item">
                            <div class="d-flex align-items-center">
                                <span class="endpoint-method get">GET</span>
                                <span class="endpoint-url">/api/v1/sales-orders</span>
                            </div>
                            <small class="text-muted">Retrieve all sales orders</small>
                        </li>
                        <li class="endpoint-item">
                            <div class="d-flex align-items-center">
                                <span class="endpoint-method post">POST</span>
                                <span class="endpoint-url">/api/v1/sales-orders</span>
                            </div>
                            <small class="text-muted">Create a new sales order</small>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Quick Start Guide -->
                <div class="api-card">
                    <h6>
                        <i class="ri-rocket-line text-success me-2"></i>
                        Quick Start
                    </h6>
                    <p class="text-muted small">Get started with the API in minutes</p>

                    <div class="mb-3">
                        <strong>1. Authentication</strong>
                        <div class="code-example mt-2">
curl -H "Authorization: Bearer YOUR_API_KEY" \
  <?= base_url('api/v1/clients') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>2. Make a Request</strong>
                        <div class="code-example mt-2">
{
  "status": "success",
  "data": [...]
}
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="ri-book-open-line me-1"></i>
                            View Documentation
                        </a>
                    </div>
                </div>

                <!-- API Status -->
                <div class="api-card">
                    <h6>
                        <i class="ri-pulse-line text-info me-2"></i>
                        API Status
                    </h6>
                    <div class="mb-2">
                        <small class="text-muted">Last 24 hours</small>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Requests</span>
                        <strong>0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Errors</span>
                        <strong class="text-success">0</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Response Time</span>
                        <strong>-- ms</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyApiKey() {
    const apiKeyInput = document.getElementById('api_key');
    apiKeyInput.select();
    apiKeyInput.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(apiKeyInput.value);
    
    // Show success message
    const copyBtn = document.querySelector('.btn-copy');
    const originalText = copyBtn.innerHTML;
    copyBtn.innerHTML = '<i class="ri-check-line"></i> Copied!';
    copyBtn.style.background = '#28a745';
    
    setTimeout(() => {
        copyBtn.innerHTML = originalText;
        copyBtn.style.background = '#007bff';
    }, 2000);
}

function generateNewKey() {
    if (confirm('Are you sure you want to generate a new API key? This will invalidate the current key.')) {
        // Generate a random API key
        const newKey = 'sk_live_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        document.getElementById('api_key').value = newKey;
        
        // Show success message
        alert('New API key generated successfully!');
    }
}

document.getElementById('apiSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Simulate saving
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i>Saving...';
    submitBtn.disabled = true;
    
    setTimeout(() => {
        submitBtn.innerHTML = '<i class="ri-check-line me-2"></i>Saved!';
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 1500);
    }, 1000);
});
</script>
<?= $this->endSection() ?> 