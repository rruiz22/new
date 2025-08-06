<div class="row">
    <!-- Today's Orders Widget -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate widget-clickable" data-tab="today" style="cursor: pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.today_orders') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge bg-soft-success text-success fs-12">
                            <i class="ri-arrow-up-line fs-13 align-middle"></i> +16.24%
                        </span>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="counter-value" id="todayOrdersCount" data-target="0">0</span>
                        </h4>
                        <p class="text-muted mb-0">
                            <span class="text-success fw-medium">
                                <i data-feather="calendar" class="icon-sm me-1"></i>
                                <?= date('M j, Y') ?>
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success rounded">
                            <i data-feather="calendar" class="text-white"></i>
                        </span>
                    </div>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">
                        <i data-feather="external-link" class="icon-xs me-1"></i>
                        Click to view today's orders
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tomorrow's Orders Widget -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate widget-clickable" data-tab="tomorrow" style="cursor: pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.tomorrow_orders') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge bg-soft-info text-info fs-12">
                            <i class="ri-arrow-up-line fs-13 align-middle"></i> +8.15%
                        </span>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="counter-value" id="tomorrowOrdersCount" data-target="0">0</span>
                        </h4>
                        <p class="text-muted mb-0">
                            <span class="text-info fw-medium">
                                <i data-feather="arrow-right-circle" class="icon-sm me-1"></i>
                                <?= date('M j, Y', strtotime('+1 day')) ?>
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info rounded">
                            <i data-feather="arrow-right-circle" class="text-white"></i>
                        </span>
                    </div>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">
                        <i data-feather="external-link" class="icon-xs me-1"></i>
                        Click to view tomorrow's orders
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Orders Widget -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate widget-clickable" data-tab="pending" style="cursor: pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.pending_orders') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge bg-soft-warning text-warning fs-12">
                            <i class="ri-alert-line fs-13 align-middle"></i> High
                        </span>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="counter-value" id="pendingOrdersCount" data-target="0">0</span>
                        </h4>
                        <p class="text-muted mb-0">
                            <span class="text-warning fw-medium">
                                <i data-feather="clock" class="icon-sm me-1"></i>
                                <?= lang('App.require_attention') ?>
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning rounded">
                            <i data-feather="clock" class="text-white"></i>
                        </span>
                    </div>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">
                        <i data-feather="external-link" class="icon-xs me-1"></i>
                        Click to view pending orders
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Week Orders Widget -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate widget-clickable" data-tab="week" style="cursor: pointer;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.week_view') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge bg-soft-secondary text-secondary fs-12">
                            <i class="ri-calendar-line fs-13 align-middle"></i> Week
                        </span>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <span class="counter-value" id="weekOrdersCount" data-target="0">0</span>
                        </h4>
                        <p class="text-muted mb-0">
                            <span class="text-secondary fw-medium">
                                <i data-feather="calendar" class="icon-sm me-1"></i>
                                <?= date('M j', strtotime('monday this week')) ?> - <?= date('M j', strtotime('sunday this week')) ?>
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-secondary rounded">
                            <i data-feather="calendar" class="text-white"></i>
                        </span>
                    </div>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">
                        <i data-feather="external-link" class="icon-xs me-1"></i>
                        Click to view week orders
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Orders Chart -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-0">
                            <i data-feather="trending-up" class="icon-sm me-2"></i>
                            <?= lang('App.orders_overview') ?>
                        </h4>
                        <p class="text-muted mb-0 small">Orders trend over time</p>
                    </div>
                    <div class="flex-shrink-0">
                        <select class="form-select form-select-sm" id="chartPeriod" style="width: auto;">
                            <option value="7">Last 7 Days</option>
                            <option value="30" selected>Last 30 Days</option>
                            <option value="90">Last 3 Months</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="ordersChart" style="height: 350px;" class="chart-container"></div>
                <div class="mt-3 text-center">
                    <button class="btn btn-outline-primary btn-sm" onclick="navigateToAllOrders()">
                        <i data-feather="list" class="icon-xs me-1"></i>
                        View All Orders
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution Chart -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i data-feather="pie-chart" class="icon-sm me-2"></i>
                    <?= lang('App.orders_by_status') ?>
                </h4>
                <p class="text-muted mb-0 small">Current status distribution</p>
            </div>
            <div class="card-body">
                <div id="statusChart" style="height: 280px;" class="chart-container"></div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center py-2 status-item" data-status="pending" style="cursor: pointer;" title="Click to view pending orders">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2">
                                <span class="avatar-title rounded-circle bg-primary">P</span>
                            </div>
                            <span class="text-muted">Pending</span>
                        </div>
                        <span class="fw-semibold" id="statusPending">0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 status-item" data-status="processing" style="cursor: pointer;" title="Click to view processing orders">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2">
                                <span class="avatar-title rounded-circle bg-warning">R</span>
                            </div>
                            <span class="text-muted">Processing</span>
                        </div>
                        <span class="fw-semibold" id="statusProcessing">0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 status-item" data-status="completed" style="cursor: pointer;" title="Click to view completed orders">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2">
                                <span class="avatar-title rounded-circle bg-success">C</span>
                            </div>
                            <span class="text-muted">Completed</span>
                        </div>
                        <span class="fw-semibold" id="statusCompleted">0</span>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <button class="btn btn-outline-secondary btn-sm" onclick="navigateToAllOrders()">
                        <i data-feather="filter" class="icon-xs me-1"></i>
                        Filter by Status
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Clients Widget -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-0">
                            <i data-feather="users" class="icon-sm me-2"></i>
                            <?= lang('App.top_clients') ?>
                        </h4>
                        <p class="text-muted mb-0 small">Clients with most orders this month</p>
                    </div>
                <div class="flex-shrink-0">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshTopClients()">
                            <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                            Refresh
                    </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="topClientsContainer">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary mb-2" role="status"></div>
                        <p class="text-muted">Loading top clients...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics Widget -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i data-feather="trending-up" class="icon-sm me-2"></i>
                    Performance Metrics
                </h4>
                <p class="text-muted mb-0 small">Order completion statistics</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- On Time Orders -->
                    <div class="col-md-6">
                        <div class="performance-metric on-time">
                            <div class="metric-icon">
                                <i data-feather="clock" class="text-success"></i>
                            </div>
                            <div class="metric-content">
                                <h3 class="metric-value" id="onTimePercentage">--</h3>
                                <p class="metric-label">On Time Completion</p>
                                <div class="metric-progress">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" id="onTimeProgressBar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delayed Orders -->
                    <div class="col-md-6">
                        <div class="performance-metric delayed">
                            <div class="metric-icon">
                                <i data-feather="alert-triangle" class="text-warning"></i>
                            </div>
                            <div class="metric-content">
                                <h3 class="metric-value" id="delayedPercentage">--</h3>
                                <p class="metric-label">Delayed Completion</p>
                                <div class="metric-progress">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-warning" id="delayedProgressBar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Metrics Row -->
                <div class="row mt-4">
                    <!-- Average Completion Time -->
                    <div class="col-md-6">
                        <div class="metric-box">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-info rounded">
                                        <i data-feather="clock" class="text-white"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="metric-number mb-1" id="avgCompletionTime">-- hours</h5>
                                    <p class="text-muted mb-0 small">Average Completion Time</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Overdue Orders -->
                    <div class="col-md-6">
                        <div class="metric-box">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-danger rounded">
                                        <i data-feather="alert-circle" class="text-white"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="metric-number mb-1" id="overdueCount">--</h5>
                                    <p class="text-muted mb-0 small">Currently Overdue</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third Metrics Row -->
                <div class="row mt-3">
                    <!-- Completion Rate -->
                    <div class="col-md-6">
                        <div class="metric-box">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-success rounded">
                                        <i data-feather="check-circle" class="text-white"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="metric-number mb-1" id="completionRate">--%</h5>
                                    <p class="text-muted mb-0 small">Completion Rate</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cancellation Rate -->
                    <div class="col-md-6">
                        <div class="metric-box">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-secondary rounded">
                                        <i data-feather="x-circle" class="text-white"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="metric-number mb-1" id="cancellationRate">--%</h5>
                                    <p class="text-muted mb-0 small">Cancellation Rate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions Widget -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i data-feather="zap" class="icon-sm me-2"></i>
                    Quick Actions
                </h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" onclick="openNewOrderModal()">
                        <i data-feather="plus" class="icon-sm me-2"></i>
                        New Sales Order
                    </button>
                    <button class="btn btn-outline-info" onclick="navigateToAllOrders()">
                        <i data-feather="list" class="icon-sm me-2"></i>
                        View All Orders
                    </button>
                    <button class="btn btn-outline-warning" onclick="navigateToOverdueOrders()">
                        <i data-feather="alert-triangle" class="icon-sm me-2"></i>
                        Check Overdue Orders
                    </button>
                    <button class="btn btn-outline-secondary" onclick="refreshDashboard()">
                        <i data-feather="refresh-cw" class="icon-sm me-2"></i>
                        Refresh Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Widget -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i data-feather="activity" class="icon-sm me-2"></i>
                    Recent Activity
                </h4>
                <p class="text-muted mb-0 small">Latest order updates and changes</p>
            </div>
            <div class="card-body">
                <div id="recentActivityContainer">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary mb-2" role="status"></div>
                        <p class="text-muted">Loading recent activity...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard specific styles */
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
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-color: #28a745;
}

.performance-metric.delayed {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    background: linear-gradient(135deg, #f7b84b 0%, #f06548 100%);
}

.client-rank.silver {
    background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
}

.client-rank.bronze {
    background: linear-gradient(135deg, #cd7f32 0%, #8b4513 100%);
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

// Add click handlers for status items
function addStatusClickHandlers() {
    const statusItems = document.querySelectorAll('.status-item');
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
        showToast('info', `Switched to ${tabName.charAt(0).toUpperCase() + tabName.slice(1)} Orders`);
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
            showToast('info', `Filtered orders by status: ${status.charAt(0).toUpperCase() + status.slice(1)}`);
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
            updateDashboardStats(data.stats);
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
        'todayOrdersCount': stats.today || 0,
        'tomorrowOrdersCount': stats.tomorrow || 0,
        'pendingOrdersCount': stats.pending || 0,
        'weekOrdersCount': stats.week || 0
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
        }
    });
    
    // Update status breakdown
    if (stats.status_breakdown) {
        const statusElements = {
            'statusPending': stats.status_breakdown.pending || 0,
            'statusProcessing': stats.status_breakdown.processing || 0,
            'statusCompleted': stats.status_breakdown.completed || 0
        };
        
        Object.keys(statusElements).forEach((elementId) => {
            const element = document.getElementById(elementId);
            if (element) {
                    element.textContent = statusElements[elementId];
            }
        });
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
            displayTopClients(data.clients || []);
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
                    <div class="client-name">${client.client_name || 'Unknown Client'}</div>
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
            displayPerformanceMetrics(data.metrics || {});
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
            displayRecentActivity(data.activities || []);
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
        const iconClass = getActivityIconClass(activity.action);
        const icon = getActivityIcon(activity.action);
        
        html += `
            <div class="activity-item">
                <div class="activity-icon ${iconClass}">
                    <i data-feather="${icon}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">${activity.title || 'Order Update'}</div>
                    <div class="activity-description">${activity.description || 'No description'}</div>
                    <div class="activity-time">${activity.time_ago || 'Unknown time'}</div>
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
    
    // Update status chart only if it exists and has the method
    if (window.statusChart && chartsData.status && typeof window.statusChart.updateSeries === 'function') {
        try {
            window.statusChart.updateSeries(chartsData.status.data || [0, 0, 0, 0]);
        } catch (error) {
            console.error('❌ Error updating status chart:', error);
        }
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

