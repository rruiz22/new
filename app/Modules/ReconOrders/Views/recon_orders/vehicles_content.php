<div class="container-fluid">
    
    <!-- Dealer Inventory Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">
                                <i class="ri-store-3-line me-2"></i>
                                <?= lang('App.dealer_inventory') ?>
                            </h5>
                            <p class="text-muted small mb-0"><?= lang('App.available_stock') ?></p>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary" id="refreshInventoryBtn">
                                    <i class="ri-refresh-line me-1"></i>
                                    <?= lang('App.refresh_inventory') ?>
                                </button>
                                <button type="button" class="btn btn-success" id="convertSelectedBtn" disabled>
                                    <i class="ri-arrow-right-line me-1"></i>
                                    <?= lang('App.move_selected') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Inventory Stats - Interactive Filter Widgets -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="stats-mini filter-widget" data-filter="" role="button" tabindex="0">
                                <div class="stats-icon bg-primary">
                                    <i class="ri-car-line"></i>
                                </div>
                                <div class="stats-content">
                                    <h6 class="mb-0" id="totalInventoryItems">0</h6>
                                    <small class="text-muted"><?= lang('App.total_stock_items') ?></small>
                                </div>
                                <div class="filter-indicator">
                                    <i class="ri-eye-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-mini filter-widget" data-filter="0-1" role="button" tabindex="0">
                                <div class="stats-icon bg-success" id="recentItemsIcon">
                                    <i class="ri-calendar-check-line"></i>
                                </div>
                                <div class="stats-content">
                                    <h6 class="mb-0" id="recentItems">0</h6>
                                    <small class="text-muted"><?= lang('App.recent_items') ?></small>
                                </div>
                                <div class="filter-indicator">
                                    <i class="ri-filter-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-mini filter-widget" data-filter="2-5" role="button" tabindex="0">
                                <div class="stats-icon bg-warning" id="moderateItemsIcon">
                                    <i class="ri-calendar-line"></i>
                                </div>
                                <div class="stats-content">
                                    <h6 class="mb-0" id="moderateItems">0</h6>
                                    <small class="text-muted"><?= lang('App.moderate_items') ?></small>
                                </div>
                                <div class="filter-indicator">
                                    <i class="ri-filter-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-mini filter-widget" data-filter="6+" role="button" tabindex="0">
                                <div class="stats-icon bg-danger" id="agedItemsIcon">
                                    <i class="ri-calendar-close-line"></i>
                                </div>
                                <div class="stats-content">
                                    <h6 class="mb-0" id="agedItems">0</h6>
                                    <small class="text-muted"><?= lang('App.aged_items') ?></small>
                                </div>
                                <div class="filter-indicator">
                                    <i class="ri-filter-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Medium Progress Widget -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="avg-days-widget-medium">
                                <div class="widget-header">
                                    <div class="widget-icon" id="avgDaysIcon">
                                        <i class="ri-time-line"></i>
                                    </div>
                                    <div class="widget-title">
                                        <span class="widget-label"><?= lang('App.avg_in_this_step') ?></span>
                                        <span class="widget-range" id="avgDaysRange">0 - 0 días</span>
                                    </div>
                                </div>
                                <div class="progress-section">
                                    <div class="progress-bar-container">
                                        <div class="progress-bar-track" id="progressTrack">
                                            <div class="progress-bar-fill" id="progressFill"></div>
                                            <div class="progress-indicator" id="progressIndicator">
                                                <div class="indicator-value" id="avgDaysInDetail">0</div>
                                                <div class="indicator-arrow"></div>
                                            </div>
                                        </div>
                                        <div class="progress-labels">
                                            <span class="progress-min">0</span>
                                            <span class="progress-max" id="maxDaysLabel">0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-status" id="avgDaysStatus">Calculando estadísticas...</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-end h-100">
                                <button type="button" class="btn btn-outline-primary" id="clearAllFilters">
                                    <i class="ri-filter-off-line me-2"></i>
                                    <?= lang('App.clear_filters') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    

                    
                    <div class="table-responsive">
                        <table id="inventoryTable" class="table table-hover align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th width="40">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllInventory">
                                        </div>
                                    </th>
                                    <th><?= lang('App.date_in_detail') ?></th>
                                    <th><?= lang('App.day_in_this_step') ?></th>
                                    <th><?= lang('App.keys') ?></th>
                                    <th><?= lang('App.stock_number') ?></th>
                                    <th><?= lang('App.vehicle') ?></th>
                                    <th><?= lang('App.write_up_date') ?></th>
                                    <th><?= lang('App.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders from Inventory Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">
                                <i class="ri-file-list-3-line me-2"></i>
                                <?= lang('App.orders_from_inventory') ?>
                            </h5>
                            <p class="text-muted small mb-0"><?= lang('App.created_from_inventory') ?></p>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-primary" id="refreshInventoryOrdersBtn">
                                <i class="ri-refresh-line me-1"></i>
                                <?= lang('App.refresh') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="inventoryOrdersTable" class="table table-hover align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th><?= lang('App.order_number') ?></th>
                                    <th><?= lang('App.stock_number') ?></th>
                                    <th><?= lang('App.vehicle') ?></th>
                                    <th><?= lang('App.client_name') ?></th>
                                    <th><?= lang('App.service_date') ?></th>
                                    <th><?= lang('App.status') ?></th>
                                    <th><?= lang('App.converted_by') ?></th>
                                    <th><?= lang('App.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




</div>



<style>
.stats-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    background: #ffffff;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.stats-mini {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.stats-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-right: 0.75rem;
}

.stats-content h6 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
}



.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}









.search-bar {
    max-width: 400px;
}

.icon-container {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.icon-blue {
    background: #3b82f6;
}

.icon-green {
    background: #10b981;
}

.icon-orange {
    background: #f59e0b;
}

.icon-purple {
    background: #8b5cf6;
}

.priority-badge {
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
}

.days-badge {
    font-size: 0.75rem;
    padding: 0.3rem 0.6rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: lowercase;
}

.stats-mini {
    transition: all 0.3s ease;
    position: relative;
}

.stats-mini:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.stats-icon {
    transition: all 0.3s ease;
}

/* Filter Widget Styling */
.filter-widget {
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 8px;
    position: relative;
    overflow: hidden;
}

.filter-widget:hover {
    border-color: #3b82f6;
    background-color: #f8fafc;
}

.filter-widget.active {
    border-color: #3b82f6;
    background-color: #eff6ff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-widget .filter-indicator {
    position: absolute;
    top: 8px;
    right: 8px;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: #6b7280;
    font-size: 0.8rem;
}

.filter-widget:hover .filter-indicator,
.filter-widget.active .filter-indicator {
    opacity: 1;
}

.filter-widget.active .filter-indicator {
    color: #3b82f6;
}

/* Filter feedback */
.filter-active {
    background-color: #eff6ff !important;
    border-left: 4px solid #3b82f6 !important;
}

/* Accessibility */
.filter-widget:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Professional Average Days Widget */
.avg-days-widget {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.avg-days-widget:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #cbd5e1;
}

.avg-days-container {
    display: flex;
    align-items: center;
    padding: 20px 24px;
    gap: 20px;
}

.avg-days-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #ffffff;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.avg-days-content {
    flex: 1;
    min-width: 0;
}

.avg-days-value {
    font-size: 2.25rem;
    font-weight: 700;
    line-height: 1;
    color: #1e293b;
    margin-bottom: 4px;
}

.avg-days-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
}

.avg-days-indicator {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.indicator-bar {
    height: 6px;
    background-color: #f1f5f9;
    border-radius: 3px;
    overflow: hidden;
    position: relative;
}

.indicator-fill {
    height: 100%;
    border-radius: 3px;
    transition: all 0.8s ease;
    width: 0%;
}

.indicator-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-align: left;
}

/* Dynamic colors for avg days */
.avg-days-excellent {
    background: linear-gradient(135deg, #10b981, #059669);
}

.avg-days-good {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.avg-days-poor {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.avg-days-neutral {
    background: linear-gradient(135deg, #6b7280, #4b5563);
}

/* Indicator fill colors */
.indicator-fill.excellent {
    background: linear-gradient(90deg, #10b981, #34d399);
}

.indicator-fill.good {
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
}

.indicator-fill.poor {
    background: linear-gradient(90deg, #ef4444, #f87171);
}

.indicator-fill.neutral {
    background: linear-gradient(90deg, #6b7280, #9ca3af);
}

/* Compact Average Days Widget */
.avg-days-widget-compact {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 12px;
    height: 60px;
}

.avg-days-widget-compact:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-color: #cbd5e1;
}

.avg-days-icon-compact {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: #ffffff;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.avg-days-content-compact {
    flex: 1;
    min-width: 0;
}

.avg-days-main {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 4px;
}

.avg-days-value-compact {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #1e293b;
}

.avg-days-label-compact {
    font-size: 0.75rem;
    font-weight: 500;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.avg-days-bar-compact {
    height: 3px;
    background-color: #f1f5f9;
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 2px;
}

.indicator-fill-compact {
    height: 100%;
    border-radius: 2px;
    transition: all 0.8s ease;
    width: 0%;
}

.avg-days-status-compact {
    font-size: 0.6875rem;
    font-weight: 500;
    color: #64748b;
    line-height: 1;
}

/* Dynamic colors for compact widget */
.avg-days-excellent {
    background: linear-gradient(135deg, #10b981, #059669);
}

.avg-days-good {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.avg-days-poor {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.avg-days-neutral {
    background: linear-gradient(135deg, #6b7280, #4b5563);
}

/* Medium Progress Widget */
.avg-days-widget-medium {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    height: 120px;
}

.avg-days-widget-medium:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #cbd5e1;
}

.widget-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.widget-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: #ffffff;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.widget-title {
    flex: 1;
}

.widget-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 2px;
}

.widget-range {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 500;
}

.progress-section {
    margin-bottom: 12px;
}

.progress-bar-container {
    position: relative;
}

.progress-bar-track {
    height: 8px;
    background: #f1f5f9;
    border-radius: 4px;
    position: relative;
    overflow: visible;
    margin-bottom: 8px;
}

.progress-bar-fill {
    height: 100%;
    border-radius: 4px;
    transition: all 0.8s ease;
    width: 0%;
}

.progress-indicator {
    position: absolute;
    top: -8px;
    transform: translateX(-50%);
    transition: left 0.8s ease;
    left: 0%;
}

.indicator-value {
    background: #1e293b;
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.indicator-arrow {
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 4px solid #1e293b;
    margin: 0 auto;
}

.progress-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.6875rem;
    color: #64748b;
    font-weight: 500;
}

.widget-status {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 500;
    text-align: center;
}

/* Dynamic colors for medium widget */
.widget-icon.excellent {
    background: linear-gradient(135deg, #10b981, #059669);
}

.widget-icon.good {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.widget-icon.poor {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.widget-icon.neutral {
    background: linear-gradient(135deg, #6b7280, #4b5563);
}

.progress-bar-fill.excellent {
    background: linear-gradient(90deg, #10b981, #34d399);
}

.progress-bar-fill.good {
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
}

.progress-bar-fill.poor {
    background: linear-gradient(90deg, #ef4444, #f87171);
}

.progress-bar-fill.neutral {
    background: linear-gradient(90deg, #6b7280, #9ca3af);
}

/* Responsive design */
@media (max-width: 768px) {
    .avg-days-widget-compact {
        padding: 10px 12px;
        gap: 10px;
        height: 50px;
    }
    
    .avg-days-icon-compact {
        width: 28px;
        height: 28px;
        font-size: 14px;
    }
    
    .avg-days-value-compact {
        font-size: 1.25rem;
    }
    
    .avg-days-label-compact {
        font-size: 0.6875rem;
    }
}

.inventory-row.selected {
    background-color: #eff6ff !important;
}

.conversion-badge {
    background: #e0f2fe;
    color: #0277bd;
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
}
</style>

<script>
// Wait for the document to be ready and ensure jQuery is available
document.addEventListener('DOMContentLoaded', function() {
    // Initialize localStorage for vehicles tab
    initializeVehiclesLocalStorage();
    
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

        // Initialize Inventory Table
        window.inventoryTable = $('#inventoryTable').DataTable({
            processing: true,
            serverSide: false, // We'll load data from the endpoint directly
            ajax: {
                url: '<?= base_url('public/bos/get_inventory.php') ?>',
                type: 'GET',
                dataSrc: function(json) {

                    
                    // Handle the response structure from get_inventory.php
                    let data = json;
                    if (json.success && json.data) {
                        data = json.data;
                    }
                    
                    // Ensure data is an array
                    if (!Array.isArray(data)) {

                        return [];
                    }
                    
                    updateInventoryStats(data);
                    return data.map((row, index) => {
                        // Calculate days from date_detail if it exists
                        let calculatedDays = '';
                        let formattedDate = '';
                        
                        if (row[0]) {
                            try {
                                // Handle different date formats that might come from Google Sheets
                                let dateInDetail;
                                
                                // Check if it's already a valid date string
                                if (row[0].includes('/')) {
                                    // Format like "8/9" or "8/9/2024"
                                    const parts = row[0].split('/');
                                    if (parts.length === 2) {
                                        // Assume current year for "8/9" format
                                        const currentYear = new Date().getFullYear();
                                        dateInDetail = new Date(`${parts[0]}/${parts[1]}/${currentYear}`);
                                    } else if (parts.length === 3) {
                                        // Full date "8/9/2024"
                                        dateInDetail = new Date(row[0]);
                                    }
                                } else {
                                    // Try parsing as is
                                    dateInDetail = new Date(row[0]);
                                }
                                
                                // Format the date properly
                                if (dateInDetail && !isNaN(dateInDetail.getTime())) {
                                    formattedDate = dateInDetail.toLocaleDateString('en-US', {
                                        month: 'numeric',
                                        day: 'numeric',
                                        year: 'numeric'
                                    });
                                    
                                    // Calculate days difference
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
                        
                        const mappedRow = {
                            id: index,
                            date_detail: formattedDate,
                            days_detail: calculatedDays,
                            keys: row[1] || '', // Shifted: was row[2]
                            stock_number: row[2] || '', // Shifted: was row[3] 
                            vehicle: row[3] || '', // Shifted: was row[4]
                            write_up_date: row[4] || '', // Shifted: was row[5]
                            raw_data: row
                        };
                        

                        
                        return mappedRow;
                    });
                },
                error: function(xhr, error, thrown) {

                    showToast('<?= lang('App.error_loading_inventory') ?>', 'error');
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
                        return data ? `<span class="text-muted">${data}</span>` : '-';
                    }
                },
                {
                    data: 'days_detail',
                    type: 'num', // Ensure proper numeric sorting
                    render: function(data, type, row) {
                        // For sorting, return the raw number
                        if (type === 'sort' || type === 'type') {
                            return data || 0;
                        }
                        
                        if (!data || data === '' || data === 0) return '-';
                        const days = parseInt(data);
                        if (isNaN(days)) return '-';
                        
                        // New classification system: 0-1 success, 2-5 warning, 6+ danger
                        let badgeClass = 'bg-success';
                        if (days >= 6) badgeClass = 'bg-danger';
                        else if (days >= 2) badgeClass = 'bg-warning';
                        
                        // Use translation for singular/plural
                        const dayText = days === 1 ? '<?= lang('App.day') ?>' : '<?= lang('App.days') ?>';
                        
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
                        return data ? `<strong class="text-primary">${data}</strong>` : '-';
                    }
                },
                {
                    data: 'vehicle',
                    render: function(data, type, row) {
                        return data ? `<span class="vehicle-info">${data}</span>` : '-';
                    }
                },
                {
                    data: 'write_up_date',
                    render: function(data, type, row) {
                        return data ? `<span class="text-muted">${data}</span>` : '-';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `<button class="btn btn-success btn-sm convert-single-btn" data-row='${JSON.stringify(row)}'>
                            <i class="ri-arrow-right-line me-1"></i>
                            <?= lang('App.move_to_recon') ?>
                        </button>`;
                    }
                }
            ],
            order: [[2, 'desc']], // Sort by days in this step (descending - most days first)
            pageLength: 10,
            responsive: true,
            language: {
                processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                emptyTable: '<div class="text-center py-4"><i class="ri-store-3-line display-4 text-muted"></i><h6 class="mt-2"><?= lang('App.no_inventory_available') ?></h6><p class="text-muted"><?= lang('App.inventory_empty') ?></p></div>'
            }
        });

        // Initialize Inventory Orders Table
        window.inventoryOrdersTable = $('#inventoryOrdersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url: '<?= base_url('recon_orders/inventory_orders_data') ?>',
                type: 'POST',
                data: function(d) {
                    d.ajax = true;
                    return d;
                },
                error: function(xhr, error, thrown) {

                    showToast('Error loading inventory orders', 'error');
                }
            },
            columns: [
                {
                    data: 'order_number',
                    render: function(data, type, row) {
                        return `<strong class="text-primary">#${data}</strong>`;
                    }
                },
                {
                    data: 'stock',
                    render: function(data, type, row) {
                        return data ? `<span class="badge bg-light text-dark">${data}</span>` : '-';
                    }
                },
                {
                    data: 'vehicle',
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    data: 'client_name',
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    data: 'service_date',
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        const statusColors = {
                            'pending': 'warning',
                            'in_progress': 'info',
                            'completed': 'success',
                            'cancelled': 'danger'
                        };
                        const color = statusColors[data] || 'secondary';
                        return `<span class="badge bg-${color}">${data}</span>`;
                    }
                },
                {
                    data: 'created_by_name',
                    render: function(data, type, row) {
                        return data ? `<small class="text-muted">${data}</small>` : '-';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `<div class="btn-group btn-group-sm">
                            <a href="<?= base_url('recon_orders/view/') ?>${row.id}" class="btn btn-outline-primary btn-sm">
                                <i class="ri-eye-line"></i>
                            </a>
                            <a href="<?= base_url('recon_orders/edit/') ?>${row.id}" class="btn btn-outline-secondary btn-sm">
                                <i class="ri-edit-line"></i>
                            </a>
                        </div>`;
                    }
                }
            ],
            order: [[0, 'desc']], // Sort by order number
            pageLength: 10,
            responsive: true,
            language: {
                processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                emptyTable: '<div class="text-center py-4"><i class="ri-file-list-3-line display-4 text-muted"></i><h6 class="mt-2">No orders from inventory</h6><p class="text-muted">Orders created from inventory will appear here</p></div>'
            }
        });



        // Event Handlers
        setupEventHandlers();
        refreshStats();
        
        // Setup widget-based filtering
        setupWidgetFiltering();
        
        // Restore previous state
        restorePreviousState();
        

    }

    function setupEventHandlers() {
        const $ = window.jQuery;

        // Select all inventory checkbox
        $('#selectAllInventory').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.inventory-checkbox').prop('checked', isChecked);
            updateConvertButtonState();
        });

        // Individual inventory checkboxes
        $(document).on('change', '.inventory-checkbox', function() {
            updateConvertButtonState();
            
            // Update select all checkbox state
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
                showToast('<?= lang('App.select_items_to_convert') ?>', 'warning');
                return;
            }

            // For bulk conversion, we'll show a simplified modal
            showBulkConversionModal(selectedItems);
        });

        // Single convert buttons - Open main modal with inventory data
        $(document).on('click', '.convert-single-btn', function() {
            const rowData = JSON.parse($(this).attr('data-row'));
            openMainModalWithInventoryData(rowData);
        });

        // Refresh buttons
        $('#refreshInventoryBtn').on('click', function() {
            window.inventoryTable.ajax.reload();
            showToast('<?= lang('App.inventory_refreshed') ?>', 'success');
        });

        $('#refreshInventoryOrdersBtn').on('click', function() {
            window.inventoryOrdersTable.ajax.reload();
        });



    }

    function updateConvertButtonState() {
        const checkedCount = $('.inventory-checkbox:checked').length;
        $('#convertSelectedBtn').prop('disabled', checkedCount === 0);
        
        if (checkedCount > 0) {
            $('#convertSelectedBtn').html(`<i class="ri-arrow-right-line me-1"></i> <?= lang('App.move_selected') ?> (${checkedCount})`);
        } else {
            $('#convertSelectedBtn').html(`<i class="ri-arrow-right-line me-1"></i> <?= lang('App.move_selected') ?>`);
        }
    }

    function openMainModalWithInventoryData(rowData) {
        // Create inventory notes in the requested format
        const daysText = rowData.days_detail ? 
            (rowData.days_detail === 1 ? `${rowData.days_detail} day` : `${rowData.days_detail} days`) : 
            'N/A';
            
        const inventoryNotes = `Auto-filled from inventory

Date in Detail: ${rowData.date_detail || 'N/A'}
Days in Detail: ${daysText}
Keys: ${rowData.keys || 'N/A'}
Write Up Date: ${rowData.write_up_date || 'N/A'}
Stock Number: ${rowData.stock_number || 'N/A'}
Original Vehicle: ${rowData.vehicle || 'N/A'}`;

        // Store inventory data in the main modal
        $('#reconOrderModal').data('inventory-data', {
            stock: rowData.stock_number || '',
            vehicle: rowData.vehicle || '',
            notes: inventoryNotes,
            service_date: new Date().toISOString().split('T')[0]
        });
        
        // Open the main modal
        $('#reconOrderModal').modal('show');
    }

    function showBulkConversionModal(selectedItems) {
        // For bulk conversion, show confirmation and then open main modal with first item data
        if (window.showConfirmDialog) {
            window.showConfirmDialog(
                '<?= lang('App.confirm_conversion') ?>',
                `<?= lang('App.move_multiple_stocks') ?>? (${selectedItems.length} <?= lang('App.selected_items') ?>)`,
                '<?= lang('App.yes_convert') ?>',
                '<?= lang('App.cancel') ?>'
            ).then((result) => {
                if (result.isConfirmed) {
                    // For bulk, open modal with first item's data as template
                    if (selectedItems.length > 0) {
                        openMainModalWithInventoryData(selectedItems[0]);
                    }
                }
            });
        } else {
            if (confirm(`<?= lang('App.move_multiple_stocks') ?>? (${selectedItems.length} <?= lang('App.selected_items') ?>)`)) {
                // For bulk, open modal with first item's data as template
                if (selectedItems.length > 0) {
                    openMainModalWithInventoryData(selectedItems[0]);
                }
            }
        }
    }





    function updateAvgDaysWidget(avgDays, totalItems, daysData) {
        const $ = window.jQuery;
        
        // Calculate max days from the data
        const maxDays = daysData && daysData.length > 0 ? Math.max(...daysData) : avgDays;
        const minDays = 0;
        
        // Update the value
        $('#avgDaysInDetail').text(avgDays);
        
        // Update range display
        $('#avgDaysRange').text(`${minDays} - ${maxDays} días`);
        $('#maxDaysLabel').text(maxDays);
        
        // Calculate position on progress bar (0% to 100%)
        const progressPercentage = maxDays > 0 ? (avgDays / maxDays) * 100 : 0;
        
        // Determine status and color scheme based on avgDays
        let statusClass, fillClass, statusText;
        
        if (avgDays <= 2) {
            statusClass = 'excellent';
            fillClass = 'excellent';
            statusText = '<?= lang('App.excellent_turnaround') ?>';
        } else if (avgDays <= 5) {
            statusClass = 'good';
            fillClass = 'good';
            statusText = '<?= lang('App.good_turnaround') ?>';
        } else if (avgDays <= 10) {
            statusClass = 'poor';
            fillClass = 'poor';
            statusText = '<?= lang('App.needs_attention') ?>';
        } else {
            statusClass = 'poor';
            fillClass = 'poor';
            statusText = '<?= lang('App.critical_delay') ?>';
        }
        
        // Update icon colors
        const $icon = $('#avgDaysIcon');
        $icon.removeClass('excellent good poor neutral');
        $icon.addClass(statusClass);
        
        // Update progress bar
        const $fill = $('#progressFill');
        $fill.removeClass('excellent good poor neutral');
        $fill.addClass(fillClass);
        
        // Update progress indicator position and fill
        const $indicator = $('#progressIndicator');
        
        // Animate the progress bar and indicator
        setTimeout(() => {
            $fill.css('width', progressPercentage + '%');
            $indicator.css('left', progressPercentage + '%');
        }, 100);
        
        // Update status text
        $('#avgDaysStatus').text(`${statusText} • ${totalItems} items • Máximo: ${maxDays} días`);
    }

    function updateInventoryStats(data) {
        if (!Array.isArray(data)) return;
        
        const total = data.length;
        
        // Calculate days and categorize items
        const daysData = [];
        let recentItems = 0; // 0-1 days
        let moderateItems = 0; // 2-5 days  
        let agedItems = 0; // 6+ days
        
        data.forEach(row => {
            if (row[0]) { // date_detail exists
                try {
                    // Handle different date formats that might come from Google Sheets
                    let dateInDetail;
                    
                    if (row[0].includes('/')) {
                        // Format like "8/9" or "8/9/2024"
                        const parts = row[0].split('/');
                        if (parts.length === 2) {
                            // Assume current year for "8/9" format
                            const currentYear = new Date().getFullYear();
                            dateInDetail = new Date(`${parts[0]}/${parts[1]}/${currentYear}`);
                        } else if (parts.length === 3) {
                            // Full date "8/9/2024"
                            dateInDetail = new Date(row[0]);
                        }
                    } else {
                        // Try parsing as is
                        dateInDetail = new Date(row[0]);
                    }
                    
                    if (dateInDetail && !isNaN(dateInDetail.getTime())) {
                        const today = new Date();
                        const diffTime = today - dateInDetail;
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        if (diffDays >= 0) {
                            daysData.push(diffDays);
                            
                            // Categorize items by day ranges
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
        
        // Update basic stats
        $('#totalInventoryItems').text(total);
        $('#avgDaysInDetail').text(avgDays);
        
        // Update category counts
        $('#recentItems').text(recentItems);
        $('#moderateItems').text(moderateItems);
        $('#agedItems').text(agedItems);
        
        // Update professional average days widget
        updateAvgDaysWidget(avgDays, daysData.length, daysData);
    }

    function refreshStats() {
        const $ = window.jQuery;
        $.get('<?= base_url('recon_orders/vehicles_stats') ?>', function(data) {
            if (data.stats) {
                $('#totalVehicles').text(data.stats.total_vehicles || 0);
                $('#recentVehicles').text(data.stats.recent_vehicles || 0);
                $('#mostServicedCount').text(data.stats.most_serviced ? data.stats.most_serviced.total_orders : 0);
                $('#popularMakesCount').text(data.stats.popular_makes ? data.stats.popular_makes.length : 0);
            }
        }).fail(function() {

        });
    }

        // Setup widget-based filtering function
    function setupWidgetFiltering() {
        const $ = window.jQuery;
        
        // Widget click handlers
        $('.filter-widget').on('click', function() {
            const filter = $(this).data('filter');
            
            
            applyWidgetFilter(filter, $(this));
        });
        
        // Clear all filters button
        $('#clearAllFilters').on('click', function() {
            clearAllFilters();
        });
        
        // Keyboard accessibility
        $('.filter-widget').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).click();
            }
        });
    }
    
    function applyWidgetFilter(filter, $widget) {
        const $ = window.jQuery;
        

        
        // Remove active class from all widgets
        $('.filter-widget').removeClass('active');
        
        // Add active class to clicked widget
        $widget.addClass('active');
        
        // Store current filter
        window.currentDayFilter = filter;

        
        // Apply the filter
        applyDayRangeFilter(filter);
        
        // Save state to localStorage
        window.saveVehiclesState();
        
        // Show feedback
        const filterName = filter === '' ? '<?= lang('App.all_day_ranges') ?>' : 
                          filter === '0-1' ? '<?= lang('App.recent_0_1_days') ?>' :
                          filter === '2-5' ? '<?= lang('App.moderate_2_5_days') ?>' :
                          filter === '6+' ? '<?= lang('App.aged_6_plus_days') ?>' : filter;
        

        showToast(`<?= lang('App.filters_applied') ?>: ${filterName}`, 'info');
    }
    
    function clearAllFilters() {
        const $ = window.jQuery;
        
        // Remove active class from all widgets
        $('.filter-widget').removeClass('active');
        
        // Clear current filter
        window.currentDayFilter = '';
        
        // Apply empty filter (show all)
        applyDayRangeFilter('');
        
        // Save state
        window.saveVehiclesState();
        
        showToast('<?= lang('App.filters_cleared') ?>', 'info');
    }
    
    function restorePreviousState() {
        const $ = window.jQuery;
        const savedState = window.loadVehiclesState();
        
        if (savedState && savedState.activeFilter !== undefined) {
            // Find the widget with the saved filter
            const $widget = $(`.filter-widget[data-filter="${savedState.activeFilter}"]`);
            if ($widget.length > 0) {
                // Apply the saved filter
                applyWidgetFilter(savedState.activeFilter, $widget);
            }
        }
    }
    
    function applyDayRangeFilter(range) {
        if (!window.inventoryTable) {

            return;
        }
        

        
        // IMPORTANT: Clear ALL custom searches first
        $.fn.dataTable.ext.search = [];
        
        if (range && range !== '') {
            // Add named custom search function
            // Create a closure to capture the current range
            const dayRangeFilterFn = function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'inventoryTable') return true;
                
                // Get the actual row data from DataTables
                const table = window.inventoryTable;
                const rowData = table.row(dataIndex).data();
                
                // Get the days_detail from the original data
                const days = rowData ? rowData.days_detail : null;
                
                if (days === null || days === undefined || days === '' || isNaN(days)) {
                    return range === '0-1'; // Show items with no days data in 0-1 range only
                }
                
                const daysNum = parseInt(days);
                
                // Use the captured range variable directly
                
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
        
        // Redraw table
        window.inventoryTable.draw();
        
        
    }

    // Function to initialize localStorage for vehicles tab
    function initializeVehiclesLocalStorage() {
        const STORAGE_KEY = 'reconOrders_vehiclesTab_state';
        
        // Save current filter state
        window.saveVehiclesState = function() {
            const state = {
                activeFilter: window.currentDayFilter || '',
                timestamp: Date.now()
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
        };
        
        // Load previous filter state
        window.loadVehiclesState = function() {
            try {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (saved) {
                    const state = JSON.parse(saved);
                    // Only restore if less than 24 hours old
                    if (Date.now() - state.timestamp < 24 * 60 * 60 * 1000) {
                        return state;
                    }
                }
            } catch (e) {

            }
            return null;
        };
        
        // Clear vehicles state
        window.clearVehiclesState = function() {
            localStorage.removeItem(STORAGE_KEY);
        };
    }

    // Initialize all tables
    initializeTables();
    
    // Refresh stats every 30 seconds
    setInterval(refreshStats, 30000);
});



function showToast(message, type = 'success') {
    // Try to use global showToast first
    if (typeof window.showToast === 'function') {
        window.showToast(type, message);
        return;
    }
    
    // Fallback to Toastify if available
    if (typeof Toastify !== 'undefined') {
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "right",
            backgroundColor: type === 'success' ? "#10b981" : (type === 'warning' ? "#f59e0b" : "#ef4444"),
    }).showToast();
        return;
    }
    
    // Final fallback to alert
    if (type === 'error') {
        alert(message);
    }
}
</script>