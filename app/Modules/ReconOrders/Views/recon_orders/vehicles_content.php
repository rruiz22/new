<div class="container01">
    
    <!-- Inventory Management Container -->
    <div class="dashboard-container mb-4">
        
        <div class="dashboard-body">
    
    <!-- Dealer Inventory Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div style="margin: 1rem 3rem 3rem 3rem!important;" class="card-header">
                <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="dashboard-title">
                                <i class="ri-car-line me-3"></i>
                                Inventory Management Dashboard
                                <span id="syncIndicator" class="badge bg-success ms-2" style="font-size: 0.75rem; font-weight: 500;">
                                    <i class="ri-wifi-line me-1"></i>Live Sync
                                </span>
                            </h4>
                            <p class="dashboard-subtitle">
                                <?= lang('App.monitor_and_track_all_vehicles') ?> • Auto-refresh every 30s
                                <br>
                                <span id="lastRefreshInfo" style="font-size: 0.7rem; opacity: 0.7;">
                                    Last refresh: Loading...
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary" id="refreshInventoryBtn">
                                    <i class="ri-refresh-line me-1"></i>
                                    <?= lang('App.refresh_inventory') ?>
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="clearAllFilters">
                                    <i class="ri-filter-off-line me-1"></i>
                                    <?= lang('App.clear_filters') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Widgets Row -->
    <div  class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div style="margin: 0px 10px 0px 10px!important;" class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modern-card-title">
                                <i class="ri-bar-chart-line"></i>
                                <?= lang('App.inventory_stats') ?>
                            </h5>
                            <p class="modern-card-subtitle"><?= lang('App.click_to_filter') ?></p>
                        </div>
                        <div class="avg-days-mini-widget">
                            <div class="avg-days-value" id="avgDaysCompact">
                                <span class="avg-number" id="avgDaysNumber">0</span>
                                <span class="avg-label">Avg Days</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div  class="modern-card-body">
                    <!-- Inventory Stats - Interactive Filter Widgets -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="stats-mini filter-widget" data-filter="" role="button" tabindex="0">
                                <div class="stats-icon bg-primary">
                                    <i class="ri-car-line"></i>
                                </div>
                                <div class="stats-content">
                                    <h6 class="mb-0" id="totalInventoryItems">0</h6>
                                    <small class="text-muted">Total</small>
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
                                    <small class="text-muted"><?= lang('App.recent') ?></small>
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
                                    <small class="text-muted"><?= lang('App.attention') ?></small>
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
                                    <small class="text-muted"><?= lang('App.attention') ?></small>
                                </div>
                                <div class="filter-indicator">
                                    <i class="ri-filter-line"></i>
                                </div>
                            </div>
                        </div>

                        
                        
                    </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    

                    
    <!-- Inventory Table Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title">
                                <i class="ri-table-line"></i>
                                <?= lang('App.inventory_table') ?>
                            </h5>
                            <p class="card-subtitle"><?= lang('App.detailed_inventory_view') ?></p>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-info" id="filterIncompleteBtn">
                                    <i class="ri-filter-line me-1"></i>
                                    Show Incomplete Only
                                </button>
                                <button type="button" class="btn btn-outline-info" id="filterCompleteBtn">
                                    <i class="ri-filter-line me-1"></i>
                                    Show Complete Only
                                </button>
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="inventoryTable" class="table table-hover align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th width="40" class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllInventory">
                                        </div>
                                    </th>
                                    <th class="text-center"><?= lang('App.date_in_detail') ?></th>
                                    <th class="text-center"><?= lang('App.day_in_this_step') ?></th>
                                    <th class="text-center"><?= lang('App.keys') ?></th>
                                    <th class="text-center"><?= lang('App.stock_number') ?></th>
                                    <th class="text-center"><?= lang('App.vehicle') ?></th>
                                    <th class="text-center">Notes</th>
                                    <th class="text-center"><?= lang('App.status') ?></th>
                                    <th class="text-center"><?= lang('App.actions') ?></th>
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
    </div>

    <!-- Staff Management Container -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="staff-title">
                        <i class="ri-admin-line me-2"></i>
                        Staff Management Tools
                    </h4>
                    <p class="dashboard-subtitle">Advanced tools for staff members only</p>
                </div>
            </div>
        </div>
        <div class="dashboard-body">

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
                                    <th>Source</th>
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


/* Mini Average Days Widget */
.avg-days-mini-widget {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    color: #1e293b;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.avg-days-mini-widget:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.avg-days-value {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.avg-number {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}

.avg-label {
    font-size: 0.7rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    opacity: 0.9;
}


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



.search-bar1 {
    max-width: 300px;
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

/* Enhanced stock number styling - text only */
.stock-number-enhanced {
    font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', 'Consolas', monospace;
    color: #2563eb;
    font-size: 1.25em;
    font-weight: 900;
    letter-spacing: 1.5px;
    line-height: 1;
    display: inline-block;
    text-transform: uppercase;
    text-shadow: 0 1px 2px rgba(37, 99, 235, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.stock-number-enhanced:hover {
    color: #1d4ed8;
    text-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
    transform: scale(1.05);
}

/* Enhanced date styling */
.date-enhanced {
    font-size: 1.1em;
    font-weight: 600;
    color: #1e293b;
    letter-spacing: 0.5px;
}

/* Status-based row background colors */
.status-pending {
    background-color: rgba(245, 158, 11, 0.08) !important;
    border-left: 4px solid #f59e0b !important;
}

.status-pending:hover {
    background-color: rgba(245, 158, 11, 0.12) !important;
}

.status-in-progress {
    background-color: rgba(59, 130, 246, 0.08) !important;
    border-left: 4px solid #3b82f6 !important;
}

.status-in-progress:hover {
    background-color: rgba(59, 130, 246, 0.12) !important;
}

.status-completed {
    background-color: rgba(16, 185, 129, 0.08) !important;
    border-left: 4px solid #10b981 !important;
}

.status-completed:hover {
    background-color: rgba(16, 185, 129, 0.12) !important;
}

.status-cancelled {
    background-color: rgba(239, 68, 68, 0.08) !important;
    border-left: 4px solid #ef4444 !important;
}

.status-cancelled:hover {
    background-color: rgba(239, 68, 68, 0.12) !important;
}

.status-no-status {
    background-color: rgba(107, 114, 128, 0.05) !important;
    border-left: 4px solid #6b7280 !important;
}

.status-no-status:hover {
    background-color: rgba(107, 114, 128, 0.08) !important;
}

/* Ensure status rows have smooth transitions */
#inventoryTable tbody tr {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

/* Duplicate Stock Alert Styling */
.duplicate-alert,
[id^="duplicate-icon-"] {
    animation: pulse-warning 2s infinite;
    filter: drop-shadow(0 0 2px rgba(245, 158, 11, 0.5));
    position: relative;
    z-index: 10;
}

.duplicate-alert:hover,
[id^="duplicate-icon-"]:hover {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

@keyframes pulse-warning {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.6;
    }
    100% {
        opacity: 1;
    }
}

/* Enhanced tooltip for duplicates */
.duplicate-alert {
    position: relative;
}

/* Custom tooltip for duplicate alerts */
.duplicate-alert::after {
    content: attr(title);
    position: absolute;
    bottom: 130%;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
    z-index: 1050;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
    pointer-events: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    min-width: max-content;
}

.duplicate-alert::before {
    content: '';
    position: absolute;
    bottom: 120%;
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.9);
    z-index: 1050;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.duplicate-alert:hover::after,
.duplicate-alert:hover::before {
    opacity: 1;
    visibility: visible;
}

.duplicate-alert:hover::after {
    transform: translateX(-50%) translateY(-2px);
}

/* Duplicate stock row highlighting */
.inventory-row.has-duplicate {
    background-color: #fef3cd !important;
    border-left: 3px solid #ffc107 !important;
}

.inventory-row.has-duplicate:hover {
    background-color: #fff3cd !important;
}

/* Center align table headers */
#inventoryTable thead th {
    text-align: center !important;
    vertical-align: middle !important;
}

/* Center align specific columns content */
#inventoryTable tbody td:nth-child(2), /* Date in Detail */
#inventoryTable tbody td:nth-child(3), /* Day in This Step */
#inventoryTable tbody td:nth-child(4), /* Keys */
#inventoryTable tbody td:nth-child(5), /* Stock Number */
#inventoryTable tbody td:nth-child(8), /* Status */
#inventoryTable tbody td:nth-child(9)  /* Actions */
{
    text-align: center !important;
    vertical-align: middle !important;
}

/* Hidden loading indicator */
.dataTables_processing {
    display: none !important;
}

/* Alternative: Very discrete loading in table header */
.dataTables_wrapper .dataTables_processing {
    position: absolute !important;
    top: -2px !important;
    right: 5px !important;
    left: auto !important;
    width: auto !important;
    height: auto !important;
    margin: 0 !important;
    padding: 2px 6px !important;
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    font-size: 0.6rem !important;
    color: rgba(108, 117, 125, 0.5) !important;
    z-index: 1001 !important;
    opacity: 0.3 !important;
}

/* Filter Button Styles */
#filterIncompleteBtn, #filterCompleteBtn {
    transition: all 0.3s ease;
    position: relative;
}

#filterIncompleteBtn.btn-info {
    background: linear-gradient(135deg, #0dcaf0, #0a9ec7);
    border-color: #0dcaf0;
    box-shadow: 0 2px 8px rgba(13, 202, 240, 0.3);
    transform: translateY(-1px);
}

#filterIncompleteBtn.btn-info:hover {
    background: linear-gradient(135deg, #31d2f2, #0dcaf0);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 202, 240, 0.4);
}

#filterCompleteBtn.btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    border-color: #10b981;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    transform: translateY(-1px);
}

#filterCompleteBtn.btn-success:hover {
    background: linear-gradient(135deg, #34d399, #10b981);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

#filterIncompleteBtn.btn-info::after {
    content: '';
    position: absolute;
    top: -2px;
    right: -2px;
    width: 8px;
    height: 8px;
    background: #28a745;
    border-radius: 50%;
    border: 2px solid white;
    animation: pulse-filter 2s infinite;
}

#filterCompleteBtn.btn-success::after {
    content: '';
    position: absolute;
    top: -2px;
    right: -2px;
    width: 8px;
    height: 8px;
    background: #0dcaf0;
    border-radius: 50%;
    border: 2px solid white;
    animation: pulse-filter 2s infinite;
}

@keyframes pulse-filter {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.7;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}


</style>

<script>
// Wait for the document to be ready and ensure jQuery is available
document.addEventListener('DOMContentLoaded', function() {
    // Initialize filter state variables
    window.incompleteFilterActive = false;
    window.completeFilterActive = false;
    window.currentDayFilter = '';
    
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
        
        
        
        if (duplicates.size > 0) {
            console.log('🚨 Duplicate stock numbers detected:', Array.from(duplicates));
            
            // Only show toast if duplicates have changed or it's the first time
            const duplicateKey = Array.from(duplicates).sort().join(',');
            if (!window.lastDuplicateKey || window.lastDuplicateKey !== duplicateKey) {
                const duplicateCount = duplicates.size;
                const duplicateList = Array.from(duplicates).slice(0, 3).join(', ');
                const message = duplicateCount > 3 
                    ? `<?= lang('App.multiple_duplicates_found') ?>: ${duplicateCount} (${duplicateList}...)`
                    : `<?= lang('App.duplicates_found') ?>: ${duplicateList}`;
                
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
            console.log('⚠️ No duplicates to update or table not ready');
            return;
        }
        
        
        // Force a complete redraw to ensure the render function is called
        window.inventoryTable.draw(false);
        
        
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

        // Initialize Inventory Table
        window.inventoryTable = $('#inventoryTable').DataTable({
            "scrollY":        "510px",
            "scrollCollapse": true,
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
                    
                    // Detect duplicate stock numbers
                    detectDuplicateStocks(data);
                    
                    // Schedule order info loading after table data is processed
                    setTimeout(() => {
                        loadOrderInfoForInventory();
                        
                        // Force table redraw to show duplicate icons immediately
                        if (window.duplicateStocks && window.duplicateStocks.size > 0) {
                            console.log('🔄 Forcing table redraw to show duplicate icons for:', Array.from(window.duplicateStocks));
                            updateDuplicateIcons();
                        }
                    }, 100); // Reduced timeout for faster response
                    
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
                            notes: row[5] || '', // Notes from webhook (was write_up_date)
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
                        return data ? `<span class="date-enhanced">${data}</span>` : '-';
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
                        if (!data) return '<div class="text-center">-</div>';
                        
                        // Check for duplicates
                        let duplicateIcon = '';
                        if (window.duplicateStocks && window.duplicateStocks.has(data.toString().trim())) {
                            duplicateIcon = `<i class="ri-alert-line text-warning ms-2 duplicate-alert" 
                                title="<?= lang('App.duplicate_stock_detected') ?>" 
                                style="font-size: 0.9rem; cursor: pointer; animation: pulse-warning 2s infinite;"></i>`;
                            console.log('🚨 Rendering duplicate icon for stock:', data);
                        }
                        
                        return `<div class="d-flex align-items-center justify-content-center">
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
                        // Show first 30 characters with tooltip for full content
                        const shortText = data.length > 30 ? data.substring(0, 30) + '...' : data;
                        return `<span class="text-muted" title="${data}" data-bs-toggle="tooltip">${shortText}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        // This will be populated by the order info lookup
                        return `<div class="status-service-info" data-stock="${row.stock_number}">
                            <span class="text-muted">Loading...</span>
                        </div>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        // Check if the item has a status (should be disabled if it has any status)
                        const stockNumber = row.stock_number;
                        let hasStatus = false;
                        let statusText = 'No Status';
                        
                        if (stockNumber && window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
                            hasStatus = true;
                            const orderInfo = window.orderInfoLookup[stockNumber];
                            statusText = orderInfo.status || 'Unknown Status';
                        }
                        
                        // Button is enabled only when there's NO status (no status yet)
                        const isEnabled = !hasStatus;
                        const buttonClass = isEnabled ? 'btn-success' : 'btn-secondary';
                        const disabledAttr = isEnabled ? '' : 'disabled';
                        const titleText = isEnabled ? 
                            'Move to Recon Orders' : 
                            `Cannot move: Item already has status (${statusText})`;
                        
                        return `<button class="btn ${buttonClass} btn-sm convert-single-btn" 
                                       data-row='${JSON.stringify(row)}' 
                                       ${disabledAttr}
                                       title="${titleText}">
                            <i class="ri-arrow-right-line me-1"></i>
                            <?= lang('App.move_to_recon') ?>
                        </button>`;
                    }
                }
            ],
            order: [[2, 'desc']], // Sort by days in this step (descending - most days first)
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            responsive: true,
            language: {
                processing: '<span style="font-size: 0.6rem; color: rgba(108, 117, 125, 0.4);">•</span>',
                emptyTable: '<div class="text-center py-4"><i class="ri-store-3-line display-4 text-muted"></i><h6 class="mt-2"><?= lang('App.no_inventory_available') ?></h6><p class="text-muted"><?= lang('App.inventory_empty') ?></p></div>'
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
                
                // Initialize tooltips if available (non-blocking)
                setTimeout(() => {
                    try {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                            tooltipTriggerList.map(function (tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl);
                            });
                        }
                    } catch (error) {
                        // Silently fail - tooltips are not critical
                    }
                }, 100);
            }
        });

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
                        let html = `<div class="d-flex align-items-center">`;
                        
                        // Add inventory source indicator (all orders in this table are from inventory)
                        html += `<span class="badge bg-info-subtle text-info me-2" title="Created from Inventory">
                            <i class="ri-store-3-line"></i>
                        </span>`;
                        
                        html += `<strong class="text-primary">#${data}</strong></div>`;
                        return html;
                    }
                },
                {
                    data: 'stock',
                    render: function(data, type, row) {
                        let html = `<div class="text-center">`;
                        
                        // Stock number
                        if (data && data !== 'N/A') {
                            html += `<div class="stock-number-badge mb-1">
                                <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1" style="font-size: 0.75rem; letter-spacing: 0.3px;">
                                    ${data}
                                </span>
                            </div>`;
                        } else {
                            html += `<div class="stock-number-badge mb-1">
                                <span class="badge bg-secondary-subtle text-secondary fw-bold px-2 py-1" style="font-size: 0.75rem;">
                                    N/A
                                </span>
                            </div>`;
                        }
                        
                        // Service information instead of VIN
                        if (row.service_name && row.service_name !== 'N/A') {
                            const serviceColor = row.service_color || '#007bff';
                            html += `<div class="service-info mt-1">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="service-color-dot me-1" style="width: 8px; height: 8px; border-radius: 50%; background-color: ${serviceColor};"></div>
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        ${row.service_name}
                                    </small>
                                </div>
                            </div>`;
                        } else {
                            html += `<div class="service-info mt-1">
                                <small class="text-muted" style="font-size: 0.65rem; opacity: 0.6;">
                                    No Service
                                </small>
                            </div>`;
                        }
                        
                        html += `</div>`;
                        return html;
                    }
                },
                {
                    data: 'vehicle',
                    render: function(data, type, row) {
                        let html = `<div><span class="fw-medium">${data || 'N/A'}</span>`;
                        
                        // VIN number - moved from stock column
                        let vinNumber = row.vin || row.vin_number || row.vehicle_vin || row.VIN || '';
                        
                        if (vinNumber && vinNumber !== 'N/A' && vinNumber.toString().trim() !== '') {
                            html += `<div class="vin-number mt-1">
                                <small class="text-muted d-block" style="font-size: 0.7rem; font-family: monospace; letter-spacing: 0.2px; line-height: 1.2;">
                                    <i class="ri-barcode-line me-1" style="font-size: 0.8rem;"></i>${vinNumber}
                                </small>
                            </div>`;
                        }
                        
                        html += `</div>`;
                        return html;
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
                        const statusLabels = {
                            'pending': 'Pending',
                            'in_progress': 'In Progress',
                            'completed': 'Completed',
                            'cancelled': 'Cancelled'
                        };
                        const color = statusColors[data] || 'secondary';
                        const label = statusLabels[data] || data;
                        return `<span class="badge bg-${color}">${label}</span>`;
                    }
                },
                {
                    data: 'source_type',
                    render: function(data, type, row) {
                        // All orders in this table are from inventory
                        const sourceText = 'Inventory';
                        const sourceBadgeClass = 'bg-info';
                        const sourceIcon = 'ri-store-3-line';
                        
                        return `<span class="badge ${sourceBadgeClass}">
                            <i class="${sourceIcon} me-1"></i>${sourceText}
                        </span>`;
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
                processing: '<span style="font-size: 0.6rem; color: rgba(108, 117, 125, 0.4);">•</span>',
                emptyTable: '<div class="text-center py-4"><i class="ri-file-list-3-line display-4 text-muted"></i><h6 class="mt-2">No orders from inventory</h6><p class="text-muted">Orders created from inventory will appear here</p></div>'
            },
            drawCallback: function(settings) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                
                // Apply status color to rows
                $('#inventoryOrdersTable tbody tr').each(function() {
                    var $row = $(this);
                    var rowData = inventoryOrdersTable.row($row).data();
                    if (rowData && rowData.status) {
                        const statusColors = {
                            'pending': '#ffc107',
                            'in_progress': '#17a2b8',
                            'completed': '#28a745',
                            'cancelled': '#dc3545'
                        };
                        var color = statusColors[rowData.status] || '#6c757d';
                        var rgba = hexToRgba(color, 0.15);
                        $row.css({
                            'background-color': rgba,
                            'border-left': '4px solid ' + color,
                            'transition': 'all 0.3s ease'
                        });
                    }
                });
            }
        });

        // Initialize All Orders Table
        window.allOrdersTable = $('#allOrdersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('recon_orders/all_orders_content') ?>',
                type: 'POST',
                data: function(d) {
                    d.ajax = true;
                    return d;
                },
                error: function(xhr, error, thrown) {
                    showToast('Error loading all orders', 'error');
                }
            },
            columns: [
                {
                    data: 'order_number',
                    render: function(data, type, row) {
                        let html = `<div class="d-flex align-items-center">`;
                        
                        // Add source indicator badge
                        const isFromInventory = row.from_inventory == 1;
                        const sourceIcon = isFromInventory ? 'ri-store-3-line' : 'ri-edit-line';
                        const sourceBadgeClass = isFromInventory ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary';
                        const sourceTitle = isFromInventory ? 'Created from Inventory' : 'Created Manually';
                        
                        html += `<span class="badge ${sourceBadgeClass} me-2" title="${sourceTitle}">
                            <i class="${sourceIcon}"></i>
                        </span>`;
                        
                        html += `<strong class="text-primary">${data}</strong></div>`;
                        return html;
                    }
                },
                {
                    data: 'stock',
                    render: function(data, type, row) {
                        let html = `<div class="text-center">`;
                        
                        // Stock number
                        if (data && data !== 'N/A') {
                            html += `<div class="stock-number-badge mb-1">
                                <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1" style="font-size: 0.75rem; letter-spacing: 0.3px;">
                                    ${data}
                                </span>
                            </div>`;
                        } else {
                            html += `<div class="stock-number-badge mb-1">
                                <span class="badge bg-secondary-subtle text-secondary fw-bold px-2 py-1" style="font-size: 0.75rem;">
                                    N/A
                                </span>
                            </div>`;
                        }
                        
                        // Service information instead of VIN
                        if (row.service_name && row.service_name !== 'N/A') {
                            const serviceColor = row.service_color || '#007bff';
                            html += `<div class="service-info mt-1">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="service-color-dot me-1" style="width: 8px; height: 8px; border-radius: 50%; background-color: ${serviceColor};"></div>
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        ${row.service_name}
                                    </small>
                                </div>
                            </div>`;
                        } else {
                            html += `<div class="service-info mt-1">
                                <small class="text-muted" style="font-size: 0.65rem; opacity: 0.6;">
                                    No Service
                                </small>
                            </div>`;
                        }
                        
                        html += `</div>`;
                        return html;
                    }
                },
                {
                    data: 'vehicle',
                    render: function(data, type, row) {
                        let html = `<div><span class="fw-medium">${data || 'N/A'}</span>`;
                        
                        // VIN number - moved from stock column
                        let vinNumber = row.vin || row.vin_number || row.vehicle_vin || row.VIN || '';
                        
                        if (vinNumber && vinNumber !== 'N/A' && vinNumber.toString().trim() !== '') {
                            html += `<div class="vin-number mt-1">
                                <small class="text-muted d-block" style="font-size: 0.7rem; font-family: monospace; letter-spacing: 0.2px; line-height: 1.2;">
                                    <i class="ri-barcode-line me-1" style="font-size: 0.8rem;"></i>${vinNumber}
                                </small>
                            </div>`;
                        }
                        
                        html += `</div>`;
                        return html;
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
                        const statusLabels = {
                            'pending': 'Pending',
                            'in_progress': 'In Progress',
                            'completed': 'Completed',
                            'cancelled': 'Cancelled'
                        };
                        const color = statusColors[data] || 'secondary';
                        const label = statusLabels[data] || data;
                        return `<span class="badge bg-${color}">${label}</span>`;
                    }
                },
                {
                    data: 'source_type',
                    render: function(data, type, row) {
                        const isFromInventory = row.from_inventory == 1;
                        const sourceText = isFromInventory ? 'Inventory' : 'Manual';
                        const sourceBadgeClass = isFromInventory ? 'bg-info' : 'bg-primary';
                        const sourceIcon = isFromInventory ? 'ri-store-3-line' : 'ri-edit-line';
                        
                        return `<span class="badge ${sourceBadgeClass}">
                            <i class="${sourceIcon} me-1"></i>${sourceText}
                        </span>`;
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
                processing: '<span style="font-size: 0.6rem; color: rgba(108, 117, 125, 0.4);">•</span>',
                emptyTable: '<div class="text-center py-4"><i class="ri-list-check-3 display-4 text-muted"></i><h6 class="mt-2">No orders found</h6><p class="text-muted">Orders will appear here</p></div>'
            },
            drawCallback: function(settings) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                
                // Apply status color to rows
                $('#allOrdersTable tbody tr').each(function() {
                    var $row = $(this);
                    var rowData = window.allOrdersTable.row($row).data();
                    if (rowData && rowData.status) {
                        const statusColors = {
                            'pending': '#ffc107',
                            'in_progress': '#17a2b8',
                            'completed': '#28a745',
                            'cancelled': '#dc3545'
                        };
                        var color = statusColors[rowData.status] || '#6c757d';
                        var rgba = hexToRgba(color, 0.15);
                        $row.css({
                            'background-color': rgba,
                            'border-left': '4px solid ' + color,
                            'transition': 'all 0.3s ease'
                        });
                    }
                });
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
            const itemsWithStatus = [];
            
            $('.inventory-checkbox:checked').each(function() {
                const rowData = window.inventoryTable.row($(this).closest('tr')).data();
                if (rowData) {
                    const stockNumber = rowData.stock_number;
                    
                    // Check if item has status
                    let hasStatus = false;
                    let statusText = 'No Status';
                    
                    if (stockNumber && window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
                        hasStatus = true;
                        const orderInfo = window.orderInfoLookup[stockNumber];
                        statusText = orderInfo.status || 'Unknown Status';
                    }
                    
                    if (hasStatus) {
                        itemsWithStatus.push({
                            stock: stockNumber,
                            status: statusText
                        });
                    } else {
                        // Only add items without status
                        selectedItems.push(rowData);
                    }
                }
            });

            if (selectedItems.length === 0 && itemsWithStatus.length === 0) {
                showToast('<?= lang('App.select_items_to_convert') ?>', 'warning');
                return;
            }
            
            if (selectedItems.length === 0 && itemsWithStatus.length > 0) {
                const statusList = itemsWithStatus.map(item => `${item.stock} (${item.status})`).join(', ');
                showToast(`Cannot move selected items: All items already have status. Items: ${statusList}`, 'warning');
                return;
            }
            
            if (itemsWithStatus.length > 0) {
                const statusList = itemsWithStatus.map(item => `${item.stock} (${item.status})`).join(', ');
                showToast(`Skipped ${itemsWithStatus.length} items with existing status: ${statusList}. Moving ${selectedItems.length} items without status.`, 'info');
            }

            // For bulk conversion, we'll show a simplified modal with only items without status
            showBulkConversionModal(selectedItems);
        });

        // Single convert buttons - Open main modal with inventory data
        $(document).on('click', '.convert-single-btn', function(e) {
            // Prevent action if button is disabled
            if ($(this).prop('disabled')) {
                e.preventDefault();
                console.log('❌ Convert button clicked but is disabled');
                return false;
            }
            
            const rowData = JSON.parse($(this).attr('data-row'));
            console.log('🔄 Convert button clicked, row data:', rowData);
            
            // Double-check status before proceeding
            const stockNumber = rowData.stock_number;
            if (stockNumber && window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
                const orderInfo = window.orderInfoLookup[stockNumber];
                showToast(`Cannot move: Item already has status (${orderInfo.status})`, 'warning');
                return false;
            }
            
            openMainModalWithInventoryData(rowData);
        });

        // Refresh buttons
        $('#refreshInventoryBtn').on('click', function() {
            const $btn = $(this);
            const originalHtml = $btn.html();
            
            // Update button to show loading
            $btn.html('<i class="ri-refresh-line me-1 spinner-border spinner-border-sm"></i> Refreshing...');
            $btn.prop('disabled', true);
            
            window.inventoryTable.ajax.reload(function() {
                // Update last refresh time
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
                
                // Restore button
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                // Also refresh order status info
                loadOrderInfoForInventory();
                
                // Update duplicate icons after refresh
                setTimeout(() => {
                    if (window.duplicateStocks && window.duplicateStocks.size > 0) {
                        updateDuplicateIcons();
                    }
                }, 300);
                
            showToast('<?= lang('App.inventory_refreshed') ?>', 'success');
            });
        });

        $('#refreshInventoryOrdersBtn').on('click', function() {
            window.inventoryOrdersTable.ajax.reload();
        });

        $('#refreshAllOrdersBtn').on('click', function() {
            window.allOrdersTable.ajax.reload();
        });

        // Filter Incomplete Button
        $('#filterIncompleteBtn').on('click', function() {
            toggleIncompleteFilter();
        });

        // Filter Complete Button
        $('#filterCompleteBtn').on('click', function() {
            toggleCompleteFilter();
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
        console.log('🔄 openMainModalWithInventoryData called with:', rowData);
        
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

        const inventoryData = {
            stock: rowData.stock_number || '',
            vehicle: rowData.vehicle || '',
            notes: inventoryNotes,
            service_date: new Date().toISOString().split('T')[0]
        };

        console.log('📦 Setting inventory data:', inventoryData);
        
        // Store inventory data in the main modal
        $('#reconOrderModal').data('inventory-data', inventoryData);
        
        console.log('🔄 Opening modal...');
        
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
        
        // Filter out completed items for stats calculation
        const filteredData = data.filter(row => {
            const stockNumber = row[2]; // stock_number is at index 2
            if (!stockNumber || !window.orderInfoLookup) return true; // Include if no status info available
            
            const orderInfo = window.orderInfoLookup[stockNumber.toString().trim()];
            return !orderInfo || orderInfo.status !== 'completed'; // Exclude completed items
        });
        
        const total = filteredData.length;
        const totalAll = data.length; // Keep track of all items for reference
        
        // Calculate days and categorize items (only non-completed)
        const daysData = [];
        let recentItems = 0; // 0-1 days
        let moderateItems = 0; // 2-5 days  
        let agedItems = 0; // 6+ days
        
        filteredData.forEach(row => {
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
        
        console.log(`📊 Stats updated - Total: ${total} (filtered), All: ${totalAll}, Avg Days: ${avgDays}`);
        
        // Update basic stats (showing filtered counts)
        $('#totalInventoryItems').text(total);
        $('#avgDaysInDetail').text(avgDays);
        
        // Update mini widget average days
        $('#avgDaysNumber').text(avgDays);
        
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
        
        console.log('🧹 Clearing all filters...');
        
        // Remove active class from all widgets
        $('.filter-widget').removeClass('active');
        
        // Clear current filters
        window.currentDayFilter = '';
        window.incompleteFilterActive = false;
        window.completeFilterActive = false;
        
        // Clear all DataTable filters completely
        clearAllDataTableFilters();
        
        // Update button appearances
        updateIncompleteFilterButton(false);
        updateCompleteFilterButton(false);
        
        // Save state
        window.saveVehiclesState();
        
        showToast('<?= lang('App.filters_cleared') ?>', 'info');
    }
    
    // Function to clear all DataTable filters completely
    function clearAllDataTableFilters() {
        console.log('🧹 Clearing all DataTable filters...');
        
        const beforeCount = $.fn.dataTable.ext.search.length;
        
        // Clear ALL custom search functions
        $.fn.dataTable.ext.search = [];
        
        const afterCount = $.fn.dataTable.ext.search.length;
        console.log(`🔄 Filters cleared: before=${beforeCount}, after=${afterCount}`);
        
        // Redraw table to show all items
        if (window.inventoryTable) {
            console.log('🔄 Redrawing table after clearing all filters');
            window.inventoryTable.draw();
        }
        
        console.log('✅ All DataTable filters cleared successfully');
    }
    
    function restorePreviousState() {
        const $ = window.jQuery;
        const savedState = window.loadVehiclesState();
        
        if (savedState) {
            // Restore day range filter
            if (savedState.activeFilter !== undefined) {
                // Find the widget with the saved filter
                const $widget = $(`.filter-widget[data-filter="${savedState.activeFilter}"]`);
                if ($widget.length > 0) {
                    // Apply the saved filter
                    applyWidgetFilter(savedState.activeFilter, $widget);
                }
            }
            
            // Restore incomplete filter
            if (savedState.incompleteFilter) {
                window.incompleteFilterActive = true;
                updateIncompleteFilterButton(true);
                applyIncompleteFilter();
            }
            
            // Restore complete filter
            if (savedState.completeFilter) {
                window.completeFilterActive = true;
                updateCompleteFilterButton(true);
                applyCompleteFilter();
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

    // Function to toggle incomplete filter
    function toggleIncompleteFilter() {
        console.log('🔄 Toggling incomplete filter. Current state:', window.incompleteFilterActive);
        
        // Clear ALL filters first to avoid conflicts
        clearAllDataTableFilters();
        
        // If complete filter is active, turn it off first
        if (window.completeFilterActive) {
            console.log('✋ Turning off complete filter first');
            window.completeFilterActive = false;
            updateCompleteFilterButton(false);
        }
        
        window.incompleteFilterActive = !window.incompleteFilterActive;
        console.log('🔄 New incomplete filter state:', window.incompleteFilterActive);
        
        updateIncompleteFilterButton(window.incompleteFilterActive);
        
        if (window.incompleteFilterActive) {
            console.log('✅ Applying incomplete filter');
            applyIncompleteFilter();
            showToast('Showing incomplete items only', 'info');
        } else {
            console.log('❌ Showing all items');
            showToast('Showing all items', 'info');
        }
        
        // Save state
        window.saveVehiclesState();
    }

    // Function to toggle complete filter
    function toggleCompleteFilter() {
        console.log('🔄 Toggling complete filter. Current state:', window.completeFilterActive);
        
        // Clear ALL filters first to avoid conflicts
        clearAllDataTableFilters();
        
        // If incomplete filter is active, turn it off first
        if (window.incompleteFilterActive) {
            console.log('✋ Turning off incomplete filter first');
            window.incompleteFilterActive = false;
            updateIncompleteFilterButton(false);
        }
        
        window.completeFilterActive = !window.completeFilterActive;
        console.log('🔄 New complete filter state:', window.completeFilterActive);
        
        updateCompleteFilterButton(window.completeFilterActive);
        
        if (window.completeFilterActive) {
            console.log('✅ Applying complete filter');
            applyCompleteFilter();
            showToast('Showing complete items only', 'info');
        } else {
            console.log('❌ Showing all items');
            showToast('Showing all items', 'info');
        }
        
        // Save state
        window.saveVehiclesState();
    }

    // Function to update incomplete filter button appearance
    function updateIncompleteFilterButton(isActive) {
        const $ = window.jQuery;
        const $btn = $('#filterIncompleteBtn');
        
        if (isActive) {
            $btn.removeClass('btn-outline-info').addClass('btn-info');
            $btn.html('<i class="ri-filter-fill me-1"></i>Show Incomplete Only');
        } else {
            $btn.removeClass('btn-info').addClass('btn-outline-info');
            $btn.html('<i class="ri-filter-line me-1"></i>Show Incomplete Only');
        }
    }

    // Function to update complete filter button appearance
    function updateCompleteFilterButton(isActive) {
        const $ = window.jQuery;
        const $btn = $('#filterCompleteBtn');
        
        if (isActive) {
            $btn.removeClass('btn-outline-info').addClass('btn-success');
            $btn.html('<i class="ri-filter-fill me-1"></i>Show Complete Only');
        } else {
            $btn.removeClass('btn-success').addClass('btn-outline-info');
            $btn.html('<i class="ri-filter-line me-1"></i>Show Complete Only');
        }
    }

    // Function to apply incomplete filter
    function applyIncompleteFilter() {
        if (!window.inventoryTable) {
            console.error('❌ Cannot apply incomplete filter: inventoryTable not available');
            return;
        }
        
        console.log('🔍 Applying incomplete filter...');
        
        // Add the incomplete filter function (filters already cleared)
        const incompleteFilterFn = function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'inventoryTable') return true;
            
            // Get the actual row data from DataTables
            const table = window.inventoryTable;
            const rowData = table.row(dataIndex).data();
            
            if (!rowData || !rowData.stock_number) {
                // Show items without stock number (considered incomplete)
                return true;
            }
            
            const stockNumber = rowData.stock_number.toString().trim();
            
            // Check if item has any status
            if (window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
                const orderInfo = window.orderInfoLookup[stockNumber];
                const status = orderInfo.status;
                
                // Show only if status is NOT 'completed' (incomplete items)
                // This includes: pending, in_progress, cancelled, or any other non-completed status
                const isIncomplete = status !== 'completed';
                
                // Debug for first few items
                if (dataIndex < 3) {
                    console.log(`📋 Stock ${stockNumber}: status="${status}", showing=${isIncomplete}`);
                }
                
                return isIncomplete;
            }
            
            // Show items without any status (they are definitely incomplete)
            if (dataIndex < 3) {
                console.log(`📋 Stock ${stockNumber}: no status, showing=true (incomplete)`);
            }
            return true;
        };
        
        incompleteFilterFn.name = 'incompleteFilter';
        $.fn.dataTable.ext.search.push(incompleteFilterFn);
        
        console.log('🔄 Redrawing table with incomplete filter');
        
        // Redraw table
        window.inventoryTable.draw();
        
        // Verify filter was added
        const filterCount = $.fn.dataTable.ext.search.length;
        console.log(`✅ Incomplete filter applied successfully. Total filters: ${filterCount}`);
    }

    // Function to remove incomplete filter
    function removeIncompleteFilter() {
        console.log('🗑️ Removing incomplete filter...');
        
        const beforeCount = $.fn.dataTable.ext.search.length;
        
        // Remove incomplete filter from DataTables search extensions
        $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) {
            return fn.name !== 'incompleteFilter';
        });
        
        const afterCount = $.fn.dataTable.ext.search.length;
        console.log(`🔄 Filters before: ${beforeCount}, after: ${afterCount}`);
        
        // Redraw table
        if (window.inventoryTable) {
            console.log('🔄 Redrawing table after removing incomplete filter');
            window.inventoryTable.draw();
        }
        
        console.log('✅ Incomplete filter removed successfully');
    }

     // Function to apply complete filter
     function applyCompleteFilter() {
        if (!window.inventoryTable) {
            console.error('❌ Cannot apply complete filter: inventoryTable not available');
            return;
        }
        
        console.log('🔍 Applying complete filter...');
        
        // Add the complete filter function (filters already cleared)
        const completeFilterFn = function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'inventoryTable') return true;
            
            // Get the actual row data from DataTables
            const table = window.inventoryTable;
            const rowData = table.row(dataIndex).data();
            
            if (!rowData || !rowData.stock_number) return false;
            
            const stockNumber = rowData.stock_number.toString().trim();
            
            // Check if item has completed status
            if (window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
                const orderInfo = window.orderInfoLookup[stockNumber];
                const status = orderInfo.status;
                
                // Show only if status is 'completed'
                return status === 'completed';
            }
            
            // Hide items without status (they are not complete)
            return false;
        };
        
        completeFilterFn.name = 'completeFilter';
        $.fn.dataTable.ext.search.push(completeFilterFn);
        
        console.log('🔄 Redrawing table with complete filter');
        
        // Redraw table
        window.inventoryTable.draw();
        
        // Verify filter was added
        const filterCount = $.fn.dataTable.ext.search.length;
        console.log(`✅ Complete filter applied successfully. Total filters: ${filterCount}`);
    }

    // Function to remove complete filter
    function removeCompleteFilter() {
        // Remove complete filter from DataTables search extensions
        $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) {
            return fn.name !== 'completeFilter';
        });
        
        // Redraw table
        if (window.inventoryTable) {
            window.inventoryTable.draw();
        }
    }



    

    // Function to initialize localStorage for vehicles tab
    function initializeVehiclesLocalStorage() {
        const STORAGE_KEY = 'reconOrders_vehiclesTab_state';
        
        // Save current filter state
        window.saveVehiclesState = function() {
            const state = {
                activeFilter: window.currentDayFilter || '',
                incompleteFilter: window.incompleteFilterActive || false,
                completeFilter: window.completeFilterActive || false,
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
    
    // Function to load order information for inventory matching
    function loadOrderInfoForInventory() {
        const $ = window.jQuery;
        
        console.log('🔍 Loading order info for inventory matching...');
        
        $.post('<?= base_url('recon_orders/get_order_info_by_stock') ?>', { ajax: true })
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
                }
            })
            .fail(function(xhr, status, error) {
                console.error('❌ Failed to load order info:', status, error);
                console.log('Response:', xhr.responseText?.substring(0, 200));
            });
    }
    
    // Function to update status/service columns in inventory table
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
                const statusLabels = {
                    'pending': 'Pending',
                    'in_progress': 'In Progress',
                    'completed': 'Completed',
                    'cancelled': 'Cancelled'
                };
                const statusColor = statusColors[orderInfo.status] || 'secondary';
                const statusLabel = statusLabels[orderInfo.status] || orderInfo.status;
                html += `<span class="badge bg-${statusColor} px-2 py-1 fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">${statusLabel}</span>`;
                
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
        
        // Update Move to Recon buttons based on status
        updateMoveToReconButtons();
        
        // Reapply incomplete filter if active
        if (window.incompleteFilterActive) {
            applyIncompleteFilter();
        }
    }
    
    // Function to update Move to Recon buttons based on status
    function updateMoveToReconButtons() {
        const $ = window.jQuery;
        
        console.log('🔄 Updating Move to Recon buttons based on status...');
        const buttons = $('.convert-single-btn');
        console.log('Found', buttons.length, 'Move to Recon buttons to update');
        
        buttons.each(function() {
            const $button = $(this);
            
            try {
                // Get row data from the button's data attribute
                const rowDataStr = $button.attr('data-row');
                if (!rowDataStr) return;
                
                const rowData = JSON.parse(rowDataStr);
                const stockNumber = rowData.stock_number;
                
                if (!stockNumber) return;
                
                // Check if the item has a status
                let hasStatus = false;
                let statusText = 'No Status';
                
                if (window.orderInfoLookup && window.orderInfoLookup[stockNumber]) {
                    hasStatus = true;
                    const orderInfo = window.orderInfoLookup[stockNumber];
                    statusText = orderInfo.status || 'Unknown Status';
                }
                
                // Button is enabled only when there's NO status (no status yet)
                const isEnabled = !hasStatus;
                
                // Update button appearance and state
                if (isEnabled) {
                    $button.removeClass('btn-secondary').addClass('btn-success');
                    $button.prop('disabled', false);
                    $button.attr('title', 'Move to Recon Orders');
                    console.log(`✅ Enabled button for stock: ${stockNumber} (no status)`);
                } else {
                    $button.removeClass('btn-success').addClass('btn-secondary');
                    $button.prop('disabled', true);
                    $button.attr('title', `Cannot move: Item already has status (${statusText})`);
                    console.log(`❌ Disabled button for stock: ${stockNumber} (status: ${statusText})`);
                }
                
            } catch (error) {
                console.error('❌ Error updating button:', error);
            }
        });
        
        console.log('✅ Move to Recon buttons update completed');
    }
    
    // Make loadOrderInfoForInventory available globally for refresh
    window.loadOrderInfoForInventory = loadOrderInfoForInventory;
    
    // Debug function to test incomplete filter (temporary)
    window.testIncompleteFilter = function() {
        console.log('🧪 Testing incomplete filter...');
        console.log('🔍 Current filter state:', window.incompleteFilterActive);
        console.log('🔍 Order info lookup available:', !!window.orderInfoLookup);
        console.log('🔍 Inventory table available:', !!window.inventoryTable);
        
        if (window.orderInfoLookup) {
            const stockNumbers = Object.keys(window.orderInfoLookup).slice(0, 5);
            console.log('🔍 Sample order info:', stockNumbers.map(stock => ({
                stock,
                status: window.orderInfoLookup[stock].status
            })));
        }
        
        // Test toggle
        console.log('🔄 Testing toggle function...');
        toggleIncompleteFilter();
    };

    // Debug function to test duplicate detection (temporary)
    window.testDuplicateDetection = function() {
        console.log('🧪 Testing duplicate detection...');
        
        // Create test data with duplicates
        const testData = [
            ['8/4/2025', '12', '2', 'BN28875', '2018 BMW 530'],
            ['8/6/2025', '10', '2', 'B35232B', '2023 BMW 330XE'],
            ['8/6/2025', '10', '2', 'BN28875', '2018 BMW 530'], // Duplicate
            ['8/8/2025', '8', '2', 'B35396A', '2023 BMW X5'],
            ['8/9/2025', '7', '1', 'B35232B', '2023 BMW 330XE'] // Duplicate
        ];
        
        const duplicates = detectDuplicateStocks(testData);
        console.log('🧪 Test results - Duplicates found:', Array.from(duplicates));
        
        if (window.inventoryTable) {
            updateDuplicateIcons();
        }
    };
    
    // Real-time sync functionality
    let pollingTimer = null;
    const pollingInterval = 30000; // 30 seconds
    
    function startRealTimeSync() {
        console.log('🔄 Starting real-time sync for inventory table');
        
        // Clear any existing polling
        if (pollingTimer) {
            clearInterval(pollingTimer);
        }
        
        // Set up new polling
        pollingTimer = setInterval(() => {
            console.log('🔄 Real-time sync: Refreshing inventory data');
            
            // Update sync indicator
            const syncIndicator = document.getElementById('syncIndicator');
            if (syncIndicator) {
                syncIndicator.innerHTML = '<i class="ri-refresh-line"></i> Syncing...';
                syncIndicator.className = 'badge bg-warning-subtle text-warning ms-2';
            }
            
            if (window.inventoryTable) {
                window.inventoryTable.ajax.reload(function() {
                    // Update sync indicator on completion
                    if (syncIndicator) {
                        syncIndicator.innerHTML = '<i class="ri-wifi-line"></i> Live Sync';
                        syncIndicator.className = 'badge bg-success-subtle text-success ms-2';
                    }
                    
                    // Update last refresh time
                    const lastRefreshInfo = document.getElementById('lastRefreshInfo');
                    if (lastRefreshInfo) {
                        const now = new Date();
                        const timeString = now.toLocaleTimeString('en-US', {
                            hour12: false,
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                        lastRefreshInfo.textContent = `Last refresh: ${timeString}`;
                    }
                    
                    loadOrderInfoForInventory(); // Also refresh order status info
                    
                    // Update duplicate icons after auto-refresh
                    setTimeout(() => {
                        if (window.duplicateStocks && window.duplicateStocks.size > 0) {
                            updateDuplicateIcons();
                        }
                    }, 300);
                }, false); // false = don't reset paging
            }
        }, pollingInterval);
        
        console.log('✅ Real-time sync started');
    }
    
    function stopRealTimeSync() {
        if (pollingTimer) {
            clearInterval(pollingTimer);
            pollingTimer = null;
            console.log('⏹️ Real-time sync stopped');
        }
    }
    
    // Start real-time sync
    startRealTimeSync();
    
    // Stop polling when page is about to unload
    window.addEventListener('beforeunload', stopRealTimeSync);
    
    // Make functions available globally
    window.startRealTimeSync = startRealTimeSync;
    window.stopRealTimeSync = stopRealTimeSync;
});

// Test function for debugging toast issues
window.testToast = function() {
    console.log('🧪 Testing toast function...');
    console.log('🔍 window.showToast available:', typeof window.showToast);
    console.log('🔍 Swal available:', typeof Swal);
    
    if (typeof window.showToast === 'function') {
        console.log('✅ Calling showToast with test message');
        showToast('This is a test message! 🚀', 'success');
        
        setTimeout(() => {
            showToast('This is a warning test! ⚠️', 'warning');
        }, 1000);
        
        setTimeout(() => {
            showToast('This is an error test! ❌', 'error');
        }, 2000);
        
        setTimeout(() => {
            showToast('This is an info test! ℹ️', 'info');
        }, 3000);
    } else {
        console.error('❌ showToast function not available');
        alert('showToast function not available');
    }
};

  
function showToast(message, type = 'success') {
    // Try to use global showToast first (with correct parameter order)
    if (typeof window.showToast === 'function') {
        window.showToast(message, type);
        return;
    }
    
    // Fallback to Toastify if available
    if (typeof Toastify !== 'undefined') {
        // Define colors for different toast types
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
            className: `toast-${type}`,
            close: true,
            stopOnFocus: true
        }).showToast();
        return;
    }
    
    // Final fallback to alert
    alert(`${type.toUpperCase()}: ${message}`);
}
</script>

        </div>
    </div>

</div>