<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>App.edit_client<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>App.edit_client<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>App.clients<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
                        <div class="col-xxl-9">
                            <div class="card">
                                <div class="card-header bg-light border-bottom d-flex align-items-center">
                                    <h4 class="card-title mb-0 flex-grow-1"><?= lang('App.client_information') ?></h4>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <?php if (session()->getFlashdata('error')) : ?>
                                        <div class="alert alert-danger">
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($validation)) : ?>
                                        <div class="alert alert-danger">
                                            <?= $validation->listErrors() ?>
                                        </div>
                                    <?php endif; ?>

                                    <form action="<?= base_url("clients/update/{$client['id']}") ?>" method="post" class="needs-validation" novalidate>
                                        <?= csrf_field() ?>
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="name" class="form-label"><?= lang('App.client_name') ?> <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control rounded-0" id="name" name="name" value="<?= old('name', $client['name']) ?>" required>
                                                <div class="invalid-feedback">
                                                    <?= lang('App.please_enter') ?> <?= lang('App.client_name') ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="email" class="form-label"><?= lang('App.client_email') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="email" class="form-control rounded-0" id="email" name="email" value="<?= old('email', $client['email'] ?? '') ?>">
                                                <div class="invalid-feedback">
                                                    <?= lang('App.please_enter_valid') ?> <?= lang('App.email') ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="phone" class="form-label"><?= lang('App.client_phone') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control rounded-0" id="phone" name="phone" value="<?= old('phone', $client['phone'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="address" class="form-label"><?= lang('App.client_address') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <textarea class="form-control rounded-0" id="address" name="address" rows="3"><?= old('address', $client['address'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="website" class="form-label"><?= lang('App.client_website') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="url" class="form-control rounded-0" id="website" name="website" value="<?= old('website', $client['website'] ?? '') ?>">
                                                <div class="invalid-feedback">
                                                    <?= lang('App.please_enter_valid') ?> URL
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="tax_number" class="form-label"><?= lang('App.client_tax_number') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control rounded-0" id="tax_number" name="tax_number" value="<?= old('tax_number', $client['tax_number'] ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-lg-3">
                                                <label for="client_status_edit" class="form-label"><?= lang('App.active_status') ?></label>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="position-relative">
                                                    <select class="form-select rounded-0 bg-light border-0" id="client_status_edit" name="status" style="height: 40px; width: 100%; appearance: none; padding-right: 30px;">
                                                        <option value="active" <?= old('status', $client['status']) === 'active' ? 'selected' : '' ?>><?= lang('App.active') ?></option>
                                                        <option value="inactive" <?= old('status', $client['status']) === 'inactive' ? 'selected' : '' ?>><?= lang('App.inactive') ?></option>
                                                    </select>
                                                    <div class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%);">
                                                        <i data-feather="chevron-down" style="width: 16px; height: 16px;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <a href="<?= base_url("clients/{$client['id']}") ?>" class="btn btn-light"><?= lang('App.cancel') ?></a>
                                                    <button type="submit" class="btn btn-primary"><?= lang('App.update') ?></button>
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
