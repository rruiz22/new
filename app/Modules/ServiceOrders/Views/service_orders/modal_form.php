<!-- SERVICE ORDER MODAL FORM -->
<?php
// Detectar si estamos en modo edición
$isEditMode = isset($isEditMode) && $isEditMode === true;
$order = $isEditMode && isset($order) ? $order : null;

?>
<style>
    .modal-dialog {
        max-width: 700px;
        margin: 1.75rem auto;
    }

    .modal-content {
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        padding: 1.5rem 1.5rem 0 1.5rem;
        border-bottom: none;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 0 1.5rem 1.5rem 1.5rem;
        border-top: none;
        gap: 0.75rem;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        color: #374151;
    }

    .form-control,
    .form-select {
        font-size: 0.875rem;
        padding: 0.75rem;
        min-height: 42px;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background-color: #fff;
        transition: all 0.2s ease-in-out;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.1);
        outline: 0;
    }

    .form-section {
        margin-bottom: 1.75rem;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    /* Espaciado mejorado para filas */
    .row {
        margin-left: -0.75rem;
        margin-right: -0.75rem;
    }

    .row > * {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    /* Botones del modal */
    .modal-footer .btn {
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        border-radius: 0.5rem;
        min-width: 120px;
    }

    .btn-primary {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .btn-primary:hover {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    .btn-secondary {
        background-color: #6b7280;
        border-color: #6b7280;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        border-color: #4b5563;
    }

    /* Texto requerido */
    .text-danger {
        color: #ef4444 !important;
    }

    /* Responsive ajustado */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .row {
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }

        .row > * {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }

    @media (max-width: 576px) {
        .form-section {
            margin-bottom: 1.25rem;
        }

        .modal-footer {
            flex-direction: column-reverse;
        }

        .modal-footer .btn {
            width: 100%;
        }
    }

    /* VIN Styles */
    .vin-input-container {
        position: relative;
    }

    /* Add missing icon styles */
    .ri-loader-line.spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
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

    /* Enhanced VIN input states */
    .vin-decoding {
        background-color: #e3f2fd !important;
        border-color: #2196f3 !important;
        animation: vinDecodingPulse 1.5s ease-in-out infinite;
    }

    @keyframes vinDecodingPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .vin-success {
        background-color: #d4edda !important;
        border-color: #28a745 !important;
        animation: vinSuccess 0.6s ease;
    }

    @keyframes vinSuccess {
        0% { background-color: #ffffff; }
        50% { background-color: #d4edda; }
        100% { background-color: #d4edda; }
    }

    .vin-error {
        background-color: #f8d7da !important;
        border-color: #dc3545 !important;
        animation: vinError 0.6s ease;
    }

    @keyframes vinError {
        0%, 20%, 40%, 60%, 80% { transform: translateX(-2px); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(2px); }
        100% { transform: translateX(0); }
    }

    .vin-warning {
        background-color: #fff3cd !important;
        border-color: #ffc107 !important;
    }

    /* Duplicate warning styles */
    .duplicate-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 0.5rem;
        border-radius: 0.375rem;
        margin-top: 0.25rem;
        font-size: 0.875rem;
    }

    .duplicate-warning .badge {
        font-size: 0.7rem;
        margin-right: 0.25rem;
    }

    .duplicate-orders-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.5rem;
        margin-top: 0.5rem;
    }

    .duplicate-order-item {
        padding: 0.5rem;
        border-bottom: 1px solid #e9ecef;
        font-size: 0.875rem;
    }

    .duplicate-order-item:last-child {
        border-bottom: none;
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
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.95);
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 10001;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .scanner-header h5 {
        margin: 0;
        color: #333;
        font-weight: 600;
    }

    .scanner-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .scanner-container {
        flex: 1;
        position: relative;
        margin-top: 80px;
        margin-bottom: 120px;
        overflow: hidden;
    }

    #scanner-target {
        width: 100%;
        height: calc(100vh - 200px);
        position: relative;
        overflow: hidden;
        background: #000;
    }

    #scanner-target video,
    #scanner-target canvas {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .scanner-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 100px;
        border: 2px solid #fff;
        border-radius: 8px;
        box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.3);
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
        border: 2px solid transparent;
        border-radius: 8px;
        background: linear-gradient(45deg, #ff0000, #00ff00, #0000ff, #ff0000);
        background-size: 400% 400%;
        animation: rainbow 2s linear infinite;
        z-index: -1;
    }

    @keyframes rainbow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
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

<form id="serviceOrderForm" method="post">
    <div class="row">
        <!-- Client Selection -->
        <div class="col-md-6 form-section">
            <label for="client_id" class="form-label"><?= lang('App.client') ?> <span class="text-danger">*</span></label>
            <select class="form-select" id="client_id" name="client_id" required>
                <option value=""><?= lang('App.select_client') ?></option>
                <?php if (isset($clients) && !empty($clients)): ?>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['id'] ?>" <?= ($isEditMode && $order && isset($order['client_id']) && $order['client_id'] == $client['id']) ? 'selected' : '' ?>><?= esc($client['name']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Contact Selection -->
        <div class="col-md-6 form-section">
            <label for="contact_id" class="form-label"><?= lang('App.assigned_contact') ?> <span class="text-danger">*</span></label>
            <select class="form-select" id="contact_id" name="contact_id" required disabled>
                <option value="">Select a client first</option>
                <?php if ($isEditMode && isset($contacts) && !empty($contacts)): ?>
                    <?php foreach ($contacts as $contact): ?>
                        <option value="<?= $contact['id'] ?>" <?= ($order && isset($order['contact_id']) && $order['contact_id'] == $contact['id']) ? 'selected' : '' ?>><?= esc($contact['name']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="row">
        <!-- RO Number -->
        <div class="col-md-4 form-section">
            <label for="ro_number" class="form-label">RO# <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="ro_number" name="ro_number" placeholder="Enter RO Number" required value="<?= $isEditMode ? esc($order['ro_number'] ?? '') : '' ?>">
        </div>

        <!-- PO Number -->
        <div class="col-md-4 form-section">
            <label for="po_number" class="form-label">PO# <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="po_number" name="po_number" placeholder="Enter PO Number" required value="<?= $isEditMode ? esc($order['po_number'] ?? '') : '' ?>">
        </div>

        <!-- Tag Number -->
        <div class="col-md-4 form-section">
            <label for="tag_number" class="form-label">Tag# <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="tag_number" name="tag_number" placeholder="Enter Tag Number" required value="<?= $isEditMode ? esc($order['tag_number'] ?? '') : '' ?>">
        </div>
    </div>

    <div class="row">
        <!-- VIN -->
        <div class="col-md-6 form-section">
            <label for="vin" class="form-label"><?= lang('App.vin') ?> <span class="text-danger">*</span></label>
            <div class="vin-input-container position-relative">
                <input type="text" class="form-control" id="vin" name="vin" placeholder="Enter VIN" required maxlength="17" value="<?= $isEditMode ? esc($order['vin'] ?? '') : '' ?>">

                <!-- Barcode Scanner Button (Only visible on mobile/tablet) -->
                <button type="button" id="scanVinBtn" class="btn btn-outline-primary btn-sm position-absolute scan-vin-btn d-none">
                    <i class="ri-qr-scan-line me-1"></i>
                    <span class="scan-btn-text">Scan</span>
                </button>

                <span class="vin-status" id="vin-status"></span>
            </div>
            <small class="text-muted">17-character Vehicle Identification Number</small>
        </div>

        <!-- Vehicle -->
        <div class="col-md-6 form-section">
            <label for="vehicle" class="form-label"><?= lang('App.vehicle') ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="vehicle" name="vehicle" placeholder="Vehicle info will be filled automatically" required value="<?= $isEditMode ? esc($order['vehicle'] ?? '') : '' ?>">
        </div>
    </div>

    <div class="row">
        <!-- Service Selection -->
        <div class="col-md-12 form-section">
            <label for="service_id" class="form-label"><?= lang('App.service') ?> <span class="text-danger">*</span></label>
            <select class="form-select" id="service_id" name="service_id" required disabled>
                <option value="">Select a client first</option>
                <?php if ($isEditMode && isset($services) && !empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['id'] ?>" <?= ($order && isset($order['service_id']) && $order['service_id'] == $service['id']) ? 'selected' : '' ?>><?= esc($service['service_name']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="row">
        <!-- Date -->
        <div class="col-md-6 form-section">
            <label for="date" class="form-label"><?= lang('App.date') ?> <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="date" name="date" required value="<?= $isEditMode ? esc($order['date'] ?? '') : '' ?>">
            <small class="form-text text-muted">Today's date only available before 5:00 PM</small>
        </div>

        <!-- Time -->
        <div class="col-md-6 form-section">
            <label for="time" class="form-label"><?= lang('App.time') ?> <span class="text-danger">*</span></label>
            <select class="form-select" id="time" name="time" required>
                <option value="">Select time</option>
                <?php $currentTime = $isEditMode && $order ? ($order['time'] ?? '') : ''; ?>
                <option value="08:00" <?= ($isEditMode && $currentTime == '08:00') ? 'selected' : '' ?>>8:00 AM</option>
                <option value="09:00" <?= ($isEditMode && $currentTime == '09:00') ? 'selected' : '' ?>>9:00 AM</option>
                <option value="10:00" <?= ($isEditMode && $currentTime == '10:00') ? 'selected' : '' ?>>10:00 AM</option>
                <option value="11:00" <?= ($isEditMode && $currentTime == '11:00') ? 'selected' : '' ?>>11:00 AM</option>
                <option value="12:00" <?= ($isEditMode && $currentTime == '12:00') ? 'selected' : '' ?>>12:00 PM</option>
                <option value="13:00" <?= ($isEditMode && $currentTime == '13:00') ? 'selected' : '' ?>>1:00 PM</option>
                <option value="14:00" <?= ($isEditMode && $currentTime == '14:00') ? 'selected' : '' ?>>2:00 PM</option>
                <option value="15:00" <?= ($isEditMode && $currentTime == '15:00') ? 'selected' : '' ?>>3:00 PM</option>
                <option value="16:00" <?= ($isEditMode && $currentTime == '16:00') ? 'selected' : '' ?>>4:00 PM</option>
            </select>
            <small class="form-text text-muted">Available times: 8:00 AM - 4:00 PM</small>
        </div>
    </div>

    <div class="row">
        <!-- Instructions -->
        <div class="col-md-12 form-section">
            <label for="instructions" class="form-label"><?= lang('App.instructions') ?></label>
            <textarea class="form-control" id="instructions" name="instructions" rows="3" placeholder="Enter special instructions..."><?= $isEditMode ? esc($order['instructions'] ?? '') : '' ?></textarea>
        </div>
    </div>

    <!-- Notes field hidden as requested -->
    <div class="row" style="display: none;">
        <div class="col-md-12 form-section">
            <label for="notes" class="form-label"><?= lang('App.notes') ?></label>
            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter additional notes..."><?= $isEditMode ? esc($order['notes'] ?? '') : '' ?></textarea>
        </div>
    </div>

    <!-- Hidden status field - status can only be changed in view.php -->
    <input type="hidden" id="order_status" name="status" value="<?= $isEditMode && $order ? ($order['status'] ?? 'pending') : 'pending' ?>">

    </form>

<script>
// Global variables for service order modal
window.ServiceOrderModal = window.ServiceOrderModal || {
    isSubmitting: false,
    submitCount: 0
};

// VIN decoder translations - Global scope
const modalVinTranslations = {
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

// Initialize service order modal - called every time modal opens
function initializeServiceOrderModal() {
    const isEditMode = <?= $isEditMode ? 'true' : 'false' ?>;
    const orderData = <?= $isEditMode ? json_encode($order) : 'null' ?>;
    const currentUserType = '<?= $current_user_type ?? 'admin' ?>';

    // Prevent multiple initializations on the same form
    const form = $('#serviceOrderForm');
    if (form.hasClass('initialized')) {

        return;
    }

    form.addClass('initialized');

    // Store data globally for access in functions
    window.ServiceOrderModal.isEditMode = isEditMode;
    window.ServiceOrderModal.orderData = orderData;
    window.ServiceOrderModal.currentUserType = currentUserType;

    // Setup event handlers
    setupServiceOrderEventHandlers();

    // Initialize date and time restrictions
    initializeDateTimeRestrictions();

    // Set default date to today if not in edit mode and before 5 PM
    if (!isEditMode) {
        setDefaultDateTime();
    }

    // Initialize form if in edit mode
    if (isEditMode && orderData) {
        initializeEditMode(orderData);
    }

    // Initialize VIN decoding functionality
    setupVINDecoding();

    // Initialize duplicate validation
    setupDuplicateValidation();

    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Check if order status requires readonly fields
    setTimeout(() => {
        checkServiceOrderStatusAndSetReadonly();
    }, 300);

}

// Setup event handlers - called every time modal opens
function setupServiceOrderEventHandlers() {

    // Remove existing event handlers to prevent duplicates
    $('#client_id').off('change.serviceorder');
    $('#serviceOrderForm').off('submit.serviceorder');

    // Client change handler
    $('#client_id').on('change.serviceorder', function() {
        const clientId = $(this).val();

        if (clientId) {
            loadContactsAndServices(clientId);
        } else {
            resetContactsAndServices();
        }
    });

    // Form submission with duplicate handling
    $('#serviceOrderForm').on('submit.serviceorder', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation(); // Prevent multiple handlers

        const submitButton = $(this).find('button[type="submit"]');

        // Check if already submitting to prevent multiple submissions
        if (submitButton.prop('disabled') || submitButton.hasClass('submitting') || window.ServiceOrderModal.isSubmitting) {

            return false;
        }

        // Set global submitting flag
        window.ServiceOrderModal.isSubmitting = true;
        window.ServiceOrderModal.submitCount++;

        const originalButtonText = submitButton.html();

        // Mark as submitting and disable submit button
        submitButton.prop('disabled', true);
        submitButton.addClass('submitting');
        submitButton.html('<i class="mdi mdi-spin mdi-loading me-1"></i> Saving...');

        const formData = $(this).serialize();
        const isEditMode = window.ServiceOrderModal.isEditMode;
        const orderData = window.ServiceOrderModal.orderData;

        const url = isEditMode ? 
            `<?= base_url('service_orders/update') ?>/${orderData.id}` : 
            '<?= base_url('service_orders/store') ?>';

        $.post(url, formData)
            .done(function(response) {
                if (response.success) {
                    // Handle duplicate orders
                    if (response.has_duplicates) {

                        showDuplicateConfirmation(response.duplicates, formData, $('#serviceOrderForm'));
                        return;
                    }

                    $('#orderModal, #editServiceOrderModal').modal('hide');
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Refresh tables if function exists
                    if (typeof refreshAllServiceOrdersTables === 'function') {
                        refreshAllServiceOrdersTables();
                    } else {
                        location.reload();
                    }
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || `Failed to ${isEditMode ? 'update' : 'create'} service order`
                    });
                }
            })
            .fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: `Failed to ${isEditMode ? 'update' : 'create'} service order. Please try again.`
                });
            })
            .always(function() {
                // Clear global submitting flag
                window.ServiceOrderModal.isSubmitting = false;

                // Re-enable submit button and remove submitting state
                submitButton.prop('disabled', false);
                submitButton.removeClass('submitting');
                submitButton.html(originalButtonText);

            });
    });

    }

// Functions - Now globally accessible
    function initializeEditMode(order) {

        // Set client first
        if (order.client_id) {

            $('#client_id').val(order.client_id);

            // Load contacts and services for the client
            loadContactsAndServices(order.client_id);

            // Wait a moment for the AJAX to complete, then set the values
            setTimeout(() => {
                if (order.contact_id) {

                    $('#contact_id').val(order.contact_id);
                }

                if (order.service_id) {

                    $('#service_id').val(order.service_id);
                }
            }, 1000);
        }

        if (order.time) {

            $('#time').val(order.time);
        }

        // Ensure client change event still works for future changes
        $('#client_id').off('change.editmode').on('change.editmode', function() {
            const clientId = $(this).val();
            if (clientId) {
                loadContactsAndServices(clientId);
            } else {
                resetContactsAndServices();
            }
        });

    }

    function loadContactsAndServices(clientId) {
        const isEditMode = window.ServiceOrderModal.isEditMode;
        const orderData = window.ServiceOrderModal.orderData;

        // Load contacts for selected client
        $.get('<?= base_url('service_orders/getContactsForClient') ?>', { client_id: clientId })
            .done(function(response) {

                if (response.success) {
                    const contactSelect = $('#contact_id');
                    contactSelect.empty().append('<option value="">Select Contact</option>');

                    response.contacts.forEach(function(contact) {
                        const selected = (isEditMode && orderData && orderData.contact_id == contact.id) ? 'selected' : '';
                        contactSelect.append(`<option value="${contact.id}" ${selected}>${contact.name}</option>`);
                    });

                    contactSelect.prop('disabled', false);

                } else {
                    console.error('ServiceOrderModal: Failed to load contacts:', response.message);
                }
            })
            .fail(function(xhr, status, error) {
                console.error('ServiceOrderModal: AJAX error loading contacts:', error);
            });

        // Load services for selected client
        $.get('<?= base_url('service_orders/getServicesForClient') ?>', { client_id: clientId })
            .done(function(response) {

                if (response.success) {
                    const serviceSelect = $('#service_id');
                    serviceSelect.empty().append('<option value="">Select Service</option>');

                    response.services.forEach(function(service) {
                        const selected = (isEditMode && orderData && orderData.service_id == service.id) ? 'selected' : '';
                        serviceSelect.append(`<option value="${service.id}" ${selected}>${service.service_name}</option>`);
                    });

                    serviceSelect.prop('disabled', false);

                } else {
                    console.error('ServiceOrderModal: Failed to load services:', response.message);
                }
            })
            .fail(function(xhr, status, error) {
                console.error('ServiceOrderModal: AJAX error loading services:', error);
            });
    }

    function resetContactsAndServices() {
                    $('#contact_id').empty().append('<option value="">Select a client first</option>').prop('disabled', true);
        $('#service_id').empty().append('<option value="">Select a client first</option>').prop('disabled', true);
}

function initializeDateTimeRestrictions() {

    // Remove existing event listeners to prevent duplicates
    $('#date').off('change.datetimerestrictions');

    // Set date restrictions based on current time
    updateDateRestrictions();

    // Validate date selection (no need for time validation since it's a select with predefined options)
    $('#date').on('change.datetimerestrictions', function() {
        validateDateSelection(this);
    });

    // Clear any existing interval and set new one
    if (window.ServiceOrderModal.dateInterval) {
        clearInterval(window.ServiceOrderModal.dateInterval);
    }

    // Update date restrictions every minute
    window.ServiceOrderModal.dateInterval = setInterval(updateDateRestrictions, 60000);

}

function setDefaultDateTime() {
    const now = new Date();
    const currentHour = now.getHours();

    // Set default date based on current time
    if (currentHour < 17) { // Before 5 PM
        $('#date').val(now.toISOString().split('T')[0]);
    } else { // After 5 PM
        const tomorrow = new Date(now);
        tomorrow.setDate(tomorrow.getDate() + 1);
        $('#date').val(tomorrow.toISOString().split('T')[0]);
    }

    // Set default time to current time + 2 hours
    const futureTime = new Date(now);
    futureTime.setHours(futureTime.getHours() + 2);

    // Format to HH:00 (round to nearest hour)
    const targetHour = futureTime.getHours();
    const timeValue = String(targetHour).padStart(2, '0') + ':00';

    // Check if the time exists in the available options (8:00 - 16:00)
    const timeSelect = $('#time');
    if (timeSelect.find(`option[value="${timeValue}"]`).length > 0) {
        timeSelect.val(timeValue);
    } else {
        // If calculated time is outside business hours, set to 8:00 AM
        timeSelect.val('08:00');
    }

}

function updateDateRestrictions() {
    const now = new Date();
    const currentHour = now.getHours();
    const today = now.toISOString().split('T')[0];
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];

    const dateInput = $('#date');

    // Different logic for edit mode vs create mode
    if (window.ServiceOrderModal.isEditMode) {
        // In edit mode, apply restrictions based on user type
        if (window.ServiceOrderModal.currentUserType === 'client') {
            // Clients still can't edit to past dates in edit mode
            dateInput.attr('min', today);
        } else {
            // Non-client users can edit to any date (past, present, or future) in edit mode
            dateInput.removeAttr('min');
        }
    } else {
        // Create mode - apply standard business hours restrictions
        if (currentHour < 17) { // Before 5 PM
            // Allow today and future dates
            dateInput.attr('min', today);
        } else { // After 5 PM
            // Only allow future dates (not today)
            dateInput.attr('min', tomorrowStr);

            // If current value is today, change it to tomorrow
            if (dateInput.val() === today) {
                dateInput.val(tomorrowStr);
                showTimeRestrictionMessage('Today\'s appointments are no longer available after 5:00 PM. Date changed to tomorrow.');
            }
        }
    }
}

function validateDateSelection(dateInput) {
    const selectedDate = new Date(dateInput.value);
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const currentHour = now.getHours();

    // Different validation logic for edit mode vs create mode
    if (window.ServiceOrderModal.isEditMode) {
        // In edit mode, only validate for client users
        if (window.ServiceOrderModal.currentUserType === 'client') {
            // Check if selected date is in the past for client users
            if (selectedDate < today) {
                dateInput.value = today.toISOString().split('T')[0];
                showTimeRestrictionMessage('Cannot select past dates.');
            }
        }
        // Non-client users can select any date in edit mode (no validation)
    } else {
        // Create mode - apply standard business hours restrictions
        // Check if selected date is today and it's after 5 PM
        if (selectedDate.getTime() === today.getTime() && currentHour >= 17) {
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            dateInput.value = tomorrow.toISOString().split('T')[0];
            showTimeRestrictionMessage('Today\'s appointments are no longer available after 5:00 PM. Please select a future date.');
        }

        // Check if selected date is in the past
        if (selectedDate < today) {
            dateInput.value = today.toISOString().split('T')[0];
            showTimeRestrictionMessage('Cannot select past dates.');
        }
    }
}

function showTimeRestrictionMessage(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'Time Restriction',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}

function setupVINDecoding() {
    const vinInput = document.getElementById('vin');
    const vehicleInput = document.getElementById('vehicle');
    const vinStatus = document.getElementById('vin-status');

    if (!vinInput || !vehicleInput) {
        return;
    }

    // Remove existing event listeners to prevent duplicates
    $(vinInput).off('input.vindecoding input.duplicatevalidation');

    // Add VIN input event listener with integrated duplicate validation
    $(vinInput).on('input.vindecoding', function(e) {
        const vin = e.target.value.toUpperCase().trim();

        // Update input value to uppercase
        e.target.value = vin;

        // Clear previous status and duplicate warnings
        clearModalVINStatus();
        hideDuplicateWarning('vin');

        // Only validate alphanumeric characters
        const validVin = vin.replace(/[^A-Z0-9]/g, '');
        if (validVin !== vin) {
            e.target.value = validVin;
            showModalVINStatus('warning', modalVinTranslations.vin_only_alphanumeric);
            return;
        }

        // Handle different VIN lengths
        if (vin.length === 17) {
            // Full 17-character VIN - comprehensive validation and decoding
            showModalVINStatus('loading', modalVinTranslations.vin_loading);
            decodeModalVIN(vin);
            // Check for duplicates for complete VINs
            checkForDuplicates('vin', vin);
        } else if (vin.length >= 10 && vin.length < 17) {
            // Partial VIN (10-16 characters) - show basic info
            showModalVINStatus('loading', modalVinTranslations.vin_loading);
            decodeModalPartialVIN(vin);
        } else if (vin.length > 0 && vin.length < 10) {
            // Short VIN - just show character count
            showModalVINStatus('info', `${vin.length}/17 ${modalVinTranslations.vin_characters}`);
            clearModalVehicleField();
        } else if (vin.length > 17) {
            // Too long - truncate
            e.target.value = vin.substring(0, 17);
            showModalVINToast('error', modalVinTranslations.vin_cannot_exceed_17);
        } else {
            // Empty VIN
            clearModalVehicleField();
        }
    });
}

function decodeModalVIN(vin) {
    // Advanced VIN validation
    const validationResult = isValidModalVIN(vin);
    if (!validationResult.isValid) {
        if (validationResult.errorType === 'suspicious' || validationResult.errorType === 'checkdigit') {
            showModalVINToast('error', validationResult.message);
        } else {
            showModalVINStatus('error', validationResult.message);
        }
        return;
    }

    // Show loading status
    showModalVINStatus('loading', modalVinTranslations.vin_loading);

    // Call NHTSA vPIC API
    const nhtsa_url = `https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/${vin}?format=json`;

    fetch(nhtsa_url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`NHTSA API Error: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data && data.Results && data.Results.length > 0) {
            const vehicleData = data.Results[0];

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
                } else {
                    console.error('ServiceOrderModal: Vehicle input field not found');
                }
            } else {
                // No vehicle info found
                showModalVINToast('warning', modalVinTranslations.vin_valid_no_info);
            }
        } else {
            console.warn('ServiceOrderModal: No results found in NHTSA response');
            showModalVINToast('warning', modalVinTranslations.vin_decoded_no_data);
        }
    })
    .catch(error => {
        console.error('ServiceOrderModal: NHTSA API error:', error);

        // Fallback to basic decoding if NHTSA API fails
        showModalVINStatus('loading', modalVinTranslations.vin_loading);

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
                }
            } else {
                showModalVINToast('error', modalVinTranslations.vin_unable_to_decode);
            }
        } catch (fallbackError) {
            console.error('ServiceOrderModal: Fallback decoding also failed:', fallbackError);
            showModalVINToast('error', modalVinTranslations.vin_decoding_failed);
        }
    });
}

// Decode partial VIN (10-16 characters)
function decodeModalPartialVIN(vin) {
    try {
        const basicInfo = decodeModalVINBasic(vin);
        
        if (basicInfo.year || basicInfo.make) {
            const vehicleParts = [];
            if (basicInfo.year) vehicleParts.push(basicInfo.year);
            if (basicInfo.make) vehicleParts.push(basicInfo.make);
            vehicleParts.push(`(${modalVinTranslations.vin_partial})`);

            const vehicleString = vehicleParts.join(' ');
            const vehicleInput = document.getElementById('vehicle');
            
            if (vehicleInput) {
                vehicleInput.value = vehicleString;
                vehicleInput.classList.add('vin-decoded');
                
                // Orange styling for partial info
                vehicleInput.style.backgroundColor = '#fff3cd';
                vehicleInput.style.borderColor = '#fd7e14';
                
                showModalVINStatus('warning', `${modalVinTranslations.vin_partial} (${vin.length}/17 ${modalVinTranslations.vin_characters})`);
                
                setTimeout(() => {
                    clearModalVINStatus();
                    vehicleInput.style.backgroundColor = '';
                    vehicleInput.style.borderColor = '';
                }, 3000);
            }
        } else {
            showModalVINStatus('info', `${vin.length}/17 ${modalVinTranslations.vin_characters}`);
            clearModalVehicleField();
        }
    } catch (error) {
        console.error('ServiceOrderModal: Partial VIN decoding error:', error);
        showModalVINStatus('info', `${vin.length}/17 ${modalVinTranslations.vin_characters}`);
        clearModalVehicleField();
    }
}

// Clear vehicle field if it was auto-filled
function clearModalVehicleField() {
    const vehicleInput = document.getElementById('vehicle');
    if (vehicleInput && vehicleInput.classList.contains('vin-decoded')) {
        vehicleInput.value = '';
        vehicleInput.classList.remove('vin-decoded');
        vehicleInput.style.backgroundColor = '';
        vehicleInput.style.borderColor = '';
    }
}

function buildModalVehicleString(nhtsa_data) {
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

    return result;
}

function isValidModalVIN(vin) {
    // VIN must be 17 characters, alphanumeric, no I, O, or Q
    if (vin.length !== 17) {
        return { isValid: false, errorType: 'format', message: modalVinTranslations.vin_invalid_format };
    }
    
    if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) {
        return { isValid: false, errorType: 'format', message: modalVinTranslations.vin_invalid_format };
    }

    // Check for suspicious patterns
    const suspiciousResult = checkModalSuspiciousPatterns(vin);
    if (!suspiciousResult.isValid) {
        return suspiciousResult;
    }

    // Validate check digit (ISO 3779)
    const checkDigitResult = validateModalCheckDigit(vin);
    if (!checkDigitResult.isValid) {
        return checkDigitResult;
    }

    return { isValid: true };
}

// Check for suspicious VIN patterns
function checkModalSuspiciousPatterns(vin) {
    // Check for consecutive identical characters (4 or more)
    for (let i = 0; i <= vin.length - 4; i++) {
        if (vin[i] === vin[i+1] && vin[i] === vin[i+2] && vin[i] === vin[i+3]) {
            return { 
                isValid: false, 
                errorType: 'suspicious', 
                message: modalVinTranslations.vin_suspicious_patterns 
            };
        }
    }

    // Check for excessive repetition of the same character (more than 4 occurrences)
    const charCount = {};
    for (const char of vin) {
        charCount[char] = (charCount[char] || 0) + 1;
        if (charCount[char] > 4) {
            return { 
                isValid: false, 
                errorType: 'suspicious', 
                message: modalVinTranslations.vin_suspicious_patterns 
            };
        }
    }

    return { isValid: true };
}

// Validate VIN check digit (9th position) according to ISO 3779
function validateModalCheckDigit(vin) {
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
        const char = vin[i];
        const value = values[char];
        if (value === undefined) {
            return { 
                isValid: false, 
                errorType: 'format', 
                message: modalVinTranslations.vin_invalid_format 
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
            message: modalVinTranslations.vin_invalid_check_digit 
        };
    }

    return { isValid: true };
}

function decodeModalVINBasic(vin) {
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
        vinInfo.year = decodeModalYearFromVIN(yearCode);

        // Decode manufacturer (World Manufacturer Identifier - first 3 characters)
        const wmi = vin.substring(0, 3);
        vinInfo.make = decodeModalMakeFromWMI(wmi);

        // For more detailed decoding, we would need extensive VIN databases
        // This is a basic implementation

    } catch (error) {
        console.error('Basic VIN decoding error:', error);
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

    if (!vinStatus) return;

    // Clear previous status
    clearModalVINStatus();

    // Set new status
    vinStatus.textContent = message;
    vinStatus.className = `vin-status vin-status-${type}`;

    // Update input styling
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

    // Auto-hide non-critical messages
    if (type === 'info' || type === 'warning') {
        setTimeout(() => {
            clearModalVINStatus();
        }, 3000);
    }
}

function clearModalVINStatus() {
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

// Toast notification system for VIN validation
function showModalVINToast(type, message) {
    // Remove any existing toast
    const existingToast = document.querySelector('.vin-toast');
    if (existingToast) {
        existingToast.remove();
    }

    // Create toast container
    const toast = document.createElement('div');
    toast.className = `vin-toast vin-toast-${type}`;
    toast.innerHTML = `
        <div class="vin-toast-content">
            <div class="vin-toast-icon">
                ${type === 'error' ? '<i class="mdi mdi-alert-circle"></i>' : 
                  type === 'warning' ? '<i class="mdi mdi-alert"></i>' : 
                  '<i class="mdi mdi-information"></i>'}
            </div>
            <div class="vin-toast-message">${message}</div>
            <button class="vin-toast-close" onclick="this.parentElement.parentElement.remove()">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
    `;

    // Add toast to body
    document.body.appendChild(toast);

    // Auto-remove toast after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);

    // Add animation class
    setTimeout(() => {
        toast.classList.add('vin-toast-show');
    }, 100);
}

// Duplicate Order Validation Functions
function setupDuplicateValidation() {
    // Duplicate validation is now integrated into setupVINDecoding()
    // This function is kept for compatibility but the actual validation
    // happens in real-time within the VIN input handler
}

function checkForDuplicates(field, value) {
    if (!value) {
        hideDuplicateWarning(field);
        return;
    }

    const isEditMode = window.ServiceOrderModal.isEditMode;
    const orderData = window.ServiceOrderModal.orderData;
    const currentOrderId = isEditMode && orderData ? orderData.id : null;

    // Clear any existing timeout for this field to debounce requests
    if (window.duplicateCheckTimeout) {
        clearTimeout(window.duplicateCheckTimeout);
    }

    // Debounce the duplicate check to avoid too many requests
    window.duplicateCheckTimeout = setTimeout(() => {
        $.post('<?= base_url('service_orders/checkDuplicateOrder') ?>', {
            [field]: value,
            current_order_id: currentOrderId
        })
        .done(function(data) {
            if (data.success && data.has_duplicates) {
                showDuplicateWarning(field, data.duplicates);
            } else {
                hideDuplicateWarning(field);
            }
        })
        .fail(function(xhr, status, error) {
            console.error('ServiceOrderModal: Error checking duplicates:', error);
            hideDuplicateWarning(field);
        });
    }, 500); // 500ms debounce delay
}

function showDuplicateWarning(field, duplicates) {
    const fieldElement = $(`#${field}`);
    const container = fieldElement.closest('.form-section');

    // Remove existing warning
    hideDuplicateWarning(field);

    // Create warning element
    let duplicateInfo = '';
    if (duplicates[field]) {
        const orders = duplicates[field].orders;
        const count = duplicates[field].count;

        // Create enhanced duplicate info with order status
        const ordersList = orders.slice(0, 3).map(order => {
            const orderNumber = '#' + String(order.id).padStart(4, '0');
            const clientName = order.client_name || 'Unknown';
            return `${orderNumber} (${clientName})`;
        }).join(', ');

        const moreText = orders.length > 3 ? ` and ${orders.length - 3} more` : '';
        const countText = count === 1 ? 'duplicate found' : 'duplicates found';

        duplicateInfo = `${count} ${countText}: ${ordersList}${moreText}`;
    }

    const warningDiv = $(`<div id="${field}-duplicate-warning" class="duplicate-warning">
        <i class="mdi mdi-alert-circle me-1"></i>
        <strong>Duplicate VIN Warning:</strong> This VIN already exists in the system.
        <br>
        <small>${duplicateInfo}</small>
    </div>`);

    container.append(warningDiv);
}

function hideDuplicateWarning(field) {
    const warningElement = document.getElementById(`${field}-duplicate-warning`);
    if (warningElement) {
        warningElement.remove();
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
        const fieldLabel = field === 'vin' ? 'VIN: {0}' : 'Field: {0}';

        duplicateHtml += `
            <div class="mb-3">
                <h6 class="text-danger">
                    <i class="mdi mdi-alert-circle me-1"></i>
                    ${fieldLabel.replace('{0}', duplicate.value)} (${duplicate.count} ${duplicate.count === 1 ? 'time' : 'times'})
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        duplicate.orders.forEach(order => {
            const orderNumber = '#' + String(order.id).padStart(4, '0');
            const clientName = order.client_name || 'N/A';
            const contactName = order.salesperson_name || 'N/A';
            const orderDate = order.date || 'N/A';
            const status = order.status || 'pending';

            duplicateHtml += `
                <tr>
                    <td><strong>${orderNumber}</strong></td>
                    <td>${clientName}</td>
                    <td>${contactName}</td>
                    <td>${orderDate}</td>
                    <td>
                        <span class="badge bg-secondary">${status}</span>
                    </td>
                </tr>
            `;
        });

        duplicateHtml += `
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    });

    duplicateHtml += '</div>';

    // Show confirmation dialog
    const titleText = totalDuplicates === 1 ?
        'Duplicate Service Order Detected' :
        `${totalDuplicates} Duplicate Service Orders Detected`;

    Swal.fire({
        title: titleText,
        html: `
            <div class="text-start">
                ${duplicateHtml}
                <p class="mt-3 text-muted">
                    <i class="mdi mdi-information-outline me-1"></i>
                    Do you want to create this service order anyway?
                </p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Create Duplicate',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        width: '800px',
        customClass: {
            popup: 'duplicate-confirmation-modal'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with order creation

            submitOrderWithDuplicates(formData, orderForm);
        } else {

            // Re-enable the submit button since we're not proceeding
            const submitButton = orderForm.find('button[type="submit"]');
            submitButton.prop('disabled', false);
            submitButton.html(submitButton.data('original-text') || 'Save Service Order');
        }
    }).catch((error) => {
        console.error('ServiceOrderModal: Error showing duplicate confirmation:', error);
        // Re-enable the submit button in case of error
        const submitButton = orderForm.find('button[type="submit"]');
        submitButton.prop('disabled', false);
        submitButton.html(submitButton.data('original-text') || 'Save Service Order');
    });
}

function submitOrderWithDuplicates(formData, orderForm) {
    // Add flag to indicate this is a confirmed duplicate
    formData += '&allow_duplicates=1';

    const isEditMode = window.ServiceOrderModal.isEditMode;
    const orderData = window.ServiceOrderModal.orderData;

    const url = isEditMode ? 
        `<?= base_url('service_orders/update') ?>/${orderData.id}` : 
        '<?= base_url('service_orders/store') ?>';

    $.post(url, formData)
        .done(function(response) {
            if (response.success) {
                $('#orderModal, #editServiceOrderModal').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                // Refresh tables
                if (typeof refreshAllServiceOrdersTables === 'function') {
                    refreshAllServiceOrdersTables();
                } else {
                    location.reload();
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to create service order'
                });
            }
        })
        .fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to create service order. Please try again.'
            });
        })
        .always(function() {
            // Re-enable submit button
            const submitButton = orderForm.find('button[type="submit"]');
            submitButton.prop('disabled', false);
            submitButton.html(submitButton.data('original-text') || 'Save Service Order');
        });
}

// Date/Time Helper Functions
function updateDateRestrictions() {
    const now = new Date();
    const currentHour = now.getHours();
    const today = now.toISOString().split('T')[0];
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];

    const dateInput = $('#date');

    // Different logic for edit mode vs create mode
    if (window.ServiceOrderModal.isEditMode) {
        // In edit mode, apply restrictions based on user type
        if (window.ServiceOrderModal.currentUserType === 'client') {
            // Clients still can't edit to past dates in edit mode
            dateInput.attr('min', today);
        } else {
            // Non-client users can edit to any date (past, present, or future) in edit mode
            dateInput.removeAttr('min');
        }
    } else {
        // Create mode - apply standard business hours restrictions
        if (currentHour < 17) { // Before 5 PM
            // Allow today and future dates
            dateInput.attr('min', today);
        } else { // After 5 PM
            // Only allow future dates (not today)
            dateInput.attr('min', tomorrowStr);

            // If current value is today, change it to tomorrow
            if (dateInput.val() === today) {
                dateInput.val(tomorrowStr);
                showTimeRestrictionMessage('Today\'s appointments are no longer available after 5:00 PM. Date changed to tomorrow.');
            }
        }
    }
}

function validateDateSelection(dateInput) {
    const selectedDate = new Date(dateInput.value);
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const currentHour = now.getHours();

    // Different validation logic for edit mode vs create mode
    if (window.ServiceOrderModal.isEditMode) {
        // In edit mode, only validate for client users
        if (window.ServiceOrderModal.currentUserType === 'client') {
            // Check if selected date is in the past for client users
            if (selectedDate < today) {
                dateInput.value = today.toISOString().split('T')[0];
                showTimeRestrictionMessage('Cannot select past dates.');
            }
        }
        // Non-client users can select any date in edit mode (no validation)
    } else {
        // Create mode - apply standard business hours restrictions
        // Check if selected date is today and it's after 5 PM
        if (selectedDate.getTime() === today.getTime() && currentHour >= 17) {
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            dateInput.value = tomorrow.toISOString().split('T')[0];
            showTimeRestrictionMessage('Today\'s appointments are no longer available after 5:00 PM. Please select a future date.');
        }

        // Check if selected date is in the past
        if (selectedDate < today) {
            dateInput.value = today.toISOString().split('T')[0];
            showTimeRestrictionMessage('Cannot select past dates.');
        }
    }
}

function showTimeRestrictionMessage(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'Time Restriction',
            text: message,
            timer: 3000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}

// Global function to reinitialize the modal - call this when modal is opened
window.reinitializeServiceOrderModal = function() {

    // Wait a bit for modal content to be loaded
    setTimeout(() => {
        initializeServiceOrderModal();
    }, 100);
};

// Cleanup function when modal is closed
window.cleanupServiceOrderModal = function() {

    // Clear interval
    if (window.ServiceOrderModal.dateInterval) {
        clearInterval(window.ServiceOrderModal.dateInterval);
        window.ServiceOrderModal.dateInterval = null;
    }

    // Remove event listeners
    $('#client_id').off('change.serviceorder');
    $('#serviceOrderForm').off('submit.serviceorder');
    $('#vin').off('input.vindecoding blur.duplicatevalidation');
    $('#date').off('change.datetimerestrictions');

    // Remove initialization flag
    $('#serviceOrderForm').removeClass('initialized submitting');

    // Reset global flags
    window.ServiceOrderModal.isSubmitting = false;
    window.ServiceOrderModal.submitCount = 0;

};

$(document).ready(function() {
    // Add a small delay to prevent multiple rapid initializations
    setTimeout(() => {
        initializeServiceOrderModal();
    }, 50);
});
</script>

<!-- VIN Barcode Scanner Modal -->
<div id="vinScannerModal" class="scanner-modal">
    <div class="scanner-header">
        <h5 class="mb-0">
            <i class="ri-qr-scan-line me-2" id="scanner-icon"></i>
            <span id="scanner-title">Scan VIN Barcode</span>
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
            Point camera to VIN barcode or QR code
        </div>
    </div>
</div>

<script>
// Load QuaggaJS with fallback
function loadQuaggaJS() {

    const quaggaSources = [
        'https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js',
        'https://unpkg.com/quagga@0.12.1/dist/quagga.min.js',
        'https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js'
    ];

    let quaggaIndex = 0;

    function tryLoadQuagga() {
        if (quaggaIndex >= quaggaSources.length) {
            console.warn('📷 All QuaggaJS CDN sources failed, barcode scanning will not be available');
            window.QuaggaLoadFailed = true;
            return;
        }

        const script = document.createElement('script');
        script.src = quaggaSources[quaggaIndex];

        script.onload = function() {

            window.QuaggaLoaded = true;
        };

        script.onerror = function() {
            console.warn('📷 Failed to load QuaggaJS from:', quaggaSources[quaggaIndex]);
            quaggaIndex++;
            setTimeout(tryLoadQuagga, 200);
        };

        document.head.appendChild(script);
    }

    tryLoadQuagga();
}

// Load jsQR for QR code scanning
function loadQRScanner() {

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

// VIN Barcode Scanner Implementation
window.VinBarcodeScanner = (function() {
    let isScanning = false;
    let scannerModal = null;
    let currentMode = 'barcode'; // 'barcode', 'qr', or 'ocr'
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
            scanBtn.innerHTML = '<i class="ri-qr-scan-line me-1"></i><span class="scan-btn-text">Scan</span>';
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

    // Switch between barcode and QR modes
    function switchScanMode() {
        if (!isScanning) return;

        const modeSwitchBtn = document.getElementById('modeSwitchBtn');
        const scannerIcon = document.getElementById('scanner-icon');
        const scannerTitle = document.getElementById('scanner-title');
        const instructions = document.querySelector('.scanner-instructions');

        // Stop current mode
        stopCurrentMode();

        // Cycle through modes: barcode -> qr -> barcode
        switch (currentMode) {
            case 'barcode':
                currentMode = 'qr';
                if (modeSwitchBtn) {
                    modeSwitchBtn.innerHTML = '<i class="ri-qr-scan-line"></i> Barcode';
                    modeSwitchBtn.title = 'Switch to barcode scanning';
                }
                if (scannerIcon) scannerIcon.className = 'ri-qr-scan-2-line me-2';
                if (scannerTitle) scannerTitle.textContent = 'Scan QR Code';
                if (instructions) {
                    instructions.innerHTML = '<i class="ri-qr-scan-2-line me-2"></i>Position camera over QR code';
                }
                startQRMode();
                break;

            case 'qr':
            default:
                currentMode = 'barcode';
                if (modeSwitchBtn) {
                    modeSwitchBtn.innerHTML = '<i class="ri-qr-scan-2-line"></i> QR';
                    modeSwitchBtn.title = 'Switch to QR code scanning';
                }
                if (scannerIcon) scannerIcon.className = 'ri-qr-scan-line me-2';
                if (scannerTitle) scannerTitle.textContent = 'Scan VIN Barcode';
                if (instructions) {
                    instructions.innerHTML = '<i class="ri-qr-code-line me-2"></i>Point camera to VIN barcode or QR code';
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

        // Clear scanner target
        const scannerTarget = document.querySelector('#scanner-target');
        if (scannerTarget) {
            scannerTarget.innerHTML = '';
        }
    }

    // Start QR mode
    function startQRMode() {
        if (typeof window.jsQR === 'undefined' && !window.QRScannerLoaded) {
            console.warn('📱 jsQR not loaded, cannot start QR mode');
            showError('QR scanner not available. Please refresh the page and try again.');
            return;
        }

        initializeQRScanner();
    }

    // Initialize QR Scanner
    function initializeQRScanner() {

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

        let isProcessingQR = false;

        function processQRFrame() {
            if (!isScanning || currentMode !== 'qr' || isProcessingQR) {
                if (isScanning && currentMode === 'qr') {
                    setTimeout(processQRFrame, 100);
                }
                return;
            }

            isProcessingQR = true;

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const qrCode = jsQR(imageData.data, imageData.width, imageData.height);

            if (qrCode) {

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

                    const vinInput = document.getElementById('vin');
                    if (vinInput) {
                        vinInput.value = vinCandidate.toUpperCase();
                        const event = new Event('input', { bubbles: true });
                        vinInput.dispatchEvent(event);
                    }

                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'VIN Found!',
                            text: 'VIN extracted from QR: ' + vinCandidate,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }

                    stopScanner();
                    return;
                } else {

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
            showError('Camera not supported by this browser');
            return;
        }

        startScanner();
    }

    // Start the VIN scanner
    function startScanner() {

        if (isScanning) return;

        scannerModal = document.getElementById('vinScannerModal');
        if (!scannerModal) {
            console.error('📷 Scanner modal not found');
            resetScanButton();
            showError('Scanner interface not found');
            return;
        }

        // Reset scan button
        resetScanButton();

        scannerModal.style.display = 'flex';
        isScanning = true;

        // Check if QuaggaJS loading failed
        if (window.QuaggaLoadFailed) {
            console.error('📷 QuaggaJS loading failed');
            resetScanButton();
            showError('Barcode scanner library could not load. Please check your internet connection and try again.');
            stopScanner();
            return;
        }

        // Initialize Quagga with better configuration
        if (typeof window.Quagga === 'undefined' && !window.QuaggaLoaded) {

            // Wait for Quagga to load (up to 5 seconds)
            let attempts = 0;
            const maxAttempts = 50;

            const checkQuagga = setInterval(() => {
                attempts++;

                if (window.QuaggaLoadFailed) {
                    clearInterval(checkQuagga);
                    resetScanButton();
                    showError('Barcode scanner library could not load. Please check your internet connection and try again.');
                    stopScanner();
                    return;
                }

                if (typeof window.Quagga !== 'undefined' || window.QuaggaLoaded) {
                    clearInterval(checkQuagga);
                    initializeQuagga();
                    return;
                }

                if (attempts >= maxAttempts) {
                    clearInterval(checkQuagga);
                    resetScanButton();
                    showError('Barcode scanner library failed to load. Please refresh the page and try again.');
                    stopScanner();
                }
            }, 100);

            return;
        }

        initializeQuagga();
    }

    // Initialize Quagga barcode scanner
    function initializeQuagga() {

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
                console.error('📷 QuaggaJS initialization error:', err);
                resetScanButton();
                showError('Camera initialization failed: ' + err.message);
                stopScanner();
                return;
            }

            window.Quagga.start();
        });

        window.Quagga.onDetected(function(result) {
            const code = result.codeResult.code;

            // Validate VIN format
            if (isValidVin(code)) {

                const vinInput = document.getElementById('vin');
                if (vinInput) {
                    vinInput.value = code.toUpperCase();
                    const event = new Event('input', { bubbles: true });
                    vinInput.dispatchEvent(event);
                }

                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'VIN Scanned!',
                        text: 'VIN scanned successfully: ' + code,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }

                stopScanner();
            } else {

            }
        });
    }

    // Stop the VIN scanner
    function stopScanner() {
        if (!isScanning) return;

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
            modeSwitchBtn.title = 'Switch to QR code scanning';
        }
        if (scannerIcon) scannerIcon.className = 'ri-qr-scan-line me-2';
        if (scannerTitle) scannerTitle.textContent = 'Scan VIN Barcode';
        if (instructions) {
            instructions.innerHTML = '<i class="ri-qr-code-line me-2"></i>Point camera to VIN barcode or QR code';
        }
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

    // Show error message
    function showError(message) {
        if (window.Swal) {
            Swal.fire({
                icon: 'error',
                title: 'Scanner Error',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            alert(message);
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

// Initialize scanner libraries and scanner functionality
loadQuaggaJS();
loadQRScanner();

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
    if (e.target.querySelector('#serviceOrderForm')) {
        setTimeout(() => VinBarcodeScanner.init(), 100);
    }
});

// Stop scanner when modal is hidden
document.addEventListener('hidden.bs.modal', function(e) {
    if (e.target.querySelector('#serviceOrderForm')) {
        VinBarcodeScanner.stop();
    }
});

// Function to check order status and set readonly fields for Service Orders
function checkServiceOrderStatusAndSetReadonly() {
    console.log('ServiceOrderModal: checkOrderStatusAndSetReadonly - Starting status check');
    
    const statusField = document.getElementById('order_status');
    if (!statusField) {
        console.log('ServiceOrderModal: checkOrderStatusAndSetReadonly - Status field not found');
        return;
    }
    
    const status = statusField.value;
    const isCompletedOrCancelled = status === 'completed' || status === 'cancelled';
    
    console.log('ServiceOrderModal: checkOrderStatusAndSetReadonly - Status:', status, 'Is completed/cancelled:', isCompletedOrCancelled);
    
    if (isCompletedOrCancelled) {
        // Get all form fields except the hidden status field
        const formFields = document.querySelectorAll('#serviceOrderForm input:not([type="hidden"]), #serviceOrderForm select, #serviceOrderForm textarea');
        
        console.log('ServiceOrderModal: checkOrderStatusAndSetReadonly - Found', formFields.length, 'fields to disable');
        
        formFields.forEach((field, index) => {
            console.log(`ServiceOrderModal: checkOrderStatusAndSetReadonly - Disabling field ${index + 1}:`, field.name || field.id, field.tagName);
            field.readOnly = true;
            field.disabled = true;
            field.style.backgroundColor = '#f8f9fa';
            field.style.cursor = 'not-allowed';
            field.style.opacity = '0.7';
        });
        
        // Also disable the submit button
        const submitBtn = document.querySelector('button[type="submit"][form="serviceOrderForm"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="mdi mdi-lock me-1"></i><?= lang('App.order_locked') ?>';
            submitBtn.style.backgroundColor = '#6c757d';
            submitBtn.style.borderColor = '#6c757d';
            submitBtn.style.cursor = 'not-allowed';
            console.log('ServiceOrderModal: checkOrderStatusAndSetReadonly - Submit button disabled');
        }
        
        console.log('ServiceOrderModal: checkOrderStatusAndSetReadonly - Service order is completed/cancelled - all fields set to readonly');
    } else {
        console.log('ServiceOrderModal: checkOrderStatusAndSetReadonly - Service order is not completed/cancelled, fields remain editable');
    }
}

// Global function to check and set readonly status for Service Orders - can be called from outside
window.checkServiceOrderStatusAndSetReadonly = function() {
    console.log('Global: checkServiceOrderStatusAndSetReadonly - Starting status check');
    
    const statusField = document.getElementById('order_status');
    if (!statusField) {
        console.log('Global: checkServiceOrderStatusAndSetReadonly - Status field not found');
        return;
    }
    
    const status = statusField.value;
    const isCompletedOrCancelled = status === 'completed' || status === 'cancelled';
    
    console.log('Global: checkServiceOrderStatusAndSetReadonly - Status:', status, 'Is completed/cancelled:', isCompletedOrCancelled);
    
    if (isCompletedOrCancelled) {
        // Get all form fields except the hidden status field
        const formFields = document.querySelectorAll('#serviceOrderForm input:not([type="hidden"]), #serviceOrderForm select, #serviceOrderForm textarea');
        
        console.log('Global: checkServiceOrderStatusAndSetReadonly - Found', formFields.length, 'fields to disable');
        
        formFields.forEach((field, index) => {
            console.log(`Global: checkServiceOrderStatusAndSetReadonly - Disabling field ${index + 1}:`, field.name || field.id, field.tagName);
            field.readOnly = true;
            field.disabled = true;
            field.style.backgroundColor = '#f8f9fa';
            field.style.cursor = 'not-allowed';
            field.style.opacity = '0.7';
        });
        
        // Also disable the submit button
        const submitBtn = document.querySelector('button[type="submit"][form="serviceOrderForm"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="mdi mdi-lock me-1"></i><?= lang('App.order_locked') ?>';
            submitBtn.style.backgroundColor = '#6c757d';
            submitBtn.style.borderColor = '#6c757d';
            submitBtn.style.cursor = 'not-allowed';
            console.log('Global: checkServiceOrderStatusAndSetReadonly - Submit button disabled');
        }
        
        console.log('Global: checkServiceOrderStatusAndSetReadonly - Service order is completed/cancelled - all fields set to readonly');
    } else {
        console.log('Global: checkServiceOrderStatusAndSetReadonly - Service order is not completed/cancelled, fields remain editable');
    }
};

// Also call the function when modal content is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Listen for modal shown events
    const orderModal = document.getElementById('orderModal');
    if (orderModal) {
        orderModal.addEventListener('shown.bs.modal', function() {
            console.log('ServiceOrderModal: Modal shown, checking status after delay');
            setTimeout(() => {
                if (typeof window.checkServiceOrderStatusAndSetReadonly === 'function') {
                    window.checkServiceOrderStatusAndSetReadonly();
                }
            }, 500);
        });
    }
});
</script> 
