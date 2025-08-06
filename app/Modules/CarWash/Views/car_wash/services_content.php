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
                            <option value="all">Todos los usuarios</option>
                            <option value="staff_only">Solo Staff</option>
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
                            <i class="ri-car-washing-line fs-16"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="serviceModalLabel"><?= lang('App.add_service') ?></h5>
                        <p class="text-muted mb-0 fs-13">Configure service details and pricing</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="serviceForm">
                <div class="modal-body" style="padding: 2rem;">
                    <input type="hidden" id="serviceId" name="id">
                    
                    <div class="row">
                        <!-- Cliente -->
                        <div class="col-md-12 mb-4">
                            <label for="serviceClient" class="form-label fw-semibold">
                                <i class="ri-building-line me-1 text-muted"></i>
                                <?= lang('App.client') ?>
                            </label>
                            <select class="form-select form-select-lg" id="serviceClient" name="client_id" style="border-radius: 8px; border: 2px solid #e9ecef; padding: 0.75rem 1rem;">
                                <option value=""><?= lang('App.global_service') ?></option>
                            </select>
                            <div class="form-text text-muted">
                                <i class="ri-information-line me-1"></i>
                                <?= lang('App.leave_empty_for_global') ?>
                            </div>
                        </div>

                        <!-- Nombre del Servicio -->
                        <div class="col-md-8 mb-4">
                            <label for="serviceName" class="form-label fw-semibold">
                                <i class="ri-service-line me-1 text-muted"></i>
                                <?= lang('App.service_name') ?> 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="serviceName" name="name" required 
                                   style="border-radius: 8px; border: 2px solid #e9ecef; padding: 0.75rem 1rem;"
                                   placeholder="Enter service name">
                        </div>

                        <!-- Color del Servicio -->
                        <div class="col-md-4 mb-4">
                            <label for="serviceColor" class="form-label fw-semibold">
                                <i class="ri-palette-line me-1 text-muted"></i>
                                Service Color
                            </label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color" id="serviceColor" name="color" value="#ffffff" 
                                       style="width: 60px; height: 50px; border-radius: 8px; border: 2px solid #e9ecef; padding: 4px;">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control" id="serviceColorHex" readonly 
                                           style="border-radius: 8px; border: 2px solid #e9ecef; background-color: #f8f9fa;"
                                           value="#ffffff">
                                </div>
                            </div>
                            <div class="form-text text-muted">
                                <i class="ri-information-line me-1"></i>
                                Color for table row highlighting
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="col-md-12 mb-4">
                            <label for="serviceDescription" class="form-label fw-semibold">
                                <i class="ri-file-text-line me-1 text-muted"></i>
                                <?= lang('App.description') ?>
                            </label>
                            <textarea class="form-control" id="serviceDescription" name="description" rows="4" 
                                      style="border-radius: 8px; border: 2px solid #e9ecef; padding: 0.75rem 1rem;"
                                      placeholder="Enter service description..."></textarea>
                        </div>

                        <!-- Precio -->
                        <div class="col-md-12 mb-4">
                            <label for="servicePrice" class="form-label fw-semibold">
                                <i class="ri-money-dollar-circle-line me-1 text-muted"></i>
                                <?= lang('App.price') ?> 
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" style="border-radius: 8px 0 0 8px; border: 2px solid #e9ecef; border-right: none; background-color: #f8f9fa;">
                                    <i class="ri-money-dollar-circle-line text-success"></i>
                                </span>
                                <input type="number" class="form-control" id="servicePrice" name="price" step="0.01" min="0" required 
                                       style="border-radius: 0 8px 8px 0; border: 2px solid #e9ecef; border-left: none; padding: 0.75rem 1rem;"
                                       placeholder="0.00">
                            </div>
                        </div>

                        <!-- Toggles: Active y Visible -->
                        <div class="col-md-12">
                            <div class="card bg-light border-0" style="border-radius: 12px;">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-3 fw-semibold">
                                        <i class="ri-settings-3-line me-1 text-muted"></i>
                                        Service Settings
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center justify-content-between p-3 bg-white rounded" style="border: 1px solid #e9ecef;">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-3">
                                                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                                            <i class="ri-check-line fs-16"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold"><?= lang('App.active') ?></h6>
                                                        <p class="text-muted mb-0 fs-12">Service is available for use</p>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch form-switch-lg">
                                                    <input class="form-check-input" type="checkbox" id="serviceActive" name="is_active" value="1" checked>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 bg-white rounded" style="border: 1px solid #e9ecef;">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar-xs me-2">
                                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                                            <i class="ri-eye-line fs-16"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">Visibilidad</h6>
                                                        <p class="text-muted mb-0 fs-12">Quién puede ver este servicio</p>
                                                    </div>
                                                </div>
                                                <select class="form-select form-select-sm" id="serviceVisibilityType" name="visibility_type" style="border-radius: 6px;">
                                                    <option value="all">Todos los usuarios</option>
                                                    <option value="staff_only">Solo Staff</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light" style="border-radius: 0 0 16px 16px; border-top: 1px solid #e9ecef; padding: 1.5rem;">
                    <button type="button" class="btn btn-light btn-lg" data-bs-dismiss="modal" style="border-radius: 8px; padding: 0.75rem 1.5rem;">
                        <i class="ri-close-line me-1"></i>
                        <?= lang('App.cancel') ?>
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg" id="saveServiceBtn" style="border-radius: 8px; padding: 0.75rem 2rem;">
                        <i class="ri-save-line me-1"></i>
                        <?= lang('App.save') ?>
                    </button>
                </div>
            </form>
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
#servicesTable {
    width: 100% !important;
}

#servicesTable_wrapper {
    width: 100% !important;
}

#servicesTable thead th {
    width: auto !important;
}

.dataTables_wrapper {
    overflow: visible !important;
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

.link-info {
    color: #299cdb !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-info:hover {
    color: #1f7bb8 !important;
}

.link-danger {
    color: #f06548 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-danger:hover {
    color: #d63384 !important;
}

/* Action buttons styling */
.action-buttons a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    transition: all 0.15s ease;
}

.action-buttons a:hover {
    background-color: rgba(0, 0, 0, 0.05);
    transform: translateY(-1px);
}

.fs-15 {
    font-size: 15px !important;
}

/* Responsive table improvements */
@media (max-width: 991.98px) {
    .table-responsive {
        border: none;
    }
    
    #servicesTable {
        font-size: 0.875rem;
    }
}

/* Badge styling consistency */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.025em;
}

/* Button group styling */
.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.375rem;
}

.btn-group .btn:not(:last-child) {
    margin-right: 0.25rem;
}

/* Table row hover effect */
.table-hover tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.05) !important;
}

/* Compact table styling */
.table > :not(caption) > * > * {
    padding: 0.75rem 0.5rem;
}

/* Auto-refresh indicator */
.auto-refresh-indicator {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1000;
    font-size: 0.75rem;
    color: #64748b;
}

.auto-refresh-indicator.refreshing {
    color: #405189;
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

/* Modal enhancements */
.modal-content {
    overflow: hidden;
}

.modal-header .avatar-sm {
    flex-shrink: 0;
}

.modal-body .card {
    transition: all 0.3s ease;
}

.modal-body .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Form control enhancements */
.form-control:focus,
.form-select:focus {
    border-color: #405189 !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25) !important;
}

.form-control-lg {
    font-size: 1rem;
    font-weight: 500;
}

/* Switch styling */
.form-switch .form-check-input:checked {
    background-color: #405189;
    border-color: #405189;
}

.form-switch .form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(64, 81, 137, 0.25);
}

/* Badge enhancements */
.badge {
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 0.5em 0.75em;
}
</style>

<script>
$(document).ready(function() {
    var servicesTable;
    
    // Initialize DataTable
    initializeServicesTable();
    
    // Load clients for select
    loadClients();
    
    // Initialize color picker functionality
    initializeColorPicker();
    
    // Filter handlers - removed automatic reload on change, will use apply button instead
    
    // Add service button
    $('#addServiceBtn').on('click', function() {
        $('#serviceModal').modal('show');
    });
    
    // Apply filters button
    $('#applyServicesFilters').on('click', function() {
        if (servicesTable) {
            servicesTable.ajax.reload();
        }
    });
    
    // Clear filters button
    $('#clearServicesFilters').on('click', function() {
        $('#servicesClientFilter').val('');
        $('#servicesStatusFilter').val('');
        $('#servicesVisibilityFilter').val('');
        if (servicesTable) {
            servicesTable.ajax.reload();
        }
    });
    
    // Refresh table button
    $('#refreshServicesTable').on('click', function() {
        if (servicesTable) {
            servicesTable.ajax.reload();
        }
    });
    
    // Form submission
    $('#serviceForm').on('submit', function(e) {
        e.preventDefault();
        saveService();
    });
    
    // Edit service
    $(document).on('click', '.edit-service', function(e) {
        e.preventDefault();
        var serviceId = $(this).data('id');
        editService(serviceId);
    });
    
    // Toggle status
    $(document).on('click', '.toggle-status', function(e) {
        e.preventDefault();
        var serviceId = $(this).data('id');
        toggleServiceStatus(serviceId);
    });
    
    // Toggle visibility type
    $(document).on('click', '.toggle-visibility-type', function(e) {
        e.preventDefault();
        var serviceId = $(this).data('id');
        toggleServiceVisibilityType(serviceId);
    });
    
    // Delete service
    $(document).on('click', '.delete-service', function(e) {
        e.preventDefault();
        var serviceId = $(this).data('id');
        deleteService(serviceId);
    });
    
    // Modal events
    $('#serviceModal').on('show.bs.modal', function() {
        if (!$(this).find('#serviceId').val()) {
            resetServiceForm();
        }
    });
    
    function initializeServicesTable() {
        servicesTable = $('#servicesTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('car_wash/services/data') ?>",
                "type": "POST",
                "data": function(d) {
                    d.client_id = $('#servicesClientFilter').val();
                    d.is_active = $('#servicesStatusFilter').val();
                    d.visibility_type = $('#servicesVisibilityFilter').val();
                }
            },
            "pageLength": 25,
            "responsive": {
                "details": {
                    "type": 'column',
                    "target": 'tr'
                }
            },
            "columnDefs": [
                { "responsivePriority": 1, "targets": 0 }, // Client - always visible
                { "responsivePriority": 1, "targets": 1 }, // Service name - always visible
                { "responsivePriority": 1, "targets": -1, "className": "actions-column", "orderable": false, "searchable": false }, // Actions - always visible
                { "responsivePriority": 2, "targets": 4 }, // Price - high priority
                { "responsivePriority": 3, "targets": 3, "className": "text-center", "orderable": false }, // Color - medium priority
                { "responsivePriority": 4, "targets": 2 }, // Description - lower priority
                { "responsivePriority": 5, "targets": 5 }, // Status - lower priority
                { "responsivePriority": 6, "targets": 6 }  // Visibility - lowest priority
            ],
            "language": {
                "url": "<?= base_url('assets/js/datatables-lang/' . (session()->get('locale') ?: 'en') . '.json') ?>",
                "emptyTable": "No services found"
            },
            "drawCallback": function() {
                // Initialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
                
                // Initialize feather icons (if available)
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
                
                // Apply row colors based on service color
                applyRowColors();
            }
        });
    }
    
    function applyRowColors() {
        // Apply colors to table rows based on service color
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
    
    function reinitializeServicesTable() {
        // Destroy existing table if it exists
        if (servicesTable) {
            servicesTable.destroy();
        }
        
        // Clear any existing table content
        $('#servicesTable tbody').empty();
        
        // Reinitialize the table
        initializeServicesTable();
    }
    
    function loadClients() {
        $.ajax({
            url: '<?= base_url('car_wash/services/load-clients') ?>',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var select = $('#serviceClient');
                    var filterSelect = $('#servicesClientFilter');
                    
                    response.clients.forEach(function(client) {
                        select.append('<option value="' + client.id + '">' + client.name + '</option>');
                        filterSelect.append('<option value="' + client.id + '">' + client.name + '</option>');
                    });
                }
            }
        });
    }
    
    function initializeColorPicker() {
        // Sync color picker with hex input
        $('#serviceColor').on('change', function() {
            var color = $(this).val();
            $('#serviceColorHex').val(color);
        });
        
        // Allow manual hex input (optional enhancement)
        $('#serviceColorHex').on('input', function() {
            var hex = $(this).val();
            if (/^#[0-9A-F]{6}$/i.test(hex)) {
                $('#serviceColor').val(hex);
            }
        });
    }
    
    function resetServiceForm() {
        $('#serviceForm')[0].reset();
        $('#serviceId').val('');
        $('#serviceModalLabel').text('<?= lang('App.add_service') ?>');
        $('#saveServiceBtn').html('<i class="ri-save-line me-1"></i><?= lang('App.save') ?>');
        $('#serviceActive').prop('checked', true);
        $('#serviceVisible').prop('checked', true);
        $('#serviceColor').val('#ffffff');
        $('#serviceColorHex').val('#ffffff');
        
        // Clear validation errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    }
    
    function editService(serviceId) {
        $.ajax({
            url: '<?= base_url('car_wash/services/show') ?>/' + serviceId,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var service = response.service;
                    
                    // Populate form fields
                    $('#serviceId').val(service.id);
                    $('#serviceClient').val(service.client_id || '');
                    $('#serviceName').val(service.name);
                    $('#serviceDescription').val(service.description || '');
                    $('#servicePrice').val(service.price);
                    $('#serviceActive').prop('checked', service.is_active == 1);
                    $('#serviceVisible').prop('checked', service.is_visible == 1);
                    
                    // Set color values
                    var color = service.color || '#ffffff';
                    $('#serviceColor').val(color);
                    $('#serviceColorHex').val(color);
                    
                    // Update modal title
                    $('#serviceModalLabel').text('<?= lang('App.edit_service') ?>');
                    $('#saveServiceBtn').html('<i class="ri-save-line me-1"></i><?= lang('App.update') ?>');
                    
                    // Show modal
                    $('#serviceModal').modal('show');
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function() {
                showToast('<?= lang('App.error_occurred') ?>', 'error');
            }
        });
    }
    
    function toggleServiceStatus(serviceId) {
        $.ajax({
            url: '<?= base_url('car_wash/services/toggle-status') ?>/' + serviceId,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    reinitializeServicesTable();
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function() {
                showToast('<?= lang('App.error_occurred') ?>', 'error');
            }
        });
    }
    
    function toggleServiceVisibilityType(serviceId) {
        $.ajax({
            url: '<?= base_url('car_wash/services/toggle-visibility-type') ?>/' + serviceId,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    reinitializeServicesTable();
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function() {
                showToast('<?= lang('App.error_occurred') ?>', 'error');
            }
        });
    }
    
    function deleteService(serviceId, serviceName) {
        Swal.fire({
            title: '<?= lang('App.are_you_sure') ?>',
            text: '<?= lang('App.delete_service_confirmation') ?>: ' + serviceName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?= lang('App.yes_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('car_wash/services/delete') ?>/' + serviceId,
                    type: 'POST',
                    success: function(response) {
                                            if (response.success) {
                        reinitializeServicesTable();
                        showToast(response.message, 'success');
                    } else {
                        showToast(response.message, 'error');
                    }
                    },
                    error: function() {
                        showToast('<?= lang('App.error_occurred') ?>', 'error');
                    }
                });
            }
        });
    }
    
    // Clear form validation errors when modal closes
    $('#serviceModal').on('hidden.bs.modal', function() {
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    });
    
    // Toast function
    function showToast(message, type) {
        // Create toast element
        var toastId = 'toast-' + Date.now();
        var bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
        var icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        
        var toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="${icon} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Add to toast container or create one
        var toastContainer = $('.toast-container');
        if (toastContainer.length === 0) {
            $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
            toastContainer = $('.toast-container');
        }
        
        toastContainer.append(toastHtml);
        
        // Show toast
        var toastElement = new bootstrap.Toast(document.getElementById(toastId));
        toastElement.show();
        
        // Remove after hiding
        $('#' + toastId).on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }
    
    function saveService() {
        var formData = $('#serviceForm').serialize();
        var serviceId = $('#serviceId').val();
        var url = serviceId ? '<?= base_url('car_wash/services/update') ?>/' + serviceId : '<?= base_url('car_wash/services/store') ?>';
        
        // Clear previous validation errors
        $('.form-control').removeClass('is-invalid');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('#saveServiceBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <?= lang('App.saving') ?>');
            },
            success: function(response) {
                if (response.success) {
                    $('#serviceModal').modal('hide');
                    // Force complete table reinitialization to ensure all columns are present
                    reinitializeServicesTable();
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'error');
                    if (response.errors) {
                        // Handle validation errors
                        Object.keys(response.errors).forEach(function(field) {
                            var fieldElement = $('#service' + field.charAt(0).toUpperCase() + field.slice(1));
                            fieldElement.addClass('is-invalid');
                            // Add error message
                            var errorDiv = '<div class="invalid-feedback">' + response.errors[field] + '</div>';
                            fieldElement.parent().append(errorDiv);
                        });
                    }
                }
            },
            error: function() {
                showToast('<?= lang('App.error_occurred') ?>', 'error');
            },
            complete: function() {
                $('#saveServiceBtn').prop('disabled', false).html('<?= lang('App.save') ?>');
            }
        });
    }
});
</script> 