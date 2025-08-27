/**
 * Status Overview Widget - FIXED
 * Shows vehicle counts by status with hover details
 */

class StatusOverviewWidget {
    constructor() {
        this.statusData = {};
        this.vehiclesByStatus = {};
        this.currentTooltip = null;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.loadStatusData();
    }
    
    setupEventListeners() {
        // Listen for stats updated event (same timing as main widgets)
        document.addEventListener('statsUpdated', () => {
            this.updateFromTableData();
        });
        
        // Also listen for table data updates
        document.addEventListener('tableDataLoaded', () => {
            this.updateFromTableData();
        });
        
        // Listen for DataTable draw events
        if (window.jQuery) {
            window.jQuery(document).on('draw.dt', '#inventoryTable', () => {
                this.updateFromTableData();
            });
        }
    }
    
    updateFromTableData() {
        if (!window.inventoryTable || !window.orderInfoLookup) return;
        
        // Get current table data
        const tableData = window.inventoryTable.data().toArray();
        
        // Initialize status counts
        this.statusData = {
            'completed': { count: 0, vehicles: [] },
            'pending': { count: 0, vehicles: [] },
            'in_progress': { count: 0, vehicles: [] },
            'cancelled': { count: 0, vehicles: [] },
            'no_status': { count: 0, vehicles: [] }
        };
        
        // Process each vehicle
        tableData.forEach(vehicle => {
            const stockNumber = vehicle.stock_number;
            const orderInfo = window.orderInfoLookup[stockNumber];
            
            let status = 'no_status';
            if (orderInfo && orderInfo.status) {
                status = orderInfo.status;
            }
            
            // Ensure status exists in our data
            if (!this.statusData[status]) {
                this.statusData[status] = { count: 0, vehicles: [] };
            }
            
            this.statusData[status].count++;
            this.statusData[status].vehicles.push({
                stock: stockNumber,
                vehicle: vehicle.vehicle || 'Unknown Vehicle',
                date: vehicle.date_detail || '',
                days: vehicle.days_detail || 0
            });
        });
        
        this.renderStatusWidget();
    }
    
    renderStatusWidget() {
        const container = document.getElementById('statusGrid');
        if (!container) return;
        
        // Clear existing content
        container.innerHTML = '';
        
        // Status display configuration
        const statusConfig = {
            'completed': { label: 'Completed', icon: 'bx bx-check-circle', class: 'status-completed' },
            'in_progress': { label: 'In Progress', icon: 'bx bx-loader-circle', class: 'status-in-progress' },
            'pending': { label: 'Pending', icon: 'bx bx-time-five', class: 'status-pending' },
            'cancelled': { label: 'Cancelled', icon: 'bx bx-x-circle', class: 'status-cancelled' },
            'no_status': { label: 'No Status', icon: 'bx bx-help-circle', class: 'status-no-status' }
        };
        
        // Create status items
        Object.keys(this.statusData).forEach(statusKey => {
            const statusInfo = this.statusData[statusKey];
            const config = statusConfig[statusKey];
            
            if (!config) return;
            
            const statusItem = document.createElement('div');
            statusItem.className = `status-item ${config.class}`;
            statusItem.setAttribute('data-status', statusKey);
            
            statusItem.innerHTML = `
                <div class="status-icon">
                    <i class="${config.icon}"></i>
                </div>
                <div class="status-name">${config.label}</div>
                <div class="status-count">${statusInfo.count}</div>
            `;
            
            // Add hover events with proper context and debugging
            statusItem.addEventListener('mouseenter', (e) => {
                this.showVehicleTooltip(statusItem, statusKey, statusInfo.vehicles);
            });
            
            statusItem.addEventListener('mouseleave', () => {
                this.hideVehicleTooltip();
            });
            
            container.appendChild(statusItem);
        });
    }
    
    showVehicleTooltip(element, status, vehicles) {
        
        // Remove existing tooltip
        this.hideVehicleTooltip();
        
        if (vehicles.length === 0) {
            return;
        }
        
        // Create tooltip
        const tooltip = document.createElement('div');
        tooltip.className = 'vehicle-tooltip';
        
        const statusLabels = {
            'completed': 'Completed Vehicles',
            'in_progress': 'Vehicles in Progress',
            'pending': 'Pending Vehicles',
            'cancelled': 'Cancelled Vehicles',
            'no_status': 'Vehicles Without Status'
        };
        
        let vehicleListHTML = vehicles.map(vehicle => `
            <div class="vehicle-item">
                <span class="vehicle-stock">${vehicle.stock}</span>
                <span class="vehicle-name">${vehicle.vehicle}</span>
                <span class="vehicle-days">${vehicle.days}d</span>
            </div>
        `).join('');
        
        tooltip.innerHTML = `
            <div class="tooltip-header">
                ${statusLabels[status] || 'Vehicles'} (${vehicles.length})
            </div>
            <div class="vehicle-list">
                ${vehicleListHTML}
            </div>
        `;
        
        // Get element position
        const rect = element.getBoundingClientRect();
        const scrollY = window.pageYOffset || document.documentElement.scrollTop;
        const scrollX = window.pageXOffset || document.documentElement.scrollLeft;
        
        // Calculate tooltip dimensions (approximate)
        const tooltipWidth = 320;
        const tooltipHeight = Math.min(250, vehicles.length * 30 + 60); // Estimate
        
        // Calculate position - try above first
        let left = rect.left + scrollX + (rect.width / 2) - (tooltipWidth / 2);
        let top = rect.top + scrollY - tooltipHeight - 15; // 15px gap above
        
        // Adjust if goes off screen
        if (left < 20) left = 20;
        if (left + tooltipWidth > window.innerWidth - 20) {
            left = window.innerWidth - tooltipWidth - 20;
        }
        
        // If no space above, show below
        if (top < 20) {
            top = rect.bottom + scrollY + 15;
            tooltip.classList.add('tooltip-below');
        }
        
        // Simple positioning - directly above the element
        tooltip.style.position = 'absolute';
        tooltip.style.bottom = '110%'; // Position above the element
        tooltip.style.left = '50%';
        tooltip.style.transform = 'translateX(-50%)';
        
        
        // Append to the status item (not body)
        element.style.position = 'relative'; // Ensure parent is positioned
        element.appendChild(tooltip);
        this.currentTooltip = tooltip;
        
        // Show immediately
        requestAnimationFrame(() => {
            tooltip.classList.add('show');
        });
    }
    
    hideVehicleTooltip() {
        if (this.currentTooltip) {
            this.currentTooltip.classList.remove('show');
            
            setTimeout(() => {
                if (this.currentTooltip && this.currentTooltip.parentNode) {
                    this.currentTooltip.parentNode.removeChild(this.currentTooltip);
                }
                this.currentTooltip = null;
            }, 200);
        }
    }
    
    loadStatusData() {
        // Show loading state initially
        this.renderLoadingState();
        
        // Auto-refresh every 30 seconds
        setInterval(() => {
            if (window.inventoryTable && window.orderInfoLookup) {
                this.updateFromTableData();
            }
        }, 30000);
    }
    
    renderLoadingState() {
        const container = document.getElementById('statusGrid');
        if (!container) return;
        
        // Show loading placeholders for each status
        const statuses = [
            { key: 'completed', label: 'Completed', icon: 'bx bx-check-circle' },
            { key: 'in_progress', label: 'In Progress', icon: 'bx bx-loader-circle' },
            { key: 'pending', label: 'Pending', icon: 'bx bx-time-five' },
            { key: 'cancelled', label: 'Cancelled', icon: 'bx bx-x-circle' },
            { key: 'no_status', label: 'No Status', icon: 'bx bx-help-circle' }
        ];
        
        container.innerHTML = statuses.map(status => `
            <div class="status-item status-${status.key} loading">
                <div class="status-icon">
                    <i class="${status.icon}"></i>
                </div>
                <div class="status-name">${status.label}</div>
                <div class="status-count">
                    <div class="loading-dots" style="justify-content: center; transform: scale(0.8);">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        `).join('');
        
    }
    
    // Public API
    refresh() {
        this.updateFromTableData();
    }
    
    getStatusData() {
        return this.statusData;
    }
}

// Initialize the status widget
let statusWidget;

// Wait for DOM and dependencies
document.addEventListener('DOMContentLoaded', () => {
    // Wait for table to be initialized
    function initStatusWidget() {
        if (typeof window.inventoryTable !== 'undefined' && window.inventoryTable) {
            statusWidget = new StatusOverviewWidget();
            window.StatusOverviewWidget = statusWidget;
            console.log('✅ Status Overview Widget initialized');
        } else {
            setTimeout(initStatusWidget, 500);
        }
    }
    
    initStatusWidget();
});

// Also listen for table ready events
window.addEventListener('tablesReady', () => {
    if (!statusWidget) {
        statusWidget = new StatusOverviewWidget();
        window.StatusOverviewWidget = statusWidget;
    }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = StatusOverviewWidget;
}