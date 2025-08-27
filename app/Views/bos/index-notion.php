<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? 'BOS Inventory Management' ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $page_title ?? 'BMW of Sudbury - Inventory Management' ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
<li class="breadcrumb-item active">BOS Inventory</li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Notion Enterprise CSS -->
<link rel="stylesheet" href="<?= base_url('public/bos/css/bos-notion-enterprise.css') ?>">

<style>
/* Additional BOS-specific overrides */
.main-content {
    padding: 0 !important;
    margin: 0 !important;
}

.page-content {
    padding: 1rem 4rem !important;
    margin: 0 !important;
    max-width: none !important;
}

/* Hide default page header for clean layout */
.page-title-box {
    display: none !important;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Notion Page Header -->
<div class="notion-page-header">
    <h1 class="notion-page-title">BMW of Sudbury</h1>
    <p class="notion-page-subtitle">Professional inventory management system for luxury automotive dealership</p>
</div>

<!-- Enterprise Statistics Grid -->
<div class="stats-container">
    <div class="stats-grid">
        <!-- Total Items Widget -->
        <div class="stat-widget filter-widget hover-lift click-scale" data-filter="" title="Click to filter">
            <div class="stat-header">
                <div class="stat-label">Total Stock Items</div>
                <div class="stat-icon">
                    <i class="bx bx-archive"></i>
                </div>
            </div>
            <div class="stat-value" id="totalInventoryItems">0</div>
            <div class="stat-description">
                <span class="stat-badge success">vehicles</span>
            </div>
        </div>

        <!-- Recent Items Widget -->
        <div class="stat-widget filter-widget hover-lift click-scale" data-filter="0-1" title="Click to filter">
            <div class="stat-header">
                <div class="stat-label">Recent Items</div>
                <div class="stat-icon">
                    <i class="bx bx-time"></i>
                </div>
            </div>
            <div class="stat-value" id="recentItems">0</div>
            <div class="stat-description">
                <span class="stat-badge info">0-1 days</span>
            </div>
        </div>

        <!-- Moderate Items Widget -->
        <div class="stat-widget filter-widget hover-lift click-scale" data-filter="2-5" title="Click to filter">
            <div class="stat-header">
                <div class="stat-label">Moderate Items</div>
                <div class="stat-icon">
                    <i class="bx bx-calendar"></i>
                </div>
            </div>
            <div class="stat-value" id="moderateItems">0</div>
            <div class="stat-description">
                <span class="stat-badge warning">2-5 days</span>
            </div>
        </div>

        <!-- Aged Items Widget -->
        <div class="stat-widget filter-widget hover-lift click-scale" data-filter="6+" title="Click to filter">
            <div class="stat-header">
                <div class="stat-label">Aged Items</div>
                <div class="stat-icon">
                    <i class="bx bx-alarm-exclamation"></i>
                </div>
            </div>
            <div class="stat-value" id="agedItems">0</div>
            <div class="stat-description">
                <span class="stat-badge error">6+ days</span>
            </div>
        </div>

        <!-- Average Days Widget - Featured -->
        <div class="stat-widget hover-lift" style="background: var(--bmw-blue-light); border-color: var(--bmw-blue);">
            <div class="stat-header">
                <div class="stat-label" style="color: var(--bmw-blue);">Avg in This Step</div>
                <div class="stat-icon" style="background: var(--bmw-blue); color: white;">
                    <i class="bx bx-calculator"></i>
                </div>
            </div>
            <div class="stat-value" id="avgDaysNumber" style="color: var(--bmw-blue);">0</div>
            <div class="stat-description">
                <span class="stat-badge" style="background: var(--bmw-blue); color: white;">avg days</span>
            </div>
        </div>
    </div>
</div>

<!-- Status Overview Widget -->
<div class="status-overview-container">
    <div class="status-overview-widget">
        <div class="status-widget-header">
            <h3>
                <i class="ri-bar-chart-box-line"></i>
                Vehicle Status Overview
            </h3>
        </div>
        <div class="status-grid" id="statusGrid">
            <!-- Status items will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Centered Table Container -->
<div class="table-wrapper-container">
    <div class="table-container">
        <div class="table-header">
            <div class="table-title">
                <h3>
                    <i class="ri-table-line notion-card-title-icon"></i>
                    Inventory Management
                </h3>
            </div>
            <div class="table-controls">
                <!-- Search Bar -->
                <div class="search-container">
                    <input type="text" id="inventorySearch" class="search-input" placeholder="Search inventory...">
                    <i class="ri-search-line search-icon"></i>
                </div>
                
                <!-- Button Group -->
                <div class="button-group">
                    <button id="clearAllFilters" class="btn btn-secondary btn-sm hover-lift click-scale">
                        <i class="ri-filter-off-line"></i>
                        Clear Filters
                    </button>
                    
                    <button id="refreshInventoryBtn" class="btn btn-primary btn-sm hover-lift click-scale">
                        <i class="ri-refresh-line"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
        
        <div class="table-wrapper">
            <table id="inventoryTable" class="modern-table">
                <thead>
                    <tr>
                        <th>Date in Detail</th>
                        <th class="text-center">Day in this Step</th>
                        <th class="text-center">Keys</th>
                        <th class="text-center">Stock Number</th>
                        <th>Vehicle</th>
                        <th>Notes</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data loaded via DataTables -->
                </tbody>
            </table>
        </div>
        
        <!-- Last Refresh Info -->
        <div class="table-footer mt-4 text-center">
            <small id="lastRefreshInfo" class="text-gray-600">
                <!-- Last refresh time will be updated here -->
            </small>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Load Notion Enterprise UI Enhancements -->
<script src="<?= base_url('public/bos/js/notion-enterprise-enhancements.js') ?>"></script>

<!-- Load Status Overview Widget -->
<script src="<?= base_url('public/bos/js/status-overview-widget.js') ?>"></script>

<!-- Load main vehicles inventory script -->
<script src="<?= base_url('public/bos/js/vehicles-inventory.js') ?>"></script>

<script>
// BOS Inventory Integration for CodeIgniter
document.addEventListener('DOMContentLoaded', function() {
    // Initialize authentication check for CodeIgniter context
    window.isAuthenticated = <?= session('logged_in') ? 'true' : 'false' ?>;
    window.userType = '<?= session('user_type') ?? 'guest' ?>';
    window.authCheckCompleted = true;
    
    console.log('🚗 BOS Inventory initialized with CodeIgniter integration');
    console.log('Auth status:', window.isAuthenticated, 'User type:', window.userType);
});
</script>
<?= $this->endSection() ?>