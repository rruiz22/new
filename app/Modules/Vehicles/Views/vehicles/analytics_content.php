<?php include(__DIR__ . '/shared_styles.php'); ?>

<style>
/* White backgrounds for analytics content */
.card {
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1px solid #e9ecef !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
}

.card-header {
    background-color: #ffffff !important;
    background-image: none !important;
    border-bottom: 1px solid #e9ecef !important;
}

.card-body {
    background-color: #ffffff !important;
    background-image: none !important;
}

.chart-container {
    background-color: #ffffff !important;
    background-image: none !important;
}

.quick-stat-item {
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1px solid #e9ecef !important;
}
</style>

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="pie-chart" class="icon-sm me-1"></i>
                    <?= lang('App.vehicle_make_distribution') ?>
                </h4>
            </div>
            <div class="card-body">
                <div id="vehicleMakeChart" class="chart-container"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="bar-chart" class="icon-sm me-1"></i>
                    <?= lang('App.services_per_vehicle_distribution') ?>
                </h4>
            </div>
            <div class="card-body">
                <div id="servicesDistributionChart" class="chart-container"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="trending-up" class="icon-sm me-1"></i>
                    <?= lang('App.monthly_vehicle_additions') ?>
                </h4>
            </div>
            <div class="card-body">
                <div id="monthlyAdditionsChart" class="chart-container"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="award" class="icon-sm me-1"></i>
                    <?= lang('App.top_performing_vehicles') ?>
                </h4>
            </div>
            <div class="card-body">
                <div id="topVehiclesContainer">
                    <div class="text-center text-muted">
                        <i data-feather="loader" class="icon-sm me-1 spin-icon"></i>
                        <?= lang('App.loading_analytics_data') ?>...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 vehicles-dashboard-card-title">
                    <i data-feather="activity" class="icon-sm me-1"></i>
                    <?= lang('App.vehicle_analytics_summary') ?>
                </h4>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="quick-stat-item">
                            <div class="quick-stat-value" id="analyticsAverageAge">-</div>
                            <div class="quick-stat-label"><?= lang('App.average_vehicle_age') ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="quick-stat-item">
                            <div class="quick-stat-value" id="analyticsServiceFrequency">-</div>
                            <div class="quick-stat-label"><?= lang('App.avg_service_frequency') ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="quick-stat-item">
                            <div class="quick-stat-value" id="analyticsLocationCoverage">-</div>
                            <div class="quick-stat-label"><?= lang('App.location_coverage_rate') ?></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="quick-stat-item">
                            <div class="quick-stat-value" id="analyticsClientRetention">-</div>
                            <div class="quick-stat-label"><?= lang('App.client_retention_rate') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Analytics Content JavaScript
window.refreshAnalyticsCharts = function() {
    loadAnalyticsData();
};

function loadAnalyticsData() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.warn('jQuery not available for analytics data loading');
        return;
    }
    
    // Load analytics data and initialize charts
    $.get('<?= base_url('vehicles/analytics-data') ?>', function(data) {
        if (data.success) {
            updateAnalyticsStats(data);
            initializeAnalyticsCharts(data);
        }
    }).fail(function() {
        console.error('Failed to load analytics data');
    });
}

function updateAnalyticsStats(data) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.warn('jQuery not available for analytics stats update');
        return;
    }
    
    $('#analyticsAverageAge').text(data.averageAge || '-');
    $('#analyticsServiceFrequency').text(data.serviceFrequency || '-');
    $('#analyticsLocationCoverage').text((data.locationCoverage || 0) + '%');
    $('#analyticsClientRetention').text((data.clientRetention || 0) + '%');
}

function initializeAnalyticsCharts(data) {
    // Initialize various charts with the data
    console.log('Initializing analytics charts with data:', data);
    
    // Initialize vehicle make chart
    if (data.makeDistribution) {
        initVehicleMakeChart(data.makeDistribution);
    }
    
    // Initialize services distribution chart
    if (data.servicesDistribution) {
        initServicesDistributionChart(data.servicesDistribution);
    }
    
    // Initialize monthly additions chart
    if (data.monthlyAdditions) {
        initMonthlyAdditionsChart(data.monthlyAdditions);
    }
    
    // Load top vehicles
    if (data.topVehicles) {
        loadTopVehicles(data.topVehicles);
    }
}

function initVehicleMakeChart(data) {
    // Implementation for vehicle make pie chart
    console.log('Initializing vehicle make chart');
}

function initServicesDistributionChart(data) {
    // Implementation for services distribution bar chart
    console.log('Initializing services distribution chart');
}

function initMonthlyAdditionsChart(data) {
    // Implementation for monthly additions line chart
    console.log('Initializing monthly additions chart');
}

function loadTopVehicles(vehicles) {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.warn('jQuery not available for top vehicles loading');
        return;
    }
    
    const container = $('#topVehiclesContainer');
    if (!vehicles || vehicles.length === 0) {
        container.html('<div class="text-center text-muted"><?= lang('App.no_analytics_data') ?></div>');
        return;
    }
    
    container.html(vehicles.map((vehicle, index) => `
        <div class="d-flex justify-content-between align-items-center py-2 ${index < vehicles.length - 1 ? 'border-bottom' : ''}">
            <div class="d-flex align-items-center">
                <div class="avatar-xs bg-warning-subtle rounded-circle me-2">
                    <span class="avatar-title text-warning font-size-12">${index + 1}</span>
                </div>
                <div>
                    <h6 class="mb-0 font-size-14">${vehicle.vehicle}</h6>
                    <small class="text-muted vehicle-vin-code">${vehicle.vin_last6}</small>
                </div>
            </div>
            <div class="text-end">
                <span class="fw-medium">${vehicle.total_services}</span>
                <div><small class="text-muted"><?= lang('App.services') ?></small></div>
            </div>
        </div>
    `).join(''));
}

// Load analytics data when this tab is shown
function initializeAnalyticsTab() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.warn('jQuery not available, retrying analytics initialization in 500ms...');
        setTimeout(initializeAnalyticsTab, 500);
        return;
    }
    
    $(document).ready(function() {
        if ($('#analytics-tab').hasClass('active')) {
            loadAnalyticsData();
        }
    });
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeAnalyticsTab);
} else {
    initializeAnalyticsTab();
}
</script> 