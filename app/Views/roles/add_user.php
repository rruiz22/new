<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Portal - My Detail Area<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>pagetitle<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>Roles Management<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (session()->getFlashdata('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('message') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h4 class="card-title mb-0 flex-grow-1">Add User to Role: <?= esc($groupTitle) ?></h4>
                                    <div class="flex-shrink-0">
                                        <a href="<?= base_url('roles/users/' . $group) ?>" class="btn btn-light btn-sm">
                                            <i class="ri-arrow-left-line align-bottom"></i> Back to Users
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($users)): ?>
                                        <div class="text-center">
                                            <p class="text-muted">No users available to add to this role</p>
                                        </div>
                                    <?php else: ?>
                                        <form action="<?= base_url('roles/assign') ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="group" value="<?= esc($group) ?>">
                                            
                                            <div class="mb-3">
                                                <label for="user_id" class="form-label">Select User</label>
                                                <select class="form-select" id="user_id" name="user_id" required>
                                                    <option value="">-- Select User --</option>
                                                    <?php foreach ($users as $user): ?>
                                                        <option value="<?= $user->id ?>">
                                                            <?= esc($user->username) ?> (<?= esc($user->email) ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary">Add User to Role</button>
                                                <a href="<?= base_url('roles/users/' . $group) ?>" class="btn btn-light">Cancel</a>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
">
<?= $this->endSection() ?>
