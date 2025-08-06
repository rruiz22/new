<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center">
                    <i data-feather="calendar" class="icon-sm me-1"></i>
                    <?= lang('App.today_orders') ?> <span id="todayOrderCount"></span> - <?= date('l, F j, Y') ?>
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshTodayTable" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                    <div class="btn btn-outline-info btn-sm" id="autoRefreshTimer">
                        <i data-feather="clock" class="icon-sm me-1"></i>
                        <span id="timerDisplay">60</span>s
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="today-orders-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
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
/* Same styles as all_content.php */
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

#today-orders-table {
    width: 100% !important;
}

#today-orders-table_wrapper {
    width: 100% !important;
}

#today-orders-table thead th {
    width: auto !important;
}

.dataTables_wrapper {
    width: 100% !important;
}

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
#today-orders-table thead th {
    text-align: center !important;
}

/* Order count styling in title */
#todayOrderCount {
    font-weight: 600;
    margin-left: 0.25rem;
    transition: color 0.3s ease;
}

/* Auto-refresh timer styles */
#autoRefreshTimer {
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

#autoRefreshTimer.refreshing {
    background-color: #f06548;
    border-color: #f06548;
    color: white;
}

#autoRefreshTimer.paused {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

#autoRefreshTimer:hover {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

#timerDisplay {
    font-weight: 600;
    min-width: 20px;
    display: inline-block;
}

/* Clickeable row styling */
#today-orders-table tbody tr {
    cursor: pointer !important;
    transition: background-color 0.15s ease !important;
}

#today-orders-table tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.05) !important;
}

#today-orders-table tbody tr:hover td {
    background-color: transparent !important;
}

/* Prevent action buttons from triggering row click */
.action-buttons {
    position: relative;
    z-index: 10;
}

/* Tooltip content styling */
.tooltip-inner {
    max-width: 350px !important;
    text-align: left !important;
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
.duplicate-orders-table {
    font-size: 0.875rem;
}

.duplicate-orders-table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    text-align: center;
}

.duplicate-orders-table td {
    vertical-align: middle;
    text-align: center;
    border-bottom: 1px solid #dee2e6;
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

/* Compact Modal Styles */
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
function waitForDataTablesOnToday(callback) {
    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForDataTablesOnToday(callback);
        }, 100);
    }
}

waitForDataTablesOnToday(function() {

    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    let todayOrdersTable;
    let isInitializing = false;

    // Get today's date in YYYY-MM-DD format
    function getTodayDate() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Function to update the order count in title
    function updateTodayOrderCount(recordsFiltered) {
        const countElement = document.getElementById('todayOrderCount');
        const badgeElement = document.getElementById('todayOrdersBadge');
        
        if (countElement) {
            if (recordsFiltered > 0) {
                countElement.textContent = `(${recordsFiltered})`;
                countElement.style.color = '#0ab39c'; // Green color for positive count
            } else {
                countElement.textContent = '(0)';
                countElement.style.color = '#64748b'; // Muted color for zero count
            }
        }

        // Update the badge in the tab navigation
        if (badgeElement) {
            if (recordsFiltered > 0) {
                badgeElement.textContent = recordsFiltered;
                badgeElement.classList.add('show');
                badgeElement.style.display = 'inline-block';
            } else {
                badgeElement.style.display = 'none';
                badgeElement.classList.remove('show');
            }
        }
    }

    // Function to show duplicate orders modal
    function showDuplicateOrdersModal(field, value, currentOrderId = null) {
        const modal = new bootstrap.Modal(document.getElementById('duplicateOrdersModal'));
        const modalTitle = document.getElementById('duplicateModalTitle');
        const modalContent = document.getElementById('duplicateOrdersContent');
        
        // Update modal title
        const fieldDisplayName = field === 'stock' ? 'Stock' : 'VIN';
        modalTitle.textContent = `Duplicate Orders - ${fieldDisplayName}: ${value}`;
        
        // Show loading state
        modalContent.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">Loading duplicate orders...</p>
            </div>
        `;
        
        modal.show();
        
        // Fetch duplicate orders
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
                    showError(response.error || 'Error loading duplicate orders');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                showError('Error loading duplicate orders');
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

    // DataTable Configuration
    function initializeTodayDataTable() {
        if (isInitializing) {
            return;
        }

        isInitializing = true;

        // Check dependencies
        if (typeof $ === 'undefined') {
            console.error('❌ jQuery not available');
            isInitializing = false;
            return;
        }

        if (typeof $.fn.DataTable === 'undefined') {
            console.error('❌ DataTables not available');
            isInitializing = false;
            return;
        }

        // Check table element exists
        const tableElement = document.getElementById('today-orders-table');
        if (!tableElement) {
            console.error('❌ Table #today-orders-table not found');
            isInitializing = false;
            return;
        }

        try {
            // Destroy existing table if it exists
            if ($.fn.DataTable.isDataTable('#today-orders-table')) {
                $('#today-orders-table').DataTable().destroy();
                $('#today-orders-table').off();
            }

            // Force table width before initialization
            $('#today-orders-table').css('width', '100%');
            $('.table-responsive').css('width', '100%');

            const todayDate = getTodayDate();

            todayOrdersTable = $('#today-orders-table').DataTable({
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
                        // Auto-filter for today's date
                        d.date_from_filter = todayDate;
                        d.date_to_filter = todayDate;
                        
                        // Add global client filter if available
                        const globalClientFilter = getCurrentClientFilter ? getCurrentClientFilter() : '';
                        d.client_filter = globalClientFilter || '';
                        
                        // Clear other filters to avoid interference from other tabs
                        d.status_filter = '';
                        d.contact_filter = '';

                        // Add CSRF token
                        d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                        
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Error loading today data:', error, xhr.responseText);
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
                order: [], // No client-side ordering - let server handle the sorting
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
                    processing: `
                        <div class="datatable-loading-overlay">
                            <div class="datatable-loading-content">
                                <div class="spinner-border text-primary me-2" role="status">
                                    <span class="visually-hidden"><?= lang('App.loading') ?>...</span>
                                </div>
                                <span class="datatable-loading-text"><?= lang('App.loading_orders') ?>...</span>
                            </div>
                        </div>
                    `,
                    lengthMenu: "<?= lang('App.show') ?> _MENU_ <?= lang('App.entries') ?>",
                    zeroRecords: `
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="calendar" class="icon-lg text-muted"></i>
                            </div>
                            <h5 class="text-muted">No orders scheduled for today</h5>
                            <p class="text-muted mb-3">Schedule a new order to get started.</p>
                            <button onclick="openNewOrderModal()" class="btn btn-primary btn-sm">
                                <i data-feather="plus-circle" class="icon-sm me-1"></i> <?= lang('App.add_sales_order') ?>
                            </button>
                        </div>
                    `,
                    info: "<?= lang('App.showing') ?> _START_ <?= lang('App.to') ?> _END_ <?= lang('App.of') ?> _TOTAL_ <?= lang('App.orders') ?>",
                    infoEmpty: "<?= lang('App.no_orders_scheduled_today') ?>",
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
                        $('#today-orders-table').css('width', '100%');
                    }, 50);

                    setTimeout(() => {
                        table.columns.adjust().draw();
                        $('#today-orders-table').css('width', '100%');
                    }, 150);

                    setTimeout(() => {
                        table.columns.adjust();
                        $('#today-orders-table').css('width', '100%');
                        $('.dataTables_wrapper').css('width', '100%');
                    }, 300);

                    // Update order count in title - try multiple sources for count
                    let count = 0;
                    if (json.recordsFiltered !== undefined) {
                        count = json.recordsFiltered;
                    } else if (json.recordsTotal !== undefined) {
                        count = json.recordsTotal;
                    } else if (json.data && Array.isArray(json.data)) {
                        count = json.data.length;
                    }
                    
                    updateTodayOrderCount(count);
                },
                drawCallback: function(settings) {
                    // Update order count in title
                    const api = this.api();
                    const pageInfo = api.page.info();
                    
                    
                    // Try multiple sources for the count
                    let count = 0;
                    if (pageInfo.recordsDisplay !== undefined) {
                        count = pageInfo.recordsDisplay;
                    } else if (pageInfo.recordsFiltered !== undefined) {
                        count = pageInfo.recordsFiltered;
                    } else if (pageInfo.recordsTotal !== undefined) {
                        count = pageInfo.recordsTotal;
                    } else {
                        // Fallback: count visible rows
                        count = api.rows({ page: 'all' }).count();
                    }
                    
                    updateTodayOrderCount(count);

                    // Initialize Feather icons after each redraw
                    if (typeof feather !== 'undefined') {
                        setTimeout(() => {
                            feather.replace();
                        }, 50);
                    }

                    // Initialize tooltips
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.map(function (tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    }

                    // Status change event listeners are handled globally in index.php

                    // Add row click event listeners
                    $('#today-orders-table tbody tr').off('click').on('click', function(e) {
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
                        const table = $('#today-orders-table').DataTable();
                        const rowData = table.row(this).data();
                        if (rowData && rowData.id) {
                            window.location.href = `<?= base_url('sales_orders/view/') ?>${rowData.id}`;
                        }
                    });

                    // Ensure table uses full width on every draw
                    $('#today-orders-table').css('width', '100%');
                    $('.dataTables_wrapper').css('width', '100%');
                }
            });

            isInitializing = false;

        } catch (error) {
            console.error('❌ Error initializing Today DataTable:', error);
            isInitializing = false;
        }
    }

    // Refresh function
    $('#refreshTodayTable').on('click', function() {
        if (todayOrdersTable) {
            todayOrdersTable.ajax.reload(null, false);
            showToast('success', '<?= lang('App.table_refreshed') ?>');
            // Reset timer when manually refreshed
            resetAutoRefreshTimer();
        }
    });

    // Auto-refresh timer variables
    let autoRefreshInterval;
    let countdownInterval;
    let timeRemaining = 60;
    let isPaused = false;

    // Auto-refresh timer functions
    function startAutoRefreshTimer() {
        if (isPaused) return;
        
        timeRemaining = 60;
        updateTimerDisplay();
        
        countdownInterval = setInterval(function() {
            if (isPaused) return;
            
            timeRemaining--;
            updateTimerDisplay();
            
            if (timeRemaining <= 0) {
                refreshTableAndResetTimer();
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        $('#timerDisplay').text(timeRemaining);
        
        // Change color based on state
        const timerElement = $('#autoRefreshTimer');
        timerElement.removeClass('refreshing paused');
        
        if (isPaused) {
            timerElement.addClass('paused');
        } else if (timeRemaining <= 10) {
            timerElement.addClass('refreshing');
        }
    }

    function pauseResumeTimer() {
        isPaused = !isPaused;
        
        if (isPaused) {
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
            updateTimerDisplay();
        } else {
            startAutoRefreshTimer();
        }
    }

    // Click handler for timer
    $('#autoRefreshTimer').on('click', function() {
        pauseResumeTimer();
    });

    function refreshTableAndResetTimer() {
        if (todayOrdersTable) {
            todayOrdersTable.ajax.reload(null, false);
        }
        resetAutoRefreshTimer();
    }

    function resetAutoRefreshTimer() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        $('#autoRefreshTimer').removeClass('refreshing');
        startAutoRefreshTimer();
    }

    // Start auto-refresh timer
    function initializeAutoRefresh() {
        startAutoRefreshTimer();
    }

    // Global functions - event listeners are handled globally
    window.openNewOrderModal = function() {
        try {
            if (typeof openModalForNewOrder === 'function') {
                openModalForNewOrder();
            } else if (typeof window.openModalForNewOrder === 'function') {
                window.openModalForNewOrder();
            } else {
                console.warn('openModalForNewOrder function not available');
                showToast('info', 'Please use the main Add Order button');
            }
        } catch (error) {
            console.error('Error in openNewOrderModal:', error);
            showToast('error', 'Error opening order form');
        }
    };

    // Toast function
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
                style: {
                    background: colors[type] || colors.info,
                }
            }).showToast();
        }
    }

    // Initialize DataTable with delay
    setTimeout(() => {
        initializeTodayDataTable();
    }, 500);

    // Initialize auto-refresh timer after DataTable is ready
    setTimeout(() => {
        initializeAutoRefresh();
    }, 1000);

    // Setup visibility observer to detect when today content becomes active
    setTimeout(() => {
        setupVisibilityObserver();
    }, 1500);

    // Pause/Resume timer based on page visibility
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Pause timer when page is not visible (but remember if it was manually paused)
            if (countdownInterval && !isPaused) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
        } else {
            // Reset and restart timer when page becomes visible (only if not manually paused)
            if (!isPaused) {
                resetAutoRefreshTimer();
            }
        }
    });

    // Reset timer when switching to Today's Orders tab within the application
    // Listen for tab switching events (Bootstrap tab events)
    $(document).on('shown.bs.tab', 'a[data-bs-toggle="tab"]', function(e) {
        const targetTab = $(e.target).attr('href') || $(e.target).attr('data-bs-target');
        
        // Check if switching to Today's Orders tab
        if (targetTab && (targetTab.includes('today') || targetTab.includes('Today'))) {
            if (!isPaused) {
                resetAutoRefreshTimer();
            }
        }
    });

    // Alternative method: Use Intersection Observer to detect when today content becomes visible
    function setupVisibilityObserver() {
        const todayContent = document.querySelector('#today-orders-table');
        if (todayContent && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting && entry.intersectionRatio > 0.5) {
                        // Today content is significantly visible
                        if (!isPaused && timeRemaining < 60) {
                            setTimeout(() => {
                                resetAutoRefreshTimer();
                            }, 500);
                        }
                    }
                });
            }, {
                threshold: [0.5] // Trigger when 50% of the element is visible
            });
            
            observer.observe(todayContent);
        }
    }

    // Additional Feather icons initialization
    setTimeout(() => {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 1200);

    // Expose showDuplicateOrdersModal globally for reuse in other views
    window.showDuplicateOrdersModal = showDuplicateOrdersModal;

});
</script>
