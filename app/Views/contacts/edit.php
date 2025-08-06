<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>App.edit_contact<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>App.edit_contact<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>App.contacts<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* Modern Clean Professional Design */
    .edit-contact-wrapper {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
        margin: -1.5rem -1.5rem 0 -1.5rem;
    }
    
    .modern-breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 2rem;
        font-size: 0.875rem;
    }
    
    .modern-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #6c757d;
        font-weight: 500;
    }
    
    .modern-breadcrumb a {
        color: #6c757d;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .modern-breadcrumb a:hover {
        color: #3577f1;
    }
    
    .page-title-modern {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }
    
    .page-subtitle {
        color: #718096;
        font-size: 1.125rem;
        margin-bottom: 2.5rem;
        font-weight: 400;
    }
    
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-group-modern {
        margin-bottom: 2rem;
        position: relative;
    }
    
    .form-section {
        background: #fff;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .form-section:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transform: translateY(-1px);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f7fafc;
    }
    
    .section-icon {
        width: 20px;
        height: 20px;
        color: #3577f1;
    }
    
    .label-modern {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .label-icon {
        width: 14px;
        height: 14px;
        color: #9ca3af;
    }
    
    .input-modern,
    .select-modern {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.9375rem;
        background: #fff;
        transition: all 0.3s ease;
        font-family: inherit;
        line-height: 1.5;
    }
    
    .input-modern:focus,
    .select-modern:focus {
        outline: none;
        border-color: #3577f1;
        box-shadow: 0 0 0 3px rgba(53, 119, 241, 0.1);
        background: #fff;
    }
    
    .input-modern:hover:not(:focus),
    .select-modern:hover:not(:focus) {
        border-color: #d1d5db;
    }
    
    .input-modern::placeholder {
        color: #9ca3af;
        font-size: 0.875rem;
    }
    
    .required-indicator {
        color: #ef4444;
        font-weight: 600;
        margin-left: 2px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .form-row {
            grid-template-columns: 1fr 1fr;
        }
        
        .form-row.single {
            grid-template-columns: 1fr;
        }
    }
    
    .info-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #bfdbfe;
    }
    
    .info-badge-icon {
        width: 16px;
        height: 16px;
        color: #3b82f6;
    }
    
    .toggle-modern {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        user-select: none;
        width: 100%;
        box-sizing: border-box;
    }
    
    .toggle-modern:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    
    /* Validation messages */
    .form-validation {
        display: none;
        align-items: center;
        gap: 0.5rem;
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    
    .form-validation[style*="display: flex"] {
        display: flex !important;
    }
    
    .validation-icon {
        width: 14px;
        height: 14px;
        color: #dc2626;
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
        background: #cbd5e1;
        border-radius: 13px;
        transition: background 0.3s ease;
        border: none;
        cursor: pointer;
        outline: none;
        flex-shrink: 0;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
    
    .toggle-switch:checked {
        background: #0ab39c;
    }
    
    .toggle-switch::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 22px;
        height: 22px;
        background: #fff;
        border-radius: 50%;
        transition: transform 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .toggle-switch:checked::before {
        transform: translateX(22px);
    }
    
    .toggle-switch:focus {
        box-shadow: 0 0 0 3px rgba(10, 179, 156, 0.2);
    }
    
    .toggle-switch:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .toggle-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        flex: 1;
        line-height: 1.4;
    }
    
    .toggle-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .toggle-title {
        font-weight: 500;
        color: #374151;
        font-size: 0.9375rem;
    }
    
    .toggle-description {
        color: #6b7280;
        font-weight: 400;
        font-size: 0.8125rem;
        line-height: 1.3;
    }
    
    .password-group {
        position: relative;
        margin-top: 1.5rem;
        padding: 1.5rem;
        background: #fafbfc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        display: none;
    }
    
    .password-group.show {
        display: block;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .password-input-group {
        position: relative;
    }
    
    .password-toggle-btn {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: color 0.2s ease;
    }
    
    .password-toggle-btn:hover {
        color: #374151;
    }
    
    .password-toggle-icon {
        width: 16px;
        height: 16px;
    }
    
    .btn-group-modern {
        display: flex;
        gap: 1rem;
        justify-content: space-between;
        align-items: center;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #e5e7eb;
    }
    
    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.9375rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        cursor: pointer;
        font-family: inherit;
    }
    
    .btn-primary {
        background: #3577f1;
        color: #fff;
        border-color: #3577f1;
    }
    
    .btn-primary:hover {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(53, 119, 241, 0.3);
    }
    
    .btn-secondary {
        background: #fff;
        color: #6b7280;
        border-color: #d1d5db;
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #374151;
        transform: translateY(-1px);
    }
    
    .btn-icon {
        width: 16px;
        height: 16px;
    }
    
    .alert-modern {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        border: 1px solid transparent;
    }
    
    .alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-color: #fecaca;
    }
    
    .alert-icon {
        width: 18px;
        height: 18px;
        margin-top: 1px;
        flex-shrink: 0;
    }
    
    .form-validation {
        margin-top: 0.5rem;
        font-size: 0.8125rem;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .validation-icon {
        width: 12px;
        height: 12px;
    }
    
    /* Contact Groups Styles */
    .groups-container {
        min-height: 60px;
        border: 2px dashed #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        background: #fafbfc;
        transition: all 0.3s ease;
    }
    
    .groups-container.has-groups {
        border-style: solid;
        background: #fff;
    }
    
    .loading-groups {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #9ca3af;
        font-size: 0.875rem;
        justify-content: center;
    }
    
    .group-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        margin: 0.25rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }
    
    .group-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .group-icon {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }
    
    .group-name {
        font-weight: 500;
        color: #374151;
    }
    
    .group-remove-btn {
        background: none;
        border: none;
        color: #9ca3af;
        padding: 2px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .group-remove-btn:hover {
        color: #ef4444;
        background: #fef2f2;
    }
    
    .remove-icon {
        width: 12px;
        height: 12px;
    }
    
    .input-group-modern {
        display: flex;
        gap: 0.75rem;
        align-items: end;
    }
    
    .input-group-modern .select-modern {
        flex: 1;
    }
    
    .input-group-modern .btn-modern {
        flex-shrink: 0;
    }
    
    .no-groups-message {
        color: #9ca3af;
        font-style: italic;
        text-align: center;
        padding: 1rem;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .edit-contact-wrapper {
            padding: 1rem 0;
            margin: -1rem -1rem 0 -1rem;
        }
        
        .page-title-modern {
            font-size: 1.5rem;
        }
        
        .form-section {
            padding: 1.5rem;
        }
        
        .btn-group-modern {
            flex-direction: column-reverse;
            gap: 0.75rem;
        }
        
        .btn-modern {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="edit-contact-wrapper">
    <div class="container-fluid">
        <div class="form-container">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb modern-breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('dashboard') ?>">
                            <i data-feather="home" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                            <?= lang('App.dashboard') ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('contacts') ?>"><?= lang('App.contacts') ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= lang('App.edit_contact') ?></li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="page-title-modern"><?= lang('App.edit_contact') ?></h1>
                <p class="page-subtitle">Update contact information and user account details</p>
            </div>

            <!-- Alert Messages -->
                                    <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert-modern alert-danger">
                    <i data-feather="alert-circle" class="alert-icon"></i>
                    <div>
                        <strong>Error:</strong> <?= session()->getFlashdata('error') ?>
                    </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($validation)) : ?>
                <div class="alert-modern alert-danger">
                    <i data-feather="alert-triangle" class="alert-icon"></i>
                    <div>
                        <strong>Validation Error:</strong>
                                            <?= $validation->listErrors() ?>
                    </div>
                                        </div>
                                    <?php endif; ?>

            <!-- Main Form -->
            <form action="<?= base_url("contacts/update/{$contact['user_id']}") ?>" method="post" class="needs-validation" novalidate>
                                        <?= csrf_field() ?>
                
                <!-- Client Assignment Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i data-feather="briefcase" class="section-icon"></i>
                        Client Assignment
                    </h2>
                    
                    <div class="form-group-modern">
                        <label for="client_id" class="label-modern">
                            <i data-feather="briefcase" class="label-icon"></i>
                            Select Client
                            <span class="required-indicator">*</span>
                        </label>
                        <select class="select-modern" id="client_id" name="client_id" required>
                            <option value="">Choose a client...</option>
                                                        <?php foreach ($clients as $client) : ?>
                                                            <option value="<?= $client['id'] ?>" <?= old('client_id', $contact['client_id']) == $client['id'] ? 'selected' : '' ?>>
                                                                <?= esc($client['name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                        <div class="form-validation" style="display: none;">
                            <i data-feather="alert-circle" class="validation-icon"></i>
                            Please select a client
                                                    </div>
                                                </div>
                                            </div>

                <!-- User Account Section -->
                                            <?php if (isset($has_user) && $has_user): ?>
                <div class="form-section">
                    <h2 class="section-title">
                        <i data-feather="user-check" class="section-icon"></i>
                        User Account
                    </h2>
                    
                    <div class="info-badge">
                        <i data-feather="info" class="info-badge-icon"></i>
                                                                <div>
                            <strong><?= esc($contact['user']['username'] ?? 'User Account Active') ?></strong>
                            <span class="text-muted ms-2">• <?= ucfirst($contact['user']['user_type'] ?? 'client') ?> user</span>
                                                            </div>
                                                        </div>
                                                    
                    <div class="form-row">
                        <div class="form-group-modern">
                            <label for="first_name" class="label-modern">
                                <i data-feather="user" class="label-icon"></i>
                                First Name
                                <span class="required-indicator">*</span>
                            </label>
                            <input type="text" class="input-modern" id="first_name" name="first_name" 
                                   value="<?= old('first_name', $contact['user']['first_name'] ?? '') ?>" 
                                   placeholder="Enter first name" required>
                                                                        </div>
                        
                        <div class="form-group-modern">
                            <label for="last_name" class="label-modern">
                                <i data-feather="user" class="label-icon"></i>
                                Last Name
                            </label>
                            <input type="text" class="input-modern" id="last_name" name="last_name" 
                                   value="<?= old('last_name', $contact['user']['last_name'] ?? '') ?>" 
                                   placeholder="Enter last name">
                                                                    </div>
                                                                </div>
                                                                
                    <div class="toggle-modern">
                        <input type="checkbox" class="toggle-switch" id="change_password" name="change_password" value="1" <?= old('change_password') ? 'checked' : '' ?>>
                        <div class="toggle-label">
                            <i data-feather="key" class="label-icon"></i>
                            <div class="toggle-content">
                                <div class="toggle-title">Update Password</div>
                                <div class="toggle-description">Enable to change user password</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                    <div class="password-group" id="password-fields">
                        <div class="form-row">
                            <div class="form-group-modern">
                                <label for="password" class="label-modern">
                                    <i data-feather="lock" class="label-icon"></i>
                                    New Password
                                    <span class="required-indicator">*</span>
                                </label>
                                <div class="password-input-group">
                                    <input type="password" class="input-modern" id="password" name="password" 
                                           placeholder="Enter new password">
                                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password')">
                                        <i data-feather="eye" class="password-toggle-icon" id="password-icon"></i>
                                                                                </button>
                                                                        </div>
                                                                    </div>
                                                                    
                            <div class="form-group-modern">
                                <label for="password_confirm" class="label-modern">
                                    <i data-feather="lock" class="label-icon"></i>
                                    Confirm Password
                                    <span class="required-indicator">*</span>
                                </label>
                                <input type="password" class="input-modern" id="password_confirm" name="password_confirm" 
                                       placeholder="Confirm new password">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                <?php endif; ?>

                <!-- Contact Information Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i data-feather="phone" class="section-icon"></i>
                        Contact Information
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group-modern">
                            <label for="position" class="label-modern">
                                <i data-feather="briefcase" class="label-icon"></i>
                                Position / Title
                                                            </label>
                            <input type="text" class="input-modern" id="position" name="position" 
                                   value="<?= old('position', $contact['position'] ?? '') ?>" 
                                   placeholder="e.g., Manager, Director, Sales Rep">
                                                    </div>
                                                
                        <div class="form-group-modern">
                            <label for="email" class="label-modern">
                                <i data-feather="mail" class="label-icon"></i>
                                Email Address
                                <span class="required-indicator">*</span>
                            </label>
                            <input type="email" class="input-modern" id="email" name="email" 
                                   value="<?= old('email', $contact['email']) ?>" 
                                   placeholder="contact@company.com" required>
                                                                </div>
                                                            </div>
                                                            
                    <div class="form-row">
                        <div class="form-group-modern">
                            <label for="phone" class="label-modern">
                                <i data-feather="phone" class="label-icon"></i>
                                Phone Number
                            </label>
                            <input type="tel" class="input-modern" id="phone" name="phone" 
                                   value="<?= old('phone', $contact['phone'] ?? '') ?>" 
                                   placeholder="+1 (555) 123-4567">
                                                            </div>
                                                            
                        <div class="form-group-modern">
                            <label for="status" class="label-modern">
                                <i data-feather="activity" class="label-icon"></i>
                                Status
                            </label>
                            <select class="select-modern" id="status" name="status">
                                <option value="active" <?= old('status', $contact['status']) === 'active' ? 'selected' : '' ?>>
                                    Active
                                </option>
                                <option value="inactive" <?= old('status', $contact['status']) === 'inactive' ? 'selected' : '' ?>>
                                    Inactive
                                </option>
                            </select>
                                                                </div>
                                                            </div>
                                                            
                    <div class="form-row single">
                        <div class="toggle-modern">
                            <input type="checkbox" class="toggle-switch" id="is_primary" name="is_primary" value="1" <?= old('is_primary', $contact['is_primary']) ? 'checked' : '' ?>>
                            <div class="toggle-label">
                                <i data-feather="star" class="label-icon"></i>
                                <div class="toggle-content">
                                    <div class="toggle-title">Set as Primary Contact</div>
                                    <div class="toggle-description">This will be the main contact for the client</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                <!-- Contact Groups Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i data-feather="users" class="section-icon"></i>
                        Access Groups & Permissions
                    </h2>
                    
                    <div class="info-badge">
                        <i data-feather="info" class="info-badge-icon"></i>
                        <div>
                            <strong>Group Management</strong>
                            <div class="toggle-description">Assign this contact to groups to manage their access permissions and features</div>
                                                                </div>
                                                            </div>
                                                            
                    <div class="form-group-modern">
                        <label class="label-modern">
                            <i data-feather="shield" class="label-icon"></i>
                            Current Groups
                        </label>
                        
                        <div id="current-groups" class="groups-container">
                            <div class="loading-groups">
                                <i data-feather="loader" style="width: 16px; height: 16px; animation: spin 1s linear infinite;"></i>
                                <span>Loading groups...</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                    <div class="form-group-modern">
                        <label class="label-modern">
                            <i data-feather="plus-circle" class="label-icon"></i>
                            Assign to New Group
                        </label>
                        
                        <div class="input-group-modern">
                            <select class="select-modern" id="available-groups">
                                <option value="">Select a group to assign...</option>
                                                                        </select>
                            <button type="button" class="btn-modern btn-primary" id="assign-group-btn" disabled>
                                <i data-feather="plus" class="btn-icon"></i>
                                Assign
                            </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                <!-- Action Buttons -->
                <div class="btn-group-modern">
                    <a href="<?= base_url("contacts/{$contact['user_id']}") ?>" class="btn-modern btn-secondary">
                        <i data-feather="arrow-left" class="btn-icon"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-modern btn-primary">
                        <i data-feather="save" class="btn-icon"></i>
                        Update Contact
                    </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Auto-focus first input
    document.getElementById('client_id').focus();
    
    // Setup toggle functionality
    setupToggleHandlers();
    
    // Load contact groups
    loadContactGroups();
    loadAvailableGroups();
    
    // Debug: Add click handler to submit button
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            console.log('Submit button clicked');
            console.log('Button type:', this.type);
            console.log('Form element:', this.closest('form'));
        });
    }
});

// Contact Groups Management
const contactId = <?= $contact['user_id'] ?>;

function loadContactGroups() {
    fetch(`<?= base_url('contact-groups/get-user-groups') ?>?user_id=${contactId}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('current-groups');
            
            if (data.status && data.data && data.data.length > 0) {
                container.classList.add('has-groups');
                container.innerHTML = data.data.map(group => `
                    <div class="group-item" style="border-color: ${group.color}">
                        <i data-feather="${group.icon}" class="group-icon" style="color: ${group.color}"></i>
                        <span class="group-name">${group.name}</span>
                        <button type="button" class="group-remove-btn" onclick="removeFromGroup(${group.id}, '${group.name}')">
                            <i data-feather="x" class="remove-icon"></i>
                        </button>
                    </div>
                `).join('');
            } else {
                container.classList.remove('has-groups');
                container.innerHTML = '<div class="no-groups-message">No groups assigned</div>';
            }
            
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        })
        .catch(error => {
            console.error('Error loading groups:', error);
            document.getElementById('current-groups').innerHTML = 
                '<div class="no-groups-message">Error loading groups</div>';
        });
}

function loadAvailableGroups() {
    fetch(`<?= base_url('contact-groups/get-available-groups') ?>?user_id=${contactId}`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('available-groups');
            
            if (data.status && data.data) {
                select.innerHTML = '<option value="">Select a group to assign...</option>';
                data.data.forEach(group => {
                    select.innerHTML += `<option value="${group.id}">${group.name}</option>`;
                });
            }
            
            updateAssignButton();
        })
        .catch(error => {
            console.error('Error loading available groups:', error);
        });
}

function updateAssignButton() {
    const select = document.getElementById('available-groups');
    const button = document.getElementById('assign-group-btn');
    
    button.disabled = !select.value;
}

function assignToGroup() {
    const groupId = document.getElementById('available-groups').value;
    
    if (!groupId) return;
    
    const button = document.getElementById('assign-group-btn');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i data-feather="loader" class="btn-icon" style="animation: spin 1s linear infinite;"></i> Assigning...';
    
    fetch('<?= base_url('contact-groups/assign-user') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${contactId}&group_id=${groupId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            showNotification('User assigned to group successfully', 'success');
            loadContactGroups();
            loadAvailableGroups();
        } else {
            showNotification(data.message || 'Failed to assign user to group', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while assigning user to group', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}

function removeFromGroup(groupId, groupName) {
    if (!confirm(`Are you sure you want to remove this contact from "${groupName}" group?`)) {
        return;
    }
    
    fetch('<?= base_url('contact-groups/remove-user') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${contactId}&group_id=${groupId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            showNotification('User removed from group successfully', 'success');
            loadContactGroups();
            loadAvailableGroups();
        } else {
            showNotification(data.message || 'Failed to remove user from group', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while removing user from group', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        max-width: 350px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    // Set background color based on type
    const colors = {
        success: '#0ab39c',
        error: '#f06548',
        info: '#3577f1',
        warning: '#f7b84b'
    };
    
    notification.style.backgroundColor = colors[type] || colors.info;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 4 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 4000);
}

// Event listeners
document.getElementById('available-groups').addEventListener('change', updateAssignButton);
document.getElementById('assign-group-btn').addEventListener('click', assignToGroup);

function setupToggleHandlers() {
    // Password toggle handler
    const passwordToggle = document.getElementById('change_password');
    const passwordToggleContainer = passwordToggle.closest('.toggle-modern');
    
    // Add click handler to the container
    passwordToggleContainer.addEventListener('click', function(e) {
        if (e.target !== passwordToggle) {
            e.preventDefault();
            passwordToggle.checked = !passwordToggle.checked;
            togglePasswordFields();
        }
    });
    
    // Add change handler to the checkbox
    passwordToggle.addEventListener('change', togglePasswordFields);
    
    // Primary contact toggle handler
    const primaryToggle = document.getElementById('is_primary');
    const primaryToggleContainer = primaryToggle.closest('.toggle-modern');
    
    // Add click handler to the container
    primaryToggleContainer.addEventListener('click', function(e) {
        if (e.target !== primaryToggle) {
            e.preventDefault();
            primaryToggle.checked = !primaryToggle.checked;
        }
    });
    
    // Initialize password fields visibility if checkbox is already checked
    console.log('Initial password toggle state:', passwordToggle.checked);
    if (passwordToggle.checked) {
        togglePasswordFields();
    }
    
    // Debug: Show initial state of all required fields
    const allRequiredFields = document.querySelectorAll('[required]');
    console.log('Initial required fields:', Array.from(allRequiredFields).map(f => ({
        name: f.name, 
        value: f.value, 
        type: f.type,
        required: f.required
    })));
}

function togglePasswordFields() {
    const checkbox = document.getElementById('change_password');
    const passwordGroup = document.getElementById('password-fields');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirm');
    
    console.log('Toggling password fields. Checkbox checked:', checkbox.checked);
    
    if (checkbox.checked) {
        passwordGroup.classList.add('show');
        passwordInput.setAttribute('required', '');
        confirmInput.setAttribute('required', '');
        console.log('Password fields set to required');
        // Focus on password field after animation
        setTimeout(() => {
            passwordInput.focus();
        }, 300);
    } else {
        passwordGroup.classList.remove('show');
        passwordInput.removeAttribute('required');
        confirmInput.removeAttribute('required');
        passwordInput.value = '';
        confirmInput.value = '';
        console.log('Password fields removed from required');
    }
}

function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-feather', 'eye-off');
    } else {
        input.type = 'password';
        icon.setAttribute('data-feather', 'eye');
    }
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Form validation
document.querySelector('.needs-validation').addEventListener('submit', function(event) {
    console.log('Form submit event triggered');
    console.log('Form validity:', this.checkValidity());
    
    if (!this.checkValidity()) {
        console.log('Form validation failed');
        event.preventDefault();
        event.stopPropagation();
        
        // Show validation messages
        const requiredInputs = this.querySelectorAll('[required]');
        console.log('Total required inputs found:', requiredInputs.length);
        
        requiredInputs.forEach(input => {
            console.log('Checking field:', input.name, 'Value:', input.value, 'Valid:', input.checkValidity());
            
            const validation = input.parentNode.querySelector('.form-validation');
            if (!input.checkValidity()) {
                console.log('Invalid field:', input.name, 'Validation message:', input.validationMessage);
                
                if (validation) {
                    validation.style.display = 'flex';
                    input.style.borderColor = '#dc2626';
                } else {
                    // Create validation message if it doesn't exist
                    const validationDiv = document.createElement('div');
                    validationDiv.className = 'form-validation';
                    validationDiv.style.cssText = `
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        color: #dc2626;
                        font-size: 0.875rem;
                        margin-top: 0.5rem;
                    `;
                    validationDiv.innerHTML = `
                        <i data-feather="alert-circle" class="validation-icon" style="width: 14px; height: 14px;"></i>
                        ${input.validationMessage || 'This field is required'}
                    `;
                    input.parentNode.appendChild(validationDiv);
                    
                    // Replace feather icons
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                }
                
                input.style.borderColor = '#dc2626';
            } else {
                if (validation) {
                    validation.style.display = 'none';
                }
                input.style.borderColor = '#e5e7eb';
            }
        });
    } else {
        console.log('Form validation passed, submitting...');
    }
    
    this.classList.add('was-validated');
});

// Real-time validation
document.querySelectorAll('[required]').forEach(input => {
    input.addEventListener('input', function() {
        const validation = this.parentNode.querySelector('.form-validation');
        if (validation) {
            if (this.value.trim()) {
                validation.style.display = 'none';
                this.style.borderColor = '#e5e7eb';
            }
        }
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->include('partials/footer') ?>
