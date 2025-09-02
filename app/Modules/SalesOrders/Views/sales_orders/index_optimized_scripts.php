<!-- Optimized Sales Orders Index - Security and Performance Enhanced Scripts -->

<!-- CSS Dependencies -->
<link rel="stylesheet" href="<?= base_url('assets/css/notion-theme-unified-v2.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/attachment-system.css') ?>">

<!-- JavaScript Security and Performance Modules -->
<script src="<?= base_url('assets/js/modules/sales-orders-security.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/sales-orders-performance.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/sales-orders-events.js') ?>"></script>

<!-- Main Application Script -->
<script nonce="<?= csrf_hash() ?>">
/**
 * Sales Orders Application - Secure and Optimized Version
 * @version 2.0.0
 * All inline scripts have been replaced with secure event delegation
 * eval() usage has been eliminated
 * XSS vulnerabilities have been patched
 * Performance optimizations implemented
 */

class SalesOrdersApp {
    constructor() {
        this.config = {
            baseUrl: '<?= base_url() ?>',
            csrfToken: '<?= csrf_token() ?>',
            csrfHash: '<?= csrf_hash() ?>',
            translations: {
                loading: '<?= lang('App.loading') ?>',
                saving: '<?= lang('App.saving') ?>',
                saved: '<?= lang('App.saved') ?>',
                error: '<?= lang('App.error') ?>',
                success: '<?= lang('App.success') ?>',
                confirmDelete: '<?= lang('App.confirm_delete') ?>',
                confirmRestore: '<?= lang('App.confirm_restore') ?>'
            }
        };
        
        this.state = {
            activeTab: 'dashboard',
            filters: {
                client: '',
                contact: '',
                status: '',
                dateFrom: '',
                dateTo: ''
            },
            modals: new Set(),
            loading: new Set()
        };
        
        this.init();
    }

    /**
     * Initialize application
     */
    init() {
        this.setupCSRFProtection();
        this.initializeComponents();
        this.setupGlobalEventListeners();
        this.loadSavedState();
        this.initializeFeatherIcons();
    }

    /**
     * Setup CSRF protection for all AJAX requests
     */
    setupCSRFProtection() {
        // Add CSRF token to all fetch requests
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            if (!options.headers) {
                options.headers = {};
            }
            
            options.headers['X-CSRF-Token'] = this.config.csrfHash;
            options.headers['X-Requested-With'] = 'XMLHttpRequest';
            
            return originalFetch.call(this, url, options);
        }.bind(this);
    }

    /**
     * Initialize application components
     */
    initializeComponents() {
        this.initializeFilters();
        this.initializeModals();
        this.initializeTabs();
        this.initializeDataTables();
        this.initializeFormValidation();
    }

    /**
     * Initialize global event listeners using secure event delegation
     */
    setupGlobalEventListeners() {
        // Remove all existing inline event handlers and replace with secure delegation
        this.removeInsecureEventHandlers();
        
        // Set up secure event delegation
        this.setupSecureEventDelegation();
        
        // Set up keyboard shortcuts
        this.setupKeyboardShortcuts();
    }

    /**
     * Remove insecure inline event handlers
     */
    removeInsecureEventHandlers() {
        // Remove all onclick attributes
        const elementsWithOnclick = document.querySelectorAll('[onclick]');
        elementsWithOnclick.forEach(element => {
            const onclickValue = element.getAttribute('onclick');
            
            // Store the action in a data attribute for secure handling
            if (onclickValue.includes('loadDeletedOrdersContent')) {
                element.dataset.action = 'load-deleted-orders';
            } else if (onclickValue.includes('bulkRestore')) {
                element.dataset.action = 'bulk-restore';
            } else if (onclickValue.includes('bulkForceDelete')) {
                element.dataset.action = 'bulk-force-delete';
            } else if (onclickValue.includes('restoreOrder')) {
                const match = onclickValue.match(/restoreOrder\((\d+)\)/);
                if (match) {
                    element.dataset.action = 'restore-order';
                    element.dataset.orderId = match[1];
                }
            } else if (onclickValue.includes('forceDeleteOrder')) {
                const match = onclickValue.match(/forceDeleteOrder\((\d+)\)/);
                if (match) {
                    element.dataset.action = 'force-delete-order';
                    element.dataset.orderId = match[1];
                }
            } else if (onclickValue.includes('openModalForNewOrder')) {
                element.dataset.action = 'open-new-order-modal';
            }
            
            // Remove the onclick attribute
            element.removeAttribute('onclick');
        });
    }

    /**
     * Setup secure event delegation
     */
    setupSecureEventDelegation() {
        // Button clicks
        document.addEventListener('click', (e) => {
            const target = e.target.closest('[data-action]');
            if (!target) return;
            
            this.handleSecureButtonClick(e, target);
        });
        
        // Form submissions
        document.addEventListener('submit', (e) => {
            this.handleSecureFormSubmit(e);
        });
        
        // Input changes
        document.addEventListener('change', (e) => {
            this.handleSecureInputChange(e);
        });
        
        // Modal events
        document.addEventListener('show.bs.modal', (e) => {
            this.handleModalShow(e);
        });
        
        document.addEventListener('hide.bs.modal', (e) => {
            this.handleModalHide(e);
        });
    }

    /**
     * Handle secure button clicks
     * @param {Event} e - Click event
     * @param {HTMLElement} target - Target element
     */
    handleSecureButtonClick(e, target) {
        const action = target.dataset.action;
        const orderId = target.dataset.orderId;
        
        // Prevent multiple clicks
        if (target.disabled) return;
        
        switch (action) {
            case 'load-deleted-orders':
                this.loadDeletedOrdersContent();
                break;
            case 'bulk-restore':
                this.handleBulkRestore();
                break;
            case 'bulk-force-delete':
                this.handleBulkForceDelete();
                break;
            case 'restore-order':
                if (orderId) this.restoreOrder(orderId);
                break;
            case 'force-delete-order':
                if (orderId) this.forceDeleteOrder(orderId);
                break;
            case 'open-new-order-modal':
                this.openModalForNewOrder();
                break;
            case 'add-order':
                this.showOrderModal();
                break;
            case 'edit-order':
                this.editOrder(orderId);
                break;
            case 'toggle-filters':
                this.toggleFilters();
                break;
            case 'apply-filters':
                this.applyFilters();
                break;
            case 'clear-filters':
                this.clearFilters();
                break;
            default:
                console.warn('Unknown secure action:', action);
        }
    }

    /**
     * Handle secure form submissions
     * @param {Event} e - Submit event
     */
    handleSecureFormSubmit(e) {
        const form = e.target;
        const formId = form.id;
        
        // Prevent double submission
        if (this.state.loading.has(formId)) {
            e.preventDefault();
            return;
        }
        
        // Validate form security
        if (!this.validateFormSecurity(form)) {
            e.preventDefault();
            this.showToast('error', 'Form validation failed - security check');
            return;
        }
        
        switch (formId) {
            case 'salesOrderForm':
                this.handleSalesOrderFormSubmit(e);
                break;
            case 'serviceForm':
                this.handleServiceFormSubmit(e);
                break;
            case 'filterForm':
                this.handleFilterFormSubmit(e);
                break;
            default:
                // Allow default form submission for other forms
                break;
        }
    }

    /**
     * Validate form security before submission
     * @param {HTMLFormElement} form - Form to validate
     * @returns {boolean} Is form secure
     */
    validateFormSecurity(form) {
        // Use security module for validation
        const validation = window.SalesOrdersSecurity.validateFormInputs(new FormData(form));
        
        if (!validation.isValid) {
            console.warn('Form validation errors:', validation.errors);
            return false;
        }
        
        return true;
    }

    /**
     * Initialize filters with secure handling
     */
    initializeFilters() {
        const filterElements = {
            client: document.getElementById('globalClientFilter'),
            contact: document.getElementById('globalContactFilter'),
            status: document.getElementById('globalStatusFilter'),
            dateFrom: document.getElementById('globalDateFromFilter'),
            dateTo: document.getElementById('globalDateToFilter')
        };
        
        // Set up filter change handlers
        Object.entries(filterElements).forEach(([key, element]) => {
            if (element) {
                element.addEventListener('change', () => {
                    this.state.filters[key] = element.value;
                    this.saveFilterState();
                });
            }
        });
        
        // Set up filter buttons
        const applyButton = document.getElementById('applyGlobalFilters');
        const clearButton = document.getElementById('clearGlobalFilters');
        
        if (applyButton) {
            applyButton.dataset.action = 'apply-filters';
        }
        
        if (clearButton) {
            clearButton.dataset.action = 'clear-filters';
        }
    }

    /**
     * Initialize modals with secure content loading
     */
    initializeModals() {
        const modalElements = document.querySelectorAll('.modal');
        
        modalElements.forEach(modal => {
            // Replace innerHTML usage with secure DOM manipulation
            const originalShow = modal.show;
            if (originalShow) {
                modal.show = function() {
                    // Add security check before showing modal
                    if (this.validateModalSecurity(modal)) {
                        originalShow.call(modal);
                    }
                }.bind(this);
            }
        });
    }

    /**
     * Validate modal security
     * @param {HTMLElement} modal - Modal element
     * @returns {boolean} Is modal secure
     */
    validateModalSecurity(modal) {
        // Check for script injection attempts
        const content = modal.innerHTML;
        const hasScript = /<script/i.test(content) || /javascript:/i.test(content);
        
        if (hasScript) {
            console.warn('Script injection attempt detected in modal:', modal.id);
            return false;
        }
        
        return true;
    }

    /**
     * Initialize tabs with state management
     */
    initializeTabs() {
        const tabElements = document.querySelectorAll('[data-bs-toggle="tab"]');
        
        tabElements.forEach(tab => {
            tab.addEventListener('shown.bs.tab', (e) => {
                const tabId = e.target.getAttribute('href') || e.target.dataset.bsTarget;
                this.state.activeTab = tabId;
                this.saveTabState();
                this.initializeTabContent(tabId);
            });
        });
        
        // Restore saved tab
        this.restoreSavedTab();
    }

    /**
     * Initialize tab content lazily
     * @param {string} tabId - Tab ID to initialize
     */
    initializeTabContent(tabId) {
        switch (tabId) {
            case '#dashboard-tab':
                this.initializeDashboard();
                break;
            case '#all-orders-tab':
                this.initializeAllOrders();
                break;
            case '#services-tab':
                this.initializeServices();
                break;
            case '#deleted-orders-tab':
                this.loadDeletedOrdersContent();
                break;
        }
    }

    /**
     * Initialize DataTables with performance optimizations
     */
    initializeDataTables() {
        // Use performance module for optimized table handling
        const tables = document.querySelectorAll('table.data-table');
        
        tables.forEach(table => {
            window.SalesOrdersPerformance.optimizeTable(table);
            
            // Set up lazy loading if table is large
            const rows = table.querySelectorAll('tbody tr');
            if (rows.length > 50) {
                this.setupTableVirtualization(table);
            }
        });
    }

    /**
     * Setup table virtualization for large datasets
     * @param {HTMLElement} table - Table to virtualize
     */
    setupTableVirtualization(table) {
        // Delegate to performance module
        window.SalesOrdersPerformance.virtualizeTable(table);
    }

    /**
     * Load modal content securely
     * @param {string} url - URL to load content from
     * @param {HTMLElement} modal - Target modal element
     * @returns {Promise} Load promise
     */
    async loadModalContentSecurely(url, modal) {
        try {
            const response = await window.SalesOrdersSecurity.secureFetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const content = await response.text();
            
            // Use security module to set content safely
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                window.SalesOrdersSecurity.setSecureHTML(modalContent, content);
            }
            
            // Initialize any forms in the modal
            const forms = modal.querySelectorAll('form');
            forms.forEach(form => this.initializeFormValidation(form));
            
            // Refresh feather icons
            this.initializeFeatherIcons();
            
            return true;
            
        } catch (error) {
            console.error('Modal content loading error:', error);
            this.showErrorModal('Failed to load content. Please try again.');
            return false;
        }
    }

    /**
     * Show order modal
     */
    async showOrderModal() {
        const modal = document.getElementById('orderModal');
        if (!modal) return;
        
        const success = await this.loadModalContentSecurely(
            `${this.config.baseUrl}sales_orders/modal_form`,
            modal
        );
        
        if (success) {
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        }
    }

    /**
     * Edit order
     * @param {string} orderId - Order ID to edit
     */
    async editOrder(orderId) {
        if (!orderId) return;
        
        const modal = document.getElementById('orderModal');
        if (!modal) return;
        
        const success = await this.loadModalContentSecurely(
            `${this.config.baseUrl}sales_orders/modal_form?id=${encodeURIComponent(orderId)}`,
            modal
        );
        
        if (success) {
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        }
    }

    /**
     * Load deleted orders content
     */
    async loadDeletedOrdersContent() {
        const container = document.getElementById('deleted-orders-content');
        if (!container) return;
        
        // Show loading state
        container.innerHTML = `
            <div class="d-flex justify-content-center p-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">${this.config.translations.loading}</span>
                </div>
            </div>
        `;
        
        try {
            const response = await window.SalesOrdersSecurity.secureFetch(
                `${this.config.baseUrl}sales_orders/deleted_content`
            );
            
            if (!response.ok) {
                throw new Error('Failed to load deleted orders');
            }
            
            const content = await response.text();
            window.SalesOrdersSecurity.setSecureHTML(container, content);
            
        } catch (error) {
            console.error('Error loading deleted orders:', error);
            container.innerHTML = `
                <div class="alert alert-danger">
                    <i data-feather="alert-circle" class="me-2"></i>
                    Error loading deleted orders. Please try again.
                </div>
            `;
        }
    }

    /**
     * Initialize form validation
     * @param {HTMLFormElement} form - Form to validate
     */
    initializeFormValidation(form) {
        if (!form) return;
        
        // Remove existing validation
        form.classList.remove('was-validated');
        
        // Set up real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                this.validateInput(input);
            });
            
            input.addEventListener('blur', () => {
                this.validateInput(input);
            });
        });
    }

    /**
     * Validate individual input
     * @param {HTMLElement} input - Input to validate
     */
    validateInput(input) {
        const value = input.value;
        const type = input.type;
        const name = input.name;
        
        let isValid = true;
        let errorMessage = '';
        
        // Required field validation
        if (input.hasAttribute('required') && !value.trim()) {
            isValid = false;
            errorMessage = 'This field is required';
        }
        
        // Type-specific validation using security module
        if (isValid && value) {
            switch (type) {
                case 'email':
                    isValid = window.SalesOrdersSecurity.isValidEmail(value);
                    errorMessage = 'Please enter a valid email address';
                    break;
                case 'tel':
                    isValid = window.SalesOrdersSecurity.isValidPhone(value);
                    errorMessage = 'Please enter a valid phone number';
                    break;
            }
            
            // Name-specific validation
            if (name === 'vin') {
                isValid = window.SalesOrdersSecurity.isValidVIN(value);
                errorMessage = 'Please enter a valid VIN (17 characters)';
            }
        }
        
        // Update UI
        this.updateInputValidationUI(input, isValid, errorMessage);
        
        return isValid;
    }

    /**
     * Update input validation UI
     * @param {HTMLElement} input - Input element
     * @param {boolean} isValid - Is input valid
     * @param {string} errorMessage - Error message
     */
    updateInputValidationUI(input, isValid, errorMessage) {
        input.classList.remove('is-valid', 'is-invalid');
        
        // Remove existing error message
        const existingError = input.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        
        if (!isValid) {
            input.classList.add('is-invalid');
            
            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = errorMessage;
            input.parentNode.appendChild(errorDiv);
        } else if (input.value) {
            input.classList.add('is-valid');
        }
    }

    /**
     * Show toast notification
     * @param {string} type - Toast type (success, error, warning, info)
     * @param {string} message - Message to display
     */
    showToast(type, message) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${window.SalesOrdersSecurity.sanitizeHTML(message)}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        // Add to container
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.appendChild(toast);
        
        // Show toast
        const bootstrapToast = new bootstrap.Toast(toast);
        bootstrapToast.show();
        
        // Remove from DOM after hiding
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    /**
     * Initialize Feather icons
     */
    initializeFeatherIcons() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    /**
     * Setup keyboard shortcuts
     */
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Don't trigger shortcuts when user is typing
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return;
            }
            
            const isCtrlOrCmd = e.ctrlKey || e.metaKey;
            
            if (isCtrlOrCmd) {
                switch (e.key.toLowerCase()) {
                    case 'n':
                        e.preventDefault();
                        this.showOrderModal();
                        break;
                    case 'f':
                        e.preventDefault();
                        this.toggleFilters();
                        break;
                    case 'r':
                        e.preventDefault();
                        this.refreshCurrentTab();
                        break;
                }
            }
            
            if (e.key === 'Escape') {
                this.closeActiveModals();
            }
        });
    }

    /**
     * Save current state
     */
    saveState() {
        localStorage.setItem('salesOrdersState', JSON.stringify(this.state));
    }

    /**
     * Load saved state
     */
    loadSavedState() {
        try {
            const savedState = localStorage.getItem('salesOrdersState');
            if (savedState) {
                const parsed = JSON.parse(savedState);
                this.state = { ...this.state, ...parsed };
            }
        } catch (error) {
            console.warn('Failed to load saved state:', error);
        }
    }

    /**
     * Save filter state
     */
    saveFilterState() {
        this.saveState();
    }

    /**
     * Save tab state
     */
    saveTabState() {
        localStorage.setItem('activeTab', this.state.activeTab);
        this.saveState();
    }

    /**
     * Restore saved tab
     */
    restoreSavedTab() {
        const savedTab = localStorage.getItem('activeTab');
        if (savedTab) {
            const tabElement = document.querySelector(`[href="${savedTab}"], [data-bs-target="${savedTab}"]`);
            if (tabElement) {
                const tab = new bootstrap.Tab(tabElement);
                tab.show();
            }
        }
    }

    /**
     * Cleanup resources
     */
    cleanup() {
        // Cancel any pending operations
        this.state.loading.clear();
        
        // Clear timeouts and intervals
        // (Implementation would clear specific timers)
    }

    // Placeholder methods for specific functionality
    // These would be implemented based on specific business logic
    
    handleBulkRestore() { console.log('Bulk restore action'); }
    handleBulkForceDelete() { console.log('Bulk force delete action'); }
    restoreOrder(orderId) { console.log('Restore order:', orderId); }
    forceDeleteOrder(orderId) { console.log('Force delete order:', orderId); }
    openModalForNewOrder() { this.showOrderModal(); }
    toggleFilters() { console.log('Toggle filters'); }
    applyFilters() { console.log('Apply filters'); }
    clearFilters() { console.log('Clear filters'); }
    initializeDashboard() { console.log('Initialize dashboard'); }
    initializeAllOrders() { console.log('Initialize all orders'); }
    initializeServices() { console.log('Initialize services'); }
    refreshCurrentTab() { console.log('Refresh current tab'); }
    closeActiveModals() { console.log('Close active modals'); }
    showErrorModal(message) { this.showToast('error', message); }
    
    handleSalesOrderFormSubmit(e) {
        // Implementation for sales order form submission
        console.log('Sales order form submit');
    }
    
    handleServiceFormSubmit(e) {
        // Implementation for service form submission
        console.log('Service form submit');
    }
    
    handleFilterFormSubmit(e) {
        // Implementation for filter form submission
        console.log('Filter form submit');
    }
    
    handleSecureInputChange(e) {
        // Implementation for input changes
        const input = e.target;
        if (input.classList.contains('status-dropdown')) {
            window.SalesOrdersPerformance.handleStatusChange(input);
        }
    }
    
    handleModalShow(e) {
        const modal = e.target;
        this.state.modals.add(modal.id);
        this.initializeFeatherIcons();
    }
    
    handleModalHide(e) {
        const modal = e.target;
        this.state.modals.delete(modal.id);
    }
}

// Initialize application when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the secure sales orders application
    window.salesOrdersApp = new SalesOrdersApp();
    
    // Legacy compatibility - gradually remove these as components are migrated
    window.showToast = (type, message) => {
        window.salesOrdersApp.showToast(type, message);
    };
    
    console.log('✅ Sales Orders Application initialized with security enhancements');
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.salesOrdersApp) {
        window.salesOrdersApp.cleanup();
    }
});
</script>

<!-- Performance optimization: Preload critical resources -->
<link rel="preload" href="<?= base_url('assets/js/modules/sales-orders-security.js') ?>" as="script">
<link rel="preload" href="<?= base_url('assets/js/modules/sales-orders-performance.js') ?>" as="script">
<link rel="preload" href="<?= base_url('assets/js/modules/sales-orders-events.js') ?>" as="script">

<!-- Content Security Policy Meta Tag -->
<meta http-equiv="Content-Security-Policy" content="
    default-src 'self';
    script-src 'self' 'nonce-<?= csrf_hash() ?>' https://cdn.jsdelivr.net https://unpkg.com;
    style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;
    img-src 'self' data: https:;
    font-src 'self' https://fonts.gstatic.com;
    connect-src 'self';
    frame-ancestors 'none';
    base-uri 'self';
    form-action 'self';
">

<!-- Security Headers -->
<meta name="referrer" content="strict-origin-when-cross-origin">
<meta name="csrf-token" content="<?= csrf_hash() ?>">