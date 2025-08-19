<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.vehicles_dashboard') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.vehicles_dashboard') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item active"><?= lang('App.vehicles_dashboard') ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Include ServiceOrders shared styles for consistent styling -->
<?php include(APPPATH . 'Modules/ServiceOrders/Views/service_orders/shared_styles.php'); ?>

<!-- Include Vehicles specific shared styles -->
<?php include(__DIR__ . '/shared_styles.php'); ?>

<style>
/* Vehicles Dashboard Custom Styles */
.vehicles-dashboard-card-title {
    font-size: clamp(0.8rem, 2.5vw, 1.25rem) !important;
    font-weight: 600 !important;
    color: #1f2937 !important;
    text-align: center !important;
    margin: 0 !important;
    padding: 0.25rem 0.5rem !important;
    line-height: 1.2 !important;
    word-break: break-word !important;
}

.vehicle-vin-code {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    color: #64748b;
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

.vehicle-service-count {
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.8rem;
}

.location-tracking-badge {
    background: #10b981;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.chart-container {
    height: 350px;
    position: relative;
}

.quick-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.quick-stat-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
}

.quick-stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1f2937;
}

.quick-stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .vehicles-dashboard-card-title {
        font-size: clamp(0.9rem, 4vw, 1.1rem) !important;
        padding: 0.5rem !important;
    }
    
    .quick-stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.75rem;
    }
}

/* Explicit white backgrounds for all cards and blocks */
.card {
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1px solid #e9ecef !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
}

.card-header {
    background-color: #ffffff !important;
    background-image: none !important;
    border-bottom: 1px solid #e9ecef !important;
}

.card-body {
    background-color: #ffffff !important;
    background-image: none !important;
}

.card-animate {
    background-color: #ffffff !important;
    background-image: none !important;
}

.stats-card {
    background-color: #ffffff !important;
    background-image: none !important;
}

.quick-stat-item {
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1px solid #e9ecef !important;
}

/* Modal backgrounds */
.modal-content {
    background-color: #ffffff !important;
    background-image: none !important;
}

.modal-header {
    background-color: #ffffff !important;
    background-image: none !important;
    border-bottom: 1px solid #e9ecef !important;
}

.modal-body {
    background-color: #ffffff !important;
    background-image: none !important;
}

.modal-footer {
    background-color: #ffffff !important;
    background-image: none !important;
    border-top: 1px solid #e9ecef !important;
}

/* Chart containers */
.chart-container {
    background-color: #ffffff !important;
    background-image: none !important;
}

/* Accordion and collapse elements */
.accordion-item {
    background-color: #ffffff !important;
    background-image: none !important;
}

.accordion-body {
    background-color: #ffffff !important;
    background-image: none !important;
}

.collapse {
    background-color: #ffffff !important;
    background-image: none !important;
}

/* Table backgrounds */
.table {
    background-color: #ffffff !important;
    background-image: none !important;
}

.table thead th {
    background-color: #f8f9fa !important;
    background-image: none !important;
}

.table tbody tr {
    background-color: #ffffff !important;
    background-image: none !important;
}

.table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05) !important;
    background-image: none !important;
}

/* Avatar and icon backgrounds - keep subtle colors but ensure clean backgrounds */
.avatar-title {
    background-image: none !important;
}

.bg-primary-subtle { 
    background-color: rgba(13, 110, 253, 0.1) !important; 
    background-image: none !important;
}
.bg-success-subtle { 
    background-color: rgba(25, 135, 84, 0.1) !important; 
    background-image: none !important;
}
.bg-warning-subtle { 
    background-color: rgba(255, 193, 7, 0.1) !important; 
    background-image: none !important;
}
.bg-info-subtle { 
    background-color: rgba(13, 202, 240, 0.1) !important; 
    background-image: none !important;
}
.bg-danger-subtle { 
    background-color: rgba(220, 53, 69, 0.1) !important; 
    background-image: none !important;
}

/* Tab content backgrounds */
.tab-content {
    background-color: #ffffff !important;
    background-image: none !important;
}

.tab-pane {
    background-color: #ffffff !important;
    background-image: none !important;
}

/* Dropdown backgrounds */
.dropdown-menu {
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1px solid #e9ecef !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
}

.dropdown-item {
    background-color: transparent !important;
    background-image: none !important;
}

.dropdown-item:hover {
    background-color: #f8f9fa !important;
    background-image: none !important;
}

/* Alert backgrounds */
.alert {
    background-image: none !important;
}

.alert-info {
    background-color: rgba(13, 202, 240, 0.1) !important;
    background-image: none !important;
    border: 1px solid rgba(13, 202, 240, 0.2) !important;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex align-items-center">
        <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
            <i data-feather="truck" class="icon-sm me-1"></i>
            <?= lang('App.vehicles_dashboard') ?>
        </h4>
        <div class="flex-shrink-0">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-primary" id="addVehicleBtn">
                    <i data-feather="plus" class="icon-sm me-1"></i>
                    <span class="d-none d-sm-inline"><?= lang('App.add_vehicle') ?></span>
                    <span class="d-inline d-sm-none"><?= lang('App.add') ?></span>
                </button>
            </div>
        </div>
    </div>

    <!-- GLOBAL FILTERS SECTION -->
    <div class="card-body border-bottom p-2">
        <div class="accordion accordion-flush" id="filtersAccordion">
            <div class="accordion-item border-0">
                <h6 class="accordion-header mb-0" id="filtersHeading">
                    <button class="accordion-button collapsed py-2 px-3 bg-light border rounded" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#filtersContent" 
                            aria-expanded="false" aria-controls="filtersContent">
                        <i data-feather="filter" class="icon-sm me-2"></i>
                        <span class="fw-semibold"><?= lang('App.filters') ?></span>
                        <span id="activeFiltersCount" class="badge bg-primary ms-2 d-none">0</span>
                    </button>
                </h6>
                <div id="filtersContent" class="accordion-collapse collapse" 
                     aria-labelledby="filtersHeading" data-bs-parent="#filtersAccordion">
                    <div class="accordion-body pt-3 pb-2">
                        <div class="row g-2 mb-3">
                            <!-- Client Filter -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <label class="form-label text-muted small mb-1"><?= lang('App.filter_by_client') ?></label>
                                <select id="globalClientFilter" class="form-select form-select-sm">
                                    <option value=""><?= lang('App.all_clients') ?></option>
                                </select>
                            </div>

                            <!-- Make Filter -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <label class="form-label text-muted small mb-1"><?= lang('App.filter_by_make') ?></label>
                                <select id="globalMakeFilter" class="form-select form-select-sm">
                                    <option value=""><?= lang('App.all_makes') ?></option>
                                </select>
                            </div>

                            <!-- Year Filter -->
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <label class="form-label text-muted small mb-1"><?= lang('App.filter_by_year') ?></label>
                                <select id="globalYearFilter" class="form-select form-select-sm">
                                    <option value=""><?= lang('App.all_years') ?></option>
                                </select>
                            </div>

                            <!-- Service Count Filter -->
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <label class="form-label text-muted small mb-1"><?= lang('App.filter_by_services') ?></label>
                                <select id="globalServiceCountFilter" class="form-select form-select-sm">
                                    <option value=""><?= lang('App.all_service_counts') ?></option>
                                    <option value="0"><?= lang('App.no_services') ?></option>
                                    <option value="1-5">1-5 <?= lang('App.services') ?></option>
                                    <option value="6-10">6-10 <?= lang('App.services') ?></option>
                                    <option value="11+"><?= lang('App.more_than_10_services') ?></option>
                                </select>
                            </div>

                            <!-- Location Tracking Filter -->
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <label class="form-label text-muted small mb-1"><?= lang('App.location_tracking') ?></label>
                                <select id="globalLocationFilter" class="form-select form-select-sm">
                                    <option value=""><?= lang('App.all_vehicles') ?></option>
                                    <option value="with_locations"><?= lang('App.with_locations') ?></option>
                                    <option value="without_locations"><?= lang('App.without_locations') ?></option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2 flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button id="applyGlobalFilters" class="btn btn-primary btn-sm">
                                            <i data-feather="check" class="icon-sm me-1"></i> <?= lang('App.apply_filters') ?>
                                        </button>
                                        <button id="clearGlobalFilters" class="btn btn-outline-secondary btn-sm">
                                            <i data-feather="x" class="icon-sm me-1"></i> <?= lang('App.clear_filters') ?>
                                        </button>
                                        <button id="refreshAllTables" class="btn btn-outline-info btn-sm">
                                            <i data-feather="refresh-cw" class="icon-sm me-1"></i> <?= lang('App.refresh') ?>
                                        </button>
                                    </div>
                                    <small class="text-muted d-none d-md-block">
                                        <i data-feather="info" class="icon-xs me-1"></i>
                                        <?= lang('App.filters_apply_to_all_views') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#dashboard-tab" role="tab">
                    <span><i data-feather="home" class="icon-sm me-1"></i> <?= lang('App.dashboard') ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#recent-vehicles-tab" role="tab">
                    <span>
                        <i data-feather="clock" class="icon-sm me-1"></i> <?= lang('App.recent_vehicles') ?>
                        <span id="recentVehiclesBadge" class="badge bg-success ms-2" style="display: none;">0</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#active-vehicles-tab" role="tab">
                    <span>
                        <i data-feather="truck" class="icon-sm me-1"></i> <?= lang('App.active_vehicles') ?>
                        <span id="activeVehiclesBadge" class="badge bg-primary ms-2" style="display: none;">0</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#location-tracking-tab" role="tab">
                    <span>
                        <i data-feather="map-pin" class="icon-sm me-1"></i> <?= lang('App.location_tracking') ?>
                        <span id="locationTrackingBadge" class="badge bg-info ms-2" style="display: none;">0</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#analytics-tab" role="tab">
                    <span><i data-feather="bar-chart-2" class="icon-sm me-1"></i> <?= lang('App.analytics') ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#all-vehicles-tab" role="tab">
                    <span><i data-feather="list" class="icon-sm me-1"></i> <?= lang('App.all_vehicles') ?></span>
                </a>
            </li>
        </ul>

        <div class="tab-content py-4">
            <!-- Dashboard Tab -->
            <div class="tab-pane active" id="dashboard-tab" role="tabpanel">
                <?= $this->include('Modules\Vehicles\Views\vehicles/dashboard_content') ?>
            </div>
            
            <!-- Recent Vehicles Tab -->
            <div class="tab-pane" id="recent-vehicles-tab" role="tabpanel">
                <?= $this->include('Modules\Vehicles\Views\vehicles/recent_vehicles_content') ?>
            </div>
            
            <!-- Active Vehicles Tab -->
            <div class="tab-pane" id="active-vehicles-tab" role="tabpanel">
                <?= $this->include('Modules\Vehicles\Views\vehicles/active_vehicles_content') ?>
            </div>
            
            <!-- Location Tracking Tab -->
            <div class="tab-pane" id="location-tracking-tab" role="tabpanel">
                <?= $this->include('Modules\Vehicles\Views\vehicles/location_tracking_content') ?>
            </div>
            
            <!-- Analytics Tab -->
            <div class="tab-pane" id="analytics-tab" role="tabpanel">
                <?= $this->include('Modules\Vehicles\Views\vehicles/analytics_content') ?>
            </div>
            
            <!-- All Vehicles Tab -->
            <div class="tab-pane" id="all-vehicles-tab" role="tabpanel">
                <?= $this->include('Modules\Vehicles\Views\vehicles/all_vehicles_content') ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="vehicleModal" tabindex="-1" aria-labelledby="vehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vehicleModalLabel">
                    <i data-feather="truck" class="icon-sm me-2"></i>
                    Add Vehicle Manually
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="vehicleForm">
                <div class="modal-body">
                    <div class="row">
                        <!-- VIN Input -->
                        <div class="col-12 mb-3">
                            <label for="vin_number" class="form-label">
                                <i data-feather="hash" class="icon-sm me-1"></i>
                                VIN Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="vin_number" 
                                   name="vin_number" 
                                   maxlength="17" 
                                   placeholder="Enter 17-character VIN number"
                                   required>
                            <div class="form-text">
                                <i data-feather="info" class="icon-xs me-1"></i>
                                VIN will be validated and vehicle information will be decoded automatically
                            </div>
                            <div class="invalid-feedback" id="vin_error"></div>
                            <div class="valid-feedback" id="vin_success"></div>
                        </div>

                        <!-- Vehicle Description (Auto-filled) -->
                        <div class="col-12 mb-3">
                            <label for="vehicle_description" class="form-label">
                                <i data-feather="truck" class="icon-sm me-1"></i>
                                Vehicle Description
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="vehicle_description" 
                                   name="vehicle_description" 
                                   placeholder="Vehicle information will be filled automatically"
                                   readonly>
                            <div class="form-text">
                                <i data-feather="zap" class="icon-xs me-1"></i>
                                This field will be populated automatically from VIN decoding
                            </div>
                        </div>

                        <!-- VIN Validation Status -->
                        <div class="col-12 mb-3" id="vin_status_container" style="display: none;">
                            <div class="alert alert-info" id="vin_status">
                                <div class="d-flex align-items-center">
                                    <div class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></div>
                                    <span>Validating VIN...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Decoded Vehicle Information -->
                        <div class="col-12 mb-3" id="decoded_info_container" style="display: none;">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i data-feather="info" class="icon-sm me-1"></i>
                                        Decoded Vehicle Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row" id="decoded_info">
                                        <!-- Decoded information will be populated here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i data-feather="x" class="icon-sm me-1"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="save_vehicle_btn" disabled>
                        <i data-feather="save" class="icon-sm me-1"></i>
                        <span class="btn-text">Save Vehicle & Generate QR</span>
                        <span class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QR Code Result Modal -->
<div class="modal fade" id="qrResultModal" tabindex="-1" aria-labelledby="qrResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrResultModalLabel">
                    <i data-feather="check-circle" class="icon-sm me-2 text-success"></i>
                    Vehicle Created Successfully
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="alert alert-success">
                        <h6 class="alert-heading">Vehicle Added Successfully!</h6>
                        <p class="mb-0">Your vehicle has been created and QR codes have been generated.</p>
                    </div>
                </div>

                <div class="row">
                    <!-- Vehicle Profile QR -->
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header text-center">
                                <h6 class="card-title mb-0">
                                    <i data-feather="eye" class="icon-sm me-1"></i>
                                    Vehicle Profile
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                <div id="profile_qr_container">
                                    <!-- QR code will be inserted here -->
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">Scan to view vehicle details</small>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary" id="copy_profile_url">
                                        <i data-feather="copy" class="icon-xs me-1"></i>
                                        Copy Link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Tracking QR -->
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header text-center">
                                <h6 class="card-title mb-0">
                                    <i data-feather="map-pin" class="icon-sm me-1"></i>
                                    Location Tracking
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                <div id="tracking_qr_container">
                                    <!-- QR code will be inserted here -->
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">Scan to track location</small>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-info" id="copy_tracking_url">
                                        <i data-feather="copy" class="icon-xs me-1"></i>
                                        Copy Link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Information Summary -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i data-feather="info" class="icon-sm me-1"></i>
                            Vehicle Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="vehicle_summary">
                            <!-- Vehicle summary will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i data-feather="x" class="icon-sm me-1"></i>
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="view_vehicle_btn">
                    <i data-feather="eye" class="icon-sm me-1"></i>
                    View Vehicle
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// =====================================================================
// VEHICLES LOCALSTORAGE FUNCTIONALITY
// =====================================================================

// Initialize global filters object with localStorage support
window.globalFilters = {
    client: '',
    make: '',
    year: '',
    serviceCount: '',
    location: ''
};

// Initialize globalFilters from localStorage
function initializeGlobalFiltersFromStorage() {
    // Load saved filters immediately to ensure they're available
    const savedClient = localStorage.getItem('vehiclesGlobalClientFilter');
    const savedMake = localStorage.getItem('vehiclesGlobalMakeFilter');
    const savedYear = localStorage.getItem('vehiclesGlobalYearFilter');
    const savedServiceCount = localStorage.getItem('vehiclesGlobalServiceCountFilter');
    const savedLocation = localStorage.getItem('vehiclesGlobalLocationFilter');
    
    window.globalFilters.client = savedClient || '';
    window.globalFilters.make = savedMake || '';
    window.globalFilters.year = savedYear || '';
    window.globalFilters.serviceCount = savedServiceCount || '';
    window.globalFilters.location = savedLocation || '';
}

// Call initialization immediately
initializeGlobalFiltersFromStorage();

// Essential functions needed by content files immediately
window.getCurrentClientFilter = function() {
    return window.globalFilters?.client || '';
};

window.getCurrentClientName = function() {
    const clientId = window.globalFilters?.client;
    if (!clientId) return '';
    
    const globalClientFilterSelect = document.getElementById('globalClientFilter');
    if (!globalClientFilterSelect) return '';
    
    const option = globalClientFilterSelect.querySelector(`option[value="${clientId}"]`);
    return option ? option.textContent.trim() : '';
};

window.getCurrentMakeFilter = function() {
    return window.globalFilters?.make || '';
};

window.getCurrentYearFilter = function() {
    return window.globalFilters?.year || '';
};

window.getCurrentServiceCountFilter = function() {
    return window.globalFilters?.serviceCount || '';
};

window.getCurrentLocationFilter = function() {
    return window.globalFilters?.location || '';
};

// Save filters to localStorage
function saveFiltersToStorage() {
    localStorage.setItem('vehiclesGlobalClientFilter', window.globalFilters.client);
    localStorage.setItem('vehiclesGlobalMakeFilter', window.globalFilters.make);
    localStorage.setItem('vehiclesGlobalYearFilter', window.globalFilters.year);
    localStorage.setItem('vehiclesGlobalServiceCountFilter', window.globalFilters.serviceCount);
    localStorage.setItem('vehiclesGlobalLocationFilter', window.globalFilters.location);
}

// Load filters from localStorage and apply to UI
function loadFiltersFromStorage() {
    initializeGlobalFiltersFromStorage();
    
    // Wait for DOM elements to be available
    setTimeout(() => {
        if (typeof $ !== 'undefined') {
            $('#globalClientFilter').val(window.globalFilters.client);
            $('#globalMakeFilter').val(window.globalFilters.make);
            $('#globalYearFilter').val(window.globalFilters.year);
            $('#globalServiceCountFilter').val(window.globalFilters.serviceCount);
            $('#globalLocationFilter').val(window.globalFilters.location);
            
            // Update active filters count
            updateActiveFiltersCount();
        }
    }, 500);
}

// Clear filters from localStorage
function clearFiltersFromStorage() {
    localStorage.removeItem('vehiclesGlobalClientFilter');
    localStorage.removeItem('vehiclesGlobalMakeFilter');
    localStorage.removeItem('vehiclesGlobalYearFilter');
    localStorage.removeItem('vehiclesGlobalServiceCountFilter');
    localStorage.removeItem('vehiclesGlobalLocationFilter');
    
    // Reset global filters object
    window.globalFilters = {
        client: '',
        make: '',
        year: '',
        serviceCount: '',
        location: ''
    };
}

// Tab persistence functionality
const ACTIVE_TAB_KEY = 'vehicles_activeTab';

// Function to get last active tab from localStorage
function getLastActiveTab() {
    const savedTab = localStorage.getItem(ACTIVE_TAB_KEY);
    return savedTab || '#dashboard-tab'; // Default to dashboard if nothing saved
}

// Function to save active tab to localStorage
function saveActiveTab(tabId) {
    localStorage.setItem(ACTIVE_TAB_KEY, tabId);
}

// Update active filters count badge
function updateActiveFiltersCount() {
    let count = 0;
    if (window.globalFilters.client) count++;
    if (window.globalFilters.make) count++;
    if (window.globalFilters.year) count++;
    if (window.globalFilters.serviceCount) count++;
    if (window.globalFilters.location) count++;
    
    const badge = document.getElementById('activeFiltersCount');
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }
}

// =====================================================================
// VEHICLES DASHBOARD INITIALIZATION
// =====================================================================

// Essential Global Functions for Vehicles Dashboard
function initVehiclesDashboard() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.warn('jQuery not available, retrying in 500ms...');
        setTimeout(initVehiclesDashboard, 500);
        return;
    }
    
    console.log('🚗 Vehicles Dashboard - Initializing');

    // Load filters from localStorage first
    loadFiltersFromStorage();
    
    // Initialize global filters
    initializeGlobalFilters();
    
    // Initialize tab functionality with localStorage support
    initializeTabFunctionality();
    
    // Restore last active tab
    restoreActiveTab();
    
    // Set up filter event listeners for auto-save
    setupFilterEventListeners();
    
    // Load initial dashboard data
    loadDashboardData();
    
    // Set up auto-refresh
    setInterval(refreshDashboard, 60000); // Refresh every minute
    
    console.log('✅ Vehicles Dashboard initialized successfully');
}

// =====================================================================
// LOCALSTORAGE HELPER FUNCTIONS
// =====================================================================

// Restore the last active tab from localStorage
function restoreActiveTab() {
    if (typeof $ === 'undefined') {
        setTimeout(restoreActiveTab, 100);
        return;
    }
    
    const lastActiveTab = getLastActiveTab();
    
    // Remove active class from all tabs and tab panes
    $('.nav-link').removeClass('active');
    $('.tab-pane').removeClass('active show');
    
    // Activate the saved tab
    $(`a[href="${lastActiveTab}"]`).addClass('active');
    $(lastActiveTab).addClass('active show');
    
    // Initialize the restored tab after a delay
    setTimeout(() => {
        console.log('🔄 Restoring tab:', lastActiveTab);
        if (typeof window.handleTabTableInitialization === 'function') {
            window.handleTabTableInitialization(lastActiveTab);
        }
    }, 1000);
}

// Set up event listeners for automatic filter saving
function setupFilterEventListeners() {
    if (typeof $ === 'undefined') {
        setTimeout(setupFilterEventListeners, 100);
        return;
    }
    
    // Auto-save when filter values change
    $('#globalClientFilter').on('change', function() {
        window.globalFilters.client = $(this).val();
        saveFiltersToStorage();
        updateActiveFiltersCount();
        console.log('💾 Client filter saved:', window.globalFilters.client);
    });
    
    $('#globalMakeFilter').on('change', function() {
        window.globalFilters.make = $(this).val();
        saveFiltersToStorage();
        updateActiveFiltersCount();
        console.log('💾 Make filter saved:', window.globalFilters.make);
    });
    
    $('#globalYearFilter').on('change', function() {
        window.globalFilters.year = $(this).val();
        saveFiltersToStorage();
        updateActiveFiltersCount();
        console.log('💾 Year filter saved:', window.globalFilters.year);
    });
    
    $('#globalServiceCountFilter').on('change', function() {
        window.globalFilters.serviceCount = $(this).val();
        saveFiltersToStorage();
        updateActiveFiltersCount();
        console.log('💾 Service count filter saved:', window.globalFilters.serviceCount);
    });
    
    $('#globalLocationFilter').on('change', function() {
        window.globalFilters.location = $(this).val();
        saveFiltersToStorage();
        updateActiveFiltersCount();
        console.log('💾 Location filter saved:', window.globalFilters.location);
    });
    
    // Set up tab event listeners for saving active tab
    const tabElements = $('a[data-bs-toggle="tab"]');
    
    tabElements.on('shown.bs.tab', function (e) {
        const targetTab = e.target.getAttribute('href');
        
        // Save the new active tab to localStorage
        saveActiveTab(targetTab);
        console.log('💾 Active tab saved:', targetTab);
        
        // Initialize the table for the new tab
        setTimeout(() => {
            if (typeof window.handleTabTableInitialization === 'function') {
                window.handleTabTableInitialization(targetTab);
            }
        }, 500);
    });
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initVehiclesDashboard);
} else {
    initVehiclesDashboard();
}

function initializeGlobalFilters() {
    if (typeof $ === 'undefined') {
        setTimeout(initializeGlobalFilters, 100);
        return;
    }
    
    // Load filter options
    loadFilterOptions();
    
    // Apply filters button
    $('#applyGlobalFilters').on('click', function() {
        applyGlobalFilters();
    });
    
    // Clear filters button
    $('#clearGlobalFilters').on('click', function() {
        clearGlobalFilters();
    });
    
    // Refresh button
    $('#refreshAllTables').on('click', function() {
        refreshDashboard();
    });
}

function loadFilterOptions() {
    if (typeof $ === 'undefined') {
        setTimeout(loadFilterOptions, 100);
        return;
    }
    
    // Load clients for filter
    $.get('<?= base_url('vehicles/filter-options/clients') ?>')
        .done(function(data) {
            const clientSelect = $('#globalClientFilter');
            clientSelect.find('option:not(:first)').remove();
            
            if (data.clients) {
                data.clients.forEach(client => {
                    clientSelect.append(`<option value="${client.id}">${client.name}</option>`);
                });
            }
        })
        .fail(function() {
            console.warn('Failed to load client filter options - endpoint not implemented');
            // Add some example data for development
            const clientSelect = $('#globalClientFilter');
            clientSelect.append(`<option value="1">Example Client 1</option>`);
            clientSelect.append(`<option value="2">Example Client 2</option>`);
        });

    // Load makes for filter
    $.get('<?= base_url('vehicles/filter-options/makes') ?>')
        .done(function(data) {
            const makeSelect = $('#globalMakeFilter');
            makeSelect.find('option:not(:first)').remove();
            
            if (data.makes) {
                data.makes.forEach(make => {
                    makeSelect.append(`<option value="${make}">${make}</option>`);
                });
            }
        })
        .fail(function() {
            console.warn('Failed to load make filter options - endpoint not implemented');
            // Add some example data for development
            const makeSelect = $('#globalMakeFilter');
            const commonMakes = ['Toyota', 'Honda', 'Ford', 'Chevrolet', 'BMW', 'Mercedes', 'Audi', 'Volkswagen'];
            commonMakes.forEach(make => {
                makeSelect.append(`<option value="${make}">${make}</option>`);
            });
        });

    // Load years for filter
    $.get('<?= base_url('vehicles/filter-options/years') ?>')
        .done(function(data) {
            const yearSelect = $('#globalYearFilter');
            yearSelect.find('option:not(:first)').remove();
            
            if (data.years) {
                data.years.forEach(year => {
                    yearSelect.append(`<option value="${year}">${year}</option>`);
                });
            }
        })
        .fail(function() {
            console.warn('Failed to load year filter options - endpoint not implemented');
            // Add some example data for development
            const yearSelect = $('#globalYearFilter');
            const currentYear = new Date().getFullYear();
            for (let year = currentYear; year >= currentYear - 20; year--) {
                yearSelect.append(`<option value="${year}">${year}</option>`);
            }
        });
}

function initializeTabFunctionality() {
    if (typeof $ === 'undefined') {
        setTimeout(initializeTabFunctionality, 100);
        return;
    }
    
    // Tab switching functionality
    $('a[data-bs-toggle="tab"]').on('show.bs.tab', function (e) {
        const targetTab = $(e.target).attr('href');
        console.log('Switching to tab:', targetTab);
        
        // Refresh specific tab content if needed
        switch(targetTab) {
            case '#recent-vehicles-tab':
                refreshRecentVehicles();
                break;
            case '#active-vehicles-tab':
                refreshActiveVehicles();
                break;
            case '#location-tracking-tab':
                refreshLocationTracking();
                break;
            case '#analytics-tab':
                refreshAnalytics();
                break;
            case '#all-vehicles-tab':
                refreshAllVehicles();
                break;
        }
    });
}

function showTab(tabId) {
    // Remove active class from all tabs
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
    });
    
    // Add active class to target tab
    const targetTab = document.querySelector(`[href="#${tabId}"]`);
    const targetPane = document.getElementById(tabId);
    
    if (targetTab && targetPane) {
        targetTab.classList.add('active');
        targetPane.classList.add('active');
    }
}

function loadDashboardData() {
    if (typeof $ === 'undefined') {
        setTimeout(loadDashboardData, 100);
        return;
    }
    
    // Load main dashboard statistics
    $.get('<?= base_url('vehicles/dashboard-data') ?>')
        .done(function(data) {
            console.log('📊 Dashboard data received:', data);
            if (data.success) {
                console.log('✅ Dashboard data success, updating stats and badges');
                updateDashboardStats(data);
                updateBadges(data);
            } else {
                console.error('❌ Dashboard data failed:', data);
            }
        })
        .fail(function(xhr, status, error) {
            console.warn('Failed to load dashboard data:', error);
            console.warn('Using sample data for development');
            
            // Provide sample data for development
            const sampleData = {
                success: true,
                totalVehicles: 156,
                activeVehicles: 89,
                locationTracked: 67,
                totalServices: 1248,
                recentCount: 12,
                activeCount: 89,
                locationTrackingCount: 67,
                averageServices: '8.2',
                mostPopularMake: 'Toyota',
                newestYear: '2024',
                locationTrackingPercentage: 43,
                recentActivity: generateSampleActivity(),
                topClients: generateSampleClients()
            };
            
            console.log('📊 Using sample dashboard data:', sampleData);
            updateDashboardStats(sampleData);
            updateBadges(sampleData);
        });
}

function generateSampleActivity() {
    return [
        {
            id: 1,
            vehicle: '2023 Toyota Camry',
            vin_last6: 'ABC123',
            client_name: 'ABC Motors',
            last_service_date: '2024-01-15',
            last_order_number: 'SO-2024-001',
            total_services: 5,
            has_location_tracking: true
        },
        {
            id: 2,
            vehicle: '2022 Honda Civic',
            vin_last6: 'DEF456',
            client_name: 'City Auto',
            last_service_date: '2024-01-14',
            last_order_number: 'SO-2024-002',
            total_services: 3,
            has_location_tracking: false
        },
        {
            id: 3,
            vehicle: '2024 Ford F-150',
            vin_last6: 'GHI789',
            client_name: 'Fleet Services',
            last_service_date: '2024-01-13',
            last_order_number: 'SO-2024-003',
            total_services: 8,
            has_location_tracking: true
        }
    ];
}

function generateSampleClients() {
    return [
        { name: 'ABC Motors', vehicle_count: 25, total_services: 156 },
        { name: 'City Auto', vehicle_count: 18, total_services: 89 },
        { name: 'Fleet Services', vehicle_count: 32, total_services: 234 },
        { name: 'Quick Fix Auto', vehicle_count: 12, total_services: 67 }
    ];
}

function updateDashboardStats(data) {
    // Update dashboard statistics (will be implemented in dashboard_content.php)
    if (window.updateVehicleStats) {
        window.updateVehicleStats(data);
    }
}

function updateBadges(data) {
    console.log('🏷️ Updating badges with data:', data);
    updateBadge('recentVehiclesBadge', data.recentCount);
    updateBadge('activeVehiclesBadge', data.activeCount);
    updateBadge('locationTrackingBadge', data.locationTrackingCount);
}

function updateBadge(badgeId, count) {
    const badge = document.getElementById(badgeId);
    if (badge) {
        const displayCount = count || 0;
        badge.textContent = displayCount;
        badge.style.display = displayCount > 0 ? 'inline' : 'none';
        console.log(`🏷️ Updated ${badgeId}: ${displayCount} (${displayCount > 0 ? 'visible' : 'hidden'})`);
    } else {
        console.warn(`🏷️ Badge element not found: ${badgeId}`);
    }
}

function applyGlobalFilters() {
    if (typeof $ === 'undefined') {
        console.warn('jQuery not available for applyGlobalFilters');
        return;
    }
    
    // Get current filter values and update global filters object
    window.globalFilters.client = $('#globalClientFilter').val();
    window.globalFilters.make = $('#globalMakeFilter').val();
    window.globalFilters.year = $('#globalYearFilter').val();
    window.globalFilters.serviceCount = $('#globalServiceCountFilter').val();
    window.globalFilters.location = $('#globalLocationFilter').val();
    
    // Save to localStorage
    saveFiltersToStorage();
    
    // Store filters for use across tabs (backward compatibility)
    window.vehicleGlobalFilters = window.globalFilters;
    
    // Apply to current active tab
    const activeTab = $('.tab-pane.active').attr('id');
    applyFiltersToTab(activeTab);
    
    // Update active filters count
    updateActiveFiltersCount();
    
    console.log('🔍 Filters applied and saved to localStorage:', window.globalFilters);
}

function clearGlobalFilters() {
    if (typeof $ === 'undefined') {
        console.warn('jQuery not available for clearGlobalFilters');
        return;
    }
    
    // Clear form inputs
    $('#globalClientFilter').val('');
    $('#globalMakeFilter').val('');
    $('#globalYearFilter').val('');
    $('#globalServiceCountFilter').val('');
    $('#globalLocationFilter').val('');
    
    // Clear localStorage and global filters object
    clearFiltersFromStorage();
    
    // Store filters for use across tabs (backward compatibility)
    window.vehicleGlobalFilters = {};
    
    // Update active filters count
    updateActiveFiltersCount();
    
    // Refresh current active tab
    const activeTab = $('.tab-pane.active').attr('id');
    applyFiltersToTab(activeTab);
    
    console.log('🗑️ All filters cleared from localStorage');
}

function applyFiltersToTab(tabId) {
    // Apply filters to specific tab
    switch(tabId) {
        case 'dashboard-tab':
            refreshDashboard();
            break;
        case 'recent-vehicles-tab':
            refreshRecentVehicles();
            break;
        case 'active-vehicles-tab':
            refreshActiveVehicles();
            break;
        case 'location-tracking-tab':
            refreshLocationTracking();
            break;
        case 'analytics-tab':
            refreshAnalytics();
            break;
        case 'all-vehicles-tab':
            refreshAllVehicles();
            break;
    }
}

function refreshDashboard() {
    loadDashboardData();
}

function refreshRecentVehicles() {
    if (window.refreshRecentVehiclesTable) {
        window.refreshRecentVehiclesTable();
    }
}

function refreshActiveVehicles() {
    if (window.refreshActiveVehiclesTable) {
        window.refreshActiveVehiclesTable();
    }
}

function refreshLocationTracking() {
    if (window.refreshLocationTrackingTable) {
        window.refreshLocationTrackingTable();
    }
}

function refreshAnalytics() {
    if (window.refreshAnalyticsCharts) {
        window.refreshAnalyticsCharts();
    }
}

function refreshAllVehicles() {
    if (window.refreshAllVehiclesTable) {
        window.refreshAllVehiclesTable();
    }
}

// Initialize Feather icons
if (typeof feather !== 'undefined') {
    feather.replace();
}

// =====================================================================
// VEHICLE MODAL AND QR GENERATION FUNCTIONALITY
// =====================================================================

// Initialize vehicle modal functionality
function initVehicleModal() {
    if (typeof $ === 'undefined') {
        setTimeout(initVehicleModal, 100);
        return;
    }
    
    // Add vehicle button click
    $('#addVehicleBtn').on('click', function() {
        $('#vehicleModal').modal('show');
        resetVehicleForm();
    });
    
    // VIN input validation
    $('#vin_number').on('input', function() {
        const vin = $(this).val().toUpperCase();
        $(this).val(vin); // Convert to uppercase
        
        if (vin.length === 17) {
            validateAndDecodeVIN(vin);
        } else {
            resetVehicleValidation();
        }
    });
    
    // Form submission
    $('#vehicleForm').on('submit', function(e) {
        e.preventDefault();
        submitVehicleForm();
    });
    
    // QR result modal buttons
    $('#copy_profile_url').on('click', function() {
        copyToClipboard(window.currentVehicleData?.profile_url, 'Profile URL copied to clipboard!');
    });
    
    $('#copy_tracking_url').on('click', function() {
        copyToClipboard(window.currentVehicleData?.tracking_url, 'Tracking URL copied to clipboard!');
    });
    
    $('#view_vehicle_btn').on('click', function() {
        if (window.currentVehicleData?.vehicle_url) {
            window.location.href = window.currentVehicleData.vehicle_url;
        }
    });
}

// Reset vehicle form
function resetVehicleForm() {
    $('#vehicleForm')[0].reset();
    $('#vehicle_description').val('').prop('readonly', true);
    $('#save_vehicle_btn').prop('disabled', true);
    resetVehicleValidation();
}

// Reset validation states
function resetVehicleValidation() {
    $('#vin_number').removeClass('is-valid is-invalid');
    $('#vin_status_container').hide();
    $('#decoded_info_container').hide();
    $('#save_vehicle_btn').prop('disabled', true);
}

// Validate and decode VIN
function validateAndDecodeVIN(vin) {
    console.log('🔍 Validating VIN:', vin);
    
    // Show loading state
    $('#vin_status_container').show();
    $('#vin_status').html(`
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></div>
            <span>Validating VIN...</span>
        </div>
    `).removeClass('alert-success alert-danger').addClass('alert-info');
    
    // Basic VIN validation
    if (!isValidVinFormat(vin)) {
        showVinError('Invalid VIN format. VIN must be 17 characters and contain only letters and numbers (no I, O, or Q).');
        return;
    }
    
    // VIN checksum validation
    if (!isValidVinChecksum(vin)) {
        showVinError('Invalid VIN checksum. Please verify the VIN number is correct.');
        return;
    }
    
    // VIN passed validation, now decode
    decodeVIN(vin);
}

// Basic VIN format validation
function isValidVinFormat(vin) {
    // Must be exactly 17 characters
    if (vin.length !== 17) return false;
    
    // Must contain only valid characters (no I, O, Q)
    const validPattern = /^[A-HJ-NPR-Z0-9]{17}$/;
    if (!validPattern.test(vin)) return false;
    
    // Check for suspicious patterns
    const repeatedChar = vin.charAt(0);
    const isAllSameChar = vin.split('').every(char => char === repeatedChar);
    if (isAllSameChar) return false;
    
    return true;
}

// VIN checksum validation (simplified)
function isValidVinChecksum(vin) {
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
        sum += (values[char] || 0) * weights[i];
    }
    
    const checkDigit = sum % 11;
    const expectedCheck = checkDigit === 10 ? 'X' : checkDigit.toString();
    const actualCheck = vin.charAt(8);
    
    return expectedCheck === actualCheck;
}

// Decode VIN using NHTSA API with fallback
function decodeVIN(vin) {
    console.log('🔧 Decoding VIN:', vin);
    
    // Try NHTSA API first
    const nhtsa_url = `https://vpic.nhtsa.dot.gov/api/vehicles/decodevin/${vin}?format=json`;
    
    fetch(nhtsa_url)
        .then(response => response.json())
        .then(data => {
            console.log('📡 NHTSA API Response:', data);
            
            if (data.Results && data.Results.length > 0) {
                processNHTSAResponse(vin, data.Results);
            } else {
                console.warn('⚠️ NHTSA API returned no results, using fallback');
                processFallbackDecoding(vin);
            }
        })
        .catch(error => {
            console.warn('⚠️ NHTSA API failed, using fallback:', error);
            processFallbackDecoding(vin);
        });
}

// Process NHTSA API response
function processNHTSAResponse(vin, results) {
    const vehicleData = {};
    
    // Extract relevant information
    results.forEach(result => {
        switch(result.Variable) {
            case 'Make':
                vehicleData.make = result.Value || '';
                break;
            case 'Model':
                vehicleData.model = result.Value || '';
                break;
            case 'Model Year':
                vehicleData.year = result.Value || '';
                break;
            case 'Vehicle Type':
                vehicleData.type = result.Value || '';
                break;
            case 'Body Class':
                vehicleData.bodyClass = result.Value || '';
                break;
            case 'Engine Number of Cylinders':
                vehicleData.cylinders = result.Value || '';
                break;
            case 'Fuel Type - Primary':
                vehicleData.fuelType = result.Value || '';
                break;
        }
    });
    
    // Build vehicle description
    let description = '';
    if (vehicleData.year) description += vehicleData.year + ' ';
    if (vehicleData.make) description += vehicleData.make + ' ';
    if (vehicleData.model) description += vehicleData.model;
    
    if (!description.trim()) {
        description = 'Vehicle Information Available';
    }
    
    showVinSuccess(vin, description.trim(), vehicleData);
}

// Fallback VIN decoding
function processFallbackDecoding(vin) {
    console.log('🔄 Using fallback VIN decoding');
    
    // Extract basic information from VIN structure
    const wmi = vin.substring(0, 3); // World Manufacturer Identifier
    const year_code = vin.charAt(9);
    
    // Simple year decoding
    const year_map = {
        'A': '2010', 'B': '2011', 'C': '2012', 'D': '2013', 'E': '2014',
        'F': '2015', 'G': '2016', 'H': '2017', 'J': '2018', 'K': '2019',
        'L': '2020', 'M': '2021', 'N': '2022', 'P': '2023', 'R': '2024',
        '1': '2001', '2': '2002', '3': '2003', '4': '2004', '5': '2005',
        '6': '2006', '7': '2007', '8': '2008', '9': '2009'
    };
    
    const year = year_map[year_code] || '';
    const description = year ? `${year} Vehicle (VIN: ${vin.substring(-6)})` : `Vehicle (VIN: ${vin.substring(-6)})`;
    
    const vehicleData = {
        year: year,
        make: 'Unknown',
        model: 'Unknown',
        source: 'fallback'
    };
    
    showVinSuccess(vin, description, vehicleData);
}

// Show VIN validation error
function showVinError(message) {
    $('#vin_number').removeClass('is-valid').addClass('is-invalid');
    $('#vin_error').text(message);
    $('#vin_status').html(`
        <div class="d-flex align-items-center">
            <i data-feather="x-circle" class="icon-sm me-2 text-danger"></i>
            <span>${message}</span>
        </div>
    `).removeClass('alert-info alert-success').addClass('alert-danger');
    
    $('#decoded_info_container').hide();
    $('#save_vehicle_btn').prop('disabled', true);
    
    if (typeof feather !== 'undefined') feather.replace();
}

// Show VIN validation success
function showVinSuccess(vin, description, vehicleData) {
    $('#vin_number').removeClass('is-invalid').addClass('is-valid');
    $('#vin_success').text('VIN validated successfully');
    $('#vehicle_description').val(description).prop('readonly', false);
    
    $('#vin_status').html(`
        <div class="d-flex align-items-center">
            <i data-feather="check-circle" class="icon-sm me-2 text-success"></i>
            <span>VIN validated and decoded successfully</span>
        </div>
    `).removeClass('alert-info alert-danger').addClass('alert-success');
    
    // Show decoded information
    displayDecodedInfo(vehicleData);
    $('#decoded_info_container').show();
    $('#save_vehicle_btn').prop('disabled', false);
    
    if (typeof feather !== 'undefined') feather.replace();
}

// Display decoded vehicle information
function displayDecodedInfo(vehicleData) {
    let html = '';
    
    if (vehicleData.year) {
        html += `<div class="col-md-6 mb-2"><strong>Year:</strong> ${vehicleData.year}</div>`;
    }
    if (vehicleData.make) {
        html += `<div class="col-md-6 mb-2"><strong>Make:</strong> ${vehicleData.make}</div>`;
    }
    if (vehicleData.model) {
        html += `<div class="col-md-6 mb-2"><strong>Model:</strong> ${vehicleData.model}</div>`;
    }
    if (vehicleData.type) {
        html += `<div class="col-md-6 mb-2"><strong>Type:</strong> ${vehicleData.type}</div>`;
    }
    if (vehicleData.bodyClass) {
        html += `<div class="col-md-6 mb-2"><strong>Body:</strong> ${vehicleData.bodyClass}</div>`;
    }
    if (vehicleData.fuelType) {
        html += `<div class="col-md-6 mb-2"><strong>Fuel:</strong> ${vehicleData.fuelType}</div>`;
    }
    
    if (!html) {
        html = '<div class="col-12"><em>Basic VIN validation passed. Limited information available.</em></div>';
    }
    
    $('#decoded_info').html(html);
}

// Submit vehicle form
function submitVehicleForm() {
    const submitBtn = $('#save_vehicle_btn');
    const btnText = submitBtn.find('.btn-text');
    const btnSpinner = submitBtn.find('.spinner-border');
    
    // Show loading state
    submitBtn.prop('disabled', true);
    btnText.text('Creating Vehicle...');
    btnSpinner.removeClass('d-none');
    
    const formData = {
        vin_number: $('#vin_number').val(),
        vehicle_description: $('#vehicle_description').val()
    };
    
    console.log('💾 Submitting vehicle form:', formData);
    
    // Submit to backend
    $.post('<?= base_url('vehicles/generate-qr') ?>', formData)
        .done(function(response) {
            console.log('✅ Vehicle creation response:', response);
            
            if (response.success) {
                // Store vehicle data globally
                window.currentVehicleData = {
                    vehicle: response.vehicle,
                    shortlink_data: response.shortlink_data,
                    profile_url: response.shortlink_data?.short_url,
                    tracking_url: '<?= base_url('api/location/track/') ?>' + response.vehicle.vin_number.slice(-6),
                    vehicle_url: '<?= base_url('vehicles/v/') ?>' + response.vehicle.vin_number.slice(-6)
                };
                
                // Close vehicle modal and show result modal
                $('#vehicleModal').modal('hide');
                showQRResultModal(response);
                
                // Refresh dashboard
                refreshDashboard();
                
            } else {
                showToast('error', response.error || 'Failed to create vehicle');
                resetSubmitButton();
            }
        })
        .fail(function(xhr, status, error) {
            console.error('❌ Vehicle creation failed:', error);
            showToast('error', 'Failed to create vehicle. Please try again.');
            resetSubmitButton();
        });
    
    function resetSubmitButton() {
        submitBtn.prop('disabled', false);
        btnText.text('Save Vehicle & Generate QR');
        btnSpinner.addClass('d-none');
    }
}

// Show QR result modal
function showQRResultModal(response) {
    const vehicle = response.vehicle;
    const shortlinkData = response.shortlink_data;
    
    // Update modal title with vehicle info
    $('#qrResultModalLabel').html(`
        <i data-feather="check-circle" class="icon-sm me-2 text-success"></i>
        ${vehicle.vehicle_info || 'Vehicle'} Created Successfully
    `);
    
    // Generate QR codes
    const profileQR = generateQRCode(shortlinkData.short_url, 200);
    const trackingQR = generateQRCode(window.currentVehicleData.tracking_url, 200);
    
    $('#profile_qr_container').html(profileQR);
    $('#tracking_qr_container').html(trackingQR);
    
    // Vehicle summary
    const summary = `
        <div class="row">
            <div class="col-md-6 mb-2"><strong>VIN:</strong> ${vehicle.vin_number}</div>
            <div class="col-md-6 mb-2"><strong>Vehicle:</strong> ${vehicle.vehicle_info}</div>
            <div class="col-md-6 mb-2"><strong>Profile URL:</strong> 
                <a href="${shortlinkData.short_url}" target="_blank">${shortlinkData.short_url}</a>
            </div>
            <div class="col-md-6 mb-2"><strong>Custom Slug:</strong> ${shortlinkData.custom_slug || 'Auto-generated'}</div>
        </div>
    `;
    
    $('#vehicle_summary').html(summary);
    
    // Show modal
    $('#qrResultModal').modal('show');
    
    if (typeof feather !== 'undefined') feather.replace();
}

// Generate QR Code HTML
function generateQRCode(url, size = 200) {
    return `<img src="https://api.qrserver.com/v1/create-qr-code/?size=${size}x${size}&data=${encodeURIComponent(url)}" 
                 alt="QR Code" class="img-fluid" style="max-width: ${size}px;">`;
}

// Copy to clipboard utility
function copyToClipboard(text, successMessage = 'Copied to clipboard!') {
    if (!text) return;
    
    navigator.clipboard.writeText(text).then(function() {
        showToast('success', successMessage);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
        showToast('error', 'Failed to copy to clipboard');
    });
}

// Initialize vehicle modal when page loads
$(document).ready(function() {
    setTimeout(initVehicleModal, 1000);
});
</script>

<!-- Estilos específicos del módulo Vehicles ya incluidos en shared_styles.php -->

<script>
// Vehicles Column Helpers - Similar to ServiceOrders pattern
window.VehiclesColumnHelpers = {

    // Generate Vehicle Info column (Vehicle + Full VIN + Location Badge)
    generateVehicleInfoColumn: function() {
        return {
            data: null,
            className: 'vehicle-info-cell',
            responsivePriority: 1,
            render: function(data, type, row) {
                let html = '<div>';
                // Row 1: Vehicle Name
                html += '<div class="vehicle-name">' + (row.vehicle || 'N/A') + '</div>';
                // Row 2: Full VIN Number
                if (row.vin_number && row.vin_number !== '') {
                    html += '<div class="vin-info">VIN: ' + row.vin_number + '</div>';
                } else {
                    html += '<div class="vin-info">VIN: N/A</div>';
                }
                // Row 3: Location Tracking Badge (if available)
                if (row.location_tracking_count && parseInt(row.location_tracking_count) > 0) {
                    html += '<div class="location-badge badge bg-info text-white" data-bs-toggle="tooltip" title="' + 
                           row.location_tracking_count + ' location records">';
                    html += '<i class="ri-map-pin-line me-1"></i>Tracked';
                    html += '</div>';
                }
                html += '</div>';
                return html;
            }
        };
    },

    // Generate Client column (Client only - vehicle info is redundant)
    generateClientColumn: function() {
        return {
            data: null,
            className: 'client-cell',
            responsivePriority: 2,
            render: function(data, type, row) {
                return '<div class="client-info">' +
                       '<i class="ri-building-line me-1"></i>' +
                       '<span>' + (row.client_name || 'N/A') + '</span>' +
                       '</div>';
            }
        };
    },

    // Generate Service History column (Total + First + Last + Tooltip)
    generateServiceHistoryColumn: function() {
        return {
            data: null,
            className: 'service-history-cell',
            responsivePriority: 3,
            render: function(data, type, row) {
                let html = '<div>';
                // Row 1: Total Services with badge and tooltip
                const totalServices = row.total_services || 0;
                
                // Create tooltip content with service details
                let tooltipContent = '';
                if (totalServices > 0) {
                    tooltipContent = '<div class="service-tooltip-content">';
                    tooltipContent += '<div class="tooltip-header">Service History</div>';
                    
                    // Add service summary
                    tooltipContent += '<div class="service-summary">';
                    tooltipContent += '<div class="summary-item">';
                    tooltipContent += '<i class="ri-tools-line"></i> Total Services: <strong>' + totalServices + '</strong>';
                    tooltipContent += '</div>';
                    
                    if (row.first_service_date) {
                        const firstDate = new Date(row.first_service_date);
                        tooltipContent += '<div class="summary-item">';
                        tooltipContent += '<i class="ri-calendar-check-line"></i> First Service: ' + 
                                        firstDate.toLocaleDateString('en-US', {
                                            month: 'short', day: 'numeric', year: 'numeric'
                                        });
                        tooltipContent += '</div>';
                    }
                    
                    if (row.last_service_date) {
                        const lastDate = new Date(row.last_service_date);
                        tooltipContent += '<div class="summary-item">';
                        tooltipContent += '<i class="ri-calendar-event-line"></i> Last Service: ' + 
                                        lastDate.toLocaleDateString('en-US', {
                                            month: 'short', day: 'numeric', year: 'numeric'
                                        });
                        tooltipContent += '</div>';
                    }
                    
                    if (row.last_order_number) {
                        tooltipContent += '<div class="summary-item">';
                        tooltipContent += '<i class="ri-file-list-3-line"></i> Last Order: ' + row.last_order_number;
                        tooltipContent += '</div>';
                    }
                    
                    tooltipContent += '</div>';
                    tooltipContent += '<div class="tooltip-footer">Click row to view details</div>';
                    tooltipContent += '</div>';
                } else {
                    tooltipContent = '<div class="service-tooltip-content">';
                    tooltipContent += '<div class="tooltip-header">No Service History</div>';
                    tooltipContent += '<div class="no-services">This vehicle has no recorded services yet.</div>';
                    tooltipContent += '</div>';
                }
                
                html += '<div class="total-services">';
                html += '<span class="badge bg-primary service-badge-tooltip" ' +
                       'data-bs-toggle="tooltip" ' +
                       'data-bs-html="true" ' +
                       'data-bs-placement="right" ' +
                       'data-bs-custom-class="velzon-service-tooltip" ' +
                       'title="' + tooltipContent.replace(/"/g, '&quot;') + '">' + 
                       totalServices + ' Services</span>';
                html += '</div>';
                
                // Row 2: First Service
                if (row.first_service_date && row.first_service_date !== '') {
                    const firstDate = new Date(row.first_service_date);
                    html += '<div class="first-service">';
                    html += '<i class="ri-play-circle-line me-1"></i>';
                    html += '<span>First: ' + firstDate.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    }) + '</span>';
                    html += '</div>';
                }
                // Row 3: Last Service
                if (row.last_service_date && row.last_service_date !== '') {
                    const lastDate = new Date(row.last_service_date);
                    html += '<div class="last-service">';
                    html += '<i class="ri-time-line me-1"></i>';
                    html += '<span>Last: ' + lastDate.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    }) + '</span>';
                    html += '</div>';
                }
                html += '</div>';
                return html;
            }
        };
    },

    // Generate Status column
    generateStatusColumn: function() {
        return {
            data: null,
            className: 'status-cell',
            responsivePriority: 4,
            render: function(data, type, row) {
                // Determine status based on service activity
                const totalServices = parseInt(row.total_services) || 0;
                const lastServiceDate = row.last_service_date;
                
                let statusClass = 'bg-secondary';
                let statusText = 'Unknown';
                
                if (totalServices === 0) {
                    statusClass = 'bg-warning';
                    statusText = 'No Services';
                } else if (lastServiceDate) {
                    const lastDate = new Date(lastServiceDate);
                    const daysDiff = Math.floor((new Date() - lastDate) / (1000 * 60 * 60 * 24));
                    
                    if (daysDiff <= 7) {
                        statusClass = 'bg-success';
                        statusText = 'Recent';
                    } else if (daysDiff <= 30) {
                        statusClass = 'bg-info';
                        statusText = 'Active';
                    } else if (daysDiff <= 90) {
                        statusClass = 'bg-primary';
                        statusText = 'Regular';
                    } else {
                        statusClass = 'bg-warning';
                        statusText = 'Inactive';
                    }
                } else {
                    statusClass = 'bg-secondary';
                    statusText = 'No Records';
                }
                
                return '<span class="badge ' + statusClass + ' status-badge">' + statusText + '</span>';
            }
        };
    },



    // Generate standard columns array for vehicles
    generateStandardColumns: function(baseUrl = '') {
        return [
            this.generateVehicleInfoColumn(),
            this.generateClientColumn(),
            this.generateServiceHistoryColumn(),
            this.generateStatusColumn()
        ];
    },

    // Standard draw callback
    standardDrawCallback: function() {
        if (typeof feather !== 'undefined') feather.replace();
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Add click functionality to table rows
        $('.service-orders-table tbody tr').off('click').on('click', function(e) {
            // Don't trigger if clicking on buttons, links, dropdowns, or service badges with tooltips
            if ($(e.target).closest('a, button, .dropdown').length > 0) {
                return;
            }
            
            // Allow clicking through service badge tooltips but prevent navigation
            if ($(e.target).closest('.service-badge-tooltip').length > 0) {
                return;
            }
            
            const table = $(this).closest('table').DataTable();
            const rowData = table.row(this).data();
            
            if (rowData && rowData.vin_number) {
                // Use last 6 characters of VIN for URL (maintained for routing compatibility)
                const vinLast6 = rowData.vin_last6 || rowData.vin_number.slice(-6);
                if (vinLast6) {
                    window.location.href = `<?= base_url('vehicles/') ?>${vinLast6}`;
                }
            }
        });
        
        // Add hover effect to clickable rows
        $('.service-orders-table tbody tr').css('cursor', 'pointer');
    }
};

// Helper functions
function exportVehicles() {
    window.open('<?= base_url('vehicles/export-all') ?>', '_blank');
}

// Toast notification function using Toastify (consistent with Service Orders module)
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
        // Fallback to alert if Toastify is not available
        alert(message);
    }
}
</script>
<?= $this->endSection() ?> 