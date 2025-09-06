/**
 * Enhanced Table Indicators with Rich Popovers
 * Provides interactive hover previews for comments, notes, and duplicates
 */

// Prevent duplicate class declarations
if (typeof window.EnhancedTableIndicators !== 'undefined') {
    console.log('🔄 EnhancedTableIndicators already loaded, skipping...');
} else {

class EnhancedTableIndicators {
    constructor() {
        this.activePopovers = new Map();
        this.loadingCache = new Map();
        this.contentCache = new Map();
        this.cacheTimeout = 30000; // 30 seconds
        
        this.init();
    }

    init() {
        console.log('🚀 EnhancedTableIndicators: Initializing...');
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Use document delegation for dynamically added elements
        document.addEventListener('mouseenter', (e) => {
            if (this.matchesSelector(e.target, '.comments-indicator, .notes-indicator, .duplicate-indicator')) {
                this.handleMouseEnter(e);
            }
        });

        document.addEventListener('mouseleave', (e) => {
            if (this.matchesSelector(e.target, '.comments-indicator, .notes-indicator, .duplicate-indicator')) {
                this.handleMouseLeave(e);
            }
        });

        // Handle clicks on duplicate indicators to show modal
        document.addEventListener('click', (e) => {
            if (e.target.closest('.duplicate-indicator')) {
                e.stopPropagation();
                this.handleDuplicateClick(e.target.closest('.duplicate-indicator'));
            }
        });
    }

    // Cross-browser compatible matches method
    matchesSelector(element, selector) {
        if (!element || !element.nodeType) return false;
        
        const matches = element.matches || 
                       element.matchesSelector || 
                       element.msMatchesSelector || 
                       element.mozMatchesSelector || 
                       element.webkitMatchesSelector || 
                       element.oMatchesSelector;
        
        if (matches) {
            return matches.call(element, selector);
        }
        
        // Fallback for older browsers
        return this.fallbackMatches(element, selector);
    }

    // Fallback matcher for very old browsers
    fallbackMatches(element, selector) {
        const selectors = selector.split(',').map(s => s.trim());
        
        for (const sel of selectors) {
            if (sel.startsWith('.')) {
                const className = sel.substring(1);
                if (element.classList && element.classList.contains(className)) {
                    return true;
                }
            }
        }
        
        return false;
    }

    handleMouseEnter(event) {
        const element = event.target.closest('.comments-indicator, .notes-indicator, .duplicate-indicator');
        if (!element) return;

        // Debounce hover events
        clearTimeout(element.hoverTimeout);
        element.hoverTimeout = setTimeout(() => {
            this.showEnhancedPopover(element);
        }, 300);
    }

    handleMouseLeave(event) {
        const element = event.target.closest('.comments-indicator, .notes-indicator, .duplicate-indicator');
        if (!element) return;

        // Clear hover timeout
        clearTimeout(element.hoverTimeout);

        // Hide popover after a short delay
        setTimeout(() => {
            this.hidePopover(element);
        }, 100);
    }

    async showEnhancedPopover(element) {
        const elementId = this.getElementId(element);
        
        // Check if popover already exists
        if (this.activePopovers.has(elementId)) {
            return;
        }

        const popoverType = this.getPopoverType(element);
        const content = await this.getPopoverContent(element, popoverType);
        
        if (!content) return;

        // Create and show popover
        const popover = new bootstrap.Popover(element, {
            trigger: 'manual',
            placement: 'auto',
            html: true,
            sanitize: false,
            content: content,
            template: this.getPopoverTemplate(),
            customClass: `enhanced-popover ${popoverType}-popover`
        });

        this.activePopovers.set(elementId, popover);
        popover.show();

        // Auto-hide after 10 seconds
        setTimeout(() => {
            this.hidePopover(element);
        }, 10000);
    }

    hidePopover(element) {
        const elementId = this.getElementId(element);
        const popover = this.activePopovers.get(elementId);
        
        if (popover) {
            popover.dispose();
            this.activePopovers.delete(elementId);
        }
    }

    async getPopoverContent(element, type) {
        const rowData = this.getRowData(element);
        if (!rowData) return null;

        switch (type) {
            case 'comments':
                return this.generateCommentPopoverContent(rowData.comments || [], rowData.comments_count || 0);
            case 'notes':
                return this.generateNotesPopoverContent(rowData.internal_notes || [], rowData.internal_notes_count || 0);
            case 'duplicate':
                return await this.generateDuplicatePopoverContent(element, rowData);
            default:
                return null;
        }
    }

    generateCommentPopoverContent(comments, count) {
        if (!count || count === 0) {
            return '<div class="popover-empty">No comments available</div>';
        }

        const commentsToShow = comments.slice(0, 3);
        
        return `
            <div class="comment-preview-popover">
                <div class="popover-header-custom">
                    <i class="ri-message-2-line me-2"></i>
                    <strong>${count} Comment${count > 1 ? 's' : ''}</strong>
                </div>
                <div class="popover-body-custom">
                    ${commentsToShow.map(comment => `
                        <div class="comment-item">
                            <div class="comment-author">
                                ${this.generateAvatar(comment.author_name || 'Unknown')}
                                <span class="author-name">${comment.author_name || 'Unknown'}</span>
                                <span class="comment-time">${this.formatTimeAgo(comment.created_at)}</span>
                            </div>
                            <div class="comment-text">${this.truncateText(comment.comment || '', 100)}</div>
                        </div>
                    `).join('')}
                    ${count > 3 ? `
                        <div class="more-items">
                            <i class="ri-add-line me-1"></i>
                            ${count - 3} more comment${count - 3 > 1 ? 's' : ''}...
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    generateNotesPopoverContent(notes, count) {
        if (!count || count === 0) {
            return '<div class="popover-empty">No internal notes available</div>';
        }

        const notesToShow = notes.slice(0, 2);
        
        return `
            <div class="notes-preview-popover">
                <div class="popover-header-custom">
                    <i class="ri-file-lock-line me-2"></i>
                    <strong>${count} Internal Note${count > 1 ? 's' : ''}</strong>
                </div>
                <div class="popover-body-custom">
                    ${notesToShow.map(note => `
                        <div class="note-item">
                            <div class="note-author">
                                <i class="ri-shield-user-line me-1"></i>
                                <span class="author-name">${note.author_name || 'Staff'}</span>
                                <span class="note-time">${this.formatTimeAgo(note.created_at)}</span>
                            </div>
                            <div class="note-text">${this.truncateText(note.content || '', 150)}</div>
                        </div>
                    `).join('')}
                    ${count > 2 ? `
                        <div class="more-items">
                            <i class="ri-add-line me-1"></i>
                            ${count - 2} more note${count - 2 > 1 ? 's' : ''}...
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    async generateDuplicatePopoverContent(element, rowData) {
        const field = element.dataset.field || 'stock';
        const value = element.dataset.value || '';
        const count = parseInt(element.dataset.count || '0');
        
        if (count === 0) {
            return '<div class="popover-empty">No duplicates found</div>';
        }

        // Try to get preview data (simplified for now)
        return `
            <div class="duplicate-preview-popover">
                <div class="popover-header-custom">
                    <i class="ri-file-copy-line me-2"></i>
                    <strong>${count} Orders with same ${field.toUpperCase()}</strong>
                </div>
                <div class="popover-body-custom">
                    <div class="duplicate-value">
                        <strong>${field}:</strong> 
                        <span class="value-highlight">${value}</span>
                    </div>
                    <div class="duplicate-preview">
                        <div class="preview-item">
                            <span class="item-icon"><i class="ri-file-list-line"></i></span>
                            <span class="item-text">${count} orders found with this ${field}</span>
                        </div>
                    </div>
                    <div class="popover-actions">
                        <button class="btn btn-sm btn-primary" onclick="this.closest('.popover').dispatchEvent(new CustomEvent('show-duplicates', {detail: {field: '${field}', value: '${value}', orderId: '${rowData.id || ''}'}}))">
                            <i class="ri-eye-line me-1"></i>View All
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    handleDuplicateClick(element) {
        const field = element.dataset.field;
        const value = element.dataset.value;
        const orderId = element.dataset.orderId;
        
        // Hide any active popover
        this.hidePopover(element);
        
        // Call the existing duplicate modal function
        if (typeof showDuplicateOrdersModal === 'function') {
            showDuplicateOrdersModal(field, value, orderId);
        }
    }

    getPopoverTemplate() {
        return `
            <div class="popover custom-popover" role="tooltip">
                <div class="popover-arrow"></div>
                <div class="popover-body"></div>
            </div>
        `;
    }

    getElementId(element) {
        // Generate a unique ID for the element
        if (!element.dataset.elementId) {
            element.dataset.elementId = 'indicator_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        }
        return element.dataset.elementId;
    }

    getPopoverType(element) {
        if (element.classList.contains('comments-indicator')) return 'comments';
        if (element.classList.contains('notes-indicator')) return 'notes';
        if (element.classList.contains('duplicate-indicator')) return 'duplicate';
        return 'unknown';
    }

    getRowData(element) {
        // Try to get row data from DataTable
        const row = element.closest('tr');
        if (!row) return null;

        // Try multiple methods to get row data
        try {
            // Method 1: DataTables API
            const table = $(row).closest('table').DataTable();
            if (table) {
                const rowData = table.row(row).data();
                if (rowData) return rowData;
            }
        } catch (e) {
            console.debug('DataTables API not available:', e);
        }

        // Method 2: Check if row has stored data
        if (row.dataset && row.dataset.rowData) {
            try {
                return JSON.parse(row.dataset.rowData);
            } catch (e) {
                console.debug('Failed to parse row data:', e);
            }
        }

        // Method 3: Extract from element attributes
        return this.extractRowDataFromElement(element);
    }

    extractRowDataFromElement(element) {
        const row = element.closest('tr');
        if (!row) return null;

        // Extract basic info from the row
        const orderLink = row.querySelector('a[href*="/view/"]');
        const orderId = orderLink ? orderLink.href.split('/').pop() : null;

        return {
            id: orderId,
            comments_count: element.classList.contains('comments-indicator') ? 
                parseInt(element.textContent.match(/\d+/)?.[0] || '0') : 0,
            internal_notes_count: element.classList.contains('notes-indicator') ? 
                parseInt(element.textContent.match(/\d+/)?.[0] || '0') : 0,
            comments: [],
            internal_notes: []
        };
    }

    formatTimeAgo(datetime) {
        if (!datetime) return 'Unknown time';
        
        const date = new Date(datetime);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000);
        
        if (diff < 60) return 'just now';
        if (diff < 3600) return `${Math.floor(diff/60)}m ago`;
        if (diff < 86400) return `${Math.floor(diff/3600)}h ago`;
        if (diff < 604800) return `${Math.floor(diff/86400)}d ago`;
        if (diff < 2592000) return `${Math.floor(diff/604800)}w ago`;
        return date.toLocaleDateString();
    }

    generateAvatar(name) {
        if (!name) return '<div class="avatar-placeholder"></div>';
        
        const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        const colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'];
        const color = colors[name.charCodeAt(0) % colors.length];
        
        return `
            <div class="avatar-circle" style="background: ${color}20; color: ${color};">
                ${initials}
            </div>
        `;
    }

    truncateText(text, maxLength) {
        if (!text || text.length <= maxLength) return text || '';
        return text.substring(0, maxLength) + '...';
    }

    // Public method to initialize popovers for a specific container
    static initializeForContainer(container) {
        if (!window.enhancedTableIndicators) {
            window.enhancedTableIndicators = new EnhancedTableIndicators();
        }
        
        // Additional container-specific initialization if needed
        console.log('🎯 Enhanced indicators initialized for container');
    }

    // Public method to refresh indicators
    static refresh() {
        if (window.enhancedTableIndicators) {
            // Clear all active popovers
            window.enhancedTableIndicators.activePopovers.forEach(popover => {
                popover.dispose();
            });
            window.enhancedTableIndicators.activePopovers.clear();
            
            console.log('🔄 Enhanced indicators refreshed');
        }
    }
}

// Export for global access
window.EnhancedTableIndicators = EnhancedTableIndicators;

} // End of duplicate prevention check

// Initialize when DOM is ready - outside the duplicate check
if (!window.enhancedTableIndicators) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.enhancedTableIndicators = new window.EnhancedTableIndicators();
        });
    } else {
        window.enhancedTableIndicators = new window.EnhancedTableIndicators();
    }
}

// Handle custom events for duplicate modal
document.addEventListener('show-duplicates', (e) => {
    const { field, value, orderId } = e.detail;
    if (typeof showDuplicateOrdersModal === 'function') {
        showDuplicateOrdersModal(field, value, orderId);
    }
});