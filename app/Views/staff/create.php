<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>App.create_staff<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>App.create_staff<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>App.staff<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
                        <div class="col-xxl-9">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><?= lang('App.create_staff') ?></h4>
                                </div>
                                <div class="card-body">
                                    <?php if (session()->getFlashdata('errors')): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <ul class="mb-0">
                                                <?php foreach (session('errors') as $error): ?>
                                                    <li><?= esc($error) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <form action="<?= base_url('staff/store') ?>" method="post" class="needs-validation" novalidate>
                                        <?= csrf_field() ?>
                                        
                                        <!-- Campo oculto para user_type -->
                                        <input type="hidden" name="user_type" value="staff">
                                        
                                        <!-- Campo de Cliente -->
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="client_id" class="form-label"><?= lang('App.client') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <select class="form-select" id="client_id" name="client_id">
                                                    <option value=""><?= lang('App.select_client') ?> (<?= lang('App.optional') ?>)</option>
                                                    <?php if (!empty($availableClients)): ?>
                                                        <?php foreach ($availableClients as $client): ?>
                                                            <?php 
                                                            $isSelected = false;
                                                            if (old('client_id')) {
                                                                $isSelected = old('client_id') == $client['id'];
                                                            } elseif (isset($preSelectedClientId)) {
                                                                $isSelected = $preSelectedClientId == $client['id'];
                                                            }
                                                            ?>
                                                            <option value="<?= $client['id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                                                                <?= esc($client['name']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="" disabled><?= lang('App.no_active_clients_available') ?></option>
                                                    <?php endif; ?>
                                                </select>
                                                <div class="form-text">
                                                    <i class="ri-information-line me-1"></i>
                                                    <?= lang('App.assign_staff_to_client_help') ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="username" class="form-label"><?= lang('App.username') ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
                                                <div class="invalid-feedback">
                                                    <?= lang('App.please_enter') ?> <?= strtolower(lang('App.username')) ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="email" class="form-label"><?= lang('App.email') ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                                                <div class="invalid-feedback">
                                                    <?= lang('App.please_enter_valid') ?> <?= strtolower(lang('App.email')) ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="password" class="form-label"><?= lang('App.password') ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                                <div class="invalid-feedback">
                                                    <?= lang('App.please_enter') ?> <?= strtolower(lang('App.password')) ?> (minimum 6 characters)
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="password_confirm" class="form-label"><?= lang('App.password_confirm') ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                                <div class="invalid-feedback">
                                                    <?= lang('App.passwords_dont_match') ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="first_name" class="form-label"><?= lang('App.first_name') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= old('first_name') ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="last_name" class="form-label"><?= lang('App.last_name') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= old('last_name') ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="phone" class="form-label"><?= lang('App.phone') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="role_id" class="form-label"><?= lang('App.role') ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <select class="form-select" id="role_id" name="role_id" required>
                                                    <option value=""><?= lang('App.select_role') ?></option>
                                                    <?php if (!empty($availableRoles)): ?>
                                                        <?php foreach ($availableRoles as $role): ?>
                                                            <option value="<?= $role['id'] ?>"
                                                                    <?= old('role_id') == $role['id'] ? 'selected' : '' ?>
                                                                   data-color="<?= esc($role['color']) ?>">
                                                                <?= esc($role['title']) ?>
                                                                <?php if (!empty($role['description'])): ?>
                                                                    - <?= esc($role['description']) ?>
                                                                <?php endif; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="" disabled><?= lang('App.no_roles_available') ?></option>
                                                    <?php endif; ?>
                                                </select>
                                                <div class="form-text">
                                                    <i class="ri-information-line me-1"></i>
                                                    <?= lang('App.select_role_help') ?>
                                                </div>
                                                <div class="invalid-feedback">
                                                    <?= lang('App.please_select') ?> <?= strtolower(lang('App.role')) ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="staff_active" class="form-label"><?= lang('App.status') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="position-relative">
                                                    <div class="form-check form-switch form-switch-md">
                                                        <input class="form-check-input" type="checkbox" id="staff_active" name="active" value="1" <?= old('active') ? 'checked' : 'checked' ?>>
                                                        <label class="form-check-label" for="staff_active"><?= lang('App.active') ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <a href="<?= base_url('staff') ?>" class="btn btn-light"><?= lang('App.cancel') ?></a>
                                                    <button type="submit" class="btn btn-primary"><?= lang('App.save') ?></button>
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
">
<?= $this->endSection() ?>
