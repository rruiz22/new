<?php if (isset($error)): ?>
    <div class="alert alert-danger" role="alert">
        <i data-feather="alert-circle" class="icon-sm me-2"></i>
        <?= $error ?>
    </div>
<?php elseif (empty($orders)): ?>
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
                            Deleted Orders (<?= count($orders) ?>)
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
                                <?php foreach ($orders as $order): ?>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
/* Ensure proper icon sizing for deleted orders table */
.deleted-orders-actions .icon-sm {
    width: 14px;
    height: 14px;
    stroke-width: 2;
}

.deleted-orders-actions .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.25rem;
}

.deleted-orders-actions [data-feather] {
    width: 14px;
    height: 14px;
    stroke-width: 2;
}

/* Fallback for when feather icons don't load */
.deleted-orders-actions .icon-fallback {
    font-size: 14px;
    line-height: 1;
    display: inline-block;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize feather icons with delay to ensure DOM is ready
    setTimeout(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        } else {
            console.warn('Feather icons library not available');
            // Fallback: add text to buttons if icons fail
            document.querySelectorAll('[data-feather="rotate-ccw"]').forEach(function(el) {
                if (!el.innerHTML.trim()) {
                    el.innerHTML = '↻';
                }
            });
            document.querySelectorAll('[data-feather="x"]').forEach(function(el) {
                if (!el.innerHTML.trim()) {
                    el.innerHTML = '✕';
                }
            });
            document.querySelectorAll('[data-feather="trash-2"]').forEach(function(el) {
                if (!el.innerHTML.trim()) {
                    el.innerHTML = '🗑';
                }
            });
        }
    }, 100);
    
    // Initialize tooltips
    setTimeout(function() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }, 200);
    
    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Force feather icons refresh after a longer delay
    setTimeout(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 500);
});

// Restore single order
function restoreOrder(orderId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Quieres restaurar esta orden?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Restaurando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`<?= base_url('sales_orders/restore/') ?>${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.close(); // Cerrar el loading
                    showNotification(data.message, 'success');
                    // Reload the page to refresh the list
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Error restoring order',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error restoring order:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Error restoring order',
                    icon: 'error'
                });
            });
        }
    });
}

// Permanently delete single order
function forceDeleteOrder(orderId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡Esta acción no se puede deshacer! ¿Quieres eliminar permanentemente esta orden?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar permanentemente',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(`<?= base_url('sales_orders/forceDelete/') ?>${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.close(); // Cerrar el loading
                    showNotification(data.message, 'success');
                    // Reload the page to refresh the list
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Error permanently deleting order',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error permanently deleting order:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Error permanently deleting order',
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
            // Implementation for bulk restore would go here
            showNotification('Funcionalidad de restauración masiva próximamente', 'info');
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
            // Implementation for bulk force delete would go here
            showNotification('Funcionalidad de eliminación masiva próximamente', 'info');
        }
    });
}

// Get selected order IDs
function getSelectedOrderIds() {
    const checkboxes = document.querySelectorAll('.order-checkbox:checked');
    return Array.from(checkboxes).map(checkbox => checkbox.value);
}

// Notification function
function showNotification(message, type = 'info') {
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
            style: {
                background: colors[type] || colors.info,
            }
        }).showToast();
    } else if (typeof window.showToast === 'function') {
        window.showToast(type, message);
    } else {
        // Fallback con alert estilizado
        alert(`${getTypeIcon(type)} ${message}`);
    }
}

// Helper function for fallback icons
function getTypeIcon(type) {
    switch(type) {
        case 'success': return '✅';
        case 'error': return '❌';
        case 'warning': return '⚠️';
        case 'info': return 'ℹ️';
        default: return '📢';
    }
}

// Global function to initialize icons when tab is shown
window.initializeDeletedOrdersIcons = function() {
    
    // First, try to initialize Feather icons
    setTimeout(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
            
            // Check if icons were actually rendered
            setTimeout(function() {
                const emptyIcons = document.querySelectorAll('.deleted-orders-actions [data-feather]');
                let hasEmptyIcons = false;
                
                emptyIcons.forEach(function(el) {
                    if (!el.innerHTML.trim() || el.innerHTML.trim() === '') {
                        hasEmptyIcons = true;
                    }
                });
                
                if (hasEmptyIcons) {
                    console.warn('Some icons are empty, applying fallback...');
                    applyFallbackIcons();
                }
            }, 200);
            
        } else {
            console.warn('⚠️ Feather not available, using fallback icons');
            applyFallbackIcons();
        }
        
        // Re-initialize tooltips
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }, 100);
};

// Function to apply fallback icons
function applyFallbackIcons() {
    document.querySelectorAll('[data-feather="rotate-ccw"]').forEach(function(el) {
        el.innerHTML = '↻';
        el.style.fontSize = '14px';
        el.style.fontWeight = 'bold';
        el.classList.add('icon-fallback');
    });
    
    document.querySelectorAll('[data-feather="x"]').forEach(function(el) {
        el.innerHTML = '✕';
        el.style.fontSize = '14px';
        el.style.fontWeight = 'bold';
        el.classList.add('icon-fallback');
    });
    
    document.querySelectorAll('[data-feather="trash-2"]').forEach(function(el) {
        el.innerHTML = '🗑';
        el.style.fontSize = '14px';
        el.classList.add('icon-fallback');
    });
    
    document.querySelectorAll('[data-feather="alert-circle"]').forEach(function(el) {
        el.innerHTML = '⚠';
        el.style.fontSize = '14px';
        el.classList.add('icon-fallback');
    });
    
}
</script>
