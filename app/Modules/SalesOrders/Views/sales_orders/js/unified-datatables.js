/**
 * Unified DataTables Implementation for Sales Orders
 * Eliminates code duplication and centralizes DataTable functionality
 */
class SalesOrderDataTable {
    constructor(config) {
        this.config = {
            tableId: config.tableId,
            type: config.type || 'all', // all, today, tomorrow, pending, week, services, deleted
            ajaxUrl: config.ajaxUrl || base_url + 'sales_orders/all_content',
            ...config
        };
        
        this.table = null;
        this.globalFilters = {};
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.initializeDataTable();
    }
    
    setupEventListeners() {
        // Global filter event listeners
        $('#applyGlobalFilters').off('click.salesOrderDT').on('click.salesOrderDT', () => {
            this.applyGlobalFilters();
        });
        
        $('#clearGlobalFilters').off('click.salesOrderDT').on('click.salesOrderDT', () => {
            this.clearGlobalFilters();
        });
        
        $('#refreshAllTables').off('click.salesOrderDT').on('click.salesOrderDT', () => {
            this.refreshTable();
        });
        
        // Individual refresh buttons
        $(`#refreshTable`).off('click.salesOrderDT').on('click.salesOrderDT', () => {
            this.refreshTable();
        });
    }
    
    initializeDataTable() {
        if ($.fn.DataTable.isDataTable(`#${this.config.tableId}`)) {
            $(`#${this.config.tableId}`).DataTable().destroy();
        }
        
        const dtConfig = this.getDataTableConfig();
        this.table = $(`#${this.config.tableId}`).DataTable(dtConfig);
        
        // Update row count badge
        this.updateBadgeCount();
        
        // Setup row click handlers
        this.setupRowHandlers();
    }
    
    getDataTableConfig() {
        const baseConfig = {
            processing: true,
            serverSide: true,
            responsive: false,
            scrollX: false,
            autoWidth: false,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                emptyTable: `<div class="text-center py-4"><i data-feather="inbox" class="icon-lg text-muted mb-3"></i><h5 class="text-muted">No ${this.config.type} orders found</h5><p class="text-muted mb-0">Try adjusting your filters or create a new order.</p></div>`,
                zeroRecords: `<div class="text-center py-4"><i data-feather="search" class="icon-lg text-muted mb-3"></i><h5 class="text-muted">No matching orders found</h5><p class="text-muted mb-0">Try different search terms or filters.</p></div>`
            },
            columnDefs: this.getColumnDefs(),
            ajax: {
                url: this.config.ajaxUrl,
                type: 'POST',
                data: (d) => this.prepareAjaxData(d),
                error: (xhr, error, thrown) => this.handleAjaxError(xhr, error, thrown)
            },
            drawCallback: () => this.onDrawCallback(),
            initComplete: () => this.onInitComplete()
        };
        
        return { ...baseConfig, ...this.config.additionalConfig };
    }
    
    getColumnDefs() {
        const commonDefs = [
            { className: "text-center", targets: [0, 1, 2, 3, 5] },
            { className: "text-center status-column", orderable: false, targets: 4 },
            { orderable: false, searchable: false, targets: 5 }
        ];
        
        // Customize based on table type
        switch (this.config.type) {
            case 'services':
                return [
                    { className: "text-center", targets: [0, 1, 2, 3, 4, 5] },
                    { orderable: false, searchable: false, targets: 5 }
                ];
            case 'deleted':
                return [
                    { className: "text-center", targets: [0, 1, 2, 3, 4, 5] },
                    { orderable: false, searchable: false, targets: 5 }
                ];
            default:
                return commonDefs;
        }
    }
    
    prepareAjaxData(d) {
        // Add type-specific filters
        const typeFilters = this.getTypeFilters();
        
        // Add global filters
        const globalFilters = this.getGlobalFilters();
        
        return { ...d, ...typeFilters, ...globalFilters };
    }
    
    getTypeFilters() {
        const today = new Date().toISOString().split('T')[0];
        const tomorrow = new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString().split('T')[0];
        
        switch (this.config.type) {
            case 'today':
                return {
                    date_from_filter: today,
                    date_to_filter: today
                };
            case 'tomorrow':
                return {
                    date_from_filter: tomorrow,
                    date_to_filter: tomorrow
                };
            case 'pending':
                return {
                    status_filter: 'pending,processing'
                };
            case 'week':
                const startOfWeek = this.getStartOfWeek();
                const endOfWeek = this.getEndOfWeek();
                return {
                    date_from_filter: startOfWeek,
                    date_to_filter: endOfWeek
                };
            case 'deleted':
                return {
                    show_deleted: true
                };
            default:
                return {};
        }
    }
    
    getGlobalFilters() {
        return {
            client_filter: $('#globalClientFilter').val() || '',
            contact_filter: $('#globalContactFilter').val() || '',
            status_filter: $('#globalStatusFilter').val() || '',
            date_from_filter: $('#globalDateFromFilter').val() || '',
            date_to_filter: $('#globalDateToFilter').val() || ''
        };
    }
    
    applyGlobalFilters() {
        if (this.table) {
            this.table.ajax.reload();
            this.updateActiveFiltersCount();
        }
    }
    
    clearGlobalFilters() {
        $('#globalClientFilter').val('').trigger('change');
        $('#globalContactFilter').val('').trigger('change');
        $('#globalStatusFilter').val('').trigger('change');
        $('#globalDateFromFilter').val('');
        $('#globalDateToFilter').val('');
        
        this.updateActiveFiltersCount();
        
        if (this.table) {
            this.table.ajax.reload();
        }
    }
    
    refreshTable() {
        if (this.table) {
            this.table.ajax.reload(null, false);
        }
    }
    
    updateActiveFiltersCount() {
        let count = 0;
        const filters = this.getGlobalFilters();
        
        Object.values(filters).forEach(value => {
            if (value && value.toString().trim() !== '') {
                count++;
            }
        });
        
        const badge = $('#activeFiltersCount');
        if (count > 0) {
            badge.text(count).removeClass('d-none');
        } else {
            badge.addClass('d-none');
        }
    }
    
    updateBadgeCount() {
        if (!this.table) return;
        
        this.table.on('draw.dt', () => {
            const info = this.table.page.info();
            const badgeId = `${this.config.type}OrdersBadge`;
            const badge = $(`#${badgeId}`);
            
            if (badge.length && info.recordsTotal > 0) {
                badge.text(info.recordsTotal).show();
            } else if (badge.length) {
                badge.hide();
            }
        });
    }
    
    setupRowHandlers() {
        // Use document-level delegation to prevent guid conflicts
        const tableId = this.config.tableId;
        
        // Remove any existing handlers for this table
        $(document).off(`click.${tableId}`);
        
        // Row click handler
        $(document).on(`click.${tableId}`, `#${tableId} tbody tr`, (e) => {
            if ($(e.target).closest('.dropdown, .btn, .form-control, .form-select, .action-buttons').length) {
                return;
            }
            
            // Get order ID from the first button in the actions column
            const actionCell = $(e.currentTarget).find('td:last-child .btn-view');
            if (actionCell.length > 0) {
                const orderId = actionCell.data('id');
                if (orderId) {
                    // Use view modal instead of opening new window
                    if (typeof window.openViewModal === 'function') {
                        window.openViewModal(orderId);
                    } else {
                        console.warn('View modal not available, falling back to new window');
                        window.open(`${base_url}sales_orders/view/${orderId}`, '_blank');
                    }
                }
            }
        });
        
        // Status dropdown handler
        $(document).on(`change.${tableId}`, `#${tableId} .status-dropdown`, function() {
            const orderId = $(this).data('order-id');
            const newStatus = $(this).val();
            const oldStatus = $(this).data('current-status');
            
            if (newStatus !== oldStatus && typeof window.updateOrderStatus === 'function') {
                window.updateOrderStatus(orderId, newStatus, oldStatus);
            }
        });
        
        // Action button handlers
        $(document).on(`click.${tableId}`, `#${tableId} .btn-view`, function(e) {
            e.preventDefault();
            e.stopPropagation();
            const orderId = $(this).data('id');
            
            if (typeof window.openViewModal === 'function') {
                window.openViewModal(orderId);
            } else {
                window.open(`${base_url}sales_orders/view/${orderId}`, '_blank');
            }
        });
        
        $(document).on(`click.${tableId}`, `#${tableId} .btn-edit`, function(e) {
            e.preventDefault();
            e.stopPropagation();
            const orderId = $(this).data('id');
            
            if (typeof window.openEditModal === 'function') {
                window.openEditModal(orderId);
            } else {
                console.error('Edit modal not available');
            }
        });
        
        $(document).on(`click.${tableId}`, `#${tableId} .btn-delete`, function(e) {
            e.preventDefault();
            e.stopPropagation();
            const orderId = $(this).data('id');
            
            if (typeof window.deleteOrder === 'function') {
                window.deleteOrder(orderId);
            } else {
                console.error('Delete function not available');
            }
        });
    }
    
    extractOrderId(orderCell) {
        // Handle both SAL-00019 and SAL-25090311272093 formats
        let match = orderCell.match(/SAL-(\d+)/);
        if (match) {
            // For the new format like SAL-25090311272093, we need to get the actual ID
            // from the data-id attribute of buttons, not from the order number
            return match[1];
        }
        return null;
    }
    
    onDrawCallback() {
        // Re-initialize Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Dispose existing tooltips to prevent memory leaks and conflicts
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const existingTooltips = document.querySelectorAll(`#${this.config.tableId} [data-bs-toggle="tooltip"]`);
            existingTooltips.forEach(el => {
                const tooltipInstance = bootstrap.Tooltip.getInstance(el);
                if (tooltipInstance) {
                    tooltipInstance.dispose();
                }
            });
            
            // Create new tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll(`#${this.config.tableId} [data-bs-toggle="tooltip"]`));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
        
        // Note: Status dropdowns and action buttons are now handled via event delegation
        // in setupRowHandlers() to prevent guid conflicts
    }
    
    onInitComplete() {
        console.log(`DataTable ${this.config.tableId} initialized successfully`);
        
        // Update filter count on initialization
        this.updateActiveFiltersCount();
        
        // Enable tooltips
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }
    
    
    handleAjaxError(xhr, error, thrown) {
        console.error('DataTable AJAX Error:', error, thrown);
        
        // Show user-friendly error message
        const errorMsg = xhr.responseJSON?.message || 'Failed to load data. Please try again.';
        
        // You can integrate with your notification system here
        if (typeof showNotification === 'function') {
            showNotification('Error', errorMsg, 'error');
        }
    }
    
    // Utility methods
    getStartOfWeek() {
        const now = new Date();
        const day = now.getDay();
        const diff = now.getDate() - day + (day === 0 ? -6 : 1);
        return new Date(now.setDate(diff)).toISOString().split('T')[0];
    }
    
    getEndOfWeek() {
        const now = new Date();
        const day = now.getDay();
        const diff = now.getDate() - day + 7;
        return new Date(now.setDate(diff)).toISOString().split('T')[0];
    }
    
    // Public methods for external access
    reload() {
        this.refreshTable();
    }
    
    getTable() {
        return this.table;
    }
    
    destroy() {
        if (this.table) {
            this.table.destroy();
        }
        
        // Clean up event handlers
        const tableId = this.config.tableId;
        $(document).off(`click.${tableId}`);
        $(document).off(`change.${tableId}`);
    }
}

// Global instances
window.salesOrderDataTables = window.salesOrderDataTables || {};

// Factory function to create DataTable instances
window.createSalesOrderDataTable = function(config) {
    const instance = new SalesOrderDataTable(config);
    window.salesOrderDataTables[config.tableId] = instance;
    return instance;
};

// Global refresh function
window.refreshAllSalesOrderTables = function() {
    Object.values(window.salesOrderDataTables).forEach(table => {
        table.reload();
    });
};