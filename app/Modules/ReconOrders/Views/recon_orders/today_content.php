<!-- Compact New Order Form -->
<style>
/* =================================================================
   RECON ORDERS - ESTILOS SIMILARES A SERVICE ORDERS
   Enfoque minimalista para asegurar funcionalidad responsive
   ================================================================= */

/* RESET Y BASE PARA TÍTULO RESPONSIVE */
.recon-orders-card-title {
    font-size: clamp(0.8rem, 2.5vw, 1.25rem) !important;
    font-weight: 600 !important;
    color: #1f2937 !important;
    text-align: center !important;
    margin: 0 !important;
    padding: 0.25rem 0.5rem !important;
    line-height: 1.2 !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
    hyphens: auto !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

/* CONTENEDOR DEL HEADER SIMPLIFICADO */
.card-header.d-flex {
    display: flex !important;
    flex-wrap: wrap !important;
    justify-content: space-between !important;
    align-items: center !important;
    gap: 0.5rem !important;
    padding: 1rem !important;
}

.card-header .flex-grow-1 {
    flex: 1 1 60% !important;
    min-width: 200px !important;
    text-align: center !important;
}

.card-header .flex-shrink-0 {
    flex: 0 0 auto !important;
}

/* ESTILOS ESPECÍFICOS PARA FORMULARIO RÁPIDO */
.quick-form-label {
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    color: #495057 !important;
    text-align: center !important;
    margin-bottom: 0.5rem !important;
    display: block !important;
    width: 100% !important;
    padding: 0.25rem 0 !important;
    border-bottom: 2px solid #e9ecef !important;
    background-color: #f8f9fa !important;
    border-radius: 0.375rem 0.375rem 0 0 !important;
}

.quick-form-input {
    border-radius: 0 0 0.375rem 0.375rem !important;
    border-top: none !important;
    border: 1px solid #e9ecef !important;
    padding: 0.5rem !important;
    font-size: 0.875rem !important;
    transition: all 0.2s ease !important;
}

.quick-form-input:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
}

.quick-form-select {
    border-radius: 0 0 0.375rem 0.375rem !important;
    border-top: none !important;
    border: 1px solid #e9ecef !important;
    padding: 0.5rem !important;
    font-size: 0.875rem !important;
    transition: all 0.2s ease !important;
}

.quick-form-select:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
}

.quick-form-container {
    background-color: #fff !important;
    border: 1px solid #e9ecef !important;
    border-radius: 0.5rem !important;
    padding: 1rem !important;
    margin-bottom: 1rem !important;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

/* RESPONSIVE BREAKPOINTS SIMPLIFICADOS */
@media (max-width: 768px) {
    .card-header.d-flex {
        flex-direction: column !important;
        text-align: center !important;
        padding: 0.75rem !important;
        gap: 1rem !important;
    }
    
    .card-header .flex-grow-1,
    .card-header .flex-shrink-0 {
        flex: none !important;
        width: 100% !important;
        text-align: center !important;
    }
    
    .recon-orders-card-title {
        font-size: clamp(0.9rem, 4vw, 1.1rem) !important;
        padding: 0.5rem !important;
    }
    
    .quick-form-label {
        font-size: 0.8rem !important;
        padding: 0.2rem 0 !important;
    }
    
    .quick-form-input,
    .quick-form-select {
        font-size: 0.8rem !important;
        padding: 0.4rem !important;
    }
}

@media (max-width: 480px) {
    .recon-orders-card-title {
        font-size: clamp(0.8rem, 5vw, 1rem) !important;
        padding: 0.25rem !important;
    }
    
    .card-header.d-flex {
        padding: 0.5rem !important;
    }
    
    .quick-form-container {
        padding: 0.75rem !important;
    }
}

/* Form refresh button styling */
#refreshFormBtn {
    transition: all 0.3s ease;
}

#refreshFormBtn:hover {
    transform: rotate(180deg);
}

#refreshFormBtn.refreshing {
    opacity: 0.6;
    pointer-events: none;
}

#refreshFormBtn.refreshing i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Quick Form VIN Validation Styles */
.quick-vin-status {
    display: block;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    min-height: 1rem;
    transition: all 0.2s ease;
}

.quick-vin-status-loading {
    color: #6c757d;
}

.quick-vin-status-success {
    color: #198754;
}

.quick-vin-status-error {
    color: #dc3545;
}

.quick-vin-status-warning {
    color: #fd7e14;
}

.quick-vin-status-info {
    color: #0dcaf0;
}

.quick-vin-input.vin-success {
    border-color: #198754 !important;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important;
}

.quick-vin-input.vin-error {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.quick-vin-input.vin-warning {
    border-color: #fd7e14 !important;
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25) !important;
}

/* Vehicle field decoded styling */
.quick-form-input.vin-decoded {
    transition: all 0.3s ease !important;
}

.quick-form-input.vin-decoded:focus {
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important;
}

/* Loading animation for VIN status */
.quick-vin-status-loading::after {
    content: '';
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-left: 5px;
    border: 2px solid #6c757d;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s ease-in-out infinite;
}
</style>

<!-- Quick Order Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-none">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-add-line me-2"></i><?= lang('App.quick_order_form') ?>
                </h5>
            </div>
            <div class="card-body">
                <form id="quickOrderForm" class="needs-validation" novalidate>
                    <!-- First row: Form fields -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="quick-form-label"><?= lang('App.client') ?></label>
                                <select class="form-select quick-form-select" id="quick_client_id" name="client_id" required>
                                    <option value=""><?= lang('App.select_client_placeholder') ?></option>
                                    <?php if (isset($clients) && !empty($clients)): ?>
                                        <?php foreach ($clients as $client): ?>
                                            <option value="<?= $client['id'] ?>"><?= esc($client['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?= lang('App.client_required') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="quick-form-label"><?= lang('App.stock') ?></label>
                                <input type="text" class="form-control quick-form-input" id="quick_stock" name="stock" placeholder="<?= lang('App.enter_stock_placeholder') ?>" required>
                                <div class="invalid-feedback">
                                    <?= lang('App.stock_required') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="quick-form-label"><?= lang('App.vin') ?></label>
                                <input type="text" class="form-control quick-form-input quick-vin-input" id="quick_vin" name="vin_number" placeholder="<?= lang('App.enter_vin_placeholder') ?>" maxlength="17" required>
                                <div class="invalid-feedback">
                                    <?= lang('App.vin_required') ?>
                                </div>
                                <small id="quick_vin_status" class="quick-vin-status"></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="quick-form-label"><?= lang('App.vehicle') ?></label>
                                <input type="text" class="form-control quick-form-input" id="quick_vehicle" name="vehicle" placeholder="<?= lang('App.enter_vehicle_placeholder') ?>" required>
                                <div class="invalid-feedback">
                                    <?= lang('App.vehicle_required') ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="quick-form-label"><?= lang('App.service') ?></label>
                                <select class="form-select quick-form-select" id="quick_service_id" name="service_id" required>
                                    <option value=""><?= lang('App.select_service_placeholder') ?></option>
                                    <?php if (isset($services) && !empty($services)): ?>
                                        <?php foreach ($services as $service): ?>
                                            <option value="<?= $service['id'] ?>"><?= esc($service['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?= lang('App.service_required') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Second row: Action buttons -->
                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-outline-secondary me-2" id="quickClearBtn">
                                <i class="ri-close-line me-1"></i><?= lang('App.clear') ?>
                            </button>
                            <button type="submit" class="btn btn-primary" id="quickSubmitBtn">
                                <i class="ri-add-line me-1"></i><?= lang('App.add_order') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Today's Orders Content -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-none">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center"><?= lang('App.today_orders') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshTodayTable" class="btn btn-secondary btn-sm">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <!-- Individual Table Filters (Users) -->
                <?php 
                $isAdmin = false;
                if (auth()->loggedIn()) {
                    $user = auth()->user();
                    $userGroups = $user->getGroups();
                    $isAdmin = !empty($userGroups) && (in_array('admin', $userGroups) || in_array('superadmin', $userGroups));
                }
                ?>
                <?php if (!$isAdmin): ?>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="bg-light rounded p-3">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label text-muted small mb-1">
                                        <i data-feather="activity" class="icon-xs me-1"></i>
                                        Status
                                    </label>
                                    <select id="todayStatusFilter" class="form-select form-select-sm">
                                        <option value="">All Status</option>
                                        <option value="pending">⏳ Pending</option>
                                        <option value="in_progress">🔄 In Progress</option>
                                        <option value="completed">✅ Completed</option>
                                        <option value="cancelled">❌ Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small mb-1">
                                        <i data-feather="search" class="icon-xs me-1"></i>
                                        Search Stock/Vehicle
                                    </label>
                                    <input type="text" id="todaySearchFilter" class="form-control form-control-sm" placeholder="Search...">
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-primary btn-sm" onclick="applyTodayFilters()">
                                            <i data-feather="filter" class="icon-xs me-1"></i>
                                            Filter
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="clearTodayFilters()">
                                            <i data-feather="x" class="icon-xs me-1"></i>
                                            Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="table-container overflow-hidden">
                    <table id="today-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><?= lang('App.order_id') ?></th>
                                <th scope="col">Order Date</th>
                                <th scope="col"><?= lang('App.stock') ?></th>
                                <th scope="col"><?= lang('App.vehicle') ?></th>
                                <th scope="col"><?= lang('App.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los datos se cargarán vía AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* SalesOrders Table Styles */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem !important;
    margin-left: 2px !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 4px !important;
    color: #6c757d !important;
    background-color: #fff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #fff !important;
    background-color: #405189 !important;
    border-color: #405189 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    border-color: #405189 !important;
    background-color: #405189 !important;
    color: #fff !important;
}

/* Quick Form Validation Styles */
.quick-form-input, .quick-form-select {
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.invalid-feedback {
    transition: opacity 0.15s ease-in-out;
}

.quick-form-input:focus, .quick-form-select:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.quick-form-input.is-invalid:focus, .quick-form-select.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.quick-form-input.is-valid:focus, .quick-form-select.is-valid:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

/* Action Links Styles */
.link-primary {
    color: #405189 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-primary:hover {
    color: #2c3e50 !important;
}

.link-success {
    color: #0ab39c !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-success:hover {
    color: #087f69 !important;
}

.link-danger {
    color: #f06548 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-danger:hover {
    color: #d63384 !important;
}

.fs-15 {
    font-size: 15px !important;
}

/* Center table headers */
#today-table thead th {
    text-align: center !important;
}

/* Stock Number Badge Styling */
.stock-number-badge .badge {
    border-radius: 12px !important;
    text-transform: uppercase !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    transition: all 0.2s ease !important;
    border: 1px solid rgba(var(--bs-primary-rgb), 0.2) !important;
}

.stock-number-badge .badge:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
}

/* VIN Number Styling */
.vin-number {
    background: rgba(var(--bs-secondary-rgb), 0.05);
    border-radius: 8px;
    padding: 4px 8px;
    margin: 2px 0;
    border: 1px solid rgba(var(--bs-secondary-rgb), 0.1);
}

.vin-number small {
    text-transform: uppercase;
    font-weight: 500;
}

/* Action buttons styling */
.action-buttons {
    position: relative;
    z-index: 10;
}

.action-buttons a {
    position: relative;
    z-index: 11;
}

/* Clickable row styling */
#today-table tbody tr {
    cursor: pointer !important;
    transition: background-color 0.15s ease !important;
}

#today-table tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.05) !important;
}

#today-table tbody tr:hover td {
    background-color: transparent !important;
}

/* Tooltip styling */
.tooltip-inner {
    max-width: 350px !important;
    text-align: left !important;
}
</style>

<script>
window.initializeTodayTable = function() {
    try {
        // console.log('Initializing Today Table...');
        
        if (typeof $ === 'undefined') {
            console.error('jQuery is not loaded for Today Table');
            return;
        }
        
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTables is not loaded');
            return;
        }

        window.todayTable = $('#today-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            scrollX: false,
            autoWidth: false,
            ajax: {
                url: '<?= base_url('recon_orders/today_content') ?>',
                type: 'POST',
                data: function(d) {
                    d.ajax = true;
                    
                    // Add global filters (Admin only)
                    if (typeof window.globalFilters !== 'undefined' && <?= $isAdmin ? 'true' : 'false' ?>) {
                        d.client_filter = window.globalFilters.client;
                        d.status_filter = window.globalFilters.status;
                        d.date_from_filter = window.globalFilters.dateFrom;
                        d.date_to_filter = window.globalFilters.dateTo;
                    }
                    
                    // Add individual table filters (Users)
                    if (<?= !$isAdmin ? 'true' : 'false' ?>) {
                        d.status_filter = document.getElementById('todayStatusFilter')?.value || '';
                        d.search_filter = document.getElementById('todaySearchFilter')?.value || '';
                    }
                },
                error: function(xhr, error, thrown) {
                    console.error('Today AJAX Error:', error);
                }
            },
            columnDefs: [
                { width: "22.5%", targets: 0, className: "text-center" }, // Order ID / Client
                { width: "22.5%", targets: 1, className: "text-center" }, // Order Date / Status
                { width: "22.5%", targets: 2, className: "text-center" }, // Stock
                { width: "22.5%", targets: 3, className: "text-center" }, // Vehicle
                { width: "10%", targets: 4, orderable: false, searchable: false, className: "text-center" } // Actions
            ],
            columns: [
                {
                    data: 'order_number',
                    render: function(data, type, row) {
                        let html = `<div><span class="fw-medium text-primary">${data || 'N/A'}</span>`;
                        
                        // Add client name below order ID with business icon
                        if (row.client_name && row.client_name !== 'N/A') {
                            html += `<div class="text-muted small mt-1">
                                <i class="ri-building-line me-1"></i>${row.client_name}
                            </div>`;
                        }
                        
                        html += `</div>`;
                        return html;
                    }
                },
                {
                    data: 'service_date',
                    render: function(data, type, row) {
                        let html = `<div>`;
                        
                        // The date is already formatted by the controller
                        if (data && data !== 'N/A' && data !== 'No Date' && data.trim() !== '') {
                            html += `<span class="fw-medium">${data}</span>`;
                        } else {
                            html += `<span class="fw-medium text-muted">No Date</span>`;
                        }
                        
                        // Add status below date
                        if (row.status && row.status !== 'N/A') {
                            let statusClass = 'text-muted';
                            let statusIcon = 'ri-time-line';
                            
                            switch(row.status.toLowerCase()) {
                                case 'completed':
                                    statusClass = 'text-success';
                                    statusIcon = 'ri-check-line';
                                    break;
                                case 'in_progress':
                                    statusClass = 'text-warning';
                                    statusIcon = 'ri-play-line';
                                    break;
                                case 'cancelled':
                                    statusClass = 'text-danger';
                                    statusIcon = 'ri-close-line';
                                    break;
                                case 'pending':
                                    statusClass = 'text-info';
                                    statusIcon = 'ri-time-line';
                                    break;
                            }
                            
                            html += `<div class="mt-1">
                                <span class="badge ${statusClass.replace('text-', 'bg-').replace('text-', '')}-subtle ${statusClass} fw-semibold px-2 py-1" style="font-size: 0.8rem;">
                                    <i class="${statusIcon} me-1"></i>${row.status.charAt(0).toUpperCase() + row.status.slice(1).replace('_', ' ')}
                                </span>
                            </div>`;
                        }
                        
                        html += `</div>`;
                        return html;
                    }
                },
                {
                    data: 'stock',
                    render: function(data, type, row) {
                        let html = `<div class="text-center">`;
                        
                        // Stock number - más pequeño
                        if (data && data !== 'N/A') {
                            html += `<div class="stock-number-badge mb-1">
                                <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1" style="font-size: 0.75rem; letter-spacing: 0.3px;">
                                    ${data}
                                </span>
                            </div>`;
                        } else {
                            html += `<div class="stock-number-badge mb-1">
                                <span class="badge bg-secondary-subtle text-secondary fw-bold px-2 py-1" style="font-size: 0.75rem;">
                                    N/A
                                </span>
                            </div>`;
                        }
                        
                        // Service information instead of VIN
                        if (row.service_name && row.service_name !== 'N/A') {
                            const serviceColor = row.service_color || '#007bff';
                            html += `<div class="service-info mt-1">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="service-color-dot me-1" style="width: 8px; height: 8px; border-radius: 50%; background-color: ${serviceColor};"></div>
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        ${row.service_name}
                                    </small>
                                </div>
                            </div>`;
                        } else {
                            html += `<div class="service-info mt-1">
                                <small class="text-muted" style="font-size: 0.65rem; opacity: 0.6;">
                                    No Service
                                </small>
                            </div>`;
                        }
                        
                        html += `</div>`;
                        return html;
                    }
                },
                {
                    data: 'vehicle',
                    render: function(data, type, row) {
                        let html = `<div><span class="fw-medium">${data || 'N/A'}</span>`;
                        
                        // VIN number - moved from stock column
                        let vinNumber = row.vin || row.vin_number || row.vehicle_vin || row.VIN || '';
                        
                        if (vinNumber && vinNumber !== 'N/A' && vinNumber.toString().trim() !== '') {
                            html += `<div class="vin-number mt-1">
                                <small class="text-muted d-block" style="font-size: 0.7rem; font-family: monospace; letter-spacing: 0.2px; line-height: 1.2;">
                                    <i class="ri-barcode-line me-1" style="font-size: 0.8rem;"></i>${vinNumber}
                                </small>
                            </div>`;
                        }
                        
                        html += `</div>`;
                        return html;
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return '<div class="d-flex justify-content-center gap-2 action-buttons">' +
                               '<a href="<?= base_url('recon_orders/view/') ?>' + data + '" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang("App.view") ?>">' +
                               '<i class="ri-eye-fill"></i>' +
                               '</a>' +
                               '<a href="#" class="link-success fs-15 edit-order-btn" data-id="' + data + '" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang("App.edit") ?>">' +
                               '<i class="ri-edit-fill"></i>' +
                               '</a>' +
                               '<a href="#" class="link-danger fs-15 delete-order-btn" data-id="' + data + '" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang("App.delete") ?>">' +
                               '<i class="ri-delete-bin-line"></i>' +
                               '</a>' +
                               '</div>';
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                processing: '<?= lang('App.processing') ?>',
                search: '<?= lang('App.search') ?>',
                lengthMenu: '<?= lang('App.show') ?> _MENU_ <?= lang('App.entries') ?>',
                info: '<?= lang('App.showing') ?> _START_ <?= lang('App.to') ?> _END_ <?= lang('App.of') ?> _TOTAL_ <?= lang('App.entries') ?>',
                infoEmpty: '<?= lang('App.showing') ?> 0 <?= lang('App.to') ?> 0 <?= lang('App.of') ?> 0 <?= lang('App.entries') ?>',
                infoFiltered: '(<?= lang('App.filtered') ?> <?= lang('App.from') ?> _MAX_ <?= lang('App.total') ?> <?= lang('App.entries') ?>)',
                infoPostFix: '',
                thousands: ',',
                loadingRecords: '<?= lang('App.loading') ?>',
                zeroRecords: '<?= lang('App.no_matching_records') ?>',
                emptyTable: '<?= lang('App.no_data') ?>',
                paginate: {
                    first: '<?= lang('App.first') ?>',
                    previous: '<?= lang('App.previous') ?>',
                    next: '<?= lang('App.next') ?>',
                    last: '<?= lang('App.last') ?>'
                }
            },
            drawCallback: function(settings) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                
                // Apply status color to rows
                $('#today-table tbody tr').each(function() {
                    var $row = $(this);
                    var rowData = window.todayTable.row($row).data();
                    if (rowData && rowData.status) {
                        const statusColors = {
                            'pending': '#ffc107',
                            'in_progress': '#17a2b8',
                            'completed': '#28a745',
                            'cancelled': '#dc3545'
                        };
                        var color = statusColors[rowData.status] || '#6c757d';
                        var rgba = hexToRgba(color, 0.15);
                        $row.css({
                            'background-color': rgba,
                            'border-left': '4px solid ' + color,
                            'transition': 'all 0.3s ease'
                        });
                        
                        // Enhanced hover effect
                        $row.hover(
                            function() { 
                                $(this).css('background-color', hexToRgba(color, 0.2)); 
                            },
                            function() { 
                                $(this).css('background-color', rgba); 
                            }
                        );
                    }
                });
            }
        });

        // Refresh button
        $('#refreshTodayTable').on('click', function() {
            window.todayTable.ajax.reload();
        });



        // Edit button handler
        $('#today-table').on('click', '.edit-order-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var orderId = $(this).data('id');
            editReconOrder(orderId);
        });

        // Delete button handler
        $('#today-table').on('click', '.delete-order-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var orderId = $(this).data('id');
            deleteReconOrder(orderId);
        });

        // View button handler - prevent row click
        $('#today-table').on('click', 'a[href*="recon_orders/view/"]', function(e) {
            e.stopPropagation();
            // Let the default href behavior happen
        });

        // Make table rows clickable to view order
        $('#today-table tbody').on('click', 'tr', function(e) {
            // Don't trigger if clicking on action buttons
            if ($(e.target).closest('.action-buttons').length > 0) {
                return;
            }
            
            var data = window.todayTable.row(this).data();
            if (data && data.id) {
                window.location.href = '<?= base_url('recon_orders/view/') ?>' + data.id;
            }
        });

        // Add pointer cursor to clickable rows
        $('#today-table tbody').on('mouseenter', 'tr', function() {
            $(this).css('cursor', 'pointer');
        });

        // console.log('Today Table initialized successfully');
        
        // Also initialize quick form if it hasn't been initialized yet
        if (typeof initializeQuickForm === 'function') {
            try {
                initializeQuickForm();
            } catch (e) {
                // console.log('Quick form already initialized or error:', e);
            }
        }
    } catch (error) {
        console.error('Error initializing Today Table:', error);
    }
};

// Helper function to convert hex color to rgba
function hexToRgba(hex, alpha) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? 
        'rgba(' + parseInt(result[1], 16) + ', ' + 
                  parseInt(result[2], 16) + ', ' + 
                  parseInt(result[3], 16) + ', ' + alpha + ')' 
        : 'rgba(0, 123, 255, ' + alpha + ')'; // fallback to bootstrap primary
}

// Quick Order Form functionality
function initializeQuickForm() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not available for quick form');
        return;
    }
    
    // Check if form is already initialized
    if ($('#quickOrderForm').data('initialized')) {
        // console.log('Quick form already initialized, skipping...');
        return;
    }
    
    // console.log('Initializing quick form...');
    
    // Mark as initialized
    $('#quickOrderForm').data('initialized', true);
    
    // Load clients and services for quick form
    loadQuickFormData();
    
    // Quick form submission - using one() to prevent multiple event handlers
    $('#quickOrderForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        // Check if form is already being submitted
        if ($(this).data('submitting')) {
            // console.log('Form already being submitted, preventing duplicate');
            return false;
        }
        
        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return false;
        }
        
        const formData = {
            client_id: $('#quick_client_id').val(),
            stock: $('#quick_stock').val(),
            vin_number: $('#quick_vin').val(),
            vehicle: $('#quick_vehicle').val(),
            service_id: $('#quick_service_id').val(),
            status: 'pending'
        };
        
        // Validate required fields
        if (!formData.client_id || !formData.stock || !formData.vin_number || !formData.vehicle || !formData.service_id) {
            showToast('<?= lang("App.complete_all_fields") ?>', 'error');
            return false;
        }
        
        // Mark form as being submitted
        $(this).data('submitting', true);
        
        // Show loading state
        const submitBtn = $('#quickSubmitBtn');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="ri-loader-2-line me-1"></i><?= lang('App.saving') ?>').prop('disabled', true);
        
        // Submit the form
        $.ajax({
            url: '<?= base_url('recon_orders/store') ?>',
            type: 'POST',
            data: formData,
            xhrFields: {
                withCredentials: true
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message || '<?= lang("App.order_saved_successfully") ?>', 'success');
                    
                    // Reset validation messages and classes
                    $('#quickOrderForm').removeClass('was-validated');
                    $('#quickOrderForm .is-invalid').removeClass('is-invalid');
                    $('#quickOrderForm .invalid-feedback').hide();
                    
                    clearQuickForm();
                    
                    // Refresh all tables using the comprehensive refresh function
                    if (typeof refreshAllReconOrdersData === 'function') {
                        try {
                            // console.log('🔄 Refreshing all tables after quick form submission');
                            refreshAllReconOrdersData({ 
                                showToast: false, // Don't show refresh toast since we already showed success
                                showProgress: true // Show visual progress indicator
                            });
                        } catch (e) {
                            console.error('Error refreshing all recon orders data:', e);
                            // Fallback: refresh only today's table
                            if (typeof $ !== 'undefined' && $.fn.DataTable && $('#today-table').length) {
                                var todayTable = $('#today-table').DataTable();
                                if (todayTable) {
                                    todayTable.ajax.reload();
                                }
                            }
                        }
                    } else {
                        // Fallback: refresh only today's table
                        console.warn('⚠️ Global refresh function not available, refreshing today table only');
                        if (typeof $ !== 'undefined' && $.fn.DataTable && $('#today-table').length) {
                            var todayTable = $('#today-table').DataTable();
                            if (todayTable) {
                                todayTable.ajax.reload();
                            }
                        }
                    }
                } else {
                    showToast(response.message || '<?= lang("App.error_saving_order") ?>', 'error');
                }
            },
            error: function(xhr) {
                let errorMessage = '<?= lang("App.error_saving_order") ?>';
                
                // console.log('AJAX Error:', xhr);
                // console.log('Status:', xhr.status);
                // console.log('Response:', xhr.responseText);
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 401) {
                    errorMessage = 'Session expired. Please refresh the page and try again.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                }
                
                // Keep validation messages visible for user correction
                // Don't clear form on error, just reset submission state
                $('#quickOrderForm').data('submitting', false);
                
                showToast(errorMessage, 'error');
            },
            complete: function() {
                // Reset button state
                submitBtn.html(originalText).prop('disabled', false);
                // Reset form submission state
                $('#quickOrderForm').data('submitting', false);
            }
        });
    });
    
    // Clear form button
    $('#quickClearBtn').on('click', function() {
        // Reset validation state before clearing
        $('#quickOrderForm').removeClass('was-validated');
        $('#quickOrderForm .is-invalid').removeClass('is-invalid');
        $('#quickOrderForm .is-valid').removeClass('is-valid');
        $('#quickOrderForm .invalid-feedback').hide();
        $('#quickOrderForm .valid-feedback').hide();
        
        clearQuickForm();
    });
    
    // Prevent double click on submit button
    $('#quickSubmitBtn').on('click', function(e) {
        if ($(this).prop('disabled') || $('#quickOrderForm').data('submitting')) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
    });
    
    // Load services when client changes
    $('#quick_client_id').on('change', function() {
        const clientId = $(this).val();
        loadServicesForClient(clientId);
    });
    
    // VIN validation with comprehensive checks
    $('#quick_vin').on('input', function() {
        const vin = $(this).val().toUpperCase().trim();
        $(this).val(vin);
        
        clearQuickVINStatus();
        
        // Remove non-alphanumeric characters
        const validVin = vin.replace(/[^A-Z0-9]/g, '');
        if (validVin !== vin) {
            $(this).val(validVin);
            showQuickVINStatus('warning', 'VIN can only contain letters and numbers');
            return;
        }
        
        if (vin.length === 17) {
            // Full VIN validation and decoding
            const validationResult = isValidQuickVIN(vin);
            if (validationResult.isValid) {
                showQuickVINStatus('loading', 'Decoding VIN...');
                $(this).removeClass('is-invalid').addClass('is-valid');
                decodeQuickVIN(vin);
            } else {
                showQuickVINStatus('error', validationResult.message);
                $(this).removeClass('is-valid').addClass('is-invalid');
                clearQuickVehicleField();
            }
        } else if (vin.length >= 10 && vin.length < 17) {
            showQuickVINStatus('loading', 'Decoding partial VIN...');
            $(this).removeClass('is-valid is-invalid');
            decodeQuickPartialVIN(vin);
        } else if (vin.length > 0 && vin.length < 10) {
            showQuickVINStatus('info', `${vin.length}/17 characters`);
            $(this).removeClass('is-valid is-invalid');
            clearQuickVehicleField();
        } else if (vin.length > 17) {
            $(this).val(vin.substring(0, 17));
            showQuickVINStatus('error', 'VIN cannot exceed 17 characters');
        } else {
            $(this).removeClass('is-valid is-invalid');
            clearQuickVehicleField();
        }
    });
     
     // Reset validation messages when user starts typing in any field
     $('#quickOrderForm input, #quickOrderForm select').on('input change', function() {
         // Remove validation classes only from the field being edited
         $(this).removeClass('is-invalid is-valid');
         
         // If user is correcting errors, remove the form's was-validated class
         if ($('#quickOrderForm').hasClass('was-validated')) {
             // Check if all required fields now have values
             let allValid = true;
             $('#quickOrderForm [required]').each(function() {
                 if (!$(this).val()) {
                     allValid = false;
                     return false;
                 }
             });
             
             // If all fields are filled, remove the was-validated class to clear error styling
             if (allValid) {
                 $('#quickOrderForm').removeClass('was-validated');
             }
         }
     });
}

// Initialize the quick form when jQuery is available
function waitForJQueryQuickForm() {
    if (typeof $ !== 'undefined') {
        try {
            $(document).ready(function() {
                initializeQuickForm();
            });
        } catch (e) {
            console.error('Error initializing quick form with jQuery:', e);
        }
    } else {
        setTimeout(waitForJQueryQuickForm, 100);
    }
}

// Start waiting for jQuery
try {
    waitForJQueryQuickForm();
} catch (e) {
    console.error('Error initializing quick form:', e);
}

function loadQuickFormData() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery not available for loadQuickFormData');
        return;
    }
    
    // Load clients
    $.ajax({
        url: '<?= base_url('recon_orders/getClients') ?>',
        type: 'GET',
        success: function(response) {
            if (response.success && response.clients) {
                const clientSelect = $('#quick_client_id');
                clientSelect.find('option:not(:first)').remove();
                
                response.clients.forEach(function(client) {
                    // Only add active clients
                    if (client.status === 'active' || !client.status) {
                        var option = document.createElement('option');
                        option.value = client.id;
                        option.text = client.name;
                        clientSelect[0].appendChild(option);
                    }
                });
            }
        },
        error: function() {
            console.error('Error loading clients for quick form');
        }
    });
    
    // Load global services
    loadServicesForClient('');
}

function loadServicesForClient(clientId) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery not available for loadServicesForClient');
        return;
    }
    
    const serviceSelect = $('#quick_service_id');
    serviceSelect.html('<option value=""><?= lang('App.select_service_placeholder') ?></option>');
    
    let url = '<?= base_url('recon_orders/getServices') ?>';
    if (clientId) {
        url = '<?= base_url('recon_orders/getServicesForClient/') ?>' + clientId;
    }
    
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
                    if (response.success && response.data) {
            response.data.forEach(function(service) {
                // Only add services that are active and should show in form
                if (service.is_active && service.show_in_form) {
                    var option = document.createElement('option');
                    option.value = service.id;
                    option.text = service.name + (service.price ? ' - $' + parseFloat(service.price).toFixed(2) : '');
                    serviceSelect[0].appendChild(option);
                }
            });
            }
        },
        error: function() {
            console.error('Error loading services for quick form');
        }
    });
}

// Quick Form VIN Decoding Functions
function decodeQuickVIN(vin) {
    const validationResult = isValidQuickVIN(vin);
    if (!validationResult.isValid) {
        showQuickVINStatus('error', validationResult.message);
        return;
    }

    showQuickVINStatus('loading', 'Decoding VIN...');

    const nhtsa_url = `https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/${vin}?format=json`;

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
        if (data && data.Results && data.Results.length > 0) {
            const vehicleData = data.Results[0];
            const vehicleString = buildQuickVehicleString(vehicleData);

            if (vehicleString && vehicleString.trim() !== '') {
                const vehicleInput = $('#quick_vehicle');
                if (vehicleInput.length) {
                    vehicleInput.val(vehicleString);
                    vehicleInput.addClass('vin-decoded');
                    vehicleInput.css({
                        'background-color': '#d1e7dd',
                        'border-color': '#198754'
                    });

                    showQuickVINStatus('success', 'VIN decoded successfully');

                    setTimeout(() => {
                        clearQuickVINStatus();
                        vehicleInput.css({
                            'background-color': '',
                            'border-color': ''
                        });
                    }, 2000);
                }
            } else {
                showQuickVINStatus('warning', 'Valid VIN but no vehicle information available');
            }
        } else {
            showQuickVINStatus('warning', 'VIN decoded but no data found');
        }
    })
    .catch(error => {
        console.error('NHTSA API error:', error);

        try {
            const basicInfo = decodeQuickVINBasic(vin);

            if (basicInfo.year || basicInfo.make) {
                const vehicleParts = [];
                if (basicInfo.year) vehicleParts.push(basicInfo.year);
                if (basicInfo.make) vehicleParts.push(basicInfo.make);

                const vehicleString = vehicleParts.join(' ');
                const vehicleInput = $('#quick_vehicle');
                
                if (vehicleInput.length) {
                    vehicleInput.val(vehicleString);
                    vehicleInput.addClass('vin-decoded');
                    vehicleInput.css({
                        'background-color': '#fff3cd',
                        'border-color': '#fd7e14'
                    });

                    showQuickVINStatus('warning', 'Basic VIN decoding (offline mode)');

                    setTimeout(() => {
                        clearQuickVINStatus();
                        vehicleInput.css({
                            'background-color': '',
                            'border-color': ''
                        });
                    }, 2000);
                }
            } else {
                showQuickVINStatus('error', 'Unable to decode VIN');
            }
        } catch (fallbackError) {
            showQuickVINStatus('error', 'VIN decoding failed');
        }
    });
}

function decodeQuickPartialVIN(vin) {
    try {
        const basicInfo = decodeQuickVINBasic(vin);
        
        if (basicInfo.year || basicInfo.make) {
            const vehicleParts = [];
            if (basicInfo.year) vehicleParts.push(basicInfo.year);
            if (basicInfo.make) vehicleParts.push(basicInfo.make);
            vehicleParts.push('(Partial)');

            const vehicleString = vehicleParts.join(' ');
            const vehicleInput = $('#quick_vehicle');
            
            if (vehicleInput.length) {
                vehicleInput.val(vehicleString);
                vehicleInput.addClass('vin-decoded');
                vehicleInput.css({
                    'background-color': '#fff3cd',
                    'border-color': '#fd7e14'
                });
                
                showQuickVINStatus('warning', `Partial decode (${vin.length}/17 characters)`);
                
                setTimeout(() => {
                    clearQuickVINStatus();
                    vehicleInput.css({
                        'background-color': '',
                        'border-color': ''
                    });
                }, 3000);
            }
        } else {
            showQuickVINStatus('info', `${vin.length}/17 characters`);
            clearQuickVehicleField();
        }
    } catch (error) {
        showQuickVINStatus('info', `${vin.length}/17 characters`);
        clearQuickVehicleField();
    }
}

function buildQuickVehicleString(nhtsa_data) {
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

function decodeQuickVINBasic(vin) {
    const vinInfo = { year: null, make: null, model: null, trim: null };

    try {
        const yearCode = vin.charAt(9);
        vinInfo.year = decodeQuickYearFromVIN(yearCode);

        const wmi = vin.substring(0, 3);
        vinInfo.make = decodeQuickMakeFromWMI(wmi);
    } catch (error) {
        console.error('Basic VIN decoding error:', error);
    }

    return vinInfo;
}

function decodeQuickYearFromVIN(yearCode) {
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

function decodeQuickMakeFromWMI(wmi) {
    const wmiMakes = {
        // North American manufacturers
        '1G1': 'CHEVROLET', '1G6': 'CADILLAC', '1GM': 'PONTIAC', '1GC': 'CHEVROLET',
        '1GT': 'GMC', '1G4': 'BUICK', '1G3': 'OLDSMOBILE', '1GK': 'GMC',
        '1FA': 'FORD', '1FB': 'FORD', '1FC': 'FORD', '1FD': 'FORD', '1FE': 'FORD',
        '1FF': 'FORD', '1FG': 'FORD', '1FH': 'FORD', '1FJ': 'FORD', '1FK': 'FORD',
        '1FL': 'FORD', '1FM': 'FORD', '1FN': 'FORD', '1FP': 'FORD', '1FR': 'FORD',
        '1FS': 'FORD', '1FT': 'FORD', '1FU': 'FORD', '1FV': 'FORD', '1FW': 'FORD',
        '1FX': 'FORD', '1FY': 'FORD', '1FZ': 'FORD',
        '1HD': 'HARLEY DAVIDSON', '1HG': 'HONDA', '1HT': 'INTERNATIONAL',
        '1J4': 'JEEP', '1J8': 'JEEP', '1JC': 'JEEP',
        '1L1': 'LINCOLN', '1LN': 'LINCOLN', '1ME': 'MERCURY', '1MH': 'MERCURY',
        '1N4': 'NISSAN', '1N6': 'NISSAN', '1NP': 'NISSAN', '1NX': 'NISSAN',
        '1P3': 'PLYMOUTH', '1P4': 'PLYMOUTH', '1P7': 'PLYMOUTH', '1P8': 'PLYMOUTH',
        '1R9': 'GEO', '1VW': 'VOLKSWAGEN', '1YV': 'MAZDA',
        '2A4': 'CHRYSLER', '2A8': 'CHRYSLER', '2B3': 'DODGE', '2B4': 'DODGE', '2B7': 'DODGE',
        '2C3': 'CHRYSLER', '2C4': 'CHRYSLER', '2C8': 'CHRYSLER',
        '2D3': 'DODGE', '2D4': 'DODGE', '2D8': 'DODGE',
        '2FA': 'FORD', '2FB': 'FORD', '2FC': 'FORD', '2FD': 'FORD', '2FE': 'FORD',
        '2G1': 'CHEVROLET', '2G2': 'PONTIAC', '2G3': 'OLDSMOBILE', '2G4': 'BUICK',
        '2HG': 'HONDA', '2HJ': 'HONDA', '2HK': 'HONDA', '2HM': 'HYUNDAI',
        '2M': 'MERCURY', '2P3': 'PLYMOUTH', '2P4': 'PLYMOUTH', '2P7': 'PLYMOUTH',
        '2T1': 'TOYOTA', '2T2': 'TOYOTA', '2T3': 'TOYOTA',
        '3C3': 'CHRYSLER', '3C4': 'CHRYSLER', '3C6': 'CHRYSLER', '3C8': 'CHRYSLER',
        '3D3': 'DODGE', '3D4': 'DODGE', '3D6': 'DODGE', '3D7': 'DODGE',
        '3FA': 'FORD', '3G': 'GENERAL MOTORS',
        '3N1': 'NISSAN', '3N6': 'NISSAN', '3N8': 'NISSAN',
        '3P3': 'PLYMOUTH', '3VW': 'VOLKSWAGEN',
        '4F2': 'MAZDA', '4F4': 'MAZDA', '4M2': 'MERCURY', '4S3': 'SUBARU', '4S4': 'SUBARU',
        '4T1': 'TOYOTA', '4T3': 'TOYOTA', '4US': 'BMW',
        '5F': 'HONDA', '5J6': 'HONDA', '5L': 'LINCOLN', '5N1': 'NISSAN', '5NP': 'HYUNDAI',
        '5S3': 'SUBARU', '5T': 'TOYOTA', '5Y2': 'HYUNDAI',
        // European manufacturers
        'WBA': 'BMW', 'WBS': 'BMW', 'WBX': 'BMW', 'WDB': 'MERCEDES-BENZ', 'WDC': 'MERCEDES-BENZ',
        'WDD': 'MERCEDES-BENZ', 'WDF': 'MERCEDES-BENZ', 'WMW': 'MINI', 'WAU': 'AUDI',
        'WVW': 'VOLKSWAGEN', 'WV1': 'VOLKSWAGEN', 'WV2': 'VOLKSWAGEN',
        'WP0': 'PORSCHE', 'WP1': 'PORSCHE', 'ZAM': 'MASERATI', 'ZAR': 'ALFA ROMEO',
        'ZFA': 'FIAT', 'ZFF': 'FERRARI', 'ZLA': 'LANCIA', 'ZHW': 'LAMBORGHINI',
        // Japanese manufacturers  
        'JA3': 'MITSUBISHI', 'JA4': 'MITSUBISHI', 'JF1': 'SUBARU', 'JF2': 'SUBARU',
        'JHG': 'HONDA', 'JH4': 'ACURA', 'JM1': 'MAZDA', 'JM3': 'MAZDA', 'JM6': 'MAZDA',
        'JMZ': 'MAZDA', 'JN1': 'NISSAN', 'JN6': 'NISSAN', 'JN8': 'NISSAN',
        'JT2': 'TOYOTA', 'JT3': 'TOYOTA', 'JT4': 'TOYOTA', 'JT6': 'TOYOTA', 'JT8': 'TOYOTA',
        'JTD': 'TOYOTA', 'JTE': 'TOYOTA', 'JTF': 'TOYOTA', 'JTG': 'TOYOTA', 'JTH': 'TOYOTA',
        'JTJ': 'TOYOTA', 'JTK': 'TOYOTA', 'JTL': 'TOYOTA', 'JTM': 'TOYOTA', 'JTN': 'TOYOTA',
        // Korean manufacturers
        'KM8': 'HYUNDAI', 'KMF': 'HYUNDAI', 'KMH': 'HYUNDAI', 'KMJ': 'HYUNDAI',
        'KNA': 'KIA', 'KNB': 'KIA', 'KNC': 'KIA', 'KND': 'KIA', 'KNE': 'KIA',
        'KNF': 'KIA', 'KNG': 'KIA', 'KNH': 'KIA', 'KNJ': 'KIA', 'KNK': 'KIA',
        'KNL': 'KIA', 'KNM': 'KIA', 'KNN': 'KIA', 'KNP': 'KIA', 'KNR': 'KIA',
        'KNS': 'KIA', 'KNT': 'KIA', 'KNU': 'KIA', 'KNV': 'KIA', 'KNW': 'KIA',
        'KNX': 'KIA', 'KNY': 'KIA', 'KNZ': 'KIA'
    };

    // Try exact match first
    if (wmiMakes[wmi]) {
        return wmiMakes[wmi];
    }

    // Try first two characters
    const wmi2 = wmi.substring(0, 2);
    for (const key in wmiMakes) {
        if (key.startsWith(wmi2)) {
            return wmiMakes[key];
        }
    }

    return null;
}

function clearQuickVehicleField() {
    const vehicleInput = $('#quick_vehicle');
    if (vehicleInput.length && vehicleInput.hasClass('vin-decoded')) {
        vehicleInput.val('');
        vehicleInput.removeClass('vin-decoded');
        vehicleInput.css({
            'background-color': '',
            'border-color': ''
        });
    }
}

// Quick Form VIN Validation Functions
function isValidQuickVIN(vin) {
    if (vin.length !== 17) {
        return { isValid: false, errorType: 'format', message: 'VIN must be exactly 17 characters' };
    }
    
    if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) {
        return { isValid: false, errorType: 'format', message: 'Invalid VIN format. VIN cannot contain I, O, or Q' };
    }

    const suspiciousResult = checkQuickSuspiciousPatterns(vin);
    if (!suspiciousResult.isValid) {
        return suspiciousResult;
    }

    const checkDigitResult = validateQuickCheckDigit(vin);
    if (!checkDigitResult.isValid) {
        return checkDigitResult;
    }

    return { isValid: true };
}

function checkQuickSuspiciousPatterns(vin) {
    // Check for 4 consecutive identical characters
    for (let i = 0; i <= vin.length - 4; i++) {
        if (vin[i] === vin[i+1] && vin[i] === vin[i+2] && vin[i] === vin[i+3]) {
            return { 
                isValid: false, 
                errorType: 'suspicious', 
                message: 'VIN contains suspicious patterns (4+ identical consecutive characters)' 
            };
        }
    }

    // Check for too many repeated characters
    const charCount = {};
    for (const char of vin) {
        charCount[char] = (charCount[char] || 0) + 1;
        if (charCount[char] > 4) {
            return { 
                isValid: false, 
                errorType: 'suspicious', 
                message: 'VIN contains too many repeated characters' 
            };
        }
    }

    return { isValid: true };
}

function validateQuickCheckDigit(vin) {
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
                message: 'Invalid character in VIN' 
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
            message: 'Invalid VIN check digit' 
        };
    }

    return { isValid: true };
}

function showQuickVINStatus(type, message) {
    const statusElement = $('#quick_vin_status');
    const vinInput = $('#quick_vin');
    
    if (statusElement.length && message) {
        statusElement.text(message)
                   .removeClass('quick-vin-status-loading quick-vin-status-success quick-vin-status-error quick-vin-status-warning quick-vin-status-info')
                   .addClass(`quick-vin-status-${type}`);
        
        // Update input styling
        vinInput.removeClass('vin-success vin-error vin-warning');
        if (type === 'success') {
            vinInput.addClass('vin-success');
        } else if (type === 'error') {
            vinInput.addClass('vin-error');
        } else if (type === 'warning') {
            vinInput.addClass('vin-warning');
        }
    }
}

function clearQuickVINStatus() {
    const statusElement = $('#quick_vin_status');
    const vinInput = $('#quick_vin');
    
    if (statusElement.length) {
        statusElement.text('')
                   .removeClass('quick-vin-status-loading quick-vin-status-success quick-vin-status-error quick-vin-status-warning quick-vin-status-info');
    }
    
    if (vinInput.length) {
        vinInput.removeClass('vin-success vin-error vin-warning');
    }
}

function clearQuickForm() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery not available for clearQuickForm');
        return;
    }
    
    $('#quickOrderForm')[0].reset();
    
    // Reset all validation classes and messages
    $('#quickOrderForm').removeClass('was-validated');
    $('#quickOrderForm .is-invalid').removeClass('is-invalid');
    $('#quickOrderForm .is-valid').removeClass('is-valid');
    $('#quickOrderForm .invalid-feedback').hide();
    $('#quickOrderForm .valid-feedback').hide();
    
    // Clear VIN status and vehicle field
    clearQuickVINStatus();
    clearQuickVehicleField();
    
    // Reset form submission state
    $('#quickOrderForm').data('submitting', false);
    
    // Reset service dropdown
    $('#quick_service_id').html('<option value=""><?= lang('App.select_service_placeholder') ?></option>');
    
    // Reset button state
    $('#quickSubmitBtn').prop('disabled', false);
    const originalText = '<i class="ri-add-line me-1"></i><?= lang('App.add_order') ?>';
    $('#quickSubmitBtn').html(originalText);
    
    // Reset last toast message to allow new notifications
    window.lastToastMessage = null;
    window.lastToastTime = null;
    
    loadServicesForClient('');
}

// showToast function removed - using global definition from index.php

// editReconOrder function removed - using global definition from index.php

// Individual Today's Orders Filters (Users only)
function applyTodayFilters() {
    if (typeof window.todayTable !== 'undefined' && window.todayTable) {
        window.todayTable.ajax.reload();
        showToast('<?= lang("App.today_orders_filters_applied") ?>', 'success');
    }
}

function clearTodayFilters() {
    document.getElementById('todayStatusFilter').value = '';
    document.getElementById('todaySearchFilter').value = '';
    
    if (typeof window.todayTable !== 'undefined' && window.todayTable) {
        window.todayTable.ajax.reload();
        showToast('<?= lang("App.today_orders_filters_cleared") ?>', 'success');
    }
}

function deleteReconOrder(orderId) {
    if (!orderId) {
        showToast('Invalid order ID', 'error');
        return;
    }
    
    // Show confirmation dialog
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '<?= lang("App.are_you_sure") ?>',
            text: 'Are you sure you want to delete this recon order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?= lang("App.yes_delete") ?>',
            cancelButtonText: '<?= lang("App.cancel") ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                performDeleteOrder(orderId);
            }
        });
    } else {
        if (confirm('Are you sure you want to delete this recon order?')) {
            performDeleteOrder(orderId);
        }
    }
}

function performDeleteOrder(orderId) {
    $.ajax({
        url: '<?= base_url('recon_orders/delete/') ?>' + orderId,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast(response.message || '<?= lang("App.order_deleted_successfully") ?>', 'success');
                
                // Refresh today's table
                if (typeof $ !== 'undefined' && $.fn.DataTable && $('#today-table').length) {
                    var todayTable = $('#today-table').DataTable();
                    if (todayTable) {
                        todayTable.ajax.reload();
                    }
                }
                
                // Refresh other tables if they exist
                if (typeof refreshAllReconOrdersData === 'function') {
                    try {
                        refreshAllReconOrdersData();
                    } catch (e) {
                        // console.log('Error refreshing all recon orders data:', e);
                    }
                }
            } else {
                showToast(response.message || 'Failed to delete recon order', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Delete error:', error);
            showToast('An error occurred while deleting the order', 'error');
        }
    });
}
</script> 