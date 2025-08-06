/**
 * Sistema de Notificaciones Unificado - Velzon Theme
 * 
 * Este archivo proporciona funciones globales para notificaciones Toast y SweetAlert2
 * Optimizado para el tema Velzon con estilos modernos y minimalistas
 */

// ========================================
// CONFIGURACIÓN GLOBAL
// ========================================

window.NotificationSystem = {
    // Configuración por defecto para Toast Velzon
    toastDefaults: {
        duration: 5000,
        gravity: "bottom",
        position: "right",
        stopOnFocus: true,
        close: true,
        className: "velzon-toast",
        offset: {
            x: 24,
            y: 24
        },
        escapeMarkup: false
    },
    
    // Configuración por defecto para SweetAlert2
    swalDefaults: {
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false,
        allowEscapeKey: true,
        buttonsStyling: true,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-secondary ms-2'
        }
    }
};

// ========================================
// FUNCIONES DE TOAST VELZON
// ========================================

/**
 * Función principal para mostrar notificaciones Toast con tema Velzon
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo: success, error, warning, info
 * @param {object} options - Opciones adicionales
 */
window.showToast = function(message, type = 'info', options = {}) {
    if (!window.Toastify) {
        console.error('Toastify no está disponible');
        return false;
    }

    // Definir iconos y clases según el tipo
    let icon, typeClass;
    
    switch (type) {
        case 'success':
            icon = '✓';
            typeClass = 'toast-success';
            break;
        case 'error':
        case 'danger':
            icon = '✕';
            typeClass = 'toast-error';
            break;
        case 'warning':
            icon = '⚠';
            typeClass = 'toast-warning';
            break;
        case 'info':
        default:
            icon = 'ℹ';
            typeClass = 'toast-info';
            break;
    }

    // Crear el contenido HTML del toast
    const toastContent = `
        <div class="toast-content">
            <div class="toast-icon">${icon}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()" aria-label="Cerrar notificación">
            ×
        </button>
    `;

    // Combinar configuraciones
    const config = {
        ...window.NotificationSystem.toastDefaults,
        ...options,
        text: toastContent,
        className: `velzon-toast ${typeClass}`,
        onClick: function() {
            // Pausar auto-close al hacer click
            if (this.toastElement) {
                this.toastElement.style.animationPlayState = 'paused';
            }
        }
    };

    // Configurar duración de la animación de progreso
    if (config.duration && config.duration > 0) {
        config.style = {
            ...config.style,
            '--toast-duration': config.duration + 'ms'
        };
    }

    try {
        const toast = Toastify(config);
        
        // Configurar la barra de progreso
        const toastElement = toast.toastElement || document.createElement('div');
        if (config.duration && config.duration > 0) {
            toastElement.style.setProperty('--toast-duration', config.duration + 'ms');
            const progressBar = toastElement.querySelector('::before');
            if (progressBar) {
                progressBar.style.animationDuration = config.duration + 'ms';
            }
        }
        
        toast.showToast();
        
        // Agregar eventos para mejorar la experiencia
        setTimeout(() => {
            const element = document.querySelector('.velzon-toast:last-child');
            if (element) {
                // Pausar progreso al hover
                element.addEventListener('mouseenter', function() {
                    this.style.setProperty('--animation-play-state', 'paused');
                });
                
                element.addEventListener('mouseleave', function() {
                    this.style.setProperty('--animation-play-state', 'running');
                });
                
                // Configurar la barra de progreso
                if (config.duration && config.duration > 0) {
                    element.style.setProperty('--toast-duration', config.duration + 'ms');
                }
            }
        }, 100);
        
        return toast;
    } catch (error) {
        console.error('Error mostrando toast:', error);
        return false;
    }
};

// Funciones de conveniencia para Toast
window.showSuccessToast = function(message, options = {}) {
    return window.showToast(message, 'success', options);
};

window.showErrorToast = function(message, options = {}) {
    return window.showToast(message, 'error', options);
};

window.showWarningToast = function(message, options = {}) {
    return window.showToast(message, 'warning', options);
};

window.showInfoToast = function(message, options = {}) {
    return window.showToast(message, 'info', options);
};

// ========================================
// FUNCIONES DE SWEETALERT2
// ========================================

/**
 * Inicializar SweetAlert2 con configuración personalizada
 */
window.initSweetAlert = function() {
    if (typeof Swal !== 'undefined') {
        // Configurar SweetAlert2 globalmente
        Swal.mixin(window.NotificationSystem.swalDefaults);
        return true;
    }
    return false;
};

/**
 * Mostrar alerta de confirmación
 * @param {object} options - Opciones de la alerta
 * @returns {Promise}
 */
window.showConfirmAlert = function(options = {}) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está disponible');
        return Promise.resolve(false);
    }

    const config = {
        title: options.title || '¿Está seguro?',
        text: options.text || '¿Desea continuar con esta acción?',
        icon: options.icon || 'question',
        showCancelButton: true,
        ...window.NotificationSystem.swalDefaults,
        ...options
    };

    return Swal.fire(config);
};

/**
 * Mostrar alerta de eliminación
 * @param {object} options - Opciones de la alerta
 * @returns {Promise}
 */
window.showDeleteAlert = function(options = {}) {
    const config = {
        title: options.title || '¿Eliminar elemento?',
        text: options.text || 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: options.confirmText || 'Sí, eliminar',
        cancelButtonText: options.cancelText || 'Cancelar',
        confirmButtonColor: '#f06548',
        ...options
    };

    return window.showConfirmAlert(config);
};

/**
 * Mostrar alerta de éxito
 * @param {object} options - Opciones de la alerta
 * @returns {Promise}
 */
window.showSuccessAlert = function(options = {}) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está disponible');
        return Promise.resolve(false);
    }

    const config = {
        title: options.title || '¡Éxito!',
        text: options.text || 'La operación se completó correctamente',
        icon: 'success',
        showCancelButton: false,
        ...window.NotificationSystem.swalDefaults,
        ...options
    };

    return Swal.fire(config);
};

/**
 * Mostrar alerta de error
 * @param {object} options - Opciones de la alerta
 * @returns {Promise}
 */
window.showErrorAlert = function(options = {}) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está disponible');
        return Promise.resolve(false);
    }

    const config = {
        title: options.title || 'Error',
        text: options.text || 'Ocurrió un error al procesar la solicitud',
        icon: 'error',
        showCancelButton: false,
        ...window.NotificationSystem.swalDefaults,
        ...options
    };

    return Swal.fire(config);
};

/**
 * Mostrar alerta de carga
 * @param {string} title - Título de la alerta
 * @param {string} text - Texto de la alerta
 */
window.showLoadingAlert = function(title = 'Procesando...', text = 'Por favor espere') {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está disponible');
        return false;
    }

    Swal.fire({
        title: title,
        text: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

/**
 * Cerrar alerta de carga
 */
window.hideLoadingAlert = function() {
    if (typeof Swal !== 'undefined') {
        Swal.close();
    }
};

// ========================================
// INICIALIZACIÓN
// ========================================

document.addEventListener('DOMContentLoaded', function() {

    
    // Inicializar SweetAlert2
    if (window.initSweetAlert()) {

    } else {
        console.warn('SweetAlert2 no está disponible');
    }
    
    // Verificar Toastify
    if (typeof Toastify !== 'undefined') {

        
        // Aplicar estilos CSS para tema Velzon
        const velzonStyles = document.createElement('style');
        velzonStyles.textContent = `
            /* Estilos Velzon para notificaciones Toast */
            .velzon-toast {
                font-family: "Poppins", sans-serif !important;
                font-size: 14px !important;
                border-radius: 8px !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
                border: 1px solid rgba(0, 0, 0, 0.1) !important;
                min-height: 60px !important;
                z-index: 9999 !important;
                position: fixed !important;
                padding: 0 !important;
                overflow: hidden !important;
            }
            
            /* Contenido del toast */
            .toast-content {
                display: flex !important;
                align-items: center !important;
                padding: 16px !important;
                width: 100% !important;
            }
            
            .toast-icon {
                width: 24px !important;
                height: 24px !important;
                border-radius: 50% !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                margin-right: 12px !important;
                flex-shrink: 0 !important;
            }
            
            .toast-message {
                flex: 1 !important;
                line-height: 1.4 !important;
                font-weight: 500 !important;
            }
            
            /* Botón de cerrar */
            .toast-close {
                position: absolute !important;
                top: 8px !important;
                right: 8px !important;
                background: transparent !important;
                border: none !important;
                font-size: 20px !important;
                cursor: pointer !important;
                opacity: 0.5 !important;
                line-height: 1 !important;
                padding: 4px !important;
                width: 24px !important;
                height: 24px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            
            .toast-close:hover {
                opacity: 1 !important;
            }
            
            /* Barra de progreso */
            .velzon-toast::before {
                content: '' !important;
                position: absolute !important;
                bottom: 0 !important;
                left: 0 !important;
                height: 3px !important;
                width: 100% !important;
                animation: toast-progress var(--toast-duration, 5000ms) linear forwards !important;
                animation-play-state: var(--animation-play-state, running) !important;
            }
            
            @keyframes toast-progress {
                from { width: 100%; }
                to { width: 0%; }
            }
            
            /* Colores por tipo */
            .toast-success {
                background: #d1ecf1 !important;
                color: #155724 !important;
                border-left: 4px solid #28a745 !important;
            }
            
            .toast-success::before { background: #28a745 !important; }
            .toast-success .toast-icon { background: #28a745 !important; color: white !important; }
            
            .toast-error {
                background: #f8d7da !important;
                color: #721c24 !important;
                border-left: 4px solid #dc3545 !important;
            }
            
            .toast-error::before { background: #dc3545 !important; }
            .toast-error .toast-icon { background: #dc3545 !important; color: white !important; }
            
            .toast-warning {
                background: #fff3cd !important;
                color: #856404 !important;
                border-left: 4px solid #ffc107 !important;
            }
            
            .toast-warning::before { background: #ffc107 !important; }
            .toast-warning .toast-icon { background: #ffc107 !important; color: white !important; }
            
            .toast-info {
                background: #d4e6f1 !important;
                color: #0c5460 !important;
                border-left: 4px solid #17a2b8 !important;
            }
            
            .toast-info::before { background: #17a2b8 !important; }
            .toast-info .toast-icon { background: #17a2b8 !important; color: white !important; }
            
            /* Posicionamiento múltiple */
            .velzon-toast:nth-of-type(1) { bottom: 24px !important; }
            .velzon-toast:nth-of-type(2) { bottom: 104px !important; }
            .velzon-toast:nth-of-type(3) { bottom: 184px !important; }
            .velzon-toast:nth-of-type(4) { bottom: 264px !important; }
            .velzon-toast:nth-of-type(5) { bottom: 344px !important; }
            
            /* Responsive */
            @media (max-width: 768px) {
                .velzon-toast {
                    right: 16px !important;
                    left: 16px !important;
                    max-width: none !important;
                }
            }
            
            /* Tema oscuro */
            [data-bs-theme="dark"] .toast-success {
                background: rgba(40, 167, 69, 0.1) !important;
                color: #75b982 !important;
                border-left-color: #28a745 !important;
            }
            
            [data-bs-theme="dark"] .toast-error {
                background: rgba(220, 53, 69, 0.1) !important;
                color: #e28a9a !important;
                border-left-color: #dc3545 !important;
            }
            
            [data-bs-theme="dark"] .toast-warning {
                background: rgba(255, 193, 7, 0.1) !important;
                color: #ffda6a !important;
                border-left-color: #ffc107 !important;
            }
            
            [data-bs-theme="dark"] .toast-info {
                background: rgba(23, 162, 184, 0.1) !important;
                color: #6fc8e0 !important;
                border-left-color: #17a2b8 !important;
            }
        `;
        document.head.appendChild(velzonStyles);

    } else {
        console.warn('Toastify no está disponible');
    }
    
    // Mostrar notificaciones pendientes desde el servidor
    showPendingNotifications();
});

// ========================================
// INTEGRACIÓN CON EL SISTEMA DE SESIÓN
// ========================================

/**
 * Mostrar notificaciones pendientes del servidor
 */
function showPendingNotifications() {
    // Esta función será llamada automáticamente desde partials/toasts.php

}

// ========================================
// FUNCIONES DE COMPATIBILIDAD
// ========================================

// Alias para compatibilidad con código existente
window.showToastMessage = window.showToast;
window.Swal = window.Swal || {};

// Función global para mostrar notificaciones desde PHP
window.showNotificationFromPHP = function(type, message) {
    setTimeout(function() {
        window.showToast(message, type);
    }, 500);
};

 