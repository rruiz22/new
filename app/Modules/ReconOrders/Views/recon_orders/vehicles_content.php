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
                                    <?= lang('App.convert_selected') ?>
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-container icon-blue">
                                <i class="ri-car-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="totalVehicles">0</h5>
                            <p class="text-muted mb-0"><?= lang('App.total_vehicles') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-container icon-green">
                                <i class="ri-calendar-check-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="recentVehicles">0</h5>
                            <p class="text-muted mb-0"><?= lang('App.new_this_month') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-container icon-orange">
                                <i class="ri-trophy-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="mostServicedCount">0</h5>
                            <p class="text-muted mb-0"><?= lang('App.most_serviced') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-container icon-purple">
                                <i class="ri-building-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="popularMakesCount">0</h5>
                            <p class="text-muted mb-0"><?= lang('App.vehicle_makes') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicles Registry Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">
                                <i class="ri-car-line me-2"></i>
                                <?= lang('App.vehicle_registry') ?>
                            </h5>
                            <p class="text-muted small mb-0"><?= lang('App.complete_history_vehicles') ?></p>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" id="refreshVehiclesBtn" onclick="refreshVehiclesData()">
                                    <i class="ri-refresh-line me-1"></i>
                                    <?= lang('App.refresh') ?>
                                </button>
                            <div class="search-bar">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ri-search-line"></i>
                                    </span>
                                    <input type="text" class="form-control" id="vehicleSearch" placeholder="<?= lang('App.search') ?> VIN, <?= lang('App.vehicle_make') ?>, <?= lang('App.vehicle_model') ?>...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="vehiclesTable" class="table table-hover align-middle" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th><?= lang('App.vehicle') ?></th>
                                    <th><?= lang('App.vin_number') ?></th>
                                    <th><?= lang('App.vehicle_details') ?></th>
                                    <th><?= lang('App.vehicle_year') ?></th>
                                    <th><?= lang('App.total_services') ?></th>
                                    <th><?= lang('App.first_service') ?></th>
                                    <th><?= lang('App.last_service') ?></th>
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

<!-- Conversion Modal -->
<div class="modal fade" id="conversionModal" tabindex="-1" aria-labelledby="conversionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="conversionModalLabel">
                    <i class="ri-exchange-line me-2"></i>
                    <?= lang('App.convert_to_recon_order') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="conversionForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('App.stock_number') ?></label>
                            <input type="text" class="form-control" id="modalStock" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('App.vehicle') ?></label>
                            <input type="text" class="form-control" id="modalVehicle" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('App.date_in_detail') ?></label>
                            <input type="text" class="form-control" id="modalDateDetail" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('App.days_in_detail') ?></label>
                            <input type="text" class="form-control" id="modalDaysDetail" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('App.select_client') ?> <span class="text-danger">*</span></label>
                            <select class="form-select" id="modalClient" required>
                                <option value=""><?= lang('App.select_client') ?></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?= lang('App.service') ?></label>
                            <select class="form-select" id="modalService">
                                <option value=""><?= lang('App.select_service') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.service_date') ?></label>
                        <input type="date" class="form-control" id="modalServiceDate">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.notes') ?></label>
                        <textarea class="form-control" id="modalNotes" rows="3" placeholder="<?= lang('App.inventory_source') ?>"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?= lang('App.cancel') ?>
                </button>
                <button type="button" class="btn btn-success" id="confirmConversionBtn">
                    <i class="ri-check-line me-1"></i>
                    <?= lang('App.convert_to_recon') ?>
                </button>
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

.vehicle-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

.vehicle-info {
    font-weight: 600;
    color: #2563eb;
}

.vin-number {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    color: #64748b;
}

.service-count {
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.8rem;
}

.vehicle-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
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
            console.log('⏳ Waiting for jQuery to load...');
            setTimeout(initializeTables, 100);
            return;
        }
        
        if (typeof window.$.fn.DataTable === 'undefined') {
            console.log('⏳ Waiting for DataTables to load...');
            setTimeout(initializeTables, 100);
            return;
        }
        
        console.log('🚗 Vehicles Content - Initializing all tables');
        
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
                    console.log('Inventory data received:', json);
                    
                    // Handle the response structure from get_inventory.php
                    let data = json;
                    if (json.success && json.data) {
                        data = json.data;
                    }
                    
                    // Ensure data is an array
                    if (!Array.isArray(data)) {
                        console.error('Expected array, got:', typeof data, data);
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
                                console.warn('Date parsing error:', e, 'for value:', row[0]);
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
                        
                        // Debug log for first few rows
                        if (index < 3) {
                            console.log(`Row ${index} mapped:`, mappedRow);
                        }
                        
                        return mappedRow;
                    });
                },
                error: function(xhr, error, thrown) {
                    console.error('Inventory Ajax Error:', error, thrown);
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
                            <?= lang('App.convert_to_recon') ?>
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
                    console.error('Inventory Orders Ajax Error:', error, thrown);
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

        // Initialize Vehicles Table
        window.vehiclesTable = $('#vehiclesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url: '<?= base_url('recon_orders/vehicles_content') ?>',
            type: 'POST',
            data: function(d) {
                d.ajax = true;
                console.log('Sending AJAX request with data:', d);
                return d;
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Ajax Error:', error, thrown);
                console.error('Response:', xhr.responseText);
                showToast('Error loading vehicles data', 'error');
            },
            success: function(data) {
                console.log('DataTable Ajax Success:', data);
            }
        },
        columns: [
            {
                data: 'vehicle_info',
                render: function(data, type, row) {
                    return `<div class="vehicle-info">${data}</div>`;
                }
            },
            {
                data: 'vin_number',
                render: function(data, type, row) {
                    return `<span class="vin-number">${data}</span>`;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `<span class="text-muted">Not available</span>`;
                }
            },
            {
                data: 'year',
                render: function(data, type, row) {
                    return '<span class="text-muted">Not available</span>';
                }
            },
            {
                data: 'total_orders',
                render: function(data, type, row) {
                    return `<div class="text-center">
                        <span class="service-count">${data || 0}</span>
                    </div>`;
                }
            },
            {
                data: 'first_service',
                render: function(data, type, row) {
                    if (data && data !== 'N/A') {
                        return `<div>
                            <div class="fw-medium">${data}</div>
                            <small class="text-muted">${row.first_order_number || ''}</small>
                        </div>`;
                    }
                    return '<span class="text-muted">N/A</span>';
                }
            },
            {
                data: 'last_service',
                render: function(data, type, row) {
                    if (data && data !== 'N/A') {
                        return `<div>
                            <div class="fw-medium">${data}</div>
                            <small class="text-muted">${row.last_order_number || ''}</small>
                        </div>`;
                    }
                    return '<span class="text-muted">N/A</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `<div class="vehicle-actions">
                        <a href="<?= base_url('vehicles/') ?>${row.vin_number.slice(-6)}" 
                           class="btn btn-outline-primary btn-sm" 
                           title="<?= lang('App.view_vehicle_history') ?>">
                            <i class="ri-eye-line"></i> <?= lang('App.view_vehicle_history') ?>
                        </a>
                    </div>`;
                }
            }
        ],
        order: [[5, 'desc']], // Sort by last service date
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>',
            emptyTable: '<div class="text-center py-4"><i class="ri-car-line display-4 text-muted"></i><h6 class="mt-2">No vehicles found</h6><p class="text-muted">Vehicles will appear here when orders are created</p></div>'
        },
        drawCallback: function(settings) {
            // Re-initialize tooltips with Bootstrap 5
            if (typeof bootstrap !== 'undefined') {
                const tooltips = document.querySelectorAll('[title]');
                tooltips.forEach(el => new bootstrap.Tooltip(el));
            }
        }
    });

        // Event Handlers
        setupEventHandlers();
        loadClients();
        loadServices();
        refreshStats();
        
        // Setup widget-based filtering
        setupWidgetFiltering();
        
        // Restore previous state
        restorePreviousState();
        
        console.log('✅ All tables initialized successfully');
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

        // Single convert buttons
        $(document).on('click', '.convert-single-btn', function() {
            const rowData = JSON.parse($(this).attr('data-row'));
            showConversionModal(rowData);
        });

        // Refresh buttons
        $('#refreshInventoryBtn').on('click', function() {
            window.inventoryTable.ajax.reload();
            showToast('<?= lang('App.inventory_refreshed') ?>', 'success');
        });

        $('#refreshInventoryOrdersBtn').on('click', function() {
            window.inventoryOrdersTable.ajax.reload();
        });

        // Vehicle search
    $('#vehicleSearch').on('keyup', function() {
            window.vehiclesTable.search(this.value).draw();
        });

        // Conversion form submit
        $('#confirmConversionBtn').on('click', function() {
            submitConversion();
        });
    }

    function updateConvertButtonState() {
        const checkedCount = $('.inventory-checkbox:checked').length;
        $('#convertSelectedBtn').prop('disabled', checkedCount === 0);
        
        if (checkedCount > 0) {
            $('#convertSelectedBtn').html(`<i class="ri-arrow-right-line me-1"></i> <?= lang('App.convert_selected') ?> (${checkedCount})`);
        } else {
            $('#convertSelectedBtn').html(`<i class="ri-arrow-right-line me-1"></i> <?= lang('App.convert_selected') ?>`);
        }
    }

    function showConversionModal(rowData) {
        // Populate modal with inventory data
        $('#modalStock').val(rowData.stock_number);
        $('#modalVehicle').val(rowData.vehicle);
        $('#modalDateDetail').val(rowData.date_detail);
        
        // Handle days display with proper translation
        const daysText = rowData.days_detail ? 
            (rowData.days_detail === 1 ? `${rowData.days_detail} <?= lang('App.day') ?>` : `${rowData.days_detail} <?= lang('App.days') ?>`) : 
            'N/A';
        $('#modalDaysDetail').val(daysText);
        
        // Create comprehensive notes with all available data
        $('#modalNotes').val(
            '<?= lang('App.auto_filled_from_inventory') ?>' + '\n\n' + 
            '<?= lang('App.date_in_detail') ?>: ' + (rowData.date_detail || 'N/A') + '\n' +
            '<?= lang('App.days_in_detail') ?>: ' + daysText + '\n' +
            '<?= lang('App.keys') ?>: ' + (rowData.keys || 'N/A') + '\n' +
            '<?= lang('App.write_up_date') ?>: ' + (rowData.write_up_date || 'N/A') + '\n' +
            '<?= lang('App.stock_number') ?>: ' + (rowData.stock_number || 'N/A') + '\n' +
            '<?= lang('App.vehicle') ?>: ' + (rowData.vehicle || 'N/A')
        );
        
        // Set today's date as default
        const today = new Date().toISOString().split('T')[0];
        $('#modalServiceDate').val(today);

        // Store row data for submission
        $('#conversionForm').data('rowData', rowData);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('conversionModal'));
        modal.show();
    }

    function showBulkConversionModal(selectedItems) {
        // For now, show a simple confirmation
        if (window.showConfirmDialog) {
            window.showConfirmDialog(
                '<?= lang('App.confirm_conversion') ?>',
                `<?= lang('App.convert_multiple_stocks') ?>? (${selectedItems.length} <?= lang('App.selected_items') ?>)`,
                '<?= lang('App.yes_convert') ?>',
                '<?= lang('App.cancel') ?>'
            ).then((result) => {
                if (result.isConfirmed) {
                    processBulkConversion(selectedItems);
                }
            });
        } else {
            if (confirm(`<?= lang('App.convert_multiple_stocks') ?>? (${selectedItems.length} <?= lang('App.selected_items') ?>)`)) {
                processBulkConversion(selectedItems);
            }
        }
    }

    function processBulkConversion(selectedItems) {
        // Show loading state
        $('#convertSelectedBtn').prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> <?= lang('App.converting_to_recon') ?>');

        // Process each item
        const promises = selectedItems.map(item => convertSingleItem(item));
        
        Promise.all(promises).then(results => {
            const successful = results.filter(r => r.success).length;
            const failed = results.length - successful;
            
            if (successful > 0) {
                showToast(`${successful} <?= lang('App.converted_successfully') ?>`, 'success');
                window.inventoryTable.ajax.reload();
                window.inventoryOrdersTable.ajax.reload();
                
                // Clear selections
                $('.inventory-checkbox').prop('checked', false);
                $('#selectAllInventory').prop('checked', false);
            }
            
            if (failed > 0) {
                showToast(`${failed} <?= lang('App.conversion_failed') ?>`, 'error');
            }
        }).finally(() => {
            updateConvertButtonState();
        });
    }

    function submitConversion() {
        const formData = {
            client_id: $('#modalClient').val(),
            service_id: $('#modalService').val(),
            service_date: $('#modalServiceDate').val(),
            notes: $('#modalNotes').val(),
            inventory_data: $('#conversionForm').data('rowData')
        };

        if (!formData.client_id) {
            showToast('<?= lang('App.client_required') ?>', 'warning');
            return;
        }

        $('#confirmConversionBtn').prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> <?= lang('App.converting_to_recon') ?>');

        $.ajax({
            url: '<?= base_url('recon_orders/convert_from_inventory') ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showToast('<?= lang('App.converted_successfully') ?>', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('conversionModal')).hide();
                    window.inventoryTable.ajax.reload();
                    window.inventoryOrdersTable.ajax.reload();
                } else {
                    showToast(response.message || '<?= lang('App.conversion_failed') ?>', 'error');
                }
            },
            error: function() {
                showToast('<?= lang('App.conversion_failed') ?>', 'error');
            },
            complete: function() {
                $('#confirmConversionBtn').prop('disabled', false).html('<i class="ri-check-line me-1"></i> <?= lang('App.convert_to_recon') ?>');
            }
        });
    }

    function convertSingleItem(item) {
        return new Promise((resolve) => {
            $.ajax({
                url: '<?= base_url('recon_orders/convert_from_inventory') ?>',
                type: 'POST',
                data: {
                    inventory_data: item,
                    auto_convert: true
                },
                success: function(response) {
                    resolve({ success: response.success, item: item });
                },
                error: function() {
                    resolve({ success: false, item: item });
                }
            });
        });
    }

    function loadClients() {
        $.get('<?= base_url('recon_orders/getClients') ?>', function(data) {
            const select = $('#modalClient');
            select.empty().append('<option value=""><?= lang('App.select_client') ?></option>');
            
            if (data.clients && Array.isArray(data.clients)) {
                data.clients.forEach(client => {
                    select.append(`<option value="${client.id}">${client.name || client.client_name}</option>`);
                });
            }
        }).fail(function() {
            console.warn('Failed to load clients');
        });
    }

    function loadServices() {
        $.get('<?= base_url('recon_orders/getActiveServices') ?>', function(data) {
            const select = $('#modalService');
            select.empty().append('<option value=""><?= lang('App.select_service') ?></option>');
            
            if (data.success && data.data && Array.isArray(data.data)) {
                data.data.forEach(service => {
                    select.append(`<option value="${service.id}">${service.service_name || service.name}</option>`);
                });
            } else if (Array.isArray(data)) {
                // Fallback for direct array response
                data.forEach(service => {
                    select.append(`<option value="${service.id}">${service.service_name || service.name}</option>`);
                });
            }
        }).fail(function() {
            console.warn('Failed to load services');
        });
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
            console.warn('Failed to refresh vehicle stats');
        });
    }

        // Setup widget-based filtering function
    function setupWidgetFiltering() {
        const $ = window.jQuery;
        
        // Widget click handlers
        $('.filter-widget').on('click', function() {
            const filter = $(this).data('filter');
            console.log('🎯 Widget clicked with filter:', filter);
            console.log('🎯 Current inventory table:', window.inventoryTable);
            
            // Debug: Show current table data
            if (window.inventoryTable) {
                const tableData = window.inventoryTable.data().toArray();
                console.log('🎯 Current table data (first 3 rows):', tableData.slice(0, 3));
            }
            
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
        
        console.log('🚀 applyWidgetFilter called with filter:', filter);
        console.log('🚀 Widget element:', $widget);
        
        // Remove active class from all widgets
        $('.filter-widget').removeClass('active');
        
        // Add active class to clicked widget
        $widget.addClass('active');
        
        // Store current filter
        window.currentDayFilter = filter;
        console.log('🚀 Stored currentDayFilter:', window.currentDayFilter);
        
        // Apply the filter
        console.log('🚀 About to call applyDayRangeFilter with:', filter);
        applyDayRangeFilter(filter);
        
        // Save state to localStorage
        window.saveVehiclesState();
        
        // Show feedback
        const filterName = filter === '' ? '<?= lang('App.all_day_ranges') ?>' : 
                          filter === '0-1' ? '<?= lang('App.recent_0_1_days') ?>' :
                          filter === '2-5' ? '<?= lang('App.moderate_2_5_days') ?>' :
                          filter === '6+' ? '<?= lang('App.aged_6_plus_days') ?>' : filter;
        
        console.log('🚀 Showing toast with filter name:', filterName);
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
            console.warn('Inventory table not initialized yet');
            return;
        }
        
        console.log('Applying day range filter:', range);
        
        // IMPORTANT: Clear ALL custom searches first
        console.log('Custom searches before clear:', $.fn.dataTable.ext.search.length);
        $.fn.dataTable.ext.search = [];
        console.log('Custom searches after clear:', $.fn.dataTable.ext.search.length);
        
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
                console.log(`🔍 Filtering row ${dataIndex}: days=${daysNum}, range="${range}"`);
                
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
                
                console.log(`🔍 Result for row ${dataIndex}: ${result}`);
                return result;
            };
            dayRangeFilterFn.name = 'dayRangeFilter';
            $.fn.dataTable.ext.search.push(dayRangeFilterFn);
            
            console.log('✅ Added new filter function for range:', range);
            console.log('✅ Total custom searches now:', $.fn.dataTable.ext.search.length);
        }
        
        // Redraw table
        console.log('🎨 About to redraw table...');
        window.inventoryTable.draw();
        
        console.log('✅ Filter applied, table redrawn');
        
        // Debug: Show how many rows are visible after filter
        setTimeout(() => {
            const info = window.inventoryTable.page.info();
            console.log(`📊 Table info after filter: ${info.recordsDisplay} of ${info.recordsTotal} entries shown`);
        }, 100);
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
                console.warn('Error loading vehicles state:', e);
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

// Global function to refresh vehicles data
function refreshVehiclesData() {
    console.log('🔄 Refreshing vehicles data...');
    
    // Check if vehiclesTable is defined
    if (typeof window.vehiclesTable !== 'undefined' && window.vehiclesTable) {
        window.vehiclesTable.ajax.reload(function() {
            console.log('✅ Vehicles table refreshed');
            showToast('<?= lang('App.inventory_refreshed') ?>', 'success');
        }, false);
    } else {
        console.warn('⚠️ Vehicles table not initialized');
        showToast('Unable to refresh: table not initialized', 'error');
    }
}

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
    
    // Final fallback to console and alert
    console.log(`${type.toUpperCase()}: ${message}`);
    if (type === 'error') {
        alert(message);
    }
}
</script>