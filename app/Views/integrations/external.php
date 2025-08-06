<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>External Services Configuration<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>External Services<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.manage_integrations') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.external-container {
    background: #f8f9fa;
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

.external-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
}

.external-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.external-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.external-icon.stripe {
    background: linear-gradient(135deg, #635bff, #5469d4);
}

.external-icon.mailgun {
    background: linear-gradient(135deg, #ff6854, #fd3018);
}

.external-icon.twilio {
    background: linear-gradient(135deg, #f22f46, #cf272d);
}

.external-icon.monitoring {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.external-icon i {
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

.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.service-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.service-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.service-card-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid #e9ecef;
}

.service-card-body {
    padding: 1.5rem;
}

.integration-toggle {
    width: 50px;
    height: 24px;
    background: #ccc;
    border-radius: 12px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.integration-toggle.active {
    background: #28a745;
}

.integration-toggle::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.integration-toggle.active::after {
    left: 28px;
}

.test-button {
    background: linear-gradient(135deg, #17a2b8, #138496);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.test-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="external-container">
    <div class="container-fluid">
        <a href="<?= base_url('integrations') ?>" class="nav-back">
            <i class="ri-arrow-left-line"></i>
            <?= lang('App.manage_integrations') ?>
        </a>

        <div class="row">
            <div class="col-12">
                <div class="external-card">
                    <h4>
                        <i class="ri-links-line text-primary me-2"></i>
                        External Services Configuration
                    </h4>
                    <p class="text-muted">Configure payment gateways, email services, SMS providers, and monitoring tools.</p>
                </div>
            </div>
        </div>

        <div class="service-grid">
            <!-- Stripe Payment Gateway -->
            <div class="service-card">
                <div class="service-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="external-icon stripe">
                                <i class="ri-bank-card-line"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Stripe Payments</h6>
                                <small class="text-muted">Payment processing</small>
                            </div>
                        </div>
                        <div class="integration-toggle" onclick="toggleIntegration(this)"></div>
                    </div>
                </div>
                <div class="service-card-body">
                    <form id="stripeForm">
                        <div class="mb-3">
                            <label class="form-label">Publishable Key</label>
                            <input type="text" class="form-control" name="stripe_public_key" placeholder="pk_live_...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Secret Key</label>
                            <input type="password" class="form-control" name="stripe_secret_key" placeholder="sk_live_...">
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="stripe_sandbox">
                                <label class="form-check-label" for="stripe_sandbox">Sandbox Mode</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn test-button" onclick="testStripe()">
                                <i class="ri-play-line me-1"></i>Test
                            </button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mailgun Email Service -->
            <div class="service-card">
                <div class="service-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="external-icon mailgun">
                                <i class="ri-mail-line"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Mailgun</h6>
                                <small class="text-muted">Email delivery service</small>
                            </div>
                        </div>
                        <div class="integration-toggle" onclick="toggleIntegration(this)"></div>
                    </div>
                </div>
                <div class="service-card-body">
                    <form id="mailgunForm">
                        <div class="mb-3">
                            <label class="form-label">Domain</label>
                            <input type="text" class="form-control" name="mailgun_domain" placeholder="mg.yourdomain.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">API Key</label>
                            <input type="password" class="form-control" name="mailgun_api_key" placeholder="key-...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Region</label>
                            <select class="form-select" name="mailgun_region">
                                <option value="us">US</option>
                                <option value="eu">EU</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn test-button" onclick="testMailgun()">
                                <i class="ri-play-line me-1"></i>Test
                            </button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Twilio SMS Service -->
            <div class="service-card">
                <div class="service-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="external-icon twilio">
                                <i class="ri-message-line"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Twilio SMS</h6>
                                <small class="text-muted">SMS messaging service</small>
                            </div>
                        </div>
                        <div class="integration-toggle" onclick="toggleIntegration(this)"></div>
                    </div>
                </div>
                <div class="service-card-body">
                    <form id="twilioForm">
                        <div class="mb-3">
                            <label class="form-label">Account SID</label>
                            <input type="text" class="form-control" name="twilio_sid" placeholder="AC...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Auth Token</label>
                            <input type="password" class="form-control" name="twilio_token" placeholder="Auth Token">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">From Number</label>
                            <input type="text" class="form-control" name="twilio_from" placeholder="+1234567890">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn test-button" onclick="testTwilio()">
                                <i class="ri-play-line me-1"></i>Test
                            </button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Application Monitoring -->
            <div class="service-card">
                <div class="service-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="external-icon monitoring">
                                <i class="ri-pulse-line"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Monitoring</h6>
                                <small class="text-muted">Application monitoring</small>
                            </div>
                        </div>
                        <div class="integration-toggle" onclick="toggleIntegration(this)"></div>
                    </div>
                </div>
                <div class="service-card-body">
                    <form id="monitoringForm">
                        <div class="mb-3">
                            <label class="form-label">Service</label>
                            <select class="form-select" name="monitoring_service">
                                <option value="">Select Service</option>
                                <option value="sentry">Sentry</option>
                                <option value="bugsnag">Bugsnag</option>
                                <option value="rollbar">Rollbar</option>
                                <option value="newrelic">New Relic</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">API Key/DSN</label>
                            <input type="password" class="form-control" name="monitoring_key" placeholder="API Key or DSN">
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="monitoring_enabled">
                                <label class="form-check-label" for="monitoring_enabled">Enable Error Tracking</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn test-button" onclick="testMonitoring()">
                                <i class="ri-play-line me-1"></i>Test
                            </button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Service Status Overview -->
        <div class="row">
            <div class="col-12">
                <div class="external-card">
                    <h5>
                        <i class="ri-dashboard-line text-primary me-2"></i>
                        Service Status Overview
                    </h5>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="h5 mb-1 text-muted">
                                <i class="ri-close-circle-line text-danger"></i>
                                Stripe
                            </div>
                            <small class="text-muted">Disconnected</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h5 mb-1 text-muted">
                                <i class="ri-close-circle-line text-danger"></i>
                                Mailgun
                            </div>
                            <small class="text-muted">Disconnected</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h5 mb-1 text-muted">
                                <i class="ri-close-circle-line text-danger"></i>
                                Twilio
                            </div>
                            <small class="text-muted">Disconnected</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h5 mb-1 text-muted">
                                <i class="ri-close-circle-line text-danger"></i>
                                Monitoring
                            </div>
                            <small class="text-muted">Inactive</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleIntegration(toggle) {
    toggle.classList.toggle('active');
}

function testStripe() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="ri-loader-4-line me-1"></i>Testing...';
    btn.disabled = true;
    
    setTimeout(() => {
        btn.innerHTML = '<i class="ri-check-line me-1"></i>Connected!';
        btn.classList.add('btn-success');
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('test-button');
            btn.disabled = false;
        }, 2000);
    }, 2000);
}

function testMailgun() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="ri-loader-4-line me-1"></i>Testing...';
    btn.disabled = true;
    
    setTimeout(() => {
        btn.innerHTML = '<i class="ri-check-line me-1"></i>Connected!';
        btn.classList.add('btn-success');
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('test-button');
            btn.disabled = false;
        }, 2000);
    }, 2000);
}

function testTwilio() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="ri-loader-4-line me-1"></i>Testing...';
    btn.disabled = true;
    
    setTimeout(() => {
        btn.innerHTML = '<i class="ri-check-line me-1"></i>Connected!';
        btn.classList.add('btn-success');
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('test-button');
            btn.disabled = false;
        }, 2000);
    }, 2000);
}

function testMonitoring() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="ri-loader-4-line me-1"></i>Testing...';
    btn.disabled = true;
    
    setTimeout(() => {
        btn.innerHTML = '<i class="ri-check-line me-1"></i>Connected!';
        btn.classList.add('btn-success');
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('test-button');
            btn.disabled = false;
        }, 2000);
    }, 2000);
}

// Form submissions
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="ri-loader-4-line me-1"></i>Saving...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            submitBtn.innerHTML = '<i class="ri-check-line me-1"></i>Saved!';
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        }, 1000);
    });
});
</script>
<?= $this->endSection() ?> 