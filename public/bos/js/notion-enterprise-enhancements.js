/**
 * BOS Notion Enterprise Enhancements
 * Advanced UI interactions and animations for BMW of Sudbury inventory system
 */

class NotionEnterpriseUI {
    constructor() {
        this.isInitialized = false;
        this.observers = new Map();
        this.activeFilters = new Set();
        this.animationQueue = [];
        
        this.init();
    }
    
    init() {
        if (this.isInitialized) return;
        
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initializeComponents());
        } else {
            this.initializeComponents();
        }
        
        this.isInitialized = true;
    }
    
    initializeComponents() {
        console.log('🎨 Initializing Notion Enterprise UI...');
        
        // Initialize all components
        this.setupStatWidgets();
        this.setupTableEnhancements();
        this.setupMicroInteractions();
        this.setupKeyboardShortcuts();
        this.setupAccessibility();
        this.setupLoadingStates();
        this.setupNotifications();
        
        // Add entry animation
        this.playEntryAnimations();
        
        console.log('✨ Notion Enterprise UI initialized successfully');
    }
    
    /**
     * Enhanced Statistics Widgets
     */
    setupStatWidgets() {
        const widgets = document.querySelectorAll('.stat-widget');
        
        widgets.forEach((widget, index) => {
            // Add progressive loading animation
            widget.style.opacity = '0';
            widget.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                widget.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                widget.style.opacity = '1';
                widget.style.transform = 'translateY(0)';
            }, index * 100);
            
            // Enhanced click handling
            widget.addEventListener('click', (e) => this.handleWidgetClick(widget, e));
            
            // Hover effects
            widget.addEventListener('mouseenter', () => this.handleWidgetHover(widget, true));
            widget.addEventListener('mouseleave', () => this.handleWidgetHover(widget, false));
            
            // Add ripple effect
            this.addRippleEffect(widget);
        });
        
        // Setup value counting animations
        this.setupValueCounters();
    }
    
    handleWidgetClick(widget, event) {
        // Add click feedback
        widget.style.transform = 'scale(0.98)';
        setTimeout(() => {
            widget.style.transform = '';
        }, 150);
        
        // Toggle active state
        const isActive = widget.classList.contains('active');
        
        // Clear other active widgets if this is becoming active
        if (!isActive) {
            document.querySelectorAll('.stat-widget.active').forEach(w => {
                w.classList.remove('active');
            });
        }
        
        widget.classList.toggle('active', !isActive);
        
        // Handle filter logic
        const filter = widget.dataset.filter;
        if (filter !== undefined) {
            this.handleFilterChange(filter, !isActive);
        }
        
        // Play success sound (if audio enabled)
        this.playInteractionSound('click');
    }
    
    handleWidgetHover(widget, isEntering) {
        if (isEntering) {
            // Add subtle tilt effect
            const rect = widget.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            
            widget.style.transform = 'perspective(1000px) rotateX(2deg) rotateY(2deg) translateY(-4px)';
        } else {
            widget.style.transform = '';
        }
    }
    
    setupValueCounters() {
        const valueElements = document.querySelectorAll('.stat-value');
        
        valueElements.forEach(element => {
            // Store original update function
            const originalUpdate = element.textContent;
            
            // Create intersection observer for animation on scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animateValue(element);
                        observer.unobserve(element);
                    }
                });
            }, { threshold: 0.5 });
            
            observer.observe(element);
        });
    }
    
    animateValue(element, targetValue = null) {
        const target = targetValue !== null ? targetValue : parseInt(element.textContent) || 0;
        const duration = 1200;
        const startTime = performance.now();
        const startValue = 0;
        
        element.classList.add('updating');
        
        const updateValue = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(startValue + (target - startValue) * easeOut);
            
            element.textContent = current.toLocaleString();
            
            if (progress < 1) {
                requestAnimationFrame(updateValue);
            } else {
                element.classList.remove('updating');
                // Add completion effect
                element.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    element.style.transform = '';
                }, 200);
            }
        };
        
        requestAnimationFrame(updateValue);
    }
    
    /**
     * Enhanced Table Interactions
     */
    setupTableEnhancements() {
        const table = document.getElementById('inventoryTable');
        if (!table) return;
        
        // Enhance table rows
        this.enhanceTableRows(table);
        
        // Add sorting indicators
        this.addSortingIndicators(table);
        
        // Setup responsive table behavior
        this.setupResponsiveTable(table);
    }
    
    enhanceTableRows(table) {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;
        
        // Use MutationObserver to handle dynamically added rows
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1 && node.tagName === 'TR') {
                        this.enhanceRow(node);
                    }
                });
            });
        });
        
        observer.observe(tbody, { childList: true });
        
        // Enhance existing rows
        tbody.querySelectorAll('tr').forEach(row => this.enhanceRow(row));
    }
    
    enhanceRow(row) {
        // Add hover effects
        row.addEventListener('mouseenter', () => {
            row.style.transform = 'translateX(4px)';
            row.style.boxShadow = '0 2px 8px rgba(28, 105, 212, 0.1)';
        });
        
        row.addEventListener('mouseleave', () => {
            row.style.transform = '';
            row.style.boxShadow = '';
        });
        
        // Add click effects
        row.addEventListener('click', () => {
            row.style.transform = 'scale(0.995)';
            setTimeout(() => {
                row.style.transform = '';
            }, 100);
        });
        
        // Add progressive loading animation
        row.style.opacity = '0';
        row.style.transform = 'translateY(10px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, Math.random() * 200);
    }
    
    addSortingIndicators(table) {
        const headers = table.querySelectorAll('th');
        
        headers.forEach(header => {
            header.addEventListener('click', () => {
                // Remove existing sort indicators
                headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
                
                // Add sort indicator to clicked header
                const currentSort = header.dataset.sort || 'none';
                const newSort = currentSort === 'asc' ? 'desc' : 'asc';
                
                header.dataset.sort = newSort;
                header.classList.add(`sort-${newSort}`);
                
                // Add visual feedback
                header.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    header.style.transform = '';
                }, 100);
            });
        });
    }
    
    setupResponsiveTable(table) {\n        const container = table.closest('.table-wrapper');\n        if (!container) return;\n        \n        // Add scroll indicators\n        const scrollIndicator = document.createElement('div');\n        scrollIndicator.className = 'scroll-indicator';\n        scrollIndicator.innerHTML = '<i class=\"bx bx-chevrons-right\"></i> Scroll to see more';\n        scrollIndicator.style.cssText = `\n            position: absolute;\n            bottom: 10px;\n            right: 10px;\n            background: var(--bmw-blue);\n            color: white;\n            padding: 4px 8px;\n            border-radius: 4px;\n            font-size: 0.75rem;\n            opacity: 0;\n            transition: opacity 0.3s ease;\n        `;\n        \n        container.style.position = 'relative';\n        container.appendChild(scrollIndicator);\n        \n        // Show/hide scroll indicator\n        container.addEventListener('scroll', () => {\n            const isScrollable = container.scrollWidth > container.clientWidth;\n            const isScrolledToEnd = container.scrollLeft >= (container.scrollWidth - container.clientWidth - 10);\n            \n            if (isScrollable && !isScrolledToEnd) {\n                scrollIndicator.style.opacity = '0.8';\n            } else {\n                scrollIndicator.style.opacity = '0';\n            }\n        });\n    }\n    \n    /**\n     * Micro-interactions and animations\n     */\n    setupMicroInteractions() {\n        // Add ripple effects to buttons\n        document.querySelectorAll('.btn').forEach(btn => {\n            this.addRippleEffect(btn);\n            \n            // Enhanced button interactions\n            btn.addEventListener('mousedown', () => {\n                btn.style.transform = 'scale(0.95)';\n            });\n            \n            btn.addEventListener('mouseup', () => {\n                btn.style.transform = '';\n            });\n            \n            btn.addEventListener('mouseleave', () => {\n                btn.style.transform = '';\n            });\n        });\n        \n        // Floating labels for inputs\n        document.querySelectorAll('.form-input').forEach(input => {\n            this.setupFloatingLabel(input);\n        });\n        \n        // Smooth page transitions\n        this.setupPageTransitions();\n    }\n    \n    addRippleEffect(element) {\n        element.addEventListener('click', (e) => {\n            const ripple = document.createElement('span');\n            const rect = element.getBoundingClientRect();\n            const size = Math.max(rect.width, rect.height);\n            const x = e.clientX - rect.left - size / 2;\n            const y = e.clientY - rect.top - size / 2;\n            \n            ripple.style.cssText = `\n                position: absolute;\n                width: ${size}px;\n                height: ${size}px;\n                left: ${x}px;\n                top: ${y}px;\n                background: rgba(255, 255, 255, 0.3);\n                border-radius: 50%;\n                transform: scale(0);\n                animation: ripple 0.6s cubic-bezier(0.4, 0, 0.2, 1);\n                pointer-events: none;\n                z-index: 1000;\n            `;\n            \n            element.style.position = 'relative';\n            element.style.overflow = 'hidden';\n            element.appendChild(ripple);\n            \n            setTimeout(() => {\n                ripple.remove();\n            }, 600);\n        });\n        \n        // Add ripple CSS if not exists\n        if (!document.getElementById('ripple-styles')) {\n            const style = document.createElement('style');\n            style.id = 'ripple-styles';\n            style.textContent = `\n                @keyframes ripple {\n                    to {\n                        transform: scale(4);\n                        opacity: 0;\n                    }\n                }\n            `;\n            document.head.appendChild(style);\n        }\n    }\n    \n    setupFloatingLabel(input) {\n        const parent = input.parentElement;\n        const label = parent.querySelector('label');\n        \n        if (!label) return;\n        \n        const updateLabel = () => {\n            if (input.value || input === document.activeElement) {\n                label.classList.add('floating');\n            } else {\n                label.classList.remove('floating');\n            }\n        };\n        \n        input.addEventListener('focus', updateLabel);\n        input.addEventListener('blur', updateLabel);\n        input.addEventListener('input', updateLabel);\n        \n        updateLabel();\n    }\n    \n    /**\n     * Keyboard shortcuts\n     */\n    setupKeyboardShortcuts() {\n        document.addEventListener('keydown', (e) => {\n            // Alt + R: Refresh\n            if (e.altKey && e.key === 'r') {\n                e.preventDefault();\n                const refreshBtn = document.getElementById('refreshInventoryBtn');\n                if (refreshBtn) {\n                    refreshBtn.click();\n                    this.showNotification('Refreshing inventory...', 'info');\n                }\n            }\n            \n            // Alt + C: Clear filters\n            if (e.altKey && e.key === 'c') {\n                e.preventDefault();\n                const clearBtn = document.getElementById('clearAllFilters');\n                if (clearBtn) {\n                    clearBtn.click();\n                    this.showNotification('Filters cleared', 'success');\n                }\n            }\n            \n            // Escape: Clear active states\n            if (e.key === 'Escape') {\n                document.querySelectorAll('.active').forEach(el => {\n                    el.classList.remove('active');\n                });\n            }\n        });\n    }\n    \n    /**\n     * Accessibility enhancements\n     */\n    setupAccessibility() {\n        // Add ARIA labels to interactive elements\n        document.querySelectorAll('.stat-widget').forEach((widget, index) => {\n            widget.setAttribute('role', 'button');\n            widget.setAttribute('tabindex', '0');\n            widget.setAttribute('aria-label', `Filter by ${widget.querySelector('.stat-label')?.textContent}`);\n            \n            // Keyboard support\n            widget.addEventListener('keydown', (e) => {\n                if (e.key === 'Enter' || e.key === ' ') {\n                    e.preventDefault();\n                    widget.click();\n                }\n            });\n        });\n        \n        // Add focus indicators\n        const focusCSS = `\n            .stat-widget:focus-visible,\n            .btn:focus-visible {\n                outline: 2px solid var(--bmw-blue) !important;\n                outline-offset: 2px !important;\n                box-shadow: 0 0 0 4px rgba(28, 105, 212, 0.1) !important;\n            }\n        `;\n        \n        if (!document.getElementById('focus-styles')) {\n            const style = document.createElement('style');\n            style.id = 'focus-styles';\n            style.textContent = focusCSS;\n            document.head.appendChild(style);\n        }\n    }\n    \n    /**\n     * Loading states and skeletons\n     */\n    setupLoadingStates() {\n        this.createSkeletonLoader();\n    }\n    \n    createSkeletonLoader() {\n        const skeletonHTML = `\n            <div class=\"skeleton-loader\" style=\"display: none;\">\n                <div class=\"skeleton-stats\">\n                    <div class=\"skeleton-card\"></div>\n                    <div class=\"skeleton-card\"></div>\n                    <div class=\"skeleton-card\"></div>\n                    <div class=\"skeleton-card\"></div>\n                </div>\n                <div class=\"skeleton-table\">\n                    <div class=\"skeleton-row\"></div>\n                    <div class=\"skeleton-row\"></div>\n                    <div class=\"skeleton-row\"></div>\n                </div>\n            </div>\n        `;\n        \n        const skeletonCSS = `\n            .skeleton-loader {\n                position: fixed;\n                top: 0;\n                left: 0;\n                right: 0;\n                bottom: 0;\n                background: rgba(255, 255, 255, 0.95);\n                z-index: 9999;\n                display: flex;\n                flex-direction: column;\n                padding: 2rem;\n            }\n            \n            .skeleton-stats {\n                display: grid;\n                grid-template-columns: repeat(4, 1fr);\n                gap: 1.5rem;\n                margin-bottom: 2rem;\n            }\n            \n            .skeleton-card {\n                height: 120px;\n                background: var(--gray-200);\n                border-radius: 12px;\n                animation: skeleton-pulse 1.5s ease-in-out infinite;\n            }\n            \n            .skeleton-table {\n                flex: 1;\n                background: var(--gray-200);\n                border-radius: 12px;\n                animation: skeleton-pulse 1.5s ease-in-out infinite;\n            }\n            \n            @keyframes skeleton-pulse {\n                0%, 100% { opacity: 0.4; }\n                50% { opacity: 0.6; }\n            }\n        `;\n        \n        // Add skeleton HTML\n        document.body.insertAdjacentHTML('beforeend', skeletonHTML);\n        \n        // Add skeleton CSS\n        if (!document.getElementById('skeleton-styles')) {\n            const style = document.createElement('style');\n            style.id = 'skeleton-styles';\n            style.textContent = skeletonCSS;\n            document.head.appendChild(style);\n        }\n    }\n    \n    showLoadingSkeleton() {\n        const skeleton = document.querySelector('.skeleton-loader');\n        if (skeleton) {\n            skeleton.style.display = 'flex';\n        }\n    }\n    \n    hideLoadingSkeleton() {\n        const skeleton = document.querySelector('.skeleton-loader');\n        if (skeleton) {\n            skeleton.style.opacity = '0';\n            setTimeout(() => {\n                skeleton.style.display = 'none';\n                skeleton.style.opacity = '1';\n            }, 300);\n        }\n    }\n    \n    /**\n     * Notification system\n     */\n    setupNotifications() {\n        this.createNotificationContainer();\n    }\n    \n    createNotificationContainer() {\n        if (document.getElementById('notification-container')) return;\n        \n        const container = document.createElement('div');\n        container.id = 'notification-container';\n        container.style.cssText = `\n            position: fixed;\n            top: 20px;\n            right: 20px;\n            z-index: 10000;\n            display: flex;\n            flex-direction: column;\n            gap: 10px;\n        `;\n        \n        document.body.appendChild(container);\n    }\n    \n    showNotification(message, type = 'info', duration = 3000) {\n        const container = document.getElementById('notification-container');\n        if (!container) return;\n        \n        const notification = document.createElement('div');\n        notification.className = `notification notification-${type}`;\n        notification.style.cssText = `\n            background: var(--surface-primary);\n            border: 1px solid var(--gray-200);\n            border-radius: 8px;\n            padding: 12px 16px;\n            box-shadow: var(--shadow-lg);\n            transform: translateX(400px);\n            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);\n            font-size: 0.875rem;\n            font-weight: 500;\n            max-width: 300px;\n        `;\n        \n        // Set type-specific styles\n        const typeStyles = {\n            success: { borderColor: 'var(--success)', color: 'var(--success)' },\n            error: { borderColor: 'var(--error)', color: 'var(--error)' },\n            warning: { borderColor: 'var(--warning)', color: 'var(--warning)' },\n            info: { borderColor: 'var(--bmw-blue)', color: 'var(--bmw-blue)' }\n        };\n        \n        const style = typeStyles[type] || typeStyles.info;\n        notification.style.borderLeftColor = style.borderColor;\n        notification.style.color = style.color;\n        notification.style.borderLeftWidth = '4px';\n        \n        notification.textContent = message;\n        \n        container.appendChild(notification);\n        \n        // Animate in\n        setTimeout(() => {\n            notification.style.transform = 'translateX(0)';\n        }, 10);\n        \n        // Auto remove\n        setTimeout(() => {\n            notification.style.transform = 'translateX(400px)';\n            setTimeout(() => {\n                if (notification.parentNode) {\n                    notification.remove();\n                }\n            }, 300);\n        }, duration);\n        \n        // Click to dismiss\n        notification.addEventListener('click', () => {\n            notification.style.transform = 'translateX(400px)';\n            setTimeout(() => {\n                if (notification.parentNode) {\n                    notification.remove();\n                }\n            }, 300);\n        });\n    }\n    \n    /**\n     * Entry animations\n     */\n    playEntryAnimations() {\n        // Fade in main container\n        const container = document.querySelector('.notion-container');\n        if (container) {\n            container.style.opacity = '0';\n            container.style.transform = 'translateY(20px)';\n            \n            setTimeout(() => {\n                container.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';\n                container.style.opacity = '1';\n                container.style.transform = 'translateY(0)';\n            }, 100);\n        }\n        \n        // Progressive reveal of elements\n        const elements = [\n            '.notion-page-header',\n            '.stats-grid',\n            '.table-container'\n        ];\n        \n        elements.forEach((selector, index) => {\n            const element = document.querySelector(selector);\n            if (element) {\n                element.style.opacity = '0';\n                element.style.transform = 'translateY(30px)';\n                \n                setTimeout(() => {\n                    element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';\n                    element.style.opacity = '1';\n                    element.style.transform = 'translateY(0)';\n                }, 200 + index * 150);\n            }\n        });\n    }\n    \n    /**\n     * Utility methods\n     */\n    handleFilterChange(filter, isActive) {\n        if (isActive) {\n            this.activeFilters.add(filter);\n        } else {\n            this.activeFilters.delete(filter);\n        }\n        \n        // Trigger filter event for DataTables integration\n        const event = new CustomEvent('filterChange', {\n            detail: { filter, isActive, activeFilters: Array.from(this.activeFilters) }\n        });\n        document.dispatchEvent(event);\n    }\n    \n    playInteractionSound(type) {\n        // Optional: Add subtle audio feedback\n        if (window.AudioContext) {\n            try {\n                const audioContext = new AudioContext();\n                const oscillator = audioContext.createOscillator();\n                const gain = audioContext.createGain();\n                \n                oscillator.connect(gain);\n                gain.connect(audioContext.destination);\n                \n                const frequencies = {\n                    click: 800,\n                    hover: 600,\n                    success: 1000,\n                    error: 300\n                };\n                \n                oscillator.frequency.setValueAtTime(frequencies[type] || 800, audioContext.currentTime);\n                gain.gain.setValueAtTime(0.1, audioContext.currentTime);\n                gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);\n                \n                oscillator.start(audioContext.currentTime);\n                oscillator.stop(audioContext.currentTime + 0.1);\n            } catch (e) {\n                // Ignore audio errors\n            }\n        }\n    }\n    \n    setupPageTransitions() {\n        // Smooth transitions between page states\n        document.addEventListener('beforeunload', () => {\n            document.body.style.transition = 'opacity 0.3s ease';\n            document.body.style.opacity = '0';\n        });\n    }\n    \n    // Public API methods\n    updateStatValue(id, newValue) {\n        const element = document.getElementById(id);\n        if (element) {\n            this.animateValue(element, newValue);\n        }\n    }\n    \n    showSuccess(message) {\n        this.showNotification(message, 'success');\n    }\n    \n    showError(message) {\n        this.showNotification(message, 'error');\n    }\n    \n    showInfo(message) {\n        this.showNotification(message, 'info');\n    }\n}\n\n// Initialize the UI enhancements\nconst notionUI = new NotionEnterpriseUI();\n\n// Expose to global scope for integration with existing code\nwindow.NotionEnterpriseUI = notionUI;\n\n// Export for module systems\nif (typeof module !== 'undefined' && module.exports) {\n    module.exports = NotionEnterpriseUI;\n}"}