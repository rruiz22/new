<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Profile.editProfile<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Profile.editProfile<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>Profile.profileSettings<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xxl-10">
            <div class="page-header d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="page-title mb-1"><?= lang('Profile.editProfile') ?></h4>
                    <p class="text-muted mb-0">Update your personal information and preferences</p>
                </div>
                <div>
                    <a href="<?= base_url('profile') ?>" class="btn btn-light">
                        <i class="ri-arrow-left-line me-1"></i> Back to Profile
                    </a>
                </div>
                                </div>

            <!-- Alert Messages -->
                                    <?php if (session()->has('message')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="ri-check-circle-line me-2"></i>
                                        <?= session('message') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="ri-error-warning-line me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                                            <?php foreach (session('errors') as $error): ?>
                                                <li><?= esc($error) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    <?php endif; ?>

            <form action="<?= base_url('profile/update') ?>" method="post" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                
                <div class="row">
                    <!-- Avatar Section -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card profile-avatar-card mb-4">
                            <div class="card-body text-center p-4">
                                <div class="avatar-section mb-3">
                                    <div class="position-relative d-inline-block">
                                        <?php 
                                        // Load avatar helper
                                        helper('avatar');
                                        
                                        // Use new avatar system with fallback
                                        $avatarUrl = getAvatarUrl($user, 120, $user->avatar_style ?? 'initials'); 
                                        ?>
                                        <img src="<?= $avatarUrl ?>" 
                                             class="rounded-circle img-thumbnail shadow-sm" 
                                             alt="Profile Avatar" 
                                             id="profile-img-preview"
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                        
                                        <!-- Upload button -->
                                        <div class="avatar-upload-btn">
                                            <label for="profile-img-file-input" class="btn btn-primary btn-sm rounded-circle position-absolute" style="bottom: 5px; right: 5px; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                <i class="ri-camera-line fs-14"></i>
                                            </label>
                                            <input id="profile-img-file-input" 
                                                   name="avatar" 
                                                   type="file" 
                                                   class="d-none" 
                                                   accept="image/png, image/jpeg, image/gif, image/webp">
                                        </div>

                                        <!-- Delete button (only show if user has uploaded avatar) -->
                                        <?php if (!empty($user->avatar) && file_exists(FCPATH . 'assets/images/users/' . $user->avatar)): ?>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm rounded-circle position-absolute" 
                                                id="delete-avatar-btn" 
                                                style="top: 5px; right: 5px; width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                                title="Delete current avatar">
                                            <i class="ri-delete-bin-line fs-12"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <h5 class="mb-1"><?= lang('Profile.changeAvatar') ?></h5>
                                <p class="text-muted small mb-3">
                                    Upload a custom image or 
                                    <a href="<?= base_url('profile/avatar-demo') ?>" class="text-primary text-decoration-none fw-medium">
                                        choose from avatar styles
                                    </a>
                                </p>
                                
                                <div class="avatar-info bg-light rounded p-3">
                                    <div class="d-flex align-items-center justify-content-center text-muted small">
                                        <i class="ri-information-line me-1"></i>
                                        Maximum file size: 2MB
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center text-muted small mt-1">
                                        Supported formats: JPG, PNG, GIF, WebP
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Avatar Style Selection -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="ri-palette-line me-2 text-primary"></i>
                                    Avatar Style
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="avatar_style" class="form-label small fw-medium">Fallback Style</label>
                                    <select class="form-select" id="avatar_style" name="avatar_style">
                                        <option value="initials" <?= old('avatar_style', $user->avatar_style ?? 'initials') === 'initials' ? 'selected' : '' ?>>✨ Initials (Recommended)</option>
                                        <option value="gravatar" <?= old('avatar_style', $user->avatar_style ?? 'initials') === 'gravatar' ? 'selected' : '' ?>>🌐 Gravatar</option>
                                        <option value="robohash" <?= old('avatar_style', $user->avatar_style ?? 'initials') === 'robohash' ? 'selected' : '' ?>>🤖 Robohash</option>
                                        <option value="identicon" <?= old('avatar_style', $user->avatar_style ?? 'initials') === 'identicon' ? 'selected' : '' ?>>🔶 Identicon</option>
                                    </select>
                                    <small class="text-muted">
                                        Used when no custom image is uploaded
                                    </small>
                                </div>
                                                </div>
                                            </div>
                                        </div>

                    <!-- Main Form Content -->
                    <div class="col-xl-8 col-lg-7">
                        <!-- Personal Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="ri-user-line me-2 text-primary"></i>
                                    <?= lang('Profile.personalInformation') ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                            <label for="first_name" class="form-label">
                                                <i class="ri-user-fill me-1 text-muted"></i>
                                                <?= lang('Profile.firstName') ?>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="first_name" 
                                                   name="first_name" 
                                                   value="<?= old('first_name', $user->first_name) ?>"
                                                   placeholder="Enter your first name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                            <label for="last_name" class="form-label">
                                                <i class="ri-user-fill me-1 text-muted"></i>
                                                <?= lang('Profile.lastName') ?>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="last_name" 
                                                   name="last_name" 
                                                   value="<?= old('last_name', $user->last_name) ?>"
                                                   placeholder="Enter your last name">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                            <label for="username" class="form-label">
                                                <i class="ri-at-line me-1 text-muted"></i>
                                                <?= lang('Profile.username') ?>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="username" 
                                                   value="<?= esc($user->username) ?>" 
                                                   readonly disabled>
                                            <div class="form-text">
                                                <i class="ri-lock-line me-1"></i>
                                                <?= lang('Profile.usernameCannotBeChanged') ?>
                                            </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                            <label for="email" class="form-label">
                                                <i class="ri-mail-line me-1 text-muted"></i>
                                                <?= lang('Profile.email') ?>
                                            </label>
                                            <input type="email" 
                                                   class="form-control" 
                                                   id="email" 
                                                   value="<?= esc($user->email) ?>" 
                                                   readonly disabled>
                                            <div class="form-text">
                                                <i class="ri-lock-line me-1"></i>
                                                <?= lang('Profile.emailCannotBeChanged') ?>
                                            </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                            <label for="phone" class="form-label">
                                                <i class="ri-phone-line me-1 text-muted"></i>
                                                <?= lang('Profile.phone') ?>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="phone" 
                                                   name="phone" 
                                                   value="<?= old('phone', $user->phone) ?>"
                                                   placeholder="Enter your phone number">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                            <label for="status_message" class="form-label">
                                                <i class="ri-chat-quote-line me-1 text-muted"></i>
                                                <?= lang('Profile.statusMessage') ?>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="status_message" 
                                                   name="status_message" 
                                                   value="<?= old('status_message', $user->status_message) ?>"
                                                   placeholder="Enter a status message">
                                            <div class="form-text"><?= lang('Profile.statusMessageHelp') ?></div>
                                        </div>
                                    </div>
                                                </div>
                                            </div>
                                        </div>

                        <!-- Preferences Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="ri-settings-3-line me-2 text-primary"></i>
                                    <?= lang('Profile.preferencesSettings') ?>
                                </h6>
                                        </div>
                            <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                            <label for="date_format" class="form-label">
                                                <i class="ri-calendar-line me-1 text-muted"></i>
                                                <?= lang('Profile.dateFormat') ?>
                                            </label>
                                                    <select class="form-select" id="date_format" name="date_format">
                                                        <option value="M-d-Y" <?= old('date_format', $user->date_format ?? 'M-d-Y') == 'M-d-Y' ? 'selected' : '' ?>>MM-DD-YYYY (03-25-2023)</option>
                                                        <option value="d-m-Y" <?= old('date_format', $user->date_format ?? 'M-d-Y') == 'd-m-Y' ? 'selected' : '' ?>>DD-MM-YYYY (25-03-2023)</option>
                                                        <option value="Y-m-d" <?= old('date_format', $user->date_format ?? 'M-d-Y') == 'Y-m-d' ? 'selected' : '' ?>>YYYY-MM-DD (2023-03-25)</option>
                                                        <option value="m/d/Y" <?= old('date_format', $user->date_format ?? 'M-d-Y') == 'm/d/Y' ? 'selected' : '' ?>>MM/DD/YYYY (03/25/2023)</option>
                                                        <option value="d/m/Y" <?= old('date_format', $user->date_format ?? 'M-d-Y') == 'd/m/Y' ? 'selected' : '' ?>>DD/MM/YYYY (25/03/2023)</option>
                                                        <option value="Y/m/d" <?= old('date_format', $user->date_format ?? 'M-d-Y') == 'Y/m/d' ? 'selected' : '' ?>>YYYY/MM/DD (2023/03/25)</option>
                                                    </select>
                                            <div class="form-text"><?= lang('Profile.dateFormatHelp') ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                            <label for="timezone" class="form-label">
                                                <i class="ri-time-zone-line me-1 text-muted"></i>
                                                <?= lang('Profile.timezone') ?>
                                            </label>
                                                    <select class="form-select" id="timezone" name="timezone">
                                                        <option value="UTC" <?= old('timezone', $user->timezone ?? 'UTC') == 'UTC' ? 'selected' : '' ?>>UTC (Coordinated Universal Time)</option>
                                                        <option value="America/New_York" <?= old('timezone', $user->timezone ?? 'UTC') == 'America/New_York' ? 'selected' : '' ?>>America/New York (EST/EDT)</option>
                                                        <option value="America/Chicago" <?= old('timezone', $user->timezone ?? 'UTC') == 'America/Chicago' ? 'selected' : '' ?>>America/Chicago (CST/CDT)</option>
                                                        <option value="America/Denver" <?= old('timezone', $user->timezone ?? 'UTC') == 'America/Denver' ? 'selected' : '' ?>>America/Denver (MST/MDT)</option>
                                                        <option value="America/Los_Angeles" <?= old('timezone', $user->timezone ?? 'UTC') == 'America/Los_Angeles' ? 'selected' : '' ?>>America/Los Angeles (PST/PDT)</option>
                                                        <option value="America/Sao_Paulo" <?= old('timezone', $user->timezone ?? 'UTC') == 'America/Sao_Paulo' ? 'selected' : '' ?>>America/Sao Paulo (BRT)</option>
                                                        <option value="Europe/London" <?= old('timezone', $user->timezone ?? 'UTC') == 'Europe/London' ? 'selected' : '' ?>>Europe/London (GMT/BST)</option>
                                                        <option value="Europe/Paris" <?= old('timezone', $user->timezone ?? 'UTC') == 'Europe/Paris' ? 'selected' : '' ?>>Europe/Paris (CET/CEST)</option>
                                                        <option value="Europe/Madrid" <?= old('timezone', $user->timezone ?? 'UTC') == 'Europe/Madrid' ? 'selected' : '' ?>>Europe/Madrid (CET/CEST)</option>
                                                        <option value="Europe/Berlin" <?= old('timezone', $user->timezone ?? 'UTC') == 'Europe/Berlin' ? 'selected' : '' ?>>Europe/Berlin (CET/CEST)</option>
                                                        <option value="Asia/Tokyo" <?= old('timezone', $user->timezone ?? 'UTC') == 'Asia/Tokyo' ? 'selected' : '' ?>>Asia/Tokyo (JST)</option>
                                                        <option value="Asia/Shanghai" <?= old('timezone', $user->timezone ?? 'UTC') == 'Asia/Shanghai' ? 'selected' : '' ?>>Asia/Shanghai (CST)</option>
                                                        <option value="Asia/Singapore" <?= old('timezone', $user->timezone ?? 'UTC') == 'Asia/Singapore' ? 'selected' : '' ?>>Asia/Singapore (SGT)</option>
                                                        <option value="Australia/Sydney" <?= old('timezone', $user->timezone ?? 'UTC') == 'Australia/Sydney' ? 'selected' : '' ?>>Australia/Sydney (AEST/AEDT)</option>
                                                    </select>
                                            <div class="form-text"><?= lang('Profile.timezoneHelp') ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="ri-notification-3-line me-2 text-primary"></i>
                                    <?= lang('Profile.notificationSettings') ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <!-- Web Notifications -->
                                    <div class="col-lg-4 col-md-6">
                                        <div class="notification-widget">
                                            <div class="notification-widget-header">
                                                <div class="notification-icon">
                                                    <i class="ri-computer-line"></i>
                                                </div>
                                                <div class="notification-toggle">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" 
                                                               type="checkbox" 
                                                               id="web_notifications" 
                                                               name="web_notifications" 
                                                               value="1" 
                                                               <?= old('web_notifications', $user->web_notifications) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="web_notifications"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="notification-widget-body">
                                                <h6 class="notification-title"><?= lang('Profile.webNotifications') ?></h6>
                                                <p class="notification-description">Receive browser notifications for important updates and messages</p>
                                            </div>
                                        </div>
                                        </div>

                                    <!-- Email Notifications -->
                                    <div class="col-lg-4 col-md-6">
                                        <div class="notification-widget">
                                            <div class="notification-widget-header">
                                                <div class="notification-icon">
                                                    <i class="ri-mail-line"></i>
                                                </div>
                                                <div class="notification-toggle">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" 
                                                               type="checkbox" 
                                                               id="email_notifications" 
                                                               name="email_notifications" 
                                                               value="1" 
                                                               <?= old('email_notifications', $user->email_notifications) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="email_notifications"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="notification-widget-body">
                                                <h6 class="notification-title"><?= lang('Profile.emailNotifications') ?></h6>
                                                <p class="notification-description">Get email alerts for account activities and system updates</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SMS Notifications -->
                                    <div class="col-lg-4 col-md-6">
                                        <div class="notification-widget">
                                            <div class="notification-widget-header">
                                                <div class="notification-icon">
                                                    <i class="ri-smartphone-line"></i>
                                                </div>
                                                <div class="notification-toggle">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" 
                                                               type="checkbox" 
                                                               id="sms_notifications" 
                                                               name="sms_notifications" 
                                                               value="1" 
                                                               <?= old('sms_notifications', $user->sms_notifications) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="sms_notifications"></label>
                                            </div>
                                                </div>
                                            </div>
                                            <div class="notification-widget-body">
                                                <h6 class="notification-title"><?= lang('Profile.smsNotifications') ?></h6>
                                                <p class="notification-description">Receive text messages for urgent notifications and alerts</p>
                                            </div>
                                                </div>
                                            </div>
                                        </div>

                                <!-- Notification Info Footer -->
                                <div class="notification-info mt-4">
                                    <div class="alert alert-info border-0" style="background: rgba(13, 110, 253, 0.1);">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-information-line text-info me-2 fs-18"></i>
                                            <div>
                                                <strong>Notification Preferences</strong>
                                                <p class="mb-0 mt-1">You can customize when and how you receive notifications. Changes are saved automatically when you update your profile.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1">Ready to save your changes?</h6>
                                        <small class="text-muted">Make sure all information is correct before saving.</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="<?= base_url('profile') ?>" class="btn btn-light">
                                            <i class="ri-close-line me-1"></i>
                                            <?= lang('Profile.cancel') ?>
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i>
                                            <?= lang('Profile.save') ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden field to handle avatar deletion -->
                <input type="hidden" id="delete-avatar" name="delete_avatar" value="0">
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.profile-avatar-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.profile-avatar-card .card-body {
    position: relative;
}

.profile-avatar-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.1);
    border-radius: 0.375rem;
}

.profile-avatar-card .card-body > * {
    position: relative;
    z-index: 1;
}

.avatar-section img {
    border: 4px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
}

.avatar-section img:hover {
    transform: scale(1.05);
    border-color: rgba(255,255,255,0.4);
}

.avatar-info {
    background: rgba(255,255,255,0.9) !important;
    color: #333 !important;
}

/* New Notification Widget Styles */
.notification-widget {
    background: #fff;
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.notification-widget::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.notification-widget:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: rgba(102, 126, 234, 0.2);
}

.notification-widget:hover::before {
    transform: scaleX(1);
}

.notification-widget-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #667eea;
    background: rgba(102, 126, 234, 0.1);
    transition: all 0.3s ease;
}

.notification-widget:hover .notification-icon {
    background: rgba(102, 126, 234, 0.2);
    transform: scale(1.05);
}

.notification-toggle {
    display: flex;
    align-items: center;
}

.notification-toggle .form-check {
    margin-bottom: 0;
}

.notification-toggle .form-check-input {
    width: 3rem;
    height: 1.5rem;
    border-radius: 1rem;
    background-color: #e9ecef;
    border: 2px solid transparent;
    background-image: none;
    transition: all 0.3s ease;
}

.notification-toggle .form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.notification-toggle .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    border-color: #667eea;
}

.notification-widget-body {
    padding-top: 0.5rem;
}

.notification-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2d3748;
}

.notification-description {
    font-size: 0.875rem;
    color: #718096;
    line-height: 1.5;
    margin-bottom: 0;
}

/* Enhanced info section */
.notification-info .alert {
    border-radius: 12px;
    padding: 1.25rem;
}

.notification-info .alert i {
    flex-shrink: 0;
}

.page-header {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.15s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-header {
    background: rgba(0,0,0,0.02);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.5rem rgba(102, 126, 234, 0.4);
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.text-primary {
    color: #667eea !important;
}

.avatar-upload-btn label:hover {
    transform: scale(1.1);
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-text {
    font-size: 0.75rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .notification-widget {
        margin-bottom: 1rem;
    }
    
    .notification-widget-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .notification-toggle {
        align-self: flex-end;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle avatar preview when file is selected
    const profileImgInput = document.getElementById('profile-img-file-input');
    const profileImgPreview = document.getElementById('profile-img-preview');
    const deleteAvatarBtn = document.getElementById('delete-avatar-btn');
    const deleteAvatarField = document.getElementById('delete-avatar');
    const avatarStyleSelect = document.getElementById('avatar_style');
    
    // Store original avatar URL
    const originalAvatarUrl = profileImgPreview.src;
    
    // Preview uploaded image
    if (profileImgInput) {
        profileImgInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImgPreview.src = e.target.result;
                    
                    // Update topbar avatar with uploaded image preview
                    updateTopbarAvatar(e.target.result);
                    
                    // Hide delete button when new image is selected
                    if (deleteAvatarBtn) {
                        deleteAvatarBtn.style.display = 'none';
                    }
                    // Reset delete flag
                    deleteAvatarField.value = '0';
                    
                    // Show success notification
                    showNotification('Image selected successfully!', 'success');
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Handle avatar style change - show preview
    if (avatarStyleSelect) {
        avatarStyleSelect.addEventListener('change', function() {
            // Only show preview if no file is selected and not deleting
            if ((!profileImgInput.files.length || profileImgInput.files.length === 0) && deleteAvatarField.value !== '1') {
                const selectedStyle = this.value;
                const userName = '<?= esc($user->first_name . ' ' . $user->last_name) ?>'.trim() || '<?= esc($user->username) ?>';
                const userEmail = '<?= esc($user->email) ?>';
                
                // Generate preview URL based on selected style
                let previewUrl = '';
                let topbarUrl = ''; // For topbar (smaller size)
                switch(selectedStyle) {
                    case 'initials':
                        const initials = userName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                        previewUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(initials)}&size=120&background=405189&color=ffffff&bold=true`;
                        topbarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(initials)}&size=40&background=405189&color=ffffff&bold=true`;
                        break;
                    case 'gravatar':
                        const emailHash = btoa(userEmail.toLowerCase()).replace(/[^a-zA-Z0-9]/g, '');
                        previewUrl = `https://www.gravatar.com/avatar/${emailHash}?s=120&d=mp`;
                        topbarUrl = `https://www.gravatar.com/avatar/${emailHash}?s=40&d=mp`;
                        break;
                    case 'robohash':
                        previewUrl = `https://robohash.org/${encodeURIComponent(userEmail)}?size=120x120`;
                        topbarUrl = `https://robohash.org/${encodeURIComponent(userEmail)}?size=40x40`;
                        break;
                    case 'identicon':
                        previewUrl = `https://www.gravatar.com/avatar/${btoa(userEmail.toLowerCase()).replace(/[^a-zA-Z0-9]/g, '')}?s=120&d=identicon`;
                        topbarUrl = `https://www.gravatar.com/avatar/${btoa(userEmail.toLowerCase()).replace(/[^a-zA-Z0-9]/g, '')}?s=40&d=identicon`;
                        break;
                    default:
                        previewUrl = originalAvatarUrl;
                        topbarUrl = originalAvatarUrl;
                }
                
                // Update main preview
                profileImgPreview.src = previewUrl;
                
                // Update topbar avatar immediately
                updateTopbarAvatar(topbarUrl);
                
                // Show temporary notification
                showStyleChangeNotification(selectedStyle);
            }
        });
    }
    
    // Handle avatar deletion
    if (deleteAvatarBtn) {
        deleteAvatarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show confirmation dialog
            if (confirm('Are you sure you want to delete your current avatar? This will revert to the default avatar style.')) {
                // Set delete flag
                deleteAvatarField.value = '1';
                
                // Clear file input
                profileImgInput.value = '';
                
                // Update preview based on current style selection
                const selectedStyle = avatarStyleSelect.value || 'initials';
                const userName = '<?= esc($user->first_name . ' ' . $user->last_name) ?>'.trim() || '<?= esc($user->username) ?>';
                const userEmail = '<?= esc($user->email) ?>';
                
                let fallbackUrl = '';
                let topbarFallbackUrl = '';
                switch(selectedStyle) {
                    case 'initials':
                        const initials = userName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                        fallbackUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(initials)}&size=120&background=405189&color=ffffff&bold=true`;
                        topbarFallbackUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(initials)}&size=40&background=405189&color=ffffff&bold=true`;
                        break;
                    case 'gravatar':
                        const emailHash = btoa(userEmail.toLowerCase()).replace(/[^a-zA-Z0-9]/g, '');
                        fallbackUrl = `https://www.gravatar.com/avatar/${emailHash}?s=120&d=mp`;
                        topbarFallbackUrl = `https://www.gravatar.com/avatar/${emailHash}?s=40&d=mp`;
                        break;
                    case 'robohash':
                        fallbackUrl = `https://robohash.org/${encodeURIComponent(userEmail)}?size=120x120`;
                        topbarFallbackUrl = `https://robohash.org/${encodeURIComponent(userEmail)}?size=40x40`;
                        break;
                    case 'identicon':
                        fallbackUrl = `https://www.gravatar.com/avatar/${btoa(userEmail.toLowerCase()).replace(/[^a-zA-Z0-9]/g, '')}?s=120&d=identicon`;
                        topbarFallbackUrl = `https://www.gravatar.com/avatar/${btoa(userEmail.toLowerCase()).replace(/[^a-zA-Z0-9]/g, '')}?s=40&d=identicon`;
                        break;
                }
                
                profileImgPreview.src = fallbackUrl;
                
                // Update topbar avatar
                updateTopbarAvatar(topbarFallbackUrl);
                
                // Hide delete button
                deleteAvatarBtn.style.display = 'none';
                
                // Show success message
                showNotification('Avatar will be deleted when you save the profile. Preview shows your selected fallback style.', 'info');
            }
        });
    }
    
    // Function to show style change notification
    function showStyleChangeNotification(style) {
        const styleNames = {
            'initials': 'Initials',
            'gravatar': 'Gravatar',
            'robohash': 'Robohash',
            'identicon': 'Identicon'
        };
        
        showNotification(`Preview: ${styleNames[style]} style selected. Save to apply changes.`, 'success');
    }
    
    // Function to update topbar avatar in real-time
    function updateTopbarAvatar(newAvatarUrl) {
        const topbarAvatar = document.querySelector('.header-profile-user');
        if (topbarAvatar) {
            topbarAvatar.src = newAvatarUrl;
            
            // Add a subtle animation to indicate change
            topbarAvatar.style.transition = 'all 0.3s ease';
            topbarAvatar.style.transform = 'scale(1.1)';
            
            setTimeout(() => {
                topbarAvatar.style.transform = 'scale(1)';
            }, 300);
        }
    }
    
    // Enhanced notification system
    function showNotification(message, type = 'info') {
        // Remove any existing notifications
        const existingNotification = document.querySelector('.dynamic-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const iconMap = {
            'success': 'ri-check-circle-line',
            'info': 'ri-information-line',
            'warning': 'ri-alert-line',
            'danger': 'ri-error-warning-line'
        };
        
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show dynamic-notification`;
        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="${iconMap[type]} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 4 seconds
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.remove();
            }
        }, 4000);
    }
    
    // Form validation enhancement
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            
            if (!firstName && !lastName) {
                e.preventDefault();
                showNotification('Please enter at least your first or last name.', 'warning');
                return false;
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
