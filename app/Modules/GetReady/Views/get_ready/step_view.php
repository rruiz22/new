<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $step['name'] ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $step['name'] ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('get-ready') ?>"><?= lang('GetReady.module_title') ?></a></li>
<li class="breadcrumb-item active"><?= $step['name'] ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Notion-inspired step view styles -->
<style>
/* Import main Notion styles */
.notion-container {
    max-width: 100%;
    margin: 0 auto;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, "Apple Color Emoji", Arial, sans-serif;
}

.notion-sidebar {
    background: #f7f6f3;
    border-right: 1px solid #e9e5e1;
    min-height: calc(100vh - 100px);
    position: sticky;
    top: 0;
    padding: 16px 0;
}

.notion-sidebar-item {
    display: flex;
    align-items: center;
    padding: 4px 16px;
    margin: 2px 8px;
    border-radius: 4px;
    color: #37352f;
    text-decoration: none;
    font-size: 14px;
    font-weight: 400;
    transition: background-color 0.1s ease;
}

.notion-sidebar-item:hover {
    background-color: rgba(55, 53, 47, 0.08);
    color: #37352f;
    text-decoration: none;
}

.notion-sidebar-item.active {
    background-color: rgba(35, 131, 226, 0.15);
    color: #2383e2;
    font-weight: 500;
}

.notion-sidebar-item .step-badge {
    margin-left: auto;
    background: rgba(55, 53, 47, 0.16);
    color: #37352f;
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 16px;
    text-align: center;
    font-weight: 500;
}

.notion-sidebar-item.active .step-badge {
    background: rgba(35, 131, 226, 0.2);
    color: #2383e2;
}

.notion-main-content {
    background: #ffffff;
    min-height: calc(100vh - 100px);
    padding: 20px 24px;
}

.notion-page-title {
    font-size: 32px;
    font-weight: 700;
    color: #37352f;
    margin: 0 0 8px 0;
    line-height: 1.2;
    display: flex;
    align-items: center;
}

.step-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

.notion-subtitle {
    font-size: 14px;
    color: rgba(55, 53, 47, 0.65);
    margin: 0 0 24px 0;
    font-weight: 400;
}

/* Step-specific colors */
.step-primary { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
.step-info { background: rgba(6, 182, 212, 0.15); color: #06b6d4; }
.step-warning { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
.step-danger { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
.step-success { background: rgba(34, 197, 94, 0.15); color: #22c55e; }

/* Metrics row */
.metrics-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.metric-card {
    background: #ffffff;
    border: 1px solid rgba(55, 53, 47, 0.16);
    border-radius: 6px;
    padding: 16px;
    text-align: center;
    transition: all 0.1s ease;
}

.metric-card:hover {
    box-shadow: 0 2px 8px rgba(15, 15, 15, 0.1);
    border-color: rgba(55, 53, 47, 0.3);
}

.metric-value {
    font-size: 24px;
    font-weight: 600;
    color: #37352f;
    margin: 0 0 4px 0;
    line-height: 1.2;
}

.metric-label {
    font-size: 11px;
    color: rgba(55, 53, 47, 0.65);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Action bar */
.action-bar {
    background: #fafaf9;
    border: 1px solid rgba(55, 53, 47, 0.16);
    border-radius: 6px;
    padding: 12px 16px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
}

.action-bar-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.action-bar-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Compact buttons */
.btn-notion {
    background: #ffffff;
    border: 1px solid rgba(55, 53, 47, 0.16);
    color: #37352f;
    font-size: 13px;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 4px;
    transition: all 0.1s ease;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-notion:hover {
    background: rgba(55, 53, 47, 0.05);
    border-color: rgba(55, 53, 47, 0.3);
    color: #37352f;
    text-decoration: none;
}

.btn-notion-primary {
    background: #2383e2;
    border-color: #2383e2;
    color: #ffffff;
}

.btn-notion-primary:hover {
    background: #1a73d1;
    border-color: #1a73d1;
    color: #ffffff;
}

/* Vehicle table */
.vehicles-table-container {
    background: #ffffff;
    border: 1px solid rgba(55, 53, 47, 0.16);
    border-radius: 6px;
    overflow: hidden;
}

.vehicles-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    margin: 0;
}

.vehicles-table th {
    background: #fafaf9;
    border-bottom: 1px solid rgba(55, 53, 47, 0.16);
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: rgba(55, 53, 47, 0.65);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.vehicles-table td {
    padding: 12px;
    border-bottom: 1px solid rgba(55, 53, 47, 0.16);
    vertical-align: top;
}

.vehicles-table tbody tr:hover {
    background: rgba(55, 53, 47, 0.03);
}

.vehicles-table tbody tr:last-child td {
    border-bottom: none;
}

/* Vehicle info cell */
.vehicle-info {
    display: flex;
    flex-direction: column;
}

.vehicle-vin {
    font-weight: 600;
    color: #37352f;
    margin-bottom: 2px;
    cursor: pointer;
}

.vehicle-vin:hover {
    color: #2383e2;
    text-decoration: underline;
}

.vehicle-details {
    font-size: 11px;
    color: rgba(55, 53, 47, 0.65);
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.status-badge.success {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.status-badge.warning {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

.status-badge.danger {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 4px;
}

.action-btn {
    width: 28px;
    height: 28px;
    border-radius: 4px;
    border: 1px solid rgba(55, 53, 47, 0.16);
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.1s ease;
}

.action-btn:hover {
    background: rgba(55, 53, 47, 0.05);
    border-color: rgba(55, 53, 47, 0.3);
}

.action-btn.primary {
    background: #2383e2;
    border-color: #2383e2;
    color: #ffffff;
}

.action-btn.primary:hover {
    background: #1a73d1;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: rgba(55, 53, 47, 0.65);
}

.empty-state i {
    margin-bottom: 16px;
    opacity: 0.5;
}

/* Loading skeleton */
.loading-skeleton {
    background: #f0f0f0;
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 4px;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Mobile responsive */
@media (max-width: 768px) {
    .notion-main-content {
        padding: 16px;
    }
    
    .notion-page-title {
        font-size: 24px;
    }
    
    .metrics-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .vehicles-table {
        font-size: 12px;
    }
    
    .vehicles-table th,
    .vehicles-table td {
        padding: 8px;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="notion-container">
    <div class="row g-0">
        <!-- Sidebar Navigation -->
        <div class="col-12 col-lg-2">
            <div class="notion-sidebar">
                <a href="<?= base_url('get-ready') ?>" class="notion-sidebar-item">
                    <i data-feather="home" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.dashboard') ?>
                </a>
                
                <?php foreach ($steps as $navStep): ?>
                <a href="<?= base_url('get-ready/step/' . $navStep['slug']) ?>" 
                   class="notion-sidebar-item <?= $navStep['slug'] === $step['slug'] ? 'active' : '' ?>" 
                   data-step="<?= $navStep['slug'] ?>">
                    <i data-feather="<?= $navStep['icon'] ?>" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= $navStep['name'] ?>
                    <span class="step-badge" id="badge-<?= $navStep['slug'] ?>">0</span>
                </a>
                <?php endforeach; ?>
                
                <div style="height: 1px; background: rgba(55, 53, 47, 0.16); margin: 8px 12px;"></div>
                
                <a href="<?= base_url('get-ready/service-manager') ?>" class="notion-sidebar-item">
                    <i data-feather="users" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.service_manager') ?>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-12 col-lg-10">
            <div class="notion-main-content">
                <!-- Page Header -->
                <h1 class="notion-page-title">
                    <div class="step-icon step-<?= $step['color'] ?>">
                        <i data-feather="<?= $step['icon'] ?>" style="width: 18px; height: 18px;"></i>
                    </div>
                    <?= $step['name'] ?>
                </h1>
                <p class="notion-subtitle"><?= $step['description'] ?></p>
                
                <!-- Metrics Row -->
                <div class="metrics-row" id="stepMetrics">
                    <div class="metric-card">
                        <div class="metric-value" id="totalVehicles">
                            <div class="loading-skeleton" style="width: 40px; height: 24px;"></div>
                        </div>
                        <div class="metric-label"><?= lang('GetReady.vehicles_in_step') ?></div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-value" id="averageTime">
                            <div class="loading-skeleton" style="width: 50px; height: 24px;"></div>
                        </div>
                        <div class="metric-label"><?= lang('GetReady.average_time') ?></div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-value" id="longestWait">
                            <div class="loading-skeleton" style="width: 60px; height: 24px;"></div>
                        </div>
                        <div class="metric-label"><?= lang('GetReady.longest_wait') ?></div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-value" id="todayArrivals">
                            <div class="loading-skeleton" style="width: 30px; height: 24px;"></div>
                        </div>
                        <div class="metric-label"><?= lang('GetReady.today_arrivals') ?></div>
                    </div>
                </div>
                
                <!-- Action Bar -->
                <div class="action-bar">
                    <div class="action-bar-left">
                        <button class="btn btn-notion-primary" onclick="showAddVehicleModal()">
                            <i data-feather="plus" style="width: 14px; height: 14px;"></i>
                            <?= lang('GetReady.add_vehicle') ?>
                        </button>
                        
                        <?php if ($step['is_service_step']): ?>
                        <button class="btn btn-notion" onclick="showBulkAssignModal()">
                            <i data-feather="users" style="width: 14px; height: 14px;"></i>
                            <?= lang('GetReady.assign_tech') ?>
                        </button>
                        <?php endif; ?>
                        
                        <button class="btn btn-notion" onclick="showNFCModal()">
                            <i data-feather="smartphone" style="width: 14px; height: 14px;"></i>
                            <?= lang('GetReady.scan_nfc') ?>
                        </button>
                    </div>
                    
                    <div class="action-bar-right">
                        <button class="btn btn-notion" onclick="refreshStepData()">
                            <i data-feather="refresh-cw" style="width: 14px; height: 14px;"></i>
                            <?= lang('GetReady.refresh') ?>
                        </button>
                        
                        <button class="btn btn-notion" onclick="exportStepData()">
                            <i data-feather="download" style="width: 14px; height: 14px;"></i>
                            Export
                        </button>
                    </div>
                </div>
                
                <!-- Vehicles Table -->
                <div class="vehicles-table-container">
                    <table class="vehicles-table" id="vehiclesTable">
                        <thead>
                            <tr>
                                <th><?= lang('GetReady.vehicle_info') ?></th>
                                <th><?= lang('GetReady.client') ?></th>
                                <th><?= lang('GetReady.days_in_step') ?></th>
                                <th><?= lang('GetReady.total_time') ?></th>
                                <?php if ($step['is_service_step']): ?>
                                <th><?= lang('GetReady.assigned_to') ?></th>
                                <?php endif; ?>
                                <th><?= lang('GetReady.location') ?></th>
                                <th><?= lang('GetReady.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id="vehiclesTableBody">
                            <!-- Loading state -->
                            <?php for ($i = 0; $i < 5; $i++): ?>
                            <tr>
                                <td>
                                    <div class="vehicle-info">
                                        <div class="loading-skeleton" style="width: 120px; height: 14px; margin-bottom: 4px;"></div>
                                        <div class="loading-skeleton" style="width: 100px; height: 11px;"></div>
                                    </div>
                                </td>
                                <td><div class="loading-skeleton" style="width: 80px; height: 13px;"></div></td>
                                <td><div class="loading-skeleton" style="width: 60px; height: 13px;"></div></td>
                                <td><div class="loading-skeleton" style="width: 50px; height: 13px;"></div></td>
                                <?php if ($step['is_service_step']): ?>
                                <td><div class="loading-skeleton" style="width: 70px; height: 13px;"></div></td>
                                <?php endif; ?>
                                <td><div class="loading-skeleton" style="width: 90px; height: 13px;"></div></td>
                                <td>
                                    <div class="action-buttons">
                                        <div class="loading-skeleton" style="width: 28px; height: 28px; border-radius: 4px;"></div>
                                        <div class="loading-skeleton" style="width: 28px; height: 28px; border-radius: 4px;"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vehicle Modal -->
<div class="modal fade" id="vehicleModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <!-- Modal content will be loaded here -->
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Step data
const currentStep = {
    slug: '<?= $step['slug'] ?>',
    name: '<?= $step['name'] ?>',
    isServiceStep: <?= $step['is_service_step'] ? 'true' : 'false' ?>
};

// Initialize step view
document.addEventListener('DOMContentLoaded', function() {
    loadStepData();
    
    // Refresh data every 30 seconds
    setInterval(loadStepData, 30000);
    
    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});

// Load step data
async function loadStepData() {
    await Promise.all([
        loadStepMetrics(),
        loadVehicles()
    ]);
}

// Load step metrics
async function loadStepMetrics() {
    try {
        const response = await fetch(`<?= base_url('get-ready/step-metrics/') ?>${currentStep.slug}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Failed to load metrics');
        
        const data = await response.json();
        
        if (data.success) {
            updateMetrics(data.metrics);
        }
    } catch (error) {
        console.error('Error loading metrics:', error);
    }
}

// Load vehicles for this step
async function loadVehicles() {
    try {
        const response = await fetch(`<?= base_url('api/get-ready/vehicles/') ?>${currentStep.slug}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Failed to load vehicles');
        
        const data = await response.json();
        
        if (data.data) {
            renderVehiclesTable(data.data);
        }
    } catch (error) {
        console.error('Error loading vehicles:', error);
        showEmptyState();
    }
}

// Update metrics
function updateMetrics(metrics) {
    document.getElementById('totalVehicles').textContent = metrics.total_vehicles || 0;
    document.getElementById('averageTime').textContent = metrics.average_time_formatted || '0m';
    document.getElementById('longestWait').textContent = metrics.longest_wait ? 
        `${metrics.longest_wait.days}d` : '0d';
    document.getElementById('todayArrivals').textContent = metrics.today_arrivals || 0;
}

// Render vehicles table
function renderVehiclesTable(vehicles) {
    const tbody = document.getElementById('vehiclesTableBody');
    
    if (vehicles.length === 0) {
        showEmptyState();
        return;
    }
    
    tbody.innerHTML = vehicles.map(vehicle => `
        <tr>
            <td>
                <div class="vehicle-info">
                    <div class="vehicle-vin" onclick="openVehicleModal(${vehicle.id})">
                        ${vehicle.vin_number}
                    </div>
                    <div class="vehicle-details">
                        ${vehicle.year || ''} ${vehicle.make || ''} ${vehicle.model || ''}
                    </div>
                </div>
            </td>
            <td>${vehicle.client_name || 'Unknown'}</td>
            <td>
                <span class="status-badge ${getDaysBadgeClass(vehicle.days_in_step)}">
                    ${vehicle.days_in_step || 0} days
                </span>
            </td>
            <td>${vehicle.total_time_formatted || '0m'}</td>
            ${currentStep.isServiceStep ? `<td>${vehicle.assigned_tech_name || 'Unassigned'}</td>` : ''}
            <td>${vehicle.current_location || 'Not set'}</td>
            <td>
                <div class="action-buttons">
                    <div class="action-btn primary" onclick="openVehicleModal(${vehicle.id})" title="<?= lang('GetReady.view_details') ?>">
                        <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                    </div>
                    <div class="action-btn" onclick="moveToNextStep(${vehicle.id})" title="<?= lang('GetReady.move_to_next') ?>">
                        <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                    </div>
                    ${currentStep.isServiceStep ? `
                    <div class="action-btn" onclick="assignTechModal(${vehicle.id})" title="<?= lang('GetReady.assign_tech') ?>">
                        <i data-feather="user-plus" style="width: 14px; height: 14px;"></i>
                    </div>` : ''}
                </div>
            </td>
        </tr>
    `).join('');
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Show empty state
function showEmptyState() {
    const tbody = document.getElementById('vehiclesTableBody');
    const colspan = currentStep.isServiceStep ? 7 : 6;
    
    tbody.innerHTML = `
        <tr>
            <td colspan="${colspan}">
                <div class="empty-state">
                    <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                    <div style="font-size: 16px; font-weight: 500; margin-bottom: 8px;">No vehicles in ${currentStep.name}</div>
                    <div style="font-size: 14px; color: rgba(55, 53, 47, 0.65);">Vehicles will appear here when they enter this step</div>
                </div>
            </td>
        </tr>
    `;
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Helper functions
function getDaysBadgeClass(days) {
    if (!days) return 'success';
    if (days <= 1) return 'success';
    if (days <= 3) return 'warning';
    return 'danger';
}

// Modal functions
async function openVehicleModal(vehicleId) {
    try {
        const response = await fetch(`<?= base_url('api/get-ready/vehicle-modal/') ?>${vehicleId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Failed to load vehicle modal');
        
        const data = await response.json();
        
        if (data.success) {
            document.querySelector('#vehicleModal .modal-content').innerHTML = data.html;
            
            const modal = new bootstrap.Modal(document.getElementById('vehicleModal'));
            modal.show();
            
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    } catch (error) {
        console.error('Error loading vehicle modal:', error);
        alert('Error loading vehicle details');
    }
}

function moveToNextStep(vehicleId) {
    // TODO: Implement move to next step
    console.log('Move vehicle to next step:', vehicleId);
}

function assignTechModal(vehicleId) {
    // TODO: Implement tech assignment modal
    console.log('Assign tech to vehicle:', vehicleId);
}

function showAddVehicleModal() {
    // TODO: Implement add vehicle modal
    console.log('Show add vehicle modal');
}

function showBulkAssignModal() {
    // TODO: Implement bulk assign modal
    console.log('Show bulk assign modal');
}

function showNFCModal() {
    // TODO: Implement NFC modal
    window.open('<?= base_url('nfc/get-ready') ?>', '_blank');
}

function refreshStepData() {
    loadStepData();
}

function exportStepData() {
    // TODO: Implement export functionality
    console.log('Export step data');
}
</script>
<?= $this->endSection() ?>