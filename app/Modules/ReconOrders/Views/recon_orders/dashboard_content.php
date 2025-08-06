<!-- Dashboard Content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center"><?= lang('App.dashboard') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshDashboard" class="btn btn-secondary btn-sm">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="dashboard-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
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
#dashboard-table thead th {
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
#dashboard-table tbody tr {
    cursor: pointer !important;
    transition: background-color 0.15s ease !important;
}

#dashboard-table tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.05) !important;
}

#dashboard-table tbody tr:hover td {
    background-color: transparent !important;
}

/* Tooltip styling */
.tooltip-inner {
    max-width: 350px !important;
    text-align: left !important;
}
</style>

<script>
function initializeDashboardTable() {
    try {
        console.log('Initializing Dashboard Table...');
        
        if (typeof $ === 'undefined') {
            console.error('jQuery is not loaded');
            return;
        }

        var dashboardTable = $('#dashboard-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            scrollX: false,
            autoWidth: false,
            ajax: {
                url: '<?= base_url('recon_orders/dashboard_content') ?>',
                type: 'POST',
                data: function(d) {
                    d.ajax = true;
                },
                error: function(xhr, error, thrown) {
                    console.error('Dashboard AJAX Error:', error);
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
                $('#dashboard-table tbody tr').each(function() {
                    var $row = $(this);
                    var rowData = dashboardTable.row($row).data();
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
        $('#refreshDashboard').on('click', function() {
            dashboardTable.ajax.reload();
        });



        // Edit button handler
        $('#dashboard-table').on('click', '.edit-order-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var orderId = $(this).data('id');
            editReconOrder(orderId);
        });

        // Delete button handler
        $('#dashboard-table').on('click', '.delete-order-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var orderId = $(this).data('id');
            deleteReconOrder(orderId);
        });

        // View button handler - prevent row click
        $('#dashboard-table').on('click', 'a[href*="recon_orders/view/"]', function(e) {
            e.stopPropagation();
            // Let the default href behavior happen
        });

        // Make table rows clickable to view order
        $('#dashboard-table tbody').on('click', 'tr', function(e) {
            // Don't trigger if clicking on action buttons
            if ($(e.target).closest('.action-buttons').length > 0) {
                return;
            }
            
            var data = dashboardTable.row(this).data();
            if (data && data.id) {
                window.location.href = '<?= base_url('recon_orders/view/') ?>' + data.id;
            }
        });

        // Add pointer cursor to clickable rows
        $('#dashboard-table tbody').on('mouseenter', 'tr', function() {
            $(this).css('cursor', 'pointer');
        });

        console.log('Dashboard Table initialized successfully');
    } catch (error) {
        console.error('Error initializing Dashboard Table:', error);
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
                
                // Refresh dashboard table
                if (typeof $ !== 'undefined' && $.fn.DataTable && $('#dashboard-table').length) {
                    var dashboardTable = $('#dashboard-table').DataTable();
                    if (dashboardTable) {
                        dashboardTable.ajax.reload();
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

function showToast(type, message) {
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

</script> 