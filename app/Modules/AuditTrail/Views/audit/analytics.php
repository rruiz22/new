<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.geographic_analytics') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.geographic_analytics') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.analytics') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0"><?= lang('App.active_countries') ?></p>
                                <h4 class="mb-0"><?= count($locationStats['countries']) ?></h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="fs-2">🌍</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0"><?= lang('App.active_cities') ?></p>
                                <h4 class="mb-0"><?= count($locationStats['cities']) ?></h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="fs-2">🏙️</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0"><?= lang('App.map_locations') ?></p>
                                <h4 class="mb-0"><?= count($mapData) ?></h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="fs-2">📍</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0"><?= lang('App.recent_activities') ?></p>
                                <h4 class="mb-0"><?= count($recentActivities) ?></h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="fs-2">⚡</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Interactive Map -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i data-feather="map" class="icon-sm me-2"></i>
                            Interactive Activity Map
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="activityMap" style="height: 500px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <!-- Top Countries -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i data-feather="globe" class="icon-sm me-2"></i>
                            Top Countries
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php foreach ($locationStats['countries'] as $country): ?>
                                <?php
                                $percentage = count($locationStats['countries']) > 0 ? 
                                    round(($country['count'] / array_sum(array_column($locationStats['countries'], 'count'))) * 100, 1) : 0;
                                ?>
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2" style="font-size: 20px;">
                                                <?= getCountryFlag($country['country_code']) ?>
                                            </span>
                                            <div>
                                                <h6 class="mb-0"><?= esc($country['country']) ?></h6>
                                                <small class="text-muted"><?= $country['count'] ?> activities</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary"><?= $percentage ?>%</span>
                                        </div>
                                    </div>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Top Cities -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i data-feather="map-pin" class="icon-sm me-2"></i>
                            Top Cities
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>City</th>
                                        <th>Country</th>
                                        <th class="text-center">Activities</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($locationStats['cities'], 0, 10) as $city): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i data-feather="map-pin" class="icon-xs me-2 text-primary"></i>
                                                    <span><?= esc($city['city']) ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-1"><?= getCountryFlag($city['country_code']) ?></span>
                                                    <span><?= esc($city['country']) ?></span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark"><?= $city['count'] ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline by Location -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i data-feather="clock" class="icon-sm me-2"></i>
                            Activity Timeline
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            <?php foreach (array_slice($recentActivities, 0, 8) as $activity): ?>
                                <?php
                                $deviceInfo = getDeviceInfo($activity['user_agent'] ?? '');
                                $locationDisplay = trim(($activity['city'] ?? '') . ((!empty($activity['city']) && !empty($activity['country'])) ? ', ' : '') . ($activity['country'] ?? ''));
                                ?>
                                <div class="activity-item d-flex mb-3">
                                    <div class="activity-icon me-3">
                                        <span style="font-size: 16px;"><?= getCountryFlag($activity['country_code'] ?? '') ?></span>
                                    </div>
                                    <div class="activity-content flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1"><?= esc($activity['first_name'] . ' ' . $activity['last_name']) ?></h6>
                                                <p class="mb-1 text-muted small"><?= esc($activity['description']) ?></p>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-<?= $activity['action'] === 'CREATE' ? 'success' : ($activity['action'] === 'UPDATE' ? 'warning' : ($activity['action'] === 'DELETE' ? 'danger' : 'info')) ?>">
                                                        <?= esc($activity['action']) ?>
                                                    </span>
                                                    <?php if (!empty($locationDisplay)): ?>
                                                        <small class="text-muted">
                                                            <i data-feather="<?= $deviceInfo['icon'] ?>" class="icon-xs me-1"></i>
                                                            <?= esc($locationDisplay) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <small class="text-muted"><?= date('M j, H:i', strtotime($activity['created_at'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?= base_url('audit') ?>" class="btn btn-sm btn-outline-primary">
                                View All Activities
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Range Statistics -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i data-feather="trending-up" class="icon-sm me-2"></i>
                            Activity Trends by Location
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($timeRangeStats as $period => $stats): ?>
                                <div class="col-md-4">
                                    <div class="border rounded p-3">
                                        <h6 class="text-uppercase text-muted mb-3">
                                            <?php
                                            echo $period === '24h' ? 'Last 24 Hours' : 
                                                ($period === '7d' ? 'Last 7 Days' : 'Last 30 Days');
                                            ?>
                                        </h6>
                                        <?php if (!empty($stats)): ?>
                                            <?php foreach (array_slice($stats, 0, 5) as $stat): ?>
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2"><?= getCountryFlag($stat['country_code']) ?></span>
                                                        <span class="small"><?= esc($stat['country']) ?></span>
                                                    </div>
                                                    <span class="badge bg-light text-dark small"><?= $stat['count'] ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-muted small mb-0">No activities in this period</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('activityMap').setView([20, 0], 2);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Map data from PHP
    const mapData = <?= json_encode($mapData) ?>;
    
    // Add markers for each location
    mapData.forEach(function(location) {
        const activityCount = location.activities.length;
        const flag = getCountryFlag(location.country_code);
        
        // Create custom icon with country flag
        const markerIcon = L.divIcon({
            html: '<div style="background: white; border: 2px solid #007bff; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">' + flag + '</div>',
            className: 'custom-marker',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        const marker = L.marker([location.latitude, location.longitude], {
            icon: markerIcon
        }).addTo(map);

        // Create popup content
        let popupContent = '<div style="min-width: 250px;"><h6 class="mb-2">' + flag + ' ' + location.city + ', ' + location.country + '</h6><p class="mb-2 small text-muted">' + activityCount + ' activit' + (activityCount === 1 ? 'y' : 'ies') + '</p><div style="max-height: 200px; overflow-y: auto;">';

        location.activities.slice(0, 5).forEach(function(activity) {
            const actionColor = activity.action === 'CREATE' ? 'success' : 
                              (activity.action === 'UPDATE' ? 'warning' : 
                              (activity.action === 'DELETE' ? 'danger' : 'info'));
            
            popupContent += '<div class="border-bottom py-2"><div class="d-flex justify-content-between align-items-start"><div><strong class="small">' + activity.user_name + '</strong><div class="small text-muted">' + activity.description + '</div><span class="badge bg-' + actionColor + ' badge-sm">' + activity.action + '</span></div><small class="text-muted">' + new Date(activity.created_at).toLocaleDateString() + '</small></div></div>';
        });

        if (activityCount > 5) {
            popupContent += '<div class="text-center mt-2"><small class="text-muted">+ ' + (activityCount - 5) + ' more activities</small></div>';
        }

        popupContent += '</div></div>';

        marker.bindPopup(popupContent);
    });

    // Helper function to get country flag (client-side)
    function getCountryFlag(countryCode) {
        if (!countryCode || countryCode.length !== 2) return '🌍';
        const firstLetter = String.fromCodePoint(countryCode.charCodeAt(0) - 65 + 127462);
        const secondLetter = String.fromCodePoint(countryCode.charCodeAt(1) - 65 + 127462);
        return firstLetter + secondLetter;
    }

    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>

<style>
.activity-timeline {
    position: relative;
}

.activity-item {
    position: relative;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.activity-icon {
    position: relative;
    z-index: 1;
    width: 24px;
    height: 24px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.custom-marker {
    background: transparent !important;
    border: none !important;
}

.leaflet-popup-content {
    margin: 8px 12px;
}
</style>
<?= $this->endSection() ?> 