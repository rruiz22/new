<?php
/**
 * Order View Layout Component
 * Layout completo reutilizable para todas las vistas de órdenes del sistema
 * 
 * Parámetros requeridos:
 * - $order: Array con datos de la orden
 * - $module_type: Tipo de módulo ('sales_orders', 'service_orders', 'car_wash_orders', 'recon_orders')
 * - $title: Título de la página
 * 
 * Parámetros opcionales:
 * - $qr_data: Datos del QR code (opcional)
 * - $show_qr_in_topbar: Mostrar QR en topbar en lugar de sidebar (default: false)
 * - $show_order_details: Mostrar card de detalles de orden (default: true)
 * - $show_schedule_info: Mostrar información de horario (default: true)
 * - $additional_sidebar_content: Contenido adicional para sidebar (HTML string)
 * - $additional_main_content: Contenido adicional para columna principal (HTML string)
 * - $custom_breadcrumbs: Array de breadcrumbs personalizados
 * - $custom_styles: Estilos CSS adicionales
 * - $custom_scripts: JavaScript adicional
 */

// Parámetros requeridos
$order = $order ?? [];
$module_type = $module_type ?? 'sales_orders';
$title = $title ?? 'Order View';

// Parámetros opcionales
$qr_data = $qr_data ?? null;
$show_qr_in_topbar = $show_qr_in_topbar ?? false;
$show_order_details = $show_order_details ?? true;
$show_schedule_info = $show_schedule_info ?? true;
$additional_sidebar_content = $additional_sidebar_content ?? '';
$additional_main_content = $additional_main_content ?? '';
$custom_breadcrumbs = $custom_breadcrumbs ?? [];
$custom_styles = $custom_styles ?? '';
$custom_scripts = $custom_scripts ?? '';

// Configuración por módulo
$module_config = [
    'sales_orders' => [
        'name' => 'Sales Order',
        'name_plural' => 'Sales Orders',
        'prefix' => 'SAL',
        'base_url' => 'sales_orders',
        'lang_key' => 'App.sales_orders',
        'icon' => 'shopping-bag',
        'color' => 'primary'
    ],
    'service_orders' => [
        'name' => 'Service Order',
        'name_plural' => 'Service Orders', 
        'prefix' => 'SER',
        'base_url' => 'service_orders',
        'lang_key' => 'App.service_orders',
        'icon' => 'tool',
        'color' => 'info'
    ],
    'car_wash_orders' => [
        'name' => 'Car Wash Order',
        'name_plural' => 'Car Wash Orders',
        'prefix' => 'CAR', 
        'base_url' => 'car_wash_orders',
        'lang_key' => 'App.car_wash_orders',
        'icon' => 'droplet',
        'color' => 'success'
    ],
    'recon_orders' => [
        'name' => 'Recon Order',
        'name_plural' => 'Recon Orders',
        'prefix' => 'REC',
        'base_url' => 'recon_orders', 
        'lang_key' => 'App.recon_orders',
        'icon' => 'search',
        'color' => 'warning'
    ]
];

$config = $module_config[$module_type] ?? $module_config['sales_orders'];
$order_number = $config['prefix'] . '-' . str_pad($order['id'] ?? 0, 5, '0', STR_PAD_LEFT);

// Generar ID único para el layout
$layout_id = 'order_layout_' . $module_type . '_' . ($order['id'] ?? 0);
?>

<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<?php if (!empty($custom_breadcrumbs)): ?>
    <?php foreach ($custom_breadcrumbs as $breadcrumb): ?>
        <li class="breadcrumb-item">
            <?php if (isset($breadcrumb['url'])): ?>
                <a href="<?= $breadcrumb['url'] ?>"><?= $breadcrumb['text'] ?></a>
            <?php else: ?>
                <?= $breadcrumb['text'] ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <li class="breadcrumb-item">
        <a href="<?= base_url($config['base_url']) ?>">
            <?= lang($config['lang_key']) ?? $config['name_plural'] ?>
        </a>
    </li>
    <li class="breadcrumb-item active"><?= $title ?></li>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Order View Layout Styles */
.order-view-container {
    min-height: calc(100vh - 200px);
}

.main-content-column {
    padding-right: 15px;
}

.sidebar-column {
    padding-left: 15px;
}

/* Order Details Card */
.order-details {
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.order-details .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
}

.order-details .card-subtitle {
    color: rgba(255,255,255,0.8);
}

/* Schedule & Vehicle Information Row */
.order-schedule-vehicle {
    margin-bottom: 2rem;
}

.order-schedule-vehicle .card {
    height: 100%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* Responsive adjustments */
@media (max-width: 1199.98px) {
    .main-content-column {
        padding-right: 15px;
    }
    
    .sidebar-column {
        padding-left: 15px;
        margin-top: 2rem;
    }
}

@media (max-width: 768px) {
    .main-content-column,
    .sidebar-column {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .sidebar-column {
        margin-top: 1.5rem;
    }
}

/* Module specific colors */
.module-sales .accent-color { color: var(--bs-primary); }
.module-service .accent-color { color: var(--bs-info); }
.module-carwash .accent-color { color: var(--bs-success); }
.module-recon .accent-color { color: var(--bs-warning); }

/* Custom styles placeholder */
<?= $custom_styles ?>
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="order-view-container module-<?= str_replace('_orders', '', $module_type) ?>" id="<?= $layout_id ?>">
    <?php if (isset($order) && $order): ?>
    
    <!-- Vehicle Information Top Bar -->
    <?= $this->include('components/vehicle_info_topbar', [
        'order' => $order,
        'module_type' => $module_type,
        'show_qr_code' => $show_qr_in_topbar,
        'qr_data' => $qr_data
    ]) ?>
    
    <!-- Main Content Row -->
    <div class="row">
        <!-- Main Content Column -->
        <div class="col-xl-8 order-xl-1 order-2 main-content-column">
            
            <?php if ($show_order_details): ?>
            <!-- Order Details Card -->
            <div class="card order-details">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">
                                <i data-feather="<?= $config['icon'] ?>" class="icon-sm me-2"></i>
                                <?= lang('App.order_details') ?? 'Order Details' ?> - Order <?= $order_number ?>
                            </h5>
                            <p class="card-subtitle mb-0">Complete order information and status</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-light text-dark">
                                ID: <?= $order['id'] ?? 'N/A' ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= lang('App.created_at') ?? 'Created' ?></label>
                                <p class="form-control-plaintext">
                                    <?= isset($order['created_at']) ? date('M j, Y g:i A', strtotime($order['created_at'])) : 'N/A' ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= lang('App.last_updated') ?? 'Last Updated' ?></label>
                                <p class="form-control-plaintext">
                                    <?= isset($order['updated_at']) ? date('M j, Y g:i A', strtotime($order['updated_at'])) : 'N/A' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($show_schedule_info && (isset($order['date']) || isset($order['time']))): ?>
            <!-- Schedule & Vehicle Information Row -->
            <div class="row order-schedule-vehicle">
                <!-- Schedule Information -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="calendar" class="icon-sm me-2 text-warning"></i>
                                <?= lang('App.schedule_information') ?? 'Schedule Information' ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= lang('App.date') ?? 'Date' ?></label>
                                <p class="form-control-plaintext">
                                    <?= isset($order['date']) && $order['date'] ? date('l, F j, Y', strtotime($order['date'])) : 'Not scheduled' ?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= lang('App.time') ?? 'Time' ?></label>
                                <p class="form-control-plaintext">
                                    <?= isset($order['time']) && $order['time'] ? date('g:i A', strtotime($order['time'])) : 'Not scheduled' ?>
                                </p>
                            </div>
                            <?php if (isset($order['notes']) && $order['notes']): ?>
                            <div class="mb-0">
                                <label class="form-label fw-medium"><?= lang('App.notes') ?? 'Notes' ?></label>
                                <p class="form-control-plaintext"><?= $order['notes'] ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Vehicle Information -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i data-feather="truck" class="icon-sm me-2 text-primary"></i>
                                <?= lang('App.vehicle_information') ?? 'Vehicle Information' ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= lang('App.vehicle') ?? 'Vehicle' ?></label>
                                <p class="form-control-plaintext"><?= $order['vehicle'] ?? 'Not specified' ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium"><?= lang('App.stock_number') ?? 'Stock #' ?></label>
                                <p class="form-control-plaintext"><?= $order['stock'] ?? 'Not specified' ?></p>
                            </div>
                            <?php if (isset($order['vin']) && $order['vin']): ?>
                            <div class="mb-0">
                                <label class="form-label fw-medium">VIN</label>
                                <p class="form-control-plaintext"><?= $order['vin'] ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Comments Section -->
            <?= $this->include('components/comments', [
                'order_id' => $order['id'],
                'module_type' => $module_type,
                'max_height' => 400,
                'allow_attachments' => true,
                'allow_mentions' => true
            ]) ?>
            
            <!-- Internal Communication Section -->
            <?= $this->include('components/internal_notes', [
                'order_id' => $order['id'],
                'module_type' => $module_type,
                'show_mentions_tab' => true,
                'show_team_activity_tab' => true
            ]) ?>
            
            <!-- Additional Main Content -->
            <?php if ($additional_main_content): ?>
            <div class="additional-main-content">
                <?= $additional_main_content ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar Column -->
        <div class="col-xl-4 order-xl-2 order-1 sidebar-column">
            
            <!-- QR Code Card (if not shown in topbar) -->
            <?php if (!$show_qr_in_topbar): ?>
            <?= $this->include('components/qr_code', [
                'order' => $order,
                'qr_data' => $qr_data,
                'module_prefix' => $config['prefix'],
                'show_sidebar' => true,
                'show_topbar' => false
            ]) ?>
            <?php endif; ?>
            
            <!-- Quick Actions Card -->
            <?= $this->include('components/quick_actions', [
                'order' => $order,
                'module_type' => $module_type,
                'show_status_update' => true,
                'show_sms_action' => true,
                'show_email_action' => true,
                'show_print_action' => true,
                'show_qr_action' => true,
                'show_notification_action' => true
            ]) ?>
            
            <!-- Additional Sidebar Content -->
            <?php if ($additional_sidebar_content): ?>
            <div class="additional-sidebar-content">
                <?= $additional_sidebar_content ?>
            </div>
            <?php endif; ?>
            
            <!-- Order Statistics Card -->
            <div class="card order-stats">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="bar-chart-2" class="icon-sm me-2"></i>
                        <?= lang('App.order_statistics') ?? 'Order Statistics' ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-muted mb-1">Comments</h6>
                                <h4 class="mb-0 text-<?= $config['color'] ?>" id="<?= $layout_id ?>_comments_count">0</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">Internal Notes</h6>
                            <h4 class="mb-0 text-<?= $config['color'] ?>" id="<?= $layout_id ?>_notes_count">0</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <small class="text-muted">
                                Last activity: <span id="<?= $layout_id ?>_last_activity">Loading...</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Order Not Found -->
    <div class="card">
        <div class="card-body text-center py-5">
            <i data-feather="alert-triangle" class="icon-xl text-warning mb-3"></i>
            <h4 class="text-muted"><?= lang('App.order_not_found') ?? 'Order Not Found' ?></h4>
            <p class="text-muted">The requested order could not be found or you don't have permission to view it.</p>
            <a href="<?= base_url($config['base_url']) ?>" class="btn btn-primary">
                <i data-feather="arrow-left" class="icon-sm me-1"></i>
                <?= lang('App.back_to_orders') ?? 'Back to Orders' ?>
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const layoutId = '<?= $layout_id ?>';
    const orderId = <?= $order['id'] ?? 0 ?>;
    const moduleType = '<?= $module_type ?>';
    
    // Initialize order view layout
    initOrderViewLayout(layoutId, orderId, moduleType);
    
    // Load order statistics
    loadOrderStatistics(layoutId, orderId, moduleType);
    
    // Setup real-time updates (if available)
    if (typeof initRealTimeUpdates === 'function') {
        initRealTimeUpdates(orderId, moduleType, layoutId);
    }
});

function initOrderViewLayout(layoutId, orderId, moduleType) {
    console.log('Initializing order view layout:', {layoutId, orderId, moduleType});
    
    // Listen for component updates
    document.addEventListener('orderStatusChanged', function(e) {
        if (e.detail.orderId === orderId) {
            refreshOrderData(layoutId, orderId, moduleType);
        }
    });
    
    document.addEventListener('commentAdded', function(e) {
        if (e.detail.orderId === orderId) {
            updateCommentCount(layoutId);
        }
    });
    
    document.addEventListener('noteAdded', function(e) {
        if (e.detail.orderId === orderId) {
            updateNoteCount(layoutId);
        }
    });
}

function loadOrderStatistics(layoutId, orderId, moduleType) {
    fetch(`/api/orders/${moduleType}/${orderId}/statistics`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateStatistics(layoutId, data.statistics);
        }
    })
    .catch(error => {
        console.error('Error loading statistics:', error);
    });
}

function updateStatistics(layoutId, stats) {
    const commentsCount = document.getElementById(layoutId + '_comments_count');
    const notesCount = document.getElementById(layoutId + '_notes_count');
    const lastActivity = document.getElementById(layoutId + '_last_activity');
    
    if (commentsCount) commentsCount.textContent = stats.comments_count || 0;
    if (notesCount) notesCount.textContent = stats.notes_count || 0;
    if (lastActivity) lastActivity.textContent = stats.last_activity || 'No activity';
}

function updateCommentCount(layoutId) {
    const commentsCount = document.getElementById(layoutId + '_comments_count');
    if (commentsCount) {
        const current = parseInt(commentsCount.textContent) || 0;
        commentsCount.textContent = current + 1;
    }
}

function updateNoteCount(layoutId) {
    const notesCount = document.getElementById(layoutId + '_notes_count');
    if (notesCount) {
        const current = parseInt(notesCount.textContent) || 0;
        notesCount.textContent = current + 1;
    }
}

function refreshOrderData(layoutId, orderId, moduleType) {
    // Refresh top bar data
    if (typeof refreshTopBarData === 'function') {
        refreshTopBarData(layoutId + '_topbar', orderId, moduleType);
    }
    
    // Refresh statistics
    loadOrderStatistics(layoutId, orderId, moduleType);
}

// Print entire order
function printOrder() {
    window.print();
}

// Export order data
function exportOrder(format = 'pdf') {
    const orderId = <?= $order['id'] ?? 0 ?>;
    const moduleType = '<?= $module_type ?>';
    
    window.open(`/api/orders/${moduleType}/${orderId}/export?format=${format}`, '_blank');
}

<?= $custom_scripts ?>
</script>
<?= $this->endSection() ?>