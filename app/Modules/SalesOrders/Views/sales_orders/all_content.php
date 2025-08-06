<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center"><?= lang('App.all_orders') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshTable" class="btn btn-secondary btn-sm">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="all-orders-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><?= lang('App.order_id') ?></th>
                                <th scope="col"><?= lang('App.stock') ?></th>
                                <th scope="col"><?= lang('App.client') ?></th>
                                <th scope="col"><?= lang('App.due') ?></th>
                                <th scope="col"><?= lang('App.status') ?></th>
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

<!-- Modal para mostrar órdenes duplicadas -->
<div class="modal fade" id="duplicateOrdersModal" tabindex="-1" aria-labelledby="duplicateOrdersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold text-primary" id="duplicateOrdersModalLabel">
                    <i class="ri-file-copy-line me-2"></i>
                    <span id="duplicateModalTitle"><?= lang('App.duplicate_orders') ?></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="<?= lang('App.close') ?>"></button>
            </div>
            <div class="modal-body p-3">
                <div id="duplicateOrdersContent">
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden"><?= lang('App.loading') ?>...</span>
                        </div>
                        <p class="mt-2 mb-0 text-muted small"><?= lang('App.loading_duplicate_orders') ?>...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i><?= lang('App.close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Clean Velzon card title styling */
.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--bs-body-color);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 767.98px) {
    .card-title {
        font-size: 1.25rem;
    }
}

@media (max-width: 575.98px) {
    .card-title {
        font-size: 1.1rem;
    }
}

/* Force DataTable to use full width on initialization */
#all-orders-table {
    width: 100% !important;
}

#all-orders-table_wrapper {
    width: 100% !important;
}

#all-orders-table thead th {
    width: auto !important;
}

.dataTables_wrapper {
    width: 100% !important;
}

/* Fix DataTable controls hover effects */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #e3ebf0 !important;
    border-radius: 6px !important;
    padding: 8px 12px !important;
    transition: all 0.15s ease-in-out !important;
    background-color: #fff !important;
}

.dataTables_wrapper .dataTables_length select:hover,
.dataTables_wrapper .dataTables_filter input:hover {
    border-color: #405189 !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.1) !important;
}

.dataTables_wrapper .dataTables_length select:focus,
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #405189 !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25) !important;
    outline: 0 !important;
}

/* Style DataTable info and pagination */
.dataTables_wrapper .dataTables_info {
    padding-top: 0.75rem !important;
    color: #64748b !important;
    font-size: 0.875rem !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.375rem 0.75rem !important;
    margin: 0 2px !important;
    border: 1px solid #e3ebf0 !important;
    border-radius: 6px !important;
    color: #64748b !important;
    text-decoration: none !important;
    transition: all 0.15s ease-in-out !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    border-color: #405189 !important;
    background-color: #405189 !important;
    color: #fff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    border-color: #405189 !important;
    background-color: #405189 !important;
    color: #fff !important;
}

/* Simplified Action Links */
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

.hstack {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
}

.gap-2 {
    gap: 0.5rem !important;
}

.flex-wrap {
    flex-wrap: wrap !important;
}

/* Center table headers */
#all-orders-table thead th {
    text-align: center !important;
}

/* Cursor pointer for tooltip elements */
.cursor-pointer {
    cursor: pointer !important;
}

/* Info badge styling */
.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.text-info {
    color: #0dcaf0 !important;
}

/* Tooltip content styling */
.tooltip-inner {
    max-width: 350px !important;
    text-align: left !important;
}

/* Clickeable row styling */
#all-orders-table tbody tr {
    cursor: pointer !important;
    transition: background-color 0.15s ease !important;
}

#all-orders-table tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.05) !important;
}

#all-orders-table tbody tr:hover td {
    background-color: transparent !important;
}

/* Prevent action buttons from triggering row click */
.action-buttons {
    position: relative;
    z-index: 10;
}

.action-buttons a {
    position: relative;
    z-index: 11;
}

/* Comments Badge Styles */
.comments-badge {
    display: inline-flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: #64748b;
    text-decoration: none;
    transition: all 0.2s ease;
    margin-left: 0.25rem;
}

.comments-badge:hover {
    background: #405189;
    border-color: #405189;
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(64, 81, 137, 0.2);
}

.comments-badge.has-comments {
    background: #ecfdf5;
    border-color: #10b981;
    color: #059669;
}

.comments-badge.has-comments:hover {
    background: #10b981;
    border-color: #10b981;
    color: #fff;
}

.comments-badge i {
    font-size: 0.7rem;
    margin-right: 0.25rem;
}

/* Internal Notes Badge Styles */
.internal-notes-badge {
    display: inline-flex;
    align-items: center;
    background: #fef3f2;
    border: 1px solid #fecaca;
    border-radius: 6px;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: #dc2626;
    text-decoration: none;
    transition: all 0.2s ease;
    margin-left: 0.25rem;
}

.internal-notes-badge:hover {
    background: #dc2626;
    border-color: #dc2626;
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
}

.internal-notes-badge.has-notes {
    background: #fef2f2;
    border-color: #ef4444;
    color: #dc2626;
}

.internal-notes-badge.has-notes:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: #fff;
}

.internal-notes-badge i {
    font-size: 0.7rem;
    margin-right: 0.25rem;
}

/* Duplicate Indicator Styles */
.duplicate-indicator {
    display: inline-flex;
    align-items: center;
    padding: 0.125rem 0.375rem;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    vertical-align: middle;
    cursor: pointer;
}

/* CRITICAL: Ensure status columns maintain styling even with duplicate badges */
.dataTables_wrapper tbody tr:has(.duplicate-indicator) td:has(.status-dropdown) {
    text-align: center !important;
    vertical-align: middle !important;
    padding: 8px !important;
}

.dataTables_wrapper tbody tr:has(.duplicate-indicator) .status-dropdown {
    width: 120px !important;
    margin: 0 auto !important;
    text-align: center !important;
    text-align-last: center !important;
    display: block !important;
}

/* CRITICAL: Ensure status columns maintain styling even with duplicate badges */
.dataTables_wrapper tbody tr:has(.duplicate-indicator) td:has(.status-dropdown) {
    text-align: center !important;
    vertical-align: middle !important;
    padding: 8px !important;
}

.dataTables_wrapper tbody tr:has(.duplicate-indicator) .status-dropdown {
    width: 120px !important;
    margin: 0 auto !important;
    text-align: center !important;
    text-align-last: center !important;
    display: block !important;
}

.duplicate-indicator i {
    font-size: 0.6rem;
    margin-right: 0.125rem;
}

.duplicate-indicator small {
    font-size: 0.6rem;
    font-weight: 600;
    margin-left: 0.125rem;
}

.stock-duplicate {
    background: #fff3cd;
    border: 1px solid #ffecb5;
    color: #997404;
}

.stock-duplicate:hover {
    background: #ffecb5;
    color: #664d03;
    transform: scale(1.05);
}

.vin-duplicate {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.vin-duplicate:hover {
    background: #f5c6cb;
    color: #491217;
    transform: scale(1.05);
}

/* Modal Styles */
#duplicateOrdersModal {
    z-index: 1060 !important;
}

#duplicateOrdersModal .modal-dialog {
    z-index: 1061 !important;
}

#duplicateOrdersModal .modal-content {
    z-index: 1062 !important;
    background-color: #fff !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.duplicate-orders-table {
    font-size: 0.8rem;
    margin-bottom: 0;
}

.duplicate-orders-table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 0.5rem 0.25rem;
    font-size: 0.75rem;
}

.duplicate-orders-table td {
    vertical-align: middle;
    border-bottom: 1px solid #dee2e6;
    padding: 0.5rem 0.25rem;
}

.current-order-row {
    background-color: #e7f3ff !important;
    border: 2px solid #0d6efd;
    border-radius: 4px;
}

.current-order-badge {
    background: #0d6efd;
    color: white;
    padding: 0.125rem 0.375rem;
    border-radius: 3px;
    font-size: 0.65rem;
    font-weight: 600;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
}

.status-completed {
    background: #d1e7dd;
    color: #0f5132;
}

.status-pending {
    background: #fff3cd;
    color: #664d03;
}

.status-processing {
    background: #cff4fc;
    color: #055160;
}

.status-cancelled {
    background: #f8d7da;
    color: #58151c;
}

/* Mobile responsive adjustments */
@media (max-width: 767.98px) {
    .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
    }
    
    .duplicate-orders-table {
        font-size: 0.7rem;
    }
    
    .duplicate-orders-table th,
    .duplicate-orders-table td {
        padding: 0.375rem 0.125rem;
    }
    
    .alert {
        font-size: 0.875rem;
        padding: 0.5rem;
    }
}

@media (max-width: 575.98px) {
    .modal-dialog {
        margin: 0.25rem;
        max-width: calc(100% - 0.5rem);
    }
    
    .duplicate-orders-table {
        font-size: 0.65rem;
    }
    
    .current-order-badge {
        font-size: 0.6rem;
        padding: 0.1rem 0.25rem;
    }
}
</style>

<script>
// Wait for DataTables to be available
function waitForDataTables(callback) {
    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForDataTables(callback);
        }, 100);
    }
}

waitForDataTables(function() {
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    let ordersTable;
    let isInitializing = false;

    // Use global duplicate modal function if available, otherwise create local one
    function showDuplicateOrdersModal(field, value, currentOrderId = null) {
        console.log('🔍 showDuplicateOrdersModal called:', { field, value, currentOrderId });
        
        const modalElement = document.getElementById('duplicateOrdersModal');
        const modalTitle = document.getElementById('duplicateModalTitle');
        const modalContent = document.getElementById('duplicateOrdersContent');
        
        console.log('📋 Modal elements found:', { 
            modalElement: !!modalElement, 
            modalTitle: !!modalTitle, 
            modalContent: !!modalContent 
        });
        
        if (!modalElement) {
            console.error('❌ Modal element #duplicateOrdersModal not found');
            return;
        }
        
        if (!modalTitle) {
            console.error('❌ Modal title element #duplicateModalTitle not found');
            return;
        }
        
        if (!modalContent) {
            console.error('❌ Modal content element #duplicateOrdersContent not found');
            return;
        }
        
        // Update modal title
        const fieldDisplayName = field === 'stock' ? 'Stock' : 'VIN';
        modalTitle.textContent = `<?= lang('App.duplicate_orders') ?> - ${fieldDisplayName}: ${value}`;
        
        console.log('✅ Modal title updated:', modalTitle.textContent);
        
        // Show loading state
        modalContent.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden"><?= lang('App.loading') ?>...</span>
                </div>
                <p class="mt-2 mb-0 text-muted small"><?= lang('App.loading_duplicate_orders') ?>...</p>
            </div>
        `;
        
        console.log('📦 Creating Bootstrap Modal instance...');
        
        try {
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
            
            console.log('✅ Bootstrap Modal created successfully');
            
            modal.show();
            console.log('✅ Modal.show() called');
            
        } catch (error) {
            console.error('❌ Error creating Bootstrap Modal:', error);
            
            // Fallback: try showing with jQuery if available
            if (typeof $ !== 'undefined') {
                console.log('🔄 Trying jQuery fallback...');
                try {
                    $('#duplicateOrdersModal').modal('show');
                    console.log('✅ jQuery modal show successful');
                } catch (jqError) {
                    console.error('❌ jQuery modal fallback failed:', jqError);
                }
            }
        }
        
        // Fetch duplicate orders
        console.log('🚀 About to start AJAX request...');
        console.log('🌐 Making AJAX request to:', '<?= base_url('sales_orders/getDuplicateOrders') ?>');
        console.log('📤 AJAX data:', { field, value, current_order_id: currentOrderId });
        console.log('🔧 jQuery available:', typeof $ !== 'undefined');
        console.log('🔧 jQuery.ajax available:', typeof $.ajax === 'function');
        
        if (typeof $ === 'undefined' || typeof $.ajax !== 'function') {
            console.error('❌ jQuery or jQuery.ajax not available');
            showError('jQuery not available for AJAX request');
            return;
        }
        
        console.log('✅ Starting jQuery AJAX request...');
        
        $.ajax({
            url: '<?= base_url('sales_orders/getDuplicateOrders') ?>',
            type: 'POST',
            data: {
                field: field,
                value: value,
                current_order_id: currentOrderId,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    displayDuplicateOrders(response);
                } else {
                    showError(response.error || '<?= lang('App.error_loading_data') ?>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                showError('<?= lang('App.error_loading_data') ?>');
            }
        });
    }
    
    // Function to display duplicate orders in modal
    function displayDuplicateOrders(data) {
        const modalContent = document.getElementById('duplicateOrdersContent');
        
        let html = `
            <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                <i class="ri-information-line me-2"></i>
                <?= lang('App.found') ?> <strong>${data.total_count}</strong> <?= lang('App.orders_with_same') ?> <strong>${data.field.toUpperCase()}</strong>: <strong>${data.value}</strong>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-sm duplicate-orders-table">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center"><?= lang('App.order_id') ?></th>
                            <th class="text-center d-none d-md-table-cell">Stock</th>
                            <th class="text-center d-none d-lg-table-cell">VIN</th>
                            <th class="text-center"><?= lang('App.vehicle') ?></th>
                            <th class="text-center d-none d-md-table-cell"><?= lang('App.client') ?></th>
                            <th class="text-center d-none d-lg-table-cell"><?= lang('App.salesperson') ?></th>
                            <th class="text-center"><?= lang('App.status') ?></th>
                            <th class="text-center d-none d-md-table-cell"><?= lang('App.date') ?></th>
                            <th class="text-center"><?= lang('App.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        data.orders.forEach(order => {
            const rowClass = order.is_current ? 'current-order-row' : '';
            const currentBadge = order.is_current ? '<span class="current-order-badge ms-1">Current</span>' : '';
            
            const statusClass = {
                'completed': 'status-completed',
                'pending': 'status-pending',
                'processing': 'status-processing',
                'in_progress': 'status-processing',
                'cancelled': 'status-cancelled'
            };
            
            const statusDisplayClass = statusClass[order.status] || 'status-pending';
            const statusDisplayText = order.status.charAt(0).toUpperCase() + order.status.slice(1).replace('_', ' ');
            
            html += `
                <tr class="${rowClass}">
                    <td class="text-center">
                        <strong class="text-primary">${order.order_number}</strong>
                        ${currentBadge}
                    </td>
                    <td class="text-center d-none d-md-table-cell">
                        <small class="text-muted">${order.stock}</small>
                    </td>
                    <td class="text-center d-none d-lg-table-cell">
                        <small class="text-muted">${order.vin}</small>
                    </td>
                    <td class="text-center">
                        <small>${order.vehicle}</small>
                    </td>
                    <td class="text-center d-none d-md-table-cell">
                        <small>${order.client_name}</small>
                    </td>
                    <td class="text-center d-none d-lg-table-cell">
                        <small>${order.salesperson_name}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge status-badge ${statusDisplayClass}">
                            ${statusDisplayText}
                        </span>
                    </td>
                    <td class="text-center d-none d-md-table-cell">
                        <small class="text-muted">
                            ${order.date !== 'N/A' ? order.date : ''} 
                            ${order.time !== 'N/A' ? '<br>' + order.time : ''}
                        </small>
                    </td>
                    <td class="text-center">
                        <a href="<?= base_url('sales_orders/view/') ?>${order.id}" 
                           class="btn btn-sm btn-outline-primary" 
                           title="<?= lang('App.view_order') ?>">
                            <i class="ri-eye-line"></i>
                        </a>
                    </td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        modalContent.innerHTML = html;
    }
    
    // Function to show error in modal
    function showError(message) {
        const modalContent = document.getElementById('duplicateOrdersContent');
        modalContent.innerHTML = `
            <div class="alert alert-danger text-center">
                <i class="ri-error-warning-line me-2"></i>
                ${message}
            </div>
        `;
    }

    // DataTable Configuration - SIMPLIFIED WITH GLOBAL FILTERS
    function initializeDataTable() {
        if (isInitializing) {
            return;
        }
        
        isInitializing = true;

        // Check dependencies
        if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
            return;
        }

        const tableElement = document.getElementById('all-orders-table');
        if (!tableElement) {
            return;
        }

        try {
            // Destroy existing table if it exists
            if ($.fn.DataTable.isDataTable('#all-orders-table')) {
                $('#all-orders-table').DataTable().destroy();
                $('#all-orders-table').off();
            }

            // Force table width before initialization
            $('#all-orders-table').css('width', '100%');
            $('.table-responsive').css('width', '100%');

            ordersTable = $('#all-orders-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                scrollX: false,
                autoWidth: false,
                columnDefs: [
                    { width: "15%", targets: 0, className: "text-center" }, // Order ID
                    { width: "20%", targets: 1, className: "text-center" }, // Stock
                    { width: "25%", targets: 2, className: "text-center" }, // Client
                    { width: "12%", targets: 3, className: "text-center" }, // Date
                    { width: "13%", targets: 4, className: "text-center" }, // Status
                    { width: "15%", targets: 5, orderable: false, searchable: false, className: "text-center" } // Actions
                ],
                ajax: {
                    url: '<?= base_url('sales_orders/all_content') ?>',
                    type: 'POST',
                    data: function(d) {
                        // Use global filter system
                        if (typeof window.getGlobalFilterData === 'function') {
                            return window.getGlobalFilterData(d);
                        } else {
                            // Fallback for older system
                            d.client_filter = window.globalFilters?.client || '';
                            d.contact_filter = window.globalFilters?.contact || '';
                            d.status_filter = window.globalFilters?.status || '';
                            d.date_from_filter = window.globalFilters?.dateFrom || '';
                            d.date_to_filter = window.globalFilters?.dateTo || '';
                            d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
                        }
                        return d;
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Error loading data:', error, xhr.responseText);
                        let errorMessage = '<?= lang('App.error_loading_data') ?>';

                        if (xhr.status === 403) {
                            errorMessage = '<?= lang('App.access_denied') ?>';
                        } else if (xhr.status === 500) {
                            errorMessage = '<?= lang('App.server_error') ?>';
                        } else if (xhr.status === 404) {
                            errorMessage = '<?= lang('App.service_not_found') ?>';
                        }

                        showToast('error', errorMessage);
                    }
                },
                columns: [
                    {
                        data: 'order_id',
                        render: function(data, type, row) {
                            let html = `<div>`;
                            html += `<a href="<?= base_url('sales_orders/view/') ?>${row.id}" class="fw-medium text-primary text-decoration-none">${data}</a>`;
                            
                            // Add comments badge if order has comments
                            if (row.comments_count && parseInt(row.comments_count) > 0) {
                                const commentCount = parseInt(row.comments_count);
                                const badgeClass = commentCount > 0 ? 'comments-badge has-comments' : 'comments-badge';
                                
                                // Create simple tooltip text
                                let tooltip = '';
                                if (row.comments && row.comments.length > 0) {
                                    // Create a simple text tooltip with comments
                                    const commentsPreview = row.comments.slice(0, 2).map(comment => 
                                        `${comment.author_name}: ${comment.comment.substring(0, 50)}${comment.comment.length > 50 ? '...' : ''}`
                                    ).join(' | ');
                                    
                                    tooltip = `${commentCount} ${commentCount === 1 ? 'comentario' : 'comentarios'} - ${commentsPreview}`;
                                    if (commentCount > 2) {
                                        tooltip += ` | +${commentCount - 2} más...`;
                                    }
                                } else {
                                    // Fallback to simple count
                                    const commentText = `<?= lang('App.comment_count') ?>`;
                                    tooltip = commentCount === 1 ? 
                                        commentText.replace('{0}', commentCount).split('|')[0] :
                                        commentText.replace('{0}', commentCount).split('|')[1];
                                }
                                
                                html += `<a href="<?= base_url('sales_orders/view/') ?>${row.id}#comments" class="${badgeClass}" 
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="${tooltip}">
                                    <i class="ri-message-2-line"></i>${commentCount}
                                </a>`;
                            }
                            
                            // Add internal notes badge if order has internal notes (only for staff users)
                            <?php if (auth()->user() && auth()->user()->user_type === 'staff'): ?>
                            if (row.internal_notes_count && parseInt(row.internal_notes_count) > 0) {
                                const notesCount = parseInt(row.internal_notes_count);
                                const notesBadgeClass = notesCount > 0 ? 'internal-notes-badge has-notes' : 'internal-notes-badge';
                                
                                // Create simple tooltip text for internal notes
                                let notesTooltip = '';
                                if (row.internal_notes && row.internal_notes.length > 0) {
                                    // Create a simple text tooltip with notes
                                    const notesPreview = row.internal_notes.slice(0, 2).map(note => 
                                        `${note.author_name}: ${note.content.substring(0, 50)}${note.content.length > 50 ? '...' : ''}`
                                    ).join(' | ');
                                    
                                    notesTooltip = `${notesCount} ${notesCount === 1 ? 'nota interna' : 'notas internas'} - ${notesPreview}`;
                                    if (notesCount > 2) {
                                        notesTooltip += ` | +${notesCount - 2} más...`;
                                    }
                                } else {
                                    // Fallback to simple count
                                    notesTooltip = `${notesCount} ${notesCount === 1 ? 'nota interna' : 'notas internas'}`;
                                }
                                
                                html += `<a href="<?= base_url('sales_orders/view/') ?>${row.id}#notes-pane" class="${notesBadgeClass}" 
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="${notesTooltip}">
                                    <i class="ri-file-lock-line"></i>${notesCount}
                                </a>`;
                            }
                            <?php endif; ?>
                            
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
                        data: 'stock',
                        render: function(data, type, row) {
                            let html = `<div><span class="fw-medium">${data}</span>`;
                            
                            // Add duplicate indicator if order has stock duplicates
                            if (row.duplicates && row.duplicates.stock && row.duplicates.stock > 0) {
                                const count = row.duplicates.stock;
                                const tooltip = `Total of ${count} orders with same stock`;
                                
                                html += `<span class="duplicate-indicator stock-duplicate ms-1" 
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="${tooltip}"
                                    onclick="showDuplicateOrdersModal('stock', '${row.duplicates.stock_value || data}', '${row.id}'); event.stopPropagation();">
                                    <i class="ri-file-copy-line"></i><small>${count}</small>
                                </span>`;
                            }
                            
                            if (row.salesperson_name && row.salesperson_name !== 'N/A') {
                                html += `<div class="text-muted small"><?= lang('App.sales') ?>: ${row.salesperson_name}</div>`;
                            }
                            html += `</div>`;
                            return html;
                        }
                    },
                    {
                        data: 'client_name',
                        render: function(data, type, row) {
                            let html = `<div><span class="fw-medium">${row.vehicle || data}</span>`;
                            if (row.vin) {
                                html += `<div class="text-muted small">VIN: ${row.vin}`;
                                
                                // Add VIN duplicate indicator
                                if (row.duplicates && row.duplicates.vin && row.duplicates.vin > 0) {
                                    const count = row.duplicates.vin;
                                    const tooltip = `Total of ${count} orders with same VIN`;
                                    
                                    html += `<span class="duplicate-indicator vin-duplicate ms-1" 
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="${tooltip}"
                                        onclick="showDuplicateOrdersModal('vin', '${row.duplicates.vin_value || row.vin}', '${row.id}'); event.stopPropagation();">
                                        <i class="ri-file-copy-line"></i><small>${count}</small>
                                    </span>`;
                                }
                                
                                html += `</div>`;
                            }
                            
                            // Add tooltip for instructions if available (removed client info)
                            if (row.instructions && row.instructions.trim() !== '') {
                                let tooltipContent = `<strong><?= lang('App.instructions') ?>:</strong><br>${row.instructions.replace(/\n/g, '<br>')}`;
                                
                                html += `<div class="mt-1">
                                    <span class="badge bg-warning-subtle text-warning-emphasis cursor-pointer" 
                                          data-bs-toggle="tooltip" 
                                          data-bs-placement="top" 
                                          data-bs-html="true"
                                          title="${tooltipContent.replace(/"/g, '&quot;')}"
                                          style="font-size: 0.7rem; border: 1px solid rgba(255, 193, 7, 0.3);">
                                        <i class="ri-file-text-line"></i> <?= lang('App.instructions') ?>
                                    </span>
                                </div>`;
                            }
                            
                            html += `</div>`;
                            return html;
                        }
                    },
                    {
                        data: 'due',
                        render: function(data, type, row) {
                            if (data && data !== 'N/A') {
                                return data; // Return the HTML directly since it's already formatted
                            }
                            return '<div class="text-center"><span class="text-muted">N/A</span></div>';
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                const statusOptions = [
                                    {value: 'pending', label: 'Pending', class: 'warning'},
                                    {value: 'processing', label: 'Processing', class: 'info'},
                                    {value: 'in_progress', label: 'In Progress', class: 'primary'},
                                    {value: 'completed', label: 'Completed', class: 'success'},
                                    {value: 'cancelled', label: 'Cancelled', class: 'danger'}
                                ];

                                let options = '';
                                statusOptions.forEach(option => {
                                    const selected = option.value === data ? 'selected' : '';
                                    options += `<option value="${option.value}" ${selected}>${option.label}</option>`;
                                });

                                return `
                                    <select class="form-select form-select-sm status-dropdown status-${data}" 
                                            data-order-id="${row.id}" 
                                            style="width: 120px; font-size: 11px;">
                                        ${options}
                                    </select>
                                `;
                            }
                            return data;
                        },
                        orderable: false
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex justify-content-center gap-2 action-buttons">
                                    <a href="<?= base_url('sales_orders/view/') ?>${data}" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.view_sales_order') ?>">
                                        <i class="ri-eye-fill"></i>
                                    </a>
                                    <a href="#" class="link-success fs-15 edit-order-btn" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.edit_sales_order') ?>">
                                        <i class="ri-edit-fill"></i>
                                    </a>
                                    <a href="#" class="link-danger fs-15 delete-order-btn" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.delete') ?>">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    url: function() {
                        const currentLang = document.documentElement.lang || 
                                           localStorage.getItem('locale') || 
                                           '<?= session()->get('locale') ?? 'en' ?>';
                                            
                        const baseUrl = window.baseUrl || '<?= base_url() ?>';
                                            
                        const langMap = {
                            'es': baseUrl + 'assets/libs/datatables/i18n/es-ES.json',
                            'pt': baseUrl + 'assets/libs/datatables/i18n/pt-BR.json',
                            'en': baseUrl + 'assets/libs/datatables/i18n/en-US.json'
                        };
                                            
                        return langMap[currentLang] || langMap['en'];
                    }(),
                    processing: "<div class='d-flex align-items-center'><div class='spinner-border text-primary me-2' role='status'><span class='visually-hidden'>Loading...</span></div> <?= lang('App.loading') ?>...</div>",
                    emptyTable: `
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="shopping-cart" class="icon-lg text-muted"></i>
                            </div>
                            <h5 class="text-muted"><?= lang('App.no_orders_found') ?></h5>
                            <p class="text-muted mb-3"><?= lang('App.no_orders_match_filters') ?></p>
                            <button onclick="clearAllGlobalFilters()" class="btn btn-secondary btn-sm">
                                <i data-feather="refresh-cw" class="icon-sm me-1"></i> <?= lang('App.clear_filters') ?>
                            </button>
                        </div>
                    `,
                    zeroRecords: `
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="shopping-cart" class="icon-lg text-muted"></i>
                            </div>
                            <h5 class="text-muted"><?= lang('App.no_orders_found') ?></h5>
                            <p class="text-muted mb-3"><?= lang('App.no_orders_match_filters') ?></p>
                            <button onclick="clearAllGlobalFilters()" class="btn btn-secondary btn-sm">
                                <i data-feather="refresh-cw" class="icon-sm me-1"></i> <?= lang('App.clear_filters') ?>
                            </button>
                        </div>
                    `,
                    info: "<?= lang('App.showing') ?> _START_ <?= lang('App.to') ?> _END_ <?= lang('App.of') ?> _TOTAL_ <?= lang('App.orders') ?>",
                    infoEmpty: "<?= lang('App.no_orders_to_display') ?>",
                    infoFiltered: "",
                    search: "<?= lang('App.search_orders') ?>:",
                    paginate: {
                        first: "<?= lang('App.first') ?>",
                        last: "<?= lang('App.last') ?>",
                        next: "<?= lang('App.next') ?>",
                        previous: "<?= lang('App.previous') ?>"
                    }
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                rowCallback: function(row, data, index) {
                    // Apply CSS class to the row based on order status and timing
                    if (data.row_class && data.row_class !== '') {
                        $(row).addClass(data.row_class);
                    }
                    

                },
                initComplete: function(settings, json) {
                    const table = this.api();

                    setTimeout(() => {
                        table.columns.adjust();
                        $('#all-orders-table').css('width', '100%');
                    }, 50);

                    setTimeout(() => {
                        table.columns.adjust().draw();
                        $('#all-orders-table').css('width', '100%');
                    }, 150);

                    setTimeout(() => {
                        table.columns.adjust();
                        $('#all-orders-table').css('width', '100%');
                        $('.dataTables_wrapper').css('width', '100%');
                    }, 300);

                    // Initialize Feather icons after each redraw
                    if (typeof feather !== 'undefined') {
                        setTimeout(() => {
                            feather.replace();
                        }, 50);
                    }

                    // Initialize tooltips on first load
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        setTimeout(() => {
                            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                            tooltipTriggerList.map(function (tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl, {
                                    html: true,
                                    trigger: 'hover focus',
                                    delay: { show: 500, hide: 100 }
                                });
                            });
                        }, 100);
                    }

                    isInitializing = false;
                },
                drawCallback: function(settings) {
                    // Initialize Feather icons after each redraw
                    if (typeof feather !== 'undefined') {
                        setTimeout(() => {
                            feather.replace();
                        }, 50);
                    }

                    // Initialize tooltips with HTML content support
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        // Dispose of existing tooltips first
                        const existingTooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                        existingTooltips.forEach(element => {
                            const tooltip = bootstrap.Tooltip.getInstance(element);
                            if (tooltip) {
                                tooltip.dispose();
                            }
                        });
                        
                        // Initialize new tooltips
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.map(function (tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl, {
                                html: true,
                                trigger: 'hover focus',
                                delay: { show: 500, hide: 100 }
                            });
                        });
                    }

                    // Status change event listeners are handled globally in index.php
                    /*
                    $(document).off('change', '.status-dropdown').on('change', '.status-dropdown', function(e) {
                        e.stopPropagation();
                        const selectElement = this;
                        const orderId = $(this).data('order-id');
                        const newStatus = $(this).val();
                        const oldStatus = $(this).find('option:selected').data('old-value') || 
                                          $(this).find('option').filter(function() { 
                                              return this.defaultSelected; 
                                          }).val();

        

                        // Disable dropdown during update
                        $(selectElement).prop('disabled', true);

                        fetch(`<?= base_url('sales_orders/updateStatus/') ?>${orderId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: `status=${encodeURIComponent(newStatus)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', `Status updated to ${newStatus.replace('_', ' ')}`);
                                // Re-enable dropdown
                                $(selectElement).prop('disabled', false);
                                
                                // Update dropdown color class
                                $(selectElement).removeClass('status-pending status-processing status-in_progress status-completed status-cancelled');
                                $(selectElement).addClass(`status-${newStatus}`);
                                
                                // Update the row styling if needed
                                const $row = $(selectElement).closest('tr');
                                $row.removeClass('order-row-pending order-row-processing order-row-completed order-row-cancelled');
                                $row.addClass(`order-row-${newStatus.replace('_', '-')}`);
                                
                            } else {
                                showToast('error', data.message || 'Error updating status');
                                // Reset to previous value
                                $(selectElement).val(oldStatus);
                                $(selectElement).prop('disabled', false);
                            }
                        })
                        .catch(error => {
                            console.error('Error updating status:', error);
                            showToast('error', 'Error updating status');
                            // Reset to previous value
                            $(selectElement).val(oldStatus);
                            $(selectElement).prop('disabled', false);
                        });
                    });
                    */

                    // Add row click event listeners
                    $('#all-orders-table tbody tr').off('click').on('click', function(e) {
                        // Don't trigger row click if clicking on action buttons, links, or dropdowns
                        if ($(e.target).closest('.action-buttons').length > 0 || 
                            $(e.target).closest('a').length > 0 || 
                            $(e.target).hasClass('badge') ||
                            $(e.target).closest('.duplicate-indicator').length > 0 ||
                            $(e.target).hasClass('status-dropdown') ||
                            $(e.target).closest('select').length > 0) {
                            return;
                        }
                        
                        // Get the order ID from the row data
                        const table = $('#all-orders-table').DataTable();
                        const rowData = table.row(this).data();
                        if (rowData && rowData.id) {
                            window.location.href = `<?= base_url('sales_orders/view/') ?>${rowData.id}`;
                        }
                    });

                    // Ensure table uses full width on every draw
                    $('#all-orders-table').css('width', '100%');
                    $('.dataTables_wrapper').css('width', '100%');
                }
            });

        } catch (error) {
            console.error('❌ Error initializing DataTable:', error);
            isInitializing = false;
        }
    }

    // Refresh function
    $('#refreshTable').on('click', function() {
        if (ordersTable) {
            ordersTable.ajax.reload(null, false); // false = don't reset pagination
            showToast('success', '<?= lang('App.table_refreshed') ?>');
        }
    });

    // Global function to sync with global filter (called from main page)
    window.syncAllContentWithGlobalFilter = function() {
        if (ordersTable) {
            // Add loading indicator to table
            $('#all-orders-table_processing').show();
            
            // Reload table with global filters
            ordersTable.ajax.reload(null, false); // false = don't reset pagination
        }
    };

    // Global function to clear filters (called from main page)
    window.clearAllGlobalFilters = function() {
        // This function is handled by the main global filter system
        if (typeof window.clearAllFilters === 'function') {
            window.clearAllFilters();
        }
    };

    // Action Functions
    window.viewOrder = function(orderId) {
        window.location.href = `<?= base_url('sales_orders/view/') ?>${orderId}`;
    };

    // Individual functions are still available but event listeners are global
    window.editOrder = function(orderId) {
        try {
            // Use the universal modal system from index.php
            if (typeof loadOrderForEdit === 'function') {
                loadOrderForEdit(orderId);
            } else if (typeof window.loadOrderForEdit === 'function') {
                window.loadOrderForEdit(orderId);
            } else {
                console.error('loadOrderForEdit function not available');
                // Fallback: redirect to edit page
                window.location.href = `<?= base_url('sales_orders?edit=') ?>${orderId}`;
            }
        } catch (error) {
            console.error('Error in editOrder:', error);
            showToast('error', 'Error opening edit form');
        }
    };

    window.deleteOrder = function(orderId) {
        // Use SweetAlert for better UX
        Swal.fire({
            title: '<?= lang('App.are_you_sure') ?>',
            text: '<?= lang('App.confirm_delete_order') ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f06548',
            cancelButtonColor: '#74788d',
            confirmButtonText: '<?= lang('App.yes_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '<?= lang('App.deleting') ?>...',
                    text: '<?= lang('App.please_wait') ?>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`<?= base_url('sales_orders/delete/') ?>${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    
                    if (data.success) {
                        showToast('success', data.message || 'Order deleted successfully');
                        
                        // Refresh this table and others
                        if (ordersTable) {
                            ordersTable.ajax.reload(null, false);
                        }
                        
                        // Refresh other tables if their sync functions exist
                        if (typeof window.syncTodayContentWithGlobalFilter === 'function') {
                            window.syncTodayContentWithGlobalFilter();
                        }
                        if (typeof window.syncDashboardWithGlobalFilter === 'function') {
                            window.syncDashboardWithGlobalFilter();
                        }
                        
                    } else {
                        showToast('error', data.message || 'Error deleting order');
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error deleting order:', error);
                    showToast('error', 'Error deleting order');
                });
            }
        });
    };

    // Toast function - Same as clients pages
    // Toast function
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

    }
    }

    // Initialize DataTable with increased delay
    setTimeout(() => {
        initializeDataTable();
    }, 500);

    // Additional Feather icons initialization
    setTimeout(() => {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 1000);

    // Expose showDuplicateOrdersModal globally for reuse in other views
    window.showDuplicateOrdersModal = showDuplicateOrdersModal;

}); // End of waitForDataTables
</script>
