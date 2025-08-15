<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center"><?= lang('App.deleted_orders') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshDeletedTable" class="btn btn-secondary btn-sm">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive" style="position: relative; z-index: 1;">
                    <table id="deleted-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><?= lang('App.order_id') ?></th>
                                <th scope="col">Order Date</th>
                                <th scope="col"><?= lang('App.stock') ?></th>
                                <th scope="col"><?= lang('App.vehicle') ?></th>
                                <th scope="col"><?= lang('App.deleted_at') ?></th>
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
/* Force deleted table to stay within tab content */
#deleted {
    position: relative !important;
    overflow: hidden !important;
}

#deleted .card {
    position: relative !important;
    z-index: 1 !important;
    width: 100% !important;
    max-width: 100% !important;
}

#deleted .card-body {
    position: relative !important;
    overflow: visible !important;
}

#deleted-table_wrapper {
    position: relative !important;
    z-index: 1 !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}

#deleted-table_wrapper .dataTables_length,
#deleted-table_wrapper .dataTables_filter,
#deleted-table_wrapper .dataTables_info,
#deleted-table_wrapper .dataTables_paginate {
    position: relative !important;
    z-index: 2 !important;
}

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
#deleted-table thead th {
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
#deleted-table tbody tr {
    cursor: pointer !important;
    transition: background-color 0.15s ease !important;
}

#deleted-table tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.05) !important;
}

#deleted-table tbody tr:hover td {
    background-color: transparent !important;
}

/* Tooltip styling */
.tooltip-inner {
    max-width: 350px !important;
    text-align: left !important;
}

/* Deleted orders specific styling */
.deleted-orders-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.deleted-orders-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>

<script>
function initializeDeletedOrdersTable() {
    try {
        console.log('Initializing Deleted Orders Table...');
        
        if (typeof $ === 'undefined') {
            console.error('jQuery is not loaded');
            return;
        }

        var deletedTable = $('#deleted-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            scrollX: false,
            autoWidth: false,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            scrollCollapse: true,
            deferRender: true,
            ajax: {
                url: '<?= base_url('recon_orders/deleted_content') ?>',
                type: 'POST',
                data: function(d) {
                    d.ajax = true;
                },
                error: function(xhr, error, thrown) {
                    console.error('Deleted Orders AJAX Error:', error);
                }
            },
            columnDefs: [
                { width: "18%", targets: 0, className: "text-center" }, // Order ID / Client
                { width: "18%", targets: 1, className: "text-center" }, // Order Date / Status
                { width: "18%", targets: 2, className: "text-center" }, // Stock
                { width: "18%", targets: 3, className: "text-center" }, // Vehicle
                { width: "18%", targets: 4, className: "text-center" }, // Deleted At
                { width: "10%", targets: 5, orderable: false, searchable: false, className: "text-center" } // Actions
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
                { data: 'deleted_at' },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return '<div class="d-flex justify-content-center gap-1 deleted-orders-actions">' +
                               '<button class="btn btn-sm btn-outline-success" onclick="restoreOrder(' + data + ', event);" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.restore') ?>">' +
                               '<i data-feather="rotate-ccw" class="icon-sm"></i>' +
                               '</button>' +
                               '<button class="btn btn-sm btn-outline-danger" onclick="forceDeleteOrder(' + data + ', event);" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.permanently_delete') ?>">' +
                               '<i data-feather="x" class="icon-sm"></i>' +
                               '</button>' +
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
                // Re-initialize feather icons for the rendered buttons
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        });

        // Refresh button
        $('#refreshDeletedTable').on('click', function() {
            deletedTable.ajax.reload();
        });

        // Action buttons handler - prevent row click
        $('#deleted-table').on('click', '.deleted-orders-actions button', function(e) {
            e.stopPropagation();
            // Let the onclick behavior happen
        });

        // Make table rows clickable to view order
        $('#deleted-table tbody').on('click', 'tr', function(e) {
            // Don't trigger if clicking on action buttons
            if ($(e.target).closest('.deleted-orders-actions').length > 0) {
                return;
            }
            
            var data = deletedTable.row(this).data();
            if (data && data.id) {
                window.location.href = '<?= base_url('recon_orders/view/') ?>' + data.id;
            }
        });

        // Add pointer cursor to clickable rows
        $('#deleted-table tbody').on('mouseenter', 'tr', function() {
            $(this).css('cursor', 'pointer');
        });

        // Force table to stay within its container after initialization
        setTimeout(function() {
            var wrapper = $('#deleted-table_wrapper');
            var container = $('#deleted .card-body');
            
            if (wrapper.length && container.length) {
                // Ensure wrapper is inside the correct container
                if (!container.find('#deleted-table_wrapper').length) {
                    console.log('Moving table wrapper to correct container...');
                    wrapper.appendTo(container);
                }
                
                // Apply containment styles
                wrapper.css({
                    'position': 'relative',
                    'width': '100%',
                    'max-width': '100%',
                    'overflow': 'visible'
                });
            }
        }, 100);

        console.log('Deleted Orders Table initialized successfully');
    } catch (error) {
        console.error('Error initializing Deleted Orders Table:', error);
    }
}

// Restore order function
function restoreOrder(orderId, event) {
    if (event) event.stopPropagation();
    if (confirm('<?= lang('App.are_you_sure_restore') ?>')) {
        $.ajax({
            url: '<?= base_url('recon_orders/restore/') ?>' + orderId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    alert('<?= lang('App.order_restored_successfully') ?>');
                    // Reload the table
                    $('#deleted-table').DataTable().ajax.reload();
                } else {
                    alert('<?= lang('App.error_occurred') ?>: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Restore error:', error);
                alert('<?= lang('App.error_occurred') ?>');
            }
        });
    }
}

// Force delete order function
function forceDeleteOrder(orderId, event) {
    if (event) event.stopPropagation();
    if (confirm('<?= lang('App.are_you_sure_permanently_delete') ?>')) {
        $.ajax({
            url: '<?= base_url('recon_orders/force_delete/') ?>' + orderId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    alert('<?= lang('App.order_permanently_deleted') ?>');
                    // Reload the table
                    $('#deleted-table').DataTable().ajax.reload();
                } else {
                    alert('<?= lang('App.error_occurred') ?>: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Force delete error:', error);
                alert('<?= lang('App.error_occurred') ?>');
            }
        });
    }
}
</script> 