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

                    <div class="tab-pane" id="vehicles" role="tabpanel">
                        <?= $this->include('Modules\ReconOrders\Views\recon_orders/vehicles_content') ?>
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
    console.log('✅ jQuery is available');
}

function isValidTab(tab) {
    var validTabs = ['dashboard', 'today', 'all-orders', 'deleted', 'services'];
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
        console.log('🚀 Starting ReconOrders initialization...');
        
        // Check if there's a saved active tab first
        var activeTab = localStorage.getItem('recon_orders_active_tab');
        var initialTab = (activeTab && isValidTab(activeTab)) ? activeTab : 'dashboard';
        
        // Log the restored tab
        if (activeTab && isValidTab(activeTab)) {
            console.log('💾 Restored active tab from localStorage:', activeTab);
        } else {
            console.log('🎯 Using default tab (no valid saved tab):', initialTab);
        }
        
        // Set active tab if not dashboard
        if (initialTab !== 'dashboard') {
            console.log('🔄 Activating tab:', initialTab);
            // Remove active from all tabs and tab panes
            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('active show');
            
            // Activate the saved tab
            $('a[href="#' + initialTab + '"]').addClass('active');
            $('#' + initialTab).addClass('active show');
        }
        
        // Initialize all tables
        setTimeout(function() {
            console.log('🔄 Initializing all tables...');
            
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
                
                if (typeof window.initializeDeletedOrdersTable === 'function') {
                    window.initializeDeletedOrdersTable();
                } else {
                    console.warn('⚠️ initializeDeletedOrdersTable function not found');
                }
                
                console.log('✅ All tables initialized');
            } catch (error) {
                console.error('❌ Error initializing tables:', error);
            }
        }, 500); // Increased delay to 500ms to ensure all functions are loaded
        
        // Handle tab clicks
        $('#reconOrderTabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr('href').substring(1); // Remove the '#'
            
            console.log('🔄 Tab switched to:', target);
            
            // Save active tab
            localStorage.setItem('recon_orders_active_tab', target);
            console.log('💾 Saved active tab to localStorage:', target);
            
            // Update page title
            var tabNames = {
                'dashboard': '<?= lang('App.dashboard') ?>',
                'today': 'Today\'s Orders',
                'all-orders': 'All Orders',
                'deleted': '<?= lang('App.deleted') ?>',
                'services': 'Services'
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
        console.log('✅ ReconOrders module loaded successfully!');
        console.log('🔧 Debug commands available:');
        console.log('   - getCurrentTab() - Get current active tab');
        console.log('   - switchToTab(tabName) - Switch to specific tab');
        console.log('   - clearReconOrdersData() - Clear all saved data');
    });
});

// Global ReconOrders Action Functions
window.editReconOrder = function(id) {
    if (!id) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    // Set the order ID for the modal
    $('#reconOrderModal').data('order-id', id);
    
    // Show the modal (this will trigger the loading logic)
    $('#reconOrderModal').modal('show');
};

window.viewReconOrder = function(id) {
    if (!id) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    // Redirect to the view page
    window.location.href = '<?= base_url('recon_orders/view/') ?>' + id;
};

window.deleteReconOrder = function(id) {
    if (!id) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    // Show confirmation dialog
    if (typeof Swal !== 'undefined') {
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
                showToast('success', response.message || 'Recon order deleted successfully');
                refreshAllReconOrdersData();
                console.log('✅ Order deleted and all data refreshed');
            } else {
                showToast('error', response.message || 'Failed to delete recon order');
            }
        },
        error: function(xhr, status, error) {
            console.error('Delete error:', error);
            showToast('error', 'An error occurred while deleting the order');
        }
    });
}

// Comprehensive refresh function for all ReconOrders data
window.refreshAllReconOrdersData = function(options = {}) {
    var showToast = options.showToast || false;
    
    console.log('🔄 Starting ReconOrders data refresh...');
    console.log('🔍 jQuery available:', typeof $);
    console.log('🔍 DataTable available:', typeof $.fn.DataTable);
    console.log('🔍 Active tab:', document.querySelector('.nav-tabs .nav-link.active')?.getAttribute('href'));
    
    // Refresh all DataTables that are currently loaded - corrected table IDs
    var tableSelectors = [
        '#dashboard-table',
        '#today-table', 
        '#all-orders-table',
        '#deleted-table',
        '#servicesTable'
    ];
    
    tableSelectors.forEach(function(selector) {
        var table = document.querySelector(selector);
        console.log('🔍 Checking table:', selector, 'Found:', !!table, 'IsDataTable:', table && $.fn.DataTable && $.fn.DataTable.isDataTable(table));
        if (table && $.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
            $(table).DataTable().ajax.reload(function() {
                console.log('✅ Refreshed DataTable:', selector);
            }, false);
        } else if (table) {
            console.warn('⚠️ Table found but not initialized as DataTable:', selector);
        } else {
            console.warn('⚠️ Table not found:', selector);
        }
    });
    
    if (showToast) {
        showToast('success', 'Data refreshed successfully!');
    }
    
    console.log('✅ ReconOrders refresh completed');
};

// Manual refresh function for buttons
window.manualRefreshReconOrders = function() {
    console.log('🔄 Manual refresh requested');
    refreshAllReconOrdersData({ showToast: true });
};

// Global toast function
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
        showToast('success', 'Filters applied to all tables');
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
    showToast('success', 'All filters cleared');
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
            console.log('💾 Tab saved to localStorage:', tabName);
            return true;
        } else {
            console.warn('⚠️ Invalid tab name:', tabName);
            return false;
        }
    },
    
    // Reset to default tab
    resetToDefault: function() {
        localStorage.removeItem('recon_orders_active_tab');
        console.log('🔄 Reset to default tab (dashboard)');
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
        
        console.log('🧹 All ReconOrders localStorage data cleared');
        
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
<?= $this->endSection() ?> 