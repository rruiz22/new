<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 16px;">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center fw-bold"><?= lang('App.services') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshServicesTable" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                    <button id="addServiceBtn" class="btn btn-primary">
                        <i data-feather="plus" class="icon-sm me-1"></i>
                        <?= lang('App.add_service') ?>
                    </button>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card-body border-bottom">
                <div class="row g-3">
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <label class="form-label"><?= lang('App.filter_by_client') ?></label>
                        <select id="servicesClientFilter" class="form-select">
                            <option value=""><?= lang('App.all_clients') ?></option>
                            <!-- Las opciones se cargarán via AJAX -->
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <label class="form-label"><?= lang('App.filter_by_status') ?></label>
                        <select id="servicesStatusFilter" class="form-select">
                            <option value=""><?= lang('App.all_status') ?></option>
                            <option value="1"><?= lang('App.active') ?></option>
                            <option value="0"><?= lang('App.inactive') ?></option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <label class="form-label"><?= lang('App.visibility') ?></label>
                        <select id="servicesVisibilityFilter" class="form-select">
                            <option value=""><?= lang('App.all') ?></option>
                            <option value="1">Visible</option>
                            <option value="0">Hidden</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-6 col-md-6">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button id="applyServicesFilters" class="btn btn-primary">
                                <i data-feather="filter" class="icon-sm me-1"></i>
                            </button>
                            <button id="clearServicesFilters" class="btn btn-secondary">
                                <i data-feather="x" class="icon-sm me-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table id="servicesTable" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th scope="col"><?= lang('App.client') ?></th>
                            <th scope="col"><?= lang('App.service_name') ?></th>
                            <th scope="col"><?= lang('App.description') ?></th>
                            <th scope="col" class="text-center">Color</th>
                            <th scope="col"><?= lang('App.price') ?></th>
                            <th scope="col"><?= lang('App.status') ?></th>
                            <th scope="col">Visibilidad</th>
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

<!-- Service Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
            <div class="modal-header bg-light" style="border-radius: 16px 16px 0 0; border-bottom: 1px solid #e9ecef; padding: 1.5rem;">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="ri-tools-line fs-16"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="serviceModalLabel"><?= lang('App.add_service') ?></h5>
                        <p class="text-muted mb-0 fs-13">Configure service details and pricing</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <form id="serviceForm">
                    <input type="hidden" id="serviceId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border h-100" style="border-radius: 12px; transition: all 0.3s ease;">
                                <div class="card-header bg-primary-subtle border-0" style="border-radius: 12px 12px 0 0;">
                                    <h6 class="mb-0 text-primary fw-bold">
                                        <i class="ri-information-line me-2"></i>Basic Information
                                    </h6>
                                </div>
                                <div class="card-body" style="padding: 1.5rem;">
                                    <div class="mb-3">
                                        <label for="serviceName" class="form-label fw-semibold">Service Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="serviceName" name="name" required placeholder="Enter service name">
                                        <div class="invalid-feedback">Please provide a service name.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="serviceDescription" class="form-label fw-semibold">Description</label>
                                        <textarea class="form-control" id="serviceDescription" name="description" rows="3" placeholder="Enter service description"></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="servicePrice" class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="servicePrice" name="price" step="0.01" min="0" required placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border h-100" style="border-radius: 12px;">
                                <div class="card-header bg-success-subtle border-0" style="border-radius: 12px 12px 0 0;">
                                    <h6 class="mb-0 text-success fw-bold">
                                        <i class="ri-settings-3-line me-2"></i>Configuration
                                    </h6>
                                </div>
                                <div class="card-body" style="padding: 1.5rem;">
                                    <div class="mb-3">
                                        <label for="serviceClient" class="form-label fw-semibold">Client</label>
                                        <select class="form-select" id="serviceClient" name="client_id">
                                            <option value="">Global (All Clients)</option>
                                            <!-- Opciones cargadas via AJAX -->
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="serviceColor" class="form-label fw-semibold">Service Color</label>
                                        <input type="color" class="form-control form-control-color w-100" id="serviceColor" name="color" value="#007bff" title="Choose service color">
                                        <small class="text-muted">This color will be used to highlight service rows</small>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="serviceActive" name="is_active" checked>
                                                <label class="form-check-label fw-semibold" for="serviceActive">Active</label>
                                                <small class="d-block text-muted">Service is available for selection</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="serviceShowInForm" name="show_in_form" checked>
                                                <label class="form-check-label fw-semibold" for="serviceShowInForm">Show in Form</label>
                                                <small class="d-block text-muted">Display in order forms</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light" style="border-radius: 0 0 16px 16px; padding: 1.5rem; border-top: 1px solid #e9ecef;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="saveServiceBtn">
                    <i class="ri-save-line me-1"></i>Save Service
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

/* Service row color styling */
#servicesTable tbody tr {
    position: relative;
    border-radius: 6px;
    margin-bottom: 2px;
}

#servicesTable tbody tr.colored-row {
    border-left-width: 4px !important;
    border-left-style: solid !important;
}

#servicesTable tbody tr.colored-row:hover {
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Color picker styling */
.form-control-color {
    border: 2px solid #e9ecef !important;
    cursor: pointer;
    transition: all 0.15s ease;
}

.form-control-color:hover {
    border-color: #405189 !important;
    transform: scale(1.05);
}

.form-control-color:focus {
    border-color: #405189 !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25) !important;
}

/* Action Links */
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
</style>

<script>
// Wait for jQuery to be available
function waitForjQueryServices(callback) {
    if (typeof $ !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForjQueryServices(callback);
        }, 100);
    }
}

waitForjQueryServices(function() {
    $(document).ready(function() {
        var servicesTable;
    
    // Define functions first
    function initializeServicesTable() {
        try {
            console.log('Initializing Services Table...');
            
            if (typeof $ === 'undefined') {
                console.error('jQuery is not loaded');
                return;
            }

            // Check if DataTable already exists and destroy it
            if ($.fn.DataTable.isDataTable('#servicesTable')) {
                console.log('Services Table already exists, destroying...');
                $('#servicesTable').DataTable().destroy();
            }

            servicesTable = $('#servicesTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                scrollX: false,
                autoWidth: false,
                ajax: {
                    url: '<?= base_url('recon_orders/services_content') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.ajax = true;
                        d.client_id = $('#servicesClientFilter').val();
                        d.is_active = $('#servicesStatusFilter').val();
                        d.show_in_form = $('#servicesVisibilityFilter').val();
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Services AJAX Error:', error);
                    }
                },
                columnDefs: [
                    { width: "15%", targets: 0, className: "text-center" },
                    { width: "20%", targets: 1, className: "text-center" },
                    { width: "25%", targets: 2, className: "text-center" },
                    { width: "8%", targets: 3, className: "text-center" },
                    { width: "10%", targets: 4, className: "text-center" },
                    { width: "8%", targets: 5, className: "text-center" },
                    { width: "8%", targets: 6, className: "text-center" },
                    { width: "6%", targets: 7, orderable: false, searchable: false, className: "text-center" }
                ],
                columns: [
                    { 
                        data: 'client_name',
                        render: function(data, type, row) {
                            return data || 'Global';
                        }
                    },
                    { data: 'service_name' },
                    { data: 'description' },
                    {
                        data: 'color',
                        render: function(data, type, row) {
                            var color = data || '#007bff';
                            return '<div class="d-flex align-items-center justify-content-center">' +
                                   '<div class="color-indicator" style="width: 24px; height: 24px; border-radius: 50%; background-color: ' + color + '; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>' +
                                   '</div>';
                        },
                        orderable: false
                    },
                    { 
                        data: 'price',
                        render: function(data, type, row) {
                            return '$' + parseFloat(data || 0).toFixed(2);
                        }
                    },
                    { data: 'status' },
                    {
                        data: 'visibility',
                        render: function(data, type, row) {
                            return data == '1' ? '<span class="badge bg-info">Visible</span>' : '<span class="badge bg-secondary">Hidden</span>';
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return '<div class="d-flex justify-content-center gap-2 action-buttons">' +
                                   '<a href="<?= base_url('recon_orders/services/view/') ?>' + data + '" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.view') ?>">' +
                                   '<i class="ri-eye-fill"></i>' +
                                   '</a>' +
                                   '<a href="#" class="link-success fs-15 edit-service-btn" data-id="' + data + '" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.edit') ?>">' +
                                   '<i class="ri-edit-fill"></i>' +
                                   '</a>' +
                                   '<a href="#" class="link-danger fs-15 delete-service-btn" data-id="' + data + '" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.delete') ?>">' +
                                   '<i class="ri-delete-bin-line"></i>' +
                                   '</a>' +
                                   '</div>';
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[1, 'asc']],
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
                    applyRowColors();
                }
            });

            console.log('Services Table initialized successfully');
            
        } catch (error) {
            console.error('Services Table initialization error:', error);
        }
    }

    function applyRowColors() {
        // Apply colors to table rows based on service color
        if (typeof $ === 'undefined' || !servicesTable) {
            console.warn('jQuery or servicesTable not available for applyRowColors');
            return;
        }
        
        $('#servicesTable tbody tr').each(function() {
            var $row = $(this);
            var rowData = servicesTable.row($row).data();
            
            if (rowData && rowData.DT_RowData && rowData.DT_RowData.color) {
                var color = rowData.DT_RowData.color;
                
                // Convert hex to rgba for subtle background
                var rgba = hexToRgba(color, 0.1);
                var borderColor = hexToRgba(color, 0.3);
                
                $row.css({
                    'background-color': rgba,
                    'border-left': '4px solid ' + color,
                    'transition': 'all 0.3s ease'
                });
                
                // Add hover effect
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

    function hexToRgba(hex, alpha) {
        var r = parseInt(hex.slice(1, 3), 16);
        var g = parseInt(hex.slice(3, 5), 16);
        var b = parseInt(hex.slice(5, 7), 16);
        return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
    }

    function loadClients() {
        if (typeof $ === 'undefined') {
            console.error('jQuery not available for loadClients');
            return;
        }
        
        // Check if clients are already loaded
        if ($('#servicesClientFilter option').length > 1) {
            console.log('Clients already loaded, skipping...');
            return;
        }
        
        $.ajax({
            url: '<?= base_url('recon_orders/getClients') ?>',
            method: 'GET',
            success: function(response) {
                if (response.success && response.clients) {
                    var clientSelect = $('#servicesClientFilter');
                    var modalClientSelect = $('#serviceClient');
                    
                    // Clear existing options (except the first one)
                    clientSelect.find('option:not(:first)').remove();
                    modalClientSelect.find('option:not(:first)').remove();
                    
                    response.clients.forEach(function(client) {
                        clientSelect.append('<option value="' + client.id + '">' + client.name + '</option>');
                        modalClientSelect.append('<option value="' + client.id + '">' + client.name + '</option>');
                    });
                }
            },
            error: function() {
                console.error('Error loading clients for services');
            }
        });
    }

    // Filter functionality
    $('#applyServicesFilters').on('click', function() {
        servicesTable.ajax.reload();
    });

    $('#clearServicesFilters').on('click', function() {
        $('#servicesClientFilter').val('');
        $('#servicesStatusFilter').val('');
        $('#servicesVisibilityFilter').val('');
        servicesTable.ajax.reload();
    });

    // Refresh button
    $('#refreshServicesTable').on('click', function() {
        servicesTable.ajax.reload();
    });

    // Add service button
    $('#addServiceBtn').on('click', function() {
        resetServiceForm();
        $('#serviceModalLabel').text('<?= lang('App.add_service') ?>');
        $('#serviceModal').modal('show');
    });

    // Edit service button
    $('#servicesTable').on('click', '.edit-service-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var serviceId = $(this).data('id');
        editReconService(serviceId);
    });

    // Delete service button
    $('#servicesTable').on('click', '.delete-service-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var serviceId = $(this).data('id');
        deleteReconService(serviceId);
    });

    // Save service
    $('#saveServiceBtn').on('click', function() {
        var formData = new FormData(document.getElementById('serviceForm'));
        var serviceId = $('#serviceId').val();
        var url = serviceId ? '<?= base_url('recon_orders/services/update/') ?>' + serviceId : '<?= base_url('recon_orders/services/store') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message || 'Service saved successfully');
                    $('#serviceModal').modal('hide');
                    servicesTable.ajax.reload();
                } else {
                    showToast('error', response.message || 'Failed to save service');
                }
            },
            error: function() {
                showToast('error', 'An error occurred while saving the service');
            }
        });
    });

    function resetServiceForm() {
        document.getElementById('serviceForm').reset();
        $('#serviceId').val('');
        $('#serviceColor').val('#007bff');
        $('#serviceActive').prop('checked', true);
        $('#serviceShowInForm').prop('checked', true);
    }
    
    // Make functions globally available with reinitialize protection
    window.initializeServicesTable = function() {
        console.log('Global initializeServicesTable called');
        if (!window.servicesTableInitialized) {
            initializeServicesTable();
            window.servicesTableInitialized = true;
        } else {
            console.log('Services table already initialized, skipping reinitialize...');
        }
    };
    
    window.loadClients = loadClients;
    
    // Add a force reinitialize function for debugging
    window.forceReinitializeServicesTable = function() {
        console.log('Force reinitializing services table...');
        window.servicesTableInitialized = false;
        if ($.fn.DataTable.isDataTable('#servicesTable')) {
            $('#servicesTable').DataTable().destroy();
        }
        initializeServicesTable();
        window.servicesTableInitialized = true;
    };
    
    // Initialize everything after functions are defined (only if not already initialized)
    if (!window.servicesTableInitialized) {
        console.log('First-time services initialization...');
        initializeServicesTable();
        loadClients();
        window.servicesTableInitialized = true;
    } else {
        console.log('Services already initialized, skipping...');
    }
    });

    // Global functions
    window.editReconService = function(serviceId) {
        // Wait for jQuery before executing
        if (typeof $ === 'undefined') {
            console.error('jQuery not available for editReconService');
            return;
        }
        
        // Load service data and show modal
        $.ajax({
        url: '<?= base_url('recon_orders/services/show/') ?>' + serviceId,
        method: 'GET',
        success: function(response) {
            if (response.success && response.service) {
                var service = response.service;
                $('#serviceId').val(service.id);
                $('#serviceName').val(service.name);
                $('#serviceDescription').val(service.description);
                $('#servicePrice').val(service.price);
                $('#serviceClient').val(service.client_id || '');
                $('#serviceColor').val(service.color || '#007bff');
                $('#serviceActive').prop('checked', service.is_active == 1);
                $('#serviceShowInForm').prop('checked', (service.show_in_form || 1) == 1);
                
                $('#serviceModalLabel').text('Edit Service');
                $('#serviceModal').modal('show');
            } else {
                showToast('error', 'Service not found');
            }
        },
        error: function() {
            showToast('error', 'Error loading service data');
        }
    });
};

window.deleteReconService = function(serviceId) {
    // Wait for jQuery before executing
    if (typeof $ === 'undefined') {
        console.error('jQuery not available for deleteReconService');
        return;
    }
    
    if (confirm('Are you sure you want to delete this service? This action cannot be undone.')) {
        $.ajax({
            url: '<?= base_url('recon_orders/services/delete/') ?>' + serviceId,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Service deleted successfully');
                    // Refresh the services table
                    if (typeof refreshAllReconOrdersData === 'function') {
                        refreshAllReconOrdersData();
                    }
                } else {
                    showToast('error', response.message || 'Failed to delete service');
                }
            },
            error: function() {
                showToast('error', 'An error occurred while deleting the service');
            }
        });
    }
};

// Define showToast function if not available
if (typeof window.showToast === 'undefined') {
    window.showToast = function(type, message) {
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
    };
}
}); // Close waitForjQueryServices
</script> 