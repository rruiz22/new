<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>
<?= lang('App.car_wash_orders') ?>
<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>
<?= lang('App.car_wash_orders') ?>
<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= lang('App.dashboard') ?></a></li>
<li class="breadcrumb-item active"><?= lang('App.car_wash_orders') ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Custom styles for CarWash module -->
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
                    <h4 class="card-title mb-0"><?= lang('App.car_wash_orders') ?></h4>
                    <div class="flex-shrink-0">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#carWashModal">
                            <i data-feather="plus" class="icon-sm me-1"></i> <?= lang('App.add_car_wash_order') ?>
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
                                            <option value="confirmed">Confirmed</option>
                                            <option value="in_progress"><?= lang('App.in_progress') ?></option>
                                            <option value="completed"><?= lang('App.completed') ?></option>
                                            <option value="cancelled"><?= lang('App.cancelled') ?></option>
                                        </select>
                                    </div>

                                    <!-- Filtro de Servicio -->
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <label class="form-label text-muted small mb-1">Filter by Service</label>
                                        <select id="globalServiceFilter" class="form-select form-select-sm">
                                            <option value="">All Services</option>
                                            <!-- Las opciones se cargarán via AJAX -->
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
                                                <button id="refreshAllTables" class="btn btn-outline-info btn-sm" onclick="manualRefreshCarWash()">
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

                                <!-- Configuraciones de Validación (separado de filtros) -->
                                <hr class="my-3">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-muted mb-2">
                                            <i class="fas fa-cogs me-1"></i><?= lang('App.validation_settings') ?>
                                        </h6>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <!-- Selector de Tiempo para Duplicados -->
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <label class="form-label text-muted small mb-1">
                                            <i class="fas fa-clock me-1"></i><?= lang('App.duplicate_check_time') ?>
                                        </label>
                                        <select id="duplicateCheckTime" class="form-select form-select-sm">
                                            <option value="5"><?= lang('App.minutes_5') ?></option>
                                            <option value="10"><?= lang('App.minutes_10') ?></option>
                                            <option value="15"><?= lang('App.minutes_15') ?></option>
                                        </select>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-info-circle me-1"></i><?= lang('App.select_duplicate_check_time') ?>
                                        </small>
                                        <small class="text-success d-block">
                                            <i class="fas fa-save me-1"></i><?= lang('App.auto_saved_locally') ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs-custom nav-justified" id="carWashTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#dashboard" role="tab" aria-controls="dashboard" aria-selected="true">
                            <i class="fas fa-tachometer-alt"></i> <?= lang('App.dashboard') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="today-tab" data-bs-toggle="tab" href="#today" role="tab" aria-controls="today" aria-selected="false">
                            <i class="fas fa-calendar-day"></i> Today's Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="all-orders-tab" data-bs-toggle="tab" href="#all-orders" role="tab" aria-controls="all-orders" aria-selected="false">
                            <i class="fas fa-list"></i> All Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="services-tab" data-bs-toggle="tab" href="#services" role="tab" aria-controls="services" aria-selected="false">
                            <i class="fas fa-cog"></i> <?= lang('App.services') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="deleted-tab" data-bs-toggle="tab" href="#deleted" role="tab" aria-controls="deleted" aria-selected="false">
                            <i class="fas fa-trash"></i> <?= lang('App.deleted') ?>
                        </a>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content p-3 text-muted" id="carWashTabContent">
                    <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                        <div id="dashboard-content">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only"><?= lang('App.loading') ?></span>
                                </div>
                                <p class="mt-2"><?= lang('App.loading_data') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="today" role="tabpanel" aria-labelledby="today-tab">
                        <div id="today-content">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only"><?= lang('App.loading') ?></span>
                                </div>
                                <p class="mt-2"><?= lang('App.loading_data') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="all-orders" role="tabpanel" aria-labelledby="all-orders-tab">
                        <div id="all-orders-content">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only"><?= lang('App.loading') ?></span>
                                </div>
                                <p class="mt-2"><?= lang('App.loading_data') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="services" role="tabpanel" aria-labelledby="services-tab">
                        <div id="services-content">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only"><?= lang('App.loading') ?></span>
                                </div>
                                <p class="mt-2"><?= lang('App.loading_data') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="deleted" role="tabpanel" aria-labelledby="deleted-tab">
                        <div id="deleted-content">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only"><?= lang('App.loading') ?></span>
                                </div>
                                <p class="mt-2"><?= lang('App.loading_data') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Car Wash Modal -->
<div class="modal fade car-wash-modal" id="carWashModal" tabindex="-1" role="dialog" aria-labelledby="carWashModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carWashModalLabel">
                    <i class="fas fa-car-wash me-2"></i><?= lang('App.new_car_wash_order') ?>
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

// Define loadTabContent function first, before it's used
window.loadTabContent = function(tabId, url) {
    var $content = $('#' + tabId + '-content');
    
    // Cancel any previous request for this tab
    if (window.activeTabRequests[tabId]) {
        window.activeTabRequests[tabId].abort();
        delete window.activeTabRequests[tabId];
    }
        
        // Show loading spinner
        $content.html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only"><?= lang('App.loading') ?></span>
                </div>
                <p class="mt-2"><?= lang('App.loading_data') ?></p>
            </div>
        `);
        
        // Load content via AJAX
    var xhr = $.ajax({
            url: url,
            type: 'GET',
        timeout: 30000, // 30 seconds timeout
            success: function(response) {
            // Remove from active requests
            delete window.activeTabRequests[tabId];
            
                $content.html(response);
                
                // Initialize any DataTables or other components
                if (tabId === 'services') {
                    // Services tab specific initialization
                    initializeServicesTab();
                }
            },
            error: function(xhr, status, error) {
            // Remove from active requests
            delete window.activeTabRequests[tabId];
            
            // Don't show error for aborted requests
            if (status === 'abort') {
                console.log('🚫 Request aborted for tab:', tabId, '(this is normal when switching tabs)');
                return;
            }
            
            // Log error for debugging
            console.error('❌ Error loading content for tab:', tabId, {
                status: status,
                error: error,
                xhr_status: xhr.status,
                xhr_response: xhr.responseText
            });
            
                var errorMessage = '';
                
                if (xhr.status === 404) {
                    errorMessage = '<?= lang('App.page_not_found') ?>';
                } else if (xhr.status === 403) {
                    // Forbidden
                    showToast('<?= lang('App.access_denied') ?>', 'error');
                errorMessage = '<?= lang('App.access_denied') ?>';
                } else if (xhr.status === 500) {
                    // Server error
                    console.error('Server Error:', xhr.responseText);
                    showToast('<?= lang('App.server_error') ?>', 'error');
                errorMessage = '<?= lang('App.server_error') ?>';
            } else if (xhr.status === 0) {
                errorMessage = 'Connection failed';
                } else {
                    errorMessage = '<?= lang('App.error_loading_content') ?>';
                }
                
                $content.html(`
                    <div class="alert alert-danger">
                        <h5><i class="fas fa-exclamation-triangle"></i> Error</h5>
                        <p>${errorMessage}</p>
                        <button class="btn btn-primary btn-sm" onclick="loadTabContent('${tabId}', '${url}')">
                            <i class="fas fa-redo"></i> <?= lang('App.retry') ?>
                        </button>
                    </div>
                `);
            }
        });
    
    // Store the request
    window.activeTabRequests[tabId] = xhr;
};

// Debug function to check active requests
window.getActiveTabRequests = function() {
    var activeCount = Object.keys(window.activeTabRequests).length;
    console.log('📊 Active Tab Requests (' + activeCount + '):', Object.keys(window.activeTabRequests));
    return window.activeTabRequests;
};

// Debug function to manually clean up requests
window.cleanupActiveRequests = function() {
    var cleaned = 0;
    Object.keys(window.activeTabRequests).forEach(function(tabId) {
        if (window.activeTabRequests[tabId]) {
            window.activeTabRequests[tabId].abort();
            delete window.activeTabRequests[tabId];
            cleaned++;
        }
    });
    console.log('🧹 Manually cleaned up ' + cleaned + ' active requests');
    return cleaned;
};
    
    function initializeServicesTab() {
        // This will be called when services tab content is loaded
        console.log('Services tab initialized');
    }
    
    function isValidTab(tab) {
        var validTabs = ['dashboard', 'today', 'all-orders', 'services', 'deleted'];
        return validTabs.includes(tab);
    }

$(document).ready(function() {
    // Check if there's a saved active tab first
    var activeTab = localStorage.getItem('carwash_active_tab');
    var initialTab = (activeTab && isValidTab(activeTab)) ? activeTab : 'dashboard';
    
    // Content mapping
    var contentMap = {
        'dashboard': '<?= base_url('car_wash/dashboard_content') ?>',
        'today': '<?= base_url('car_wash/today_content') ?>',
        'all-orders': '<?= base_url('car_wash/all_orders_content') ?>',
        'services': '<?= base_url('car_wash/services_content') ?>',
        'deleted': '<?= base_url('car_wash/deleted_content') ?>'
    };
    
    // Load initial content based on saved tab or dashboard
    setTimeout(function() {
        if (contentMap[initialTab]) {
            console.log('🔄 Loading initial tab:', initialTab);
            loadTabContent(initialTab, contentMap[initialTab]);
        }
        
        // Set active tab if not dashboard
        if (initialTab !== 'dashboard') {
            $('#' + initialTab + '-tab').tab('show');
        }
    }, 100); // Small delay to ensure DOM is ready
    
    // Handle tab clicks
    $('#carWashTabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr('href').substring(1); // Remove the '#'
        
        console.log('🔄 Tab switched to:', target);
        
        // Cancel any requests for other tabs when switching
        var cancelledRequests = 0;
        Object.keys(window.activeTabRequests).forEach(function(tabId) {
            if (tabId !== target && window.activeTabRequests[tabId]) {
                window.activeTabRequests[tabId].abort();
                delete window.activeTabRequests[tabId];
                cancelledRequests++;
            }
        });
        
        if (cancelledRequests > 0) {
            console.log('🚫 Cancelled ' + cancelledRequests + ' pending requests for other tabs');
        }
        
        if (contentMap[target]) {
            loadTabContent(target, contentMap[target]);
        }
        
        // Save active tab
        localStorage.setItem('carwash_active_tab', target);
        
        // Update page title
        var tabNames = {
            'dashboard': '<?= lang('App.dashboard') ?>',
                            'today': 'Today\'s Orders',
            'all-orders': 'All Orders',
            'services': '<?= lang('App.services') ?>',
            'deleted': '<?= lang('App.deleted') ?>'
        };
        
        document.title = '<?= lang('App.car_wash_orders') ?> - ' + (tabNames[target] || tabNames['dashboard']);
    });
    
    // Handle modal loading
    $('#carWashModal').on('show.bs.modal', function (e) {
        var $modal = $(this);
        var $content = $modal.find('#modal-content');
        var orderId = $modal.data('order-id') || null;
        
        // Update modal title
        var title = orderId ? 
            '<i class="fas fa-edit me-2"></i><?= lang('App.edit_car_wash_order') ?>' : 
            '<i class="fas fa-plus me-2"></i><?= lang('App.new_car_wash_order') ?>';
        $('#carWashModalLabel').html(title);
        
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
            '<?= base_url('car_wash/modal_edit/') ?>' + orderId :
            '<?= base_url('car_wash/modal_form') ?>';
            
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $content.html(response);
                
                // Force values after content is loaded (for edit mode)
                if (orderId) {
                    setTimeout(function() {
                        if (typeof window.forceCarWashValues === 'function') {
                            window.forceCarWashValues();
                            console.log('CarWash Modal: Forced values after AJAX load');
                        }
                    }, 200);
                }
            },
            error: function() {
                $content.html(`
                    <div class="alert alert-danger m-3">
                        <h5><?= lang('App.error') ?></h5>
                        <p><?= lang('App.error_loading_form') ?></p>
                    </div>
                `);
            }
        });
    });
    
    // Force values when modal is fully shown
    $('#carWashModal').on('shown.bs.modal', function () {
        var orderId = $(this).data('order-id');
        if (orderId && typeof window.forceCarWashValues === 'function') {
            setTimeout(function() {
                window.forceCarWashValues();
                console.log('CarWash Modal: Forced values on modal shown event');
            }, 100);
        }
    });
    
    // Clear modal data when closed
    $('#carWashModal').on('hidden.bs.modal', function () {
        $(this).removeData('order-id');
    });
    
    // Clean up active requests when leaving the page
    $(window).on('beforeunload', function() {
        var cleanedRequests = 0;
        Object.keys(window.activeTabRequests).forEach(function(tabId) {
            if (window.activeTabRequests[tabId]) {
                window.activeTabRequests[tabId].abort();
                delete window.activeTabRequests[tabId];
                cleanedRequests++;
            }
        });
        
        if (cleanedRequests > 0) {
            console.log('🧹 Cleaned up ' + cleanedRequests + ' active requests before page unload');
        }
    });
    
    // Also clean up when the page becomes hidden (mobile/tab switching)
    $(document).on('visibilitychange', function() {
        if (document.hidden) {
            // Page is hidden, clean up any unnecessary requests
            var hiddenCleanup = 0;
            Object.keys(window.activeTabRequests).forEach(function(tabId) {
                if (window.activeTabRequests[tabId]) {
                    window.activeTabRequests[tabId].abort();
                    delete window.activeTabRequests[tabId];
                    hiddenCleanup++;
                }
            });
            
            if (hiddenCleanup > 0) {
                console.log('🧹 Cleaned up ' + hiddenCleanup + ' requests on page hidden');
            }
        }
    });
});

// Global CarWash Action Functions
window.editCarWashOrder = function(id) {
    if (!id) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    // Set the order ID for the modal
    $('#carWashModal').data('order-id', id);
    
    // Show the modal (this will trigger the loading logic)
    $('#carWashModal').modal('show');
};

window.deleteCarWashOrder = function(id) {
    if (!id) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    // Show confirmation dialog
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '<?= lang('App.are_you_sure') ?>',
            text: '<?= lang('App.confirm_delete_order') ?>',
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
        // Fallback to native confirm
        if (confirm('Are you sure you want to delete this car wash order? This action can be undone from the deleted orders section.')) {
            performDeleteOrder(id);
        }
    }
};

window.restoreCarWashOrder = function(id) {
    if (!id) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    // Show confirmation dialog
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '<?= lang('App.restore_order') ?>',
            text: '<?= lang('App.confirm_restore_order') ?>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<?= lang('App.yes_restore') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                performRestoreOrder(id);
            }
        });
    } else {
        // Fallback to native confirm
        if (confirm('Are you sure you want to restore this car wash order?')) {
            performRestoreOrder(id);
        }
    }
};

window.permanentDeleteCarWashOrder = function(id) {
    if (!id) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    // Show confirmation dialog
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '<?= lang('App.permanent_delete') ?>',
            text: '<?= lang('App.confirm_permanent_delete') ?>',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<?= lang('App.yes_permanent_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                performPermanentDeleteOrder(id);
            }
        });
    } else {
        // Fallback to native confirm
        if (confirm('Are you sure you want to permanently delete this car wash order? This action CANNOT be undone!')) {
            performPermanentDeleteOrder(id);
        }
    }
};

function performDeleteOrder(id) {
    $.ajax({
        url: '<?= base_url('car_wash/delete') ?>',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            if (response.success) {
                showToast('success', response.message || 'Car wash order deleted successfully');
                // Comprehensive refresh
                refreshAllCarWashData();
                console.log('✅ Order deleted and all data refreshed');
            } else {
                showToast('error', response.message || 'Failed to delete car wash order');
            }
        },
        error: function(xhr, status, error) {
            console.error('Delete error:', error);
            showToast('error', 'An error occurred while deleting the order');
        }
    });
}

function performRestoreOrder(id) {
    // Disable restore button and show loading
    var $restoreBtn = $(`.restore-btn[data-id="${id}"]`);
    var originalHtml = $restoreBtn.html();
    $restoreBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
    
    $.ajax({
        url: '<?= base_url('car_wash/restore') ?>',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            if (response.success) {
                showToast('success', response.message || 'Car wash order restored successfully');
                // Comprehensive refresh
                refreshAllCarWashData();
                console.log('✅ Order restored and all data refreshed');
            } else {
                showToast('error', response.message || 'Failed to restore car wash order');
            }
        },
        error: function(xhr, status, error) {
            console.error('Restore error:', error);
            console.error('Response:', xhr.responseText);
            var errorMessage = 'An error occurred while restoring the order';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showToast('error', errorMessage);
        },
        complete: function() {
            // Re-enable button and restore original text
            $restoreBtn.prop('disabled', false).html(originalHtml);
        }
    });
}

function performPermanentDeleteOrder(id) {
    // Disable permanent delete button and show loading
    var $deleteBtn = $(`.permanent-delete-btn[data-id="${id}"]`);
    var originalHtml = $deleteBtn.html();
    $deleteBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
    
    $.ajax({
        url: '<?= base_url('car_wash/permanent-delete') ?>',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            if (response.success) {
                showToast('success', response.message || 'Car wash order permanently deleted');
                // Comprehensive refresh
                refreshAllCarWashData();
                console.log('✅ Order permanently deleted and all data refreshed');
            } else {
                showToast('error', response.message || 'Failed to permanently delete car wash order');
            }
        },
        error: function(xhr, status, error) {
            console.error('Permanent delete error:', error);
            console.error('Response:', xhr.responseText);
            var errorMessage = 'An error occurred while permanently deleting the order';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showToast('error', errorMessage);
        },
        complete: function() {
            // Re-enable button and restore original text
            $deleteBtn.prop('disabled', false).html(originalHtml);
        }
    });
}

function refreshCurrentTable() {
    // Get current active tab
    var activeTab = document.querySelector('#carWashTabs .nav-link.active');
    if (!activeTab) return;
    
    var tabId = activeTab.getAttribute('href').substring(1);
    
    // Refresh DataTables if they exist
    var tableSelectors = {
        'dashboard': '#dashboardTable',
        'today': '#todayTable',
        'all-orders': '#allOrdersTable',
        'deleted': '#deletedOrdersTable'
    };
    
    var tableSelector = tableSelectors[tabId];
    if (tableSelector) {
        var table = document.querySelector(tableSelector);
        if (table && $.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
            $(table).DataTable().ajax.reload(null, false); // false = don't reset pagination
        }
    }
    
    // Also refresh tab content if needed
    var contentMap = {
        'dashboard': '<?= base_url('car_wash/dashboard_content') ?>',
        'today': '<?= base_url('car_wash/today_content') ?>',
        'all-orders': '<?= base_url('car_wash/all_orders_content') ?>',
        'deleted': '<?= base_url('car_wash/deleted_content') ?>'
    };
    
    // Reload content for tables that don't use AJAX
    if (tabId === 'deleted' && contentMap[tabId]) {
        setTimeout(function() {
            loadTabContent(tabId, contentMap[tabId]);
        }, 500);
    }
}

// Comprehensive refresh function for all CarWash data
window.refreshAllCarWashData = function(options = {}) {
    var showToast = options.showToast || false;
    var force = options.force || false;
    
    console.log('🔄 Starting comprehensive CarWash data refresh...');
    
    // Throttling: prevent refreshes more frequent than 2 seconds
    var now = Date.now();
    if (window.lastCarWashRefresh && (now - window.lastCarWashRefresh) < 2000 && !force) {
        console.log('⏳ Refresh throttled, too soon since last refresh');
        return;
    }
    
    // Show visual indicator
    showRefreshIndicator(true);
    
    // Prevent multiple simultaneous refreshes
    if (window.carWashRefreshing && !force) {
        console.log('⏳ Refresh already in progress, skipping...');
        return;
    }
    
    window.carWashRefreshing = true;
    window.lastCarWashRefresh = now;
    
    var refreshPromises = [];
    
    // 1. Refresh all DataTables that are currently loaded
    var tableSelectors = [
        '#dashboardTable',
        '#todayTable', 
        '#allOrdersTable',
        '#deletedOrdersTable'
    ];
    
    tableSelectors.forEach(function(selector) {
        var table = document.querySelector(selector);
        if (table && $.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
            var promise = new Promise(function(resolve) {
                $(table).DataTable().ajax.reload(function() {
                    console.log('✅ Refreshed DataTable:', selector);
                    resolve();
                }, false);
            });
            refreshPromises.push(promise);
        }
    });
    
    // 2. Refresh Dashboard badges/stats
    if (typeof refreshDashboardBadges === 'function') {
        refreshDashboardBadges();
        console.log('✅ Refreshed dashboard badges');
    }
    
    // 3. Refresh current active tab content (for non-DataTable content)
    var activeTab = $('.nav-tabs .nav-link.active').attr('href');
    if (activeTab) {
        var tabId = activeTab.substring(1);
        
        // Special refresh for specific tabs that need content reload
        var contentMap = {
            'deleted': '<?= base_url('car_wash/deleted_content') ?>'
        };
        
        if (contentMap[tabId]) {
            setTimeout(function() {
                loadTabContent(tabId, contentMap[tabId]);
                console.log('✅ Refreshed tab content:', tabId);
            }, 300);
        }
    }
    
    // 4. Update any counters or badges visible across tabs
    updateTabCounters();
    
    // Wait for all DataTable refreshes to complete
    Promise.all(refreshPromises).then(function() {
        // 5. Refresh any global filters or stats
        setTimeout(function() {
            if (typeof updateGlobalStats === 'function') {
                updateGlobalStats();
            }
            
            // Hide visual indicator
            showRefreshIndicator(false);
            window.carWashRefreshing = false;
            
            if (showToast) {
                window.showToast('success', 'All data refreshed successfully!');
            }
            
            console.log('✅ CarWash comprehensive refresh completed');
        }, 200);
    }).catch(function(error) {
        console.error('❌ Error during refresh:', error);
        showRefreshIndicator(false);
        window.carWashRefreshing = false;
    });
};

// Show/hide refresh indicator
function showRefreshIndicator(show) {
    if (show) {
        // Add refresh indicator to active refresh buttons
        $('[id^="refresh"]').each(function() {
            var $btn = $(this);
            var $icon = $btn.find('i');
            if ($icon.length) {
                $icon.addClass('fa-spin');
                $btn.prop('disabled', true);
            }
        });
        
        // Add global refresh indicator
        if ($('#globalRefreshIndicator').length === 0) {
            $('body').append(`
                <div id="globalRefreshIndicator" style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: rgba(64, 81, 137, 0.9);
                    color: white;
                    padding: 8px 16px;
                    border-radius: 20px;
                    font-size: 12px;
                    z-index: 9999;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                    display: flex;
                    align-items: center;
                    gap: 8px;
                ">
                    <i class="fas fa-sync-alt fa-spin"></i>
                    Refreshing data...
                </div>
            `);
        }
    } else {
        // Remove spin from refresh buttons
        $('[id^="refresh"]').each(function() {
            var $btn = $(this);
            var $icon = $btn.find('i');
            if ($icon.length) {
                $icon.removeClass('fa-spin');
                $btn.prop('disabled', false);
            }
        });
        
        // Remove global refresh indicator
        $('#globalRefreshIndicator').fadeOut(300, function() {
            $(this).remove();
        });
    }
}

// Update tab counters dynamically
function updateTabCounters() {
    // Update "Today's Orders" counter
    $.get('<?= base_url('car_wash/getTodayOrders') ?>', function(response) {
        if (response.success && response.data) {
            var todayCount = response.data.length;
            $('#todayOrdersCount').text('(' + todayCount + ')');
        }
    });
    
    // Update "All Orders" counter  
    $.get('<?= base_url('car_wash/getAllActiveOrders') ?>', function(response) {
        if (response.success && response.data) {
            var allCount = response.data.length;
            $('#allOrdersCount').text('(' + allCount + ')');
        }
    });
    
    // Update "Deleted Orders" counter
    $.get('<?= base_url('car_wash/getDeletedOrders') ?>', function(response) {
        if (response.success && response.data) {
            var deletedCount = response.data.length;
            $('#deletedOrdersCount').text('(' + deletedCount + ')');
        }
    });
    
    console.log('✅ Updated tab counters');
}

// Auto-refresh system
function initializeAutoRefresh() {
    // 1. Refresh when switching tabs
    $(document).on('shown.bs.tab', '#carWashTabs a[data-bs-toggle="tab"]', function (e) {
        var targetTab = $(e.target).attr('href').substring(1);
        console.log('🔄 Tab changed to:', targetTab, '- Refreshing data...');
        
        // Small delay to ensure tab content is loaded
        setTimeout(function() {
            // Refresh the specific tab's data
            refreshTabData(targetTab);
        }, 200);
    });
    
    // 2. Auto-refresh current tab every 30 seconds (optional)
    var autoRefreshInterval;
    var enableAutoRefresh = false; // Set to true to enable periodic refresh
    
    if (enableAutoRefresh) {
        autoRefreshInterval = setInterval(function() {
            var activeTab = $('.nav-tabs .nav-link.active').attr('href');
            if (activeTab) {
                var tabId = activeTab.substring(1);
                console.log('🔄 Auto-refreshing tab:', tabId);
                refreshTabData(tabId);
            }
        }, 30000); // 30 seconds
        
        console.log('✅ Auto-refresh enabled (30s interval)');
    }
    
    // 3. Manual refresh button handlers
    $(document).on('click', '[id^="refresh"]', function() {
        var buttonId = $(this).attr('id');
        console.log('🔄 Manual refresh triggered:', buttonId);
        refreshAllCarWashData();
    });
    
    // 4. Refresh on browser tab focus (when user returns to page)
    $(window).on('focus', function() {
        console.log('🔄 Browser tab focused - Refreshing CarWash data...');
        refreshAllCarWashData();
    });
    
    console.log('✅ Auto-refresh system initialized');
}

// Refresh specific tab data
function refreshTabData(tabId) {
    var tableSelectors = {
        'dashboard': '#dashboardTable',
        'today': '#todayTable',
        'all-orders': '#allOrdersTable',
        'services': '#servicesTable',
        'deleted': '#deletedOrdersTable'
    };
    
            // Refresh DataTable for this specific tab
        var tableSelector = tableSelectors[tabId];
        if (tableSelector) {
            var table = document.querySelector(tableSelector);
            if (table && $.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
                // For services table, skip auto-refresh to avoid column issues
                // The services table has its own refresh mechanisms
                if (tabId === 'services') {
                    console.log('✅ Skipping auto-refresh for services table to avoid column issues');
                } else {
                    $(table).DataTable().ajax.reload(null, false);
                    console.log('✅ Refreshed DataTable for tab:', tabId);
                }
            }
        }
    
    // Special content refresh for non-DataTable tabs
    var contentMap = {
        'deleted': '<?= base_url('car_wash/deleted_content') ?>'
    };
    
    if (contentMap[tabId]) {
        setTimeout(function() {
            loadTabContent(tabId, contentMap[tabId]);
            console.log('✅ Refreshed content for tab:', tabId);
        }, 100);
    }
    
    // Update counters
    updateTabCounters();
}

// Manual refresh function for buttons
window.manualRefreshCarWash = function() {
    console.log('🔄 Manual refresh requested');
    refreshAllCarWashData({ showToast: true, force: false });
};

// Force refresh function (bypasses throttling)
window.forceRefreshCarWash = function() {
    console.log('🔄 Force refresh requested (bypasses throttling)');
    refreshAllCarWashData({ showToast: true, force: true });
};

// Reset refresh state (for debugging)
window.resetCarWashRefreshState = function() {
    window.carWashRefreshing = false;
    window.lastCarWashRefresh = null;
    showRefreshIndicator(false);
    console.log('✅ CarWash refresh state reset');
};

// Debug refresh status
window.getCarWashRefreshStatus = function() {
    console.log('CarWash Refresh Status:', {
        refreshing: window.carWashRefreshing || false,
        lastRefresh: window.lastCarWashRefresh || null,
        timeSinceLastRefresh: window.lastCarWashRefresh ? (Date.now() - window.lastCarWashRefresh) + 'ms' : 'never'
    });
};

// Global toast function
window.showToast = function(type, message) {
    if (typeof Swal !== 'undefined') {
        const config = {
            html: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        };

        // Set icon and title based on type
        switch(type) {
            case 'success':
                config.icon = 'success';
                config.title = 'Success!';
                break;
            case 'error':
                config.icon = 'error';
                config.title = 'Error!';
                break;
            case 'warning':
                config.icon = 'warning';
                config.title = 'Warning!';
                break;
            case 'info':
                config.icon = 'info';
                config.title = 'Info';
                break;
            default:
                config.icon = 'info';
                config.title = 'Notification';
        }

        Swal.fire(config);
    } else {
        // Fallback to alert if SweetAlert is not available
        alert(`${type.toUpperCase()}: ${message.replace(/<[^>]*>/g, '')}`);
    }
};

$(document).ready(function() {
    
    // Refresh functions
    window.refreshCarWashData = function() {
        var activeTab = $('.nav-tabs .nav-link.active').attr('href').substring(1);
        var contentMap = {
            'dashboard': '<?= base_url('car_wash/dashboard_content') ?>',
            'today': '<?= base_url('car_wash/today_content') ?>',
            'all-orders': '<?= base_url('car_wash/all_orders_content') ?>',
            'services': '<?= base_url('car_wash/services_content') ?>',
            'deleted': '<?= base_url('car_wash/deleted_content') ?>'
        };
        
        if (contentMap[activeTab]) {
            loadTabContent(activeTab, contentMap[activeTab]);
        }
    };
    
    // Debug function to clear localStorage
    window.clearCarWashStorage = function() {
        localStorage.removeItem('carwash_active_tab');
        console.log('🧹 CarWash localStorage cleared');
    };
    
    // Debug function to reset everything
    window.resetCarWashTab = function() {
        // Clean up active requests
        window.cleanupActiveRequests();
        
        // Clear localStorage
        window.clearCarWashStorage();
        
        // Reset to dashboard
        $('#dashboard-tab').tab('show');
        
        console.log('🔄 CarWash tab system reset to dashboard');
    };
    
    // Initialize tooltips and popovers
    if (typeof $('[data-bs-toggle="tooltip"]').tooltip === 'function') {
        $('[data-bs-toggle="tooltip"]').tooltip();
    }
    if (typeof $('[data-bs-toggle="popover"]').popover === 'function') {
        $('[data-bs-toggle="popover"]').popover();
    }
    
    // Global error handler for AJAX requests
    $(document).ajaxError(function(event, xhr, settings) {
        if (xhr.status === 401) {
            // Redirect to login
            window.location.href = '<?= base_url('login') ?>';
        } else if (xhr.status === 403) {
            // Forbidden
            showToast('<?= lang('App.access_denied') ?>', 'error');
        } else if (xhr.status === 500) {
            // Server error
            console.error('Server Error:', xhr.responseText);
            showToast('<?= lang('App.server_error') ?>', 'error');
        }
    });
    
    // Initialize global filters system
    initializeGlobalFilters();
    
    // Initialize auto-refresh system
    initializeAutoRefresh();
    
    // Initialize dashboard filters integration
    initializeDashboardFiltersIntegration();
    
    console.log('✅ CarWash module with full functionality loaded!');
});

// Toast notification function
function showToast(type, message) {
    if (typeof Swal !== 'undefined') {
        const config = {
            html: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        };

        // Set icon and title based on type
        switch(type) {
            case 'success':
                config.icon = 'success';
                config.title = 'Success!';
                break;
            case 'error':
                config.icon = 'error';
                config.title = 'Error!';
                break;
            case 'warning':
                config.icon = 'warning';
                config.title = 'Warning!';
                break;
            case 'info':
                config.icon = 'info';
                config.title = 'Info';
                break;
            default:
                config.icon = 'info';
                config.title = 'Notification';
        }

        Swal.fire(config);
    } else {
        // Fallback to alert if SweetAlert is not available
        alert(`${type.toUpperCase()}: ${message.replace(/<[^>]*>/g, '')}`);
    }
}

// Global filters system
window.globalFilters = {
    client: '',
    status: '',
    service: '',
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
    // Load from localStorage
    const savedClient = localStorage.getItem('carWashGlobalClientFilter');
    const savedStatus = localStorage.getItem('carWashGlobalStatusFilter');
    const savedService = localStorage.getItem('carWashGlobalServiceFilter');
    const savedDateFrom = localStorage.getItem('carWashGlobalDateFromFilter');
    const savedDateTo = localStorage.getItem('carWashGlobalDateToFilter');
    
    // Update global filters object
    window.globalFilters.client = savedClient || '';
    window.globalFilters.status = savedStatus || '';
    window.globalFilters.service = savedService || '';
    window.globalFilters.dateFrom = savedDateFrom || '';
    window.globalFilters.dateTo = savedDateTo || '';
    
    // Update UI elements
    document.getElementById('globalClientFilter').value = window.globalFilters.client;
    document.getElementById('globalStatusFilter').value = window.globalFilters.status;
    document.getElementById('globalServiceFilter').value = window.globalFilters.service;
    document.getElementById('globalDateFromFilter').value = window.globalFilters.dateFrom;
    document.getElementById('globalDateToFilter').value = window.globalFilters.dateTo;
}

function loadFilterOptions() {
    // Load clients
    $.get('<?= base_url('car_wash/getFormData') ?>')
        .done(function(data) {
            if (data && data.clients && Array.isArray(data.clients)) {
                const clientFilter = $('#globalClientFilter');
                // Clear existing options except the first one
                clientFilter.find('option:not(:first)').remove();
                
                // Add client options
                data.clients.forEach(function(client) {
                    clientFilter.append('<option value="' + client.id + '">' + client.name + '</option>');
                });
                
                // Restore saved value
                clientFilter.val(window.globalFilters.client);
            }
            
            if (data && data.services && Array.isArray(data.services)) {
                const serviceFilter = $('#globalServiceFilter');
                // Clear existing options except the first one
                serviceFilter.find('option:not(:first)').remove();
                
                // Add service options
                data.services.forEach(function(service) {
                    serviceFilter.append('<option value="' + service.id + '">' + service.name + '</option>');
                });
                
                // Restore saved value
                serviceFilter.val(window.globalFilters.service);
            }
        })
        .fail(function() {
            console.error('Error loading filter options');
        });
}

function setupFilterEventListeners() {
    // Client filter change
    document.getElementById('globalClientFilter').addEventListener('change', function() {
        window.globalFilters.client = this.value;
        saveFilter('client', this.value);
        updateActiveFiltersCounter();
        applyAllFilters();
        showToast('info', `Client filter: ${this.value ? this.options[this.selectedIndex].text : 'All Clients'}`);
    });
    
    // Status filter change
    document.getElementById('globalStatusFilter').addEventListener('change', function() {
        window.globalFilters.status = this.value;
        saveFilter('status', this.value);
        updateActiveFiltersCounter();
        applyAllFilters();
        showToast('info', `Status filter: ${this.value ? this.options[this.selectedIndex].text : 'All Status'}`);
    });
    
    // Service filter change
    document.getElementById('globalServiceFilter').addEventListener('change', function() {
        window.globalFilters.service = this.value;
        saveFilter('service', this.value);
        updateActiveFiltersCounter();
        applyAllFilters();
        showToast('info', `Service filter: ${this.value ? this.options[this.selectedIndex].text : 'All Services'}`);
    });
    
    // Date filters change
    document.getElementById('globalDateFromFilter').addEventListener('change', function() {
        window.globalFilters.dateFrom = this.value;
        saveFilter('dateFrom', this.value);
        updateActiveFiltersCounter();
        applyAllFilters();
    });
    
    document.getElementById('globalDateToFilter').addEventListener('change', function() {
        window.globalFilters.dateTo = this.value;
        saveFilter('dateTo', this.value);
        updateActiveFiltersCounter();
        applyAllFilters();
    });
    
    // Apply filters button
    document.getElementById('applyGlobalFilters').addEventListener('click', function() {
        applyAllFilters();
        showToast('success', 'Filters applied to all tables');
    });
    
    // Clear filters button
    document.getElementById('clearGlobalFilters').addEventListener('click', function() {
        clearAllFilters();
    });
    
    // Refresh all tables button
    document.getElementById('refreshAllTables').addEventListener('click', function() {
        refreshAllTables();
    });
}

function saveFilter(type, value) {
    const storageKey = `carWashGlobal${type.charAt(0).toUpperCase() + type.slice(1)}Filter`;
    if (value) {
        localStorage.setItem(storageKey, value);
    } else {
        localStorage.removeItem(storageKey);
    }
}

function hasActiveFilters() {
    return window.globalFilters.client || 
           window.globalFilters.status || 
           window.globalFilters.service || 
           window.globalFilters.dateFrom || 
           window.globalFilters.dateTo;
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

function applyAllFilters() {
    // Get current active tab
    const activeTab = document.querySelector('#carWashTabs .nav-link.active');
    if (!activeTab) return;
    
    const tabId = activeTab.getAttribute('href').substring(1);
    
    // Apply filters to DataTables if they exist
    applyFiltersToDataTables(tabId);
    
    // Update active filters counter
    updateActiveFiltersCounter();
}

function applyFiltersToDataTables(tabId) {
    // Apply filters to specific DataTables based on active tab
    const tableSelectors = {
        'today': '#today-orders-table',
        'all-orders': '#all-orders-table'
    };
    
    const tableSelector = tableSelectors[tabId];
    if (tableSelector) {
        const table = document.querySelector(tableSelector);
        if (table && $.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
            const dataTable = $(table).DataTable();
            dataTable.ajax.reload(null, false); // false = don't reset pagination
        }
    }
}

function clearAllFilters() {
    // Clear global filters object
    window.globalFilters = {
        client: '',
        status: '',
        service: '',
        dateFrom: '',
        dateTo: ''
    };
    
    // Clear UI elements
    document.getElementById('globalClientFilter').value = '';
    document.getElementById('globalStatusFilter').value = '';
    document.getElementById('globalServiceFilter').value = '';
    document.getElementById('globalDateFromFilter').value = '';
    document.getElementById('globalDateToFilter').value = '';
    
    // Clear localStorage
    localStorage.removeItem('carWashGlobalClientFilter');
    localStorage.removeItem('carWashGlobalStatusFilter');
    localStorage.removeItem('carWashGlobalServiceFilter');
    localStorage.removeItem('carWashGlobalDateFromFilter');
    localStorage.removeItem('carWashGlobalDateToFilter');
    
    // Update active filters counter
    updateActiveFiltersCounter();
    
    // Apply cleared filters
    applyAllFilters();
    
    // Hide accordion after clearing
    const accordion = document.getElementById('filtersContent');
    if (accordion.classList.contains('show')) {
        const collapseInstance = new bootstrap.Collapse(accordion);
        collapseInstance.hide();
    }
    
    showToast('success', 'All filters cleared');
}

function refreshAllTables() {
    // Get current active tab and refresh its content
    const activeTab = document.querySelector('#carWashTabs .nav-link.active');
    if (activeTab) {
        const tabId = activeTab.getAttribute('href').substring(1);
        const contentMap = {
            'dashboard': '<?= base_url('car_wash/dashboard_content') ?>',
            'today': '<?= base_url('car_wash/today_content') ?>',
            'all-orders': '<?= base_url('car_wash/all_orders_content') ?>',
            'services': '<?= base_url('car_wash/services_content') ?>',
            'deleted': '<?= base_url('car_wash/deleted_content') ?>'
        };
        
        if (contentMap[tabId]) {
            loadTabContent(tabId, contentMap[tabId]);
        }
    }
    
    showToast('success', 'Tables refreshed');
}

// Helper function to get current filters for AJAX requests
function getCurrentFilters() {
    return {
        client_filter: window.globalFilters.client || '',
        status_filter: window.globalFilters.status || '',
        service_filter: window.globalFilters.service || '',
        date_from_filter: window.globalFilters.dateFrom || '',
        date_to_filter: window.globalFilters.dateTo || ''
    };
}

// Initialize dashboard filters integration
function initializeDashboardFiltersIntegration() {
    // Sync global filters with dashboard filters when switching to dashboard tab
    $('#dashboard-tab').on('shown.bs.tab', function() {
        syncGlobalFiltersWithDashboard();
    });
    
    // Listen for filter changes from the main filter section
    $('#filterForm').on('change', 'select, input', function() {
        // Update global filters object
        updateGlobalFilters();
        
        // If dashboard is active, sync filters
        if ($('#dashboard-tab').hasClass('active')) {
            syncGlobalFiltersWithDashboard();
        }
    });
}

// Sync global filters with dashboard filters
function syncGlobalFiltersWithDashboard() {
    // Wait for dashboard content to load
    setTimeout(function() {
        if ($('#dashboard_client_filter').length) {
            $('#dashboard_client_filter').val(window.globalFilters.client || '');
            $('#dashboard_status_filter').val(window.globalFilters.status || '');
            $('#dashboard_service_filter').val(window.globalFilters.service || '');
            $('#dashboard_date_from').val(window.globalFilters.dateFrom || '');
            $('#dashboard_date_to').val(window.globalFilters.dateTo || '');
            
            // Apply filters if any are set
            if (window.globalFilters.client || window.globalFilters.status || 
                window.globalFilters.service || window.globalFilters.dateFrom || 
                window.globalFilters.dateTo) {
                if (typeof applyDashboardFilters === 'function') {
                    applyDashboardFilters();
                }
            }
        }
    }, 500);
}

// Update global filters from main filter form
function updateGlobalFilters() {
    window.globalFilters = {
        client: $('#client_filter').val() || '',
        status: $('#status_filter').val() || '',
        service: $('#service_filter').val() || '',
        dateFrom: $('#date_from_filter').val() || '',
        dateTo: $('#date_to_filter').val() || ''
    };
}

// Enhanced apply filters function
function applyGlobalFilters() {
    updateGlobalFilters();
    
    // Apply filters to current active tab
    const activeTab = $('.nav-tabs .nav-link.active').attr('href').substring(1);
    
    if (activeTab === 'dashboard') {
        syncGlobalFiltersWithDashboard();
    } else {
        // Apply to other tabs
        refreshCurrentTab();
    }
    
    showToast('<?= lang('App.filters_applied') ?>', 'success');
}

// Enhanced clear filters function
function clearGlobalFilters() {
    $('#filterForm')[0].reset();
    window.globalFilters = {
        client: '',
        status: '',
        service: '',
        dateFrom: '',
        dateTo: ''
    };
    
    // Clear dashboard filters if dashboard is active
    if ($('#dashboard-tab').hasClass('active') && typeof clearDashboardFilters === 'function') {
        clearDashboardFilters();
    } else {
        refreshCurrentTab();
    }
    
    showToast('<?= lang('App.filters_cleared') ?>', 'success');
}

// Refresh current active tab
function refreshCurrentTab() {
    const activeTab = $('.nav-tabs .nav-link.active').attr('href').substring(1);
    const contentMap = {
        'dashboard': '<?= base_url('car_wash/dashboard_content') ?>',
        'today': '<?= base_url('car_wash/today_content') ?>',
        'all-orders': '<?= base_url('car_wash/all_orders_content') ?>',
        'services': '<?= base_url('car_wash/services_content') ?>',
        'deleted': '<?= base_url('car_wash/deleted_content') ?>'
    };
    
    if (contentMap[activeTab]) {
        const filters = getCurrentFilters();
        const params = new URLSearchParams(filters).toString();
        loadTabContent(activeTab, contentMap[activeTab] + (params ? '?' + params : ''));
    }
}

// Duplicate Check Time Persistence
function initializeDuplicateCheckTime() {
    const duplicateCheckTimeSelector = document.getElementById('duplicateCheckTime');
    
    if (!duplicateCheckTimeSelector) {
        console.warn('Duplicate check time selector not found');
        return;
    }
    
    // Load saved value from localStorage
    const savedTime = localStorage.getItem('carWashDuplicateCheckTime');
    if (savedTime && ['5', '10', '15'].includes(savedTime)) {
        duplicateCheckTimeSelector.value = savedTime;
        console.log('Loaded duplicate check time from localStorage:', savedTime);
    } else {
        // Set default value
        duplicateCheckTimeSelector.value = '5';
        localStorage.setItem('carWashDuplicateCheckTime', '5');
    }
    
    // Auto-save when changed
    duplicateCheckTimeSelector.addEventListener('change', function() {
        const selectedTime = this.value;
        localStorage.setItem('carWashDuplicateCheckTime', selectedTime);
        console.log('Saved duplicate check time to localStorage:', selectedTime);
        
        // Show confirmation toast
        const timeLabels = {
            '5': '<?= lang('App.minutes_5') ?>',
            '10': '<?= lang('App.minutes_10') ?>',
            '15': '<?= lang('App.minutes_15') ?>'
        };
        
        showToast('info', `<?= lang('App.duplicate_check_time') ?>: ${timeLabels[selectedTime] || selectedTime + ' minutes'}`);
    });
}

// Initialize duplicate check time when document is ready
$(document).ready(function() {
    // Initialize duplicate check time after a short delay to ensure DOM is ready
    setTimeout(initializeDuplicateCheckTime, 100);
});
</script>
<?= $this->endSection() ?> 