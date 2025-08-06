<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Portal - Services Management<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Services Management<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>Services<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
    <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center"><?= lang('App.services') ?></h4>
                                    <div class="flex-shrink-0">
                    <button id="refreshTable" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal">
                                            <i data-feather="plus" class="icon-sm me-1"></i> <?= lang('App.add_service') ?>
                                        </a>
                                    </div>
                                </div>

            <!-- Filtros -->
            <div class="card-body border-bottom">
                <div class="row g-3">
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <label class="form-label"><?= lang('App.filter_by_client') ?></label>
                        <select id="clientFilter" class="form-select">
                            <option value=""><?= lang('App.all_clients') ?></option>
                            <option value="general">General Services</option>
                            <!-- Los clientes se cargarán dinámicamente -->
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <label class="form-label"><?= lang('App.filter_by_status') ?></label>
                        <select id="statusFilter" class="form-select">
                            <option value=""><?= lang('App.all_status') ?></option>
                            <option value="active"><?= lang('App.active') ?></option>
                            <option value="deactivated">Inactive</option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <label class="form-label">Show in Orders</label>
                        <select id="showInOrdersFilter" class="form-select">
                            <option value="">All Services</option>
                            <option value="1">Shown in Orders</option>
                            <option value="0">Hidden from Orders</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-6 col-md-6">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button id="applyFilters" class="btn btn-primary">
                                <i data-feather="filter" class="icon-sm me-1"></i> <?= lang('App.apply_filters') ?>
                            </button>
                            <button id="clearFilters" class="btn btn-secondary">
                                <i data-feather="x" class="icon-sm me-1"></i> <?= lang('App.clear_filters') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                    <table id="service-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col"><?= lang('App.service') ?></th>
                                                    <th scope="col"><?= lang('App.clients') ?></th>
                                                    <th scope="col"><?= lang('App.price') ?></th>
                                                    <th scope="col"><?= lang('App.notes') ?></th>
                                                    <th scope="col" class="text-center"><?= lang('App.status') ?></th>
                                                    <th scope="col" class="text-center"><?= lang('App.show_in_orders') ?></th>
                                <th scope="col" class="text-center"><?= lang('App.actions') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Los datos se cargarán dinámicamente via DataTables -->
                                            </tbody>
                                        </table>
                                    </div>
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
#service-table {
    width: 100% !important;
}

#service-table_wrapper {
    width: 100% !important;
}

#service-table thead th {
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
    text-decoration: underline !important;
}

/* Service specific styling */
.service-name {
    font-weight: 600;
    color: #405189;
}

.service-description {
    font-size: 0.875rem;
    color: #64748b;
    margin-top: 2px;
}

.price-display {
    font-weight: 600;
    color: #28a745;
    font-size: 1rem;
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-header .flex-shrink-0 {
        margin-top: 0.5rem;
    }
    
    .card-header .flex-shrink-0 .btn {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable for services
    var serviceTable = $('#service-table').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "<?= base_url('sales_orders_services/list_data') ?>",
            "type": "POST",
            "dataSrc": function(json) {
                return json.data || [];
            },
            "error": function(xhr, error, thrown) {
                console.error('DataTables error:', error, thrown);
                console.error('Response:', xhr.responseText);
            }
        },
        "columns": [
            {
                "data": "service_name",
                "render": function(data, type, row) {
                    var html = '<div class="service-name">' + (data || 'N/A') + '</div>';
                    if (row.service_description && row.service_description.trim() !== '') {
                        html += '<div class="service-description">' + 
                               (row.service_description.length > 60 ? 
                                row.service_description.substring(0, 60) + '...' : 
                                row.service_description) + '</div>';
                    }
                    return html;
                }
            },
            {
                "data": "client_name",
                "render": function(data, type, row) {
                    if (!data || data.trim() === '') {
                        return '<span class="badge bg-info">General Service</span>';
                    }
                    return '<span class="text-primary fw-medium">' + data + '</span>';
                }
            },
            {
                "data": "service_price",
                "render": function(data, type, row) {
                    return '<span class="price-display">$' + parseFloat(data || 0).toFixed(2) + '</span>';
                }
            },
            {
                "data": "notes",
                "render": function(data, type, row) {
                    if (!data || data.trim() === '') {
                        return '<span class="text-muted">No notes</span>';
                    }
                    return '<span class="text-dark">' + 
                           (data.length > 40 ? data.substring(0, 40) + '...' : data) + 
                           '</span>';
                }
            },
            {
                "data": "service_status",
                "className": "text-center",
                "render": function(data, type, row) {
                    if (data === 'active') {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                }
            },
            {
                "data": "show_in_orders",
                "className": "text-center",
                "render": function(data, type, row) {
                    if (data == 1) {
                        return '<span class="badge bg-primary">Yes</span>';
                    } else {
                        return '<span class="badge bg-secondary">No</span>';
                    }
                }
            },
            {
                "data": null,
                "className": "text-center",
                "orderable": false,
                "render": function(data, type, row) {
                    return `
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?= base_url('sales_orders_services/view/') ?>${row.id}" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="View Service">
                                <i class="ri-eye-fill"></i>
                            </a>
                            <a href="#" class="link-success fs-15 edit-service" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Service">
                                <i class="ri-edit-fill"></i>
                            </a>
                            <a href="#" class="link-danger fs-15 delete-service" data-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Service">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </div>
                    `;
                }
            }
        ],
        "order": [[0, "asc"]],
        "pageLength": 25,
        "responsive": true,
        "language": {
            "emptyTable": "No services found",
            "processing": "Loading services...",
            "search": "Search services:",
            "lengthMenu": "Show _MENU_ services per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ services",
            "infoEmpty": "No services available",
            "infoFiltered": "(filtered from _MAX_ total services)"
        },
        "drawCallback": function(settings) {
            // Re-initialize feather icons after table redraw
            if (typeof feather !== 'undefined') {
                // Small delay to ensure DOM is ready
                setTimeout(() => {
                    feather.replace();
                }, 50);
            } else {
                console.warn('⚠️ Feather icons library not available');
            }

            // Initialize tooltips
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Ensure table uses full width on every draw
            $('#service-table').css('width', '100%');
            $('.dataTables_wrapper').css('width', '100%');
        }
    });

    // Load clients for filter
    loadClientsForFilter();

    // Filter functionality
    $('#applyFilters').on('click', function() {
        applyFilters();
    });

    $('#clearFilters').on('click', function() {
        clearFilters();
    });

    // Refresh table
    $('#refreshTable').on('click', function() {
        refreshTable();
    });

    // Refresh table data
    function refreshTable() {
        serviceTable.ajax.reload(null, false);
        showToast('info', 'Table refreshed');
    }

    // Load clients for filter dropdown
    function loadClientsForFilter() {
        $.get('<?= base_url('clients/get_clients_json') ?>')
            .done(function(data) {
                if (data && Array.isArray(data)) {
                    var clientFilter = $('#clientFilter');
                    // Keep existing options and add clients
                    data.forEach(function(client) {
                        clientFilter.append('<option value="' + client.id + '">' + client.name + '</option>');
                    });
                }
            })
            .fail(function() {
                console.error('Error loading clients for filter');
            });
    }

    // Apply filters
    function applyFilters() {
        var clientId = $('#clientFilter').val();
        var status = $('#statusFilter').val();
        var showInOrders = $('#showInOrdersFilter').val();

        // Apply custom filtering
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'service-table') {
                return true;
            }

            var rowData = serviceTable.row(dataIndex).data();
            
            // Client filter
            if (clientId && clientId !== '') {
                if (clientId === 'general') {
                    if (rowData.client_name && rowData.client_name.trim() !== '') {
                        return false;
                    }
                } else {
                    if (!rowData.client_name || rowData.client_name.indexOf(clientId) === -1) {
                        // Need to check by client ID, not name
                        // This is a simplified check - in production you'd want to match by ID
                        return false;
                    }
                }
            }

            // Status filter
            if (status && status !== '') {
                if (rowData.service_status !== status) {
                    return false;
                }
            }

            // Show in orders filter
            if (showInOrders && showInOrders !== '') {
                if (rowData.show_in_orders != showInOrders) {
                    return false;
                }
            }

            return true;
        });

        serviceTable.draw();
        showToast('success', 'Filters applied');
    }

    // Clear filters
    function clearFilters() {
        $('#clientFilter').val('');
        $('#statusFilter').val('');
        $('#showInOrdersFilter').val('');
        
        // Remove custom search functions
        $.fn.dataTable.ext.search.pop();
        
        serviceTable.draw();
        showToast('info', 'Filters cleared');
    }

    // Edit service
    $(document).on('click', '.edit-service', function(e) {
        e.preventDefault();
        var serviceId = $(this).data('id');
        
        // Load modal form for editing
        $.get('<?= base_url('sales_orders_services/modal_form') ?>', { id: serviceId })
            .done(function(data) {
                $('#serviceModal .modal-content').html(data);
                $('#serviceModal').modal('show');
            })
            .fail(function() {
                showToast('error', 'Error loading service data');
            });
    });

    // View service
    $(document).on('click', '.view-service', function(e) {
        e.preventDefault();
        var serviceId = $(this).data('id');
        window.location.href = '<?= base_url('sales_orders_services/view') ?>/' + serviceId;
    });

    // Delete service
    $(document).on('click', '.delete-service', function(e) {
        e.preventDefault();
        var serviceId = $(this).data('id');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer. ¿Quieres eliminar este servicio?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Eliminando...',
                    text: 'Por favor espera',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: '<?= base_url('sales_orders_services/delete') ?>/' + serviceId,
                    type: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.close(); // Cerrar el loading
                            showToast('success', 'El servicio ha sido eliminado exitosamente');
                            setTimeout(() => {
                                refreshTable();
                            }, 1000);
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message || 'Error deleting service',
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error deleting service',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Add new service button
    $('.btn[data-bs-target="#serviceModal"]').on('click', function() {
        // Load modal form for new service
        $.get('<?= base_url('sales_orders_services/modal_form') ?>')
            .done(function(data) {
                $('#serviceModal .modal-content').html(data);
                $('#serviceModal').modal('show');
            })
            .fail(function() {
                showToast('error', 'Error loading service form');
            });
    });

    // Handle form submission
    $(document).on('submit', '#serviceForm', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '<?= base_url('sales_orders_services/store') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    $('#serviceModal').modal('hide');
                    refreshTable();
                    showToast('success', response.message || 'Service saved successfully');
                } else {
                    showToast('error', response.message || 'Error saving service');
                }
            },
            error: function() {
                showToast('error', 'Error saving service');
            }
        });
    });

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
});
</script>

<!-- Service Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Content will be loaded via AJAX -->
        </div>
    </div>
</div>
<?= $this->endSection() ?>
