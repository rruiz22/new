<!-- ============================================ -->
<!-- OPTIMIZED SALES ORDER MODAL -->
<!-- Clean, Fast, and Responsive Implementation -->
<!-- ============================================ -->

<style>
/* Modern Modal Styling */
.sales-order-modal {
    --modal-border-radius: 12px;
    --modal-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-600: #4b5563;
    --gray-900: #111827;
}

.sales-order-modal .modal-dialog {
    max-width: 1000px;
    margin: 1rem auto;
}

.sales-order-modal .modal-content {
    border: none;
    border-radius: var(--modal-border-radius);
    box-shadow: var(--modal-shadow);
    overflow: hidden;
}

.sales-order-modal .modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
    color: white;
    border: none;
    padding: 1.5rem 2rem;
}

.sales-order-modal .modal-title {
    font-weight: 600;
    font-size: 1.25rem;
    margin: 0;
}

.sales-order-modal .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.sales-order-modal .btn-close:hover {
    opacity: 1;
}

/* Stepped Form Navigation */
.step-navigation {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
    padding: 0;
    margin: 0;
}

.step-item {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    color: var(--gray-600);
    text-decoration: none;
    border-right: 1px solid var(--gray-200);
    position: relative;
    transition: all 0.2s ease;
    min-height: 80px;
}

.step-item:last-child {
    border-right: none;
}

.step-item.active {
    background: white;
    color: var(--primary-color);
    font-weight: 600;
}

.step-item.completed {
    color: var(--success-color);
}

.step-item::before {
    content: attr(data-step);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--gray-200);
    color: var(--gray-600);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    margin-right: 0.75rem;
    transition: all 0.2s ease;
}

.step-item.active::before {
    background: var(--primary-color);
    color: white;
}

.step-item.completed::before {
    background: var(--success-color);
    color: white;
    content: '✓';
}

/* Form Styling */
.form-step {
    display: none;
    padding: 2rem;
}

.form-step.active {
    display: block;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
}

.form-label .required {
    color: var(--danger-color);
    margin-left: 0.25rem;
}

.form-label .icon {
    width: 16px;
    height: 16px;
    margin-right: 0.5rem;
    opacity: 0.7;
}

.form-control, .form-select {
    border: 2px solid var(--gray-200);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: white;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

.form-control:disabled, .form-select:disabled {
    background: var(--gray-50);
    border-color: var(--gray-200);
    color: var(--gray-600);
    cursor: not-allowed;
}

.form-text {
    font-size: 0.75rem;
    color: var(--gray-600);
    margin-top: 0.25rem;
}

.form-error {
    color: var(--danger-color);
    font-size: 0.75rem;
    margin-top: 0.25rem;
    display: none;
}

.form-control.error, .form-select.error {
    border-color: var(--danger-color);
}

.form-control.error + .form-error,
.form-select.error + .form-error {
    display: block;
}

/* Loading States */
.loading {
    position: relative;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Footer Actions */
.modal-footer {
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.625rem 1.25rem;
    transition: all 0.2s ease;
}

.btn-primary {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-success {
    background: var(--success-color);
    border-color: var(--success-color);
}

/* Responsive Design */
@media (max-width: 768px) {
    .sales-order-modal .modal-dialog {
        margin: 0;
        max-width: 100%;
        height: 100vh;
    }
    
    .sales-order-modal .modal-content {
        height: 100vh;
        border-radius: 0;
    }
    
    .step-navigation {
        flex-direction: column;
    }
    
    .step-item {
        border-right: none;
        border-bottom: 1px solid var(--gray-200);
        min-height: 60px;
        padding: 1rem;
    }
    
    .step-item:last-child {
        border-bottom: none;
    }
    
    .form-step {
        padding: 1.5rem;
    }
}

/* Success Animation */
@keyframes checkmark {
    0% {
        height: 0;
        width: 0;
        opacity: 1;
    }
    20% {
        height: 0;
        width: 7px;
        opacity: 1;
    }
    40% {
        height: 12px;
        width: 7px;
        opacity: 1;
    }
    100% {
        height: 12px;
        width: 7px;
        opacity: 1;
    }
}
</style>

<div class="modal fade sales-order-modal" id="salesOrderModal" tabindex="-1" aria-labelledby="salesOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="salesOrderModalLabel">
                    <span id="modalTitle"><?= lang('App.create_sales_order') ?></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation d-flex">
                <a href="#" class="step-item active" data-step="1" data-target="basic-info">
                    <span><?= lang('App.basic_information') ?></span>
                </a>
                <a href="#" class="step-item" data-step="2" data-target="service-details">
                    <span><?= lang('App.service_details') ?></span>
                </a>
                <a href="#" class="step-item" data-step="3" data-target="scheduling">
                    <span><?= lang('App.scheduling') ?></span>
                </a>
                <a href="#" class="step-item" data-step="4" data-target="review">
                    <span><?= lang('App.review') ?></span>
                </a>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-0">
                <form id="salesOrderForm" novalidate>
                    <input type="hidden" id="order_id" name="id">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                    <!-- Step 1: Basic Information -->
                    <div class="form-step active" id="step-basic-info">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_id" class="form-label">
                                        <i data-feather="home" class="icon"></i>
                                        <?= lang('App.client') ?><span class="required">*</span>
                                    </label>
                                    <select class="form-select" id="client_id" name="client_id" required>
                                        <option value=""><?= lang('App.select_client') ?></option>
                                        <?php if (isset($clients)): ?>
                                            <?php foreach ($clients as $client): ?>
                                                <option value="<?= $client['id'] ?>"><?= esc($client['name']) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <div class="form-error"><?= lang('App.client_required') ?></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_id" class="form-label">
                                        <i data-feather="user" class="icon"></i>
                                        <?= lang('App.contact') ?>
                                    </label>
                                    <select class="form-select" id="contact_id" name="contact_id" disabled>
                                        <option value=""><?= lang('App.select_contact') ?></option>
                                    </select>
                                    <div class="form-text"><?= lang('App.select_client_first') ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vehicle" class="form-label">
                                        <i data-feather="truck" class="icon"></i>
                                        <?= lang('App.vehicle') ?>
                                    </label>
                                    <input type="text" class="form-control" id="vehicle" name="vehicle" 
                                           placeholder="<?= lang('App.enter_vehicle_info') ?>">
                                    <div class="form-text"><?= lang('App.year_make_model') ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vin" class="form-label">
                                        <i data-feather="hash" class="icon"></i>
                                        <?= lang('App.vin') ?>
                                    </label>
                                    <input type="text" class="form-control" id="vin" name="vin" 
                                           placeholder="<?= lang('App.enter_vin') ?>" maxlength="17">
                                    <div class="form-text"><?= lang('App.vin_17_characters') ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock" class="form-label">
                                        <i data-feather="tag" class="icon"></i>
                                        <?= lang('App.stock_number') ?>
                                    </label>
                                    <input type="text" class="form-control" id="stock" name="stock" 
                                           placeholder="<?= lang('App.enter_stock_number') ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">
                                        <i data-feather="activity" class="icon"></i>
                                        <?= lang('App.status') ?><span class="required">*</span>
                                    </label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="pending" selected><?= lang('App.pending') ?></option>
                                        <option value="in_progress"><?= lang('App.in_progress') ?></option>
                                        <option value="completed"><?= lang('App.completed') ?></option>
                                    </select>
                                    <div class="form-error"><?= lang('App.status_required') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Service Details -->
                    <div class="form-step" id="step-service-details">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="service_id" class="form-label">
                                        <i data-feather="tool" class="icon"></i>
                                        <?= lang('App.primary_service') ?><span class="required">*</span>
                                    </label>
                                    <select class="form-select" id="service_id" name="service_id" required disabled>
                                        <option value=""><?= lang('App.select_service') ?></option>
                                    </select>
                                    <div class="form-text"><?= lang('App.services_loaded_after_client') ?></div>
                                    <div class="form-error"><?= lang('App.service_required') ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_price" class="form-label">
                                        <i data-feather="dollar-sign" class="icon"></i>
                                        <?= lang('App.service_price') ?>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="service_price" name="service_price" 
                                               step="0.01" min="0" readonly>
                                    </div>
                                    <div class="form-text"><?= lang('App.automatically_loaded') ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority" class="form-label">
                                        <i data-feather="flag" class="icon"></i>
                                        <?= lang('App.priority') ?>
                                    </label>
                                    <select class="form-select" id="priority" name="priority">
                                        <option value="normal" selected><?= lang('App.normal') ?></option>
                                        <option value="high"><?= lang('App.high') ?></option>
                                        <option value="urgent"><?= lang('App.urgent') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="instructions" class="form-label">
                                        <i data-feather="file-text" class="icon"></i>
                                        <?= lang('App.special_instructions') ?>
                                    </label>
                                    <textarea class="form-control" id="instructions" name="instructions" rows="3"
                                              placeholder="<?= lang('App.enter_special_instructions') ?>"></textarea>
                                    <div class="form-text"><?= lang('App.visible_to_service_team') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Scheduling -->
                    <div class="form-step" id="step-scheduling">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scheduled_date" class="form-label">
                                        <i data-feather="calendar" class="icon"></i>
                                        <?= lang('App.scheduled_date') ?>
                                    </label>
                                    <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" 
                                           min="<?= date('Y-m-d') ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scheduled_time" class="form-label">
                                        <i data-feather="clock" class="icon"></i>
                                        <?= lang('App.scheduled_time') ?>
                                    </label>
                                    <input type="time" class="form-control" id="scheduled_time" name="scheduled_time">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estimated_duration" class="form-label">
                                        <i data-feather="timer" class="icon"></i>
                                        <?= lang('App.estimated_duration') ?>
                                    </label>
                                    <select class="form-select" id="estimated_duration" name="estimated_duration">
                                        <option value=""><?= lang('App.select_duration') ?></option>
                                        <option value="30"><?= lang('App.30_minutes') ?></option>
                                        <option value="60"><?= lang('App.1_hour') ?></option>
                                        <option value="120"><?= lang('App.2_hours') ?></option>
                                        <option value="240"><?= lang('App.4_hours') ?></option>
                                        <option value="480"><?= lang('App.full_day') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="service_bay" class="form-label">
                                        <i data-feather="map-pin" class="icon"></i>
                                        <?= lang('App.service_bay') ?>
                                    </label>
                                    <select class="form-select" id="service_bay" name="service_bay">
                                        <option value=""><?= lang('App.select_bay') ?></option>
                                        <option value="bay_1"><?= lang('App.bay_1') ?></option>
                                        <option value="bay_2"><?= lang('App.bay_2') ?></option>
                                        <option value="bay_3"><?= lang('App.bay_3') ?></option>
                                        <option value="bay_4"><?= lang('App.bay_4') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes" class="form-label">
                                        <i data-feather="edit-3" class="icon"></i>
                                        <?= lang('App.internal_notes') ?>
                                    </label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"
                                              placeholder="<?= lang('App.internal_notes_placeholder') ?>"></textarea>
                                    <div class="form-text"><?= lang('App.internal_only') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Review -->
                    <div class="form-step" id="step-review">
                        <div id="order-summary">
                            <h6 class="mb-3"><?= lang('App.order_summary') ?></h6>
                            <div id="summary-content">
                                <!-- Summary will be populated by JS -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <div>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i data-feather="x" class="me-1" style="width: 16px; height: 16px;"></i>
                        <?= lang('App.cancel') ?>
                    </button>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" id="prevStepBtn" style="display: none;">
                        <i data-feather="chevron-left" class="me-1" style="width: 16px; height: 16px;"></i>
                        <?= lang('App.previous') ?>
                    </button>
                    <button type="button" class="btn btn-primary" id="nextStepBtn">
                        <?= lang('App.next') ?>
                        <i data-feather="chevron-right" class="ms-1" style="width: 16px; height: 16px;"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                        <i data-feather="check" class="me-1" style="width: 16px; height: 16px;"></i>
                        <span id="submitBtnText"><?= lang('App.create_order') ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ============================================
// OPTIMIZED SALES ORDER MODAL HANDLER
// Clean, performant, and maintainable
// ============================================

class SalesOrderModal {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 4;
        this.formData = {};
        this.isEditing = false;
        this.modal = null;
        
        this.init();
    }

    init() {
        this.modal = document.getElementById('salesOrderModal');
        this.bindEvents();
        this.initializeFeatherIcons();
    }

    bindEvents() {
        // Step navigation
        document.querySelectorAll('.step-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const step = parseInt(item.dataset.step);
                if (step <= this.currentStep || item.classList.contains('completed')) {
                    this.goToStep(step);
                }
            });
        });

        // Form navigation buttons
        document.getElementById('nextStepBtn').addEventListener('click', () => this.nextStep());
        document.getElementById('prevStepBtn').addEventListener('click', () => this.prevStep());
        document.getElementById('submitBtn').addEventListener('click', () => this.submitForm());

        // Client change handler
        document.getElementById('client_id').addEventListener('change', (e) => {
            this.loadClientContacts(e.target.value);
            this.loadClientServices(e.target.value);
        });

        // Service change handler
        document.getElementById('service_id').addEventListener('change', (e) => {
            this.loadServicePrice(e.target.value);
        });

        // Form validation on input
        this.modal.querySelectorAll('input[required], select[required]').forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => this.clearFieldError(field));
        });

        // Modal events
        this.modal.addEventListener('shown.bs.modal', () => this.onModalShown());
        this.modal.addEventListener('hidden.bs.modal', () => this.onModalHidden());
    }

    initializeFeatherIcons() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    goToStep(step) {
        if (step < 1 || step > this.totalSteps) return;

        // Validate current step before moving forward
        if (step > this.currentStep && !this.validateCurrentStep()) {
            return;
        }

        // Hide current step
        document.querySelector('.form-step.active').classList.remove('active');
        document.querySelector('.step-item.active').classList.remove('active');

        // Show target step
        document.getElementById(`step-${this.getStepName(step)}`).classList.add('active');
        document.querySelector(`.step-item[data-step="${step}"]`).classList.add('active');

        // Mark completed steps
        for (let i = 1; i < step; i++) {
            document.querySelector(`.step-item[data-step="${i}"]`).classList.add('completed');
        }

        this.currentStep = step;
        this.updateNavigationButtons();

        // Special handling for review step
        if (step === 4) {
            this.generateOrderSummary();
        }
    }

    getStepName(step) {
        const stepNames = ['', 'basic-info', 'service-details', 'scheduling', 'review'];
        return stepNames[step];
    }

    nextStep() {
        if (this.currentStep < this.totalSteps) {
            this.goToStep(this.currentStep + 1);
        }
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.goToStep(this.currentStep - 1);
        }
    }

    updateNavigationButtons() {
        const prevBtn = document.getElementById('prevStepBtn');
        const nextBtn = document.getElementById('nextStepBtn');
        const submitBtn = document.getElementById('submitBtn');

        // Show/hide previous button
        prevBtn.style.display = this.currentStep > 1 ? 'inline-flex' : 'none';

        // Show/hide next/submit buttons
        if (this.currentStep === this.totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-flex';
        } else {
            nextBtn.style.display = 'inline-flex';
            submitBtn.style.display = 'none';
        }
    }

    validateCurrentStep() {
        const currentStepElement = document.querySelector('.form-step.active');
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = field.getAttribute('data-error') || 'This field is required';
        }

        // VIN validation
        if (field.id === 'vin' && value && value.length !== 17) {
            isValid = false;
            errorMessage = 'VIN must be exactly 17 characters';
        }

        // Email validation
        if (field.type === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
        }

        this.setFieldError(field, isValid, errorMessage);
        return isValid;
    }

    setFieldError(field, isValid, errorMessage) {
        const errorElement = field.parentNode.querySelector('.form-error');
        
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

    clearFieldError(field) {
        field.classList.remove('error');
        const errorElement = field.parentNode.querySelector('.form-error');
        if (errorElement) errorElement.style.display = 'none';
    }

    async loadClientContacts(clientId) {
        const contactSelect = document.getElementById('contact_id');
        
        if (!clientId) {
            contactSelect.disabled = true;
            contactSelect.innerHTML = '<option value="">Select contact</option>';
            return;
        }

        try {
            contactSelect.classList.add('loading');
            
            const response = await fetch(`${base_url}sales_orders/get_client_contacts/${clientId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const contacts = await response.json();
            
            contactSelect.innerHTML = '<option value="">Select contact</option>';
            contacts.forEach(contact => {
                contactSelect.innerHTML += `<option value="${contact.id}">${contact.name}</option>`;
            });
            
            contactSelect.disabled = false;
        } catch (error) {
            console.error('Error loading contacts:', error);
            this.showToast('Error loading contacts', 'error');
        } finally {
            contactSelect.classList.remove('loading');
        }
    }

    async loadClientServices(clientId) {
        const serviceSelect = document.getElementById('service_id');
        
        if (!clientId) {
            serviceSelect.disabled = true;
            serviceSelect.innerHTML = '<option value="">Select service</option>';
            return;
        }

        try {
            serviceSelect.classList.add('loading');
            
            const response = await fetch(`${base_url}sales_orders/get_client_services/${clientId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const services = await response.json();
            
            serviceSelect.innerHTML = '<option value="">Select service</option>';
            services.forEach(service => {
                serviceSelect.innerHTML += `<option value="${service.id}" data-price="${service.price}">${service.name} - $${service.price}</option>`;
            });
            
            serviceSelect.disabled = false;
        } catch (error) {
            console.error('Error loading services:', error);
            this.showToast('Error loading services', 'error');
        } finally {
            serviceSelect.classList.remove('loading');
        }
    }

    loadServicePrice(serviceId) {
        const serviceSelect = document.getElementById('service_id');
        const priceInput = document.getElementById('service_price');
        
        if (!serviceId) {
            priceInput.value = '';
            return;
        }

        const selectedOption = serviceSelect.querySelector(`option[value="${serviceId}"]`);
        if (selectedOption) {
            const price = selectedOption.dataset.price || '0';
            priceInput.value = price;
        }
    }

    generateOrderSummary() {
        const formData = new FormData(document.getElementById('salesOrderForm'));
        const summaryContent = document.getElementById('summary-content');
        
        const clientName = document.getElementById('client_id').selectedOptions[0]?.text || 'Not selected';
        const contactName = document.getElementById('contact_id').selectedOptions[0]?.text || 'Not selected';
        const serviceName = document.getElementById('service_id').selectedOptions[0]?.text || 'Not selected';
        const vehicle = formData.get('vehicle') || 'Not specified';
        const scheduledDate = formData.get('scheduled_date') || 'Not scheduled';
        const scheduledTime = formData.get('scheduled_time') || 'Not scheduled';
        
        summaryContent.innerHTML = `
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6 class="mb-2">Client Information</h6>
                        <p class="mb-1"><strong>Client:</strong> ${clientName}</p>
                        <p class="mb-1"><strong>Contact:</strong> ${contactName}</p>
                        <p class="mb-0"><strong>Vehicle:</strong> ${vehicle}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6 class="mb-2">Service Details</h6>
                        <p class="mb-1"><strong>Service:</strong> ${serviceName}</p>
                        <p class="mb-1"><strong>Date:</strong> ${scheduledDate}</p>
                        <p class="mb-0"><strong>Time:</strong> ${scheduledTime}</p>
                    </div>
                </div>
            </div>
        `;
    }

    async submitForm() {
        if (!this.validateCurrentStep()) {
            this.showToast('Please fix the errors before submitting', 'error');
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('salesOrderForm');
        const formData = new FormData(form);

        try {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            const response = await fetch(`${base_url}sales_orders/save`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showToast('Sales order created successfully!', 'success');
                
                // Close modal and refresh page/table
                setTimeout(() => {
                    bootstrap.Modal.getInstance(this.modal).hide();
                    if (typeof refreshTable === 'function') {
                        refreshTable();
                    } else {
                        location.reload();
                    }
                }, 1500);
            } else {
                this.showToast(result.message || 'Error creating order', 'error');
            }
        } catch (error) {
            console.error('Submit error:', error);
            this.showToast('Error submitting form', 'error');
        } finally {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
        }
    }

    showToast(message, type = 'info') {
        // Use existing toast system or create a simple one
        if (typeof showToast === 'function') {
            showToast(type, message);
        } else {
            alert(message); // Fallback
        }
    }

    onModalShown() {
        // Reset form to first step
        this.goToStep(1);
        
        // Focus first input
        const firstInput = document.querySelector('.form-step.active input:not([readonly]), .form-step.active select:not([disabled])');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }

        // Initialize feather icons
        this.initializeFeatherIcons();
    }

    onModalHidden() {
        // Reset form
        document.getElementById('salesOrderForm').reset();
        
        // Reset steps
        document.querySelectorAll('.step-item').forEach(item => {
            item.classList.remove('active', 'completed');
        });
        document.querySelector('.step-item[data-step="1"]').classList.add('active');
        
        // Reset form steps
        document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
        document.getElementById('step-basic-info').classList.add('active');
        
        // Clear errors
        document.querySelectorAll('.form-control.error, .form-select.error').forEach(field => {
            this.clearFieldError(field);
        });
        
        this.currentStep = 1;
        this.updateNavigationButtons();
    }

    // Public methods for external access
    openForEdit(orderId) {
        this.isEditing = true;
        document.getElementById('order_id').value = orderId;
        document.getElementById('modalTitle').textContent = 'Edit Sales Order';
        document.getElementById('submitBtnText').textContent = 'Update Order';
        
        // Load order data
        this.loadOrderData(orderId);
    }

    openForCreate() {
        this.isEditing = false;
        document.getElementById('order_id').value = '';
        document.getElementById('modalTitle').textContent = 'Create Sales Order';
        document.getElementById('submitBtnText').textContent = 'Create Order';
    }

    async loadOrderData(orderId) {
        try {
            const response = await fetch(`${base_url}sales_orders/get/${orderId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const order = await response.json();
            
            if (order.success && order.data) {
                this.populateForm(order.data);
            }
        } catch (error) {
            console.error('Error loading order:', error);
            this.showToast('Error loading order data', 'error');
        }
    }

    populateForm(data) {
        Object.keys(data).forEach(key => {
            const field = document.getElementById(key);
            if (field) {
                field.value = data[key] || '';
                
                // Trigger change events for dependent fields
                if (key === 'client_id') {
                    field.dispatchEvent(new Event('change'));
                }
            }
        });
    }
}

// Initialize modal when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.salesOrderModal = new SalesOrderModal();
});

// Global functions for backward compatibility
function openSalesOrderModal() {
    window.salesOrderModal.openForCreate();
    new bootstrap.Modal(document.getElementById('salesOrderModal')).show();
}

function editSalesOrder(orderId) {
    window.salesOrderModal.openForEdit(orderId);
    new bootstrap.Modal(document.getElementById('salesOrderModal')).show();
}
</script>