<div class="row">
    <div class="col-12">
        <div class="card table-card border-0 shadow-none">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1"><?= lang('App.deleted_orders') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshDeletedTable" class="btn btn-secondary btn-sm">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-container overflow-hidden">
                    <table id="deleted-table" class="table table-borderless table-hover table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center"><?= lang('App.order_id') ?></th>
                                <th class="text-center">Order Date</th>
                                <th class="text-center"><?= lang('App.stock') ?></th>
                                <th class="text-center"><?= lang('App.vehicle') ?></th>
                                <th class="text-center"><?= lang('App.deleted_at') ?></th>
                                <th class="text-center"><?= lang('App.actions') ?></th>
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
/* Use Velzon theme variables for deleted orders styling */
.deleted-orders-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.deleted-orders-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* VIN number styling using theme variables */
.vin-number {
    background: var(--vz-secondary-bg);
    border-radius: var(--vz-border-radius);
    padding: 0.25rem 0.5rem;
    margin: 0.125rem 0;
    border: 1px solid var(--vz-border-color);
}

.vin-number small {
    text-transform: uppercase;
    font-weight: 500;
    color: var(--vz-body-color);
}

/* Stock badge styling */
.stock-number-badge .badge {
    border-radius: calc(var(--vz-border-radius) * 2);
    text-transform: uppercase;
    box-shadow: var(--vz-box-shadow-sm);
    transition: all 0.2s ease;
}

.stock-number-badge .badge:hover {
    transform: translateY(-1px);
    box-shadow: var(--vz-box-shadow);
}

/* Clickable rows */
#deleted-table tbody tr {
    cursor: pointer;
    transition: background-color 0.15s ease;
}

#deleted-table tbody tr:hover {
    background-color: var(--vz-gray-100);
}
</style>

<script>
// Don't execute any code immediately, only define functions
window.initializeDeletedOrdersTable = function() {
    try {
        // Initializing Deleted Orders Table
        
        if (typeof $ === 'undefined') {
            console.error('jQuery is not loaded');
            return;
        }

        // Check if table is already initialized
        if ($.fn.DataTable.isDataTable('#deleted-table')) {
            // Deleted Orders Table already initialized, skipping
            return;
        }

        // Check if the tab pane is visible
        const deletedTab = document.getElementById('deleted');
        if (!deletedTab || !deletedTab.classList.contains('active')) {
            // Deleted tab is not active/visible, waiting
            // If not visible, wait and try again
            setTimeout(() => {
                window.initializeDeletedOrdersTable();
            }, 500);
            return;
        }

        var deletedTable = $('#deleted-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            scrollX: false,
            autoWidth: false,
            retrieve: true,
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

        // Initialize tooltips and feather icons after table is rendered
        setTimeout(function() {
            // Deleted Orders Table: Post-initialization setup complete
        }, 100);

        // Deleted Orders Table initialized successfully
    } catch (error) {
        console.error('Error initializing Deleted Orders Table:', error);
    }
}

// Restore order function
window.restoreOrder = function(orderId, event) {
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
window.forceDeleteOrder = function(orderId, event) {
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