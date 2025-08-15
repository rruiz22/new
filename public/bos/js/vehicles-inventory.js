// Complete JavaScript from vehicles_content.php with authentication integration

// Wait for the document to be ready and ensure jQuery is available
document.addEventListener('DOMContentLoaded', function() {
    // Initialize localStorage for vehicles tab
    initializeVehiclesLocalStorage();
    
    // Function to detect duplicate stock numbers
    function detectDuplicateStocks(data) {
        console.log('🔍 Starting duplicate detection with', data.length, 'items');
        
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
                
                console.log(`Row ${index}: Stock "${cleanStock}" - Count: ${count + 1}`);
            }
        });
        
        // Identify duplicates (stocks that appear more than once)
        stockCounts.forEach((count, stockNumber) => {
            if (count > 1) {
                duplicates.add(stockNumber);
                console.log(`🚨 Duplicate found: "${stockNumber}" appears ${count} times`);
            }
        });
        
        // Store duplicates globally for use in column rendering
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
            console.log('✅ No duplicate stock numbers found');
            // Clear the duplicate key if no duplicates found
            window.lastDuplicateKey = null;
        }
        
        return duplicates;
    }

    // Function to update duplicate icons in stock column
    function updateDuplicateIcons() {
        if (!window.inventoryTable || !window.duplicateStocks || window.duplicateStocks.size === 0) {
            return;
        }
        
        // Throttle updates to prevent excessive redraws
        if (window.duplicateIconsUpdateTimeout) {
            clearTimeout(window.duplicateIconsUpdateTimeout);
        }
        
        window.duplicateIconsUpdateTimeout = setTimeout(() => {
            console.log('🔄 Updating duplicate icons in stock column for:', Array.from(window.duplicateStocks));
            
            // First try to update existing icons without full redraw
            let iconsUpdated = false;
            window.duplicateStocks.forEach(stockNumber => {
                const stockId = `stock-${stockNumber}`.replace(/[^a-zA-Z0-9]/g, '-');
                const duplicateIconId = `duplicate-icon-${stockNumber}`.replace(/[^a-zA-Z0-9]/g, '-');
                const stockElement = document.getElementById(stockId);
                
                if (stockElement && !document.getElementById(duplicateIconId)) {
                    const icon = `<i id="${duplicateIconId}" class="ri-alert-line text-warning ms-2 duplicate-alert" 
                        title="Duplicate stock number detected!" 
                        style="font-size: 0.9rem; cursor: pointer; animation: pulse-warning 2s infinite;"></i>`;
                    stockElement.innerHTML = `<strong class="text-primary">${stockNumber}</strong>${icon}`;
                    iconsUpdated = true;
                    console.log('🚨 Added duplicate icon for stock:', stockNumber);
                }
            });
            
            // If no icons were updated, force a complete redraw
            if (!iconsUpdated) {
                console.log('🔄 No icons updated directly, forcing table redraw...');
                window.inventoryTable.draw(false);
            }
            
            console.log('✅ Duplicate icons update completed');
        }, 100);
    }

    // Function to initialize all tables
    function initializeTables() {
        // Check if jQuery and DataTables are available
        if (typeof window.$ === 'undefined' || typeof window.jQuery === 'undefined') {
            setTimeout(initializeTables, 100);
            return;
        }
        
        if (typeof window.$.fn.DataTable === 'undefined') {
            setTimeout(initializeTables, 100);
            return;
        }
        
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
                    
                    // Check for duplicates
                    let duplicateIcon = '';
                    if (window.duplicateStocks && window.duplicateStocks.has(data.toString().trim())) {
                        duplicateIcon = `<i id="${duplicateIconId}" class="ri-alert-line text-warning ms-2 duplicate-alert" 
                            title="Duplicate stock number detected!" 
                            style="font-size: 0.9rem; cursor: pointer; animation: pulse-warning 2s infinite;"></i>`;
                        // Reduced logging to avoid console spam
                        if (!window.loggedDuplicates) window.loggedDuplicates = new Set();
                        if (!window.loggedDuplicates.has(data)) {
                            console.log('🚨 Rendering duplicate icon for stock:', data);
                            window.loggedDuplicates.add(data);
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
            // Actions column (always hidden)
            {
                data: null,
                orderable: false,
                visible: false, // Always hidden
                render: function(data, type, row) {
                    return ''; // Always return empty
                }
            }
        ];

        // Initialize Inventory Table
        window.inventoryTable = $('#inventoryTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: './get_inventory.php',
                type: 'GET',
                error: function(xhr, error, code) {
                    console.warn('⚠️ Main inventory table Ajax error:', error);
                    
                    // Handle session expiry
                    if (xhr.status === 401) {
                        console.log('🔐 Session expired during main inventory load');
                        try {
                            const response = JSON.parse(xhr.responseText);
                            handleSessionExpiry(response);
                            return false;
                        } catch (e) {
                            console.warn('Could not parse error response');
                            // Fallback session expiry handling
                            handleSessionExpiry({redirect: '/login'});
                            return false;
                        }
                    }
                    
                    return false;
                },
                dataSrc: function(json) {
                    let data = json;
                    if (json.success && json.data) {
                        data = json.data;
                    }
                    
                    if (!Array.isArray(data)) {
                        return [];
                    }
                    
                    updateInventoryStats(data);
                    detectDuplicateStocks(data);
                    
                    // Use a single timeout to batch updates
                    if (window.inventoryUpdateTimeout) clearTimeout(window.inventoryUpdateTimeout);
                    window.inventoryUpdateTimeout = setTimeout(() => {
                        loadOrderInfoForInventory();
                        
                        if (window.duplicateStocks && window.duplicateStocks.size > 0) {
                            console.log('🔄 Forcing table redraw to show duplicate icons for:', Array.from(window.duplicateStocks));
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
                const $ = window.jQuery;
                
                // Highlight rows with duplicate stocks
                if (window.duplicateStocks && window.duplicateStocks.size > 0) {
                    console.log('📋 DrawCallback: Highlighting duplicate rows for:', Array.from(window.duplicateStocks));
                    
                    $('#inventoryTable tbody tr').each(function() {
                        const $row = $(this);
                        const rowData = window.inventoryTable.row($row).data();
                        
                        if (rowData && rowData.stock_number && window.duplicateStocks.has(rowData.stock_number.toString().trim())) {
                            $row.addClass('has-duplicate');
                            console.log('🎨 Added duplicate highlighting to row with stock:', rowData.stock_number);
                        }
                    });
                }
                
                // Update status columns after table draw with a small delay
                setTimeout(() => {
                    updateInventoryStatusColumns();
                    // Apply row background colors based on status after updating status columns
                    setTimeout(() => {
                        applyStatusRowColors();
                    }, 200);
                }, 100);
                
                // Update checkbox states
                updateCheckboxStates();
            }
        });

        // Initialize staff-only tables if authenticated
        if (window.isAuthenticated) {
            initializeStaffTables();
        }

        setupEventHandlers();
        setupWidgetFiltering();
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
                    ajax: {
                        url: '../recon_orders/inventory_orders_data',
                        type: 'POST',
                        data: function(d) {
                            d.ajax = true;
                            return d;
                        },
                        error: function(xhr, error, code) {
                            console.warn('⚠️ Inventory Orders table Ajax error:', error);
                            
                            // Handle session expiry
                            if (xhr.status === 401) {
                                console.log('🔐 Session expired, redirecting to login...');
                                handleSessionExpiry(xhr.responseJSON);
                                return false;
                            }
                            
                            // Hide other errors from user
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
                    ajax: {
                        url: '../recon_orders/all_orders_content',
                        type: 'POST',
                        data: function(d) {
                            d.ajax = true;
                            return d;
                        },
                        error: function(xhr, error, code) {
                            console.warn('⚠️ All Orders table Ajax error:', error);
                            
                            // Handle session expiry
                            if (xhr.status === 401) {
                                console.log('🔐 Session expired, redirecting to login...');
                                handleSessionExpiry(xhr.responseJSON);
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

    // Event handlers and other functions...
    function setupEventHandlers() {
        const $ = window.jQuery;

        // Refresh button
        $('#refreshInventoryBtn').on('click', function() {
            const $btn = $(this);
            const originalHtml = $btn.html();
            
            $btn.html('<i class="ri-refresh-line me-1 spinner-border spinner-border-sm"></i> Refreshing...');
            $btn.prop('disabled', true);
            
            window.inventoryTable.ajax.reload(function() {
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
                
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                loadOrderInfoForInventory();
                
                setTimeout(() => {
                    if (window.duplicateStocks && window.duplicateStocks.size > 0) {
                        updateDuplicateIcons();
                    }
                    // Debug table status after loading
                    debugTableStatus();
                    
                    // Force status column update
                    console.log('🔄 Forcing status column update after refresh...');
                    updateInventoryStatusColumns();
                    // Apply status row colors after update
                    setTimeout(() => {
                        applyStatusRowColors();
                    }, 300);
                }, 300);
                
                showToast('Inventory refreshed', 'success');
            });
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
                if (window.inventoryOrdersTable) {
                    window.inventoryOrdersTable.ajax.reload();
                }
            });

            $('#refreshAllOrdersBtn').on('click', function() {
                if (window.allOrdersTable) {
                    window.allOrdersTable.ajax.reload();
                }
            });
        }

        // Clear filters button
        $('#clearAllFilters').on('click', function() {
            clearAllFilters();
        });
    }

    // Additional functions...
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
        const totalAll = data.length; // Keep track of all items for reference
        
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
        
        console.log(`📊 Stats updated - Total: ${total} (filtered), All: ${totalAll}, Avg Days: ${avgDays}`);
        
        // Update stats
        $('#totalInventoryItems').text(total);
        $('#recentItems').text(recentItems);
        $('#moderateItems').text(moderateItems);
        $('#agedItems').text(agedItems);
        
        // Update mini widget average days
        $('#avgDaysNumber').text(avgDays);

        // Update average days widget
        updateAvgDaysWidget(daysData, total);
    }

    function updateAvgDaysWidget(daysData, totalItems) {
        if (daysData.length === 0) return;
        
        const avgDays = Math.round(daysData.reduce((a, b) => a + b, 0) / daysData.length);
        const maxDays = Math.max(...daysData);
        const minDays = 0;
        
        // Update the mini widget value
        $('#avgDaysNumber').text(avgDays);
        
        console.log(`📊 Updated average days widget: ${avgDays} days`);
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

    // Placeholder functions for missing functionality
    function loadOrderInfoForInventory() {
        const $ = window.jQuery;
        
        console.log('🔍 Loading order info for inventory matching...');
        
        $.post('../recon_orders/get_order_info_by_stock', { ajax: true })
            .done(function(response) {
                console.log('📦 Order info response:', response);
                
                // Check if response is JSON
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        console.error('❌ Invalid JSON response:', response.substring(0, 200));
                        return;
                    }
                }
                
                if (response.success && response.data) {
                    window.orderInfoLookup = response.data;
                    console.log('✅ Order info loaded:', Object.keys(response.data).length, 'items');
                    console.log('📋 Sample data:', Object.keys(response.data).slice(0, 3).map(key => ({
                        stock: key,
                        info: response.data[key]
                    })));
                    updateInventoryStatusColumns();
                } else {
                    console.log('⚠️ No order info data received');
                    console.log('Response details:', response);
                    // Still call updateInventoryStatusColumns to show "NO STATUS YET"
                    updateInventoryStatusColumns();
                }
            })
            .fail(function(xhr, status, error) {
                console.error('❌ Failed to load order info:', status, error);
                console.log('Response:', xhr.responseText?.substring(0, 200));
                
                // Handle session expiry
                if (xhr.status === 401) {
                    console.log('🔐 Session expired during order info fetch');
                    try {
                        const response = JSON.parse(xhr.responseText);
                        handleSessionExpiry(response);
                        return; // Don't update status columns if session expired
                    } catch (e) {
                        console.warn('Could not parse error response');
                    }
                }
                
                // Fallback to showing "NO STATUS YET" for all
                updateInventoryStatusColumns();
            });
    }

    function updateInventoryStatusColumns() {
        const $ = window.jQuery;
        
        console.log('🔄 Updating inventory status columns...');
        const statusElements = $('.status-service-info');
        console.log('Found', statusElements.length, 'status elements to update');
        
        statusElements.each(function() {
            const $element = $(this);
            const stockNumber = $element.data('stock');
            console.log('Processing stock number:', stockNumber);
            
            if (stockNumber && window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
                const orderInfo = window.orderInfoLookup[stockNumber];
                
                let html = '<div class="d-flex flex-column align-items-center gap-1">';
                
                // Status badge - highlighted and prominent
                const statusColors = {
                    'pending': 'warning',
                    'in_progress': 'info', 
                    'completed': 'success',
                    'cancelled': 'danger'
                };
                const statusColor = statusColors[orderInfo.status] || 'secondary';
                html += `<span class="badge bg-${statusColor} px-2 py-1 fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">${orderInfo.status}</span>`;
                
                // Service info below - smaller and muted
                if (orderInfo.service_name) {
                    const serviceColor = orderInfo.service_color || '#007bff';
                    html += `<div class="d-flex align-items-center mt-1">
                        <div class="service-color-dot me-1" style="width: 5px; height: 5px; border-radius: 50%; background-color: ${serviceColor};"></div>
                        <small class="text-muted" style="font-size: 0.6rem; opacity: 0.8;">${orderInfo.service_name}</small>
                    </div>`;
                }
                
                html += '</div>';
                $element.html(html);
            } else {
                $element.html('<div class="d-flex flex-column align-items-center"><span class="badge bg-secondary-subtle text-secondary px-2 py-1 fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">NO STATUS YET</span></div>');
            }
        });
        
        // Reapply completed filter if it's active
        if (window.applyCompletedFilter) {
            setTimeout(() => {
                window.applyCompletedFilter();
            }, 500);
        }
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
                
                console.log(`🎨 Applied status class for row with status: ${statusText}`);
            }
        });
    }

    function showBulkConversionModal(selectedItems) {
        console.log('Bulk conversion modal for:', selectedItems);
        showToast(`Selected ${selectedItems.length} items for conversion`, 'info');
    }

    function initializeVehiclesLocalStorage() {
        // Placeholder for localStorage initialization
        console.log('Initializing vehicles localStorage...');
    }

    // Debug function to check table status
    function debugTableStatus() {
        console.log('🔍 DEBUG: Table Status Check');
        console.log('- inventoryTable exists:', !!window.inventoryTable);
        console.log('- duplicateStocks size:', window.duplicateStocks ? window.duplicateStocks.size : 0);
        console.log('- Status elements count:', $('.status-service-info').length);
        console.log('- Duplicate icons count:', $('.duplicate-alert').length);
        console.log('- Loading status elements:', $('.status-service-info:contains("Loading")').length);
        
        // Check status elements
        $('.status-service-info').each(function() {
            const $element = $(this);
            const stockNumber = $element.data('stock');
            const content = $element.text().trim();
            console.log(`  - Status for ${stockNumber}: "${content}"`);
        });
        
        if (window.duplicateStocks && window.duplicateStocks.size > 0) {
            console.log('- Duplicate stocks:', Array.from(window.duplicateStocks));
            window.duplicateStocks.forEach(stock => {
                const stockId = `stock-${stock}`.replace(/[^a-zA-Z0-9]/g, '-');
                const iconId = `duplicate-icon-${stock}`.replace(/[^a-zA-Z0-9]/g, '-');
                console.log(`  - Stock ${stock}: element exists=${!!document.getElementById(stockId)}, icon exists=${!!document.getElementById(iconId)}`);
            });
        }
    }

    // Wait for authentication to be verified before initializing tables
    function waitForAuth() {
        if (typeof window.authCheckCompleted === 'undefined') {
            // Wait for auth check to complete
            setTimeout(waitForAuth, 100);
            return;
        }
        
        console.log('🔐 Auth check completed, initializing tables with isAuthenticated:', window.isAuthenticated);
        initializeTables();
    }
    
    // Start waiting for auth
    waitForAuth();
    
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
        if (window.inventoryTable) {
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

// Function to update authentication UI
function updateAuthenticationUI() {
    console.log('🔐 updateAuthenticationUI called - isAuthenticated:', window.isAuthenticated);
    
    // Show/hide staff-only elements
    const staffOnlyElements = document.querySelectorAll('.staff-only');
    staffOnlyElements.forEach(element => {
        if (window.isAuthenticated) {
            element.style.display = '';
            element.classList.remove('staff-only');
        } else {
            element.style.display = 'none !important';
        }
    });

    // Update top bar
    const topBar = document.getElementById('topBar');
    if (topBar) {
        if (window.isAuthenticated) {
            topBar.classList.add('show');
        } else {
            topBar.classList.remove('show');
        }
    }
    
    // Reinitialize tables if they exist and auth state changed
    if (window.inventoryTable && typeof window.inventoryTable.destroy === 'function') {
        console.log('🔄 Reinitializing inventory table with new auth state');
        window.inventoryTable.destroy();
        window.inventoryTable = null;
        initializeTables();
    }
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

// Handle session expiry
function handleSessionExpiry(response) {
    console.log('🔐 Handling session expiry...');
    
    // Show a user-friendly message
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
                window.location.href = '/login';
            }
        });
    } else {
        // Fallback without SweetAlert
        alert('Your session has expired. Please login again.');
        if (response?.redirect) {
            window.location.href = response.redirect;
        } else {
            window.location.href = '/login';
        }
    }
}

// Make functions globally available
window.handleSessionExpiry = handleSessionExpiry;
