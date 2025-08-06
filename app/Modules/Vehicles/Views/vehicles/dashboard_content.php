<!-- Main Dashboard Statistics Cards -->
<?php include(__DIR__ . '/shared_styles.php'); ?>

<style>
/* Ensure white backgrounds for all dashboard content cards */
.card-animate {
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1px solid #e9ecef !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
}

.card-animate .card-body {
    background-color: #ffffff !important;
    background-image: none !important;
}

.card-height-100 {
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1px solid #e9ecef !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
}

.card-height-100 .card-body {
    background-color: #ffffff !important;
    background-image: none !important;
}

.card-height-100 .card-header {
    background-color: #ffffff !important;
    background-image: none !important;
    border-bottom: 1px solid #e9ecef !important;
}
</style>

<div class="row">
    <div class="col-xl-3 col-lg-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.total_vehicles') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <h5 class="text-success fs-14 mb-0">
                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +<?= lang('App.growth_rate') ?>
                        </h5>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="totalVehiclesCount">0</h4>
                        <a href="#" class="text-decoration-underline" onclick="showTab('all-vehicles-tab')"><?= lang('App.view_details') ?></a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i data-feather="truck" class="text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.active_vehicles') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <h5 class="text-success fs-14 mb-0">
                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +<?= lang('App.weekly_growth') ?>
                        </h5>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="activeVehiclesCount">0</h4>
                        <a href="#" class="text-decoration-underline" onclick="showTab('active-vehicles-tab')"><?= lang('App.view_details') ?></a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i data-feather="activity" class="text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.location_tracked') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <h5 class="text-info fs-14 mb-0">
                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +<?= lang('App.tracking_growth') ?>
                        </h5>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="locationTrackedCount">0</h4>
                        <a href="#" class="text-decoration-underline" onclick="showTab('location-tracking-tab')"><?= lang('App.view_details') ?></a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info-subtle rounded fs-3">
                            <i data-feather="map-pin" class="text-info"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><?= lang('App.total_services') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <h5 class="text-warning fs-14 mb-0">
                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +<?= lang('App.service_growth') ?>
                        </h5>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="totalServicesCount">0</h4>
                        <a href="#" class="text-decoration-underline" onclick="showTab('analytics-tab')"><?= lang('App.view_details') ?></a>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                                                            <i data-feather="settings" class="text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row with Additional Metrics -->
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="clock" class="icon-sm me-1"></i>
                    <?= lang('App.recent_vehicle_activity') ?>
                </h4>
                <div class="flex-shrink-0">
                    <button class="btn btn-outline-secondary btn-sm" onclick="refreshRecentActivity()">
                        <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><?= lang('App.vehicle') ?></th>
                                <th scope="col"><?= lang('App.vin') ?></th>
                                <th scope="col"><?= lang('App.client') ?></th>
                                <th scope="col"><?= lang('App.last_service') ?></th>
                                <th scope="col"><?= lang('App.services_count') ?></th>
                                <th scope="col"><?= lang('App.location_status') ?></th>
                                <th scope="col"><?= lang('App.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id="recentActivityTableBody">
                            <tr>
                                <td colspan="7" class="text-center"><?= lang('App.loading') ?>...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card card-height-100">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="pie-chart" class="icon-sm me-1"></i>
                    <?= lang('App.vehicle_distribution') ?>
                </h4>
            </div>
            <div class="card-body">
                <div id="vehicleDistributionChart" class="chart-container"></div>
            </div>
        </div>
    </div>
</div>

<!-- Third Row with Quick Stats and More Charts -->
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="trending-up" class="icon-sm me-1"></i>
                    <?= lang('App.quick_statistics') ?>
                </h4>
            </div>
            <div class="card-body">
                <div class="quick-stats-grid">
                    <div class="quick-stat-item">
                        <div class="quick-stat-value" id="averageServicesPerVehicle">0</div>
                        <div class="quick-stat-label"><?= lang('App.avg_services_per_vehicle') ?></div>
                    </div>
                    <div class="quick-stat-item">
                        <div class="quick-stat-value" id="mostPopularMake">-</div>
                        <div class="quick-stat-label"><?= lang('App.most_popular_make') ?></div>
                    </div>
                    <div class="quick-stat-item">
                        <div class="quick-stat-value" id="newestVehicleYear">-</div>
                        <div class="quick-stat-label"><?= lang('App.newest_vehicle_year') ?></div>
                    </div>
                    <div class="quick-stat-item">
                        <div class="quick-stat-value" id="locationTrackingPercentage">0%</div>
                        <div class="quick-stat-label"><?= lang('App.location_tracking_coverage') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="bar-chart" class="icon-sm me-1"></i>
                    <?= lang('App.services_by_month') ?>
                </h4>
            </div>
            <div class="card-body">
                <div id="servicesByMonthChart" class="chart-container"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="users" class="icon-sm me-1"></i>
                    <?= lang('App.top_clients') ?>
                </h4>
            </div>
            <div class="card-body">
                <div id="topClientsContainer">
                    <div class="text-center text-muted">
                        <i data-feather="loader" class="icon-sm me-1 spin-icon"></i>
                        <?= lang('App.loading_client_data') ?>...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Dashboard Content JavaScript
window.updateVehicleStats = function(data) {
    // Update main statistics
    $('#totalVehiclesCount').text(data.totalVehicles || 0);
    $('#activeVehiclesCount').text(data.activeVehicles || 0);
    $('#locationTrackedCount').text(data.locationTracked || 0);
    $('#totalServicesCount').text(data.totalServices || 0);
    
    // Update quick statistics
    $('#averageServicesPerVehicle').text(data.averageServices || '0');
    $('#mostPopularMake').text(data.mostPopularMake || '-');
    $('#newestVehicleYear').text(data.newestYear || '-');
    $('#locationTrackingPercentage').text((data.locationTrackingPercentage || 0) + '%');
    
    // Load recent activity table
    loadRecentActivity(data.recentActivity || []);
    
    // Load top clients
    loadTopClients(data.topClients || []);
    
    // Initialize charts
    initializeCharts(data);
};

function loadRecentActivity(activities) {
    const tbody = $('#recentActivityTableBody');
    if (!activities || activities.length === 0) {
        tbody.html('<tr><td colspan="7" class="text-center text-muted"><?= lang('App.no_recent_activity') ?></td></tr>');
        return;
    }
    
    tbody.html(activities.map(activity => `
        <tr class="location-row" onclick="viewVehicle('${activity.vin_last6}')">
            <td>
                <div class="text-center">
                    <h6 class="mb-1 font-size-14">
                        <i data-feather="truck" class="icon-xs text-primary me-1"></i>
                        ${activity.vehicle || 'Unknown Vehicle'}
                    </h6>
                </div>
            </td>
            <td>
                <div class="text-center">
                    <span class="vehicle-vin-code">${activity.vin_last6}</span>
                </div>
            </td>
            <td>
                <div class="text-center">
                    <span class="fw-medium">${activity.client_name || 'N/A'}</span>
                </div>
            </td>
            <td>
                <div class="text-center">
                    <div class="fw-medium">${activity.last_service_date || 'N/A'}</div>
                    <small class="text-muted">${activity.last_order_number || ''}</small>
                </div>
            </td>
            <td>
                <div class="text-center">
                    <span class="vehicle-service-count">${activity.total_services || 0}</span>
                </div>
            </td>
            <td>
                <div class="text-center">
                    ${activity.has_location_tracking ? 
                        '<span class="location-tracking-badge"><i data-feather="map-pin" class="icon-xs me-1"></i><?= lang('App.tracked') ?></span>' : 
                        '<span class="badge bg-light text-muted"><?= lang('App.not_tracked') ?></span>'
                    }
                </div>
            </td>
            <td>
                <div class="service-order-action-buttons">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle btn btn-sm btn-outline-secondary service-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="more-horizontal" class="icon-xs"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('vehicles/') ?>${activity.vin_last6}">
                                    <i data-feather="eye" class="icon-xs align-bottom me-2 text-muted"></i>
                                    <?= lang('App.view_details') ?>
                                </a>
                            </li>
                            ${activity.has_location_tracking ? `
                                <li>
                                    <a class="dropdown-item" href="#" onclick="showLocationHistory('${activity.vin_last6}')">
                                        <i data-feather="map-pin" class="icon-xs align-bottom me-2 text-muted"></i>
                                        <?= lang('App.location_history') ?>
                                    </a>
                                </li>
                            ` : ''}
                        </ul>
                    </div>
                </div>
            </td>
        </tr>
    `).join(''));
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function loadTopClients(clients) {
    const container = $('#topClientsContainer');
    if (!clients || clients.length === 0) {
        container.html('<div class="text-center text-muted"><?= lang('App.no_client_data') ?></div>');
        return;
    }
    
    container.html(clients.map((client, index) => `
        <div class="d-flex justify-content-between align-items-center py-2 ${index < clients.length - 1 ? 'border-bottom' : ''}">
            <div class="d-flex align-items-center">
                <div class="avatar-xs bg-primary-subtle rounded-circle me-2">
                    <span class="avatar-title text-primary font-size-12">${index + 1}</span>
                </div>
                <div>
                    <h6 class="mb-0 font-size-14">${client.name}</h6>
                    <small class="text-muted">${client.vehicle_count} ${client.vehicle_count === 1 ? '<?= lang('App.vehicle') ?>' : '<?= lang('App.vehicles') ?>'}</small>
                </div>
            </div>
            <div class="text-end">
                <span class="fw-medium">${client.total_services}</span>
                <div><small class="text-muted"><?= lang('App.services') ?></small></div>
            </div>
        </div>
    `).join(''));
}

function initializeCharts(data) {
    // Initialize vehicle distribution chart
    if (data.vehicleDistribution) {
        initVehicleDistributionChart(data.vehicleDistribution);
    }
    
    // Initialize services by month chart
    if (data.servicesByMonth) {
        initServicesByMonthChart(data.servicesByMonth);
    }
}

function initVehicleDistributionChart(data) {
    // Implementation for vehicle distribution pie chart
    // This would use a charting library like Chart.js or ApexCharts
    console.log('Initializing vehicle distribution chart with data:', data);
}

function initServicesByMonthChart(data) {
    // Implementation for services by month bar chart
    console.log('Initializing services by month chart with data:', data);
}

function refreshRecentActivity() {
    $('#recentActivityTableBody').html('<tr><td colspan="7" class="text-center"><i data-feather="loader" class="icon-sm me-1 spin-icon"></i> <?= lang('App.refreshing') ?>...</td></tr>');
    
    // Reload dashboard data
    if (window.loadDashboardData) {
        window.loadDashboardData();
    }
}

function viewVehicle(vinLast6) {
    window.location.href = `<?= base_url('vehicles/') ?>${vinLast6}`;
}

function showLocationHistory(vinLast6) {
    // Show location tracking tab and filter by specific vehicle
    showTab('location-tracking-tab');
    // Additional filtering logic would go here
}

// Add CSS for spin animation
if (!document.getElementById('spin-animation-style')) {
    const style = document.createElement('style');
    style.id = 'spin-animation-style';
    style.textContent = `
        .spin-icon {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
}
</script> 