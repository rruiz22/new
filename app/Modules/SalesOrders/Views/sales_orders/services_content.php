<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center"><?= lang('App.services') ?></h4>
                <div class="flex-shrink-0">
                    <button id="refreshServicesTable" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                    <a href="<?= base_url('sales_orders_services') ?>" class="btn btn-primary">
                        <i class="ri-settings-line me-1"></i> <?= lang('App.manage') ?> <?= lang('App.services') ?>
                    </a>
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
                            <option value="active"><?= lang('App.active') ?></option>
                            <option value="inactive"><?= lang('App.inactive') ?></option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <label class="form-label"><?= lang('App.show_in_sales_orders') ?></label>
                        <select id="servicesOrdersFilter" class="form-select">
                            <option value=""><?= lang('App.all') ?></option>
                            <option value="1"><?= lang('App.yes') ?></option>
                            <option value="0"><?= lang('App.no') ?></option>
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
                <table id="services-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th scope="col"><?= lang('App.service_name') ?></th>
                            <th scope="col"><?= lang('App.description') ?></th>
                            <th scope="col"><?= lang('App.price') ?></th>
                            <th scope="col"><?= lang('App.client') ?></th>
                            <th scope="col"><?= lang('App.status') ?></th>
                            <th scope="col"><?= lang('App.show_in_sales_orders') ?></th>
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
#services-table {
    width: 100% !important;
}

#services-table_wrapper {
    width: 100% !important;
}

#services-table thead th {
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
#services-table thead th {
    text-align: center !important;
}

/* Status indicators */
.status-active {
    background-color: #0ab39c !important;
    color: #fff !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 0.375rem !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
}

.status-inactive {
    background-color: #f06548 !important;
    color: #fff !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 0.375rem !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
}

/* Price styling */
.price-badge {
    background-color: rgba(13, 110, 253, 0.1) !important;
    color: #0d6efd !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 0.375rem !important;
    font-weight: 600 !important;
}

/* Horizontal Scroll Enhancements - CORRECTED VERSION */
/* Remove default table-responsive conflicts */
.dataTables_wrapper {
    overflow: visible !important;
}

/* Force horizontal scroll when needed */
.dataTables_scroll {
    overflow: auto !important;
    clear: both !important;
}

.dataTables_scrollBody {
    overflow: auto !important;
    -webkit-overflow-scrolling: touch !important;
    border-bottom: 1px solid #dee2e6 !important;
}

.dataTables_scrollHead {
    overflow: hidden !important;
}

.dataTables_scrollHeadInner {
    box-sizing: content-box !important;
}

/* Table minimum widths for different screen sizes */
@media (max-width: 1199.98px) {
    #services-table {
        min-width: 900px !important;
    }
}

@media (max-width: 991.98px) {
    #services-table {
        min-width: 800px !important;
    }
    
    .card-header .flex-shrink-0 {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    
    .card-header .btn {
        font-size: 0.875rem !important;
        padding: 0.375rem 0.75rem !important;
    }
}

@media (max-width: 767.98px) {
    #services-table {
        min-width: 700px !important;
    }
    
    .card-body.border-bottom .row.g-3 > [class*="col-"] {
        flex: 0 0 100% !important;
        max-width: 100% !important;
        margin-bottom: 1rem !important;
    }
    
    .card-header h4 {
        font-size: 1.1rem !important;
    }
    
    .card-body.border-bottom .d-flex.gap-2 {
        justify-content: center !important;
    }
    
    .card-body.border-bottom .btn {
        flex: 1 !important;
        max-width: 120px !important;
    }
}

@media (max-width: 575.98px) {
    #services-table {
        min-width: 600px !important;
        font-size: 0.875rem !important;
    }
    
    #services-table th,
    #services-table td {
        padding: 0.5rem 0.25rem !important;
        white-space: nowrap !important;
    }
    
    .card-header {
        padding: 1rem 0.75rem !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 0.75rem !important;
    }
    
    .card-header .card-title {
        text-align: center !important;
        margin-bottom: 0 !important;
    }
    
    .card-header .flex-shrink-0 {
        display: flex !important;
        justify-content: center !important;
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    
    .price-badge {
        font-size: 0.75rem !important;
        padding: 0.25rem 0.5rem !important;
    }
    
    .status-active,
    .status-inactive {
        font-size: 0.7rem !important;
        padding: 0.2rem 0.4rem !important;
    }
}

/* Enhanced touch scrolling */
.dataTables_scrollBody {
    -webkit-overflow-scrolling: touch !important;
    scroll-behavior: smooth !important;
}

/* Custom scrollbar styling */
.dataTables_scrollBody::-webkit-scrollbar {
    height: 12px !important;
    width: 12px !important;
}

.dataTables_scrollBody::-webkit-scrollbar-track {
    background: #f8f9fa !important;
    border-radius: 6px !important;
}

.dataTables_scrollBody::-webkit-scrollbar-thumb {
    background: #6c757d !important;
    border-radius: 6px !important;
    border: 2px solid #f8f9fa !important;
}

.dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
    background: #495057 !important;
}

/* Sticky actions column - SIMPLIFIED */
@media (min-width: 576px) {
    .table th:last-child,
    .table td:last-child {
        position: sticky !important;
        right: 0 !important;
        background-color: #fff !important;
        z-index: 2 !important;
        box-shadow: -3px 0 10px rgba(0,0,0,0.1) !important;
    }
    
    .table thead th:last-child {
        background-color: var(--bs-gray-100) !important;
        z-index: 3 !important;
    }
}
</style>

<script>
// Wait for DataTables to be available
function waitForDataTables(callback) {
    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForDataTables(callback);
        }, 100);
    }
}

waitForDataTables(function() {
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    let servicesTable;
    let isInitializing = false;

    // Load clients for filter
    function loadClientsForFilter() {
        fetch('<?= base_url('clients/get_clients_json') ?>')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('servicesClientFilter');
                // Clear existing options except the first one
                while (select.children.length > 1) {
                    select.removeChild(select.lastChild);
                }
                
                if (data && Array.isArray(data)) {
                    data.forEach(client => {
                        const option = document.createElement('option');
                        option.value = client.id;
                        option.textContent = client.name;
                        select.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading clients:', error);
            });
    }

    // DataTable Configuration
    function initializeServicesDataTable() {
        if (isInitializing) {
            return;
        }
        
        isInitializing = true;

        // Check dependencies
        if (typeof $ === 'undefined') {
            return;
        }

        if (typeof $.fn.DataTable === 'undefined') {
            return;
        }

        // Check table element exists
        const tableElement = document.getElementById('services-table');
        if (!tableElement) {
            return;
        }

        try {
            // Destroy existing table if it exists
            if ($.fn.DataTable.isDataTable('#services-table')) {
                $('#services-table').DataTable().destroy();
                $('#services-table').off();
            }

            // Force table width before initialization
            $('#services-table').css('width', '100%');

            servicesTable = $('#services-table').DataTable({
                processing: true,
                serverSide: false,
                responsive: false,
                scrollX: true,
                scrollCollapse: false,
                autoWidth: false,
                columnDefs: [
                    { width: "20%", targets: 0, className: "text-center" }, // Service Name
                    { width: "25%", targets: 1, className: "text-left" },   // Description
                    { width: "10%", targets: 2, className: "text-center" }, // Price
                    { width: "15%", targets: 3, className: "text-center" }, // Client
                    { width: "10%", targets: 4, className: "text-center" }, // Status
                    { width: "10%", targets: 5, className: "text-center" }, // Show in Orders
                    { width: "10%", targets: 6, orderable: false, searchable: false, className: "text-center" } // Actions
                ],
                ajax: {
                    url: '<?= base_url('sales_orders_services/list_data') ?>',
                    type: 'POST',
                    dataSrc: function(json) { return json.data || []; },
                    data: function(d) {
                        // Custom filters (optional server-side usage)
                        d.client_filter = $('#servicesClientFilter').val() || '';
                        d.status_filter = $('#servicesStatusFilter').val() || '';
                        d.show_in_orders_filter = $('#servicesOrdersFilter').val() || '';
                        d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Error loading services data:', error, xhr.responseText);
                        showToast('error', '<?= lang('App.error_loading_data') ?>');
                    }
                },
                columns: [
                    {
                        data: 'service_name',
                        render: function(data, type, row) {
                            return `<div class="fw-medium text-primary">${data}</div>`;
                        }
                    },
                    {
                        data: 'service_description',
                        render: function(data, type, row) {
                            if (data && data.length > 50) {
                                return `<span title="${data}">${data.substring(0, 50)}...</span>`;
                            }
                            return data || '<span class="text-muted">Sin descripción</span>';
                        }
                    },
                    {
                        data: 'service_price',
                        render: function(data, type, row) {
                            return `<span class="price-badge">$${parseFloat(data).toFixed(2)}</span>`;
                        }
                    },
                    {
                        data: 'client_name',
                        render: function(data, type, row) {
                            return data || '<span class="text-muted">Todos los clientes</span>';
                        }
                    },
                    {
                        data: 'service_status',
                        render: function(data, type, row) {
                            const statusClass = data === 'active' ? 'status-active' : 'status-inactive';
                            const statusText = data === 'active' ? '<?= lang('App.active') ?>' : '<?= lang('App.inactive') ?>';
                            return `<span class="${statusClass}">${statusText}</span>`;
                        }
                    },
                    {
                        data: 'show_in_orders',
                        render: function(data, type, row) {
                            if (parseInt(data, 10) === 1) {
                                return '<span class="badge bg-success"><?= lang('App.yes') ?></span>';
                            } else {
                                return '<span class="badge bg-secondary"><?= lang('App.no') ?></span>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="<?= base_url('sales_orders_services/view/') ?>${data}" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.view') ?>">
                                        <i class="ri-eye-fill"></i>
                                    </a>
                                    <a href="#" class="link-success fs-15 edit-service-btn" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.edit') ?>">
                                        <i class="ri-edit-fill"></i>
                                    </a>
                                    <a href="#" class="link-danger fs-15 delete-service-btn" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.delete') ?>">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            `;
                        }
                    }
                ],
                order: [[0, 'asc']],
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
                        <div class="d-flex justify-content-center align-items-center p-4">
                            <div class="spinner-border text-primary me-2" role="status">
                                <span class="visually-hidden"><?= lang('App.loading') ?>...</span>
                            </div>
                            <span><?= lang('App.loading') ?> <?= lang('App.services') ?>...</span>
                        </div>
                    `,
                    lengthMenu: "<?= lang('App.show') ?> _MENU_ <?= lang('App.entries') ?>",
                    zeroRecords: `
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="service" class="icon-lg text-muted"></i>
                            </div>
                            <h5 class="text-muted"><?= lang('App.no_active_services') ?></h5>
                            <p class="text-muted mb-3">No se encontraron servicios con los filtros aplicados</p>
                            <button onclick="clearAllServicesFilters()" class="btn btn-secondary btn-sm">
                                <i data-feather="refresh-cw" class="icon-sm me-1"></i> <?= lang('App.clear_filters') ?>
                            </button>
                        </div>
                    `,
                    info: "Mostrando _START_ a _END_ de _TOTAL_ servicios",
                    infoEmpty: "<?= lang('App.no_services_to_display') ?>",
                    infoFiltered: "",
                    search: "Buscar <?= lang('App.services') ?>:",
                    paginate: {
                        first: "<?= lang('App.first') ?>",
                        last: "<?= lang('App.last') ?>",
                        next: "<?= lang('App.next') ?>",
                        previous: "<?= lang('App.previous') ?>"
                    }
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                initComplete: function(settings, json) {
                    // Force multiple adjustments after initialization
                    const table = this.api();

                    setTimeout(() => {
                        table.columns.adjust();
                        $('#services-table').css('width', '100%');
                        
                        // Force horizontal scroll to work
                        $('.dataTables_scrollBody').css({
                            'overflow-x': 'auto',
                            '-webkit-overflow-scrolling': 'touch'
                        });
                    }, 50);

                    setTimeout(() => {
                        table.columns.adjust().draw();
                        $('#services-table').css('width', '100%');
                    }, 150);

                    setTimeout(() => {
                        table.columns.adjust();
                        $('#services-table').css('width', '100%');
                        $('.dataTables_wrapper').css('width', '100%');
                        
                        // Ensure horizontal scroll is working properly
                        $('.dataTables_scrollBody').css({
                            'overflow-x': 'auto',
                            '-webkit-overflow-scrolling': 'touch',
                            'scroll-behavior': 'smooth'
                        });
                        
                        // Set minimum table width based on screen size
                        const screenWidth = $(window).width();
                        let minWidth = '100%';
                        if (screenWidth < 576) minWidth = '600px';
                        else if (screenWidth < 768) minWidth = '700px';
                        else if (screenWidth < 992) minWidth = '800px';
                        else if (screenWidth < 1200) minWidth = '900px';
                        
                        $('#services-table').css('min-width', minWidth);
                    }, 300);

                    // Initialize Feather icons after each redraw
                    if (typeof feather !== 'undefined') {
                        setTimeout(() => {
                            feather.replace();
                        }, 50);
                    }

                    // Initialize tooltips on first load
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        setTimeout(() => {
                            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                            tooltipTriggerList.map(function (tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl, {
                                    html: true,
                                    trigger: 'hover focus',
                                    delay: { show: 500, hide: 100 }
                                });
                            });
                        }, 100);
                    }

                    isInitializing = false;
                },
                drawCallback: function(settings) {
                    // Initialize Feather icons after each redraw
                    if (typeof feather !== 'undefined') {
                        setTimeout(() => {
                            feather.replace();
                        }, 50);
                    }

                    // Initialize tooltips
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        // Dispose of existing tooltips first
                        const existingTooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                        existingTooltips.forEach(element => {
                            const tooltip = bootstrap.Tooltip.getInstance(element);
                            if (tooltip) {
                                tooltip.dispose();
                            }
                        });
                        
                        // Initialize new tooltips
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.map(function (tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl, {
                                html: true,
                                trigger: 'hover focus',
                                delay: { show: 500, hide: 100 }
                            });
                        });
                    }

                    // Ensure table uses full width on every draw and maintain horizontal scroll
                    $('#services-table').css('width', '100%');
                    $('.dataTables_wrapper').css('width', '100%');
                    
                    // Maintain horizontal scroll functionality
                    $('.dataTables_scrollBody').css({
                        'overflow-x': 'auto',
                        '-webkit-overflow-scrolling': 'touch'
                    });
                }
            });

        } catch (error) {
            console.error('❌ Error initializing Services DataTable:', error);
            isInitializing = false;
        }
    }

    // Filter Functions
    $('#applyServicesFilters').on('click', function() {
        if (servicesTable) {
            servicesTable.ajax.reload();
            showToast('success', '<?= lang('App.filters_applied') ?>');
        }
    });

    $('#clearServicesFilters').on('click', function() {
        clearAllServicesFilters();
    });

    // Global function to clear all filters
    window.clearAllServicesFilters = function() {
        $('#servicesClientFilter, #servicesStatusFilter, #servicesOrdersFilter').val('');

        if (servicesTable) {
            servicesTable.ajax.reload();
            showToast('info', '<?= lang('App.filters_cleared') ?>');
        }
    };

    // Refresh function
    $('#refreshServicesTable').on('click', function() {
        if (servicesTable) {
            servicesTable.ajax.reload(null, false); // false = don't reset pagination
            showToast('success', '<?= lang('App.table_refreshed') ?>');
        }
    });

    // Auto-apply filters when filters change
    $('#servicesClientFilter, #servicesStatusFilter, #servicesOrdersFilter').on('change', function() {
        if (servicesTable) {
            servicesTable.ajax.reload(null, false);
        }
    });

    // Action Functions
    $(document).on('click', '.edit-service-btn', function(e) {
        e.preventDefault();
        const serviceId = $(this).data('id');
        editService(serviceId);
    });

    $(document).on('click', '.delete-service-btn', function(e) {
        e.preventDefault();
        const serviceId = $(this).data('id');
        deleteService(serviceId);
    });

    window.editService = function(serviceId) {
        // Load modal form via AJAX and stay on this tab
        $.get('<?= base_url('sales_orders_services/modal_form') ?>', { id: serviceId })
            .done(function(html) {
                const $modal = $('#serviceModal');
                $modal.find('.modal-content').html(html);
                $modal.modal('show');
            })
            .fail(function() {
                showToast('error', '<?= lang('App.error_loading_data') ?>');
            });
    };

    window.deleteService = function(serviceId) {
        Swal.fire({
            title: '<?= lang('App.are_you_sure') ?>',
            text: 'Este servicio se eliminará permanentemente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f06548',
            cancelButtonColor: '#74788d',
            confirmButtonText: '<?= lang('App.yes_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '<?= lang('App.deleting') ?>...',
                    text: '<?= lang('App.please_wait') ?>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`<?= base_url('sales_orders_services/delete/') ?>${serviceId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.close();
                        showToast('success', data.message || 'Servicio eliminado exitosamente');
                        
                        if (servicesTable) {
                            servicesTable.ajax.reload();
                        }
                    } else {
                        Swal.fire({
                            title: '<?= lang('App.error') ?>!',
                            text: data.message || 'Error al eliminar el servicio',
                            icon: 'error',
                            confirmButtonText: '<?= lang('App.ok') ?>'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error deleting service:', error);
                    Swal.fire({
                        title: '<?= lang('App.error') ?>!',
                        text: 'Error al eliminar el servicio',
                        icon: 'error',
                        confirmButtonText: '<?= lang('App.ok') ?>'
                    });
                });
            }
        });
    };

    // Toast function
    // Toast function
function showToast(type, message) {
    if (typeof Toastify !== 'undefined') {
        const colors = { success: "#28a745", error: "#dc3545", info: "#17a2b8", warning: "#ffc107" };
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            style: { background: colors[type] || colors.info },
        }).showToast();
    }
}

    // Handle window resize for horizontal scroll
    $(window).on('resize', function() {
        if (servicesTable) {
            // Adjust columns and maintain scroll functionality
            setTimeout(() => {
                servicesTable.columns.adjust();
                
                // Set appropriate table minimum width based on screen size
                const screenWidth = $(window).width();
                let minWidth = '100%';
                if (screenWidth < 576) minWidth = '600px';
                else if (screenWidth < 768) minWidth = '700px';
                else if (screenWidth < 992) minWidth = '800px';
                else if (screenWidth < 1200) minWidth = '900px';
                
                $('#services-table').css('min-width', minWidth);
                
                // Ensure horizontal scroll is maintained on resize
                $('.dataTables_scrollBody').css({
                    'overflow-x': 'auto',
                    '-webkit-overflow-scrolling': 'touch',
                    'scroll-behavior': 'smooth'
                });
            }, 100);
        }
    });

    // Initialize everything
    setTimeout(() => {
        loadClientsForFilter();
        initializeServicesDataTable();
    }, 200);

    // Additional Feather icons initialization
    setTimeout(() => {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 1000);
    // Intercept modal form submit to save via AJAX and reload the table
    $(document).on('submit', '#serviceForm', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        $.ajax({
            url: $(form).attr('action') || '<?= base_url('sales_orders_services/store') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(resp) {
                if (resp && resp.success) {
                    $('#serviceModal').modal('hide');
                    if (servicesTable) {
                        servicesTable.ajax.reload(null, false);
                    }
                    showToast('success', resp.message || '<?= lang('App.saved_successfully') ?>');
                } else {
                    showToast('error', (resp && resp.message) || '<?= lang('App.error_saving') ?>');
                }
            },
            error: function() {
                showToast('error', '<?= lang('App.error_saving') ?>');
            }
        });
    });

}); // End of waitForDataTables
</script>