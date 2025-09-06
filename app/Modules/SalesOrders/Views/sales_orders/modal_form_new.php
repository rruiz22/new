<!-- ULTRATHINK: Simple 3-Column Modal with Notion Styling -->
<style>
/* ULTRATHINK: Perfect 3-Column Modal */
.modal-ultrawide {
    max-width: 1200px;
    width: 90vw;
    margin: 1.5rem auto;
}

/* Notion-style modal design */
.modal-ultrawide .modal-content {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
}

.modal-ultrawide .modal-header {
    background: #ffffff;
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem 2rem;
}

.modal-ultrawide .modal-body {
    padding: 2rem;
}

/* Form styling */
.modal-ultrawide .form-control,
.modal-ultrawide .form-select {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    font-size: 0.875rem;
    padding: 0.625rem 0.875rem;
    transition: all 0.2s ease;
}

.modal-ultrawide .form-control:focus,
.modal-ultrawide .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

/* Responsive behavior */
@media (max-width: 1199px) {
    .modal-ultrawide .col-md-4:nth-child(3n+1) {
        grid-column-start: 1;
    }
}

@media (max-width: 991px) {
    .modal-ultrawide .col-md-4 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (max-width: 767px) {
    .modal-ultrawide {
        margin: 0;
        width: 100vw;
        height: 100vh;
    }
    
    .modal-ultrawide .modal-content {
        height: 100vh;
        border-radius: 0;
    }
    
    .modal-ultrawide .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>

<div class="modal fade" id="salesOrderModal" tabindex="-1" aria-labelledby="salesOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-ultrawide">
        <div class="modal-content">
            <div class="modal-header bg-white border-bottom">
                <div class="d-flex align-items-center">
                    <div class="modal-icon me-3">
                        <i data-feather="file-text" class="icon-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 text-dark" id="salesOrderModalLabel">
                            <span id="modalTitle"><?= lang('App.add_sales_order') ?></span>
                        </h5>
                        <small class="text-muted" id="modalSubtitle">
                            <?= lang('App.create_new_sales_order') ?>
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <form id="salesOrderForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="order_id" name="id" value="">
                    <input type="hidden" id="action_type" name="action_type" value="create">
                    
                    <!-- Navigation Tabs -->
                    <div class="border-bottom">
                        <nav class="nav nav-tabs nav-tabs-line" id="salesOrderTabs" role="tablist">
                            <a class="nav-link active" id="basic-info-tab" data-bs-toggle="tab" 
                               href="#basic-info-pane" role="tab" aria-controls="basic-info-pane" aria-selected="true">
                                <i data-feather="info" class="icon-sm me-2"></i>
                                <?= lang('App.basic_information') ?>
                            </a>
                            <a class="nav-link" id="service-details-tab" data-bs-toggle="tab" 
                               href="#service-details-pane" role="tab" aria-controls="service-details-pane" aria-selected="false">
                                <i data-feather="package" class="icon-sm me-2"></i>
                                <?= lang('App.service_details') ?>
                            </a>
                            <a class="nav-link" id="scheduling-tab" data-bs-toggle="tab" 
                               href="#scheduling-pane" role="tab" aria-controls="scheduling-pane" aria-selected="false">
                                <i data-feather="calendar" class="icon-sm me-2"></i>
                                <?= lang('App.scheduling') ?>
                            </a>
                            <a class="nav-link" id="notes-tab" data-bs-toggle="tab" 
                               href="#notes-pane" role="tab" aria-controls="notes-pane" aria-selected="false">
                                <i data-feather="file-text" class="icon-sm me-2"></i>
                                <?= lang('App.notes_instructions') ?>
                            </a>
                            <a class="nav-link" id="history-tab" data-bs-toggle="tab" 
                               href="#history-pane" role="tab" aria-controls="history-pane" aria-selected="false" style="display: none;">
                                <i data-feather="clock" class="icon-sm me-2"></i>
                                <?= lang('App.history') ?>
                            </a>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content" id="salesOrderTabContent">
                        
                        <!-- Basic Information Tab -->
                        <div class="tab-pane fade show active" id="basic-info-pane" role="tabpanel" aria-labelledby="basic-info-tab">
                            <div class="p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="alert alert-light border-start border-primary border-4 mb-4" role="alert">
                                            <div class="d-flex">
                                                <i data-feather="info" class="icon-sm text-primary me-2 mt-1"></i>
                                                <div>
                                                    <h6 class="alert-heading mb-1"><?= lang('App.order_information') ?></h6>
                                                    <p class="mb-0 small text-muted"><?= lang('App.provide_basic_order_details') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <!-- Client Selection -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="client_id" class="form-label fw-semibold">
                                                <i data-feather="user" class="icon-xs me-1"></i>
                                                <?= lang('App.client') ?> <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="client_id" name="client_id" required>
                                                <option value=""><?= lang('App.select_client') ?></option>
                                                <?php if (isset($clients) && !empty($clients)): ?>
                                                    <?php foreach ($clients as $client): ?>
                                                        <option value="<?= $client['id'] ?>"><?= esc($client['name']) ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <div class="form-text text-muted">
                                                <i data-feather="help-circle" class="icon-xs me-1"></i>
                                                <?= lang('App.select_client_first') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact/Salesperson Selection -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="contact_id" class="form-label fw-semibold">
                                                <i data-feather="users" class="icon-xs me-1"></i>
                                                <?= lang('App.salesperson') ?>
                                            </label>
                                            <select class="form-select" id="contact_id" name="contact_id" disabled>
                                                <option value=""><?= lang('App.select_contact') ?></option>
                                            </select>
                                            <div class="form-text text-muted">
                                                <i data-feather="info" class="icon-xs me-1"></i>
                                                <?= lang('App.contacts_loaded_after_client') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Vehicle Information -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="vehicle" class="form-label fw-semibold">
                                                <i data-feather="truck" class="icon-xs me-1"></i>
                                                <?= lang('App.vehicle') ?>
                                            </label>
                                            <input type="text" class="form-control" id="vehicle" name="vehicle" 
                                                   placeholder="<?= lang('App.enter_vehicle_info') ?>">
                                            <div class="form-text text-muted">
                                                <?= lang('App.year_make_model') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- VIN -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="vin" class="form-label fw-semibold">
                                                <i data-feather="credit-card" class="icon-xs me-1"></i>
                                                <?= lang('App.vin') ?>
                                            </label>
                                            <div class="vin-input-container">
                                                <input type="text" class="form-control" id="vin" name="vin" 
                                                       placeholder="<?= lang('App.enter_vin') ?>" maxlength="17">
                                                <button type="button" class="btn btn-outline-primary btn-sm scan-vin-btn position-absolute" 
                                                        id="scanVinBtn" title="<?= lang('App.scan_barcode') ?>">
                                                    <i data-feather="camera" class="icon-xs"></i>
                                                </button>
                                            </div>
                                            <div class="form-text text-muted">
                                                <?= lang('App.vin_17_characters') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stock Number -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="stock" class="form-label fw-semibold">
                                                <i data-feather="hash" class="icon-xs me-1"></i>
                                                <?= lang('App.stock_number') ?>
                                            </label>
                                            <input type="text" class="form-control" id="stock" name="stock" 
                                                   placeholder="<?= lang('App.enter_stock_number') ?>">
                                        </div>
                                    </div>

                                    <!-- Order Status -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="status" class="form-label fw-semibold">
                                                <i data-feather="activity" class="icon-xs me-1"></i>
                                                <?= lang('App.status') ?> <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="pending" selected><?= lang('App.pending') ?></option>
                                                <option value="in_progress"><?= lang('App.in_progress') ?></option>
                                                <option value="completed"><?= lang('App.completed') ?></option>
                                                <option value="cancelled"><?= lang('App.cancelled') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Details Tab -->
                        <div class="tab-pane fade" id="service-details-pane" role="tabpanel" aria-labelledby="service-details-tab">
                            <div class="p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="alert alert-light border-start border-info border-4 mb-4" role="alert">
                                            <div class="d-flex">
                                                <i data-feather="package" class="icon-sm text-info me-2 mt-1"></i>
                                                <div>
                                                    <h6 class="alert-heading mb-1"><?= lang('App.service_selection') ?></h6>
                                                    <p class="mb-0 small text-muted"><?= lang('App.choose_services_for_order') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <!-- Primary Service -->
                                    <div class="col-12">
                                        <div class="form-section">
                                            <label for="service_id" class="form-label fw-semibold">
                                                <i data-feather="tool" class="icon-xs me-1"></i>
                                                <?= lang('App.primary_service') ?> <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="service_id" name="service_id" required disabled>
                                                <option value=""><?= lang('App.select_service') ?></option>
                                            </select>
                                            <div class="form-text text-muted">
                                                <i data-feather="info" class="icon-xs me-1"></i>
                                                <?= lang('App.services_loaded_after_client') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Service Price Display -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label class="form-label fw-semibold">
                                                <i data-feather="dollar-sign" class="icon-xs me-1"></i>
                                                <?= lang('App.service_price') ?>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="service_price" name="service_price" 
                                                       step="0.01" min="0" readonly>
                                            </div>
                                            <div class="form-text text-muted">
                                                <?= lang('App.automatically_loaded') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Priority -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="priority" class="form-label fw-semibold">
                                                <i data-feather="flag" class="icon-xs me-1"></i>
                                                <?= lang('App.priority') ?>
                                            </label>
                                            <select class="form-select" id="priority" name="priority">
                                                <option value="normal" selected><?= lang('App.normal') ?></option>
                                                <option value="high"><?= lang('App.high') ?></option>
                                                <option value="urgent"><?= lang('App.urgent') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scheduling Tab -->
                        <div class="tab-pane fade" id="scheduling-pane" role="tabpanel" aria-labelledby="scheduling-tab">
                            <div class="p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="alert alert-light border-start border-warning border-4 mb-4" role="alert">
                                            <div class="d-flex">
                                                <i data-feather="calendar" class="icon-sm text-warning me-2 mt-1"></i>
                                                <div>
                                                    <h6 class="alert-heading mb-1"><?= lang('App.schedule_appointment') ?></h6>
                                                    <p class="mb-0 small text-muted"><?= lang('App.set_date_time_for_service') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <!-- Date -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="date" class="form-label fw-semibold">
                                                <i data-feather="calendar" class="icon-xs me-1"></i>
                                                <?= lang('App.date') ?>
                                            </label>
                                            <input type="date" class="form-control" id="date" name="date" 
                                                   min="<?= date('Y-m-d') ?>">
                                            <div class="form-text text-muted">
                                                <?= lang('App.select_service_date') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Time -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="time" class="form-label fw-semibold">
                                                <i data-feather="clock" class="icon-xs me-1"></i>
                                                <?= lang('App.time') ?>
                                            </label>
                                            <input type="time" class="form-control" id="time" name="time">
                                            <div class="form-text text-muted">
                                                <?= lang('App.select_service_time') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Duration Estimate -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="estimated_duration" class="form-label fw-semibold">
                                                <i data-feather="timer" class="icon-xs me-1"></i>
                                                <?= lang('App.estimated_duration') ?>
                                            </label>
                                            <select class="form-select" id="estimated_duration" name="estimated_duration">
                                                <option value=""><?= lang('App.select_duration') ?></option>
                                                <option value="30"><?= lang('App.30_minutes') ?></option>
                                                <option value="60"><?= lang('App.1_hour') ?></option>
                                                <option value="90"><?= lang('App.1_5_hours') ?></option>
                                                <option value="120"><?= lang('App.2_hours') ?></option>
                                                <option value="180"><?= lang('App.3_hours') ?></option>
                                                <option value="240"><?= lang('App.4_hours') ?></option>
                                                <option value="480"><?= lang('App.8_hours') ?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Location/Bay -->
                                    <div class="col-md-4">
                                        <div class="form-section">
                                            <label for="service_bay" class="form-label fw-semibold">
                                                <i data-feather="map-pin" class="icon-xs me-1"></i>
                                                <?= lang('App.service_bay') ?>
                                            </label>
                                            <select class="form-select" id="service_bay" name="service_bay">
                                                <option value=""><?= lang('App.select_bay') ?></option>
                                                <option value="bay_1"><?= lang('App.bay_1') ?></option>
                                                <option value="bay_2"><?= lang('App.bay_2') ?></option>
                                                <option value="bay_3"><?= lang('App.bay_3') ?></option>
                                                <option value="bay_4"><?= lang('App.bay_4') ?></option>
                                                <option value="outdoor"><?= lang('App.outdoor_area') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes & Instructions Tab -->
                        <div class="tab-pane fade" id="notes-pane" role="tabpanel" aria-labelledby="notes-tab">
                            <div class="p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="alert alert-light border-start border-success border-4 mb-4" role="alert">
                                            <div class="d-flex">
                                                <i data-feather="edit-3" class="icon-sm text-success me-2 mt-1"></i>
                                                <div>
                                                    <h6 class="alert-heading mb-1"><?= lang('App.additional_information') ?></h6>
                                                    <p class="mb-0 small text-muted"><?= lang('App.add_notes_instructions') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <!-- Instructions -->
                                    <div class="col-12">
                                        <div class="form-section">
                                            <label for="instructions" class="form-label fw-semibold">
                                                <i data-feather="file-text" class="icon-xs me-1"></i>
                                                <?= lang('App.instructions') ?>
                                            </label>
                                            <textarea class="form-control" id="instructions" name="instructions" rows="4"
                                                      placeholder="<?= lang('App.enter_special_instructions') ?>"></textarea>
                                            <div class="form-text text-muted">
                                                <?= lang('App.visible_to_service_team') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Internal Notes -->
                                    <div class="col-12">
                                        <div class="form-section">
                                            <label for="notes" class="form-label fw-semibold">
                                                <i data-feather="lock" class="icon-xs me-1"></i>
                                                <?= lang('App.internal_notes') ?>
                                            </label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                                      placeholder="<?= lang('App.internal_notes_placeholder') ?>"></textarea>
                                            <div class="form-text text-muted">
                                                <i data-feather="eye-off" class="icon-xs me-1"></i>
                                                <?= lang('App.internal_only_not_visible_customer') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Customer Special Requests -->
                                    <div class="col-12">
                                        <div class="form-section">
                                            <label for="customer_requests" class="form-label fw-semibold">
                                                <i data-feather="message-circle" class="icon-xs me-1"></i>
                                                <?= lang('App.customer_requests') ?>
                                            </label>
                                            <textarea class="form-control" id="customer_requests" name="customer_requests" rows="3"
                                                      placeholder="<?= lang('App.customer_special_requests') ?>"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- History Tab (Only for Edit Mode) -->
                        <div class="tab-pane fade" id="history-pane" role="tabpanel" aria-labelledby="history-tab">
                            <div class="p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="alert alert-light border-start border-secondary border-4 mb-4" role="alert">
                                            <div class="d-flex">
                                                <i data-feather="clock" class="icon-sm text-secondary me-2 mt-1"></i>
                                                <div>
                                                    <h6 class="alert-heading mb-1"><?= lang('App.order_history') ?></h6>
                                                    <p class="mb-0 small text-muted"><?= lang('App.track_changes_activities') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-12">
                                        <div id="orderActivityList" class="order-history-container">
                                            <div class="text-center py-4 text-muted">
                                                <i data-feather="clock" class="icon-lg mb-2"></i>
                                                <p><?= lang('App.no_activity_history') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer bg-light border-0">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i data-feather="x" class="icon-sm me-1"></i>
                            <?= lang('App.cancel') ?>
                        </button>
                        <button type="button" class="btn btn-outline-info" id="saveDraftBtn" style="display: none;">
                            <i data-feather="save" class="icon-sm me-1"></i>
                            <?= lang('App.save_draft') ?>
                        </button>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" id="prevTabBtn" style="display: none;">
                            <i data-feather="chevron-left" class="icon-sm me-1"></i>
                            <?= lang('App.previous') ?>
                        </button>
                        <button type="button" class="btn btn-primary" id="nextTabBtn">
                            <?= lang('App.next') ?>
                            <i data-feather="chevron-right" class="icon-sm ms-1"></i>
                        </button>
                        <button type="submit" class="btn btn-success" id="submitOrderBtn" style="display: none;">
                            <i data-feather="check" class="icon-sm me-1"></i>
                            <span id="submitBtnText"><?= lang('App.create_order') ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VIN Scanner Modal -->
<div class="scanner-modal" id="vinScannerModal">
    <div class="scanner-header">
        <h5><?= lang('App.scan_vin_barcode') ?></h5>
        <button type="button" class="btn btn-outline-secondary btn-sm" id="closeScannerBtn">
            <i data-feather="x" class="icon-sm"></i>
            <?= lang('App.close') ?>
        </button>
    </div>
    <div class="scanner-body">
        <div id="vinScannerVideo"></div>
        <div class="scanner-instructions">
            <p><?= lang('App.position_vin_barcode_center') ?></p>
        </div>
    </div>
</div>

<style>
/* Enhanced Modal Styles */
.modal-xl {
    max-width: 1200px;
}

.modal-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-tabs-line {
    border-bottom: 2px solid #e9ecef;
    padding: 0 1.5rem;
    margin-bottom: 0;
}

.nav-tabs-line .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    background: transparent;
    color: #6c757d;
    padding: 1rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
}

.nav-tabs-line .nav-link:hover {
    border-color: transparent;
    color: #495057;
    background: rgba(0, 123, 255, 0.1);
}

.nav-tabs-line .nav-link.active {
    color: #007bff;
    border-bottom-color: #007bff;
    background: transparent;
}

.form-section {
    margin-bottom: 1.5rem;
}

.form-section:last-child {
    margin-bottom: 0;
}

.form-label.fw-semibold {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1.5px solid #d1d5db;
    padding: 0.75rem;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    outline: none;
}

.vin-input-container {
    position: relative;
}

.scan-vin-btn {
    top: 50%;
    right: 8px;
    transform: translateY(-50%);
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
}

/* Scanner Modal */
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
}

.scanner-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    padding: 2rem;
}

.scanner-instructions {
    text-align: center;
    margin-top: 1rem;
}

/* Order History Container */
.order-history-container {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-xl {
        max-width: calc(100% - 1rem);
        margin: 0.5rem;
    }
    
    .nav-tabs-line {
        padding: 0 0.5rem;
    }
    
    .nav-tabs-line .nav-link {
        padding: 0.75rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .nav-tabs-line .nav-link i {
        display: none;
    }
    
    .modal-footer .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .modal-footer .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Loading States */
.btn.loading {
    position: relative;
    color: transparent !important;
}

.btn.loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>