/**
 * Global Notifications System
 * Manages notifications in the topbar across all pages
 */

class GlobalNotifications {
    constructor() {
        this.isLoading = false;
        this.notifications = [];
        this.todoNotifications = [];
        this.init();
    }

    // Helper function to build URLs correctly
    buildUrl(path) {
        if (!window.baseUrl) return path;
        
        const baseUrl = window.baseUrl.replace(/\/+$/, ''); // Remove trailing slashes
        const cleanPath = path.replace(/^\/+/, ''); // Remove leading slashes
        
        return `${baseUrl}/${cleanPath}`;
    }

    init() {
        // Wait for both DOM and jQuery to be ready
        const initializeNotifications = () => {
        
            
            // Don't load notifications on auth pages
            if (window.location.pathname.includes('/login') || 
                window.location.pathname.includes('/register') || 
                window.location.pathname.includes('/auth/') ||
                !window.baseUrl) {
                console.log('🔕 Skipping notifications on auth page');
                return;
            }
            
            // Check if required elements exist before proceeding
            if (!$('#global-notification-badge').length || 
                !$('#all-notifications-container').length) {
                console.log('🔕 Required notification elements not found, skipping initialization');
                return;
            }
            
            this.loadNotifications();
            this.bindEvents();
            this.startAutoRefresh();
        };

        // Check if jQuery is available
        if (typeof $ === 'undefined') {
            console.log('🔕 jQuery not available, skipping notifications');
            return;
        }

        $(document).ready(initializeNotifications);
    }

    bindEvents() {
        // Mark notification as read
        $(document).on('click', '.mark-notification-read', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const notificationId = $(e.currentTarget).data('id');
            this.markNotificationRead(notificationId);
        });

        // Mark all notifications as read
        $('#markAllNotificationsRead').on('click', () => {
            this.markAllNotificationsRead();
        });

        // Refresh notifications when dropdown is opened
        $('#page-header-notifications-dropdown').on('show.bs.dropdown', () => {
            this.loadNotifications();
        });

        // Handle notification tab switches
        $('#notificationItemsTab a[data-bs-toggle="tab"]').on('shown.bs.tab', (e) => {
            const target = $(e.target).attr('href');
            if (target === '#todos-tab') {
                this.loadTodoNotifications();
            }
        });
    }

    async loadNotifications() {
        if (this.isLoading) return;
        
        // Early return if baseUrl is not available (like on login page)
        if (!window.baseUrl) {
            console.log('🔕 BaseUrl not available, skipping notifications load');
            return;
        }
        
        this.isLoading = true;
        
        try {
    
            const response = await $.get(this.buildUrl('notifications'));
            
            if (response.success) {
    
                this.notifications = response.notifications;
                this.updateNotificationBadge(response.count);
                this.renderNotifications();
            } else {
                console.error('Failed to load notifications:', response.message);
                this.showError();
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
            this.showError();
        } finally {
            this.isLoading = false;
        }
    }

    async loadTodoNotifications() {
        // Now handled automatically in renderTodoNotifications()
        this.renderTodoNotifications();
    }

    updateNotificationBadge(count) {
        const badge = $('#global-notification-badge');
        const countSpan = $('#global-notification-count');
        const allCountSpan = $('#all-notification-count');
        
        if (count > 0) {
            badge.text(count).show();
            countSpan.text(`${count} New`);
            allCountSpan.text(count);
        } else {
            badge.hide();
            countSpan.text('0 New');
            allCountSpan.text('0');
        }
    }

    renderNotifications() {
        const container = $('#all-notifications-container');
        
        if (!this.notifications || this.notifications.length === 0) {
            container.html(this.getEmptyNotificationsHtml());
            return;
        }

        let html = '';
        this.notifications.forEach(notification => {
            html += this.getNotificationHtml(notification);
        });

        container.html(html);
        
        // Update todo notifications tab automatically
        this.renderTodoNotifications();
        
        // Re-initialize Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    renderTodoNotifications() {
        const container = $('#todo-notifications-container');
        
        // Filter notifications that are related to TODOs
        const todoNotifications = this.notifications.filter(notification => {
            return notification.type === 'completed' || 
                   notification.type === 'overdue' || 
                   notification.type === 'due_today' || 
                   notification.type === 'due_soon' ||
                   (notification.todo_id && notification.todo_id > 0);
        });
        
        if (!todoNotifications || todoNotifications.length === 0) {
            container.html(`
                <div class="text-center py-4">
                    <i class="bx bx-check-circle fs-48 text-success"></i>
                    <h6 class="mt-2 text-muted">No TODO Notifications</h6>
                    <p class="text-muted small">All your todos are up to date!</p>
                </div>
            `);
            return;
        }

        let html = '';
        todoNotifications.forEach(notification => {
            html += this.getTodoNotificationHtml(notification);
        });

        container.html(html);
        
        // Re-initialize Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    getNotificationHtml(notification) {
        const priorityColor = this.getPriorityColor(notification.priority);
        const iconClass = this.getNotificationIcon(notification.type);
        const timeAgo = this.getTimeAgo(notification.created_at);
        
        return `
            <div class="text-reset notification-item d-block dropdown-item position-relative" data-notification-id="${notification.id}">
                <div class="d-flex">
                    <div class="avatar-xs me-3 flex-shrink-0">
                        <span class="avatar-title bg-${priorityColor}-subtle text-${priorityColor} rounded-circle fs-16">
                            <i class="${iconClass}"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <a href="#!" class="stretched-link">
                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">${this.escapeHtml(notification.title)}</h6>
                        </a>
                        <div class="fs-13 text-muted">
                            <p class="mb-1">${this.escapeHtml(notification.message)}</p>
                        </div>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                            <span><i class="mdi mdi-clock-outline"></i> ${timeAgo}</span>
                        </p>
                    </div>
                    <div class="px-2 fs-15">
                        <button class="btn btn-sm btn-outline-secondary mark-notification-read" data-id="${notification.id}" title="Mark as read">
                            <i class="bx bx-check fs-16"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    getTodoNotificationHtml(notification) {
        const timeAgo = this.getTimeAgo(notification.created_at);
        
        return `
            <div class="text-reset notification-item d-block dropdown-item position-relative" data-notification-id="${notification.id}">
                <div class="d-flex">
                    <div class="avatar-xs me-3 flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-16">
                            <i class="bx bx-check-square"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <a href="${this.buildUrl('todos')}" class="stretched-link">
                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">${this.escapeHtml(notification.title)}</h6>
                        </a>
                        <div class="fs-13 text-muted">
                            <p class="mb-1">${this.escapeHtml(notification.message)}</p>
                        </div>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                            <span><i class="mdi mdi-clock-outline"></i> ${timeAgo}</span>
                        </p>
                    </div>
                    <div class="px-2 fs-15">
                        <button class="btn btn-sm btn-outline-secondary mark-notification-read" data-id="${notification.id}" title="Mark as read">
                            <i class="bx bx-check fs-16"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    getEmptyNotificationsHtml() {
        return `
            <div class="text-center py-4">
                <i class="bx bx-bell-off fs-48 text-muted"></i>
                <h6 class="mt-2 text-muted">No Notifications</h6>
                <p class="text-muted small">You're all caught up! No new notifications at this time.</p>
            </div>
        `;
    }

    showError() {
        const container = $('#all-notifications-container');
        container.html(`
            <div class="text-center py-4">
                <i class="bx bx-error-circle fs-48 text-danger"></i>
                <h6 class="mt-2 text-danger">Error Loading Notifications</h6>
                <p class="text-muted small">Please try again later.</p>
                <button class="btn btn-sm btn-outline-primary" onclick="globalNotifications.loadNotifications()">
                    <i class="bx bx-refresh me-1"></i> Retry
                </button>
            </div>
        `);
    }

    async markNotificationRead(notificationId) {
        try {
            const response = await $.post(this.buildUrl(`notifications/mark-read/${notificationId}`), {
                [window.csrfTokenName]: window.csrfHash
            });

            if (response.success) {
                // Remove notification from UI
                $(`.notification-item[data-notification-id="${notificationId}"]`).fadeOut(300, function() {
                    $(this).remove();
                });
                
                // Update counter
                const currentCount = parseInt($('#global-notification-badge').text()) || 0;
                const newCount = Math.max(0, currentCount - 1);
                this.updateNotificationBadge(newCount);
                
                this.showToast('success', 'Notification marked as read');
            } else {
                this.showToast('error', response.message || 'Failed to mark notification as read');
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
            this.showToast('error', 'Failed to mark notification as read');
        }
    }

    async markAllNotificationsRead() {
        try {
            const response = await $.post(this.buildUrl('notifications/mark-all-read'), {
                [window.csrfTokenName]: window.csrfHash
            });

            if (response.success) {
                // Clear all notifications from UI
                $('.notification-item').fadeOut(300, function() {
                    $(this).remove();
                });
                
                // Reset counter
                this.updateNotificationBadge(0);
                
                // Show empty state
                setTimeout(() => {
                    $('#all-notifications-container').html(this.getEmptyNotificationsHtml());
                }, 300);
                
                this.showToast('success', 'All notifications marked as read');
            } else {
                this.showToast('error', response.message || 'Failed to mark all notifications as read');
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
            this.showToast('error', 'Failed to mark all notifications as read');
        }
    }

    startAutoRefresh() {
        // Auto-refresh notifications every 2 minutes
        setInterval(() => {
            this.loadNotifications();
        }, 120000);
    }

    getPriorityColor(priority) {
        switch (priority) {
            case 'urgent': return 'danger';
            case 'high': return 'warning';
            case 'medium': return 'info';
            case 'low': return 'secondary';
            default: return 'primary';
        }
    }

    getNotificationIcon(type) {
        switch (type) {
            case 'completed': return 'bx bx-check-circle';
            case 'overdue': return 'bx bx-time-five';
            case 'due_today': return 'bx bx-calendar-check';
            case 'due_soon': return 'bx bx-calendar-exclamation';
            case 'todo': return 'bx bx-check-square';
            case 'system': return 'bx bx-cog';
            case 'message': return 'bx bx-message-dots';
            case 'alert': return 'bx bx-error-circle';
            default: return 'bx bx-bell';
        }
    }

    getTimeAgo(dateString) {
        const now = new Date();
        const date = new Date(dateString);
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) {
            return 'Just now';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return `${minutes} min${minutes > 1 ? 's' : ''} ago`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `${hours} hr${hours > 1 ? 's' : ''} ago`;
        } else {
            const days = Math.floor(diffInSeconds / 86400);
            return `${days} day${days > 1 ? 's' : ''} ago`;
        }
    }

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    showToast(type, message) {
        // Use existing toast system if available, otherwise simple alert
        if (typeof showToast === 'function') {
            showToast(type, message);
        } else if (typeof Toastify !== 'undefined') {
            Toastify({
                text: message,
                duration: 3000,
                backgroundColor: type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'
            }).showToast();
        } else {
            console.log(`${type.toUpperCase()}: ${message}`);
        }
    }
}

// Initialize global notifications system
window.globalNotifications = new GlobalNotifications();

// Make CSRF token available globally
$(document).ready(function() {
    // Get CSRF token from meta tags or forms
    const csrfToken = $('meta[name="X-CSRF-TOKEN"]').attr('content') || 
                     $('input[name="<?= csrf_token() ?>"]').val();
    const csrfTokenName = '<?= csrf_token() ?>';
    
    if (csrfToken) {
        window.csrfHash = csrfToken;
        window.csrfTokenName = csrfTokenName;
    }
}); 