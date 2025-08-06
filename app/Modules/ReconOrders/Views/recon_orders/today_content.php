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
</style>

<!-- Quick Order Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
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
                                <input type="text" class="form-control quick-form-input" id="quick_vin" name="vin_number" placeholder="<?= lang('App.enter_vin_placeholder') ?>" maxlength="17" required>
                                <div class="invalid-feedback">
                                    <?= lang('App.vin_required') ?>
                                </div>
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
        <div class="card">
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
                <div class="table-responsive">
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
function initializeTodayTable() {
    try {
        console.log('Initializing Today Table...');
        
        if (typeof $ === 'undefined') {
            console.error('jQuery is not loaded for Today Table');
            return;
        }
        
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTables is not loaded');
            return;
        }

        var todayTable = $('#today-table').DataTable({
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
                    data: 'order_id',
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
                        
                        // Add service date
                        if (data && data !== 'N/A') {
                            const date = new Date(data);
                            const formattedDate = date.toLocaleDateString();
                            html += `<span class="fw-medium">${formattedDate}</span>`;
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
                            
                            html += `<div class="small mt-1 ${statusClass}">
                                <i class="${statusIcon} me-1"></i>${row.status.charAt(0).toUpperCase() + row.status.slice(1).replace('_', ' ')}
                            </div>`;
                        }
                        
                        html += `</div>`;
                        return html;
                    }
                },
                {
                    data: 'stock',
                    render: function(data, type, row) {
                        return `<div><span class="fw-medium">${data || 'N/A'}</span></div>`;
                    }
                },
                {
                    data: 'vehicle',
                    render: function(data, type, row) {
                        return `<div><span class="fw-medium">${data || 'N/A'}</span></div>`;
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return '<div class="d-flex justify-content-center gap-2 action-buttons">' +
                               '<a href="<?= base_url('recon_orders/view/') ?>' + data + '" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.view') ?>">' +
                               '<i class="ri-eye-fill"></i>' +
                               '</a>' +
                               '<a href="#" class="link-success fs-15 edit-order-btn" data-id="' + data + '" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.edit') ?>">' +
                               '<i class="ri-edit-fill"></i>' +
                               '</a>' +
                               '<a href="#" class="link-danger fs-15 delete-order-btn" data-id="' + data + '" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.delete') ?>">' +
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
                
                // Apply service color to rows
                $('#today-table tbody tr').each(function() {
                    var $row = $(this);
                    var rowData = todayTable.row($row).data();
                    if (rowData && rowData.DT_RowData && rowData.DT_RowData['service-color']) {
                        var color = rowData.DT_RowData['service-color'];
                        var rgba = hexToRgba(color, 0.1);
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
            todayTable.ajax.reload();
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
            
            var data = todayTable.row(this).data();
            if (data && data.id) {
                window.location.href = '<?= base_url('recon_orders/view/') ?>' + data.id;
            }
        });

        // Add pointer cursor to clickable rows
        $('#today-table tbody').on('mouseenter', 'tr', function() {
            $(this).css('cursor', 'pointer');
        });

        console.log('Today Table initialized successfully');
        
        // Also initialize quick form if it hasn't been initialized yet
        if (typeof initializeQuickForm === 'function') {
            try {
                initializeQuickForm();
            } catch (e) {
                console.log('Quick form already initialized or error:', e);
            }
        }
    } catch (error) {
        console.error('Error initializing Today Table:', error);
    }
}

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
        console.log('Quick form already initialized, skipping...');
        return;
    }
    
    console.log('Initializing quick form...');
    
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
            console.log('Form already being submitted, preventing duplicate');
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
            showToast('error', '<?= lang('App.complete_all_fields') ?>');
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
                    showToast('success', response.message || '<?= lang('App.order_saved_successfully') ?>');
                    
                    // Reset validation messages and classes
                    $('#quickOrderForm').removeClass('was-validated');
                    $('#quickOrderForm .is-invalid').removeClass('is-invalid');
                    $('#quickOrderForm .invalid-feedback').hide();
                    
                    clearQuickForm();
                    
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
                             console.log('Error refreshing all recon orders data:', e);
                         }
                     }
                } else {
                    showToast('error', response.message || '<?= lang('App.error_saving_order') ?>');
                }
            },
            error: function(xhr) {
                let errorMessage = '<?= lang('App.error_saving_order') ?>';
                
                console.log('AJAX Error:', xhr);
                console.log('Status:', xhr.status);
                console.log('Response:', xhr.responseText);
                
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
                
                showToast('error', errorMessage);
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
    
    // VIN validation
    $('#quick_vin').on('input', function() {
        const vin = $(this).val().toUpperCase();
        $(this).val(vin);
        
        if (vin.length > 0 && vin.length < 17) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
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

// Global toast function (if not already defined)
function showToast(type, message) {
    // Prevent duplicate toasts
    if (window.lastToastMessage === message && window.lastToastTime && (Date.now() - window.lastToastTime) < 2000) {
        return;
    }
    
    window.lastToastMessage = message;
    window.lastToastTime = Date.now();
    
    // Simple toast notification using SweetAlert2
    const icon = type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info';
    
    Swal.fire({
        icon: icon,
        title: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

// Use global editReconOrder function from index.php
function editReconOrder(orderId) {
    if (typeof window.editReconOrder === 'function') {
        // Use the global function from index.php that handles modal correctly
        console.log('🔄 Using global editReconOrder function for order:', orderId);
        window.editReconOrder(orderId);
    } else {
        // Fallback - should not happen in normal operations
        console.warn('⚠️ Global editReconOrder not found, redirecting to edit page');
        window.location.href = '<?= base_url('recon_orders/edit/') ?>' + orderId;
    }
}

function deleteReconOrder(orderId) {
    if (!orderId) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    // Show confirmation dialog
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '<?= lang('App.are_you_sure') ?>',
            text: 'Are you sure you want to delete this recon order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?= lang('App.yes_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>',
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
                showToast('success', response.message || 'Recon order deleted successfully');
                
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
                        console.log('Error refreshing all recon orders data:', e);
                    }
                }
            } else {
                showToast('error', response.message || 'Failed to delete recon order');
            }
        },
        error: function(xhr, status, error) {
            console.error('Delete error:', error);
            showToast('error', 'An error occurred while deleting the order');
        }
    });
}
</script> 