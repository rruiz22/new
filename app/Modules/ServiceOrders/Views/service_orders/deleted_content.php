<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-warning">
            <i data-feather="alert-triangle" class="icon-sm me-2"></i>
            <strong>Deleted Orders:</strong> These orders have been soft-deleted and can be restored or permanently deleted.
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table id="deletedOrdersTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th><?= lang('App.order_id') ?></th>
                        <th><?= lang('App.tag_ro') ?></th>
                        <th><?= lang('App.vehicle') ?></th>
                        <th><?= lang('App.due') ?></th>
                        <th><?= lang('App.status') ?></th>
                        <th>Deleted Date</th>
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

<script>
// NOTE: Table initialization is now handled by index.php
// This prevents conflicts between two different initialization systems

// Wait for both DOM and jQuery to be loaded
function initDeletedOrdersTable() {
    if (typeof $ === 'undefined') {
        setTimeout(initDeletedOrdersTable, 100);
        return;
    }

    // Only initialize if not already initialized from index.php
    if (window.deletedOrdersTable && $.fn.DataTable.isDataTable('#deletedOrdersTable')) {

        return;
    }
    
    // Initialize DataTable for deleted orders
    window.deletedOrdersTable = $('#deletedOrdersTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '<?= base_url('/service_orders/get-deleted-orders') ?>',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            window.ServiceOrdersColumnHelpers.generateOrderIdColumn(),
            window.ServiceOrdersColumnHelpers.generateTagRoColumn(),
            window.ServiceOrdersColumnHelpers.generateVehicleColumn(),
            window.ServiceOrdersColumnHelpers.generateDueColumn(),
            window.ServiceOrdersColumnHelpers.generateStatusColumn(),
            { 
                data: null,
                className: 'due-cell',
                responsivePriority: 6,
                render: function(data, type, row) {
                    if (row.deleted_at) {
                        // Handle both date and datetime formats safely
                        let dateObj;
                        if (row.deleted_at.includes(' ')) {
                            // DateTime format (YYYY-MM-DD HH:MM:SS)
                            dateObj = new Date(row.deleted_at.replace(' ', 'T'));
                        } else {
                            // Date format (YYYY-MM-DD)
                            const dateParts = row.deleted_at.split('-');
                            dateObj = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
                        }
                        const formattedDate = dateObj.toLocaleDateString('en-US', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });
                        return '<div class="due-date">' + formattedDate + '</div>';
                }
                    return '<div class="due-date">N/A</div>';
                }
            },
            {
                data: null,
                className: 'actions-cell',
                responsivePriority: 1,
                orderable: false,
                render: function(data, type, row) {
                    let html = '<div class="action-buttons">';
                    html += '<a href="#" class="action-btn edit" onclick="restoreOrder(' + row.id + ')" data-bs-toggle="tooltip" title="Restore">';
                    html += '<i class="ri-restart-line"></i>';
                    html += '</a>';
                    html += '<a href="<?= base_url() ?>service_orders/view/' + row.id + '" class="action-btn view" data-bs-toggle="tooltip" title="View">';
                    html += '<i class="ri-eye-line"></i>';
                    html += '</a>';
                    html += '<a href="#" class="action-btn delete" onclick="permanentDeleteOrder(' + row.id + ')" data-bs-toggle="tooltip" title="Permanent Delete">';
                    html += '<i class="ri-delete-bin-fill"></i>';
                    html += '</a>';
                    html += '</div>';
                    return html;
                }
            }
        ],
        order: [[5, 'desc']], // Order by deleted date desc
        pageLength: 25,
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        language: {
            search: "Search deleted orders:",
            lengthMenu: "Show _MENU_ orders per page",
            info: "Showing _START_ to _END_ of _TOTAL_ deleted orders",
            infoEmpty: "No deleted orders found",
            infoFiltered: "(filtered from _MAX_ total deleted orders)",
            emptyTable: "No deleted orders found",
            zeroRecords: "No matching deleted orders found"
        },
        drawCallback: window.ServiceOrdersColumnHelpers.standardDrawCallback
    });
}

// DON'T auto-initialize - let index.php handle it
// Initialize when DOM is loaded and jQuery is available
// if (document.readyState === 'loading') {
//     document.addEventListener('DOMContentLoaded', initDeletedOrdersTable);
// } else {
//     initDeletedOrdersTable();
// }

// Event handlers are managed globally in index.php

// Functions for deleted orders actions
window.restoreOrder = function(orderId) {
    Swal.fire({
        title: 'Restore Order?',
        text: 'Are you sure you want to restore this service order?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, restore it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Restoring...',
                text: 'Please wait while we restore the order.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make AJAX request to restore order
            $.post('<?= base_url('service_orders/restore') ?>', {
                id: orderId,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
            .done(function(response) {
                Swal.fire({
                    title: 'Restored!',
                    text: 'The service order has been restored successfully.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Reload the table
                if (window.deletedOrdersTable) {
                    window.deletedOrdersTable.ajax.reload();
                }
            })
            .fail(function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to restore the order. Please try again.',
                    icon: 'error'
                });
            });
        }
        });
    };

window.permanentDeleteOrder = function(orderId) {
    Swal.fire({
        title: 'Permanent Delete?',
        text: 'This will permanently delete the order. This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete permanently!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait while we permanently delete the order.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make AJAX request to permanently delete order
            $.post('<?= base_url('service_orders/permanent-delete') ?>', {
                id: orderId,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
            .done(function(response) {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'The service order has been permanently deleted.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Reload the table
                if (window.deletedOrdersTable) {
                    window.deletedOrdersTable.ajax.reload();
                }
            })
            .fail(function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete the order. Please try again.',
                    icon: 'error'
                });
            });
        }
    });
};
</script> 
