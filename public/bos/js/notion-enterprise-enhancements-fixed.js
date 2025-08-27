/**
 * BOS Notion Enterprise Enhancements - FIXED
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
            widget.style.transform = 'perspective(1000px) rotateX(2deg) rotateY(2deg) translateY(-4px)';
        } else {
            widget.style.transform = '';
        }
    }
    
    setupValueCounters() {
        const valueElements = document.querySelectorAll('.stat-value');
        
        valueElements.forEach(element => {
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
    
    setupResponsiveTable(table) {
        const container = table.closest('.table-wrapper');
        if (!container) return;
        
        // Add scroll indicators
        const scrollIndicator = document.createElement('div');
        scrollIndicator.className = 'scroll-indicator';
        scrollIndicator.innerHTML = '<i class="bx bx-chevrons-right"></i> Scroll to see more';
        scrollIndicator.style.cssText = `
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: var(--bmw-blue);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        
        container.style.position = 'relative';
        container.appendChild(scrollIndicator);
        
        // Show/hide scroll indicator
        container.addEventListener('scroll', () => {
            const isScrollable = container.scrollWidth > container.clientWidth;
            const isScrolledToEnd = container.scrollLeft >= (container.scrollWidth - container.clientWidth - 10);
            
            if (isScrollable && !isScrolledToEnd) {
                scrollIndicator.style.opacity = '0.8';
            } else {
                scrollIndicator.style.opacity = '0';
            }
        });
    }
    
    /**
     * Micro-interactions and animations
     */
    setupMicroInteractions() {
        // Add ripple effects to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            this.addRippleEffect(btn);
            
            // Enhanced button interactions
            btn.addEventListener('mousedown', () => {
                btn.style.transform = 'scale(0.95)';
            });
            
            btn.addEventListener('mouseup', () => {
                btn.style.transform = '';
            });
            
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = '';
            });
        });
        
        // Floating labels for inputs
        document.querySelectorAll('.form-input').forEach(input => {
            this.setupFloatingLabel(input);
        });
        
        // Smooth page transitions
        this.setupPageTransitions();
    }
    
    addRippleEffect(element) {
        element.addEventListener('click', (e) => {
            const ripple = document.createElement('span');
            const rect = element.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s cubic-bezier(0.4, 0, 0.2, 1);
                pointer-events: none;
                z-index: 1000;
            `;
            
            element.style.position = 'relative';
            element.style.overflow = 'hidden';
            element.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
        
        // Add ripple CSS if not exists
        if (!document.getElementById('ripple-styles')) {
            const style = document.createElement('style');
            style.id = 'ripple-styles';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    setupFloatingLabel(input) {
        const parent = input.parentElement;
        const label = parent.querySelector('label');
        
        if (!label) return;
        
        const updateLabel = () => {
            if (input.value || input === document.activeElement) {
                label.classList.add('floating');
            } else {
                label.classList.remove('floating');
            }
        };
        
        input.addEventListener('focus', updateLabel);
        input.addEventListener('blur', updateLabel);
        input.addEventListener('input', updateLabel);
        
        updateLabel();
    }
    
    /**
     * Keyboard shortcuts
     */
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Alt + R: Refresh
            if (e.altKey && e.key === 'r') {
                e.preventDefault();
                const refreshBtn = document.getElementById('refreshInventoryBtn');
                if (refreshBtn) {
                    refreshBtn.click();
                    this.showNotification('Refreshing inventory...', 'info');
                }
            }
            
            // Alt + C: Clear filters
            if (e.altKey && e.key === 'c') {
                e.preventDefault();
                const clearBtn = document.getElementById('clearAllFilters');
                if (clearBtn) {
                    clearBtn.click();
                    this.showNotification('Filters cleared', 'success');
                }
            }
            
            // Escape: Clear active states
            if (e.key === 'Escape') {
                document.querySelectorAll('.active').forEach(el => {
                    el.classList.remove('active');
                });
            }
        });
    }
    
    /**
     * Accessibility enhancements
     */
    setupAccessibility() {
        // Add ARIA labels to interactive elements
        document.querySelectorAll('.stat-widget').forEach((widget, index) => {
            widget.setAttribute('role', 'button');
            widget.setAttribute('tabindex', '0');
            widget.setAttribute('aria-label', `Filter by ${widget.querySelector('.stat-label')?.textContent}`);
            
            // Keyboard support
            widget.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    widget.click();
                }
            });
        });
        
        // Add focus indicators
        const focusCSS = `
            .stat-widget:focus-visible,
            .btn:focus-visible {
                outline: 2px solid var(--bmw-blue) !important;
                outline-offset: 2px !important;
                box-shadow: 0 0 0 4px rgba(28, 105, 212, 0.1) !important;
            }
        `;
        
        if (!document.getElementById('focus-styles')) {
            const style = document.createElement('style');
            style.id = 'focus-styles';
            style.textContent = focusCSS;
            document.head.appendChild(style);
        }
    }
    
    /**
     * Loading states and skeletons
     */
    setupLoadingStates() {
        this.createSkeletonLoader();
    }
    
    createSkeletonLoader() {
        const skeletonHTML = `
            <div class="skeleton-loader" style="display: none;">
                <div class="skeleton-stats">
                    <div class="skeleton-card"></div>
                    <div class="skeleton-card"></div>
                    <div class="skeleton-card"></div>
                    <div class="skeleton-card"></div>
                </div>
                <div class="skeleton-table">
                    <div class="skeleton-row"></div>
                    <div class="skeleton-row"></div>
                    <div class="skeleton-row"></div>
                </div>
            </div>
        `;
        
        const skeletonCSS = `
            .skeleton-loader {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.95);
                z-index: 9999;
                display: flex;
                flex-direction: column;
                padding: 2rem;
            }
            
            .skeleton-stats {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 1.5rem;
                margin-bottom: 2rem;
            }
            
            .skeleton-card {
                height: 120px;
                background: var(--gray-200);
                border-radius: 12px;
                animation: skeleton-pulse 1.5s ease-in-out infinite;
            }
            
            .skeleton-table {
                flex: 1;
                background: var(--gray-200);
                border-radius: 12px;
                animation: skeleton-pulse 1.5s ease-in-out infinite;
            }
            
            @keyframes skeleton-pulse {
                0%, 100% { opacity: 0.4; }
                50% { opacity: 0.6; }
            }
        `;
        
        // Add skeleton HTML
        document.body.insertAdjacentHTML('beforeend', skeletonHTML);
        
        // Add skeleton CSS
        if (!document.getElementById('skeleton-styles')) {
            const style = document.createElement('style');
            style.id = 'skeleton-styles';
            style.textContent = skeletonCSS;
            document.head.appendChild(style);
        }
    }
    
    /**
     * Notification system
     */
    setupNotifications() {
        this.createNotificationContainer();
    }
    
    createNotificationContainer() {
        if (document.getElementById('notification-container')) return;
        
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        `;
        
        document.body.appendChild(container);
    }
    
    showNotification(message, type = 'info', duration = 3000) {
        const container = document.getElementById('notification-container');
        if (!container) return;
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            background: var(--surface-primary);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 12px 16px;
            box-shadow: var(--shadow-lg);
            transform: translateX(400px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.875rem;
            font-weight: 500;
            max-width: 300px;
        `;
        
        // Set type-specific styles
        const typeStyles = {
            success: { borderColor: 'var(--success)', color: 'var(--success)' },
            error: { borderColor: 'var(--error)', color: 'var(--error)' },
            warning: { borderColor: 'var(--warning)', color: 'var(--warning)' },
            info: { borderColor: 'var(--bmw-blue)', color: 'var(--bmw-blue)' }
        };
        
        const style = typeStyles[type] || typeStyles.info;
        notification.style.borderLeftColor = style.borderColor;
        notification.style.color = style.color;
        notification.style.borderLeftWidth = '4px';
        
        notification.textContent = message;
        
        container.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, duration);
        
        // Click to dismiss
        notification.addEventListener('click', () => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        });
    }
    
    /**
     * Entry animations
     */
    playEntryAnimations() {
        // Fade in main container
        const container = document.querySelector('.notion-container');
        if (container) {
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        }
        
        // Progressive reveal of elements
        const elements = [
            '.notion-page-header',
            '.stats-grid',
            '.table-container'
        ];
        
        elements.forEach((selector, index) => {
            const element = document.querySelector(selector);
            if (element) {
                element.style.opacity = '0';
                element.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 200 + index * 150);
            }
        });
    }
    
    /**
     * Utility methods
     */
    handleFilterChange(filter, isActive) {
        if (isActive) {
            this.activeFilters.add(filter);
        } else {
            this.activeFilters.delete(filter);
        }
        
        // Trigger filter event for DataTables integration
        const event = new CustomEvent('filterChange', {
            detail: { filter, isActive, activeFilters: Array.from(this.activeFilters) }
        });
        document.dispatchEvent(event);
    }
    
    playInteractionSound(type) {
        // Optional: Add subtle audio feedback
        if (window.AudioContext) {
            try {
                const audioContext = new AudioContext();
                const oscillator = audioContext.createOscillator();
                const gain = audioContext.createGain();
                
                oscillator.connect(gain);
                gain.connect(audioContext.destination);
                
                const frequencies = {
                    click: 800,
                    hover: 600,
                    success: 1000,
                    error: 300
                };
                
                oscillator.frequency.setValueAtTime(frequencies[type] || 800, audioContext.currentTime);
                gain.gain.setValueAtTime(0.1, audioContext.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.1);
            } catch (e) {
                // Ignore audio errors
            }
        }
    }
    
    setupPageTransitions() {
        // Smooth transitions between page states
        document.addEventListener('beforeunload', () => {
            document.body.style.transition = 'opacity 0.3s ease';
            document.body.style.opacity = '0';
        });
    }
    
    // Public API methods
    updateStatValue(id, newValue) {
        const element = document.getElementById(id);
        if (element) {
            this.animateValue(element, newValue);
        }
    }
    
    showSuccess(message) {
        this.showNotification(message, 'success');
    }
    
    showError(message) {
        this.showNotification(message, 'error');
    }
    
    showInfo(message) {
        this.showNotification(message, 'info');
    }
}

// Initialize the UI enhancements
const notionUI = new NotionEnterpriseUI();

// Expose to global scope for integration with existing code
window.NotionEnterpriseUI = notionUI;

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = NotionEnterpriseUI;
}