<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('GetReady.service_manager') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('GetReady.service_manager') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('get-ready') ?>"><?= lang('GetReady.module_title') ?></a></li>
<li class="breadcrumb-item active"><?= lang('GetReady.service_manager') ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Notion-inspired Service Manager styles -->
<style>
/* Main Notion styles */
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

.notion-subtitle {
    font-size: 14px;
    color: rgba(55, 53, 47, 0.65);
    margin: 0 0 24px 0;
    font-weight: 400;
}

/* Tech workload grid */
.tech-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}

.tech-card {
    background: #ffffff;
    border: 1px solid rgba(55, 53, 47, 0.16);
    border-radius: 8px;
    padding: 20px;
    transition: all 0.1s ease;
    position: relative;
}

.tech-card:hover {
    box-shadow: 0 4px 12px rgba(15, 15, 15, 0.1);
    border-color: rgba(55, 53, 47, 0.3);
}

.tech-header {
    display: flex;
    align-items: center;
    margin-bottom: 16px;
}

.tech-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #2383e2;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: 600;
    font-size: 16px;
    margin-right: 12px;
}

.tech-info h6 {
    font-size: 16px;
    font-weight: 600;
    color: #37352f;
    margin: 0 0 2px 0;
}

.tech-info p {
    font-size: 12px;
    color: rgba(55, 53, 47, 0.65);
    margin: 0;
}

.workload-meter {
    background: rgba(55, 53, 47, 0.1);
    border-radius: 8px;
    height: 8px;
    margin: 12px 0;
    overflow: hidden;
    position: relative;
}

.workload-fill {
    height: 100%;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.workload-fill.low { background: #22c55e; }
.workload-fill.medium { background: #f59e0b; }
.workload-fill.high { background: #ef4444; }

.workload-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    margin-bottom: 16px;
}

.workload-count {
    font-weight: 600;
    color: #37352f;
}

.workload-label {
    color: rgba(55, 53, 47, 0.65);
}

/* Vehicle assignment area */
.assignment-area {
    background: #fafaf9;
    border: 2px dashed rgba(55, 53, 47, 0.2);
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.1s ease;
}

.assignment-area.drag-over {
    border-color: #2383e2;
    background: rgba(35, 131, 226, 0.05);
}

.assignment-area.has-vehicles {
    background: #ffffff;
    border-style: solid;
    border-color: rgba(55, 53, 47, 0.16);
    padding: 8px;
    min-height: auto;
}

.vehicle-chip {
    display: inline-flex;
    align-items: center;
    background: rgba(35, 131, 226, 0.1);
    color: #2383e2;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    margin: 2px;
    cursor: pointer;
    transition: all 0.1s ease;
}

.vehicle-chip:hover {
    background: rgba(35, 131, 226, 0.15);
}

.vehicle-chip .remove-btn {
    margin-left: 4px;
    cursor: pointer;
    color: rgba(35, 131, 226, 0.7);
}

.vehicle-chip .remove-btn:hover {
    color: #2383e2;
}

/* Unassigned vehicles section */
.unassigned-section {
    background: #ffffff;
    border: 1px solid rgba(55, 53, 47, 0.16);
    border-radius: 8px;
    margin-bottom: 24px;
    overflow: hidden;
}

.section-header {
    background: #fafaf9;
    border-bottom: 1px solid rgba(55, 53, 47, 0.16);
    padding: 16px 20px;
    font-size: 16px;
    font-weight: 600;
    color: #37352f;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.section-content {
    padding: 16px 20px;
}

.unassigned-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
}

.unassigned-vehicle {
    background: #ffffff;
    border: 1px solid rgba(55, 53, 47, 0.16);
    border-radius: 6px;
    padding: 12px;
    cursor: grab;
    transition: all 0.1s ease;
    position: relative;
}

.unassigned-vehicle:hover {
    box-shadow: 0 2px 8px rgba(15, 15, 15, 0.1);
    border-color: rgba(55, 53, 47, 0.3);
}

.unassigned-vehicle.dragging {
    opacity: 0.5;
    cursor: grabbing;
}

.vehicle-vin {
    font-size: 14px;
    font-weight: 600;
    color: #37352f;
    margin-bottom: 4px;
}

.vehicle-details {
    font-size: 11px;
    color: rgba(55, 53, 47, 0.65);
    margin-bottom: 6px;
}

.vehicle-client {
    font-size: 12px;
    color: #37352f;
    margin-bottom: 6px;
}

.vehicle-days {
    display: inline-flex;
    align-items: center;
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 500;
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

/* Empty states */
.empty-state {
    text-align: center;
    padding: 32px;
    color: rgba(55, 53, 47, 0.65);
}

.empty-state i {
    margin-bottom: 12px;
    opacity: 0.5;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .notion-main-content {
        padding: 16px;
    }
    
    .notion-page-title {
        font-size: 24px;
    }
    
    .tech-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .unassigned-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }
}

/* Drag and drop visual feedback */
.drag-indicator {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(35, 131, 226, 0.1);
    border: 2px dashed #2383e2;
    border-radius: 6px;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: #2383e2;
    font-weight: 500;
}

.drag-indicator.show {
    display: flex;
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
                
                <?php 
                $steps = [
                    ['name' => lang('GetReady.in_transit'), 'slug' => 'in_transit', 'icon' => 'truck'],
                    ['name' => lang('GetReady.in_detail'), 'slug' => 'in_detail', 'icon' => 'droplet'],
                    ['name' => lang('GetReady.in_service'), 'slug' => 'in_service', 'icon' => 'tool'],
                    ['name' => lang('GetReady.in_bodyshop'), 'slug' => 'in_bodyshop', 'icon' => 'settings']
                ];
                ?>
                
                <?php foreach ($steps as $step): ?>
                <a href="<?= base_url('get-ready/step/' . $step['slug']) ?>" class="notion-sidebar-item">
                    <i data-feather="<?= $step['icon'] ?>" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= $step['name'] ?>
                    <span class="step-badge" id="badge-<?= $step['slug'] ?>">0</span>
                </a>
                <?php endforeach; ?>
                
                <div style="height: 1px; background: rgba(55, 53, 47, 0.16); margin: 8px 12px;"></div>
                
                <a href="<?= base_url('get-ready/service-manager') ?>" class="notion-sidebar-item active">
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
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: rgba(99, 102, 241, 0.15); color: #6366f1; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                        <i data-feather="users" style="width: 18px; height: 18px;"></i>
                    </div>
                    <?= lang('GetReady.service_manager') ?>
                </h1>
                <p class="notion-subtitle">Assign vehicles to technicians and manage service workloads</p>
                
                <!-- Action Bar -->
                <div class="action-bar">
                    <div>
                        <button class="btn btn-notion-primary" onclick="refreshServiceData()">
                            <i data-feather="refresh-cw" style="width: 14px; height: 14px;"></i>
                            <?= lang('GetReady.refresh') ?>
                        </button>
                        
                        <button class="btn btn-notion" onclick="showBulkAssignModal()">
                            <i data-feather="layers" style="width: 14px; height: 14px;"></i>
                            Bulk Assign
                        </button>
                    </div>
                    
                    <div>
                        <button class="btn btn-notion" onclick="exportServiceData()">
                            <i data-feather="download" style="width: 14px; height: 14px;"></i>
                            Export Report
                        </button>
                    </div>
                </div>
                
                <!-- Unassigned Vehicles Section -->
                <div class="unassigned-section">
                    <div class="section-header">
                        <div style="display: flex; align-items: center;">
                            <i data-feather="inbox" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                            <?= lang('GetReady.unassigned_vehicles') ?>
                            <span id="unassignedCount" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; font-size: 11px; padding: 2px 6px; border-radius: 10px; margin-left: 8px; font-weight: 500;">
                                0
                            </span>
                        </div>
                        
                        <div>
                            <button class="btn btn-notion" onclick="addVehicleToService()">
                                <i data-feather="plus" style="width: 14px; height: 14px;"></i>
                                Add Vehicle
                            </button>
                        </div>
                    </div>
                    
                    <div class="section-content">
                        <div class="unassigned-grid" id="unassignedVehicles">
                            <!-- Loading state -->
                            <?php for ($i = 0; $i < 4; $i++): ?>
                            <div class="unassigned-vehicle">
                                <div class="loading-skeleton" style="width: 120px; height: 14px; margin-bottom: 6px;"></div>
                                <div class="loading-skeleton" style="width: 100px; height: 11px; margin-bottom: 8px;"></div>
                                <div class="loading-skeleton" style="width: 80px; height: 12px; margin-bottom: 6px;"></div>
                                <div class="loading-skeleton" style="width: 60px; height: 10px;"></div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Technician Workload Grid -->
                <div class="tech-grid" id="techGrid">
                    <!-- Loading state -->
                    <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="tech-card">
                        <div class="tech-header">
                            <div class="tech-avatar">
                                <div class="loading-skeleton" style="width: 16px; height: 16px; border-radius: 50%;"></div>
                            </div>
                            <div class="tech-info">
                                <div class="loading-skeleton" style="width: 100px; height: 16px; margin-bottom: 4px;"></div>
                                <div class="loading-skeleton" style="width: 80px; height: 12px;"></div>
                            </div>
                        </div>
                        
                        <div class="workload-stats">
                            <div class="loading-skeleton" style="width: 60px; height: 12px;"></div>
                            <div class="loading-skeleton" style="width: 40px; height: 12px;"></div>
                        </div>
                        
                        <div class="workload-meter">
                            <div class="loading-skeleton" style="width: 60%; height: 8px;"></div>
                        </div>
                        
                        <div class="assignment-area">
                            <div class="loading-skeleton" style="width: 120px; height: 12px;"></div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
                
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Service Manager data
let technicians = [];
let unassignedVehicles = [];

// Drag and drop state
let draggedVehicle = null;

// Initialize Service Manager
document.addEventListener('DOMContentLoaded', function() {
    loadServiceData();
    
    // Refresh data every 60 seconds
    setInterval(loadServiceData, 60000);
    
    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});

// Load service manager data
async function loadServiceData() {
    await Promise.all([
        loadTechnicians(),
        loadUnassignedVehicles()
    ]);
}

// Load technicians and their workloads
async function loadTechnicians() {
    try {
        const response = await fetch('<?= base_url('api/get-ready/available-techs') ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            technicians = data.technicians;
            renderTechnicians();
        }
    } catch (error) {
        console.error('Error loading technicians:', error);
    }
}

// Load unassigned vehicles
async function loadUnassignedVehicles() {
    try {
        const response = await fetch('<?= base_url('get-ready/service-manager-content') ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            unassignedVehicles = data.unassigned_vehicles;
            renderUnassignedVehicles();
        }
    } catch (error) {
        console.error('Error loading unassigned vehicles:', error);
    }
}

// Render technicians
function renderTechnicians() {
    const techGrid = document.getElementById('techGrid');
    
    if (technicians.length === 0) {
        techGrid.innerHTML = `
            <div class="empty-state">
                <i data-feather="users" style="width: 48px; height: 48px;"></i>
                <div style="font-size: 16px; font-weight: 500; margin-bottom: 8px;">No technicians available</div>
                <div style="font-size: 14px;">Add technicians to start managing assignments</div>
            </div>
        `;
        feather.replace();
        return;
    }
    
    techGrid.innerHTML = technicians.map(tech => {
        const workloadLevel = getWorkloadLevel(tech.current_workload);
        const initials = tech.username.substring(0, 2).toUpperCase();
        
        return `
            <div class="tech-card" data-tech-id="${tech.id}">
                <div class="tech-header">
                    <div class="tech-avatar">${initials}</div>
                    <div class="tech-info">
                        <h6>${tech.username}</h6>
                        <p>${tech.email || 'Technician'}</p>
                    </div>
                </div>
                
                <div class="workload-stats">
                    <span class="workload-count">${tech.current_workload} vehicles</span>
                    <span class="workload-label">Current Load</span>
                </div>
                
                <div class="workload-meter">
                    <div class="workload-fill ${workloadLevel}" style="width: ${Math.min(tech.current_workload * 20, 100)}%;"></div>
                </div>
                
                <div class="assignment-area" 
                     ondragover="allowDrop(event)" 
                     ondrop="dropVehicle(event, ${tech.id})"
                     data-tech-id="${tech.id}">
                    <div style="color: rgba(55, 53, 47, 0.65); font-size: 12px;">
                        <i data-feather="plus" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                        Drop vehicles here to assign
                    </div>
                </div>
                
                <div class="drag-indicator" id="drag-indicator-${tech.id}">
                    Assign to ${tech.username}
                </div>
            </div>
        `;
    }).join('');
    
    // Re-initialize feather icons
    feather.replace();
}

// Render unassigned vehicles
function renderUnassignedVehicles() {
    const container = document.getElementById('unassignedVehicles');
    const countBadge = document.getElementById('unassignedCount');
    
    countBadge.textContent = unassignedVehicles.length;
    
    if (unassignedVehicles.length === 0) {
        container.innerHTML = `
            <div class="empty-state" style="grid-column: 1 / -1;">
                <i data-feather="check-circle" style="width: 48px; height: 48px; color: #22c55e;"></i>
                <div style="font-size: 16px; font-weight: 500; margin-bottom: 8px;">All vehicles assigned</div>
                <div style="font-size: 14px;">Great job! All service vehicles have been assigned to technicians.</div>
            </div>
        `;
        feather.replace();
        return;
    }
    
    container.innerHTML = unassignedVehicles.map(vehicle => {
        const daysInSystem = vehicle.days_in_system || 0;
        
        return `
            <div class="unassigned-vehicle" 
                 draggable="true" 
                 ondragstart="dragStart(event, ${vehicle.id})"
                 data-vehicle-id="${vehicle.id}">
                <div class="vehicle-vin">${vehicle.vin_number}</div>
                <div class="vehicle-details">${vehicle.year || ''} ${vehicle.make || ''} ${vehicle.model || ''}</div>
                <div class="vehicle-client">${vehicle.client_name}</div>
                <div class="vehicle-days">${daysInSystem} days in system</div>
            </div>
        `;
    }).join('');
}

// Drag and drop functions
function dragStart(event, vehicleId) {
    draggedVehicle = vehicleId;
    event.dataTransfer.effectAllowed = 'move';
    event.target.classList.add('dragging');
    
    // Show drag indicators
    technicians.forEach(tech => {
        const indicator = document.getElementById(`drag-indicator-${tech.id}`);
        if (indicator) indicator.classList.add('show');
    });
}

function allowDrop(event) {
    event.preventDefault();
    const techCard = event.currentTarget.closest('.tech-card');
    const assignmentArea = event.currentTarget;
    
    if (techCard && assignmentArea) {
        assignmentArea.classList.add('drag-over');
    }
}

function dragEnd(event) {
    event.target.classList.remove('dragging');
    
    // Hide drag indicators
    technicians.forEach(tech => {
        const indicator = document.getElementById(`drag-indicator-${tech.id}`);
        if (indicator) indicator.classList.remove('show');
    });
    
    // Remove drag-over class from all assignment areas
    document.querySelectorAll('.assignment-area').forEach(area => {
        area.classList.remove('drag-over');
    });
}

function dropVehicle(event, techId) {
    event.preventDefault();
    const assignmentArea = event.currentTarget;
    
    assignmentArea.classList.remove('drag-over');
    
    if (draggedVehicle) {
        assignVehicleToTech(draggedVehicle, techId);
        draggedVehicle = null;
    }
}

// Assign vehicle to technician
async function assignVehicleToTech(vehicleId, techId) {
    try {
        const response = await fetch(`<?= base_url('api/get-ready/assign-tech/') ?>${vehicleId}/${techId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            // Show success feedback
            showNotification('Vehicle assigned successfully!', 'success');
            
            // Refresh data
            loadServiceData();
        } else {
            showNotification('Failed to assign vehicle', 'error');
        }
    } catch (error) {
        console.error('Error assigning vehicle:', error);
        showNotification('Error assigning vehicle', 'error');
    }
}

// Helper functions
function getWorkloadLevel(workload) {
    if (workload <= 2) return 'low';
    if (workload <= 4) return 'medium';
    return 'high';
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#22c55e' : type === 'error' ? '#ef4444' : '#2383e2'};
        color: white;
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Action functions
function refreshServiceData() {
    // Show loading state
    document.getElementById('unassignedCount').textContent = '...';
    
    loadServiceData();
}

function showBulkAssignModal() {
    // TODO: Implement bulk assign modal
    console.log('Show bulk assign modal');
}

function addVehicleToService() {
    // TODO: Implement add vehicle modal
    console.log('Add vehicle to service');
}

function exportServiceData() {
    // TODO: Implement export functionality
    console.log('Export service data');
}

// Add drag end event listeners
document.addEventListener('dragend', dragEnd);
</script>
<?= $this->endSection() ?>