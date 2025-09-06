<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.sales_orders') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.sales_orders') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.sales_orders') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Access restrictions for client and staff users
$currentUser = auth()->user();
$userType = $currentUser->user_type ?? 'client';
$canAccessServices = in_array($userType, ['admin', 'superadmin']); // Only admin/superadmin can access Services
$canAccessDeleted = in_array($userType, ['admin', 'superadmin']); // Only admin/superadmin can access Deleted
$canViewPrices = in_array($userType, ['admin', 'superadmin']); // Only admin/superadmin can see prices
?>
<div class="card border-0 shadow-none">
                        <div class="card-header d-flex align-items-center">
                            <h4 class="card-title mb-0 flex-grow-1"><?= lang('App.sales_orders') ?></h4>
                            <div class="flex-shrink-0">
                                <div class="d-flex align-items-center gap-2">
                <button class="btn btn-primary" id="addOrderBtn">
                    <i data-feather="plus" class="icon-sm me-1"></i>
                    <span class="d-none d-sm-inline"><?= lang('App.add_sales_order') ?></span>
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
                                <?php if ($canAccessServices): ?>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#services-tab" role="tab">
                                        <span><i data-feather="package" class="icon-sm me-1"></i> <?= lang('App.services') ?></span>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if ($canAccessDeleted): ?>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#deleted-orders-tab" role="tab">
                                        <span><i data-feather="trash-2" class="icon-sm me-1"></i> Deleted Orders</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>

                            <div class="tab-content py-4">
                                <div class="tab-pane active" id="dashboard-tab" role="tabpanel">
                                    <?= $this->include('Modules\SalesOrders\Views\sales_orders/dashboard_content') ?>
                                </div>
                                <div class="tab-pane" id="today-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\SalesOrders\Views\sales_orders/today_content') ?>
                                </div>
                                <div class="tab-pane" id="tomorrow-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\SalesOrders\Views\sales_orders/tomorrow_content') ?>
                                </div>
                                <div class="tab-pane" id="pending-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\SalesOrders\Views\sales_orders/pending_content') ?>
                                </div>
                                <div class="tab-pane" id="week-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\SalesOrders\Views\sales_orders/week_content') ?>
                                </div>
                                <div class="tab-pane" id="all-orders-tab" role="tabpanel">
                                    <?= $this->include('Modules\SalesOrders\Views\sales_orders/all_content') ?>
                                </div>
                                <?php if ($canAccessServices): ?>
                                <div class="tab-pane" id="services-tab" role="tabpanel">
                                    <!-- Services Management Content -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header d-flex align-items-center">
                                                    <h4 class="card-title mb-0 flex-grow-1"><?= lang('App.services') ?></h4>
                                                    <div class="flex-shrink-0">
                                                        <button id="refreshServicesTable" class="btn btn-secondary btn-sm me-2">
                                                            <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                                                            <?= lang('App.refresh') ?>
                                                        </button>
                                                        <a href="<?= base_url('sales_orders_services') ?>" class="btn btn-primary">
                                                            <i data-feather="plus" class="icon-sm me-1"></i> <?= lang('App.add_service') ?>
                                                        </a>
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
                                </div>
                                <?php endif; ?>
                                <?php if ($canAccessDeleted): ?>
                                <div class="tab-pane" id="deleted-orders-tab" role="tabpanel">
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <i data-feather="alert-circle" class="icon-sm me-2"></i>
                                            <?= $error ?>
                                        </div>
                                    <?php elseif (empty($deleted_orders)): ?>
                                        <div class="text-center py-5">
                                            <div class="mb-3">
                                                <i data-feather="trash-2" class="icon-lg text-muted"></i>
                                            </div>
                                            <h5 class="text-muted">No Deleted Orders</h5>
                                            <p class="text-muted">There are no deleted orders to display.</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h5 class="card-title mb-0">
                                                                <i data-feather="trash-2" class="icon-sm me-2"></i>
                                                                Deleted Orders (<?= isset($deleted_orders) ? count($deleted_orders) : 0 ?>)
                                                            </h5>
                                                            <div>
                                                                <button class="btn btn-sm btn-outline-success" onclick="bulkRestore()">
                                                                    <i data-feather="rotate-ccw" class="icon-sm me-1"></i>
                                                                    Restore Selected
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger" onclick="bulkForceDelete()">
                                                                    <i data-feather="x" class="icon-sm me-1"></i>
                                                                    Permanently Delete Selected
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="30" class="text-center">
                                                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                                                        </th>
                                                                        <th class="text-center">Order ID</th>
                                                                        <th class="text-center">Client</th>
                                                                        <th class="text-center">Salesperson</th>
                                                                        <th class="text-center">Vehicle</th>
                                                                        <th class="text-center">Service</th>
                                                                        <th class="text-center">Date</th>
                                                                        <th class="text-center">Status</th>
                                                                        <th class="text-center">Deleted At</th>
                                                                        <th width="120" class="text-center">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (isset($deleted_orders) && !empty($deleted_orders)): ?>
                                                                        <?php foreach ($deleted_orders as $order): ?>
                                                                            <tr>
                                                                                <td class="text-center">
                                                                                    <input type="checkbox" class="form-check-input order-checkbox" value="<?= $order['id'] ?>">
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <strong>SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></strong>
                                                                                </td>
                                                                                <td><?= $order['client_name'] ?? 'N/A' ?></td>
                                                                                <td><?= $order['salesperson_name'] ?? 'N/A' ?></td>
                                                                                <td><?= $order['vehicle'] ?? 'N/A' ?></td>
                                                                                <td><?= $order['service_name'] ?? 'N/A' ?></td>
                                                                                <td class="text-center"><?= date('M d, Y', strtotime($order['date'])) ?></td>
                                                                                <td class="text-center">
                                                                                    <span class="badge bg-secondary"><?= 
                                                $order['status'] == 'in_progress' ? 'In Progress' : 
                                                ($order['status'] == 'processing' ? 'Processing' : 
                                                ($order['status'] == 'completed' ? 'Completed' : 
                                                ($order['status'] == 'cancelled' ? 'Cancelled' : 
                                                ($order['status'] == 'pending' ? 'Pending' : ucfirst($order['status'])))))
                                            ?></span>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <small class="text-muted">
                                                                                        <?= date('M d, Y H:i', strtotime($order['updated_at'])) ?>
                                                                                    </small>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <div class="d-flex gap-1 deleted-orders-actions justify-content-center">
                                                                                        <button class="btn btn-sm btn-outline-success" 
                                                                                                onclick="restoreOrder(<?= $order['id'] ?>)"
                                                                                                data-bs-toggle="tooltip" 
                                                                                                title="Restore Order">
                                                                                            <i data-feather="rotate-ccw" class="icon-sm"></i>
                                                                                        </button>
                                                                                        <button class="btn btn-sm btn-outline-danger" 
                                                                                                onclick="forceDeleteOrder(<?= $order['id'] ?>)"
                                                                                                data-bs-toggle="tooltip" 
                                                                                                title="Permanently Delete">
                                                                                            <i data-feather="x" class="icon-sm"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

<!-- MODALES REMOVIDOS - Usar global_sales_order_modal.php desde partials -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// ========================================
// CSS OVERRIDE FUNCTIONS FOR STATUS COLUMNS
// ========================================

// Function to force correct styling on status columns
function forceStatusColumnStyling() {
    
    // Create ultra-high priority CSS with body prefix for maximum specificity
    const styleId = 'ultra-status-column-styles';
    let existingStyle = document.getElementById(styleId);
    if (existingStyle) {
        existingStyle.remove();
    }
    
    const styleSheet = document.createElement('style');
    styleSheet.id = styleId;
    styleSheet.textContent = `
        /* NUCLEAR OPTION - MAXIMUM SPECIFICITY CSS FOR STATUS COLUMNS */
        body .dataTables_wrapper tbody td:has(.status-dropdown),
        body .dataTables_wrapper tbody td .status-dropdown-cell {
            text-align: center !important;
            vertical-align: middle !important;
            padding: 8px !important;
        }
        
        body .dataTables_wrapper .status-dropdown,
        body .status-dropdown {
            width: 120px !important;
            margin: 0 auto !important;
            text-align: center !important;
            text-align-last: center !important;
            display: block !important;
        }
        
        /* Remove sorting icons from status columns */
        body .dataTables_wrapper thead th.sorting:nth-child(6)::after,
        body .dataTables_wrapper thead th.sorting:nth-child(7)::after,
        body .dataTables_wrapper thead th.sorting:nth-child(8)::after,
        body .dataTables_wrapper thead th:nth-child(6)::after,
        body .dataTables_wrapper thead th:nth-child(7)::after,
        body .dataTables_wrapper thead th:nth-child(8)::after {
            display: none !important;
            content: none !important;
        }
        
        body .dataTables_wrapper thead th.sorting:nth-child(6),
        body .dataTables_wrapper thead th.sorting:nth-child(7),
        body .dataTables_wrapper thead th.sorting:nth-child(8),
        body .dataTables_wrapper thead th:nth-child(6),
        body .dataTables_wrapper thead th:nth-child(7),
        body .dataTables_wrapper thead th:nth-child(8) {
            text-align: center !important;
            cursor: default !important;
        }
        
        /* Special handling for tables with duplicate badges */
        body .dataTables_wrapper tbody tr:has(.duplicate-indicator) td:has(.status-dropdown) {
            text-align: center !important;
            vertical-align: middle !important;
        }
        
        body .dataTables_wrapper tbody tr:has(.duplicate-indicator) .status-dropdown {
            width: 120px !important;
            margin: 0 auto !important;
            text-align: center !important;
            text-align-last: center !important;
            display: block !important;
        }
    `;
    document.head.appendChild(styleSheet);
    
    // Force direct DOM manipulation with multiple attempts
    const applyDirectStyling = () => {
        // Find all status dropdowns and force styling
        document.querySelectorAll('.status-dropdown').forEach(dropdown => {
            const cell = dropdown.closest('td');
            if (cell) {
                cell.style.setProperty('text-align', 'center', 'important');
                cell.style.setProperty('vertical-align', 'middle', 'important');
                cell.style.setProperty('padding', '8px', 'important');
                cell.classList.add('status-dropdown-cell');
            }
            
            dropdown.style.setProperty('width', '120px', 'important');
            dropdown.style.setProperty('margin', '0 auto', 'important');
            dropdown.style.setProperty('text-align', 'center', 'important');
            dropdown.style.setProperty('text-align-last', 'center', 'important');
            dropdown.style.setProperty('display', 'block', 'important');
        });
        
        // Find status column headers and remove sorting icons
        document.querySelectorAll('.dataTables_wrapper thead th').forEach((th, index) => {
            const headerText = th.textContent.toLowerCase().trim();
            if (headerText.includes('status') || index >= 5) {
                th.style.setProperty('text-align', 'center', 'important');
                th.style.setProperty('cursor', 'default', 'important');
                
                // Remove sorting functionality completely
                th.classList.remove('sorting');
                th.classList.remove('sorting_asc');
                th.classList.remove('sorting_desc');
                th.classList.add('no-sort');
                
                // Clone element to remove all event listeners
                const clonedTh = th.cloneNode(true);
                th.parentNode.replaceChild(clonedTh, th);
            }
        });
        
        // Special handling for rows with duplicate badges
        document.querySelectorAll('tr:has(.duplicate-indicator)').forEach(row => {
            const statusCell = row.querySelector('td:has(.status-dropdown)');
            const statusDropdown = row.querySelector('.status-dropdown');
            
            if (statusCell) {
                statusCell.style.setProperty('text-align', 'center', 'important');
                statusCell.style.setProperty('vertical-align', 'middle', 'important');
            }
            
            if (statusDropdown) {
                statusDropdown.style.setProperty('width', '120px', 'important');
                statusDropdown.style.setProperty('margin', '0 auto', 'important');
                statusDropdown.style.setProperty('text-align', 'center', 'important');
                statusDropdown.style.setProperty('text-align-last', 'center', 'important');
                statusDropdown.style.setProperty('display', 'block', 'important');
            }
        });
    };
    
    // Apply immediately
    applyDirectStyling();
    
    // Apply with delays to catch late-loading elements
    setTimeout(applyDirectStyling, 100);
    setTimeout(applyDirectStyling, 300);
    setTimeout(applyDirectStyling, 500);
}

// Function to apply styling after DataTables are initialized
function applyPostTableStyling() {
    
    // Apply immediately
    forceStatusColumnStyling();
    
    // Re-apply after short delay to catch late-loading elements
    setTimeout(() => {
        forceStatusColumnStyling();
    }, 1000);
    
    // Re-apply after longer delay for final catch
    setTimeout(() => {
        forceStatusColumnStyling();
    }, 3000);
}

// ========================================
// SISTEMA DE FILTRO GLOBAL UNIFICADO
// ========================================

// Variables globales
window.globalFilters = {
    client: '',
    contact: '',
    status: '',
    dateFrom: '',
    dateTo: ''
};

// CSS OVERRIDE FUNCTIONS FOR STATUS COLUMNS
function forceStatusColumnStyling() {
    
    // Apply direct DOM styling to status dropdowns
    setTimeout(() => {
        document.querySelectorAll('.status-dropdown').forEach(dropdown => {
            const cell = dropdown.closest('td');
            if (cell) {
                cell.style.setProperty('text-align', 'center', 'important');
                cell.style.setProperty('vertical-align', 'middle', 'important');
                cell.style.setProperty('padding', '8px', 'important');
            }
            
            dropdown.style.setProperty('width', '120px', 'important');
            dropdown.style.setProperty('margin', '0 auto', 'important');
            dropdown.style.setProperty('text-align', 'center', 'important');
            dropdown.style.setProperty('text-align-last', 'center', 'important');
            dropdown.style.setProperty('display', 'block', 'important');
        });
        
        // Fix status column headers
        document.querySelectorAll('.dataTables_wrapper thead th').forEach((th, index) => {
            const headerText = th.textContent.toLowerCase().trim();
            if (headerText.includes('status') || index >= 5) {
                th.style.setProperty('text-align', 'center', 'important');
                th.style.setProperty('cursor', 'default', 'important');
                
                // Remove sorting functionality
                th.classList.remove('sorting');
                th.classList.remove('sorting_asc');
                th.classList.remove('sorting_desc');
                th.classList.add('no-sort');
                
                // Remove click handlers
                const clonedTh = th.cloneNode(true);
                th.parentNode.replaceChild(clonedTh, th);
            }
        });
    }, 100);
    
    // Apply status colors after styling
    applyStatusColors();
}

// Function to apply status colors to dropdowns
function applyStatusColors() {
    
    setTimeout(() => {
        document.querySelectorAll('.status-dropdown').forEach(dropdown => {
            // Remove existing status classes
            dropdown.classList.remove('status-pending', 'status-in_progress', 'status-completed', 'status-cancelled');
            
            // Get current value and apply corresponding class
            const currentValue = dropdown.value;
            if (currentValue) {
                dropdown.classList.add(`status-${currentValue}`);
            }
        });
    }, 200);
}

// VIN Decoder translations - Global scope for SalesOrderIndex
const vinTranslations = <?= json_encode([
    'loading' => lang('App.vin_loading'),
    'onlyAlphanumeric' => lang('App.vin_only_alphanumeric'),
    'cannotExceed17' => lang('App.vin_cannot_exceed_17'),
    'invalidFormat' => lang('App.vin_invalid_format'),
    'validNoInfo' => lang('App.vin_valid_no_info'),
    'decodedNoData' => lang('App.vin_decoded_no_data'),
    'unableToDecode' => lang('App.vin_unable_to_decode'),
    'decodingFailed' => lang('App.vin_decoding_failed'),
    'partial' => lang('App.vin_partial'),
    'characters' => lang('App.vin_characters'),
    'suspiciousPatterns' => lang('App.vin_suspicious_patterns'),
    'invalidCheckDigit' => lang('App.vin_invalid_check_digit')
]) ?>;

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Initialize global filter system
    initializeGlobalFilterSystem();
    
    // Configure Add Order button to use global modal
    const addOrderBtn = document.getElementById('addOrderBtn');
    if (addOrderBtn) {
        addOrderBtn.addEventListener('click', function() {
            if (typeof openGlobalSalesOrderModal === 'function') {
                openGlobalSalesOrderModal();
            } else {
                console.error('openGlobalSalesOrderModal function not available');
            }
        });
    }
    
    // Initialize services modal functionality
    initializeServicesModal();
    
    // Initialize VIN decoder functionality
    setupIndexVINDecoding();
    
    // Initialize global event listeners (ONCE)
    initializeGlobalEventListeners();
    
    // Initialize tab memory
    initializeTabMemory();
    
    // Apply status column styling and colors periodically
    forceStatusColumnStyling();
    applyStatusColors();
    
    // Add global modal event listeners to ensure CSS is restored after modal closes
    document.addEventListener('hidden.bs.modal', function(e) {
        
        // Immediate restoration
        forceStatusColumnStyling();
        applyStatusColors();
        
        // Multiple restoration attempts with increasing delays
        setTimeout(() => {
            forceStatusColumnStyling();
            applyStatusColors();
        }, 100);
        
        setTimeout(() => {
            forceStatusColumnStyling();
            applyStatusColors();
        }, 300);
        
        setTimeout(() => {
            forceStatusColumnStyling();
            applyStatusColors();
        }, 800);
        
        setTimeout(() => {
            forceStatusColumnStyling();
            applyStatusColors();
        }, 1500);
    });
    
    document.addEventListener('shown.bs.modal', function(e) {
        setTimeout(() => {
            forceStatusColumnStyling();
            applyStatusColors();
        }, 500);
    });
    
    // Add MutationObserver to detect when new tables/dropdowns are added to DOM
    if (window.MutationObserver) {
        const observer = new MutationObserver(function(mutations) {
            let shouldApplyStyles = false;
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    // Check if new status dropdowns were added
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            if (node.classList?.contains('status-dropdown') || 
                                node.querySelector?.('.status-dropdown')) {
                                shouldApplyStyles = true;
                            }
                        }
                    });
                }
            });
            
            if (shouldApplyStyles) {
                setTimeout(() => {
                    forceStatusColumnStyling();
                    applyStatusColors();
                }, 100);
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    setInterval(() => {
        forceStatusColumnStyling();
        applyStatusColors();
    }, 5000); // Re-apply every 5 seconds
    
    // Set up MutationObserver to detect new status dropdowns
    const observer = new MutationObserver(function(mutations) {
        let shouldApplyColors = false;
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        if (node.classList && node.classList.contains('status-dropdown')) {
                            shouldApplyColors = true;
                        } else if (node.querySelectorAll) {
                            const statusDropdowns = node.querySelectorAll('.status-dropdown');
                            if (statusDropdowns.length > 0) {
                                shouldApplyColors = true;
                            }
                        }
                    }
                });
            }
        });
        
        if (shouldApplyColors) {
            setTimeout(() => {
                applyStatusColors();
                forceStatusColumnStyling();
            }, 100);
        }
    });
    
    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Check for edit parameter in URL
    const urlParams = new URLSearchParams(window.location.search);
    const editOrderId = urlParams.get('edit');
    if (editOrderId) {
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
        
        // Use global modal for editing
        setTimeout(() => {
            if (typeof editGlobalSalesOrder === 'function') {
                editGlobalSalesOrder(editOrderId);
            } else {
                console.error('editGlobalSalesOrder function not available');
            }
        }, 500);
    }
});

// ========================================
// SISTEMA DE FILTRO GLOBAL UNIFICADO
// ========================================

function initializeGlobalFilterSystem() {
    
    // Load saved filters from localStorage
    loadSavedFilters();
    
    // Initialize contact filter based on client filter
    setupContactFilterForClient(window.globalFilters.client);
    
    // Add event listeners for all filters
    setupFilterEventListeners();
    
    // Initialize accordion functionality
    initializeFiltersAccordion();
    
    // Set up global variables for dashboard compatibility
    window.globalClientFilter = window.globalFilters.client || '';
    
    // Apply saved filters with delay to ensure dashboard is loaded
    if (hasActiveFilters()) {
        
        // Show accordion if filters are active
        showFiltersAccordion();
        
        // Apply filters to tables immediately
        setTimeout(() => {
            applyFiltersToDataTables();
        }, 500);
        
        // Apply filters to dashboard with longer delay to ensure it's fully loaded
        setTimeout(() => {
            forceDashboardSync();
        }, 2000);
        
        // Final sync to catch any late-loading components
        setTimeout(() => {
            forceDashboardSync();
        }, 4000);
    }
}

function loadSavedFilters() {
    // Load from localStorage
    const savedClient = localStorage.getItem('salesOrdersGlobalClientFilter');
    const savedContact = localStorage.getItem('salesOrdersGlobalContactFilter');
    const savedStatus = localStorage.getItem('salesOrdersGlobalStatusFilter');
    const savedDateFrom = localStorage.getItem('salesOrdersGlobalDateFromFilter');
    const savedDateTo = localStorage.getItem('salesOrdersGlobalDateToFilter');
    
    // Update global filters object
    window.globalFilters.client = savedClient || '';
    window.globalFilters.contact = savedContact || '';
    window.globalFilters.status = savedStatus || '';
    window.globalFilters.dateFrom = savedDateFrom || '';
    window.globalFilters.dateTo = savedDateTo || '';
    
    // Update UI elements
    document.getElementById('globalClientFilter').value = window.globalFilters.client;
    document.getElementById('globalContactFilter').value = window.globalFilters.contact;
    document.getElementById('globalStatusFilter').value = window.globalFilters.status;
    document.getElementById('globalDateFromFilter').value = window.globalFilters.dateFrom;
    document.getElementById('globalDateToFilter').value = window.globalFilters.dateTo;
}

function setupFilterEventListeners() {
    // Client filter change
    document.getElementById('globalClientFilter').addEventListener('change', function() {
        const oldClientFilter = window.globalFilters.client;
        window.globalFilters.client = this.value;
        
        // Update global variable for dashboard
        window.globalClientFilter = this.value;
        
        saveFilter('client', this.value);
        updateActiveFiltersCounter();
        
        // Update contact filter based on client selection
        setupContactFilterForClient(this.value);
        
        // If client changes, clear contact selection
        window.globalFilters.contact = '';
        document.getElementById('globalContactFilter').value = '';
        saveFilter('contact', '');
        
        // Notify dashboard of filter change IMMEDIATELY
        notifyDashboardFilterChange('client', oldClientFilter, this.value);
        
        // Apply filters to all tables
        applyAllFilters();
        
        showToast('info', `Client filter: ${this.value ? this.options[this.selectedIndex].text : 'All Clients'}`);
    });
    
    // Contact filter change
    document.getElementById('globalContactFilter').addEventListener('change', function() {
        const oldContactFilter = window.globalFilters.contact;
        window.globalFilters.contact = this.value;
        saveFilter('contact', this.value);
        updateActiveFiltersCounter();
        
        // Notify dashboard of filter change
        notifyDashboardFilterChange('contact', oldContactFilter, this.value);
        
        applyAllFilters();
        
        showToast('info', `Contact filter: ${this.value ? this.options[this.selectedIndex].text : 'All Contacts'}`);
    });
    
    // Status filter change
    document.getElementById('globalStatusFilter').addEventListener('change', function() {
        const oldStatusFilter = window.globalFilters.status;
        window.globalFilters.status = this.value;
        saveFilter('status', this.value);
        updateActiveFiltersCounter();
        
        // Notify dashboard of filter change
        notifyDashboardFilterChange('status', oldStatusFilter, this.value);
        
        applyAllFilters();
        
        showToast('info', `Status filter: ${this.value ? this.options[this.selectedIndex].text : 'All Status'}`);
    });
    
    // Date filters change
    document.getElementById('globalDateFromFilter').addEventListener('change', function() {
        const oldDateFilter = window.globalFilters.dateFrom;
        window.globalFilters.dateFrom = this.value;
        saveFilter('dateFrom', this.value);
        updateActiveFiltersCounter();
        
        // Notify dashboard of filter change
        notifyDashboardFilterChange('dateFrom', oldDateFilter, this.value);
        
        applyAllFilters();
    });
    
    document.getElementById('globalDateToFilter').addEventListener('change', function() {
        const oldDateFilter = window.globalFilters.dateTo;
        window.globalFilters.dateTo = this.value;
        saveFilter('dateTo', this.value);
        updateActiveFiltersCounter();
        
        // Notify dashboard of filter change
        notifyDashboardFilterChange('dateTo', oldDateFilter, this.value);
        
        applyAllFilters();
    });
    
    // Apply filters button
    document.getElementById('applyGlobalFilters').addEventListener('click', function() {
        // Force sync all systems
        forceSyncAllSystems();
        showToast('success', 'Filters applied to all tables and dashboard');
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

function setupContactFilterForClient(clientId) {
    const contactFilter = document.getElementById('globalContactFilter');
    const contactOptions = contactFilter.querySelectorAll('option');
    
    if (!clientId) {
        // Show all contact options
        contactOptions.forEach(option => {
            option.style.display = 'block';
        });
        return;
    }
    
    let firstMatchingContact = '';
    
    // Filter and show only contacts that belong to the selected client
    contactOptions.forEach(option => {
        const optionClientId = option.dataset.clientId;
        
        if (!optionClientId) {
            // Keep "All contacts" option visible
            option.style.display = 'block';
        } else if (optionClientId == clientId) {
            // Show contacts that belong to the client
            option.style.display = 'block';
            if (!firstMatchingContact && option.value) {
                firstMatchingContact = option.value;
            }
        } else {
            // Hide contacts that don't belong to the client
            option.style.display = 'none';
        }
    });
}

function saveFilter(type, value) {
    const storageKey = `salesOrdersGlobal${type.charAt(0).toUpperCase() + type.slice(1)}Filter`;
    if (value) {
        localStorage.setItem(storageKey, value);
    } else {
        localStorage.removeItem(storageKey);
    }
}

function hasActiveFilters() {
    return window.globalFilters.client || 
           window.globalFilters.contact || 
           window.globalFilters.status || 
           window.globalFilters.dateFrom || 
           window.globalFilters.dateTo;
}

function applyAllFilters() {
    
    // Update global variable for dashboard compatibility
    window.globalClientFilter = window.globalFilters.client || '';
    
    // Apply to all DataTables
    applyFiltersToDataTables();
    
    // Apply to dashboard with improved error handling
    syncDashboardFilters();
}

function syncDashboardFilters() {
    
    // Try multiple dashboard sync methods in order of preference
    const syncMethods = [
        () => {
            if (typeof window.syncDashboardWithGlobalFilter === 'function') {
                window.syncDashboardWithGlobalFilter();
                return true;
            }
            return false;
        },
        () => {
            if (typeof window.onGlobalClientFilterChange === 'function') {
                window.onGlobalClientFilterChange();
                return true;
            }
            return false;
        },
        () => {
            if (typeof window.forceDashboardRefresh === 'function') {
                window.forceDashboardRefresh();
                return true;
            }
            return false;
        },
        () => {
            // Manual update of dashboard functions
            updateDashboardWidgets();
            return true;
        }
    ];
    
    let syncSuccess = false;
    for (const method of syncMethods) {
        try {
            if (method()) {
                syncSuccess = true;
                break;
            }
        } catch (error) {
            console.warn('⚠️ Dashboard sync method failed:', error);
        }
    }
    
    if (!syncSuccess) {
        console.warn('⚠️ No dashboard sync methods available');
    }
}

function applyFiltersToDataTables() {
    // Get all DataTables and apply filters
    const tableIds = [
        '#today-orders-table',
        '#tomorrow-orders-table', 
        '#pending-orders-table',
        '#week-orders-table',
        '#all-orders-table'
    ];
    
    tableIds.forEach(tableId => {
        const table = document.querySelector(tableId);
        if (table && $.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
            const dataTable = $(table).DataTable();
            dataTable.ajax.reload(null, false); // false = don't reset pagination
        }
    });
    
    // Apply status column styling and colors after tables reload
    setTimeout(() => {
        forceStatusColumnStyling();
        applyStatusColors();
    }, 1000);
}

function clearAllFilters() {
    
    // Clear global filters object
    window.globalFilters = {
        client: '',
        contact: '',
        status: '',
        dateFrom: '',
        dateTo: ''
    };
    
    // Clear UI elements
    document.getElementById('globalClientFilter').value = '';
    document.getElementById('globalContactFilter').value = '';
    document.getElementById('globalStatusFilter').value = '';
    document.getElementById('globalDateFromFilter').value = '';
    document.getElementById('globalDateToFilter').value = '';
    
    // Clear localStorage
    localStorage.removeItem('salesOrdersGlobalClientFilter');
    localStorage.removeItem('salesOrdersGlobalContactFilter');
    localStorage.removeItem('salesOrdersGlobalStatusFilter');
    localStorage.removeItem('salesOrdersGlobalDateFromFilter');
    localStorage.removeItem('salesOrdersGlobalDateToFilter');
    
    // Update active filters counter
    updateActiveFiltersCounter();
    
    // Reset contact options
    const contactOptions = document.querySelectorAll('#globalContactFilter option');
    contactOptions.forEach(option => {
        option.style.display = 'block';
    });
    
    // Hide accordion after clearing
    setTimeout(() => {
        hideFiltersAccordion();
    }, 1000);
    
    // Apply cleared filters
    applyAllFilters();
    
    showToast('info', 'All filters cleared');
}

function refreshAllTables() {
    
    // Refresh all DataTables
    const tableIds = [
        '#today-orders-table',
        '#tomorrow-orders-table', 
        '#pending-orders-table',
        '#week-orders-table',
        '#all-orders-table',
        '#service-table'
    ];
    
    tableIds.forEach(tableId => {
        const table = document.querySelector(tableId);
        if (table && $.fn.DataTable && $.fn.DataTable.isDataTable(table)) {
            const dataTable = $(table).DataTable();
            dataTable.ajax.reload(null, false);
        }
    });
    
    // Apply status column styling and colors after refresh
    setTimeout(() => {
        forceStatusColumnStyling();
        applyStatusColors();
    }, 1000);
    
    // Refresh deleted orders content
    loadDeletedOrdersContent();
    
    // Refresh dashboard with improved error handling
    forceDashboardSync();
    
    showToast('success', 'All tables and dashboard refreshed');
}

// ========================================
// FUNCIONES GLOBALES PARA LAS TABLAS
// ========================================

// Function for DataTables to get filter data
window.getGlobalFilterData = function(d) {
    // Add all global filters to the AJAX data
    d.client_filter = window.globalFilters.client || '';
    d.contact_filter = window.globalFilters.contact || '';
    d.status_filter = window.globalFilters.status || '';
    d.date_from_filter = window.globalFilters.dateFrom || '';
    d.date_to_filter = window.globalFilters.dateTo || '';
    
    // Add CSRF token
    d[<?= json_encode(csrf_token()) ?>] = <?= json_encode(csrf_hash()) ?>;
    
    return d;
};

// Backward compatibility functions
window.getCurrentClientFilter = function() {
    return window.globalFilters.client || '';
};

window.getCurrentClientName = function() {
    const clientId = window.globalFilters.client;
    if (!clientId) return '';
    
    const globalClientFilterSelect = document.getElementById('globalClientFilter');
    if (!globalClientFilterSelect) return '';
    
    const option = globalClientFilterSelect.querySelector(`option[value="${clientId}"]`);
    return option ? option.textContent.trim() : '';
};

// ========================================
// FUNCIONES DE ENLACE CON EL DASHBOARD
// ========================================

// Function to manually trigger dashboard filter sync (exposed globally)
window.triggerDashboardFilterSync = function() {
    forceDashboardSync();
};

// Function to update global client filter and sync dashboard
window.updateGlobalClientFilter = function(clientId) {
    
    // Update the filter value
    window.globalFilters.client = clientId || '';
    window.globalClientFilter = clientId || '';
    
    // Update UI
    const clientFilterElement = document.getElementById('globalClientFilter');
    if (clientFilterElement) {
        clientFilterElement.value = clientId || '';
    }
    
    // Save to localStorage
    saveFilter('client', clientId || '');
    
    // Sync dashboard immediately
    forceDashboardSync();
    
    // Apply to all tables
    applyFiltersToDataTables();
};

// Function to check if dashboard functions are available
window.checkDashboardAvailability = function() {
    const dashboardFunctions = [
        'syncDashboardWithGlobalFilter',
        'loadDashboardStats',
        'loadTopClients',
        'loadPerformanceMetrics',
        'loadRecentActivity',
        'updateChartsForPeriod'
    ];
    
    const available = {};
    const unavailable = [];
    
    dashboardFunctions.forEach(funcName => {
        if (typeof window[funcName] === 'function') {
            available[funcName] = true;
        } else {
            available[funcName] = false;
            unavailable.push(funcName);
        }
    });
    
    if (unavailable.length > 0) {
        console.warn('⚠️ Unavailable dashboard functions:', unavailable);
    }
    
    return available;
};

// Function to initialize dashboard filter listener (call this when dashboard loads)
window.initializeDashboardFilterListener = function() {
    
    // Set up a periodic check for filter changes
    if (!window.dashboardFilterWatcher) {
        window.dashboardFilterWatcher = setInterval(() => {
            const currentFilter = window.globalFilters?.client || '';
            const lastKnownFilter = window.lastKnownDashboardFilter || '';
            
            if (currentFilter !== lastKnownFilter) {
                window.lastKnownDashboardFilter = currentFilter;
                
                // Trigger dashboard sync
                if (typeof window.syncDashboardWithGlobalFilter === 'function') {
                    window.syncDashboardWithGlobalFilter();
                }
            }
        }, 1000); // Check every second
    }
    
    // Initial sync
    window.lastKnownDashboardFilter = window.globalFilters?.client || '';
    
};

// ========================================
// TAB MEMORY FUNCTIONALITY
// ========================================

function initializeTabMemory() {
    const TAB_STORAGE_KEY = 'salesOrdersActiveTab';
    
    // Restore last active tab on page load
    const savedTab = localStorage.getItem(TAB_STORAGE_KEY);
    if (savedTab) {
        const savedTabElement = document.querySelector(`a[href="${savedTab}"]`);
        if (savedTabElement) {
            const tab = new bootstrap.Tab(savedTabElement);
            tab.show();
            
            // Special handling for services tab
            if (savedTab === '#services-tab') {
                setTimeout(() => {
                    initializeServicesTable();
                }, 100);
            }
        }
    }
    
    // Save active tab when user switches tabs
    const tabTriggerElements = document.querySelectorAll('.nav-tabs a[data-bs-toggle="tab"]');
    tabTriggerElements.forEach(function(tabElement) {
        tabElement.addEventListener('shown.bs.tab', function(event) {
            const activeTabHref = event.target.getAttribute('href');
            localStorage.setItem(TAB_STORAGE_KEY, activeTabHref);
            
            // Special handling for different tabs
            if (activeTabHref === '#services-tab') {
                setTimeout(() => {
                    initializeServicesTable();
                }, 100);
            } else if (activeTabHref === '#deleted-orders-tab') {
                loadDeletedOrdersContent();
            }
        });
    });
}

// ========================================
// MODAL FUNCTIONALITY (SIMPLIFIED)
// ========================================

function initializeServicesModal() {
    // Handle Add Service button click
    const addServiceBtn = document.querySelector('a[data-bs-target="#serviceModal"]');
    if (addServiceBtn) {
        addServiceBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Add Service button clicked');
            
            // Load modal form for new service
            fetch(<?= json_encode(base_url('sales_orders_services/modal_form')) ?>, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                const serviceModal = document.getElementById('serviceModal');
                const modalContent = serviceModal.querySelector('.modal-content');
                modalContent.innerHTML = data;
                
                // Initialize the modal
                const modal = new bootstrap.Modal(serviceModal);
                modal.show();
                
                // Replace feather icons
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
                
                console.log('Service modal loaded successfully');
            })
            .catch(error => {
                console.error('Error loading service form:', error);
                showToast('error', 'Error loading service form');
            });
        });
    }
    
    // Handle service form submission
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.id === 'serviceForm') {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            
            fetch(<?= json_encode(base_url('sales_orders_services/store')) ?>, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const serviceModal = document.getElementById('serviceModal');
                    const modal = bootstrap.Modal.getInstance(serviceModal);
                    
                    if (modal) {
                        modal.hide();
                    } else {
                        // Fallback: close modal manually
                        serviceModal.classList.remove('show');
                        serviceModal.style.display = 'none';
                        serviceModal.setAttribute('aria-hidden', 'true');
                        
                        // Remove backdrop manually
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.remove();
                        }
                        
                        // Restore body overflow
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }
                    
                    showToast('success', data.message || 'Service saved successfully');
                    
                    // Refresh services table if it exists
                    if (window.serviceTable) {
                        window.serviceTable.ajax.reload();
                    }
                } else {
                    showToast('error', data.message || 'Error saving service');
                }
            })
            .catch(error => {
                console.error('Error saving service:', error);
                showToast('error', 'Error saving service');
            });
        }
    });
    
    // Handle edit service buttons
    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('.edit-service')) {
            e.preventDefault();
            
            const editBtn = e.target.closest('.edit-service');
            const serviceId = editBtn.dataset.id;
            
            if (serviceId) {
                // Load modal form for editing
                fetch(<?= json_encode(base_url('sales_orders_services/modal_form')) ?> + '?id=' + serviceId, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    const serviceModal = document.getElementById('serviceModal');
                    const modalContent = serviceModal.querySelector('.modal-content');
                    modalContent.innerHTML = data;
                    
                    // Initialize the modal
                    const modal = new bootstrap.Modal(serviceModal);
                    modal.show();
                    
                    // Replace feather icons
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                    
                    console.log('Service edit modal loaded successfully');
                })
                .catch(error => {
                    console.error('Error loading service data:', error);
                    showToast('error', 'Error loading service data');
                });
            }
        }
    });
    
    // Handle modal close events to ensure overlay is removed
    document.addEventListener('hidden.bs.modal', function(e) {
        if (e.target && e.target.id === 'serviceModal') {
            console.log('Service modal hidden event');
            
            // Force remove any remaining backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            
            // Restore body state
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            console.log('Service modal cleanup completed');
        }
    });
    
    // Handle potential overlay clicking issue
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('modal-backdrop')) {
            const serviceModal = document.getElementById('serviceModal');
            if (serviceModal && serviceModal.classList.contains('show')) {
                const modal = bootstrap.Modal.getInstance(serviceModal);
                if (modal) {
                    modal.hide();
                }
            }
        }
    });
}

// FUNCIÓN REMOVIDA - La inicialización del modal ahora se maneja en global-sales-order-modal.js

// ========================================
// SERVICES TABLE FUNCTIONALITY
// ========================================

function initializeServicesTable() {
    if ($.fn.DataTable.isDataTable('#service-table')) {
        $('#service-table').DataTable().destroy();
    }

    var serviceTable = $('#service-table').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": <?= json_encode(base_url('sales_orders_services/list_data')) ?>,
            "type": "POST",
            "dataSrc": function(json) {
                return json.data || [];
            }
        },
        "columns": [
            {
                "data": "service_name",
                "render": function(data, type, row) {
                    var html = '<div class="service-name fw-medium text-primary">' + (data || 'N/A') + '</div>';
                    if (row.service_description && row.service_description.trim() !== '') {
                        html += '<div class="text-muted small">' + 
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
                    return '<span class="fw-medium text-success">$' + parseFloat(data || 0).toFixed(2) + '</span>';
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
                            <a href="${<?= json_encode(base_url('sales_orders_services/view/')) ?> + row.id}" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="View Service">
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
        "language": {
            "emptyTable": "No services found",
            "processing": "Loading services...",
            "search": "Search services:",
        },
        "drawCallback": function(settings) {
            if (typeof feather !== 'undefined') {
                setTimeout(() => {
                    feather.replace();
                }, 50);
            }
        }
    });

    window.serviceTable = serviceTable;
}

// ========================================
// UTILITY FUNCTIONS
// ========================================

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

// ========================================
// PLACEHOLDER FUNCTIONS (TO BE IMPLEMENTED)
// ========================================

// FUNCIONES REMOVIDAS - openModalForNewOrder y loadOrderForEdit ahora manejadas por global-sales-order-modal.js

function setupNativeEventListeners() {
    
    // Set up client change listener
    const clientSelect = document.getElementById('client_id');
    if (clientSelect) {
        clientSelect.addEventListener('change', function(e) {
            // Extract the value from the event
            const clientId = e.target.value;
            
            // Only proceed if we're not in a modal context
            const isInModal = e.target.closest('.modal');
            if (isInModal) {
                return;
            }
            
            handleClientChange(clientId);
        });
    }
    
    // Set up form submission
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitOrderForm();
        });
    }
}

function initializeChoicesSelects() {
    
    // Destroy existing instances first
    if (window.choicesInstances) {
        Object.values(window.choicesInstances).forEach(instance => {
            if (instance && typeof instance.destroy === 'function') {
                instance.destroy();
            }
        });
    }
    
    window.choicesInstances = {};
    
    // Initialize all select elements with data-choices attribute
    const selectElements = document.querySelectorAll('[data-choices]');
    selectElements.forEach(select => {
        if (select.id && !window.choicesInstances[select.id]) {
            try {
                const searchEnabled = select.hasAttribute('data-choices-search-true');
                window.choicesInstances[select.id] = new Choices(select, {
                    searchEnabled: searchEnabled,
                    itemSelectText: '',
                    removeItemButton: false,
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: select.getAttribute('data-placeholder') || 'Select an option'
                });
            } catch (error) {
                console.error(`❌ Error initializing Choices.js for #${select.id}:`, error);
            }
        }
    });
}

function setupFormEventListeners() {
    
    // Client change handler
    const clientSelect = document.getElementById('client_id');
    if (clientSelect && window.choicesInstances['client_id']) {
        const choicesInstance = window.choicesInstances['client_id'];
        choicesInstance.passedElement.element.addEventListener('change', function(e) {
            handleClientChange(e.target.value);
        });
    }
}

function handleClientChange(clientId) {
    
    const salespersonSelect = document.getElementById('salesperson_id');
    const serviceSelect = document.getElementById('service_id');
    
    if (!clientId) {
        // Clear contacts and services
        updateSelectOptions('salesperson_id', []);
        updateSelectOptions('service_id', []);
        return;
    }
    
    // Load contacts for selected client
    fetch(<?= json_encode(base_url('sales_orders/getContactsForClient/')) ?> + clientId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateSelectOptions('salesperson_id', data.contacts);
    } else {
            console.error('Error loading contacts:', data.message);
            updateSelectOptions('salesperson_id', []);
        }
    })
    .catch(error => {
        console.error('Error loading contacts:', error);
        updateSelectOptions('salesperson_id', []);
    });
    
    // Load services for selected client
    fetch(<?= json_encode(base_url('sales_orders/getServicesForClient/')) ?> + clientId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateSelectOptions('service_id', data.services);
        } else {
            console.error('Error loading services:', data.message);
            updateSelectOptions('service_id', []);
        }
    })
    .catch(error => {
        console.error('Error loading services:', error);
        updateSelectOptions('service_id', []);
    });
}

function updateSelectOptions(selectId, options) {
    const choicesInstance = window.choicesInstances[selectId];
    if (!choicesInstance) {
        console.error(`Choices instance not found for #${selectId}`);
        return;
    }
    
    // Clear existing options
    choicesInstance.clearStore();
    
    // Add new options
    const formattedOptions = options.map(option => ({
        value: option.id || option.value,
        label: option.name || option.label || option.text,
        selected: false,
        disabled: false
    }));
    
    choicesInstance.setChoices(formattedOptions, 'value', 'label', true);
}

function setDefaultDateTime() {
    
    const dateField = document.getElementById('date');
    const timeField = document.getElementById('time');
    
    if (dateField && !dateField.value) {
        // Set to today's date
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        dateField.value = `${year}-${month}-${day}`;
    }
    
    if (timeField && !timeField.value) {
        // Set to current time + 1 hour
        const now = new Date();
        now.setHours(now.getHours() + 1);
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        timeField.value = `${hours}:${minutes}`;
    }
}

// FUNCIÓN resetModalForm REMOVIDA - Manejada por global-sales-order-modal.js

// FUNCIÓN submitOrderForm REMOVIDA - Manejada por global-sales-order-modal.js
function loadDeletedOrdersContent() {
    const deletedTabContent = document.querySelector('#deleted-orders-tab');
    if (!deletedTabContent) return;
    
    // Show loading spinner
    deletedTabContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading deleted orders...</p>
        </div>
    `;
    
    // Fetch deleted orders content
    fetch(<?= json_encode(base_url('sales_orders/deleted_content')) ?>, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        deletedTabContent.innerHTML = html;
        
        // Initialize icons for the new content
        setTimeout(() => {
            if (typeof window.initializeDeletedOrdersIcons === 'function') {
                window.initializeDeletedOrdersIcons();
            }
        }, 100);
    })
    .catch(error => {
        console.error('Error loading deleted orders:', error);
        deletedTabContent.innerHTML = `
            <div class="text-center py-5">
                <div class="mb-3">
                    <i data-feather="alert-circle" class="icon-lg text-danger"></i>
                </div>
                <h5 class="text-danger">Error Loading Content</h5>
                <p class="text-muted">Failed to load deleted orders. Please try again.</p>
                <button class="btn btn-outline-primary" onclick="loadDeletedOrdersContent()">
                    <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                    Retry
                </button>
            </div>
        `;
        
        // Initialize feather icons for error state
        setTimeout(() => {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }, 100);
    });
}

// Global variables REMOVIDAS - Modal management handled by global-sales-order-modal.js

// ========================================
// NOTIFICACIÓN DE CAMBIOS DE FILTRO AL DASHBOARD
// ========================================

function notifyDashboardFilterChange(filterType, oldValue, newValue) {
    
    // Update global client filter variable for dashboard
    if (filterType === 'client') {
        window.globalClientFilter = newValue;
    }
    
    // Immediate dashboard notification with retry mechanism
    setTimeout(() => {
        syncDashboardWithDelay();
    }, 100);
    
    // Secondary sync to ensure dashboard updates
        setTimeout(() => {
        forceDashboardSync();
        }, 500);
    }
    
function syncDashboardWithDelay() {
    // Try multiple methods to sync dashboard
    const methods = [
        'window.syncDashboardWithGlobalFilter',
        'window.onGlobalClientFilterChange', 
        'window.forceDashboardRefresh',
        'window.applyFilterToDashboard'
    ];
    
    methods.forEach(methodName => {
        try {
            const method = window[methodName.split('.')[1]];
            if (typeof method === 'function') {
                method();
            }
        } catch (error) {
            console.warn(`⚠️ Could not call ${methodName}:`, error);
        }
    });
}

function forceDashboardSync() {
    
    // Try all available dashboard sync methods
    const syncAttempted = [];
    
    // Method 1: Direct sync function
                        if (typeof window.syncDashboardWithGlobalFilter === 'function') {
        try {
                            window.syncDashboardWithGlobalFilter();
            syncAttempted.push('syncDashboardWithGlobalFilter');
        } catch (error) {
            console.warn('⚠️ syncDashboardWithGlobalFilter failed:', error);
        }
    }
    
    // Method 2: Dashboard refresh
    if (typeof window.refreshDashboard === 'function') {
        try {
            window.refreshDashboard();
            syncAttempted.push('refreshDashboard');
        } catch (error) {
            console.warn('⚠️ refreshDashboard failed:', error);
        }
    }
    
    // Method 3: Force refresh
    if (typeof window.forceDashboardRefresh === 'function') {
        try {
            window.forceDashboardRefresh();
            syncAttempted.push('forceDashboardRefresh');
        } catch (error) {
            console.warn('⚠️ forceDashboardRefresh failed:', error);
        }
    }
    
    // Method 4: Manual widget update
    try {
        updateDashboardWidgets();
        syncAttempted.push('updateDashboardWidgets');
    } catch (error) {
        console.warn('⚠️ updateDashboardWidgets failed:', error);
    }
    
    
    if (syncAttempted.length === 0) {
        console.warn('⚠️ No dashboard sync methods were available');
    }
}

function updateDashboardWidgets() {
    
    // Call all dashboard update functions directly
    if (typeof window.loadDashboardStats === 'function') {
        window.loadDashboardStats();
    }
    if (typeof window.loadTopClients === 'function') {
        window.loadTopClients();
    }
    if (typeof window.loadPerformanceMetrics === 'function') {
        window.loadPerformanceMetrics();
    }
    if (typeof window.loadRecentActivity === 'function') {
        window.loadRecentActivity();
    }
}

function forceSyncAllSystems() {
    
    // Update global variables
    window.globalClientFilter = window.globalFilters.client || '';
    
    // Apply to all DataTables
    applyFiltersToDataTables();
    
    // Force dashboard sync with multiple attempts
    forceDashboardSync();
    
    // Additional sync after delay to catch any late-loading components
            setTimeout(() => {
        forceDashboardSync();
                    }, 1000);
}

// ========================================
// FUNCIONES DEL ACORDEÓN DE FILTROS
// ========================================

function initializeFiltersAccordion() {
    // Update active filters counter
    updateActiveFiltersCounter();
    
    // Remember accordion state
    const accordionState = localStorage.getItem('salesOrdersFiltersAccordionState');
    if (accordionState === 'open' || hasActiveFilters()) {
        showFiltersAccordion();
    }
    
    // Save accordion state when toggled
    const filtersAccordion = document.getElementById('filtersContent');
    if (filtersAccordion) {
        filtersAccordion.addEventListener('shown.bs.collapse', function() {
            localStorage.setItem('salesOrdersFiltersAccordionState', 'open');
        });
        
        filtersAccordion.addEventListener('hidden.bs.collapse', function() {
            localStorage.setItem('salesOrdersFiltersAccordionState', 'closed');
        });
    }
}

function updateActiveFiltersCounter() {
    const activeFiltersCount = getActiveFiltersCount();
    const badge = document.getElementById('activeFiltersCount');
    
    if (badge) {
        if (activeFiltersCount > 0) {
            badge.textContent = activeFiltersCount;
            badge.classList.remove('d-none');
            } else {
            badge.classList.add('d-none');
        }
    }
}

function getActiveFiltersCount() {
    let count = 0;
    if (window.globalFilters.client) count++;
    if (window.globalFilters.contact) count++;
    if (window.globalFilters.status) count++;
    if (window.globalFilters.dateFrom) count++;
    if (window.globalFilters.dateTo) count++;
    return count;
}

function showFiltersAccordion() {
    const filtersContent = document.getElementById('filtersContent');
    const accordionButton = document.querySelector('[data-bs-target="#filtersContent"]');
    
    if (filtersContent && accordionButton) {
        const bsCollapse = new bootstrap.Collapse(filtersContent, {
            show: true
        });
        accordionButton.setAttribute('aria-expanded', 'true');
        accordionButton.classList.remove('collapsed');
    }
}

function hideFiltersAccordion() {
    const filtersContent = document.getElementById('filtersContent');
    const accordionButton = document.querySelector('[data-bs-target="#filtersContent"]');
    
    if (filtersContent && accordionButton) {
        const bsCollapse = new bootstrap.Collapse(filtersContent, {
            hide: true
        });
        accordionButton.setAttribute('aria-expanded', 'false');
        accordionButton.classList.add('collapsed');
    }
}

// ========================================
// GLOBAL EVENT LISTENER MANAGEMENT
// ========================================

// Prevent duplicate event listeners
window.salesOrderEventListenersInitialized = false;

function initializeGlobalEventListeners() {
    if (window.salesOrderEventListenersInitialized) {
        return;
    }
    
    
    // Single global event listener for edit buttons - redirect to view page
    $(document).off('click', '.edit-order-btn').on('click', '.edit-order-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const orderId = $(this).data('id');
        
        if (orderId) {
            // Redirect to view page and auto-open edit modal
            window.location.href = <?= json_encode(base_url('sales_orders/view/')) ?> + orderId + '?edit=1';
        } else {
            console.error('No order ID found in edit button');
            showToast('error', 'No order ID found');
        }
    });
    
    // Single global event listener for delete buttons
    $(document).off('click', '.delete-order-btn').on('click', '.delete-order-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const orderId = $(this).data('id');
        
        if (orderId) {
            deleteOrderGlobal(orderId);
        } else {
            console.error('No order ID found in delete button');
            showToast('error', 'No order ID found');
        }
    });
    
    // Select all functionality for deleted orders
    $(document).off('change', '#selectAll').on('change', '#selectAll', function() {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Global event listener for status dropdown changes
    $(document).off('change', '.status-dropdown').on('change', '.status-dropdown', function(e) {
        const dropdown = e.target;
        const currentValue = dropdown.value;
        
        // Remove existing status classes
        dropdown.classList.remove('status-pending', 'status-in_progress', 'status-completed', 'status-cancelled');
        
        // Apply new status class
        if (currentValue) {
            dropdown.classList.add(`status-${currentValue}`);
        }
    });
    
    // Mark as initialized
    window.salesOrderEventListenersInitialized = true;
}

function deleteOrderGlobal(orderId) {
    Swal.fire({
        title: <?= json_encode(lang('App.are_you_sure')) ?>,
        text: <?= json_encode(lang('App.confirm_delete_order')) ?>,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f06548',
        cancelButtonColor: '#74788d',
        confirmButtonText: <?= json_encode(lang('App.yes_delete')) ?>,
        cancelButtonText: <?= json_encode(lang('App.cancel')) ?>,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: <?= json_encode(lang('App.deleting')) ?> + '...',
                text: <?= json_encode(lang('App.please_wait')) ?>,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(<?= json_encode(base_url('sales_orders/delete/')) ?> + orderId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    [<?= json_encode(csrf_token()) ?>]: <?= json_encode(csrf_hash()) ?>
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                
                if (data.success) {
                    showToast('success', data.message || 'Order deleted successfully');
                    refreshAllTables();
                } else {
                    showToast('error', data.message || 'Error deleting order');
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error deleting order:', error);
                showToast('error', 'Error deleting order');
            });
        }
    });
}

// ========================================
// DELETED ORDERS FUNCTIONS
// ========================================

// Restore single order
function restoreOrder(orderId) {
    Swal.fire({
        title: <?= json_encode(lang('App.are_you_sure')) ?>,
        text: <?= json_encode(lang('App.want_to_restore_order')) ?>,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: <?= json_encode(lang('App.yes_restore')) ?>,
        cancelButtonText: <?= json_encode(lang('App.cancel')) ?>
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: <?= json_encode(lang('App.restoring')) ?> + '...',
                text: <?= json_encode(lang('App.please_wait')) ?>,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(<?= json_encode(base_url('sales_orders/restore/')) ?> + orderId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    [<?= json_encode(csrf_token()) ?>]: <?= json_encode(csrf_hash()) ?>
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.close();
                    showToast('success', data.message || 'Order restored successfully');
                    // Refresh tables dynamically instead of reloading page
                    refreshAllTables();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || <?= json_encode(lang('App.error_restoring_order')) ?>,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error restoring order:', error);
                Swal.fire({
                    title: 'Error',
                    text: <?= json_encode(lang('App.error_restoring_order')) ?>,
                    icon: 'error'
                });
            });
        }
    });
}

// Permanently delete single order
function forceDeleteOrder(orderId) {
    Swal.fire({
        title: <?= json_encode(lang('App.are_you_sure')) ?>,
        text: <?= json_encode(lang('App.want_to_permanently_delete_order')) ?>,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: <?= json_encode(lang('App.yes_permanently_delete')) ?>,
        cancelButtonText: <?= json_encode(lang('App.cancel')) ?>
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: <?= json_encode(lang('App.deleting')) ?> + '...',
                text: <?= json_encode(lang('App.please_wait')) ?>,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(<?= json_encode(base_url('sales_orders/forceDelete/')) ?> + orderId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    [<?= json_encode(csrf_token()) ?>]: <?= json_encode(csrf_hash()) ?>
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.close();
                    showToast('success', data.message || 'Order permanently deleted');
                    // Refresh tables dynamically instead of reloading page
                    refreshAllTables();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || <?= json_encode(lang('App.error_permanently_deleting_order')) ?>,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error permanently deleting order:', error);
                Swal.fire({
                    title: 'Error',
                    text: <?= json_encode(lang('App.error_permanently_deleting_order')) ?>,
                    icon: 'error'
                });
            });
        }
    });
}

// Bulk restore selected orders
function bulkRestore() {
    const selectedIds = getSelectedOrderIds();
    if (selectedIds.length === 0) {
        Swal.fire({
            title: 'Atención',
            text: 'Por favor selecciona al menos una orden para restaurar',
            icon: 'warning'
        });
        return;
    }
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres restaurar ${selectedIds.length} orden(es) seleccionada(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            showToast('info', 'Funcionalidad de restauración masiva próximamente');
        }
    });
}

// Bulk permanently delete selected orders
function bulkForceDelete() {
    const selectedIds = getSelectedOrderIds();
    if (selectedIds.length === 0) {
        Swal.fire({
            title: 'Atención',
            text: 'Por favor selecciona al menos una orden para eliminar permanentemente',
            icon: 'warning'
        });
        return;
    }
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¡Esta acción no se puede deshacer! ¿Quieres eliminar permanentemente ${selectedIds.length} orden(es) seleccionada(s)?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar permanentemente',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            showToast('info', 'Funcionalidad de eliminación masiva próximamente');
        }
    });
}

// Get selected order IDs for bulk operations
function getSelectedOrderIds() {
    const checkboxes = document.querySelectorAll('.order-checkbox:checked');
    return Array.from(checkboxes).map(checkbox => checkbox.value);
}

// ========================================
// VIN DECODER FUNCTIONALITY FOR INDEX FORM
// ========================================

function setupIndexVINDecoding() {
    const vinInput = document.getElementById('vin');
    if (!vinInput) {
        console.log('SalesOrderIndex: VIN input not found');
        return;
    }
    
    console.log('SalesOrderIndex: Setting up VIN decoding');
    
    // Add input event listener for real-time validation
    vinInput.addEventListener('input', function(e) {
        let vin = e.target.value.toUpperCase();
        
        // Remove invalid characters and replace them
        const validVin = vin.replace(/[^A-HJ-NPR-Z0-9]/g, '');
        
        if (validVin !== vin) {
            e.target.value = validVin;
            showVINStatus('warning', vinTranslations.onlyAlphanumeric);
            return;
        }
        
        // Clear vehicle field when VIN is modified and shorter than 17 characters
        if (vin.length < 17) {
            clearVehicleField();
        }
        
        // Check VIN length
        if (vin.length === 17) {
            // Valid 17-character VIN - attempt decoding
            showVINStatus('loading', vinTranslations.loading);
            decodeIndexVIN(vin);
        } else if (vin.length >= 10 && vin.length < 17) {
            // Partial VIN with some basic info
            const basicInfo = decodeVINBasic(vin + '0'.repeat(17 - vin.length));
            if (basicInfo.year || basicInfo.make) {
                const partialInfo = [basicInfo.year, basicInfo.make].filter(Boolean).join(' ');
                showVINStatus('info', `${vin.length}/17 ${vinTranslations.characters} - ${partialInfo} (${vinTranslations.partial})`);
            } else {
                showVINStatus('info', `${vin.length}/17 ${vinTranslations.characters}`);
            }
        } else if (vin.length > 0 && vin.length < 10) {
            showVINStatus('info', `${vin.length}/17 ${vinTranslations.characters}`);
        } else if (vin.length > 17) {
            // Too long
            e.target.value = vin.substring(0, 17);
            showVINStatus('error', vinTranslations.cannotExceed17);
        }
    });
    
    // Add VIN validation styles
    addIndexVINStyles();
    
    console.log('SalesOrderIndex: VIN decoding setup complete');
}

function decodeIndexVIN(vin) {
    console.log('SalesOrderIndex: Decoding VIN with NHTSA API:', vin);
    
    // Advanced VIN validation
    const validationResult = isValidIndexVIN(vin);
    if (!validationResult.isValid) {
        let errorMessage;
        switch (validationResult.error) {
            case 'suspicious_patterns':
                errorMessage = vinTranslations.suspiciousPatterns;
                break;
            case 'invalid_check_digit':
                errorMessage = vinTranslations.invalidCheckDigit;
                break;
            case 'invalid_format':
                errorMessage = vinTranslations.invalidFormat;
                break;
            default:
                errorMessage = vinTranslations.invalidFormat;
        }
        showVINStatus('error', errorMessage);
        return;
    }
    
    // Show loading status
    showVINStatus('loading', vinTranslations.loading);
    
    // Call NHTSA vPIC API
    const nhtsa_url = `https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/${vin}?format=json`;
    
    fetch(nhtsa_url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('SalesOrderIndex: NHTSA API response status:', response.status);
        if (!response.ok) {
            throw new Error(`NHTSA API Error: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('SalesOrderIndex: NHTSA API response data:', data);
        
        if (data && data.Results && data.Results.length > 0) {
            const vehicleData = data.Results[0];
            console.log('SalesOrderIndex: Vehicle data from NHTSA:', vehicleData);
            
            // Build comprehensive vehicle string
            const vehicleString = buildIndexVehicleString(vehicleData);
            
            if (vehicleString && vehicleString.trim() !== '') {
                // Update vehicle field
                const vehicleInput = document.getElementById('vehicle');
                if (vehicleInput) {
                    vehicleInput.value = vehicleString;
                    vehicleInput.classList.add('vin-decoded');
                    
                    // Add temporary visual indicator to vehicle field
                    vehicleInput.style.backgroundColor = '#d4edda';
                    vehicleInput.style.borderColor = '#28a745';
                    
                    // Clear VIN status after successful decoding
                    setTimeout(() => {
                        clearVINStatus();
                        // Reset vehicle field styling
                        vehicleInput.style.backgroundColor = '';
                        vehicleInput.style.borderColor = '';
                    }, 2000);
                    
                    console.log('SalesOrderIndex: Vehicle field updated with NHTSA data:', vehicleString);
                } else {
                    console.error('SalesOrderIndex: Vehicle input field not found');
                }
            } else {
                // No vehicle info found
                showVINStatus('warning', vinTranslations.validNoInfo);
                console.log('SalesOrderIndex: No vehicle information found in NHTSA response');
            }
        } else {
            console.warn('SalesOrderIndex: No results found in NHTSA response');
            showVINStatus('warning', vinTranslations.decodedNoData);
        }
    })
    .catch(error => {
        console.error('SalesOrderIndex: NHTSA API error:', error);
        
        // Fallback to basic decoding if NHTSA API fails
        console.log('SalesOrderIndex: Falling back to basic VIN decoding');
        showVINStatus('loading', vinTranslations.loading);
        
        try {
            const basicInfo = decodeVINBasic(vin);
            
            if (basicInfo.year || basicInfo.make) {
                const vehicleParts = [];
                if (basicInfo.year) vehicleParts.push(basicInfo.year);
                if (basicInfo.make) vehicleParts.push(basicInfo.make);
                
                const vehicleString = vehicleParts.join(' ');
                
                const vehicleInput = document.getElementById('vehicle');
                if (vehicleInput) {
                    vehicleInput.value = vehicleString;
                    vehicleInput.classList.add('vin-decoded');
                    
                    // Add temporary visual indicator to vehicle field (fallback)
                    vehicleInput.style.backgroundColor = '#fff3cd';
                    vehicleInput.style.borderColor = '#fd7e14';
                    
                    setTimeout(() => {
                        clearVINStatus();
                        // Reset vehicle field styling
                        vehicleInput.style.backgroundColor = '';
                        vehicleInput.style.borderColor = '';
                    }, 2000);
                    console.log('SalesOrderIndex: Fallback decoding successful:', vehicleString);
                }
            } else {
                showVINStatus('error', vinTranslations.unableToDecode);
            }
        } catch (fallbackError) {
            console.error('SalesOrderIndex: Fallback decoding also failed:', fallbackError);
            showVINStatus('error', vinTranslations.decodingFailed);
        }
    });
}

function buildIndexVehicleString(nhtsa_data) {
    // Build simplified vehicle string from NHTSA data
    // Target format: "2017 ACURA MDX (Tech) (6 cyl)"
    
    const parts = [];
    
    // 1. Model Year
    if (nhtsa_data.ModelYear && nhtsa_data.ModelYear !== '') {
        parts.push(nhtsa_data.ModelYear);
    }
    
    // 2. Make (Manufacturer)
    if (nhtsa_data.Make && nhtsa_data.Make !== '') {
        parts.push(nhtsa_data.Make.toUpperCase());
    }
    
    // 3. Model
    if (nhtsa_data.Model && nhtsa_data.Model !== '') {
        parts.push(nhtsa_data.Model.toUpperCase());
    }
    
    // 4. Series/Trim in parentheses
    if (nhtsa_data.Series && nhtsa_data.Series !== '') {
        parts.push(`(${nhtsa_data.Series})`);
    } else if (nhtsa_data.Trim && nhtsa_data.Trim !== '') {
        parts.push(`(${nhtsa_data.Trim})`);
    }
    
    // 5. Engine cylinders in parentheses
    if (nhtsa_data.EngineNumberOfCylinders && nhtsa_data.EngineNumberOfCylinders !== '') {
        parts.push(`(${nhtsa_data.EngineNumberOfCylinders} cyl)`);
    }
    
    const result = parts.join(' ').trim();
    
    console.log('SalesOrderIndex: Built simplified vehicle string from NHTSA data:', {
        'ModelYear': nhtsa_data.ModelYear,
        'Make': nhtsa_data.Make,
        'Model': nhtsa_data.Model,
        'Series': nhtsa_data.Series,
        'Trim': nhtsa_data.Trim,
        'EngineNumberOfCylinders': nhtsa_data.EngineNumberOfCylinders,
        'Final String': result
    });
    
    return result;
}

function isValidIndexVIN(vin) {
    // VIN must be 17 characters, alphanumeric, no I, O, or Q
    if (vin.length !== 17) return false;
    if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) return false;
    
    // Check for suspicious patterns
    const suspiciousPatternResult = checkIndexSuspiciousPatterns(vin);
    if (!suspiciousPatternResult.isValid) {
        return { isValid: false, error: suspiciousPatternResult.error };
    }
    
    // Validate check digit (9th position, index 8)
    const checkDigitResult = validateIndexVINCheckDigit(vin);
    if (!checkDigitResult.isValid) {
        return { isValid: false, error: checkDigitResult.error };
    }
    
    return { isValid: true };
}

function checkIndexSuspiciousPatterns(vin) {
    // Check for consecutive identical characters (4 or more)
    const consecutiveRegex = /(.)\1{3,}/;
    if (consecutiveRegex.test(vin)) {
        return { isValid: false, error: 'suspicious_patterns' };
    }
    
    // Check for excessive character repetition (more than 4 occurrences)
    const charCount = {};
    for (let char of vin) {
        charCount[char] = (charCount[char] || 0) + 1;
        if (charCount[char] > 4) {
            return { isValid: false, error: 'suspicious_patterns' };
        }
    }
    
    return { isValid: true };
}

function validateIndexVINCheckDigit(vin) {
    // ISO 3779 VIN check digit validation
    const weights = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];
    const charValues = {
        'A': 1, 'B': 2, 'C': 3, 'D': 4, 'E': 5, 'F': 6, 'G': 7, 'H': 8,
        'J': 1, 'K': 2, 'L': 3, 'M': 4, 'N': 5, 'P': 7, 'R': 9,
        'S': 2, 'T': 3, 'U': 4, 'V': 5, 'W': 6, 'X': 7, 'Y': 8, 'Z': 9,
        '0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9
    };
    
    let sum = 0;
    for (let i = 0; i < 17; i++) {
        if (i === 8) continue; // Skip check digit position
        const char = vin.charAt(i);
        const value = charValues[char];
        if (value === undefined) {
            return { isValid: false, error: 'invalid_format' };
        }
        sum += value * weights[i];
    }
    
    const calculatedCheckDigit = sum % 11;
    const actualCheckDigit = vin.charAt(8);
    const expectedCheckDigit = calculatedCheckDigit === 10 ? 'X' : calculatedCheckDigit.toString();
    
    if (actualCheckDigit !== expectedCheckDigit) {
        return { isValid: false, error: 'invalid_check_digit' };
    }
    
    return { isValid: true };
}

function decodeVINBasic(vin) {
    // Basic VIN decoding - extracts year, make, and some model info
    // This is a simplified decoder - for production, consider using a VIN API service
    
    const vinInfo = {
        year: null,
        make: null,
        model: null,
        trim: null
    };
    
    try {
        // Decode year (10th character)
        const yearCode = vin.charAt(9);
        vinInfo.year = decodeYearFromVIN(yearCode);
        
        // Decode manufacturer (World Manufacturer Identifier - first 3 characters)
        const wmi = vin.substring(0, 3);
        vinInfo.make = decodeMakeFromWMI(wmi);
        
        // For more detailed decoding, we would need extensive VIN databases
        // This is a basic implementation
        
    } catch (error) {
        console.error('Basic VIN decoding error:', error);
    }
    
    return vinInfo;
}

function decodeYearFromVIN(yearCode) {
    const yearCodes = {
        'A': 1980, 'B': 1981, 'C': 1982, 'D': 1983, 'E': 1984, 'F': 1985, 'G': 1986, 'H': 1987,
        'J': 1988, 'K': 1989, 'L': 1990, 'M': 1991, 'N': 1992, 'P': 1993, 'R': 1994, 'S': 1995,
        'T': 1996, 'V': 1997, 'W': 1998, 'X': 1999, 'Y': 2000, '1': 2001, '2': 2002, '3': 2003,
        '4': 2004, '5': 2005, '6': 2006, '7': 2007, '8': 2008, '9': 2009, 'A': 2010, 'B': 2011,
        'C': 2012, 'D': 2013, 'E': 2014, 'F': 2015, 'G': 2016, 'H': 2017, 'J': 2018, 'K': 2019,
        'L': 2020, 'M': 2021, 'N': 2022, 'P': 2023, 'R': 2024, 'S': 2025, 'T': 2026, 'V': 2027,
        'W': 2028, 'X': 2029, 'Y': 2030
    };
    
    return yearCodes[yearCode] || null;
}

function decodeMakeFromWMI(wmi) {
    const wmiCodes = {
        // US Manufacturers
        '1G1': 'Chevrolet', '1G6': 'Cadillac', '1GC': 'Chevrolet', '1GT': 'GMC',
        '1FA': 'Ford', '1FB': 'Ford', '1FC': 'Ford', '1FD': 'Ford', '1FE': 'Ford', '1FF': 'Ford',
        '1FG': 'Ford', '1FH': 'Ford', '1FJ': 'Ford', '1FK': 'Ford', '1FL': 'Ford', '1FM': 'Ford',
        '1FN': 'Ford', '1FP': 'Ford', '1FR': 'Ford', '1FS': 'Ford', '1FT': 'Ford', '1FU': 'Ford',
        '1FV': 'Ford', '1FW': 'Ford', '1FX': 'Ford', '1FY': 'Ford', '1FZ': 'Ford',
        '1HD': 'Harley-Davidson', '1HG': 'Honda', '1J4': 'Jeep', '1J8': 'Jeep',
        '1L1': 'Lincoln', '1LN': 'Lincoln', '1ME': 'Mercury', '1MH': 'Mercury',
        '1N4': 'Nissan', '1N6': 'Nissan', '1VW': 'Volkswagen',
        '2C3': 'Chrysler', '2C4': 'Chrysler', '2D4': 'Dodge', '2D8': 'Dodge',
        '2FA': 'Ford', '2FB': 'Ford', '2FC': 'Ford', '2FD': 'Ford', '2FE': 'Ford',
        '2G1': 'Chevrolet', '2G4': 'Pontiac', '2HG': 'Honda', '2HK': 'Honda', '2HM': 'Hyundai',
        '2T1': 'Toyota', '2T2': 'Toyota', '2T3': 'Toyota',
        '3FA': 'Ford', '3FE': 'Ford', '3G1': 'Chevrolet', '3G3': 'Oldsmobile', '3G4': 'Buick',
        '3G5': 'Buick', '3G6': 'Pontiac', '3G7': 'Pontiac', '3GN': 'Chevrolet',
        '3H1': 'Honda', '3HG': 'Honda', '3HM': 'Honda', '3N1': 'Nissan', '3VW': 'Volkswagen',
        '4F2': 'Ford', '4F4': 'Mazda', '4M2': 'Mercury', '4S3': 'Subaru', '4S4': 'Subaru',
        '4T1': 'Toyota', '4T3': 'Toyota', '4US': 'BMW',
        '5F2': 'Ford', '5FN': 'Honda', '5J6': 'Honda', '5L1': 'Lincoln', '5N1': 'Nissan',
        '5NP': 'Hyundai', '5TD': 'Toyota', '5TE': 'Toyota', '5TF': 'Toyota',
        
        // German Manufacturers
        'WAU': 'Audi', 'WA1': 'Audi', 'WBA': 'BMW', 'WBS': 'BMW', 'WBX': 'BMW',
        'WDB': 'Mercedes-Benz', 'WDD': 'Mercedes-Benz', 'WDC': 'Mercedes-Benz',
        'WP0': 'Porsche', 'WP1': 'Porsche', 'WVW': 'Volkswagen', 'WV1': 'Volkswagen', 'WV2': 'Volkswagen',
        
        // Japanese Manufacturers
        'JH4': 'Acura', 'JHM': 'Honda', 'JF1': 'Subaru', 'JF2': 'Subaru',
        'JM1': 'Mazda', 'JM3': 'Mazda', 'JN1': 'Nissan', 'JN6': 'Nissan', 'JN8': 'Nissan',
        'JT2': 'Toyota', 'JT3': 'Toyota', 'JT4': 'Toyota', 'JT6': 'Toyota', 'JT8': 'Toyota',
        'JTD': 'Toyota', 'JTE': 'Toyota', 'JTF': 'Toyota', 'JTG': 'Toyota', 'JTH': 'Lexus',
        'JTJ': 'Lexus', 'JTK': 'Lexus', 'JTL': 'Lexus', 'JTM': 'Lexus', 'JTN': 'Lexus',
        
        // Korean Manufacturers
        'KMH': 'Hyundai', 'KMJ': 'Hyundai', 'KNA': 'Kia', 'KNB': 'Kia', 'KNC': 'Kia', 'KND': 'Kia',
        'KNE': 'Kia', 'KNF': 'Kia', 'KNG': 'Kia', 'KNH': 'Kia', 'KNJ': 'Kia', 'KNK': 'Kia',
        'KNL': 'Kia', 'KNM': 'Kia',
        
        // Other
        'SAL': 'Land Rover', 'SAJ': 'Jaguar', 'SCC': 'Lotus',
        'VF1': 'Renault', 'VF3': 'Peugeot', 'VF7': 'Citroën',
        'YK1': 'Saab', 'YS3': 'Saab', 'YV1': 'Volvo', 'YV4': 'Volvo'
    };
    
    return wmiCodes[wmi] || null;
}

function showVINStatus(type, message) {
    const vinStatus = document.getElementById('vin-status');
    const vinInput = document.getElementById('vin');
    
    // Clear previous status
    clearVINStatus();
    
    // For critical errors, show toast notification
    if (type === 'error') {
        showVINToast('error', message);
        
        // Also update input styling for visual feedback
        if (vinInput) {
            vinInput.classList.add('vin-error');
            // Clear error styling after 3 seconds
            setTimeout(() => {
                vinInput.classList.remove('vin-error');
            }, 3000);
        }
        return;
    }
    
    // For warnings about API issues, show toast
    if (type === 'warning' && (message.includes(vinTranslations.validNoInfo) || message.includes(vinTranslations.decodedNoData))) {
        showVINToast('warning', message);
        return;
    }
    
    // For info messages (character counter) and loading, show inline
    if (vinStatus && (type === 'info' || type === 'loading')) {
        vinStatus.textContent = message;
        vinStatus.className = `vin-status vin-status-${type}`;
        
        // Update input styling
        if (vinInput) {
            vinInput.classList.remove('vin-decoding', 'vin-success', 'vin-error', 'vin-warning');
            
            if (type === 'loading') {
                vinInput.classList.add('vin-decoding');
            }
        }
        
        // Auto-hide info messages
        if (type === 'info') {
            setTimeout(() => {
                clearVINStatus();
            }, 2000);
        }
    }
}

function showVINToast(type, message) {
    // Use global toast system if available
    if (typeof window.showToast === 'function') {
        window.showToast(message, type, { duration: 4000 });
    } else if (typeof showToast === 'function') {
        showToast(type, message);
    } else if (typeof Toastify !== 'undefined') {
        // Fallback to simple Toastify
        const colors = {
            success: '#28a745',
            error: '#dc3545', 
            warning: '#fd7e14',
            info: '#17a2b8'
        };
        
        Toastify({
            text: message,
            duration: 4000,
            gravity: 'top',
            position: 'right',
            backgroundColor: colors[type] || colors.info,
            close: true,
            stopOnFocus: true,
            className: 'vin-toast'
        }).showToast();
    } else {
        // Final fallback
        console.log(`VIN ${type.toUpperCase()}: ${message}`);
    }
}

function clearVINStatus() {
    const vinStatus = document.getElementById('vin-status');
    const vinInput = document.getElementById('vin');
    
    if (vinStatus) {
        vinStatus.textContent = '';
        vinStatus.className = 'vin-status';
    }
    
    if (vinInput) {
        vinInput.classList.remove('vin-decoding', 'vin-success', 'vin-error', 'vin-warning');
    }
}

function clearVehicleField() {
    const vehicleInput = document.getElementById('vehicle');
    
    if (vehicleInput) {
        // Only clear if the field was previously auto-filled by VIN decoder
        if (vehicleInput.classList.contains('vin-decoded')) {
            vehicleInput.value = '';
            vehicleInput.classList.remove('vin-decoded');
            vehicleInput.style.backgroundColor = '';
            vehicleInput.style.borderColor = '';
            
            console.log('SalesOrderIndex: Vehicle field cleared due to VIN modification');
        }
    }
}

function addIndexVINStyles() {
    // Check if styles already exist
    if (document.getElementById('index-vin-decoding-styles')) {
        return;
    }
    
    const style = document.createElement('style');
    style.id = 'index-vin-decoding-styles';
    style.textContent = `
        .vin-input-container {
            position: relative;
        }
        
        .vin-status {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.675rem;
            font-weight: 500;
            pointer-events: none;
            z-index: 10;
        }
        
        .vin-status-loading {
            color: #6c757d;
        }
        
        .vin-status-success {
            color: #198754;
        }
        
        .vin-status-error {
            color: #dc3545;
        }
        
        .vin-status-warning {
            color: #fd7e14;
        }
        
        .vin-status-info {
            color: #0dcaf0;
        }
        
        .vin-decoding {
            background-color: #f1f8ff !important;
            border-color: #007bff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15) !important;
            position: relative;
        }
        
        .vin-success {
            background-color: #d4edda !important;
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
        }
        
        .vin-error {
            background-color: #f8d7da !important;
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15) !important;
            animation: vinErrorPulse 0.5s ease-in-out;
        }
        
        .vin-warning {
            background-color: #fff3cd !important;
            border-color: #fd7e14 !important;
            box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.15) !important;
        }
        
        .vin-decoded {
            background-color: #d4edda !important;
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15) !important;
            animation: vinDecodeSuccess 0.5s ease-out;
        }
        
        @keyframes vinDecodeSuccess {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        @keyframes vinErrorPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        .vin-decoding::after {
            content: "";
            position: absolute;
            right: 35px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border: 2px solid #007bff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: vinSpin 1s linear infinite;
        }
        
        @keyframes vinSpin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }
        
        /* VIN Toast Styling */
        .vin-toast {
            font-family: inherit !important;
            font-size: 12.6px !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
        
        .vin-toast .toastify-content {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
        }
        
        .vin-toast .toastify-content::before {
            content: '⚠️';
            font-size: 14.4px;
            line-height: 1;
        }
        
        .vin-toast[style*="#dc3545"] .toastify-content::before {
            content: '❌';
        }
        
        .vin-toast[style*="#28a745"] .toastify-content::before {
            content: '✅';
        }
        
        .vin-toast[style*="#fd7e14"] .toastify-content::before {
            content: '⚠️';
        }
    `;
    
    document.head.appendChild(style);
}
</script>

<!-- Enhanced Table Indicators for popovers -->
<script src="<?= base_url('assets/js/enhanced-table-indicators.js') ?>"></script>

<!-- Quick Actions Modal Functions - CRITICAL FOR TABLE ACTION BUTTONS -->
<script src="<?= base_url('assets/js/sales-orders-tables.js') ?>?v=<?= time() ?>"></script>

<?= $this->endSection() ?>
