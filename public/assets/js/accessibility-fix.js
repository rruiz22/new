/**
 * Accesibilidad - Correcciones
 * 
 * Este script corrige problemas de accesibilidad detectados en la consola:
 * - Elementos sin atributos title o alt
 * - Elementos de formulario sin ID o name
 * - Elementos sin atributos de accesibilidad necesarios
 * - Elementos con aria-hidden que tienen descendientes con foco
 */

document.addEventListener('DOMContentLoaded', function() {
    try {
        // Corregir problemas de accesibilidad
        fixAccessibility();
        
        // Corregir problemas específicos de aria-hidden
        fixAriaHiddenIssues();
        
        // Observar cambios en el DOM para aplicar correcciones cuando se añadan nuevos elementos
        const observer = new MutationObserver(function(mutations) {
            try {
                // Aplicar correcciones si hay cambios en el DOM
                fixAccessibility();
            } catch (error) {
                console.warn("Error al aplicar correcciones de accesibilidad en observer:", error);
            }
        });
        
        // Configurar el observador para detectar cambios en el DOM
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['aria-hidden']
        });
    } catch (error) {
        console.warn("Error al inicializar correcciones de accesibilidad:", error);
    }
    
    // Función para corregir problemas de accesibilidad
    function fixAccessibility() {
        // 1. Añadir title a elementos sin title
        try {
            document.querySelectorAll('a:not([title]), button:not([title])').forEach(function(element) {
                // Si tiene texto, usar ese texto como title
                if (element.textContent && element.textContent.trim()) {
                    element.setAttribute('title', element.textContent.trim());
                } else if (element.getAttribute('aria-label')) {
                    // Si tiene aria-label, usar ese valor como title
                    element.setAttribute('title', element.getAttribute('aria-label'));
                } else {
                    // Si no tiene texto ni aria-label, añadir un title genérico
                    element.setAttribute('title', 'Acción');
                }
            });
        } catch (error) {
            console.warn("Error al corregir titles:", error);
        }
        
        // 2. Añadir alt a imágenes sin alt
        try {
            document.querySelectorAll('img:not([alt])').forEach(function(img) {
                const src = img.getAttribute('src');
                if (src) {
                    const parts = src.split('/');
                    const filename = parts[parts.length - 1] || 'Imagen';
                    img.setAttribute('alt', filename);
                } else {
                    img.setAttribute('alt', 'Imagen');
                }
            });
        } catch (error) {
            console.warn("Error al corregir alt de imágenes:", error);
        }
        
        // 3. Añadir id y name a elementos de formulario sin estos atributos
        try {
            const formElements = document.querySelectorAll('input:not([id]), textarea:not([id]), select:not([id])');
            if (formElements.length > 0) {
                for (let i = 0; i < formElements.length; i++) {
                    const element = formElements[i];
                    const type = element.getAttribute('type') || element.tagName.toLowerCase();
                    const randomId = 'form-' + type + '-' + Math.floor(Math.random() * 10000);
                    
                    element.setAttribute('id', randomId);
                    
                    // Si no tiene name, añadir uno
                    if (!element.getAttribute('name')) {
                        element.setAttribute('name', randomId);
                    }
                }
            }
        } catch (error) {
            console.warn("Error al corregir IDs de formularios:", error);
        }
        
        // 4. Corregir elementos <ul> y <ol> para contener solo elementos <li>
        try {
            document.querySelectorAll('ul, ol').forEach(function(list) {
                if (!list) return;
                
                // Convertir texto directo en <li>
                for (let i = 0; i < list.childNodes.length; i++) {
                    const node = list.childNodes[i];
                    if (node && node.nodeType === Node.TEXT_NODE && node.textContent && node.textContent.trim()) {
                        const li = document.createElement('li');
                        li.textContent = node.textContent;
                        list.replaceChild(li, node);
                    }
                }
            });
        } catch (error) {
            console.warn("Error al corregir listas:", error);
        }
        
        // 5. Añadir atributos ARIA a elementos de navegación
        try {
            document.querySelectorAll('nav:not([role])').forEach(function(nav) {
                if (nav) {
                    nav.setAttribute('role', 'navigation');
                }
            });
        } catch (error) {
            console.warn("Error al corregir elementos nav:", error);
        }
        
        // 6. Corregir problemas con tablas
        try {
            document.querySelectorAll('table').forEach(function(table) {
                if (!table) return;
                
                // Añadir role a tablas sin role
                if (!table.getAttribute('role')) {
                    table.setAttribute('role', 'table');
                }
                
                // Asegurarse de que thead tenga role="rowgroup"
                const thead = table.querySelector('thead');
                if (thead && !thead.getAttribute('role')) {
                    thead.setAttribute('role', 'rowgroup');
                }
                
                // Asegurarse de que tbody tenga role="rowgroup"
                const tbody = table.querySelector('tbody');
                if (tbody && !tbody.getAttribute('role')) {
                    tbody.setAttribute('role', 'rowgroup');
                }
                
                // Asegurarse de que las filas tengan role="row"
                const rows = table.querySelectorAll('tr');
                if (rows) {
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        if (row && !row.getAttribute('role')) {
                            row.setAttribute('role', 'row');
                        }
                    }
                }
                
                // Asegurarse de que los th tengan scope
                const headers = table.querySelectorAll('th:not([scope])');
                if (headers) {
                    for (let i = 0; i < headers.length; i++) {
                        const th = headers[i];
                        if (th && th.parentElement && th.parentElement.parentElement) {
                            // Determinar si es una celda de encabezado de columna o fila
                            const isInHeader = th.parentElement.parentElement.tagName.toLowerCase() === 'thead';
                            th.setAttribute('scope', isInHeader ? 'col' : 'row');
                        }
                    }
                }
            });
        } catch (error) {
            console.warn("Error al corregir tablas:", error);
        }
        
        // 7. Corregir problemas de elementos con aria-hidden
        try {
            document.querySelectorAll('[aria-hidden="true"]').forEach(function(element) {
                // Verificar si contiene elementos focusables
                const focusableElements = element.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                
                // Verificar si alguno tiene el foco
                let hasFocusedElement = false;
                for (let i = 0; i < focusableElements.length; i++) {
                    if (document.activeElement === focusableElements[i]) {
                        hasFocusedElement = true;
                        break;
                    }
                }
                
                // Si hay un elemento con foco, quitar aria-hidden
                if (hasFocusedElement) {
                    element.setAttribute('aria-hidden', 'false');
                }
            });
        } catch (error) {
            console.warn("Error al corregir aria-hidden:", error);
        }
    }
    
    // Función específica para corregir problemas de aria-hidden
    function fixAriaHiddenIssues() {
        // Manejar el botón específico probarToast
        const probarToastBtn = document.getElementById('probarToast');
        if (probarToastBtn) {
            probarToastBtn.addEventListener('click', function() {
                // Restaurar accesibilidad después de hacer clic
                setTimeout(function() {
                    const layoutWrapper = document.getElementById('layout-wrapper');
                    if (layoutWrapper && layoutWrapper.getAttribute('aria-hidden') === 'true') {
                        layoutWrapper.setAttribute('aria-hidden', 'false');
                    }
                }, 100);
            });
        }
        
        // Observar atributos aria-hidden
        const ariaObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && 
                    mutation.attributeName === 'aria-hidden') {
                    const element = mutation.target;
                    
                    // Si se establece aria-hidden="true", verificar si hay elementos focusables
                    if (element.getAttribute('aria-hidden') === 'true') {
                        // Verificar si contiene elementos focusables con foco
                        const focusableElements = element.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                        
                        // Verificar si alguno tiene el foco
                        const hasFocusedElement = Array.from(focusableElements).some(
                            el => document.activeElement === el
                        );
                        
                        // Si hay un elemento con foco, quitar aria-hidden
                        if (hasFocusedElement) {
                            // Usar timeout para asegurar que se aplique después de los cambios actuales
                            setTimeout(function() {
                                element.setAttribute('aria-hidden', 'false');
                            }, 0);
                        }
                    }
                }
            });
        });
        
        // Observar todo el documento para cambios en aria-hidden
        ariaObserver.observe(document.body, {
            attributes: true,
            attributeFilter: ['aria-hidden'],
            subtree: true
        });
        
        // Verificar periódicamente para casos donde la observación no sea suficiente
        setInterval(function() {
            const layoutWrapper = document.getElementById('layout-wrapper');
            if (layoutWrapper && layoutWrapper.getAttribute('aria-hidden') === 'true') {
                // Verificar si contiene elementos focusables con foco
                const focusedElement = layoutWrapper.querySelector(':focus');
                if (focusedElement) {
                    layoutWrapper.setAttribute('aria-hidden', 'false');
                }
            }
        }, 1000);
        
        // Agregar manejador global de eventos de foco
        document.addEventListener('focusin', function(e) {
            // Buscar ancestros con aria-hidden="true"
            let element = e.target;
            while (element && element !== document) {
                if (element.getAttribute && element.getAttribute('aria-hidden') === 'true') {
                    // Si el elemento o uno de sus ancestros tiene aria-hidden="true", corregirlo
                    element.setAttribute('aria-hidden', 'false');
                }
                element = element.parentNode;
            }
        });
        
        // Manejar modales y diálogos
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-bs-toggle="modal"]') || 
                e.target.closest('[data-bs-toggle="modal"]')) {
                // Restaurar accesibilidad después de abrir modal
                setTimeout(function() {
                    const layoutWrapper = document.getElementById('layout-wrapper');
                    if (layoutWrapper && layoutWrapper.getAttribute('aria-hidden') === 'true') {
                        const focusedElement = layoutWrapper.querySelector(':focus');
                        if (focusedElement) {
                            layoutWrapper.setAttribute('aria-hidden', 'false');
                        }
                    }
                }, 100);
            }
        });
    }
}); 