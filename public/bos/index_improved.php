<?php
// Start session before any HTML output
session_start();

// Authentication check
$isAuthenticated = false;
$userType = 'guest';

// Check for CodeIgniter session
$ciSessionFound = false;
foreach ($_COOKIE as $name => $value) {
    if (strpos($name, 'ci_session') !== false) {
        $ciSessionFound = true;
        $isAuthenticated = true;
        $userType = 'staff';
        break;
    }
}

// Language function mock
if (!function_exists('lang')) {
    function lang($key) {
        $translations = [
            'App.dealer_inventory' => 'BMW of Sudbury Inventory',
            'App.available_stock' => 'Available Vehicles',
            'App.refresh_inventory' => 'Refresh Inventory',
            'App.move_selected' => 'Move Selected',
            'App.date_in_detail' => 'Date in Detail',
            'App.day_in_this_step' => 'Days in Step',
            'App.keys' => 'Keys',
            'App.stock_number' => 'Stock #',
            'App.vehicle' => 'Vehicle',
            'App.status' => 'Status',
            'App.actions' => 'Actions',
            'App.total_stock_items' => 'Total Inventory',
            'App.recent_items' => 'New Arrivals',
            'App.moderate_items' => 'In Process',
            'App.aged_items' => 'Aged Stock',
            'App.avg_in_this_step' => 'Average Days',
            'App.clear_filters' => 'Clear Filters',
            'App.refresh' => 'Refresh',
            'App.inventory_stats' => 'Inventory Analytics',
            'App.inventory_table' => 'Vehicle Inventory Management',
            'App.search_placeholder' => 'Search vehicles...'
        ];
        return $translations[$key] ?? $key;
    }
}
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark">
<head>
    <meta charset="utf-8" />
    <title>BMW of Sudbury - Inventory Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="BMW of Sudbury Inventory Management System" name="description" />
    <meta content="BMW BOS" name="author" />
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.ico">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- BMW Modern CSS -->
    <link rel="stylesheet" href="css/bmw-modern.css">
    
    <!-- Custom Styles -->
    <style>
        /* Page specific styles */
        .main-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .content-area {
            flex: 1;
            padding: 2rem;
        }
        
        /* Search Bar */
        .search-bar {
            position: relative;
        }
        
        .search-bar input {
            padding-left: 2.5rem;
            border-radius: var(--radius-full);
            border: 2px solid transparent;
            background: rgba(255, 255, 255, 0.9);
            transition: all var(--transition-base);
        }
        
        .search-bar input:focus {
            border-color: var(--bmw-blue);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }
        
        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--bmw-grey);
        }
        
        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .quick-action-btn {
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            border: 2px solid var(--bmw-blue);
            background: transparent;
            color: var(--bmw-blue);
            font-weight: 600;
            transition: all var(--transition-base);
            cursor: pointer;
        }
        
        .quick-action-btn:hover {
            background: var(--bmw-blue);
            color: var(--bmw-white);
            transform: translateY(-2px);
        }
        
        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 1rem;
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-base);
        }
        
        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .loader {
            width: 48px;
            height: 48px;
            border: 4px solid var(--bmw-light-grey);
            border-top-color: var(--bmw-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loader"></div>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        
        <!-- Modern BMW Header -->
        <header class="bmw-header">
            <nav class="navbar">
                <a href="#" class="bmw-logo">
                    <i class="bi bi-car-front-fill"></i>
                    <div>
                        <div>BMW of Sudbury</div>
                        <div style="font-size: 0.75rem; font-weight: 400; opacity: 0.9;">Inventory Management System</div>
                    </div>
                </a>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <input type="text" class="form-control" placeholder="<?= lang('App.search_placeholder') ?>" id="globalSearch">
                        <i class="bi bi-search"></i>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-link text-white dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-4"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Welcome, <?= ucfirst($userType) ?></h6></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content Area -->
        <div class="content-area">
            
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 fade-in">
                <div>
                    <h1 class="h3 mb-1 text-bmw-blue"><?= lang('App.inventory_stats') ?></h1>
                    <p class="text-muted mb-0">Real-time inventory tracking and analytics</p>
                </div>
                
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <button class="quick-action-btn" onclick="refreshInventory()">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Refresh
                    </button>
                    <button class="quick-action-btn" onclick="exportData()">
                        <i class="bi bi-download me-1"></i>
                        Export
                    </button>
                    <?php if($isAuthenticated): ?>
                    <button class="quick-action-btn" onclick="addVehicle()">
                        <i class="bi bi-plus-lg me-1"></i>
                        Add Vehicle
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Statistics Cards Grid -->
            <div class="row g-3 mb-4">
                <!-- Total Inventory Card -->
                <div class="col-xl-3 col-md-6">
                    <div class="glass-card stat-card slide-up" data-filter="all" style="animation-delay: 0.1s">
                        <div class="stat-card-header">
                            <div class="stat-icon">
                                <i class="bi bi-car-front"></i>
                            </div>
                            <div class="stat-trend trend-up">
                                <i class="bi bi-arrow-up"></i>
                                <span>+12%</span>
                            </div>
                        </div>
                        <h3 class="stat-number counter-animate" data-target="247">0</h3>
                        <p class="stat-label"><?= lang('App.total_stock_items') ?></p>
                        <div class="stat-progress">
                            <div class="stat-progress-bar" style="width: 75%"></div>
                        </div>
                    </div>
                </div>

                <!-- New Arrivals Card -->
                <div class="col-xl-3 col-md-6">
                    <div class="glass-card stat-card slide-up" data-filter="0-1" style="animation-delay: 0.2s">
                        <div class="stat-card-header">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #00B04F, #00D55F)">
                                <i class="bi bi-stars"></i>
                            </div>
                            <div class="stat-trend trend-up">
                                <i class="bi bi-arrow-up"></i>
                                <span>+5</span>
                            </div>
                        </div>
                        <h3 class="stat-number counter-animate" data-target="42">0</h3>
                        <p class="stat-label"><?= lang('App.recent_items') ?></p>
                        <div class="stat-progress">
                            <div class="stat-progress-bar" style="width: 30%; background: linear-gradient(90deg, #00B04F, #00D55F)"></div>
                        </div>
                    </div>
                </div>

                <!-- In Process Card -->
                <div class="col-xl-3 col-md-6">
                    <div class="glass-card stat-card slide-up" data-filter="2-5" style="animation-delay: 0.3s">
                        <div class="stat-card-header">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #FF6600, #FF8833)">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="stat-trend trend-down">
                                <i class="bi bi-arrow-down"></i>
                                <span>-3</span>
                            </div>
                        </div>
                        <h3 class="stat-number counter-animate" data-target="89">0</h3>
                        <p class="stat-label"><?= lang('App.moderate_items') ?></p>
                        <div class="stat-progress">
                            <div class="stat-progress-bar" style="width: 45%; background: linear-gradient(90deg, #FF6600, #FF8833)"></div>
                        </div>
                    </div>
                </div>

                <!-- Aged Stock Card -->
                <div class="col-xl-3 col-md-6">
                    <div class="glass-card stat-card slide-up" data-filter="6+" style="animation-delay: 0.4s">
                        <div class="stat-card-header">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #CC0000, #FF3333)">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="stat-trend trend-up">
                                <i class="bi bi-arrow-up"></i>
                                <span>+8</span>
                            </div>
                        </div>
                        <h3 class="stat-number counter-animate" data-target="116">0</h3>
                        <p class="stat-label"><?= lang('App.aged_items') ?></p>
                        <div class="stat-progress">
                            <div class="stat-progress-bar" style="width: 60%; background: linear-gradient(90deg, #CC0000, #FF3333)"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-3 mb-4">
                <!-- Inventory Distribution Chart -->
                <div class="col-lg-8">
                    <div class="clean-card p-3 fade-in">
                        <h5 class="mb-3 text-bmw-blue">
                            <i class="bi bi-graph-up me-2"></i>
                            Inventory Trends
                        </h5>
                        <div class="chart-container">
                            <canvas id="inventoryTrendChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Status Distribution -->
                <div class="col-lg-4">
                    <div class="clean-card p-3 fade-in">
                        <h5 class="mb-3 text-bmw-blue">
                            <i class="bi bi-pie-chart me-2"></i>
                            Status Distribution
                        </h5>
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="glass-card fade-in">
                <div class="card-header bg-transparent border-0 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-bmw-blue">
                            <i class="bi bi-table me-2"></i>
                            <?= lang('App.inventory_table') ?>
                        </h5>
                        
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearFilters()">
                                <i class="bi bi-funnel-fill me-1"></i>
                                Clear Filters
                            </button>
                            <button class="btn btn-sm btn-primary" style="background: var(--bmw-blue); border-color: var(--bmw-blue)" onclick="refreshTable()">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table id="inventoryTable" class="table table-modern">
                            <thead>
                                <tr>
                                    <?php if($isAuthenticated): ?>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <?php endif; ?>
                                    <th><?= lang('App.stock_number') ?></th>
                                    <th><?= lang('App.vehicle') ?></th>
                                    <th><?= lang('App.date_in_detail') ?></th>
                                    <th><?= lang('App.day_in_this_step') ?></th>
                                    <th><?= lang('App.keys') ?></th>
                                    <th><?= lang('App.status') ?></th>
                                    <?php if($isAuthenticated): ?>
                                    <th><?= lang('App.actions') ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Last Update Info -->
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            Last updated: <span id="lastUpdate">Loading...</span>
                        </small>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Floating Action Button -->
    <button class="fab" onclick="scrollToTop()">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Global variables
        let inventoryTable = null;
        let inventoryData = [];
        
        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeCounters();
            initializeCharts();
            initializeDataTable();
            startAutoRefresh();
            updateLastRefresh();
        });
        
        // Counter Animation
        function initializeCounters() {
            const counters = document.querySelectorAll('.stat-number');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target') || counter.innerText);
                let current = 0;
                const increment = target / 50;
                
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.innerText = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.innerText = target;
                    }
                };
                
                updateCounter();
            });
        }
        
        // Initialize Charts
        function initializeCharts() {
            // Trend Chart
            const trendCtx = document.getElementById('inventoryTrendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'New Arrivals',
                        data: [12, 19, 15, 25, 22, 30, 28],
                        borderColor: '#0066CC',
                        backgroundColor: 'rgba(0, 102, 204, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Sold',
                        data: [8, 12, 10, 15, 18, 14, 20],
                        borderColor: '#00B04F',
                        backgroundColor: 'rgba(0, 176, 79, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Available', 'In Process', 'Pending', 'Sold'],
                    datasets: [{
                        data: [42, 89, 116, 27],
                        backgroundColor: [
                            '#00B04F',
                            '#FF6600',
                            '#0066CC',
                            '#CC0000'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        // Initialize DataTable
        function initializeDataTable() {
            inventoryTable = $('#inventoryTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[1, 'asc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search inventory...'
                },
                ajax: {
                    url: 'get_inventory.php',
                    dataSrc: function(json) {
                        inventoryData = json.data || [];
                        return inventoryData;
                    }
                },
                columns: [
                    <?php if($isAuthenticated): ?>
                    {
                        data: null,
                        orderable: false,
                        render: function() {
                            return '<div class="form-check"><input class="form-check-input row-select" type="checkbox"></div>';
                        }
                    },
                    <?php endif; ?>
                    { data: 'stock_number' },
                    { data: 'vehicle' },
                    { data: 'date_in_detail' },
                    { 
                        data: 'days_in_step',
                        render: function(data) {
                            let badgeClass = 'badge-status pending';
                            if (data <= 1) badgeClass = 'badge-status active';
                            else if (data <= 5) badgeClass = 'badge-status pending';
                            else badgeClass = 'badge-status completed';
                            
                            return `<span class="${badgeClass}">${data} days</span>`;
                        }
                    },
                    { 
                        data: 'keys',
                        render: function(data) {
                            const icon = data === 'Yes' ? 'bi-check-circle text-success' : 'bi-x-circle text-danger';
                            return `<i class="bi ${icon}"></i>`;
                        }
                    },
                    { 
                        data: 'status',
                        render: function(data) {
                            return `<span class="badge-status ${data.toLowerCase()}">${data}</span>`;
                        }
                    }
                    <?php if($isAuthenticated): ?>
                    ,{
                        data: null,
                        orderable: false,
                        render: function() {
                            return `
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewDetails(this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" onclick="editVehicle(this)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteVehicle(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                    <?php endif; ?>
                ]
            });
        }
        
        // Refresh Functions
        function refreshInventory() {
            showLoading();
            if (inventoryTable) {
                inventoryTable.ajax.reload(() => {
                    hideLoading();
                    showToast('success', 'Inventory refreshed successfully');
                    updateLastRefresh();
                });
            }
        }
        
        function refreshTable() {
            refreshInventory();
        }
        
        // Auto-refresh every 30 seconds
        function startAutoRefresh() {
            setInterval(() => {
                if (inventoryTable) {
                    inventoryTable.ajax.reload(null, false);
                    updateLastRefresh();
                }
            }, 30000);
        }
        
        // Update last refresh time
        function updateLastRefresh() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            document.getElementById('lastUpdate').textContent = timeString;
        }
        
        // Filter Functions
        function clearFilters() {
            if (inventoryTable) {
                inventoryTable.search('').draw();
                document.querySelectorAll('.stat-card').forEach(card => {
                    card.classList.remove('active');
                });
                showToast('info', 'Filters cleared');
            }
        }
        
        // Loading Functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }
        
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }
        
        // Toast Notification
        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;
            toast.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            
            document.getElementById('toastContainer').appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideIn var(--transition-base) ease-out reverse';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Scroll to top
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
        
        // CRUD Functions (for authenticated users)
        function viewDetails(btn) {
            const data = inventoryTable.row($(btn).parents('tr')).data();
            Swal.fire({
                title: `Vehicle Details: ${data.stock_number}`,
                html: `
                    <div class="text-start">
                        <p><strong>Vehicle:</strong> ${data.vehicle}</p>
                        <p><strong>Date:</strong> ${data.date_in_detail}</p>
                        <p><strong>Days in Step:</strong> ${data.days_in_step}</p>
                        <p><strong>Keys:</strong> ${data.keys}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                    </div>
                `,
                icon: 'info'
            });
        }
        
        function editVehicle(btn) {
            const data = inventoryTable.row($(btn).parents('tr')).data();
            // Implement edit functionality
            showToast('info', 'Edit feature coming soon');
        }
        
        function deleteVehicle(btn) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#CC0000',
                cancelButtonColor: '#666666',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showToast('success', 'Vehicle deleted successfully');
                }
            });
        }
        
        function addVehicle() {
            // Implement add vehicle functionality
            showToast('info', 'Add vehicle feature coming soon');
        }
        
        function exportData() {
            // Implement export functionality
            showToast('info', 'Export feature coming soon');
        }
        
        // Global search
        document.getElementById('globalSearch').addEventListener('input', function(e) {
            if (inventoryTable) {
                inventoryTable.search(e.target.value).draw();
            }
        });
        
        // Stat card click filter
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                if (filter && inventoryTable) {
                    // Remove active class from all cards
                    document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('active'));
                    // Add active class to clicked card
                    this.classList.add('active');
                    
                    // Apply filter logic based on data-filter attribute
                    if (filter === 'all') {
                        inventoryTable.search('').draw();
                    } else {
                        // Custom filter logic based on days
                        inventoryTable.search(filter).draw();
                    }
                }
            });
        });
    </script>
</body>
</html>