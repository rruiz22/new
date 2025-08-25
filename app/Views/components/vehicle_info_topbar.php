<?php
/**
 * Vehicle Information Top Bar Component
 * Reutilizable para todos los módulos (Sales Orders, Service Orders, CarWash, Recon)
 * 
 * Parámetros requeridos:
 * - $order: Array con datos de la orden
 * - $module_type: Tipo de módulo ('sales_orders', 'service_orders', 'car_wash_orders', 'recon_orders')
 * 
 * Parámetros opcionales:
 * - $show_qr_code: Mostrar QR code en el topbar (default: false)
 * - $qr_data: Datos del QR code si está disponible
 * - $custom_items: Array de items personalizados adicionales
 * - $responsive_breakpoints: Configuración de breakpoints responsive
 */

$order = $order ?? [];
$module_type = $module_type ?? 'sales_orders';
$show_qr_code = $show_qr_code ?? false;
$qr_data = $qr_data ?? null;
$custom_items = $custom_items ?? [];

// Configuración por módulo
$module_config = [
    'sales_orders' => [
        'contact_field' => 'salesperson',
        'module_prefix' => 'SAL',
        'primary_service' => 'service'
    ],
    'service_orders' => [
        'contact_field' => 'technician',
        'module_prefix' => 'SER',
        'primary_service' => 'repair_type'
    ],
    'car_wash_orders' => [
        'contact_field' => 'assigned_staff',
        'module_prefix' => 'CAR',
        'primary_service' => 'wash_type'
    ],
    'recon_orders' => [
        'contact_field' => 'inspector',
        'module_prefix' => 'REC',
        'primary_service' => 'inspection_type'
    ]
];

$config = $module_config[$module_type] ?? $module_config['sales_orders'];
$contact_field = $config['contact_field'];
$module_prefix = $config['module_prefix'];

// Generar ID único para el componente
$component_id = 'vehicle_topbar_' . $module_type . '_' . ($order['id'] ?? 0);

// Obtener información de contacto
$contact_name = $order[$contact_field . '_name'] ?? $order['contact_name'] ?? 'Not assigned';
$contact_phone = $order[$contact_field . '_phone'] ?? $order['contact_phone'] ?? '';
$contact_email = $order[$contact_field . '_email'] ?? $order['contact_email'] ?? '';

// Estados con sus configuraciones
$status_config = [
    'pending' => ['class' => 'bg-warning', 'text' => 'Pending', 'icon' => 'clock'],
    'processing' => ['class' => 'bg-info', 'text' => 'Processing', 'icon' => 'settings'],
    'in_progress' => ['class' => 'bg-primary', 'text' => 'In Progress', 'icon' => 'play-circle'],
    'completed' => ['class' => 'bg-success', 'text' => 'Completed', 'icon' => 'check-circle'],
    'cancelled' => ['class' => 'bg-danger', 'text' => 'Cancelled', 'icon' => 'x-circle'],
    'on_hold' => ['class' => 'bg-secondary', 'text' => 'On Hold', 'icon' => 'pause-circle']
];

$current_status = $order['status'] ?? 'pending';
$status_info = $status_config[$current_status] ?? $status_config['pending'];

// Calcular estado de tiempo
$time_status_info = calculateTimeStatus($order);
?>

<!-- Vehicle Information Top Bar Component -->
<div class="card order-top-bar" id="<?= $component_id ?>">
    <div class="card-body p-0">
        <div class="row g-0">
            
            <!-- 1. Schedule Information -->
            <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                <div class="top-bar-item">
                    <div class="top-bar-icon">
                        <i data-feather="calendar" class="text-warning"></i>
                    </div>
                    <div class="top-bar-content">
                        <div class="top-bar-label">Scheduled</div>
                        <div class="top-bar-value">
                            <?php if (isset($order['date']) && $order['date'] && isset($order['time']) && $order['time']): ?>
                                <?= date('M j, Y', strtotime($order['date'])) ?>
                            <?php else: ?>
                                Not scheduled
                            <?php endif; ?>
                        </div>
                        <div class="top-bar-sub">
                            <?php if (isset($order['time']) && $order['time']): ?>
                                <i data-feather="clock" class="icon-xs me-1"></i>
                                <?= date('g:i A', strtotime($order['time'])) ?>
                            <?php else: ?>
                                Time not set
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 2. Contact Information -->
            <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                <div class="top-bar-item">
                    <div class="top-bar-icon">
                        <i data-feather="user" class="text-success"></i>
                    </div>
                    <div class="top-bar-content">
                        <div class="top-bar-label">Contact</div>
                        <div class="top-bar-value"><?= $contact_name ?></div>
                        <div class="top-bar-sub">
                            <?php if ($contact_phone): ?>
                                <a href="tel:<?= $contact_phone ?>" class="text-decoration-none">
                                    <i data-feather="phone" class="icon-xs me-1"></i>
                                    <?= $contact_phone ?>
                                </a>
                            <?php elseif ($contact_email): ?>
                                <a href="mailto:<?= $contact_email ?>" class="text-decoration-none">
                                    <i data-feather="mail" class="icon-xs me-1"></i>
                                    <?= substr($contact_email, 0, 20) ?><?= strlen($contact_email) > 20 ? '...' : '' ?>
                                </a>
                            <?php else: ?>
                                No contact info
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 3. Vehicle Information -->
            <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                <div class="top-bar-item">
                    <div class="top-bar-icon">
                        <i data-feather="truck" class="text-primary"></i>
                    </div>
                    <div class="top-bar-content">
                        <div class="top-bar-label">Vehicle</div>
                        <div class="top-bar-value"><?= $order['vehicle'] ?? 'Not specified' ?></div>
                        <div class="top-bar-sub">
                            <?php if (isset($order['stock']) && $order['stock']): ?>
                                Stock: <?= $order['stock'] ?>
                            <?php elseif (isset($order['vin']) && $order['vin']): ?>
                                VIN: <?= substr($order['vin'], -6) ?>
                            <?php elseif (isset($order['license_plate']) && $order['license_plate']): ?>
                                Plate: <?= $order['license_plate'] ?>
                            <?php else: ?>
                                No vehicle info
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 4. Service Information -->
            <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                <div class="top-bar-item">
                    <div class="top-bar-icon">
                        <i data-feather="settings" class="text-info"></i>
                    </div>
                    <div class="top-bar-content">
                        <div class="top-bar-label">Service</div>
                        <div class="top-bar-value">
                            <?= $order['service_name'] ?? $order[$config['primary_service']] ?? 'Not specified' ?>
                        </div>
                        <div class="top-bar-sub">
                            <?php if (isset($order['service_price']) && $order['service_price']): ?>
                                $<?= number_format($order['service_price'], 2) ?>
                            <?php elseif (isset($order['estimated_price']) && $order['estimated_price']): ?>
                                Est. $<?= number_format($order['estimated_price'], 2) ?>
                            <?php else: ?>
                                Price not set
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 5. Status -->
            <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                <div class="top-bar-item">
                    <div class="top-bar-icon">
                        <i data-feather="<?= $status_info['icon'] ?>" class="text-info"></i>
                    </div>
                    <div class="top-bar-content">
                        <div class="top-bar-label">Status</div>
                        <div class="top-bar-value">
                            <span class="badge <?= $status_info['class'] ?> status-badge-large">
                                <?= $status_info['text'] ?>
                            </span>
                        </div>
                        <div class="top-bar-sub">
                            <?php if (isset($order['priority']) && $order['priority']): ?>
                                <i data-feather="flag" class="icon-xs me-1"></i>
                                <?= ucfirst($order['priority']) ?> priority
                            <?php else: ?>
                                Normal priority
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 6. Time Status / QR Code -->
            <?php if ($show_qr_code && $qr_data): ?>
            <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                <div class="top-bar-item">
                    <div class="top-bar-icon">
                        <i data-feather="smartphone" class="text-secondary"></i>
                    </div>
                    <div class="top-bar-content">
                        <div class="top-bar-label">QR Access</div>
                        <div class="top-bar-value">
                            <img src="<?= $qr_data['qr_url'] ?>" 
                                 alt="QR Code" 
                                 class="qr-topbar-image" 
                                 style="width: 60px; height: 60px; cursor: pointer;"
                                 onclick="showQRModal('qr_modal_<?= $component_id ?>')"
                                 title="QR Code - Click to enlarge">
                        </div>
                        <div class="top-bar-sub">
                            <small>Scan for mobile access</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- 6. Time Remaining Status -->
            <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                <div class="top-bar-item">
                    <div class="top-bar-icon">
                        <i data-feather="clock" class="text-secondary"></i>
                    </div>
                    <div class="top-bar-content">
                        <div class="top-bar-label">Time Status</div>
                        <div class="top-bar-value">
                            <span class="badge <?= $time_status_info['class'] ?>">
                                <?= $time_status_info['text'] ?>
                            </span>
                        </div>
                        <div class="top-bar-sub">
                            <?= $time_status_info['detail'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Custom Items -->
            <?php foreach ($custom_items as $item): ?>
            <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                <div class="top-bar-item">
                    <div class="top-bar-icon">
                        <i data-feather="<?= $item['icon'] ?>" class="<?= $item['icon_class'] ?? 'text-secondary' ?>"></i>
                    </div>
                    <div class="top-bar-content">
                        <div class="top-bar-label"><?= $item['label'] ?></div>
                        <div class="top-bar-value"><?= $item['value'] ?></div>
                        <div class="top-bar-sub"><?= $item['sub'] ?? '' ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Vehicle Information Top Bar Styles -->
<style>
.order-top-bar {
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.top-bar-item {
    position: relative;
    padding: 1rem 0.75rem;
    border-right: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    align-items: center;
    min-height: 120px;
}

.top-bar-item:last-child {
    border-right: none;
}

.top-bar-item:hover {
    background: rgba(59, 130, 246, 0.05);
}

.top-bar-icon {
    margin-right: 0.75rem;
    flex-shrink: 0;
    width: 32px;
    display: flex;
    justify-content: center;
}

.top-bar-content {
    flex: 1;
    min-width: 0; /* Allow text truncation */
}

.top-bar-label {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #64748b;
}

.top-bar-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    margin-top: 0.25rem;
    line-height: 1.3;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.top-bar-sub {
    font-size: 0.75rem;
    color: #64748b;
    line-height: 1.2;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.status-badge-large {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
}

.qr-topbar-image {
    transition: transform 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.qr-topbar-image:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Responsive adjustments */
@media (min-width: 1400px) {
    .top-bar-item {
        padding: 1.25rem 1rem;
    }
    
    .top-bar-value {
        font-size: 1rem;
    }
    
    .top-bar-sub {
        font-size: 0.875rem;
    }
}

@media (min-width: 1200px) and (max-width: 1399.98px) {
    .top-bar-item {
        padding: 1rem 0.8rem;
        min-height: 115px;
    }
    
    .top-bar-icon {
        margin-right: 0.6rem;
        width: 30px;
    }
}

@media (max-width: 1199.98px) {
    .top-bar-item {
        padding: 1rem 0.6rem;
        min-height: 110px;
    }
    
    .top-bar-icon {
        margin-right: 0.5rem;
        width: 28px;
    }
    
    .top-bar-value {
        font-size: 0.8rem;
    }
    
    .top-bar-sub {
        font-size: 0.7rem;
    }
}

@media (max-width: 991.98px) {
    .top-bar-item {
        padding: 0.875rem 0.5rem;
        min-height: 100px;
    }
    
    .top-bar-icon {
        width: 26px;
    }
    
    .top-bar-value {
        font-size: 0.75rem;
    }
    
    .top-bar-sub {
        font-size: 0.65rem;
    }
}

@media (max-width: 768px) {
    .top-bar-item {
        padding: 1rem;
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
        min-height: auto;
    }
    
    .top-bar-item:last-child {
        border-bottom: none;
    }
    
    .top-bar-icon {
        margin-right: 0.75rem;
        width: 32px;
    }
    
    .top-bar-value {
        font-size: 0.875rem;
    }
    
    .top-bar-sub {
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .top-bar-item {
        padding: 1rem 0.75rem;
    }
    
    .top-bar-icon {
        margin-right: 0.5rem;
        width: 28px;
    }
}
</style>

<!-- Vehicle Information Top Bar JavaScript -->
<script>
// Function to refresh top bar data
function refreshTopBarData(componentId, orderId, moduleType) {
    fetch(`/api/orders/${moduleType}/${orderId}/summary`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateTopBarContent(componentId, data.order);
        }
    })
    .catch(error => {
        console.error('Error refreshing top bar data:', error);
    });
}

// Function to update top bar content
function updateTopBarContent(componentId, orderData) {
    // Update specific elements based on new data
    console.log('Updating top bar content:', {componentId, orderData});
    // Implementation would update individual top bar items
}

// Function to show QR Modal (if QR component is loaded)
function showQRModal(modalId) {
    if (typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }
}
</script>

<?php
/**
 * Calculate time status information
 */
function calculateTimeStatus($order) {
    $time_status_info = [
        'class' => 'bg-secondary',
        'text' => 'Not scheduled',
        'detail' => 'Schedule not set'
    ];
    
    if (isset($order['date']) && $order['date'] && isset($order['time']) && $order['time']) {
        try {
            $scheduledDateTime = new DateTime($order['date'] . ' ' . $order['time']);
            $now = new DateTime();
            $diff = $scheduledDateTime->getTimestamp() - $now->getTimestamp();
            $hours = round($diff / 3600, 1);
            
            if ($diff > 86400) { // More than 24 hours
                $days = round($diff / 86400);
                $time_status_info = [
                    'class' => 'bg-info',
                    'text' => "In {$days} day(s)",
                    'detail' => $scheduledDateTime->format('M j, g:i A')
                ];
            } elseif ($diff > 3600) { // More than 1 hour
                $time_status_info = [
                    'class' => 'bg-primary',
                    'text' => "In {$hours}h",
                    'detail' => $scheduledDateTime->format('g:i A')
                ];
            } elseif ($diff > 0) { // Less than 1 hour, but future
                $minutes = round($diff / 60);
                $time_status_info = [
                    'class' => 'bg-warning',
                    'text' => "In {$minutes}min",
                    'detail' => 'Starting soon'
                ];
            } elseif ($diff > -3600) { // Started less than 1 hour ago
                $time_status_info = [
                    'class' => 'bg-success',
                    'text' => 'Active',
                    'detail' => 'In progress'
                ];
            } else { // Overdue
                $overdue_hours = abs(round($diff / 3600));
                $time_status_info = [
                    'class' => 'bg-danger',
                    'text' => "Overdue {$overdue_hours}h",
                    'detail' => 'Past scheduled time'
                ];
            }
        } catch (Exception $e) {
            // Keep default values if date parsing fails
        }
    }
    
    return $time_status_info;
}
?>