<!-- UNIFIED MODAL - Works for both Add and Edit -->
<style>
    .modal-dialog {
        max-width: 700px;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
        min-height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        background-color: #fff;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: 0;
    }

    .form-select:hover {
        border-color: #86b7fe;
    }

    .form-select:disabled {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
        opacity: 0.65;
    }

    .form-section {
        margin-bottom: 1.5rem;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }
    }

    .modal-title .badge {
        font-size: 0.65rem;
        vertical-align: top;
    }

    /* VIN Barcode Scanner Styles */
    .scan-vin-btn {
        top: 4px;
        right: 4px;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.2;
        z-index: 10;
    }

    .scan-vin-btn:hover {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    .vin-input-container {
        position: relative;
    }

    /* Scanner Modal Styles */
    .scanner-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 10000;
        display: none;
        flex-direction: column;
    }

    .scanner-header {
        background: #fff;
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .scanner-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .scanner-controls .btn {
        font-size: 12px;
        padding: 4px 8px;
    }

    .scanner-container {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
        overflow: hidden;
    }

    #scanner-target {
        width: 100%;
        height: 100%;
        position: relative;
    }
    
    #scanner-target video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
    }

    .scanner-overlay {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 280px;
        height: 100px;
        border: 2px solid #0d6efd;
        border-radius: 8px;
        box-shadow: 0 0 0 100vmax rgba(0, 0, 0, 0.3);
        z-index: 10001;
        pointer-events: none;
    }

    .scanner-overlay::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border: 2px solid #fff;
        border-radius: 8px;
        animation: scan-pulse 2s infinite;
    }

    @keyframes scan-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .spin {
        animation: spin 1s linear infinite;
    }

    .scanner-instructions {
        position: fixed;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.95);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        color: #333;
        font-size: 0.875rem;
        text-align: center;
        max-width: 300px;
        z-index: 10002;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    /* Mobile Responsive */
    @media (max-width: 991.98px) {
        .scan-vin-btn {
            display: block !important;
        }
    }

    @media (max-width: 576px) {
        .scanner-overlay {
            width: 260px;
            height: 80px;
        }
        
        .scanner-instructions {
            bottom: 60px;
            max-width: 280px;
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
    }
    
    @media (max-width: 414px) {
        .scanner-overlay {
            width: 240px;
            height: 70px;
        }
        
        .scanner-instructions {
            bottom: 50px;
            max-width: 260px;
            font-size: 0.75rem;
        }
    }
</style>

<div class="modal-header">
    <h5 class="modal-title" id="modalTitle">
        <?php if(isset($order) && $order): ?>
            <?= lang('App.edit_sales_order') ?> 
            <span class="badge bg-primary ms-2">ID: <?= $order['id'] ?></span>
        <?php else: ?>
            <?= lang('App.add_sales_order') ?>
            <span class="badge bg-success ms-2"><?= lang('App.new') ?></span>
        <?php endif; ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <form id="orderForm" action="<?= base_url('sales_orders/store') ?>" method="post">
        <?php if(isset($order) && $order): ?>
            <input type="hidden" name="id" value="<?= $order['id'] ?>">
        <?php endif; ?>

        <!-- Client and Contact Section -->
        <div class="form-section">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                        <label for="client_id" class="form-label"><?= lang('App.client') ?> <span class="text-danger">*</span></label>
                        <select class="form-select" id="client_id" name="client_id" required>
                                <option value=""><?= lang('App.select_client') ?></option>
                                <?php if (isset($clients) && !empty($clients)): ?>
                                    <?php foreach ($clients as $client): ?>
                                    <option value="<?= $client['id'] ?>" <?= (isset($order) && $order && $order['client_id'] == $client['id']) ? 'selected' : '' ?>>
                                            <?= esc($client['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="contact_id" class="form-label"><?= lang('App.contact') ?> <span class="text-danger">*</span></label>
                        <select class="form-select" id="contact_id" name="contact_id" required disabled>
                        <option value=""><?= lang('App.select_contact') ?></option>
                            <?php if (isset($order) && $order && isset($contacts) && !empty($contacts)): ?>
                            <?php foreach ($contacts as $contact): ?>
                                    <option value="<?= $contact['id'] ?>" data-client-id="<?= $contact['client_id'] ?>" 
                                    <?= (isset($order['contact_id']) && $order['contact_id'] == $contact['id']) ? 'selected' : '' ?>>
                                    <?= esc($contact['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Information Section -->
        <div class="form-section">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="stock" class="form-label"><?= lang('App.stock') ?></label>
                        <input type="text" class="form-control" id="stock" name="stock" value="<?= isset($order) && $order ? esc($order['stock']) : '' ?>" placeholder="<?= lang('App.enter_stock_number') ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="vin" class="form-label"><?= lang('App.vin') ?></label>
                    <div class="vin-input-container position-relative">
                        <input type="text" class="form-control" id="vin" name="vin" value="<?= isset($order) && $order ? esc($order['vin']) : '' ?>" placeholder="<?= lang('App.enter_vin_placeholder') ?>" maxlength="17">
                        
                        <!-- Barcode Scanner Button (Only visible on mobile/tablet) -->
                        <button type="button" id="scanVinBtn" class="btn btn-outline-primary btn-sm position-absolute scan-vin-btn d-none">
                            <i class="ri-qr-scan-line me-1"></i>
                            <span class="scan-btn-text"><?= lang('App.scan') ?></span>
                        </button>
                        
                        <span class="vin-status" id="vin-status"></span>
                    </div>
                    <small class="text-muted"><?= lang('App.vin_help_text') ?></small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="vehicle" class="form-label"><?= lang('App.vehicle') ?></label>
                        <input type="text" class="form-control" id="vehicle" name="vehicle" value="<?= isset($order) && $order ? esc($order['vehicle']) : '' ?>" placeholder="<?= lang('App.enter_vehicle_details') ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="service_id" class="form-label"><?= lang('App.service') ?> <span class="text-danger">*</span></label>
                        <select class="form-select" id="service_id" name="service_id" required disabled>
                        <option value=""><?= lang('App.select') ?> <?= lang('App.service') ?></option>
                        <?php if (isset($services) && !empty($services)): ?>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['id'] ?>"
                                        data-client-id="<?= $service['client_id'] ?? '' ?>"
                                            <?= (isset($order) && $order && $order['service_id'] == $service['id']) ? 'selected' : '' ?>>
                                    <?= esc($service['service_name']) ?> - $<?= number_format($service['service_price'], 2) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date and Time Section -->
        <div class="form-section">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                        <label for="order_date" class="form-label"><?= lang('App.date') ?> <span class="text-danger">*</span></label>
                        <?php 
                        // Fix: Use correct field name 'date' instead of 'order_date'
                        if (isset($order) && $order && !empty($order['date'])) {
                            $defaultDate = $order['date']; // Use directly if already in Y-m-d format
                        } else {
                            $defaultDate = date('Y-m-d');
                        }
                        ?>
                        <input type="date" class="form-control" id="order_date" name="date" value="<?= $defaultDate ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                        <label for="order_time" class="form-label"><?= lang('App.time') ?></label>
                    <?php
                        // Fix: Use correct field name 'time' instead of extracting from 'order_date'
                        if (isset($order) && $order && !empty($order['time'])) {
                            // Remove seconds if present (16:30:00 -> 16:30)
                            $defaultTime = substr($order['time'], 0, 5);
                        } else {
                            $defaultTime = '09:00';
                        }
                    ?>
                        <input type="time" class="form-control" id="order_time" name="time" value="<?= $defaultTime ?>">
                </div>
            </div>
        </div>

        <!-- Hidden status field - status can only be changed in view.php -->
        <input type="hidden" id="order_status" name="status" value="<?= isset($order) && !empty($order) && isset($order['status']) ? $order['status'] : 'pending' ?>">
        
        <!-- Debug: Show what data we have -->
        <script>
        console.log('Modal Form Debug - Order data:', <?= json_encode($order ?? null) ?>);
        console.log('Modal Form Debug - Status value will be:', '<?= isset($order) && !empty($order) && isset($order['status']) ? $order['status'] : 'pending' ?>');
        </script>

        <!-- Additional Information Section -->
        <div class="form-section">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="instructions" class="form-label"><?= lang('App.instructions') ?></label>
                        <textarea class="form-control" id="instructions" name="instructions" rows="3" placeholder="<?= lang('App.enter_special_instructions') ?>"><?= isset($order) && $order ? esc($order['instructions']) : '' ?></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3" style="display: none;">
                    <label for="notes" class="form-label"><?= lang('App.notes') ?></label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="<?= lang('App.enter_internal_notes') ?>"><?= isset($order) && $order ? esc($order['notes']) : '' ?></textarea>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?= lang('App.cancel') ?></button>
    <button type="submit" form="orderForm" class="btn btn-primary">
        <?php if(isset($order) && $order): ?>
            <i class="mdi mdi-content-save me-1"></i><?= lang('App.update_order') ?>
        <?php else: ?>
            <i class="mdi mdi-plus me-1"></i><?= lang('App.create_order') ?>
        <?php endif; ?>
    </button>
</div>

<!-- COMPLETELY ISOLATED MODAL SCRIPT -->
<script>
// Use a unique namespace to prevent any conflicts with global scripts
window.OrderModalHandler = (function() {
    'use strict';
    
    let initialized = false;
    
    function init() {
        if (initialized) {
            console.log('OrderModal: Already initialized, skipping...');
            return;
    }
    
        console.log('OrderModal: Starting initialization...');
        
        // Wait for elements
        const checkAndInit = (attempts = 0) => {
            const clientSelect = document.getElementById('client_id');
            const orderForm = document.getElementById('orderForm');
            
            if (!clientSelect || !orderForm) {
                if (attempts < 50) {
                    setTimeout(() => checkAndInit(attempts + 1), 100);
                } else {
                    console.error('OrderModal: Required elements not found after 5 seconds');
                }
                return;
            }
            
            console.log('OrderModal: Elements found, setting up...');
            setupClientListener();
            setupFormSubmission();
            setupDateTimeValidations();
            loadInitialData();
            initialized = true;
            console.log('OrderModal: Initialization complete');
            
            // Final safety check after all initialization is complete
            setTimeout(() => {
                forceEnableFieldsInEditMode();
                checkOrderStatusAndSetReadonly();
                console.log('OrderModal: Final safety check for field enablement completed');
            }, 300);
        };
        
        checkAndInit();
    }
    
    function setupClientListener() {
        const clientSelect = document.getElementById('client_id');
        if (!clientSelect) {
            console.error('OrderModal: Client select not found');
            return;
        }
        
        // Clone to remove existing listeners
        const newClientSelect = clientSelect.cloneNode(true);
        clientSelect.parentNode.replaceChild(newClientSelect, clientSelect);
        
        newClientSelect.addEventListener('change', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const clientId = e.target.value;
            console.log('OrderModal: Client changed to:', clientId);
            
            filterContactsAndServices(clientId);
        });
        
        console.log('OrderModal: Client listener attached');
    }
    
    function filterContactsAndServices(clientId) {
        const contactSelect = document.getElementById('contact_id');
        const serviceSelect = document.getElementById('service_id');
        
        if (!contactSelect || !serviceSelect) {
            console.error('OrderModal: Dropdown elements not found');
            return;
        }

        // Store current values BEFORE clearing
        const currentContactId = contactSelect.value;
        const currentServiceId = serviceSelect.value;
        console.log('OrderModal: Storing values before filter - Contact:', currentContactId, 'Service:', currentServiceId);

        // Clear dropdowns
        contactSelect.innerHTML = '<option value=""><?= lang('App.select_contact') ?></option>';
        serviceSelect.innerHTML = '<option value=""><?= lang('App.select') ?> <?= lang('App.service') ?></option>';
        
        if (!clientId) {
            console.log('OrderModal: No client selected, disabling dropdowns');
            // Disable the fields when no client is selected
            contactSelect.disabled = true;
            serviceSelect.disabled = true;
            return;
        }

        console.log('OrderModal: Loading data for client:', clientId);
        
        // Enable the fields when a client is selected
        contactSelect.disabled = false;
        serviceSelect.disabled = false;
        
        // Load contacts and services with preserved values
        loadContacts(clientId, currentContactId);
        loadServices(clientId, currentServiceId);
    }
    
    function loadContacts(clientId, currentContactId) {
            const url = `<?= base_url('sales_orders/getContactsForClient') ?>/${clientId}`;
        console.log('OrderModal: Loading contacts from:', url);
        console.log('OrderModal: Will restore salesperson ID:', currentContactId);

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
            console.log('OrderModal: Contacts response status:', response.status);
                if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
            console.log('OrderModal: Contacts data:', data);

            const contactSelect = document.getElementById('contact_id');
                    if (data.success && data.contacts && Array.isArray(data.contacts)) {
                // Clear and rebuild options
                contactSelect.innerHTML = '<option value=""><?= lang('App.select_contact') ?></option>';

                data.contacts.forEach(contact => {
                    const option = document.createElement('option');
                    option.value = contact.id;
                    option.textContent = contact.name;
                    contactSelect.appendChild(option);
                });
                
                // Restore the original selection if it exists in the new options
                if (currentContactId) {
                    contactSelect.value = currentContactId;
                    console.log('OrderModal: Restored salesperson selection:', currentContactId);
                } else {
                    console.log('OrderModal: No salesperson ID to restore');
                }
                
                console.log(`OrderModal: Added ${data.contacts.length} contacts`);
                    } else {
                console.warn('OrderModal: No contacts found');
                }
            })
            .catch(error => {
            console.error('OrderModal: Error loading contacts:', error);
        });
    }
    
    function loadServices(clientId, currentServiceId) {
        const url = `<?= base_url('sales_orders/getServicesForClient') ?>/${clientId}`;
        console.log('OrderModal: Loading services from:', url);
        console.log('OrderModal: Will restore service ID:', currentServiceId);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
                }
        })
        .then(response => {
            console.log('OrderModal: Services response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('OrderModal: Services data:', data);
            
            const serviceSelect = document.getElementById('service_id');
            if (data.success && data.services && Array.isArray(data.services)) {
                // Clear and rebuild options
                serviceSelect.innerHTML = '<option value=""><?= lang('App.select') ?> <?= lang('App.service') ?></option>';
                
                data.services.forEach(service => {
                    const option = document.createElement('option');
                    option.value = service.id;
                    option.textContent = `${service.service_name} - $${parseFloat(service.service_price).toFixed(2)}`;
                    serviceSelect.appendChild(option);
                });
                
                // Restore the original selection if it exists in the new options
                if (currentServiceId) {
                    serviceSelect.value = currentServiceId;
                    console.log('OrderModal: Restored service selection:', currentServiceId);
                } else {
                    console.log('OrderModal: No service ID to restore');
                }
                
                console.log(`OrderModal: Added ${data.services.length} services`);
        } else {
                console.warn('OrderModal: No services found');
            }
        })
        .catch(error => {
            console.error('OrderModal: Error loading services:', error);
        });
    }
    
    function setupFormSubmission() {
        const orderForm = document.getElementById('orderForm');
        if (!orderForm) {
            console.error('OrderModal: Order form not found');
            return;
        }
        
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('OrderModal: Form submitted');
            
            // Ensure status field has a value before submitting
            const statusField = document.getElementById('order_status');
            if (statusField && (!statusField.value || statusField.value.trim() === '')) {
                console.log('OrderModal: Status field is empty, setting to pending');
                statusField.value = 'pending';
            }
            
            // Force enable fields before collecting form data to ensure they are included
            forceEnableFieldsInEditMode();
            
            const formData = new FormData(orderForm);
            
            // Debug: Log all form data being sent
            console.log('OrderModal: Form data being sent:');
            for (let pair of formData.entries()) {
                console.log('  ' + pair[0] + ': ' + pair[1]);
            }
            
            const submitButton = document.querySelector('button[type="submit"][form="orderForm"]');
            
            if (submitButton) {
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="mdi mdi-spin mdi-loading me-1"></i> <?= lang('App.saving') ?>';
                submitButton.dataset.originalText = originalText;
            }
            
            fetch(orderForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('OrderModal: Response status:', response.status);
                console.log('OrderModal: Response headers:', response.headers);
                
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        console.error('OrderModal: Non-JSON response:', text);
                        throw new Error('Invalid response format');
                    });
                }
            })
            .then(data => {
                console.log('OrderModal: Form response:', data);
                
                // Handle validation errors specifically
                if (data.success === false && data.errors) {
                    console.log('OrderModal: Validation errors:', data.errors);
                    
                    // Clear previous validation messages
                    document.querySelectorAll('.is-invalid').forEach(element => {
                        element.classList.remove('is-invalid');
                    });
                    document.querySelectorAll('.invalid-feedback').forEach(element => {
                        element.remove();
                    });
                    
                    // Display validation errors
                    let errorMessages = [];
                    for (const field in data.errors) {
                        errorMessages.push(`${field}: ${data.errors[field]}`);
                        
                        // Highlight fields with errors
                        const fieldElement = document.querySelector(`[name="${field}"]`);
                        if (fieldElement) {
                            fieldElement.classList.add('is-invalid');
                            
                            // Add error message
                            let errorDiv = fieldElement.parentElement.querySelector('.invalid-feedback');
                            if (!errorDiv) {
                                errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback';
                                fieldElement.parentElement.appendChild(errorDiv);
                            }
                            errorDiv.textContent = data.errors[field];
                        }
                    }
                    
                    if (window.showToast) {
                        window.showToast('error', 'Validation failed: ' + errorMessages.join(', '));
                    }
                    
                    return;
                }
                
                // Handle duplicate orders
                if (data.has_duplicates) {
                    console.log('OrderModal: Duplicates found:', data.duplicates);
                    showDuplicateConfirmation(data.duplicates, formData, orderForm);
                    return;
                }
                
                if (data.success) {
                    if (window.showToast) {
                        window.showToast('success', data.message || '<?= lang('App.order_saved_successfully') ?>');
                    }
                    
                    // Close modal
                    const modal = document.querySelector('.modal.show');
                    if (modal) {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) bsModal.hide();
                    }
                    
                    // Refresh data
                    if (typeof refreshOrdersTable === 'function') {
                        refreshOrdersTable();
                    } else if (typeof location !== 'undefined') {
                        location.reload();
                    }
                } else {
                    if (window.showToast) {
                        window.showToast('error', data.message || '<?= lang('App.error_saving_order') ?>');
                    }
                }
            })
            .catch(error => {
                console.error('OrderModal: Error saving order:', error);
                if (window.showToast) {
                    window.showToast('error', '<?= lang('App.error_saving_order') ?>');
                }
            })
            .finally(() => {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = submitButton.dataset.originalText || '<?= lang('App.save') ?>';
                        }
                    });
                });
                
        console.log('OrderModal: Form submission handler attached');
    }
    
    function setupDateTimeValidations() {
        const dateInput = document.getElementById('order_date');
        const timeInput = document.getElementById('order_time');
        
        if (!dateInput) {
            console.error('OrderModal: Date input not found');
            return;
        }
        
        if (!timeInput) {
            console.error('OrderModal: Time input not found');
            return;
        }
        
        console.log('OrderModal: Setting up date and time validations...');
        
        // 🔍 DETECT EDIT MODE 
        const modalTitle = document.getElementById('modalTitle');
        const orderIdInput = document.querySelector('input[name="id"]');
        const clientSelect = document.getElementById('client_id');
        
        const titleText = modalTitle ? modalTitle.textContent.toLowerCase().trim() : '';
        const titleHasEdit = titleText.includes('edit') || titleText.includes('editar');
        const hasClientSelected = clientSelect ? clientSelect.value : null;
        const hasOrderId = orderIdInput && orderIdInput.value;
        const isEditMode = titleHasEdit || hasOrderId;
        
        console.log('OrderModal: Detection results:');
        console.log('  - Modal title:', titleText);
        console.log('  - Title has edit:', titleHasEdit);
        console.log('  - Has client selected:', hasClientSelected);
        console.log('  - Has order ID:', !!hasOrderId);
        console.log('  - Final edit mode:', isEditMode);
        
        // Enable salesperson and service fields if editing
        if (isEditMode) {
            const contactSelect = document.getElementById('contact_id');
            const serviceSelect = document.getElementById('service_id');
            
            if (contactSelect) {
                contactSelect.disabled = false;
                contactSelect.removeAttribute('disabled');
                console.log('OrderModal: Enabled salesperson field for editing');
            }
            if (serviceSelect) {
                serviceSelect.disabled = false;
                serviceSelect.removeAttribute('disabled');
                console.log('OrderModal: Enabled service field for editing');
            }
            
            // Call force enable function with delay to ensure fields stay enabled
            setTimeout(() => {
                forceEnableFieldsInEditMode();
            }, 50);
        }
        
        if (dateInput) {
            const currentDateValue = dateInput.value;
            console.log('OrderModal: Current date value from PHP:', currentDateValue);
            
            if (!isEditMode) {
                // NEW ORDER: Set minimum date to today
                const today = new Date().toISOString().split('T')[0];
                dateInput.setAttribute('min', today);
                
                // Only auto-fill if empty
                if (!currentDateValue) {
                    dateInput.value = today;
                    console.log('OrderModal: Set date to today for new order');
                }
            } else {
                // EDIT MODE: Remove restrictions, preserve value
                dateInput.removeAttribute('min');
                console.log('OrderModal: Edit mode - preserving date:', currentDateValue);
            }
            
            // Add change event listener
            dateInput.addEventListener('change', function(e) {
                const timeSelect = document.getElementById('order_time');
                if (timeSelect && timeSelect.value) {
                    checkCompletionTime(timeSelect.value, e.target.value);
                }
            });
        }
        
        if (timeInput) {
            const currentTimeValue = timeInput.value;
            console.log('OrderModal: Current time value from PHP:', currentTimeValue);
            
            // Replace with select dropdown
            const timeSelect = document.createElement('select');
            timeSelect.className = timeInput.className;
            timeSelect.id = timeInput.id;
            timeSelect.name = timeInput.name;
            timeSelect.required = timeInput.required;
            
            // Time options
            const timeOptions = [
                { value: '', text: '<?= lang('App.select_time') ?>' },
                { value: '08:00', text: '8:00 AM' },
                { value: '09:00', text: '9:00 AM' },
                { value: '10:00', text: '10:00 AM' },
                { value: '11:00', text: '11:00 AM' },
                { value: '12:00', text: '12:00 PM' },
                { value: '13:00', text: '1:00 PM' },
                { value: '14:00', text: '2:00 PM' },
                { value: '15:00', text: '3:00 PM' },
                { value: '16:00', text: '4:00 PM' },
                { value: '17:00', text: '5:00 PM' },
                { value: '18:00', text: '6:00 PM' }
            ];
            
            timeOptions.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option.value;
                optionElement.textContent = option.text;
                timeSelect.appendChild(optionElement);
            });
            
            // Set time value
            if (isEditMode) {
                // EDIT MODE: Always preserve the PHP value
                timeSelect.value = currentTimeValue;
                console.log('OrderModal: Edit mode - preserved time:', currentTimeValue);
            } else {
                // NEW ORDER: Auto-complete only if default value
                if (currentTimeValue === '09:00' || !currentTimeValue) {
                    const now = new Date();
                    let targetHour = now.getHours() + 2;
                    
                    if (targetHour < 8) targetHour = 8;
                    if (targetHour > 18) targetHour = 10;
                    
                    const autoTime = String(targetHour).padStart(2, '0') + ':00';
                    timeSelect.value = autoTime;
                    console.log('OrderModal: New order - auto-completed time:', autoTime);
                } else {
                    timeSelect.value = currentTimeValue;
                    console.log('OrderModal: New order - keeping PHP time:', currentTimeValue);
                }
            }
            
            // Add event listener
            timeSelect.addEventListener('change', function(e) {
                const currentDate = dateInput ? dateInput.value : null;
                checkCompletionTime(e.target.value, currentDate);
            });
            
            // Replace input with select
            timeInput.parentNode.replaceChild(timeSelect, timeInput);
        }
        
        console.log('OrderModal: Setup complete');
    }
    
    function checkCompletionTime(selectedTime, selectedDate) {
        if (!selectedTime || !selectedDate) return;
        
        const now = new Date();
        const selectedDateTime = new Date(`${selectedDate}T${selectedTime}`);
                
        // Only check if it's today
        const today = new Date().toISOString().split('T')[0];
        if (selectedDate !== today) {
            hideTimeWarning();
            return;
        }
        
        // Calculate hours difference
        const hoursDiff = (selectedDateTime - now) / (1000 * 60 * 60);
        
        console.log('OrderModal: Time check - Hours until selected time:', hoursDiff.toFixed(2));
        
        // Show warning if less than 1 hour OR if time is in the past
        if (hoursDiff < 1) {
            showTimeWarning(hoursDiff);
        } else {
            hideTimeWarning();
                }
            }

    function showTimeWarning(hoursDiff) {
        // Remove existing warning
        hideTimeWarning();
        
        const timeSelect = document.getElementById('order_time');
        if (!timeSelect) return;
        
        // Find the time field container (the column div)
        const timeContainer = timeSelect.closest('.col-md-6');
        if (!timeContainer) return;
        
        // Determine the appropriate message based on time difference
        let alertMessage = '';
        if (hoursDiff < 0) {
            // Time is in the past
            alertMessage = `
                <i class="mdi mdi-clock-alert-outline me-2"></i>
                <strong><?= lang('App.warning') ?>:</strong><br>
                <?= lang('App.past_time_warning') ?>
            `;
    } else {
            // Time is less than 2 hours in the future
            alertMessage = `
                <i class="mdi mdi-clock-alert-outline me-2"></i>
                <strong><?= lang('App.insufficient_time_alert') ?>:</strong><br>
                <?= lang('App.short_completion_time_message') ?>
            `;
        }
        
        // Create warning element
        const warningDiv = document.createElement('div');
        warningDiv.id = 'time-warning';
        warningDiv.className = 'alert alert-warning mt-2 mb-0';
        warningDiv.innerHTML = alertMessage;
        
        // Insert at the end of the time container (below the field)
        timeContainer.appendChild(warningDiv);
        
        console.log('OrderModal: Time warning displayed');
    }
    
    function hideTimeWarning() {
        const existingWarning = document.getElementById('time-warning');
        if (existingWarning) {
            existingWarning.remove();
            console.log('OrderModal: Time warning hidden');
        }
    }
    
    function loadInitialData() {
        const clientSelect = document.getElementById('client_id');
        const modalTitle = document.getElementById('modalTitle');
        const titleText = modalTitle ? modalTitle.textContent.toLowerCase() : '';
        const titleHasEdit = titleText.includes('edit') || titleText.includes('editar');
        const orderIdInput = document.querySelector('input[name="id"]');
        const hasOrderId = orderIdInput && orderIdInput.value;
        const isEditMode = titleHasEdit || hasOrderId;
        
        console.log('OrderModal: loadInitialData - Edit mode:', isEditMode);
        
        // Debug current field values
        const contactSelect = document.getElementById('contact_id');
        const serviceSelect = document.getElementById('service_id');
        
        console.log('OrderModal: Current field values:');
        console.log('  - Client ID:', clientSelect ? clientSelect.value : 'not found');
        console.log('  - Salesperson ID:', contactSelect ? contactSelect.value : 'not found');
        console.log('  - Service ID:', serviceSelect ? serviceSelect.value : 'not found');
        console.log('  - Order ID input:', hasOrderId ? orderIdInput.value : 'not found');
        
        if (clientSelect && clientSelect.value) {
            console.log('OrderModal: Client already selected:', clientSelect.value);
            
            // Enable fields and load data
            if (contactSelect) {
                contactSelect.disabled = false;
                contactSelect.removeAttribute('disabled');
                console.log('OrderModal: Enabled salesperson field');
            }
            if (serviceSelect) {
                serviceSelect.disabled = false;
                serviceSelect.removeAttribute('disabled');
                console.log('OrderModal: Enabled service field');
            }
            
            filterContactsAndServices(clientSelect.value);
        } else if (isEditMode) {
            // In edit mode, even without client selected, enable the fields
            console.log('OrderModal: Edit mode - enabling fields without client');
            
            if (contactSelect) {
                contactSelect.disabled = false;
                contactSelect.removeAttribute('disabled');
                console.log('OrderModal: Enabled salesperson field for edit');
                console.log('OrderModal: Salesperson has', contactSelect.options.length, 'options');
            }
            if (serviceSelect) {
                serviceSelect.disabled = false;
                serviceSelect.removeAttribute('disabled');
                console.log('OrderModal: Enabled service field for edit');
                console.log('OrderModal: Service has', serviceSelect.options.length, 'options');
            }
            
            // If we have a client but somehow the filterContactsAndServices wasn't called
            if (clientSelect && clientSelect.value) {
                console.log('OrderModal: Edit mode with client - calling filter');
                filterContactsAndServices(clientSelect.value);
            }
        }
        
        // Initialize VIN decoding functionality
        setupVINDecoding();
        
        // Initialize duplicate validation
        setupDuplicateValidation();
        
        // Force enable fields in edit mode (additional safety check)
        if (isEditMode) {
            setTimeout(() => {
                forceEnableFieldsInEditMode();
            }, 100);
        }
        
        // Configurar formulario después de cargar los datos
        setupFormSubmission();
        
        // Forzar habilitación de campos en modo edición con múltiples intentos
        forceEnableFieldsInEditMode();
        setTimeout(() => forceEnableFieldsInEditMode(), 100);
        setTimeout(() => forceEnableFieldsInEditMode(), 300);
        setTimeout(() => forceEnableFieldsInEditMode(), 500);
        setTimeout(() => forceEnableFieldsInEditMode(), 1000);
        
        console.log('OrderModal: loadInitialData completed');
    }
    
    // Force enable salesperson and service fields in edit mode
    function forceEnableFieldsInEditMode() {
        console.log('OrderModal: forceEnableFieldsInEditMode - Starting force enable check');
        
        const orderIdField = document.querySelector('input[name="id"]');
        const isEditMode = orderIdField && orderIdField.value && orderIdField.value.trim() !== '';
        
        console.log('OrderModal: forceEnableFieldsInEditMode - Order ID field:', orderIdField);
        console.log('OrderModal: forceEnableFieldsInEditMode - Order ID value:', orderIdField ? orderIdField.value : 'null');
        console.log('OrderModal: forceEnableFieldsInEditMode - Is edit mode:', isEditMode);
        
        if (!isEditMode) {
            console.log('OrderModal: forceEnableFieldsInEditMode - Not in edit mode, skipping');
            return;
        }
        
        console.log('OrderModal: forceEnableFieldsInEditMode - Confirmed edit mode, enabling fields');
        
        const contactSelect = document.getElementById('contact_id');
        const serviceSelect = document.getElementById('service_id');
        
        console.log('OrderModal: forceEnableFieldsInEditMode - Salesperson element:', contactSelect);
        console.log('OrderModal: forceEnableFieldsInEditMode - Service element:', serviceSelect);
        
        if (contactSelect) {
            console.log('OrderModal: forceEnableFieldsInEditMode - Salesperson disabled before:', contactSelect.disabled);
            console.log('OrderModal: forceEnableFieldsInEditMode - Salesperson hasAttribute disabled before:', contactSelect.hasAttribute('disabled'));
            
            contactSelect.disabled = false;
            contactSelect.removeAttribute('disabled');
            
            // Force enable any Choose.js select if it exists
            if (window.salespersonChoices) {
                try {
                    window.salespersonChoices.enable();
                    console.log('OrderModal: forceEnableFieldsInEditMode - Salesperson Choices enabled');
                } catch (e) {
                    console.warn('OrderModal: forceEnableFieldsInEditMode - Error enabling salesperson Choices:', e);
                }
            }
            
            console.log('OrderModal: forceEnableFieldsInEditMode - Salesperson disabled after:', contactSelect.disabled);
            console.log('OrderModal: forceEnableFieldsInEditMode - Salesperson hasAttribute disabled after:', contactSelect.hasAttribute('disabled'));
            console.log('OrderModal: forceEnableFieldsInEditMode - Salesperson field enabled');
        } else {
            console.warn('OrderModal: forceEnableFieldsInEditMode - Salesperson field not found');
        }
        
        if (serviceSelect) {
            console.log('OrderModal: forceEnableFieldsInEditMode - Service disabled before:', serviceSelect.disabled);
            console.log('OrderModal: forceEnableFieldsInEditMode - Service hasAttribute disabled before:', serviceSelect.hasAttribute('disabled'));
            
            serviceSelect.disabled = false;
            serviceSelect.removeAttribute('disabled');
            
            // Force enable any Choose.js select if it exists
            if (window.serviceChoices) {
                try {
                    window.serviceChoices.enable();
                    console.log('OrderModal: forceEnableFieldsInEditMode - Service Choices enabled');
                } catch (e) {
                    console.warn('OrderModal: forceEnableFieldsInEditMode - Error enabling service Choices:', e);
                }
            }
            
            console.log('OrderModal: forceEnableFieldsInEditMode - Service disabled after:', serviceSelect.disabled);
            console.log('OrderModal: forceEnableFieldsInEditMode - Service hasAttribute disabled after:', serviceSelect.hasAttribute('disabled'));
            console.log('OrderModal: forceEnableFieldsInEditMode - Service field enabled');
        } else {
            console.warn('OrderModal: forceEnableFieldsInEditMode - Service field not found');
        }
        
        // Double-check by setting a brief delay and checking again
        setTimeout(() => {
            if (contactSelect && contactSelect.disabled) {
                console.warn('OrderModal: forceEnableFieldsInEditMode - Salesperson still disabled, forcing again');
                contactSelect.disabled = false;
                contactSelect.removeAttribute('disabled');
                if (window.salespersonChoices) {
                    try {
                        window.salespersonChoices.enable();
                    } catch (e) {
                        console.warn('OrderModal: forceEnableFieldsInEditMode - Error re-enabling salesperson Choices:', e);
                    }
                }
            }
            if (serviceSelect && serviceSelect.disabled) {
                console.warn('OrderModal: forceEnableFieldsInEditMode - Service still disabled, forcing again');
                serviceSelect.disabled = false;
                serviceSelect.removeAttribute('disabled');
                if (window.serviceChoices) {
                    try {
                        window.serviceChoices.enable();
                    } catch (e) {
                        console.warn('OrderModal: forceEnableFieldsInEditMode - Error re-enabling service Choices:', e);
                    }
                }
            }
        }, 200);
    }
    
    function checkOrderStatusAndSetReadonly() {
        console.log('OrderModal: checkOrderStatusAndSetReadonly - Starting status check');
        
        const statusField = document.getElementById('order_status');
        if (!statusField) {
            console.log('OrderModal: checkOrderStatusAndSetReadonly - Status field not found');
            return;
        }
        
        const status = statusField.value;
        const isCompletedOrCancelled = status === 'completed' || status === 'cancelled';
        
        console.log('OrderModal: checkOrderStatusAndSetReadonly - Status field found with value:', status, 'Is completed/cancelled:', isCompletedOrCancelled);
        console.log('OrderModal: checkOrderStatusAndSetReadonly - Status field element:', statusField);
        
        if (isCompletedOrCancelled) {
            // Get all form fields except the hidden status field
            const formFields = document.querySelectorAll('#orderForm input:not([type="hidden"]), #orderForm select, #orderForm textarea');
            
            console.log('OrderModal: checkOrderStatusAndSetReadonly - Found', formFields.length, 'fields to disable');
            
            formFields.forEach((field, index) => {
                console.log(`OrderModal: checkOrderStatusAndSetReadonly - Disabling field ${index + 1}:`, field.name || field.id, field.tagName);
                field.readOnly = true;
                field.disabled = true;
                field.style.backgroundColor = '#f8f9fa';
                field.style.cursor = 'not-allowed';
                field.style.opacity = '0.7';
            });
            
            // Also disable the submit button
            const submitBtn = document.querySelector('button[type="submit"][form="orderForm"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="mdi mdi-lock me-1"></i><?= lang('App.order_locked') ?>';
                submitBtn.style.backgroundColor = '#6c757d';
                submitBtn.style.borderColor = '#6c757d';
                submitBtn.style.cursor = 'not-allowed';
                console.log('OrderModal: checkOrderStatusAndSetReadonly - Submit button disabled');
            }
            
            // Disable Choices.js instances if they exist
            if (window.salespersonChoices) {
                window.salespersonChoices.disable();
                console.log('OrderModal: checkOrderStatusAndSetReadonly - Salesperson Choices disabled');
            }
            if (window.serviceChoices) {
                window.serviceChoices.disable();
                console.log('OrderModal: checkOrderStatusAndSetReadonly - Service Choices disabled');
            }
            
            console.log('OrderModal: checkOrderStatusAndSetReadonly - Order is completed/cancelled - all fields set to readonly');
        } else {
            console.log('OrderModal: checkOrderStatusAndSetReadonly - Order is not completed/cancelled, fields remain editable');
        }
    }
    
    // VIN Decoder translations - Global scope for SalesOrderModal
    const modalVinTranslations = {
        loading: '<?= lang('App.vin_loading') ?>',
        onlyAlphanumeric: '<?= lang('App.vin_only_alphanumeric') ?>',
        cannotExceed17: '<?= lang('App.vin_cannot_exceed_17') ?>',
        invalidFormat: '<?= lang('App.vin_invalid_format') ?>',
        validNoInfo: '<?= lang('App.vin_valid_no_info') ?>',
        decodedNoData: '<?= lang('App.vin_decoded_no_data') ?>',
        unableToDecode: '<?= lang('App.vin_unable_to_decode') ?>',
        decodingFailed: '<?= lang('App.vin_decoding_failed') ?>',
        partial: '<?= lang('App.vin_partial') ?>',
        characters: '<?= lang('App.vin_characters') ?>',
        suspiciousPatterns: '<?= lang('App.vin_suspicious_patterns') ?>',
        invalidCheckDigit: '<?= lang('App.vin_invalid_check_digit') ?>'
    };
    
    function setupVINDecoding() {
        const vinInput = document.getElementById('vin');
        const vehicleInput = document.getElementById('vehicle');
        const vinStatus = document.getElementById('vin-status');
        
        if (!vinInput || !vehicleInput) {
            console.log('OrderModal: VIN or Vehicle input not found, skipping VIN decoding setup');
            return;
        }
        
        console.log('OrderModal: Setting up VIN decoding functionality');
        
        // Add VIN input event listener with integrated duplicate validation
        vinInput.addEventListener('input', function(e) {
            const vin = e.target.value.toUpperCase().trim();
            
            // Update input value to uppercase
            e.target.value = vin;
            
            // Clear previous status and duplicate warnings
            clearVINStatus();
            hideDuplicateWarning('vin');
            
            // Only validate alphanumeric characters
            const validVin = vin.replace(/[^A-Z0-9]/g, '');
            if (validVin !== vin) {
                e.target.value = validVin;
                showModalVINStatus('warning', modalVinTranslations.onlyAlphanumeric);
                return;
            }
            
            // Clear vehicle field when VIN is modified and shorter than 17 characters
            if (vin.length < 17) {
                clearModalVehicleField();
            }
            
            // Check VIN length
            if (vin.length === 17) {
                // Valid 17-character VIN - attempt decoding
                showModalVINStatus('loading', modalVinTranslations.loading);
                decodeVIN(vin);
                // Check for duplicates for complete VINs
                checkForDuplicates('vin', vin);
            } else if (vin.length >= 10 && vin.length < 17) {
                // Partial VIN with some basic info
                const basicInfo = decodeVINBasic(vin + '0'.repeat(17 - vin.length));
                if (basicInfo.year || basicInfo.make) {
                    const partialInfo = [basicInfo.year, basicInfo.make].filter(Boolean).join(' ');
                    showModalVINStatus('info', `${vin.length}/17 ${modalVinTranslations.characters} - ${partialInfo} (${modalVinTranslations.partial})`);
                } else {
                    showModalVINStatus('info', `${vin.length}/17 ${modalVinTranslations.characters}`);
                }
            } else if (vin.length > 0 && vin.length < 10) {
                showModalVINStatus('info', `${vin.length}/17 ${modalVinTranslations.characters}`);
            } else if (vin.length > 17) {
                // Too long
                e.target.value = vin.substring(0, 17);
                showModalVINStatus('error', modalVinTranslations.cannotExceed17);
            }
        });
        
        // Add VIN validation styles
        addModalVINStyles();
        
        console.log('OrderModal: VIN decoding setup complete');
    }
    
    function decodeVIN(vin) {
        console.log('OrderModal: Decoding VIN with NHTSA API:', vin);
        
        // Advanced VIN validation
        const validationResult = isValidVIN(vin);
        if (!validationResult.isValid) {
            let errorMessage;
            switch (validationResult.error) {
                case 'suspicious_patterns':
                    errorMessage = modalVinTranslations.suspiciousPatterns;
                    break;
                case 'invalid_check_digit':
                    errorMessage = modalVinTranslations.invalidCheckDigit;
                    break;
                case 'invalid_format':
                    errorMessage = modalVinTranslations.invalidFormat;
                    break;
                default:
                    errorMessage = modalVinTranslations.invalidFormat;
            }
            showModalVINStatus('error', errorMessage);
            return;
        }
        
        // Show loading status
        showModalVINStatus('loading', modalVinTranslations.loading);
        
        // Call NHTSA vPIC API
        const nhtsa_url = `https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/${vin}?format=json`;
        
        fetch(nhtsa_url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('OrderModal: NHTSA API response status:', response.status);
            if (!response.ok) {
                throw new Error(`NHTSA API Error: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('OrderModal: NHTSA API response data:', data);
            
            if (data && data.Results && data.Results.length > 0) {
                const vehicleData = data.Results[0];
                console.log('OrderModal: Vehicle data from NHTSA:', vehicleData);
                
                // Build comprehensive vehicle string
                const vehicleString = buildVehicleString(vehicleData);
                
                if (vehicleString && vehicleString.trim() !== '') {
                    // Update vehicle field
                    const vehicleInput = document.getElementById('vehicle');
                    if (vehicleInput) {
                        vehicleInput.value = vehicleString;
                        vehicleInput.classList.add('vin-decoded');
                        
                        // Add temporary visual indicator to vehicle field
                        vehicleInput.style.backgroundColor = '#d1e7dd';
                        vehicleInput.style.borderColor = '#198754';
                        
                        // Clear VIN status after successful decoding
                        setTimeout(() => {
                            clearVINStatus();
                            // Reset vehicle field styling
                            vehicleInput.style.backgroundColor = '';
                            vehicleInput.style.borderColor = '';
                        }, 2000);
                        
                        console.log('OrderModal: Vehicle field updated with NHTSA data:', vehicleString);
                    } else {
                        console.error('OrderModal: Vehicle input field not found');
                    }
                } else {
                    // No vehicle info found
                    showModalVINStatus('warning', modalVinTranslations.validNoInfo);
                    console.log('OrderModal: No vehicle information found in NHTSA response');
                }
            } else {
                console.warn('OrderModal: No results found in NHTSA response');
                showModalVINStatus('warning', modalVinTranslations.decodedNoData);
            }
        })
        .catch(error => {
            console.error('OrderModal: NHTSA API error:', error);
            
            // Fallback to basic decoding if NHTSA API fails
            console.log('OrderModal: Falling back to basic VIN decoding');
            showModalVINStatus('loading', modalVinTranslations.loading);
            
            try {
                const basicInfo = decodeVINBasic(vin);
                
                if (basicInfo.year || basicInfo.make) {
                    const vehicleParts = [];
                    if (basicInfo.year) vehicleParts.push(basicInfo.year);
                    if (basicInfo.make) vehicleParts.push(basicInfo.make);
                    
                    const vehicleString = vehicleParts.join(' ');
                    
                    const vehicleInput = document.getElementById('vehicle');
                    if (vehicleInput) {
                        vehicleInput.value = vehicleString;
                        vehicleInput.classList.add('vin-decoded');
                        
                        // Add temporary visual indicator to vehicle field (fallback)
                        vehicleInput.style.backgroundColor = '#fff3cd';
                        vehicleInput.style.borderColor = '#fd7e14';
                        
                        setTimeout(() => {
                            clearVINStatus();
                            // Reset vehicle field styling
                            vehicleInput.style.backgroundColor = '';
                            vehicleInput.style.borderColor = '';
                        }, 2000);
                        console.log('OrderModal: Fallback decoding successful:', vehicleString);
                    }
                } else {
                    showModalVINStatus('error', modalVinTranslations.unableToDecode);
                }
            } catch (fallbackError) {
                console.error('OrderModal: Fallback decoding also failed:', fallbackError);
                showModalVINStatus('error', modalVinTranslations.decodingFailed);
            }
        });
    }
    
    function buildVehicleString(nhtsa_data) {
        // Build simplified vehicle string from NHTSA data
        // Target format: "2017 ACURA MDX (Tech) (6 cyl)"
        
        const parts = [];
        
        // 1. Model Year
        if (nhtsa_data.ModelYear && nhtsa_data.ModelYear !== '') {
            parts.push(nhtsa_data.ModelYear);
        }
        
        // 2. Make (Manufacturer)
        if (nhtsa_data.Make && nhtsa_data.Make !== '') {
            parts.push(nhtsa_data.Make.toUpperCase());
        }
        
        // 3. Model
        if (nhtsa_data.Model && nhtsa_data.Model !== '') {
            parts.push(nhtsa_data.Model.toUpperCase());
        }
        
        // 4. Series/Trim in parentheses
        if (nhtsa_data.Series && nhtsa_data.Series !== '') {
            parts.push(`(${nhtsa_data.Series})`);
        } else if (nhtsa_data.Trim && nhtsa_data.Trim !== '') {
            parts.push(`(${nhtsa_data.Trim})`);
        }
        
        // 5. Engine cylinders in parentheses
        if (nhtsa_data.EngineNumberOfCylinders && nhtsa_data.EngineNumberOfCylinders !== '') {
            parts.push(`(${nhtsa_data.EngineNumberOfCylinders} cyl)`);
        }
        
        const result = parts.join(' ').trim();
        
        console.log('OrderModal: Built simplified vehicle string from NHTSA data:', {
            'ModelYear': nhtsa_data.ModelYear,
            'Make': nhtsa_data.Make,
            'Model': nhtsa_data.Model,
            'Series': nhtsa_data.Series,
            'Trim': nhtsa_data.Trim,
            'EngineNumberOfCylinders': nhtsa_data.EngineNumberOfCylinders,
            'Final String': result
        });
        
        return result;
    }
    
    function isValidVIN(vin) {
        // VIN must be 17 characters, alphanumeric, no I, O, or Q
        if (vin.length !== 17) return false;
        if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) return false;
        
        // Check for suspicious patterns
        const suspiciousPatternResult = checkSuspiciousPatterns(vin);
        if (!suspiciousPatternResult.isValid) {
            return { isValid: false, error: suspiciousPatternResult.error };
        }
        
        // Validate check digit (9th position, index 8)
        const checkDigitResult = validateVINCheckDigit(vin);
        if (!checkDigitResult.isValid) {
            return { isValid: false, error: checkDigitResult.error };
        }
        
        return { isValid: true };
    }
    
    function checkSuspiciousPatterns(vin) {
        // Check for consecutive identical characters (4 or more)
        const consecutiveRegex = /(.)\1{3,}/;
        if (consecutiveRegex.test(vin)) {
            return { isValid: false, error: 'suspicious_patterns' };
        }
        
        // Check for excessive character repetition (more than 4 occurrences)
        const charCount = {};
        for (let char of vin) {
            charCount[char] = (charCount[char] || 0) + 1;
            if (charCount[char] > 4) {
                return { isValid: false, error: 'suspicious_patterns' };
            }
        }
        
        return { isValid: true };
    }
    
    function validateVINCheckDigit(vin) {
        // ISO 3779 VIN check digit validation
        const weights = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];
        const charValues = {
            'A': 1, 'B': 2, 'C': 3, 'D': 4, 'E': 5, 'F': 6, 'G': 7, 'H': 8,
            'J': 1, 'K': 2, 'L': 3, 'M': 4, 'N': 5, 'P': 7, 'R': 9,
            'S': 2, 'T': 3, 'U': 4, 'V': 5, 'W': 6, 'X': 7, 'Y': 8, 'Z': 9,
            '0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9
        };
        
        let sum = 0;
        for (let i = 0; i < 17; i++) {
            if (i === 8) continue; // Skip check digit position
            const char = vin.charAt(i);
            const value = charValues[char];
            if (value === undefined) {
                return { isValid: false, error: 'invalid_format' };
            }
            sum += value * weights[i];
        }
        
        const calculatedCheckDigit = sum % 11;
        const actualCheckDigit = vin.charAt(8);
        const expectedCheckDigit = calculatedCheckDigit === 10 ? 'X' : calculatedCheckDigit.toString();
        
        if (actualCheckDigit !== expectedCheckDigit) {
            return { isValid: false, error: 'invalid_check_digit' };
        }
        
        return { isValid: true };
    }
    
    function decodeVINBasic(vin) {
        // Basic VIN decoding - extracts year, make, and some model info
        // This is a simplified decoder - for production, consider using a VIN API service
        
        const vinInfo = {
            year: null,
            make: null,
            model: null,
            trim: null
        };
        
        try {
            // Decode year (10th character)
            const yearCode = vin.charAt(9);
            vinInfo.year = decodeYearFromVIN(yearCode);
            
            // Decode manufacturer (World Manufacturer Identifier - first 3 characters)
            const wmi = vin.substring(0, 3);
            vinInfo.make = decodeMakeFromWMI(wmi);
            
            // For more detailed decoding, we would need extensive VIN databases
            // This is a basic implementation
            
        } catch (error) {
            console.error('Basic VIN decoding error:', error);
        }
        
        return vinInfo;
    }
    
    function decodeYearFromVIN(yearCode) {
        const yearCodes = {
            'A': 1980, 'B': 1981, 'C': 1982, 'D': 1983, 'E': 1984, 'F': 1985, 'G': 1986, 'H': 1987,
            'J': 1988, 'K': 1989, 'L': 1990, 'M': 1991, 'N': 1992, 'P': 1993, 'R': 1994, 'S': 1995,
            'T': 1996, 'V': 1997, 'W': 1998, 'X': 1999, 'Y': 2000, '1': 2001, '2': 2002, '3': 2003,
            '4': 2004, '5': 2005, '6': 2006, '7': 2007, '8': 2008, '9': 2009, 'A': 2010, 'B': 2011,
            'C': 2012, 'D': 2013, 'E': 2014, 'F': 2015, 'G': 2016, 'H': 2017, 'J': 2018, 'K': 2019,
            'L': 2020, 'M': 2021, 'N': 2022, 'P': 2023, 'R': 2024, 'S': 2025, 'T': 2026, 'V': 2027,
            'W': 2028, 'X': 2029, 'Y': 2030
        };
        
        return yearCodes[yearCode] || null;
    }
    
    function decodeMakeFromWMI(wmi) {
        const wmiCodes = {
            // US Manufacturers
            '1G1': 'Chevrolet', '1G6': 'Cadillac', '1GC': 'Chevrolet', '1GT': 'GMC',
            '1FA': 'Ford', '1FB': 'Ford', '1FC': 'Ford', '1FD': 'Ford', '1FE': 'Ford', '1FF': 'Ford',
            '1FG': 'Ford', '1FH': 'Ford', '1FJ': 'Ford', '1FK': 'Ford', '1FL': 'Ford', '1FM': 'Ford',
            '1FN': 'Ford', '1FP': 'Ford', '1FR': 'Ford', '1FS': 'Ford', '1FT': 'Ford', '1FU': 'Ford',
            '1FV': 'Ford', '1FW': 'Ford', '1FX': 'Ford', '1FY': 'Ford', '1FZ': 'Ford',
            '1HD': 'Harley-Davidson', '1HG': 'Honda', '1J4': 'Jeep', '1J8': 'Jeep',
            '1L1': 'Lincoln', '1LN': 'Lincoln', '1ME': 'Mercury', '1MH': 'Mercury',
            '1N4': 'Nissan', '1N6': 'Nissan', '1VW': 'Volkswagen',
            '2C3': 'Chrysler', '2C4': 'Chrysler', '2D4': 'Dodge', '2D8': 'Dodge',
            '2FA': 'Ford', '2FB': 'Ford', '2FC': 'Ford', '2FD': 'Ford', '2FE': 'Ford',
            '2G1': 'Chevrolet', '2G4': 'Pontiac', '2HG': 'Honda', '2HK': 'Honda', '2HM': 'Hyundai',
            '2T1': 'Toyota', '2T2': 'Toyota', '2T3': 'Toyota',
            '3FA': 'Ford', '3FE': 'Ford', '3G1': 'Chevrolet', '3G3': 'Oldsmobile', '3G4': 'Buick',
            '3G5': 'Buick', '3G6': 'Pontiac', '3G7': 'Pontiac', '3GN': 'Chevrolet',
            '3H1': 'Honda', '3HG': 'Honda', '3HM': 'Honda', '3N1': 'Nissan', '3VW': 'Volkswagen',
            '4F2': 'Ford', '4F4': 'Mazda', '4M2': 'Mercury', '4S3': 'Subaru', '4S4': 'Subaru',
            '4T1': 'Toyota', '4T3': 'Toyota', '4US': 'BMW',
            '5F2': 'Ford', '5FN': 'Honda', '5J6': 'Honda', '5L1': 'Lincoln', '5N1': 'Nissan',
            '5NP': 'Hyundai', '5TD': 'Toyota', '5TE': 'Toyota', '5TF': 'Toyota',
            
            // German Manufacturers
            'WAU': 'Audi', 'WA1': 'Audi', 'WBA': 'BMW', 'WBS': 'BMW', 'WBX': 'BMW',
            'WDB': 'Mercedes-Benz', 'WDD': 'Mercedes-Benz', 'WDC': 'Mercedes-Benz',
            'WP0': 'Porsche', 'WP1': 'Porsche', 'WVW': 'Volkswagen', 'WV1': 'Volkswagen', 'WV2': 'Volkswagen',
            
            // Japanese Manufacturers
            'JH4': 'Acura', 'JHM': 'Honda', 'JF1': 'Subaru', 'JF2': 'Subaru',
            'JM1': 'Mazda', 'JM3': 'Mazda', 'JN1': 'Nissan', 'JN6': 'Nissan', 'JN8': 'Nissan',
            'JT2': 'Toyota', 'JT3': 'Toyota', 'JT4': 'Toyota', 'JT6': 'Toyota', 'JT8': 'Toyota',
            'JTD': 'Toyota', 'JTE': 'Toyota', 'JTF': 'Toyota', 'JTG': 'Toyota', 'JTH': 'Lexus',
            'JTJ': 'Lexus', 'JTK': 'Lexus', 'JTL': 'Lexus', 'JTM': 'Lexus', 'JTN': 'Lexus',
            
            // Korean Manufacturers
            'KMH': 'Hyundai', 'KMJ': 'Hyundai', 'KNA': 'Kia', 'KNB': 'Kia', 'KNC': 'Kia', 'KND': 'Kia',
            'KNE': 'Kia', 'KNF': 'Kia', 'KNG': 'Kia', 'KNH': 'Kia', 'KNJ': 'Kia', 'KNK': 'Kia',
            'KNL': 'Kia', 'KNM': 'Kia',
            
            // Other
            'SAL': 'Land Rover', 'SAJ': 'Jaguar', 'SCC': 'Lotus',
            'VF1': 'Renault', 'VF3': 'Peugeot', 'VF7': 'Citroën',
            'YK1': 'Saab', 'YS3': 'Saab', 'YV1': 'Volvo', 'YV4': 'Volvo'
        };
        
        return wmiCodes[wmi] || null;
    }
    
    function showModalVINStatus(type, message) {
        const vinStatus = document.getElementById('vin-status');
        const vinInput = document.getElementById('vin');
        
        // Clear previous status
        clearVINStatus();
        
        // For critical errors, show toast notification
        if (type === 'error') {
            showModalVINToast('error', message);
            
            // Also update input styling for visual feedback
            if (vinInput) {
                vinInput.classList.add('vin-error');
                // Clear error styling after 3 seconds
                setTimeout(() => {
                    vinInput.classList.remove('vin-error');
                }, 3000);
            }
            return;
        }
        
        // For warnings about API issues, show toast
        if (type === 'warning' && (message.includes('VIN valid but no vehicle info') || message.includes('VIN decoded but no vehicle data'))) {
            showModalVINToast('warning', message);
            return;
        }
        
        // For info messages (character counter) and loading, show inline
        if (vinStatus && (type === 'info' || type === 'loading')) {
        vinStatus.textContent = message;
        vinStatus.className = `vin-status vin-status-${type}`;
        
        // Update input styling
        if (vinInput) {
            vinInput.classList.remove('vin-decoding', 'vin-success', 'vin-error', 'vin-warning');
            
            if (type === 'loading') {
                vinInput.classList.add('vin-decoding');
            }
        }
        
            // Auto-hide info messages
            if (type === 'info') {
            setTimeout(() => {
                clearVINStatus();
                }, 2000);
            }
        }
    }
    
    function showModalVINToast(type, message) {
        // Use global toast system if available
        if (typeof window.showToast === 'function') {
            window.showToast(message, type, { duration: 4000 });
        } else if (typeof showToast === 'function') {
            showToast(type, message);
        } else if (typeof Toastify !== 'undefined') {
            // Fallback to simple Toastify
            const colors = {
                success: '#28a745',
                error: '#dc3545', 
                warning: '#fd7e14',
                info: '#17a2b8'
            };
            
            Toastify({
                text: message,
                duration: 4000,
                gravity: 'top',
                position: 'right',
                backgroundColor: colors[type] || colors.info,
                close: true,
                stopOnFocus: true,
                className: 'vin-toast-modal'
            }).showToast();
        } else {
            // Final fallback
            console.log(`Modal VIN ${type.toUpperCase()}: ${message}`);
        }
    }
    
    // Legacy function name support
    function showVINStatus(type, message) {
        showModalVINStatus(type, message);
    }
    
    function clearVINStatus() {
        const vinStatus = document.getElementById('vin-status');
        const vinInput = document.getElementById('vin');
        
        if (vinStatus) {
            vinStatus.textContent = '';
            vinStatus.className = 'vin-status';
        }
        
        if (vinInput) {
            vinInput.classList.remove('vin-decoding', 'vin-success', 'vin-error', 'vin-warning');
        }
    }
    
    function clearModalVehicleField() {
        const vehicleInput = document.getElementById('vehicle');
        
        if (vehicleInput) {
            // Only clear if the field was previously auto-filled by VIN decoder
            if (vehicleInput.classList.contains('vin-decoded')) {
                vehicleInput.value = '';
                vehicleInput.classList.remove('vin-decoded');
                vehicleInput.style.backgroundColor = '';
                vehicleInput.style.borderColor = '';
                
                console.log('SalesOrderModal: Vehicle field cleared due to VIN modification');
            }
        }
    }
    
    function addModalVINStyles() {
        // Check if styles already exist
        if (document.getElementById('vin-decoding-styles')) {
            return;
        }
        
        const style = document.createElement('style');
        style.id = 'vin-decoding-styles';
        style.textContent = `
            .vin-input-container {
                position: relative;
            }
            
            .vin-status {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 0.75rem;
                font-weight: 500;
                pointer-events: none;
                z-index: 10;
            }
            
            .vin-status-loading {
                color: #6c757d;
            }
            
            .vin-status-success {
                color: #198754;
            }
            
            .vin-status-error {
                color: #dc3545;
            }
            
            .vin-status-warning {
                color: #fd7e14;
            }
            
            .vin-status-info {
                color: #0dcaf0;
            }
            
            .vin-decoding {
                background-color: #f1f8ff !important;
                border-color: #007bff !important;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15) !important;
                position: relative;
            }
            
            .vin-success {
                background-color: #d4edda !important;
                border-color: #28a745 !important;
                box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
            }
            
            .vin-error {
                background-color: #f8d7da !important;
                border-color: #dc3545 !important;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15) !important;
                animation: vinErrorPulse 0.5s ease-in-out;
            }
            
            .vin-warning {
                background-color: #fff3cd !important;
                border-color: #fd7e14 !important;
                box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.15) !important;
            }
            
            .vin-decoded {
                background-color: #d4edda !important;
                border-color: #28a745 !important;
                box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
                animation: vinDecodeSuccess 0.5s ease-out;
            }
            
            @keyframes vinDecodeSuccess {
                0% { transform: scale(1); }
                50% { transform: scale(1.02); }
                100% { transform: scale(1); }
            }
            
            @keyframes vinErrorPulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.02); }
                100% { transform: scale(1); }
            }
            
            .vin-decoding::after {
                content: "";
                position: absolute;
                right: 35px;
                top: 50%;
                transform: translateY(-50%);
                width: 16px;
                height: 16px;
                border: 2px solid #007bff;
                border-top: 2px solid transparent;
                border-radius: 50%;
                animation: vinSpin 1s linear infinite;
            }
            
            @keyframes vinSpin {
                0% { transform: translateY(-50%) rotate(0deg); }
                100% { transform: translateY(-50%) rotate(360deg); }
            }
            
            /* VIN Toast Styling */
            .vin-toast-modal {
                font-family: inherit !important;
                font-size: 14px !important;
                border-radius: 8px !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
                border: 1px solid rgba(255, 255, 255, 0.2) !important;
            }
            
            .vin-toast-modal .toastify-content {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 12px 16px;
            }
            
            .vin-toast-modal .toastify-content::before {
                content: '⚠️';
                font-size: 16px;
                line-height: 1;
            }
            
            .vin-toast-modal[style*="#dc3545"] .toastify-content::before {
                content: '❌';
            }
            
            .vin-toast-modal[style*="#28a745"] .toastify-content::before {
                content: '✅';
            }
            
            .vin-toast-modal[style*="#fd7e14"] .toastify-content::before {
                content: '⚠️';
            }
        `;
        
        document.head.appendChild(style);
    }
    
    // Duplicate Order Validation Functions
    function setupDuplicateValidation() {
        const stockInput = document.getElementById('stock');
        
        // Setup dynamic validation for stock field
        if (stockInput) {
            stockInput.addEventListener('input', function() {
                const stockValue = this.value.trim();
                // Clear existing warnings immediately
                hideDuplicateWarning('stock');
                
                if (stockValue) {
                    // Check for duplicates with debounce
                    checkForDuplicates('stock', stockValue);
                }
            });
        }
        
        // VIN duplicate validation is now integrated into setupVINDecoding()
        // for real-time validation during VIN input
    }
    
    function checkForDuplicates(field, value) {
        if (!value) {
            hideDuplicateWarning(field);
            return;
        }

        const orderIdInput = document.querySelector('input[name="id"]');
        const currentOrderId = orderIdInput ? orderIdInput.value : null;
        
        // Clear any existing timeout for this field to debounce requests
        if (window.salesOrderDuplicateTimeout) {
            clearTimeout(window.salesOrderDuplicateTimeout);
        }

        // Debounce the duplicate check to avoid too many requests
        window.salesOrderDuplicateTimeout = setTimeout(() => {
        const formData = new FormData();
        formData.append(field, value);
        if (currentOrderId) {
            formData.append('current_order_id', currentOrderId);
        }
        
        fetch('<?= base_url('sales_orders/checkDuplicateOrder') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.has_duplicates) {
                showDuplicateWarning(field, data.duplicates);
            } else {
                hideDuplicateWarning(field);
            }
        })
        .catch(error => {
            console.error('OrderModal: Error checking duplicates:', error);
                hideDuplicateWarning(field);
        });
        }, 500); // 500ms debounce delay
    }
    
    function showDuplicateWarning(field, duplicates) {
        const inputField = document.getElementById(field);
        if (!inputField) return;
        
        // Remove existing warning
        hideDuplicateWarning(field);
        
        const fieldContainer = inputField.closest('.col-md-6');
        if (!fieldContainer) return;
        
        let duplicateInfo = '';
        if (duplicates[field]) {
            const orders = duplicates[field].orders;
            const count = duplicates[field].count;
            
            // Create enhanced duplicate info with order status
            const ordersList = orders.map(order => {
                const status = order.status ? 
                    order.status.charAt(0).toUpperCase() + order.status.slice(1) : 
                    'Unknown';
                return `Order #${order.id} (${status})`;
            }).join(', ');
            
            const countText = count === 1 ? 
                '<?= addslashes(lang('App.time')) ?>' : 
                '<?= addslashes(lang('App.times')) ?>';
                
            duplicateInfo = `${count} ${countText}: ${ordersList}`;
        }
        
        const warningDiv = document.createElement('div');
        warningDiv.id = `${field}-duplicate-warning`;
        warningDiv.className = 'alert alert-warning mt-2 mb-0';
        
        const warningMessage = field === 'stock' ? '<?= addslashes(lang('App.duplicate_stock_warning')) ?>' : '<?= addslashes(lang('App.duplicate_vin_warning')) ?>';
        
        warningDiv.innerHTML = `
            <i class="mdi mdi-alert me-2"></i>
            <strong>${warningMessage}:</strong><br>
            <small>${duplicateInfo}</small>
        `;
        
        fieldContainer.appendChild(warningDiv);
        
        // Add styling to input
        inputField.style.borderColor = '#fd7e14';
        inputField.style.backgroundColor = '#fff3cd';
    }
    
    function hideDuplicateWarning(field) {
        const warningElement = document.getElementById(`${field}-duplicate-warning`);
        if (warningElement) {
            warningElement.remove();
        }
        
        const inputField = document.getElementById(field);
        if (inputField) {
            inputField.style.borderColor = '';
            inputField.style.backgroundColor = '';
        }
    }
    
    function showDuplicateConfirmation(duplicates, formData, orderForm) {
        let duplicateHtml = '<div class="duplicate-orders-list">';
        let totalDuplicates = 0;
        
        // Count total duplicates for title
        Object.keys(duplicates).forEach(field => {
            totalDuplicates += duplicates[field].count;
        });
        
        // Build duplicate orders information
        Object.keys(duplicates).forEach(field => {
            const duplicate = duplicates[field];
            
            const fieldLabel = field === 'stock' ? 
                '<?= addslashes(lang('App.stock_number_exists')) ?>' : 
                '<?= addslashes(lang('App.vin_exists')) ?>';
            
            duplicateHtml += `
                <div class="mb-3">
                    <h6 class="text-warning">
                        <i class="mdi mdi-alert me-1"></i>
                        ${fieldLabel.replace('{0}', duplicate.value)} (${duplicate.count} ${duplicate.count === 1 ? '<?= addslashes(lang('App.time')) ?>' : '<?= addslashes(lang('App.times')) ?>'})
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th><?= addslashes(lang('App.order_id')) ?></th>
                                    <th><?= addslashes(lang('App.completion_date')) ?></th>
                                    <th><?= addslashes(lang('App.date')) ?></th>
                                    <th><?= addslashes(lang('App.status')) ?></th>
                                    <th><?= addslashes(lang('App.actions')) ?></th>
                                </tr>
                            </thead>
                            <tbody>`;
            
            duplicate.orders.forEach(order => {
                // Format completion_date in mm-dd-yyyy format
                let completionDate;
                if (order.completion_date) {
                    const date = new Date(order.completion_date);
                    const month = (date.getMonth() + 1).toString().padStart(2, '0');
                    const day = date.getDate().toString().padStart(2, '0');
                    const year = date.getFullYear();
                    completionDate = `${month}-${day}-${year}`;
                } else {
                    completionDate = '<?= addslashes(lang('App.not_completed')) ?>';
                }
                
                // Format order date in mm-dd-yyyy format
                let orderDate = 'N/A';
                if (order.date) {
                    const date = new Date(order.date);
                    const month = (date.getMonth() + 1).toString().padStart(2, '0');
                    const day = date.getDate().toString().padStart(2, '0');
                    const year = date.getFullYear();
                    orderDate = `${month}-${day}-${year}`;
                }
                    
                duplicateHtml += `
                    <tr>
                        <td>#${order.id}</td>
                        <td>${completionDate}</td>
                        <td>${orderDate}</td>
                        <td><span class="badge bg-${getStatusBadgeClass(order.status)}">${order.status || 'N/A'}</span></td>
                        <td>
                            <a href="<?= base_url('sales_orders/view/') ?>${order.id}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="mdi mdi-eye me-1"></i><?= addslashes(lang('App.view_existing_order')) ?>
                            </a>
                        </td>
                    </tr>`;
            });
            
            duplicateHtml += `
                            </tbody>
                        </table>
                    </div>
                </div>`;
        });
        
        duplicateHtml += '</div>';
        
        // Show SweetAlert confirmation with count in title
        if (window.Swal) {
            const titleText = totalDuplicates === 1 ? 
                '<?= addslashes(lang('App.duplicate_order_detected_single')) ?>' : 
                '<?= addslashes(lang('App.duplicate_order_detected_multiple')) ?>'.replace('{0}', totalDuplicates);
                
            Swal.fire({
                title: titleText,
                html: `
                    <div class="text-start">
                        ${duplicateHtml}
                        <hr>
                        <p class="text-center mb-0"><?= addslashes(lang('App.are_you_sure_continue')) ?></p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fd7e14',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<?= addslashes(lang('App.yes_create_duplicate')) ?>',
                cancelButtonText: '<?= addslashes(lang('App.no_go_back')) ?>',
                customClass: {
                    popup: 'duplicate-confirmation-modal'
                },
                width: '800px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, force save
                    formData.append('force_save', 'true');
                    submitOrderWithDuplicates(formData, orderForm);
                } else {
                    // Re-enable submit button
                    resetSubmitButton();
                }
            });
        }
    }
    
    function getStatusBadgeClass(status) {
        const statusClasses = {
            'pending': 'warning',
            'processing': 'info',
            'in_progress': 'primary',
            'completed': 'success',
            'cancelled': 'danger'
        };
        return statusClasses[status] || 'secondary';
    }
    
    function submitOrderWithDuplicates(formData, orderForm) {
        const submitButton = document.querySelector('button[type="submit"][form="orderForm"]');
        
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="mdi mdi-spin mdi-loading me-1"></i> <?= lang('App.saving') ?>';
        }
        
        fetch(orderForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (window.showToast) {
                    window.showToast('success', data.message || '<?= lang('App.order_saved_successfully') ?>');
                }
                
                // Close modal
                const modal = document.querySelector('.modal.show');
                if (modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                }
                
                // Refresh data
                if (typeof refreshOrdersTable === 'function') {
                    refreshOrdersTable();
                } else if (typeof location !== 'undefined') {
                    location.reload();
                }
            } else {
                if (window.showToast) {
                    window.showToast('error', data.message || '<?= lang('App.error_saving_order') ?>');
                }
                resetSubmitButton();
            }
        })
        .catch(error => {
            console.error('OrderModal: Error saving order:', error);
            if (window.showToast) {
                window.showToast('error', '<?= lang('App.error_saving_order') ?>');
            }
            resetSubmitButton();
        });
    }
    
    function resetSubmitButton() {
        const submitButton = document.querySelector('button[type="submit"][form="orderForm"]');
        if (submitButton && submitButton.dataset.originalText) {
            submitButton.disabled = false;
            submitButton.innerHTML = submitButton.dataset.originalText;
        }
    }
    
    // Public API
    return {
        init: init
    };
    
})();

// Initialize the modal handler
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => window.OrderModalHandler.init(), 100);
    });
} else {
    setTimeout(() => window.OrderModalHandler.init(), 100);
}

// Also initialize when modal is shown
document.addEventListener('shown.bs.modal', function(e) {
    if (e.target.querySelector('#orderForm')) {
        console.log('OrderModal: Bootstrap modal shown, reinitializing...');
        setTimeout(() => window.OrderModalHandler.init(), 100);
    }
});
</script>

<!-- VIN Barcode Scanner Modal -->
<div id="vinScannerModal" class="scanner-modal">
    <div class="scanner-header">
        <h5 class="mb-0">
            <i class="ri-qr-scan-line me-2" id="scanner-icon"></i>
            <span id="scanner-title"><?= lang('App.scan_vin_barcode') ?></span>
        </h5>
        <div class="scanner-controls">
            <button type="button" id="modeSwitchBtn" class="btn btn-sm btn-outline-primary me-2" title="Switch scanning mode">
                <i class="ri-qr-scan-2-line"></i> QR
            </button>
        <button type="button" class="btn-close" id="scannerCloseBtn"></button>
        </div>
    </div>
    <div class="scanner-container">
        <div id="scanner-target"></div>
        <div class="scanner-overlay"></div>
        <div class="scanner-instructions">
            <i class="ri-qr-code-line me-2"></i>
            <?= lang('App.point_camera_to_vin_barcode') ?>
        </div>
    </div>
</div>

<!-- Include QuaggaJS Library with multiple fallbacks -->
<script>
// Try multiple CDN sources for QuaggaJS
(function loadQuaggaJS() {
    const cdnSources = [
        'https://unpkg.com/quagga@0.12.1/dist/quagga.min.js',
        'https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js'
    ];
    
    let currentIndex = 0;
    
    function tryLoad() {
        if (currentIndex >= cdnSources.length) {
            console.error('📷 All QuaggaJS CDN sources failed');
            window.QuaggaLoadFailed = true;
            return;
        }
        
        const script = document.createElement('script');
        script.src = cdnSources[currentIndex];
        
        script.onload = function() {
            console.log('📷 QuaggaJS loaded from:', cdnSources[currentIndex]);
            console.log('📷 Quagga object:', typeof window.Quagga);
            window.QuaggaLoaded = true;
            
            // Load Tesseract.js for OCR capabilities
            loadTesseract();
        };
        
                 script.onerror = function() {
             console.warn('📷 Failed to load from:', cdnSources[currentIndex]);
             currentIndex++;
             setTimeout(tryLoad, 200); // Small delay before trying next source
         };
        
        document.head.appendChild(script);
    }
    
    tryLoad();
})();

// Load Tesseract.js for OCR functionality
function loadTesseract() {
    console.log('📱 Loading Tesseract.js for OCR...');
    
    const tesseractSources = [
        'https://unpkg.com/tesseract.js@5/dist/tesseract.min.js',
        'https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js'
    ];
    
    let tesseractIndex = 0;
    
    function tryLoadTesseract() {
        if (tesseractIndex >= tesseractSources.length) {
            console.warn('📱 All Tesseract CDN sources failed, OCR will not be available');
            window.TesseractLoadFailed = true;
            loadQRScanner(); // Load QR scanner after Tesseract attempt
            return;
        }
        
        const script = document.createElement('script');
        script.src = tesseractSources[tesseractIndex];
        
        script.onload = function() {
            console.log('📱 Tesseract.js loaded from:', tesseractSources[tesseractIndex]);
            window.TesseractLoaded = true;
            loadQRScanner(); // Load QR scanner after Tesseract
        };
        
        script.onerror = function() {
            console.warn('📱 Failed to load Tesseract from:', tesseractSources[tesseractIndex]);
            tesseractIndex++;
            setTimeout(tryLoadTesseract, 200);
        };
        
        document.head.appendChild(script);
    }
    
    tryLoadTesseract();
}

// Load jsQR library for QR code scanning
function loadQRScanner() {
    console.log('📱 Loading jsQR for QR code support...');
    
    const qrSources = [
        'https://unpkg.com/jsqr@1.4.0/dist/jsQR.js',
        'https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js'
    ];
    
    let qrIndex = 0;
    
    function tryLoadQR() {
        if (qrIndex >= qrSources.length) {
            console.warn('📱 All QR Scanner CDN sources failed, QR codes will not be available');
            window.QRScannerLoadFailed = true;
            return;
        }
        
        const script = document.createElement('script');
        script.src = qrSources[qrIndex];
        
        script.onload = function() {
            console.log('📱 jsQR loaded from:', qrSources[qrIndex]);
            window.QRScannerLoaded = true;
        };
        
        script.onerror = function() {
            console.warn('📱 Failed to load jsQR from:', qrSources[qrIndex]);
            qrIndex++;
            setTimeout(tryLoadQR, 200);
        };
        
        document.head.appendChild(script);
    }
    
    tryLoadQR();
}
</script>

<script>
// VIN Barcode Scanner Implementation
window.VinBarcodeScanner = (function() {
    let isScanning = false;
    let scannerModal = null;
    let currentMode = 'barcode'; // 'barcode', 'qr', or 'ocr'
    let ocrWorker = null;
    let qrScanner = null;
    
    // Check if device is mobile/tablet
    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
               (navigator.maxTouchPoints && navigator.maxTouchPoints > 2 && /MacIntel/.test(navigator.platform));
    }
    
    // Check camera permission status
    function checkCameraPermission() {
        if (!navigator.permissions) {
            return Promise.resolve('prompt'); // Unknown, try anyway
        }
        
        return navigator.permissions.query({ name: 'camera' })
            .then(permissionStatus => {
                return permissionStatus.state; // 'granted', 'denied', or 'prompt'
            })
            .catch(() => {
                return 'prompt'; // Fallback if permission query fails
            });
    }
    
    // Reset scan button to original state
    function resetScanButton() {
        const scanBtn = document.getElementById('scanVinBtn');
        if (scanBtn) {
            scanBtn.disabled = false;
            scanBtn.innerHTML = '<i class="ri-qr-scan-line me-1"></i><span class="scan-btn-text"><?= lang('App.scan') ?></span>';
        }
    }
    
    // Initialize mobile detection and show scan button if mobile
    function initMobileDetection() {
        const scanBtn = document.getElementById('scanVinBtn');
        const closeBtn = document.getElementById('scannerCloseBtn');
        
        if (scanBtn && isMobileDevice()) {
            scanBtn.classList.remove('d-none');
            
            // Add event listener for scan button
            scanBtn.addEventListener('click', function() {
                // Disable button and show loading
                const originalText = scanBtn.innerHTML;
                scanBtn.disabled = true;
                scanBtn.innerHTML = '<i class="ri-loader-line spin me-1"></i>Loading...';
                
                // Check permission status first
                checkCameraPermission().then(permissionState => {
                    if (permissionState === 'denied') {
                        // Reset button
                        scanBtn.disabled = false;
                        scanBtn.innerHTML = originalText;
                        showError('Camera permission is denied. Please go to your browser settings and allow camera access for this site.', true);
                        return;
                    }
                    
                startScannerWithPermission();
                }).catch((error) => {
                    console.error('Camera permission check error:', error);
                    // Reset button on error
                    scanBtn.disabled = false;
                    scanBtn.innerHTML = originalText;
                });
            });
        }
        
        // Add event listener for close button
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                stopScanner();
            });
        }
        
        // Add event listener for mode switching
        const modeSwitchBtn = document.getElementById('modeSwitchBtn');
        if (modeSwitchBtn) {
            modeSwitchBtn.addEventListener('click', function() {
                switchScanMode();
            });
        }
    }
    
    // Switch between barcode, QR, and OCR modes
    function switchScanMode() {
        if (!isScanning) return;
        
        const modeSwitchBtn = document.getElementById('modeSwitchBtn');
        const scannerIcon = document.getElementById('scanner-icon');
        const scannerTitle = document.getElementById('scanner-title');
        const instructions = document.querySelector('.scanner-instructions');
        
        // Stop current mode
        stopCurrentMode();
        
        // Cycle through modes: barcode -> qr -> ocr -> barcode
        switch (currentMode) {
            case 'barcode':
                currentMode = 'qr';
                if (modeSwitchBtn) {
                    modeSwitchBtn.innerHTML = '<i class="ri-text-line"></i> OCR';
                    modeSwitchBtn.title = 'Switch to text recognition';
                }
                if (scannerIcon) scannerIcon.className = 'ri-qr-scan-2-line me-2';
                if (scannerTitle) scannerTitle.textContent = 'Scan QR Code';
                if (instructions) {
                    instructions.innerHTML = '<i class="ri-qr-scan-2-line me-2"></i>Position camera over QR code';
                }
                startQRMode();
                break;
                
            case 'qr':
                currentMode = 'ocr';
                if (modeSwitchBtn) {
                    modeSwitchBtn.innerHTML = '<i class="ri-qr-scan-line"></i> Barcode';
                    modeSwitchBtn.title = 'Switch to barcode scanning';
                }
                if (scannerIcon) scannerIcon.className = 'ri-text-line me-2';
                if (scannerTitle) scannerTitle.textContent = 'Read VIN Text';
                if (instructions) {
                    instructions.innerHTML = '<i class="ri-text-line me-2"></i>Position camera over VIN text';
                }
                startOCRMode();
                break;
                
            case 'ocr':
            default:
                currentMode = 'barcode';
                if (modeSwitchBtn) {
                    modeSwitchBtn.innerHTML = '<i class="ri-qr-scan-2-line"></i> QR';
                    modeSwitchBtn.title = 'Switch to QR code scanning';
                }
                if (scannerIcon) scannerIcon.className = 'ri-qr-scan-line me-2';
                if (scannerTitle) scannerTitle.textContent = '<?= lang('App.scan_vin_barcode') ?>';
                if (instructions) {
                    instructions.innerHTML = '<i class="ri-qr-code-line me-2"></i><?= lang('App.point_camera_to_vin_barcode') ?>';
                }
                initializeQuagga();
                break;
        }
    }
    
    // Stop current scanning mode
    function stopCurrentMode() {
        // Stop barcode scanner
        if (typeof window.Quagga !== 'undefined') {
            window.Quagga.stop();
            window.Quagga.offDetected();
            window.Quagga.offProcessed();
        }
        
        // Stop QR scanner
        if (qrScanner && qrScanner.stream) {
            const tracks = qrScanner.stream.getTracks();
            tracks.forEach(track => track.stop());
            qrScanner = null;
        }
        
        // Stop OCR mode
        stopOCRMode();
        
        // Clear scanner target
        const scannerTarget = document.querySelector('#scanner-target');
        if (scannerTarget) {
            scannerTarget.innerHTML = '';
        }
    }
    
    // Check camera permissions and start scanner
    function startScannerWithPermission() {
        
        // Check if running in secure context (HTTPS or localhost)
        if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
            resetScanButton();
            showError('Camera access requires HTTPS connection');
            return;
        }
        
        // Check for browser support
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            resetScanButton();
            showError('<?= lang('App.camera_not_supported') ?>');
            return;
        }
        
        console.log('📷 Starting scanner directly...');
        console.log('📷 Mobile device detected:', isMobileDevice());
        console.log('📷 Current mode:', currentMode);
        // Start scanner directly to avoid camera conflicts
        startScanner();
    }
    
    // Start the VIN scanner
    function startScanner() {
        console.log('📷 startScanner() called, isScanning:', isScanning);
        
        if (isScanning) return;
        
        scannerModal = document.getElementById('vinScannerModal');
        if (!scannerModal) {
            console.error('📷 Scanner modal not found');
            resetScanButton();
            showError('Scanner interface not found');
            return;
        }
        
        console.log('📷 Scanner modal found, showing...');
        
        // Reset scan button
        resetScanButton();
        
        scannerModal.style.display = 'flex';
        isScanning = true;
        
        // Check if QuaggaJS loading failed
        if (window.QuaggaLoadFailed) {
            console.error('📷 QuaggaJS loading failed - all CDN sources failed');
            resetScanButton();
            showError('Barcode scanner library could not load from any source. Please check your internet connection and try again.');
            stopScanner();
            return;
        }
        
        // Initialize Quagga with better configuration
        if (typeof window.Quagga === 'undefined' && !window.QuaggaLoaded) {
            console.log('📷 Quagga not loaded yet, waiting...');
            // Wait for Quagga to load (up to 5 seconds)
            let attempts = 0;
            const maxAttempts = 50; // 5 seconds with 100ms intervals
            
            const checkQuagga = setInterval(() => {
                attempts++;
                console.log('📷 Checking for Quagga... attempt', attempts);
                
                if (window.QuaggaLoadFailed) {
                    console.error('📷 QuaggaJS loading failed during wait');
                    clearInterval(checkQuagga);
                    resetScanButton();
                    showError('Barcode scanner library could not load. Please check your internet connection and try again.');
                    stopScanner();
                    return;
                }
                
                if (typeof window.Quagga !== 'undefined' || window.QuaggaLoaded) {
                    console.log('📷 Quagga loaded after waiting');
                    clearInterval(checkQuagga);
                    initializeQuagga();
                    return;
                }
                
                if (attempts >= maxAttempts) {
                    console.error('📷 Quagga failed to load after 5 seconds');
                    clearInterval(checkQuagga);
                    resetScanButton();
                    showError('Barcode scanner library failed to load. Please refresh the page and try again.');
                    stopScanner();
                }
            }, 100);
            
            return;
        }
        
        console.log('📷 Quagga already loaded, initializing...');
        initializeQuagga();
    }
    
    // Start OCR mode for text recognition
    function startOCRMode() {
        console.log('📱 Starting OCR mode...');
        
        if (window.TesseractLoadFailed) {
            console.warn('📱 Tesseract failed to load, OCR not available');
            if (window.showToast) {
                window.showToast('warning', 'OCR library not available. Using barcode mode only.');
            }
            // Fall back to barcode mode
            toggleScanMode();
            return;
        }
        
        if (typeof Tesseract === 'undefined') {
            console.log('📱 Tesseract not loaded yet, waiting...');
            // Wait for Tesseract to load
            let attempts = 0;
            const maxAttempts = 30;
            
            const checkTesseract = setInterval(() => {
                attempts++;
                console.log('📱 Checking for Tesseract... attempt', attempts);
                
                if (typeof Tesseract !== 'undefined') {
                    console.log('📱 Tesseract loaded after waiting');
                    clearInterval(checkTesseract);
                    initializeOCR();
                    return;
                }
                
                if (attempts >= maxAttempts) {
                    console.error('📱 Tesseract failed to load after waiting');
                    clearInterval(checkTesseract);
                    if (window.showToast) {
                        window.showToast('error', 'OCR library failed to load. Please try barcode mode.');
                    }
                    toggleScanMode(); // Fall back to barcode mode
                }
            }, 200);
            
            return;
        }
        
        initializeOCR();
    }
    
    // Start QR code scanning mode
    function startQRMode() {
        console.log('📱 Starting QR mode...');
        
        if (window.QRScannerLoadFailed) {
            console.warn('📱 jsQR failed to load');
            if (window.showToast) {
                window.showToast('warning', 'QR Scanner library not available. Switching to next mode.');
            }
            // Fall back to next mode
            switchScanMode();
            return;
        }
        
        if (typeof jsQR === 'undefined') {
            console.log('📱 jsQR not loaded yet, waiting...');
            // Wait for jsQR to load
            let attempts = 0;
            const maxAttempts = 30;
            
            const checkQRScanner = setInterval(() => {
                attempts++;
                console.log('📱 Checking for jsQR... attempt', attempts);
                
                if (typeof jsQR !== 'undefined') {
                    console.log('📱 jsQR loaded after waiting');
                    clearInterval(checkQRScanner);
                    initializeQRScanner();
                    return;
                }
                
                if (attempts >= maxAttempts) {
                    console.error('📱 jsQR failed to load after waiting');
                    clearInterval(checkQRScanner);
                    if (window.showToast) {
                        window.showToast('error', 'QR Scanner library failed to load. Please try another mode.');
                    }
                    switchScanMode(); // Fall back to next mode
                }
            }, 200);
            
            return;
        }
        
        initializeQRScanner();
    }
    
    // Initialize QR Scanner using jsQR
    function initializeQRScanner() {
        console.log('📱 Initializing jsQR Scanner...');
        
        const scannerTarget = document.querySelector('#scanner-target');
        if (!scannerTarget) return;
        
        // Create video element for QR scanning
        const video = document.createElement('video');
        video.setAttribute('playsinline', '');
        video.setAttribute('autoplay', '');
        video.setAttribute('muted', '');
        video.style.width = '100%';
        video.style.height = '100%';
        video.style.objectFit = 'cover';
        
        // Create canvas for QR processing
        const canvas = document.createElement('canvas');
        canvas.style.display = 'none';
        const ctx = canvas.getContext('2d');
        
        scannerTarget.innerHTML = '';
        scannerTarget.appendChild(video);
        scannerTarget.appendChild(canvas);
        
        // Get camera stream
        navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: { ideal: "environment" },
                width: { ideal: 1280, min: 640 },
                height: { ideal: 720, min: 480 }
            }
        })
        .then(function(stream) {
            video.srcObject = stream;
            video.play();
            
            // Store stream reference for cleanup
            qrScanner = { stream: stream, video: video };
            
            // Start QR processing
            startQRProcessing(video, canvas, ctx);
        })
        .catch(function(err) {
            console.error('📱 QR camera access failed:', err);
            showError('Failed to access camera for QR scanning: ' + err.message);
            stopScanner();
        });
    }
    
    // Process QR codes using jsQR
    function startQRProcessing(video, canvas, ctx) {
        console.log('📱 Starting QR processing with jsQR...');
        
        let isProcessingQR = false;
        let lastQRTime = 0;
        
        function processQRFrame() {
            if (!isScanning || currentMode !== 'qr') return;
            
            const currentTime = Date.now();
            
            // Process every 500ms to be responsive but not overwhelming
            if (isProcessingQR || (currentTime - lastQRTime < 500)) {
                setTimeout(processQRFrame, 100);
                return;
            }
            
            isProcessingQR = true;
            lastQRTime = currentTime;
            
            // Set canvas size to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            if (canvas.width === 0 || canvas.height === 0) {
                isProcessingQR = false;
                setTimeout(processQRFrame, 100);
                return;
            }
            
            // Draw video frame to canvas
            ctx.drawImage(video, 0, 0);
            
            // Get image data for jsQR
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            
            // Scan for QR code
            const qrCode = jsQR(imageData.data, imageData.width, imageData.height);
            
            if (qrCode) {
                console.log('📱 QR Code detected:', qrCode.data);
                
                if (window.showToast) {
                    window.showToast('info', 'QR Code detected');
                }
                
                // Process QR data for VIN
                const qrData = qrCode.data.toUpperCase().trim();
                let vinCandidate = null;
                
                // Check if QR data is directly a VIN
                if (isValidVin(qrData)) {
                    vinCandidate = qrData;
                } else {
                    // Try to extract VIN from structured data (JSON, etc.)
                    try {
                        const jsonData = JSON.parse(qrCode.data);
                        if (jsonData.vin && isValidVin(jsonData.vin)) {
                            vinCandidate = jsonData.vin.toUpperCase();
                        } else if (jsonData.VIN && isValidVin(jsonData.VIN)) {
                            vinCandidate = jsonData.VIN.toUpperCase();
                        }
                    } catch (e) {
                        // Not JSON, try regex search
                        const vinPattern = /[A-HJ-NPR-Z0-9]{10,20}/gi;
                        const matches = qrData.match(vinPattern);
                        
                        if (matches && matches.length > 0) {
                            for (let match of matches) {
                                if (isValidVin(match)) {
                                    vinCandidate = match;
                                    break;
                                }
                            }
                        }
                    }
                }
                
                if (vinCandidate) {
                    console.log('📱 Valid VIN found in QR:', vinCandidate);
                    
                    const vinInput = document.getElementById('vin');
                    if (vinInput) {
                        vinInput.value = vinCandidate.toUpperCase();
                        const event = new Event('input', { bubbles: true });
                        vinInput.dispatchEvent(event);
                    }
                    
                    if (window.showToast) {
                        window.showToast('success', 'VIN extracted from QR: ' + vinCandidate);
                    }
                    
                    stopScanner();
                    return;
                } else {
                    console.log('📱 No valid VIN found in QR data:', qrData);
                    if (window.showToast) {
                        window.showToast('warning', 'QR code detected but no valid VIN found');
                    }
                }
            }
            
            isProcessingQR = false;
            setTimeout(processQRFrame, 100);
        }
        
        // Start processing after video is ready
        video.addEventListener('loadedmetadata', () => {
            setTimeout(processQRFrame, 500);
        });
        
        // Start immediately if video is already loaded
        if (video.readyState >= video.HAVE_METADATA) {
            setTimeout(processQRFrame, 500);
        }
    }
    
    // Initialize OCR with camera
    function initializeOCR() {
        console.log('📱 Initializing OCR...');
        
        const scannerTarget = document.querySelector('#scanner-target');
        if (!scannerTarget) return;
        
        // Create video element for OCR
        const video = document.createElement('video');
        video.setAttribute('playsinline', '');
        video.setAttribute('autoplay', '');
        video.setAttribute('muted', '');
        video.style.width = '100%';
        video.style.height = '100%';
        video.style.objectFit = 'cover';
        
        // Create canvas for capturing frames
        const canvas = document.createElement('canvas');
        canvas.style.display = 'none';
        const ctx = canvas.getContext('2d');
        
        // Create overlay canvas for visual feedback
        const overlayCanvas = document.createElement('canvas');
        overlayCanvas.style.position = 'absolute';
        overlayCanvas.style.top = '0';
        overlayCanvas.style.left = '0';
        overlayCanvas.style.width = '100%';
        overlayCanvas.style.height = '100%';
        overlayCanvas.style.pointerEvents = 'none';
        overlayCanvas.style.zIndex = '10';
        const overlayCtx = overlayCanvas.getContext('2d');
        
        scannerTarget.innerHTML = '';
        scannerTarget.appendChild(video);
        scannerTarget.appendChild(canvas);
        scannerTarget.appendChild(overlayCanvas);
        
        // Get camera stream
        navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: { ideal: "environment" },
                width: { ideal: 1280, min: 640 },
                height: { ideal: 720, min: 480 }
            }
        })
        .then(function(stream) {
            video.srcObject = stream;
            video.play();
            
            // Start OCR processing
            startOCRProcessing(video, canvas, ctx);
        })
        .catch(function(err) {
            console.error('📱 OCR camera access failed:', err);
            showError('Failed to access camera for OCR: ' + err.message);
            stopScanner();
        });
    }
    
    // Process OCR continuously
    function startOCRProcessing(video, canvas, ctx) {
        console.log('📱 Starting OCR processing...');
        
        let isProcessingOCR = false;
        let lastOCRTime = 0;
        
        function processFrame() {
            if (!isScanning || currentMode !== 'ocr') return;
            
            const currentTime = Date.now();
            
            // Process every 2 seconds to avoid overload
            if (isProcessingOCR || (currentTime - lastOCRTime < 2000)) {
                setTimeout(processFrame, 500);
                return;
            }
            
            isProcessingOCR = true;
            lastOCRTime = currentTime;
            
            // Capture frame from video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);
            
            // Convert to blob for Tesseract
            canvas.toBlob(function(blob) {
                if (!blob) {
                    isProcessingOCR = false;
                    setTimeout(processFrame, 500);
                    return;
                }
                
                console.log('📱 Processing OCR frame...');
                
                // Process with Tesseract
                Tesseract.recognize(blob, 'eng', {
                    tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
                    tessedit_pageseg_mode: Tesseract.PSM.SINGLE_LINE
                })
                .then(function(result) {
                    const text = result.data.text.trim().toUpperCase();
                    console.log('📱 OCR detected text:', text, 'Confidence:', result.data.confidence);
                    
                    if (text && text.length >= 10 && result.data.confidence > 60) {
                        // Extract potential VINs from text
                        const vinPattern = /[A-HJ-NPR-Z0-9]{10,20}/gi;
                        const matches = text.match(vinPattern);
                        
                        if (matches && matches.length > 0) {
                            const potentialVin = matches[0];
                            console.log('📱 Potential VIN found:', potentialVin);
                            
                            if (isValidVin(potentialVin)) {
                                console.log('📱 Valid VIN detected via OCR:', potentialVin);
                                
                                const vinInput = document.getElementById('vin');
                                if (vinInput) {
                                    vinInput.value = potentialVin.toUpperCase();
                                    const event = new Event('input', { bubbles: true });
                                    vinInput.dispatchEvent(event);
                                }
                                
                                if (window.showToast) {
                                    window.showToast('success', 'VIN read successfully: ' + potentialVin);
                                }
                                
                                stopScanner();
                                return;
                            }
                        }
                    }
                    
                    isProcessingOCR = false;
                    setTimeout(processFrame, 500);
                })
                .catch(function(err) {
                    console.error('📱 OCR processing error:', err);
                    isProcessingOCR = false;
                    setTimeout(processFrame, 1000);
                });
            }, 'image/jpeg', 0.8);
        }
        
        // Start processing
        setTimeout(processFrame, 1000);
    }
    
    // Stop OCR mode
    function stopOCRMode() {
        console.log('📱 Stopping OCR mode...');
        
        const scannerTarget = document.querySelector('#scanner-target');
        if (scannerTarget) {
            const video = scannerTarget.querySelector('video');
            if (video && video.srcObject) {
                const tracks = video.srcObject.getTracks();
                tracks.forEach(track => track.stop());
            }
        }
    }
    

    
        // Fallback initialization with basic camera constraints
    function tryBasicCameraInit() {
        console.log('📷 Trying basic camera initialization...');
        
        window.Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner-target'),
                constraints: {
                    video: true  // Very basic constraint
                }
            },
            decoder: {
                readers: ["code_128_reader", "code_39_reader"]
            },
            locate: true
        }, function(err) {
            if (err) {
                console.error('📷 Basic camera init also failed:', err);
                resetScanButton();
                showError('Camera could not be accessed. Please check that:\n• Camera permissions are granted\n• No other app is using the camera\n• Camera is not blocked by browser settings');
                stopScanner();
                return;
            }
            
            console.log('📷 Basic camera initialization successful');
            window.Quagga.start();
            
            // Variables for result stability
            let detectionCounts = {};
            let lastDetectionTime = 0;
            let isProcessing = false;
            
            // Add processing feedback
            window.Quagga.onProcessed(function(result) {
                var drawingCtx = window.Quagga.canvas.ctx.overlay;
                var drawingCanvas = window.Quagga.canvas.dom.overlay;
                
                if (result) {
                    // Clear previous drawings
                    drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                    
                    if (result.boxes) {
                        drawingCtx.strokeStyle = "green";
                        drawingCtx.lineWidth = 2;
                        result.boxes.filter(function (box) {
                            return box !== result.box;
                        }).forEach(function (box) {
                            window.Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
                        });
                    }
                    
                    if (result.box) {
                        drawingCtx.strokeStyle = "blue";
                        drawingCtx.lineWidth = 3;
                        window.Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "blue", lineWidth: 3});
                    }
                    
                    if (result.codeResult && result.codeResult.code) {
                        drawingCtx.strokeStyle = "red";
                        drawingCtx.lineWidth = 4;
                        window.Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 4});
                    }
                }
            });
            
            // Simple and reliable barcode detection
            window.Quagga.onDetected(function(result) {
                const code = result.codeResult.code;
                console.log('📷 Barcode detected:', code, 'Format:', result.codeResult.format);
                
                if (window.showToast) {
                    window.showToast('info', 'Code detected: ' + code);
                }
                
                // Validate VIN format - relaxed for testing
                if (isValidVin(code)) {
                    console.log('📷 Valid VIN detected:', code);
                    
                    const vinInput = document.getElementById('vin');
                    if (vinInput) {
                        vinInput.value = code.toUpperCase();
                        const event = new Event('input', { bubbles: true });
                        vinInput.dispatchEvent(event);
                    }
                    
                    if (window.showToast) {
                        window.showToast('success', 'VIN scanned successfully: ' + code);
                    }
                    
                    stopScanner();
                } else {
                    console.log('📷 Code does not match VIN format, continuing scan...', code);
                    if (window.showToast) {
                        window.showToast('warning', 'Code detected but not a valid VIN: ' + code);
                    }
                }
            });
        });
    }
    
    // Separate function for Quagga initialization (simplified)
    function initializeQuagga() {
        console.log('📷 Initializing QuaggaJS (stable version)...');
        console.log('📷 Quagga object check:', typeof window.Quagga);
        
        if (typeof window.Quagga === 'undefined') {
            console.error('📷 Quagga still undefined at initialization');
            resetScanButton();
            showError('Barcode scanner not ready. Please try again.');
            stopScanner();
            return;
        }
        
        window.Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner-target'),
                constraints: {
                    width: 640,
                    height: 480,
                    facingMode: "environment"
                }
            },
            decoder: {
                readers: [
                    "code_128_reader",
                    "code_39_reader",
                    "ean_reader"
                ]
            },
            locate: true
        }, function(err) {
            if (err) {
                console.error('📷 Quagga initialization error:', err);
                console.error('📷 Error details:', {
                    name: err.name,
                    message: err.message,
                    type: err.type || 'unknown'
                });
                
                // Try fallback with simpler constraints
                if (err.message && err.message.includes('Could not start video source')) {
                    console.log('📷 Trying fallback with basic camera constraints...');
                    tryBasicCameraInit();
                    return;
                }
                
                // Provide specific error handling
                if (err.name === 'SourceUnavailableError') {
                    showError('Camera is not available. Please check camera permissions.', true);
                } else if (err.name === 'StreamApiNotSupportedError') {
                    showError('Camera streaming is not supported in this browser.');
                } else if (err.message && err.message.includes('video source')) {
                    showError('Could not access camera. Please ensure camera permissions are granted and no other app is using the camera.');
                } else {
                    showError('Camera initialization failed: ' + (err.message || err.toString()));
                }
                
                resetScanButton();
                stopScanner();
                return;
            }
            
            console.log('📷 QuaggaJS initialized successfully');
            window.Quagga.start();
            console.log('📷 QuaggaJS scanner started');
        });
        
        // Handle successful barcode detection
        window.Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            console.log('📷 Barcode detected:', code, 'Format:', result.codeResult.format);
            
            // For debugging - show all detected codes temporarily
            if (window.showToast) {
                window.showToast('info', 'Code detected: ' + code + ' (Format: ' + result.codeResult.format + ')');
            }
            
            // Validate VIN format (17 characters, alphanumeric except I, O, Q)
            if (isValidVin(code)) {
                console.log('📷 Valid VIN detected:', code);
                
                // Fill the VIN input field
                const vinInput = document.getElementById('vin');
                if (vinInput) {
                    vinInput.value = code.toUpperCase();
                    
                    // Trigger validation
                    const event = new Event('input', { bubbles: true });
                    vinInput.dispatchEvent(event);
                }
                
                // Show success message
                if (window.showToast) {
                    window.showToast('success', '<?= lang('App.vin_scanned_successfully') ?>: ' + code);
                } else {
                    alert('VIN scanned successfully: ' + code);
                }
                
                // Stop scanner
                stopScanner();
            } else {
                console.log('📷 Invalid VIN format:', code, 'Length:', code.length);
                // Continue scanning for valid VIN if invalid format
            }
        });
    }
    
    // Stop the VIN scanner
    function stopScanner() {
        if (!isScanning) return;
        
        isScanning = false;
        
        // Stop barcode scanner
        if (typeof window.Quagga !== 'undefined') {
            window.Quagga.stop();
            window.Quagga.offDetected();
            window.Quagga.offProcessed();
        }
        
        // Stop current mode
        stopCurrentMode();
        
        // Reset mode to barcode
        currentMode = 'barcode';
        
        if (scannerModal) {
            scannerModal.style.display = 'none';
        }
        
        // Clear scanner target
        const scannerTarget = document.querySelector('#scanner-target');
        if (scannerTarget) {
            scannerTarget.innerHTML = '';
        }
        
        // Reset UI elements
        const modeSwitchBtn = document.getElementById('modeSwitchBtn');
        const scannerIcon = document.getElementById('scanner-icon');
        const scannerTitle = document.getElementById('scanner-title');
        const instructions = document.querySelector('.scanner-instructions');
        
        if (modeSwitchBtn) {
            modeSwitchBtn.innerHTML = '<i class="ri-qr-scan-2-line"></i> QR';
            modeSwitchBtn.title = 'Switch to QR code scanning';
        }
        if (scannerIcon) scannerIcon.className = 'ri-qr-scan-line me-2';
        if (scannerTitle) scannerTitle.textContent = '<?= lang('App.scan_vin_barcode') ?>';
        if (instructions) {
            instructions.innerHTML = '<i class="ri-qr-code-line me-2"></i><?= lang('App.point_camera_to_vin_barcode') ?>';
        }
    }
    
    // Validate VIN format (relaxed for testing)
    function isValidVin(vin) {
        if (!vin || typeof vin !== 'string') return false;
        
        // Remove spaces and convert to uppercase
        vin = vin.replace(/\s/g, '').toUpperCase();
        
        console.log('📷 Validating VIN:', vin, 'Length:', vin.length);
        
        // For testing: accept any code with 10+ characters that looks like VIN
        if (vin.length >= 10 && vin.length <= 20) {
            // Must be alphanumeric
            if (/^[A-Z0-9]+$/.test(vin)) {
                console.log('📷 VIN validation passed (relaxed mode)');
                return true;
            }
        }
        
        // VIN must be exactly 17 characters
        if (vin.length !== 17) {
            console.log('📷 VIN rejected: wrong length');
            return false;
        }
        
        // VIN should not contain I, O, Q
        if (/[IOQ]/.test(vin)) {
            console.log('📷 VIN rejected: contains I, O, or Q');
            return false;
        }
        
        // VIN should be alphanumeric
        if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) {
            console.log('📷 VIN rejected: invalid format');
            return false;
        }
        
        console.log('📷 VIN validation passed (strict mode)');
        return true;
    }
    
    // Show error message with optional help button
    function showError(message, showHelpButton = false) {
        if (window.showToast) {
            window.showToast('error', message);
        } else {
            alert(message);
        }
        
        // If it's a permission error, show help
        if (message.includes('permission') && showHelpButton) {
            showCameraHelp();
        }
    }
    
    // Show camera permission help
    function showCameraHelp() {
        const helpMessage = `
To enable camera access:

📱 Chrome/Edge Mobile:
1. Tap the address bar
2. Tap the camera icon or "Site settings"
3. Allow Camera access

📱 Safari iOS:
1. Go to Settings > Safari > Camera
2. Set to "Allow" or "Ask"
3. Refresh the page

📱 Firefox Mobile:
1. Tap the shield icon in address bar
2. Allow Camera permission

If problems persist, try:
• Refresh the page
• Clear browser cache
• Check if other apps are using the camera
        `;
        
        if (confirm(helpMessage + '\n\nWould you like to try again?')) {
            // Try again
            setTimeout(() => {
                startScannerWithPermission();
            }, 500);
        }
    }
    
    // Public API
    return {
        init: initMobileDetection,
        start: startScanner,
        stop: stopScanner,
        isMobile: isMobileDevice
    };
})();

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        VinBarcodeScanner.init();
    });
} else {
    VinBarcodeScanner.init();
}

// Re-initialize when modal is shown
document.addEventListener('shown.bs.modal', function(e) {
    if (e.target.querySelector('#orderForm')) {
        setTimeout(() => VinBarcodeScanner.init(), 100);
    }
});

// Stop scanner when modal is hidden
document.addEventListener('hidden.bs.modal', function(e) {
    if (e.target.querySelector('#orderForm')) {
        VinBarcodeScanner.stop();
    }
});


    
    // Global debug function to check field states
    window.debugOrderModalFields = function() {
        console.log('=== ORDER MODAL FIELD DEBUG ===');
        
        const orderIdField = document.querySelector('input[name="id"]');
        const contactSelect = document.getElementById('contact_id');
        const serviceSelect = document.getElementById('service_id');
        const modalTitle = document.getElementById('modalTitle');
        
        console.log('Order ID field:', orderIdField);
        console.log('Order ID value:', orderIdField ? orderIdField.value : 'null');
        console.log('Modal title:', modalTitle ? modalTitle.textContent : 'null');
        console.log('Salesperson field:', contactSelect);
        console.log('Salesperson disabled:', contactSelect ? contactSelect.disabled : 'null');
        console.log('Salesperson hasAttribute disabled:', contactSelect ? contactSelect.hasAttribute('disabled') : 'null');
        console.log('Service field:', serviceSelect);
        console.log('Service disabled:', serviceSelect ? serviceSelect.disabled : 'null');
        console.log('Service hasAttribute disabled:', serviceSelect ? serviceSelect.hasAttribute('disabled') : 'null');
        console.log('Choices instances:', window.salespersonChoices, window.serviceChoices);
        
        console.log('=== END DEBUG ===');
    };
    
    // Auto-debug when in edit mode
    setTimeout(() => {
        const orderIdField = document.querySelector('input[name="id"]');
        if (orderIdField && orderIdField.value) {
            console.log('OrderModal: Auto-debugging edit mode fields...');
            window.debugOrderModalFields();
        }
    }, 2000);

// Global function to check and set readonly status - can be called from outside
window.checkOrderStatusAndSetReadonly = function() {
    console.log('Global: checkOrderStatusAndSetReadonly - Starting status check');
    
    const statusField = document.getElementById('order_status');
    if (!statusField) {
        console.log('Global: checkOrderStatusAndSetReadonly - Status field not found');
        return;
    }
    
    const status = statusField.value;
    const isCompletedOrCancelled = status === 'completed' || status === 'cancelled';
    
    console.log('Global: checkOrderStatusAndSetReadonly - Status:', status, 'Is completed/cancelled:', isCompletedOrCancelled);
    
    if (isCompletedOrCancelled) {
        // Get all form fields except the hidden status field
        const formFields = document.querySelectorAll('#orderForm input:not([type="hidden"]), #orderForm select, #orderForm textarea');
        
        console.log('Global: checkOrderStatusAndSetReadonly - Found', formFields.length, 'fields to disable');
        
        formFields.forEach((field, index) => {
            console.log(`Global: checkOrderStatusAndSetReadonly - Disabling field ${index + 1}:`, field.name || field.id, field.tagName);
            field.readOnly = true;
            field.disabled = true;
            field.style.backgroundColor = '#f8f9fa';
            field.style.cursor = 'not-allowed';
            field.style.opacity = '0.7';
        });
        
        // Also disable the submit button
        const submitBtn = document.querySelector('button[type="submit"][form="orderForm"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="mdi mdi-lock me-1"></i><?= lang('App.order_locked') ?>';
            submitBtn.style.backgroundColor = '#6c757d';
            submitBtn.style.borderColor = '#6c757d';
            submitBtn.style.cursor = 'not-allowed';
            console.log('Global: checkOrderStatusAndSetReadonly - Submit button disabled');
        }
        
        // Disable Choices.js instances if they exist
        if (window.salespersonChoices) {
            window.salespersonChoices.disable();
            console.log('Global: checkOrderStatusAndSetReadonly - Salesperson Choices disabled');
        }
        if (window.serviceChoices) {
            window.serviceChoices.disable();
            console.log('Global: checkOrderStatusAndSetReadonly - Service Choices disabled');
        }
        
        console.log('Global: checkOrderStatusAndSetReadonly - Order is completed/cancelled - all fields set to readonly');
    } else {
        console.log('Global: checkOrderStatusAndSetReadonly - Order is not completed/cancelled, fields remain editable');
    }
};

// Also call the function when modal content is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Listen for modal shown events
    const orderModal = document.getElementById('orderModal');
    if (orderModal) {
        orderModal.addEventListener('shown.bs.modal', function() {
            console.log('OrderModal: Modal shown, checking status after delay');
            setTimeout(() => {
                window.checkOrderStatusAndSetReadonly();
            }, 500);
        });
    }
});
    
</script>
