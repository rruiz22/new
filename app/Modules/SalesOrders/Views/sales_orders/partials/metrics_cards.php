<!-- Sales Orders Metrics Cards -->
<div class="row mb-4" id="salesOrdersMetrics">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary border-primary overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="text-white mb-1">
                            <span id="totalOrdersCount">
                                <div class="spinner-border spinner-border-sm text-white" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </span>
                        </h5>
                        <p class="text-white-75 mb-2 fw-semibold"><?= lang('App.total_orders') ?></p>
                        <!-- Sparkline Chart -->
                        <div id="totalOrdersSparkline" class="sparkline" style="height: 40px;"></div>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-white bg-opacity-20 text-white">
                            <span class="avatar-title">
                                <i data-feather="file-text" class="icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <span class="text-white-75 small me-2">Last 7 days:</span>
                    <span class="text-white small fw-medium" id="totalOrdersChange">+12%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning border-warning overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="text-white mb-1">
                            <span id="pendingOrdersCount">
                                <div class="spinner-border spinner-border-sm text-white" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </span>
                        </h5>
                        <p class="text-white-75 mb-2 fw-semibold"><?= lang('App.pending_orders') ?></p>
                        <!-- Sparkline Chart -->
                        <div id="pendingOrdersSparkline" class="sparkline" style="height: 40px;"></div>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-white bg-opacity-20 text-white">
                            <span class="avatar-title">
                                <i data-feather="clock" class="icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <span class="text-white-75 small me-2">Last 7 days:</span>
                    <span class="text-white small fw-medium" id="pendingOrdersChange">-8%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-info border-info overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="text-white mb-1">
                            <span id="inProgressOrdersCount">
                                <div class="spinner-border spinner-border-sm text-white" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </span>
                        </h5>
                        <p class="text-white-75 mb-2 fw-semibold"><?= lang('App.in_progress_orders') ?></p>
                        <!-- Sparkline Chart -->
                        <div id="inProgressOrdersSparkline" class="sparkline" style="height: 40px;"></div>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-white bg-opacity-20 text-white">
                            <span class="avatar-title">
                                <i data-feather="play-circle" class="icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <span class="text-white-75 small me-2">Last 7 days:</span>
                    <span class="text-white small fw-medium" id="inProgressOrdersChange">+24%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-success border-success overflow-hidden">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="text-white mb-1">
                            <span id="completedOrdersCount">
                                <div class="spinner-border spinner-border-sm text-white" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </span>
                        </h5>
                        <p class="text-white-75 mb-2 fw-semibold"><?= lang('App.completed_orders') ?></p>
                        <!-- Sparkline Chart -->
                        <div id="completedOrdersSparkline" class="sparkline" style="height: 40px;"></div>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-white bg-opacity-20 text-white">
                            <span class="avatar-title">
                                <i data-feather="check-circle" class="icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-2">
                    <span class="text-white-75 small me-2">Last 7 days:</span>
                    <span class="text-white small fw-medium" id="completedOrdersChange">+18%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Row -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1"><?= lang('App.quick_actions') ?></h6>
                        <p class="card-subtitle text-muted mb-0"><?= lang('App.manage_sales_orders_efficiently') ?></p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-primary btn-sm" id="addOrderBtn">
                            <i data-feather="plus" class="icon-xs me-1"></i>
                            <span class="d-none d-sm-inline"><?= lang('App.add_order') ?></span>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" id="exportBtn">
                            <i data-feather="download" class="icon-xs me-1"></i>
                            <span class="d-none d-sm-inline"><?= lang('App.export') ?></span>
                        </button>
                        <button class="btn btn-outline-info btn-sm" id="refreshBtn">
                            <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                            <span class="d-none d-sm-inline"><?= lang('App.refresh') ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Metrics Cards Enhancements */
#salesOrdersMetrics .card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

#salesOrdersMetrics .card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: rgba(255,255,255,0.3);
}

#salesOrdersMetrics .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

#salesOrdersMetrics .avatar-sm {
    width: 48px;
    height: 48px;
}

#salesOrdersMetrics .avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

#salesOrdersMetrics h5 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.25rem !important;
}

#salesOrdersMetrics .text-white-75 {
    color: rgba(255,255,255,0.85) !important;
    font-size: 0.9rem;
}

/* Quick Actions Card */
.card-subtitle {
    font-size: 0.875rem;
}

.btn-sm {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Loading Animation */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.125em;
}

/* Responsive Design */
@media (max-width: 768px) {
    #salesOrdersMetrics h5 {
        font-size: 1.5rem;
    }
    
    #salesOrdersMetrics .avatar-sm {
        width: 40px;
        height: 40px;
    }
    
    #salesOrdersMetrics .text-white-75 {
        font-size: 0.8rem;
    }
}

/* Hover Effects for Cards */
#salesOrdersMetrics .bg-primary:hover {
    background: #3b82f6 !important;
}

#salesOrdersMetrics .bg-warning:hover {
    background: #f59e0b !important;
}

#salesOrdersMetrics .bg-info:hover {
    background: #06b6d4 !important;
}

#salesOrdersMetrics .bg-success:hover {
    background: #10b981 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSalesOrdersMetrics();
    
    // Refresh button functionality
    document.getElementById('refreshBtn').addEventListener('click', function() {
        const btn = this;
        const icon = btn.querySelector('i');
        
        // Add spinning animation
        icon.style.animation = 'spin 1s linear infinite';
        btn.disabled = true;
        
        loadSalesOrdersMetrics().finally(() => {
            icon.style.animation = '';
            btn.disabled = false;
        });
    });
});

async function loadSalesOrdersMetrics() {
    try {
        const response = await fetch('<?= base_url('sales_orders/getMetrics') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();
        
        if (data.success) {
            // Animate numbers counting up
            animateNumber('totalOrdersCount', data.metrics.total);
            animateNumber('pendingOrdersCount', data.metrics.pending);
            animateNumber('inProgressOrdersCount', data.metrics.in_progress);
            animateNumber('completedOrdersCount', data.metrics.completed);
            
            // Load sparkline charts
            loadSparklines(data.sparklines || {});
        } else {
            console.error('Error loading metrics:', data.message);
            setMetricError();
        }
    } catch (error) {
        console.error('Failed to load metrics:', error);
        setMetricError();
    }
}

function animateNumber(elementId, targetValue) {
    const element = document.getElementById(elementId);
    const currentValue = 0;
    const increment = Math.ceil(targetValue / 20); // 20 steps animation
    let current = 0;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= targetValue) {
            current = targetValue;
            clearInterval(timer);
        }
        element.textContent = current.toLocaleString();
    }, 50);
}

function setMetricError() {
    const elements = ['totalOrdersCount', 'pendingOrdersCount', 'inProgressOrdersCount', 'completedOrdersCount'];
    elements.forEach(id => {
        document.getElementById(id).innerHTML = '<i data-feather="alert-circle" class="icon-sm"></i>';
        feather.replace();
    });
}

function loadSparklines(sparklineData) {
    // Create sparkline charts for each metric
    const sparklines = [
        { id: 'totalOrdersSparkline', data: sparklineData.total || [5, 9, 5, 6, 4, 12, 18, 14, 10, 15, 12, 6, 8, 21] },
        { id: 'pendingOrdersSparkline', data: sparklineData.pending || [3, 6, 4, 8, 10, 6, 8, 12, 10, 4, 7, 9, 5, 3] },
        { id: 'inProgressOrdersSparkline', data: sparklineData.inProgress || [2, 8, 6, 10, 13, 15, 16, 18, 20, 22, 18, 24, 20, 30] },
        { id: 'completedOrdersSparkline', data: sparklineData.completed || [12, 14, 16, 18, 15, 19, 22, 26, 24, 25, 28, 30, 32, 35] }
    ];

    sparklines.forEach(sparkline => {
        const element = document.getElementById(sparkline.id);
        if (!element) return;

        const options = {
            series: [{
                name: 'Orders',
                data: sparkline.data
            }],
            chart: {
                type: 'area',
                height: 40,
                sparkline: {
                    enabled: true
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'solid',
                    /* Solid color fill */
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            colors: ['rgba(255, 255, 255, 0.8)'],
            tooltip: {
                enabled: true,
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function () {
                            return ''
                        }
                    },
                    formatter: function (value) {
                        return value + ' orders'
                    }
                },
                marker: {
                    show: false
                }
            }
        };

        try {
            const chart = new ApexCharts(element, options);
            chart.render();
            element.classList.add('chart-loaded');
        } catch (error) {
            console.error('Failed to create sparkline:', error);
            element.innerHTML = '<div class="sparkline-error">Chart error</div>';
        }
    });
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</script>