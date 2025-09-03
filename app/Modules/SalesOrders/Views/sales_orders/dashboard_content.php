<!-- Enhanced Dashboard Widgets Row 1 -->
<div class="row mb-4">
    <!-- Today's Orders Widget -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card card-animate widget-clickable modern-widget border-0 shadow-none" data-tab="today" style="cursor: pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2 text-uppercase fw-medium"><?= lang('App.today_orders') ?></h6>
                        <h2 class="counter-value mb-1" id="todayOrdersCount" data-target="0">0</h2>
                        <p class="mb-0 text-muted">
                            <span class="text-success fw-medium">
                                <i class="ri-calendar-line me-1"></i>
                                <?= date('M j, Y') ?>
                            </span>
                        </p>
                    </div>
                    <div class="avatar-lg flex-shrink-0">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="ri-calendar-check-line fs-24"></i>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-success" role="progressbar" id="todayProgress" style="width: 0%"></div>
                </div>
                <p class="text-muted mt-2 mb-0 small">
                    <i class="ri-arrow-up-line text-success me-1"></i>
                    <?= lang('App.click_to_view_today') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Tomorrow's Orders Widget -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card card-animate widget-clickable modern-widget border-0 shadow-none" data-tab="tomorrow" style="cursor: pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2 text-uppercase fw-medium"><?= lang('App.tomorrow_orders') ?></h6>
                        <h2 class="counter-value mb-1" id="tomorrowOrdersCount" data-target="0">0</h2>
                        <p class="mb-0 text-muted">
                            <span class="text-info fw-medium">
                                <i class="ri-calendar-2-line me-1"></i>
                                <?= date('M j, Y', strtotime('+1 day')) ?>
                            </span>
                        </p>
                    </div>
                    <div class="avatar-lg flex-shrink-0">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                            <i class="ri-calendar-schedule-line fs-24"></i>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-info" role="progressbar" id="tomorrowProgress" style="width: 0%"></div>
                </div>
                <p class="text-muted mt-2 mb-0 small">
                    <i class="ri-arrow-right-line text-info me-1"></i>
                    <?= lang('App.click_to_view_tomorrow') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Pending Orders Widget -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card card-animate widget-clickable modern-widget border-0 shadow-none" data-tab="pending" style="cursor: pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2 text-uppercase fw-medium"><?= lang('App.pending_orders') ?></h6>
                        <h2 class="counter-value mb-1" id="pendingOrdersCount" data-target="0">0</h2>
                        <p class="mb-0 text-muted">
                            <span class="text-warning fw-medium">
                                <i class="ri-time-line me-1"></i>
                                <?= lang('App.require_attention') ?>
                            </span>
                        </p>
                    </div>
                    <div class="avatar-lg flex-shrink-0">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                            <i class="ri-hourglass-2-line fs-24"></i>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-warning" role="progressbar" id="pendingProgress" style="width: 0%"></div>
                </div>
                <p class="text-muted mt-2 mb-0 small">
                    <i class="ri-alert-line text-warning me-1"></i>
                    <?= lang('App.click_to_view_pending') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Week Orders Widget -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card card-animate widget-clickable modern-widget border-0 shadow-none" data-tab="week" style="cursor: pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2 text-uppercase fw-medium"><?= lang('App.week_orders') ?></h6>
                        <h2 class="counter-value mb-1" id="weekOrdersCount" data-target="0">0</h2>
                        <p class="mb-0 text-muted">
                            <span class="text-primary fw-medium">
                                <i class="ri-calendar-week-line me-1"></i>
                                <?= date('M j', strtotime('monday this week')) ?> - <?= date('M j', strtotime('sunday this week')) ?>
                            </span>
                        </p>
                    </div>
                    <div class="avatar-lg flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="ri-calendar-week-fill fs-24"></i>
                        </div>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-primary" role="progressbar" id="weekProgress" style="width: 0%"></div>
                </div>
                <p class="text-muted mt-2 mb-0 small">
                    <i class="ri-calendar-line text-primary me-1"></i>
                    <?= lang('App.click_to_view_week') ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Charts and Analytics Row -->
<div class="row mb-4">
    <!-- Orders Trend Chart -->
    <div class="col-xl-8">
        <div class="card modern-card border-0 shadow-none">
            <div class="card-header border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">
                            <i class="ri-line-chart-line me-2 text-primary"></i>
                            <?= lang('App.orders_trend_analysis') ?>
                        </h5>
                        <p class="text-muted mb-0 small"><?= lang('App.orders_trend_description') ?></p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="ri-calendar-line me-1"></i>
                                <span id="chartPeriodLabel"><?= lang('App.last_30_days') ?></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="updateChartPeriod(7)"><?= lang('App.last_7_days') ?></a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateChartPeriod(30)"><?= lang('App.last_30_days') ?></a></li>
                                <li><a class="dropdown-item" href="#" onclick="updateChartPeriod(90)"><?= lang('App.last_90_days') ?></a></li>
                            </ul>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="refreshCharts()" title="<?= lang('App.refresh_charts') ?>">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-2">
                <div id="ordersChart" style="height: 350px;" class="chart-container"></div>
                <div class="row mt-3">
                    <div class="col-md-4 text-center">
                        <div class="border-end">
                            <h5 class="mb-1" id="totalOrdersThisPeriod">--</h5>
                            <p class="text-muted mb-0 small"><?= lang('App.total_orders') ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="border-end">
                            <h5 class="mb-1" id="avgOrdersPerDay">--</h5>
                            <p class="text-muted mb-0 small"><?= lang('App.avg_per_day') ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5 class="mb-1" id="growthPercentage">--</h5>
                        <p class="text-muted mb-0 small"><?= lang('App.growth_rate') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution Chart -->
    <div class="col-xl-4">
        <div class="card modern-card h-100 border-0 shadow-none">
            <div class="card-header border-0 pb-0">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="ri-donut-chart-line me-2 text-primary"></i>
                        <?= lang('App.status_distribution') ?>
                    </h5>
                    <p class="text-muted mb-0 small"><?= lang('App.current_status_breakdown') ?></p>
                </div>
            </div>
            <div class="card-body pt-2">
                <div id="statusChart" style="height: 250px;" class="chart-container mb-3"></div>
                
                <!-- Status Legend with Progress Bars -->
                <div class="status-legend">
                    <div class="status-item-modern mb-3" data-status="pending" style="cursor: pointer;" title="<?= lang('App.click_to_view_pending') ?>">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center">
                                <div class="status-indicator bg-primary me-2"></div>
                                <span class="fw-medium"><?= lang('App.pending') ?></span>
                            </div>
                            <span class="fw-semibold text-primary" id="statusPending">0</span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-primary" id="pendingStatusProgress" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="status-item-modern mb-3" data-status="processing" style="cursor: pointer;" title="<?= lang('App.click_to_view_processing') ?>">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center">
                                <div class="status-indicator bg-warning me-2"></div>
                                <span class="fw-medium"><?= lang('App.processing') ?></span>
                            </div>
                            <span class="fw-semibold text-warning" id="statusProcessing">0</span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-warning" id="processingStatusProgress" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="status-item-modern mb-3" data-status="completed" style="cursor: pointer;" title="<?= lang('App.click_to_view_completed') ?>">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center">
                                <div class="status-indicator bg-success me-2"></div>
                                <span class="fw-medium"><?= lang('App.completed') ?></span>
                            </div>
                            <span class="fw-semibold text-success" id="statusCompleted">0</span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" id="completedStatusProgress" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="status-item-modern mb-3" data-status="cancelled" style="cursor: pointer;" title="<?= lang('App.click_to_view_cancelled') ?>">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center">
                                <div class="status-indicator bg-danger me-2"></div>
                                <span class="fw-medium"><?= lang('App.cancelled') ?></span>
                            </div>
                            <span class="fw-semibold text-danger" id="statusCancelled">0</span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-danger" id="cancelledStatusProgress" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance and Analytics Row -->
<div class="row mb-4">
    <!-- Top Clients Widget -->
    <div class="col-xl-6">
        <div class="card modern-card h-100 border-0 shadow-none">
            <div class="card-header border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">
                            <i class="ri-vip-crown-line me-2 text-primary"></i>
                            <?= lang('App.top_clients_performance') ?>
                        </h5>
                        <p class="text-muted mb-0 small"><?= lang('App.clients_most_orders_month') ?></p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ri-more-2-line"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="refreshTopClients()">
                                <i class="ri-refresh-line me-2"></i><?= lang('App.refresh') ?>
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportTopClients()">
                                <i class="ri-download-line me-2"></i><?= lang('App.export') ?>
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body pt-2">
                <div id="topClientsContainer">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary mb-2" role="status" style="width: 1.5rem; height: 1.5rem;"></div>
                        <p class="text-muted small"><?= lang('App.loading_top_clients') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics Widget -->
    <div class="col-xl-6">
        <div class="card modern-card h-100 border-0 shadow-none">
            <div class="card-header border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">
                            <i class="ri-speed-up-line me-2 text-primary"></i>
                            <?= lang('App.performance_metrics') ?>
                        </h5>
                        <p class="text-muted mb-0 small"><?= lang('App.order_completion_statistics') ?></p>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="refreshPerformanceMetrics()" title="<?= lang('App.refresh_metrics') ?>">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
            <div class="card-body pt-2">
                <!-- Key Performance Indicators -->
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="performance-card text-center">
                            <div class="performance-icon bg-success-subtle text-success mb-2">
                                <i class="ri-timer-line fs-20"></i>
                            </div>
                            <h4 class="performance-value mb-1" id="onTimePercentage">--</h4>
                            <p class="text-muted mb-0 small"><?= lang('App.on_time_completion') ?></p>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-success" id="onTimeProgressBar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="performance-card text-center">
                            <div class="performance-icon bg-warning-subtle text-warning mb-2">
                                <i class="ri-alarm-warning-line fs-20"></i>
                            </div>
                            <h4 class="performance-value mb-1" id="delayedPercentage">--</h4>
                            <p class="text-muted mb-0 small"><?= lang('App.delayed_orders') ?></p>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-warning" id="delayedProgressBar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Metrics Grid -->
                <div class="metrics-grid">
                    <div class="metric-item">
                        <div class="d-flex align-items-center">
                            <div class="metric-icon-sm bg-info-subtle text-info me-3">
                                <i class="ri-time-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="metric-number mb-0" id="avgCompletionTime">--</h6>
                                <p class="text-muted mb-0 small"><?= lang('App.avg_completion_time') ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-item">
                        <div class="d-flex align-items-center">
                            <div class="metric-icon-sm bg-danger-subtle text-danger me-3">
                                <i class="ri-error-warning-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="metric-number mb-0" id="overdueCount">--</h6>
                                <p class="text-muted mb-0 small"><?= lang('App.overdue_orders') ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-item">
                        <div class="d-flex align-items-center">
                            <div class="metric-icon-sm bg-success-subtle text-success me-3">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="metric-number mb-0" id="completionRate">--</h6>
                                <p class="text-muted mb-0 small"><?= lang('App.completion_rate') ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-item">
                        <div class="d-flex align-items-center">
                            <div class="metric-icon-sm bg-secondary-subtle text-secondary me-3">
                                <i class="ri-close-circle-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="metric-number mb-0" id="cancellationRate">--</h6>
                                <p class="text-muted mb-0 small"><?= lang('App.cancellation_rate') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions and Activity Row -->
<div class="row mb-4">
    <!-- Quick Actions Widget -->
    <div class="col-xl-4">
        <div class="card modern-card h-100 border-0 shadow-none">
            <div class="card-header border-0 pb-0">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="ri-flashlight-line me-2 text-primary"></i>
                        <?= lang('App.quick_actions') ?>
                    </h5>
                    <p class="text-muted mb-0 small"><?= lang('App.common_tasks_shortcuts') ?></p>
                </div>
            </div>
            <div class="card-body pt-2">
                <div class="quick-actions-grid">
                    <button class="quick-action-btn primary-action" onclick="openNewOrderModal()">
                        <div class="action-icon bg-primary-subtle text-primary">
                            <i class="ri-add-circle-line"></i>
                        </div>
                        <div class="action-content">
                            <h6 class="action-title"><?= lang('App.new_order') ?></h6>
                            <p class="action-desc"><?= lang('App.create_new_sales_order') ?></p>
                        </div>
                    </button>
                    
                    <button class="quick-action-btn" onclick="navigateToAllOrders()">
                        <div class="action-icon bg-info-subtle text-info">
                            <i class="ri-list-check-3"></i>
                        </div>
                        <div class="action-content">
                            <h6 class="action-title"><?= lang('App.view_all') ?></h6>
                            <p class="action-desc"><?= lang('App.browse_all_orders') ?></p>
                        </div>
                    </button>
                    
                    <button class="quick-action-btn" onclick="navigateToOverdueOrders()">
                        <div class="action-icon bg-warning-subtle text-warning">
                            <i class="ri-alarm-warning-line"></i>
                        </div>
                        <div class="action-content">
                            <h6 class="action-title"><?= lang('App.overdue') ?></h6>
                            <p class="action-desc"><?= lang('App.check_overdue_orders') ?></p>
                        </div>
                    </button>
                    
                    <button class="quick-action-btn" onclick="refreshDashboard()">
                        <div class="action-icon bg-secondary-subtle text-secondary">
                            <i class="ri-refresh-line"></i>
                        </div>
                        <div class="action-content">
                            <h6 class="action-title"><?= lang('App.refresh') ?></h6>
                            <p class="action-desc"><?= lang('App.update_dashboard') ?></p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Widget -->
    <div class="col-xl-8">
        <div class="card modern-card h-100 border-0 shadow-none">
            <div class="card-header border-0 pb-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">
                            <i class="ri-pulse-line me-2 text-primary"></i>
                            <?= lang('App.recent_activity') ?>
                        </h5>
                        <p class="text-muted mb-0 small"><?= lang('App.latest_order_updates') ?></p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="autoRefreshActivity" checked>
                            <label class="form-check-label small text-muted" for="autoRefreshActivity">
                                <?= lang('App.auto_refresh') ?>
                            </label>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="refreshRecentActivity()" title="<?= lang('App.refresh_activity') ?>">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-2">
                <div id="recentActivityContainer" class="activity-timeline">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary mb-2" role="status" style="width: 1.5rem; height: 1.5rem;"></div>
                        <p class="text-muted small"><?= lang('App.loading_recent_activity') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Dashboard Styles */
.modern-widget {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.08);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.modern-widget:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    border-color: rgba(0,0,0,0.12);
}

.modern-card {
    border: 1px solid rgba(0,0,0,0.08);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}

.modern-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

/* Widget Progress Bars */
.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 1s ease-in-out;
}

/* Avatar Improvements */
.avatar-lg {
    width: 60px;
    height: 60px;
}

.avatar-lg .avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

/* Counter Values */
.counter-value {
    font-weight: 700;
    font-size: 2.2rem;
    line-height: 1;
    color: #2c3e50;
}

/* Status Indicators */
.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

.status-item-modern {
    padding: 8px 0;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.status-item-modern:hover {
    background-color: rgba(0,0,0,0.02);
    padding-left: 8px;
    margin-left: -8px;
    margin-right: -8px;
}

/* Performance Cards */
.performance-card {
    padding: 20px 15px;
    border-radius: 12px;
    background: #f8f9fa;
    border: 1px solid rgba(0,0,0,0.06);
    transition: all 0.3s ease;
}

.performance-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.performance-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.performance-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
}

/* Metrics Grid */
.metrics-grid {
    display: grid;
    gap: 15px;
}

.metric-item {
    padding: 15px;
    border-radius: 10px;
    background: rgba(0,0,0,0.02);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.metric-item:hover {
    background: rgba(0,0,0,0.04);
    transform: translateY(-1px);
}

.metric-icon-sm {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.metric-number {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

/* Quick Actions Grid */
.quick-actions-grid {
    display: grid;
    gap: 12px;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    padding: 15px;
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 10px;
    background: #ffffff;
    transition: all 0.3s ease;
    text-align: left;
    width: 100%;
}

.quick-action-btn:hover {
    background: rgba(0,0,0,0.02);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border-color: rgba(0,0,0,0.12);
}

.quick-action-btn.primary-action {
    background: #405189;
    color: white;
    border-color: #405189;
}

.quick-action-btn.primary-action:hover {
    background: #364675;
    color: white;
}

.action-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 18px;
    flex-shrink: 0;
}

.quick-action-btn.primary-action .action-icon {
    background: rgba(255,255,255,0.2) !important;
    color: white !important;
}

.action-content {
    flex-grow: 1;
}

.action-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 2px;
    color: inherit;
}

.action-desc {
    font-size: 0.75rem;
    color: rgba(108, 117, 125, 0.8);
    margin-bottom: 0;
    line-height: 1.2;
}

.quick-action-btn.primary-action .action-desc {
    color: rgba(255,255,255,0.8);
}

/* Activity Timeline */
.activity-timeline {
    max-height: 400px;
    overflow-y: auto;
}

/* Subtle Color Variants */
.bg-success-subtle {
    background-color: rgba(40, 167, 69, 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(23, 162, 184, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.bg-primary-subtle {
    background-color: rgba(64, 81, 137, 0.1) !important;
}

.bg-secondary-subtle {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

/* Chart Enhancements */
.chart-container {
    position: relative;
    min-height: 200px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .counter-value {
        font-size: 1.8rem;
    }
    
    .performance-value {
        font-size: 1.5rem;
    }
    
    .quick-actions-grid {
        gap: 8px;
    }
    
    .quick-action-btn {
        padding: 12px;
    }
    
    .action-icon {
        width: 35px;
        height: 35px;
        margin-right: 10px;
    }
}

/* Animation Classes */
.metric-loaded {
    animation: slideInUp 0.6s ease forwards;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Legacy Compatibility */
.card-animate {
    transition: all 0.3s ease;
}

.card-animate:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.widget-clickable {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.widget-clickable:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.widget-clickable:active {
    transform: translateY(-1px);
}

.widget-clickable:hover .avatar-title {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

.status-item {
    transition: all 0.2s ease;
    border-radius: 0.375rem;
    padding: 0.5rem;
    margin: 0.25rem 0;
}

.status-item:hover {
    background-color: rgba(0,0,0,0.05);
    border-radius: 0.375rem;
    transition: background-color 0.2s ease;
}

.chart-container {
    position: relative;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chart-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #6c757d;
}

.chart-error {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #dc3545;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    font-size: 1rem;
}

.counter-value {
    font-weight: 700;
    font-size: 2rem;
    line-height: 1;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.bg-soft-success {
    background-color: rgba(40, 167, 69, 0.1) !important;
}

.bg-soft-info {
    background-color: rgba(23, 162, 184, 0.1) !important;
}

.bg-soft-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-soft-secondary {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

.text-success {
    color: #28a745 !important;
}

.text-info {
    color: #17a2b8 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-secondary {
    color: #6c757d !important;
}

.fs-22 {
    font-size: 1.375rem !important;
}

.fs-12 {
    font-size: 0.75rem !important;
}

.fs-13 {
    font-size: 0.8125rem !important;
}

.icon-sm {
    width: 16px;
    height: 16px;
}

.icon-xs {
    width: 12px;
    height: 12px;
}

#dashboard-orders-table {
    width: 100% !important;
}

#dashboard-orders-table thead th {
    text-align: center !important;
    font-weight: 600;
}

.link-primary, .link-success, .link-danger {
    text-decoration: none;
    transition: all 0.15s ease;
}

.link-primary:hover, .link-success:hover, .link-danger:hover {
    transform: scale(1.1);
}

/* Chart containers */
#ordersChart, #statusChart {
    width: 100% !important;
}

/* Status indicators */
.status-indicator {
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
    white-space: nowrap;
}

.status-completed {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-processing {
    background-color: #cce7ff;
    color: #004085;
    border: 1px solid #b3d7ff;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Animation for counter values */
@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.counter-value {
    animation: countUp 0.6s ease;
}

/* Hover effects for charts */
.chart-container:hover {
    transform: scale(1.01);
}

/* Performance Metrics Styles */
.performance-metric {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
    height: 100%;
}

.performance-metric:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.performance-metric.on-time {
    background: #d4edda;
    border-color: #28a745;
}

.performance-metric.delayed {
    background: #fff3cd;
    border-color: #ffc107;
}

.metric-icon {
    margin-bottom: 15px;
}

.metric-icon i {
    width: 32px;
    height: 32px;
}

.metric-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 5px;
    color: #343a40;
}

.metric-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 15px;
    font-weight: 500;
}

.metric-progress {
    margin-top: 10px;
}

.metric-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.metric-box:hover {
    background: #e9ecef;
    transform: translateY(-1px);
}

.metric-number {
    font-size: 1.5rem;
    font-weight: 600;
    color: #343a40;
}

/* Top Clients Styles */
.client-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s ease;
}

.client-item:last-child {
    border-bottom: none;
}

.client-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding-left: 10px;
    margin: 0 -10px;
}

.client-rank {
    width: 40px;
    height: 40px;
    background: #667eea;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    margin-right: 15px;
}

.client-rank.gold {
    background: #f7b84b;
}

.client-rank.silver {
    background: #adb5bd;
}

.client-rank.bronze {
    background: #cd7f32;
}

.client-info {
    flex-grow: 1;
}

.client-name {
    font-weight: 600;
    color: #343a40;
    margin-bottom: 2px;
}

.client-details {
    font-size: 0.85rem;
    color: #6c757d;
}

.client-orders {
    text-align: right;
}

.client-orders .orders-count {
    font-size: 1.2rem;
    font-weight: 700;
    color: #405189;
}

.client-orders .orders-label {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Recent Activity Styles */
.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.activity-icon.created {
    background: #d4edda;
    color: #28a745;
}

.activity-icon.updated {
    background: #d1ecf1;
    color: #17a2b8;
}

.activity-icon.completed {
    background: #d4edda;
    color: #28a745;
}

.activity-icon.deleted {
    background: #f8d7da;
    color: #dc3545;
}

.activity-content {
    flex-grow: 1;
}

.activity-title {
    font-weight: 600;
    color: #343a40;
    margin-bottom: 4px;
}

.activity-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.activity-time {
    color: #adb5bd;
    font-size: 0.8rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .performance-metric {
        margin-bottom: 15px;
    }
    
    .metric-value {
        font-size: 2rem;
    }
    
    .client-item {
        flex-direction: column;
        text-align: center;
    }
    
    .client-rank {
        margin-bottom: 10px;
        margin-right: 0;
    }
}

/* Animation for loading */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.metric-loaded {
    animation: slideIn 0.5s ease forwards;
}
</style>

<script>
// Static configuration
    const DASHBOARD_CONFIG = {
        baseUrl: '<?= base_url() ?>',
        ajaxUrl: '<?= base_url('sales_orders/all_content') ?>',
        viewUrl: '<?= base_url('sales_orders/view/') ?>',
        csrfName: '<?= csrf_token() ?>',
        csrfHash: '<?= csrf_hash() ?>'
    };

    document.addEventListener("DOMContentLoaded", function() {
    
        // Initialize Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

    // Add click handlers for widgets
    addWidgetClickHandlers();
    
    // Add click handlers for status items
    addStatusClickHandlers();

    // Initialize dashboard with immediate load
    setTimeout(() => {
    initializeDashboardData();
    }, 500);
    
    // Initialize filter listener
    setTimeout(() => {
        if (typeof window.initializeDashboardFilterListener === 'function') {
            window.initializeDashboardFilterListener();
        }
    }, 1000);
});

// Get global client filter
function getInitialGlobalClientFilter() {
    try {
        // Check window.globalClientFilter first
        if (typeof window.globalClientFilter !== 'undefined' && window.globalClientFilter) {
            return window.globalClientFilter || '';
        }
        
        // Check localStorage
        const savedFilter = localStorage.getItem('salesOrdersGlobalClientFilter');
        if (savedFilter) {
            return savedFilter;
        }
        
        // Check DOM element
        const globalFilterElement = document.getElementById('globalClientFilter');
        if (globalFilterElement && globalFilterElement.value) {
            return globalFilterElement.value;
        }
        
        return '';
    } catch (error) {
        console.error('❌ Dashboard: Error getting global client filter:', error);
        return '';
    }
}

// Add click handlers for widgets
function addWidgetClickHandlers() {
    const widgets = document.querySelectorAll('.widget-clickable');
    widgets.forEach(widget => {
        widget.addEventListener('click', function() {
            const tab = this.getAttribute('data-tab');
            navigateToTab(tab);
        });
    });
}

// Update progress bars for widgets
function updateWidgetProgress(widgetId, percentage) {
    const progressBar = document.getElementById(widgetId + 'Progress');
    if (progressBar) {
        setTimeout(() => {
            progressBar.style.width = percentage + '%';
        }, 500);
    }
}

// Update status progress bars
function updateStatusProgress() {
    const statusElements = ['pending', 'processing', 'completed', 'cancelled'];
    const total = statusElements.reduce((sum, status) => {
        // Add safety check for status string
        if (!status || typeof status !== 'string') return sum;
        const element = document.getElementById('status' + status.charAt(0).toUpperCase() + status.slice(1));
        return sum + (element ? parseInt(element.textContent) || 0 : 0);
    }, 0);
    
    if (total > 0) {
        statusElements.forEach(status => {
            // Add safety check for status string
            if (!status || typeof status !== 'string') return;
            const element = document.getElementById('status' + status.charAt(0).toUpperCase() + status.slice(1));
            const progressBar = document.getElementById(status + 'StatusProgress');
            if (element && progressBar) {
                const count = parseInt(element.textContent) || 0;
                const percentage = (count / total) * 100;
                setTimeout(() => {
                    progressBar.style.width = percentage + '%';
                }, 300);
            }
        });
    }
}

// Add click handlers for status items
function addStatusClickHandlers() {
    const statusItems = document.querySelectorAll('.status-item, .status-item-modern');
    statusItems.forEach(item => {
        item.addEventListener('click', function() {
            const status = this.getAttribute('data-status');
            navigateToAllOrdersWithStatus(status);
        });
    });
}

// Navigate to specific tab
function navigateToTab(tabName) {
    const tabMapping = {
        'today': 'today-orders-tab',
        'tomorrow': 'tomorrow-orders-tab', 
        'pending': 'pending-orders-tab',
        'week': 'week-orders-tab',
        'all': 'all-orders-tab'
    };
    
    const actualTabId = tabMapping[tabName] || tabName;
    const tabButton = document.querySelector(`[href="#${actualTabId}"]`);
    
    if (tabButton) {
        const tab = new bootstrap.Tab(tabButton);
        tab.show();
        // Add safety check for tabName
        const displayName = (tabName && typeof tabName === 'string') 
            ? tabName.charAt(0).toUpperCase() + tabName.slice(1) 
            : 'Unknown';
        showToast('info', `Switched to ${displayName} Orders`);
    } else {
        console.warn(`Tab button for ${tabName} not found`);
    }
}

// Navigate to all orders tab
function navigateToAllOrders() {
    navigateToTab('all');
}

// Navigate to all orders with status filter
function navigateToAllOrdersWithStatus(status) {
    const allTabButton = document.querySelector('[href="#all-orders-tab"]');
    if (allTabButton) {
        const tab = new bootstrap.Tab(allTabButton);
        tab.show();
        
        setTimeout(() => {
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.value = status;
                statusFilter.dispatchEvent(new Event('change'));
            }
            // Add safety check for status
            const displayStatus = (status && typeof status === 'string') 
                ? status.charAt(0).toUpperCase() + status.slice(1) 
                : 'Unknown';
            showToast('info', `Filtered orders by status: ${displayStatus}`);
        }, 500);
    }
}

// Dashboard data initialization
function initializeDashboardData() {
    
    // Initialize counters immediately
    initializeCounters();
    
    // Load dashboard stats
    setTimeout(() => {
        loadDashboardStats();
    }, 100);

    // Load widgets
    setTimeout(() => {
        loadTopClients();
        loadPerformanceMetrics();
        loadRecentActivity();
    }, 300);

    // Initialize charts
    setTimeout(() => {
        initializeSimpleCharts();
    }, 500);

    // Apply global filter if exists
    setTimeout(() => {
        const globalClientFilter = getInitialGlobalClientFilter();
        if (globalClientFilter) {
            window.syncDashboardWithGlobalFilter();
        }
    }, 1000);
}

// Initialize counter animations
function initializeCounters() {
        const counters = document.querySelectorAll('.counter-value');
        counters.forEach((counter, index) => {
        const targetValue = parseInt(counter.getAttribute('data-target')) || 0;
        animateCounter(counter, 0, targetValue, 1000, index * 100);
    });
}

// Animate individual counter
function animateCounter(element, start, end, duration, delay = 0) {
    setTimeout(() => {
        const range = end - start;
        const increment = Math.ceil(range / 30);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= end) {
                current = end;
                clearInterval(timer);
            }
            element.textContent = current;
        }, duration / 30);
    }, delay);
}

// Load dashboard statistics
function loadDashboardStats() {
    const globalClientFilter = getInitialGlobalClientFilter();
    
    let url = DASHBOARD_CONFIG.baseUrl + 'sales_orders/dashboard_stats';
    if (globalClientFilter) {
        url += `?client_id=${encodeURIComponent(globalClientFilter)}`;
    }


    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateDashboardStats(data.data || data.metrics || data.stats);
            if (data.charts) {
                updateChartsData(data.charts);
            }
        } else {
            console.error('❌ Dashboard: Error loading stats:', data.message);
            updateDashboardStats({ today: 0, tomorrow: 0, pending: 0, week: 0 });
        }
    })
    .catch(error => {
        console.error('❌ Dashboard: Error fetching stats:', error);
        updateDashboardStats({ today: 0, tomorrow: 0, pending: 0, week: 0 });
    });
}

// Update dashboard statistics
function updateDashboardStats(stats) {
    const elements = {
        'todayOrdersCount': stats.today_count || stats.today || 0,
        'tomorrowOrdersCount': stats.tomorrow_count || stats.tomorrow || 0,
        'pendingOrdersCount': stats.pending_count || stats.pending || 0,
        'weekOrdersCount': stats.week_count || stats.week || 0
    };
    
    Object.keys(elements).forEach((elementId, index) => {
        const element = document.getElementById(elementId);
        if (element) {
            const newValue = elements[elementId];
            element.setAttribute('data-target', newValue);
            
            element.style.opacity = '1';
            element.style.transition = 'opacity 0.3s ease';
            
            setTimeout(() => {
                animateCounter(element, 0, newValue, 800);
            }, index * 50);
            
            // Update progress bars for widgets
            const maxValue = Math.max(...Object.values(elements));
            const percentage = maxValue > 0 ? (newValue / maxValue) * 100 : 0;
            updateWidgetProgress(elementId.replace('Count', ''), percentage);
        }
    });
    
    // Update status breakdown
    if (stats.status_breakdown) {
        const statusElements = {
            'statusPending': stats.status_breakdown.pending || 0,
            'statusProcessing': stats.status_breakdown.processing || 0,
            'statusCompleted': stats.status_breakdown.completed || 0,
            'statusCancelled': stats.status_breakdown.cancelled || 0
        };
        
        Object.keys(statusElements).forEach((elementId) => {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = statusElements[elementId];
            }
        });
        
        // Update status progress bars
        setTimeout(() => {
            updateStatusProgress();
        }, 500);
    }
    
}

// Load top clients data
function loadTopClients() {
    const globalClientFilter = getInitialGlobalClientFilter();
    
    let url = DASHBOARD_CONFIG.baseUrl + 'sales_orders/top_clients';
    if (globalClientFilter) {
        url += `?client_id=${encodeURIComponent(globalClientFilter)}`;
    }


    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayTopClients(data.data || data.clients || []);
        } else {
            console.error('❌ Dashboard: Error loading top clients:', data.message);
            showTopClientsError('Error loading top clients data');
        }
    })
    .catch(error => {
        console.error('❌ Dashboard: Error fetching top clients:', error);
        showTopClientsError('Error connecting to server');
    });
}

// Display top clients
function displayTopClients(clients) {
    const container = document.getElementById('topClientsContainer');
    if (!container) return;

    if (!clients || clients.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i data-feather="users" style="width: 48px; height: 48px; margin-bottom: 1rem; color: #6c757d;"></i>
                <h6 class="text-muted">No Client Data</h6>
                <p class="text-muted small">No orders found for the selected period</p>
            </div>
        `;
        if (typeof feather !== 'undefined') feather.replace();
        return;
    }
    
    let html = '';
    clients.slice(0, 5).forEach((client, index) => {
        const rankClass = index === 0 ? 'gold' : index === 1 ? 'silver' : index === 2 ? 'bronze' : '';
        html += `
            <div class="client-item">
                <div class="client-rank ${rankClass}">${index + 1}</div>
                <div class="client-info">
                    <div class="client-name">${client.name || client.client_name || 'Unknown Client'}</div>
                    <div class="client-details">
                        Orders this month: ${client.order_count || 0}
                    </div>
                </div>
                <div class="client-orders">
                    <div class="orders-count">${client.order_count}</div>
                    <div class="orders-label">orders</div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    container.classList.add('metric-loaded');
}

// Show top clients error
function showTopClientsError(message) {
    const container = document.getElementById('topClientsContainer');
    if (!container) return;

    container.innerHTML = `
        <div class="text-center py-4">
            <i data-feather="alert-circle" style="width: 48px; height: 48px; margin-bottom: 1rem; color: #dc3545;"></i>
            <h6 class="text-danger">Error</h6>
            <p class="text-muted small">${message}</p>
            <button class="btn btn-outline-primary btn-sm" onclick="loadTopClients()">
                <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                Retry
            </button>
        </div>
    `;
    if (typeof feather !== 'undefined') feather.replace();
}

// Load performance metrics
function loadPerformanceMetrics() {
    const globalClientFilter = getInitialGlobalClientFilter();
    
    let url = DASHBOARD_CONFIG.baseUrl + 'sales_orders/performance_metrics';
    if (globalClientFilter) {
        url += `?client_id=${encodeURIComponent(globalClientFilter)}`;
    }


    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayPerformanceMetrics(data.data || data.metrics || {});
        } else {
            console.error('❌ Dashboard: Error loading performance metrics:', data.message);
            showPerformanceMetricsError();
        }
    })
    .catch(error => {
        console.error('❌ Dashboard: Error fetching performance metrics:', error);
        showPerformanceMetricsError();
    });
}

// Display performance metrics
function displayPerformanceMetrics(metrics) {
    // On-time percentage
    const onTimePercentage = metrics.on_time_percentage || 0;
    const onTimeElement = document.getElementById('onTimePercentage');
    const onTimeProgressBar = document.getElementById('onTimeProgressBar');
    
    if (onTimeElement) {
        animateValue(onTimeElement, 0, onTimePercentage, 1000, '%');
    }
    if (onTimeProgressBar) {
        setTimeout(() => {
            onTimeProgressBar.style.width = onTimePercentage + '%';
        }, 300);
    }

    // Delayed percentage
    const delayedPercentage = metrics.delayed_percentage || 0;
    const delayedElement = document.getElementById('delayedPercentage');
    const delayedProgressBar = document.getElementById('delayedProgressBar');
    
    if (delayedElement) {
        animateValue(delayedElement, 0, delayedPercentage, 1000, '%');
    }
    if (delayedProgressBar) {
        setTimeout(() => {
            delayedProgressBar.style.width = delayedPercentage + '%';
        }, 400);
    }

    // Average completion time
    const avgTimeElement = document.getElementById('avgCompletionTime');
    if (avgTimeElement) {
        const avgHours = metrics.avg_completion_hours || 0;
        animateValue(avgTimeElement, 0, avgHours, 1000, ' hours');
    }

    // Overdue count
    const overdueElement = document.getElementById('overdueCount');
    if (overdueElement) {
        const overdueCount = metrics.overdue_count || 0;
        animateValue(overdueElement, 0, overdueCount, 1000, '');
    }

    // Completion rate
    const completionRateElement = document.getElementById('completionRate');
    if (completionRateElement) {
        const completionRate = metrics.completion_rate || 0;
        animateValue(completionRateElement, 0, completionRate, 1000, '%');
    }

    // Cancellation rate
    const cancellationRateElement = document.getElementById('cancellationRate');
    if (cancellationRateElement) {
        const cancellationRate = metrics.cancellation_rate || 0;
        animateValue(cancellationRateElement, 0, cancellationRate, 1000, '%');
    }

}

// Show performance metrics error
function showPerformanceMetricsError() {
    const elements = ['onTimePercentage', 'delayedPercentage', 'avgCompletionTime', 'overdueCount', 'completionRate', 'cancellationRate'];
    elements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = 'Error';
            element.style.color = '#dc3545';
        }
    });
}

// Animate value function
function animateValue(element, start, end, duration, suffix = '') {
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            current = end;
            clearInterval(timer);
        }
        
        if (suffix === '%') {
            element.textContent = Math.round(current) + suffix;
        } else if (suffix === ' hours') {
            element.textContent = Math.round(current * 10) / 10 + suffix;
        } else {
            element.textContent = Math.round(current) + suffix;
        }
    }, 16);
}

// Load recent activity
function loadRecentActivity() {
    const globalClientFilter = getInitialGlobalClientFilter();
    
    let url = DASHBOARD_CONFIG.baseUrl + 'sales_orders/recent_activity';
    if (globalClientFilter) {
        url += `?client_id=${encodeURIComponent(globalClientFilter)}`;
    }


    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayRecentActivity(data.data || data.activities || []);
        } else {
            console.error('❌ Dashboard: Error loading recent activity:', data.message);
            showRecentActivityError('Error loading recent activity');
        }
    })
    .catch(error => {
        console.error('❌ Dashboard: Error fetching recent activity:', error);
        showRecentActivityError('Error connecting to server');
    });
}

// Display recent activity
function displayRecentActivity(activities) {
    const container = document.getElementById('recentActivityContainer');
    if (!container) return;

    if (!activities || activities.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i data-feather="activity" style="width: 48px; height: 48px; margin-bottom: 1rem; color: #6c757d;"></i>
                <h6 class="text-muted">No Recent Activity</h6>
                <p class="text-muted small">No recent order updates found</p>
            </div>
        `;
        if (typeof feather !== 'undefined') feather.replace();
        return;
    }

    let html = '';
    activities.slice(0, 8).forEach(activity => {
        const iconClass = getActivityIconClass(activity.activity_type || activity.action);
        const icon = getActivityIcon(activity.activity_type || activity.action);
        
        // Format time ago
        const timeAgo = activity.time_ago || formatTimeAgo(activity.created_at);
        const orderNumber = activity.order_number || `SAL-${String(activity.order_id || 0).padStart(5, '0')}`;
        const title = `${orderNumber} - ${activity.activity_type || 'Updated'}`;
        const description = activity.description || 'Order activity';
        
        html += `
            <div class="activity-item">
                <div class="activity-icon ${iconClass}">
                    <i data-feather="${icon}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">${title}</div>
                    <div class="activity-description">${description}</div>
                    <div class="activity-time">${timeAgo}</div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    container.classList.add('metric-loaded');
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Get activity icon class
function getActivityIconClass(action) {
    const classes = {
        'created': 'created',
        'updated': 'updated',
        'completed': 'completed',
        'deleted': 'deleted',
        'cancelled': 'deleted'
    };
    return classes[action] || 'updated';
}

// Get activity icon
function getActivityIcon(action) {
    const icons = {
        'created': 'plus-circle',
        'updated': 'edit-3',
        'completed': 'check-circle',
        'deleted': 'trash-2',
        'cancelled': 'x-circle'
    };
    return icons[action] || 'edit-3';
}

// Format time ago helper function
function formatTimeAgo(dateString) {
    if (!dateString) return 'Unknown time';
    
    try {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMins / 60);
        const diffDays = Math.floor(diffHours / 24);
        
        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return `${diffMins}m ago`;
        if (diffHours < 24) return `${diffHours}h ago`;
        if (diffDays < 7) return `${diffDays}d ago`;
        
        return date.toLocaleDateString();
    } catch (error) {
        return 'Unknown time';
    }
}

// Show recent activity error
function showRecentActivityError(message) {
    const container = document.getElementById('recentActivityContainer');
    if (!container) return;

    container.innerHTML = `
        <div class="text-center py-4">
            <i data-feather="alert-circle" style="width: 48px; height: 48px; margin-bottom: 1rem; color: #dc3545;"></i>
            <h6 class="text-danger">Error</h6>
            <p class="text-muted small">${message}</p>
            <button class="btn btn-outline-primary btn-sm" onclick="loadRecentActivity()">
                <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                Retry
            </button>
        </div>
    `;
    if (typeof feather !== 'undefined') feather.replace();
}

// Initialize simple charts (fallback)
function initializeSimpleCharts() {
    
    // Check if ApexCharts is available
    if (typeof ApexCharts === 'undefined') {
        console.warn('⚠️ ApexCharts not available, showing fallback');
        showSimpleChartFallback();
        return;
    }
    
    try {
        // Initialize orders chart
        initializeOrdersChart();
    
        // Initialize status chart with delay
    setTimeout(() => {
            initializeStatusChart();
        }, 500);
        
        } catch (error) {
        console.error('❌ Error initializing charts:', error);
        showSimpleChartFallback();
    }
}

// Simple orders chart
function initializeOrdersChart() {
    const chartContainer = document.querySelector("#ordersChart");
    if (!chartContainer) return;
    
    const options = {
        series: [{
            name: 'Orders',
            data: [0, 0, 0, 0, 0, 0, 0]
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: { show: false }
        },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#405189'],
        xaxis: {
            categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        },
        yaxis: { min: 0 }
    };
    
    try {
        // Only destroy if chart exists and has destroy method
        if (window.ordersChart && typeof window.ordersChart.destroy === 'function') {
                    window.ordersChart.destroy();
                }
        window.ordersChart = new ApexCharts(chartContainer, options);
        window.ordersChart.render().then(() => {
                loadOrdersChartData();
        });
    } catch (error) {
        console.error('❌ Error creating orders chart:', error);
    }
}

// Simple status chart
function initializeStatusChart() {
    const chartContainer = document.querySelector("#statusChart");
    if (!chartContainer) return;
    
    const options = {
        series: [0, 0, 0, 0],
        chart: {
            type: 'donut',
            height: 280
        },
        labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
        colors: ['#405189', '#f7b84b', '#0ab39c', '#f06548'],
        legend: { show: false }
    };
    
    try {
        // Only destroy if chart exists and has destroy method
        if (window.statusChart && typeof window.statusChart.destroy === 'function') {
                    window.statusChart.destroy();
                }
            window.statusChart = new ApexCharts(chartContainer, options);
        window.statusChart.render().then(() => {
        });
    } catch (error) {
        console.error('❌ Error creating status chart:', error);
    }
}

// Load chart data
function loadOrdersChartData(period = 30) {
    const globalClientFilter = getInitialGlobalClientFilter();
    
    let url = DASHBOARD_CONFIG.baseUrl + 'sales_orders/chart_data?period=' + period;
    if (globalClientFilter) {
        url += '&client_id=' + encodeURIComponent(globalClientFilter);
    }

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.charts) {
            updateChartsData(data.charts);
        }
    })
    .catch(error => {
        console.error('❌ Error loading chart data:', error);
    });
}

// Update charts with data
function updateChartsData(chartsData) {
    if (!chartsData) return;
    
    // Update orders chart only if it exists and has the method
    if (window.ordersChart && chartsData.orders && typeof window.ordersChart.updateOptions === 'function') {
        try {
            window.ordersChart.updateOptions({
                xaxis: {
                    categories: chartsData.orders.categories || []
                }
            });
            
            if (typeof window.ordersChart.updateSeries === 'function') {
                window.ordersChart.updateSeries([{
                    name: 'Orders',
                    data: chartsData.orders.data || []
                }]);
            }
        } catch (error) {
            console.error('❌ Error updating orders chart:', error);
        }
    }
    
    // Update status chart AND the progress bars below
    if (window.statusChart && chartsData.status && typeof window.statusChart.updateSeries === 'function') {
        try {
            const statusData = chartsData.status.data || [0, 0, 0, 0];
            
            // Update the donut chart
            window.statusChart.updateSeries(statusData);
            
            // Update the status numbers and progress bars below the chart
            updateStatusBarsFromChartData(statusData);
            
        } catch (error) {
            console.error('❌ Error updating status chart:', error);
        }
    }
}

// Update status bars from chart data to keep them in sync
function updateStatusBarsFromChartData(statusData) {
    // statusData = [pending, processing, completed, cancelled]
    const statusElements = {
        'statusPending': statusData[0] || 0,
        'statusProcessing': statusData[1] || 0, 
        'statusCompleted': statusData[2] || 0,
        'statusCancelled': statusData[3] || 0
    };
    
    // Update the numbers
    Object.keys(statusElements).forEach((elementId) => {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = statusElements[elementId];
        }
    });
    
    // Update the progress bars
    const total = statusData.reduce((sum, value) => sum + value, 0);
    
    if (total > 0) {
        const statusTypes = ['pending', 'processing', 'completed', 'cancelled'];
        statusTypes.forEach((status, index) => {
            const progressBar = document.getElementById(status + 'StatusProgress');
            if (progressBar) {
                const percentage = (statusData[index] / total) * 100;
                setTimeout(() => {
                    progressBar.style.width = percentage + '%';
                }, 300);
            }
        });
    } else {
        // If no data, reset all progress bars to 0%
        const statusTypes = ['pending', 'processing', 'completed', 'cancelled'];
        statusTypes.forEach((status) => {
            const progressBar = document.getElementById(status + 'StatusProgress');
            if (progressBar) {
                progressBar.style.width = '0%';
            }
        });
    }
}

// Show chart fallback
function showSimpleChartFallback() {
    const ordersContainer = document.getElementById('ordersChart');
    const statusContainer = document.getElementById('statusChart');
    
    if (ordersContainer) {
        ordersContainer.innerHTML = `
            <div class="text-center py-4">
                <i data-feather="bar-chart-2" style="width: 48px; height: 48px; margin-bottom: 1rem; color: #6c757d;"></i>
                <h6 class="text-muted">Chart Loading...</h6>
                <p class="text-muted small">Charts will appear when the library loads</p>
            </div>
        `;
    }
    
    if (statusContainer) {
        statusContainer.innerHTML = `
            <div class="text-center py-4">
                <i data-feather="pie-chart" style="width: 48px; height: 48px; margin-bottom: 1rem; color: #6c757d;"></i>
                <h6 class="text-muted">Chart Loading...</h6>
                <p class="text-muted small">Status chart will appear when ready</p>
            </div>
        `;
    }
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Refresh functions
window.refreshTopClients = function() {
    document.getElementById('topClientsContainer').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary mb-2" role="status"></div>
            <p class="text-muted">Loading top clients...</p>
        </div>
    `;
    loadTopClients();
};

window.refreshDashboard = function() {
    loadDashboardStats();
    loadTopClients();
    loadPerformanceMetrics();
    loadRecentActivity();
    
    if (typeof loadOrdersChartData === 'function') {
        const currentPeriod = document.getElementById('chartPeriod')?.value || 30;
        loadOrdersChartData(currentPeriod);
    }
    
    showToast('info', 'Dashboard refreshed successfully');
};

window.navigateToOverdueOrders = function() {
    navigateToTab('all');
            setTimeout(() => {
        showToast('info', 'Navigated to overdue orders');
    }, 500);
};

// Global sync function
window.syncDashboardWithGlobalFilter = function() {
    
    // Clear current stats
    const statElements = ['todayOrdersCount', 'tomorrowOrdersCount', 'pendingOrdersCount', 'weekOrdersCount'];
    statElements.forEach(elementId => {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = '...';
            element.style.opacity = '0.6';
        }
    });
    
    // Refresh all components
    loadDashboardStats();
    loadTopClients();
    loadPerformanceMetrics();
    loadRecentActivity();
    
    if (typeof loadOrdersChartData === 'function') {
        const currentPeriod = document.getElementById('chartPeriod')?.value || 30;
        loadOrdersChartData(currentPeriod);
    }
    
};

// Additional compatibility functions
window.forceDashboardRefresh = function() {
    setTimeout(() => {
        loadDashboardStats();
    }, 100);
};

window.onGlobalClientFilterChange = function() {
    window.syncDashboardWithGlobalFilter();
};

window.applyFilterToDashboard = function() {
    window.syncDashboardWithGlobalFilter();
};

window.syncDashboardFilters = function() {
    window.syncDashboardWithGlobalFilter();
};

// New dashboard functions
window.updateChartPeriod = function(period) {
    const labels = {
        7: '<?= lang('App.last_7_days') ?>',
        30: '<?= lang('App.last_30_days') ?>',
        90: '<?= lang('App.last_90_days') ?>'
    };
    
    document.getElementById('chartPeriodLabel').textContent = labels[period] || labels[30];
    
    if (typeof loadOrdersChartData === 'function') {
        loadOrdersChartData(period);
    }
    
    showToast('info', `Chart updated for ${labels[period]}`);
};

window.refreshCharts = function() {
    const currentPeriod = getCurrentChartPeriod();
    if (typeof loadOrdersChartData === 'function') {
        loadOrdersChartData(currentPeriod);
    }
    showToast('info', 'Charts refreshed');
};

window.refreshPerformanceMetrics = function() {
    loadPerformanceMetrics();
    showToast('info', 'Performance metrics refreshed');
};

window.refreshRecentActivity = function() {
    loadRecentActivity();
    showToast('info', 'Recent activity refreshed');
};

window.exportTopClients = function() {
    showToast('info', 'Export functionality coming soon');
};

function getCurrentChartPeriod() {
    const label = document.getElementById('chartPeriodLabel')?.textContent || '';
    if (label.includes('7')) return 7;
    if (label.includes('90')) return 90;
    return 30;
}

// Auto refresh functionality
let autoRefreshInterval = null;

function initializeAutoRefresh() {
    const autoRefreshSwitch = document.getElementById('autoRefreshActivity');
    if (autoRefreshSwitch) {
        autoRefreshSwitch.addEventListener('change', function() {
            if (this.checked) {
                startAutoRefresh();
            } else {
                stopAutoRefresh();
            }
        });
        
        // Start auto refresh if enabled
        if (autoRefreshSwitch.checked) {
            startAutoRefresh();
        }
    }
}

function startAutoRefresh() {
    stopAutoRefresh(); // Clear existing interval
    autoRefreshInterval = setInterval(() => {
        loadRecentActivity();
    }, 30000); // Refresh every 30 seconds
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
        autoRefreshInterval = null;
    }
}

// Initialize auto refresh when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        initializeAutoRefresh();
    }, 2000);
});

// Global functions
window.openNewOrderModal = function() {
    if (typeof window.openModalForNewOrder === 'function') {
        window.openModalForNewOrder();
    } else {
        console.warn('openModalForNewOrder function not available');
    }
};

window.deleteOrder = function(orderId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '<?= lang('App.are_you_sure') ?>',
            text: '<?= lang('App.confirm_delete_order') ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f06548',
            cancelButtonColor: '#74788d',
            confirmButtonText: '<?= lang('App.yes_delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`<?= base_url('sales_orders/delete/') ?>${orderId}`, {
                    method: 'POST',
        headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    })
                })
                .then(response => response.json())
    .then(data => {
                    if (data.success) {
                        showToast('success', data.message || 'Order deleted successfully');
                        loadDashboardStats();
        } else {
                        showToast('error', data.message || 'Error deleting order');
        }
    })
    .catch(error => {
                    showToast('error', 'Error deleting order');
                });
            }
        });
    }
};

// Toast function
// Toast function
function showToast(type, message) {
    if (typeof Toastify !== 'undefined') {
        const colors = {
            success: "#28a745",
            error: "#dc3545", 
            info: "#17a2b8",
            warning: "#ffc107"
        };
        
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            style: {
                background: colors[type] || colors.info,
            }
        }).showToast();
    }
}

// Filter listener initialization
window.initializeDashboardFilterListener = function() {
    
    if (!window.dashboardFilterWatcher) {
        window.dashboardFilterWatcher = setInterval(() => {
            const currentFilter = window.globalFilters?.client || '';
            const lastKnownFilter = window.lastKnownDashboardFilter || '';
            
            if (currentFilter !== lastKnownFilter) {
                window.lastKnownDashboardFilter = currentFilter;
                
                if (typeof window.syncDashboardWithGlobalFilter === 'function') {
                    window.syncDashboardWithGlobalFilter();
                }
            }
        }, 1000);
    }
    
    window.lastKnownDashboardFilter = window.globalFilters?.client || '';
};
</script>

