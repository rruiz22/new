/**
 * Status Overview Widget
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
        // Listen for table data updates
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
            'no_status': { count: 0, vehicles: [] }\n        };\n        \n        // Process each vehicle\n        tableData.forEach(vehicle => {\n            const stockNumber = vehicle.stock_number;\n            const orderInfo = window.orderInfoLookup[stockNumber];\n            \n            let status = 'no_status';\n            if (orderInfo && orderInfo.status) {\n                status = orderInfo.status;\n            }\n            \n            // Ensure status exists in our data\n            if (!this.statusData[status]) {\n                this.statusData[status] = { count: 0, vehicles: [] };\n            }\n            \n            this.statusData[status].count++;\n            this.statusData[status].vehicles.push({\n                stock: stockNumber,\n                vehicle: vehicle.vehicle || 'Unknown Vehicle',\n                date: vehicle.date_detail || '',\n                days: vehicle.days_detail || 0\n            });\n        });\n        \n        this.renderStatusWidget();\n    }\n    \n    renderStatusWidget() {\n        const container = document.getElementById('statusGrid');\n        if (!container) return;\n        \n        // Clear existing content\n        container.innerHTML = '';\n        \n        // Status display configuration\n        const statusConfig = {\n            'completed': { label: 'Completed', icon: 'ri-check-line', class: 'status-completed' },\n            'in_progress': { label: 'In Progress', icon: 'ri-time-line', class: 'status-in-progress' },\n            'pending': { label: 'Pending', icon: 'ri-clock-line', class: 'status-pending' },\n            'cancelled': { label: 'Cancelled', icon: 'ri-close-line', class: 'status-cancelled' },\n            'no_status': { label: 'No Status', icon: 'ri-question-line', class: 'status-no-status' }\n        };\n        \n        // Create status items\n        Object.keys(this.statusData).forEach(statusKey => {\n            const statusInfo = this.statusData[statusKey];\n            const config = statusConfig[statusKey];\n            \n            if (!config) return;\n            \n            const statusItem = document.createElement('div');\n            statusItem.className = `status-item ${config.class}`;\n            statusItem.setAttribute('data-status', statusKey);\n            \n            statusItem.innerHTML = `\n                <div class=\"status-icon\">\n                    <i class=\"${config.icon}\"></i>\n                </div>\n                <div class=\"status-name\">${config.label}</div>\n                <div class=\"status-count\">${statusInfo.count}</div>\n            `;\n            \n            // Add hover events\n            statusItem.addEventListener('mouseenter', (e) => {\n                this.showVehicleTooltip(e.target, statusKey, statusInfo.vehicles);\n            });\n            \n            statusItem.addEventListener('mouseleave', () => {\n                this.hideVehicleTooltip();\n            });\n            \n            container.appendChild(statusItem);\n        });\n    }\n    \n    showVehicleTooltip(element, status, vehicles) {\n        // Remove existing tooltip\n        this.hideVehicleTooltip();\n        \n        if (vehicles.length === 0) return;\n        \n        // Create tooltip\n        const tooltip = document.createElement('div');\n        tooltip.className = 'vehicle-tooltip';\n        \n        const statusLabels = {\n            'completed': 'Completed Vehicles',\n            'in_progress': 'Vehicles in Progress',\n            'pending': 'Pending Vehicles',\n            'cancelled': 'Cancelled Vehicles',\n            'no_status': 'Vehicles Without Status'\n        };\n        \n        let vehicleListHTML = vehicles.map(vehicle => `\n            <div class=\"vehicle-item\">\n                <span class=\"vehicle-stock\">${vehicle.stock}</span>\n                <span class=\"vehicle-name\">${vehicle.vehicle}</span>\n                <span class=\"vehicle-days\">${vehicle.days}d</span>\n            </div>\n        `).join('');\n        \n        tooltip.innerHTML = `\n            <div class=\"tooltip-header\">\n                ${statusLabels[status] || 'Vehicles'} (${vehicles.length})\n            </div>\n            <div class=\"vehicle-list\">\n                ${vehicleListHTML}\n            </div>\n        `;\n        \n        // Position and show tooltip\n        element.appendChild(tooltip);\n        this.currentTooltip = tooltip;\n        \n        // Show with delay\n        setTimeout(() => {\n            tooltip.classList.add('show');\n        }, 100);\n    }\n    \n    hideVehicleTooltip() {\n        if (this.currentTooltip) {\n            this.currentTooltip.classList.remove('show');\n            \n            setTimeout(() => {\n                if (this.currentTooltip && this.currentTooltip.parentNode) {\n                    this.currentTooltip.parentNode.removeChild(this.currentTooltip);\n                }\n                this.currentTooltip = null;\n            }, 200);\n        }\n    }\n    \n    loadStatusData() {\n        // Initial load - will be updated when table data is available\n        this.renderStatusWidget();\n        \n        // Auto-refresh every 30 seconds\n        setInterval(() => {\n            if (window.inventoryTable && window.orderInfoLookup) {\n                this.updateFromTableData();\n            }\n        }, 30000);\n    }\n    \n    // Public API\n    refresh() {\n        this.updateFromTableData();\n    }\n    \n    getStatusData() {\n        return this.statusData;\n    }\n}\n\n// Initialize the status widget\nlet statusWidget;\n\n// Wait for DOM and dependencies\ndocument.addEventListener('DOMContentLoaded', () => {\n    // Wait for table to be initialized\n    function initStatusWidget() {\n        if (typeof window.inventoryTable !== 'undefined' && window.inventoryTable) {\n            statusWidget = new StatusOverviewWidget();\n            window.StatusOverviewWidget = statusWidget;\n            console.log('✅ Status Overview Widget initialized');\n        } else {\n            setTimeout(initStatusWidget, 500);\n        }\n    }\n    \n    initStatusWidget();\n});\n\n// Also listen for table ready events\nwindow.addEventListener('tablesReady', () => {\n    if (!statusWidget) {\n        statusWidget = new StatusOverviewWidget();\n        window.StatusOverviewWidget = statusWidget;\n    }\n});\n\n// Export for module systems\nif (typeof module !== 'undefined' && module.exports) {\n    module.exports = StatusOverviewWidget;\n}"}