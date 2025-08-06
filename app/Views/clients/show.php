<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.client_details') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.client_details') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.clients') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1"><?= lang('App.client_details') ?></h4>
                <div class="flex-shrink-0">
                    <a href="<?= base_url('clients') ?>" class="btn btn-secondary btn-sm me-2">
                        <i class="ri-arrow-left-line me-1"></i> <?= lang('App.back_to_list') ?>
                    </a>
                    <a href="<?= base_url('clients/edit/' . $client['id']) ?>" class="btn btn-primary btn-sm">
                        <i class="ri-edit-2-line me-1"></i> <?= lang('App.edit_client') ?>
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <!-- Client Information -->
                    <div class="col-lg-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="ri-user-line me-2"></i><?= lang('App.client_information') ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="fw-medium text-muted" style="width: 40%;">
                                                    <i class="ri-user-line me-2"></i><?= lang('App.name') ?>:
                                                </td>
                                                <td><?= esc($client['name']) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium text-muted">
                                                    <i class="ri-mail-line me-2"></i><?= lang('App.email') ?>:
                                                </td>
                                                <td>
                                                    <?php if (!empty($client['email'])): ?>
                                                        <a href="mailto:<?= esc($client['email']) ?>" class="text-primary">
                                                            <?= esc($client['email']) ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium text-muted">
                                                    <i class="ri-phone-line me-2"></i><?= lang('App.phone') ?>:
                                                </td>
                                                <td>
                                                    <?php if (!empty($client['phone'])): ?>
                                                        <a href="tel:<?= esc($client['phone']) ?>" class="text-primary">
                                                            <?= esc($client['phone']) ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium text-muted">
                                                    <i class="ri-home-line me-2"></i><?= lang('App.address') ?>:
                                                </td>
                                                <td>
                                                    <?php if (!empty($client['address'])): ?>
                                                        <?= nl2br(esc($client['address'])) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium text-muted">
                                                    <i class="ri-map-pin-line me-2"></i><?= lang('App.city') ?>:
                                                </td>
                                                <td>
                                                    <?php if (!empty($client['city'])): ?>
                                                        <?= esc($client['city']) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium text-muted">
                                                    <i class="ri-map-line me-2"></i><?= lang('App.state') ?>:
                                                </td>
                                                <td>
                                                    <?php if (!empty($client['state'])): ?>
                                                        <?= esc($client['state']) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium text-muted">
                                                    <i class="ri-mail-send-line me-2"></i><?= lang('App.postal_code') ?>:
                                                </td>
                                                <td>
                                                    <?php if (!empty($client['postal_code'])): ?>
                                                        <?= esc($client['postal_code']) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium text-muted">
                                                    <i class="ri-global-line me-2"></i><?= lang('App.country') ?>:
                                                </td>
                                                <td>
                                                    <?php if (!empty($client['country'])): ?>
                                                        <?= esc($client['country']) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium text-muted">
                                                    <i class="ri-checkbox-circle-line me-2"></i><?= lang('App.status') ?>:
                                                </td>
                                                <td>
                                                    <?php if ($client['status'] === 'active'): ?>
                                                        <span class="badge bg-success"><?= lang('App.active') ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger"><?= lang('App.inactive') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="col-lg-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="ri-information-line me-2"></i><?= lang('App.additional_information') ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="fw-medium text-muted" style="width: 40%;">
                                                    <i class="ri-calendar-line me-2"></i><?= lang('App.created_at') ?>:
                                                </td>
                                                <td>
                                                    <?php if (!empty($client['created_at'])): ?>
                                                        <?= date('M d, Y H:i', strtotime($client['created_at'])) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium text-muted">
                                                    <i class="ri-calendar-event-line me-2"></i><?= lang('App.updated_at') ?>:
                                                </td>
                                                <td>
                                                    <?php if (!empty($client['updated_at'])): ?>
                                                        <?= date('M d, Y H:i', strtotime($client['updated_at'])) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium text-muted">
                                                    <i class="ri-file-text-line me-2"></i><?= lang('App.notes') ?>:
                                                </td>
                                                <td>
                                                    <?php if (!empty($client['notes'])): ?>
                                                        <?= nl2br(esc($client['notes'])) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="card border-0 shadow-sm mt-3" style="border-radius: 16px; overflow: hidden;">
                            <div class="card-header bg-white border-0 py-3">
                                <h6 class="card-title mb-0 text-muted fw-medium">
                                    <?= lang('App.quick_actions') ?>
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <!-- Edit Action -->
                                <a href="<?= base_url('clients/edit/' . $client['id']) ?>" class="d-flex align-items-center px-4 py-3 text-decoration-none border-bottom apple-action-item">
                                    <div class="me-3">
                                        <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: linear-gradient(135deg, #007AFF, #5856D6); border-radius: 8px;">
                                            <i class="ri-edit-2-line text-white" style="font-size: 16px;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium text-dark"><?= lang('App.edit_client') ?></div>
                                        <small class="text-muted"><?= lang('App.modify_client_info') ?></small>
                                    </div>
                                    <div class="text-muted">
                                        <i class="ri-arrow-right-s-line" style="font-size: 18px;"></i>
                                    </div>
                                </a>
                                
                                <!-- Email Action -->
                                <a href="mailto:<?= esc($client['email']) ?>" class="d-flex align-items-center px-4 py-3 text-decoration-none border-bottom apple-action-item <?= empty($client['email']) ? 'disabled-action' : '' ?>">
                                    <div class="me-3">
                                        <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: linear-gradient(135deg, #34C759, #30D158); border-radius: 8px;">
                                            <i class="ri-mail-line text-white" style="font-size: 16px;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium text-dark"><?= lang('App.send_email') ?></div>
                                        <small class="text-muted"><?= !empty($client['email']) ? esc($client['email']) : lang('App.no_email_available') ?></small>
                                    </div>
                                    <div class="text-muted">
                                        <i class="ri-arrow-right-s-line" style="font-size: 18px;"></i>
                                    </div>
                                </a>
                                
                                <!-- Call Action -->
                                <a href="tel:<?= esc($client['phone']) ?>" class="d-flex align-items-center px-4 py-3 text-decoration-none border-bottom apple-action-item <?= empty($client['phone']) ? 'disabled-action' : '' ?>">
                                    <div class="me-3">
                                        <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: linear-gradient(135deg, #FF9500, #FF9F0A); border-radius: 8px;">
                                            <i class="ri-phone-line text-white" style="font-size: 16px;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium text-dark"><?= lang('App.call_client') ?></div>
                                        <small class="text-muted"><?= !empty($client['phone']) ? esc($client['phone']) : lang('App.no_phone_available') ?></small>
                                    </div>
                                    <div class="text-muted">
                                        <i class="ri-arrow-right-s-line" style="font-size: 18px;"></i>
                                    </div>
                                </a>
                                
                                <!-- Delete Action -->
                                <button type="button" class="d-flex align-items-center px-4 py-3 w-100 border-0 bg-transparent apple-action-item text-start" onclick="deleteClient(<?= $client['id'] ?>)">
                                    <div class="me-3">
                                        <div class="d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: linear-gradient(135deg, #FF3B30, #FF453A); border-radius: 8px;">
                                            <i class="ri-delete-bin-line text-white" style="font-size: 16px;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium text-danger"><?= lang('App.delete_client') ?></div>
                                        <small class="text-muted"><?= lang('App.action_cannot_be_undone') ?></small>
                                    </div>
                                    <div class="text-muted">
                                        <i class="ri-arrow-right-s-line" style="font-size: 18px;"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                        
                        <style>
                        .apple-action-item {
                            transition: all 0.2s ease;
                            background-color: white;
                        }
                        
                        .apple-action-item:hover {
                            background-color: #f8f9fa;
                            transform: translateX(2px);
                        }
                        
                        .apple-action-item:active {
                            background-color: #e9ecef;
                            transform: translateX(0px);
                        }
                        
                        .disabled-action {
                            opacity: 0.5;
                            pointer-events: none;
                        }
                        
                        .apple-action-item .fw-medium {
                            font-size: 15px;
                            line-height: 1.3;
                        }
                        
                        .apple-action-item small {
                            font-size: 13px;
                            line-height: 1.2;
                        }
                        </style>
                    </div>
                </div>
                
                <!-- Assigned Staff Users Widget -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-header bg-light d-flex align-items-center">
                                <h5 class="card-title mb-0 flex-grow-1">
                                    <i class="ri-team-line me-2"></i><?= lang('App.assigned_staff_users') ?>
                                </h5>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-primary"><?= count($assignedStaffUsers) ?> <?= lang('App.staff') ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (empty($assignedStaffUsers)): ?>
                                    <div class="text-center py-4">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="ri-team-line fs-1"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted"><?= lang('App.no_staff_assigned') ?></h5>
                                        <p class="text-muted mb-0"><?= lang('App.no_staff_assigned_description') ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="row g-3">
                                        <?php foreach ($assignedStaffUsers as $user): ?>
                                            <div class="col-xl-4 col-lg-6 col-md-6">
                                                <div class="card border h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                                        <i class="ri-user-line"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h6 class="mb-1">
                                                                    <a href="<?= base_url('staff/edit/' . $user['id']) ?>" class="text-decoration-none">
                                                                        <?php 
                                                                        $fullName = trim($user['first_name'] . ' ' . $user['last_name']);
                                                                        echo esc($fullName ?: $user['username']);
                                                                        ?>
                                    </a>
                                                                </h6>
                                                                <p class="text-muted mb-2 fs-13">
                                                                    <i class="ri-mail-line me-1"></i><?= esc($user['email']) ?>
                                                                </p>
                                                                
                                                                <!-- Role Badge -->
                                                                <?php if (!empty($user['role_title'])): ?>
                                                                    <span class="badge fs-12" style="background-color: <?= $user['role_color'] ?: '#6c757d' ?>; color: white;">
                                                                        <?= esc($user['role_title']) ?>
                                                                    </span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary fs-12"><?= lang('App.no_role') ?></span>
                                                                <?php endif; ?>
                                                                
                                                                <!-- Status and Last Seen -->
                                                                <div class="mt-2">
                                                                    <?php if ($user['active']): ?>
                                                                        <span class="badge bg-success-subtle text-success fs-11">
                                                                            <i class="ri-checkbox-circle-line me-1"></i><?= lang('App.active') ?>
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-danger-subtle text-danger fs-11">
                                                                            <i class="ri-close-circle-line me-1"></i><?= lang('App.inactive') ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if (!empty($user['last_seen'])): ?>
                                                                        <small class="text-muted d-block mt-1">
                                                                            <i class="ri-time-line me-1"></i>
                                                                            <?= lang('App.last_seen') ?>: <?= date('M d, Y H:i', strtotime($user['last_seen'])) ?>
                                                                        </small>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Quick Actions -->
                                                        <div class="mt-3 pt-3 border-top">
                                                            <div class="d-flex gap-2">
                                                                <a href="<?= base_url('staff/edit/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary flex-fill">
                                                                    <i class="ri-edit-line me-1"></i><?= lang('App.edit') ?>
                                                                </a>
                                                                <a href="mailto:<?= esc($user['email']) ?>" class="btn btn-sm btn-outline-info flex-fill">
                                                                    <i class="ri-mail-line me-1"></i><?= lang('App.email') ?>
                                    </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Add Staff User Button -->
                                    <div class="text-center mt-4 pt-3 border-top">
                                        <a href="<?= base_url('staff/create?client_id=' . $client['id']) ?>" class="btn btn-primary">
                                            <i class="ri-user-add-line me-2"></i><?= lang('App.add_new_staff') ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Assigned Client Users Widget -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-header bg-light d-flex align-items-center">
                                <h5 class="card-title mb-0 flex-grow-1">
                                    <i class="ri-user-line me-2"></i><?= lang('App.assigned_client_users') ?>
                                </h5>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-info"><?= count($assignedClientUsers) ?> <?= lang('App.contacts') ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (empty($assignedClientUsers)): ?>
                                    <div class="text-center py-4">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="ri-user-line fs-1"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted"><?= lang('App.no_client_users_assigned') ?></h5>
                                        <p class="text-muted mb-0"><?= lang('App.no_client_users_assigned_description') ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="row g-3">
                                        <?php foreach ($assignedClientUsers as $user): ?>
                                            <div class="col-xl-4 col-lg-6 col-md-6">
                                                <div class="card border h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                                                        <i class="ri-user-line"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h6 class="mb-1">
                                                                    <a href="<?= base_url('contacts/edit/' . $user['id']) ?>" class="text-decoration-none">
                                                                        <?php 
                                                                        $fullName = trim($user['first_name'] . ' ' . $user['last_name']);
                                                                        echo esc($fullName ?: $user['username']);
                                                                        ?>
                                                                    </a>
                                                                </h6>
                                                                <p class="text-muted mb-2 fs-13">
                                                                    <i class="ri-mail-line me-1"></i><?= esc($user['email']) ?>
                                                                </p>
                                                                
                                                                <!-- User Type Badge -->
                                                                <span class="badge bg-info fs-12"><?= lang('App.client_user') ?></span>
                                                                
                                                                <!-- Status and Last Seen -->
                                                                <div class="mt-2">
                                                                    <?php if ($user['active']): ?>
                                                                        <span class="badge bg-success-subtle text-success fs-11">
                                                                            <i class="ri-checkbox-circle-line me-1"></i><?= lang('App.active') ?>
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-danger-subtle text-danger fs-11">
                                                                            <i class="ri-close-circle-line me-1"></i><?= lang('App.inactive') ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if (!empty($user['last_seen'])): ?>
                                                                        <small class="text-muted d-block mt-1">
                                                                            <i class="ri-time-line me-1"></i>
                                                                            <?= lang('App.last_seen') ?>: <?= date('M d, Y H:i', strtotime($user['last_seen'])) ?>
                                                                        </small>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Quick Actions -->
                                                        <div class="mt-3 pt-3 border-top">
                                                            <div class="d-flex gap-2">
                                                                <a href="<?= base_url('contacts/edit/' . $user['id']) ?>" class="btn btn-sm btn-outline-info flex-fill">
                                                                    <i class="ri-edit-line me-1"></i><?= lang('App.edit') ?>
                                                                </a>
                                                                <a href="mailto:<?= esc($user['email']) ?>" class="btn btn-sm btn-outline-primary flex-fill">
                                                                    <i class="ri-mail-line me-1"></i><?= lang('App.email') ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Add Client User Button -->
                                    <div class="text-center mt-4 pt-3 border-top">
                                        <a href="<?= base_url('contacts/create?client_id=' . $client['id']) ?>" class="btn btn-info">
                                            <i class="ri-user-add-line me-2"></i><?= lang('App.add_new_contact') ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function deleteClient(clientId) {
        console.log('deleteClient called with ID:', clientId);
        
        Swal.fire({
            title: '<?= lang('App.are_you_sure') ?>',
            text: '<?= lang('App.delete_client_warning') ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?= lang('App.yes_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('User confirmed deletion');
                
                // Show loading
                Swal.fire({
                    title: '<?= lang('App.processing') ?>',
                    text: '<?= lang('App.please_wait') ?>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Prepare data
                const postData = {
                    id: clientId
                };
                
                console.log('Sending POST data:', postData);
                
                // Make AJAX request
                $.ajax({
                    url: '<?= base_url('clients/delete') ?>',
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(response) {
                        console.log('AJAX success response:', response);
                        
                        if (response.status) {
                            Swal.fire({
                                title: '<?= lang('App.success') ?>',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: '<?= lang('App.ok') ?>'
                            }).then(() => {
                                window.location.href = '<?= base_url('clients') ?>';
                            });
                        } else {
                            Swal.fire({
                                title: '<?= lang('App.error') ?>',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: '<?= lang('App.ok') ?>'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX error:', {
                            xhr: xhr,
                            status: status,
                            error: error,
                            responseText: xhr.responseText
                        });
                        
                        let errorMessage = '<?= lang('App.something_wrong') ?>';
                        
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage = errorResponse.message;
                            }
                        } catch (e) {
                            console.log('Could not parse error response as JSON');
                        }
                        
                        Swal.fire({
                            title: '<?= lang('App.error') ?>',
                            text: errorMessage + ' (Status: ' + xhr.status + ')',
                            icon: 'error',
                            confirmButtonText: '<?= lang('App.ok') ?>'
                        });
                    }
                });
            } else {
                console.log('User cancelled deletion');
            }
        });
    }
</script>
<?= $this->endSection() ?>