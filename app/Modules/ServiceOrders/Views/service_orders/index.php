<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.service_orders') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.service_orders') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.service_orders') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex align-items-center">
        <h4 class="card-title mb-0 flex-grow-1"><?= lang('App.service_orders') ?></h4>
        <div class="flex-shrink-0">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-primary" id="addOrderBtn">
                    <i data-feather="plus" class="icon-sm me-1"></i>
                    <span class="d-none d-sm-inline">Add Service Order</span>
                    <span class="d-inline d-sm-none"><?= lang('App.create') ?></span>
                </button>
            </div>
        </div>
    </div>

    <!-- FILTROS GLOBALES UNIFICADOS - ACORDEÓN COMPACTO -->
    <div class="card-body border-bottom p-2">
        <div class="accordion accordion-flush" id="filtersAccordion">
            <div class="accordion-item border-0">
                <h6 class="accordion-header mb-0" id="filtersHeading">
                    <button class="accordion-button collapsed py-2 px-3 bg-light border rounded" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#filtersContent" 
                            aria-expanded="false" aria-controls="filtersContent">
                        <i data-feather="filter" class="icon-sm me-2"></i>
                        <span class="fw-semibold"><?= lang('App.filters') ?></span>
                        <span id="activeFiltersCount" class="badge bg-primary ms-2 d-none">0</span>
                    </button>
                </h6>
                <div id="filtersContent" class="accordion-collapse collapse" 
                     aria-labelledby="filtersHeading" data-bs-parent="#filtersAccordion">
                    <div class="accordion-body pt-3 pb-2">
                        <!-- Filtros Row 1 -->
                        <div class="row g-2 mb-3">
                            <!-- Filtro de Cliente -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <label class="form-label text-muted small mb-1"><?= lang('App.filter_by_client') ?></label>
                                <select id="globalClientFilter" class="form-select form-select-sm">
                                    <option value=""><?= lang('App.all_clients') ?></option>
                                    <?php if (isset($clients) && !empty($clients)): ?>
                                        <?php foreach ($clients as $client): ?>
                                            <option value="<?= $client['id'] ?>">
                                                <?= esc($client['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Filtro de Contacto -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <label class="form-label text-muted small mb-1"><?= lang('App.filter_by_contact') ?></label>
                                <select id="globalContactFilter" class="form-select form-select-sm">
                                    <option value=""><?= lang('App.all_contacts') ?></option>
                                    <?php if(isset($contacts) && is_array($contacts)): ?>
                                        <?php foreach($contacts as $contact): ?>
                                            <option value="<?= $contact['id'] ?>" data-client-id="<?= $contact['client_id'] ?>"><?= esc($contact['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Filtro de Estado -->
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <label class="form-label text-muted small mb-1"><?= lang('App.filter_by_status') ?></label>
                                <select id="globalStatusFilter" class="form-select form-select-sm">
                                    <option value=""><?= lang('App.all_status') ?></option>
                                    <option value="pending"><?= lang('App.pending') ?></option>
                                    <option value="processing"><?= lang('App.processing') ?></option>
                                    <option value="in_progress"><?= lang('App.in_progress') ?></option>
                                    <option value="completed"><?= lang('App.completed') ?></option>
                                    <option value="cancelled"><?= lang('App.cancelled') ?></option>
                                </select>
                            </div>

                            <!-- Filtro Fecha Desde -->
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <label class="form-label text-muted small mb-1"><?= lang('App.date_from') ?></label>
                                <input type="date" id="globalDateFromFilter" class="form-control form-control-sm">
                            </div>

                            <!-- Filtro Fecha Hasta -->
                            <div class="col-lg-2 col-md-6 col-sm-6">
                                <label class="form-label text-muted small mb-1"><?= lang('App.date_to') ?></label>
                                <input type="date" id="globalDateToFilter" class="form-control form-control-sm">
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2 flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button id="applyGlobalFilters" class="btn btn-primary btn-sm">
                                            <i data-feather="check" class="icon-sm me-1"></i> <?= lang('App.apply_filters') ?>
                                        </button>
                                        <button id="clearGlobalFilters" class="btn btn-outline-secondary btn-sm">
                                            <i data-feather="x" class="icon-sm me-1"></i> <?= lang('App.clear_filters') ?>
                                        </button>
                                        <button id="refreshAllTables" class="btn btn-outline-info btn-sm">
                                            <i data-feather="refresh-cw" class="icon-sm me-1"></i> <?= lang('App.refresh') ?>
                                        </button>
                                    </div>
                                    <small class="text-muted d-none d-md-block">
                                        <i data-feather="info" class="icon-xs me-1"></i>
                                        Filtros se aplican a todas las tablas y dashboard
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#dashboard-tab" role="tab">
                    <span><i data-feather="home" class="icon-sm me-1"></i> <?= lang('App.dashboard') ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#today-orders-tab" role="tab">
                    <span>
                        <i data-feather="calendar" class="icon-sm me-1"></i> <?= lang('App.today_orders') ?>
                        <span id="todayOrdersBadge" class="badge bg-danger ms-2" style="display: none;">0</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tomorrow-orders-tab" role="tab">
                    <span>
                        <i data-feather="calendar" class="icon-sm me-1"></i> <?= lang('App.tomorrow_orders') ?>
                        <span id="tomorrowOrdersBadge" class="badge bg-primary ms-2" style="display: none;">0</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#pending-orders-tab" role="tab">
                    <span>
                        <i data-feather="clock" class="icon-sm me-1"></i> <?= lang('App.pending_orders') ?>
                        <span id="pendingOrdersBadge" class="badge bg-warning ms-2" style="display: none;">0</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#week-orders-tab" role="tab">
                    <span>
                        <i data-feather="calendar" class="icon-sm me-1"></i> <?= lang('App.week_view') ?>
                        <span id="weekOrdersBadge" class="badge bg-secondary ms-2" style="display: none;">0</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#all-orders-tab" role="tab">
                    <span><i data-feather="list" class="icon-sm me-1"></i> <?= lang('App.all_orders') ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#services-tab" role="tab">
                    <span><i data-feather="package" class="icon-sm me-1"></i> <?= lang('App.services') ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#deleted-orders-tab" role="tab">
                    <span><i data-feather="trash-2" class="icon-sm me-1"></i> Deleted Orders</span>
                </a>
            </li>
        </ul>

        <div class="tab-content py-4">
            <div class="tab-pane active" id="dashboard-tab" role="tabpanel">
                                                    <?= $this->include('Modules\ServiceOrders\Views\service_orders/dashboard_content') ?>
                                </div>
                                <div class="tab-pane" id="today-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\ServiceOrders\Views\service_orders/today_content') ?>
                                </div>
                                <div class="tab-pane" id="tomorrow-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\ServiceOrders\Views\service_orders/tomorrow_content') ?>
                                </div>
                                <div class="tab-pane" id="pending-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\ServiceOrders\Views\service_orders/pending_content') ?>
                                </div>
                                <div class="tab-pane" id="week-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\ServiceOrders\Views\service_orders/week_content') ?>
                                </div>
                                <div class="tab-pane" id="all-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\ServiceOrders\Views\service_orders/all_content') ?>
                                </div>
                                <div class="tab-pane" id="services-tab" role="tabpanel">
                                    <?= $this->include('Modules\ServiceOrders\Views\service_orders/services_content') ?>
                                </div>
                                <div class="tab-pane" id="deleted-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\ServiceOrders\Views\service_orders/deleted_content') ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar orden -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Add Service Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- El contenido del modal se cargará aquí -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i data-feather="x" class="icon-sm me-1"></i>
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary" form="serviceOrderForm">
                    <i data-feather="save" class="icon-sm me-1"></i>
                    Save Service Order
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar servicios -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- El contenido del modal se cargará aquí -->
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<!-- Essential Global Functions - Must be defined FIRST -->
<script>
// ========================================
// ESSENTIAL FUNCTIONS - AVAILABLE IMMEDIATELY
// ========================================

// Initialize globalFilters immediately if not exists
if (!window.globalFilters) {
    window.globalFilters = {
        client: '',
        contact: '',
        status: '',
        dateFrom: '',
        dateTo: ''
    };
    
    // Load saved filters immediately to ensure they're available
    const savedClient = localStorage.getItem('serviceOrdersGlobalClientFilter');
    const savedContact = localStorage.getItem('serviceOrdersGlobalContactFilter');
    const savedStatus = localStorage.getItem('serviceOrdersGlobalStatusFilter');
    const savedDateFrom = localStorage.getItem('serviceOrdersGlobalDateFromFilter');
    const savedDateTo = localStorage.getItem('serviceOrdersGlobalDateToFilter');
    
    window.globalFilters.client = savedClient || '';
    window.globalFilters.contact = savedContact || '';
    window.globalFilters.status = savedStatus || '';
    window.globalFilters.dateFrom = savedDateFrom || '';
    window.globalFilters.dateTo = savedDateTo || '';
}

// Initialize globalClientFilter immediately
window.globalClientFilter = window.globalFilters.client || '';

// Essential functions needed by content files immediately
window.getCurrentClientFilter = function() {
    return window.globalFilters?.client || '';
};

window.getCurrentClientName = function() {
    const clientId = window.globalFilters?.client;
    if (!clientId) return '';
    
    const globalClientFilterSelect = document.getElementById('globalClientFilter');
    if (!globalClientFilterSelect) return '';
    
    const option = globalClientFilterSelect.querySelector(`option[value="${clientId}"]`);
    return option ? option.textContent.trim() : '';
};

</script>

<script>
// Service Orders Complete JavaScript functionality
$(document).ready(function() {
    
    // LocalStorage key for active tab
    const ACTIVE_TAB_KEY = 'serviceOrders_activeTab';
    
    // Function to get last active tab from localStorage
    function getLastActiveTab() {
        const savedTab = localStorage.getItem(ACTIVE_TAB_KEY);

        return savedTab || '#dashboard-tab'; // Default to dashboard if nothing saved
    }
    
    // Function to save active tab to localStorage
    function saveActiveTab(tabId) {
        localStorage.setItem(ACTIVE_TAB_KEY, tabId);

    }
    
    // Get the last active tab and activate it
    const lastActiveTab = getLastActiveTab();
    
    // Remove active class from all tabs and tab panes
    $('.nav-link').removeClass('active');
    $('.tab-pane').removeClass('active show');
    
    // Activate the saved tab
    $(`a[href="${lastActiveTab}"]`).addClass('active');
    $(lastActiveTab).addClass('active show');
    
    // Initialize the restored tab after a delay
    setTimeout(() => {

        if (typeof window.handleTabTableInitialization === 'function') {
            window.handleTabTableInitialization(lastActiveTab);
        }
    }, 1000);
    
    // Set up tab event listeners
    const tabElements = $('a[data-bs-toggle="tab"]');
    
    tabElements.on('shown.bs.tab', function (e) {
        const targetTab = e.target.getAttribute('href');
        
        // Save the new active tab to localStorage
        saveActiveTab(targetTab);
        
        // Initialize the table for the new tab
        setTimeout(() => {

            if (typeof window.handleTabTableInitialization === 'function') {
                window.handleTabTableInitialization(targetTab);
            }
        }, 100);
    });

    // Add Order Button
    $('#addOrderBtn').on('click', function() {
        $.get('<?= base_url('service_orders/modal_form') ?>', function(data) {
            $('#orderModal .modal-title').text('Add Service Order');
            $('#orderModal .modal-footer button[type="submit"]').html('<i data-feather="save" class="icon-sm me-1"></i> Save Service Order');
            $('#orderModal .modal-body').html(data);
            $('#orderModal').modal('show');

            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Initialize modal after content is loaded
            if (typeof window.initializeServiceOrderModal === 'function') {
                window.initializeServiceOrderModal();
            }
            
            // Check if order status requires readonly fields
            if (typeof window.checkServiceOrderStatusAndSetReadonly === 'function') {
                setTimeout(() => {
                    window.checkServiceOrderStatusAndSetReadonly();
                }, 100);
            }
        });
    });

        // Modal event handlers for proper initialization and cleanup
    $('#orderModal').on('shown.bs.modal', function () {

        if (typeof window.initializeServiceOrderModal === 'function') {
            window.initializeServiceOrderModal();
        }
        
        // Check if order status requires readonly fields
        if (typeof window.checkServiceOrderStatusAndSetReadonly === 'function') {
            setTimeout(() => {
                window.checkServiceOrderStatusAndSetReadonly();
            }, 100);
        }
    });

    $('#orderModal').on('hidden.bs.modal', function () {

        if (typeof window.cleanupServiceOrderModal === 'function') {
            window.cleanupServiceOrderModal();
        }
    });

    // Edit order button handler
    $(document).on('click', '.edit-service-order-btn', function(e) {
        e.preventDefault();
        const orderId = $(this).data('id');

        $.get('<?= base_url('service_orders/modal_form') ?>?edit=' + orderId, function(data) {
            $('#orderModal .modal-title').text('Edit Service Order');
            $('#orderModal .modal-footer button[type="submit"]').html('<i data-feather="save" class="icon-sm me-1"></i> Update Service Order');
            $('#orderModal .modal-body').html(data);
            $('#orderModal').modal('show');

            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Initialize modal after content is loaded
            if (typeof window.initializeServiceOrderModal === 'function') {
                window.initializeServiceOrderModal();
            }
            
            // Check if order status requires readonly fields
            if (typeof window.checkServiceOrderStatusAndSetReadonly === 'function') {
                setTimeout(() => {
                    window.checkServiceOrderStatusAndSetReadonly();
                }, 200);
            }
        });
    });

    // Delete order button handler
    $(document).on('click', '.delete-service-order-btn', function(e) {
        e.preventDefault();
        const orderId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('service_orders/delete') ?>/' + orderId,
                    type: 'POST',
                    data: {
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            // Refresh all tables
                            if (typeof refreshAllServiceOrdersTables === 'function') {
                                refreshAllServiceOrdersTables();
                            } else {
                                location.reload();
                            }
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to delete service order', 'error');
                    }
                });
            }
        });
    });
    
    // Add Service Button and Service Modal handling
    $(document).on('click', '[data-bs-toggle="modal"][data-bs-target="#serviceModal"]', function(e) {
        e.preventDefault();
        
        const serviceId = $(this).data('service-id') || null;
        const url = serviceId ? 
            '<?= base_url('service_orders_services/modal_form') ?>?id=' + serviceId :
            '<?= base_url('service_orders_services/modal_form') ?>';
        
        $.get(url)
            .done(function(data) {
                $('#serviceModal .modal-content').html(data);
                $('#serviceModal').modal('show');

            })
            .fail(function(xhr, status, error) {
                console.error('❌ Error loading service modal:', error);
                showToast('error', 'Error loading service form');
            });
    });
    
    // Handle service form submission with duplicate prevention
    $(document).on('submit', '#serviceForm', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation(); // Prevent multiple handlers
        
        const form = $(this);
        const submitButton = form.find('button[type="submit"]');
        
        // Prevent multiple submissions
        if (submitButton.prop('disabled') || submitButton.hasClass('submitting')) {
            console.log('🔒 Service form submission prevented - already submitting');
            return false;
        }
        
        // Mark as submitting and disable button
        submitButton.prop('disabled', true).addClass('submitting');
        const originalText = submitButton.html();
        submitButton.html('<i class="mdi mdi-spin mdi-loading me-1"></i> Saving...');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('✅ Service form submitted successfully:', response);
                
                if (response.success) {
                    $('#serviceModal').modal('hide');
                    
                    // Show success message
                    const isEditing = form.find('input[name="id"]').length > 0;
                    const defaultMessage = isEditing ? 'Service updated successfully' : 'Service created successfully';
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || defaultMessage,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        showToast('success', response.message || defaultMessage);
                    }
                    
                    // Refresh services table if exists
                    if (window.servicesTable) {
                        console.log('🔄 Refreshing services table...');
                        window.servicesTable.ajax.reload(null, false);
                    }
                } else {
                    // Show error message
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Error saving service'
                        });
                } else {
                    showToast('error', response.message || 'Error saving service');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error submitting service form:', error);
                
                let errorMessage = 'Error saving service';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                } else {
                    showToast('error', errorMessage);
                }
            },
            complete: function() {
                // Re-enable button and restore original text
                submitButton.prop('disabled', false).removeClass('submitting');
                submitButton.html(originalText);
            }
        });
    });
    
    // Refresh Services Table Button
    $(document).on('click', '#refreshServicesTable', function() {

        if (window.servicesTable) {
            window.servicesTable.ajax.reload(null, false);
            showToast('info', 'Services table refreshed');
        }
    });
});

function initializeTabTable(tabId) {

    switch(tabId) {
        case '#dashboard-tab':
            initializeDashboard();
            break;
        case '#today-orders-tab':

            initializeTodayOrdersTable();
            break;
        case '#tomorrow-orders-tab':

            initializeTomorrowOrdersTable();
            break;
        case '#pending-orders-tab':

            initializePendingOrdersTable();
            break;
        case '#week-orders-tab':

            initializeWeekOrdersTable();
            break;
        case '#all-orders-tab':

            if (typeof initAllServiceOrdersTable === 'function') {
                initAllServiceOrdersTable();
            } else {
                console.error('❌ initAllServiceOrdersTable function not found');
            }
            break;
        case '#services-tab':
            initializeServicesTable();
            break;
        case '#deleted-orders-tab':
            initializeDeletedOrdersTable();
            break;
    }
}

function initializeDashboard() {
    // Load dashboard badges on initialization
    if (typeof window.refreshDashboardBadges === 'function') {
        window.refreshDashboardBadges();
    }
}

function initializeTodayOrdersTable() {
    if (window.todayServiceOrdersTable) {

        return;
    }
    
    const tableElement = document.getElementById('today-service-orders-table');
    if (!tableElement) {
        console.error('❌ today-service-orders-table element not found!');
        return;
    }
    
    try {
        window.todayServiceOrdersTable = $('#today-service-orders-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= base_url('service_orders/get-today-orders') ?>',
                type: 'GET',
                dataSrc: function(json) {
                    return json.data || [];
                },
                error: function(xhr, error, code) {
                    console.error('Error loading today service orders:', error);
                }
            },
            columns: [
                { 
                    data: 'id',
                    render: function(data, type, row) {
                        let html = '<span class="fw-semibold">SER-' + String(data).padStart(5, '0') + '</span>';
                        
                        // Add internal notes badge if order has internal notes (only for staff and admin users)
                        <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
                        if (row.internal_notes_count && parseInt(row.internal_notes_count) > 0) {
                            const notesCount = parseInt(row.internal_notes_count);
                            const notesBadgeClass = notesCount > 0 ? 'internal-notes-badge has-notes' : 'internal-notes-badge';
                            
                            html += `<a href="#" class="${notesBadgeClass}" 
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Loading notes..." 
                                data-order-id="${row.id}"
                                onmouseenter="loadNotesTooltip(this, ${row.id})"
                                onclick="event.preventDefault(); event.stopPropagation(); window.location.href='<?= base_url('service_orders/view/') ?>${row.id}#internal-notes-card';">
                                <i class="ri-file-lock-line"></i>${notesCount}
                            </a>`;
                        }
                        <?php endif; ?>
                        
                        return html;
                    }
                },
                { data: 'vehicle', defaultContent: 'N/A' },
                { data: 'client_name', defaultContent: 'N/A' },
                { data: 'service_name', defaultContent: 'N/A' },
                { 
                    data: 'date',
                    render: function(data, type, row) {
                        if (data && data !== 'N/A') {
                            const date = new Date(data);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                            const time = row.time ? '<br><small class="text-muted">' + row.time + '</small>' : '';
                            return '<div class="text-center">' + formattedDate + time + '</div>';
                        }
                        return '<div class="text-center"><span class="text-muted">N/A</span></div>';
                    }
                },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        const statusColors = {
                            'pending': 'warning',
                            'processing': 'info', 
                            'in_progress': 'primary',
                            'completed': 'success',
                            'cancelled': 'danger'
                        };
                        const color = statusColors[data] || 'secondary';
                        return '<span class="badge bg-' + color + '">' + (data || 'pending') + '</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-center gap-2 action-buttons">
                                <a href="<?= base_url('service_orders/view/') ?>${row.id}" class="link-primary fs-15" data-bs-toggle="tooltip" title="View">
                                    <i class="ri-eye-fill"></i>
                                </a>
                                <a href="#" class="link-success fs-15 edit-service-order-btn" data-id="${row.id}" data-bs-toggle="tooltip" title="Edit">
                                    <i class="ri-edit-fill"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            order: [[4, 'asc']],
            pageLength: 25,
            language: {
                search: "Search today's orders:",
                emptyTable: "No service orders found for today"
            },
            drawCallback: function() {
                if (typeof feather !== 'undefined') feather.replace();
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

    } catch (error) {
        console.error('❌ Error initializing today orders table:', error);
        console.error('Error details:', error.message);
    }
}

function initializeTomorrowOrdersTable() {
    if (window.tomorrowServiceOrdersTable) {

        return;
    }
    
    const tableElement = document.getElementById('tomorrow-service-orders-table');
    if (!tableElement) {
        console.error('❌ tomorrow-service-orders-table element not found!');
        return;
    }
    
    try {
        window.tomorrowServiceOrdersTable = $('#tomorrow-service-orders-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= base_url('service_orders/get-tomorrow-orders') ?>',
                type: 'GET',
                dataSrc: function(json) {
                    return json.data || [];
                },
                error: function(xhr, error, code) {
                    console.error('Error loading tomorrow service orders:', error);
                }
            },
            columns: [
                { 
                    data: 'id',
                    render: function(data, type, row) {
                        let html = '<span class="fw-semibold">SER-' + String(data).padStart(5, '0') + '</span>';
                        
                        // Add internal notes badge if order has internal notes (only for staff and admin users)
                        <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
                        if (row.internal_notes_count && parseInt(row.internal_notes_count) > 0) {
                            const notesCount = parseInt(row.internal_notes_count);
                            const notesBadgeClass = notesCount > 0 ? 'internal-notes-badge has-notes' : 'internal-notes-badge';
                            
                            html += `<a href="#" class="${notesBadgeClass}" 
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Loading notes..." 
                                data-order-id="${row.id}"
                                onmouseenter="loadNotesTooltip(this, ${row.id})"
                                onclick="event.preventDefault(); event.stopPropagation(); window.location.href='<?= base_url('service_orders/view/') ?>${row.id}#internal-notes-card';">
                                <i class="ri-file-lock-line"></i>${notesCount}
                            </a>`;
                        }
                        <?php endif; ?>
                        
                        return html;
                    }
                },
                { data: 'vehicle', defaultContent: 'N/A' },
                { data: 'client_name', defaultContent: 'N/A' },
                { data: 'service_name', defaultContent: 'N/A' },
                { 
                    data: 'date',
                    render: function(data, type, row) {
                        if (data && data !== 'N/A') {
                            const date = new Date(data);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                            const time = row.time ? '<br><small class="text-muted">' + row.time + '</small>' : '';
                            return '<div class="text-center">' + formattedDate + time + '</div>';
                        }
                        return '<div class="text-center"><span class="text-muted">N/A</span></div>';
                    }
                },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        const statusColors = {
                            'pending': 'warning',
                            'processing': 'info', 
                            'in_progress': 'primary',
                            'completed': 'success',
                            'cancelled': 'danger'
                        };
                        const color = statusColors[data] || 'secondary';
                        return '<span class="badge bg-' + color + '">' + (data || 'pending') + '</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-center gap-2 action-buttons">
                                <a href="<?= base_url('service_orders/view/') ?>${row.id}" class="link-primary fs-15" data-bs-toggle="tooltip" title="View">
                                    <i class="ri-eye-fill"></i>
                                </a>
                                <a href="#" class="link-success fs-15 edit-service-order-btn" data-id="${row.id}" data-bs-toggle="tooltip" title="Edit">
                                    <i class="ri-edit-fill"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            order: [[4, 'asc']],
            pageLength: 25,
            language: {
                search: "Search tomorrow's orders:",
                emptyTable: "No service orders found for tomorrow"
            },
            drawCallback: function() {
                if (typeof feather !== 'undefined') feather.replace();
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

    } catch (error) {
        console.error('❌ Error initializing tomorrow orders table:', error);
        console.error('Error details:', error.message);
    }
}

function initializePendingOrdersTable() {
    if (window.pendingServiceOrdersTable) {

        return;
    }
    
    const tableElement = document.getElementById('pending-service-orders-table');
    if (!tableElement) {
        console.error('❌ pending-service-orders-table element not found!');
        return;
    }
    
    try {
        window.pendingServiceOrdersTable = $('#pending-service-orders-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= base_url('service_orders/get-pending-orders') ?>',
                type: 'GET',
                dataSrc: function(json) {
                    return json.data || [];
                },
                error: function(xhr, error, code) {
                    console.error('Error loading pending service orders:', error);
                }
            },
            columns: [
                { 
                    data: 'id',
                    render: function(data, type, row) {
                        let html = '<span class="fw-semibold">SER-' + String(data).padStart(5, '0') + '</span>';
                        
                        // Add internal notes badge if order has internal notes (only for staff and admin users)
                        <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
                        if (row.internal_notes_count && parseInt(row.internal_notes_count) > 0) {
                            const notesCount = parseInt(row.internal_notes_count);
                            const notesBadgeClass = notesCount > 0 ? 'internal-notes-badge has-notes' : 'internal-notes-badge';
                            
                            html += `<a href="#" class="${notesBadgeClass}" 
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Loading notes..." 
                                data-order-id="${row.id}"
                                onmouseenter="loadNotesTooltip(this, ${row.id})"
                                onclick="event.preventDefault(); event.stopPropagation(); window.location.href='<?= base_url('service_orders/view/') ?>${row.id}#internal-notes-card';">
                                <i class="ri-file-lock-line"></i>${notesCount}
                            </a>`;
                        }
                        <?php endif; ?>
                        
                        return html;
                    }
                },
                { data: 'vehicle', defaultContent: 'N/A' },
                { data: 'client_name', defaultContent: 'N/A' },
                { data: 'service_name', defaultContent: 'N/A' },
                { 
                    data: 'date',
                    render: function(data, type, row) {
                        if (data && data !== 'N/A') {
                            const date = new Date(data);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                            const time = row.time ? '<br><small class="text-muted">' + row.time + '</small>' : '';
                            return '<div class="text-center">' + formattedDate + time + '</div>';
                        }
                        return '<div class="text-center"><span class="text-muted">N/A</span></div>';
                    }
                },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        return '<span class="badge bg-warning">pending</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-center gap-2 action-buttons">
                                <a href="<?= base_url('service_orders/view/') ?>${row.id}" class="link-primary fs-15" data-bs-toggle="tooltip" title="View">
                                    <i class="ri-eye-fill"></i>
                                </a>
                                <a href="#" class="link-success fs-15 edit-service-order-btn" data-id="${row.id}" data-bs-toggle="tooltip" title="Edit">
                                    <i class="ri-edit-fill"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            order: [[4, 'asc']],
            pageLength: 25,
            language: {
                search: "Search pending orders:",
                emptyTable: "No pending service orders found"
            },
            drawCallback: function() {
                if (typeof feather !== 'undefined') feather.replace();
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

    } catch (error) {
        console.error('❌ Error initializing pending orders table:', error);
        console.error('Error details:', error.message);
    }
}

function initializeWeekOrdersTable() {
    if (window.weekServiceOrdersTable) {

        return;
    }
    
    const tableElement = document.getElementById('week-service-orders-table');
    if (!tableElement) {
        console.error('❌ week-service-orders-table element not found!');
        return;
    }
    
    try {
        window.weekServiceOrdersTable = $('#week-service-orders-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= base_url('service_orders/get-week-orders') ?>',
                type: 'GET',
                dataSrc: function(json) {
                    return json.data || [];
                },
                error: function(xhr, error, code) {
                    console.error('Error loading week service orders:', error);
                }
            },
            columns: [
                { 
                    data: 'id',
                    render: function(data, type, row) {
                        let html = '<span class="fw-semibold">SER-' + String(data).padStart(5, '0') + '</span>';
                        
                        // Add internal notes badge if order has internal notes (only for staff and admin users)
                        <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
                        if (row.internal_notes_count && parseInt(row.internal_notes_count) > 0) {
                            const notesCount = parseInt(row.internal_notes_count);
                            const notesBadgeClass = notesCount > 0 ? 'internal-notes-badge has-notes' : 'internal-notes-badge';
                            
                            html += `<a href="#" class="${notesBadgeClass}" 
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Loading notes..." 
                                data-order-id="${row.id}"
                                onmouseenter="loadNotesTooltip(this, ${row.id})"
                                onclick="event.preventDefault(); event.stopPropagation(); window.location.href='<?= base_url('service_orders/view/') ?>${row.id}#internal-notes-card';">
                                <i class="ri-file-lock-line"></i>${notesCount}
                            </a>`;
                        }
                        <?php endif; ?>
                        
                        return html;
                    }
                },
                { data: 'vehicle', defaultContent: 'N/A' },
                { data: 'client_name', defaultContent: 'N/A' },
                { data: 'service_name', defaultContent: 'N/A' },
                { 
                    data: 'date',
                    render: function(data, type, row) {
                        if (data && data !== 'N/A') {
                            const date = new Date(data);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                            const time = row.time ? '<br><small class="text-muted">' + row.time + '</small>' : '';
                            return '<div class="text-center">' + formattedDate + time + '</div>';
                        }
                        return '<div class="text-center"><span class="text-muted">N/A</span></div>';
                    }
                },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        const statusColors = {
                            'pending': 'warning',
                            'processing': 'info', 
                            'in_progress': 'primary',
                            'completed': 'success',
                            'cancelled': 'danger'
                        };
                        const color = statusColors[data] || 'secondary';
                        return '<span class="badge bg-' + color + '">' + (data || 'pending') + '</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-center gap-2 action-buttons">
                                <a href="<?= base_url('service_orders/view/') ?>${row.id}" class="link-primary fs-15" data-bs-toggle="tooltip" title="View">
                                    <i class="ri-eye-fill"></i>
                                </a>
                                <a href="#" class="link-success fs-15 edit-service-order-btn" data-id="${row.id}" data-bs-toggle="tooltip" title="Edit">
                                    <i class="ri-edit-fill"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            order: [[4, 'asc']],
            pageLength: 25,
            language: {
                search: "Search week orders:",
                emptyTable: "No service orders found for this week"
            },
            drawCallback: function() {
                if (typeof feather !== 'undefined') feather.replace();
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

    } catch (error) {
        console.error('❌ Error initializing week orders table:', error);
        console.error('Error details:', error.message);
    }
}

function initializeAllOrdersTable() {
    if (window.allServiceOrdersTable) {

        return;
    }
    
    const tableElement = document.getElementById('all-service-orders-table');
    if (!tableElement) {
        console.error('❌ all-service-orders-table element not found!');
        return;
    }
    
    try {
        window.allServiceOrdersTable = $('#all-service-orders-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= base_url('service_orders/get-all-orders') ?>',
                type: 'GET',
                dataSrc: function(json) {
                    return json.data || [];
                },
                error: function(xhr, error, code) {
                    console.error('Error loading all service orders:', error);
                }
            },
            columns: [
                { 
                    data: 'id',
                    render: function(data, type, row) {
                        let html = '<span class="fw-semibold">SER-' + String(data).padStart(5, '0') + '</span>';
                        
                        // Add internal notes badge if order has internal notes (only for staff and admin users)
                        <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
                        if (row.internal_notes_count && parseInt(row.internal_notes_count) > 0) {
                            const notesCount = parseInt(row.internal_notes_count);
                            const notesBadgeClass = notesCount > 0 ? 'internal-notes-badge has-notes' : 'internal-notes-badge';
                            
                            html += `<a href="#" class="${notesBadgeClass}" 
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Loading notes..." 
                                data-order-id="${row.id}"
                                onmouseenter="loadNotesTooltip(this, ${row.id})"
                                onclick="event.preventDefault(); event.stopPropagation(); window.location.href='<?= base_url('service_orders/view/') ?>${row.id}#internal-notes-card';">
                                <i class="ri-file-lock-line"></i>${notesCount}
                            </a>`;
                        }
                        <?php endif; ?>
                        
                        return html;
                    }
                },
                { data: 'vehicle', defaultContent: 'N/A' },
                { data: 'client_name', defaultContent: 'N/A' },
                { data: 'service_name', defaultContent: 'N/A' },
                { 
                    data: 'date',
                    render: function(data, type, row) {
                        if (data && data !== 'N/A') {
                            const date = new Date(data);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            });
                            const time = row.time ? '<br><small class="text-muted">' + row.time + '</small>' : '';
                            return '<div class="text-center">' + formattedDate + time + '</div>';
                        }
                        return '<div class="text-center"><span class="text-muted">N/A</span></div>';
                    }
                },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        const statusColors = {
                            'pending': 'warning',
                            'processing': 'info', 
                            'in_progress': 'primary',
                            'completed': 'success',
                            'cancelled': 'danger'
                        };
                        const color = statusColors[data] || 'secondary';
                        return '<span class="badge bg-' + color + '">' + (data || 'pending') + '</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-center gap-2 action-buttons">
                                <a href="<?= base_url('service_orders/view/') ?>${row.id}" class="link-primary fs-15" data-bs-toggle="tooltip" title="View Service Order">
                                    <i class="ri-eye-fill"></i>
                                </a>
                                <a href="#" class="link-success fs-15 edit-service-order-btn" data-id="${row.id}" data-bs-toggle="tooltip" title="Edit Service Order">
                                    <i class="ri-edit-fill"></i>
                                </a>
                                <a href="#" class="link-danger fs-15 delete-service-order-btn" data-id="${row.id}" data-bs-toggle="tooltip" title="Delete">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            order: [[4, 'desc']],
            pageLength: 25,
            responsive: true,
            language: {
                search: "Search service orders:",
                lengthMenu: "Show _MENU_ service orders per page",
                info: "Showing _START_ to _END_ of _TOTAL_ service orders",
                infoEmpty: "No service orders found",
                infoFiltered: "(filtered from _MAX_ total orders)",
                emptyTable: "No service orders found",
                zeroRecords: "No matching service orders found",
                processing: "Loading service orders..."
            },
            drawCallback: function() {
                if (typeof feather !== 'undefined') feather.replace();
                $('[data-bs-toggle="tooltip"]').tooltip();
                
                $('#all-service-orders-table tbody tr').off('click').on('click', function(e) {
                    if ($(e.target).closest('.action-buttons').length > 0 || 
                        $(e.target).closest('a').length > 0) {
                        return;
                    }
                    
                    const table = $('#all-service-orders-table').DataTable();
                    const rowData = table.row(this).data();
                    if (rowData && rowData.id) {
                        window.location.href = `<?= base_url('service_orders/view/') ?>${rowData.id}`;
                    }
                });
            }
        });

    } catch (error) {
        console.error('❌ Error initializing all orders table:', error);
        console.error('Error details:', error.message);
    }
}

function initializeServicesTable() {
    
    // Check if the services tab is active first
    const servicesTab = document.querySelector('#services-tab');
    if (!servicesTab || !servicesTab.classList.contains('active')) {

        // Trigger tab activation to ensure content loads
        const servicesTabLink = document.querySelector('a[href="#services-tab"]');
        if (servicesTabLink) {
            const tabTrigger = new bootstrap.Tab(servicesTabLink);
            tabTrigger.show();
        }
    }
    
    // Wait for the content to be loaded with retry mechanism
    let retryCount = 0;
    const maxRetries = 15; // Increased retries
    
    function tryInitialize() {
        const tableElement = document.getElementById('services-table');
        if (!tableElement) {
            retryCount++;
            if (retryCount < maxRetries) {

                setTimeout(tryInitialize, 300); // Increased delay
                return;
            } else {
                console.error('❌ services-table element not found after all retries!');
                
                // Debug: Check what table elements exist
                const allTables = document.querySelectorAll('table[id]');
                
                // Check if services tab content is loaded
                const servicesTabPane = document.querySelector('#services-tab');
                
                return;
            }
        }

        initializeServicesDataTable();
    }
    
    // Start trying after a small delay to allow tab content to load
    setTimeout(tryInitialize, 500);
}

function initializeServicesDataTable() {
    
    try {
        // Check if table is already initialized
        if (window.servicesTable && $.fn.DataTable.isDataTable('#services-table')) {

            return;
        }
        
        window.servicesTable = $('#services-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= base_url('service_orders/get-services') ?>',
                type: 'GET',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                }
            },
            columns: [
                { 
                    data: 'service_name',
                    title: 'Service Name'
                },
                { 
                    data: 'service_description',
                    title: 'Description',
                    render: function(data, type, row) {
                        if (!data || data === '') return '<span class="text-muted">-</span>';
                        return data.length > 50 ? 
                            '<span title="' + data + '">' + data.substring(0, 50) + '...</span>' : 
                            data;
                    }
                },
                { 
                    data: 'service_price',
                    title: 'Price',
                    render: function(data, type, row) {
                        return '<span class="fw-semibold text-success">$' + data + '</span>';
                    }
                },
                { 
                    data: null,
                    title: 'Client',
                    defaultContent: '<span class="text-muted fst-italic">All Clients</span>',
                    orderable: false
                },
                { 
                    data: 'service_status',
                    title: 'Status',
                    render: function(data, type, row) {
                        const statusColors = {
                            'active': 'success',
                            'inactive': 'danger',
                            'pending': 'warning'
                        };
                        const color = statusColors[data] || 'secondary';
                        return '<span class="badge bg-' + color + '">' + data + '</span>';
                    }
                },
                { 
                    data: 'show_in_orders',
                    title: 'Show in Orders'
                },
                {
                    data: 'actions',
                    title: 'Actions',
                    orderable: false
                }
            ],
            order: [[0, 'asc']], // Order by service name
            pageLength: 25,
            responsive: true,
            autoWidth: false,
            destroy: true, // Allow reinitialization
            language: {
                search: "Search services:",
                lengthMenu: "Show _MENU_ services per page",
                info: "Showing _START_ to _END_ of _TOTAL_ services",
                infoEmpty: "No services found",
                infoFiltered: "(filtered from _MAX_ total services)",
                emptyTable: "No services found",
                zeroRecords: "No matching services found",
                processing: "Loading services..."
            },
            drawCallback: function() {
                if (typeof feather !== 'undefined') feather.replace();
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            initComplete: function() {

            }
        });

    } catch (error) {
        console.error('❌ Error initializing services table:', error);
        console.error('Error details:', error.message);
    }
}

function initializeDeletedOrdersTable() {
    
    // Wait for the content to be loaded with retry mechanism
    let retryCount = 0;
    const maxRetries = 10;
    
    function tryInitialize() {
        const tableElement = document.getElementById('deletedOrdersTable');
        if (!tableElement) {
            retryCount++;
            if (retryCount < maxRetries) {

                setTimeout(tryInitialize, 200);
                return;
            } else {
                console.error('❌ deletedOrdersTable element not found after all retries!');
                return;
            }
        }

        initializeDeletedOrdersDataTable();
    }
    
    tryInitialize();
}

function initializeDeletedOrdersDataTable() {
    
    try {
        // Check if table is already initialized
        if (window.deletedOrdersTable && $.fn.DataTable.isDataTable('#deletedOrdersTable')) {

            return;
        }
        
        window.deletedOrdersTable = $('#deletedOrdersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('service_orders/all_content') ?>',
                type: 'POST',
                data: function(d) {
                    d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                    
                    // Filter for deleted orders only
                    d.deleted_filter = '1';
                    
                    if (typeof window.getCurrentClientFilter === 'function') {
                        d.client_filter = window.getCurrentClientFilter() || '';
                    }
                    if (window.globalFilters) {
                        d.status_filter = window.globalFilters.status || '';
                        d.contact_filter = window.globalFilters.contact || '';
                        d.date_from_filter = window.globalFilters.dateFrom || '';
                        d.date_to_filter = window.globalFilters.dateTo || '';
                    }
                }
            },
            columns: [
                { 
                    data: 'id',
                    render: function(data, type, row) {
                        return '<span class="fw-semibold text-muted">SER-' + String(data).padStart(5, '0') + '</span>';
                    }
                },
                { data: 'client_name', defaultContent: 'N/A' },
                { data: 'contact_name', defaultContent: '<span class="text-muted">N/A</span>' },
                { 
                    data: 'vin',
                    render: function(data, type, row) {
                        if (!data || data === 'N/A') return '<span class="text-muted">N/A</span>';
                        return '<span class="font-monospace small text-muted" data-bs-toggle="tooltip" title="VIN: ' + data + '">' + data + '</span>';
                    }
                },
                { data: 'vehicle', defaultContent: 'N/A' },
                { data: 'service_name', defaultContent: 'N/A' },
                { 
                    data: 'date',
                    render: function(data, type, row) {
                        if (!data || data === 'N/A') return '<div class="text-center"><span class="text-muted">N/A</span></div>';
                        const date = new Date(data);
                        const formattedDate = date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                        const time = row.time ? '<br><small class="text-muted">' + row.time + '</small>' : '';
                        return '<div class="text-center">' + formattedDate + time + '</div>';
                    }
                },
                { data: 'ro_number', defaultContent: '<span class="text-muted">-</span>' },
                { data: 'po_number', defaultContent: '<span class="text-muted">-</span>' },
                { data: 'tag_number', defaultContent: '<span class="text-muted">-</span>' },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        return '<span class="badge bg-secondary">' + (data || 'deleted') + '</span>';
                    }
                },
                { 
                    data: 'deleted_at',
                    render: function(data, type, row) {
                        if (!data || data === 'N/A') {
                            // Fallback to updated_at
                            data = row.updated_at;
                        }
                        if (!data || data === 'N/A') return '<div class="text-center"><span class="text-muted">N/A</span></div>';
                        const date = new Date(data);
                        return '<div class="text-center">' + date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        }) + '</div>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-center gap-2 action-buttons">
                                <a href="#" class="link-success fs-15" onclick="restoreOrder(${row.id})" data-bs-toggle="tooltip" title="Restore Order">
                                    <i class="ri-restart-line"></i>
                                </a>
                                <a href="<?= base_url('service_orders/view/') ?>${row.id}" class="link-primary fs-15" data-bs-toggle="tooltip" title="View Order">
                                    <i class="ri-eye-fill"></i>
                                </a>
                                <a href="#" class="link-danger fs-15" onclick="permanentDeleteOrder(${row.id})" data-bs-toggle="tooltip" title="Permanent Delete">
                                    <i class="ri-delete-bin-fill"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            order: [[11, 'desc']], // Order by deleted date desc
            pageLength: 25,
            responsive: true,
            language: {
                search: "Search deleted orders:",
                lengthMenu: "Show _MENU_ deleted orders per page",
                info: "Showing _START_ to _END_ of _TOTAL_ deleted orders",
                infoEmpty: "No deleted orders found",
                infoFiltered: "(filtered from _MAX_ total deleted orders)",
                emptyTable: "No deleted orders found",
                zeroRecords: "No matching deleted orders found",
                processing: "Loading deleted orders..."
            },
            drawCallback: function() {
                if (typeof feather !== 'undefined') feather.replace();
                $('[data-bs-toggle="tooltip"]').tooltip();
                
                $('#deletedOrdersTable tbody tr').off('click').on('click', function(e) {
                    if ($(e.target).closest('.action-buttons').length > 0 || 
                        $(e.target).closest('a').length > 0) {
                        return;
                    }
                    
                    const table = $('#deletedOrdersTable').DataTable();
                    const rowData = table.row(this).data();
                    if (rowData && rowData.id) {
                        window.location.href = `<?= base_url('service_orders/view/') ?>${rowData.id}`;
                    }
                });
            }
        });

    } catch (error) {
        console.error('❌ Error initializing deleted orders table:', error);
        console.error('Error details:', error.message);
    }
}

// Services Management Functions
function editService(serviceId) {

    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('❌ jQuery not available for service modal');
        showToast('error', 'Unable to load service form - jQuery not available');
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

    $.get('<?= base_url('service_orders_services/modal_form') ?>', { id: serviceId })
        .done(function(response) {

            $('#serviceModal .modal-content').html(response);
        })
        .fail(function(xhr, status, error) {
            console.error('❌ Error loading service:', error);
            $('#serviceModal').modal('hide');

            const errorMessage = xhr.responseJSON?.message || 'Error loading service form';
            showToast('error', errorMessage);
        });
}

function toggleServiceStatus(serviceId) {
    if (confirm('Are you sure you want to change the status of this service?')) {

        // Check if jQuery is available
        if (typeof $ === 'undefined' || typeof $.ajax !== 'function') {
            console.error('❌ jQuery not available for AJAX request');
            showToast('error', 'Unable to update service status - jQuery not loaded');
            return;
        }
        
        // Make AJAX request to toggle status
        $.ajax({
            url: '<?= base_url('service_orders/toggle_service_status') ?>',
            type: 'POST',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                service_id: serviceId
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message || 'Service status updated successfully');
                    if (window.servicesTable) {
                        window.servicesTable.ajax.reload(null, false);
                    }
                } else {
                    showToast('error', response.message || 'Failed to update service status');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error toggling service status:', error);
                showToast('error', 'Failed to update service status');
            }
        });
    }
}

function toggleShowInOrders(serviceId, checkbox) {

    // Check if jQuery is available
    if (typeof $ === 'undefined' || typeof $.ajax !== 'function') {
        console.error('❌ jQuery not available for AJAX request');
        showToast('error', 'Unable to update service visibility - jQuery not loaded');
        return;
    }
    
    // Make AJAX request to toggle show in orders
    $.ajax({
        url: '<?= base_url('service_orders/toggle_show_in_orders') ?>',
        type: 'POST',
        data: {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            service_id: serviceId,
            show_in_orders: checkbox.checked ? 1 : 0
        },
        success: function(response) {
            if (response.success) {
                showToast('success', response.message || 'Service visibility updated successfully');
            } else {
                showToast('error', response.message || 'Failed to update service visibility');
                // Revert checkbox state
                checkbox.checked = !checkbox.checked;
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Error toggling show in orders:', error);
            showToast('error', 'Failed to update service visibility');
            // Revert checkbox state
            checkbox.checked = !checkbox.checked;
        }
    });
}

function deleteService(serviceId) {
    // Check if required libraries are available
    if (typeof Swal === 'undefined') {
    if (confirm('Are you sure you want to delete this service? This action cannot be undone!')) {
            proceedWithDeleteService(serviceId);
        }
        return;
    }

    Swal.fire({
        title: 'Delete Service?',
        text: 'Are you sure you want to delete this service? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            proceedWithDeleteService(serviceId);
        }
    });
}

function proceedWithDeleteService(serviceId) {
        // Check if jQuery is available
        if (typeof $ === 'undefined' || typeof $.ajax !== 'function') {
        console.error('❌ jQuery not available for service deletion');
        alert('Unable to delete service - jQuery not available');
            return;
        }
        
    // Make AJAX request to delete service using the correct endpoint
        $.ajax({
        url: '<?= base_url('service_orders_services/delete') ?>/' + serviceId,
            type: 'POST',
            data: {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
            console.log('✅ Service deletion response:', response);
            
                if (response.success) {
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
                    showToast('success', response.message || 'Service deleted successfully');
                }
                
                // Refresh services table
                    if (window.servicesTable) {
                    console.log('🔄 Refreshing services table after deletion...');
                        window.servicesTable.ajax.reload(null, false);
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
                    showToast('error', response.message || 'Failed to delete service');
                }
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error deleting service:', error);
            console.error('XHR:', xhr);
            
            let errorMessage = 'Failed to delete service';
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
                showToast('error', errorMessage);
            }
        }
    });
}

// Deleted Orders Management Functions
function restoreOrder(orderId) {
    if (confirm('Are you sure you want to restore this order?')) {

        // Check if jQuery is available
        if (typeof $ === 'undefined' || typeof $.ajax !== 'function') {
            console.error('❌ jQuery not available for AJAX request');
            showToast('error', 'Unable to restore order - jQuery not loaded');
            return;
        }
        
        // Make AJAX request to restore order
        $.ajax({
            url: '<?= base_url('service_orders/restore_order') ?>',
            type: 'POST',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                order_id: orderId
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message || 'Order restored successfully');
                    if (window.deletedOrdersTable) {
                        window.deletedOrdersTable.ajax.reload(null, false);
                    }
                    // Refresh other tables to show restored order
                    refreshAllTables();
                } else {
                    showToast('error', response.message || 'Failed to restore order');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error restoring order:', error);
                showToast('error', 'Failed to restore order');
            }
        });
    }
}

function permanentDeleteOrder(orderId) {
    if (confirm('Are you sure you want to permanently delete this order? This action cannot be undone!')) {

        // Check if jQuery is available
        if (typeof $ === 'undefined' || typeof $.ajax !== 'function') {
            console.error('❌ jQuery not available for AJAX request');
            showToast('error', 'Unable to permanently delete order - jQuery not loaded');
            return;
        }
        
        // Make AJAX request to permanently delete order
        $.ajax({
            url: '<?= base_url('service_orders/permanent_delete_order') ?>',
            type: 'POST',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                order_id: orderId
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message || 'Order permanently deleted');
                    if (window.deletedOrdersTable) {
                        window.deletedOrdersTable.ajax.reload(null, false);
                    }
                } else {
                    showToast('error', response.message || 'Failed to permanently delete order');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error permanently deleting order:', error);
                showToast('error', 'Failed to permanently delete order');
            }
        });
    }
}

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

// =====================================================================
// EVENT HANDLERS SETUP COMPLETE
// =====================================================================

</script>

<?= $this->endSection() ?> 

