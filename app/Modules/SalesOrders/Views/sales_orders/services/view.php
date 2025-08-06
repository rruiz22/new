<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Service Details<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Service Details<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('sales_orders_services') ?>">Services</a></li>
<li class="breadcrumb-item active">Service Details</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1">
                    <i class="ri-tools-fill me-2 text-primary"></i>
                    <?= esc($service['service_name']) ?>
                </h4>
                <div class="flex-shrink-0">
                    <a href="<?= base_url('sales_orders_services') ?>" class="btn btn-secondary btn-sm me-2">
                        <i class="ri-arrow-left-line me-1"></i> Back to Services
                    </a>
                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#serviceModal" data-service-id="<?= $service['id'] ?>">
                        <i class="ri-edit-fill me-1"></i> Edit Service
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Service Information -->
                    <div class="col-lg-8">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="ri-information-line me-2"></i>Service Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Service Name</label>
                                            <p class="text-muted mb-0"><?= esc($service['service_name']) ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Price</label>
                                            <p class="text-success fw-bold fs-5 mb-0">$<?= number_format($service['service_price'], 2) ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Status</label>
                                            <div>
                                                <?php if ($service['service_status'] === 'active'): ?>
                                                    <span class="badge bg-success fs-6">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger fs-6">Inactive</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Show in Orders</label>
                                            <div>
                                                <?php if ($service['show_in_orders'] == 1): ?>
                                                    <span class="badge bg-primary fs-6">Yes</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary fs-6">No</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($service['service_description'])): ?>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Description</label>
                                            <div class="bg-light p-3 rounded">
                                                <p class="mb-0"><?= nl2br(esc($service['service_description'])) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($service['notes'])): ?>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Notes</label>
                                            <div class="bg-light p-3 rounded">
                                                <p class="mb-0"><?= nl2br(esc($service['notes'])) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Service Metadata -->
                    <div class="col-lg-4">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="ri-settings-3-line me-2"></i>Service Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Service ID</label>
                                    <p class="text-muted mb-0">#<?= str_pad($service['id'], 5, '0', STR_PAD_LEFT) ?></p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Client</label>
                                    <?php if (!empty($service['client_id']) && !empty($service['client_name'])): ?>
                                        <p class="text-primary mb-0">
                                            <i class="ri-user-line me-1"></i>
                                            <?= esc($service['client_name']) ?>
                                            <?php if (!empty($service['client_email'])): ?>
                                                <br><small class="text-muted"><?= esc($service['client_email']) ?></small>
                                            <?php endif; ?>
                                        </p>
                                    <?php elseif (!empty($service['client_id'])): ?>
                                        <p class="text-warning mb-0">
                                            <i class="ri-user-line me-1"></i>
                                            Client ID: <?= esc($service['client_id']) ?> (Client not found)
                                        </p>
                                    <?php else: ?>
                                        <p class="text-info mb-0">
                                            <i class="ri-global-line me-1"></i>
                                            General Service (All Clients)
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Created</label>
                                    <p class="text-muted mb-0">
                                        <i class="ri-calendar-line me-1"></i>
                                        <?= date('M d, Y', strtotime($service['created_at'])) ?>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Last Updated</label>
                                    <p class="text-muted mb-0">
                                        <i class="ri-time-line me-1"></i>
                                        <?= date('M d, Y \a\t g:i A', strtotime($service['updated_at'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card border mt-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="ri-flashlight-line me-2"></i>Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#serviceModal" data-service-id="<?= $service['id'] ?>">
                                        <i class="ri-edit-2-line me-2"></i>Edit Service
                                    </a>
                                    
                                    <?php if ($service['service_status'] === 'active'): ?>
                                        <button class="btn btn-outline-warning" onclick="toggleStatus(<?= $service['id'] ?>, 'deactivate')">
                                            <i class="ri-pause-circle-line me-2"></i>Deactivate
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-success" onclick="toggleStatus(<?= $service['id'] ?>, 'activate')">
                                            <i class="ri-play-circle-line me-2"></i>Activate
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($service['show_in_orders'] == 1): ?>
                                        <button class="btn btn-outline-secondary" onclick="toggleShowInOrders(<?= $service['id'] ?>, 0)">
                                            <i class="ri-eye-off-line me-2"></i>Hide from Orders
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-info" onclick="toggleShowInOrders(<?= $service['id'] ?>, 1)">
                                            <i class="ri-eye-line me-2"></i>Show in Orders
                                        </button>
                                    <?php endif; ?>

                                    <hr>
                                    
                                    <button class="btn btn-outline-danger" onclick="deleteService(<?= $service['id'] ?>)">
                                        <i class="ri-delete-bin-line me-2"></i>Delete Service
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Service Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Content will be loaded via AJAX -->
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Toggle service status
function toggleStatus(serviceId, action) {
    const isActive = action === 'activate' ? 1 : 0;
    const actionText = action === 'activate' ? 'activate' : 'deactivate';
    
    Swal.fire({
        title: `¿Estás seguro?`,
        text: `¿Quieres ${actionText === 'activate' ? 'activar' : 'desactivar'} este servicio?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: action === 'activate' ? '#28a745' : '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Sí, ${actionText === 'activate' ? 'activar' : 'desactivar'}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('sales_orders_services/toggle_status') ?>/' + serviceId,
                type: 'POST',
                data: { is_active: isActive },
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.close(); // Cerrar el loading
                        showToast('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error updating service status',
                        icon: 'error'
                    });
                }
            });
        }
    });
}

// Toggle show in orders
function toggleShowInOrders(serviceId, showInOrders) {
    const actionText = showInOrders ? 'mostrar en órdenes' : 'ocultar de órdenes';
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres ${actionText}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: showInOrders ? '#17a2b8' : '#6c757d',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Sí, ${actionText}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('sales_orders_services/toggle_show_in_orders') ?>/' + serviceId,
                type: 'POST',
                data: { show_in_orders: showInOrders },
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.close(); // Cerrar el loading
                        showToast('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error updating service visibility',
                        icon: 'error'
                    });
                }
            });
        }
    });
}

// Delete service
function deleteService(serviceId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer. ¿Quieres eliminar este servicio?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: '<?= base_url('sales_orders_services/delete') ?>/' + serviceId,
                type: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(response) {
                    if (response.success) {
                        Swal.close(); // Cerrar el loading
                        showToast('success', 'El servicio ha sido eliminado exitosamente');
                        setTimeout(() => {
                            window.location.href = '<?= base_url('sales_orders_services') ?>';
                        }, 1000);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message || 'Error deleting service',
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error deleting service',
                        icon: 'error'
                    });
                }
            });
        }
    });
}

// Toast function
function showToast(type, message) {
    if (typeof Toastify !== 'undefined') {
        const colors = {
            success: "#28a745",
            error: "#dc3545", 
            info: "#17a2b8",
            warning: "#ffc107"
        };
        
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: colors[type] || colors.info,
        }).showToast();
    } else {

    }
}

// Initialize feather icons
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Handle edit service modal
    $('[data-bs-toggle="modal"][data-bs-target="#serviceModal"]').on('click', function(e) {
        e.preventDefault();
        var serviceId = $(this).data('service-id');
        
        // Load modal form for editing
        $.get('<?= base_url('sales_orders_services/modal_form') ?>', { id: serviceId })
            .done(function(data) {
                $('#serviceModal .modal-content').html(data);
                $('#serviceModal').modal('show');
            })
            .fail(function() {
                showToast('error', 'Error loading service data');
            });
    });
    
    // Handle form submission
    $(document).on('submit', '#serviceForm', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '<?= base_url('sales_orders_services/store') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    $('#serviceModal').modal('hide');
                    showToast('success', response.message || 'Service updated successfully');
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast('error', response.message || 'Error saving service');
                }
            },
            error: function() {
                showToast('error', 'Error saving service');
            }
        });
    });
});
</script>
<?= $this->endSection() ?> 
