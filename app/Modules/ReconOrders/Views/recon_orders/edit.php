<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? 'Edit Recon Order' ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?? 'Edit Recon Order' ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('recon_orders') ?>">Recon Orders</a></li>
<li class="breadcrumb-item active"><?= $title ?? 'Edit Recon Order' ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS -->
<?= $this->include('partials/datatables-styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><?= $title ?></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('recon_orders') ?>">Recon Orders</a></li>
                        <li class="breadcrumb-item active"><?= lang('App.edit') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-edit-line me-2"></i>Edit Recon Order: <?= esc($order['order_number']) ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form id="editReconOrderForm" method="POST" action="<?= base_url('recon_orders/update/' . $order['id']) ?>">
                        <!-- Primera fila: Order Number, Client -->
                        <div class="row">
                            <!-- Order Number -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order_number" class="form-label">Order Number</label>
                                    <input type="text" class="form-control" id="order_number" name="order_number" 
                                           value="<?= esc($order['order_number']) ?>" readonly>
                                </div>
                            </div>
                            
                            <!-- Service Date -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_date" class="form-label">
                                        <i class="ri-calendar-line me-1"></i>Service Date
                                    </label>
                                    <input type="date" class="form-control" id="service_date" name="service_date" 
                                           value="<?= esc($order['service_date'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Segunda fila: Client -->
                        <div class="row">
                            <!-- Client -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="client_id" class="form-label">
                                        <i class="ri-user-line me-1"></i>Client *
                                    </label>
                                    <select class="form-select" id="client_id" name="client_id" required>
                                        <option value="">Select Client</option>
                                        <?php if (!empty($clients)): ?>
                                            <?php foreach ($clients as $client): ?>
                                                <option value="<?= $client['id'] ?>" 
                                                        <?= ($order['client_id'] == $client['id']) ? 'selected' : '' ?>>
                                                    <?= esc($client['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Client is required
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tercera fila: Stock, VIN, Vehicle -->
                        <div class="row">
                            <!-- Stock -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">
                                        <i class="ri-archive-line me-1"></i>Stock *
                                    </label>
                                    <input type="text" class="form-control" id="stock" name="stock" 
                                           value="<?= esc($order['stock']) ?>" placeholder="Enter stock number" required>
                                    <div class="invalid-feedback">
                                        Stock is required
                                    </div>
                                </div>
                            </div>
                            
                            <!-- VIN -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="vin_number" class="form-label">
                                        <i class="ri-car-line me-1"></i>VIN *
                                    </label>
                                    <input type="text" class="form-control" id="vin_number" name="vin_number" 
                                           value="<?= esc($order['vin_number']) ?>" placeholder="Enter VIN number" 
                                           maxlength="17" required>
                                    <div class="invalid-feedback">
                                        VIN number is required
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Vehicle -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="vehicle" class="form-label">
                                        <i class="ri-car-fill me-1"></i>Vehicle *
                                    </label>
                                    <input type="text" class="form-control" id="vehicle" name="vehicle" 
                                           value="<?= esc($order['vehicle']) ?>" placeholder="Enter vehicle details" required>
                                    <div class="invalid-feedback">
                                        Vehicle is required
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cuarta fila: Service, Status -->
                        <div class="row">
                            <!-- Service -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_id" class="form-label">
                                        <i class="ri-tools-line me-1"></i>Service *
                                    </label>
                                    <select class="form-select" id="service_id" name="service_id" required>
                                        <option value="">Select Service</option>
                                        <?php if (!empty($services)): ?>
                                            <?php foreach ($services as $service): ?>
                                                <option value="<?= $service['id'] ?>" 
                                                        <?= ($order['service_id'] == $service['id']) ? 'selected' : '' ?>>
                                                    <?= esc($service['name']) ?>
                                                    <?php if (!empty($service['price'])): ?>
                                                        - $<?= number_format($service['price'], 2) ?>
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Service is required
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <i class="ri-flag-line me-1"></i>Status
                                    </label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="pending" <?= ($order['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                        <option value="in_progress" <?= ($order['status'] == 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                                        <option value="completed" <?= ($order['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= ($order['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pictures -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Pictures</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pictures" name="pictures" value="1" 
                                               <?= ($order['pictures'] == 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="pictures">
                                            Pictures taken
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Notes -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">
                                        <i class="ri-sticky-note-line me-1"></i>Notes
                                    </label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                                              placeholder="Enter any additional notes"><?= esc($order['notes']) ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= base_url('recon_orders') ?>" class="btn btn-secondary">
                                        <i class="ri-arrow-left-line me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="updateBtn">
                                        <i class="ri-save-line me-1"></i>Update Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load services for the current client on page load
    const currentClientId = document.getElementById('client_id').value;
    if (currentClientId) {
        loadServicesForClient(currentClientId);
    }
    
    // VIN validation
    const vinInput = document.getElementById('vin_number');
    if (vinInput) {
        vinInput.addEventListener('input', function() {
            const vin = this.value.toUpperCase();
            this.value = vin;
            
            if (vin.length > 0 && vin.length < 17) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
    
    // Form submission
    const form = document.getElementById('editReconOrderForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }
            
            const formData = {
                client_id: document.getElementById('client_id').value,
                service_id: document.getElementById('service_id').value,
                service_date: document.getElementById('service_date').value,
                stock: document.getElementById('stock').value,
                vin_number: document.getElementById('vin_number').value,
                vehicle: document.getElementById('vehicle').value,
                status: document.getElementById('status').value,
                pictures: document.getElementById('pictures').checked ? 1 : 0,
                notes: document.getElementById('notes').value
            };
            
            const submitBtn = document.getElementById('updateBtn');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="ri-loader-2-line me-1"></i>Updating...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(response => {
                if (response.success) {
                    showToast('success', response.message || 'Order updated successfully');
                    // Redirect back to orders list after successful update
                    setTimeout(function() {
                        window.location.href = '<?= base_url('recon_orders') ?>';
                    }, 1500);
                } else {
                    showToast('error', response.message || 'Failed to update order');
                }
            })
            .catch(error => {
                console.error('Update error:', error);
                showToast('error', 'An error occurred while updating the order');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
   
    // Load services when client changes
    const clientSelect = document.getElementById('client_id');
    if (clientSelect) {
        clientSelect.addEventListener('change', function() {
            const clientId = this.value;
            if (clientId) {
                loadServicesForClient(clientId);
            }
        });
    }
});

function loadServicesForClient(clientId) {
    fetch('<?= base_url('recon_orders/getServicesForClient/') ?>' + clientId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success && response.data) {
            const serviceSelect = document.getElementById('service_id');
            const currentServiceId = '<?= $order['service_id'] ?>';
            
            if (serviceSelect) {
                // Clear existing options except the first one
                const firstOption = serviceSelect.querySelector('option:first-child');
                serviceSelect.innerHTML = '';
                if (firstOption) {
                    serviceSelect.appendChild(firstOption);
                } else {
                    serviceSelect.innerHTML = '<option value="">Select Service</option>';
                }
                
                response.data.forEach(function(service) {
                    if (service.is_active && service.show_in_form) {
                        const option = document.createElement('option');
                        option.value = service.id;
                        option.textContent = service.name + (service.price ? ' - $' + parseFloat(service.price).toFixed(2) : '');
                        
                        if (service.id == currentServiceId) {
                            option.selected = true;
                        }
                        
                        serviceSelect.appendChild(option);
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error loading services for client:', error);
    });
}

// showToast function removed - using global definition from index.php
</script>

<!-- DataTables scripts already loaded globally in default.php -->

<?= $this->endSection() ?> 