<!-- Custom styles for compact modal -->
<style>
.car-wash-modal .modal-dialog {
    margin: 2rem auto;
    max-width: 720px;
}

.car-wash-modal .modal-content {
    border-radius: 12px;
    border: 1px solid #e9ecef;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.car-wash-modal .modal-header {
    padding: 1.25rem 1.5rem 1rem;
    border-bottom: 1px solid #e9ecef;
    border-radius: 12px 12px 0 0;
    background-color: #f8f9fa;
    color: #495057;
}

.car-wash-modal .modal-title {
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
    color: #495057;
}

.car-wash-modal .btn-close {
    opacity: 0.7;
}

.car-wash-modal .btn-close:hover {
    opacity: 1;
}

.car-wash-modal .modal-body {
    padding: 1.5rem;
    background-color: #ffffff;
}

.car-wash-modal .form-label {
    font-weight: 500;
    font-size: 0.875rem;
    color: #495057;
    margin-bottom: 0.375rem;
}

.car-wash-modal .form-control,
.car-wash-modal .form-select {
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    background-color: white;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}



.car-wash-modal .form-control:focus,
.car-wash-modal .form-select:focus {
    border-color: #405189;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25);
    outline: none;
}

.car-wash-modal .form-control::placeholder {
    color: #6c757d;
    font-size: 0.875rem;
}

.car-wash-modal .mb-3 {
    margin-bottom: 1rem !important;
}

.car-wash-modal .row {
    margin: 0 -0.5rem;
}

.car-wash-modal .row > * {
    padding: 0 0.5rem;
}

.car-wash-modal .modal-footer {
    padding: 1rem 1.5rem 1.5rem;
    border-top: 1px solid #e9ecef;
    background-color: #f8f9fa;
    border-radius: 0 0 12px 12px;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}

.car-wash-modal .btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
}

.car-wash-modal .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.car-wash-modal .btn-secondary:hover {
    background-color: #5c636a;
    border-color: #565e64;
}

.car-wash-modal .btn-primary {
    background-color: #405189;
    border-color: #405189;
    color: white;
    min-width: 120px;
}

.car-wash-modal .btn-primary:hover {
    background-color: #364574;
    border-color: #313a65;
}

.car-wash-modal .btn-primary:disabled {
    background-color: #6c757d;
    border-color: #6c757d;
    opacity: 0.65;
}

.car-wash-modal .text-danger {
    color: #dc3545 !important;
}

.car-wash-modal .alert {
    border-radius: 6px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
}

.car-wash-modal .alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.car-wash-modal .spinner-border-sm {
    width: 1rem;
    height: 1rem;
    margin-right: 0.5rem;
}

.car-wash-modal .form-label i {
    color: #6c757d;
    width: 14px;
    text-align: center;
}

/* Status field protection */
#carwash_status {
    display: block !important;
    width: 100% !important;
    padding: 0.625rem 0.875rem !important;
    font-size: 0.875rem !important;
    border: 1px solid #ced4da !important;
    border-radius: 6px !important;
    background-color: white !important;
    appearance: auto !important;
    -webkit-appearance: auto !important;
    -moz-appearance: auto !important;
}

#carwash_status:focus {
    border-color: #405189 !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25) !important;
    outline: none !important;
}

/* Prevent any library from hiding or modifying the status field */
.car-wash-modal select[data-no-choice="true"] {
    visibility: visible !important;
    display: block !important;
    opacity: 1 !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .car-wash-modal .modal-dialog {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }
    
    .car-wash-modal .modal-body {
        padding: 1rem;
    }
    
    .car-wash-modal .modal-footer {
        padding: 0.75rem 1rem 1rem;
        flex-direction: row;
        justify-content: space-between;
    }
    
    .car-wash-modal .btn {
        flex: 1;
        margin: 0 0.25rem;
    }
}
</style>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <h5><i class="fas fa-exclamation-triangle me-2"></i>Error</h5>
        <p class="mb-0"><?= esc($error) ?></p>
    </div>
<?php endif; ?>

<form id="carWashForm" method="POST" <?php if (isset($order)): ?>data-edit-mode="true" data-order-id="<?= esc($order['id']) ?>"<?php endif; ?>>
    <?php if (isset($order) && !empty($order)): ?>
        <input type="hidden" name="id" value="<?= esc($order['id']) ?>">
    <?php endif; ?>
    <div class="row g-3">
        <!-- Client -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="client_id" class="form-label">
                    <i class="fas fa-building me-1"></i><?= lang('App.client') ?> 
                    <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="client_id" name="client_id" required>
                    <option value=""><?= lang('App.select_client') ?></option>
                    <?php if (!empty($clients) && is_array($clients)): ?>
                    <?php foreach ($clients as $client): ?>
                            <?php if (($client['status'] ?? 'active') == 'active'): // Only show active clients ?>
                                <option value="<?= esc($client['id'] ?? '') ?>" 
                                    <?= (isset($order) && $order['client_id'] == $client['id']) ? 'selected' : '' ?>>
                                    <?= esc($client['name'] ?? 'Unknown Client') ?>
                                </option>
                            <?php endif; ?>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled><?= lang('App.no_active_clients_available') ?></option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Tag/Stock -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tag_stock" class="form-label">
                    <i class="fas fa-tag me-1"></i>Tag/Stock
                </label>
                <input type="text" class="form-control" id="tag_stock" name="tag_stock" 
                       placeholder="Enter tag or stock number" 
                       value="<?= isset($order) ? esc($order['tag_stock'] ?? '') : '' ?>">
            </div>
        </div>

        <!-- VIN# -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="vin_number" class="form-label">
                    <i class="fas fa-barcode me-1"></i>VIN#
                </label>
                <div class="vin-input-container position-relative">
                    <input type="text" class="form-control" id="vin_number" name="vin_number" 
                           placeholder="<?= lang('App.vin_17_character') ?>" maxlength="17"
                           value="<?= isset($order) ? esc($order['vin_number'] ?? '') : '' ?>">
                    <span class="vin-status" id="modal-vin-status"></span>
                </div>
                <small class="text-muted"><?= lang('App.vin_enter_for_decode') ?></small>
            </div>
        </div>

        <!-- Vehicle -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="vehicle" class="form-label">
                    <i class="fas fa-car me-1"></i><?= lang('App.vehicle') ?> 
                    <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="vehicle" name="vehicle" required 
                       placeholder="<?= lang('App.vin_vehicle_info_auto_fill') ?>"
                       value="<?= isset($order) ? esc($order['vehicle'] ?? '') : '' ?>">
            </div>
        </div>

        <!-- Service -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="service_id" class="form-label">
                    <i class="fas fa-cog me-1"></i><?= lang('App.service') ?> 
                    <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="service_id" name="service_id" required>
                    <option value=""><?= lang('App.select_service') ?></option>
                    <?php if (!empty($carWashServices) && is_array($carWashServices)): ?>
                        <?php foreach ($carWashServices as $service): ?>
                            <?php if (($service['is_active'] ?? 1) == 1 && ($service['is_visible'] ?? 1) == 1): // Only active and visible services ?>
                                <option value="<?= esc($service['id'] ?? '') ?>" 
                                        data-price="<?= esc($service['price'] ?? 0) ?>"
                                        <?= (isset($order) && $order['service_id'] == $service['id']) ? 'selected' : '' ?>>
                                    <?= esc($service['name'] ?? 'Unknown Service') ?>
                                    <?php if (!empty($service['price'])): ?>
                                        - $<?= number_format($service['price'], 2) ?>
                                    <?php endif; ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled><?= lang('App.no_services_available') ?></option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Status -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="carwash_status" class="form-label">
                    <i class="fas fa-flag me-1"></i><?= lang('App.status') ?>
                </label>
                <select class="form-select" id="carwash_status" name="status" 
                        data-no-choice="true" 
                        data-original-value="<?= isset($order) ? esc($order['status']) : 'completed' ?>"
                        style="appearance: auto; -webkit-appearance: auto; -moz-appearance: auto;">
                    <option value="pending" <?= (isset($order) && $order['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="in_progress" <?= (isset($order) && $order['status'] == 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                    <option value="completed" <?= (isset($order) && $order['status'] == 'completed') ? 'selected' : ((!isset($order)) ? 'selected' : '') ?>>Completed</option>
                    <option value="cancelled" <?= (isset($order) && $order['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
        </div>
        
        <!-- Price -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="carwash_price" class="form-label">
                    <i class="fas fa-dollar-sign me-1"></i><?= lang('App.price') ?>
                </label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" id="carwash_price" name="price" 
                           value="<?= isset($order) ? esc($order['price'] ?? 0) : '' ?>" 
                           step="0.01" min="0" placeholder="0.00">
                </div>
                <small class="text-muted">Will auto-fill from selected service</small>
            </div>
        </div>

        <!-- Priority -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="carwash_priority" class="form-label">
                    <i class="fas fa-exclamation-triangle me-1"></i>Waiter Priority
                </label>
                <div class="form-check" style="margin-top: 0.5rem;">
                    <input class="form-check-input" type="checkbox" id="carwash_priority" name="priority" value="waiter"
                           <?= (isset($order) && $order['priority'] == 'waiter') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="carwash_priority">
                        Mark as Waiter priority
                    </label>
                </div>
                <small class="text-muted">If unchecked, will be saved as Normal priority</small>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            <i class="fas fa-save me-1"></i><?= isset($order) ? 'Update Order' : 'Save Order' ?>
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    // Force set values using native JavaScript to avoid Choice.js interference
    <?php if (isset($order) && !empty($order)): ?>
    // Set client value
    setTimeout(function() {
        const clientSelect = document.getElementById('client_id');
        if (clientSelect) {
            clientSelect.value = '<?= esc($order['client_id']) ?>';
            // Trigger change event for any listeners
            $(clientSelect).trigger('change');
        }
        
        // Set service value
        const serviceSelect = document.getElementById('service_id');
        if (serviceSelect) {
            serviceSelect.value = '<?= esc($order['service_id']) ?>';
            $(serviceSelect).trigger('change');
        }
        
        // Set status value
        const statusSelect = document.getElementById('carwash_status');
        if (statusSelect) {
            statusSelect.value = '<?= esc($order['status']) ?>';
            $(statusSelect).trigger('change');
            console.log('CarWash Modal: Status field set to:', statusSelect.value);
        }
        
        // Set priority value
        const priorityCheckbox = document.getElementById('carwash_priority');
        if (priorityCheckbox) {
            priorityCheckbox.checked = '<?= esc($order['priority'] ?? 'normal') ?>' === 'waiter';
            $(priorityCheckbox).trigger('change');
            console.log('CarWash Modal: Priority field set to:', priorityCheckbox.checked ? 'waiter' : 'normal');
        }
        
        // Set text fields
        const tagStockInput = document.getElementById('tag_stock');
        if (tagStockInput) {
            tagStockInput.value = '<?= esc($order['tag_stock'] ?? '') ?>';
        }
        
        const vinInput = document.getElementById('vin_number');
        if (vinInput) {
            vinInput.value = '<?= esc($order['vin_number'] ?? '') ?>';
        }
        
        const vehicleInput = document.getElementById('vehicle');
        if (vehicleInput) {
            vehicleInput.value = '<?= esc($order['vehicle'] ?? '') ?>';
        }
        
        const priceInput = document.getElementById('carwash_price');
        if (priceInput) {
            priceInput.value = '<?= esc($order['price'] ?? 0) ?>';
        }
        
        console.log('CarWash Modal: Values set for editing order <?= esc($order['id']) ?>');
        
        // Also force update any Choice.js instances if they exist
        if (window.Choices) {
            // Destroy and recreate Choice.js instances to ensure proper values
            const selects = ['client_id', 'service_id', 'carwash_status'];
            selects.forEach(function(selectId) {
                const selectElement = document.getElementById(selectId);
                if (selectElement && selectElement.choicesInstance) {
                    selectElement.choicesInstance.destroy();
                    delete selectElement.choicesInstance;
                    console.log('CarWash Modal: Destroyed Choice.js for ' + selectId);
                }
            });
        }
    }, 100);
    <?php endif; ?>

    // Handle client selection to load contacts
    $('#client_id').change(function() {
        var clientId = $(this).val();
        var contactSelect = $('#contact_id');
        
        contactSelect.html('<option value="">Loading...</option>');
        
        if (clientId) {
            $.get('<?= base_url('car_wash/getContactsForClient/') ?>' + clientId, function(data) {
                contactSelect.html('<option value="">Select Contact</option>');
                $.each(data, function(index, contact) {
                    contactSelect.append('<option value="' + contact.id + '">' + contact.first_name + ' ' + contact.last_name + '</option>');
                });
            }).fail(function() {
                contactSelect.html('<option value="">Error loading contacts</option>');
            });
        } else {
            contactSelect.html('<option value="">Select Contact</option>');
        }
    });

    // Handle form submission
    $('#carWashForm').submit(function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var spinner = submitBtn.find('.spinner-border');
        
        // Clear previous validation errors
        clearModalValidationErrors();
        
        // Show loading state
        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');
        
        // Note: Duplicate order validation removed - only recent duplicates are checked via JavaScript
        // This allows creating orders even with duplicate client/date/time combinations
            submitOrder();
        
        function submitOrder() {
            var isEdit = form.find('input[name="id"]').length > 0;
            var url = isEdit ? 
                '<?= base_url('car_wash/update/') ?>' + form.find('input[name="id"]').val() :
                '<?= base_url('car_wash/store') ?>';
            
            // Process form data to handle checkbox properly
            var formData = form.serialize();
            var priorityChecked = $('#carwash_priority').is(':checked');
            
            // Remove any existing priority parameter and add correct one
            formData = formData.replace(/&?priority=[^&]*/g, '');
            formData += '&priority=' + (priorityChecked ? 'waiter' : 'normal');
            
            $.post(url, formData, function(response) {
                if (response.success) {
                    var message = isEdit ? 
                        'Car wash order updated successfully!' : 
                        'Car wash order created successfully!';
                    Swal.fire('Success!', message, 'success');
                    $('#carWashModal').modal('hide');
                    
                    // Clear validation errors on success
                    clearModalValidationErrors();
                    
                    // Comprehensive refresh system
                    refreshAllCarWashData();
                    
                    console.log('✅ CarWash order saved and all data refreshed');
                } else {
                    var errorMessage = response.message;
                    
                    // Handle individual field validation errors
                    if (response.errors) {
                        $.each(response.errors, function(field, message) {
                            showModalFieldValidationError(field, message);
                        });
                        
                        // Also show in SweetAlert for user feedback
                        errorMessage += '<br><ul>';
                        $.each(response.errors, function(field, message) {
                            errorMessage += '<li>' + message + '</li>';
                        });
                        errorMessage += '</ul>';
                    }
                    Swal.fire('Error!', errorMessage, 'error');
                }
            }, 'json').fail(function() {
                Swal.fire('Error!', 'An error occurred while saving the order', 'error');
            }).always(function() {
                // Reset button state
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            });
        }
    });

    // Set default date to today
    $('#date').val(new Date().toISOString().split('T')[0]);
    
    // Initialize VIN decoding functionality
    setupModalVINDecoding();
    
    // Initialize recent duplicate validation
    setupRecentDuplicateValidation();
    
    // Auto-fill price when service is selected
    $('#service_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price') || 0;
        const priceInput = $('#carwash_price');
        
        if (priceInput.length && price > 0) {
            priceInput.val(parseFloat(price).toFixed(2));
            console.log('Price auto-filled from service:', price);
        }
    });
    
    // Additional check to ensure values are set (handles Choice.js interference)
    <?php if (isset($order) && !empty($order)): ?>
        setTimeout(function() {
        // Double-check that values are actually set
        const clientSelect = document.getElementById('client_id');
        const serviceSelect = document.getElementById('service_id');
        const statusSelect = document.getElementById('carwash_status');
        
        if (clientSelect && clientSelect.value !== '<?= esc($order['client_id']) ?>') {
            clientSelect.value = '<?= esc($order['client_id']) ?>';
            $(clientSelect).trigger('change');
            console.log('CarWash Modal: Client value corrected');
        }
        
        if (serviceSelect && serviceSelect.value !== '<?= esc($order['service_id']) ?>') {
            serviceSelect.value = '<?= esc($order['service_id']) ?>';
            $(serviceSelect).trigger('change');
            console.log('CarWash Modal: Service value corrected');
        }
        
        if (statusSelect && statusSelect.value !== '<?= esc($order['status']) ?>') {
            statusSelect.value = '<?= esc($order['status']) ?>';
            $(statusSelect).trigger('change');
            console.log('CarWash Modal: Status value corrected to:', statusSelect.value);
        }
        
        const priceInput = document.getElementById('carwash_price');
        if (priceInput && priceInput.value !== '<?= esc($order['price'] ?? 0) ?>') {
            priceInput.value = '<?= esc($order['price'] ?? 0) ?>';
            console.log('CarWash Modal: Price value corrected to:', priceInput.value);
        }
         }, 500); // Longer delay to ensure all libs are initialized
     <?php endif; ?>
     
     // Protect status field from external modifications
     const statusField = document.getElementById('carwash_status');
     if (statusField) {
         // Create a mutation observer to watch for changes to the status field
         const observer = new MutationObserver(function(mutations) {
             mutations.forEach(function(mutation) {
                 if (mutation.type === 'attributes' && 
                     (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {
                     const target = mutation.target;
                     if (target.id === 'carwash_status') {
                         // Restore visibility if something tries to hide it
                         target.style.display = 'block';
                         target.style.visibility = 'visible';
                         target.style.opacity = '1';
                         console.log('CarWash Modal: Protected status field from modification');
                     }
                 }
             });
         });
         
         // Start observing
         observer.observe(statusField, {
             attributes: true,
             attributeFilter: ['style', 'class', 'hidden']
         });
         
         console.log('CarWash Modal: Status field protection active');
     }
});

// Global debugging function - call from browser console: forceCarWashValues()
window.forceCarWashValues = function() {
    <?php if (isset($order) && !empty($order)): ?>
    console.log('🔧 Forcing CarWash values manually...');
    
    // Client
    const clientSelect = document.getElementById('client_id');
    if (clientSelect) {
        clientSelect.value = '<?= esc($order['client_id']) ?>';
        console.log('Client set to:', clientSelect.value);
    }
    
    // Service
    const serviceSelect = document.getElementById('service_id');
    if (serviceSelect) {
        serviceSelect.value = '<?= esc($order['service_id']) ?>';
        console.log('Service set to:', serviceSelect.value);
    }
    
    // Status
    const statusSelect = document.getElementById('carwash_status');
    if (statusSelect) {
        statusSelect.value = '<?= esc($order['status']) ?>';
        console.log('Status set to:', statusSelect.value);
    }
    
    // Text fields
    document.getElementById('tag_stock').value = '<?= esc($order['tag_stock'] ?? '') ?>';
    document.getElementById('vin_number').value = '<?= esc($order['vin_number'] ?? '') ?>';
    document.getElementById('vehicle').value = '<?= esc($order['vehicle'] ?? '') ?>';
    
    // Force price field
    const priceFieldFinal = document.getElementById('carwash_price');
    if (priceFieldFinal) {
        priceFieldFinal.value = '<?= esc($order['price'] ?? 0) ?>';
        console.log('Final price force set to:', priceFieldFinal.value);
    }
    
    // Force status field again (most important fix)
    const statusFieldFinal = document.getElementById('carwash_status');
    if (statusFieldFinal) {
        statusFieldFinal.value = '<?= esc($order['status']) ?>';
        console.log('Final status force set to:', statusFieldFinal.value);
    }
    
    console.log('✅ All values forced');
    <?php else: ?>
    console.log('ℹ️  No order data available for prepopulation');
    <?php endif; ?>
};

// VIN Decoding Functions for Modal
// VIN Decoder translations for modal - Safe global scope
window.CarWashModalVinTranslations = window.CarWashModalVinTranslations || {
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

function setupModalVINDecoding() {
    const vinInput = document.getElementById('vin_number');
    const vehicleInput = document.getElementById('vehicle');
    const vinStatus = document.getElementById('modal-vin-status');
    
    if (!vinInput || !vehicleInput) {
        console.log('CarWash Modal: VIN or Vehicle input not found, skipping VIN decoding setup');
        return;
    }
    
    console.log('CarWash Modal: Setting up VIN decoding functionality');
    
    // Add VIN input event listener
    vinInput.addEventListener('input', function(e) {
        const vin = e.target.value.toUpperCase().trim();
        
        // Update input value to uppercase
        e.target.value = vin;
        
        // Clear previous status
        clearModalVINStatus();
        
        // Only validate alphanumeric characters
        const validVin = vin.replace(/[^A-Z0-9]/g, '');
        if (validVin !== vin) {
            e.target.value = validVin;
            showModalVINStatus('warning', window.CarWashModalVinTranslations.onlyAlphanumeric);
            return;
        }
        
        // Clear vehicle field when VIN is modified/reduced
        if (vin.length < 17) {
            clearModalVehicleField();
        }
        
        // Check VIN length and decode accordingly
        if (vin.length === 17) {
            // Complete VIN - attempt full decoding
            showModalVINStatus('loading', window.CarWashModalVinTranslations.loading);
            decodeModalVIN(vin);
            // Also check for recent duplicates on complete VIN
            checkRecentDuplicates('vin_number', vin);
        } else if (vin.length >= 10 && vin.length < 17) {
            // Partial VIN with enough info for basic decoding
            showModalVINStatus('info', `${vin.length}/17 ${window.CarWashModalVinTranslations.characters}`);
            decodeModalPartialVIN(vin);
        } else if (vin.length > 0 && vin.length < 10) {
            // Too short for any decoding
            showModalVINStatus('info', `${vin.length}/17 ${window.CarWashModalVinTranslations.characters}`);
        } else if (vin.length > 17) {
            // Too long
            e.target.value = vin.substring(0, 17);
            showModalVINStatus('error', window.CarWashModalVinTranslations.cannotExceed17);
        } else if (vin.length === 0) {
            // Empty VIN - clear everything
            clearModalVINStatus();
        }
    });
    
    // Add VIN validation styles
    addModalVINStyles();
    
    console.log('CarWash Modal: VIN decoding setup complete');
}

function decodeModalVIN(vin) {
    console.log('CarWash Modal: Decoding VIN with NHTSA API:', vin);
    
    // Basic VIN validation
    if (!isModalValidVIN(vin)) {
        let errorMessage = window.CarWashModalVinTranslations.invalidFormat;
        if (window.modalVinValidationError === 'suspicious_patterns') {
            errorMessage = window.CarWashModalVinTranslations.suspiciousPatterns;
        } else if (window.modalVinValidationError === 'invalid_check_digit') {
            errorMessage = window.CarWashModalVinTranslations.invalidCheckDigit;
        }
        showModalVINStatus('error', errorMessage);
        return;
    }
    
    // Show loading status
    showModalVINStatus('loading', window.CarWashModalVinTranslations.loading);
    
    // Call NHTSA vPIC API
    const nhtsa_url = `https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/${vin}?format=json`;
    
    fetch(nhtsa_url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('CarWash Modal: NHTSA API response status:', response.status);
        if (!response.ok) {
            throw new Error(`NHTSA API Error: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('CarWash Modal: NHTSA API response data:', data);
        
        if (data && data.Results && data.Results.length > 0) {
            const vehicleData = data.Results[0];
            console.log('CarWash Modal: Vehicle data from NHTSA:', vehicleData);
            
            // Build comprehensive vehicle string
            const vehicleString = buildModalVehicleString(vehicleData);
            
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
                        clearModalVINStatus();
                        // Reset vehicle field styling
                        vehicleInput.style.backgroundColor = '';
                        vehicleInput.style.borderColor = '';
                    }, 2000);
                    
                    console.log('CarWash Modal: Vehicle field updated with NHTSA data:', vehicleString);
                } else {
                    console.error('CarWash Modal: Vehicle input field not found');
                }
            } else {
                // No vehicle info found
                showModalVINStatus('warning', window.CarWashModalVinTranslations.validNoInfo);
                console.log('CarWash Modal: No vehicle information found in NHTSA response');
            }
        } else {
            console.warn('CarWash Modal: No results found in NHTSA response');
            showModalVINStatus('warning', window.CarWashModalVinTranslations.decodedNoData);
        }
    })
    .catch(error => {
        console.error('CarWash Modal: NHTSA API error:', error);
        
        // Fallback to basic decoding if NHTSA API fails
        console.log('CarWash Modal: Falling back to basic VIN decoding');
        showModalVINStatus('loading', window.CarWashModalVinTranslations.loading);
        
        try {
            const basicInfo = decodeModalVINBasic(vin);
            
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
                        clearModalVINStatus();
                        // Reset vehicle field styling
                        vehicleInput.style.backgroundColor = '';
                        vehicleInput.style.borderColor = '';
                    }, 2000);
                    console.log('CarWash Modal: Fallback decoding successful:', vehicleString);
                }
            } else {
                showModalVINStatus('error', window.CarWashModalVinTranslations.unableToDecode);
            }
        } catch (fallbackError) {
            console.error('CarWash Modal: Fallback decoding also failed:', fallbackError);
            showModalVINStatus('error', window.CarWashModalVinTranslations.decodingFailed);
        }
    });
}

function decodeModalPartialVIN(vin) {
    console.log('CarWash Modal: Attempting partial VIN decoding:', vin);
    
    if (vin.length >= 10) {
        try {
            const basicInfo = decodeModalVINBasic(vin);
            
            if (basicInfo.year || basicInfo.make) {
                const vehicleParts = [];
                if (basicInfo.year) vehicleParts.push(basicInfo.year);
                if (basicInfo.make) vehicleParts.push(basicInfo.make);
                
                const vehicleString = vehicleParts.join(' ');
                
                if (vehicleString.trim() !== '') {
                    const vehicleInput = document.getElementById('vehicle');
                    if (vehicleInput) {
                                        vehicleInput.value = vehicleString + ` (${window.CarWashModalVinTranslations.partial})`;
                vehicleInput.classList.add('vin-decoded');
                        
                        // Add visual indicator for partial decoding
                        vehicleInput.style.backgroundColor = '#fff3cd';
                        vehicleInput.style.borderColor = '#fd7e14';
                        
                        console.log('CarWash Modal: Partial VIN decoding successful:', vehicleString);
                    }
                }
            }
        } catch (error) {
            console.error('CarWash Modal: Partial VIN decoding error:', error);
        }
    }
}

function buildModalVehicleString(nhtsa_data) {
    // Build simplified vehicle string from NHTSA data
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
    
    console.log('CarWash Modal: Built vehicle string from NHTSA data:', result);
    
    return result;
}

function isModalValidVIN(vin) {
    // VIN must be 17 characters, alphanumeric, no I, O, or Q
    if (vin.length !== 17) return false;
    if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) return false;
    
    // Check for suspicious patterns (too many repeated characters)
    if (hasModalSuspiciousPatterns(vin)) {
        console.log('CarWash Modal: VIN rejected - suspicious patterns detected:', vin);
        window.modalVinValidationError = 'suspicious_patterns';
        return false;
    }
    
    // Validate check digit (9th character)
    if (!validateModalVINCheckDigit(vin)) {
        console.log('CarWash Modal: VIN rejected - invalid check digit:', vin);
        window.modalVinValidationError = 'invalid_check_digit';
        return false;
    }
    
    window.modalVinValidationError = null;
    return true;
}

function hasModalSuspiciousPatterns(vin) {
    // Check for too many consecutive identical characters
    const consecutivePattern = /(.)\1{3,}/; // 4+ consecutive identical characters
    if (consecutivePattern.test(vin)) {
        return true;
    }
    
    // Check for too many of the same character in the entire VIN
    const charCounts = {};
    for (let char of vin) {
        charCounts[char] = (charCounts[char] || 0) + 1;
        // If any character appears more than 4 times, it's suspicious
        if (charCounts[char] > 4) {
            return true;
        }
    }
    
    return false;
}

function validateModalVINCheckDigit(vin) {
    // VIN check digit validation algorithm (ISO 3779)
    const weights = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];
    const values = {
        'A': 1, 'B': 2, 'C': 3, 'D': 4, 'E': 5, 'F': 6, 'G': 7, 'H': 8,
        'J': 1, 'K': 2, 'L': 3, 'M': 4, 'N': 5, 'P': 7, 'R': 9,
        'S': 2, 'T': 3, 'U': 4, 'V': 5, 'W': 6, 'X': 7, 'Y': 8, 'Z': 9,
        '0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9
    };
    
    let sum = 0;
    for (let i = 0; i < 17; i++) {
        if (i === 8) continue; // Skip check digit position
        const char = vin.charAt(i);
        const value = values[char];
        if (value === undefined) return false;
        sum += value * weights[i];
    }
    
    const checkDigit = sum % 11;
    const expectedCheckChar = checkDigit === 10 ? 'X' : checkDigit.toString();
    const actualCheckChar = vin.charAt(8);
    
    return expectedCheckChar === actualCheckChar;
}

function decodeModalVINBasic(vin) {
    // Basic VIN decoding - extracts year, make, and some model info
    const vinInfo = {
        year: null,
        make: null,
        model: null,
        trim: null
    };
    
    try {
        // Decode year (10th character)
        const yearCode = vin.charAt(9);
        vinInfo.year = decodeModalYearFromVIN(yearCode);
        
        // Decode manufacturer (World Manufacturer Identifier - first 3 characters)
        const wmi = vin.substring(0, 3);
        vinInfo.make = decodeModalMakeFromWMI(wmi);
        
    } catch (error) {
        console.error('Modal Basic VIN decoding error:', error);
    }
    
    return vinInfo;
}

function decodeModalYearFromVIN(yearCode) {
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

function decodeModalMakeFromWMI(wmi) {
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
        'JHM': 'Honda', 'JH4': 'Acura', 'JA3': 'Mitsubishi', 'JA4': 'Mitsubishi',
        'JF1': 'Subaru', 'JF2': 'Subaru', 'JM1': 'Mazda', 'JM3': 'Mazda',
        'JN1': 'Nissan', 'JN8': 'Nissan', 'JT1': 'Toyota', 'JT2': 'Toyota', 'JT3': 'Toyota',
        'JTD': 'Toyota', 'JTE': 'Toyota', 'JTF': 'Toyota', 'JTG': 'Toyota', 'JTH': 'Toyota',
        'JTJ': 'Toyota', 'JTK': 'Toyota', 'JTL': 'Toyota', 'JTM': 'Toyota', 'JTN': 'Toyota',
        
        // Korean Manufacturers
        'KMH': 'Hyundai', 'KNA': 'Kia', 'KNB': 'Kia', 'KNC': 'Kia', 'KND': 'Kia',
        'KNE': 'Kia', 'KNF': 'Kia', 'KNG': 'Kia', 'KNH': 'Kia', 'KNJ': 'Kia',
        'KNK': 'Kia', 'KNL': 'Kia', 'KNM': 'Kia', 'KNN': 'Kia', 'KNP': 'Kia'
    };
    
    return wmiCodes[wmi] || null;
}

function showModalVINStatus(type, message) {
    const vinStatus = document.getElementById('modal-vin-status');
    const vinInput = document.getElementById('vin_number');
    
    // Clear previous status
    clearModalVINStatus();
    
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
    if (type === 'warning' && (message.includes(modalVinTranslations.validNoInfo) || message.includes(modalVinTranslations.decodedNoData))) {
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
                clearModalVINStatus();
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

function clearModalVINStatus() {
    const vinStatus = document.getElementById('modal-vin-status');
    const vinInput = document.getElementById('vin_number');
    
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
            
            console.log('CarWash Modal: Vehicle field cleared due to VIN modification');
        }
    }
}

function addModalVINStyles() {
    // Check if styles already exist
    if (document.getElementById('modal-vin-decoding-styles')) {
        return;
    }
    
    const style = document.createElement('style');
    style.id = 'modal-vin-decoding-styles';
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

// Recent Duplicate Validation Functions
function setupRecentDuplicateValidation() {
    const tagStockInput = document.getElementById('tag_stock');
    const vinInput = document.getElementById('vin_number');
    
    console.log('CarWash Modal: Setting up recent duplicate validation');
    console.log('Tag/Stock input found:', !!tagStockInput);
    console.log('VIN input found:', !!vinInput);
    
    // Setup dynamic validation for tag/stock field
    if (tagStockInput) {
        tagStockInput.addEventListener('input', function() {
            const tagValue = this.value.trim();
            console.log('Modal Tag/Stock input changed:', tagValue);
            
            // Clear existing warnings immediately
            hideRecentDuplicateWarning('tag_stock');
            
            if (tagValue) {
                // Check for recent duplicates with debounce
                checkRecentDuplicates('tag_stock', tagValue);
            }
        });
        console.log('CarWash Modal: Tag/Stock duplicate validation initialized');
    } else {
        console.warn('CarWash Modal: Tag/Stock input not found');
    }
    
    // VIN duplicate validation is integrated into setupModalVINDecoding()
    // It checks for recent duplicates when VIN is complete (17 characters)
}

function checkRecentDuplicates(field, value) {
    if (!value) {
        hideRecentDuplicateWarning(field);
        return;
    }

    console.log(`CarWash Modal: Checking ${field} for recent duplicates:`, value);

    // Clear any existing timeout for this specific field to debounce requests
    if (!window.carWashDuplicateTimeouts) {
        window.carWashDuplicateTimeouts = {};
    }
    
    if (window.carWashDuplicateTimeouts[field]) {
        clearTimeout(window.carWashDuplicateTimeouts[field]);
    }

    // Get selected time window from the selector (default to 5 if not found)
    const duplicateCheckTimeSelector = document.getElementById('duplicateCheckTime');
    const selectedMinutes = duplicateCheckTimeSelector ? duplicateCheckTimeSelector.value : '5';

    console.log(`CarWash Modal: Selected minutes: ${selectedMinutes}`);

    // Debounce the duplicate check to avoid too many requests (separate timeout per field)
    window.carWashDuplicateTimeouts[field] = setTimeout(() => {
        console.log(`CarWash Modal: Making duplicate check request for ${field}:`, value);
        $.post('<?= base_url('car_wash/checkRecentDuplicates') ?>', {
            field: field,
            value: value,
            minutes: selectedMinutes,
            current_order_id: null // For edit mode, this would be populated
        })
        .done(function(data) {
            console.log(`CarWash Modal: Duplicate check response for ${field}:`, data);
            console.log(`CarWash Modal: Has duplicates: ${data.has_duplicates}`);
            console.log(`CarWash Modal: Duplicates data:`, data.duplicates);
            
            if (data.success && data.has_duplicates) {
                console.log(`CarWash Modal: Showing warning for ${field}`);
                showRecentDuplicateWarning(field, data.duplicates);
            } else {
                console.log(`CarWash Modal: Hiding warning for ${field}`);
                hideRecentDuplicateWarning(field);
            }
        })
        .fail(function(xhr, status, error) {
            console.error('CarWash Modal: Error checking recent duplicates:', error);
            console.error('CarWash Modal: XHR:', xhr);
            hideRecentDuplicateWarning(field);
        })
        .always(function() {
            // Clean up timeout reference
            if (window.carWashDuplicateTimeouts && window.carWashDuplicateTimeouts[field]) {
                delete window.carWashDuplicateTimeouts[field];
            }
        });
    }, 800); // 800ms debounce delay (slightly longer for Car Wash)
}

function showRecentDuplicateWarning(field, duplicates) {
    const inputField = document.getElementById(field);
    if (!inputField) return;

    // Remove existing warning
    hideRecentDuplicateWarning(field);

    const fieldContainer = inputField.closest('.col-md-6');
    if (!fieldContainer) return;

    // Get selected time window for display
    const duplicateCheckTimeSelector = document.getElementById('duplicateCheckTime');
    const selectedMinutes = duplicateCheckTimeSelector ? duplicateCheckTimeSelector.value : '5';

    let duplicateInfo = '';
    if (duplicates[field]) {
        const orders = duplicates[field].orders;
        const count = duplicates[field].count;

        // Create enhanced duplicate info with timing
        const ordersList = orders.map(order => {
            const minutesAgo = order.minutes_ago;
            const timeText = minutesAgo < 1 ? 'just now' : 
                            minutesAgo === 1 ? '1 minute ago' : 
                            `${minutesAgo} minutes ago`;
            return `Order #${order.order_number} (${timeText})`;
        }).join(', ');

        const countText = count === 1 ? 'recent duplicate found' : 'recent duplicates found';
        duplicateInfo = `${count} ${countText}: ${ordersList}`;
    }

    const warningDiv = document.createElement('div');
    warningDiv.id = `${field}-duplicate-warning`;
    warningDiv.className = 'alert alert-warning mt-2 mb-0';
    warningDiv.style.fontSize = '0.875rem';

    const fieldLabel = field === 'tag_stock' ? 'Tag/Stock' : 'VIN';
    
    warningDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>⚠️ Recent ${fieldLabel} Duplicate!</strong><br>
        <small>${duplicateInfo}</small><br>
        <small class="text-muted"><em>This ${fieldLabel.toLowerCase()} was used in the last ${selectedMinutes} minutes. Please verify this is not a duplicate submission.</em></small>
    `;

    fieldContainer.appendChild(warningDiv);

    // Add warning styling to input
    inputField.style.borderColor = '#fd7e14';
    inputField.style.backgroundColor = '#fff3cd';
    inputField.style.boxShadow = '0 0 0 0.2rem rgba(253, 126, 20, 0.25)';
}

function hideRecentDuplicateWarning(field) {
    const warningElement = document.getElementById(`${field}-duplicate-warning`);
    if (warningElement) {
        warningElement.remove();
    }

    const inputField = document.getElementById(field);
    if (inputField) {
        inputField.style.borderColor = '';
        inputField.style.backgroundColor = '';
        inputField.style.boxShadow = '';
    }
}

// Clear modal validation errors when user starts typing or changing fields
$(document).on('input change', '#carWashForm .form-control, #carWashForm .form-select', function() {
    var $field = $(this);
    $field.removeClass('is-invalid');
    $field.siblings('.invalid-feedback').remove();
});

// Clear modal validation errors when user focuses on a field
$(document).on('focus', '#carWashForm .form-control, #carWashForm .form-select', function() {
    var $field = $(this);
    $field.removeClass('is-invalid');
    $field.siblings('.invalid-feedback').remove();
});

// Clear modal validation errors
function clearModalValidationErrors() {
    // Remove validation classes and error messages
    $('#carWashForm .form-control, #carWashForm .form-select').removeClass('is-invalid');
    $('#carWashForm .invalid-feedback').remove();
    $('#carWashForm .text-danger').remove();
}

// Show modal field validation error
function showModalFieldValidationError(field, message) {
    var fieldElement = $('#' + field);
    
    if (fieldElement.length) {
        // Add invalid class
        fieldElement.addClass('is-invalid');
        
        // Remove any existing error message
        fieldElement.siblings('.invalid-feedback').remove();
        
        // Add error message
        fieldElement.after('<div class="invalid-feedback">' + message + '</div>');
    }
}

// Clear form and validation errors when modal is closed
$(document).on('hidden.bs.modal', '#carWashModal', function() {
    // Clear the form
    $('#carWashForm')[0].reset();
    
    // Clear all validation errors
    clearModalValidationErrors();
    
    // Clear any VIN decoding status
    clearModalVINStatus();
    
    // Clear duplicate warnings
    hideRecentDuplicateWarning('tag_stock');
    hideRecentDuplicateWarning('vin_number');
    
    console.log('CarWash Modal: Form cleared and validation errors reset');
});
</script> 