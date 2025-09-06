/**
 * ============================================================================
 * GLOBAL SALES ORDER MODAL - Complete Implementation
 * Mobile-first, responsive, and feature-rich modal handler
 * ============================================================================
 */

class GlobalSalesOrderModal {
    constructor() {
        this.modal = null;
        this.form = null;
        this.modalInstance = null;
        this.isEditMode = false;
        this.dateTimeController = null;
        
        // Configuration
        this.config = {
            baseUrl: window.base_url || '/',
            apiEndpoints: {
                clients: 'sales_orders/getActiveClients',
                contacts: 'sales_orders/getContactsForClient/',
                services: 'sales_orders/getServicesForClient/',
                save: 'sales_orders/save',
                get: 'sales_orders/get/',
                decodeVin: 'sales_orders/decodeVin',
                validateDateTime: 'sales_orders/validateDateTime',
                checkDuplicate: 'sales_orders/checkDuplicateOrder'
            },
            businessHours: {
                start: 8,  // 8 AM
                end: 18,   // 6 PM
                interval: 1 // 1 hour intervals
            },
            validation: {
                minAdvanceHours: 1 // Minimum 1 hour advance booking
            }
        };
        
        this.init();
    }

    init() {
        // Wait for DOM to be fully loaded before initializing
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.init());
            return;
        }
        
        // Check if Bootstrap is available
        if (typeof bootstrap === 'undefined') {
            console.error('❌ Bootstrap not loaded - waiting...');
            setTimeout(() => this.init(), 500);
            return;
        }
        
        this.modal = document.getElementById('global-sales-order-modal');
        this.form = document.getElementById('globalSalesOrderForm');
        
        console.log('🔍 Initializing GlobalSalesOrderModal...');
        console.log('Modal element:', this.modal);
        console.log('Form element:', this.form);
        
        if (!this.modal || !this.form) {
            console.warn('⚠️ GlobalSalesOrderModal: Elements not found - will initialize on first use');
            console.log('Missing modal:', !this.modal);
            console.log('Missing form:', !this.form);
            return;
        }

        // Check if submit button exists
        const submitBtn = document.getElementById('global_submit_btn');
        console.log('Submit button found:', submitBtn);

        this.bindEvents();
        this.initializeFeatherIcons();
        
        // Initialize date time controller if available
        if (typeof DateTimeController !== 'undefined') {
            this.dateTimeController = new DateTimeController(this);
        }
        
        console.log('✅ GlobalSalesOrderModal: Initialized successfully');
    }

    bindEvents() {
        if (!this.modal || !this.form) {
            console.warn('Cannot bind events - modal or form not found');
            return;
        }

        // Modal events
        this.modal.addEventListener('shown.bs.modal', () => this.onModalShown());
        this.modal.addEventListener('hidden.bs.modal', () => this.onModalHidden());

        // Form submission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Direct button click handler as backup
        const submitBtn = document.getElementById('global_submit_btn');
        if (submitBtn) {
            submitBtn.addEventListener('click', (e) => {
                console.log('Submit button clicked directly');
                e.preventDefault();
                this.handleSubmit(e);
            });
        }

        // Client change - with null check
        const clientSelect = document.getElementById('global_client_id');
        if (clientSelect) {
            clientSelect.addEventListener('change', (e) => this.onClientChange(e.target.value));
        }

        // VIN input - with null check
        const vinInput = document.getElementById('global_vin');
        if (vinInput) {
            vinInput.addEventListener('input', (e) => this.onVinInput(e.target.value));
            vinInput.addEventListener('blur', (e) => this.onVinBlur(e.target.value));
        }

        // VIN scanner button - with null check
        const scanBtn = document.getElementById('global_scan_vin_btn');
        if (scanBtn) {
            scanBtn.addEventListener('click', () => this.openVinScanner());
        }

        // Service selection - with null check
        const serviceSelect = document.getElementById('global_service_id');
        if (serviceSelect) {
            serviceSelect.addEventListener('change', (e) => this.onServiceChange(e.target.value));
        }
    }

    initializeFeatherIcons() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    // ========================================
    // PUBLIC METHODS
    // ========================================

    open(orderId = null) {
        // Reinitialize if elements weren't found during initial load
        if (!this.modal || !this.form) {
            console.log('🔄 Reinitializing modal elements...');
            this.modal = document.getElementById('global-sales-order-modal');
            this.form = document.getElementById('globalSalesOrderForm');
            
            if (!this.modal || !this.form) {
                console.error('❌ Modal elements still not found');
                if (typeof showError === 'function') {
                    showError('Modal Error', 'Could not find modal elements');
                } else {
                    console.error('Modal Error: Could not find modal elements');
                }
                return;
            }
            
            // Rebind events for newly found elements
            this.bindEvents();
        }

        this.isEditMode = !!orderId;
        
        // Pre-load data before showing modal
        if (this.isEditMode) {
            console.log('📊 Loading order data for edit mode...');
            this.loadOrderData(orderId);
        } else {
            console.log('📊 Resetting form for create mode...');
            this.resetForm();
            this.loadClients();
        }
        
        try {
            // Check for existing modal instance and dispose it
            let modalInstance = bootstrap.Modal.getInstance(this.modal);
            if (modalInstance) {
                console.log('🔄 Disposing existing modal instance');
                modalInstance.dispose();
            }
            
            // Create fresh modal instance with explicit options
            modalInstance = new bootstrap.Modal(this.modal, {
                backdrop: 'static',
                keyboard: false,
                focus: true
            });
            
            console.log('✅ Modal instance created, attempting to show...');
            
            // Add one-time event listeners for debugging
            this.modal.addEventListener('show.bs.modal', function() {
                console.log('🎭 Modal show event fired');
            }, { once: true });
            
            this.modal.addEventListener('shown.bs.modal', function() {
                console.log('🎭 Modal shown event fired - modal is now visible');
            }, { once: true });
            
            this.modal.addEventListener('hidden.bs.modal', function() {
                console.log('🎭 Modal hidden event fired');
            }, { once: true });
            
            // Show the modal
            modalInstance.show();
            
            // Store instance for later use
            this.modalInstance = modalInstance;
            
        } catch (error) {
            console.error('❌ Error opening modal:', error);
            console.error('Error details:', error.stack);
            
            if (typeof showError === 'function') {
                showError('Modal Error', 'Could not open modal: ' + error.message);
            } else {
                console.error('Modal Error: Could not open modal: ' + error.message);
            }
        }
    }

    close() {
        console.log('🚪 Closing modal...');
        
        // Use stored instance if available, otherwise get instance from element
        let modalInstance = this.modalInstance || bootstrap.Modal.getInstance(this.modal);
        
        if (modalInstance) {
            console.log('✅ Modal instance found, hiding...');
            modalInstance.hide();
        } else {
            console.warn('⚠️ No modal instance found to close');
            // Force hide by removing classes as fallback
            if (this.modal) {
                this.modal.classList.remove('show');
                this.modal.style.display = 'none';
                document.body.classList.remove('modal-open');
                
                // Remove backdrop
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
        }
    }

    // ========================================
    // DATA LOADING METHODS
    // ========================================

    async loadClients() {
        const clientSelect = document.getElementById('global_client_id');
        
        try {
            this.setLoading(clientSelect, true);
            
            const url = `${this.config.baseUrl}${this.config.apiEndpoints.clients}`;
            console.log('📊 Loading clients from URL:', url);
            
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            const clients = data.data || [];
            
            console.log('📊 DEBUG - Clients response:', data);
            console.log('📊 DEBUG - Clients count:', clients.length);
            
            clientSelect.innerHTML = '<option value="">Select client</option>';
            clients.forEach(client => {
                console.log('📊 DEBUG - Processing client:', client);
                const option = document.createElement('option');
                option.value = client.id;
                option.textContent = client.name;
                clientSelect.appendChild(option);
            });

        } catch (error) {
            console.error('Error loading clients:', error);
            this.showToast('Error loading clients', 'error');
        } finally {
            this.setLoading(clientSelect, false);
        }
    }

    async loadContacts(clientId) {
        const contactSelect = document.getElementById('global_contact_id');
        
        if (!clientId) {
            contactSelect.innerHTML = '<option value="">Select contact</option>';
            contactSelect.disabled = true;
            return;
        }

        try {
            this.setLoading(contactSelect, true);
            
            const response = await fetch(`${this.config.baseUrl}${this.config.apiEndpoints.contacts}${clientId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            const contacts = data.data || [];
            
            console.log('📊 DEBUG - Contacts response:', data);
            console.log('📊 DEBUG - Contacts count:', contacts.length);
            
            contactSelect.innerHTML = '<option value="">Select contact</option>';
            contacts.forEach(contact => {
                console.log('📊 DEBUG - Processing contact:', contact);
                const option = document.createElement('option');
                option.value = contact.id;
                option.textContent = `${contact.name || (contact.first_name + ' ' + contact.last_name)}`;
                if (contact.email) {
                    option.textContent += ` (${contact.email})`;
                }
                contactSelect.appendChild(option);
            });

            contactSelect.disabled = false;

        } catch (error) {
            console.error('❌ Error loading contacts:', error);
            console.error('URL used:', `${this.config.baseUrl}${this.config.apiEndpoints.contacts}${clientId}`);
            this.showToast('Error loading contacts', 'error');
        } finally {
            this.setLoading(contactSelect, false);
        }
    }

    async loadServices(clientId) {
        const serviceSelect = document.getElementById('global_service_id');
        
        if (!clientId) {
            serviceSelect.innerHTML = '<option value="">Select service</option>';
            serviceSelect.disabled = true;
            return;
        }

        try {
            this.setLoading(serviceSelect, true);
            
            const response = await fetch(`${this.config.baseUrl}${this.config.apiEndpoints.services}${clientId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            const services = data.data || [];
            
            console.log('📊 DEBUG - Services response:', data);
            console.log('📊 DEBUG - Services count:', services.length);
            
            serviceSelect.innerHTML = '<option value="">Select service</option>';
            services.forEach(service => {
                console.log('📊 DEBUG - Processing service:', service);
                const option = document.createElement('option');
                option.value = service.id;
                option.textContent = `${service.service_name || service.name}`;
                
                // Only show prices for admin and superadmin users
                const userType = window.currentUserInfo?.type || 'client';
                const canViewPrices = ['admin', 'superadmin'].includes(userType);
                
                if (canViewPrices && (service.service_price || service.price)) {
                    option.textContent += ` - $${service.service_price || service.price}`;
                }
                serviceSelect.appendChild(option);
            });

            serviceSelect.disabled = false;

        } catch (error) {
            console.error('❌ Error loading services:', error);
            console.error('URL used:', `${this.config.baseUrl}${this.config.apiEndpoints.services}${clientId}`);
            this.showToast('Error loading services', 'error');
        } finally {
            this.setLoading(serviceSelect, false);
        }
    }

    async loadOrderData(orderId) {
        try {
            const response = await fetch(`${this.config.baseUrl}${this.config.apiEndpoints.get}${orderId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const result = await response.json();
            
            if (result.success) {
                this.populateForm(result.data);
                this.updateModalTitle('Edit Sales Order');
            } else {
                throw new Error(result.message || 'Failed to load order data');
            }

        } catch (error) {
            console.error('Error loading order:', error);
            this.showToast('Error loading order data', 'error');
            this.close();
        }
    }

    // ========================================
    // EVENT HANDLERS
    // ========================================

    onModalShown() {
        // Focus first input
        const firstInput = this.form.querySelector('select:not([disabled]), input:not([readonly])');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }

        // Initialize icons
        this.initializeFeatherIcons();
        
        // Initialize datetime if not in edit mode
        if (!this.isEditMode) {
            this.dateTimeController.setDefaults();
        }
    }

    onModalHidden() {
        this.resetForm();
        this.isEditMode = false;
    }

    async onClientChange(clientId) {
        if (clientId) {
            await Promise.all([
                this.loadContacts(clientId),
                this.loadServices(clientId)
            ]);
        } else {
            this.loadContacts(null);
            this.loadServices(null);
        }
    }

    onVinInput(vin) {
        const vinStatus = document.getElementById('global_vin_status');
        
        if (vin.length === 0) {
            vinStatus.style.display = 'none';
            return;
        }
        
        // Show character count
        vinStatus.textContent = `${vin.length}/17 characters`;
        vinStatus.className = 'vin-status';
        vinStatus.style.display = 'inline-block';
        
        if (vin.length === 17) {
            this.decodeVin(vin);
        }
    }

    onVinBlur(vin) {
        if (vin.length === 17) {
            this.validateVin(vin);
        }
    }

    onServiceChange(serviceId) {
        // Could implement service-specific logic here
        console.log('Service changed to:', serviceId);
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        console.log('🚀 Form submission started');
        console.log('Event target:', e.target);
        console.log('Form element:', this.form);
        console.log('Is edit mode:', this.isEditMode);
        
        const submitBtn = document.getElementById('global_submit_btn');
        console.log('Submit button:', submitBtn);
        
        if (!this.validateForm()) {
            console.log('❌ Form validation failed');
            return;
        }
        
        console.log('✅ Form validation passed');

        const formData = new FormData(this.form);
        
        // Debug form data
        console.log('Form data entries:');
        for (const [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        try {
            this.setLoading(submitBtn, true);
            
            const url = `${this.config.baseUrl}${this.config.apiEndpoints.save}`;
            console.log('Submitting to URL:', url);

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();
            console.log('API Response:', result);

            if (result.success) {
                this.showToast(
                    this.isEditMode ? 'Order updated successfully!' : 'Order created successfully!',
                    'success'
                );
                
                setTimeout(() => {
                    this.close();
                    this.refreshTablesAndData();
                }, 800); // Faster close and refresh
            } else {
                this.showToast(result.message || 'Error saving order', 'error');
            }

        } catch (error) {
            console.error('Submit error:', error);
            this.showToast('Error submitting form', 'error');
        } finally {
            this.setLoading(submitBtn, false);
        }
    }

    // ========================================
    // VIN FUNCTIONALITY
    // ========================================

    async decodeVin(vin) {
        if (!window.VINDecoder) {
            console.warn('VIN Decoder not available');
            return;
        }

        const vinStatus = document.getElementById('global_vin_status');
        
        try {
            vinStatus.textContent = 'Decoding VIN...';
            vinStatus.className = 'vin-status vin-status-loading';
            vinStatus.style.display = 'inline-block';

            const result = await window.VINDecoder.decode(vin);
            
            if (result && result.vehicle) {
                const vehicleInput = document.getElementById('global_vehicle');
                vehicleInput.value = result.vehicle;
                vehicleInput.classList.add('success');
                
                vinStatus.textContent = 'VIN decoded successfully';
                vinStatus.className = 'vin-status vin-status-success';
                
                setTimeout(() => {
                    vinStatus.style.display = 'none';
                }, 3000);
            } else {
                vinStatus.textContent = 'VIN valid but no vehicle info found';
                vinStatus.className = 'vin-status vin-status-warning';
            }

        } catch (error) {
            console.error('VIN decode error:', error);
            vinStatus.textContent = 'Error decoding VIN';
            vinStatus.className = 'vin-status vin-status-error';
        }
    }

    validateVin(vin) {
        if (vin.length !== 17) {
            return false;
        }
        
        // Basic VIN validation
        const invalidChars = /[IOQ]/gi;
        if (invalidChars.test(vin)) {
            this.showVinStatus('VIN contains invalid characters (I, O, Q)', 'error');
            return false;
        }
        
        return true;
    }

    openVinScanner() {
        if (!window.VinBarcodeScanner) {
            this.showToast('VIN scanner not available', 'warning');
            return;
        }

        try {
            window.VinBarcodeScanner.open((scannedVin) => {
                document.getElementById('global_vin').value = scannedVin;
                this.onVinInput(scannedVin);
            });
        } catch (error) {
            console.error('Scanner error:', error);
            this.showToast('Error opening scanner', 'error');
        }
    }

    showVinStatus(message, type) {
        const vinStatus = document.getElementById('global_vin_status');
        vinStatus.textContent = message;
        vinStatus.className = `vin-status vin-status-${type}`;
        vinStatus.style.display = 'inline-block';
    }

    // ========================================
    // FORM UTILITIES
    // ========================================

    validateForm() {
        const requiredFields = this.form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        // Custom validations
        if (!this.dateTimeController.validate()) {
            isValid = false;
        }

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        }

        // VIN specific validation
        if (field.id === 'global_vin' && value && !this.validateVin(value)) {
            isValid = false;
        }

        this.setFieldError(field, isValid, errorMessage);
        return isValid;
    }

    setFieldError(field, isValid, errorMessage) {
        const errorElement = field.parentElement.querySelector('.form-error');
        
        if (isValid) {
            field.classList.remove('error');
            if (errorElement) errorElement.style.display = 'none';
        } else {
            field.classList.add('error');
            if (errorElement) {
                errorElement.textContent = errorMessage;
                errorElement.style.display = 'block';
            }
        }
    }

    populateForm(data) {
        console.log('🔄 Populating form with data:', data);
        
        // DEBUG: Show all data received
        console.log('📊 DEBUG - Full data object:');
        console.table(data);
        console.log('📊 Client name:', data.client_name);
        console.log('📊 Contact name:', data.contact_name);  
        console.log('📊 Service name:', data.service_name);
        console.log('📊 Stock field:', data.stock);
        if (data.stock === null || data.stock === '') {
            console.log('📊 ℹ️ Stock Number is empty in database for this order');
        }
        console.log('📊 All data keys:', Object.keys(data));
        
        // Map database fields to form fields
        const fieldMap = {
            'id': 'global_order_id',
            'client_id': 'global_client_id',
            'contact_id': 'global_contact_id',
            'service_id': 'global_service_id',
            'stock': 'global_stock',
            'vin': 'global_vin',
            'vehicle': 'global_vehicle',
            'date': 'global_date',
            'time': 'global_time',
            'instructions': 'global_instructions',
            'status': 'global_status',
            'salesperson_id': 'global_salesperson_id'
        };

        // Populate fields using the mapping
        Object.keys(fieldMap).forEach(dbField => {
            const formFieldId = fieldMap[dbField];
            const field = document.getElementById(formFieldId);
            
            if (field && data.hasOwnProperty(dbField)) {
                field.value = data[dbField] || '';
                console.log(`✅ Populated ${formFieldId} with:`, data[dbField]);
            }
        });
        
        // Special handling for dependent dropdowns
        const clientSelect = document.getElementById('global_client_id');
        const contactSelect = document.getElementById('global_contact_id');
        const serviceSelect = document.getElementById('global_service_id');
        
        // Load client first, then contacts and services
        if (data.client_id) {
            // First, load all clients to populate the select
            this.loadClients().then(() => {
                clientSelect.value = data.client_id;
                console.log('🔄 Client selected:', data.client_id);
                
                // Verify client selection worked
                const selectedClientOption = clientSelect.querySelector(`option[value="${data.client_id}"]`);
                if (selectedClientOption) {
                    console.log('✅ Client option found and selected:', selectedClientOption.textContent);
                } else {
                    console.warn('⚠️ Client option not found, current client options:');
                    const options = Array.from(clientSelect.options).map(opt => ({value: opt.value, text: opt.textContent}));
                    console.table(options);
                }
                
                // Then load dependent data and wait for completion
                this.loadDependentDataForEdit(data);
            });
        }
    }

    async loadDependentDataForEdit(data) {
        try {
            console.log('🔄 Loading dependent data for edit mode...');
            
            // Load contacts and services simultaneously
            await Promise.all([
                this.loadContacts(data.client_id),
                this.loadServices(data.client_id)
            ]);
            
            console.log('✅ Dependent data loaded, setting selected values...');
            
            // Set the selected values after data is loaded
            const contactSelect = document.getElementById('global_contact_id');
            const serviceSelect = document.getElementById('global_service_id');
            
            // Use a small delay to ensure DOM updates are complete
            setTimeout(() => {
                if (data.contact_id) {
                    contactSelect.value = data.contact_id;
                    console.log('✅ Contact selected:', data.contact_id);
                    
                    // Verify the option exists
                    const contactOption = contactSelect.querySelector(`option[value="${data.contact_id}"]`);
                    if (!contactOption) {
                        console.warn('⚠️ Contact option not found, adding manually');
                        let contactName = '';
                        if (data.contact_name && data.contact_name !== 'undefined') {
                            contactName = data.contact_name;
                        } else if (data.contact_first_name && data.contact_last_name) {
                            contactName = `${data.contact_first_name} ${data.contact_last_name}`;
                        } else {
                            contactName = `Contact ${data.contact_id}`;
                        }
                        this.addMissingOption(contactSelect, data.contact_id, contactName);
                    }
                }
                
                if (data.service_id) {
                    serviceSelect.value = data.service_id;
                    console.log('✅ Service selected:', data.service_id);
                    
                    // Verify the option exists
                    const serviceOption = serviceSelect.querySelector(`option[value="${data.service_id}"]`);
                    if (!serviceOption) {
                        console.warn('⚠️ Service option not found, adding manually');
                        let serviceName = '';
                        if (data.service_name && data.service_name !== 'undefined') {
                            serviceName = data.service_name;
                        } else if (data.service_title && data.service_title !== 'undefined') {
                            serviceName = data.service_title;
                        } else {
                            serviceName = `Service ${data.service_id}`;
                        }
                        this.addMissingOption(serviceSelect, data.service_id, serviceName);
                    }
                }
            }, 100);
            
        } catch (error) {
            console.error('❌ Error loading dependent data for edit:', error);
            this.showToast('Error loading contact and service data', 'error');
        }
    }

    addMissingOption(selectElement, value, text) {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        option.selected = true;
        selectElement.appendChild(option);
        selectElement.disabled = false;
    }

    resetForm() {
        this.form.reset();
        
        // Clear error states
        this.form.querySelectorAll('.error').forEach(field => {
            field.classList.remove('error');
        });
        
        this.form.querySelectorAll('.form-error').forEach(error => {
            error.style.display = 'none';
        });

        // Reset dependent dropdowns
        document.getElementById('global_contact_id').disabled = true;
        document.getElementById('global_service_id').disabled = true;
        
        // Hide VIN status
        document.getElementById('global_vin_status').style.display = 'none';
        
        // Reset datetime
        if (this.dateTimeController) {
            this.dateTimeController.reset();
        }
        
        this.updateModalTitle('Create Sales Order');
    }

    updateModalTitle(title) {
        document.getElementById('modal-title-text').textContent = title;
        document.getElementById('global_submit_text').textContent = 
            title.includes('Edit') ? 'Update Order' : 'Create Order';
    }

    // ========================================
    // UI UTILITIES
    // ========================================

    setLoading(element, loading) {
        if (loading) {
            element.classList.add('loading');
            element.disabled = true;
        } else {
            element.classList.remove('loading');
            element.disabled = false;
        }
    }

    showToast(message, type = 'info') {
        // Use existing toast system or create a simple one
        if (typeof showToast === 'function') {
            showToast(type, message);
        } else if (typeof Toastify === 'object') {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: this.getToastColor(type)
            }).showToast();
        } else {
            // Fallback to alert
            alert(message);
        }
    }

    getToastColor(type) {
        const colors = {
            success: "#10b981",
            error: "#ef4444",
            warning: "#f59e0b",
            info: "#3b82f6"
        };
        return colors[type] || colors.info;
    }

    // ========================================
    // TABLE REFRESH METHODS
    // ========================================

    refreshTablesAndData() {
        console.log('🔄 Refreshing tables and data...');
        
        // Try multiple refresh methods in order of preference
        const refreshMethods = [
            () => this.refreshDataTable(),
            () => this.refreshBootstrapTable(),
            () => this.refreshCustomTable(),
            () => this.refreshPageData(),
            () => this.fallbackRefresh()
        ];

        // Execute the first available method
        for (const method of refreshMethods) {
            try {
                if (method()) {
                    console.log('✅ Table refresh successful');
                    return;
                }
            } catch (error) {
                console.log('⚠️ Refresh method failed, trying next...', error.message);
            }
        }
    }

    refreshDataTable() {
        // For DataTables - try Sales Orders specific selectors first
        if (typeof $.fn.DataTable !== 'undefined') {
            const specificSelectors = [
                '#service-table',      // Sales Orders specific table
                '#sales-orders-table', 
                '#orders-table',
                '.dataTable'
            ];

            for (const selector of specificSelectors) {
                const $table = $(selector);
                if ($table.length > 0 && $.fn.DataTable.isDataTable($table)) {
                    console.log(`🔄 Refreshing DataTable: ${selector}...`);
                    $table.DataTable().ajax.reload(null, false); // Don't reset paging
                    return true;
                }
            }

            // Try generic DataTable refresh
            const $allTables = $('.dataTable');
            if ($allTables.length > 0) {
                $allTables.each(function() {
                    if ($.fn.DataTable.isDataTable(this)) {
                        console.log('🔄 Refreshing DataTable...');
                        $(this).DataTable().ajax.reload(null, false);
                    }
                });
                return true;
            }

            // Alternative global table refresh
            if (window.table && window.table.ajax) {
                console.log('🔄 Refreshing global DataTable...');
                window.table.ajax.reload(null, false);
                return true;
            }
        }
        return false;
    }

    refreshBootstrapTable() {
        // For Bootstrap Table
        if (typeof $.fn.bootstrapTable !== 'undefined') {
            const $table = $('[data-toggle="table"], .bootstrap-table table');
            if ($table.length > 0) {
                console.log('🔄 Refreshing Bootstrap Table...');
                $table.bootstrapTable('refresh');
                return true;
            }
        }
        return false;
    }

    refreshCustomTable() {
        // First try registered page-specific refresh functions
        if (window.globalModalRefreshRegistry && window.globalModalRefreshRegistry.length > 0) {
            console.log('🔄 Trying registered refresh functions...');
            for (const registration of window.globalModalRefreshRegistry) {
                try {
                    registration.fn();
                    console.log('✅ Page-specific refresh function executed');
                    return true;
                } catch (error) {
                    console.log('⚠️ Registered refresh function failed:', error.message);
                }
            }
        }

        // Look for common refresh functions - Sales Orders specific first
        const refreshFunctions = [
            'refreshAllTables',     // Sales Orders specific
            'refreshTable',
            'reloadTable', 
            'updateTable',
            'loadTableData',
            'refreshSalesOrders',
            'refreshOrders',
            'refreshDashboard',     // Dashboard specific
            'loadData'
        ];

        for (const funcName of refreshFunctions) {
            if (typeof window[funcName] === 'function') {
                console.log(`🔄 Calling ${funcName}()...`);
                window[funcName]();
                return true;
            }
        }

        // Try specific table instances
        const tableInstances = [
            'serviceTable',         // Sales Orders specific
            'table',
            'dataTable',
            'mainTable'
        ];

        for (const tableName of tableInstances) {
            if (window[tableName] && window[tableName].ajax) {
                console.log(`🔄 Refreshing ${tableName} via ajax.reload()...`);
                window[tableName].ajax.reload(null, false);
                return true;
            }
        }

        return false;
    }

    refreshPageData() {
        // Try to refresh specific page content
        const contentSelectors = [
            '#page-content',
            '.page-content', 
            '#main-content',
            '.main-content',
            '#content-wrapper',
            '.content-wrapper'
        ];

        for (const selector of contentSelectors) {
            const element = document.querySelector(selector);
            if (element && element.dataset.refreshUrl) {
                console.log(`🔄 Refreshing content via AJAX: ${selector}...`);
                this.refreshElementContent(element, element.dataset.refreshUrl);
                return true;
            }
        }
        return false;
    }

    async refreshElementContent(element, url) {
        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (response.ok) {
                const html = await response.text();
                element.innerHTML = html;
                
                // Re-initialize any scripts if needed
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        } catch (error) {
            console.error('Error refreshing content:', error);
        }
    }

    fallbackRefresh() {
        // Last resort - dispatch custom events for manual handling
        console.log('🔄 Dispatching custom refresh events...');
        
        const events = [
            'orderCreated',
            'orderUpdated', 
            'tableRefresh',
            'dataRefresh'
        ];

        events.forEach(eventName => {
            const event = new CustomEvent(eventName, {
                detail: { 
                    isEditMode: this.isEditMode,
                    source: 'GlobalSalesOrderModal'
                }
            });
            document.dispatchEvent(event);
            window.dispatchEvent(event);
        });

        // Try window.location.hash update to trigger refresh
        if (window.location.hash) {
            const currentHash = window.location.hash;
            window.location.hash = '';
            setTimeout(() => {
                window.location.hash = currentHash;
            }, 100);
            return true;
        }

        // Final fallback - partial page reload if absolutely necessary
        console.log('⚠️ Using fallback: partial page reload');
        setTimeout(() => {
            window.location.reload();
        }, 2000);
        
        return true;
    }
}

/**
 * ============================================================================
 * DATE TIME CONTROLLER - Advanced date/time management
 * ============================================================================
 */

class DateTimeController {
    constructor(modal) {
        this.modal = modal;
        this.dateInput = document.getElementById('global_date');
        this.timeSelect = document.getElementById('global_time');
        this.warningDiv = document.getElementById('global_datetime_warning');
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.populateTimeOptions();
    }

    bindEvents() {
        this.dateInput.addEventListener('change', () => {
            this.populateTimeOptions();
            this.validate();
        });

        this.timeSelect.addEventListener('change', () => {
            this.validate();
        });
    }

    setDefaults() {
        const userType = window.currentUserInfo?.type || 'client';
        const isSuperAdmin = window.currentUserInfo?.isSuperAdmin === true;
        const today = new Date().toISOString().split('T')[0];
        
        if (!this.modal.isEditMode) {
            // Create mode: Set default date to today
            this.dateInput.value = today;
            
            // Set minimum date restriction based on user type
            if (isSuperAdmin) {
                // SuperAdmin: no restrictions
                this.dateInput.removeAttribute('min');
            } else {
                // All other users: can only create future orders
                this.dateInput.min = today;
            }
        } else {
            // Edit mode: set restrictions based on user type
            if (isSuperAdmin) {
                // SuperAdmin: no restrictions
                this.dateInput.removeAttribute('min');
            } else if (userType === 'staff' || userType === 'admin') {
                // Staff/Admin: can edit to past dates
                this.dateInput.removeAttribute('min');
            } else {
                // Contact users: can only edit to future dates
                this.dateInput.min = today;
            }
        }
        
        if (!this.modal.isEditMode) {
            // Set default time for create mode
            const defaultTime = this.calculateNextAvailableTime();
            this.timeSelect.value = defaultTime;
        }
        
        this.populateTimeOptions();
    }

    calculateNextAvailableTime() {
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinutes = now.getMinutes();
        
        // Calculate next available hour
        let nextHour;
        if (currentMinutes > 0) {
            // If current time has minutes (e.g., 9:30), round up to next hour + 1 (11:00)
            nextHour = currentHour + 2;
        } else {
            // If exact hour (e.g., 9:00), add 1 hour (10:00)
            nextHour = currentHour + 1;
        }
        
        // Ensure within business hours
        const { start, end } = this.modal.config.businessHours;
        if (nextHour < start) nextHour = start;
        if (nextHour > end) nextHour = start; // Next day
        
        return `${String(nextHour).padStart(2, '0')}:00`;
    }

    populateTimeOptions() {
        const selectedDate = this.dateInput.value;
        const isToday = this.isToday(selectedDate);
        const userType = window.currentUserInfo?.type || 'client';
        const isSuperAdmin = window.currentUserInfo?.isSuperAdmin === true;
        const minHour = isToday ? this.getMinimumHour() : this.modal.config.businessHours.start;
        
        // Clear existing options
        this.timeSelect.innerHTML = '<option value="">Select time</option>';
        
        const { start, end, interval } = this.modal.config.businessHours;
        
        // Determine hour range based on user type
        let hourStart, hourEnd;
        if (isSuperAdmin) {
            // SuperAdmin: all hours available
            hourStart = 0;
            hourEnd = 23;
        } else if ((userType === 'staff' || userType === 'admin') && this.modal.isEditMode) {
            // Staff/Admin in edit mode: all business hours available (can edit past dates)
            hourStart = start;
            hourEnd = end;
        } else {
            // Contact users or create mode: business hours with restrictions
            hourStart = start;
            hourEnd = end;
        }
        
        for (let hour = hourStart; hour <= hourEnd; hour += interval) {
            const option = document.createElement('option');
            const timeValue = `${String(hour).padStart(2, '0')}:00`;
            const timeLabel = this.format12Hour(hour);
            
            option.value = timeValue;
            option.textContent = timeLabel;
            
            // Apply restrictions based on user type
            let shouldDisable = false;
            if (isSuperAdmin) {
                // SuperAdmin: never disable
                shouldDisable = false;
            } else if ((userType === 'staff' || userType === 'admin') && this.modal.isEditMode) {
                // Staff/Admin editing: never disable (can edit past times)
                shouldDisable = false;
            } else {
                // Contact users or create mode: disable past hours for today
                shouldDisable = isToday && hour < minHour;
            }
            
            if (shouldDisable) {
                option.disabled = true;
                option.textContent += ' (Not available)';
                option.className = 'text-muted';
            }
            
            this.timeSelect.appendChild(option);
        }
    }

    getMinimumHour() {
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinutes = now.getMinutes();
        const advanceHours = this.modal.config.validation.minAdvanceHours;
        
        let minHour = currentHour + advanceHours;
        if (currentMinutes > 0) {
            minHour += 1; // Round up to next hour
        }
        
        return Math.min(minHour, this.modal.config.businessHours.end);
    }

    format12Hour(hour) {
        if (hour === 0) return '12:00 AM';
        if (hour === 12) return '12:00 PM';
        if (hour < 12) return `${hour}:00 AM`;
        return `${hour - 12}:00 PM`;
    }

    isToday(dateString) {
        const today = new Date().toISOString().split('T')[0];
        return dateString === today;
    }

    validate() {
        const selectedDate = this.dateInput.value;
        const selectedTime = this.timeSelect.value;
        
        this.hideWarning();
        
        if (!selectedDate || !selectedTime) {
            return true; // No validation needed if fields are empty
        }
        
        // Apply validation based on user type and mode
        const userType = window.currentUserInfo?.type || 'client';
        const isSuperAdmin = window.currentUserInfo?.isSuperAdmin === true;
        
        if (isSuperAdmin) {
            console.log('⏰ Skipping date/time validation for superadmin (allow all)');
            return true;
        }
        
        if (this.modal.isEditMode) {
            if (userType === 'staff' || userType === 'admin') {
                console.log('⏰ Skipping date/time validation for edit mode (staff/admin can edit past dates)');
                return true;
            } else {
                console.log('⏰ Edit mode detected but user is contact - applying future-only validation');
                // Contact users can only edit to future dates, continue with validation
            }
        }
        
        const selectedDateTime = new Date(`${selectedDate}T${selectedTime}`);
        const now = new Date();
        const minDateTime = new Date(now.getTime() + (this.modal.config.validation.minAdvanceHours * 60 * 60 * 1000));
        
        if (selectedDateTime < minDateTime) {
            this.showWarning('Order must be scheduled at least 1 hour in advance');
            return false;
        }
        
        // Check business hours
        const hour = selectedDateTime.getHours();
        const { start, end } = this.modal.config.businessHours;
        if (hour < start || hour > end) {
            this.showWarning(`Business hours are ${this.format12Hour(start)} to ${this.format12Hour(end)}`);
            return false;
        }
        
        return true;
    }

    showWarning(message) {
        this.warningDiv.querySelector('span').textContent = message;
        this.warningDiv.style.display = 'flex';
        
        // Re-initialize feather icons for the warning icon
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    hideWarning() {
        this.warningDiv.style.display = 'none';
    }

    reset() {
        this.hideWarning();
        if (!this.modal.isEditMode) {
            this.setDefaults();
        }
    }
}

// ============================================================================
// GLOBAL FUNCTIONS FOR BACKWARD COMPATIBILITY
// ============================================================================

// Initialize the modal when DOM is ready with resilient loading
document.addEventListener('DOMContentLoaded', function() {
    // Try to initialize immediately
    initGlobalSalesOrderModal();
    
    // Also try again after a delay in case elements load later
    setTimeout(initGlobalSalesOrderModal, 1000);
});

function initGlobalSalesOrderModal() {
    if (window.globalSalesOrderModal) {
        console.log('✅ Global Sales Order Modal already initialized');
        return;
    }
    
    if (document.getElementById('global-sales-order-modal')) {
        try {
            window.globalSalesOrderModal = new GlobalSalesOrderModal();
            console.log('✅ Global Sales Order Modal initialized successfully');
        } catch (error) {
            console.error('❌ Error initializing Global Sales Order Modal:', error);
        }
    } else {
        console.log('⏳ Modal element not found, waiting...');
    }
}

// Global functions with fallback initialization
function openGlobalSalesOrderModal() {
    if (window.globalSalesOrderModal) {
        window.globalSalesOrderModal.open();
    } else {
        console.log('🔄 Modal not initialized, trying to initialize...');
        initGlobalSalesOrderModal();
        
        if (window.globalSalesOrderModal) {
            window.globalSalesOrderModal.open();
        } else {
            console.error('❌ Could not initialize Global Sales Order Modal');
            showError('Modal Error', 'Could not open sales order modal');
        }
    }
}

function editGlobalSalesOrder(orderId) {
    if (window.globalSalesOrderModal) {
        window.globalSalesOrderModal.open(orderId);
    } else {
        console.log('🔄 Modal not initialized, trying to initialize...');
        initGlobalSalesOrderModal();
        
        if (window.globalSalesOrderModal) {
            window.globalSalesOrderModal.open(orderId);
        } else {
            console.error('❌ Could not initialize Global Sales Order Modal');
            showError('Modal Error', 'Could not open edit modal');
        }
    }
}

// Global function for DataTables to use
window.openEditModal = function(orderId) {
    console.log('🔄 Opening edit modal for order ID:', orderId);
    if (window.globalSalesOrderModal) {
        window.globalSalesOrderModal.open(orderId);
    } else if (typeof GlobalSalesOrderModal !== 'undefined') {
        const modal = new GlobalSalesOrderModal();
        modal.open(orderId);
    } else {
        console.error('❌ GlobalSalesOrderModal not available');
        alert('Edit modal is not available. Please refresh the page.');
    }
}

// ============================================================================
// PAGE-SPECIFIC REFRESH REGISTRATION
// ============================================================================

// Global registry for page-specific refresh functions
window.globalModalRefreshRegistry = window.globalModalRefreshRegistry || [];

// Function to register page-specific refresh methods
function registerGlobalModalRefresh(refreshFunction, priority = 50) {
    if (typeof refreshFunction === 'function') {
        window.globalModalRefreshRegistry.push({
            fn: refreshFunction,
            priority: priority
        });
        // Sort by priority (higher = first)
        window.globalModalRefreshRegistry.sort((a, b) => b.priority - a.priority);
        console.log('📝 Registered global modal refresh function');
    }
}

// Make functions available globally
window.registerGlobalModalRefresh = registerGlobalModalRefresh;

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { GlobalSalesOrderModal, DateTimeController, registerGlobalModalRefresh };
}