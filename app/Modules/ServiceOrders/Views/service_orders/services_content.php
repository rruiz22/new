<?php include(__DIR__ . '/shared_styles.php'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="flex-grow-1">
                    <h4 class="service-orders-card-title mb-0"><?= lang('App.services_management') ?></h4>
                </div>
                <div class="flex-shrink-0">
                    <button id="refreshServicesTable" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                    <button class="btn btn-success btn-sm" onclick="addService()">
                        <i data-feather="plus" class="icon-sm me-1"></i>
                        Add Service
                    </button>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card-body border-bottom">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="servicesClientFilter" class="form-label"><?= lang('App.filter_by_client') ?></label>
                        <select class="form-select" id="servicesClientFilter">
                            <option value=""><?= lang('App.all_clients') ?></option>
                            <!-- Options loaded via AJAX -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="servicesStatusFilter" class="form-label"><?= lang('App.filter_by_status') ?></label>
                        <select class="form-select" id="servicesStatusFilter">
                            <option value=""><?= lang('App.all_status') ?></option>
                            <option value="active"><?= lang('App.active') ?></option>
                            <option value="inactive"><?= lang('App.inactive') ?></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="servicesOrdersFilter" class="form-label">Show in Orders</label>
                        <select class="form-select" id="servicesOrdersFilter">
                            <option value="">All Services</option>
                            <option value="1">Visible in Orders</option>
                            <option value="0">Hidden from Orders</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary me-2" id="applyServicesFilters">
                            <i data-feather="filter" class="icon-sm me-1"></i>
                            <?= lang('App.apply_filters') ?>
                        </button>
                        <button type="button" class="btn btn-secondary" id="clearServicesFilters">
                            <i data-feather="x" class="icon-sm me-1"></i>
                            <?= lang('App.clear_filters') ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="services-table" class="table table-bordered table-hover align-middle mb-0 service-orders-table dt-responsive">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><?= lang('App.service_name') ?></th>
                                <th scope="col"><?= lang('App.service_description') ?></th>
                                <th scope="col"><?= lang('App.service_price') ?></th>
                                <th scope="col"><?= lang('App.client') ?></th>
                                <th scope="col"><?= lang('App.service_status') ?></th>
                                <th scope="col"><?= lang('App.show_in_service_orders') ?></th>
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

<script>
// Variables globales para la gestión de servicios
let servicesTable;

// Inicializar DataTable de servicios cuando se cargue la pestaña
document.addEventListener('DOMContentLoaded', function() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.warn('⚠️ jQuery not available for services table initialization');
        return;
    }

    // Inicializar la tabla solo si estamos en la pestaña de servicios
    if ($('#services-tab').hasClass('active')) {
        initServicesTable();
    }

    // Inicializar cuando se active la pestaña
    $('a[data-bs-toggle="tab"][href="#services-tab"]').on('shown.bs.tab', function() {
        if (!servicesTable) {
            initServicesTable();
        }
    });
});

function initServicesTable() {
    // Check if jQuery and DataTables are available
    if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
        console.error('❌ jQuery or DataTables not available for services table');
        return;
    }

    if ($.fn.DataTable.isDataTable('#services-table')) {
        $('#services-table').DataTable().destroy();
    }

    servicesTable = $('#services-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '<?= base_url('service_orders_services/list_data') ?>',
            type: 'POST',
            data: function(d) {
                d.client_filter = $('#servicesClientFilter').val();
                d.status_filter = $('#servicesStatusFilter').val();
                d.orders_filter = $('#servicesOrdersFilter').val();
            }
        },
        columns: [
            { data: 'service_name' },
            { 
                data: 'service_description',
                render: function(data) {
                    return data ? (data.length > 50 ? data.substring(0, 50) + '...' : data) : '';
                }
            },
            { 
                data: 'service_price',
                render: function(data) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            { 
                data: 'client_name',
                defaultContent: '<span class="text-muted">General</span>'
            },
            { 
                data: 'service_status',
                render: function(data) {
                    return data === 'active' ? 
                        '<span class="badge bg-success">Active</span>' : 
                        '<span class="badge bg-danger">Inactive</span>';
                }
            },
            { 
                data: 'show_in_orders',
                render: function(data) {
                    return data == 1 ? 
                        '<span class="badge bg-info">Visible</span>' : 
                        '<span class="badge bg-secondary">Hidden</span>';
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="editService(${row.id})">
                                    <i data-feather="edit-2" class="icon-sm me-1"></i> Edit
                                </a></li>
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="toggleServiceStatus(${row.id})">
                                    <i data-feather="toggle-right" class="icon-sm me-1"></i> Toggle Status
                                </a></li>
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="toggleShowInOrders(${row.id})">
                                    <i data-feather="eye" class="icon-sm me-1"></i> Toggle Visibility
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteService(${row.id})">
                                    <i data-feather="trash-2" class="icon-sm me-1"></i> Delete
                                </a></li>
                            </ul>
                        </div>
                    `;
                },
                orderable: false
            }
        ],
        language: {
            processing: "Loading services...",
            emptyTable: "No services found",
            search: "Search services:"
        },
        drawCallback: function() {
            feather.replace();
        }
    });

    // Make table globally accessible
    window.servicesTable = servicesTable;

    // Event listeners para filtros
    $('#applyServicesFilters').on('click', function() {
        servicesTable.ajax.reload();
    });

    $('#clearServicesFilters').on('click', function() {
        $('#servicesClientFilter, #servicesStatusFilter, #servicesOrdersFilter').val('');
        servicesTable.ajax.reload();
    });

    $('#refreshServicesTable').on('click', function() {
        servicesTable.ajax.reload();
    });
}

function addService() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('❌ jQuery not available for service modal');
        alert('Unable to load service form - jQuery not available');
        return;
    }

    // Show loading modal
    $('#serviceModal .modal-content').html(`
        <div class="modal-header">
            <h5 class="modal-title">
                <i data-feather="plus" class="icon-sm me-2"></i>
                Loading Form...
            </h5>
        </div>
        <div class="modal-body">
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Preparing new service form...</p>
            </div>
        </div>
    `);
    $('#serviceModal').modal('show');

    // Initialize feather icons in loading state
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    $.get('<?= base_url('service_orders_services/modal_form') ?>')
        .done(function(response) {

            $('#serviceModal .modal-content').html(response);
        })
        .fail(function(xhr, status, error) {
            console.error('❌ Error loading service form:', error);
            $('#serviceModal').modal('hide');

            const errorMessage = xhr.responseJSON?.message || 'Error loading service form';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            } else {
                alert(errorMessage);
            }
        });
}

function editService(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('❌ jQuery not available for service modal');
        alert('Unable to load service form - jQuery not available');
        return;
    }

    // Show loading modal
    $('#serviceModal .modal-content').html(`
        <div class="modal-header">
            <h5 class="modal-title">
                <i data-feather="edit-2" class="icon-sm me-2"></i>
                Loading Service...
            </h5>
        </div>
        <div class="modal-body">
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading service data...</p>
            </div>
        </div>
    `);
    $('#serviceModal').modal('show');

    // Initialize feather icons in loading state
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    $.get('<?= base_url('service_orders_services/modal_form') ?>', { id: id })
        .done(function(response) {

            $('#serviceModal .modal-content').html(response);
        })
        .fail(function(xhr, status, error) {
            console.error('❌ Error loading service:', error);
            $('#serviceModal').modal('hide');

            const errorMessage = xhr.responseJSON?.message || 'Error loading service form';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            } else {
                alert(errorMessage);
            }
        });
}

function deleteService(id) {
    // Check if required libraries are available
    if (typeof Swal === 'undefined') {
        if (confirm('Are you sure you want to delete this service?')) {
            proceedWithDeleteService(id);
        }
        return;
    }

    Swal.fire({
        title: 'Delete Service?',
        text: 'Are you sure you want to delete this service?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            proceedWithDeleteService(id);
        }
    });
}

function proceedWithDeleteService(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined' || typeof $.ajax !== 'function') {
        console.error('❌ jQuery not available for service deletion');
        alert('Unable to delete service - jQuery not available');
        return;
    }

    $.ajax({
        url: '<?= base_url('service_orders_services/delete') ?>/' + id,
        type: 'POST',
        data: {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        success: function(response) {
            console.log('✅ Service deletion response:', response);
            
            if (response.success) {
                // Refresh services table
                if (servicesTable) {
                    console.log('🔄 Refreshing services table after deletion...');
                    servicesTable.ajax.reload();
                }
                
                // Show success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message || 'Service deleted successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert('Service deleted successfully');
                }
            } else {
                // Show error message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to delete service'
                    });
                } else {
                    alert('Error: ' + response.message);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error deleting service:', error);
            console.error('XHR:', xhr);
            
            let errorMessage = 'Error deleting service';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 404) {
                errorMessage = 'Service deletion endpoint not found';
            }
            
            // Show error message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            } else {
                alert(errorMessage);
            }
        }
    });
}

function toggleServiceStatus(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined' || typeof $.ajax !== 'function') {
        console.error('❌ jQuery not available for AJAX request');
        alert('Unable to update service status - jQuery not available');
        return;
    }

    $.ajax({
        url: '<?= base_url('service_orders_services/toggle_status') ?>/' + id,
        type: 'POST',
        data: {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        success: function(response) {
            if (response.success) {
                if (servicesTable) {
                    servicesTable.ajax.reload();
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Updated!', 'Service status updated successfully', 'success');
                } else {
                    alert('Service status updated successfully');
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', response.message, 'error');
                } else {
                    alert('Error: ' + response.message);
                }
            }
        },
        error: function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'Error updating service status', 'error');
            } else {
                alert('Error updating service status');
            }
        }
    });
}

function toggleShowInOrders(id) {
    // Check if jQuery is available
    if (typeof $ === 'undefined' || typeof $.ajax !== 'function') {
        console.error('❌ jQuery not available for AJAX request');
        alert('Unable to update service visibility - jQuery not available');
        return;
    }

    $.ajax({
        url: '<?= base_url('service_orders_services/toggle_show_in_orders') ?>/' + id,
        type: 'POST',
        data: {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        success: function(response) {
            if (response.success) {
                if (servicesTable) {
                    servicesTable.ajax.reload();
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Updated!', 'Service visibility updated successfully', 'success');
                } else {
                    alert('Service visibility updated successfully');
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', response.message, 'error');
                } else {
                    alert('Error: ' + response.message);
                }
            }
        },
        error: function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'Error updating service visibility', 'error');
            } else {
                alert('Error updating service visibility');
            }
        }
    });
}
</script>

<!-- Los estilos ahora están en shared_styles.php --> 
