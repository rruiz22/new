<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>
Recon Orders
<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>
Recon Orders
<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= lang('App.dashboard') ?></a></li>
<li class="breadcrumb-item active">Recon Orders</li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS -->
<?= $this->include('partials/datatables-styles') ?>

<!-- Custom styles for ReconOrders module -->
<style>
.nav-tabs-custom .nav-link {
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}
.nav-tabs-custom .nav-link.active {
    border-bottom-color: var(--bs-primary);
    background-color: transparent;
}
.tab-content {
    min-height: 400px;
}
.card {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.125);
}
.btn-group .btn {
    margin-right: 2px;
}
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}
.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}
.badge {
    font-size: 0.75em;
}
.spinner-border {
    width: 2rem;
    height: 2rem;
}
.modal-xl {
    max-width: 90%;
}
@media (max-width: 768px) {
    .nav-tabs-custom {
        flex-wrap: wrap;
    }
    .nav-tabs-custom .nav-link {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
}

/* Clickable table rows styling */
.table tbody tr {
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.08) !important;
}

.table tbody tr[style*="cursor: pointer"]:hover {
    background-color: rgba(64, 81, 137, 0.12) !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.table tbody tr[style*="cursor: pointer"]:active {
    transform: translateY(0);
    background-color: rgba(64, 81, 137, 0.15) !important;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 16px;">
            <!-- Filters Section -->
            <div class="card-header border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title mb-0 me-3 fs-1 fw-bold text-primary">Recon Orders</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reconOrderModal">
                            <i data-feather="plus" class="icon-sm me-1"></i> Add Recon Order
                        </button>
                    </div>
                </div>
                
                <!-- Filters Accordion -->
                <div class="accordion" id="filtersAccordion">
                    <div class="accordion-item border">
                        <h6 class="accordion-header mb-0" id="filtersHeading">
                            <button class="accordion-button collapsed py-2 px-3 shadow-none" type="button" 
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
                                            <!-- Las opciones se cargarán via AJAX -->
                                        </select>
                                    </div>

                                    <!-- Filtro de Estado -->
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <label class="form-label text-muted small mb-1"><?= lang('App.filter_by_status') ?></label>
                                        <select id="globalStatusFilter" class="form-select form-select-sm">
                                            <option value=""><?= lang('App.all_status') ?></option>
                                            <option value="pending"><?= lang('App.pending') ?></option>
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
                                                <button id="refreshAllTables" class="btn btn-outline-info btn-sm" onclick="manualRefreshReconOrders()">
                                                    <i data-feather="refresh-cw" class="icon-sm me-1"></i> <?= lang('App.refresh') ?>
                                                </button>
                                            </div>
                                            <small class="text-muted d-none d-md-block">
                                                <i data-feather="info" class="icon-xs me-1"></i>
                                                Filters apply to all tables
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
                <br>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs-custom" id="reconOrderTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#dashboard" role="tab">
                            <span class="fw-semibold"><i data-feather="home" class="icon-sm me-1"></i> <?= lang('App.dashboard') ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="today-tab" data-bs-toggle="tab" href="#today" role="tab">
                            <span class="fw-semibold"><i data-feather="calendar" class="icon-sm me-1"></i> Today's Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="all-orders-tab" data-bs-toggle="tab" href="#all-orders" role="tab">
                            <span class="fw-semibold"><i data-feather="list" class="icon-sm me-1"></i> All Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="services-tab" data-bs-toggle="tab" href="#services" role="tab">
                            <span class="fw-semibold"><i data-feather="package" class="icon-sm me-1"></i> Services</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="vehicles-tab" data-bs-toggle="tab" href="#vehicles" role="tab">
                            <span class="fw-semibold"><i data-feather="truck" class="icon-sm me-1"></i> Vehicles</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="deleted-tab" data-bs-toggle="tab" href="#deleted" role="tab">
                            <span class="fw-semibold"><i data-feather="trash-2" class="icon-sm me-1"></i> <?= lang('App.deleted') ?></span>
                        </a>
                    </li>
                </ul>
                <br><br>

                <!-- Tab content -->
                <div class="tab-content py-4">
                    <div class="tab-pane active show" id="dashboard" role="tabpanel">
                        <?= $this->include('Modules\ReconOrders\Views\recon_orders/dashboard_content') ?>
                    </div>

                    <div class="tab-pane" id="today" role="tabpanel">
                        <?= $this->include('Modules\ReconOrders\Views\recon_orders/today_content') ?>
                    </div>

                    <div class="tab-pane" id="all-orders" role="tabpanel">
                        <?= $this->include('Modules\ReconOrders\Views\recon_orders/all_orders_content') ?>
                    </div>

                    <div class="tab-pane" id="services" role="tabpanel">
                        <?= $this->include('Modules\ReconOrders\Views\recon_orders/services_content') ?>
                    </div>

                   
                    
                    <div class="tab-pane" id="deleted" role="tabpanel">
                        <?= $this->include('Modules\ReconOrders\Views\recon_orders/deleted_content') ?>
                    </div>

 
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recon Order Modal -->
<div class="modal fade recon-order-modal" id="reconOrderModal" tabindex="-1" role="dialog" aria-labelledby="reconOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reconOrderModalLabel">
                    <i class="fas fa-search me-2"></i>New Recon Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-content">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden"><?= lang('App.loading') ?></span>
                        </div>
                        <p class="mt-2 text-muted"><?= lang('App.loading_form') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Store active requests to prevent conflicts
window.activeTabRequests = window.activeTabRequests || {};

// Verify jQuery is available
if (typeof $ === 'undefined') {
    console.error('❌ jQuery is not loaded! ReconOrders will not function properly.');
    alert('jQuery is required for ReconOrders to function properly. Please refresh the page.');
} else {
    // jQuery is available
}

function isValidTab(tab) {
    var validTabs = ['dashboard', 'today', 'all-orders', 'deleted', 'services', 'vehicles'];
    return validTabs.includes(tab);
}

// Wait for jQuery to be available before initializing
function waitForJQuery(callback) {
    if (typeof $ !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForJQuery(callback);
        }, 100);
    }
}

waitForJQuery(function() {
    $(document).ready(function() {
        // Starting ReconOrders initialization
        
        // Check if there's a saved active tab first
        var activeTab = localStorage.getItem('recon_orders_active_tab');
        var initialTab = (activeTab && isValidTab(activeTab)) ? activeTab : 'dashboard';
        
        // Log the restored tab
        if (activeTab && isValidTab(activeTab)) {
            // Restored active tab from localStorage
        } else {
            // Using default tab (no valid saved tab)
        }
        
        // Set active tab if not dashboard
        if (initialTab !== 'dashboard') {
            // Activating tab
            // Remove active from all tabs and tab panes
            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('active show');
            
            // Activate the saved tab
            $('a[href="#' + initialTab + '"]').addClass('active');
            $('#' + initialTab).addClass('active show');
        }
        
        // Initialize all tables
        setTimeout(function() {
            // Initializing all tables
            
            // Initialize based on active tab and all tables for proper functionality
            try {
                if (typeof window.initializeDashboardTable === 'function') {
                    window.initializeDashboardTable();
                } else {
                    console.warn('⚠️ initializeDashboardTable function not found');
                }
                
                if (typeof window.initializeTodayTable === 'function') {
                    window.initializeTodayTable();
                } else {
                    console.warn('⚠️ initializeTodayTable function not found');
                }
                
                if (typeof window.initializeAllOrdersTable === 'function') {
                    window.initializeAllOrdersTable();
                } else {
                    console.warn('⚠️ initializeAllOrdersTable function not found');
                }
                
                if (typeof window.initializeServicesTable === 'function') {
                    window.initializeServicesTable();
                } else {
                    console.warn('⚠️ initializeServicesTable function not found');
                }
                
                // Initialize deleted table only when tab is visible
                if (initialTab === 'deleted') {
                    // Deleted tab is active on load, initializing table
                    // Wait for tab to be fully visible
                    setTimeout(() => {
                        if (typeof window.initializeDeletedOrdersTable === 'function') {
                            window.initializeDeletedOrdersTable();
                        } else {
                            console.warn('⚠️ initializeDeletedOrdersTable function not found');
                        }
                    }, 200);
                }
                
                // Initialize deleted table when tab is activated
                $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                    const target = $(e.target).attr("href");
                    if (target === '#deleted') {
                        // Deleted tab activated, initializing table
                        // Wait for tab transition to complete
                        setTimeout(() => {
                            if (typeof window.initializeDeletedOrdersTable === 'function') {
                                window.initializeDeletedOrdersTable();
                            }
                        }, 100);
                    }
                });
                
                // All tables initialized
            } catch (error) {
                console.error('❌ Error initializing tables:', error);
            }
        }, 500); // Increased delay to 500ms to ensure all functions are loaded
        
        // Handle tab clicks
        $('#reconOrderTabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr('href').substring(1); // Remove the '#'
            
            // Tab switched, save active tab
            localStorage.setItem('recon_orders_active_tab', target);
            
            // Update page title
            var tabNames = {
                'dashboard': '<?= lang('App.dashboard') ?>',
                'today': 'Today\'s Orders',
                'all-orders': 'All Orders',
                'deleted': '<?= lang('App.deleted') ?>',
                'services': 'Services',
                'vehicles': 'Vehicles'
            };
            
            document.title = 'Recon Orders - ' + (tabNames[target] || tabNames['dashboard']);
        });
        
        // Handle modal loading
        $('#reconOrderModal').on('show.bs.modal', function (e) {
            var $modal = $(this);
            var $content = $modal.find('#modal-content');
            var orderId = $modal.data('order-id') || null;
            
            // Update modal title
            var title = orderId ? 
                '<i class="fas fa-edit me-2"></i>Edit Recon Order' : 
                '<i class="fas fa-plus me-2"></i>New Recon Order';
            $('#reconOrderModalLabel').html(title);
            
            // Show loading
            $content.html(`
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only"><?= lang('App.loading') ?></span>
                    </div>
                    <p class="mt-2"><?= lang('App.loading_form') ?></p>
                </div>
            `);
            
            // Load modal content
            var url = orderId ? 
                '<?= base_url('recon_orders/modal_edit/') ?>' + orderId :
                '<?= base_url('recon_orders/modal_form') ?>';
                
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $content.html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Modal loading error:', error);
                    
                    var errorMessage = 'Error loading form';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        errorMessage = 'Order not found';
                    }
                    
                    $content.html(`
                        <div class="alert alert-danger m-3">
                            <h5><?= lang('App.error') ?></h5>
                            <p>${errorMessage}</p>
                        </div>
                    `);
                }
            });
        });
        
        // Clear modal data when closed
        $('#reconOrderModal').on('hidden.bs.modal', function () {
            $(this).removeData('order-id');
        });
        

        
        // Initialize global filters system
        if (typeof initializeGlobalFilters === 'function') {
            initializeGlobalFilters();
        }
        
        // Add debug info to console
        // ReconOrders module loaded successfully
    });
});
</script>

<!-- SweetAlert2 handler removed to prevent conflicts -->

<script>
// Global ReconOrders Action Functions
window.editReconOrder = function(id) {
    if (!id) {
        showToast('Invalid order ID', 'error');
        return;
    }
    
    // Set the order ID for the modal
    $('#reconOrderModal').data('order-id', id);
    
    // Show the modal (this will trigger the loading logic)
    $('#reconOrderModal').modal('show');
};

window.viewReconOrder = function(id) {
    if (!id) {
        showToast('Invalid order ID', 'error');
        return;
    }
    
    // Redirect to the view page
    window.location.href = '<?= base_url('recon_orders/view/') ?>' + id;
};

window.deleteReconOrder = function(id) {
    if (!id) {
        showToast('Invalid order ID', 'error');
        return;
    }
    
    // Use global confirmation dialog if available
    if (typeof window.showConfirmDialog === 'function') {
        window.showConfirmDialog(
            '<?= lang('App.are_you_sure') ?>',
            'Are you sure you want to delete this recon order?',
            '<?= lang('App.yes_delete') ?>',
            '<?= lang('App.cancel') ?>'
        ).then((result) => {
            if (result.isConfirmed) {
                performDeleteOrder(id);
            }
        });
    } else if (typeof Swal !== 'undefined') {
        // Fallback to direct Swal usage
        Swal.fire({
            title: '<?= lang('App.are_you_sure') ?>',
            text: 'Are you sure you want to delete this recon order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?= lang('App.yes_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                performDeleteOrder(id);
            }
        });
    } else {
        // Final fallback to confirm
        if (confirm('Are you sure you want to delete this recon order?')) {
            performDeleteOrder(id);
        }
    }
};

function performDeleteOrder(id) {
    $.ajax({
        url: '<?= base_url('recon_orders/delete/') ?>' + id,
        type: 'DELETE',
        success: function(response) {
            if (response.success) {
                showToast(response.message || 'Recon order deleted successfully', 'success');
                refreshAllReconOrdersData();
                // Order deleted and all data refreshed
            } else {
                showToast(response.message || 'Failed to delete recon order', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Delete error:', error);
            showToast('An error occurred while deleting the order', 'error');
        }
    });
}

// Comprehensive refresh function for all ReconOrders data
window.refreshAllReconOrdersData = function(options = {}) {
    var showToast = options.showToast || false;
    var showProgress = options.showProgress || false;
    var progressToast = null;
    
    // Starting ReconOrders data refresh
    
    // Show progress indicator if requested
    if (showProgress && typeof window.showToast === 'function') {
        progressToast = window.showToast('🔄 Refreshing tables...', 'info', { duration: 0 });
    }
    
    // Refresh all DataTables that are currently loaded - including vehicles tab tables
    var tableSelectors = [
        '#dashboard-table',
        '#today-table', 
        '#all-orders-table',
        '#deleted-table',
        '#servicesTable',
        '#inventoryTable',
        '#inventoryOrdersTable',
        '#allOrdersTable'
    ];
    
    var refreshedCount = 0;
    var totalTables = 0;
    
    tableSelectors.forEach(function(selector) {
        var table = document.querySelector(selector);
        // Checking table
        if (table && $.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
            totalTables++;
            $(table).DataTable().ajax.reload(function() {
                refreshedCount++;
                // Refreshed DataTable
                
                // If all tables are refreshed, hide progress and show completion
                if (refreshedCount === totalTables) {
                    if (progressToast && typeof progressToast.close === 'function') {
                        progressToast.close();
                    }
                    if (showToast && typeof window.showToast === 'function') {
                        window.showToast(`✅ ${totalTables} tables refreshed successfully!`, 'success');
                    }
                    // All ReconOrders tables refresh completed
                }
            }, false);
        } else if (table) {
            console.warn('⚠️ Table found but not initialized as DataTable:', selector);
        } else {
            console.warn('⚠️ Table not found:', selector);
        }
    });
    
    // If no tables were found to refresh, hide progress immediately
    if (totalTables === 0) {
        if (progressToast && typeof progressToast.close === 'function') {
            progressToast.close();
        }
        // No DataTables found to refresh
    }
    
    // Started refresh for tables
};

// Manual refresh function for buttons
window.manualRefreshReconOrders = function() {
    // Manual refresh requested
    refreshAllReconOrdersData({ showToast: true });
};

// Global toast function - Centralized and clean
window.showToast = function(message, type) {
    // Validate parameters
    if (!message || typeof message !== 'string') {
        console.error('❌ Invalid message parameter:', message);
        message = 'Unknown message';
    }
    
    if (!type || typeof type !== 'string') {
        type = 'info';
    }
    
    const icon = type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info';
    
    if (typeof Swal !== 'undefined') {
        try {
            Swal.fire({
                icon: icon,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        } catch (error) {
            console.error('❌ Error showing toast:', error);
            alert(`${type.toUpperCase()}: ${message}`);
        }
    } else {
        console.error('❌ SweetAlert2 not available in index.php');
        alert(`${type.toUpperCase()}: ${message}`);
    }
};

// Global confirmation dialog
window.showConfirmDialog = function(title, text, confirmText = 'Yes', cancelText = 'Cancel') {
    if (typeof Swal !== 'undefined') {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            reverseButtons: true
        });
    } else {
        return Promise.resolve({ isConfirmed: confirm(text) });
    }
};

// Global filters system
window.globalFilters = {
    client: '',
    status: '',
    dateFrom: '',
    dateTo: ''
};

function initializeGlobalFilters() {
    // Load saved filters from localStorage
    loadSavedFilters();
    
    // Load filter options via AJAX
    loadFilterOptions();
    
    // Setup event listeners
    setupFilterEventListeners();
    
    // Update active filters counter
    updateActiveFiltersCounter();
}

function loadSavedFilters() {
    const savedClient = localStorage.getItem('reconOrdersGlobalClientFilter');
    const savedStatus = localStorage.getItem('reconOrdersGlobalStatusFilter');
    const savedDateFrom = localStorage.getItem('reconOrdersGlobalDateFromFilter');
    const savedDateTo = localStorage.getItem('reconOrdersGlobalDateToFilter');
    
    window.globalFilters.client = savedClient || '';
    window.globalFilters.status = savedStatus || '';
    window.globalFilters.dateFrom = savedDateFrom || '';
    window.globalFilters.dateTo = savedDateTo || '';
    
    document.getElementById('globalClientFilter').value = window.globalFilters.client;
    document.getElementById('globalStatusFilter').value = window.globalFilters.status;
    document.getElementById('globalDateFromFilter').value = window.globalFilters.dateFrom;
    document.getElementById('globalDateToFilter').value = window.globalFilters.dateTo;
}

function loadFilterOptions() {
    $.get('<?= base_url('recon_orders/getClients') ?>')
        .done(function(data) {
            if (data && data.clients && Array.isArray(data.clients)) {
                const clientFilter = $('#globalClientFilter');
                clientFilter.find('option:not(:first)').remove();
                
                data.clients.forEach(function(client) {
                    clientFilter.append('<option value="' + client.id + '">' + client.name + '</option>');
                });
                
                clientFilter.val(window.globalFilters.client);
            }
        })
        .fail(function() {
            console.error('Error loading filter options');
        });
}

function setupFilterEventListeners() {
    document.getElementById('globalClientFilter').addEventListener('change', function() {
        window.globalFilters.client = this.value;
        localStorage.setItem('reconOrdersGlobalClientFilter', this.value);
        updateActiveFiltersCounter();
    });
    
    document.getElementById('globalStatusFilter').addEventListener('change', function() {
        window.globalFilters.status = this.value;
        localStorage.setItem('reconOrdersGlobalStatusFilter', this.value);
        updateActiveFiltersCounter();
    });
    
    document.getElementById('globalDateFromFilter').addEventListener('change', function() {
        window.globalFilters.dateFrom = this.value;
        localStorage.setItem('reconOrdersGlobalDateFromFilter', this.value);
        updateActiveFiltersCounter();
    });
    
    document.getElementById('globalDateToFilter').addEventListener('change', function() {
        window.globalFilters.dateTo = this.value;
        localStorage.setItem('reconOrdersGlobalDateToFilter', this.value);
        updateActiveFiltersCounter();
    });
    
    document.getElementById('applyGlobalFilters').addEventListener('click', function() {
        showToast('Filters applied to all tables', 'success');
        refreshAllReconOrdersData();
    });
    
    document.getElementById('clearGlobalFilters').addEventListener('click', function() {
        clearAllFilters();
    });
}

function updateActiveFiltersCounter() {
    const activeCount = Object.values(window.globalFilters).filter(value => value && value !== '').length;
    const badge = document.getElementById('activeFiltersCount');
    
    if (activeCount > 0) {
        badge.textContent = activeCount;
        badge.classList.remove('d-none');
    } else {
        badge.classList.add('d-none');
    }
}

function clearAllFilters() {
    window.globalFilters = {
        client: '',
        status: '',
        dateFrom: '',
        dateTo: ''
    };
    
    document.getElementById('globalClientFilter').value = '';
    document.getElementById('globalStatusFilter').value = '';
    document.getElementById('globalDateFromFilter').value = '';
    document.getElementById('globalDateToFilter').value = '';
    
    localStorage.removeItem('reconOrdersGlobalClientFilter');
    localStorage.removeItem('reconOrdersGlobalStatusFilter');
    localStorage.removeItem('reconOrdersGlobalDateFromFilter');
    localStorage.removeItem('reconOrdersGlobalDateToFilter');
    
    updateActiveFiltersCounter();
    showToast('All filters cleared', 'success');
    refreshAllReconOrdersData();
}

// LocalStorage Tab Management Functions
window.reconOrdersTabManager = {
    // Get current active tab
    getCurrentTab: function() {
        return localStorage.getItem('recon_orders_active_tab') || 'dashboard';
    },
    
    // Set active tab
    setActiveTab: function(tabName) {
        if (isValidTab(tabName)) {
            localStorage.setItem('recon_orders_active_tab', tabName);
            // Tab saved to localStorage
            return true;
        } else {
            console.warn('⚠️ Invalid tab name:', tabName);
            return false;
        }
    },
    
    // Reset to default tab
    resetToDefault: function() {
        localStorage.removeItem('recon_orders_active_tab');
        // Reset to default tab (dashboard)
        setTimeout(function() {
            window.location.reload();
        }, 100);
    },
    
    // Get all valid tabs
    getValidTabs: function() {
        return ['dashboard', 'today', 'all-orders', 'deleted', 'services', 'vehicles'];
    },
    
    // Switch to specific tab programmatically
    switchToTab: function(tabName) {
        if (isValidTab(tabName)) {
            $('#' + tabName + '-tab').tab('show');
            return true;
        } else {
            console.warn('⚠️ Cannot switch to invalid tab:', tabName);
            return false;
        }
    },
    
    // Clear all localStorage data for ReconOrders
    clearAllData: function() {
        // Clear tab preference
        localStorage.removeItem('recon_orders_active_tab');
        
        // Clear filters
        localStorage.removeItem('reconOrdersGlobalClientFilter');
        localStorage.removeItem('reconOrdersGlobalStatusFilter');
        localStorage.removeItem('reconOrdersGlobalDateFromFilter');
        localStorage.removeItem('reconOrdersGlobalDateToFilter');
        
        // All ReconOrders localStorage data cleared
        
        // Optionally reload the page
        if (confirm('All saved preferences cleared. Reload page to reset to defaults?')) {
            window.location.reload();
        }
    }
};

// Make functions globally available for debugging
window.switchToTab = function(tabName) {
    return window.reconOrdersTabManager.switchToTab(tabName);
};

window.getCurrentTab = function() {
    return window.reconOrdersTabManager.getCurrentTab();
};

window.clearReconOrdersData = function() {
    return window.reconOrdersTabManager.clearAllData();
};

</script>

<!-- DataTables scripts already loaded globally in default.php -->

<?= $this->endSection() ?> 