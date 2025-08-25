<?php
/**
 * Quick Actions Component
 * Reutilizable para todos los módulos (Sales Orders, Service Orders, CarWash, Recon)
 * 
 * Parámetros requeridos:
 * - $order: Array con datos de la orden
 * - $module_type: Tipo de módulo ('sales_orders', 'service_orders', 'car_wash_orders', 'recon_orders')
 * 
 * Parámetros opcionales:
 * - $show_status_update: Mostrar selector de estado (default: true)
 * - $show_sms_action: Mostrar acción SMS (default: true)
 * - $show_email_action: Mostrar acción Email (default: true)
 * - $show_print_action: Mostrar acción Print (default: true)
 * - $show_qr_action: Mostrar acción QR (default: true)
 * - $show_notification_action: Mostrar acción Notification (default: true)
 * - $available_statuses: Array de estados disponibles (opcional)
 */

$order = $order ?? [];
$module_type = $module_type ?? 'sales_orders';
$show_status_update = $show_status_update ?? true;
$show_sms_action = $show_sms_action ?? true;
$show_email_action = $show_email_action ?? true;
$show_print_action = $show_print_action ?? true;
$show_qr_action = $show_qr_action ?? true;
$show_notification_action = $show_notification_action ?? true;

// Estados por defecto si no se proporcionan
$default_statuses = [
    'pending' => ['icon' => '⏳', 'label' => 'Pending'],
    'processing' => ['icon' => '⚙️', 'label' => 'Processing'],
    'in_progress' => ['icon' => '🔄', 'label' => 'In Progress'],
    'completed' => ['icon' => '✅', 'label' => 'Completed'],
    'cancelled' => ['icon' => '❌', 'label' => 'Cancelled']
];

$available_statuses = $available_statuses ?? $default_statuses;

// Generar IDs únicos para el componente
$component_id = 'quick_actions_' . $module_type . '_' . ($order['id'] ?? 0);
$modal_id = $component_id . '_modal';

// Determinar el campo de contacto según el módulo
$contact_field_map = [
    'sales_orders' => 'salesperson',
    'service_orders' => 'technician',
    'car_wash_orders' => 'assigned_staff',
    'recon_orders' => 'inspector'
];

$contact_field = $contact_field_map[$module_type] ?? 'assigned_contact';
$contact_name = $order[$contact_field . '_name'] ?? $order['contact_name'] ?? 'Not assigned';
$contact_phone = $order[$contact_field . '_phone'] ?? $order['contact_phone'] ?? '';
$contact_email = $order[$contact_field . '_email'] ?? $order['contact_email'] ?? '';

// Verificar permisos del usuario
$user = auth()->user();
$is_staff_admin = $user && in_array($user->user_type, ['staff', 'admin']);
?>

<!-- Quick Actions Component -->
<div class="card quick-actions-card" id="<?= $component_id ?>">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i data-feather="zap" class="icon-sm me-2"></i>
            <?= lang('App.quick_actions') ?? 'Quick Actions' ?>
        </h5>
        <small class="text-muted">Actions for assigned contact: <?= $contact_name ?></small>
    </div>
    <div class="card-body">
        <div class="d-grid gap-3">
            
            <?php if ($show_status_update): ?>
            <!-- Update Status - Only for Staff and Admin Users -->
            <?php if ($is_staff_admin): ?>
            <div>
                <label class="form-label fw-medium"><?= lang('App.update_status') ?? 'Update Status' ?></label>
                <select class="form-select" id="<?= $component_id ?>_status_select" 
                        onchange="updateOrderStatus('<?= $component_id ?>', <?= $order['id'] ?? 0 ?>, '<?= $module_type ?>')">
                    <?php foreach ($available_statuses as $status => $config): ?>
                    <option value="<?= $status ?>" <?= ($order['status'] ?? '') == $status ? 'selected' : '' ?>>
                        <?= $config['icon'] ?> <?= lang('App.' . $status) ?? $config['label'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php else: ?>
            <!-- Status Display Only for Non-Staff/Admin Users -->
            <div>
                <label class="form-label fw-medium"><?= lang('App.current_status') ?? 'Current Status' ?></label>
                <div class="form-control-plaintext">
                    <?php
                    $current_status = $order['status'] ?? 'pending';
                    $status_config = $available_statuses[$current_status] ?? $available_statuses['pending'];
                    echo $status_config['icon'] . ' ' . (lang('App.' . $current_status) ?? $status_config['label']);
                    ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
            
            <!-- Quick Action Buttons -->
            <div class="d-grid gap-2">
                
                <?php if ($show_sms_action && $contact_phone): ?>
                <!-- SMS Action -->
                <button class="btn btn-outline-success" onclick="openSMSModal('<?= $component_id ?>', <?= $order['id'] ?>, '<?= $module_type ?>', '<?= $contact_phone ?>')">
                    <i data-feather="message-square" class="icon-sm me-2"></i>
                    Send SMS
                </button>
                <?php endif; ?>
                
                <?php if ($show_email_action && $contact_email): ?>
                <!-- Email Action -->
                <button class="btn btn-outline-warning" onclick="openEmailModal('<?= $component_id ?>', <?= $order['id'] ?>, '<?= $module_type ?>', '<?= $contact_email ?>')">
                    <i data-feather="mail" class="icon-sm me-2"></i>
                    Send Email
                </button>
                <?php endif; ?>
                
                <?php if ($show_print_action): ?>
                <!-- Print Action -->
                <button class="btn btn-outline-info" onclick="printOrder(<?= $order['id'] ?>, '<?= $module_type ?>')">
                    <i data-feather="printer" class="icon-sm me-2"></i>
                    Print Order
                </button>
                <?php endif; ?>
                
                <?php if ($show_qr_action): ?>
                <!-- QR Code Action -->
                <button class="btn btn-outline-primary" onclick="generateQRCodeAction(<?= $order['id'] ?>, '<?= $module_type ?>')">
                    <i data-feather="smartphone" class="icon-sm me-2"></i>
                    Generate QR Code
                </button>
                <?php endif; ?>
                
                <?php if ($show_notification_action): ?>
                <!-- Notification Action -->
                <button class="btn btn-outline-secondary" onclick="sendNotificationAction(<?= $order['id'] ?>, '<?= $module_type ?>')">
                    <i data-feather="bell" class="icon-sm me-2"></i>
                    Send Alert
                </button>
                <?php endif; ?>
                
                <!-- Additional Module-Specific Actions -->
                <div id="<?= $component_id ?>_additional_actions">
                    <!-- Placeholder for module-specific actions -->
                </div>
            </div>
            
            <!-- Contact Information -->
            <?php if ($contact_name && $contact_name !== 'Not assigned'): ?>
            <div class="mt-3 pt-3 border-top">
                <h6 class="text-muted mb-2">Assigned Contact</h6>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2">
                        <div class="avatar-title bg-primary text-white rounded-circle">
                            <?= substr($contact_name, 0, 1) ?>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-medium"><?= $contact_name ?></div>
                        <?php if ($contact_phone): ?>
                        <small class="text-muted"><?= $contact_phone ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions Mobile Modal -->
<div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" aria-labelledby="<?= $modal_id ?>_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content quick-actions-modal">
            <div class="modal-header quick-actions-modal-header">
                <div class="d-flex align-items-center">
                    <i data-feather="zap" class="icon-sm me-2 text-primary"></i>
                    <h5 class="modal-title" id="<?= $modal_id ?>_label">Quick Actions</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Modal content will be dynamically populated -->
                <div id="<?= $component_id ?>_modal_content">
                    <!-- Copy of quick actions for mobile -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Component JavaScript -->
<script>
// Update order status
function updateOrderStatus(componentId, orderId, moduleType) {
    const select = document.getElementById(componentId + '_status_select');
    const newStatus = select.value;
    
    // Show loading state
    select.disabled = true;
    showToast('Updating status...', 'info');
    
    // Make AJAX request
    fetch(`/api/orders/${moduleType}/${orderId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({status: newStatus})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Status updated successfully!', 'success');
            // Trigger status change event
            document.dispatchEvent(new CustomEvent('orderStatusChanged', {
                detail: {orderId, moduleType, newStatus}
            }));
        } else {
            showToast(data.message || 'Failed to update status', 'error');
            // Revert selection
            select.value = select.getAttribute('data-original-value') || 'pending';
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
        showToast('Error updating status', 'error');
        // Revert selection
        select.value = select.getAttribute('data-original-value') || 'pending';
    })
    .finally(() => {
        select.disabled = false;
    });
}

// Open SMS Modal
function openSMSModal(componentId, orderId, moduleType, phone) {
    // Implementation depends on SMS modal system
    console.log('Open SMS Modal:', {componentId, orderId, moduleType, phone});
    showToast('SMS feature coming soon!', 'info');
}

// Open Email Modal
function openEmailModal(componentId, orderId, moduleType, email) {
    // Implementation depends on Email modal system
    console.log('Open Email Modal:', {componentId, orderId, moduleType, email});
    showToast('Email feature coming soon!', 'info');
}

// Print Order
function printOrder(orderId, moduleType) {
    const printUrl = `/orders/${moduleType}/${orderId}/print`;
    window.open(printUrl, '_blank');
}

// Generate QR Code Action
function generateQRCodeAction(orderId, moduleType) {
    // Use the QR component's generate function
    if (typeof generateQRCode === 'function') {
        generateQRCode(orderId, moduleType);
    } else {
        showToast('QR Code generation not available', 'warning');
    }
}

// Send Notification Action
function sendNotificationAction(orderId, moduleType) {
    showToast('Sending notification...', 'info');
    
    fetch(`/api/orders/${moduleType}/${orderId}/notify`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Notification sent successfully!', 'success');
        } else {
            showToast(data.message || 'Failed to send notification', 'error');
        }
    })
    .catch(error => {
        console.error('Error sending notification:', error);
        showToast('Error sending notification', 'error');
    });
}

// Function to add custom actions to a specific module
function addCustomQuickAction(componentId, buttonHtml) {
    const container = document.getElementById(componentId + '_additional_actions');
    if (container) {
        container.innerHTML += buttonHtml;
    }
}

// Show toast function (placeholder - debe implementarse globalmente)
function showToast(message, type = 'info') {
    console.log(`Toast [${type}]: ${message}`);
    // Implementar sistema de toast notification
}
</script>

<!-- Quick Actions Component Styles -->
<style>
.quick-actions-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.quick-actions-card .btn {
    border-radius: 6px;
    font-weight: 500;
}

.quick-actions-modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.quick-actions-modal-header .btn-close {
    filter: invert(1);
}

.avatar {
    width: 32px;
    height: 32px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
}

.avatar-sm {
    width: 28px;
    height: 28px;
}

@media (max-width: 768px) {
    .quick-actions-card {
        margin-bottom: 1rem;
    }
}
</style>