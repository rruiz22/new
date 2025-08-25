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
    
    <!-- Responsive Framework CSS -->
    <link rel="stylesheet" href="css/responsive-framework.css">
    <link rel="stylesheet" href="css/responsive-integration.css">
    
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

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Top navigation -->
        <header id="page-topbar" class="<?= $isAuthenticated ? '' : 'd-none' ?>">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="#" class="logo logo-dark">
                                <span class="logo-sm">
                                    <i class="ri-database-2-line fs-22 text-primary"></i>
                                </span>
                                <span class="logo-lg">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-database-2-line fs-24 text-primary me-2"></i>
                                        <div>
                                            <span class="fw-bold fs-18">BOS Inventory</span>
                                            <div class="fs-11 text-muted">Management System</div>
                                        </div>
                                    </div>
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <!-- Quick Actions -->
                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                                <i class='bx bx-fullscreen fs-22'></i>
                            </button>
                        </div>
                        
                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" title="Refresh Data">
                                <i class='bx bx-refresh fs-22'></i>
                            </button>
                        </div>

                        <!-- User Dropdown -->
                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-primary-subtle text-primary fs-16 fw-bold">
                                            <?= $userType === 'staff' ? 'S' : 'G' ?>
                                        </div>
                                    </div>
                                    <span class="text-start ms-xl-2">
                                        <span class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text"><?= $userType === 'staff' ? 'Staff User' : 'Guest User' ?></span>
                                        <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text"><?= ucfirst($userType) ?> Access</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">Welcome <?= ucfirst($userType) ?>!</h6>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Settings</span></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-xxl">

                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">
                                    <i class="ri-car-line me-2"></i>
                                    Bmw of Sudbury
                                </h4>
                                <br><br>
                            
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">BOS</a></li>
                                        <li class="breadcrumb-item active">Inventory</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">
                                        <i class="ri-bar-chart-box-line me-2"></i>
                                        Inventory Statistics
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                        <!-- Total Items Card -->
                        <div class="col-xl col-lg-3 col-md-6">
                            <div class="card card-animate filter-widget" data-filter="" style="cursor: pointer;" title="<?= lang('App.click_to_filter') ?>">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted mb-1 fs-12"><?= lang('App.total_stock_items') ?></p>
                                            <h4 class="fs-18 fw-semibold ff-secondary mb-2" id="totalInventoryItems">0</h4>
                                            <span class="badge bg-success-subtle text-success fs-11">vehicles</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-success-subtle rounded fs-4">
                                                    <i class="bx bx-archive text-success"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Items Card -->
                        <div class="col-xl col-lg-3 col-md-6">
                            <div class="card card-animate filter-widget" data-filter="0-1" style="cursor: pointer;" title="<?= lang('App.click_to_filter') ?>">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted mb-1 fs-12"><?= lang('App.recent_items') ?></p>
                                            <h4 class="fs-18 fw-semibold ff-secondary mb-2" id="recentItems">0</h4>
                                            <span class="badge bg-info-subtle text-info fs-11">0-1 days</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-info-subtle rounded fs-4">
                                                    <i class="bx bx-time text-info"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Moderate Items Card -->
                        <div class="col-xl col-lg-3 col-md-6">
                            <div class="card card-animate filter-widget" data-filter="2-5" style="cursor: pointer;" title="<?= lang('App.click_to_filter') ?>">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted mb-1 fs-12"><?= lang('App.moderate_items') ?></p>
                                            <h4 class="fs-18 fw-semibold ff-secondary mb-2" id="moderateItems">0</h4>
                                            <span class="badge bg-warning-subtle text-warning fs-11">2-5 days</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-warning-subtle rounded fs-4">
                                                    <i class="bx bx-calendar text-warning"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Aged Items Card -->
                        <div class="col-xl col-lg-3 col-md-6">
                            <div class="card card-animate filter-widget" data-filter="6+" style="cursor: pointer;" title="<?= lang('App.click_to_filter') ?>">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-muted mb-1 fs-12"><?= lang('App.aged_items') ?></p>
                                            <h4 class="fs-18 fw-semibold ff-secondary mb-2" id="agedItems">0</h4>
                                            <span class="badge bg-danger-subtle text-danger fs-11">6+ days</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-danger-subtle rounded fs-4">
                                                    <i class="bx bx-alarm-exclamation text-danger"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Average Days Card - Special widget -->
                        <div class="col-xl-2 col-lg-3 col-md-6">
                            <div class="card card-animate bg-primary-subtle border-0">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase fw-medium text-primary mb-1 fs-12"><?= lang('App.avg_in_this_step') ?></p>
                                            <h4 class="fs-18 fw-semibold ff-secondary mb-2 text-primary" id="avgDaysNumber">0</h4>
                                            <span class="badge bg-primary text-white fs-11">avg days</span>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-primary rounded fs-4">
                                                    <i class="bx bx-calculator text-white"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <br>
                <br>
                    <!-- Main Inventory Table -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <div class="flex-grow-1">
                                            <h4 class="card-title mb-0">
                                                <i class="ri-table-line me-2"></i>
                                                <?= lang('App.inventory_table') ?>
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="d-flex flex-wrap align-items-start gap-2">
                                                <!-- Clear Filters Button -->
                                                <button id="clearAllFilters" class="btn btn-outline-secondary btn-sm">
                                                    <i class="ri-filter-off-line align-bottom me-1"></i>
                                                    <?= lang('App.clear_filters') ?>
                                                </button>
                                                
                                                <!-- Refresh Button -->
                                                <button id="refreshInventoryBtn" class="btn btn-primary btn-sm">
                                                    <i class="ri-refresh-line align-bottom me-1"></i>
                                                    <?= lang('App.refresh') ?>
                                                </button>

                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <div class="table-responsive table-card">
                                        <table id="inventoryTable" class="table ">
                                            <thead class="">
                                                <tr>
                                                    <!-- Staff-only Select Column -->
                                                    <th class="<?= $staffOnlyClass ?>" <?= $staffOnlyStyle ?>>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="selectAllInventory">
                                                        </div>
                                                    </th>
                                                    <th scope="col"><?= lang('App.date_in_detail') ?></th>
                                                    <th scope="col"><?= lang('App.day_in_this_step') ?></th>
                                                    <th scope="col"><?= lang('App.keys') ?></th>
                                                    <th scope="col"><?= lang('App.stock_number') ?></th>
                                                    <th scope="col"><?= lang('App.vehicle') ?></th>
                                                    <th scope="col">Notes</th>
                                                    <th scope="col"><?= lang('App.status') ?></th>
                                                    <!-- Staff-only Actions Column -->
                                                    <th scope="col" class="<?= $staffOnlyClass ?>" <?= $staffOnlyStyle ?>><?= lang('App.actions') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data loaded via DataTables -->
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Last Refresh Info -->
                                    <div class="mt-3 text-center">
                                        <small id="lastRefreshInfo" class="text-muted">
                                            <!-- Last refresh time will be updated here -->
                                        </small>
                                    </div>
                                </div>
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
            fetch('../recon_orders/check_auth', {
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
                updateAuthenticationUI();
            })
            .catch(error => {
                // On error, assume not authenticated
                window.isAuthenticated = false;
                window.userType = 'guest';
                window.authCheckCompleted = true;
                
                if (document.getElementById('debugAuth')) {
                    document.getElementById('debugAuth').textContent = 'Error';
                }
                
                updateAuthenticationUI();
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
        
        // Start authentication check
        checkAuthenticationStatus();
    </script>

    <!-- Load responsive interactions -->
    <script src="js/responsive-interactions.js"></script>
    
    <!-- Load main vehicles inventory script -->
    <script src="js/vehicles-inventory.js"></script>

</body>
</html>