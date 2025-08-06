<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? 'Recon Service' ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?? 'Recon Service' ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('recon_orders') ?>">Recon Orders</a></li>
<li class="breadcrumb-item"><a href="<?= base_url('recon_orders') ?>#services-tab">Services</a></li>
<li class="breadcrumb-item active"><?= $title ?? 'Recon Service' ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Enhanced Topbar Styling */
.service-top-bar {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.top-bar-item {
    position: relative;
    padding: 1rem 0.75rem;
    border-right: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    align-items: center;
    min-height: 120px;
}

.top-bar-item:last-child {
    border-right: none;
}

.top-bar-item:hover {
    background: rgba(59, 130, 246, 0.05);
}

.top-bar-icon {
    margin-right: 0.75rem;
    flex-shrink: 0;
    width: 32px;
    display: flex;
    justify-content: center;
}

.top-bar-icon i {
    font-size: 1.25rem;
}

.top-bar-content {
    flex: 1;
    min-width: 0;
}

.top-bar-label {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 0.25rem;
    letter-spacing: 0.5px;
    line-height: 1;
}

.top-bar-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
    line-height: 1.3;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.top-bar-sub {
    font-size: 0.75rem;
    color: #64748b;
    line-height: 1.2;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Service Color Styling */
.service-color-indicator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    display: inline-block;
    margin-right: 8px;
}

.status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 0.375rem;
    font-weight: 500;
    font-size: 0.75rem;
}

.visibility-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 0.375rem;
    font-weight: 500;
    font-size: 0.75rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 fw-bold text-dark">
                <i class="ri-tools-line me-2"></i><?= $service['name'] ?? 'Recon Service' ?>
            </h4>
            <div class="page-title-right">
                <a href="<?= base_url('recon_orders') ?>#services-tab" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to Services
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Top Bar Information -->
<div class="row mb-4">
    <div class="col-12">
        <div class="service-top-bar">
            <div class="row g-0">
                <!-- Service Name -->
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-tools-line text-primary"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Service Name</div>
                            <div class="top-bar-value">
                                <div class="service-color-indicator" style="background-color: <?= $service['color'] ?? '#007bff' ?>"></div>
                                <?= $service['name'] ?>
                            </div>
                            <div class="top-bar-sub"><?= date('M d, Y', strtotime($service['created_at'])) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Client -->
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-building-line text-info"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Client</div>
                            <div class="top-bar-value"><?= $service['client_name'] ?? 'Global' ?></div>
                            <div class="top-bar-sub">Service Assignment</div>
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-money-dollar-circle-line text-success"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Price</div>
                            <div class="top-bar-value">$<?= number_format($service['price'] ?? 0, 2) ?></div>
                            <div class="top-bar-sub">Service Rate</div>
                        </div>
                    </div>
                </div>



                <!-- Status -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-toggle-line text-danger"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Status</div>
                            <div class="top-bar-value">
                                <?php
                                $statusColor = $service['is_active'] ? 'success' : 'danger';
                                $statusText = $service['is_active'] ? 'Active' : 'Inactive';
                                ?>
                                <span class="badge bg-<?= $statusColor ?> status-badge"><?= $statusText ?></span>
                            </div>
                            <div class="top-bar-sub">Current Status</div>
                        </div>
                    </div>
                </div>

                <!-- Visibility -->
                <div class="col-xl-2 col-lg-6 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-eye-line text-primary"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Visibility</div>
                            <div class="top-bar-value">
                                <?php
                                $visibilityColor = ($service['show_in_form'] ?? 1) ? 'info' : 'secondary';
                                $visibilityText = ($service['show_in_form'] ?? 1) ? 'Visible' : 'Hidden';
                                ?>
                                <span class="badge bg-<?= $visibilityColor ?> visibility-badge"><?= $visibilityText ?></span>
                            </div>
                            <div class="top-bar-sub">Form Display</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Service Details -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-information-line me-2"></i>Service Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary">Basic Information</h6>
                        <ul class="list-unstyled">
                            <li><strong>Service Name:</strong> <?= $service['name'] ?></li>
                            <li><strong>Client:</strong> <?= $service['client_name'] ?? 'Global' ?></li>

                            <li><strong>Price:</strong> $<?= number_format($service['price'] ?? 0, 2) ?></li>
                            <li><strong>Color:</strong> 
                                <span class="service-color-indicator" style="background-color: <?= $service['color'] ?? '#007bff' ?>"></span>
                                <?= $service['color'] ?? '#007bff' ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary">Status & Visibility</h6>
                        <ul class="list-unstyled">
                            <li><strong>Status:</strong> 
                                <span class="badge bg-<?= $service['is_active'] ? 'success' : 'danger' ?>">
                                    <?= $service['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </li>
                            <li><strong>Show in Form:</strong> 
                                <span class="badge bg-<?= ($service['show_in_form'] ?? 1) ? 'info' : 'secondary' ?>">
                                    <?= ($service['show_in_form'] ?? 1) ? 'Yes' : 'No' ?>
                                </span>
                            </li>
                            <li><strong>Created:</strong> <?= date('M d, Y g:i A', strtotime($service['created_at'])) ?></li>
                            <li><strong>Updated:</strong> <?= $service['updated_at'] ? date('M d, Y g:i A', strtotime($service['updated_at'])) : 'Never' ?></li>
                        </ul>
                    </div>
                </div>
                
                <?php if (!empty($service['description'])): ?>
                <div class="mt-4">
                    <h6 class="fw-bold text-primary">Description</h6>
                    <div class="bg-light p-3 rounded">
                        <?= nl2br(esc($service['description'])) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Usage Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-line me-2"></i>Usage Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="p-3">
                            <h4 class="fw-bold text-primary mb-1"><?= $stats['total_orders'] ?? 0 ?></h4>
                            <p class="text-muted mb-0">Total Orders</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <h4 class="fw-bold text-success mb-1"><?= $stats['completed_orders'] ?? 0 ?></h4>
                            <p class="text-muted mb-0">Completed</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <h4 class="fw-bold text-info mb-1">$<?= number_format($stats['total_revenue'] ?? 0, 2) ?></h4>
                            <p class="text-muted mb-0">Total Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-xl-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-settings-3-line me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" onclick="editService(<?= $service['id'] ?>)">
                        <i class="ri-edit-line me-2"></i>Edit Service
                    </button>
                    <button class="btn btn-<?= $service['is_active'] ? 'warning' : 'success' ?>" onclick="toggleServiceStatus(<?= $service['id'] ?>, <?= $service['is_active'] ? 0 : 1 ?>)">
                        <i class="ri-toggle-line me-2"></i><?= $service['is_active'] ? 'Deactivate' : 'Activate' ?> Service
                    </button>
                    <button class="btn btn-info" onclick="toggleServiceVisibility(<?= $service['id'] ?>, <?= ($service['show_in_form'] ?? 1) ? 0 : 1 ?>)">
                        <i class="ri-eye-line me-2"></i><?= ($service['show_in_form'] ?? 1) ? 'Hide from' : 'Show in' ?> Form
                    </button>
                    <button class="btn btn-danger" onclick="deleteService(<?= $service['id'] ?>)">
                        <i class="ri-delete-bin-line me-2"></i>Delete Service
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-history-line me-2"></i>Recent Orders
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentOrders)): ?>
                    <?php foreach (array_slice($recentOrders, 0, 5) as $order): ?>
                        <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                            <div class="flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle text-primary rounded">
                                    <i class="ri-car-line"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?= base_url('recon_orders/view/' . $order['id']) ?>" class="fw-medium text-dark">
                                    <?= $order['order_number'] ?>
                                </a>
                                <p class="text-muted mb-0 fs-12"><?= $order['client_name'] ?> - <?= $order['vehicle'] ?></p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-<?= $this->getStatusColor($order['status']) ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No recent orders using this service.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <div class="modal-header bg-light" style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #e9ecef; padding: 1.5rem;">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-3">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="ri-edit-line fs-16"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="editServiceModalLabel">Edit Service</h5>
                        <p class="text-muted mb-0 fs-13">Update service details and configuration</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <form id="editServiceForm">
                    <input type="hidden" id="editServiceId" name="id" value="<?= $service['id'] ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border h-100" style="border-radius: 12px; transition: all 0.3s ease;">
                                <div class="card-header bg-primary-subtle border-0" style="border-radius: 12px 12px 0 0;">
                                    <h6 class="mb-0 text-primary fw-bold">
                                        <i class="ri-information-line me-2"></i>Basic Information
                                    </h6>
                                </div>
                                <div class="card-body" style="padding: 1.5rem;">
                                    <div class="mb-3">
                                        <label for="editServiceName" class="form-label fw-semibold">Service Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="editServiceName" name="name" required placeholder="Enter service name" value="<?= esc($service['name']) ?>">
                                        <div class="invalid-feedback">Please provide a service name.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="editServiceDescription" class="form-label fw-semibold">Description</label>
                                        <textarea class="form-control" id="editServiceDescription" name="description" rows="3" placeholder="Enter service description"><?= esc($service['description'] ?? '') ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="editServicePrice" class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="editServicePrice" name="price" step="0.01" min="0" required placeholder="0.00" value="<?= $service['price'] ?? 0 ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border h-100" style="border-radius: 12px;">
                                <div class="card-header bg-success-subtle border-0" style="border-radius: 12px 12px 0 0;">
                                    <h6 class="mb-0 text-success fw-bold">
                                        <i class="ri-settings-3-line me-2"></i>Configuration
                                    </h6>
                                </div>
                                <div class="card-body" style="padding: 1.5rem;">
                                    <div class="mb-3">
                                        <label for="editServiceClient" class="form-label fw-semibold">Client</label>
                                        <select class="form-select" id="editServiceClient" name="client_id">
                                            <option value="">Global (All Clients)</option>
                                            <!-- Options loaded via AJAX -->
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="editServiceColor" class="form-label fw-semibold">Service Color</label>
                                        <input type="color" class="form-control form-control-color w-100" id="editServiceColor" name="color" value="<?= $service['color'] ?? '#007bff' ?>" title="Choose service color">
                                        <small class="text-muted">This color will be used to highlight service rows</small>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="editServiceActive" name="is_active" <?= ($service['is_active'] ?? 0) ? 'checked' : '' ?>>
                                                <label class="form-check-label fw-semibold" for="editServiceActive">Active</label>
                                                <small class="d-block text-muted">Service is available for selection</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="editServiceShowInForm" name="show_in_form" <?= ($service['show_in_form'] ?? 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label fw-semibold" for="editServiceShowInForm">Show in Form</label>
                                                <small class="d-block text-muted">Display in order forms</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light" style="border-radius: 0 0 16px 16px; padding: 1.5rem; border-top: 1px solid #e9ecef;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" id="saveEditServiceBtn">
                    <i class="ri-save-line me-1"></i>Update Service
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function editService(serviceId) {
    // Load clients and show the edit modal
    loadEditModalClients();
    
    // Reset any previous validation states
    $('#editServiceModal .is-invalid').removeClass('is-invalid');
    
    $('#editServiceModal').modal('show');
}

function loadEditModalClients() {
    // Load clients for the edit modal
    $.ajax({
        url: '<?= base_url('recon_orders/getClients') ?>',
        method: 'GET',
        success: function(response) {
            if (response.success && response.clients) {
                var clientSelect = $('#editServiceClient');
                
                // Clear existing options (except the first one)
                clientSelect.find('option:not(:first)').remove();
                
                // Add client options
                response.clients.forEach(function(client) {
                    var selected = client.id == '<?= $service['client_id'] ?? '' ?>' ? 'selected' : '';
                    clientSelect.append('<option value="' + client.id + '" ' + selected + '>' + client.name + '</option>');
                });
            }
        },
        error: function() {
            console.error('Error loading clients for edit modal');
        }
    });
}

function toggleServiceStatus(serviceId, newStatus) {
    if (confirm('Are you sure you want to ' + (newStatus ? 'activate' : 'deactivate') + ' this service?')) {
        $.ajax({
            url: '<?= base_url('recon_orders/services/toggle-status/') ?>' + serviceId,
            type: 'POST',
            data: {
                is_active: newStatus
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Service status updated successfully');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showToast('error', response.message || 'Failed to update service status');
                }
            },
            error: function() {
                showToast('error', 'An error occurred while updating service status');
            }
        });
    }
}

function toggleServiceVisibility(serviceId, newVisibility) {
    if (confirm('Are you sure you want to ' + (newVisibility ? 'show' : 'hide') + ' this service in forms?')) {
        $.ajax({
            url: '<?= base_url('recon_orders/services/toggle-visibility/') ?>' + serviceId,
            type: 'POST',
            data: {
                show_in_form: newVisibility
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Service visibility updated successfully');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showToast('error', response.message || 'Failed to update service visibility');
                }
            },
            error: function() {
                showToast('error', 'An error occurred while updating service visibility');
            }
        });
    }
}

function deleteService(serviceId) {
    if (confirm('Are you sure you want to delete this service? This action cannot be undone.')) {
        $.ajax({
            url: '<?= base_url('recon_orders/services/delete/') ?>' + serviceId,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Service deleted successfully');
                    setTimeout(function() {
                        window.location.href = '<?= base_url('recon_orders') ?>#services-tab';
                    }, 1000);
                } else {
                    showToast('error', response.message || 'Failed to delete service');
                }
            },
            error: function() {
                showToast('error', 'An error occurred while deleting the service');
            }
        });
    }
}

// Handle save edit service button
$(document).ready(function() {
    $('#saveEditServiceBtn').on('click', function() {
        var $btn = $(this);
        var originalText = $btn.html();
        
        // Validate form
        var isValid = true;
        var serviceName = $('#editServiceName').val().trim();
        var servicePrice = $('#editServicePrice').val();
        
        // Reset validation states
        $('.is-invalid').removeClass('is-invalid');
        
        // Validate service name
        if (!serviceName) {
            $('#editServiceName').addClass('is-invalid');
            isValid = false;
        }
        
        // Validate price
        if (!servicePrice || parseFloat(servicePrice) < 0) {
            $('#editServicePrice').addClass('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            showToast('error', 'Please fill in all required fields correctly');
            return;
        }
        
        // Show loading state
        $btn.html('<i class="ri-loader-4-line me-1"></i>Updating...').prop('disabled', true);
        
        var formData = new FormData(document.getElementById('editServiceForm'));
        var serviceId = $('#editServiceId').val();
        
        // Convert checkbox values
        formData.set('is_active', $('#editServiceActive').is(':checked') ? '1' : '0');
        formData.set('show_in_form', $('#editServiceShowInForm').is(':checked') ? '1' : '0');
        
        // Set client_id to empty string if not selected (for global services)
        if (!$('#editServiceClient').val()) {
            formData.set('client_id', '');
        }
        
        $.ajax({
            url: '<?= base_url('recon_orders/services/update/') ?>' + serviceId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
                                        success: function(response) {
                if (response.success) {
                    showToast('success', response.message || 'Service updated successfully');
                    
                    // Update the page elements with new data
                    var newName = $('#editServiceName').val();
                    var newColor = $('#editServiceColor').val();
                    var newPrice = $('#editServicePrice').val();
                    var newStatus = $('#editServiceActive').is(':checked');
                    var newVisibility = $('#editServiceShowInForm').is(':checked');
                    
                    // Update the top bar
                    $('.service-color-indicator').css('background-color', newColor);
                    $('h4:contains("<?= $service['name'] ?>")').html('<i class="ri-tools-line me-2"></i>' + newName);
                    
                    // Update service details
                    $('strong:contains("Service Name:")').next().text(newName);
                    $('strong:contains("Price:")').next().text('$' + parseFloat(newPrice).toFixed(2));
                    
                    // Force close modal without unsaved changes confirmation
                    $('#editServiceModal').data('force-close', true).modal('hide');
                    
                    // Reload the page to show all updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showToast('error', response.message || 'Failed to update service');
                }
            },
            error: function(xhr, status, error) {
                console.error('Update service error:', error);
                var errorMessage = 'An error occurred while updating the service';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 422) {
                    errorMessage = 'Validation error. Please check your input.';
                }
                
                showToast('error', errorMessage);
            },
            complete: function() {
                // Restore button state
                $btn.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Reset modal when hidden
    $('#editServiceModal').on('hidden.bs.modal', function() {
        // Clear any validation states
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').hide();
    });
    
    // Add real-time validation
    $('#editServiceName').on('input', function() {
        if ($(this).val().trim()) {
            $(this).removeClass('is-invalid');
        }
    });
    
    $('#editServicePrice').on('input', function() {
        var price = parseFloat($(this).val());
        if ($(this).val() && price >= 0) {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Color preview update
    $('#editServiceColor').on('input', function() {
        var color = $(this).val();
        $(this).css('background-color', color);
    });
    
    // Track form changes
    var originalFormData = {};
    $('#editServiceModal').on('shown.bs.modal', function() {
        // Store original form data
        originalFormData = {
            name: $('#editServiceName').val(),
            description: $('#editServiceDescription').val(),
            price: $('#editServicePrice').val(),
            client_id: $('#editServiceClient').val(),
            color: $('#editServiceColor').val(),
            is_active: $('#editServiceActive').is(':checked'),
            show_in_form: $('#editServiceShowInForm').is(':checked')
        };
    });
    
    // Check for unsaved changes
    function hasUnsavedChanges() {
        return (
            originalFormData.name !== $('#editServiceName').val() ||
            originalFormData.description !== $('#editServiceDescription').val() ||
            originalFormData.price !== $('#editServicePrice').val() ||
            originalFormData.client_id !== $('#editServiceClient').val() ||
            originalFormData.color !== $('#editServiceColor').val() ||
            originalFormData.is_active !== $('#editServiceActive').is(':checked') ||
            originalFormData.show_in_form !== $('#editServiceShowInForm').is(':checked')
        );
    }
    
    // Confirm before closing modal with unsaved changes
    $('#editServiceModal').on('hide.bs.modal', function(e) {
        if (hasUnsavedChanges() && !$(this).data('force-close')) {
            e.preventDefault();
            if (confirm('You have unsaved changes. Are you sure you want to close without saving?')) {
                $(this).data('force-close', true);
                $(this).modal('hide');
            }
        } else {
            $(this).removeData('force-close');
        }
    });
});

// Define showToast function if not available
if (typeof window.showToast === 'undefined') {
    window.showToast = function(type, message) {
        // Simple toast notification using SweetAlert2
        const icon = type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info';
        
        Swal.fire({
            icon: icon,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    };
}
</script>
<?= $this->endSection() ?> 