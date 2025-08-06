<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Profile.userProfile<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Profile.userProfile<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>Profile.userProfile<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xxl-11">
            <!-- Page Header -->
            <div class="page-header d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="page-title mb-1"><?= lang('Profile.userProfile') ?></h4>
                    <p class="text-muted mb-0">Manage your account settings and preferences</p>
                </div>
                <div>
                    <a href="<?= base_url('profile/edit') ?>" class="btn btn-primary">
                        <i class="ri-edit-box-line me-1"></i> Edit Profile
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Avatar and Basic Info -->
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
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                    
                                    <!-- Edit button -->
                                    <a href="<?= base_url('profile/edit') ?>" 
                                       class="btn btn-light btn-sm rounded-circle position-absolute" 
                                       style="bottom: 5px; right: 5px; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                       title="Edit profile">
                                        <i class="ri-pencil-line fs-14"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <h4 class="profile-name mb-1">
                                <?php if (!empty($user->first_name) || !empty($user->last_name)): ?>
                                    <?= esc($user->first_name) ?> <?= esc($user->last_name) ?>
                                <?php else: ?>
                                    <?= esc($user->username) ?>
                                <?php endif; ?>
                            </h4>
                            
                            <?php if (!empty($user->status_message)): ?>
                            <p class="profile-status mb-3">"<?= esc($user->status_message) ?>"</p>
                            <?php endif; ?>
                            
                            <div class="profile-role mb-3">
                                <?php if (in_array('admin', $user->getGroups())): ?>
                                    <span class="badge bg-gradient-success fs-12 px-3 py-2">
                                        <i class="ri-shield-star-line me-1"></i>
                                        <?= lang('Profile.administrator') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-gradient-info fs-12 px-3 py-2">
                                        <i class="ri-user-line me-1"></i>
                                        <?= lang('Profile.user') ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="profile-stats">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <h6 class="stat-number">
                                                <?php if ($user->created_at): ?>
                                                    <?php
                                                        $createdDate = new DateTime($user->created_at->format('Y-m-d'));
                                                        $currentDate = new DateTime();
                                                        $diff = $currentDate->diff($createdDate);
                                                        echo $diff->days;
                                                    ?>
                                                <?php else: ?>
                                                    --
                                                <?php endif; ?>
                                            </h6>
                                            <p class="stat-label">Days Active</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <div class="stat-number">
                                                <?php if ($user->active): ?>
                                                    <span class="text-success">
                                                        <i class="ri-checkbox-circle-fill"></i>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-danger">
                                                        <i class="ri-close-circle-fill"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="stat-label">Status</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="ri-lightning-line me-2 text-primary"></i>
                                Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?= base_url('profile/edit') ?>" class="btn btn-outline-primary">
                                    <i class="ri-edit-box-line me-2"></i>
                                    Edit Profile
                                </a>
                                <a href="<?= base_url('profile/avatar-demo') ?>" class="btn btn-outline-info">
                                    <i class="ri-palette-line me-2"></i>
                                    Avatar Styles
                                </a>
                                <a href="<?= base_url('settings') ?>" class="btn btn-outline-secondary">
                                    <i class="ri-settings-3-line me-2"></i>
                                    Account Settings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-xl-8 col-lg-7">
                    <!-- Personal Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="card-title mb-0">
                                    <i class="ri-user-line me-2 text-primary"></i>
                                    <?= lang('Profile.personalInformation') ?>
                                </h6>
                                <a href="<?= base_url('profile/edit') ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-edit-box-line me-1"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="ri-at-line"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label"><?= lang('Profile.username') ?></label>
                                            <p class="info-value"><?= esc($user->username) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="ri-mail-line"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label"><?= lang('Profile.email') ?></label>
                                            <p class="info-value"><?= esc($user->email) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($user->phone)): ?>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="ri-phone-line"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label"><?= lang('Profile.phone') ?></label>
                                            <p class="info-value"><?= esc($user->phone) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="ri-calendar-2-line"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label"><?= lang('Profile.accountCreated') ?></label>
                                            <p class="info-value"><?= $user->created_at ? $user->created_at->format('d M, Y') : '-' ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="ri-time-line"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label"><?= lang('Profile.lastActive') ?></label>
                                            <p class="info-value"><?= $user->last_active ? $user->last_active->format('d M, Y H:i') : '-' ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="ri-shield-user-line"></i>
                                        </div>
                                        <div class="info-content">
                                            <label class="info-label"><?= lang('Profile.status') ?></label>
                                            <p class="info-value">
                                                <?php if ($user->active): ?>
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="ri-check-line me-1"></i>
                                                        <?= lang('Profile.active') ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        <i class="ri-close-line me-1"></i>
                                                        <?= lang('Profile.inactive') ?>
                                                    </span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences Settings -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="card-title mb-0">
                                    <i class="ri-settings-3-line me-2 text-primary"></i>
                                    <?= lang('Profile.preferencesSettings') ?>
                                </h6>
                                <a href="<?= base_url('profile/edit') ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-edit-box-line me-1"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="preference-item">
                                        <div class="preference-icon">
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                        <div class="preference-content">
                                            <label class="preference-label"><?= lang('Profile.dateFormat') ?></label>
                                            <p class="preference-value">
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <?php
                                                    $dateFormats = [
                                                        'M-d-Y' => 'MM-DD-YYYY',
                                                        'd-m-Y' => 'DD-MM-YYYY',
                                                        'Y-m-d' => 'YYYY-MM-DD',
                                                        'm/d/Y' => 'MM/DD/YYYY',
                                                        'd/m/Y' => 'DD/MM/YYYY',
                                                        'Y/m/d' => 'YYYY/MM/DD'
                                                    ];
                                                    echo $dateFormats[$user->date_format ?? 'M-d-Y'];
                                                    ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="preference-item">
                                        <div class="preference-icon">
                                            <i class="ri-time-zone-line"></i>
                                        </div>
                                        <div class="preference-content">
                                            <label class="preference-label"><?= lang('Profile.timezone') ?></label>
                                            <p class="preference-value">
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <?= $user->timezone ?? 'UTC' ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="preference-item">
                                        <div class="preference-icon">
                                            <i class="ri-user-smile-line"></i>
                                        </div>
                                        <div class="preference-content">
                                            <label class="preference-label">Avatar Configuration</label>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <?php if (!empty($user->avatar) && file_exists(FCPATH . 'assets/images/users/' . $user->avatar)): ?>
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="ri-image-line me-1"></i>
                                                        Custom Image
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-info-subtle text-info">
                                                        <i class="ri-magic-line me-1"></i>
                                                        Auto-Generated
                                                    </span>
                                                <?php endif; ?>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <?php
                                                    $avatarStyles = [
                                                        'initials' => '✨ Initials',
                                                        'gravatar' => '🌐 Gravatar',
                                                        'robohash' => '🤖 Robohash',
                                                        'identicon' => '🔶 Identicon'
                                                    ];
                                                    echo $avatarStyles[$user->avatar_style ?? 'initials'];
                                                    ?>
                                                </span>
                                                <a href="<?= base_url('profile/avatar-demo') ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="ri-eye-line me-1"></i>
                                                    Preview Styles
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="card-title mb-0">
                                    <i class="ri-notification-3-line me-2 text-primary"></i>
                                    <?= lang('Profile.notificationSettings') ?>
                                </h6>
                                <a href="<?= base_url('profile/edit') ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-edit-box-line me-1"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="notification-status-item">
                                        <div class="notification-status-icon">
                                            <i class="ri-computer-line"></i>
                                        </div>
                                        <div class="notification-status-content">
                                            <h6 class="notification-status-title"><?= lang('Profile.webNotifications') ?></h6>
                                            <p class="notification-status-subtitle">Browser alerts</p>
                                            <div class="notification-status-toggle">
                                                <?php if ($user->web_notifications): ?>
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="ri-check-line me-1"></i>
                                                        Enabled
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        <i class="ri-close-line me-1"></i>
                                                        Disabled
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="notification-status-item">
                                        <div class="notification-status-icon">
                                            <i class="ri-mail-line"></i>
                                        </div>
                                        <div class="notification-status-content">
                                            <h6 class="notification-status-title"><?= lang('Profile.emailNotifications') ?></h6>
                                            <p class="notification-status-subtitle">Email alerts</p>
                                            <div class="notification-status-toggle">
                                                <?php if ($user->email_notifications): ?>
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="ri-check-line me-1"></i>
                                                        Enabled
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        <i class="ri-close-line me-1"></i>
                                                        Disabled
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="notification-status-item">
                                        <div class="notification-status-icon">
                                            <i class="ri-smartphone-line"></i>
                                        </div>
                                        <div class="notification-status-content">
                                            <h6 class="notification-status-title"><?= lang('Profile.smsNotifications') ?></h6>
                                            <p class="notification-status-subtitle">Text messages</p>
                                            <div class="notification-status-toggle">
                                                <?php if ($user->sms_notifications): ?>
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="ri-check-line me-1"></i>
                                                        Enabled
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary">
                                                        <i class="ri-close-line me-1"></i>
                                                        Disabled
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Profile Avatar Card */
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

.profile-name {
    color: white;
    font-weight: 600;
}

.profile-status {
    color: rgba(255,255,255,0.8);
    font-style: italic;
}

.profile-role .badge {
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8, #6f42c1) !important;
}

.profile-stats {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255,255,255,0.2);
}

.stat-item {
    padding: 0.5rem;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.7);
    margin-bottom: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Info Items */
.info-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    background: rgba(102, 126, 234, 0.03);
    border-radius: 8px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    transition: all 0.3s ease;
}

.info-item:hover {
    background: rgba(102, 126, 234, 0.08);
    border-color: rgba(102, 126, 234, 0.2);
    transform: translateY(-1px);
}

.info-icon {
    width: 40px;
    height: 40px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.info-icon i {
    font-size: 1.2rem;
    color: #667eea;
}

.info-content {
    flex: 1;
}

.info-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.25rem;
    display: block;
}

.info-value {
    font-size: 0.95rem;
    color: #2d3748;
    margin-bottom: 0;
    font-weight: 500;
}

/* Preference Items */
.preference-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    background: rgba(13, 110, 253, 0.03);
    border-radius: 8px;
    border: 1px solid rgba(13, 110, 253, 0.1);
    transition: all 0.3s ease;
}

.preference-item:hover {
    background: rgba(13, 110, 253, 0.08);
    border-color: rgba(13, 110, 253, 0.2);
    transform: translateY(-1px);
}

.preference-icon {
    width: 40px;
    height: 40px;
    background: rgba(13, 110, 253, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.preference-icon i {
    font-size: 1.2rem;
    color: #0d6efd;
}

.preference-content {
    flex: 1;
}

.preference-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.5rem;
    display: block;
}

.preference-value {
    margin-bottom: 0;
}

/* Notification Status Items */
.notification-status-item {
    background: #fff;
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
}

.notification-status-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: rgba(102, 126, 234, 0.2);
}

.notification-status-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #667eea;
    background: rgba(102, 126, 234, 0.1);
    margin: 0 auto 1rem;
    transition: all 0.3s ease;
}

.notification-status-item:hover .notification-status-icon {
    background: rgba(102, 126, 234, 0.2);
    transform: scale(1.05);
}

.notification-status-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #2d3748;
}

.notification-status-subtitle {
    font-size: 0.875rem;
    color: #718096;
    margin-bottom: 1rem;
}

.notification-status-toggle {
    display: flex;
    justify-content: center;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

/* General Card Styles */
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

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.25rem 0.5rem rgba(102, 126, 234, 0.4);
}

.text-primary {
    color: #667eea !important;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .info-item,
    .preference-item {
        flex-direction: column;
        text-align: center;
    }
    
    .info-icon,
    .preference-icon {
        margin: 0 auto 0.75rem;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add subtle animations to cards on scroll
    const cards = document.querySelectorAll('.card');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Add click animation to action buttons
    const actionButtons = document.querySelectorAll('.btn');
    
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255,255,255,0.6)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.pointerEvents = 'none';
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Add CSS animation for ripple effect
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
<?= $this->endSection() ?>
