<!-- ============================================== -->
<!-- GLOBAL SALES ORDER MODAL - Mobile Optimized -->
<!-- ============================================== -->

<style data-timestamp="<?= time() ?>">
/* ================================================ */
/* NOTION-STYLE MODAL - Clean & Minimalist */
/* ================================================ */

#global-sales-order-modal {
    --notion-text: #37352f;
    --notion-text-light: #787774;
    --notion-border: #e9e9e7;
    --notion-bg: #ffffff;
    --notion-bg-light: #f7f7f5;
    --notion-success: #0f7b0f;
    --notion-danger: #e03e3e;
    --notion-focus: #2383e2;
    --modal-z-index: 9999;
}

/* Base Modal Styling - 3 Columns Desktop */
#global-sales-order-modal .modal-dialog {
    max-width: 1200px;
    margin: 2rem auto;
    width: calc(100% - 2rem);
}

#global-sales-order-modal .modal-content {
    border: 1px solid var(--notion-border);
    border-radius: 8px;
    box-shadow: rgba(15, 15, 15, 0.05) 0px 0px 0px 1px, rgba(15, 15, 15, 0.1) 0px 3px 6px, rgba(15, 15, 15, 0.2) 0px 9px 24px;
    overflow: hidden;
    background: var(--notion-bg);
}

#global-sales-order-modal .modal-header {
    background: var(--notion-bg);
    color: var(--notion-text);
    border-bottom: 1px solid var(--notion-border);
    padding: 14px 24px;
    position: relative;
}

#global-sales-order-modal .modal-title {
    font-weight: 600;
    font-size: 16px;
    margin: 0;
    display: flex;
    align-items: center;
    color: var(--notion-text);
    line-height: 1.2;
}

#global-sales-order-modal .modal-title i {
    margin-right: 10px;
    font-size: 14px;
    color: var(--notion-text-light);
}

#global-sales-order-modal .btn-close {
    background: transparent;
    border: none;
    opacity: 0.6;
    transition: all 0.15s ease;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

#global-sales-order-modal .btn-close:hover {
    opacity: 1;
    background-color: var(--notion-bg-light);
}

/* Notion-style Body */
#global-sales-order-modal .modal-body {
    padding: 20px 24px;
    background: var(--notion-bg);
    max-height: 70vh;
    overflow-y: auto;
}

/* Form Sections */
#global-sales-order-modal .form-section {
    margin-bottom: 20px;
}

#global-sales-order-modal .form-section:last-child {
    margin-bottom: 0;
}

#global-sales-order-modal .form-label {
    font-weight: 500;
    color: var(--notion-text);
    margin-bottom: 6px;
    font-size: 14px;
    display: flex;
    align-items: center;
    line-height: 1.3;
}

/* Icons removed for clean Notion style */

#global-sales-order-modal .form-label .required {
    color: var(--notion-danger);
    margin-left: 4px;
}

/* Notion-style Form Controls */
#global-sales-order-modal .form-control,
#global-sales-order-modal .form-select {
    border: 1px solid var(--notion-border);
    border-radius: 4px;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.15s ease;
    background: var(--notion-bg);
    line-height: 1.4;
    color: var(--notion-text);
    height: auto;
    min-height: 36px;
}

#global-sales-order-modal .form-control::placeholder {
    color: var(--notion-text-light);
    opacity: 1;
}

#global-sales-order-modal .form-control:focus,
#global-sales-order-modal .form-select:focus {
    border-color: var(--notion-focus);
    box-shadow: none;
    outline: none;
    background: var(--notion-bg);
}

#global-sales-order-modal .form-control:disabled,
#global-sales-order-modal .form-select:disabled {
    background: var(--notion-bg-light);
    border-color: var(--notion-border);
    color: var(--notion-text-light);
    cursor: not-allowed;
}

#global-sales-order-modal .form-text {
    font-size: 12px;
    color: var(--notion-text-light);
    margin-top: 4px;
    line-height: 1.3;
}

/* Error States */
#global-sales-order-modal .form-error {
    color: var(--notion-danger);
    font-size: 12px;
    margin-top: 4px;
    display: none;
    line-height: 1.3;
}

#global-sales-order-modal .form-control.error,
#global-sales-order-modal .form-select.error {
    border-color: var(--notion-danger);
}

#global-sales-order-modal .form-control.error + .form-error,
#global-sales-order-modal .form-select.error + .form-error {
    display: block;
}

/* Success State */
#global-sales-order-modal .form-control.success {
    border-color: var(--success-color);
}

/* VIN Input Special Styling */
#global-sales-order-modal .vin-input-container {
    position: relative;
}

#global-sales-order-modal .vin-scan-btn {
    position: absolute;
    top: 50%;
    right: 8px;
    transform: translateY(-50%);
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
    z-index: 10;
}

/* VIN Status - Notion Style */
#global-sales-order-modal .vin-status {
    font-size: 12px;
    margin-top: 4px;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
    font-weight: 500;
}

#global-sales-order-modal .vin-status.vin-status-loading {
    background: var(--notion-focus);
    color: white;
}

#global-sales-order-modal .vin-status.vin-status-success {
    background: var(--notion-success);
    color: white;
}

#global-sales-order-modal .vin-status.vin-status-error {
    background: var(--notion-danger);
    color: white;
}

#global-sales-order-modal .vin-status.vin-status-warning {
    background: #ff6b35;
    color: white;
}

/* Date Time Styling - Notion Style */
#global-sales-order-modal .datetime-warning {
    background: #fdf4e7;
    border: 1px solid #e9dcc9;
    color: var(--notion-text);
    padding: 12px;
    border-radius: 4px;
    margin-top: 8px;
    font-size: 12px;
    display: flex;
    align-items: center;
    line-height: 1.3;
}

#global-sales-order-modal .datetime-warning i {
    margin-right: 8px;
    color: #ff6b35;
    width: 13px;
    height: 13px;
}

/* Loading States */
#global-sales-order-modal .loading {
    position: relative;
    pointer-events: none;
}

#global-sales-order-modal .loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: global-modal-spin 1s linear infinite;
}

@keyframes global-modal-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Notion-style Footer */
#global-sales-order-modal .modal-footer {
    background: var(--notion-bg);
    border-top: 1px solid var(--notion-border);
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

/* Notion-style Buttons */
#global-sales-order-modal .btn {
    border-radius: 4px;
    font-weight: 500;
    padding: 8px 16px;
    font-size: 14px;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    line-height: 1.4;
    border: 1px solid transparent;
    text-decoration: none;
    cursor: pointer;
    white-space: nowrap;
}

#global-sales-order-modal .btn i {
    margin-right: 8px;
    font-size: 13px;
}

#global-sales-order-modal .btn-primary {
    background: var(--notion-success);
    border-color: var(--notion-success);
    color: white;
}

#global-sales-order-modal .btn-primary:hover {
    background: #0d6a0d;
    border-color: #0d6a0d;
    color: white;
}

#global-sales-order-modal .btn-secondary {
    background: transparent;
    border-color: var(--notion-border);
    color: var(--notion-text);
}

#global-sales-order-modal .btn-secondary:hover {
    background: var(--notion-bg-light);
    border-color: var(--notion-border);
    color: var(--notion-text);
}

/* Remove old duplicate styles - already handled above */

/* ================================================ */
/* MOBILE OPTIMIZATION - Notion Style */
/* ================================================ */

@media (max-width: 768px) {
    #global-sales-order-modal .modal-dialog {
        margin: 0;
        max-width: 100%;
        width: 100vw;
        height: 100vh;
    }
    
    #global-sales-order-modal .modal-content {
        height: 100vh;
        border-radius: 0;
        border: none;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    
    #global-sales-order-modal .modal-header {
        position: sticky;
        top: 0;
        z-index: 100;
        flex-shrink: 0;
        padding: 16px 20px;
        border-bottom: 1px solid var(--notion-border);
    }
    
    #global-sales-order-modal .modal-body {
        flex: 1;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        padding: 20px;
    }
    
    #global-sales-order-modal .modal-footer {
        position: sticky;
        bottom: 0;
        z-index: 100;
        flex-shrink: 0;
        box-shadow: 0 -1px 0 var(--notion-border);
        padding: 16px 20px;
        flex-direction: column;
        gap: 12px;
    }
    
    #global-sales-order-modal .modal-footer .btn {
        width: 100%;
        justify-content: center;
        padding: 12px 16px;
        min-height: 44px; /* Touch target */
    }
    
    /* Mobile Form Optimizations */
    #global-sales-order-modal .form-control,
    #global-sales-order-modal .form-select {
        font-size: 16px; /* Prevent zoom on iOS */
        padding: 12px;
        min-height: 44px; /* Touch target */
    }
    
    #global-sales-order-modal .form-label {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    /* Mobile Spacing */
    #global-sales-order-modal .form-section {
        margin-bottom: 24px;
    }
    
    #global-sales-order-modal .row > .col-lg-4,
    #global-sales-order-modal .row > .col-md-6 {
        margin-bottom: 16px;
    }
    
    #global-sales-order-modal .row > .col-lg-4:last-child,
    #global-sales-order-modal .row > .col-md-6:last-child {
        margin-bottom: 0;
    }
    
    /* VIN Scanner Button Mobile */
    #global-sales-order-modal .vin-scan-btn {
        padding: 8px;
        right: 4px;
    }
    
    /* Mobile Typography */
    #global-sales-order-modal .modal-title {
        font-size: 16px;
    }
}

/* ================================================ */
/* TABLET OPTIMIZATION */
/* ================================================ */

@media (min-width: 769px) and (max-width: 1024px) {
    #global-sales-order-modal .modal-dialog {
        max-width: 800px;
        margin: 1rem auto;
    }
    
    #global-sales-order-modal .form-control,
    #global-sales-order-modal .form-select {
        font-size: 14px;
        padding: 0.75rem;
    }
}

/* ================================================ */
/* ACCESSIBILITY IMPROVEMENTS */
/* ================================================ */

#global-sales-order-modal .form-control:focus,
#global-sales-order-modal .form-select:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

@media (prefers-reduced-motion: reduce) {
    #global-sales-order-modal .form-control,
    #global-sales-order-modal .form-select,
    #global-sales-order-modal .btn {
        transition: none;
    }
    
    #global-sales-order-modal .loading::after {
        animation: none;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    #global-sales-order-modal .modal-content {
        background: #1f2937;
        color: #f9fafb;
    }
    
    #global-sales-order-modal .form-control,
    #global-sales-order-modal .form-select {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }
    
    #global-sales-order-modal .form-control:focus,
    #global-sales-order-modal .form-select:focus {
        background: #374151;
    }
    
    #global-sales-order-modal .modal-footer {
        background: #1f2937;
        border-color: #4b5563;
    }
}

/* Print styles */
@media print {
    #global-sales-order-modal {
        display: none !important;
    }
}
</style>

<!-- ============================================== -->
<!-- MODAL HTML STRUCTURE -->
<!-- ============================================== -->

<div class="modal fade" id="global-sales-order-modal" tabindex="-1" aria-labelledby="globalSalesOrderModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="globalSalesOrderModalLabel">
                    <span id="modal-title-text"><?= lang('App.create_sales_order') ?></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="globalSalesOrderForm" novalidate>
                    <input type="hidden" id="global_order_id" name="id">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <!-- Status hidden - default pending -->
                    <input type="hidden" id="global_status" name="status" value="pending">
                    <!-- Salesperson ID from current user -->
                    <input type="hidden" id="global_salesperson_id" name="salesperson_id" value="<?= auth()->id() ?>">

                    <!-- Row 1: Client, Contact, Stock -->
                    <div class="form-section">
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <label for="global_client_id" class="form-label">
                                    <?= lang('App.client') ?><span class="required">*</span>
                                </label>
                                <select class="form-select" id="global_client_id" name="client_id" required>
                                    <option value=""><?= lang('App.select_client') ?></option>
                                </select>
                                <div class="form-error"><?= lang('App.client_required') ?></div>
                            </div>
                            
                            <div class="col-lg-4 col-md-6">
                                <label for="global_contact_id" class="form-label">
                                    <?= lang('App.contact') ?>
                                </label>
                                <select class="form-select" id="global_contact_id" name="contact_id" disabled>
                                    <option value=""><?= lang('App.select_contact') ?></option>
                                </select>
                                <div class="form-text"><?= lang('App.select_client_first') ?></div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <label for="global_stock" class="form-label">
                                    <?= lang('App.stock_number') ?>
                                </label>
                                <input type="text" class="form-control" id="global_stock" name="stock" 
                                       placeholder="<?= lang('App.enter_stock_number') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: VIN, Vehicle, Service -->
                    <div class="form-section">
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <label for="global_vin" class="form-label">
                                    <?= lang('App.vin') ?>
                                </label>
                                <div class="vin-input-container">
                                    <input type="text" class="form-control" id="global_vin" name="vin" 
                                           placeholder="<?= lang('App.enter_vin') ?>" maxlength="17"
                                           inputmode="text" autocapitalize="characters" autocomplete="off">
                                    <button type="button" class="btn btn-outline-primary btn-sm vin-scan-btn" 
                                            id="global_scan_vin_btn" title="<?= lang('App.scan_barcode') ?>">
                                    </button>
                                </div>
                                <div id="global_vin_status" class="vin-status" style="display: none;"></div>
                                <div class="form-text"><?= lang('App.vin_17_characters') ?></div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <label for="global_vehicle" class="form-label">
                                    <?= lang('App.vehicle') ?>
                                </label>
                                <input type="text" class="form-control" id="global_vehicle" name="vehicle" 
                                       placeholder="<?= lang('App.enter_vehicle_info') ?>">
                                <div class="form-text"><?= lang('App.year_make_model') ?></div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <label for="global_service_id" class="form-label">
                                    <?= lang('App.service') ?><span class="required">*</span>
                                </label>
                                <select class="form-select" id="global_service_id" name="service_id" required disabled>
                                    <option value=""><?= lang('App.select_service') ?></option>
                                </select>
                                <div class="form-text"><?= lang('App.select_client_first') ?></div>
                                <div class="form-error"><?= lang('App.service_required') ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Date, Time -->
                    <div class="form-section">
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <label for="global_date" class="form-label">
                                    <?= lang('App.date') ?><span class="required">*</span>
                                </label>
                                <input type="date" class="form-control" id="global_date" name="date" required>
                                <div class="form-error"><?= lang('App.date_required') ?></div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <label for="global_time" class="form-label">
                                    <?= lang('App.time') ?>
                                </label>
                                <select class="form-select" id="global_time" name="time">
                                    <option value=""><?= lang('App.select_time') ?></option>
                                </select>
                                <div id="global_datetime_warning" class="datetime-warning" style="display: none;">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="form-section">
                        <label for="global_instructions" class="form-label">
                            <?= lang('App.special_instructions') ?>
                        </label>
                        <textarea class="form-control" id="global_instructions" name="instructions" rows="3"
                                  placeholder="<?= lang('App.enter_special_instructions') ?>"></textarea>
                        <div class="form-text"><?= lang('App.visible_to_service_team') ?></div>
                    </div>

                    <!-- Modal Footer - Inside Form -->
                    <div class="modal-footer">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <?= lang('App.cancel') ?>
                    </button>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-info" id="global_save_draft_btn" style="display: none;">
                            <?= lang('App.save_draft') ?>
                        </button>
                        <button type="submit" class="btn btn-success" id="global_submit_btn">
                            <span id="global_submit_text"><?= lang('App.create_order') ?></span>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>