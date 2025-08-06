<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= lang('App.dashboard') ?></a></li>
<li class="breadcrumb-item"><a href="<?= base_url('car_wash') ?>"><?= lang('App.car_wash_orders') ?></a></li>
<li class="breadcrumb-item"><a href="<?= base_url('car_wash/view/' . $order['id']) ?>">#<?= $order['order_number'] ?></a></li>
<li class="breadcrumb-item active"><?= lang('App.edit') ?></li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 16px;">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-primary text-white">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="card-title fw-bold mb-0"><?= $title ?></h5>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="<?= base_url('car_wash/view/' . $order['id']) ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> <?= lang('App.back_to_order') ?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="editCarWashForm" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                    
                    <div class="row">
                        <!-- Client Information -->
                        <div class="col-md-6">
                            <div class="card mb-4" style="border-radius: 12px;">
                                <div class="card-header">
                                    <h6 class="fw-bold mb-0">
                                        <i class="fas fa-building text-primary me-2"></i>
                                        <?= lang('App.client_information') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="client_id" class="form-label"><?= lang('App.client') ?> <span class="text-danger">*</span></label>
                                        <select class="form-select" id="client_id" name="client_id" required>
                                            <option value=""><?= lang('App.select') ?> <?= lang('App.client') ?></option>
                                            <?php foreach ($clients as $client): ?>
                                            <option value="<?= $client['id'] ?>" <?= $client['id'] == $order['client_id'] ? 'selected' : '' ?>>
                                                <?= $client['name'] ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback"><?= lang('App.client_required') ?></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="contact_id" class="form-label"><?= lang('App.contact') ?></label>
                                        <select class="form-select" id="contact_id" name="contact_id">
                                            <option value=""><?= lang('App.select') ?> <?= lang('App.contact') ?></option>
                                            <?php foreach ($contacts as $contact): ?>
                                            <option value="<?= $contact['id'] ?>" <?= $contact['id'] == $order['contact_id'] ? 'selected' : '' ?>>
                                                <?= $contact['name'] ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="po_number" class="form-label"><?= lang('App.po_number') ?></label>
                                        <input type="text" class="form-control" id="po_number" name="po_number" value="<?= $order['po_number'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Information -->
                        <div class="col-md-6">
                            <div class="card mb-4" style="border-radius: 12px;">
                                <div class="card-header">
                                    <h6 class="fw-bold mb-0">
                                        <i class="fas fa-car text-success me-2"></i>
                                        <?= lang('App.vehicle_information') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vehicle_make" class="form-label"><?= lang('App.make') ?> <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="vehicle_make" name="vehicle_make" value="<?= $order['vehicle_make'] ?>" required>
                                                <div class="invalid-feedback"><?= lang('App.make_required') ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vehicle_model" class="form-label"><?= lang('App.model') ?> <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" value="<?= $order['vehicle_model'] ?>" required>
                                                <div class="invalid-feedback"><?= lang('App.model_required') ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vehicle_year" class="form-label"><?= lang('App.year') ?></label>
                                                <input type="number" class="form-control" id="vehicle_year" name="vehicle_year" value="<?= $order['vehicle_year'] ?>" min="1900" max="<?= date('Y') + 1 ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vehicle_color" class="form-label"><?= lang('App.color') ?></label>
                                                <input type="text" class="form-control" id="vehicle_color" name="vehicle_color" value="<?= $order['vehicle_color'] ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="license_plate" class="form-label"><?= lang('App.license_plate') ?></label>
                                        <input type="text" class="form-control" id="license_plate" name="license_plate" value="<?= $order['license_plate'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Service Details -->
                        <div class="col-md-6">
                            <div class="card mb-4" style="border-radius: 12px;">
                                <div class="card-header">
                                    <h6 class="fw-bold mb-0">
                                        <i class="fas fa-cogs text-info me-2"></i>
                                        <?= lang('App.service_details') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="service_type" class="form-label"><?= lang('App.service_type') ?> <span class="text-danger">*</span></label>
                                        <select class="form-select" id="service_type" name="service_type" required>
                                            <option value=""><?= lang('App.select_service_type') ?></option>
                                            <option value="basic" <?= $order['service_type'] == 'basic' ? 'selected' : '' ?>><?= lang('App.basic') ?></option>
                                            <option value="premium" <?= $order['service_type'] == 'premium' ? 'selected' : '' ?>><?= lang('App.premium') ?></option>
                                            <option value="deluxe" <?= $order['service_type'] == 'deluxe' ? 'selected' : '' ?>><?= lang('App.deluxe') ?></option>
                                            <option value="custom" <?= $order['service_type'] == 'custom' ? 'selected' : '' ?>><?= lang('App.custom') ?></option>
                                        </select>
                                        <div class="invalid-feedback"><?= lang('App.service_type_required') ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="estimated_duration" class="form-label"><?= lang('App.estimated_duration') ?> (<?= lang('App.minutes') ?>)</label>
                                                <input type="number" class="form-control" id="estimated_duration" name="estimated_duration" value="<?= $order['estimated_duration'] ?>" min="15" max="480">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label"><?= lang('App.price') ?> <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control" id="price" name="price" value="<?= $order['price'] ?>" step="0.01" min="0" required>
                                                </div>
                                                <div class="invalid-feedback"><?= lang('App.price_required') ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule & Assignment -->
                        <div class="col-md-6">
                            <div class="card mb-4" style="border-radius: 12px;">
                                <div class="card-header">
                                    <h6 class="fw-bold mb-0">
                                        <i class="fas fa-calendar-alt text-warning me-2"></i>
                                        <?= lang('App.schedule_assignment') ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="date" class="form-label"><?= lang('App.date') ?> <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="date" name="date" value="<?= $order['date'] ?>" required>
                                                <div class="invalid-feedback"><?= lang('App.date_required') ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="time" class="form-label"><?= lang('App.time') ?> <span class="text-danger">*</span></label>
                                                <input type="time" class="form-control" id="time" name="time" value="<?= $order['time'] ?>" required>
                                                <div class="invalid-feedback"><?= lang('App.time_required') ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="priority" class="form-label">Waiter Priority</label>
                                                <div class="form-check" style="margin-top: 0.5rem;">
                                                    <input class="form-check-input" type="checkbox" id="priority" name="priority" value="waiter"
                                                           <?= ($order['priority'] == 'waiter') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="priority">
                                                        Mark as Waiter priority
                                                    </label>
                                                </div>
                                                <small class="text-muted">If unchecked, will be saved as Normal priority</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="assigned_to" class="form-label"><?= lang('App.assigned_to') ?></label>
                                                <select class="form-select" id="assigned_to" name="assigned_to">
                                                    <option value=""><?= lang('App.not_assigned') ?></option>
                                                    <?php foreach ($users as $user): ?>
                                                    <option value="<?= $user['id'] ?>" <?= $user['id'] == $order['assigned_to'] ? 'selected' : '' ?>>
                                                        <?= $user['first_name'] . ' ' . $user['last_name'] ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="card mb-4" style="border-radius: 12px;">
                        <div class="card-header">
                            <h6 class="fw-bold mb-0">
                                <i class="fas fa-sticky-note text-secondary me-2"></i>
                                <?= lang('App.notes') ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label"><?= lang('App.public_notes') ?></label>
                                        <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="<?= lang('App.visible_to_client') ?>"><?= $order['notes'] ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="internal_notes" class="form-label"><?= lang('App.internal_notes') ?></label>
                                        <textarea class="form-control" id="internal_notes" name="internal_notes" rows="4" placeholder="<?= lang('App.staff_only') ?>"><?= $order['internal_notes'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= base_url('car_wash/view/' . $order['id']) ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> <?= lang('App.cancel') ?>
                                </a>
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <i class="fas fa-save"></i> <?= lang('App.update_order') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Client change handler
    $('#client_id').on('change', function() {
        var clientId = $(this).val();
        loadContactsForClient(clientId);
        loadServicesForClient(clientId);
    });

    // Form submission
    $('#editCarWashForm').on('submit', function(e) {
        e.preventDefault();
        updateCarWashOrder();
    });

    // Load contacts for selected client
    function loadContactsForClient(clientId) {
        if (!clientId) {
            $('#contact_id').html('<option value=""><?= lang('App.select') ?> <?= lang('App.contact') ?></option>').prop('disabled', true);
            return;
        }

        $.ajax({
            url: '<?= base_url('car_wash/getContactsForClient') ?>/' + clientId,
            type: 'GET',
            beforeSend: function() {
                $('#contact_id').prop('disabled', true);
            },
            success: function(response) {
                var options = '<option value=""><?= lang('App.select') ?> <?= lang('App.contact') ?></option>';
                if (response.success && response.data) {
                    response.data.forEach(function(contact) {
                        options += '<option value="' + contact.id + '">' + contact.name + '</option>';
                    });
                }
                $('#contact_id').html(options).prop('disabled', false);
            },
            error: function() {
                $('#contact_id').html('<option value=""><?= lang('App.error_loading_contacts') ?></option>').prop('disabled', false);
            }
        });
    }

    // Load services for selected client
    function loadServicesForClient(clientId) {
        if (!clientId) return;

        $.ajax({
            url: '<?= base_url('car_wash/getServicesForClient') ?>/' + clientId,
            type: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    // Update service options or pricing if needed
                    console.log('Services loaded for client:', response.data);
                }
            }
        });
    }

    // Update car wash order
    function updateCarWashOrder() {
        var formData = $('#editCarWashForm').serialize();
        
        // Process priority checkbox
        var priorityChecked = $('#priority').is(':checked');
        formData = formData.replace(/&?priority=[^&]*/g, '');
        formData += '&priority=' + (priorityChecked ? 'waiter' : 'normal');

        $.ajax({
            url: '<?= base_url('car_wash/update/' . $order['id']) ?>',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#saveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <?= lang('App.saving') ?>...');
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: '<?= lang('App.success') ?>',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirect to view page
                        window.location.href = '<?= base_url('car_wash/view/' . $order['id']) ?>';
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: '<?= lang('App.error') ?>',
                        text: response.message
                    });

                    // Handle validation errors
                    if (response.errors) {
                        Object.keys(response.errors).forEach(function(field) {
                            $('#' + field).addClass('is-invalid');
                            $('#' + field).siblings('.invalid-feedback').text(response.errors[field]);
                        });
                    }
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: '<?= lang('App.error') ?>',
                    text: '<?= lang('App.error_updating_order') ?>'
                });
            },
            complete: function() {
                $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> <?= lang('App.update_order') ?>');
            }
        });
    }

    // Remove validation classes on input
    $('.form-control, .form-select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>

<?= $this->endSection() ?> 