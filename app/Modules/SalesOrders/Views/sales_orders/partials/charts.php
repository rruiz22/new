<!-- Sales Orders Analytics Charts -->
<div class="row mb-4" id="salesAnalyticsCharts">
    <!-- Order Trends Chart -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title mb-1 text-dark fw-bold">
                        <i data-feather="trending-up" class="icon-sm me-2 text-primary"></i>
                        <?= lang('App.order_trends') ?>
                    </h5>
                    <p class="card-subtitle text-muted mb-0"><?= lang('App.last_30_days_analytics') ?></p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-soft-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i data-feather="calendar" class="icon-xs me-1"></i>
                        <span id="trendsDateRange">Last 30 Days</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" data-range="7">Last 7 Days</a></li>
                        <li><a class="dropdown-item active" href="#" data-range="30">Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="#" data-range="90">Last 90 Days</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" data-range="custom">Custom Range</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="orderTrendsChart" style="height: 350px;"></div>
                <div class="d-none" id="trendsLoading">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading trends...</span>
                        </div>
                        <p class="text-muted mt-2">Loading analytics...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Distribution -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-1 text-dark fw-bold">
                    <i data-feather="pie-chart" class="icon-sm me-2 text-success"></i>
                    <?= lang('App.status_distribution') ?>
                </h5>
                <p class="card-subtitle text-muted mb-0"><?= lang('App.current_orders_breakdown') ?></p>
            </div>
            <div class="card-body">
                <div id="statusDistributionChart" style="height: 300px;"></div>
                <div class="status-legend mt-3">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="legend-item">
                            <span class="legend-color bg-success"></span>
                            <small class="text-muted"><?= lang('App.completed') ?></small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color bg-warning"></span>
                            <small class="text-muted"><?= lang('App.in_progress') ?></small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color bg-info"></span>
                            <small class="text-muted"><?= lang('App.pending') ?></small>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color bg-danger"></span>
                            <small class="text-muted"><?= lang('App.cancelled') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Performance Row -->
<div class="row mb-4">
    <!-- Top Services Chart -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-1 text-dark fw-bold">
                    <i data-feather="bar-chart-2" class="icon-sm me-2 text-info"></i>
                    <?= lang('App.top_services') ?>
                </h5>
                <p class="card-subtitle text-muted mb-0"><?= lang('App.most_requested_services') ?></p>
            </div>
            <div class="card-body">
                <div id="topServicesChart" style="height: 280px;"></div>
            </div>
        </div>
    </div>

    <!-- Performance KPIs -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-1 text-dark fw-bold">
                    <i data-feather="activity" class="icon-sm me-2 text-warning"></i>
                    <?= lang('App.performance_kpis') ?>
                </h5>
                <p class="card-subtitle text-muted mb-0"><?= lang('App.key_performance_indicators') ?></p>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="kpi-item">
                            <div class="d-flex align-items-center">
                                <div class="kpi-icon bg-success-subtle text-success me-3">
                                    <i data-feather="clock" class="icon-sm"></i>
                                </div>
                                <div>
                                    <h4 class="kpi-value mb-1" id="avgCompletionTime">0h</h4>
                                    <p class="kpi-label mb-0">Average Completion</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: 85%"></div>
                                </div>
                                <small class="text-success">15% faster than last month</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="kpi-item">
                            <div class="d-flex align-items-center">
                                <div class="kpi-icon bg-info-subtle text-info me-3">
                                    <i data-feather="star" class="icon-sm"></i>
                                </div>
                                <div>
                                    <h4 class="kpi-value mb-1" id="customerSatisfaction">0.0</h4>
                                    <p class="kpi-label mb-0">Customer Rating</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-info" style="width: 96%"></div>
                                </div>
                                <small class="text-info">Excellent performance</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="kpi-item">
                            <div class="d-flex align-items-center">
                                <div class="kpi-icon bg-primary-subtle text-primary me-3">
                                    <i data-feather="dollar-sign" class="icon-sm"></i>
                                </div>
                                <div>
                                    <h4 class="kpi-value mb-1" id="monthlyRevenue">$0</h4>
                                    <p class="kpi-label mb-0">Monthly Revenue</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: 78%"></div>
                                </div>
                                <small class="text-primary">22% to target</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="kpi-item">
                            <div class="d-flex align-items-center">
                                <div class="kpi-icon bg-warning-subtle text-warning me-3">
                                    <i data-feather="zap" class="icon-sm"></i>
                                </div>
                                <div>
                                    <h4 class="kpi-value mb-1" id="efficiency">0%</h4>
                                    <p class="kpi-label mb-0">Efficiency Rate</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: 92%"></div>
                                </div>
                                <small class="text-warning">Above industry average</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Chart Container Enhancements */
#salesAnalyticsCharts .card {
    border-radius: 12px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}

#salesAnalyticsCharts .card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

/* Chart Headers */
#salesAnalyticsCharts .card-header {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    border-bottom: 1px solid #e9ecef;
    padding: 1.25rem;
}

#salesAnalyticsCharts .card-title {
    font-size: 1.1rem;
    font-weight: 600;
}

#salesAnalyticsCharts .card-subtitle {
    font-size: 0.875rem;
}

/* Status Legend */
.status-legend .legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

/* KPI Items */
.kpi-item {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.kpi-item:hover {
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transform: translateY(-1px);
}

.kpi-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.kpi-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.progress-sm {
    height: 4px;
    border-radius: 2px;
    background: #e5e7eb;
    margin-bottom: 0.5rem;
}

.progress-sm .progress-bar {
    border-radius: 2px;
}

/* Chart Loading States */
.chart-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 300px;
    background: #f8f9fa;
    border-radius: 8px;
}

/* Dropdown Customization */
.dropdown-item.active {
    background-color: #4A90E2;
    color: white;
}

.dropdown-item:hover {
    background-color: #e7f3ff;
}

/* Responsive Design */
@media (max-width: 768px) {
    #salesAnalyticsCharts .card-header {
        padding: 1rem;
    }
    
    .kpi-item {
        padding: 0.75rem;
        margin-bottom: 0.5rem;
    }
    
    .kpi-value {
        font-size: 1.25rem;
    }
    
    .kpi-icon {
        width: 32px;
        height: 32px;
    }
    
    .status-legend {
        margin-top: 1rem !important;
    }
    
    .status-legend .d-flex {
        flex-direction: column !important;
        gap: 0.5rem !important;
    }
}

/* Animation for loading charts */
@keyframes chartFadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chart-loaded {
    animation: chartFadeIn 0.5s ease-out;
}
</style>