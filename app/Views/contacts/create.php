<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.create_contact') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.create_contact') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.contacts') ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Modern Professional Styling */
.create-contact-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
    position: relative;
    overflow: hidden;
}

.create-contact-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
    pointer-events: none;
}

.main-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 24px;
    box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.main-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
}

.main-card:hover {
    transform: translateY(-5px);
    box-shadow: 
        0 35px 70px rgba(0, 0, 0, 0.2),
        0 0 0 1px rgba(255, 255, 255, 0.1);
}

.card-header-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 20px 20px 0 0;
    padding: 1.5rem 2rem;
    position: relative;
    overflow: hidden;
}

.card-header-modern::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 50%);
    border-radius: 50%;
    transform: translate(30px, -30px);
}

.card-title-modern {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.card-title-modern i {
    font-size: 1.75rem;
    opacity: 0.9;
}

.form-floating-modern {
    position: relative;
    margin-bottom: 1.5rem;
}

.form-control-modern {
    border: 2px solid rgba(102, 126, 234, 0.1);
    border-radius: 12px;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.8);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.form-control-modern:focus {
    border-color: #667eea;
    box-shadow: 
        0 0 0 0.2rem rgba(102, 126, 234, 0.25),
        0 4px 20px rgba(102, 126, 234, 0.15);
    transform: translateY(-2px);
}

.form-label-modern {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
}

.form-label-modern i {
    color: #667eea;
    font-size: 1.1rem;
}

.section-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
    border: 1px solid rgba(102, 126, 234, 0.1);
    border-radius: 16px;
    padding: 0;
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.section-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.section-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1rem 1.5rem;
    border: none;
    position: relative;
    overflow: hidden;
}

.section-header::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 60px;
    height: 60px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 50%);
    border-radius: 50%;
    transform: translate(20px, -20px);
}

.section-title {
    color: white;
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    font-size: 1.2rem;
}

.form-switch-modern {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    margin: 0;
}

.form-switch-modern input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider-modern {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
    transition: 0.4s;
    border-radius: 34px;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.slider-modern:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background: white;
    transition: 0.4s;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

input:checked + .slider-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

input:focus + .slider-modern {
    box-shadow: 0 0 1px #667eea;
}

input:checked + .slider-modern:before {
    transform: translateX(26px);
}

.btn-modern {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.btn-primary-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
}

.btn-light-modern {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    color: #4a5568;
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.btn-light-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    color: #667eea;
}

.input-group-modern {
    position: relative;
}

.input-group-modern .btn {
    border: 2px solid rgba(102, 126, 234, 0.1);
    border-left: none;
    border-radius: 0 12px 12px 0;
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    transition: all 0.3s ease;
}

.input-group-modern .btn:hover {
    background: #667eea;
    color: white;
}

.alert-modern {
    border: none;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
}

.alert-danger-modern {
    background: linear-gradient(135deg, rgba(245, 101, 101, 0.1) 0%, rgba(245, 101, 101, 0.05) 100%);
    border-left: 4px solid #f56565;
    color: #c53030;
}

.fade-in {
    animation: fadeInUp 0.6s ease-out;
}

.fade-in-delay {
    animation: fadeInUp 0.6s ease-out 0.2s both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.floating-label {
    position: relative;
}

.floating-label input:focus + label,
.floating-label input:not(:placeholder-shown) + label {
    transform: translateY(-1.5rem) scale(0.85);
    color: #667eea;
}

.floating-label label {
    position: absolute;
    top: 1rem;
    left: 1.25rem;
    transition: all 0.3s ease;
    pointer-events: none;
    background: white;
    padding: 0 0.25rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .create-contact-container {
        padding: 1rem 0;
    }
    
    .main-card {
        margin: 0 0.5rem;
        border-radius: 16px;
    }
    
    .card-header-modern {
        padding: 1rem 1.5rem;
    }
    
    .card-title-modern {
        font-size: 1.25rem;
    }
}

/* Custom Select Styling */
.select-modern {
    position: relative;
}

.select-modern select {
    appearance: none;
    background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") no-repeat right 0.75rem center/1.5em 1.5em;
}

/* Loading Animation */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid rgba(102, 126, 234, 0.3);
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
                        <div class="col-xxl-9">
                            <div class="card">
                                <div class="card-header bg-light border-bottom d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1">
                    <i class="ri-user-add-line me-2 text-primary"></i>
                    <?= lang('App.contact_information') ?>
                </h4>
            </div>
                                <div class="card-body">
                                    <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                        <i class="ri-error-warning-line me-3 align-middle fs-16"></i>
                        <strong><?= lang('App.error') ?>!</strong> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($validation)) : ?>
                    <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                        <i class="ri-error-warning-line me-3 align-middle fs-16"></i>
                        <strong><?= lang('App.validation_errors') ?>!</strong>
                                            <?= $validation->listErrors() ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <form action="<?= base_url('contacts/store') ?>" method="post" class="needs-validation" novalidate>
                                        <?= csrf_field() ?>
                    
                    <!-- Client Selection -->
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="client_id" class="form-label"><?= lang('App.client') ?> <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-lg-9">
                            <select class="form-select rounded-0" id="client_id" name="client_id" required>
                                                        <option value=""><?= lang('App.select') ?> <?= lang('App.client') ?></option>
                                                        <?php foreach ($clients as $client) : ?>
                                                            <option value="<?= $client['id'] ?>" <?= old('client_id') == $client['id'] ? 'selected' : '' ?>>
                                                                <?= esc($client['name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        <?= lang('App.please_select') ?> <?= lang('App.client') ?>
                                                    </div>
                                                </div>
                                            </div>

                    <!-- User Account Creation Section -->
                    <div class="row mb-4">
                                            <div class="col-lg-12">
                            <div class="card border border-dashed border-primary">
                                <div class="card-header bg-primary bg-opacity-10 border-bottom border-dashed">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-semibold text-primary">
                                                <i class="ri-account-circle-line me-2"></i>
                                                <?= lang('App.user_account_information') ?>
                                            </h6>
                                            <small class="text-muted"><?= lang('App.user_account_created_automatically') ?></small>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    
                                <!-- User Fields (always visible) -->
                                <div class="card-body">
                                    <input type="hidden" name="create_user" value="1">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                            <label for="first_name" class="form-label"><?= lang('App.first_name') ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-0" id="first_name" name="first_name" value="<?= old('first_name') ?>" required>
                                            <div class="invalid-feedback">
                                                <?= lang('App.please_enter') ?> <?= lang('App.first_name') ?>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                            <label for="last_name" class="form-label"><?= lang('App.last_name') ?></label>
                                                                    <input type="text" class="form-control rounded-0" id="last_name" name="last_name" value="<?= old('last_name') ?>">
                                                            </div>
                                                            
                                                            <div class="col-md-12">
                                            <label for="username" class="form-label"><?= lang('App.username') ?></label>
                                                                    <input type="text" class="form-control rounded-0" id="username" name="username" value="<?= old('username') ?>">
                                                                    <div class="form-text text-muted">
                                                <i class="ri-information-line me-1"></i>
                                                                        <?= lang('App.username_help') ?>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                            <label for="password" class="form-label"><?= lang('App.password') ?> <span class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                <input type="password" class="form-control rounded-0" id="password" name="password" required>
                                                <button class="btn btn-outline-secondary rounded-0" type="button" id="password-toggle">
                                                    <i class="ri-eye-line" id="password-icon"></i>
                                                                        </button>
                                                                    </div>
                                            <div class="invalid-feedback">
                                                <?= lang('App.please_enter') ?> <?= lang('App.password') ?>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                            <label for="password_confirm" class="form-label"><?= lang('App.password_confirm') ?> <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control rounded-0" id="password_confirm" name="password_confirm" required>
                                            <div class="invalid-feedback">
                                                <?= lang('App.please_confirm') ?> <?= lang('App.password') ?>
                                            </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                    <!-- Contact Details Section -->
                    <h5 class="mb-3 text-decoration-underline">
                        <i class="ri-contacts-line me-2 text-primary"></i>
                        <?= lang('App.contact_details') ?>
                    </h5>
                    
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="position" class="form-label"><?= lang('App.contact_position') ?></label>
                                                    </div>
                        <div class="col-lg-9">
                                                                    <input type="text" class="form-control rounded-0" id="position" name="position" value="<?= old('position') ?>">
                                                                </div>
                                                            </div>
                                                            
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="email" class="form-label"><?= lang('App.contact_email') ?> <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-lg-9">
                                                                    <input type="email" class="form-control rounded-0" id="email" name="email" value="<?= old('email') ?>" required>
                                                                    <div class="invalid-feedback">
                                                                        <?= lang('App.please_enter_valid') ?> <?= lang('App.email') ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="phone" class="form-label"><?= lang('App.contact_phone') ?></label>
                        </div>
                        <div class="col-lg-9">
                                                                    <input type="text" class="form-control rounded-0" id="phone" name="phone" value="<?= old('phone') ?>">
                                                                </div>
                                                            </div>
                                                            
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="status-select" class="form-label"><?= lang('App.active_status') ?></label>
                        </div>
                        <div class="col-lg-9">
                            <select class="form-select rounded-0" id="status-select" name="status">
                                                                            <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>><?= lang('App.active') ?></option>
                                                                            <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>><?= lang('App.inactive') ?></option>
                                                                        </select>
                                                                </div>
                                                            </div>
                                                            
                    <div class="row mb-4">
                        <div class="col-lg-3">
                            <label class="form-label"><?= lang('App.primary_contact') ?></label>
                        </div>
                        <div class="col-lg-9">
                                                                    <div class="form-check form-switch form-switch-success">
                                                                        <input class="form-check-input" type="checkbox" role="switch" id="is_primary" name="is_primary" value="1" <?= old('is_primary') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_primary"><?= lang('App.set_as_primary') ?></label>
                                                                    </div>
                            <div class="form-text text-muted">
                                <i class="ri-information-line me-1"></i>
                                <?= lang('App.primary_contact_help') ?>
                                                </div>
                                            </div>
                                        </div>

                    <!-- Action Buttons -->
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                <a href="<?= base_url('contacts') ?>" class="btn btn-light">
                                    <i class="ri-arrow-left-line me-1"></i>
                                    <?= lang('App.cancel') ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>
                                    <?= lang('App.save') ?>
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
    // Password visibility toggle
    const passwordToggle = document.getElementById('password-toggle');
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');
    
    if (passwordToggle) {
        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            passwordIcon.className = type === 'password' ? 'ri-eye-line' : 'ri-eye-off-line';
        });
    }
    
    // Auto-generate username from email
    const emailInput = document.getElementById('email');
    const usernameInput = document.getElementById('username');
    
    if (emailInput && usernameInput) {
        emailInput.addEventListener('input', function() {
            if (!usernameInput.value && this.value.includes('@')) {
                const username = this.value.split('@')[0];
                usernameInput.value = username;
            }
        });
    }
    
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        // Password confirmation validation
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        
        if (password.value !== passwordConfirm.value) {
            event.preventDefault();
            event.stopPropagation();
            passwordConfirm.setCustomValidity('<?= lang('App.passwords_not_match') ?>');
            passwordConfirm.classList.add('is-invalid');
        } else {
            passwordConfirm.setCustomValidity('');
            passwordConfirm.classList.remove('is-invalid');
        }
        
        form.classList.add('was-validated');
    });
    
    // Real-time password confirmation validation
    document.getElementById('password_confirm').addEventListener('input', function() {
        const password = document.getElementById('password');
        if (this.value !== password.value) {
            this.setCustomValidity('<?= lang('App.passwords_not_match') ?>');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
<?= $this->endSection() ?>
