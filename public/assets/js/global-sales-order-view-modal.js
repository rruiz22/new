/**
 * ============================================================================
 * GLOBAL SALES ORDER VIEW MODAL - Complete Implementation
 * Fullscreen modal for viewing order details with tabs
 * ============================================================================
 */

class GlobalSalesOrderViewModal {
    constructor() {
        this.modal = null;
        this.currentOrderId = null;
        
        // Configuration
        this.config = {
            baseUrl: window.base_url || '/',
            apiEndpoints: {
                orderDetails: 'sales_orders/get/',
                activities: 'sales_orders/getActivities/',
                comments: 'sales_orders/getComments/',
                followers: 'sales_orders/getFollowers/'
            }
        };
        
        this.init();
    }

    init() {
        console.log('🔍 Initializing GlobalSalesOrderViewModal...');
        
        this.modal = document.getElementById('global-sales-order-view-modal');
        
        if (!this.modal) {
            console.error('❌ GlobalSalesOrderViewModal: Modal element not found');
            console.log('Available elements with "modal" in ID:', document.querySelectorAll('[id*="modal"]'));
            return;
        }

        console.log('📱 Modal element found:', this.modal);
        console.log('📱 Modal HTML structure check:', {
            hasModalClass: this.modal.classList.contains('modal'),
            hasModalDialog: !!this.modal.querySelector('.modal-dialog'),
            hasModalContent: !!this.modal.querySelector('.modal-content'),
            modalId: this.modal.id,
            modalDisplay: window.getComputedStyle(this.modal).display
        });

        this.bindEvents();
        console.log('✅ GlobalSalesOrderViewModal: Initialized successfully');
    }

    bindEvents() {
        // Modal events
        this.modal.addEventListener('shown.bs.modal', () => this.onModalShown());
        this.modal.addEventListener('hidden.bs.modal', () => this.onModalHidden());

        // Tab change events
        const tabButtons = this.modal.querySelectorAll('[data-bs-toggle="tab"]');
        tabButtons.forEach(tab => {
            tab.addEventListener('shown.bs.tab', (e) => this.onTabChange(e));
        });
    }

    // ========================================
    // PUBLIC METHODS
    // ========================================

    open(orderId) {
        console.log('🔄 Opening view modal for order ID:', orderId);
        
        if (!orderId) {
            console.error('❌ Invalid order ID provided to view modal');
            return;
        }
        
        this.currentOrderId = orderId;
        this.resetModal();
        
        try {
            // Ensure modal exists
            if (!this.modal) {
                console.error('❌ Modal element not found');
                return;
            }
            
            console.log('📱 Modal element found, checking Bootstrap availability...');
            
            // Check if Bootstrap is available
            if (typeof bootstrap === 'undefined') {
                console.error('❌ Bootstrap is not available');
                
                // Try jQuery fallback immediately
                if (typeof $ !== 'undefined') {
                    console.log('🔄 Using jQuery fallback for modal...');
                    $(this.modal).modal('show');
                    setTimeout(() => {
                        this.loadOrderData(orderId);
                    }, 200);
                    return;
                } else {
                    console.error('❌ Neither Bootstrap nor jQuery available');
                    return;
                }
            }
            
            // Force remove any existing instances
            const existingInstance = bootstrap.Modal.getInstance(this.modal);
            if (existingInstance) {
                console.log('🧹 Disposing existing modal instance...');
                existingInstance.dispose();
            }
            
            console.log('🔧 Creating fresh Bootstrap modal instance...');
            
            // Create fresh instance with basic options
            const modalInstance = new bootstrap.Modal(this.modal, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
            
            // Add event listeners
            this.modal.addEventListener('shown.bs.modal', () => {
                console.log('✅ Modal is now shown - loading data...');
                this.loadOrderData(orderId);
            });
            
            this.modal.addEventListener('show.bs.modal', () => {
                console.log('🔄 Modal is showing...');
            });
            
            console.log('✅ Showing modal...');
            modalInstance.show();
            
        } catch (error) {
            console.error('❌ Error opening view modal:', error);
            console.error('Error details:', error.stack);
            
            // Fallback - try direct DOM manipulation
            console.log('🔄 Trying direct DOM manipulation fallback...');
            
            this.modal.style.display = 'block';
            this.modal.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Create backdrop manually
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
            
            setTimeout(() => {
                this.loadOrderData(orderId);
            }, 200);
        }
    }

    close() {
        try {
            console.log('🔄 Closing view modal...');
            
            // Try Bootstrap method first
            if (typeof bootstrap !== 'undefined') {
                const modalInstance = bootstrap.Modal.getInstance(this.modal);
                if (modalInstance) {
                    modalInstance.hide();
                    return;
                }
            }
            
            // Try jQuery fallback
            if (typeof $ !== 'undefined') {
                $(this.modal).modal('hide');
                return;
            }
            
            // Manual DOM manipulation fallback
            this.modal.style.display = 'none';
            this.modal.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            // Remove manually created backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            console.log('✅ Modal closed using fallback method');
            
        } catch (error) {
            console.error('❌ Error closing modal:', error);
            
            // Force close as last resort
            this.modal.style.display = 'none';
            this.modal.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
        }
    }

    // ========================================
    // DATA LOADING METHODS
    // ========================================

    async loadOrderData(orderId) {
        try {
            console.log('🔄 Loading order data for ID:', orderId);
            
            const response = await fetch(`${this.config.baseUrl}${this.config.apiEndpoints.orderDetails}${orderId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const result = await response.json();
            
            if (result.success) {
                this.populateTopBar(result.data);
                this.loadDetailsTab(result.data);
                console.log('✅ Order data loaded successfully');
            } else {
                throw new Error(result.message || 'Failed to load order data');
            }

        } catch (error) {
            console.error('❌ Error loading order data:', error);
            this.showError('Failed to load order data');
        }
    }

    async loadActivities(orderId) {
        try {
            console.log('🔄 Loading activities for order ID:', orderId);
            
            const response = await fetch(`${this.config.baseUrl}${this.config.apiEndpoints.activities}${orderId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            
            if (result.success) {
                this.populateActivitiesTab(result.data);
            } else {
                this.showTabError('activities', 'Failed to load activities');
            }

        } catch (error) {
            console.error('❌ Error loading activities:', error);
            this.showTabError('activities', 'Error loading activities');
        }
    }

    async loadComments(orderId) {
        try {
            console.log('🔄 Loading comments for order ID:', orderId);
            
            const response = await fetch(`${this.config.baseUrl}${this.config.apiEndpoints.comments}${orderId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            
            if (result.success) {
                this.populateCommentsTab(result.data);
            } else {
                this.showTabError('comments', 'Failed to load comments');
            }

        } catch (error) {
            console.error('❌ Error loading comments:', error);
            this.showTabError('comments', 'Error loading comments');
        }
    }

    async loadFollowers(orderId) {
        try {
            console.log('🔄 Loading followers for order ID:', orderId);
            
            const response = await fetch(`${this.config.baseUrl}${this.config.apiEndpoints.followers}${orderId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            
            if (result.success) {
                this.populateFollowersTab(result.data);
            } else {
                this.showTabError('followers', 'Failed to load followers');
            }

        } catch (error) {
            console.error('❌ Error loading followers:', error);
            this.showTabError('followers', 'Error loading followers');
        }
    }

    // ========================================
    // UI POPULATION METHODS
    // ========================================

    populateTopBar(order) {
        // Order ID
        const orderIdElement = document.getElementById('viewOrderId');
        if (orderIdElement) {
            orderIdElement.textContent = order.order_number || `SAL-${String(order.id).padStart(5, '0')}`;
        }

        // Client
        const clientElement = document.getElementById('viewClient');
        if (clientElement) {
            clientElement.textContent = order.client_name || 'N/A';
        }

        // Date
        const dateElement = document.getElementById('viewDate');
        if (dateElement) {
            dateElement.textContent = order.date || 'N/A';
        }

        // Time
        const timeElement = document.getElementById('viewTime');
        if (timeElement) {
            timeElement.textContent = order.time || 'N/A';
        }

        // Status
        const statusElement = document.getElementById('viewStatus');
        if (statusElement) {
            statusElement.textContent = this.formatStatus(order.status);
            statusElement.className = `badge ${this.getStatusBadgeClass(order.status)}`;
        }
    }

    loadDetailsTab(order) {
        const detailsContent = document.getElementById('detailsContent');
        if (!detailsContent) return;

        const html = `
            <div class="row">
                <div class="col-lg-8">
                    <!-- Order Details Card -->
                    <div class="view-modal-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Order Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Order Number</label>
                                    <div class="fw-semibold">${order.order_number || `SAL-${String(order.id).padStart(5, '0')}`}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Status</label>
                                    <div><span class="badge ${this.getStatusBadgeClass(order.status)}">${this.formatStatus(order.status)}</span></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Client</label>
                                    <div class="fw-semibold">${order.client_name || 'N/A'}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Contact</label>
                                    <div>${order.contact_name || 'N/A'}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Service</label>
                                    <div>${order.service_name || 'N/A'}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Date & Time</label>
                                    <div>${order.date || 'N/A'} ${order.time ? 'at ' + order.time : ''}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Information Card -->
                    <div class="view-modal-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Vehicle Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Stock Number</label>
                                    <div class="fw-semibold">${order.stock || 'N/A'}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">VIN</label>
                                    <div class="font-monospace">${order.vin || 'N/A'}</div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label text-muted">Vehicle</label>
                                    <div class="fw-semibold">${order.vehicle || 'N/A'}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    ${order.instructions ? `
                    <!-- Special Instructions Card -->
                    <div class="view-modal-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Special Instructions</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i data-feather="info" width="16" class="me-2"></i>
                                ${order.instructions}
                            </div>
                        </div>
                    </div>
                    ` : ''}
                </div>

                <div class="col-lg-4">
                    <!-- Quick Actions Card -->
                    <div class="view-modal-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary" onclick="editCurrentOrder()">
                                    <i data-feather="edit-2" width="16" class="me-2"></i>
                                    Edit Order
                                </button>
                                <button class="btn btn-outline-success" onclick="printCurrentOrder()">
                                    <i data-feather="printer" width="16" class="me-2"></i>
                                    Print Order
                                </button>
                                <button class="btn btn-outline-info" onclick="downloadCurrentOrderPDF()">
                                    <i data-feather="download" width="16" class="me-2"></i>
                                    Download PDF
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline Card -->
                    <div class="view-modal-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Timeline</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Order Created</h6>
                                        <small class="text-muted">${this.formatDateTime(order.created_at)}</small>
                                    </div>
                                </div>
                                ${order.updated_at && order.updated_at !== order.created_at ? `
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Last Updated</h6>
                                        <small class="text-muted">${this.formatDateTime(order.updated_at)}</small>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        detailsContent.innerHTML = html;
        
        // Re-initialize Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    populateActivitiesTab(activities) {
        const activitiesContent = document.getElementById('activitiesContent');
        if (!activitiesContent) return;

        if (!activities || activities.length === 0) {
            activitiesContent.innerHTML = `
                <div class="text-center py-5">
                    <i data-feather="activity" width="48" class="text-muted mb-3"></i>
                    <p class="text-muted">No activities found for this order.</p>
                </div>
            `;
            return;
        }

        const html = `
            <div class="activity-list">
                ${activities.map(activity => `
                    <div class="activity-item border-bottom pb-3 mb-3">
                        <div class="d-flex">
                            <div class="activity-icon me-3">
                                <i data-feather="${this.getActivityIcon(activity.action)}" width="16" class="text-${this.getActivityColor(activity.action)}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="activity-content">
                                    <strong>${activity.user_name || 'System'}</strong>
                                    <span>${activity.description}</span>
                                </div>
                                <small class="text-muted">${this.formatDateTime(activity.created_at)}</small>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;

        activitiesContent.innerHTML = html;
        
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    populateCommentsTab(comments) {
        const commentsContent = document.getElementById('commentsContent');
        if (!commentsContent) return;

        if (!comments || comments.length === 0) {
            commentsContent.innerHTML = `
                <div class="text-center py-5">
                    <i data-feather="message-circle" width="48" class="text-muted mb-3"></i>
                    <p class="text-muted">No comments found for this order.</p>
                </div>
            `;
            return;
        }

        const html = `
            <div class="comments-list">
                ${comments.map(comment => `
                    <div class="comment-item border-bottom pb-3 mb-3">
                        <div class="d-flex">
                            <div class="comment-avatar me-3">
                                <div class="avatar-circle bg-primary text-white">
                                    ${(comment.user_name || 'U').charAt(0).toUpperCase()}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="comment-header d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>${comment.user_name || 'Unknown User'}</strong>
                                        <small class="text-muted ms-2">${this.formatDateTime(comment.created_at)}</small>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    ${comment.comment}
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;

        commentsContent.innerHTML = html;
    }

    populateFollowersTab(followers) {
        const followersContent = document.getElementById('followersContent');
        if (!followersContent) return;

        if (!followers || followers.length === 0) {
            followersContent.innerHTML = `
                <div class="text-center py-5">
                    <i data-feather="users" width="48" class="text-muted mb-3"></i>
                    <p class="text-muted">No followers for this order.</p>
                </div>
            `;
            return;
        }

        const html = `
            <div class="followers-list">
                <div class="row">
                    ${followers.map(follower => `
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-success text-white me-3">
                                            ${(follower.user_name || 'U').charAt(0).toUpperCase()}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">${follower.user_name || 'Unknown User'}</h6>
                                            <small class="text-muted">Following since ${this.formatDateTime(follower.created_at)}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        followersContent.innerHTML = html;
    }

    // ========================================
    // EVENT HANDLERS
    // ========================================

    onModalShown() {
        // Re-initialize Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    onModalHidden() {
        this.currentOrderId = null;
        this.resetModal();
    }

    onTabChange(event) {
        const tabId = event.target.getAttribute('data-bs-target');
        
        // Load data for specific tabs when they're shown
        switch (tabId) {
            case '#activities-pane':
                if (this.currentOrderId) {
                    this.loadActivities(this.currentOrderId);
                }
                break;
            case '#comments-pane':
                if (this.currentOrderId) {
                    this.loadComments(this.currentOrderId);
                }
                break;
            case '#followers-pane':
                if (this.currentOrderId) {
                    this.loadFollowers(this.currentOrderId);
                }
                break;
        }
    }

    // ========================================
    // UTILITY METHODS
    // ========================================

    resetModal() {
        // Reset top bar
        document.getElementById('viewOrderId').textContent = 'Loading...';
        document.getElementById('viewClient').textContent = 'Loading...';
        document.getElementById('viewDate').textContent = 'Loading...';
        document.getElementById('viewTime').textContent = 'Loading...';
        document.getElementById('viewStatus').textContent = 'Loading...';

        // Reset tab content to loading state
        const tabs = ['details', 'activities', 'comments', 'followers'];
        tabs.forEach(tab => {
            const content = document.getElementById(`${tab}Content`);
            if (content) {
                content.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading ${tab}...</span>
                        </div>
                        <div class="mt-2 text-muted">Loading ${tab}...</div>
                    </div>
                `;
            }
        });

        // Reset to first tab
        const firstTab = document.getElementById('details-tab');
        if (firstTab) {
            firstTab.click();
        }
    }

    showError(message) {
        const detailsContent = document.getElementById('detailsContent');
        if (detailsContent) {
            detailsContent.innerHTML = `
                <div class="text-center py-5">
                    <i data-feather="alert-circle" width="48" class="text-danger mb-3"></i>
                    <h5 class="text-danger">Error</h5>
                    <p class="text-muted">${message}</p>
                    <button class="btn btn-primary" onclick="window.globalSalesOrderViewModal.loadOrderData(${this.currentOrderId})">
                        Try Again
                    </button>
                </div>
            `;
            
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    }

    showTabError(tab, message) {
        const content = document.getElementById(`${tab}Content`);
        if (content) {
            content.innerHTML = `
                <div class="text-center py-5">
                    <i data-feather="alert-circle" width="48" class="text-warning mb-3"></i>
                    <p class="text-muted">${message}</p>
                </div>
            `;
            
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    }

    formatStatus(status) {
        const statusMap = {
            'pending': 'Pending',
            'processing': 'Processing',
            'in_progress': 'In Progress',
            'completed': 'Completed',
            'cancelled': 'Cancelled'
        };
        return statusMap[status] || status;
    }

    getStatusBadgeClass(status) {
        const classMap = {
            'pending': 'bg-warning',
            'processing': 'bg-info',
            'in_progress': 'bg-primary',
            'completed': 'bg-success',
            'cancelled': 'bg-danger'
        };
        return classMap[status] || 'bg-secondary';
    }

    getActivityIcon(action) {
        const iconMap = {
            'created': 'plus-circle',
            'updated': 'edit-2',
            'status_changed': 'arrow-right-circle',
            'deleted': 'trash-2',
            'restored': 'refresh-ccw'
        };
        return iconMap[action] || 'activity';
    }

    getActivityColor(action) {
        const colorMap = {
            'created': 'success',
            'updated': 'info',
            'status_changed': 'primary',
            'deleted': 'danger',
            'restored': 'warning'
        };
        return colorMap[action] || 'secondary';
    }

    formatDateTime(dateTimeString) {
        if (!dateTimeString) return 'N/A';
        
        try {
            const date = new Date(dateTimeString);
            return date.toLocaleString();
        } catch (error) {
            return dateTimeString;
        }
    }
}

// ============================================================================
// GLOBAL FUNCTIONS
// ============================================================================

// Initialize the view modal when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('global-sales-order-view-modal')) {
        window.globalSalesOrderViewModal = new GlobalSalesOrderViewModal();
    }
});

// Global functions for actions
window.openViewModal = function(orderId) {
    console.log('🔄 Opening view modal for order ID:', orderId);
    if (window.globalSalesOrderViewModal) {
        window.globalSalesOrderViewModal.open(orderId);
    } else {
        console.error('❌ GlobalSalesOrderViewModal not available');
        alert('View modal is not available. Please refresh the page.');
    }
}

window.editCurrentOrder = function() {
    if (window.globalSalesOrderViewModal && window.globalSalesOrderViewModal.currentOrderId) {
        const orderId = window.globalSalesOrderViewModal.currentOrderId;
        
        // Close view modal
        window.globalSalesOrderViewModal.close();
        
        // Open edit modal
        setTimeout(() => {
            if (typeof window.openEditModal === 'function') {
                window.openEditModal(orderId);
            } else {
                console.error('❌ Edit modal function not available');
            }
        }, 300);
    }
}

window.printCurrentOrder = function() {
    if (window.globalSalesOrderViewModal && window.globalSalesOrderViewModal.currentOrderId) {
        const orderId = window.globalSalesOrderViewModal.currentOrderId;
        const url = `${window.base_url || '/'}sales_orders/print/${orderId}`;
        window.open(url, '_blank');
    }
}

window.downloadCurrentOrderPDF = function() {
    if (window.globalSalesOrderViewModal && window.globalSalesOrderViewModal.currentOrderId) {
        const orderId = window.globalSalesOrderViewModal.currentOrderId;
        const url = `${window.base_url || '/'}sales_orders/downloadPdf/${orderId}`;
        window.location.href = url;
    }
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { GlobalSalesOrderViewModal };
}