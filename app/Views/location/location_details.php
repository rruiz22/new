<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $page_title ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $page_title ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item">
    <a href="<?= base_url('vehicles/' . substr($location['vin_number'], -6)) ?>">Vehicle Details</a>
</li>
<li class="breadcrumb-item active"><?= $page_title ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Leaflet CSS for Maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.info-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.location-header {
    background: #f8f9fa;
    color: #495057;
    border-radius: 15px 15px 0 0;
}

.device-info-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 8px;
}

.coordinate-display {
    font-family: 'Courier New', monospace;
    background: #e9ecef;
    padding: 8px;
    border-radius: 6px;
    font-size: 0.9rem;
}

.location-address-main {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
}

.address-display .spin-icon {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.main-address-line {
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    display: block;
    margin-bottom: 4px;
}

.address-details {
    color: #6c757d;
    font-size: 0.9rem;
}

.accuracy-badge {
    background: #f8f9fa;
    color: #495057;
    border: 1px solid #e9ecef;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.location-map {
    height: 350px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.timeline-item {
    padding-left: 15px;
    margin-bottom: 15px;
    position: relative;
    border-left: 2px solid #e9ecef;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -7px;
    top: 8px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #007bff;
}

.nearby-location {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 8px;
}

.nearby-location-enhanced {
    background: #ffffff;
    border: 1px solid #e3f2fd;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 0;
    transition: all 0.3s ease;
    position: relative;
}

.nearby-location-enhanced:hover {
    border-color: #2196f3;
    box-shadow: 0 4px 20px rgba(33, 150, 243, 0.1);
    transform: translateY(-1px);
}

.nearby-location-enhanced .vehicle-info {
    width: 100%;
}

.nearby-location-enhanced .vehicle-details h6 {
    color: #1976d2;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 8px;
}

.nearby-location-enhanced .vehicle-meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.nearby-location-enhanced .vehicle-meta small {
    font-size: 0.875rem;
    line-height: 1.4;
}

.nearby-location-enhanced .vehicle-meta i {
    color: #6c757d;
    font-size: 0.875rem;
    width: 16px;
    text-align: center;
}

.nearby-location-enhanced .action-buttons {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.nearby-location-enhanced .badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.badge-sm {
    font-size: 0.7rem;
    padding: 2px 6px;
}

@media (max-width: 768px) {
    .nearby-location-enhanced .row {
        display: block;
    }
    
    .nearby-location-enhanced .col-md-4 {
        margin-top: 15px;
        text-align: left !important;
    }
    
    .nearby-location-enhanced .action-buttons {
        flex-direction: row;
        justify-content: flex-start;
    }
    
    .nearby-location-enhanced .action-buttons .btn {
        width: auto !important;
        margin-right: 8px;
    }
}

.user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f8f9fa;
    border: 3px solid #ffffff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #495057;
    font-weight: 700;
    font-size: 1.4rem;
    margin: 0 auto;
}

/* Enhanced Info Card Styles */
.info-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    background: #ffffff;
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    border-color: #dee2e6;
}

.info-card .card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 16px 16px 0 0;
    padding: 1.2rem 1.5rem;
}

.info-card .card-body {
    padding: 1.5rem;
}

/* User Info Section */
.user-info-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.user-info-item:last-child {
    border-bottom: none;
}

.user-info-item i {
    width: 20px;
    text-align: center;
    margin-right: 0.75rem;
    color: #6c757d;
}

.nfc-code-display {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    text-align: center;
    margin-top: 1rem;
}

.nfc-code-display code {
    background: #e9ecef;
    color: #495057;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
}

/* Vehicle Info Section */
.vehicle-info-item {
    padding: 0.875rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.vehicle-info-item:last-child {
    border-bottom: none;
}

.vehicle-info-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.vehicle-info-value {
    color: #6c757d;
    font-size: 0.95rem;
}

.vehicle-info-value code {
    background: #f8f9fa;
    color: #495057;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
    border: 1px solid #e9ecef;
}

.status-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: 0.5rem;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.completed { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.status-badge.in-progress { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
.status-badge.pending { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
.status-badge.cancelled { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
.status-badge.urgent { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
.status-badge.high { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

.view-details-btn {
    background: #ffffff;
    border: 2px solid #007bff;
    color: #007bff;
    padding: 0.625rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.view-details-btn:hover {
    background: #007bff;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.25);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 5px;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.device-type-card, .browser-info-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 15px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    height: 100%;
}

.device-type-card:hover, .browser-info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.device-icon, .browser-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.device-icon {
    background: #f8f9fa;
    color: #495057;
    border: 1px solid #e9ecef;
}

.browser-icon {
    background: #f8f9fa;
    color: #495057;
    border: 1px solid #e9ecef;
}

.device-details, .browser-details {
    flex-grow: 1;
}

.device-details strong, .browser-details strong {
    color: #495057;
    font-size: 1rem;
}

/* Device Type Specific Icons */
.device-mobile { background: #f8f9fa !important; color: #495057 !important; border: 1px solid #e9ecef !important; }
.device-tablet { background: #f8f9fa !important; color: #495057 !important; border: 1px solid #e9ecef !important; }
.device-desktop { background: #f8f9fa !important; color: #495057 !important; border: 1px solid #e9ecef !important; }

/* Browser Specific Colors */
.browser-chrome { background: #f8f9fa !important; color: #495057 !important; border: 1px solid #e9ecef !important; }
.browser-firefox { background: #f8f9fa !important; color: #495057 !important; border: 1px solid #e9ecef !important; }
.browser-safari { background: #f8f9fa !important; color: #495057 !important; border: 1px solid #e9ecef !important; }
.browser-edge { background: #f8f9fa !important; color: #495057 !important; border: 1px solid #e9ecef !important; }
.browser-opera { background: #f8f9fa !important; color: #495057 !important; border: 1px solid #e9ecef !important; }

/* Activity Item Styling (Recent History) */
.activity-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
    background: #f8fafc;
    border-radius: 8px;
    padding: 0.75rem;
    margin: 0 -0.75rem;
}

.activity-icon .avatar-xs {
    width: 32px;
    height: 32px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
}

.activity-content h6 {
    color: #495057;
    font-weight: 600;
}

.activity-content p {
    font-size: 0.875rem;
}

.activity-actions .btn {
    opacity: 0.7;
    transition: opacity 0.2s ease;
}

.activity-item:hover .activity-actions .btn {
    opacity: 1;
}

/* Recent History Container Scrolling */
#recentHistoryContainer {
    max-height: 600px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #e9ecef #f8f9fa;
}

#recentHistoryContainer::-webkit-scrollbar {
    width: 6px;
}

#recentHistoryContainer::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

#recentHistoryContainer::-webkit-scrollbar-thumb {
    background: #e9ecef;
    border-radius: 3px;
}

#recentHistoryContainer::-webkit-scrollbar-thumb:hover {
    background: #dee2e6;
}

/* Smooth animations for loading states */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    
    <!-- Location Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card info-card">
                <div class="location-header p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                                                         <h1 class="h3 mb-2">
                                 <i class="ri-map-pin-fill me-2"></i>
                                 <?= lang('Location') ?>: <?= esc($location['spot_number']) ?>
                             </h1>
                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                <div>
                                    <i class="ri-car-line me-1"></i>
                                                                         <strong><?= lang('Vehicle') ?>:</strong> <?= esc($location['vehicle'] ?? $location['vin_number']) ?>
                                </div>
                                <?php if (!empty($location['zone'])): ?>
                                <div>
                                    <i class="ri-building-2-line me-1"></i>
                                                                         <strong><?= lang('Zone') ?>:</strong> <?= esc($location['zone']) ?>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <i class="ri-time-line me-1"></i>
                                                                         <strong><?= lang('Recorded') ?>:</strong> <?= date('M j, Y g:i A', strtotime($location['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex flex-column align-items-end gap-2">
                                <!-- Go Back Button -->
                                <button class="btn btn-outline-secondary btn-sm" onclick="goBack()">
                                    <i class="ri-arrow-left-line me-1"></i><?= lang('App.go_back') ?>
                                </button>
                                <?php if (!empty($location['accuracy'])): ?>
                                <div class="accuracy-badge">
                                    <i class="ri-focus-3-line me-1"></i>
                                    ±<?= round($location['accuracy']) ?>m accuracy
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">
                <?php if (!empty($location['latitude'])): ?>
                    <i class="ri-gps-line text-success"></i>
                <?php else: ?>
                    <i class="ri-gps-fill text-muted"></i>
                <?php endif; ?>
            </div>
                         <div class="stat-label"><?= lang('GPS Status') ?></div>
            <small class="text-muted">
                                 <?= !empty($location['latitude']) ? lang('GPS Available') : lang('Manual Entry') ?>
            </small>
        </div>
        
        <div class="stat-card">
            <div class="stat-number"><?= count($nearby_locations) ?></div>
            <div class="stat-label">Nearby Records</div>
            <small class="text-muted">Within 100m radius</small>
        </div>
        
        <div class="stat-card">
            <div class="stat-number"><?= count($vehicle_history) ?></div>
            <div class="stat-label">Vehicle History</div>
            <small class="text-muted">Previous locations</small>
        </div>
        
        <div class="stat-card">
            <div class="stat-number">
                <?= !empty($device_info) ? count($device_info) : 0 ?>
            </div>
            <div class="stat-label">Device Details</div>
            <small class="text-muted">Info captured</small>
        </div>
    </div>

    <div class="row">
        
        <!-- Main Location Info -->
        <div class="col-lg-8">
            
            <!-- Map Section -->
            <?php if (!empty($location['latitude']) && !empty($location['longitude'])): ?>
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-map-2-line me-2 text-primary"></i>
                        Location Map
                    </h5>
                    <p class="text-muted small mb-0">Interactive map showing exact GPS coordinates</p>
                </div>
                <div class="card-body">
                    <div id="locationMap" class="location-map"></div>
                    
                    <!-- Address Display with Reverse Geocoding -->
                    <div class="address-display mt-3 text-center">
                        <div id="location-address-main" class="location-address-main mb-2">
                            <?php if (!empty($location['latitude']) && !empty($location['longitude'])): ?>
                            <small class="text-muted">
                                <i class="ri-loader-4-line spin-icon"></i> Loading address...
                            </small>
                            <?php else: ?>
                            <small class="text-muted">
                                <i class="ri-map-pin-line me-1"></i> No GPS coordinates available
                            </small>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Device Information -->
            <?php if (!empty($device_info)): ?>
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-smartphone-line me-2 text-primary"></i>
                        Device Information
                    </h5>
                    <p class="text-muted small mb-0">Details about the device used to record this location</p>
                </div>
                <div class="card-body">
                    <!-- Device Type and OS Detection -->
                    <?php if (!empty($device_info['user_agent'])): ?>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="device-type-card" id="device-type-info">
                                <div class="device-icon" id="device-icon">
                                    <i class="ri-smartphone-line"></i>
                                </div>
                                <div class="device-details">
                                    <strong id="device-type">Detecting...</strong>
                                    <br><small class="text-muted" id="device-os">Please wait</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="browser-info-card" id="browser-info">
                                <div class="browser-icon" id="browser-icon">
                                    <i class="ri-global-line"></i>
                                </div>
                                <div class="browser-details">
                                    <strong id="browser-name">Detecting...</strong>
                                    <br><small class="text-muted" id="browser-version">Please wait</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                <strong>Device information not available</strong>
                                <br><small class="text-muted">No user agent data was captured for this location record.</small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <?php if (!empty($device_info['user_agent'])): ?>
                        <div class="col-md-12 mb-3">
                            <div class="device-info-item">
                                <strong><i class="ri-computer-line me-2"></i>User Agent:</strong>
                                <br><small class="text-muted"><?= esc($device_info['user_agent']) ?></small>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="col-md-6">
                            <?php if (!empty($device_info['ip_address'])): ?>
                            <div class="device-info-item">
                                <strong><i class="ri-global-line me-2"></i>IP Address:</strong>
                                <br><code><?= esc($device_info['ip_address']) ?></code>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6">
                            <?php if (!empty($device_info['timestamp'])): ?>
                            <div class="device-info-item">
                                <strong><i class="ri-time-line me-2"></i>Device Timestamp:</strong>
                                <br><small><?= date('M j, Y g:i:s A', strtotime($device_info['timestamp'])) ?></small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Nearby Locations -->
            <?php if (!empty($nearby_locations)): ?>
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-radar-line me-2 text-primary"></i>
                        Nearby Locations
                    </h5>
                    <p class="text-muted small mb-0">Other vehicles recorded within 100m radius</p>
                </div>
                <div class="card-body">
                    <?php foreach ($nearby_locations as $nearby): ?>
                    <div class="nearby-location-enhanced">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="vehicle-info">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-secondary me-2">Spot <?= esc($nearby['spot_number']) ?></span>
                                        <span class="badge bg-info"><?= round($nearby['distance'] * 1000) ?>m away</span>
                                        <?php if (!empty($nearby['priority']) && $nearby['priority'] === 'urgent'): ?>
                                            <span class="badge bg-danger ms-1">
                                                <i class="ri-alarm-warning-line"></i> Urgent
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="vehicle-details">
                                        <h6 class="mb-1 text-dark">
                                            <?= esc($nearby['vehicle'] ?? 'N/A') ?>
                                            <?php if (!empty($nearby['stock'])): ?>
                                                <small class="text-primary ms-1">(Stock: <?= esc($nearby['stock']) ?>)</small>
                                            <?php endif; ?>
                                        </h6>
                                        
                                        <div class="vehicle-meta">
                                            <small class="text-muted d-block">
                                                <i class="ri-barcode-line me-1"></i>
                                                <strong>VIN:</strong> <?= esc($nearby['vin_number']) ?>
                                            </small>
                                            
                                            <?php if (!empty($nearby['order_number'])): ?>
                                            <small class="text-muted d-block">
                                                <i class="ri-file-list-3-line me-1"></i>
                                                <strong>Order:</strong> <?= esc($nearby['order_number']) ?>
                                                <?php if (!empty($nearby['order_status'])): ?>
                                                    <span class="badge badge-sm ms-1 
                                                        <?= $nearby['order_status'] === 'completed' ? 'bg-success' : 
                                                            ($nearby['order_status'] === 'in_progress' ? 'bg-warning' : 'bg-secondary') ?>">
                                                        <?= ucfirst($nearby['order_status']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </small>
                                            <?php endif; ?>
                                            
                                            <small class="text-muted d-block">
                                                <i class="ri-user-line me-1"></i>
                                                <strong>Recorded by:</strong> <?= esc($nearby['user_full_name'] ?? $nearby['user_name'] ?? 'Anonymous') ?>
                                            </small>
                                            
                                            <small class="text-muted d-block">
                                                <i class="ri-time-line me-1"></i>
                                                <strong>Date:</strong> <?= date('M j, Y g:i A', strtotime($nearby['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 text-end">
                                <div class="action-buttons">
                                    <a href="<?= base_url('location-details/' . $nearby['id']) ?>" 
                                       class="btn btn-outline-primary btn-sm mb-1 w-100">
                                        <i class="ri-eye-line me-1"></i>
                                        View Details
                                    </a>
                                    
                                    <?php if (!empty($nearby['order_number'])): ?>
                                    <a href="<?= base_url('recon-orders/view/' . $nearby['order_number']) ?>" 
                                       class="btn btn-outline-success btn-sm w-100" 
                                       target="_blank">
                                        <i class="ri-external-link-line me-1"></i>
                                        View Order
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($nearby !== end($nearby_locations)): ?>
                        <hr class="my-3">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            
            <!-- User Information -->
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-line me-2 text-primary"></i>
                        <?= lang('App.recorded_by') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="user-avatar mb-3">
                            <?php 
                            $displayName = $user_details['full_name'] ?? $location['user_full_name'] ?? $location['user_name'] ?? 'Anonymous';
                            echo strtoupper(substr($displayName, 0, 1));
                            ?>
                        </div>
                        <h6 class="mb-0 fw-bold"><?= esc($user_details['full_name'] ?? $location['user_full_name'] ?? $location['user_name'] ?? 'Anonymous User') ?></h6>
                    </div>
                    
                    <div class="user-info-section">
                        <?php if ($user_details && !empty($user_details['email'])): ?>
                        <div class="user-info-item">
                            <i class="ri-mail-line"></i>
                            <span><?= esc($user_details['email']) ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($user_details && !empty($user_details['username'])): ?>
                        <div class="user-info-item">
                            <i class="ri-user-3-line"></i>
                            <div>
                                <small class="text-muted"><?= lang('App.username') ?>:</small><br>
                                <span><?= esc($user_details['username']) ?></span>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="user-info-item">
                            <i class="ri-user-3-line"></i>
                            <span class="text-muted"><?= lang('App.guest_manual_entry') ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($location['token_used'])): ?>
                        <div class="nfc-code-display">
                            <small class="text-muted d-block mb-2"><?= lang('App.nfc_code_used') ?>:</small>
                            <code><?= esc(substr($location['token_used'], 0, 12)) ?>...</code>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Vehicle Information -->
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-car-line me-2 text-primary"></i>
                        <?= lang('App.vehicle_details') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="vehicle-info-item">
                        <div class="vehicle-info-label"><?= lang('App.vin_number') ?>:</div>
                        <div class="vehicle-info-value">
                            <code><?= esc($location['vin_number']) ?></code>
                        </div>
                    </div>
                    
                    <?php if (!empty($location['vehicle'])): ?>
                    <div class="vehicle-info-item">
                        <div class="vehicle-info-label"><?= lang('App.description') ?>:</div>
                        <div class="vehicle-info-value"><?= esc($location['vehicle']) ?></div>
                    </div>
                    <?php endif; ?>
                     
                    <?php if (!empty($location['order_number'])): ?>
                    <div class="vehicle-info-item">
                        <div class="vehicle-info-label"><?= lang('App.order_number') ?>:</div>
                        <div class="vehicle-info-value">
                            <code><?= esc($location['order_number']) ?></code>
                        </div>
                    </div>
                    <?php endif; ?>
                     
                    <?php if (!empty($location['stock'])): ?>
                    <div class="vehicle-info-item">
                        <div class="vehicle-info-label"><?= lang('App.stock_number') ?>:</div>
                        <div class="vehicle-info-value">
                            <code><?= esc($location['stock']) ?></code>
                        </div>
                    </div>
                    <?php endif; ?>
                     
                    <?php if (!empty($location['status']) || !empty($location['priority'])): ?>
                    <div class="vehicle-info-item">
                        <div class="vehicle-info-label"><?= lang('App.order_status') ?>:</div>
                        <div class="status-badges">
                            <?php if (!empty($location['status'])): ?>
                            <span class="status-badge <?= esc($location['status']) ?>">
                                <?= ucfirst(str_replace('_', ' ', esc($location['status']))) ?>
                            </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($location['priority']) && $location['priority'] !== 'normal'): ?>
                            <span class="status-badge <?= esc($location['priority']) ?>">
                                <?= ucfirst(esc($location['priority'])) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="text-center mt-4">
                        <a href="<?= base_url('vehicles/' . substr($location['vin_number'], -6)) ?>" 
                           class="view-details-btn">
                            <i class="ri-car-line"></i>
                            <?= lang('App.view_vehicle_details') ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Additional Notes -->
            <?php if (!empty($location['notes'])): ?>
            <div class="card info-card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-sticky-note-line me-2 text-primary"></i>
                        Notes
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= nl2br(esc($location['notes'])) ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Vehicle Location History -->
            <?php if (!empty($vehicle_history)): ?>
            <div class="card info-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i data-feather="map-pin" class="icon-sm me-2"></i>
                        <?= lang('App.recent_history') ?>
                    </h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshRecentHistory()" id="refreshHistoryBtn">
                        <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
                <div class="card-body" id="recentHistoryContainer">
                    <p class="text-muted small mb-3"><?= lang('App.previous_locations_vehicle') ?></p>
                    <div id="historyList">
                        <?php foreach ($vehicle_history as $history): ?>
                        <div class="activity-item d-flex align-items-start">
                            <div class="activity-icon me-3">
                                <div class="avatar-xs bg-light border rounded-circle d-flex align-items-center justify-content-center">
                                    <i data-feather="map-pin" class="icon-xs text-primary"></i>
                                </div>
                            </div>
                            <div class="activity-content flex-grow-1">
                                <h6 class="mb-1"><?= lang('App.spot') ?> <?= esc($history['spot_number']) ?></h6>
                                <p class="text-muted mb-1">Location recorded at this parking spot</p>
                                <small class="text-muted">
                                    <i data-feather="user" class="icon-xs me-1"></i>
                                    <?= esc($history['user_full_name'] ?? $history['user_name'] ?? lang('App.anonymous')) ?>
                                    <i data-feather="clock" class="icon-xs ms-2 me-1"></i>
                                    <?= date('M j, Y \\a\\t g:i A', strtotime($history['created_at'])) ?>
                                </small>
                            </div>
                            <div class="activity-actions">
                                <a href="<?= base_url('location-details/' . $history['id']) ?>" 
                                   class="btn btn-outline-primary btn-sm" 
                                   title="<?= lang('App.view_details') ?>">
                                    <i data-feather="eye" class="icon-xs"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Loading indicator -->
                    <div id="historyLoadingIndicator" class="text-center py-3" style="display: none;">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0 small">Loading more history...</p>
                    </div>
                    
                    <!-- Load more button -->
                    <div id="loadMoreContainer" class="text-center mt-3" style="display: none;">
                        <button class="btn btn-outline-secondary btn-sm" onclick="loadMoreHistory()" id="loadMoreBtn">
                            <i data-feather="chevron-down" class="icon-xs me-1"></i>
                            Load More
                        </button>
                    </div>
                    
                    <!-- No more data message -->
                    <div id="noMoreDataMessage" class="text-center mt-3" style="display: none;">
                        <small class="text-muted">
                            <i data-feather="check-circle" class="icon-xs me-1"></i>
                            No more history records
                        </small>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Leaflet JS for Maps -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($location['latitude']) && !empty($location['longitude'])): ?>
    // Initialize the map
    const map = L.map('locationMap').setView([<?= $location['latitude'] ?>, <?= $location['longitude'] ?>], 18);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Create custom icon for main location
    const mainLocationIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="background: #f8f9fa; color: #495057; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid #495057; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                   <i class="ri-map-pin-fill" style="font-size: 16px;"></i>
               </div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });
    
    // Add marker for main location
    const mainMarker = L.marker([<?= $location['latitude'] ?>, <?= $location['longitude'] ?>], {
        icon: mainLocationIcon
    }).addTo(map);
    
    // Popup for main location
    const popupContent = `
        <div style="text-align: center; min-width: 200px;">
            <h6 style="color: #007bff; margin-bottom: 10px;">
                <i class="ri-map-pin-fill"></i> Spot <?= esc($location['spot_number']) ?>
            </h6>
            <p style="margin: 5px 0;"><strong>Vehicle:</strong> <?= esc($location['vin_number']) ?></p>
            <p style="margin: 5px 0;"><strong>Recorded:</strong> <?= date('M j, Y g:i A', strtotime($location['created_at'])) ?></p>
            <p style="margin: 5px 0;"><strong>By:</strong> <?= esc($location['user_full_name'] ?? $location['user_name'] ?? 'Anonymous') ?></p>
            <?php if (!empty($location['accuracy'])): ?>
            <p style="margin: 5px 0;"><strong>Accuracy:</strong> ±<?= round($location['accuracy']) ?>m</p>
            <?php endif; ?>
            <?php if (!empty($location['notes'])): ?>
            <p style="margin: 5px 0;"><strong>Notes:</strong> <?= esc(substr($location['notes'], 0, 50)) ?><?= strlen($location['notes']) > 50 ? '...' : '' ?></p>
            <?php endif; ?>
        </div>
    `;
    
    mainMarker.bindPopup(popupContent).openPopup();
    
    <?php if (!empty($location['accuracy'])): ?>
    // Add accuracy circle
    const accuracyCircle = L.circle([<?= $location['latitude'] ?>, <?= $location['longitude'] ?>], {
        color: '#007bff',
        fillColor: '#007bff',
        fillOpacity: 0.1,
        radius: <?= $location['accuracy'] ?>
    }).addTo(map);
    <?php endif; ?>
    
    <?php if (!empty($nearby_locations)): ?>
    // Add nearby location markers
    const processedIds = []; // Track processed IDs to avoid duplicates
    const nearbyMarkers = []; // Store markers for group bounds
    
    <?php
    $processedLocationIds = [];
    foreach ($nearby_locations as $nearby): 
        // Skip if we've already processed this location ID
        if (in_array($nearby['id'], $processedLocationIds)) continue;
        $processedLocationIds[] = $nearby['id'];
        
        if (!empty($nearby['latitude']) && !empty($nearby['longitude'])): 
    ?>
    
    // Check if this ID was already processed in JavaScript
    if (!processedIds.includes(<?= $nearby['id'] ?>)) {
        processedIds.push(<?= $nearby['id'] ?>);
        
        const nearbyIcon<?= $nearby['id'] ?> = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background: #f8f9fa; color: #6c757d; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 1px solid #6c757d; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                       <i class="ri-map-pin-2-fill" style="font-size: 12px;"></i>
                   </div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });
        
        const nearbyMarker<?= $nearby['id'] ?> = L.marker([<?= $nearby['latitude'] ?>, <?= $nearby['longitude'] ?>], {
            icon: nearbyIcon<?= $nearby['id'] ?>
        }).addTo(map)
        .bindPopup(`
            <div style="text-align: center; min-width: 250px;">
                <h6 style="color: #ffc107; margin-bottom: 8px;">
                    <i class="ri-map-pin-2-fill"></i> Spot <?= esc($nearby['spot_number']) ?>
                </h6>
                <div style="text-align: left; margin-bottom: 10px;">
                    <p style="margin: 3px 0; font-weight: bold; color: #333;">
                        <?= esc($nearby['vehicle'] ?? 'N/A') ?>
                    </p>
                    <?php if (!empty($nearby['stock'])): ?>
                    <p style="margin: 3px 0; color: #0066cc;">
                        <strong>Stock:</strong> <?= esc($nearby['stock']) ?>
                    </p>
                    <?php endif; ?>
                    <p style="margin: 3px 0;"><strong>VIN:</strong> <?= esc($nearby['vin_number']) ?></p>
                    <?php if (!empty($nearby['order_number'])): ?>
                    <p style="margin: 3px 0;">
                        <strong>Order:</strong> <?= esc($nearby['order_number']) ?>
                        <?php if (!empty($nearby['order_status'])): ?>
                            <span style="background: <?= $nearby['order_status'] === 'completed' ? '#28a745' : 
                                                        ($nearby['order_status'] === 'in_progress' ? '#ffc107' : '#6c757d') ?>; 
                                         color: white; padding: 2px 6px; border-radius: 10px; font-size: 10px;">
                                <?= ucfirst($nearby['order_status']) ?>
                            </span>
                        <?php endif; ?>
                    </p>
                    <?php endif; ?>
                    <p style="margin: 3px 0;"><strong>Distance:</strong> <?= round($nearby['distance'] * 1000) ?>m</p>
                    <p style="margin: 3px 0;"><strong>By:</strong> <?= esc($nearby['user_full_name'] ?? $nearby['user_name'] ?? 'Anonymous') ?></p>
                </div>
                <div>
                    <a href="<?= base_url('location-details/' . $nearby['id']) ?>" 
                       class="btn btn-sm btn-outline-primary me-1">
                        <i class="ri-eye-line"></i> View Details
                    </a>
                    <?php if (!empty($nearby['order_number'])): ?>
                    <a href="<?= base_url('recon-orders/view/' . $nearby['order_number']) ?>" 
                       class="btn btn-sm btn-outline-success" target="_blank">
                        <i class="ri-external-link-line"></i> Order
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        `);
        
        nearbyMarkers.push(nearbyMarker<?= $nearby['id'] ?>);
    }
    
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
    
    // Fit map to show all markers if there are nearby locations
    <?php if (!empty($nearby_locations) && count($nearby_locations) > 0): ?>
    if (nearbyMarkers.length > 0) {
        const group = new L.featureGroup([mainMarker, ...nearbyMarkers]);
        map.fitBounds(group.getBounds().pad(0.1));
    }
    <?php endif; ?>
    
    <?php endif; ?>
    
    // Load address for main location
    <?php if (!empty($location['latitude']) && !empty($location['longitude'])): ?>
    loadMainLocationAddress(<?= $location['latitude'] ?>, <?= $location['longitude'] ?>, <?= $location['accuracy'] ?? 'null' ?>);
    <?php endif; ?>
    
    // Parse device information from User Agent
    <?php if (!empty($device_info['user_agent'])): ?>
    parseDeviceInfo('<?= addslashes($device_info['user_agent']) ?>');
    <?php endif; ?>
    
    console.log('🗺️ Location details page loaded successfully');
});

// Reverse geocoding functions for location details
async function loadMainLocationAddress(lat, lng, accuracy) {
    const addressElement = document.getElementById('location-address-main');
    if (!addressElement) return;
    
    // Create cache key
    const cacheKey = `${lat.toFixed(4)},${lng.toFixed(4)}`;
    
    // Add timeout to prevent infinite loading
    const timeoutId = setTimeout(() => {
        console.warn('Address loading timed out after 10 seconds');
        addressElement.innerHTML = `
            <div class="main-address-line">
                <i class="ri-map-pin-line me-2 text-warning"></i>
                <small class="text-muted">Address loading timed out</small>
            </div>
            <div class="address-details">
                <small>
                    <i class="ri-gps-line me-1"></i>
                    ${lat.toFixed(6)}, ${lng.toFixed(6)}
                    ${accuracy ? ` • ±${Math.round(accuracy)}m accuracy` : ''}
                </small>
            </div>
        `;
    }, 10000); // 10 second timeout
    
    try {
        let address;
        
        // Check localStorage cache first
        const cachedData = localStorage.getItem(`address_${cacheKey}`);
        if (cachedData) {
            const parsed = JSON.parse(cachedData);
            // Cache for 24 hours
            if (Date.now() - parsed.timestamp < 24 * 60 * 60 * 1000) {
                address = parsed.address;
            }
        }
        
        // If not in cache, fetch from Nominatim API
        if (!address) {
            const controller = new AbortController();
            const timeoutSignal = setTimeout(() => controller.abort(), 8000); // 8 second API timeout
            
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`,
                {
                    headers: {
                        'User-Agent': 'VehicleLocationTracker/1.0'
                    },
                    signal: controller.signal
                }
            );
            
            clearTimeout(timeoutSignal);
            
            if (response.ok) {
                const data = await response.json();
                address = formatMainAddress(data);
                
                // Cache the result
                localStorage.setItem(`address_${cacheKey}`, JSON.stringify({
                    address: address,
                    timestamp: Date.now()
                }));
            } else {
                throw new Error('Geocoding API failed');
            }
        }
        
        // Clear the timeout since we succeeded
        clearTimeout(timeoutId);
        
        // Update the UI with the address
        addressElement.innerHTML = `
            <div class="main-address-line">
                <i class="ri-map-pin-2-line me-2 text-primary"></i>
                ${address}
            </div>
            <div class="address-details">
                <small>
                    <i class="ri-gps-line me-1"></i>
                    ${lat.toFixed(6)}, ${lng.toFixed(6)}
                    ${accuracy ? ` • ±${Math.round(accuracy)}m accuracy` : ''}
                </small>
            </div>
        `;
        
    } catch (error) {
        // Clear the timeout
        clearTimeout(timeoutId);
        
        console.warn(`Failed to load address for main location:`, error);
        
        // Fallback to coordinates only
        addressElement.innerHTML = `
            <div class="main-address-line">
                <i class="ri-map-pin-line me-2 text-muted"></i>
                <small class="text-muted">Address unavailable</small>
            </div>
            <div class="address-details">
                <small>
                    <i class="ri-gps-line me-1"></i>
                    ${lat.toFixed(6)}, ${lng.toFixed(6)}
                    ${accuracy ? ` • ±${Math.round(accuracy)}m accuracy` : ''}
                </small>
            </div>
        `;
    }
}

function formatMainAddress(geocodeData) {
    if (!geocodeData || !geocodeData.address) {
        return 'Unknown location';
    }
    
    const addr = geocodeData.address;
    const parts = [];
    
    // Building number and street
    if (addr.house_number && addr.road) {
        parts.push(`${addr.house_number} ${addr.road}`);
    } else if (addr.road) {
        parts.push(addr.road);
    } else if (addr.pedestrian || addr.footway) {
        parts.push(addr.pedestrian || addr.footway);
    }
    
    // Area/Neighborhood
    if (addr.neighbourhood || addr.suburb || addr.district) {
        parts.push(addr.neighbourhood || addr.suburb || addr.district);
    }
    
    // City
    if (addr.city || addr.town || addr.village) {
        parts.push(addr.city || addr.town || addr.village);
    }
    
    // State/Province
    if (addr.state || addr.province) {
        parts.push(addr.state || addr.province);
    }
    
    // Country (only if not US to save space)
    if (addr.country && addr.country !== 'United States') {
        parts.push(addr.country);
    }
    
    // If we have very specific location
    if (parts.length === 0) {
        if (addr.amenity) parts.push(addr.amenity);
        if (addr.shop) parts.push(addr.shop);
        if (addr.building) parts.push(addr.building);
        if (addr.postcode) parts.push(addr.postcode);
    }
    
    return parts.length > 0 ? parts.join(', ') : geocodeData.display_name || 'Unknown location';
}

// Device and Browser Detection Functions
function parseDeviceInfo(userAgent) {
    const deviceInfo = detectDevice(userAgent);
    const browserInfo = detectBrowser(userAgent);
    
    updateDeviceUI(deviceInfo);
    updateBrowserUI(browserInfo);
}

function detectDevice(userAgent) {
    const ua = userAgent.toLowerCase();
    
    let deviceType = 'Desktop';
    let deviceOS = 'Unknown';
    let deviceIcon = 'ri-computer-line';
    let deviceClass = 'device-desktop';
    
    // Detect device type (check tablet first, then mobile)
    if (/tablet|ipad/i.test(ua)) {
        deviceType = 'Tablet';
        deviceIcon = 'ri-tablet-line';
        deviceClass = 'device-tablet';
    } else if (/mobile|android.*mobile|iphone|ipod|phone/i.test(ua)) {
        deviceType = 'Mobile';
        deviceIcon = 'ri-smartphone-line';
        deviceClass = 'device-mobile';
    }
    
    // Detect OS
    if (/android/i.test(ua)) {
        deviceOS = 'Android';
        if (/android ([0-9\.]+)/i.test(ua)) {
            const version = ua.match(/android ([0-9\.]+)/i)[1];
            deviceOS = `Android ${version.split('.').slice(0, 2).join('.')}`;
        }
    } else if (/iphone os ([0-9_]+)/i.test(ua)) {
        const version = ua.match(/iphone os ([0-9_]+)/i)[1].replace(/_/g, '.');
        deviceOS = `iOS ${version.split('.').slice(0, 2).join('.')}`;
    } else if (/ipad.*os ([0-9_]+)/i.test(ua)) {
        const version = ua.match(/ipad.*os ([0-9_]+)/i)[1].replace(/_/g, '.');
        deviceOS = `iPadOS ${version.split('.').slice(0, 2).join('.')}`;
    } else if (/windows nt ([0-9\.]+)/i.test(ua)) {
        const version = ua.match(/windows nt ([0-9\.]+)/i)[1];
        const windowsVersions = {
            '10.0': 'Windows 10/11',
            '6.3': 'Windows 8.1',
            '6.2': 'Windows 8',
            '6.1': 'Windows 7'
        };
        deviceOS = windowsVersions[version] || `Windows NT ${version}`;
    } else if (/mac os x ([0-9_\.]+)/i.test(ua)) {
        const version = ua.match(/mac os x ([0-9_\.]+)/i)[1].replace(/_/g, '.');
        deviceOS = `macOS ${version.split('.').slice(0, 2).join('.')}`;
    } else if (/linux/i.test(ua)) {
        deviceOS = 'Linux';
        if (/ubuntu/i.test(ua)) deviceOS = 'Ubuntu Linux';
        else if (/fedora/i.test(ua)) deviceOS = 'Fedora Linux';
        else if (/debian/i.test(ua)) deviceOS = 'Debian Linux';
    }
    
    return { deviceType, deviceOS, deviceIcon, deviceClass };
}

function detectBrowser(userAgent) {
    const ua = userAgent.toLowerCase();
    
    let browserName = 'Unknown';
    let browserVersion = '';
    let browserIcon = 'ri-global-line';
    let browserClass = '';
    
    if (/edg\//i.test(ua)) {
        browserName = 'Microsoft Edge';
        browserIcon = 'ri-edge-line';
        browserClass = 'browser-edge';
        const match = ua.match(/edg\/([0-9\.]+)/i);
        if (match) browserVersion = `v${match[1].split('.')[0]}`;
    } else if (/chrome\//i.test(ua) && !/edg/i.test(ua)) {
        browserName = 'Google Chrome';
        browserIcon = 'ri-chrome-line';
        browserClass = 'browser-chrome';
        const match = ua.match(/chrome\/([0-9\.]+)/i);
        if (match) browserVersion = `v${match[1].split('.')[0]}`;
    } else if (/firefox\//i.test(ua)) {
        browserName = 'Mozilla Firefox';
        browserIcon = 'ri-firefox-line';
        browserClass = 'browser-firefox';
        const match = ua.match(/firefox\/([0-9\.]+)/i);
        if (match) browserVersion = `v${match[1].split('.')[0]}`;
    } else if (/safari\//i.test(ua) && !/chrome/i.test(ua)) {
        browserName = 'Safari';
        browserIcon = 'ri-safari-line';
        browserClass = 'browser-safari';
        const match = ua.match(/version\/([0-9\.]+)/i);
        if (match) browserVersion = `v${match[1].split('.')[0]}`;
    } else if (/opera|opr\//i.test(ua)) {
        browserName = 'Opera';
        browserIcon = 'ri-opera-line';
        browserClass = 'browser-opera';
        const match = ua.match(/(?:opera|opr)\/([0-9\.]+)/i);
        if (match) browserVersion = `v${match[1].split('.')[0]}`;
    }
    
    return { browserName, browserVersion, browserIcon, browserClass };
}

function updateDeviceUI(deviceInfo) {
    const deviceIcon = document.getElementById('device-icon');
    const deviceType = document.getElementById('device-type');
    const deviceOS = document.getElementById('device-os');
    
    if (deviceIcon && deviceType && deviceOS) {
        // Update icon
        deviceIcon.innerHTML = `<i class="${deviceInfo.deviceIcon}"></i>`;
        deviceIcon.className = `device-icon ${deviceInfo.deviceClass}`;
        
        // Update text
        deviceType.textContent = deviceInfo.deviceType;
        deviceOS.textContent = deviceInfo.deviceOS;
    }
}

function updateBrowserUI(browserInfo) {
    const browserIcon = document.getElementById('browser-icon');
    const browserName = document.getElementById('browser-name');
    const browserVersion = document.getElementById('browser-version');
    
    if (browserIcon && browserName && browserVersion) {
        // Update icon
        browserIcon.innerHTML = `<i class="${browserInfo.browserIcon}"></i>`;
        if (browserInfo.browserClass) {
            browserIcon.className = `browser-icon ${browserInfo.browserClass}`;
        }
        
        // Update text
        browserName.textContent = browserInfo.browserName;
        browserVersion.textContent = browserInfo.browserVersion || 'Version unknown';
    }
}

<?php if (!empty($vehicle_history)): ?>
// Recent History Management
let currentHistoryPage = 1;
let isHistoryLoading = false;
let hasMoreHistory = true;
const historyLimit = 5;

// Get current location data from PHP
const currentLocationId = <?= $location['id'] ?>;
const currentVinNumber = '<?= esc($location['vin_number']) ?>';
<?php else: ?>
// Recent History not available - define stub functions to prevent errors
console.log('ℹ️ Recent History variables not initialized - no history data available');

// Stub functions for when Recent History is not available
function refreshRecentHistory() {
    console.log('ℹ️ Recent History not available - refresh ignored');
}

function loadMoreHistory() {
    console.log('ℹ️ Recent History not available - load more ignored');
}

function updateHistoryPaginationUI() {
    // Silent - no need to log
}

function showHistoryLoading(show) {
    // Silent - no need to log
}

function initializeHistoryScrollDetection() {
    // Silent - no need to log
}
<?php endif; ?>

<?php if (!empty($vehicle_history)): ?>
/**
 * Refresh the Recent History section
 */
function refreshRecentHistory() {
    const refreshBtn = document.getElementById('refreshHistoryBtn');
    if (!refreshBtn) {
        console.error('Refresh button not found');
        return;
    }
    
    // Find icon - try multiple selectors for robustness
    let refreshIcon = refreshBtn.querySelector('i[data-feather="refresh-cw"]') || 
                     refreshBtn.querySelector('i.icon-xs') || 
                     refreshBtn.querySelector('i');
    
    // Show loading state
    refreshBtn.disabled = true;
    
    // Add spinning animation to icon if found
    if (refreshIcon) {
        refreshIcon.style.animation = 'spin 1s linear infinite';
        refreshIcon.style.transformOrigin = 'center';
    }
    
    // Update button text
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = `
        <i class="icon-xs me-1" style="animation: spin 1s linear infinite; transform-origin: center;">⟳</i>
        Loading...
    `;
    
    // Reset pagination
    currentHistoryPage = 1;
    hasMoreHistory = true;
    
    // Load fresh data
    loadVehicleHistory(true)
        .then(() => {
            // Success feedback
            showHistoryMessage('History refreshed successfully', 'success');
        })
        .catch((error) => {
            console.error('Error refreshing history:', error);
            showHistoryMessage('Failed to refresh history', 'error');
        })
        .finally(() => {
            // Reset button state
            refreshBtn.disabled = false;
            refreshBtn.innerHTML = originalText;
            
            // Re-initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
}

/**
 * Load more history items (for infinite scroll)
 */
function loadMoreHistory() {
    if (isHistoryLoading || !hasMoreHistory) return;
    
    currentHistoryPage++;
    loadVehicleHistory(false);
}

/**
 * Load vehicle history from API
 * @param {boolean} reset - Whether to reset the list or append
 */
async function loadVehicleHistory(reset = false) {
    if (isHistoryLoading) return;
    
    isHistoryLoading = true;
    showHistoryLoading(true);
    
    try {
        const url = `<?= base_url('api/location/vehicle-history/') ?>${currentVinNumber}?page=${currentHistoryPage}&limit=${historyLimit}&exclude_id=${currentLocationId}`;
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.success) {
            const historyList = document.getElementById('historyList');
            
            if (!historyList) {
                throw new Error('History list container not found');
            }
            
            if (reset) {
                historyList.innerHTML = '';
            }
            
            // Add new items
            if (data.data && Array.isArray(data.data)) {
                data.data.forEach(history => {
                    const historyItem = createHistoryItem(history);
                    if (historyItem) {
                        historyList.appendChild(historyItem);
                    }
                });
            }
            
            // Update pagination state
            hasMoreHistory = data.pagination.has_more;
            
            // Update UI elements
            updateHistoryPaginationUI();
            
            // Re-initialize Feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        } else {
            throw new Error(data.message || 'Failed to load history');
        }
    } catch (error) {
        console.error('Error loading vehicle history:', error);
        showHistoryMessage('Failed to load history', 'error');
    } finally {
        isHistoryLoading = false;
        showHistoryLoading(false);
    }
}

/**
 * Create a history item element
 * @param {Object} history - History data
 * @returns {HTMLElement|null}
 */
function createHistoryItem(history) {
    if (!history || !history.id) {
        console.warn('Invalid history data:', history);
        return null;
    }
    
    const div = document.createElement('div');
    div.className = 'activity-item d-flex align-items-start fade-in';
    
    let formattedDate = 'Unknown date';
    if (history.created_at) {
        try {
            const date = new Date(history.created_at);
            if (!isNaN(date.getTime())) {
                formattedDate = date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                }) + ' at ' + date.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            }
        } catch (e) {
            console.warn('Error formatting date:', history.created_at, e);
        }
    }
    
    const spotNumber = history.spot_number || 'UNKNOWN';
    const userName = history.user_full_name || history.user_name || '<?= lang('App.anonymous') ?>';
    const historyId = history.id || '';
    
    div.innerHTML = `
        <div class="activity-icon me-3">
            <div class="avatar-xs bg-light border rounded-circle d-flex align-items-center justify-content-center">
                <i data-feather="map-pin" class="icon-xs text-primary"></i>
            </div>
        </div>
        <div class="activity-content flex-grow-1">
            <h6 class="mb-1"><?= lang('App.spot') ?> ${spotNumber}</h6>
            <p class="text-muted mb-1">Location recorded at this parking spot</p>
            <small class="text-muted">
                <i data-feather="user" class="icon-xs me-1"></i>
                ${userName}
                <i data-feather="clock" class="icon-xs ms-2 me-1"></i>
                ${formattedDate}
            </small>
        </div>
        <div class="activity-actions">
            <a href="<?= base_url('location-details/') ?>${historyId}" 
               class="btn btn-outline-primary btn-sm" 
               title="<?= lang('App.view_details') ?>">
                <i data-feather="eye" class="icon-xs"></i>
            </a>
        </div>
    `;
    
    return div;
}

/**
 * Update pagination UI elements
 */
function updateHistoryPaginationUI() {
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    const noMoreDataMessage = document.getElementById('noMoreDataMessage');
    
    // Only proceed if Recent History section exists
    if (!document.getElementById('recentHistoryContainer')) {
        return; // Silent return - no need to warn if section doesn't exist
    }
    
    if (loadMoreContainer && noMoreDataMessage) {
        if (hasMoreHistory) {
            loadMoreContainer.style.display = 'block';
            noMoreDataMessage.style.display = 'none';
        } else {
            loadMoreContainer.style.display = 'none';
            noMoreDataMessage.style.display = 'block';
        }
    } else {
        console.warn('⚠️ Pagination UI elements not found in Recent History section');
    }
}

/**
 * Show/hide loading indicator
 * @param {boolean} show
 */
function showHistoryLoading(show) {
    // Only proceed if Recent History section exists
    if (!document.getElementById('recentHistoryContainer')) {
        return; // Silent return - no need to warn if section doesn't exist
    }
    
    const loadingIndicator = document.getElementById('historyLoadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = show ? 'block' : 'none';
    }
    
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.disabled = show;
    }
}

/**
 * Show temporary message
 * @param {string} message
 * @param {string} type - 'success' or 'error'
 */
function showHistoryMessage(message, type = 'info') {
    // Create toast-like notification
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i data-feather="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="icon-xs me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}

/**
 * Initialize scroll detection for infinite scroll
 */
function initializeHistoryScrollDetection() {
    const historyContainer = document.getElementById('recentHistoryContainer');
    
    if (historyContainer) {
        console.log('📜 Initializing scroll detection for Recent History');
        historyContainer.addEventListener('scroll', () => {
            const { scrollTop, scrollHeight, clientHeight } = historyContainer;
            
            // If scrolled to bottom (with 50px threshold)
            if (scrollTop + clientHeight >= scrollHeight - 50) {
                loadMoreHistory();
            }
        });
    } else {
        console.log('ℹ️ History container not found, skipping scroll detection');
    }
}
<?php endif; ?>

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($vehicle_history)): ?>
    // Initialize Recent History functionality only if section exists
    const historyList = document.getElementById('historyList');
    const refreshHistoryBtn = document.getElementById('refreshHistoryBtn');
    const recentHistoryContainer = document.getElementById('recentHistoryContainer');
    
    if (historyList && refreshHistoryBtn && recentHistoryContainer) {
        console.log('✅ Recent History section found, initializing...');
        
        // Set initial pagination UI
        try {
            updateHistoryPaginationUI();
        } catch (error) {
            console.error('Error initializing pagination UI:', error);
        }
        
        // Initialize scroll detection
        try {
            initializeHistoryScrollDetection();
        } catch (error) {
            console.error('Error initializing scroll detection:', error);
        }
        
        // Check if we need to show load more button initially
        try {
            const historyItems = document.querySelectorAll('#historyList .activity-item');
            console.log(`📊 Found ${historyItems.length} initial history items`);
        } catch (error) {
            console.error('Error checking initial history items:', error);
        }
    } else {
        console.warn('⚠️ Some Recent History elements not found, skipping initialization');
    }
    <?php else: ?>
    console.log('ℹ️ No vehicle history available, skipping Recent History initialization');
    <?php endif; ?>
});

// Go Back function
function goBack() {
    // Try history.back() first
    if (window.history.length > 1) {
        window.history.back();
    } else {
        // Fallback: go to vehicles list or dashboard
        const vehicleVin = '<?= esc($location['vin_number']) ?>';
        if (vehicleVin) {
            // Go to vehicle details page
            window.location.href = '<?= base_url('vehicles/') ?>' + vehicleVin.slice(-6);
        } else {
            // Go to dashboard as fallback
            window.location.href = '<?= base_url('dashboard') ?>';
        }
    }
}

</script>
<?= $this->endSection() ?> 