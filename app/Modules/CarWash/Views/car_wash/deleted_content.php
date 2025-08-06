<?php
use App\Helpers\DeviceHelper;
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-trash-alt text-danger me-2"></i>
                            <?= lang('App.deleted_orders') ?>
                        </h5>
                    </div>
                    <div class="col-auto">
                        <small class="text-muted"><?= lang('App.deleted_orders_desc') ?></small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="deletedOrdersTable">
                        <thead>
                            <tr>
                                <th><?= lang('App.order_number') ?></th>
                                <th><?= lang('App.client') ?></th>
                                <th><?= lang('App.vehicle') ?></th>
                                <th><?= lang('App.date') ?></th>
                                <th><?= lang('App.deleted_date') ?></th>
                                <th><?= lang('App.deleted_by') ?></th>
                                <th><?= lang('App.total') ?></th>
                                <th><?= lang('App.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    console.log('🗑️ Initializing deleted orders tab...');
    // Initialize DataTable only once
    if ($.fn.DataTable.isDataTable('#deletedOrdersTable')) {
        $('#deletedOrdersTable').DataTable().destroy();
    }
    
    var deletedTable = $('#deletedOrdersTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?= base_url('car_wash/getDeletedOrders') ?>',
            type: 'GET',
            timeout: 30000, // 30 seconds timeout
            dataSrc: function(json) {
                console.log('📊 Deleted orders response:', json);
                
                // Update deleted orders count
                if (json && json.success && json.data && Array.isArray(json.data)) {
                $('#deletedOrdersCount').text('(' + json.data.length + ')');
                    console.log('✅ Processed ' + json.data.length + ' deleted orders');
                return json.data;
                } else {
                    $('#deletedOrdersCount').text('(0)');
                    console.log('⚠️ No deleted orders data or invalid response');
                    return [];
                }
            },
            error: function(xhr, error, thrown) {
                console.error('Error loading deleted orders:', error);
                console.error('XHR Status:', xhr.status);
                console.error('XHR Response:', xhr.responseText);
                console.error('Error Type:', error);
                console.error('Thrown:', thrown);
                
                // Update count to 0 on error
                $('#deletedOrdersCount').text('(0)');
                
                // Handle different error types
                if (error === 'abort') {
                    console.log('Request was aborted - likely due to tab switching');
                    return; // Don't show toast for aborted requests
                }
                
                // Show error message to user for other errors
                if (typeof showToast === 'function') {
                    var errorMessage = 'Error loading deleted orders';
                    if (xhr.status === 0) {
                        errorMessage += ': Connection failed';
                    } else if (xhr.status === 404) {
                        errorMessage += ': Endpoint not found';
                    } else if (xhr.status === 500) {
                        errorMessage += ': Server error';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage += ': ' + xhr.responseJSON.message;
                    } else {
                        errorMessage += ': ' + error;
                    }
                    showToast('error', errorMessage);
                }
            }
        },
        columns: [
            {
                data: 'order_number',
                render: function(data, type, row) {
                    return '<strong class="text-muted">' + (data || 'CW-' + String(row.id).padStart(5, '0')) + '</strong>';
                }
            },
            {
                data: 'client_name',
                render: function(data, type, row) {
                    if (data) {
                        return '<div class="d-flex align-items-center">' +
                               '<div class="avatar-xs bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">' +
                               '<small class="text-white fw-bold">' + data.charAt(0).toUpperCase() + '</small>' +
                               '</div>' +
                               '<span class="text-muted">' + data + '</span>' +
                               '</div>';
                    }
                    return '<span class="text-muted">N/A</span>';
                }
            },
            {
                data: 'vehicle',
                render: function(data, type, row) {
                    return '<div class="text-muted small">' + (data || 'N/A') + '</div>';
                }
            },
            {
                data: 'date',
                render: function(data, type, row) {
                    if (data) {
                        var date = new Date(data);
                        return '<span class="badge bg-light text-dark">' + date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        }) + '</span>';
                    }
                    return '<span class="text-muted">N/A</span>';
                }
            },
            {
                data: 'deleted_at',
                render: function(data, type, row) {
                    if (data) {
                        var date = new Date(data);
                        return '<small class="text-muted">' + date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        }) + '</small>';
                    }
                    return '<span class="text-muted">N/A</span>';
                }
            },
            {
                data: 'deleted_by_name',
                render: function(data, type, row) {
                    if (data) {
                        return '<div class="d-flex align-items-center">' +
                               '<div class="avatar-xs bg-warning rounded-circle d-flex align-items-center justify-content-center me-2">' +
                               '<small class="text-white fw-bold">' + data.charAt(0).toUpperCase() + '</small>' +
                               '</div>' +
                               '<small class="text-muted">' + data + '</small>' +
                               '</div>';
                    }
                    return '<small class="text-muted fst-italic">Unknown User</small>';
                }
            },
            {
                data: 'service_price',
                render: function(data, type, row) {
                    return '<span class="text-muted">$' + (data ? parseFloat(data).toFixed(2) : '0.00') + '</span>';
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<div class="btn-group btn-group-sm" role="group">' +
                           '<button type="button" class="btn btn-outline-success restore-btn" data-id="' + row.id + '" onclick="window.restoreCarWashOrder(' + row.id + ')" title="<?= lang('App.restore') ?>">' +
                           '<i class="fas fa-undo"></i>' +
                           '</button>' +
                           '<button type="button" class="btn btn-outline-danger permanent-delete-btn" data-id="' + row.id + '" onclick="window.permanentDeleteCarWashOrder(' + row.id + ')" title="<?= lang('App.permanent_delete') ?>">' +
                           '<i class="fas fa-trash"></i>' +
                           '</button>' +
                           '</div>';
                }
            }
        ],
        order: [[4, 'desc']], // Order by deleted date
        pageLength: 25,
        responsive: true,
        language: {
            emptyTable: '<?= lang('App.no_deleted_orders') ?>',
            info: '<?= lang('App.showing') ?> _START_ <?= lang('App.to') ?> _END_ <?= lang('App.of') ?> _TOTAL_ <?= lang('App.orders') ?>',
            infoEmpty: '<?= lang('App.showing') ?> 0 <?= lang('App.to') ?> 0 <?= lang('App.of') ?> 0 <?= lang('App.orders') ?>',
            infoFiltered: '(filtered from _MAX_ total <?= lang('App.orders') ?>)',
            processing: 'Loading deleted orders...'
        },
        drawCallback: function() {
            // Make table rows clickable
            $('#deletedOrdersTable tbody tr').off('click.carwash').on('click.carwash', function(e) {
                // Don't trigger row click if clicking on action buttons or links
                if ($(e.target).closest('button, a').length > 0) {
                    return;
                }
                
                // Get the order ID from the row data
                var rowData = deletedTable.row(this).data();
                if (rowData) {
                    // For deleted orders, we might not want to navigate to view but instead show info
                    var orderNumber = rowData.order_number || 'CW-' + String(rowData.id).padStart(5, '0');
                    if (typeof showToast === 'function') {
                        showToast('info', 'This is a deleted order: ' + orderNumber);
                    } else {
                        alert('This is a deleted order: ' + orderNumber);
                    }
                }
            });
            
            // Add cursor pointer to rows
            $('#deletedOrdersTable tbody tr').css('cursor', 'pointer');
        }
    });
    
    // Make table globally accessible
    window.deletedOrdersTable = deletedTable;
    
    // Add retry mechanism for failed loads
    window.retryDeletedOrdersLoad = function() {
        console.log('Retrying deleted orders load...');
        if (window.deletedOrdersTable) {
            window.deletedOrdersTable.ajax.reload(null, false);
        }
    };
    
    // Auto-retry once if initial load fails
    var initialLoadFailed = false;
    deletedTable.on('xhr.dt', function ( e, settings, json, xhr ) {
        if (json && json.data) {
            initialLoadFailed = false;
            console.log('✅ Deleted orders loaded successfully:', json.data.length + ' orders');
        }
    });
    
    deletedTable.on('error.dt', function ( e, settings, techNote, message ) {
        console.error('DataTable error:', message);
        if (!initialLoadFailed) {
            initialLoadFailed = true;
            console.log('⚠️ Initial load failed, retrying in 2 seconds...');
            setTimeout(function() {
                window.retryDeletedOrdersLoad();
            }, 2000);
        }
    });
});

// Restore and permanent delete functions are now handled globally in index.php
</script> 