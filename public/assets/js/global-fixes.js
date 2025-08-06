/**
 * Global Fixes
 * 
 * Este archivo incluye correcciones globales para:
 * - Problemas de accesibilidad con aria-hidden
 * - Promesas rechazadas no manejadas
 * - Errores de canales de mensajes cerrados
 */

document.addEventListener('DOMContentLoaded', function() {
    // Solucionar problemas con aria-hidden en elementos con foco
    fixGlobalAriaHidden();
    
    // Manejar promesas rechazadas no manejadas
    handleUnhandledPromises();
    
    // Función para corregir problemas de aria-hidden globalmente
    function fixGlobalAriaHidden() {
        // Función para restaurar aria-hidden cuando se mueve el foco
        function restoreAriaHiddenOnFocus(e) {
            // Buscar hacia arriba en el árbol de elementos para encontrar cualquier elemento con aria-hidden="true"
            let element = e.target;
            while (element && element !== document) {
                if (element.hasAttribute && element.hasAttribute('aria-hidden') && 
                    element.getAttribute('aria-hidden') === 'true') {
                    // Si el elemento o alguno de sus ancestros tiene aria-hidden="true", corregirlo
                    element.setAttribute('aria-hidden', 'false');
                }
                element = element.parentNode;
            }
            
            // Comprobar específicamente el layout-wrapper
            const layoutWrapper = document.getElementById('layout-wrapper');
            if (layoutWrapper && layoutWrapper.getAttribute('aria-hidden') === 'true') {
                layoutWrapper.setAttribute('aria-hidden', 'false');
            }
        }
        
        // Instalar manejadores de eventos para detectar el foco
        document.addEventListener('focusin', restoreAriaHiddenOnFocus);
        document.addEventListener('click', function(e) {
            // Verificar si el clic es en un botón o enlace que pueda recibir foco
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A' || 
                e.target.closest('button') || e.target.closest('a')) {
                restoreAriaHiddenOnFocus(e);
            }
        });
        
        // Verificar periódicamente elementos con aria-hidden que contienen foco
        setInterval(function() {
            const ariaHiddenElements = document.querySelectorAll('[aria-hidden="true"]');
            ariaHiddenElements.forEach(function(element) {
                // Verificar si contiene el elemento activo
                if (element.contains(document.activeElement)) {
                    element.setAttribute('aria-hidden', 'false');
                }
            });
        }, 500);
        
        // Observar cambios en los atributos aria-hidden
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && 
                    mutation.attributeName === 'aria-hidden' &&
                    mutation.target.getAttribute('aria-hidden') === 'true') {
                    // Verificar si contiene elementos focusables con foco
                    if (mutation.target.contains(document.activeElement)) {
                        mutation.target.setAttribute('aria-hidden', 'false');
                    }
                }
            });
        });
        
        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['aria-hidden'],
            subtree: true
        });
    }
    
    // Función para manejar promesas rechazadas no manejadas
    function handleUnhandledPromises() {
        // Agregar manejador global para promesas rechazadas no manejadas
        window.addEventListener('unhandledrejection', function(event) {
            console.warn('Unhandled promise rejection:', event.reason);
            
            // Evitar que el error se muestre en la consola del usuario
            event.preventDefault();
            
            // Si el error es de un canal de mensajes cerrado, ignorarlo silenciosamente
            if (event.reason && event.reason.message && 
                (event.reason.message.includes('message channel') || 
                 event.reason.message.includes('closed') || 
                 event.reason.message.includes('abrupted'))) {
                return;
            }
            
            // Aquí se podría implementar un sistema de reporte de errores
            // o mostrar una notificación al usuario si es necesario
        });
        
        // Sobrescribir el método fetch para manejar errores de red
        const originalFetch = window.fetch;
        window.fetch = function() {
            return originalFetch.apply(this, arguments)
                .catch(function(error) {
                    // Manejar errores de red
                    console.warn('Fetch error:', error);
                    
                    // Propagar el error para que el código que llamó a fetch pueda manejarlo
                    throw error;
                });
        };
    }
}); 