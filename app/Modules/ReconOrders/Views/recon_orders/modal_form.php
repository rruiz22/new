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

    /* VIN Decoding Styles */
    .vin-input-container {
        position: relative;
    }

    .vin-status {
        display: block;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        min-height: 1rem;
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
        border-color: #0dcaf0 !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 202, 240, 0.25) !important;
        background-color: #e3f2fd !important;
        animation: vinDecodingPulse 1.5s ease-in-out infinite;
    }

    .vin-success {
        border-color: #198754 !important;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important;
    }

    .vin-error {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    .vin-warning {
        border-color: #fd7e14 !important;
        box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25) !important;
    }

    .vin-decoded {
        background-color: #d1e7dd !important;
        border-color: #198754 !important;
        animation: vinDecodeSuccess 0.5s ease-out;
    }

    @keyframes vinDecodingPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    @keyframes vinDecodeSuccess {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }

    /* VIN Toast Notification Styles */
    .vin-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        max-width: 350px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .vin-toast.vin-toast-show {
        opacity: 1;
        transform: translateX(0);
    }

    .vin-toast-content {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: white;
        font-size: 14px;
        line-height: 1.4;
    }

    .vin-toast-error {
        background-color: #dc3545;
    }

    .vin-toast-warning {
        background-color: #fd7e14;
    }

    .vin-toast-info {
        background-color: #0dcaf0;
    }

    .vin-toast-icon {
        margin-right: 12px;
        font-size: 18px;
        opacity: 0.9;
    }

    .vin-toast-message {
        flex: 1;
        font-weight: 500;
    }

    .vin-toast-close {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
        padding: 0;
        margin-left: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
    }

    .vin-toast-close:hover {
        opacity: 1;
    }
</style>

<form id="reconOrderForm" action="<?= base_url('recon_orders/store') ?>" method="post">
    
    <!-- Client and Service Date Section -->
    <div class="form-section">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="client_id" class="form-label"><?= lang('App.client') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" id="client_id" name="client_id" required>
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
                    <label for="vin_number" class="form-label"><?= lang('App.vin') ?> <span class="text-danger">*</span></label>
                    <div class="vin-input-container">
                        <input type="text" class="form-control" id="vin_number" name="vin_number" 
                               placeholder="<?= lang('App.enter_vin_placeholder') ?>" maxlength="17" required>
                        <small id="form-vin-status" class="vin-status"></small>
                    </div>
                    <small class="text-muted"><?= lang('App.vin_help_text') ?></small>
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
                    <label for="service_id" class="form-label"><?= lang('App.service') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" id="service_id" name="service_id" required>
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
                    <label for="status" class="form-label"><?= lang('App.status') ?></label>
                    <select class="form-select" id="status" name="status">
                        <option value="pending" selected><?= lang('App.pending') ?></option>
                        <option value="in_progress"><?= lang('App.in_progress') ?></option>
                        <option value="completed"><?= lang('App.completed') ?></option>
                        <option value="cancelled"><?= lang('App.cancelled') ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

</form>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <?= lang('App.cancel') ?>
    </button>
    <button type="submit" form="reconOrderForm" class="btn btn-primary" id="createOrderBtn">
        <?= lang('App.create_order') ?>
    </button>
</div>

<script>
// Execute immediately when modal loads (not DOMContentLoaded since modal loads dynamically)
(function() {
    console.log('🔧 Form Modal JavaScript executing...');
    
    // Add small delay to ensure DOM elements are fully loaded
    setTimeout(function() {
        console.log('🔧 Initializing form modal elements after delay...');
        
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
        const clientSelect = document.getElementById('client_id');
        const serviceSelect = document.getElementById('service_id');
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
            const existingToast = document.querySelector('.vin-toast');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.className = `vin-toast vin-toast-${type}`;
            toast.innerHTML = `
                <div class="vin-toast-content">
                    <div class="vin-toast-icon">
                        ${type === 'error' ? '⚠️' : type === 'warning' ? '⚠️' : 'ℹ️'}
                    </div>
                    <div class="vin-toast-message">${message}</div>
                    <button class="vin-toast-close" onclick="this.parentElement.parentElement.remove()">
                        ×
                    </button>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 5000);

            setTimeout(() => {
                toast.classList.add('vin-toast-show');
            }, 100);
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
                    serviceSelect.innerHTML = '<option value=""><?= lang('App.select') ?> <?= lang('App.service') ?></option>';
                    
                    data.data.forEach(service => {
                        if (service.is_active && service.show_in_form) {
                            const option = document.createElement('option');
                            option.value = service.id;
                            option.textContent = service.name + (service.price ? ' - $' + parseFloat(service.price).toFixed(2) : '');
                            option.setAttribute('data-price', service.price || '');
                            
                            serviceSelect.appendChild(option);
                        }
                    });
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
                const clientId = document.getElementById('client_id').value;
                const serviceId = document.getElementById('service_id').value;
                const stock = document.getElementById('stock').value;
                const vinNumber = document.getElementById('vin_number').value;
                const vehicle = document.getElementById('vehicle').value;
                
                if (!clientId || !serviceId || !stock || !vinNumber || !vehicle) {
                    showToast('error', '<?= lang('App.fill_required_fields') ?>');
                    return;
                }
                
                // Show loading state
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span><?= lang('App.creating') ?>...';
                    submitBtn.disabled = true;
                }
                
                // Prepare form data
                const formData = new FormData(form);
                
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
                        showToast('success', data.message || '<?= lang('App.order_created_successfully') ?>');
                        
                        // Close modal and refresh data
                        const modal = document.getElementById('reconOrderModal');
                        if (modal && typeof bootstrap !== 'undefined') {
                            const bsModal = bootstrap.Modal.getInstance(modal);
                            if (bsModal) bsModal.hide();
                        }
                        
                        // Refresh page data - prefer index.php refresh function over page reload
                        setTimeout(() => {
                            if (typeof refreshAllReconOrdersData === 'function') {
                                // Use index.php refresh function - refreshes all tables without page reload
                                console.log('🔄 Refreshing ReconOrders tables after creating order');
                                refreshAllReconOrdersData({ showToast: false }); // Don't show toast since we already showed success
                            } else if (typeof refreshReconOrderViewData === 'function') {
                                // Fallback to view.php refresh function
                                console.log('🔄 Using view.php refresh function');
                                refreshReconOrderViewData();
                            } else {
                                // Last resort - full page reload
                                console.log('⚠️ No refresh function available, reloading page');
                                window.location.reload();
                            }
                        }, 500);
                    } else {
                        showToast('error', data.message || '<?= lang('App.create_failed') ?>');
                    }
                })
                .catch(error => {
                    console.error('Create error:', error);
                    showToast('error', '<?= lang('App.error_occurred') ?>');
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
        function showToast(type, message) {
            if (typeof window.showToast === 'function') {
                window.showToast(type, message);
            } else {
                // Fallback
                alert(message);
            }
        }
        
    }, 100); // End setTimeout
})(); // IIFE - Execute immediately
</script> 