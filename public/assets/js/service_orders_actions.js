/**
 * SERVICE ORDERS - FUNCIONES DE BOTONES DE ACCIÓN
 * Funcionalidad completa para los botones View, Edit, Delete en todas las tablas
 */

// Variables globales para service orders actions
var baseUrl = window.baseUrl || '';
var csrfTokenName = window.csrfTokenName || 'csrf_test_name';  
var csrfHash = window.csrfHash || '';

/**
 * Inicializar variables globales - DISPONIBLE GLOBALMENTE
 */
window.initServiceOrdersActions = function(config) {
    baseUrl = config.baseUrl || '';
    csrfTokenName = config.csrfTokenName || 'csrf_test_name';
    csrfHash = config.csrfHash || '';
    

};

// Verificar si jQuery está disponible antes de continuar
if (typeof $ === 'undefined') {

    // Reintentar después de un momento
    setTimeout(function() {
        if (typeof $ !== 'undefined') {
            // jQuery ahora está disponible, continuar con la inicialización
            initServiceOrdersActionsWhenReady();
        } else {
            // Volver a intentar
            setTimeout(arguments.callee, 100);
        }
    }, 100);
} else {
    // jQuery está disponible, continuar
    initServiceOrdersActionsWhenReady();
}

// =====================================================================
// FUNCIONES PRINCIPALES DE ACCIONES - GLOBALMENTE DISPONIBLES
// =====================================================================

/**
 * Ver detalles de una orden de servicio
 */
window.viewServiceOrder = function(orderId) {
    if (!orderId) {
        showToast('error', 'Invalid order ID');
        return;
    }
    

    
    // Redirigir a la página de vista
    window.location.href = `${baseUrl}service_orders/view/${orderId}`;
}

/**
 * Editar una orden de servicio
 */
window.editServiceOrder = function(orderId) {
    if (!orderId) {
        showToast('error', 'Invalid order ID');
        return;
    }
    

    
    // Cargar el modal de edición
    $.ajax({
        url: `${baseUrl}service_orders/edit/${orderId}`,
        type: 'GET',
        beforeSend: function() {
            // Mostrar loading
            showToast('info', 'Loading order data...', false);
        },
        success: function(response) {
            // Cerrar loading toast
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            // Crear el modal con el contenido de edición
            showEditModal(response, orderId);
        },
        error: function(xhr, status, error) {
            console.error('❌ Error loading edit form:', error);
            showToast('error', 'Failed to load order data. Please try again.');
        }
    });
}

/**
 * Mostrar el modal de edición con los datos cargados
 */
function showEditModal(htmlContent, orderId) {
    // Crear el modal dinámicamente
    const modalHtml = `
        <div class="modal fade" id="editServiceOrderModal" tabindex="-1" role="dialog" aria-labelledby="editServiceOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    ${htmlContent}
                </div>
            </div>
        </div>
    `;
    
    // Remover modal existente si existe
    $('#editServiceOrderModal').remove();
    
    // Agregar el nuevo modal al body
    $('body').append(modalHtml);
    
    // Mostrar el modal
    $('#editServiceOrderModal').modal('show');
    
    // Configurar el evento de cierre
    $('#editServiceOrderModal').on('hidden.bs.modal', function() {
        $(this).remove();
    });
    
    // Inicializar componentes del modal si es necesario
    initEditModalComponents(orderId);
}

/**
 * Inicializar componentes específicos del modal de edición
 */
function initEditModalComponents(orderId) {
    // Configurar Select2 si está disponible
    if (typeof $.fn.select2 !== 'undefined') {
        $('#editServiceOrderModal .select2').select2({
            dropdownParent: $('#editServiceOrderModal')
        });
    }
    
    // Configurar eventos específicos del formulario de edición
    $('#editServiceOrderModal form').off('submit').on('submit', function(e) {
        e.preventDefault();
        
        // Check if we're on the view page and use custom handler
        if (window.isServiceOrderViewPage && typeof window.handleServiceOrderUpdate === 'function') {
            console.log('🔧 Using custom view page handler');
            window.handleServiceOrderUpdate(this);
        } else {
            console.log('🔧 Using global handler');
            updateServiceOrder(orderId, $(this));
        }
    });
}

/**
 * Eliminar una orden de servicio (soft delete)
 */
window.deleteServiceOrder = function(orderId) {
    if (!orderId) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    // Confirmación con SweetAlert2
    Swal.fire({
        title: 'Delete Service Order',
        text: 'Are you sure you want to delete this service order? This action can be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            performDeleteOrder(orderId);
        }
    });
}

/**
 * Restaurar una orden eliminada
 */
window.restoreServiceOrder = function(orderId) {
    if (!orderId) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    Swal.fire({
        title: 'Restore Service Order',
        text: 'Are you sure you want to restore this service order?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, restore it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            performRestoreOrder(orderId);
        }
    });
}

/**
 * Eliminar permanentemente una orden
 */
window.permanentDeleteServiceOrder = function(orderId) {
    if (!orderId) {
        showToast('error', 'Invalid order ID');
        return;
    }
    
    Swal.fire({
        title: 'Permanent Delete',
        text: 'Are you sure you want to permanently delete this service order? This action CANNOT be undone!',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete permanently!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        input: 'text',
        inputPlaceholder: 'Type "DELETE" to confirm',
        inputValidator: (value) => {
            if (value !== 'DELETE') {
                return 'You must type "DELETE" to confirm!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            performPermanentDelete(orderId);
        }
    });
}

// =====================================================================
// FUNCIONES DE EJECUCIÓN DE ACCIONES
// =====================================================================

/**
 * Ejecutar eliminación suave
 */
function performDeleteOrder(orderId) {
    const loadingToast = showToast('info', 'Deleting service order...', false);
    
    $.ajax({
        url: `${baseUrl}service_orders/delete`,
        type: 'POST',
        data: {
            [csrfTokenName]: csrfHash,
            id: orderId
        },
        beforeSend: function() {
            // Mostrar loading en todos los botones de esta fila
            $(`.delete-service-order-btn[data-id="${orderId}"]`).prop('disabled', true);
        },
        success: function(response) {
            loadingToast.close();
            
            if (response.success) {
                showToast('success', response.message || 'Service order deleted successfully');
                
                // Recargar todas las tablas
                refreshAllServiceOrdersTables();
                
                // Actualizar estadísticas si existe la función
                if (typeof updateDashboardStats === 'function') {
                    updateDashboardStats();
                }
            } else {
                showToast('error', response.message || 'Failed to delete service order');
            }
        },
        error: function(xhr, status, error) {
            loadingToast.close();
            console.error('❌ Error deleting service order:', error);
            showToast('error', 'Failed to delete service order. Please try again.');
        },
        complete: function() {
            $(`.delete-service-order-btn[data-id="${orderId}"]`).prop('disabled', false);
        }
    });
}

/**
 * Ejecutar restauración
 */
function performRestoreOrder(orderId) {
    const loadingToast = showToast('info', 'Restoring service order...', false);
    
    $.ajax({
        url: `${baseUrl}service_orders/restore`,
        type: 'POST',
        data: {
            [csrfTokenName]: csrfHash,
            id: orderId
        },
        success: function(response) {
            loadingToast.close();
            
            if (response.success) {
                showToast('success', response.message || 'Service order restored successfully');
                refreshAllServiceOrdersTables();
            } else {
                showToast('error', response.message || 'Failed to restore service order');
            }
        },
        error: function(xhr, status, error) {
            loadingToast.close();
            console.error('❌ Error restoring service order:', error);
            showToast('error', 'Failed to restore service order. Please try again.');
        }
    });
}

/**
 * Ejecutar eliminación permanente
 */
function performPermanentDelete(orderId) {
    const loadingToast = showToast('info', 'Permanently deleting service order...', false);
    
    $.ajax({
        url: `${baseUrl}service_orders/permanent-delete`,
        type: 'POST',
        data: {
            [csrfTokenName]: csrfHash,
            id: orderId
        },
        success: function(response) {
            loadingToast.close();
            
            if (response.success) {
                showToast('success', response.message || 'Service order permanently deleted');
                refreshAllServiceOrdersTables();
            } else {
                showToast('error', response.message || 'Failed to permanently delete service order');
            }
        },
        error: function(xhr, status, error) {
            loadingToast.close();
            console.error('❌ Error permanently deleting service order:', error);
            showToast('error', 'Failed to permanently delete service order. Please try again.');
        }
    });
}

// =====================================================================
// FUNCIONES DE ACTUALIZACIÓN RÁPIDA DE ESTADO
// =====================================================================

/**
 * Actualizar una orden de servicio
 */
function updateServiceOrder(orderId, form) {
    const formData = new FormData(form[0]);
    formData.append(csrfTokenName, csrfHash);
    formData.append('id', orderId);
    
    const loadingToast = showToast('info', 'Updating service order...', false);
    
    $.ajax({
        url: `${baseUrl}service_orders/update/${orderId}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            loadingToast.close();
            
            if (response.success) {
                showToast('success', response.message || 'Service order updated successfully');
                
                // Cerrar el modal
                $('#editServiceOrderModal').modal('hide');
                
                // Recargar todas las tablas
                refreshAllServiceOrdersTables();
                
                // Actualizar estadísticas si existe la función
                if (typeof updateDashboardStats === 'function') {
                    updateDashboardStats();
                }
            } else {
                showToast('error', response.message || 'Failed to update service order');
                
                // Mostrar errores de validación si existen
                if (response.errors) {
                    let errorText = 'Validation errors:\n';
                    for (let field in response.errors) {
                        errorText += `• ${response.errors[field]}\n`;
                    }
                    showToast('error', errorText);
                }
            }
        },
        error: function(xhr, status, error) {
            loadingToast.close();
            console.error('❌ Error updating service order:', error);
            showToast('error', 'Failed to update service order. Please try again.');
        }
    });
}

/**
 * Cambio rápido de estado desde la tabla
 */
function quickStatusChange(orderId, newStatus) {
    if (!orderId || !newStatus) {
        showToast('error', 'Invalid parameters');
        return;
    }
    
    const loadingToast = showToast('info', 'Updating status...', false);
    
    $.ajax({
        url: `${baseUrl}service_orders/update-status`,
        type: 'POST',
        data: {
            [csrfTokenName]: csrfHash,
            id: orderId,
            status: newStatus
        },
        success: function(response) {
            loadingToast.close();
            
            if (response.success) {
                showToast('success', `Status updated to: ${newStatus}`);
                refreshAllServiceOrdersTables();
            } else {
                showToast('error', response.message || 'Failed to update status');
            }
        },
        error: function(xhr, status, error) {
            loadingToast.close();
            console.error('❌ Error updating status:', error);
            showToast('error', 'Failed to update status. Please try again.');
        }
    });
}

// =====================================================================
// FUNCIONES DE GENERACIÓN DE BOTONES HTML
// =====================================================================

/**
 * Generar botones de acción para tablas activas
 */
window.generateActiveOrderActions = function(orderId, orderData = {}) {
    return `
        <div class="service-order-action-buttons">
            <a href="javascript:void(0)" 
               onclick="viewServiceOrder(${orderId})" 
               class="service-btn service-btn-view" 
               title="View Service Order"
               data-bs-toggle="tooltip">
                <i class="ri-eye-fill"></i>
            </a>
            <a href="javascript:void(0)" 
               onclick="editServiceOrder(${orderId})" 
               class="service-btn service-btn-edit" 
               title="Edit Service Order"
               data-bs-toggle="tooltip">
                <i class="ri-edit-2-fill"></i>
            </a>
            <a href="javascript:void(0)" 
               onclick="deleteServiceOrder(${orderId})" 
               class="service-btn service-btn-delete delete-service-order-btn" 
               data-id="${orderId}"
               title="Delete Service Order"
               data-bs-toggle="tooltip">
                <i class="ri-delete-bin-fill"></i>
            </a>
        </div>
    `;
};

/**
 * Generar botones de acción para tablas de órdenes eliminadas
 */
window.generateDeletedOrderActions = function(orderId) {
    return `
        <div class="service-order-action-buttons">
            <a href="javascript:void(0)" 
               onclick="restoreServiceOrder(${orderId})" 
               class="service-btn service-btn-edit" 
               title="Restore Service Order"
               data-bs-toggle="tooltip">
                <i class="ri-restart-line"></i>
            </a>
            <a href="javascript:void(0)" 
               onclick="viewServiceOrder(${orderId})" 
               class="service-btn service-btn-view" 
               title="View Service Order"
               data-bs-toggle="tooltip">
                <i class="ri-eye-fill"></i>
            </a>
            <a href="javascript:void(0)" 
               onclick="permanentDeleteServiceOrder(${orderId})" 
               class="service-btn service-btn-delete" 
               title="Permanently Delete"
               data-bs-toggle="tooltip">
                <i class="ri-delete-bin-2-fill"></i>
            </a>
        </div>
    `;
};

// =====================================================================
// FUNCIONES AUXILIARES
// =====================================================================

/**
 * Refrescar todas las tablas de service orders
 */
function refreshAllServiceOrdersTables() {
    const tables = [
        'allServiceOrdersTable',
        'todayServiceOrdersTable', 
        'tomorrowServiceOrdersTable',
        'pendingServiceOrdersTable',
        'weekServiceOrdersTable',
        'deletedOrdersTable'
    ];
    
    tables.forEach(tableName => {
        if (window[tableName] && typeof window[tableName].ajax === 'object') {
            try {
                window[tableName].ajax.reload(null, false);

            } catch (error) {
                console.warn(`⚠️ Could not refresh ${tableName}:`, error);
            }
        }
    });
}

/**
 * Función de toast mejorada
 */
function showToast(type, message, autoClose = true) {
    if (typeof Swal !== 'undefined') {
        const config = {
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: autoClose ? 3000 : undefined,
            timerProgressBar: autoClose,
            icon: type,
            title: message,
            showClass: {
                popup: 'animate__animated animate__fadeInRight'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutRight'
            }
        };
        
        return Swal.fire(config);
    } else {
        // Fallback para sistemas sin SweetAlert2
        console.log(`${type.toUpperCase()}: ${message}`);
        return { close: () => {} };
    }
}

// =====================================================================
// INICIALIZACIÓN Y VARIABLES GLOBALES
// =====================================================================

// La función initServiceOrdersActions ya está definida al inicio del archivo

/**
 * Inicialización automática cuando el documento esté listo
 */
function initServiceOrdersWhenReady() {
    if (typeof $ !== 'undefined') {
        $(document).ready(function() {
            // Configurar tooltips
            $('body').tooltip({
                selector: '[data-bs-toggle="tooltip"]',
                trigger: 'hover'
            });
            

        });
    } else {
        // Esperar a que jQuery esté disponible
        setTimeout(initServiceOrdersWhenReady, 100);
    }
}

// Función para llamar desde initServiceOrdersActionsWhenReady
function initServiceOrdersActionsWhenReady() {
    // Evitar redeclaración si el archivo ya fue cargado
    if (typeof window.serviceOrdersActionsLoaded !== 'undefined') {

        return;
    }
    window.serviceOrdersActionsLoaded = true;
    
    // Inicializar cuando sea posible
    initServiceOrdersWhenReady();
} 