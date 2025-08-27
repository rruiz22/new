<?php
// Start session before any HTML output
session_start();

// Check if user is authenticated for staff-only features
// Default to false for initial load, JavaScript will handle the real check
$isAuthenticated = false; // Default to false - hide staff-only elements initially
$userType = 'guest';

// Look for CodeIgniter session cookie
$ciSessionFound = false;
foreach ($_COOKIE as $name => $value) {
    if (strpos($name, 'ci_session') !== false) {
        $ciSessionFound = true;
        break;
    }
}

// If CI session cookie found, likely authenticated
if ($ciSessionFound) {
    $isAuthenticated = true;
    $userType = 'staff';
} else {
    // If no CI session cookie found, check for other session indicators
    $authIndicators = [
        'user', 'isLoggedIn', 'user_id', 'logged_in', 
        'auth_login', 'login_user', 'user_data'
    ];
    
    $sessionAuthFound = false;
    foreach ($authIndicators as $indicator) {
        if (isset($_SESSION[$indicator])) {
            $sessionAuthFound = true;
            break;
        }
    }
    
    // If no session indicators, set as guest and hide staff-only elements
    // JavaScript will handle the final authentication state
    if (!$sessionAuthFound) {
        $userType = 'guest';
        $isAuthenticated = false;
    } else {
        // Found session indicators, likely authenticated
        $isAuthenticated = true;
        $userType = 'staff';
    }
}

// Set staff-only classes
$staffOnlyClass = $isAuthenticated ? '' : 'staff-only';
$staffOnlyStyle = $isAuthenticated ? '' : 'style="display: none !important;"';

// Mock lang function if not available
if (!function_exists('lang')) {
    function lang($key) {
        $translations = [
            'App.dealer_inventory' => 'Dealer Inventory',
            'App.available_stock' => 'Available stock',
            'App.refresh_inventory' => 'Refresh Inventory',
            'App.move_selected' => 'Move Selected',
            'App.date_in_detail' => 'Date in Detail',
            'App.day_in_this_step' => 'Day in this Step',
            'App.keys' => 'Keys',
            'App.stock_number' => 'Stock Number',
            'App.vehicle' => 'Vehicle',
            'App.status' => 'Status',
            'App.actions' => 'Actions',
            'App.total_stock_items' => 'Total Items',
            'App.recent_items' => 'Recent',
            'App.moderate_items' => 'Moderate',
            'App.aged_items' => 'Aged',
            'App.avg_in_this_step' => 'Average Days',
            'App.clear_filters' => 'Clear Filters',
            'App.refresh' => 'Refresh',
            'App.inventory_stats' => 'Inventory Statistics',
            'App.click_to_filter' => 'Click to filter results',
            'App.inventory_table' => 'Inventory Management',
            'App.detailed_inventory_view' => 'Comprehensive inventory overview'
        ];
        
        return isset($translations[$key]) ? $translations[$key] : $key;
    }
}
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>BOS Inventory Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="BOS Inventory Management System" name="description" />
    <meta content="BOS" name="author" />
    
    <?php include 'partials/head-css.php'; ?>
    
    <!-- Notion Enterprise CSS -->
    <link rel="stylesheet" href="css/bos-notion-enterprise.css">
    
    <!-- Minimal BOS-specific styles -->
    <style>
        /* Staff-only elements hidden by default */
        .staff-only {
            display: none !important;
        }
        
        /* Header visibility control */
        #page-topbar.d-none {
            display: none !important;
        }
        
        /* Filter widget cursor */
        .filter-widget {
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .filter-widget:hover {
            transform: translateY(-2px);
        }
        
        .filter-widget.active {
            transform: scale(1.02);
        }
    </style>
    
</head>

<body data-bs-spy="scroll" data-bs-target="#navbar-example">

    <!-- BOS Notion Enterprise Layout -->


        <!-- Main Content - Full Width -->
        <div class="notion-container" style="margin: 0; padding: 1rem 4rem; max-width: none;">

                    <!-- Notion Page Header -->
                    <div class="notion-page-header">
                        <h1 class="notion-page-title">BMW of Sudbury</h1>
                        <p class="notion-page-subtitle">Professional inventory management system for luxury automotive dealership</p>
                    </div>

                    <!-- Enterprise Statistics Grid -->
                    <div class="stats-container">
                        <div class="stats-grid">
                        <!-- Total Items Widget -->
                        <div class="stat-widget filter-widget hover-lift click-scale" data-filter="" title="<?= lang('App.click_to_filter') ?>">
                            <div class="stat-header">
                                <div class="stat-label"><?= lang('App.total_stock_items') ?></div>
                                <div class="stat-icon">
                                    <i class="bx bx-archive"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="totalInventoryItems">
                                <div class="loading-dots" style="justify-content: center; transform: scale(0.7);">
                                    <span></span><span></span><span></span>
                                </div>
                            </div>
                            <div class="stat-description">
                                <span class="stat-badge success">vehicles</span>
                            </div>
                        </div>

                        <!-- Recent Items Widget -->
                        <div class="stat-widget filter-widget hover-lift click-scale" data-filter="0-1" title="<?= lang('App.click_to_filter') ?>">
                            <div class="stat-header">
                                <div class="stat-label"><?= lang('App.recent_items') ?></div>
                                <div class="stat-icon">
                                    <i class="bx bx-time"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="recentItems">
                                <div class="loading-dots" style="justify-content: center; transform: scale(0.7);">
                                    <span></span><span></span><span></span>
                                </div>
                            </div>
                            <div class="stat-description">
                                <span class="stat-badge info">0-1 days</span>
                            </div>
                        </div>

                        <!-- Moderate Items Widget -->
                        <div class="stat-widget filter-widget hover-lift click-scale" data-filter="2-5" title="<?= lang('App.click_to_filter') ?>">
                            <div class="stat-header">
                                <div class="stat-label"><?= lang('App.moderate_items') ?></div>
                                <div class="stat-icon">
                                    <i class="bx bx-calendar"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="moderateItems">
                                <div class="loading-dots" style="justify-content: center; transform: scale(0.7);">
                                    <span></span><span></span><span></span>
                                </div>
                            </div>
                            <div class="stat-description">
                                <span class="stat-badge warning">2-5 days</span>
                            </div>
                        </div>

                        <!-- Aged Items Widget -->
                        <div class="stat-widget filter-widget hover-lift click-scale" data-filter="6+" title="<?= lang('App.click_to_filter') ?>">
                            <div class="stat-header">
                                <div class="stat-label"><?= lang('App.aged_items') ?></div>
                                <div class="stat-icon">
                                    <i class="bx bx-alarm-exclamation"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="agedItems">
                                <div class="loading-dots" style="justify-content: center; transform: scale(0.7);">
                                    <span></span><span></span><span></span>
                                </div>
                            </div>
                            <div class="stat-description">
                                <span class="stat-badge error">6+ days</span>
                            </div>
                        </div>

                        <!-- Average Days Widget - Featured -->
                        <div class="stat-widget hover-lift" style="background: var(--bmw-blue-light); border-color: var(--bmw-blue);">
                            <div class="stat-header">
                                <div class="stat-label" style="color: var(--bmw-blue);"><?= lang('App.avg_in_this_step') ?></div>
                                <div class="stat-icon" style="background: var(--bmw-blue); color: white;">
                                    <i class="bx bx-calculator"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="avgDaysNumber" style="color: var(--bmw-blue);">
                                <div class="loading-dots" style="justify-content: center; transform: scale(0.7);">
                                    <span></span><span></span><span></span>
                                </div>
                            </div>
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
                                        <?= lang('App.inventory_table') ?>
                                    </h3>
                                </div>
                                <div class="table-controls">
                                    <!-- Search Bar -->
                                    <div class="search-container">
                                        <input type="text" id="inventorySearch" class="form-input search-input" placeholder="Search inventory...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                    
                                    <!-- Clear Filters Button -->
                                    <button id="clearAllFilters" class="btn btn-secondary btn-sm hover-lift click-scale">
                                        <i class="ri-filter-off-line"></i>
                                        <?= lang('App.clear_filters') ?>
                                    </button>
                                    
                                    <!-- Refresh Button -->
                                    <button id="refreshInventoryBtn" class="btn btn-primary btn-sm hover-lift click-scale">
                                        <i class="ri-refresh-line"></i>
                                        <?= lang('App.refresh') ?>
                                    </button>
                                </div>
                            </div>
                        
                        <div class="table-wrapper">
                            <table id="inventoryTable" class="modern-table">
                                <thead>
                                    <tr>
                                        <!-- Staff-only Select Column -->
                                        <th class="<?= $staffOnlyClass ?>" <?= $staffOnlyStyle ?>>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAllInventory">
                                            </div>
                                        </th>
                                        <th scope="col"><?= lang('App.date_in_detail') ?></th>
                                        <th scope="col" class="text-center"><?= lang('App.day_in_this_step') ?></th>
                                        <th scope="col" class="text-center"><?= lang('App.keys') ?></th>
                                        <th scope="col" class="text-center"><?= lang('App.stock_number') ?></th>
                                        <th scope="col"><?= lang('App.vehicle') ?></th>
                                        <th scope="col">Notes</th>
                                        <th scope="col" class="text-center"><?= lang('App.status') ?></th>
                                        <!-- Staff-only Actions Column -->
                                        <th scope="col" class="text-center <?= $staffOnlyClass ?>" <?= $staffOnlyStyle ?>><?= lang('App.actions') ?></th>
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

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Debug Panel (development only) -->
    <button id="debugToggle" class="btn btn-primary <?= $staffOnlyClass ?>" <?= $staffOnlyStyle ?> title="Toggle Debug Panel" 
            style="position: fixed; bottom: 20px; left: 20px; border-radius: 50%; width: 50px; height: 50px; z-index: 1001;">
        <i class="ri-bug-line"></i>
    </button>
    <div id="debugPanel" class="card <?= $staffOnlyClass ?>" <?= $staffOnlyStyle ?> style="display: none; position: fixed; bottom: 20px; right: 20px; max-width: 300px; z-index: 1000;">
        <div class="card-header">
            <h6 class="card-title mb-0">Debug Info</h6>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <span>Auth:</span>
                <span id="debugAuth" class="badge bg-secondary">Checking...</span>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <span>User Type:</span>
                <span id="debugUserType" class="badge bg-info"><?= $userType ?></span>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <span>Tables:</span>
                <span id="debugTables" class="badge bg-warning">Loading...</span>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <span>Last Action:</span>
                <span id="debugLastAction" class="badge bg-success">-</span>
            </div>
        </div>
    </div>

    <!-- Load vendor scripts -->
    <?php include 'partials/vendor-scripts.php'; ?>

    <!-- Enhanced Authentication and UI Management -->
    <script>
        // Global variables for UI state
        window.isAuthenticated = <?= $isAuthenticated ? 'true' : 'false' ?>;
        window.userType = '<?= $userType ?>';
        window.authCheckCompleted = false; // Will be updated by AJAX
        window.inventoryTable = null;
        window.orderInfoLookup = {};
        window.duplicateStocks = new Set();
        
        // Initialize authentication check
        function checkAuthenticationStatus() {
            // Check if we can make a request to a protected endpoint
            fetch('./check_auth.php', {
                method: 'GET',
                credentials: 'include'
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else if (response.status === 401) {
                    // Unauthorized - not authenticated
                    return { isAuthenticated: false, userType: 'guest' };
                } else {
                    // Other error - treat as not authenticated
                    return { isAuthenticated: false, userType: 'guest' };
                }
            })
            .then(data => {
                // Update global authentication state
                window.isAuthenticated = data.isAuthenticated || false;
                window.userType = data.userType || 'guest';
                window.authCheckCompleted = true;
                
                // Update debug panel
                if (document.getElementById('debugAuth')) {
                    document.getElementById('debugAuth').textContent = window.isAuthenticated ? 'Yes' : 'No';
                }
                
                // Update UI based on authentication
                if (typeof updateAuthenticationUI === 'function') {
                    updateAuthenticationUI();
                }
            })
            .catch(error => {
                // On error, assume not authenticated
                window.isAuthenticated = false;
                window.userType = 'guest';
                window.authCheckCompleted = true;
                
                if (document.getElementById('debugAuth')) {
                    document.getElementById('debugAuth').textContent = 'Error';
                }
                
                if (typeof updateAuthenticationUI === 'function') {
                    updateAuthenticationUI();
                }
            });
        }
        
        // Enhanced debug panel functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Debug toggle functionality for staff
            const debugToggle = document.getElementById('debugToggle');
            const debugPanel = document.getElementById('debugPanel');
            
            if (debugToggle && debugPanel) {
                debugToggle.addEventListener('click', function() {
                    const isVisible = debugPanel.style.display !== 'none';
                    debugPanel.style.display = isVisible ? 'none' : 'block';
                    
                    if (document.getElementById('debugLastAction')) {
                        document.getElementById('debugLastAction').textContent = 'Debug toggled';
                    }
                });
            }
            
            // Update debug tables status
            function updateDebugTables() {
                if (document.getElementById('debugTables')) {
                    const tablesStatus = window.inventoryTable ? 'Inventory' : 'None';
                    document.getElementById('debugTables').textContent = tablesStatus;
                }
            }
            
            // Listen for table ready events
            window.addEventListener('tablesReady', function() {
                updateDebugTables();
            });
            
            // Periodic debug update
            setInterval(updateDebugTables, 2000);
        });
        
        // Define updateAuthenticationUI function
        function updateAuthenticationUI() {
            // Show/hide staff-only elements
            const staffElements = document.querySelectorAll('.staff-only');
            staffElements.forEach(element => {
                if (window.isAuthenticated) {
                    element.style.display = '';
                    element.classList.add('show');
                } else {
                    element.style.display = 'none';
                    element.classList.remove('show');
                }
            });
        }
        
        // Start authentication check
        checkAuthenticationStatus();
    </script>

    <!-- Load Notion Enterprise UI Enhancements -->
    <script src="js/notion-enterprise-enhancements-fixed.js"></script>
    
    <!-- Load Status Overview Widget -->
    <script src="js/status-overview-widget-fixed.js"></script>
    
    <!-- Load main vehicles inventory script -->
    <script src="js/vehicles-inventory.js"></script>

</body>
</html>