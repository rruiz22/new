<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.settings') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.settings') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.settings') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Modern Settings Page Styling */
.settings-container {
    background: #f8f9fa;
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

.settings-nav-tabs {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    padding: 1rem;
    margin-bottom: 2rem;
    border: none;
}

.settings-nav-tabs .nav-link {
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    margin: 0 4px;
    color: #6c757d;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    white-space: nowrap;
}

.settings-nav-tabs .nav-link i {
    margin-right: 8px;
    font-size: 1.1rem;
}

.settings-nav-tabs .nav-link:hover {
    background: #f8f9fa;
    color: #495057;
    transform: translateY(-1px);
}

.settings-nav-tabs .nav-link.active {
    background: #007bff;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.settings-nav-tabs .nav-link.active i {
    color: #fff;
}

.settings-content {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    padding: 0;
    overflow: hidden;
}

.settings-section {
    padding: 2rem;
}

.settings-section-header {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 1rem;
    margin-bottom: 2rem;
}

.settings-section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
}

.settings-section-title i {
    margin-right: 12px;
    font-size: 1.4rem;
    color: #007bff;
}

.settings-section-desc {
    color: #6c757d;
    margin: 0;
    font-size: 0.95rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.form-label i {
    margin-right: 8px;
    color: #007bff;
}

.form-control, .form-select {
    border: 1px solid #e1e5e9;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
}

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.btn-modern {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn-modern:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-primary.btn-modern {
    background: #007bff;
    color: #fff;
}

.btn-primary.btn-modern:hover {
    background: #0056b3;
}

.btn-outline-secondary.btn-modern {
    border: 1px solid #e1e5e9;
    color: #6c757d;
    background: #fff;
}

.btn-outline-secondary.btn-modern:hover {
    background: #f8f9fa;
    border-color: #dee2e6;
}

.preview-container {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.preview-container.has-image {
    border-style: solid;
    border-color: #007bff;
    background: #fff;
}

.preview-image {
    max-width: 200px;
    max-height: 80px;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.favicon-preview {
    width: 32px;
    height: 32px;
    border-radius: 4px;
}

.test-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.save-section {
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.alert-modern {
    border: none;
    border-radius: 10px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}

.alert-success.alert-modern {
    background: #d4edda;
    color: #155724;
}

.alert-danger.alert-modern {
    background: #f8d7da;
    color: #721c24;
}

@media (max-width: 768px) {
    .settings-nav-tabs {
        overflow-x: auto;
        white-space: nowrap;
    }
    
    .settings-nav-tabs .nav-link {
        min-width: auto;
    }
    
    .settings-section {
        padding: 1rem;
    }
}

/* URL Shortener specific styles */
.stat-item {
    padding: 1rem;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.8);
    margin-bottom: 1rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.card-header.bg-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.card-header.bg-secondary {
    background: linear-gradient(135deg, #6c757d, #495057) !important;
}

#lima-status {
    min-height: 20px;
    display: flex;
    align-items: center;
}

.alert-info {
    background-color: #e3f2fd;
    border-color: #90caf9;
    color: #01579b;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="settings-container">
<div class="container-fluid">
    <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
            <!-- Alert Messages -->
            <?php if (session('success')): ?>
                    <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                            <i class="ri-check-circle-line fs-20 me-3"></i>
                        <div class="flex-grow-1">
                                <strong><?= lang('App.success') ?>!</strong> <?= session('success') ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session('errors')): ?>
                    <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                            <i class="ri-error-warning-line fs-20 me-3"></i>
                        <div class="flex-grow-1">
                                <strong><?= lang('App.error') ?>!</strong>
                            <?php foreach (session('errors') as $error): ?>
                                <div><?= $error ?></div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            <?php endif; ?>

                <!-- Settings Navigation Tabs -->
                <ul class="nav nav-tabs settings-nav-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                            <i class="ri-settings-3-line"></i>
                            <?= lang('App.general_settings') ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding" type="button" role="tab">
                            <i class="ri-image-line"></i>
                            <?= lang('App.branding_settings') ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                            <i class="ri-mail-line"></i>
                            <?= lang('App.email_settings') ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sms-tab" data-bs-toggle="tab" data-bs-target="#sms" type="button" role="tab">
                            <i class="ri-phone-line"></i>
                            <?= lang('App.sms_settings') ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="shortener-tab" data-bs-toggle="tab" data-bs-target="#shortener" type="button" role="tab">
                            <i class="ri-link"></i>
                            URL Shortener
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="templates-tab" data-bs-toggle="tab" data-bs-target="#templates" type="button" role="tab">
                            <i class="ri-file-list-line"></i>
                            Templates
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                            <i class="ri-notification-line"></i>
                            <?= lang('App.notification_settings') ?>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="advanced-tab" data-bs-toggle="tab" data-bs-target="#advanced" type="button" role="tab">
                            <i class="ri-tools-line"></i>
                            <?= lang('App.advanced_settings') ?>
                        </button>
                    </li>
                </ul>

                <!-- Settings Content -->
            <form id="settingsForm" action="<?= base_url('settings/save') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                    <div class="settings-content">
                        <div class="tab-content" id="settingsTabContent">
                            
                            <!-- General Settings Tab -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <div class="settings-section">
                                    <div class="settings-section-header">
                                        <h3 class="settings-section-title">
                                            <i class="ri-settings-3-line"></i>
                                <?= lang('App.general_settings') ?>
                                        </h3>
                                        <p class="settings-section-desc"><?= lang('App.general_settings_desc') ?></p>
                        </div>
                                    
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="app_name" class="form-label">
                                                    <i class="ri-apps-line"></i>
                                        <?= lang('App.app_name') ?>
                                    </label>
                                    <input type="text" class="form-control" id="app_name" name="app_name" 
                                           value="<?= $settings['app_name'] ?? 'Velzon' ?>"
                                                       placeholder="<?= lang('App.enter_app_name') ?>">
                                                <div class="form-text"><?= lang('App.app_name_help') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="app_email" class="form-label">
                                                    <i class="ri-mail-line"></i>
                                        <?= lang('App.app_email') ?>
                                    </label>
                                    <input type="email" class="form-control" id="app_email" name="app_email" 
                                           value="<?= $settings['app_email'] ?? '' ?>"
                                           placeholder="contact@yourapp.com">
                                                <div class="form-text"><?= lang('App.app_email_help') ?></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="app_description" class="form-label">
                                                    <i class="ri-file-text-line"></i>
                                        <?= lang('App.app_description') ?>
                                    </label>
                                    <textarea class="form-control" id="app_description" name="app_description" rows="3"
                                                          placeholder="<?= lang('App.app_description_placeholder') ?>"><?= $settings['app_description'] ?? '' ?></textarea>
                                                <div class="form-text"><?= lang('App.app_description_help') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="app_lang" class="form-label">
                                                    <i class="ri-global-line"></i>
                                        <?= lang('App.default_language') ?>
                                    </label>
                                    <select class="form-select" id="app_lang" name="app_lang">
                                                    <option value="en" <?= ($settings['app_lang'] ?? 'en') === 'en' ? 'selected' : '' ?>>🇺🇸 <?= lang('App.lang_en') ?></option>
                                                    <option value="es" <?= ($settings['app_lang'] ?? 'en') === 'es' ? 'selected' : '' ?>>🇪🇸 <?= lang('App.lang_es') ?></option>
                                                    <option value="pt" <?= ($settings['app_lang'] ?? 'en') === 'pt' ? 'selected' : '' ?>>🇧🇷 <?= lang('App.lang_pt') ?></option>
                                    </select>
                                                <div class="form-text"><?= lang('App.default_language_help') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="footer_text" class="form-label">
                                                    <i class="ri-layout-bottom-line"></i>
                                        <?= lang('App.footer_text') ?>
                                    </label>
                                    <input type="text" class="form-control" id="footer_text" name="footer_text" 
                                           value="<?= $settings['footer_text'] ?? '' ?>"
                                           placeholder="© 2024 Your Company. All rights reserved.">
                                                <div class="form-text"><?= lang('App.footer_text_help') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                            <!-- Branding Settings Tab -->
                            <div class="tab-pane fade" id="branding" role="tabpanel">
                                <div class="settings-section">
                                    <div class="settings-section-header">
                                        <h3 class="settings-section-title">
                                            <i class="ri-image-line"></i>
                                            <?= lang('App.branding_settings') ?>
                                        </h3>
                                        <p class="settings-section-desc"><?= lang('App.branding_settings_desc') ?></p>
                        </div>
                                    
                        <div class="row g-4">
                            <div class="col-md-6">
                                            <div class="form-group">
                                    <label for="app_logo" class="form-label">
                                                    <i class="ri-image-add-line"></i>
                                        <?= lang('App.main_logo') ?>
                                    </label>
                                    
                                                <div class="preview-container <?= !empty($settings['app_logo']) ? 'has-image' : '' ?>">
                                    <?php if (!empty($settings['app_logo'])): ?>
                                                <img src="<?= base_url('assets/images/logos/' . $settings['app_logo']) ?>" 
                                                             alt="Current Logo" class="preview-image mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                                                    <label class="form-check-label text-danger" for="remove_logo">
                                                        <i class="ri-delete-bin-line me-1"></i>
                                                        <?= lang('App.remove_current_logo') ?>
                                                    </label>
                                        </div>
                                    <?php else: ?>
                                                        <i class="ri-image-line fs-24 text-muted mb-2"></i>
                                                        <p class="text-muted mb-0"><?= lang('App.no_logo_uploaded') ?></p>
                                    <?php endif; ?>
                                                </div>
                                    
                                                <input type="file" class="form-control mt-3" id="app_logo" name="app_logo" accept="image/*">
                                                <div class="form-text"><?= lang('App.logo_requirements') ?></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                            <div class="form-group">
                                    <label for="app_favicon" class="form-label">
                                                    <i class="ri-firefox-line"></i>
                                        <?= lang('App.favicon') ?>
                                    </label>
                                    
                                                <div class="preview-container <?= !empty($settings['app_favicon']) ? 'has-image' : '' ?>">
                                    <?php if (!empty($settings['app_favicon'])): ?>
                                                <img src="<?= base_url('assets/images/logos/' . $settings['app_favicon']) ?>" 
                                                             alt="Current Favicon" class="favicon-preview mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remove_favicon" name="remove_favicon" value="1">
                                                    <label class="form-check-label text-danger" for="remove_favicon">
                                                        <i class="ri-delete-bin-line me-1"></i>
                                                        <?= lang('App.remove_current_favicon') ?>
                                                    </label>
                                        </div>
                                    <?php else: ?>
                                                        <i class="ri-firefox-line fs-24 text-muted mb-2"></i>
                                                        <p class="text-muted mb-0"><?= lang('App.no_favicon_uploaded') ?></p>
                                    <?php endif; ?>
                                                </div>
                                    
                                                <input type="file" class="form-control mt-3" id="app_favicon" name="app_favicon" accept="image/*">
                                                <div class="form-text"><?= lang('App.favicon_requirements') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                            <!-- Email Settings Tab -->
                            <div class="tab-pane fade" id="email" role="tabpanel">
                                <div class="settings-section">
                                    <div class="settings-section-header">
                                        <h3 class="settings-section-title">
                                            <i class="ri-mail-line"></i>
                                            <?= lang('App.email_settings') ?>
                                        </h3>
                                        <p class="settings-section-desc"><?= lang('App.email_settings_desc') ?></p>
                        </div>
                                    
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="smtp_host" class="form-label">
                                                    <i class="ri-server-line"></i>
                                        <?= lang('App.smtp_host') ?>
                                    </label>
                                    <input type="text" class="form-control" id="smtp_host" name="smtp_host" 
                                           value="<?= $settings['smtp_host'] ?? '' ?>" placeholder="smtp.gmail.com">
                                                <div class="form-text"><?= lang('App.smtp_host_help') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="smtp_port" class="form-label">
                                                    <i class="ri-door-line"></i>
                                        <?= lang('App.smtp_port') ?>
                                    </label>
                                    <input type="number" class="form-control" id="smtp_port" name="smtp_port" 
                                           value="<?= $settings['smtp_port'] ?? '587' ?>" placeholder="587">
                                                <div class="form-text"><?= lang('App.smtp_port_help') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="smtp_user" class="form-label">
                                                    <i class="ri-user-line"></i>
                                        <?= lang('App.smtp_username') ?>
                                    </label>
                                    <input type="text" class="form-control" id="smtp_user" name="smtp_user" 
                                           value="<?= $settings['smtp_user'] ?? '' ?>"
                                           placeholder="your.email@gmail.com">
                                                <div class="form-text"><?= lang('App.smtp_username_help') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="smtp_pass" class="form-label">
                                                    <i class="ri-lock-line"></i>
                                        <?= lang('App.smtp_password') ?>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="smtp_pass" name="smtp_pass" 
                                               value="<?= $settings['smtp_pass'] ?? '' ?>"
                                               placeholder="••••••••••••">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('smtp_pass')">
                                            <i class="ri-eye-line" id="smtp_pass_icon"></i>
                                        </button>
                                    </div>
                                                <div class="form-text"><?= lang('App.smtp_password_help') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="smtp_encryption" class="form-label">
                                                    <i class="ri-shield-check-line"></i>
                                        <?= lang('App.smtp_encryption') ?>
                                    </label>
                                    <select class="form-select" id="smtp_encryption" name="smtp_encryption">
                                                    <option value="tls" <?= ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>🔒 TLS (<?= lang('App.recommended') ?>)</option>
                                        <option value="ssl" <?= ($settings['smtp_encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' ?>>🔐 SSL</option>
                                    </select>
                                                <div class="form-text"><?= lang('App.smtp_encryption_help') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="smtp_from" class="form-label">
                                                    <i class="ri-mail-send-line"></i>
                                        <?= lang('App.smtp_from_email') ?>
                                    </label>
                                    <input type="email" class="form-control" id="smtp_from" name="smtp_from" 
                                           value="<?= $settings['smtp_from'] ?? '' ?>"
                                           placeholder="noreply@yourapp.com">
                                                <div class="form-text"><?= lang('App.smtp_from_help') ?></div>
                                </div>
                            </div>
                            <div class="col-12">
                                            <div class="test-section">
                                                <button type="button" id="testSmtp" class="btn btn-outline-info btn-modern">
                                        <i class="ri-mail-check-line me-1"></i>
                                        <?= lang('App.test_smtp_connection') ?>
                                    </button>
                                    <div class="test-result-container mt-3" id="smtpTestResult" style="display: none;">
                                        <!-- Test results will be displayed here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                            <!-- SMS Settings Tab -->
                            <div class="tab-pane fade" id="sms" role="tabpanel">
                                <div class="settings-section">
                                    <div class="settings-section-header">
                                        <h3 class="settings-section-title">
                                            <i class="ri-phone-line"></i>
                                            <?= lang('App.sms_settings') ?>
                                        </h3>
                                        <p class="settings-section-desc"><?= lang('App.sms_settings_desc') ?></p>
                        </div>
                                    
                        <div class="row g-4">
                                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twilio_sid" class="form-label">
                                                    <i class="ri-key-line"></i>
                                        <?= lang('App.twilio_sid') ?>
                                    </label>
                                    <input type="text" class="form-control" id="twilio_sid" name="twilio_sid" 
                                                       value="<?= $settings['twilio_sid'] ?? '' ?>" placeholder="ACxxxxxxxxxxxxx">
                                                <div class="form-text"><?= lang('App.twilio_sid_help') ?></div>
                                </div>
                            </div>
                                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twilio_token" class="form-label">
                                                    <i class="ri-lock-line"></i>
                                        <?= lang('App.twilio_token') ?>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="twilio_token" name="twilio_token" 
                                                           value="<?= $settings['twilio_token'] ?? '' ?>" placeholder="Your auth token">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('twilio_token')">
                                            <i class="ri-eye-line" id="twilio_token_icon"></i>
                                        </button>
                                    </div>
                                                <div class="form-text"><?= lang('App.twilio_token_help') ?></div>
                                </div>
                            </div>
                                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twilio_number" class="form-label">
                                                    <i class="ri-phone-line"></i>
                                        <?= lang('App.twilio_number') ?>
                                    </label>
                                    <input type="text" class="form-control" id="twilio_number" name="twilio_number" 
                                           value="<?= $settings['twilio_number'] ?? '' ?>" placeholder="+1234567890">
                                                <div class="form-text"><?= lang('App.twilio_number_help') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                            <!-- URL Shortener Settings Tab -->
                            <div class="tab-pane fade" id="shortener" role="tabpanel">
                                <div class="settings-section">
                                    <div class="settings-section-header">
                                        <h3 class="settings-section-title">
                                            <i class="ri-link"></i>
                                            URL Shortener Settings
                                        </h3>
                                        <p class="settings-section-desc">Configure professional URL shortening services for SMS and email communications</p>
                        </div>
                                    
                                    <!-- Lima Links Configuration -->
                                    <div class="row g-4 mb-4">
                                        <div class="col-12">
                                            <div class="card border-0 bg-light">
                                                <div class="card-header bg-primary text-white">
                                                    <h5 class="card-title mb-0">
                                                        <i class="ri-star-line me-2"></i>
                                                        MDA Links (Professional Service)
                                                    </h5>
                    </div>
                    <div class="card-body">
                                                    <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                                                <label for="lima_api_key" class="form-label">
                                                                    <i class="ri-key-line"></i>
                                                                    MDA Links API Key
                                    </label>
                                                                <div class="input-group">
                                                                    <input type="password" class="form-control" id="lima_api_key" name="lima_api_key" 
                                                                           value="<?= $settings['lima_api_key'] ?? '' ?>" placeholder="ll_xxxxxxxxxxxxxxxx">
                                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('lima_api_key')">
                                                                        <i class="ri-eye-line" id="lima_api_key_icon"></i>
                                                                    </button>
                                </div>
                                                                <div class="form-text">
                                                                    Get your API key from <a href="<?= \App\Helpers\LimaLinksHelper::getApiBaseUrl() ?>/developers" target="_blank">MDA Links Dashboard</a>
                            </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                                                <label for="lima_branded_domain" class="form-label">
                                                                    <i class="ri-global-line"></i>
                                                                    Branded Domain (Optional)
                                    </label>
                                                                <input type="text" class="form-control" id="lima_branded_domain" name="lima_branded_domain" 
                                                                       value="<?= $settings['lima_branded_domain'] ?? '' ?>" placeholder="links.yourcompany.com">
                                                                <div class="form-text">Custom domain for professional branding</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="lima_api_base_url" class="form-label">
                                                                    <i class="ri-server-line"></i>
                                                                    API Base URL
                                                                </label>
                                                                <input type="url" class="form-control" id="lima_api_base_url" name="lima_api_base_url" 
                                                                       value="<?= $settings['lima_api_base_url'] ?? '' ?>" placeholder="https://mda.to">
                                                                <div class="form-text">
                                                                    <i class="ri-information-line me-1"></i>
                                                                    Base URL for the shortlinks API. Leave empty to use default (https://mda.to)
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="testLimaLinks()">
                                                                        <i class="ri-test-tube-line me-1"></i>
                                                                        Test MDA Links Connection
                                                                    </button>
                                                                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="showLimaLinksInfo()">
                                                                        <i class="ri-information-line me-1"></i>
                                                                        Service Info
                                        </button>
                                    </div>
                                                                <div id="lima-status" class="text-muted small"></div>
                                </div>
                            </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                                    <!-- Fallback Services -->
                                    <div class="row g-4 mb-4">
                                        <div class="col-12">
                                            <div class="card border-0">
                                                <div class="card-header bg-secondary text-white">
                                                    <h5 class="card-title mb-0">
                                                        <i class="ri-shield-check-line me-2"></i>
                                                        Fallback Services (Free)
                                                    </h5>
                    </div>
                    <div class="card-body">
                                                    <div class="row g-3">
                            <div class="col-md-6">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="enable_tinyurl" name="enable_tinyurl" 
                                                                       value="1" <?= ($settings['enable_tinyurl'] ?? true) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="enable_tinyurl">
                                                                    <strong>TinyURL</strong> - Free backup service
                                    </label>
                                </div>
                                                            <div class="form-text ms-4">Reliable free service, no API key required</div>
                            </div>
                            <div class="col-md-6">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="enable_isgd" name="enable_isgd" 
                                                                       value="1" <?= ($settings['enable_isgd'] ?? true) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="enable_isgd">
                                                                    <strong>is.gd</strong> - Final fallback service
                                    </label>
                                    </div>
                                                            <div class="form-text ms-4">Final fallback if other services fail</div>
                                </div>
                            </div>
                                                    </div>
                                                        </div>
                                                    </div>
                                                </div>

                                    <!-- Service Priority Order -->
                                    <div class="row g-4 mb-4">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <h6 class="alert-heading">
                                                    <i class="ri-information-line me-2"></i>
                                                    Service Priority Order
                                                </h6>
                                                <ol class="mb-0">
                                                    <li><strong>MDA Links</strong> - Professional service with branded domains and analytics</li>
                                                    <li><strong>TinyURL</strong> - Free backup service</li>
                                                    <li><strong>is.gd</strong> - Final fallback service</li>
                                                    <li><strong>Original URL</strong> - Used if all services fail</li>
                                                </ol>
                                                </div>
                                            </div>
                                        </div>

                                    <!-- Usage Statistics -->
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="card border-0 bg-light">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">
                                                        <i class="ri-bar-chart-line me-2"></i>
                                                        Usage Statistics & Monitoring
                                                    </h5>
                                                    </div>
                                                <div class="card-body">
                                                    <div class="row text-center">
                                                        <div class="col-md-3">
                                                            <div class="stat-item">
                                                                <div class="stat-number text-primary" id="urls-shortened">0</div>
                                                                <div class="stat-label">URLs Shortened</div>
                                                        </div>
                                                    </div>
                                                        <div class="col-md-3">
                                                            <div class="stat-item">
                                                                <div class="stat-number text-success" id="lima-success">0</div>
                                                                <div class="stat-label">MDA Links Success</div>
                                                </div>
                                                </div>
                                                        <div class="col-md-3">
                                                            <div class="stat-item">
                                                                <div class="stat-number text-warning" id="fallback-used">0</div>
                                                                <div class="stat-label">Fallback Used</div>
                                            </div>
                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="stat-item">
                                                                <div class="stat-number text-info" id="chars-saved">0</div>
                                                                <div class="stat-label">Characters Saved</div>
                                                    </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-12">
                                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshShortenerStats()">
                                                                <i class="ri-refresh-line me-1"></i>
                                                                Refresh Statistics
                                                            </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                            <!-- Templates Settings Tab -->
                            <div class="tab-pane fade" id="templates" role="tabpanel">
                                <div class="settings-section">
                                    <div class="settings-section-header">
                                        <h3 class="settings-section-title">
                                            <i class="ri-file-list-line"></i>
                                Message Templates
                                        </h3>
                                        <p class="settings-section-desc">Manage SMS and Email templates for quick communication with contacts</p>
                        </div>
                                    
                                    <!-- SMS Templates -->
                                    <div class="row g-4 mb-4">
                                        <div class="col-12">
                                            <div class="card border-0 bg-light">
                                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title mb-0">
                                                        <i class="ri-message-line me-2"></i>
                                                        SMS Templates
                                                    </h5>
                                                    <button type="button" class="btn btn-light btn-sm" onclick="addSmsTemplate()">
                                            <i class="ri-add-line me-1"></i>
                                                        Add Template
                                        </button>
                                    </div>
                                                <div class="card-body">
                                                    <div id="smsTemplatesList">
                                                        <!-- SMS templates will be loaded here -->
                                            </div>
                                                    <div class="alert alert-info mt-3">
                                                        <h6 class="alert-heading">Available Variables:</h6>
                                                        <p class="mb-0">
                                                            <code>{contact_name}</code>, <code>{client_name}</code>, <code>{order_number}</code>, 
                                                            <code>{vehicle}</code>, <code>{stock}</code>, <code>{service_name}</code>, 
                                                            <code>{status}</code>, <code>{scheduled_date}</code>, <code>{scheduled_time}</code>
                                                        </p>
                                        </div>
                                    </div>
                                                        </div>
                                                    </div>
                                                        </div>

                                    <!-- Email Templates -->
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="card border-0 bg-light">
                                                <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title mb-0">
                                                        <i class="ri-mail-line me-2"></i>
                                                        Email Templates
                                                    </h5>
                                                    <button type="button" class="btn btn-light btn-sm" onclick="addEmailTemplate()">
                                                        <i class="ri-add-line me-1"></i>
                                                        Add Template
                                                                </button>
                                                            </div>
                                                <div class="card-body">
                                                    <div id="emailTemplatesList">
                                                        <!-- Email templates will be loaded here -->
                                                        </div>
                                                    <div class="alert alert-info mt-3">
                                                        <h6 class="alert-heading">Available Variables:</h6>
                                                        <p class="mb-0">
                                                            Same as SMS templates: <code>{contact_name}</code>, <code>{client_name}</code>, 
                                                            <code>{order_number}</code>, <code>{vehicle}</code>, <code>{stock}</code>, 
                                                            <code>{service_name}</code>, <code>{status}</code>, <code>{scheduled_date}</code>, 
                                                            <code>{scheduled_time}</code>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notifications Settings Tab -->
                            <div class="tab-pane fade" id="notifications" role="tabpanel">
                                <div class="settings-section">
                                    <div class="settings-section-header">
                                        <h3 class="settings-section-title">
                                            <i class="ri-notification-line"></i>
                                            <?= lang('App.notification_settings') ?>
                                        </h3>
                                        <p class="settings-section-desc"><?= lang('App.notification_settings_desc') ?></p>
                                    </div>
                                    
                        <div class="row g-4">
                                        <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pusher_app_id" class="form-label">
                                                    <i class="ri-apps-2-line"></i>
                                        <?= lang('App.pusher_app_id') ?>
                                    </label>
                                    <input type="text" class="form-control" id="pusher_app_id" name="pusher_app_id" 
                                           value="<?= $settings['pusher_app_id'] ?? '' ?>"
                                           placeholder="123456">
                                                <div class="form-text"><?= lang('App.pusher_app_id_help') ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                                        <div class="form-group">
                                    <label for="pusher_key" class="form-label">
                                                    <i class="ri-key-2-line"></i>
                                        <?= lang('App.pusher_key') ?>
                                    </label>
                                    <input type="text" class="form-control" id="pusher_key" name="pusher_key" 
                                           value="<?= $settings['pusher_key'] ?? '' ?>"
                                           placeholder="xxxxxxxxxxxxxxxx">
                                                <div class="form-text"><?= lang('App.pusher_key_help') ?></div>
                                                        </div>
                                                    </div>
                                        <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pusher_secret" class="form-label">
                                                    <i class="ri-lock-2-line"></i>
                                        <?= lang('App.pusher_secret') ?>
                                    </label>
                                        <input type="password" class="form-control" id="pusher_secret" name="pusher_secret" 
                                               value="<?= $settings['pusher_secret'] ?? '' ?>"
                                                       placeholder="••••••••••••••••">
                                                <div class="form-text"><?= lang('App.pusher_secret_help') ?></div>
                                                        </div>
                                </div>
                                        <div class="col-md-3">
                                                        <div class="form-group">
                                    <label for="pusher_cluster" class="form-label">
                                                    <i class="ri-global-2-line"></i>
                                        <?= lang('App.pusher_cluster') ?>
                                    </label>
                                    <input type="text" class="form-control" id="pusher_cluster" name="pusher_cluster" 
                                           value="<?= $settings['pusher_cluster'] ?? 'us2' ?>" placeholder="us2">
                                                <div class="form-text"><?= lang('App.pusher_cluster_help') ?></div>
                                                        </div>
                                                    </div>
                        </div>
                    </div>
                </div>

                            <!-- Advanced Settings Tab -->
                            <div class="tab-pane fade" id="advanced" role="tabpanel">
                                <div class="settings-section">
                                    <div class="settings-section-header">
                                        <h3 class="settings-section-title">
                                            <i class="ri-tools-line"></i>
                                            <?= lang('App.advanced_settings') ?>
                                        </h3>
                                        <p class="settings-section-desc"><?= lang('App.advanced_settings_desc') ?></p>
                        </div>
                                    
                        <div class="row g-4">
                            <div class="col-md-6">
                                                        <div class="form-group">
                                    <label for="cron_token" class="form-label">
                                                    <i class="ri-timer-line"></i>
                                        <?= lang('App.cron_token') ?>
                                    </label>
                                        <input type="text" class="form-control" id="cron_token" name="cron_token" 
                                               value="<?= $settings['cron_token'] ?? '' ?>"
                                                       placeholder="<?= lang('App.auto_generated') ?>">
                                                <div class="form-text"><?= lang('App.cron_token_help') ?></div>
                                                            </div>
                                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    <i class="ri-toggle-line"></i>
                                                    <?= lang('App.feature_toggles') ?>
                                                </label>
                                                <div class="d-flex flex-column gap-2">
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="enable_cron_jobs" name="enable_cron_jobs" 
                                                               value="1" <?= ($settings['enable_cron_jobs'] ?? false) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="enable_cron_jobs">
                                                            <?= lang('App.enable_cron_jobs') ?>
                                                        </label>
                                                    </div>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="show_theme_color_changer" name="show_theme_color_changer" 
                                                               value="1" <?= ($settings['show_theme_color_changer'] ?? false) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="show_theme_color_changer">
                                                            <?= lang('App.show_theme_color_changer') ?>
                                                        </label>
                                                </div>
                                                        <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="enable_pwa" name="enable_pwa" 
                                                               value="1" <?= ($settings['enable_pwa'] ?? false) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="enable_pwa">
                                                            <?= lang('App.enable_pwa') ?>
                                                        </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                        <!-- Save Section -->
                        <div class="save-section">
                                            <div>
                                <small class="text-muted">
                                    <i class="ri-information-line me-1"></i>
                                    <?= lang('App.settings_auto_save_info') ?>
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-modern" onclick="location.reload()">
                                    <i class="ri-refresh-line me-1"></i>
                                    <?= lang('App.refresh') ?>
                                </button>
                                <button type="submit" class="btn btn-primary btn-modern">
                                    <i class="ri-save-line me-1"></i>
                                    <?= lang('App.save_settings') ?>
                                </button>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Bootstrap to be fully loaded
    if (typeof bootstrap === 'undefined') {
        console.warn('Bootstrap not loaded, retrying...');
        setTimeout(() => {
            initializeSettings();
        }, 100);
        return;
    }
    
    initializeSettings();
});

function initializeSettings() {
    // Initialize tooltips
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Tab persistence
    const activeTab = localStorage.getItem('activeSettingsTab');
    if (activeTab && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
        const tabElement = document.querySelector(`#${activeTab}`);
        if (tabElement && tabElement.closest) {
            try {
                const tabTrigger = new bootstrap.Tab(tabElement);
                if (tabTrigger) tabTrigger.show();
            } catch (error) {
                console.warn('Error initializing tab:', error);
                // Clear invalid tab from localStorage
                localStorage.removeItem('activeSettingsTab');
            }
        } else {
            // Clear invalid tab from localStorage
            localStorage.removeItem('activeSettingsTab');
        }
    }

    // Save active tab
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            localStorage.setItem('activeSettingsTab', e.target.id);
        });
    });
    
    // SMTP Test
    document.getElementById('testSmtp').addEventListener('click', function() {
        testSmtpConnection();
    });

    // Form submission with SweetAlert confirmation
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: '<?= lang('App.save_settings') ?>',
            text: '<?= lang('App.please_confirm') ?>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<?= lang('App.yes') ?>, <?= lang('App.save') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return saveSettings();
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    });

    // File upload preview handlers
    setupFileUploadPreviews();
}

// Toggle password visibility with animation
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'ri-eye-off-line';
        showToast('info', '<?= lang('App.password') ?> visible');
    } else {
        field.type = 'password';
        icon.className = 'ri-eye-line';
        showToast('info', '<?= lang('App.password') ?> hidden');
    }
}

// Test SMTP connection with improved feedback
function testSmtpConnection() {
    const button = document.getElementById('testSmtp');
    const resultContainer = document.getElementById('smtpTestResult');
    
    // Validate required fields
    const requiredFields = ['smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass'];
    const missingFields = requiredFields.filter(field => !document.getElementById(field).value);
    
    if (missingFields.length > 0) {
        Swal.fire({
            title: '<?= lang('App.error') ?>',
            text: '<?= lang('App.complete_all_fields') ?>',
            icon: 'warning',
            confirmButtonColor: '#007bff'
        });
        return;
    }
    
    button.disabled = true;
    button.innerHTML = '<i class="ri-loader-4-line spin me-1"></i> <?= lang('App.testing') ?>...';
        
        const formData = new FormData();
    formData.append('smtp_host', document.getElementById('smtp_host').value);
    formData.append('smtp_port', document.getElementById('smtp_port').value);
    formData.append('smtp_user', document.getElementById('smtp_user').value);
    formData.append('smtp_pass', document.getElementById('smtp_pass').value);
    formData.append('smtp_encryption', document.getElementById('smtp_encryption').value);
    formData.append('smtp_from', document.getElementById('smtp_from').value);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        
    fetch('<?= base_url('settings/test-smtp') ?>', {
            method: 'POST',
            headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
        })
    .then(response => {
        // Verificar que la respuesta sea exitosa
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned HTML instead of JSON. Please check server configuration.');
        }
        
        return response.json();
    })
        .then(data => {
        resultContainer.style.display = 'block';
            if (data.success) {
            resultContainer.innerHTML = `
                <div class="alert alert-success">
                    <i class="ri-check-circle-line me-2"></i>
                    <?= lang('App.connection_successful') ?>
                </div>
            `;
            showToast('success', '<?= lang('App.smtp_test_success') ?>');
            
            Swal.fire({
                title: '<?= lang('App.connection_successful') ?>',
                text: '<?= lang('App.smtp_test_success') ?>',
                icon: 'success',
                confirmButtonColor: '#007bff'
            });
            } else {
            resultContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>
                    ${data.message || '<?= lang('App.connection_test_failed') ?>'}
                </div>
            `;
            showToast('error', '<?= lang('App.smtp_test_failed') ?>');
            
            Swal.fire({
                title: '<?= lang('App.connection_test_failed') ?>',
                text: data.message || '<?= lang('App.smtp_test_failed') ?>',
                icon: 'error',
                confirmButtonColor: '#007bff'
            });
        }
        })
        .catch(error => {
        console.error('SMTP test error:', error);
        resultContainer.style.display = 'block';
        resultContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="ri-error-warning-line me-2"></i>
                <?= lang('App.connection_test_failed') ?>
            </div>
        `;
            showToast('error', '<?= lang('App.connection_test_failed') ?>');
        
        Swal.fire({
            title: '<?= lang('App.error') ?>',
            text: '<?= lang('App.connection_test_failed') ?>',
            icon: 'error',
            confirmButtonColor: '#007bff'
        });
        })
        .finally(() => {
        button.disabled = false;
        button.innerHTML = '<i class="ri-mail-check-line me-1"></i> <?= lang('App.test_smtp_connection') ?>';
        });
}

// Save settings with improved feedback
async function saveSettings() {
    try {
        const formData = new FormData(document.getElementById('settingsForm'));
        
        const response = await fetch('<?= base_url('settings/save') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        // Verificar que la respuesta sea exitosa
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Non-JSON response received:', text);
            throw new Error('Server returned HTML instead of JSON. Please check server configuration.');
        }
        
        const data = await response.json();
        
        if (data.success) {
            showToast('success', '<?= lang('App.settings_saved') ?>');
            
            Swal.fire({
                title: '<?= lang('App.success') ?>',
                text: '<?= lang('App.settings_saved') ?>',
                icon: 'success',
                confirmButtonColor: '#007bff',
                timer: 3000,
                timerProgressBar: true
            });
        
            // Refresh page after short delay
            setTimeout(() => {
                window.location.reload();
            }, 2000);
            
        } else {
            throw new Error(data.message || '<?= lang('App.error') ?>');
        }
    } catch (error) {
        console.error('Save settings error:', error);
        showToast('error', error.message);
        
        Swal.fire({
            title: '<?= lang('App.error') ?>',
            text: error.message,
            icon: 'error',
            confirmButtonColor: '#007bff'
        });
        
        throw error;
    }
}

// Setup file upload previews
function setupFileUploadPreviews() {
    // Logo preview
    document.getElementById('app_logo').addEventListener('change', function(e) {
        previewFile(e.target, 'logo');
    });
    
    // Favicon preview
    document.getElementById('app_favicon').addEventListener('change', function(e) {
        previewFile(e.target, 'favicon');
    });
}

// Preview uploaded files
function previewFile(input, type) {
    const file = input.files[0];
    if (!file) return;
    
    // Validate file type
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml'];
    if (!validTypes.includes(file.type)) {
        showToast('error', '<?= lang('App.invalid_image_type') ?>');
        input.value = '';
        return;
}

    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        showToast('error', '<?= lang('App.image_too_large') ?>');
        input.value = '';
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = type === 'logo' ? 
            input.closest('.form-group').querySelector('.preview-container') :
            input.closest('.form-group').querySelector('.preview-container');
        
        if (preview) {
            preview.classList.add('has-image');
            const imgClass = type === 'favicon' ? 'favicon-preview' : 'preview-image';
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="${imgClass} mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remove_${type}" name="remove_${type}" value="1">
                    <label class="form-check-label text-danger" for="remove_${type}">
                        <i class="ri-delete-bin-line me-1"></i>
                        <?= lang('App.remove_current_' . '${type}') ?>
                    </label>
        </div>
    `;
            showToast('success', `<?= lang('App.main_logo') ?> preview updated`);
        }
    };
    reader.readAsDataURL(file);
}

// Show toast notification
function showToast(type, message) {
    if (typeof Toastify !== 'undefined') {
        const colors = {
            success: '#28a745',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8'
        };
        
        Toastify({
            text: message,
            duration: 4000,
            gravity: 'top',
            position: 'right',
            style: {
                background: colors[type] || colors.info
            },
            stopOnFocus: true,
            close: true,
            onClick: function() {
                this.hideToast();
            }
        }).showToast();
    }
}

// Auto-save functionality (optional)
function enableAutoSave() {
    const form = document.getElementById('settingsForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Add visual indicator for unsaved changes
            this.classList.add('border-warning');
            showToast('info', '<?= lang('App.settings') ?> changed');
            
            // Remove indicator after 2 seconds
            setTimeout(() => {
                this.classList.remove('border-warning');
            }, 2000);
        });
    });
}

// Test Lima Links connection
function testLimaLinks() {
    const apiKey = document.getElementById('lima_api_key').value;
    const brandedDomain = document.getElementById('lima_branded_domain').value;
    const status = document.getElementById('lima-status');
    
    if (!apiKey) {
                    showToast('error', 'Please enter your MDA Links API Key first');
        return;
    }
    
    status.innerHTML = '<i class="ri-loader-line spinner"></i> Testing connection...';
    
    // Create form data
    const formData = new FormData();
    formData.append('api_key', apiKey);
    if (brandedDomain) {
        formData.append('branded_domain', brandedDomain);
    }
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    // Call backend endpoint instead of direct API call
    fetch('<?= base_url('settings/testLimaLinks') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
        .then(data => {
            if (data.success) {
            status.innerHTML = '<i class="ri-check-line text-success"></i> Connection successful';
                showToast('success', data.message);
            
            // Show detailed success information
            if (data.data) {
                Swal.fire({
                    title: 'MDA Links Test Successful!',
                    html: `
                        <div class="text-start">
                            <p><strong>✅ Connection Status:</strong> Success</p>
                            <p><strong>🔗 Test URL Created:</strong><br>
                               <small class="text-muted">${data.data.original_url}</small><br>
                               <strong>→ ${data.data.short_url}</strong>
                            </p>
                            <p><strong>🌐 Domain:</strong> ${data.data.domain}</p>
                            <hr>
                            <p class="text-success"><i class="ri-check-circle-line"></i> Your MDA Links API is working correctly!</p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Perfect!',
                    width: 500
                });
            }
        } else {
            status.innerHTML = '<i class="ri-close-line text-danger"></i> Connection failed';
            showToast('error', data.message);
            
            // Enhanced error information with debugging
            let debugInfo = '';
            if (data.debug) {
                debugInfo = `
                    <hr>
                    <div class="text-start">
                        <h6>🔍 Debug Information:</h6>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px; max-height: 200px; overflow-y: auto;">
                `;
                
                if (data.debug.api_key_prefix) {
                    debugInfo += `<div><strong>API Key:</strong> ${data.debug.api_key_prefix}</div>`;
                }
                if (data.debug.http_code) {
                    debugInfo += `<div><strong>HTTP Code:</strong> ${data.debug.http_code}</div>`;
                }
                if (data.debug.raw_response) {
                    debugInfo += `<div><strong>API Response:</strong><br><pre style="white-space: pre-wrap; word-break: break-all;">${data.debug.raw_response}</pre></div>`;
                }
                if (data.debug.parsed_data) {
                    debugInfo += `<div><strong>Parsed Data:</strong><br><pre>${JSON.stringify(data.debug.parsed_data, null, 2)}</pre></div>`;
                }
                
                debugInfo += `
            </div>
        </div>
    `;
            }
            
            // Show detailed error information
            Swal.fire({
                title: 'MDA Links Test Failed',
                html: `
                    <div class="text-start">
                        <p><strong>❌ Error:</strong> ${data.message}</p>
                        <hr>
                        <p><strong>💡 Common solutions:</strong></p>
                        <ul class="text-start">
                            <li>Verify your API key is correct (check MDA Links dashboard)</li>
                            <li>Check if your account has remaining quota</li>
                            <li>Ensure branded domain is properly configured</li>
                            <li>Try again in a few minutes (rate limiting)</li>
                            <li>Check if your API key starts with 'll_'</li>
                        </ul>
                        ${debugInfo}
                    </div>
                `,
                icon: 'error',
                confirmButtonText: 'Try Again',
                width: 600,
                customClass: {
                    htmlContainer: 'text-left'
                }
            });
        }
    })
    .catch(error => {
        console.error('Lima Links test error:', error);
        status.innerHTML = '<i class="ri-close-line text-danger"></i> Connection failed';
        showToast('error', 'Connection failed: ' + error.message);
        
        Swal.fire({
            title: 'Connection Error',
            text: 'Failed to test MDA Links connection. Please check your internet connection and try again.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

// Show Lima Links service information
function showLimaLinksInfo() {
    Swal.fire({
        title: 'MDA Links Service Information',
        html: `
            <div class="text-start">
                <h6><i class="ri-star-line text-warning"></i> Features:</h6>
                <ul>
                    <li>Professional branded domains</li>
                    <li>Click analytics and tracking</li>
                    <li>Custom aliases and QR codes</li>
                    <li>99.9% uptime SLA</li>
                    <li>Password protection & expiration</li>
                </ul>
                
                <h6><i class="ri-money-dollar-circle-line text-success"></i> Pricing:</h6>
                <ul>
                    <li><strong>Free:</strong> 1,000 links/month</li>
                    <li><strong>Pro ($9/month):</strong> 25,000 links + branded domains</li>
                    <li><strong>Enterprise:</strong> Custom pricing</li>
                </ul>
                
                <h6><i class="ri-links-line text-info"></i> Setup:</h6>
                <ol>
                                                            <li>Visit <a href="<?= \App\Helpers\LimaLinksHelper::getApiBaseUrl() ?>" target="_blank">MDA Links</a></li>
                    <li>Create account and get API key</li>
                    <li>Optional: Setup branded domain</li>
                    <li>Configure API key above and test</li>
                </ol>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Got it!',
        width: 600
    });
}

// Refresh shortener statistics
function refreshShortenerStats() {
    // Mock statistics for now - in real implementation, this would fetch from database
    const stats = {
        urls_shortened: Math.floor(Math.random() * 1000) + 500,
        lima_success: Math.floor(Math.random() * 800) + 400,
        fallback_used: Math.floor(Math.random() * 200) + 100,
        chars_saved: Math.floor(Math.random() * 50000) + 25000
    };
    
    // Animate numbers
    animateNumber('urls-shortened', stats.urls_shortened);
    animateNumber('lima-success', stats.lima_success);
    animateNumber('fallback-used', stats.fallback_used);
    animateNumber('chars-saved', stats.chars_saved);
    
    showToast('success', 'Statistics refreshed successfully');
}

// Animate number counting
function animateNumber(elementId, targetNumber) {
    const element = document.getElementById(elementId);
    const startNumber = 0;
    const duration = 1000; // 1 second
    const increment = targetNumber / (duration / 16); // 60 FPS
    
    let currentNumber = startNumber;
    
    const timer = setInterval(() => {
        currentNumber += increment;
        if (currentNumber >= targetNumber) {
            currentNumber = targetNumber;
            clearInterval(timer);
        }
        element.textContent = Math.floor(currentNumber).toLocaleString();
    }, 16);
}
</script>
<?= $this->endSection() ?>
