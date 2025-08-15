
<style>
    /* Modal size adjustment only */
    #reconOrderModal .modal-dialog {
        max-width: 900px;
    }

    /* VIN Scanner Styles - Velzon Theme Compatible */
    .scanner-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .scanner-modal-content {
        background: var(--bs-body-bg, #ffffff);
        border-radius: var(--bs-border-radius-lg, 0.75rem);
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: var(--bs-box-shadow-lg, 0 1rem 3rem rgba(0, 0, 0, 0.175));
        border: 1px solid var(--bs-border-color, #dee2e6);
    }

    .scanner-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        background: var(--bs-light, #f8f9fa);
        color: var(--bs-body-color, #212529);
        border-bottom: 1px solid var(--bs-border-color, #dee2e6);
    }

    .scanner-header h5 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--bs-heading-color, #495057);
    }

    .scanner-controls {
        display: flex;
        gap: 0.5rem;
    }

    .scanner-body {
        position: relative;
        padding: 1.25rem;
        text-align: center;
        background: var(--bs-body-bg, #ffffff);
    }

    .scanner-target {
        width: 100%;
        height: 300px;
        background: var(--bs-gray-900, #212529);
        border-radius: var(--bs-border-radius, 0.375rem);
        overflow: hidden;
        position: relative;
        margin-bottom: 1rem;
        border: 2px dashed var(--bs-border-color, #dee2e6);
    }

    .scanner-target canvas,
    .scanner-target video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        border-radius: calc(var(--bs-border-radius, 0.375rem) - 2px);
    }

    .scanner-instructions {
        color: var(--bs-secondary, #6c757d);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        background: var(--bs-light, #f8f9fa);
        border-radius: var(--bs-border-radius, 0.375rem);
        border: 1px solid var(--bs-border-color-translucent, rgba(0, 0, 0, 0.075));
    }

    /* VIN Status Styles */
    .vin-status {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .vin-status-loading {
        color: var(--bs-primary, #0d6efd);
    }

    .vin-status-success {
        color: var(--bs-success, #198754);
    }

    .vin-status-error {
        color: var(--bs-danger, #dc3545);
    }

    .vin-status-warning {
        color: var(--bs-warning, #fd7e14);
    }

    .vin-status-info {
        color: var(--bs-info, #0dcaf0);
    }

    /* VIN Input States */
    .vin-decoding {
        border-color: var(--bs-primary, #0d6efd) !important;
        box-shadow: 0 0 0 0.2rem var(--bs-primary-bg-subtle, rgba(13, 110, 253, 0.25)) !important;
    }

    .vin-success {
        border-color: var(--bs-success, #198754) !important;
        box-shadow: 0 0 0 0.2rem var(--bs-success-bg-subtle, rgba(25, 135, 84, 0.25)) !important;
    }

    .vin-error {
        border-color: var(--bs-danger, #dc3545) !important;
        box-shadow: 0 0 0 0.2rem var(--bs-danger-bg-subtle, rgba(220, 53, 69, 0.25)) !important;
    }

    .vin-warning {
        border-color: var(--bs-warning, #fd7e14) !important;
        box-shadow: 0 0 0 0.2rem var(--bs-warning-bg-subtle, rgba(253, 126, 20, 0.25)) !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .scanner-modal-content {
            width: 95%;
            margin: 1rem;
        }
        
        .scanner-target {
            height: 250px;
        }

        .scanner-header {
            padding: 0.75rem 1rem;
        }

        .scanner-body {
            padding: 1rem;
        }
    }

    /* Dark mode support */
    [data-bs-theme="dark"] .scanner-modal-content {
        background: var(--bs-dark, #212529);
        border-color: var(--bs-border-color, #495057);
    }

    [data-bs-theme="dark"] .scanner-header {
        background: var(--bs-gray-800, #343a40);
        color: var(--bs-light, #f8f9fa);
        border-bottom-color: var(--bs-border-color, #495057);
    }

    [data-bs-theme="dark"] .scanner-header h5 {
        color: var(--bs-light, #f8f9fa);
    }

    [data-bs-theme="dark"] .scanner-body {
        background: var(--bs-dark, #212529);
    }

    [data-bs-theme="dark"] .scanner-instructions {
        background: var(--bs-gray-800, #343a40);
        color: var(--bs-light, #f8f9fa);
        border-color: var(--bs-border-color, #495057);
    }
</style>

<form id="reconOrderForm" action="<?= base_url('recon_orders/store') ?>" method="post">
    
    <!-- Client and Service Date Section -->
    <div class="form-section">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="recon_order_client_id" class="form-label"><?= lang('App.client') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" id="recon_order_client_id" name="client_id" required>
                        <option value=""><?= lang('App.select_client') ?></option>
                        <?php if (!empty($clients)): ?>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['id'] ?>">
                                    <?= esc($client['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="service_date" class="form-label"><?= lang('App.service_date') ?></label>
                    <input type="date" class="form-control" id="service_date" name="service_date" 
                           value="<?= date('Y-m-d') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Information Section -->
    <div class="form-section">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="stock" class="form-label"><?= lang('App.stock') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="stock" name="stock" 
                           placeholder="<?= lang('App.enter_stock_number') ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="vin_number" class="form-label">
                        <?= lang('App.vin') ?> 
                        <i class="fas fa-info-circle text-muted ms-1" 
                           data-bs-toggle="tooltip" 
                           data-bs-placement="top" 
                           title="Enter complete 17-character VIN for automatic vehicle detection"></i>
                    </label>
                    <div class="vin-input-container">
                        <div class="input-group">
                            <input type="text" class="form-control" id="vin_number" name="vin_number" 
                                   placeholder="<?= lang('App.enter_vin_placeholder') ?>" maxlength="17">
                            <button class="btn btn-outline-primary" type="button" id="scanVinBtn" 
                                    title="<?= lang('App.scan_vin_barcode') ?>" style="display: none;">
                                <i class="ri-qr-scan-line me-1"></i><span class="scan-btn-text"><?= lang('App.scan') ?></span>
                            </button>
                        </div>
                        <small id="form-vin-status" class="vin-status"></small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="vehicle" class="form-label"><?= lang('App.vehicle') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="vehicle" name="vehicle" 
                           placeholder="<?= lang('App.enter_vehicle_details') ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Service and Status Section -->
    <div class="form-section">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="recon_order_service_id" class="form-label"><?= lang('App.service') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" id="recon_order_service_id" name="service_id" required>
                        <option value=""><?= lang('App.select') ?> <?= lang('App.service') ?></option>
                        <?php if (!empty($services)): ?>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['id'] ?>" data-price="<?= $service['price'] ?>">
                                    <?= esc($service['name']) ?>
                                    <?php if (!empty($service['price'])): ?>
                                        - $<?= number_format($service['price'], 2) ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="recon_order_status" class="form-label"><?= lang('App.status') ?></label>
                    <select class="form-select" id="recon_order_status" name="status" required>
                        <option value="pending" selected>⏳ <?= lang('App.pending') ?></option>
                        <option value="in_progress">🔄 <?= lang('App.in_progress') ?></option>
                        <option value="completed">✅ <?= lang('App.completed') ?></option>
                        <option value="cancelled">❌ <?= lang('App.cancelled') ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Section - Hidden -->
    <div class="form-section" style="display: none;">
        <div class="mb-3">
            <label for="notes" class="form-label"><?= lang('App.notes') ?></label>
            <textarea class="form-control" id="notes" name="notes" rows="4" 
                      placeholder="<?= lang('App.enter_notes') ?>"></textarea>
        </div>
    </div>

    <!-- Source tracking fields - Hidden -->
    <input type="hidden" name="from_inventory" value="0" id="from_inventory_field">
    <input type="hidden" name="source_type" value="manual" id="source_type_field">

</form>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <?= lang('App.cancel') ?>
    </button>
    <button type="submit" form="reconOrderForm" class="btn btn-primary" id="createOrderBtn">
        <?= lang('App.create_order') ?>
    </button>
</div>

<!-- VIN Scanner Modal -->
<div id="vinScannerModal" class="scanner-modal" style="display: none;">
    <div class="scanner-modal-content">
        <div class="scanner-header">
            <h5 id="scanner-title" class="mb-0">
                <i id="scanner-icon" class="ri-qr-scan-line me-2"></i>
                <?= lang('App.scan_vin_barcode') ?>
            </h5>
            <div class="scanner-controls">
                <button type="button" id="modeSwitchBtn" class="btn btn-sm btn-outline-secondary me-2" 
                        title="<?= lang('App.switch_to_qr_scanning') ?>">
                    <i class="ri-qr-scan-2-line"></i> QR
                </button>
                <button type="button" id="closeScannerBtn" class="btn btn-sm btn-outline-secondary">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        </div>
        <div class="scanner-body">
            <div id="scanner-target" class="scanner-target"></div>
            <div class="scanner-instructions">
                <i class="ri-qr-code-line me-2"></i><?= lang('App.point_camera_to_vin_barcode') ?>
            </div>
        </div>
    </div>
</div>

<script>
// Execute immediately when modal loads (not DOMContentLoaded since modal loads dynamically)
(function() {
    console.log('🔧 Form Modal JavaScript executing...');
    
    // Add small delay to ensure DOM elements are fully loaded
    setTimeout(function() {
        console.log('🔧 Initializing form modal elements after delay...');
        
        // Check if there's inventory data or if fields are already set to inventory before resetting
        const modal = document.getElementById('reconOrderModal');
        const hasInventoryData = modal && $(modal).data('inventory-data');
        
        const fromInventoryField = document.getElementById('from_inventory_field');
        const sourceTypeField = document.getElementById('source_type_field');
        
        // Check if fields are already set to inventory values
        const alreadySetToInventory = fromInventoryField && fromInventoryField.value === '1' && 
                                     sourceTypeField && sourceTypeField.value === 'inventory';
        
        if (!hasInventoryData && !alreadySetToInventory) {
            // Only reset if no inventory data AND not already set to inventory - this is a manual order
            if (fromInventoryField) {
                fromInventoryField.value = '0';
                console.log('🔄 Reset from_inventory to: 0 (manual order)');
            }
            if (sourceTypeField) {
                sourceTypeField.value = 'manual';
                console.log('🔄 Reset source_type to: manual (manual order)');
            }
        } else {
            if (hasInventoryData) {
                console.log('📦 Inventory data detected, skipping reset');
            } else if (alreadySetToInventory) {
                console.log('📦 Fields already set to inventory values, skipping reset');
            }
        }
        
        // VIN decoder translations - Local scope to avoid conflicts
        const formModalVinTranslations = {
            vin_loading: '<?= lang('App.vin_loading') ?>',
            vin_only_alphanumeric: '<?= lang('App.vin_only_alphanumeric') ?>',
            vin_cannot_exceed_17: '<?= lang('App.vin_cannot_exceed_17') ?>',
            vin_invalid_format: '<?= lang('App.vin_invalid_format') ?>',
            vin_valid_no_info: '<?= lang('App.vin_valid_no_info') ?>',
            vin_decoded_no_data: '<?= lang('App.vin_decoded_no_data') ?>',
            vin_unable_to_decode: '<?= lang('App.vin_unable_to_decode') ?>',
            vin_decoding_failed: '<?= lang('App.vin_decoding_failed') ?>',
            vin_partial: '<?= lang('App.vin_partial') ?>',
            vin_characters: '<?= lang('App.vin_characters') ?>',
            vin_suspicious_patterns: '<?= lang('App.vin_suspicious_patterns') ?>',
            vin_invalid_check_digit: '<?= lang('App.vin_invalid_check_digit') ?>'
        };
        
        const form = document.getElementById('reconOrderForm');
        const clientSelect = document.getElementById('recon_order_client_id');
        const serviceSelect = document.getElementById('recon_order_service_id');
        const vinInput = document.getElementById('vin_number');
        const submitBtn = document.getElementById('createOrderBtn');

        // VIN Decoding Functions - Local scope
        function setupFormVINDecoding() {
            console.log('📝 Setting up Form VIN decoding...');
            if (!vinInput) {
                console.error('❌ VIN input not found: vin_number');
                return;
            }
            console.log('✅ VIN input found, attaching event listener');

            vinInput.addEventListener('input', function(e) {
                console.log('🔤 VIN input event triggered:', e.target.value);
                const vin = e.target.value.toUpperCase().trim();
                e.target.value = vin;

                clearFormVINStatus();

                const validVin = vin.replace(/[^A-Z0-9]/g, '');
                if (validVin !== vin) {
                    e.target.value = validVin;
                    showFormVINStatus('warning', formModalVinTranslations.vin_only_alphanumeric);
                    return;
                }

                if (vin.length === 17) {
                    console.log('🔍 17-character VIN detected, starting decoding:', vin);
                    showFormVINStatus('loading', formModalVinTranslations.vin_loading);
                    decodeFormVIN(vin);
                } else if (vin.length >= 10 && vin.length < 17) {
                    showFormVINStatus('loading', formModalVinTranslations.vin_loading);
                    decodeFormPartialVIN(vin);
                } else if (vin.length > 0 && vin.length < 10) {
                    showFormVINStatus('info', `${vin.length}/17 ${formModalVinTranslations.vin_characters}`);
                    clearFormVehicleField();
                } else if (vin.length > 17) {
                    e.target.value = vin.substring(0, 17);
                    showFormVINToast('error', formModalVinTranslations.vin_cannot_exceed_17);
                } else {
                    clearFormVehicleField();
                }
            });
        }

        function decodeFormVIN(vin) {
            console.log('🚀 decodeFormVIN called with VIN:', vin);
            const validationResult = isValidFormVIN(vin);
            console.log('✅ VIN validation result:', validationResult);
            if (!validationResult.isValid) {
                if (validationResult.errorType === 'suspicious' || validationResult.errorType === 'checkdigit') {
                    showFormVINToast('error', validationResult.message);
                } else {
                    showFormVINStatus('error', validationResult.message);
                }
                return;
            }

            showFormVINStatus('loading', formModalVinTranslations.vin_loading);

            const nhtsa_url = `https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/${vin}?format=json`;
            console.log('🌐 Making NHTSA API call to:', nhtsa_url);

            fetch(nhtsa_url, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`NHTSA API Error: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('📊 NHTSA API response received:', data);
                if (data && data.Results && data.Results.length > 0) {
                    const vehicleData = data.Results[0];
                    console.log('🚗 Vehicle data from API:', vehicleData);
                    const vehicleString = buildFormVehicleString(vehicleData);
                    console.log('🏷️ Built vehicle string:', vehicleString);

                    if (vehicleString && vehicleString.trim() !== '') {
                        const vehicleInput = document.getElementById('vehicle');
                        if (vehicleInput) {
                            vehicleInput.value = vehicleString;
                            vehicleInput.classList.add('vin-decoded');
                            vehicleInput.style.backgroundColor = '#d1e7dd';
                            vehicleInput.style.borderColor = '#198754';

                            setTimeout(() => {
                                clearFormVINStatus();
                                vehicleInput.style.backgroundColor = '';
                                vehicleInput.style.borderColor = '';
                            }, 2000);
                        }
                    } else {
                        showFormVINToast('warning', formModalVinTranslations.vin_valid_no_info);
                    }
                } else {
                    showFormVINToast('warning', formModalVinTranslations.vin_decoded_no_data);
                }
            })
            .catch(error => {
                console.error('NHTSA API error:', error);

                try {
                    const basicInfo = decodeFormVINBasic(vin);

                    if (basicInfo.year || basicInfo.make) {
                        const vehicleParts = [];
                        if (basicInfo.year) vehicleParts.push(basicInfo.year);
                        if (basicInfo.make) vehicleParts.push(basicInfo.make);

                        const vehicleString = vehicleParts.join(' ');
                        const vehicleInput = document.getElementById('vehicle');
                        
                        if (vehicleInput) {
                            vehicleInput.value = vehicleString;
                            vehicleInput.classList.add('vin-decoded');
                            vehicleInput.style.backgroundColor = '#fff3cd';
                            vehicleInput.style.borderColor = '#fd7e14';

                            setTimeout(() => {
                                clearFormVINStatus();
                                vehicleInput.style.backgroundColor = '';
                                vehicleInput.style.borderColor = '';
                            }, 2000);
                        }
                    } else {
                        showFormVINToast('error', formModalVinTranslations.vin_unable_to_decode);
                    }
                } catch (fallbackError) {
                    showFormVINToast('error', formModalVinTranslations.vin_decoding_failed);
                }
            });
        }

        function decodeFormPartialVIN(vin) {
            try {
                const basicInfo = decodeFormVINBasic(vin);
                
                if (basicInfo.year || basicInfo.make) {
                    const vehicleParts = [];
                    if (basicInfo.year) vehicleParts.push(basicInfo.year);
                    if (basicInfo.make) vehicleParts.push(basicInfo.make);
                    vehicleParts.push(`(${formModalVinTranslations.vin_partial})`);

                    const vehicleString = vehicleParts.join(' ');
                    const vehicleInput = document.getElementById('vehicle');
                    
                    if (vehicleInput) {
                        vehicleInput.value = vehicleString;
                        vehicleInput.classList.add('vin-decoded');
                        vehicleInput.style.backgroundColor = '#fff3cd';
                        vehicleInput.style.borderColor = '#fd7e14';
                        
                        showFormVINStatus('warning', `${formModalVinTranslations.vin_partial} (${vin.length}/17 ${formModalVinTranslations.vin_characters})`);
                        
                        setTimeout(() => {
                            clearFormVINStatus();
                            vehicleInput.style.backgroundColor = '';
                            vehicleInput.style.borderColor = '';
                        }, 3000);
                    }
                } else {
                    showFormVINStatus('info', `${vin.length}/17 ${formModalVinTranslations.vin_characters}`);
                    clearFormVehicleField();
                }
            } catch (error) {
                showFormVINStatus('info', `${vin.length}/17 ${formModalVinTranslations.vin_characters}`);
                clearFormVehicleField();
            }
        }

        function clearFormVehicleField() {
            const vehicleInput = document.getElementById('vehicle');
            if (vehicleInput && vehicleInput.classList.contains('vin-decoded')) {
                vehicleInput.value = '';
                vehicleInput.classList.remove('vin-decoded');
                vehicleInput.style.backgroundColor = '';
                vehicleInput.style.borderColor = '';
            }
        }

        function buildFormVehicleString(nhtsa_data) {
            const parts = [];

            if (nhtsa_data.ModelYear && nhtsa_data.ModelYear !== '') {
                parts.push(nhtsa_data.ModelYear);
            }

            if (nhtsa_data.Make && nhtsa_data.Make !== '') {
                parts.push(nhtsa_data.Make.toUpperCase());
            }

            if (nhtsa_data.Model && nhtsa_data.Model !== '') {
                parts.push(nhtsa_data.Model.toUpperCase());
            }

            if (nhtsa_data.Series && nhtsa_data.Series !== '') {
                parts.push(`(${nhtsa_data.Series})`);
            } else if (nhtsa_data.Trim && nhtsa_data.Trim !== '') {
                parts.push(`(${nhtsa_data.Trim})`);
            }

            if (nhtsa_data.EngineNumberOfCylinders && nhtsa_data.EngineNumberOfCylinders !== '') {
                parts.push(`(${nhtsa_data.EngineNumberOfCylinders} cyl)`);
            }

            return parts.join(' ').trim();
        }

        function isValidFormVIN(vin) {
            if (vin.length !== 17) {
                return { isValid: false, errorType: 'format', message: formModalVinTranslations.vin_invalid_format };
            }
            
            if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) {
                return { isValid: false, errorType: 'format', message: formModalVinTranslations.vin_invalid_format };
            }

            const suspiciousResult = checkFormSuspiciousPatterns(vin);
            if (!suspiciousResult.isValid) {
                return suspiciousResult;
            }

            const checkDigitResult = validateFormCheckDigit(vin);
            if (!checkDigitResult.isValid) {
                return checkDigitResult;
            }

            return { isValid: true };
        }

        function checkFormSuspiciousPatterns(vin) {
            for (let i = 0; i <= vin.length - 4; i++) {
                if (vin[i] === vin[i+1] && vin[i] === vin[i+2] && vin[i] === vin[i+3]) {
                    return { 
                        isValid: false, 
                        errorType: 'suspicious', 
                        message: formModalVinTranslations.vin_suspicious_patterns 
                    };
                }
            }

            const charCount = {};
            for (const char of vin) {
                charCount[char] = (charCount[char] || 0) + 1;
                if (charCount[char] > 4) {
                    return { 
                        isValid: false, 
                        errorType: 'suspicious', 
                        message: formModalVinTranslations.vin_suspicious_patterns 
                    };
                }
            }

            return { isValid: true };
        }

        function validateFormCheckDigit(vin) {
            const weights = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];
            const values = {
                'A': 1, 'B': 2, 'C': 3, 'D': 4, 'E': 5, 'F': 6, 'G': 7, 'H': 8,
                'J': 1, 'K': 2, 'L': 3, 'M': 4, 'N': 5, 'P': 7, 'R': 9,
                'S': 2, 'T': 3, 'U': 4, 'V': 5, 'W': 6, 'X': 7, 'Y': 8, 'Z': 9,
                '0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9
            };

            let sum = 0;
            for (let i = 0; i < 17; i++) {
                if (i === 8) continue;
                const char = vin[i];
                const value = values[char];
                if (value === undefined) {
                    return { 
                        isValid: false, 
                        errorType: 'format', 
                        message: formModalVinTranslations.vin_invalid_format 
                    };
                }
                sum += value * weights[i];
            }

            const checkDigit = sum % 11;
            const expectedCheckDigit = checkDigit === 10 ? 'X' : checkDigit.toString();
            const actualCheckDigit = vin[8];

            if (actualCheckDigit !== expectedCheckDigit) {
                return { 
                    isValid: false, 
                    errorType: 'checkdigit', 
                    message: formModalVinTranslations.vin_invalid_check_digit 
                };
            }

            return { isValid: true };
        }

        function decodeFormVINBasic(vin) {
            const vinInfo = { year: null, make: null, model: null, trim: null };

            try {
                const yearCode = vin.charAt(9);
                vinInfo.year = decodeFormYearFromVIN(yearCode);

                const wmi = vin.substring(0, 3);
                vinInfo.make = decodeFormMakeFromWMI(wmi);
            } catch (error) {
                console.error('Basic VIN decoding error:', error);
            }

            return vinInfo;
        }

        function decodeFormYearFromVIN(yearCode) {
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

        function decodeFormMakeFromWMI(wmi) {
            const wmiCodes = {
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
                'JH4': 'Acura', 'JHM': 'Honda', 'JF1': 'Subaru', 'JF2': 'Subaru',
                'JM1': 'Mazda', 'JM3': 'Mazda', 'JN1': 'Nissan', 'JN6': 'Nissan', 'JN8': 'Nissan',
                'JT2': 'Toyota', 'JT3': 'Toyota', 'JT4': 'Toyota', 'JT6': 'Toyota', 'JT8': 'Toyota',
                'JTD': 'Toyota', 'JTE': 'Toyota', 'JTF': 'Toyota', 'JTG': 'Toyota', 'JTH': 'Lexus',
                'KMH': 'Hyundai', 'KMJ': 'Hyundai', 'KNA': 'Kia', 'KNB': 'Kia', 'KNC': 'Kia', 'KND': 'Kia',
                'WAU': 'Audi', 'WA1': 'Audi', 'WBA': 'BMW', 'WBS': 'BMW', 'WBX': 'BMW',
                'WDB': 'Mercedes-Benz', 'WDD': 'Mercedes-Benz', 'WDC': 'Mercedes-Benz',
                'WP0': 'Porsche', 'WP1': 'Porsche', 'WVW': 'Volkswagen', 'WV1': 'Volkswagen', 'WV2': 'Volkswagen'
            };

            return wmiCodes[wmi] || null;
        }

        function showFormVINStatus(type, message) {
            const vinStatus = document.getElementById('form-vin-status');
            const vinInput = document.getElementById('vin_number');

            if (!vinStatus) return;

            clearFormVINStatus();

            vinStatus.textContent = message;
            vinStatus.className = `vin-status vin-status-${type}`;

            if (vinInput) {
                vinInput.classList.remove('vin-decoding', 'vin-success', 'vin-error', 'vin-warning');

                if (type === 'loading') {
                    vinInput.classList.add('vin-decoding');
                } else if (type === 'success') {
                    vinInput.classList.add('vin-success');
                } else if (type === 'error') {
                    vinInput.classList.add('vin-error');
                } else if (type === 'warning') {
                    vinInput.classList.add('vin-warning');
                }
            }

            if (type === 'info' || type === 'warning') {
                setTimeout(() => {
                    clearFormVINStatus();
                }, 3000);
            }
        }

        function clearFormVINStatus() {
            const vinStatus = document.getElementById('form-vin-status');
            const vinInput = document.getElementById('vin_number');

            if (vinStatus) {
                vinStatus.textContent = '';
                vinStatus.className = 'vin-status';
            }

            if (vinInput) {
                vinInput.classList.remove('vin-decoding', 'vin-success', 'vin-error', 'vin-warning');
            }
        }

        function showFormVINToast(type, message) {
            // Use simple alert for VIN messages
            if (type === 'error' || type === 'warning') {
                alert(message);
            } else {
                // For info messages, show in status instead
                showFormVINStatus('info', message);
            }
        }

        // Load services when client changes
        if (clientSelect) {
            clientSelect.addEventListener('change', function() {
                const clientId = this.value;
                
                if (clientId) {
                    loadServicesForClient(clientId);
                }
            });
        }

        function loadServicesForClient(clientId) {
            fetch('<?= base_url("recon_orders/getServicesForClient/") ?>' + clientId, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && serviceSelect) {
                    // Clear existing options
                    serviceSelect.innerHTML = '<option value=""><?= lang('App.select') ?> <?= lang('App.service') ?></option>';
                    
                    // Add new services to native dropdown
                    data.data.forEach(service => {
                        if (service.is_active && service.show_in_form) {
                            const option = document.createElement('option');
                            option.value = service.id;
                            option.textContent = service.name + (service.price ? ' - $' + parseFloat(service.price).toFixed(2) : '');
                            option.setAttribute('data-price', service.price || '');
                            
                            serviceSelect.appendChild(option);
                        }
                    });
                    
                    console.log('✅ Services loaded into native dropdown - ' + data.data.length + ' services');
                }
            })
            .catch(error => console.error('Error loading services:', error));
        }
        
        // VIN decoding functionality
        console.log('🔍 Looking for VIN input with ID: vin_number');
        console.log('🔍 VIN input found:', vinInput);
        if (vinInput) {
            console.log('🔧 Initializing VIN decoding for form modal');
            setupFormVINDecoding();
        } else {
            console.error('❌ VIN input not found in form modal during initialization');
            console.error('❌ Available elements:', document.querySelectorAll('input[type="text"]'));
        }
        
        // Handle form submission
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate required fields
                const clientId = document.getElementById('recon_order_client_id').value;
                const serviceId = document.getElementById('recon_order_service_id').value;
                const stock = document.getElementById('stock').value;
                const vehicle = document.getElementById('vehicle').value;
                
                if (!clientId || !serviceId || !stock || !vehicle) {
                    showToast('<?= lang('App.fill_required_fields') ?>', 'error');
                    return;
                }
                
                // Show loading state
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><?= lang('App.creating') ?>...';
                    submitBtn.disabled = true;
                }
                
                // Prepare form data
                const formData = new FormData(form);
                
                // Debug: Log all form data before sending
                console.log('📤 Form data being sent:');
                for (let [key, value] of formData.entries()) {
                    console.log(`  ${key}: ${value}`);
                }
                
                // Specifically check our source tracking fields
                console.log('🔍 Source tracking fields:');
                console.log('  from_inventory:', formData.get('from_inventory'));
                console.log('  source_type:', formData.get('source_type'));
                
                // Submit form
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || '<?= lang('App.order_created_successfully') ?>', 'success');
                        
                        // Clear inventory data after successful submission
                        const modal = document.getElementById('reconOrderModal');
                        if (modal) {
                            $(modal).removeData('inventory-data');
                            console.log('🧹 Cleared inventory data after successful submission');
                        }
                        
                        // Close modal and refresh data
                        if (modal && typeof bootstrap !== 'undefined') {
                            const bsModal = bootstrap.Modal.getInstance(modal);
                            if (bsModal) bsModal.hide();
                        }
                        
                        // Refresh page data immediately after modal closes
                        setTimeout(() => {
                            if (typeof refreshAllReconOrdersData === 'function') {
                                // Use index.php refresh function - refreshes all tables without page reload
                                console.log('🔄 Refreshing all ReconOrders tables after creating order');
                                refreshAllReconOrdersData({ 
                                    showToast: false, // Don't show toast since we already showed success
                                    showProgress: true // Show visual progress indicator
                                });
                            } else if (typeof refreshReconOrderViewData === 'function') {
                                // Fallback to view.php refresh function
                                console.log('🔄 Using view.php refresh function');
                                refreshReconOrderViewData();
                            } else {
                                // Last resort - full page reload
                                console.log('⚠️ No refresh function available, reloading page');
                                window.location.reload();
                            }
                        }, 200); // Reduced delay for faster refresh
                    } else {
                        // Check if this is a duplicate confirmation case
                        if (data.duplicate_found && data.confirmation_required) {
                            // Show confirmation dialog for duplicate
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: '<?= lang('App.duplicate_order_found') ?>',
                                    text: data.message,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: '<?= lang('App.yes_create_anyway') ?>',
                                    cancelButtonText: '<?= lang('App.cancel') ?>'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Re-submit with force_create flag
                                        const forceFormData = new FormData(form);
                                        forceFormData.set('force_create', '1');
                                        
                                        fetch(form.action, {
                                            method: 'POST',
                                            body: forceFormData
                                        })
                                        .then(response => response.json())
                                        .then(forceData => {
                                            if (forceData.success) {
                                                showToast(forceData.message || '<?= lang('App.order_created_successfully') ?>', 'success');
                                                
                                                // Clear inventory data after successful submission
                                                const modal = document.getElementById('reconOrderModal');
                                                if (modal) {
                                                    $(modal).removeData('inventory-data');
                                                }
                                                
                                                // Close modal and refresh data
                                                if (modal && typeof bootstrap !== 'undefined') {
                                                    const bsModal = bootstrap.Modal.getInstance(modal);
                                                    if (bsModal) bsModal.hide();
                                                }
                                                
                                                // Refresh all tables
                                                setTimeout(() => {
                                                    if (typeof refreshAllReconOrdersData === 'function') {
                                                        refreshAllReconOrdersData({ showToast: false, showProgress: true });
                                                    }
                                                }, 200);
                                            } else {
                                                showToast(forceData.message || '<?= lang('App.error_creating_order') ?>', 'error');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Force create error:', error);
                                            showToast('<?= lang('App.network_error') ?>', 'error');
                                        });
                                    }
                                });
                            } else {
                                // Fallback for when SweetAlert2 is not available
                                if (confirm(data.message + '\n\n<?= lang('App.create_anyway_question') ?>')) {
                                    // Re-submit with force_create flag
                                    const forceFormData = new FormData(form);
                                    forceFormData.set('force_create', '1');
                                    
                                    fetch(form.action, {
                                        method: 'POST',
                                        body: forceFormData
                                    })
                                    .then(response => response.json())
                                    .then(forceData => {
                                        if (forceData.success) {
                                            showToast(forceData.message || '<?= lang('App.order_created_successfully') ?>', 'success');
                                            
                                            // Close modal and refresh data
                                            const modal = document.getElementById('reconOrderModal');
                                            if (modal && typeof bootstrap !== 'undefined') {
                                                const bsModal = bootstrap.Modal.getInstance(modal);
                                                if (bsModal) bsModal.hide();
                                            }
                                            
                                            setTimeout(() => {
                                                if (typeof refreshAllReconOrdersData === 'function') {
                                                    refreshAllReconOrdersData({ showToast: false, showProgress: true });
                                                }
                                            }, 200);
                                        } else {
                                            showToast(forceData.message || '<?= lang('App.error_creating_order') ?>', 'error');
                                        }
                                    });
                                }
                            }
                    } else {
                        showToast(data.message || '<?= lang('App.create_failed') ?>', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Create error:', error);
                    showToast('<?= lang('App.error_occurred') ?>', 'error');
                })
                .finally(() => {
                    // Restore button state
                    if (submitBtn) {
                        submitBtn.innerHTML = '<?= lang('App.create_order') ?>';
                        submitBtn.disabled = false;
                    }
                });
            });
        }
        
        // Simple toast function
        function showToast(message, type) {
            if (typeof window.showToast === 'function') {
                window.showToast(message, type);
            } else {
                // Fallback
                alert(message);
            }
        }
        
        // Check if modal has inventory data and pre-populate fields
        function checkAndPopulateInventoryData() {
            console.log('🔍 Checking for inventory data...');
            const modal = document.getElementById('reconOrderModal');
            console.log('📋 Modal element:', modal);
            
            if (modal) {
                const inventoryData = $(modal).data('inventory-data');
                console.log('📦 Inventory data found:', inventoryData);
                
                if (inventoryData) {
                    console.log('🔄 Pre-populating form with inventory data:', inventoryData);
                    
                    // Mark as inventory source
                    const fromInventoryField = document.getElementById('from_inventory_field');
                    const sourceTypeField = document.getElementById('source_type_field');
                    if (fromInventoryField) {
                        fromInventoryField.value = '1';
                        console.log('✅ Set from_inventory to: 1 (inventory source)');
                    }
                    if (sourceTypeField) {
                        sourceTypeField.value = 'inventory';
                        console.log('✅ Set source_type to: inventory (inventory source)');
                    }
                    
                    // Pre-populate fields
                    if (inventoryData.stock && document.getElementById('stock')) {
                        document.getElementById('stock').value = inventoryData.stock;
                    }
                    
                    if (inventoryData.vehicle && document.getElementById('vehicle')) {
                        document.getElementById('vehicle').value = inventoryData.vehicle;
                    }
                    
                    if (inventoryData.service_date && document.getElementById('service_date')) {
                        document.getElementById('service_date').value = inventoryData.service_date;
                    }
                    
                    if (inventoryData.notes && document.getElementById('notes')) {
                        document.getElementById('notes').value = inventoryData.notes;
                    }
                    
                    console.log('✅ Form pre-populated with inventory data and marked as inventory source');
                }
            }
        }
        
        // Call the function to check for inventory data with delay to ensure modal is ready
        setTimeout(function() {
        checkAndPopulateInventoryData();
        }, 100);
        
        // Also check when the modal is fully shown (in case the data is set after initial load)
        $(document).ready(function() {
            $('#reconOrderModal').on('shown.bs.modal', function() {
                console.log('🔔 Modal shown event triggered, checking inventory data again...');
                setTimeout(function() {
                    checkAndPopulateInventoryData();
                }, 50);
            });
        });
        
        // Initialize Bootstrap tooltips
        initializeTooltips();
        
        console.log('✅ Modal form initialized with native HTML dropdowns and Bootstrap tooltips');
        
    }, 100); // End setTimeout

// Function to initialize Bootstrap tooltips
function initializeTooltips() {
    // Initialize tooltips using Bootstrap 5 syntax
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = document.querySelectorAll('#reconOrderModal [data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        console.log('✅ Bootstrap tooltips initialized for modal');
    } else {
        console.warn('⚠️ Bootstrap tooltips not available');
    }
}
})(); // IIFE - Execute immediately

// Global function to populate form fields (for external use)
window.populateReconOrderForm = function(data) {
    console.log('🔄 Populating recon order form with data:', data);
    
    // Populate client
    if (data.client_id && document.getElementById('recon_order_client_id')) {
        document.getElementById('recon_order_client_id').value = data.client_id;
        // Trigger change event to load services
        document.getElementById('recon_order_client_id').dispatchEvent(new Event('change'));
    }
    
    // Populate other fields
    if (data.stock && document.getElementById('stock')) {
        document.getElementById('stock').value = data.stock;
    }
    
    if (data.vin_number && document.getElementById('vin_number')) {
        document.getElementById('vin_number').value = data.vin_number;
        // Trigger input event for VIN decoding
        document.getElementById('vin_number').dispatchEvent(new Event('input'));
    }
    
    if (data.vehicle && document.getElementById('vehicle')) {
        document.getElementById('vehicle').value = data.vehicle;
    }
    
    if (data.service_date && document.getElementById('service_date')) {
        document.getElementById('service_date').value = data.service_date;
    }
    
    if (data.notes && document.getElementById('notes')) {
        document.getElementById('notes').value = data.notes;
    }
    
    // Populate service after a delay to allow services to load
    if (data.service_id) {
        setTimeout(function() {
            if (document.getElementById('recon_order_service_id')) {
                document.getElementById('recon_order_service_id').value = data.service_id;
            }
        }, 500);
    }
    
    // Populate status
    if (data.status && document.getElementById('recon_order_status')) {
        document.getElementById('recon_order_status').value = data.status;
    }
    
    console.log('✅ Form populated successfully with native HTML elements');
};

// VIN Barcode Scanner Implementation for ReconOrders
window.ReconVinBarcodeScanner = (function() {
    let isScanning = false;
    let scannerModal = null;
    let currentMode = 'barcode'; // 'barcode' or 'qr'
    let qrScanner = null;
    
    // Check if device is mobile/tablet
    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
               (navigator.maxTouchPoints && navigator.maxTouchPoints > 2 && /MacIntel/.test(navigator.platform));
    }
    
    // Check camera permission status
    function checkCameraPermission() {
        if (!navigator.permissions) {
            return Promise.resolve('prompt');
        }
        
        return navigator.permissions.query({ name: 'camera' })
            .then(permissionStatus => permissionStatus.state)
            .catch(() => 'prompt');
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
        console.log('📱 Checking device capabilities...');
        
        // Check if device has camera capability
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.log('📱 Camera not supported on this device');
            return;
        }
        
        if (isMobileDevice()) {
            console.log('📱 Mobile device detected, showing scan button');
            const scanBtn = document.getElementById('scanVinBtn');
            if (scanBtn) {
                scanBtn.style.display = 'block';
                scanBtn.addEventListener('click', startScanner);
            }
        } else {
            // For desktop, still show but with different behavior
            console.log('🖥️ Desktop device detected, showing scan button for webcam use');
            const scanBtn = document.getElementById('scanVinBtn');
            if (scanBtn) {
                scanBtn.style.display = 'block';
                scanBtn.addEventListener('click', startScanner);
            }
        }
    }
    
    // Show error message
    function showError(message) {
        if (typeof window.showToast === 'function') {
            window.showToast(message, 'error');
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: '<?= lang('App.scanner_error') ?>',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            alert('<?= lang('App.scanner_error') ?>: ' + message);
        }
    }
    
    // Start the VIN scanner
    function startScanner() {
        console.log('📷 Starting VIN scanner...');
        
        const scanBtn = document.getElementById('scanVinBtn');
        if (scanBtn) {
            scanBtn.disabled = true;
            scanBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><?= lang('App.starting_scanner') ?>';
        }
        
        // Check camera permission first
        checkCameraPermission().then(permission => {
            if (permission === 'denied') {
                resetScanButton();
                showError('<?= lang('App.camera_permission_denied') ?>');
                return;
            }
            
            // Create scanner modal if it doesn't exist
            if (!scannerModal) {
                scannerModal = document.getElementById('vinScannerModal');
            }
            
            if (!scannerModal) {
                resetScanButton();
                showError('<?= lang('App.scanner_interface_not_found') ?>');
                return;
            }
            
            // Show scanner modal
            scannerModal.style.display = 'flex';
            isScanning = true;
            
            // Set up close button
            const closeBtn = document.getElementById('closeScannerBtn');
            if (closeBtn) {
                closeBtn.onclick = stopScanner;
            }
            
            // Set up mode switch button
            const modeSwitchBtn = document.getElementById('modeSwitchBtn');
            if (modeSwitchBtn) {
                modeSwitchBtn.onclick = switchScanningMode;
            }
            
            // Check if QuaggaJS loading failed
            if (window.QuaggaLoadFailed) {
                console.error('📷 QuaggaJS loading failed - all CDN sources failed');
                resetScanButton();
                showError('<?= lang('App.barcode_scanner_library_failed') ?>');
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
                        showError('<?= lang('App.barcode_scanner_library_failed') ?>');
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
                        showError('<?= lang('App.scanner_library_failed_to_load') ?>');
                        stopScanner();
                    }
                }, 100);
                
                return;
            }
            
            console.log('📷 Quagga already loaded, initializing...');
            initializeQuagga();
        });
    }
    
    // Switch between scanning modes
    function switchScanningMode() {
        console.log('🔄 Switching scanning mode from:', currentMode);
        
        // Stop current mode
        stopCurrentMode();
        
        // Switch mode
        currentMode = currentMode === 'barcode' ? 'qr' : 'barcode';
        
        // Update UI
        const modeSwitchBtn = document.getElementById('modeSwitchBtn');
        const scannerIcon = document.getElementById('scanner-icon');
        const scannerTitle = document.getElementById('scanner-title');
        const instructions = document.querySelector('.scanner-instructions');
        
        switch (currentMode) {
            case 'qr':
                if (modeSwitchBtn) {
                    modeSwitchBtn.innerHTML = '<i class="ri-qr-scan-line"></i> Barcode';
                    modeSwitchBtn.title = '<?= lang('App.switch_to_barcode_scanning') ?>';
                }
                if (scannerIcon) scannerIcon.className = 'ri-qr-scan-2-line me-2';
                if (scannerTitle) scannerTitle.innerHTML = '<i class="ri-qr-scan-2-line me-2"></i><?= lang('App.scan_qr_code') ?>';
                if (instructions) {
                    instructions.innerHTML = '<i class="ri-qr-code-line me-2"></i><?= lang('App.point_camera_to_qr_code') ?>';
                }
                initializeQRScanner();
                break;
                
            case 'barcode':
            default:
                if (modeSwitchBtn) {
                    modeSwitchBtn.innerHTML = '<i class="ri-qr-scan-2-line"></i> QR';
                    modeSwitchBtn.title = '<?= lang('App.switch_to_qr_scanning') ?>';
                }
                if (scannerIcon) scannerIcon.className = 'ri-qr-scan-line me-2';
                if (scannerTitle) scannerTitle.innerHTML = '<i class="ri-qr-scan-line me-2"></i><?= lang('App.scan_vin_barcode') ?>';
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
            qrScanner.stream.getTracks().forEach(track => track.stop());
            qrScanner = null;
        }
        
        // Clear scanner target
        const scannerTarget = document.querySelector('#scanner-target');
        if (scannerTarget) {
            scannerTarget.innerHTML = '';
        }
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
            showError('<?= lang('App.failed_camera_access_qr') ?>: ' + err.message);
            stopScanner();
        });
    }
    
    // Process QR codes using jsQR
    function startQRProcessing(video, canvas, ctx) {
        let isProcessingQR = false;
        
        function processFrame() {
            if (!isScanning || currentMode !== 'qr') return;
            
            if (video.readyState === video.HAVE_ENOUGH_DATA && !isProcessingQR) {
                isProcessingQR = true;
                
                canvas.height = video.videoHeight;
                canvas.width = video.videoWidth;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                
                // Use jsQR if available, otherwise try basic processing
                if (typeof jsQR !== 'undefined') {
                    const qrCode = jsQR(imageData.data, imageData.width, imageData.height);
                    
                    if (qrCode && qrCode.data) {
                        console.log('📱 QR Code detected:', qrCode.data);
                        processQRData(qrCode.data);
                        return;
                    }
                } else {
                    // Fallback: try to extract VIN from any text in QR
                    console.log('📱 jsQR not available, using fallback processing');
                }
                
                isProcessingQR = false;
            }
            
            if (isScanning && currentMode === 'qr') {
                requestAnimationFrame(processFrame);
            }
        }
        
        processFrame();
    }
    
    // Process QR code data to extract VIN
    function processQRData(qrData) {
        console.log('📱 Processing QR data:', qrData);
        
        let vinCandidate = null;
        
        // Direct VIN check
        if (isValidVin(qrData)) {
            vinCandidate = qrData;
        } else {
            // Try to extract VIN from structured data (JSON, etc.)
            try {
                const jsonData = JSON.parse(qrData);
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
            const vinInput = document.getElementById('vin_number');
            if (vinInput) {
                vinInput.value = vinCandidate.toUpperCase();
                const event = new Event('input', { bubbles: true });
                vinInput.dispatchEvent(event);
            }
            
            if (window.showToast) {
                window.showToast('<?= lang('App.vin_extracted_from_qr') ?>: ' + vinCandidate, 'success');
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'VIN Found!',
                    text: '<?= lang('App.vin_extracted_from_qr') ?>: ' + vinCandidate,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
            
            stopScanner();
        } else {
            console.log('📱 <?= lang('App.no_valid_vin_found_in_qr') ?>');
        }
    }
    
    // Initialize Quagga barcode scanner
    function initializeQuagga() {
        console.log('📷 Initializing QuaggaJS...');
        
        if (typeof window.Quagga === 'undefined') {
            console.error('📷 Quagga still undefined at initialization');
            resetScanButton();
            showError('<?= lang('App.scanner_not_ready') ?>');
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
                console.error('📷 QuaggaJS initialization error:', err);
                resetScanButton();
                showError('<?= lang('App.camera_initialization_failed') ?>: ' + err.message);
                stopScanner();
                return;
            }
            
            window.Quagga.start();
        });
        
        window.Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            console.log('📷 Barcode detected:', code, 'Format:', result.codeResult.format);
            
            // Validate VIN format
            if (isValidVin(code)) {
                const vinInput = document.getElementById('vin_number');
                if (vinInput) {
                    vinInput.value = code.toUpperCase();
                    const event = new Event('input', { bubbles: true });
                    vinInput.dispatchEvent(event);
                }
                
                if (window.showToast) {
                    window.showToast('<?= lang('App.vin_scanned_successfully') ?>: ' + code, 'success');
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'VIN Scanned!',
                        text: '<?= lang('App.vin_scanned_successfully') ?>: ' + code,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
                
                stopScanner();
            } else {
                console.log('📷 Code does not match VIN format, continuing scan...', code);
            }
        });
    }
    
    // Stop the VIN scanner
    function stopScanner() {
        if (!isScanning) return;
        
        console.log('📷 Stopping VIN scanner...');
        isScanning = false;
        
        // Stop current mode
        stopCurrentMode();
        
        // Reset mode to barcode
        currentMode = 'barcode';
        
        if (scannerModal) {
            scannerModal.style.display = 'none';
        }
        
        // Reset UI elements
        const modeSwitchBtn = document.getElementById('modeSwitchBtn');
        const scannerIcon = document.getElementById('scanner-icon');
        const scannerTitle = document.getElementById('scanner-title');
        const instructions = document.querySelector('.scanner-instructions');
        
        if (modeSwitchBtn) {
            modeSwitchBtn.innerHTML = '<i class="ri-qr-scan-2-line"></i> QR';
            modeSwitchBtn.title = '<?= lang('App.switch_to_qr_scanning') ?>';
        }
        if (scannerIcon) scannerIcon.className = 'ri-qr-scan-line me-2';
        if (scannerTitle) scannerTitle.innerHTML = '<i class="ri-qr-scan-line me-2"></i><?= lang('App.scan_vin_barcode') ?>';
        if (instructions) {
            instructions.innerHTML = '<i class="ri-qr-code-line me-2"></i><?= lang('App.point_camera_to_vin_barcode') ?>';
        }
        
        // Reset scan button
        resetScanButton();
    }
    
    // Validate VIN format
    function isValidVin(vin) {
        if (!vin || typeof vin !== 'string') return false;
        
        // Remove spaces and convert to uppercase
        vin = vin.replace(/\s/g, '').toUpperCase();
        
        // For testing: accept any code with 10+ characters that looks like VIN
        if (vin.length >= 10 && vin.length <= 20) {
            // Must be alphanumeric
            if (/^[A-Z0-9]+$/.test(vin)) {
                return true;
            }
        }
        
        // VIN must be exactly 17 characters
        if (vin.length !== 17) {
            return false;
        }
        
        // VIN should not contain I, O, Q
        if (/[IOQ]/.test(vin)) {
            return false;
        }
        
        // VIN should be alphanumeric
        if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) {
            return false;
        }
        
        return true;
    }
    
    // Public API
    return {
        init: initMobileDetection,
        start: startScanner,
        stop: stopScanner,
        isScanning: () => isScanning
    };
})();

// Load QuaggaJS library with multiple fallbacks
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
            window.QuaggaLoaded = true;
        };
        
        script.onerror = function() {
            console.warn('📷 Failed to load QuaggaJS from:', cdnSources[currentIndex]);
            currentIndex++;
            tryLoad();
        };
        
        document.head.appendChild(script);
    }
    
    tryLoad();
})();

// Load jsQR for QR code support
(function loadJsQR() {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js';
    script.onload = function() {
        console.log('📱 jsQR loaded successfully');
    };
    script.onerror = function() {
        console.warn('📱 Failed to load jsQR - QR scanning will have limited functionality');
    };
    document.head.appendChild(script);
})();

// Initialize the scanner when the modal loads
setTimeout(function() {
    if (window.ReconVinBarcodeScanner) {
        window.ReconVinBarcodeScanner.init();
    }
}, 500);
</script> 