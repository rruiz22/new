/**
 * Sistema de Notificaciones Unificado - Versión Mejorada
 * 
 * Este archivo corrige problemas de posicionamiento y carga
 */

// Esperar a que todo esté cargado
(function() {
    'use strict';
    
    console.log('🔧 Iniciando sistema de notificaciones mejorado...');
    
    // ========================================
    // VERIFICACIÓN DE DEPENDENCIAS
    // ========================================
    
    function checkDependencies() {
        const dependencies = {
            Toastify: typeof Toastify !== 'undefined',
            Swal: typeof Swal !== 'undefined',
            jQuery: typeof $ !== 'undefined'
        };
        
        console.log('📋 Estado de dependencias:', dependencies);
        return dependencies;
    }
    
    // ========================================
    // CONFIGURACIÓN MEJORADA
    // ========================================
    
    window.NotificationSystemFixed = {
        isReady: false,
        dependencies: {},
        
        // Configuración por defecto para Toast
        toastDefaults: {
            duration: 4000,
            gravity: "bottom",
            position: "right",
            stopOnFocus: true,
            close: true,
            className: "notification-toast-fixed",
            offset: {
                x: 20,
                y: 20
            }
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
        },
        
        init: function() {
            console.log('🚀 Inicializando sistema de notificaciones...');
            
            this.dependencies = checkDependencies();
            this.injectStyles();
            this.setupGlobalFunctions();
            this.isReady = true;
            
            console.log('✅ Sistema de notificaciones listo');
            
            // Evento personalizado para notificar que está listo
            window.dispatchEvent(new CustomEvent('notificationSystemReady'));
        },
        
        injectStyles: function() {
            const styles = `
                .notification-toast-fixed {
                    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
                    border-radius: 8px !important;
                    z-index: 99999 !important;
                    min-width: 300px !important;
                    max-width: 500px !important;
                    box-shadow: 0 8px 32px rgba(0,0,0,0.12) !important;
                    backdrop-filter: blur(8px) !important;
                }
                
                .toastify {
                    position: fixed !important;
                    bottom: 20px !important;
                    right: 20px !important;
                    top: auto !important;
                    left: auto !important;
                }
                
                .toastify.toastify-right {
                    right: 20px !important;
                    left: auto !important;
                }
                
                .toastify.toastify-bottom {
                    bottom: 20px !important;
                    top: auto !important;
                }
                
                .toastify.on {
                    opacity: 1 !important;
                    transform: translateY(0) !important;
                }
                
                .toast-close {
                    background: rgba(255,255,255,0.2) !important;
                    border: none !important;
                    border-radius: 4px !important;
                    color: white !important;
                    opacity: 0.8 !important;
                    padding: 4px 8px !important;
                    margin-left: 10px !important;
                    font-size: 16px !important;
                    line-height: 1 !important;
                    cursor: pointer !important;
                }
                
                .toast-close:hover {
                    opacity: 1 !important;
                    background: rgba(255,255,255,0.3) !important;
                }
                
                /* Animaciones mejoradas */
                .toastify {
                    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
                    transform: translateY(100px) !important;
                    opacity: 0 !important;
                }
                
                /* Stack multiple toasts */
                .toastify:nth-child(2) { bottom: 85px !important; }
                .toastify:nth-child(3) { bottom: 150px !important; }
                .toastify:nth-child(4) { bottom: 215px !important; }
                .toastify:nth-child(5) { bottom: 280px !important; }
                
                @media (max-width: 768px) {
                    .notification-toast-fixed {
                        min-width: 280px !important;
                        max-width: calc(100vw - 40px) !important;
                        right: 20px !important;
                        left: 20px !important;
                        margin: 0 auto !important;
                    }
                }
            `;
            
            const styleSheet = document.createElement('style');
            styleSheet.textContent = styles;
            document.head.appendChild(styleSheet);
            
            console.log('💄 Estilos CSS inyectados');
        },
        
        setupGlobalFunctions: function() {
            const self = this;
            
            // ========================================
            // FUNCIONES DE TOAST MEJORADAS
            // ========================================
            
            window.showToastFixed = function(message, type = 'info', options = {}) {
                if (!self.dependencies.Toastify) {
                    console.error('❌ Toastify no disponible, usando fallback');
                    self.fallbackNotification(message, type);
                    return false;
                }
                
                // Definir colores y iconos según el tipo
                let backgroundColor, textColor, icon;
                
                switch (type) {
                    case 'success':
                        backgroundColor = 'linear-gradient(135deg, #0ab39c 0%, #3dd5f3 100%)';
                        textColor = '#ffffff';
                        icon = '<i class="ri-check-double-line me-2"></i>';
                        break;
                    case 'error':
                    case 'danger':
                        backgroundColor = 'linear-gradient(135deg, #f06548 0%, #f093d2 100%)';
                        textColor = '#ffffff';
                        icon = '<i class="ri-error-warning-line me-2"></i>';
                        break;
                    case 'warning':
                        backgroundColor = 'linear-gradient(135deg, #f7b84b 0%, #f8d97a 100%)';
                        textColor = '#ffffff';
                        icon = '<i class="ri-alert-line me-2"></i>';
                        break;
                    case 'info':
                    default:
                        backgroundColor = 'linear-gradient(135deg, #405189 0%, #6c7cdb 100%)';
                        textColor = '#ffffff';
                        icon = '<i class="ri-information-line me-2"></i>';
                        break;
                }
                
                // Combinar configuraciones
                const config = {
                    ...self.toastDefaults,
                    ...options,
                    text: icon + message,
                    style: {
                        background: backgroundColor,
                        color: textColor,
                        borderRadius: '8px',
                        fontSize: '14px',
                        fontWeight: '500',
                        lineHeight: '1.4',
                        padding: '16px 20px',
                        boxShadow: '0 8px 32px rgba(0,0,0,0.12)',
                        border: 'none',
                        ...options.style
                    },
                    escapeMarkup: false
                };
                
                try {
                    console.log('🎯 Creando toast:', { message, type, config });
                    const toast = Toastify(config);
                    toast.showToast();
                    
                    // Log de éxito
                    console.log('✅ Toast mostrado exitosamente');
                    return toast;
                } catch (error) {
                    console.error('❌ Error mostrando toast:', error);
                    self.fallbackNotification(message, type);
                    return false;
                }
            };
            
            // Funciones de conveniencia
            window.showSuccessToastFixed = function(message, options = {}) {
                return window.showToastFixed(message, 'success', options);
            };
            
            window.showErrorToastFixed = function(message, options = {}) {
                return window.showToastFixed(message, 'error', options);
            };
            
            window.showWarningToastFixed = function(message, options = {}) {
                return window.showToastFixed(message, 'warning', options);
            };
            
            window.showInfoToastFixed = function(message, options = {}) {
                return window.showToastFixed(message, 'info', options);
            };
            
            // ========================================
            // FUNCIONES DE SWEETALERT2 MEJORADAS
            // ========================================
            
            window.showConfirmAlertFixed = function(options = {}) {
                if (!self.dependencies.Swal) {
                    console.error('❌ SweetAlert2 no disponible, usando fallback');
                    return Promise.resolve(confirm(options.text || '¿Continuar?'));
                }
                
                const config = {
                    title: options.title || '¿Está seguro?',
                    text: options.text || '¿Desea continuar con esta acción?',
                    icon: options.icon || 'question',
                    showCancelButton: true,
                    ...self.swalDefaults,
                    ...options
                };
                
                return Swal.fire(config);
            };
            
            window.showSuccessAlertFixed = function(options = {}) {
                if (!self.dependencies.Swal) {
                    console.error('❌ SweetAlert2 no disponible, usando fallback');
                    alert('✅ ' + (options.text || 'Operación exitosa'));
                    return Promise.resolve(true);
                }
                
                const config = {
                    title: options.title || '¡Éxito!',
                    text: options.text || 'La operación se completó correctamente',
                    icon: 'success',
                    showCancelButton: false,
                    ...self.swalDefaults,
                    ...options
                };
                
                return Swal.fire(config);
            };
            
            // ========================================
            // OVERRIDE DE FUNCIONES ORIGINALES
            // ========================================
            
            // Sobrescribir las funciones originales para usar la versión mejorada
            window.showToast = window.showToastFixed;
            window.showSuccessToast = window.showSuccessToastFixed;
            window.showErrorToast = window.showErrorToastFixed;
            window.showWarningToast = window.showWarningToastFixed;
            window.showInfoToast = window.showInfoToastFixed;
            window.showConfirmAlert = window.showConfirmAlertFixed;
            window.showSuccessAlert = window.showSuccessAlertFixed;
            
            console.log('🔧 Funciones globales configuradas');
        },
        
        fallbackNotification: function(message, type) {
            const icons = {
                success: '✅',
                error: '❌',
                warning: '⚠️',
                info: 'ℹ️'
            };
            
            const icon = icons[type] || 'ℹ️';
            alert(icon + ' ' + message);
        }
    };
    
    // ========================================
    // INICIALIZACIÓN AUTOMÁTICA
    // ========================================
    
    function initSystem() {
        console.log('📱 Iniciando sistema en estado:', document.readyState);
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => window.NotificationSystemFixed.init(), 500);
            });
        } else {
            setTimeout(() => window.NotificationSystemFixed.init(), 100);
        }
    }
    
    // Inicializar inmediatamente
    initSystem();
    
    // ========================================
    // FUNCIÓN DE PRUEBA AUTOMÁTICA
    // ========================================
    
    window.testNotificationSystem = function() {
        console.log('🧪 Ejecutando pruebas del sistema...');
        
        if (!window.NotificationSystemFixed.isReady) {
            console.warn('⚠️ Sistema no está listo, esperando...');
            setTimeout(window.testNotificationSystem, 1000);
            return;
        }
        
        // Prueba básica
        setTimeout(() => {
            window.showSuccessToast('🧪 Sistema de notificaciones funcionando correctamente');
        }, 500);
        
        setTimeout(() => {
            window.showInfoToast('💡 Las notificaciones aparecen en la esquina inferior derecha');
        }, 1500);
    };
    
})();

console.log('📦 Archivo de notificaciones mejorado cargado'); 