// Dashboard JavaScript adaptado para páginas públicas con datos reales
document.addEventListener('DOMContentLoaded', function() {
    // Initialize filter state variables
    window.incompleteFilterActive = false;
    window.completeFilterActive = false;
    window.currentDayFilter = '';
    
    console.log('🚀 Initializing Public Dashboard with Real Data');
    
    // Check if jQuery and DataTables are available
    function waitForDependencies() {
        return new Promise((resolve) => {
            function checkDeps() {
                if (typeof window.$ !== 'undefined' && 
                    typeof window.jQuery !== 'undefined' && 
                    typeof window.$.fn.DataTable !== 'undefined') {
                    resolve();
                } else {
                    console.log('⏳ Waiting for dependencies...');
                    setTimeout(checkDeps, 100);
                }
            }
            checkDeps();
        });
    }
    
    // Initialize tables after dependencies are ready
    waitForDependencies().then(() => {
        initializeTables();
        setupEventHandlers();
        setupWidgetFiltering();
        startRealTimeSync();
    });
    
    function initializeTables() {
        const $ = window.jQuery;
        console.log('📊 Initializing tables with real data...');
        
        // Initialize Inventory Table with public endpoints
        window.inventoryTable = $('#inventoryTable').DataTable({
            "scrollY": "510px",
            "scrollCollapse": true,
            processing: true,
            serverSide: false,
            ajax: {
                url: '/mda_nuevo/api/public/inventory', // ✅ Endpoint público
                type: 'GET',
                dataSrc: function(json) {
                    console.log('📦 Inventory data received:', json);
                    
                    let data = json.data || [];
                    
                    if (!Array.isArray(data)) {
                        console.warn('⚠️ Invalid data format, using empty array');
                        data = [];
                    }
                    
                    // Update stats with real data
                    updateInventoryStats(data);
                    
                    // Load order info
                    loadOrderInfoForInventory();
                    
                    return data;
                },
                error: function(xhr, error, thrown) {
                    console.error('❌ Error loading inventory:', error);
                    showToast('Error loading inventory data', 'error');
                }
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `<div class="form-check">
                            <input class="form-check-input inventory-checkbox" type="checkbox" value="${row.id}" data-stock="${row.stock_number}">
                        </div>`;
                    }
                },
                {
                    data: 'date_detail',
                    render: function(data, type, row) {
                        return data ? `<span class="date-enhanced">${data}</span>` : '-';
                    }
                },
                {
                    data: 'days_detail',
                    type: 'num',
                    render: function(data, type, row) {
                        if (type === 'sort' || type === 'type') {
                            return data || 0;
                        }
                        
                        if (!data || data === '' || data === 0) return '-';
                        const days = parseInt(data);
                        if (isNaN(days)) return '-';
                        
                        let badgeClass = 'bg-success';
                        if (days >= 6) badgeClass = 'bg-danger';
                        else if (days >= 2) badgeClass = 'bg-warning';
                        
                        const dayText = days === 1 ? 'day' : 'days';
                        return `<span class="badge ${badgeClass} days-badge">${days} ${dayText}</span>`;
                    }
                },
                {
                    data: 'keys',
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    data: 'stock_number',
                    render: function(data, type, row) {
                        if (!data) return '<div class="text-center">-</div>';
                        
                        return `<div class="d-flex align-items-center justify-content-center">
                            <span class="stock-number-enhanced">${data}</span>
                        </div>`;
                    }
                },
                {
                    data: 'vehicle',
                    render: function(data, type, row) {
                        return data ? `<span class="vehicle-info">${data}</span>` : '-';
                    }
                },
                {
                    data: 'notes',
                    render: function(data, type, row) {
                        if (!data || data.trim() === '') return '-';
                        const shortText = data.length > 30 ? data.substring(0, 30) + '...' : data;
                        return `<span class="text-muted" title="${data}">${shortText}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `<div class="status-service-info" data-stock="${row.stock_number}">
                            <span class="text-muted">Loading...</span>
                        </div>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `<button class="btn btn-info btn-sm view-details-btn" 
                                       data-stock="${row.stock_number}">
                            <i class="ri-eye-line me-1"></i>
                            View Details
                        </button>`;
                    }
                }
            ],
            order: [[2, 'desc']],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            responsive: true,
            language: {
                processing: '<span style="font-size: 0.6rem; color: rgba(108, 117, 125, 0.4);">•</span>',
                emptyTable: '<div class="text-center py-4"><i class="ri-store-3-line display-4 text-muted"></i><h6 class="mt-2">No inventory available</h6><p class="text-muted">Inventory data will appear here</p></div>'
            }
        });
        
        console.log('✅ Inventory table initialized successfully');
    }
    
    function setupEventHandlers() {
        const $ = window.jQuery;
        console.log('🔧 Setting up event handlers...');
        
        // Refresh button
        $('#refreshInventoryBtn').on('click', function() {
            const $btn = $(this);
            const originalHtml = $btn.html();
            
            $btn.html('<i class="ri-refresh-line me-1 spinner-border spinner-border-sm"></i> Refreshing...');
            $btn.prop('disabled', true);
            
            if (window.inventoryTable) {
                window.inventoryTable.ajax.reload(function() {
                    updateLastRefreshTime('Manual');
                    $btn.html(originalHtml);
                    $btn.prop('disabled', false);
                    loadOrderInfoForInventory();
                    showToast('Inventory refreshed successfully', 'success');
                });
            }
        });
        
        // Clear filters button
        $('#clearAllFilters').on('click', function() {
            clearAllFilters();
        });
        
        // Filter buttons
        $('#filterIncompleteBtn').on('click', function() {
            toggleIncompleteFilter();
        });
        
        $('#filterCompleteBtn').on('click', function() {
            toggleCompleteFilter();
        });
        
        // View details buttons
        $(document).on('click', '.view-details-btn', function() {
            const stockNumber = $(this).data('stock');
            const rowData = window.inventoryTable.row($(this).closest('tr')).data();
            showVehicleDetailsModal(stockNumber, rowData);
        });
        
        console.log('✅ Event handlers setup complete');
    }
    
    function setupWidgetFiltering() {
        const $ = window.jQuery;
        
        $('.filter-widget').on('click', function() {
            const filter = $(this).data('filter');
            applyWidgetFilter(filter, $(this));
        });
        
        $('.filter-widget').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).click();
            }
        });
    }
    
    function loadOrderInfoForInventory() {
        const $ = window.jQuery;
        console.log('🔍 Loading order info...');
        
        $.get('/mda_nuevo/api/public/orders') // ✅ Endpoint público
            .done(function(response) {
                console.log('📦 Order info received:', response);
                
                if (response.success && response.data) {
                    window.orderInfoLookup = response.data;
                    console.log('✅ Order info loaded:', Object.keys(response.data).length, 'items');
                    updateInventoryStatusColumns();
                } else {
                    console.log('⚠️ No order info data received');
                }
            })
            .fail(function(xhr, status, error) {
                console.error('❌ Failed to load order info:', status, error);
            });
    }
    
    function updateInventoryStats(data) {
        if (!Array.isArray(data)) return;
        
        const total = data.length;
        let recentItems = 0;
        let moderateItems = 0; 
        let agedItems = 0;
        const daysData = [];
        
        data.forEach(row => {
            const days = parseInt(row.days_detail) || 0;
            if (days >= 0) {
                daysData.push(days);
                
                if (days <= 1) {
                    recentItems++;
                } else if (days >= 2 && days <= 5) {
                    moderateItems++;
                } else if (days >= 6) {
                    agedItems++;
                }
            }
        });
        
        const avgDays = daysData.length > 0 ? Math.round(daysData.reduce((a, b) => a + b, 0) / daysData.length) : 0;
        
        // Update UI elements
        $('#totalInventoryItems').text(total);
        $('#avgDaysNumber').text(avgDays);
        $('#recentItems').text(recentItems);
        $('#moderateItems').text(moderateItems);
        $('#agedItems').text(agedItems);
        
        console.log(`📊 Stats updated - Total: ${total}, Avg Days: ${avgDays}`);
    }
    
    function updateInventoryStatusColumns() {
        const $ = window.jQuery;
        console.log('🔄 Updating status columns...');
        
        $('.status-service-info').each(function() {
            const $element = $(this);
            const stockNumber = $element.data('stock');
            
            if (stockNumber && window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
                const orderInfo = window.orderInfoLookup[stockNumber];
                
                const statusColors = {
                    'pending': 'warning',
                    'in_progress': 'info',
                    'completed': 'success',
                    'cancelled': 'danger'
                };
                
                const statusColor = statusColors[orderInfo.status] || 'secondary';
                
                const html = `<div class="d-flex flex-column align-items-center">
                    <span class="badge bg-${statusColor} px-2 py-1 fw-bold text-uppercase" style="font-size: 0.65rem;">
                        ${orderInfo.status}
                    </span>
                </div>`;
                
                $element.html(html);
            } else {
                $element.html('<div class="d-flex flex-column align-items-center"><span class="badge bg-secondary-subtle text-secondary px-2 py-1 fw-bold" style="font-size: 0.65rem;">NO STATUS</span></div>');
            }
        });
    }
    
    function applyWidgetFilter(filter, $widget) {
        const $ = window.jQuery;
        
        $('.filter-widget').removeClass('active');
        $widget.addClass('active');
        window.currentDayFilter = filter;
        
        applyDayRangeFilter(filter);
        
        const filterName = filter === '' ? 'All Items' : 
                          filter === '0-1' ? 'Recent (0-1 days)' :
                          filter === '2-5' ? 'Moderate (2-5 days)' :
                          filter === '6+' ? 'Aged (6+ days)' : filter;
        
        showToast(`Filter applied: ${filterName}`, 'info');
    }
    
    function applyDayRangeFilter(range) {
        if (!window.inventoryTable) return;
        
        $.fn.dataTable.ext.search = [];
        
        if (range && range !== '') {
            const dayRangeFilterFn = function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'inventoryTable') return true;
                
                const table = window.inventoryTable;
                const rowData = table.row(dataIndex).data();
                const days = rowData ? rowData.days_detail : null;
                
                if (days === null || days === undefined || days === '' || isNaN(days)) {
                    return range === '0-1';
                }
                
                const daysNum = parseInt(days);
                
                switch(range) {
                    case '0-1':
                        return daysNum <= 1;
                    case '2-5':
                        return daysNum >= 2 && daysNum <= 5;
                    case '6+':
                        return daysNum >= 6;
                    default:
                        return true;
                }
            };
            
            $.fn.dataTable.ext.search.push(dayRangeFilterFn);
        }
        
        window.inventoryTable.draw();
    }
    
    function clearAllFilters() {
        const $ = window.jQuery;
        
        $('.filter-widget').removeClass('active');
        window.currentDayFilter = '';
        window.incompleteFilterActive = false;
        window.completeFilterActive = false;
        
        $.fn.dataTable.ext.search = [];
        
        if (window.inventoryTable) {
            window.inventoryTable.draw();
        }
        
        showToast('All filters cleared', 'info');
    }
    
    function toggleIncompleteFilter() {
        window.incompleteFilterActive = !window.incompleteFilterActive;
        
        if (window.incompleteFilterActive) {
            window.completeFilterActive = false;
            applyIncompleteFilter();
            showToast('Showing incomplete items only', 'info');
        } else {
            clearAllFilters();
        }
    }
    
    function toggleCompleteFilter() {
        window.completeFilterActive = !window.completeFilterActive;
        
        if (window.completeFilterActive) {
            window.incompleteFilterActive = false;
            applyCompleteFilter();
            showToast('Showing complete items only', 'info');
        } else {
            clearAllFilters();
        }
    }
    
    function applyIncompleteFilter() {
        if (!window.inventoryTable) return;
        
        $.fn.dataTable.ext.search = [];
        
        const incompleteFilterFn = function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'inventoryTable') return true;
            
            const table = window.inventoryTable;
            const rowData = table.row(dataIndex).data();
            
            if (!rowData || !rowData.stock_number) return true;
            
            const stockNumber = rowData.stock_number.toString().trim();
            
            if (window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
                const orderInfo = window.orderInfoLookup[stockNumber];
                return orderInfo.status !== 'completed';
            }
            
            return true;
        };
        
        $.fn.dataTable.ext.search.push(incompleteFilterFn);
        window.inventoryTable.draw();
    }
    
    function applyCompleteFilter() {
        if (!window.inventoryTable) return;
        
        $.fn.dataTable.ext.search = [];
        
        const completeFilterFn = function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'inventoryTable') return true;
            
            const table = window.inventoryTable;
            const rowData = table.row(dataIndex).data();
            
            if (!rowData || !rowData.stock_number) return false;
            
            const stockNumber = rowData.stock_number.toString().trim();
            
            if (window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
                const orderInfo = window.orderInfoLookup[stockNumber];
                return orderInfo.status === 'completed';
            }
            
            return false;
        };
        
        $.fn.dataTable.ext.search.push(completeFilterFn);
        window.inventoryTable.draw();
    }
    
    function showVehicleDetailsModal(stockNumber, rowData) {
        const details = `
            <div class="vehicle-details">
                <h5>Vehicle Details</h5>
                <table class="table table-sm">
                    <tr><td><strong>Stock Number:</strong></td><td>${stockNumber}</td></tr>
                    <tr><td><strong>Vehicle:</strong></td><td>${rowData.vehicle || 'N/A'}</td></tr>
                    <tr><td><strong>Date in Detail:</strong></td><td>${rowData.date_detail || 'N/A'}</td></tr>
                    <tr><td><strong>Days in Detail:</strong></td><td>${rowData.days_detail || 'N/A'}</td></tr>
                    <tr><td><strong>Keys:</strong></td><td>${rowData.keys || 'N/A'}</td></tr>
                    <tr><td><strong>Notes:</strong></td><td>${rowData.notes || 'N/A'}</td></tr>
                </table>
            </div>
        `;
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Vehicle Information',
                html: details,
                width: 600,
                showCloseButton: true,
                focusConfirm: false
            });
        } else {
            alert(`Vehicle Details:\n\nStock: ${stockNumber}\nVehicle: ${rowData.vehicle}\nDays: ${rowData.days_detail}\nNotes: ${rowData.notes}`);
        }
    }
    
    function updateLastRefreshTime(type = 'Auto') {
        const lastRefreshInfo = document.getElementById('lastRefreshInfo');
        if (lastRefreshInfo) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            lastRefreshInfo.textContent = `Last refresh: ${timeString} (${type})`;
        }
    }
    
    function startRealTimeSync() {
        console.log('🔄 Starting real-time sync...');
        
        const pollingInterval = 30000; // 30 seconds
        
        setInterval(() => {
            if (window.inventoryTable) {
                window.inventoryTable.ajax.reload(function() {
                    updateLastRefreshTime('Auto');
                    loadOrderInfoForInventory();
                }, false);
            }
        }, pollingInterval);
        
        console.log('✅ Real-time sync started');
    }
    
    // Toast function
    function showToast(message, type = 'success') {
        if (typeof window.showToast === 'function') {
            window.showToast(message, type);
            return;
        }
        
        if (typeof Toastify !== 'undefined') {
            const colors = {
                'success': "#10b981",
                'info': "#3b82f6", 
                'warning': "#f59e0b",
                'error': "#ef4444"
            };
            
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: colors[type] || colors['success'],
                close: true,
                stopOnFocus: true
            }).showToast();
            return;
        }
        
        console.log(`${type.toUpperCase()}: ${message}`);
    }
    
    // Make functions globally available
    window.showToast = showToast;
    window.loadOrderInfoForInventory = loadOrderInfoForInventory;
    
    console.log('✅ Public Dashboard initialization complete');
});
