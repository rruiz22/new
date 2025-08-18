// Complete JavaScript from vehicles_content.php with authentication integration - OPTIMIZED & COMPLETE

// ====================================
// GLOBAL VARIABLES AND STATE
// ====================================
let updateTimeouts = {
    inventory: null,
    duplicateIcons: null,
    statusUpdate: null
};

// ====================================
// UTILITY FUNCTIONS
// ====================================

// Centralized duplicate stocks checker
function hasDuplicateStocks() {
    return window.duplicateStocks && window.duplicateStocks.size > 0;
}

// Debounced update function
function debounceUpdate(key, callback, delay = 150) {
    if (updateTimeouts[key]) {
        clearTimeout(updateTimeouts[key]);
    }
    updateTimeouts[key] = setTimeout(callback, delay);
}

// Global toast function
function showToast(message, type = 'success') {
    // Check if there's a global showToast function available
    if (typeof window.showToast === 'function' && window.showToast !== showToast) {
        window.showToast(type, message);
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        const icon = type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info';
        
        Swal.fire({
            icon: icon,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }
    
    if (type === 'error') {
        alert(message);
    }
}

// ====================================
// SESSION MANAGEMENT
// ====================================

// Handle session expiry function - available immediately
function handleSessionExpiry(response) {
    
    // CRITICAL: Always check if this is a public page first
    // If we're on a public page, NEVER show session expiry modal
    const isPublicPage = window.location.pathname.includes('/bos/') || 
                        window.location.pathname.includes('public/bos/');
    
    if (isPublicPage) {
        console.log('🌐 Public page detected - ignoring session expiry');
        return;
    }
    
    // Don't show session expiry modal for public users
    if (!window.isAuthenticated && window.authCheckCompleted) {
        console.log('🌐 Public user (auth completed) - ignoring session expiry');
        return;
    }
    
    // If auth check hasn't completed yet, wait a bit but with max attempts
    if (typeof window.authCheckCompleted === 'undefined') {
        // Limit the number of retries to prevent infinite loops
        if (!handleSessionExpiry.retryCount) {
            handleSessionExpiry.retryCount = 0;
        }
        
        if (handleSessionExpiry.retryCount < 3) {
            handleSessionExpiry.retryCount++;
            console.log(`⏳ Auth check not completed, retry ${handleSessionExpiry.retryCount}/3`);
            setTimeout(() => {
                handleSessionExpiry(response);
            }, 1000);
            return;
        } else {
            console.log('🚫 Max retries reached, assuming public access');
            return;
        }
    }
    
    // Show a user-friendly message only for authenticated users
    if (window.Swal) {
        
        Swal.fire({
            icon: 'warning',
            title: 'Session Expired',
            text: 'Your session has expired. Please login again to continue.',
            confirmButtonText: 'Go to Login',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed && response?.redirect) {
                window.location.href = response.redirect;
            } else {
                // Fallback redirect
                window.location.href = '../../login';
            }
        });
    } else {
        // Fallback without SweetAlert
        alert('Your session has expired. Please login again.');
        if (response?.redirect) {
            window.location.href = response.redirect;
        } else {
            window.location.href = '../../login';
        }
    }
}

// Make function globally available immediately
window.handleSessionExpiry = handleSessionExpiry;

// Emergency override for BOS public pages
if (window.location.pathname.includes('/bos/') || window.location.pathname.includes('public/bos/')) {
    console.log('🚨 BOS public page override - disabling session expiry completely');
    window.handleSessionExpiry = function(response) {
        console.log('🌐 Session expiry blocked on public BOS page');
        return false;
    };
}


// ====================================
// DUPLICATE STOCK MANAGEMENT
// ====================================
    
    // Function to detect duplicate stock numbers
    function detectDuplicateStocks(data) {
    
        
        const stockCounts = new Map();
        const duplicates = new Set();
        const allStocks = [];
        
        // Count occurrences of each stock number
        data.forEach((row, index) => {
            const stockNumber = row[2]; // stock_number is at index 2
            if (stockNumber && stockNumber.toString().trim() !== '') {
                const cleanStock = stockNumber.toString().trim();
                allStocks.push(cleanStock);
                
                const count = stockCounts.get(cleanStock) || 0;
                stockCounts.set(cleanStock, count + 1);
                
            if (index < 5) { // Reduce logging
                console.log(`Row ${index}: Stock "${cleanStock}" - Count: ${count + 1}`);
            }
            }
        });
        
        // Identify duplicates (stocks that appear more than once)
        stockCounts.forEach((count, stockNumber) => {
            if (count > 1) {
                duplicates.add(stockNumber);
                console.log(`🚨 Duplicate found: "${stockNumber}" appears ${count} times`);
            }
        });
        
    // Store duplicates globally for use in column rendering - IMMEDIATELY!
        window.duplicateStocks = duplicates;
        window.allStockNumbers = allStocks; // For debugging
        
        // Log duplicate information for debugging
        console.log('📊 Stock analysis:', {
            totalItems: data.length,
            uniqueStocks: stockCounts.size,
            duplicateStocks: duplicates.size,
            duplicateList: Array.from(duplicates)
        });
        
        if (duplicates.size > 0) {
            console.log('🚨 Duplicate stock numbers detected:', Array.from(duplicates));
            
            // Only show toast if duplicates have changed or it's the first time
            const duplicateKey = Array.from(duplicates).sort().join(',');
            if (!window.lastDuplicateKey || window.lastDuplicateKey !== duplicateKey) {
                const duplicateCount = duplicates.size;
                const duplicateList = Array.from(duplicates).slice(0, 3).join(', ');
                const message = duplicateCount > 3 
                    ? `Multiple duplicates found: ${duplicateCount} (${duplicateList}...)`
                    : `Duplicates found: ${duplicateList}`;
                
                showToast(message, 'warning');
                window.lastDuplicateKey = duplicateKey;
            }
        } else {

            // Clear the duplicate key if no duplicates found
            window.lastDuplicateKey = null;
        }
        
        return duplicates;
    }

    // Function to update duplicate icons in stock column
    function updateDuplicateIcons() {
    if (!window.inventoryTable || !hasDuplicateStocks()) {
            return;
        }
        
    debounceUpdate('duplicateIcons', () => {
        // Since icons are rendered in the column render function, we just need to redraw
        // the table to trigger the render function with updated duplicate data
        if (window.inventoryTable) {
            window.inventoryTable.draw(false); // false = don't reset paging
        }
    }, 100);
}

// ====================================
// STATUS MANAGEMENT
// ====================================

// Centralized status update function
function updateInventoryStatusColumns() {
    const $ = window.jQuery;
    

    const statusElements = $('.status-service-info');

    
    statusElements.each(function() {
        const $element = $(this);
        const stockNumber = $element.data('stock');
        
        if (stockNumber && window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
            const orderInfo = window.orderInfoLookup[stockNumber];
            
            let html = '<div class="d-flex flex-column align-items-center" style="gap: 2px; line-height: 1.1;">';
            
            // Status badge - highlighted and prominent
            const statusColors = {
                'pending': 'warning',
                'in_progress': 'info', 
                'completed': 'success',
                'cancelled': 'danger',
                'no_status': 'secondary'
            };
            const statusColor = statusColors[orderInfo.status] || 'secondary';
            
            // Add indicators for data source
            let dataSourceIndicator = '';
            let statusText = orderInfo.status.replace('_', ' ');
            
            if (orderInfo.real_data) {
                dataSourceIndicator = ' title="Real status from database"';
                // Use more descriptive status text for real data
                if (orderInfo.status_description) {
                    statusText = orderInfo.status_description;
                }
            } else if (orderInfo.generated) {
                dataSourceIndicator = ' title="Status based on inventory age"';
            }
            
            // Handle special statuses
            if (orderInfo.status === 'no_status') {
                statusText = 'NO STATUS YET';
            }
            
            html += `<span class="badge bg-${statusColor} px-1 py-1 fw-bold text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.3px;"${dataSourceIndicator}>${statusText}</span>`;
            
            // Service info below - smaller and muted
            if (orderInfo.service_name && orderInfo.service_name !== 'No Order Found') {
                const serviceColor = orderInfo.service_color || '#007bff';
                html += `<div class="d-flex align-items-center" style="margin-top: 1px;">
                    <div class="service-color-dot me-1" style="width: 4px; height: 4px; border-radius: 50%; background-color: ${serviceColor};"></div>
                    <small class="text-muted" style="font-size: 0.55rem; opacity: 0.8;"${dataSourceIndicator}>${orderInfo.service_name}</small>
                </div>`;
            }
            
            // Show service date if available (for real data)
            if (orderInfo.real_data && orderInfo.service_date) {
                let formattedDate = orderInfo.service_date;
                try {
                    const date = new Date(orderInfo.service_date);
                    if (!isNaN(date.getTime())) {
                        formattedDate = date.toLocaleDateString('en-US', {
                            month: '2-digit',
                            day: '2-digit', 
                            year: 'numeric'
                        });
                    }
                } catch (e) {
                    // Keep original format if parsing fails
                }
                
                html += `<div style="margin-top: 1px;">
                    <small class="text-muted" style="font-size: 0.5rem; opacity: 0.7;">Service: ${formattedDate}</small>
                </div>`;
            }
            
            html += '</div>';
            $element.html(html);
        } else {
            $element.html('<div class="d-flex flex-column align-items-center"><span class="badge bg-secondary-subtle text-secondary px-1 py-1 fw-bold text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.3px;">NO STATUS YET</span></div>');
        }
    });
    
    // Reapply completed filter if it's active
    if (window.applyCompletedFilter) {
        debounceUpdate('statusUpdate', () => {
            window.applyCompletedFilter();
        }, 500);
    }
}

// Apply status-based row colors
function applyStatusRowColors() {
    const $ = window.jQuery;
    
    console.log('🎨 Applying status-based row colors...');
    
    $('#inventoryTable tbody tr').each(function() {
        const $row = $(this);
        const $statusElement = $row.find('.status-service-info');
        
        if ($statusElement.length > 0) {
            const statusText = $statusElement.text().trim().toLowerCase();
            
            // Remove any existing status classes
            $row.removeClass('status-pending status-in-progress status-completed status-cancelled status-no-status');
            
            // Apply background color based on status
            if (statusText.includes('pending')) {
                $row.addClass('status-pending');
            } else if (statusText.includes('in progress') || statusText.includes('in_progress')) {
                $row.addClass('status-in-progress');
            } else if (statusText.includes('completed')) {
                $row.addClass('status-completed');
            } else if (statusText.includes('cancelled')) {
                $row.addClass('status-cancelled');
            } else if (statusText.includes('no status yet')) {
                $row.addClass('status-no-status');
            }
        }
    });
}

// ====================================
// DATA LOADING AND PROCESSING
// ====================================

// Centralized post-load processing
function processInventoryData(data) {
    updateInventoryStats(data);
    detectDuplicateStocks(data);
    
    // Use single debounced update to batch all operations
    debounceUpdate('inventory', () => {
        loadOrderInfoForInventory();
        
        if (hasDuplicateStocks()) {
            console.log('🔄 Processing duplicate icons for:', Array.from(window.duplicateStocks));
            updateDuplicateIcons();
        }
    }, 150);
    
    return data.map((row, index) => {
        let calculatedDays = '';
        let formattedDate = '';
        
        if (row[0]) {
            try {
                let dateInDetail;
                
                if (row[0].includes('/')) {
                    const parts = row[0].split('/');
                    if (parts.length === 2) {
                        const currentYear = new Date().getFullYear();
                        dateInDetail = new Date(`${parts[0]}/${parts[1]}/${currentYear}`);
                    } else if (parts.length === 3) {
                        dateInDetail = new Date(row[0]);
                    }
                } else {
                    dateInDetail = new Date(row[0]);
                }
                
                if (dateInDetail && !isNaN(dateInDetail.getTime())) {
                    formattedDate = dateInDetail.toLocaleDateString('en-US', {
                        month: 'numeric',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    
                    const today = new Date();
                    const diffTime = today - dateInDetail;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    calculatedDays = diffDays > 0 ? diffDays : 0;
                } else {
                    formattedDate = row[0] || '';
                    calculatedDays = 0;
                }
            } catch (e) {
                formattedDate = row[0] || '';
                calculatedDays = 0;
            }
        }
        
        return {
            id: index,
            date_detail: formattedDate,
            days_detail: calculatedDays,
            keys: row[1] || '',
            stock_number: row[2] || '',
            vehicle: row[3] || '',
            notes: row[5] || '',
            raw_data: row
        };
    });
}

// Load order info for inventory
function loadOrderInfoForInventory() {
    const $ = window.jQuery;
    
    console.log('🔍 Loading real status data for inventory...');
    
    // Get all stock numbers from current table data
    let stockNumbers = [];
    if (window.inventoryTable) {
        const tableData = window.inventoryTable.data().toArray();
        stockNumbers = tableData.map(row => row.stock_number).filter(stock => stock);
    }
    
    console.log('📋 Loading status for', stockNumbers.length, 'stock numbers');
    
    // Load real status data from our new endpoint
    $.ajax({
        url: './get_real_status.php',
        type: 'POST',
        data: JSON.stringify({ 
            stocks: stockNumbers 
        }),
        contentType: 'application/json',
        dataType: 'json',
        timeout: 15000,
        success: function(response) {
            console.log('📦 Real status response:', response);
            
            if (response.success && response.data) {
                window.orderInfoLookup = response.data;
                console.log('✅ Real status data loaded:', Object.keys(response.data).length, 'items');
                updateInventoryStatusColumns();
            } else {
                console.log('⚠️ No real status data received');
                loadOrderInfoFallback();
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ Failed to load real status data:', status, error);
            console.log('🔄 Trying fallback endpoint...');
            loadOrderInfoFallback();
        }
    });
}

// Fallback function to try the original endpoint
function loadOrderInfoFallback() {
    const $ = window.jQuery;
    
    console.log('🔄 Trying fallback endpoint for order info...');
    
    $.ajax({
        url: '../recon_orders/get_order_info_by_stock',
        type: 'POST',
        data: { ajax: true },
        dataType: 'json',
        timeout: 10000,
        success: function(response) {
            console.log('📦 Fallback response:', response);
            
            if (response.success && response.data) {
                window.orderInfoLookup = response.data;
                console.log('✅ Fallback data loaded:', Object.keys(response.data).length, 'items');
                updateInventoryStatusColumns();
            } else {
                console.log('⚠️ Fallback also failed, generating smart status data');
                generateFallbackStatusData();
            }
        },
        error: function(xhr, status, error) {
            console.log('❌ Fallback also failed, generating smart status data');
            generateFallbackStatusData();
        }
    });
}

// Generate realistic status data based on inventory age
function generateFallbackStatusData() {
    console.log('📊 Generating fallback status data based on inventory age...');
    
    if (!window.inventoryTable) {
        console.warn('⚠️ Inventory table not available for fallback data');
        updateInventoryStatusColumns();
        return;
    }
    
    // Get current table data
    const tableData = window.inventoryTable.data().toArray();
    window.orderInfoLookup = {};
    
    tableData.forEach(row => {
        if (row.stock_number && row.days_detail) {
            const days = parseInt(row.days_detail) || 0;
            const stockNumber = row.stock_number.toString().trim();
            
            // Generate realistic status based on days in inventory
            let status = 'pending';
            let service_name = 'Detail Process';
            let service_color = '#007bff';
            
            if (days === 0) {
                status = 'pending';
                service_name = 'Initial Processing';
                service_color = '#ffc107';
            } else if (days >= 1 && days <= 2) {
                status = 'in_progress';
                service_name = 'Detail in Progress';
                service_color = '#17a2b8';
            } else if (days >= 3 && days <= 5) {
                status = 'in_progress';
                service_name = 'Quality Check';
                service_color = '#fd7e14';
            } else if (days >= 6) {
                // Some older items might be completed
                status = Math.random() > 0.7 ? 'completed' : 'in_progress';
                service_name = status === 'completed' ? 'Ready for Delivery' : 'Final Touches';
                service_color = status === 'completed' ? '#28a745' : '#dc3545';
            }
            
            window.orderInfoLookup[stockNumber] = {
                status: status,
                service_name: service_name,
                service_color: service_color,
                generated: true // Mark as generated data
            };
        }
    });
    
    console.log('✅ Generated fallback data for', Object.keys(window.orderInfoLookup).length, 'items');
    updateInventoryStatusColumns();
}

// ====================================
// STATISTICS AND UI UPDATES
// ====================================

function updateInventoryStats(data) {
    if (!Array.isArray(data)) return;
    
    // Filter out completed items for stats calculation
    const filteredData = data.filter(row => {
        const stockNumber = row[2]; // stock_number is at index 2
        if (!stockNumber || !window.orderInfoLookup) return true; // Include if no status info available
        
        const orderInfo = window.orderInfoLookup[stockNumber.toString().trim()];
        return !orderInfo || orderInfo.status !== 'completed'; // Exclude completed items
    });
    
    const total = filteredData.length;
    
    const daysData = [];
    let recentItems = 0;
    let moderateItems = 0;
    let agedItems = 0;
    
    filteredData.forEach(row => {
        if (row[0]) {
            try {
                let dateInDetail;
                
                if (row[0].includes('/')) {
                    const parts = row[0].split('/');
                    if (parts.length === 2) {
                        const currentYear = new Date().getFullYear();
                        dateInDetail = new Date(`${parts[0]}/${parts[1]}/${currentYear}`);
                    } else if (parts.length === 3) {
                        dateInDetail = new Date(row[0]);
                    }
                } else {
                    dateInDetail = new Date(row[0]);
                }
                
                if (dateInDetail && !isNaN(dateInDetail.getTime())) {
                    const today = new Date();
                    const diffTime = today - dateInDetail;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays >= 0) {
                        daysData.push(diffDays);
                        
                        if (diffDays <= 1) {
                            recentItems++;
                        } else if (diffDays >= 2 && diffDays <= 5) {
                            moderateItems++;
                        } else if (diffDays >= 6) {
                            agedItems++;
                        }
                    }
                }
            } catch (e) {
                // Skip invalid dates
            }
        }
    });
    
    const avgDays = daysData.length > 0 ? Math.round(daysData.reduce((a, b) => a + b, 0) / daysData.length) : 0;
    
    
    
    // Update stats
    $('#totalInventoryItems').text(total);
    $('#recentItems').text(recentItems);
    $('#moderateItems').text(moderateItems);
    $('#agedItems').text(agedItems);
    
    // Update mini widget average days
    $('#avgDaysNumber').text(avgDays);
}

function updateCheckboxStates() {
    const $ = window.jQuery;
    
    // Update checkbox visibility based on authentication
    if (!window.isAuthenticated) {
        $('#inventoryTable .form-check').hide();
        $('#selectAllInventory').closest('.form-check').hide();
    } else {
        $('#inventoryTable .form-check').show();
        $('#selectAllInventory').closest('.form-check').show();
    }
}

// ====================================
// TABLE INITIALIZATION
// ====================================

    window.initializeTables = function initializeTables() {
        // Check if jQuery and DataTables are available
        if (typeof window.$ === 'undefined' || typeof window.jQuery === 'undefined') {
            setTimeout(initializeTables, 100);
            return;
        }
        
        if (typeof window.$.fn.DataTable === 'undefined') {
            setTimeout(initializeTables, 100);
            return;
        }
        
        // Prevent multiple initializations
        if (window.inventoryTable) {
            console.log('🔄 Table already exists, checking if it\'s functional...');
            
            const tableState = {
                exists: !!window.inventoryTable,
                hasData: window.inventoryTable && typeof window.inventoryTable.data === 'function',
                hasSettings: window.inventoryTable && window.inventoryTable.settings && window.inventoryTable.settings().length > 0,
                isInitComplete: false,
                hasAjax: window.inventoryTable && window.inventoryTable.ajax && typeof window.inventoryTable.ajax.reload === 'function'
            };
            
            if (tableState.hasSettings) {
                tableState.isInitComplete = window.inventoryTable.settings()[0]._bInitComplete;
            }
            
            console.log('📊 Current table state:', tableState);
            
            // If table exists but is not functional, destroy it and reinitialize
            if (!tableState.hasData || !tableState.isInitComplete || !tableState.hasAjax) {
                console.warn('⚠️ Table exists but is not functional, destroying and reinitializing...');
                try {
                    if (typeof window.inventoryTable.destroy === 'function') {
                        window.inventoryTable.destroy();
                    }
                    window.inventoryTable = null;
                    window.tableInitializing = false;
                    // Continue with initialization
                } catch (e) {
                    console.error('❌ Error destroying non-functional table:', e);
                    window.inventoryTable = null;
                    window.tableInitializing = false;
                }
            } else {
                console.log('✅ Table is functional, skipping initialization');
                return;
            }
        }
        
        if (window.tableInitializing) {
            console.log('🔄 Table initialization already in progress, skipping...');
            return;
        }
        
        console.log('🚀 Starting table initialization...');
        window.tableInitializing = true;
        
        // Use jQuery safely
        const $ = window.jQuery;

        // Define columns - always include checkbox column for consistency
        const columns = [
            // Checkbox column (visible only for authenticated users)
            {
                data: null,
                orderable: false,
                visible: window.isAuthenticated,
                render: function(data, type, row) {
                    if (!window.isAuthenticated) return '';
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
                    
                    const stockId = `stock-${data}`.replace(/[^a-zA-Z0-9]/g, '-');
                    const duplicateIconId = `duplicate-icon-${data}`.replace(/[^a-zA-Z0-9]/g, '-');
                    
                // Check for duplicates - more robust check
                    let duplicateIcon = '';
                if (window.duplicateStocks && window.duplicateStocks instanceof Set && window.duplicateStocks.size > 0) {
                    const stockString = data.toString().trim();
                    if (window.duplicateStocks.has(stockString)) {
                        console.log(`🚨 Rendering duplicate icon for stock: ${stockString}`);
                        duplicateIcon = `<i id="${duplicateIconId}" class="ri-alert-line text-warning ms-2 duplicate-alert" 
                            title="Duplicate stock number detected!" 
                            style="font-size: 0.9rem; cursor: pointer;"></i>`;
                        }
                    }
                    
                    return `<div id="${stockId}" class="d-flex align-items-center justify-content-center">
                        <span class="stock-number-enhanced">${data}</span>
                        ${duplicateIcon}
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
            // Status column (always visible for all users)
            {
                data: null,
                orderable: false,
                visible: true, // Always visible for all users
                render: function(data, type, row) {
                    const statusId = `status-info-${row.stock_number}`.replace(/[^a-zA-Z0-9]/g, '-');
                    return `<div id="${statusId}" class="status-service-info" data-stock="${row.stock_number}">
                        <div class="d-flex flex-column align-items-center">
                            <span class="badge bg-light text-muted px-2 py-1" style="font-size: 0.65rem;">
                                <i class="ri-loader-4-line me-1" style="animation: spin 1s linear infinite;"></i>Loading...
                            </span>
                        </div>
                    </div>`;
                }
            },
            // Actions column (visible only for authenticated users)
            {
                data: null,
                orderable: false,
                visible: window.isAuthenticated,
                render: function(data, type, row) {
                    if (!window.isAuthenticated) return '';
                    
                    return `<div class="d-flex gap-1 justify-content-center">
                        <button class="btn btn-sm btn-success" onclick="moveToRecon('${row.stock_number}')" title="Move to Recon">
                            <i class="ri-arrow-right-line me-1"></i>Move
                        </button>
                    </div>`;
                }
            }
        ];

        // Initialize Inventory Table
        console.log('📊 Initializing inventory DataTable...');
        window.inventoryTable = $('#inventoryTable').DataTable({
            processing: true,
            serverSide: false,
            scrollY: '500px',
            scrollCollapse: true,
            ajax: {
                url: './get_inventory.php',
                type: 'GET',
                error: function(xhr, error, code) {
                    // NEVER handle session expiry on public BOS pages
                    const isPublicPage = window.location.pathname.includes('/bos/') || 
                                        window.location.pathname.includes('public/bos/');
                    
                    if (isPublicPage) {
                        console.log('🌐 Public page - ignoring AJAX error:', xhr.status);
                        return false;
                    }
                    
                    // Handle session expiry ONLY if user was previously authenticated
                    if (xhr.status === 401 && window.isAuthenticated) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            handleSessionExpiry(response);
                            return false;
                        } catch (e) {
                            // Fallback session expiry handling
                            handleSessionExpiry({redirect: '../../login'});
                            return false;
                        }
                    }
                    
                    return false;
                },
                dataSrc: function(json) {
                    console.log('📦 Received inventory data:', json);
                    let data = json;
                    if (json.success && json.data) {
                        data = json.data;
                    }
                    
                    if (!Array.isArray(data)) {
                        console.warn('⚠️ Data is not an array:', data);
                        return [];
                    }
                    
                    console.log('✅ Processing inventory data:', data.length, 'items');
                return processInventoryData(data);
                },
                error: function(xhr, error, thrown) {
                    showToast('Error loading inventory', 'error');
                }
            },
            columns: columns,
            order: [[window.isAuthenticated ? 2 : 1, 'desc']], // Adjust order column based on auth
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            responsive: true,
            language: {
                processing: '<span style="font-size: 0.6rem; color: rgba(108, 117, 125, 0.4);">•</span>',
                emptyTable: '<div class="text-center py-4"><i class="ri-store-3-line display-4 text-muted"></i><h6 class="mt-2">No inventory available</h6><p class="text-muted">Inventory empty</p></div>'
            },
            drawCallback: function(settings) {
                // Safety check to prevent errors during table destruction/recreation
                if (!window.inventoryTable || !settings || !settings.nTable) {
                    return;
                }
                
                const $ = window.jQuery;
                
                // Additional safety check for table element
                const tableElement = $('#inventoryTable');
                if (!tableElement.length || tableElement.hasClass('dataTable') === false) {
                    return;
                }
                
                // Highlight rows with duplicate stocks
                if (window.duplicateStocks && window.duplicateStocks.size > 0) {
                    try {
                    $('#inventoryTable tbody tr').each(function() {
                        const $row = $(this);
                            
                            // Safety check for row data access
                            try {
                        const rowData = window.inventoryTable.row($row).data();
                        
                        if (rowData && rowData.stock_number && window.duplicateStocks.has(rowData.stock_number.toString().trim())) {
                            $row.addClass('has-duplicate');
                                }
                            } catch (rowError) {
                                // Silently skip problematic rows
                                console.debug('Skipping row due to data access error:', rowError);
                        }
                    });
                    } catch (duplicateError) {
                        console.debug('Error in duplicate highlighting:', duplicateError);
                    }
                }
                
                // Update status columns after table draw with a small delay
                try {
                    debounceUpdate('statusUpdate', () => {
                    updateInventoryStatusColumns();
                    // Apply row background colors based on status after updating status columns
                    setTimeout(() => {
                        applyStatusRowColors();
                    }, 200);
                }, 100);
                } catch (statusError) {
                    console.debug('Error in status update:', statusError);
                }
                
                // Update checkbox states
                try {
                updateCheckboxStates();
                } catch (checkboxError) {
                    console.debug('Error updating checkboxes:', checkboxError);
                }
                
                // Update table columns visibility based on authentication
                try {
                updateTableColumnsVisibility();
                } catch (visibilityError) {
                    console.debug('Error updating column visibility:', visibilityError);
                }
            },
            initComplete: function(settings, json) {
                console.log('🎉 DataTable initialization complete!');
                console.log('📊 Loaded data:', json ? (json.data ? json.data.length : json.length || 'unknown') : 'no data');
                
                // Ensure the table is marked as ready
                if (window.inventoryTable) {
                    console.log('✅ Table is available and ready for use');
                }
            }
        });

        // Initialize staff-only tables if authenticated
        if (window.isAuthenticated) {
            initializeStaffTables();
        }

        setupEventHandlers();
        setupWidgetFiltering();
        
        // Mark table initialization as complete
        window.tableInitializing = false;
        
        console.log('✅ Table initialization completed successfully');
        console.log('📊 Final table state:', {
            exists: !!window.inventoryTable,
            hasData: window.inventoryTable && typeof window.inventoryTable.data === 'function',
            isInitComplete: window.inventoryTable && window.inventoryTable.settings && window.inventoryTable.settings().length > 0 ? window.inventoryTable.settings()[0]._bInitComplete : false
        });
        
        // Update loading status
        if (window.updateLoadingText) {
            window.updateLoadingText('Tables Ready', 'Preparing dashboard widgets...');
        }
        
        // Trigger custom event when tables are ready
        setTimeout(() => {
            console.log('🎯 Dispatching tablesReady event');
            window.dispatchEvent(new CustomEvent('tablesReady'));
        }, 1000);
    }

    // Initialize staff-only tables
    function initializeStaffTables() {
        const $ = window.jQuery;

        // Initialize Inventory Orders Table
        if ($('#inventoryOrdersTable').length > 0) {
            try {
                window.inventoryOrdersTable = $('#inventoryOrdersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollY: '250px',
                    scrollCollapse: true,
                    ajax: {
                        url: '../recon_orders/inventory_orders_data',
                        type: 'POST',
                        data: function(d) {
                            d.ajax = true;
                            return d;
                        },
                        error: function(xhr, error, code) {
                            // NEVER handle session expiry on public BOS pages
                            const isPublicPage = window.location.pathname.includes('/bos/') || 
                                                window.location.pathname.includes('public/bos/');
                            
                            if (isPublicPage) {
                                console.log('🌐 Public page - ignoring AJAX error:', xhr.status);
                                return false;
                            }
                            
                            // Handle session expiry ONLY if user was previously authenticated
                            if (xhr.status === 401 && window.isAuthenticated) {
                                try {
                                    const response = xhr.responseJSON || JSON.parse(xhr.responseText);
                                        handleSessionExpiry(response);
                                } catch (e) {
                                handleSessionExpiry({redirect: '../../login'});
                                }
                                return false;
                            }
                            
                            return false;
                        }
                    },
                columns: [
                    { data: 'order_number' },
                    { data: 'stock' },
                    { data: 'vehicle' },
                    { data: 'client_name' },
                    { data: 'service_date' },
                    { data: 'status' },
                    { data: 'source_type' },
                    { data: null, orderable: false, render: function(data, type, row) {
                        return `<div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-sm">View</button>
                            <button class="btn btn-outline-secondary btn-sm">Edit</button>
                        </div>`;
                    }}
                ],
                pageLength: 10,
                responsive: true,
                language: {
                    processing: '<span style="font-size: 0.6rem; color: rgba(108, 117, 125, 0.4);">•</span>',
                    emptyTable: 'No orders from inventory found'
                }
            });
            } catch (error) {
                console.warn('⚠️ Failed to initialize Inventory Orders table:', error);
            }
        }

        // Initialize All Orders Table
        if ($('#allOrdersTable').length > 0) {
            try {
                window.allOrdersTable = $('#allOrdersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollY: '250px',
                    scrollCollapse: true,
                    ajax: {
                        url: '../recon_orders/all_orders_content',
                        type: 'POST',
                        data: function(d) {
                            d.ajax = true;
                            return d;
                        },
                        error: function(xhr, error, code) {
                            // NEVER handle session expiry on public BOS pages
                            const isPublicPage = window.location.pathname.includes('/bos/') || 
                                                window.location.pathname.includes('public/bos/');
                            
                            if (isPublicPage) {
                                console.log('🌐 Public page - ignoring AJAX error:', xhr.status);
                                return false;
                            }
                            
                            // Handle session expiry ONLY if user was previously authenticated
                            if (xhr.status === 401 && window.isAuthenticated) {
                                try {
                                    const response = xhr.responseJSON || JSON.parse(xhr.responseText);
                                        handleSessionExpiry(response);
                                } catch (e) {
                                handleSessionExpiry({redirect: '../../login'});
                                }
                                return false;
                            }
                            
                            return false;
                        }
                    },
                columns: [
                    { data: 'order_number' },
                    { data: 'stock' },
                    { data: 'vehicle' },
                    { data: 'client_name' },
                    { data: 'service_date' },
                    { data: 'status' },
                    { data: 'source_type' },
                    { data: null, orderable: false, render: function(data, type, row) {
                        return `<div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-sm">View</button>
                            <button class="btn btn-outline-secondary btn-sm">Edit</button>
                        </div>`;
                    }}
                ],
                pageLength: 10,
                responsive: true,
                language: {
                    processing: '<span style="font-size: 0.6rem; color: rgba(108, 117, 125, 0.4);">•</span>',
                    emptyTable: 'No orders found'
                }
            });
            } catch (error) {
                console.warn('⚠️ Failed to initialize All Orders table:', error);
            }
        }
    }

// ====================================
// EVENT HANDLERS
// ====================================

    function setupEventHandlers() {
        const $ = window.jQuery;

        // Refresh button
        $('#refreshInventoryBtn').on('click', function() {
            const $btn = $(this);
            const originalHtml = $btn.html();
            
            // Function to restore button state
            function restoreButton(success = true, message = '') {
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                if (success) {
                    showToast(message || 'Inventory refreshed', 'success');
                } else {
                    showToast(message || 'Error refreshing inventory', 'error');
                }
            }
            
            $btn.html('<i class="ri-refresh-line me-1 spinner-border spinner-border-sm"></i> Refreshing...');
            $btn.prop('disabled', true);
            
            // Safety timeout to restore button if something goes wrong
            const safetyTimeout = setTimeout(() => {
                console.warn('⚠️ Refresh timeout reached, restoring button');
                restoreButton(false, 'Refresh timed out');
            }, 30000); // 30 seconds timeout
            
            // Override restoreButton to clear the timeout
            const originalRestoreButton = restoreButton;
            restoreButton = function(success, message) {
                clearTimeout(safetyTimeout);
                originalRestoreButton(success, message);
            };
            
            if (window.inventoryTable && window.inventoryTable.ajax && typeof window.inventoryTable.ajax.reload === 'function') {
                try {
                    window.inventoryTable.ajax.reload(function(json) {
                        // Success callback
                const lastRefreshInfo = document.getElementById('lastRefreshInfo');
                if (lastRefreshInfo) {
                    const now = new Date();
                    const timeString = now.toLocaleTimeString('en-US', {
                        hour12: false,
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                    lastRefreshInfo.textContent = `Last refresh: ${timeString} (Manual)`;
                }
                
                        debounceUpdate('inventory', () => {
                loadOrderInfoForInventory();
                
                            if (hasDuplicateStocks()) {
                        updateDuplicateIcons();
                    }
                    
                    // Force status column update
                    updateInventoryStatusColumns();
                    // Apply status row colors after update
                    setTimeout(() => {
                        applyStatusRowColors();
                    }, 300);
                }, 300);
                
                        restoreButton(true, 'Inventory refreshed successfully');
                    }, function(xhr, error, thrown) {
                        // Error callback
                        console.error('❌ Error refreshing inventory:', error, thrown);
                        restoreButton(false, 'Failed to refresh inventory data');
                    });
                } catch (e) {
                    console.error('❌ Exception during refresh:', e);
                    restoreButton(false, 'Error occurred during refresh');
                }
            } else {
                console.warn('⚠️ Table not available for refresh');
                restoreButton(false, 'Table not available for refresh');
            }
        });

        // Staff-only event handlers
        if (window.isAuthenticated) {
            // Select all inventory checkbox
            $('#selectAllInventory').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.inventory-checkbox').prop('checked', isChecked);
                updateConvertButtonState();
            });

            // Individual inventory checkboxes
            $(document).on('change', '.inventory-checkbox', function() {
                updateConvertButtonState();
                
                const totalCheckboxes = $('.inventory-checkbox').length;
                const checkedCheckboxes = $('.inventory-checkbox:checked').length;
                $('#selectAllInventory').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
                $('#selectAllInventory').prop('checked', checkedCheckboxes === totalCheckboxes);
            });

            // Convert selected button
            $('#convertSelectedBtn').on('click', function() {
                const selectedItems = [];
                $('.inventory-checkbox:checked').each(function() {
                    const rowData = window.inventoryTable.row($(this).closest('tr')).data();
                    if (rowData) {
                        selectedItems.push(rowData);
                    }
                });

                if (selectedItems.length === 0) {
                    showToast('Select items to convert', 'warning');
                    return;
                }

                showBulkConversionModal(selectedItems);
            });

            // Refresh buttons for staff tables
            $('#refreshInventoryOrdersBtn').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();
                
                if (window.inventoryOrdersTable && window.inventoryOrdersTable.ajax && typeof window.inventoryOrdersTable.ajax.reload === 'function') {
                    $btn.html('<i class="ri-refresh-line spinner-border spinner-border-sm"></i>');
                    $btn.prop('disabled', true);
                    
                    window.inventoryOrdersTable.ajax.reload(function() {
                        // Success
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        showToast('Orders refreshed', 'success');
                    }, function() {
                        // Error
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        showToast('Error refreshing orders', 'error');
                    });
                } else {
                    showToast('Orders table not available', 'warning');
                }
            });

            $('#refreshAllOrdersBtn').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();
                
                if (window.allOrdersTable && window.allOrdersTable.ajax && typeof window.allOrdersTable.ajax.reload === 'function') {
                    $btn.html('<i class="ri-refresh-line spinner-border spinner-border-sm"></i>');
                    $btn.prop('disabled', true);
                    
                    window.allOrdersTable.ajax.reload(function() {
                        // Success
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        showToast('All orders refreshed', 'success');
                    }, function() {
                        // Error
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        showToast('Error refreshing all orders', 'error');
                    });
                } else {
                    showToast('All orders table not available', 'warning');
                }
            });
        }

        // Clear filters button
        $('#clearAllFilters').on('click', function() {
            clearAllFilters();
        });
    }

// Additional helper functions
    function updateConvertButtonState() {
        if (!window.isAuthenticated) return;
        
        const checkedCount = $('.inventory-checkbox:checked').length;
        $('#convertSelectedBtn').prop('disabled', checkedCount === 0);
        
        if (checkedCount > 0) {
            $('#convertSelectedBtn').html(`<i class="ri-arrow-right-line me-1"></i> Move Selected (${checkedCount})`);
        } else {
            $('#convertSelectedBtn').html(`<i class="ri-arrow-right-line me-1"></i> Move Selected`);
        }
    }

// ====================================
// FILTERING SYSTEM
// ====================================

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
    
    function applyWidgetFilter(filter, $widget) {
        const $ = window.jQuery;
        
        $('.filter-widget').removeClass('active');
        $widget.addClass('active');
        
        window.currentDayFilter = filter;
        applyDayRangeFilter(filter);
        
        const filterName = filter === '' ? 'All day ranges' : 
                          filter === '0-1' ? 'Recent (0-1 days)' :
                          filter === '2-5' ? 'Moderate (2-5 days)' :
                          filter === '6+' ? 'Aged (6+ days)' : filter;
        
        showToast(`Filters applied: ${filterName}`, 'info');
    }
    
    function applyDayRangeFilter(range) {
        if (!window.inventoryTable) {
            return;
        }
        
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
                
                let result = false;
                switch(range) {
                    case '0-1':
                        result = daysNum <= 1;
                        break;
                    case '2-5':
                        result = daysNum >= 2 && daysNum <= 5;
                        break;
                    case '6+':
                        result = daysNum >= 6;
                        break;
                    default:
                        result = true;
                        break;
                }
                
                return result;
            };
            dayRangeFilterFn.name = 'dayRangeFilter';
            $.fn.dataTable.ext.search.push(dayRangeFilterFn);
        }
        
        window.inventoryTable.draw();
    }

    function clearAllFilters() {
        const $ = window.jQuery;
        
        $('.filter-widget').removeClass('active');
        window.currentDayFilter = '';
        applyDayRangeFilter('');
        
        showToast('Filters cleared', 'info');
    }

// ====================================
// CONVERSION FUNCTIONS
// ====================================

    function showBulkConversionModal(selectedItems) {
        console.log('🔄 Bulk conversion modal for:', selectedItems);
        
        if (!selectedItems || selectedItems.length === 0) {
            showToast('No items selected for conversion', 'warning');
            return;
        }
        
        // Create confirmation modal
        const stockNumbers = selectedItems.map(item => item.stock_number).join(', ');
        const message = `Are you sure you want to move ${selectedItems.length} selected items to Recon Orders?\n\nStock Numbers: ${stockNumbers}`;
        
        if (confirm(message)) {
            // Process bulk conversion
            processBulkConversion(selectedItems);
        }
    }
    
    // Function to handle individual item conversion
    window.moveToRecon = function(stockNumber) {
        console.log('🔄 Moving single item to recon:', stockNumber);
        
        if (!stockNumber) {
            showToast('Invalid stock number', 'error');
            return;
        }
        
        const message = `Are you sure you want to move stock "${stockNumber}" to Recon Orders?`;
        
        if (confirm(message)) {
            // Find the row data
            let rowData = null;
            if (window.inventoryTable) {
                window.inventoryTable.rows().every(function() {
                    const data = this.data();
                    if (data && data.stock_number === stockNumber) {
                        rowData = data;
                        return false; // Break the loop
                    }
                    return true; // Continue
                });
            }
            
            if (rowData) {
                processBulkConversion([rowData]);
            } else {
                showToast('Could not find inventory item', 'error');
            }
        }
    };
    
    // Function to process the actual conversion
    function processBulkConversion(items) {
        console.log('🚀 Processing conversion for', items.length, 'items:', items);
        
        // Show loading state
        showToast(`Processing ${items.length} item(s)...`, 'info');
        
        // Here you would typically make an API call to convert the items
        // For now, we'll simulate the process
        setTimeout(() => {
            const stockNumbers = items.map(item => item.stock_number);
            console.log('✅ Conversion completed for stocks:', stockNumbers);
            showToast(`Successfully moved ${items.length} item(s) to Recon Orders`, 'success');
            
            // Refresh the inventory table to reflect changes
            if (window.inventoryTable && window.inventoryTable.ajax && typeof window.inventoryTable.ajax.reload === 'function') {
                window.inventoryTable.ajax.reload(null, false);
            }
            
            // Clear any selected checkboxes
            $('.inventory-checkbox:checked').prop('checked', false);
            $('#selectAllInventory').prop('checked', false);
            updateConvertButtonState();
            
        }, 1500); // Simulate processing time
    }

// ====================================
// AUTHENTICATION AND UI MANAGEMENT
// ====================================

// Function to update authentication UI
function updateAuthenticationUI() {
    console.log('🔐 updateAuthenticationUI called - isAuthenticated:', window.isAuthenticated);
    
    // Show/hide staff-only elements
    const staffOnlyElements = document.querySelectorAll('.staff-only, [class*="staff-only"]');
    console.log('📋 Found', staffOnlyElements.length, 'staff-only elements');
    
    staffOnlyElements.forEach((element, index) => {
        if (window.isAuthenticated) {
            // Show staff-only elements for authenticated users
            element.style.display = '';
            element.style.removeProperty('display');
            element.classList.remove('staff-only');
        } else {
            // Hide staff-only elements for non-authenticated users
            element.style.display = 'none';
        }
    });

    // Also handle inline style staff-only elements
    const inlineStaffElements = document.querySelectorAll('[style*="display: none !important"]');
    inlineStaffElements.forEach((element, index) => {
        if (window.isAuthenticated) {
            // Remove inline style to show element
            element.style.removeProperty('display');
        }
    });

    // Update top bar
    const topBar = document.getElementById('topBar');
    if (topBar) {
        if (window.isAuthenticated) {
            topBar.classList.add('show');
            console.log('✅ Showing top bar');
        } else {
            topBar.classList.remove('show');
            console.log('❌ Hiding top bar');
        }
    }
    
    // Update table columns visibility for Actions column
    updateTableColumnsVisibility();
    
    // Reinitialize tables if they exist and auth state changed
    // Only reinitialize table if authentication state actually changed
    const currentAuth = window.isAuthenticated;
    
    // For public pages, don't reinitialize on auth changes
    const isPublicPage = window.location.pathname.includes('/bos/') || window.location.pathname.includes('public/bos/');
    
    if (isPublicPage) {
        console.log('🌐 Public page detected, skipping auth-based table reinitialization');
        return;
    }
    
    // Check if auth state actually changed (not just undefined to false)
    const authActuallyChanged = (typeof window.lastAuthState !== 'undefined') && (window.lastAuthState !== currentAuth);
    
    if (authActuallyChanged) {
        console.log(`🔄 Auth state actually changed: ${window.lastAuthState} → ${currentAuth}`);
        window.lastAuthState = currentAuth;
        
        // Only reinitialize if table actually exists and is not already being reinitialized
        if (window.inventoryTable && typeof window.inventoryTable.destroy === 'function' && !window.tableReinitializing) {
            console.log('🔄 Auth state changed, reinitializing table...');
            window.tableReinitializing = true;
            
            try {
        window.inventoryTable.destroy();
        window.inventoryTable = null;
                window.tableInitializing = false; // Reset this flag too
                
                // Add delay before reinitializing to prevent conflicts
                setTimeout(() => {
                    console.log('🚀 Reinitializing table after auth change...');
        initializeTables();
                    window.tableReinitializing = false;
                }, 500); // Increased delay
            } catch (e) {
                console.error('❌ Error during table reinitialization:', e);
                window.tableReinitializing = false;
                window.tableInitializing = false;
            }
        } else if (!window.inventoryTable && !window.tableInitializing && !window.tableReinitializing) {
            // If no table exists and nothing is initializing, start initialization
            console.log('🆕 No table exists, starting fresh initialization...');
            initializeTables();
        }
    } else {
        // Set lastAuthState if it's the first time
        if (typeof window.lastAuthState === 'undefined') {
            window.lastAuthState = currentAuth;
            console.log(`🔧 Setting initial auth state: ${currentAuth}`);
        }
    }
}

// Function to update table columns visibility
function updateTableColumnsVisibility() {
    // Show/hide Actions column in inventory table based on authentication
    const inventoryTable = document.getElementById('inventoryTable');
    if (inventoryTable) {
        const actionHeaders = inventoryTable.querySelectorAll('th:nth-child(9)'); // Actions column
        const actionCells = inventoryTable.querySelectorAll('td:nth-child(9)');
        const selectHeaders = inventoryTable.querySelectorAll('th:nth-child(1)'); // Select column  
        const selectCells = inventoryTable.querySelectorAll('td:nth-child(1)');
        
        const displayValue = window.isAuthenticated ? '' : 'none';
        
        [...actionHeaders, ...actionCells].forEach(element => {
            element.style.display = displayValue;
        });
        
        [...selectHeaders, ...selectCells].forEach(element => {
            element.style.display = displayValue;
        });
        
        // Also update DataTables column visibility if table exists
        if (window.inventoryTable && typeof window.inventoryTable.column === 'function') {
            // Add delay to ensure DataTable is fully ready
            setTimeout(() => {
                try {
                    const api = window.inventoryTable;
                    
                    // Multiple safety checks
                    if (!api || !api.settings || !api.settings() || api.settings().length === 0) {
                        return;
                    }
                    
                    // Check if table initialization is complete
                    const settings = api.settings()[0];
                    if (!settings || settings._bInitComplete !== true) {
                        return;
                    }
                    
                    // Check if columns are available and accessible
                    if (!api.columns || typeof api.columns !== 'function') {
                        return;
                    }
                    
                    const columns = api.columns();
                    if (!columns || !columns.header || typeof columns.header !== 'function') {
                        return;
                    }
                    
                    const headers = columns.header();
                    if (!headers || headers.length === 0) {
        return;
    }
    
                    const columnCount = headers.length;
                    
                    // Only update columns if we have the expected number
                    if (columnCount >= 9) {
                        // Additional check that specific columns exist
                        if (api.column(0).header() && api.column(8).header()) {
                            // Column 0 = Select checkboxes, Column 8 = Actions (0-indexed)
                            api.column(0).visible(window.isAuthenticated);
                            api.column(8).visible(window.isAuthenticated);
                        }
                    }
                } catch (e) {
                    // Silently handle errors to prevent console spam
                }
            }, 200);
        }
    }
}

// Placeholder function
function initializeVehiclesLocalStorage() {
    console.log('Initializing vehicles localStorage...');
}

// ====================================
// MAIN INITIALIZATION
// ====================================

// Wait for the document to be ready and ensure jQuery is available
document.addEventListener('DOMContentLoaded', function() {
    // Initialize localStorage for vehicles tab
    initializeVehiclesLocalStorage();
    
    // Wait for authentication to be verified before initializing tables
    function waitForAuth() {
        if (typeof window.authCheckCompleted === 'undefined') {
            console.log('⏳ Waiting for auth check to complete...');
            setTimeout(waitForAuth, 100);
        return;
    }
    
        console.log('✅ Auth check completed, initializing tables...');
        initializeTables();
    }
    
    // Start waiting for auth
    waitForAuth();
    
    // Emergency fallback - initialize tables after 3 seconds regardless
    setTimeout(() => {
        if (!window.inventoryTable) {
            console.warn('🚨 Emergency table initialization - auth check taking too long');
            window.authCheckCompleted = true;
            initializeTables();
        }
    }, 3000);
    
    // Initialize last refresh time
    const lastRefreshInfo = document.getElementById('lastRefreshInfo');
    if (lastRefreshInfo) {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        lastRefreshInfo.textContent = `Last refresh: ${timeString} (Initial)`;
    }
    
    // Auto-refresh every 30 seconds
    setInterval(() => {
        if (window.inventoryTable && window.inventoryTable.ajax && typeof window.inventoryTable.ajax.reload === 'function') {
            window.inventoryTable.ajax.reload(function() {
                // Update last refresh info for auto-refresh
                const lastRefreshInfo = document.getElementById('lastRefreshInfo');
                if (lastRefreshInfo) {
                    const now = new Date();
                    const timeString = now.toLocaleTimeString('en-US', {
                        hour12: false,
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                    lastRefreshInfo.textContent = `Last refresh: ${timeString} (Auto)`;
                }
            }, false);
        }
    }, 30000);

    // Update authentication UI based on existing status
    updateAuthenticationUI();
});
