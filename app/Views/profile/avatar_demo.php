<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Avatar Demo - Avatar Styles<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Avatar Demo<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
    <li class="breadcrumb-item"><a href="<?= base_url('profile') ?>">Profile</a></li>
    <li class="breadcrumb-item active">Avatar Demo</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 
helper('avatar');
$user = auth()->user();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">🎨 Avatar System Demo</h4>
                <p class="text-muted mb-0">Choose your preferred avatar style. Your avatar will be generated automatically based on your profile information.</p>
            </div>
            <div class="card-body">
                
                <!-- Current User Avatar -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h5 class="text-primary">🔹 Your Current Avatar</h5>
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                            <img src="<?= getAvatarUrl($user, 80) ?>" alt="Current Avatar" class="rounded-circle" width="80" height="80">
                            <div>
                                <h6 class="mb-1"><?= esc($user->username ?? 'Username') ?></h6>
                                <p class="text-muted mb-1"><?= esc($user->email) ?></p>
                                <small class="text-success">Current Style: <?= ucfirst($user->avatar_style ?? 'initials') ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avatar Style Examples -->
                <div class="row">
                    
                    <!-- Initials Avatar -->
                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary">✨ Initials</h5>
                                <div class="mb-3">
                                    <img src="<?= getInitialsAvatar($user, 100) ?>" alt="Initials Avatar" class="rounded-circle" width="100" height="100">
                                </div>
                                <h6>Features:</h6>
                                <ul class="text-start small">
                                    <li>✅ Uses your name initials</li>
                                    <li>✅ Consistent colors</li>
                                    <li>✅ Fast loading</li>
                                    <li>✅ Clean design</li>
                                </ul>
                                <div class="mt-3">
                                    <span class="badge bg-success">Recommended</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gravatar -->
                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <h5 class="card-title text-info">🌐 Gravatar</h5>
                                <div class="mb-3">
                                    <img src="<?= getGravatarUrl($user->email, 100, 'mp') ?>" alt="Gravatar Avatar" class="rounded-circle" width="100" height="100">
                                </div>
                                <h6>Features:</h6>
                                <ul class="text-start small">
                                    <li>✅ Universal avatar service</li>
                                    <li>✅ Same across all sites</li>
                                    <li>✅ Professional look</li>
                                    <li>⚠️ Requires Gravatar account</li>
                                </ul>
                                <div class="mt-3">
                                    <?php if (isGravatarAvailable($user->email)): ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Not Available</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Robohash -->
                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <h5 class="card-title text-warning">🤖 Robohash</h5>
                                <div class="mb-3">
                                    <img src="<?= getRobohashAvatar($user, 100) ?>" alt="Robohash Avatar" class="rounded-circle" width="100" height="100">
                                </div>
                                <h6>Features:</h6>
                                <ul class="text-start small">
                                    <li>✅ Unique robot designs</li>
                                    <li>✅ Fun and creative</li>
                                    <li>✅ Consistent per user</li>
                                    <li>🎨 Great for gaming apps</li>
                                </ul>
                                <div class="mt-3">
                                    <span class="badge bg-warning">Fun</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Identicon -->
                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <h5 class="card-title text-secondary">🔶 Identicon</h5>
                                <div class="mb-3">
                                    <img src="<?= getIdenticonAvatar($user, 100) ?>" alt="Identicon Avatar" class="rounded-circle" width="100" height="100">
                                </div>
                                <h6>Features:</h6>
                                <ul class="text-start small">
                                    <li>✅ Geometric patterns</li>
                                    <li>✅ Unique per user</li>
                                    <li>✅ GitHub-style</li>
                                    <li>✅ Professional look</li>
                                </ul>
                                <div class="mt-3">
                                    <span class="badge bg-secondary">Geometric</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avatar Size Examples -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="text-primary">🔹 Different Sizes</h5>
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                            <img src="<?= getAvatarUrl($user, 32) ?>" alt="Small" class="rounded-circle" title="32px">
                            <img src="<?= getAvatarUrl($user, 48) ?>" alt="Medium" class="rounded-circle" title="48px">
                            <img src="<?= getAvatarUrl($user, 64) ?>" alt="Large" class="rounded-circle" title="64px">
                            <img src="<?= getAvatarUrl($user, 96) ?>" alt="XLarge" class="rounded-circle" title="96px">
                            <img src="<?= getAvatarUrl($user, 128) ?>" alt="XXLarge" class="rounded-circle" title="128px">
                            <div class="ms-3">
                                <small class="text-muted">Sizes: 32px, 48px, 64px, 96px, 128px</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Custom Avatar -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="text-primary">🔹 Upload Custom Avatar</h5>
                        <div class="card">
                            <div class="card-body">
                                <form action="<?= base_url('profile/update') ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="avatar" class="form-label">Choose Avatar Image</label>
                                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                            <div class="form-text">
                                                Supported formats: JPG, PNG, GIF, WebP (Max: 2MB)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="avatar_style" class="form-label">Fallback Style</label>
                                            <select class="form-select" id="avatar_style" name="avatar_style">
                                                <option value="initials" <?= ($user->avatar_style ?? 'initials') === 'initials' ? 'selected' : '' ?>>✨ Initials</option>
                                                <option value="gravatar" <?= ($user->avatar_style ?? '') === 'gravatar' ? 'selected' : '' ?>>🌐 Gravatar</option>
                                                <option value="robohash" <?= ($user->avatar_style ?? '') === 'robohash' ? 'selected' : '' ?>>🤖 Robohash</option>
                                                <option value="identicon" <?= ($user->avatar_style ?? '') === 'identicon' ? 'selected' : '' ?>>🔶 Identicon</option>
                                            </select>
                                            <div class="form-text">
                                                Used when no custom image is uploaded
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden fields to maintain other profile data -->
                                <input type="hidden" name="first_name" value="<?= esc($user->first_name ?? '') ?>">
                                <input type="hidden" name="last_name" value="<?= esc($user->last_name ?? '') ?>">
                                <input type="hidden" name="phone" value="<?= esc($user->phone ?? '') ?>">
                                <input type="hidden" name="status_message" value="<?= esc($user->status_message ?? '') ?>">
                                <input type="hidden" name="date_format" value="<?= esc($user->date_format ?? 'M-d-Y') ?>">
                                <input type="hidden" name="timezone" value="<?= esc($user->timezone ?? 'UTC') ?>">

                                <div class="text-end">
                                    <a href="<?= base_url('profile') ?>" class="btn btn-light">← Back to Profile</a>
                                    <button type="submit" class="btn btn-primary">💾 Update Avatar</button>
                                </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avatar System Info -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">ℹ️ How It Works</h6>
                            <p class="mb-0">
                                Our avatar system uses a <strong>smart fallback mechanism</strong>:
                            </p>
                            <ol class="mb-0 mt-2">
                                <li><strong>Custom Upload</strong> - If you upload an image, it's used first</li>
                                <li><strong>Gravatar</strong> - If available for your email, it's used as backup</li>
                                <li><strong>Generated Avatar</strong> - Based on your selected style (initials, robohash, etc.)</li>
                                <li><strong>Default</strong> - System default if all else fails</li>
                            </ol>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.card img {
    object-fit: cover;
}
</style>

<?= $this->endSection() ?> 