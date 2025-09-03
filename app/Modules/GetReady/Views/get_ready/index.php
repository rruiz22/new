<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('GetReady.get_ready_dashboard') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('GetReady.get_ready_dashboard') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('get-ready') ?>"><?= lang('GetReady.module_title') ?></a></li>
<li class="breadcrumb-item active"><?= lang('GetReady.dashboard') ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Notion-inspired compact styles -->
<style>
/* Notion-style compact design */
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
    position: relative;
}

.notion-sidebar-item:hover {
    background-color: rgba(55, 53, 47, 0.08);
    color: #37352f;
    text-decoration: none;
}

.notion-sidebar-item.active {
    background-color: rgba(35, 131, 226, 0.15);
    color: #2383e2;
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
    margin: 0 0 16px 0;
    line-height: 1.2;
}

.notion-subtitle {
    font-size: 14px;
    color: rgba(55, 53, 47, 0.65);
    margin: 0 0 32px 0;
    font-weight: 400;
}

/* Compact metrics cards */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}

.metric-card {
    background: #ffffff;
    border: 1px solid rgba(55, 53, 47, 0.16);
    border-radius: 6px;
    padding: 16px;
    transition: all 0.1s ease;
}

.metric-card:hover {
    box-shadow: 0 2px 8px rgba(15, 15, 15, 0.1);
    border-color: rgba(55, 53, 47, 0.3);
}

.metric-value {
    font-size: 28px;
    font-weight: 600;
    color: #37352f;
    margin: 0 0 4px 0;
    line-height: 1.2;
}

.metric-label {
    font-size: 12px;
    color: rgba(55, 53, 47, 0.65);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.metric-change {
    font-size: 11px;
    margin-top: 4px;
    padding: 2px 6px;
    border-radius: 3px;
    font-weight: 500;
}

.metric-change.positive {
    background: rgba(0, 135, 107, 0.1);
    color: #00876b;
}

.metric-change.negative {
    background: rgba(235, 87, 87, 0.1);
    color: #eb5757;
}

/* Content sections */
.notion-section {
    background: #ffffff;
    border: 1px solid rgba(55, 53, 47, 0.16);
    border-radius: 6px;
    margin-bottom: 24px;
    overflow: hidden;
}

.notion-section-header {
    background: #fafaf9;
    border-bottom: 1px solid rgba(55, 53, 47, 0.16);
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 600;
    color: #37352f;
}

.notion-section-content {
    padding: 0;
}

/* Compact table styles */
.notion-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.notion-table th,
.notion-table td {
    padding: 8px 12px;
    border-bottom: 1px solid rgba(55, 53, 47, 0.16);
    text-align: left;
}

.notion-table th {
    background: #fafaf9;
    font-weight: 600;
    color: rgba(55, 53, 47, 0.65);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.notion-table tbody tr:hover {
    background: rgba(55, 53, 47, 0.05);
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
}

.btn-notion:hover {
    background: rgba(55, 53, 47, 0.05);
    border-color: rgba(55, 53, 47, 0.3);
    color: #37352f;
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

/* Timer styles */
.timer-display {
    background: #2383e2;
    border-radius: 8px;
    padding: 16px;
    color: #ffffff;
    text-align: center;
    margin-bottom: 24px;
}

.timer-value {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 4px 0;
    font-family: "SF Mono", Monaco, "Cascadia Code", "Roboto Mono", Consolas, "Courier New", monospace;
}

.timer-label {
    font-size: 12px;
    opacity: 0.9;
    font-weight: 500;
}

/* Activity feed */
.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 8px 16px;
    border-bottom: 1px solid rgba(55, 53, 47, 0.16);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(55, 53, 47, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
    min-width: 0;
}

.activity-description {
    font-size: 13px;
    color: #37352f;
    margin: 0 0 2px 0;
}

.activity-time {
    font-size: 11px;
    color: rgba(55, 53, 47, 0.65);
}

/* Mobile responsive - Full Responsive Design */
@media (max-width: 768px) {
    .notion-container {
        padding: 0 8px;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .notion-main-content {
        padding: 16px;
    }
    
    .notion-page-title {
        font-size: 24px;
    }
    
    /* Mobile sidebar - stack vertically */
    .notion-sidebar {
        display: none; /* Hide on mobile, use collapsed system sidebar */
    }
    
    .col-12.col-lg-2 {
        display: none;
    }
    
    .col-12.col-lg-10 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    /* Mobile cards */
    .notion-card {
        margin-bottom: 12px;
        padding: 12px;
    }
    
    .notion-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .notion-card-actions {
        width: 100%;
        justify-content: flex-start;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    /* Mobile timer */
    .timer-display {
        text-align: center;
        padding: 16px 12px;
    }
    
    .timer-value {
        font-size: 24px !important;
    }
    
    .timer-label {
        font-size: 12px;
    }
    
    /* Mobile buttons */
    .btn-group-mobile {
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 8px;
    }
    
    .btn-group-mobile .btn {
        width: 100%;
        justify-content: center;
        padding: 12px;
        font-size: 14px;
    }
    
    /* Mobile table */
    .table-responsive {
        border-radius: 8px;
        margin: 0 -8px;
    }
    
    .table {
        font-size: 13px;
        margin-bottom: 0;
    }
    
    .table th,
    .table td {
        padding: 8px 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
    }
    
    /* Step badges mobile */
    .step-badge {
        font-size: 11px;
        padding: 2px 6px;
        min-width: 24px;
    }
    
    /* Mobile modal adjustments */
    .modal-dialog {
        margin: 0;
        max-width: 100%;
        height: 100vh;
    }
    
    .modal-content {
        height: 100vh;
        border-radius: 0;
    }
}

/* Extra small screens (phones in portrait) */
@media (max-width: 480px) {
    .notion-container {
        padding: 0 4px;
    }
    
    .notion-main-content {
        padding: 12px;
    }
    
    .notion-card {
        padding: 8px;
        margin-bottom: 8px;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .timer-value {
        font-size: 20px !important;
    }
    
    .notion-card-header h6 {
        font-size: 14px;
    }
    
    .btn {
        font-size: 12px;
        padding: 8px 12px;
    }
    
    /* Ultra compact table for very small screens */
    .table th,
    .table td {
        padding: 6px 4px;
        font-size: 12px;
        max-width: 80px;
    }
    
    /* Hide less critical columns */
    .table .d-none-xs {
        display: none !important;
    }
}

/* Touch-friendly adjustments for all touch devices */
@media (hover: none) and (pointer: coarse) {
    .notion-sidebar-item,
    .btn,
    .badge {
        min-height: 44px;
        display: flex;
        align-items: center;
    }
    
    .btn {
        padding: 12px 16px;
        font-size: 14px;
    }
    
    .table tbody tr {
        cursor: pointer;
    }
    
    .table tbody td {
        padding: 12px 8px;
        min-height: 44px;
    }
    
    /* Larger tap areas for step badges */
    .step-badge {
        min-width: 44px;
        min-height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
}

/* Loading states */
.loading-skeleton {
    background: #f0f0f0;
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="notion-container">
    <div class="row g-0">
        <!-- Notion-style Sidebar -->
        <div class="col-12 col-lg-2">
            <div class="notion-sidebar">
                <a href="<?= base_url('get-ready') ?>" class="notion-sidebar-item active">
                    <i data-feather="home" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.dashboard') ?>
                </a>
                
                <?php foreach ($steps as $step): ?>
                <a href="<?= base_url('get-ready/step/' . $step['slug']) ?>" class="notion-sidebar-item" data-step="<?= $step['slug'] ?>">
                    <i data-feather="<?= $step['icon'] ?>" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= $step['name'] ?>
                    <span class="step-badge" id="badge-<?= $step['slug'] ?>">0</span>
                </a>
                <?php endforeach; ?>
                
                <div style="height: 1px; background: rgba(55, 53, 47, 0.16); margin: 8px 12px;"></div>
                
                <a href="<?= base_url('get-ready/service-manager') ?>" class="notion-sidebar-item">
                    <i data-feather="users" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.service_manager') ?>
                </a>
                
                <a href="#" class="notion-sidebar-item" onclick="refreshAllData()">
                    <i data-feather="refresh-cw" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                    <?= lang('GetReady.refresh') ?>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-12 col-lg-10">
            <div class="notion-main-content">
                <!-- Page Header -->
                <h1 class="notion-page-title"><?= lang('GetReady.get_ready_dashboard') ?></h1>
                <p class="notion-subtitle"><?= lang('GetReady.module_description') ?></p>
                
                <!-- Real-time Timer Display -->
                <div class="timer-display" id="globalTimer">
                    <div class="timer-value" id="timerValue">00:00:00</div>
                    <div class="timer-label"><?= lang('GetReady.total_elapsed') ?></div>
                </div>
                
                <!-- Metrics Cards -->
                <div class="metrics-grid" id="metricsGrid">
                    <div class="metric-card">
                        <div class="metric-value" id="totalActiveCount">
                            <div class="loading-skeleton" style="width: 40px; height: 28px; border-radius: 4px;"></div>
                        </div>
                        <div class="metric-label"><?= lang('GetReady.total_active') ?></div>
                        <div class="metric-change positive" id="totalActiveChange" style="display: none;">+12%</div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-value" id="completedTodayCount">
                            <div class="loading-skeleton" style="width: 30px; height: 28px; border-radius: 4px;"></div>
                        </div>
                        <div class="metric-label"><?= lang('GetReady.completed_today') ?></div>
                        <div class="metric-change positive" id="completedTodayChange" style="display: none;">+24%</div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-value" id="overdueCount">
                            <div class="loading-skeleton" style="width: 20px; height: 28px; border-radius: 4px;"></div>
                        </div>
                        <div class="metric-label"><?= lang('GetReady.overdue_vehicles') ?></div>
                        <div class="metric-change negative" id="overdueChange" style="display: none;">-8%</div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-value" id="avgCompletionTime">
                            <div class="loading-skeleton" style="width: 60px; height: 28px; border-radius: 4px;"></div>
                        </div>
                        <div class="metric-label"><?= lang('GetReady.average_time') ?></div>
                        <div class="metric-change positive" id="avgTimeChange" style="display: none;">-5%</div>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <div class="notion-section">
                    <div class="notion-section-header">
                        <i data-feather="activity" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                        <?= lang('GetReady.recent_activities') ?>
                    </div>
                    <div class="notion-section-content">
                        <div id="activityFeed">
                            <!-- Loading state -->
                            <?php for ($i = 0; $i < 5; $i++): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <div class="loading-skeleton" style="width: 12px; height: 12px; border-radius: 50%;"></div>
                                </div>
                                <div class="activity-content">
                                    <div class="loading-skeleton" style="width: 200px; height: 13px; border-radius: 2px; margin-bottom: 4px;"></div>
                                    <div class="loading-skeleton" style="width: 80px; height: 11px; border-radius: 2px;"></div>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="notion-section">
                    <div class="notion-section-header">
                        <i data-feather="zap" style="width: 16px; height: 16px; margin-right: 8px;"></i>
                        <?= lang('GetReady.quick_actions') ?>
                    </div>
                    <div class="notion-section-content" style="padding: 16px;">
                        <div class="row g-2">
                            <div class="col-auto">
                                <button class="btn btn-notion-primary" onclick="showAddVehicleModal()">
                                    <i data-feather="plus" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                                    <?= lang('GetReady.add_vehicle') ?>
                                </button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-notion" onclick="window.open('<?= base_url('nfc/get-ready') ?>', '_blank')">
                                    <i data-feather="smartphone" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                                    <?= lang('GetReady.scan_nfc') ?>
                                </button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-notion" onclick="showAnalyticsModal()">
                                    <i data-feather="bar-chart-2" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                                    <?= lang('GetReady.analytics') ?>
                                </button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-notion" onclick="exportData()">
                                    <i data-feather="download" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                                    Export Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal content will be loaded here -->
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Global timer functionality
let globalTimer = {
    startTime: null,
    totalSeconds: 0,
    intervalId: null
};

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    startGlobalTimer();
    
    // Refresh data every 30 seconds
    setInterval(loadDashboardData, 30000);
    
    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});

// Load dashboard data
async function loadDashboardData() {
    try {
        const response = await fetch('<?= base_url('get-ready/dashboard_stats') ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Failed to load data');
        
        const data = await response.json();
        
        if (data.success) {
            updateMetrics(data.stats);
            updateStepBadges(data.stats.by_step);
            loadRecentActivities();
        }
    } catch (error) {
        console.error('Error loading dashboard data:', error);
    }
}

// Update metrics cards
function updateMetrics(stats) {
    // Update values with animation
    animateValue('totalActiveCount', stats.total_active || 0);
    animateValue('completedTodayCount', stats.completed_today || 0);
    animateValue('overdueCount', stats.overdue || 0);
    
    // Calculate average completion time (mock data for now)
    document.getElementById('avgCompletionTime').textContent = '2.5d';
    
    // Show change indicators
    setTimeout(() => {
        document.getElementById('totalActiveChange').style.display = 'inline-block';
        document.getElementById('completedTodayChange').style.display = 'inline-block';
        document.getElementById('overdueChange').style.display = 'inline-block';
        document.getElementById('avgTimeChange').style.display = 'inline-block';
    }, 500);
}

// Update step badges
function updateStepBadges(stepStats) {
    Object.keys(stepStats).forEach(slug => {
        const badge = document.getElementById(`badge-${slug}`);
        if (badge) {
            animateValue(badge, stepStats[slug].count || 0);
        }
    });
}

// Load recent activities
async function loadRecentActivities() {
    try {
        const response = await fetch('<?= base_url('api/get-ready/activities/recent') ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        if (data.success && data.activities) {
            renderActivities(data.activities);
        }
    } catch (error) {
        console.error('Error loading activities:', error);
        // Keep skeleton loading state
    }
}

// Render activities
function renderActivities(activities) {
    const feed = document.getElementById('activityFeed');
    
    if (activities.length === 0) {
        feed.innerHTML = `
            <div class="activity-item" style="justify-content: center; padding: 32px 16px;">
                <div style="text-align: center; color: rgba(55, 53, 47, 0.65);">
                    <i data-feather="inbox" style="width: 24px; height: 24px; margin-bottom: 8px;"></i>
                    <div style="font-size: 13px;"><?= lang('GetReady.no_activities') ?></div>
                </div>
            </div>
        `;
        feather.replace();
        return;
    }
    
    feed.innerHTML = activities.map(activity => `
        <div class="activity-item">
            <div class="activity-icon">
                <i data-feather="${getActivityIcon(activity.action)}" style="width: 12px; height: 12px; color: ${getActivityColor(activity.action)};"></i>
            </div>
            <div class="activity-content">
                <div class="activity-description">${activity.description}</div>
                <div class="activity-time">${activity.time_ago}</div>
            </div>
        </div>
    `).join('');
    
    feather.replace();
}

// Start global timer
function startGlobalTimer() {
    globalTimer.startTime = Date.now();
    
    function updateTimer() {
        const elapsed = Math.floor((Date.now() - globalTimer.startTime) / 1000) + globalTimer.totalSeconds;
        const hours = Math.floor(elapsed / 3600);
        const minutes = Math.floor((elapsed % 3600) / 60);
        const seconds = elapsed % 60;
        
        document.getElementById('timerValue').textContent = 
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    updateTimer();
    globalTimer.intervalId = setInterval(updateTimer, 1000);
}

// Animate value changes
function animateValue(elementId, targetValue) {
    const element = typeof elementId === 'string' ? document.getElementById(elementId) : elementId;
    if (!element) return;
    
    const currentValue = parseInt(element.textContent) || 0;
    const increment = (targetValue - currentValue) / 20;
    let current = currentValue;
    
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= targetValue) || (increment < 0 && current <= targetValue)) {
            current = targetValue;
            clearInterval(timer);
        }
        element.textContent = Math.round(current);
    }, 50);
}

// Helper functions
function getActivityIcon(action) {
    const icons = {
        'created': 'plus-circle',
        'moved_to_step': 'arrow-right',
        'assigned_tech': 'user-check',
        'added_photos': 'camera',
        'updated_location': 'map-pin',
        'nfc_scanned': 'smartphone'
    };
    return icons[action] || 'activity';
}

function getActivityColor(action) {
    const colors = {
        'created': '#00876b',
        'moved_to_step': '#2383e2',
        'assigned_tech': '#6366f1',
        'added_photos': '#f59e0b',
        'updated_location': '#8b5cf6',
        'nfc_scanned': '#06b6d4'
    };
    return colors[action] || '#6b7280';
}

// Modal functions
async function showAddVehicleModal() {
    try {
        const response = await fetch('<?= base_url('get-ready/modal_form') ?>');
        const html = await response.text();
        
        document.querySelector('#addVehicleModal .modal-content').innerHTML = html;
        
        const modal = new bootstrap.Modal(document.getElementById('addVehicleModal'));
        modal.show();
        
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    } catch (error) {
        console.error('Error loading modal:', error);
    }
}

function showAnalyticsModal() {
    // TODO: Implement analytics modal
    alert('Analytics coming soon!');
}

function exportData() {
    // TODO: Implement data export
    alert('Export functionality coming soon!');
}

function refreshAllData() {
    // Show loading state
    document.querySelectorAll('.step-badge').forEach(badge => {
        badge.textContent = '...';
    });
    
    // Reload data
    loadDashboardData();
}

// Auto-collapse sidebar for Get Ready module - Notion compact experience
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're in the Get Ready module
    const currentUrl = window.location.pathname;
    if (currentUrl.includes('get-ready')) {
        // Add CSS to make sidebar collapsed by default
        const style = document.createElement('style');
        style.textContent = `
            .app-menu {
                transform: translateX(-100%);
                transition: transform 0.2s ease;
            }
            .main-content {
                margin-left: 0 !important;
                transition: margin-left 0.2s ease;
            }
            body[data-sidebar-size="sm"] .app-menu {
                transform: translateX(0);
                width: 70px;
            }
            body[data-sidebar-size="sm"] .main-content {
                margin-left: 70px !important;
            }
            /* Notion-style collapsed sidebar */
            body[data-sidebar-size="sm"] .navbar-nav .nav-link {
                justify-content: center;
            }
            body[data-sidebar-size="sm"] .navbar-nav .nav-link span {
                display: none;
            }
        `;
        document.head.appendChild(style);
        
        // Set body attribute to collapsed
        setTimeout(() => {
            document.body.setAttribute('data-sidebar-size', 'sm');
            
            // Also trigger the sidebar collapse button if it exists
            const sidebarToggle = document.getElementById('vertical-hover');
            if (sidebarToggle) {
                sidebarToggle.click();
            }
        }, 100);
    }
});

// Fix Bootstrap modal aria-hidden accessibility issue
document.addEventListener('DOMContentLoaded', function() {
    // Handle all modals to prevent aria-hidden on focused elements
    document.addEventListener('shown.bs.modal', function(event) {
        const modal = event.target;
        if (modal && modal.hasAttribute('aria-hidden')) {
            modal.removeAttribute('aria-hidden');
        }
    });

    // Also handle when modals are hidden
    document.addEventListener('hidden.bs.modal', function(event) {
        const modal = event.target;
        if (modal && !modal.hasAttribute('aria-hidden')) {
            modal.setAttribute('aria-hidden', 'true');
        }
    });
});
</script>
<?= $this->endSection() ?>