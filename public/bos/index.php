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
            'App.total_stock_items' => 'Total Incomplete',
            'App.recent_items' => 'Recent',
            'App.moderate_items' => 'Moderate',
            'App.aged_items' => 'Aged',
            'App.avg_in_this_step' => 'Avg in this Step',
            'App.clear_filters' => 'Clear Filters',
            'App.order_number' => 'Order Number',
            'App.client_name' => 'Client Name',
            'App.service_date' => 'Service Date',
            'App.refresh' => 'Refresh',
            'App.filters_applied' => 'Filters applied',
            'App.filters_cleared' => 'Filters cleared',
            'App.excellent_turnaround' => 'Excellent turnaround',
            'App.good_turnaround' => 'Good turnaround',
            'App.needs_attention' => 'Needs attention',
            'App.critical_delay' => 'Critical delay',
            'App.confirm_conversion' => 'Confirm Conversion',
            'App.move_multiple_stocks' => 'Move multiple stocks',
            'App.selected_items' => 'selected items',
            'App.yes_convert' => 'Yes, Convert',
            'App.cancel' => 'Cancel',
            'App.inventory_stats' => 'Inventory Statistics',
            'App.click_to_filter' => 'Click to filter results',
            'App.time_analysis' => 'Time Analysis',
            'App.inventory_table' => 'Inventory Table',
            'App.detailed_inventory_view' => 'Detailed inventory view',
            'App.orders_from_inventory' => 'Orders from Inventory',
            'App.created_from_inventory' => 'Created from inventory',
            'App.all_orders' => 'All Orders',
            'App.complete_orders_list' => 'Complete orders list',
            'App.status_initial_processing' => 'Initial Processing',
            'App.status_detail_in_progress' => 'Detail in Progress',
            'App.status_completed' => 'Completed',
            'App.status_cancelled' => 'Cancelled',
            'App.status_no_status_yet' => 'No Status Yet',
            'App.move_to_recon' => 'Move to Recon'
        ];
        return isset($translations[$key]) ? $translations[$key] : $key;
    }
}

// Mock base_url function if not available
if (!function_exists('base_url')) {
    function base_url($path = '') {
        return '../' . ltrim($path, '/');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>BOS Inventory Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="BOS Inventory Management System" name="description" />
    <meta content="BOS" name="author" />
    
    <?php include 'partials/head-css.php'; ?>

    <!-- Enhanced component-specific styles with modern design -->
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-light: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Top Bar Enhancements */
        .top-bar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            box-shadow: var(--shadow-lg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .top-bar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .top-bar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            font-weight: 700;
            font-size: 1.125rem;
        }

        .top-bar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-role {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .top-bar-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
            border-radius: var(--radius-md);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .top-bar-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-1px);
        }

        /* Enhanced Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            background: white;
            border-radius: var(--radius-lg);
                 padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .metric-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .metric-change {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
        }

        .metric-change.positive {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .metric-change.neutral {
            background: rgba(100, 116, 139, 0.1);
            color: var(--text-secondary);
        }

        .metric-change.negative {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        /* Enhanced Cards */
        .minimal-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .minimal-card:hover {
            box-shadow: var(--shadow-lg);
        }

        .minimal-card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .minimal-card-icon {
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .minimal-card-title {
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            font-size: 1rem;
        }

        .minimal-card-body {
            padding: 1.5rem;
        }

        /* Enhanced Tables */
        .service-table {
            width: 100%;
            border-collapse: collapse;
        }

        .service-table thead th {
            background: var(--light-color);
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.875rem;
            padding: 1rem 0.75rem;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
        }

        .service-table tbody td {
            padding: 0.875rem 0.75rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
        }

        .service-table tbody tr:hover {
            background: rgba(37, 99, 235, 0.02);
        }

        /* Enhanced Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .status-badge.recent {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-badge.moderate {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-badge.aged {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .status-badge.completed {
            background: rgba(6, 182, 212, 0.1);
            color: var(--info-color);
            border: 1px solid rgba(6, 182, 212, 0.2);
        }

        /* Enhanced Filter Widgets */
        .filter-widget {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .filter-widget::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-color);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .filter-widget:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .filter-widget:hover::before {
            transform: scaleX(1);
        }

        .filter-widget.active {
            border-color: var(--primary-color);
            background: rgba(37, 99, 235, 0.02);
        }

        .filter-widget.active::before {
            transform: scaleX(1);
        }

        /* Enhanced Buttons */
        .btn {
            border-radius: var(--radius-md);
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            box-shadow: var(--shadow-sm);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-outline-secondary {
            border: 2px solid var(--text-secondary);
            color: var(--text-secondary);
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: var(--text-secondary);
            color: white;
        }

        /* Enhanced Page Title */
        .page-title-box h4 {
            color: var(--text-primary);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #syncIndicator {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        /* Enhanced Inventory Table */
        .inventory-table-card {
            margin-bottom: 2rem;
        }

        .inventory-table-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .table-hover tbody tr:hover {
            background: rgba(37, 99, 235, 0.02);
        }

        .table thead th {
            background: var(--light-color);
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Debug Info Enhancement */
        .debug-info {
            background: var(--dark-color);
            color: white;
            padding: 1rem;
            border-radius: var(--radius-md);
            margin: 1rem 0;
            display: none;
        }

        .debug-info.show {
            display: block;
        }

        .debug-info h4 {
            color: #fbbf24;
            margin-bottom: 0.5rem;
        }

        .debug-info pre {
            background: rgba(0, 0, 0, 0.3);
            padding: 1rem;
            border-radius: var(--radius-sm);
            overflow-x: auto;
            font-size: 0.875rem;
        }

        /* Staff Only Sections */
        .staff-only {
            opacity: 0.5;
            pointer-events: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .top-bar-content {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .top-bar-left,
            .top-bar-right {
                width: 100%;
                justify-content: space-between;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .metric-card {
                padding: 1rem;
            }

            .metric-value {
                font-size: 1.5rem;
            }

            .minimal-card-header {
                padding: 1rem;
            }

            .minimal-card-body {
                padding: 1rem;
            }

            .service-table thead th,
            .service-table tbody td {
                padding: 0.5rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .filter-widget {
                padding: 1rem;
            }

            .inventory-table-header {
                padding: 1rem;
            }

            .inventory-table-header .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .d-flex.gap-2 {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .btn-sm {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }
        }

        /* Loading States */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* Enhanced Container */
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .dashboard-container {
            background: transparent;
        }

        .dashboard-body {
            background: transparent;
        }
    </style>
    
</head>

<body>
    <!-- Page Loading Overlay -->
    <div id="pageLoadingOverlay" class="page-loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="loading-text">
                <h5 class="mb-2">Loading Inventory Data</h5>
                <p class="text-muted mb-0">Please wait while we load the latest inventory information...</p>
            </div>
        </div>
    </div>

    <!-- Enhanced top bar with better visual hierarchy -->
    <div id="topBar" class="top-bar">
        <div class="top-bar-content">
            <div class="top-bar-left">
                <div class="top-bar-logo">
                    <i data-feather="home"></i>
                    My Detail Area
                </div>
                <div class="top-bar-breadcrumb">
                    <span>Home</span>
                    <i data-feather="chevron-right" style="width: 16px; height: 16px;"></i>
                    <span>BOS Inventory</span>
                </div>
            </div>
            <div class="top-bar-right">
                
                <div class="top-bar-actions">
                   
                    <button id="debugToggle" class="top-bar-btn" style="display: none;">
                        <i data-feather="code" style="width: 16px; height: 16px;"></i>
                        Debug
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug Information -->
    <div id="debugInfo" class="debug-info">
        <h4>🔍 Authentication Debug Information</h4>
        <pre id="debugContent"></pre>
    </div>

    <!-- Inventory Management Content -->
    <div class="container-fluid">
        
        <!-- Dashboard Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">
                                <i class="ri-dashboard-3-line me-2"></i>
                                Inventory Management Dashboard
                                <span id="syncIndicator" class="badge bg-success ms-2" style="font-size: 0.75rem; font-weight: 500;">
                                    <i class="ri-wifi-line me-1"></i>Live Sync
                                </span>
                            </h4>
                    
        </div>
                    </div>
                </div>

        

        <!-- Complete Dashboard Container -->
        <div class="service-summary-container">
            <div class="service-summary-header">
                <h5 class="service-summary-title">
                    <i class="ri-dashboard-line me-2"></i>Dashboard Overview
                </h5>
                <div class="service-summary-stats">
                    <span class="summary-stat inventory">
                        <i class="ri-car-line"></i>
                        <span id="dashTotalItems">25</span> Total
                    </span>
                    <span class="summary-stat recent">
                        <i class="ri-time-line"></i>
                        <span id="recentActivityCount">0</span> Recent
                    </span>
                    <span class="summary-stat attention">
                        <i class="ri-alert-line"></i>
                        <span id="needsAttentionCount">0</span> Attention
                    </span>
                    <span class="summary-stat completed">
                        <i class="ri-check-line"></i>
                        <span id="completedCount">0</span> Completed
                    </span>
                </div>
            </div>
            
            <!-- Inventory Overview Section -->
            <div class="inventory-overview-section">
                <div class="section-header">
                    <i class="ri-dashboard-line section-icon overview"></i>
                    <span class="section-title">Inventory Overview</span>
                </div>
                <div class="overview-metrics-grid">
                    <div class="overview-metric">
                        <div class="metric-icon total">
                            <i class="ri-car-line"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-label">Total Inventory</div>
                            <div class="metric-value" id="dashTotalItems">25</div>
                            <div class="metric-subtitle">All vehicles in system</div>
                        </div>
                    </div>
                    
                    <div class="overview-metric">
                        <div class="metric-icon recent">
                            <i class="ri-time-line"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-label">Recent Items</div>
                            <div class="metric-value" id="dashRecentItems">0</div>
                            <div class="metric-subtitle">0-1 days • Just arrived</div>
                        </div>
                    </div>
                    
                    <div class="overview-metric">
                        <div class="metric-icon moderate">
                            <i class="ri-hourglass-line"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-label">Moderate Items</div>
                            <div class="metric-value" id="dashModerateItems">24</div>
                            <div class="metric-subtitle">2-5 days • In progress</div>
                        </div>
                    </div>
                    
                    <div class="overview-metric">
                        <div class="metric-icon aged">
                            <i class="ri-alert-line"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-label">Aged Items</div>
                            <div class="metric-value" id="dashAgedItems">1</div>
                            <div class="metric-subtitle">6+ days • Needs attention</div>
                        </div>
                    </div>
                    
                    <div class="overview-metric">
                        <div class="metric-icon average">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-label">Average Days</div>
                            <div class="metric-value" id="dashAvgDays">4</div>
                            <div class="metric-subtitle">Processing time</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="service-summary-grid">
                <div class="service-summary-section">
                    <div class="section-header">
                        <i class="ri-time-line section-icon recent"></i>
                        <span class="section-title">Recent Activity (0-1 days)</span>
                    </div>
                    <div class="section-table">
                        <table class="compact-table">
                            <thead>
                                <tr>
                                    <th>Stock</th>
                                    <th>Vehicle</th>
                                </tr>
                            </thead>
                            <tbody id="recentActivityTable">
                                <tr><td colspan="2" class="text-center text-muted">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="service-summary-section">
                    <div class="section-header">
                        <i class="ri-alert-line section-icon attention"></i>
                        <span class="section-title">Needs Attention (6+ days)</span>
                    </div>
                    <div class="section-table">
                        <table class="compact-table">
                            <thead>
                                <tr>
                                    <th>Stock</th>
                                    <th>Vehicle</th>
                                    <th>Days</th>
                                </tr>
                            </thead>
                            <tbody id="needsAttentionTable">
                                <tr><td colspan="3" class="text-center text-muted">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="service-summary-section">
                    <div class="section-header">
                        <i class="ri-check-line section-icon completed"></i>
                        <span class="section-title">Recently Completed</span>
                    </div>
                    <div class="section-table">
                        <table class="compact-table">
                            <thead>
                                <tr>
                                    <th>Stock</th>
                                    <th>Vehicle</th>
                                </tr>
                            </thead>
                            <tbody id="completedTable">
                                <tr><td colspan="2" class="text-center text-muted">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Analytics Sections -->
            <div class="analytics-sections-grid">
                <div class="service-summary-section">
                    <div class="section-header">
                        <i class="ri-settings-line section-icon analytics"></i>
                        <span class="section-title">Service Status Summary</span>
                        <span class="section-count" id="statusSummaryTotal">2</span>
                    </div>
                    <div class="analytics-content">
                        <div class="status-chart-container">
                            <div id="serviceStatusChart"></div>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <span class="legend-color in-progress"></span>
                                <span class="legend-label">In Progress</span>
                                <span class="legend-value" id="statusInProgress">24 (96%)</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color needs-attention"></span>
                                <span class="legend-label">Needs Attention</span>
                                <span class="legend-value" id="statusNeedsAttention">1 (4%)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="service-summary-section">
                    <div class="section-header">
                        <i class="ri-calendar-line section-icon analytics"></i>
                        <span class="section-title">Vehicle Years Distribution</span>
                        <span class="section-count" id="vehicleYearsTotal">4</span>
                    </div>
                    <div class="analytics-content">
                        <div class="years-chart-container">
                            <div id="vehicleYearsChart"></div>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <span class="legend-color year-old"></span>
                                <span class="legend-label">&lt;2015</span>
                                <span class="legend-value" id="years2015">1 (4%)</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color year-mid"></span>
                                <span class="legend-label">2015-2019</span>
                                <span class="legend-value" id="years2019">4 (16%)</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color year-recent"></span>
                                <span class="legend-label">2020-2022</span>
                                <span class="legend-value" id="years2022">6 (24%)</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color year-new"></span>
                                <span class="legend-label">2023+</span>
                                <span class="legend-value" id="years2023">14 (56%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>





        <div class="dashboard-container mb-4">
        <div class="dashboard-body">

    <!-- Inventory Statistics Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="minimal-card inventory-stats-section">
                <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                        <h5 class="mb-1">
                            <i class="ri-bar-chart-line me-2"></i>
                                <?= lang('App.inventory_stats') ?>
                            </h5>
                        <p class="text-muted mb-0"><?= lang('App.click_to_filter') ?></p>
                </div>
                    <div class="text-end">
                        <div class="text-muted small">Average Processing</div>
                        <div class="h4 mb-0" id="avgDaysNumber">0 days</div>
                    </div>
                </div>
                
                <!-- Interactive Filter Widgets -->
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="filter-widget" data-filter="" role="button" tabindex="0">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ri-car-line text-primary" style="font-size: 1.5rem;"></i>
            </div>
                                <div class="flex-grow-1">
                                    <div class="h5 mb-0" id="totalInventoryItems">0</div>
                                    <div class="text-muted small"><?= lang('App.total_stock_items') ?></div>
                </div>
                                <div class="ms-2">
                                    <i class="ri-eye-line text-muted"></i>
                    </div>
                </div>
            </div>
                </div>
                    
                    <div class="col-md-3 col-sm-6">
                        <div class="filter-widget" data-filter="0-1" role="button" tabindex="0">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ri-calendar-check-line text-success" style="font-size: 1.5rem;"></i>
                            </div>
                                <div class="flex-grow-1">
                                    <div class="h5 mb-0" id="recentItems">0</div>
                                    <div class="text-muted small"><?= lang('App.recent_items') ?></div>
                            </div>
                                <div class="ms-2">
                                    <i class="ri-filter-line text-muted"></i>
                        </div>
                            </div>
                            </div>
                        </div>
                    
                    <div class="col-md-3 col-sm-6">
                        <div class="filter-widget" data-filter="2-5" role="button" tabindex="0">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ri-calendar-line text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                                <div class="flex-grow-1">
                                    <div class="h5 mb-0" id="moderateItems">0</div>
                                    <div class="text-muted small"><?= lang('App.moderate_items') ?></div>
                            </div>
                                <div class="ms-2">
                                    <i class="ri-filter-line text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
                    
                    <div class="col-md-3 col-sm-6">
                        <div class="filter-widget" data-filter="6+" role="button" tabindex="0">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ri-calendar-close-line text-danger" style="font-size: 1.5rem;"></i>
                </div>
                                <div class="flex-grow-1">
                                    <div class="h5 mb-0" id="agedItems">0</div>
                                    <div class="text-muted small"><?= lang('App.aged_items') ?></div>
                                </div>
                                <div class="ms-2">
                                    <i class="ri-filter-line text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Main Inventory Table Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="minimal-card inventory-table-card">
                <div class="inventory-table-header">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div>
                            <h5 class="mb-1">
                                <i class="ri-table-line me-2"></i>
                                <?= lang('App.inventory_table') ?>
                            </h5>
                            <p class="text-muted mb-0 small"><?= lang('App.detailed_inventory_view') ?></p>
                </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-success btn-sm <?php echo $staffOnlyClass; ?>" id="convertSelectedBtn" disabled <?php echo $staffOnlyStyle; ?>>
                            <i class="ri-arrow-right-line me-1"></i>
                            <?= lang('App.move_selected') ?>
                        </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="hideCompletedBtn">
                            <i class="ri-eye-off-line me-1"></i>
                            Hide Completed
                        </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="refreshInventoryBtn">
                            <i class="ri-refresh-line me-1"></i>
                            <?= lang('App.refresh_inventory') ?>
                        </button>
                    </div>
        </div>
            </div>
                    <div class="table-responsive">
                    <table id="inventoryTable" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th width="40" class="text-center <?php echo $staffOnlyClass; ?>" <?php echo $staffOnlyStyle; ?>>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllInventory">
                        </div>
                                    </th>
                                    <th class="text-center"><?= lang('App.date_in_detail') ?></th>
                                    <th class="text-center"><?= lang('App.day_in_this_step') ?></th>
                                    <th class="text-center"><?= lang('App.keys') ?></th>
                                    <th class="text-center"><?= lang('App.stock_number') ?></th>
                                    <th class="text-center"><?= lang('App.vehicle') ?></th>
                                    <th class="text-center">Notes</th>
                                    <th class="text-center"><?= lang('App.status') ?></th>
                                    <th class="text-center <?php echo $staffOnlyClass; ?>" <?php echo $staffOnlyStyle; ?>><?= lang('App.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                            </div>
                        </div>
                            </div>
                            </div>
        
    <!-- Staff Management Tools - Staff Only -->
    <div class="<?php echo $staffOnlyClass; ?>" <?php echo $staffOnlyStyle; ?>>
        <div class="mb-4">
            <h4 class="mb-2">
                        <i class="ri-admin-line me-2"></i>
                        Staff Management Tools
                    </h4>
            <p class="text-muted">Advanced tools for staff members only</p>
    </div>
            
            <!-- Orders from Inventory Section - Staff Only -->
        <div class="row mb-4">
        <div class="col-12">
                <div class="minimal-card">
                    <div class="minimal-card-header">
                        <i class="ri-file-list-3-line minimal-card-icon"></i>
                        <h6 class="minimal-card-title"><?= lang('App.orders_from_inventory') ?></h6>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="refreshInventoryOrdersBtn">
                                <i class="ri-refresh-line me-1"></i>
                                <?= lang('App.refresh') ?>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="inventoryOrdersTable" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th><?= lang('App.order_number') ?></th>
                                    <th><?= lang('App.stock_number') ?></th>
                                    <th><?= lang('App.vehicle') ?></th>
                                    <th><?= lang('App.client_name') ?></th>
                                    <th><?= lang('App.service_date') ?></th>
                                    <th><?= lang('App.status') ?></th>
                                    <th>Source</th>
                                    <th><?= lang('App.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <!-- All Orders Section - Staff Only -->
        <div class="row mb-4">
        <div class="col-12">
                <div class="minimal-card">
                    <div class="minimal-card-header">
                        <i class="ri-list-check-3 minimal-card-icon"></i>
                        <h6 class="minimal-card-title">All Orders</h6>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="refreshAllOrdersBtn">
                                <i class="ri-refresh-line me-1"></i>
                Refresh
            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="allOrdersTable" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Stock</th>
                                    <th>Vehicle</th>
                                    <th>Client</th>
                                    <th>Service Date</th>
                                    <th>Status</th>
                                    <th>Source</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

   <script>
// Pass authentication status to JavaScript - CRITICAL for session handling
window.isAuthenticated = <?php echo $isAuthenticated ? 'true' : 'false'; ?>;
window.userType = '<?php echo $userType; ?>';
window.authCheckCompleted = true; // Mark as completed immediately for public pages

// Additional safety check for public BOS pages
if (window.location.pathname.includes('/bos/') || window.location.pathname.includes('public/bos/')) {
    console.log('🌐 BOS public page detected - session handling disabled');
    window.isAuthenticated = false; // Force to false for public pages
    window.authCheckCompleted = true;
}
</script>

    <?php include 'partials/vendor-scripts.php'; ?>

   <!-- Include the vehicles inventory JavaScript -->
   <script src="js/vehicles-inventory.js"></script>
    
    <!-- Dashboard JavaScript -->
    <script>
    // Dashboard functionality
    
    // Loading overlay functions (global)
    window.showPageLoading = function() {
        const overlay = document.getElementById('pageLoadingOverlay');
        if (overlay) {
            overlay.classList.remove('hidden');
        }
    };
    
    window.hidePageLoading = function() {
        const overlay = document.getElementById('pageLoadingOverlay');
        if (overlay) {
            overlay.classList.add('hidden');
            // Remove from DOM after animation
            setTimeout(() => {
                if (overlay.classList.contains('hidden')) {
                    overlay.style.display = 'none';
                }
            }, 300);
        }
    };
    
    // Update loading text (global)
    window.updateLoadingText = function(title, description) {
        const titleElement = document.querySelector('.loading-text h5');
        const descElement = document.querySelector('.loading-text p');
        if (titleElement) titleElement.textContent = title;
        if (descElement) descElement.textContent = description;
    };
    
    // Wait for inventory data to be loaded
    function initializeDashboard() {
        // Prevent multiple dashboard initializations
        if (window.dashboardInitializing) {
            console.log('🔄 Dashboard already initializing, skipping...');
            return;
        }
        
        window.dashboardInitializing = true;
        console.log('🚀 Starting dashboard initialization...');
        
        // Wait for inventory table to be ready
        function waitForInventoryData(callback, maxAttempts = 40) {
            let attempts = 0;
            
            function checkInventoryData() {
                attempts++;
                
                console.log(`🔍 Checking inventory data (attempt ${attempts}/${maxAttempts})`);
                
                // Detailed check of table state for dashboard
                const dashboardTableState = {
                    tableExists: !!window.inventoryTable,
                    hasDataMethod: window.inventoryTable && typeof window.inventoryTable.data === 'function',
                    hasSettings: window.inventoryTable && window.inventoryTable.settings,
                    settingsLength: window.inventoryTable && window.inventoryTable.settings ? window.inventoryTable.settings().length : 0,
                    isInitComplete: false
                };
                
                if (dashboardTableState.hasSettings && dashboardTableState.settingsLength > 0) {
                    dashboardTableState.isInitComplete = window.inventoryTable.settings()[0]._bInitComplete === true;
                }
                
                console.log('🔍 Dashboard table check:', dashboardTableState);
                
                // Check if table exists and is properly initialized
                if (dashboardTableState.tableExists && 
                    dashboardTableState.hasDataMethod && 
                    dashboardTableState.hasSettings &&
                    dashboardTableState.settingsLength > 0 &&
                    dashboardTableState.isInitComplete) {
                    
                    // Additional check that data is actually loaded
                    try {
                        const data = window.inventoryTable.data().toArray();
                        console.log(`📊 Found ${data.length} inventory items`);
                        if (data.length > 0) {
                            console.log('✅ Inventory data ready, initializing dashboard');
                            try {
                                callback();
                            } catch (e) {
                                console.error('❌ Error in dashboard callback:', e);
                                window.dashboardInitializing = false;
                                hidePageLoading();
                            }
                            return;
                        } else {
                            console.log('⏳ Table initialized but no data yet...');
                        }
                    } catch (e) {
                        console.log('⚠️ Error checking table data:', e.message);
                    }
                } else {
                    console.log('⏳ Table not ready yet...');
                }
                
                if (attempts < maxAttempts) {
                    setTimeout(checkInventoryData, 500); // Check more frequently
                } else {
                    console.warn('⚠️ Inventory data not ready after maximum attempts, initializing dashboard anyway');
                    try {
                        callback();
                    } catch (e) {
                        console.error('❌ Error in dashboard callback:', e);
                        window.dashboardInitializing = false;
                        hidePageLoading();
                    }
                }
            }
            
            checkInventoryData();
        }
        
        waitForInventoryData(() => {
            window.dashboardInitialized = true;
            window.dashboardInitializing = false; // Mark as complete
            updateDashboardStats();
            
            // Hide loading overlay once everything is ready
            hidePageLoading();
            
            console.log('✅ Dashboard initialization completed successfully');
            
            // Update dashboard every time inventory is refreshed
            if (window.inventoryTable && typeof window.inventoryTable.on === 'function') {
                window.inventoryTable.on('draw', function() {
                    setTimeout(updateDashboardStats, 500);
                });
            }
        });
    }
    
    // Update dashboard statistics
    function updateDashboardStats() {
        let data = [];
        
        if (window.inventoryTable && window.inventoryTable.data && typeof window.inventoryTable.data === 'function') {
            data = window.inventoryTable.data().toArray();
        }
        
        if (data.length === 0) {
            return;
        }
        
        const stats = calculateInventoryStats(data);
        
        // Update dashboard widgets
        updateCounterValue('dashTotalItems', stats.total);
        updateCounterValue('dashRecentItems', stats.recent);
        updateCounterValue('dashModerateItems', stats.moderate);
        updateCounterValue('dashAgedItems', stats.aged);
        updateCounterValue('dashAvgDays', stats.avgDays);
        
        // Update avg days in statistics section
        const avgDaysElement = document.getElementById('avgDaysNumber');
        if (avgDaysElement) {
            avgDaysElement.textContent = `${stats.avgDays} days`;
        }
        
        // Update service tables
        updateServiceTables(data, stats);
    }
    
    // Calculate statistics from inventory data
    function calculateInventoryStats(data) {
        let total = data.length;
        let recent = 0;
        let moderate = 0;
        let aged = 0;
        let active = 0;
        let completed = 0;
        let totalDays = 0;
        let validDaysCount = 0;
        
        data.forEach(row => {
            const days = parseInt(row.days_detail) || 0;
            
            if (days >= 0) {
                totalDays += days;
                validDaysCount++;
            }
            
            // Categorize by age
            if (days <= 1) {
                recent++;
            } else if (days >= 2 && days <= 5) {
                moderate++;
            } else if (days >= 6) {
                aged++;
            }
            
            // Check status (this would need to be connected to the status system)
            // For now, we'll use a simple heuristic
            if (days < 10) {
                active++;
            } else {
                completed++;
            }
        });
        
        const avgDays = validDaysCount > 0 ? Math.round(totalDays / validDaysCount) : 0;
        
        return {
            total,
            recent,
            moderate,
            aged,
            active,
            completed,
            avgDays,
            distribution: {
                recent: recent,
                moderate: moderate,
                aged: aged
            }
        };
    }
    
    // Update counter values with animation
    function updateCounterValue(elementId, value) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const currentValue = parseInt(element.textContent) || 0;
        
        if (currentValue !== value) {
            // Simple counter animation
            const increment = value > currentValue ? 1 : -1;
            const step = Math.abs(value - currentValue) / 10;
            let current = currentValue;
            
            const timer = setInterval(() => {
                current += increment * Math.max(1, Math.floor(step));
                
                if ((increment > 0 && current >= value) || (increment < 0 && current <= value)) {
                    current = value;
                    clearInterval(timer);
                }
                
                element.textContent = Math.round(current);
            }, 50);
        }
    }
    
    // Update service tables with filtered data
    function updateServiceTables(data, stats) {
        updateRecentActivityTable(data);
        updateNeedsAttentionTable(data);
        updateCompletedTable(data);
        updateServiceStatusChart(data);
        updateVehicleYearsChart(data);
    }
    
    // Update recent activity table - Show 5 most recent (lowest days)
    function updateRecentActivityTable(data) {
        const recentItems = data
            .filter(item => {
                const days = parseInt(item.days_detail) || 0;
                return days <= 1;
            })
            .sort((a, b) => {
                // Sort by days ascending (most recent first)
                const daysA = parseInt(a.days_detail) || 0;
                const daysB = parseInt(b.days_detail) || 0;
                return daysA - daysB;
            })
            .slice(0, 5);
        
        // Update count in summary stats
        updateCounterValue('recentActivityCount', data.filter(item => {
            const days = parseInt(item.days_detail) || 0;
            return days <= 1;
        }).length);
        
        const tbody = document.getElementById('recentActivityTable');
        if (!tbody) return;
        
        if (recentItems.length === 0) {
            tbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">No recent items</td></tr>';
            return;
        }
        
        tbody.innerHTML = recentItems.map(item => `
            <tr>
                <td><strong>${item.stock_number || 'N/A'}</strong></td>
                <td>${item.vehicle || 'N/A'}</td>
            </tr>
        `).join('');
    }
    
    // Update needs attention table - Show 5 with most days (highest priority)
    function updateNeedsAttentionTable(data) {
        const allNeedsAttention = data.filter(item => {
            const days = parseInt(item.days_detail) || 0;
            return days >= 6;
        });
        
        const needsAttention = allNeedsAttention
            .sort((a, b) => {
                // Sort by days descending (most urgent first)
                const daysA = parseInt(a.days_detail) || 0;
                const daysB = parseInt(b.days_detail) || 0;
                return daysB - daysA;
            })
            .slice(0, 5);
        
        // Update count in summary stats (total count, not just top 5)
        updateCounterValue('needsAttentionCount', allNeedsAttention.length);
        
        const tbody = document.getElementById('needsAttentionTable');
        if (!tbody) return;
        
        if (needsAttention.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No items need attention</td></tr>';
            return;
        }
        
        tbody.innerHTML = needsAttention.map(item => `
            <tr>
                <td><strong>${item.stock_number || 'N/A'}</strong></td>
                <td>${item.vehicle || 'N/A'}</td>
                <td><span class="status-badge aged">${item.days_detail || 0} days</span></td>
            </tr>
        `).join('');
    }
    
    // Update completed table - Show 5 most recently completed (highest days, assuming they were completed)
    function updateCompletedTable(data) {
        // For demo purposes, we'll show items that have been processed for a while
        const allCompleted = data.filter(item => {
            const days = parseInt(item.days_detail) || 0;
            return days >= 10; // Assume items over 10 days are completed
        });
        
        const completed = allCompleted
            .sort((a, b) => {
                // Sort by days descending (most recently completed first)
                const daysA = parseInt(a.days_detail) || 0;
                const daysB = parseInt(b.days_detail) || 0;
                return daysB - daysA;
            })
            .slice(0, 5);
        
        // Update count in summary stats (total count, not just top 5)
        updateCounterValue('completedCount', allCompleted.length);
        
        const tbody = document.getElementById('completedTable');
        if (!tbody) return;
        
        if (completed.length === 0) {
            tbody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">No completed items</td></tr>';
            return;
        }
        
        tbody.innerHTML = completed.map(item => `
            <tr>
                <td><strong>${item.stock_number || 'N/A'}</strong></td>
                <td>${item.vehicle || 'N/A'}</td>
            </tr>
        `).join('');
    }
    
    // Update service status chart
    function updateServiceStatusChart(data) {
        // Group by actual status values instead of days
        const statusGroups = {};
        
        data.forEach(item => {
            // Use the actual status from the data, or default based on days
            let status = 'Unknown';
            if (item.status && typeof item.status === 'string') {
                status = item.status.trim();
            } else if (item.inventory_status && typeof item.inventory_status === 'string') {
                status = item.inventory_status.trim();
            } else {
                // Fallback to days-based grouping if no status available
                const days = parseInt(item.days_detail) || 0;
                if (days <= 1) status = 'Initial Processing';
                else if (days <= 5) status = 'In Progress';
                else status = 'Needs Attention';
            }
            
            statusGroups[status] = (statusGroups[status] || 0) + 1;
        });
        
        // Update count in metric widget and section header
        updateCounterValue('statusSummaryTotal', Object.keys(statusGroups).length);
        
        // Update legend values
        const total = data.length;
        let inProgressCount = 0, needsAttentionCount = 0;
        
        Object.entries(statusGroups).forEach(([status, count]) => {
            if (status.toLowerCase().includes('progress')) {
                inProgressCount = count;
            } else if (status.toLowerCase().includes('attention') || status.toLowerCase().includes('needs')) {
                needsAttentionCount = count;
            }
        });
        
        const inProgressPercent = Math.round((inProgressCount / total) * 100);
        const needsAttentionPercent = Math.round((needsAttentionCount / total) * 100);
        
        updateCounterValue('statusInProgress', `${inProgressCount} (${inProgressPercent}%)`);
        updateCounterValue('statusNeedsAttention', `${needsAttentionCount} (${needsAttentionPercent}%)`);
        
        const chartContainer = document.getElementById('serviceStatusChart');
        if (!chartContainer) return;
        
        const chartData = Object.entries(statusGroups)
            .sort(([,a], [,b]) => b - a); // Sort by count descending
        
        if (chartData.length === 0) {
            chartContainer.innerHTML = '<div class="text-center text-muted py-4">No status data</div>';
            return;
        }
        
        // Create horizontal bar chart using CSS
        const maxCount = Math.max(...chartData.map(([, count]) => count));
        
        // Define colors for different statuses
        const statusColors = {
            'Initial Processing': '#17a2b8',
            'In Progress': '#ffc107', 
            'Needs Attention': '#dc3545',
            'Completed': '#28a745',
            'Unknown': '#6c757d'
        };
        
        chartContainer.innerHTML = `
            <div class="status-chart">
                ${chartData.map(([status, count], index) => {
                    const percentage = Math.round((count / total) * 100);
                    const barWidth = Math.round((count / maxCount) * 100);
                    const color = statusColors[status] || `hsl(${index * 60}, 70%, 50%)`;
                    
                    return `
                        <div class="status-bar-container">
                            <div class="status-bar-label">${status}</div>
                            <div class="status-bar-wrapper">
                                <div class="status-bar" style="width: ${barWidth}%; background-color: ${color};">
                                    <div class="status-bar-fill"></div>
                                </div>
                                <div class="status-bar-count">${count} (${percentage}%)</div>
                            </div>
                        </div>
                    `;
                }).join('')}
            </div>
        `;
    }
    
    // Update vehicle years chart
    function updateVehicleYearsChart(data) {
        const vehicleYears = {};
        const currentYear = new Date().getFullYear();
        
        data.forEach((item, index) => {
            if (item.vehicle) {
                // Extract year from vehicle string (look for 4-digit number)
                const yearMatch = item.vehicle.match(/\b(19|20)\d{2}\b/);
                let year = yearMatch ? parseInt(yearMatch[0]) : null;
                
                // If no year found in vehicle string, try other fields or use a default range
                if (!year) {
                    // You can add logic here to extract year from other fields if available
                    year = 'Unknown';
                } else {
                    // Group years into ranges for better visualization
                    if (year >= currentYear - 2) {
                        year = `${currentYear-2}+`;
                    } else if (year >= currentYear - 5) {
                        year = `${currentYear-5}-${currentYear-3}`;
                    } else if (year >= currentYear - 10) {
                        year = `${currentYear-10}-${currentYear-6}`;
                    } else {
                        year = `<${currentYear-10}`;
                    }
                }
                
                vehicleYears[year] = (vehicleYears[year] || 0) + 1;
            }
        });
        
        // Update count in metric widget (number of different year ranges)
        updateCounterValue('vehicleYearsTotal', Object.keys(vehicleYears).length);
        
        // Update legend values
        const total = data.length;
        const yearRanges = {
            '<2015': vehicleYears[`<${currentYear-10}`] || 0,
            '2015-2019': vehicleYears[`${currentYear-10}-${currentYear-6}`] || 0,
            '2020-2022': vehicleYears[`${currentYear-5}-${currentYear-3}`] || 0,
            '2023+': vehicleYears[`${currentYear-2}+`] || 0
        };
        
        Object.entries(yearRanges).forEach(([range, count]) => {
            const percent = Math.round((count / total) * 100);
            const elementId = range.replace('<', '').replace('-', '').replace('+', '');
            updateCounterValue(`years${elementId}`, `${count} (${percent}%)`);
        });
        
        // Prepare data for chart
        const chartData = Object.entries(vehicleYears)
            .sort(([a], [b]) => {
                // Custom sort to put year ranges in logical order
                if (a === 'Unknown') return 1;
                if (b === 'Unknown') return -1;
                return a.localeCompare(b);
            });
        
        const chartContainer = document.getElementById('vehicleYearsChart');
        if (!chartContainer) return;
        
        if (chartData.length === 0) {
            chartContainer.innerHTML = '<div class="text-center text-muted py-4">No vehicle data</div>';
            return;
        }
        
        // Create simple bar chart using CSS
        const maxCount = Math.max(...chartData.map(([, count]) => count));
        
        const chartHTML = `
            <div class="years-chart">
                ${chartData.map(([year, count], index) => {
                    const percentage = Math.round((count / total) * 100);
                    const barHeight = Math.max(Math.round((count / maxCount) * 100), 5); // Minimum 5% height
                    return `
                        <div class="year-bar-container">
                            <div class="year-bar" style="height: ${barHeight}%;">
                                <div class="year-bar-fill"></div>
                            </div>
                            <div class="year-label">${year}</div>
                            <div class="year-count">${count} (${percentage}%)</div>
                        </div>
                    `;
                }).join('')}
            </div>
        `;
        
        chartContainer.innerHTML = chartHTML;
    }

    
    // Initialize dashboard when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Show loading overlay immediately
        showPageLoading();
        updateLoadingText('Initializing System', 'Setting up authentication and components...');
        
        // Listen for tables ready event (prevent multiple initializations)
        let dashboardInitStarted = false;
        window.addEventListener('tablesReady', function() {
            if (dashboardInitStarted) {
                console.log('🔄 Dashboard initialization already started, skipping...');
                return;
            }
            
            dashboardInitStarted = true;
            console.log('🎯 Tables ready event received, initializing dashboard');
            updateLoadingText('Loading Inventory Data', 'Fetching latest inventory information...');
            setTimeout(initializeDashboard, 500);
        });
        
        // Additional check - if no table initialization happens, force it
        setTimeout(() => {
            if (!window.inventoryTable && !window.tableInitializing) {
                console.warn('🚨 No table found after 5 seconds, forcing initialization...');
                if (window.initializeTables && typeof window.initializeTables === 'function') {
                    window.initializeTables();
                }
            }
        }, 5000);
        
        // Update loading text when auth is complete
        setTimeout(() => {
            if (window.authCheckCompleted) {
                updateLoadingText('Loading Tables', 'Initializing data tables and components...');
            }
        }, 1000);
        
        // Fallback in case event doesn't fire
        setTimeout(() => {
            if (!window.dashboardInitialized) {
                console.log('🔄 Fallback dashboard initialization');
                updateLoadingText('Finalizing Setup', 'Completing initialization process...');
                initializeDashboard();
            }
        }, 10000);
        
        // Emergency fallback to hide loading
        setTimeout(() => {
            if (!window.dashboardInitialized) {
                console.warn('⚠️ Emergency fallback - hiding loading overlay');
                hidePageLoading();
            }
        }, 15000);
        
        // Mobile responsive and completed filter functionality
        // Add authenticated class to body for mobile styling
        if (window.isAuthenticated) {
            document.body.classList.add('authenticated');
        }
        
        // Wait for DataTables to be ready
        function waitForDataTables(callback, maxAttempts = 20) {
            let attempts = 0;
            
            function checkDataTables() {
                attempts++;
                
                console.log(`🔍 Checking for DataTables (attempt ${attempts}/${maxAttempts})`);
                
                if (window.inventoryTable && window.inventoryTable.data && window.inventoryTable.data().length > 0) {
                    console.log('✅ DataTables ready, initializing mobile features');
                    callback();
                } else if (attempts < maxAttempts) {
                    setTimeout(checkDataTables, 100);
                } else {
                    console.warn('⚠️ DataTables not ready after maximum attempts, proceeding anyway');
                    callback();
                }
            }
            
            checkDataTables();
        }
        
        // Initialize mobile features after DataTables is ready
        waitForDataTables(function() {
            // Initialize mobile column visibility
            if (window.updateTableColumnsVisibility) {
                window.updateTableColumnsVisibility();
            }
            
            // Set up hide completed functionality
            const hideCompletedBtn = document.getElementById('hideCompletedBtn');
            if (hideCompletedBtn) {
                console.log('✅ Hide Completed button found, setting up event listener');
                hideCompletedBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const isHidden = this.classList.contains('active');
                    
                    if (isHidden) {
                        // Show all rows
                        this.classList.remove('active');
                        this.innerHTML = '<i data-feather="eye-off" style="width: 16px; height: 16px;"></i> Hide Completed';
                        
                        if (window.inventoryTable) {
                            window.inventoryTable.rows().every(function() {
                                $(this.node()).show();
                            });
                        }
                    } else {
                        // Hide completed rows
                        this.classList.add('active');
                        this.innerHTML = '<i data-feather="eye" style="width: 16px; height: 16px;"></i> Show All';
                        
                        if (window.inventoryTable) {
                            window.inventoryTable.rows().every(function() {
                                const data = this.data();
                                const statusCell = $(this.node()).find('td').eq(8); // Status column
                                const statusText = statusCell.text().toLowerCase();
                                
                                if (statusText.includes('completed') || statusText.includes('done') || statusText.includes('finished')) {
                                    $(this.node()).hide();
                                }
                            });
                        }
                    }
                    
                    // Re-initialize feather icons
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                });
            } else {
                console.warn('⚠️ Hide Completed button not found');
            }
        });
    });
    </script>
   
   <script>
   // Legacy authentication check for backward compatibility - CONSOLIDATED
   (async function() {
       // Add authenticated class to body for mobile styling
       if (window.isAuthenticated) {
           document.body.classList.add('authenticated');
       }
       
       // Wait for DataTables to be ready
       function waitForDataTables(callback, maxAttempts = 20) {
           let attempts = 0;
           
           function checkDataTables() {
               attempts++;
               
               if (window.$ && window.$.fn && window.$.fn.dataTable && window.inventoryTable) {
                   console.log('✅ DataTables ready, initializing mobile features');
                   callback();
               } else if (attempts < maxAttempts) {
                   setTimeout(checkDataTables, 500);
               } else {
                   console.warn('⚠️ DataTables not ready after maximum attempts');
                   // Still run callback for basic functionality
                   callback();
               }
           }
           
           checkDataTables();
       }
       
       // Initialize everything when DataTables is ready
       waitForDataTables(function() {
           initializeMobileAndFilters();
       });
       
       function initializeMobileAndFilters() {
           // Hide completed items filter
           let hideCompleted = false;
           const hideCompletedBtn = document.getElementById('hideCompletedBtn');
           
           if (hideCompletedBtn) {
               console.log('✅ Hide Completed button found, setting up event listener');
               
               hideCompletedBtn.addEventListener('click', function(e) {
                   e.preventDefault();
                   
                   hideCompleted = !hideCompleted;
                   console.log('🔄 Button clicked, hideCompleted is now:', hideCompleted);
                   
                   if (hideCompleted) {
                       this.innerHTML = '<i class="ri-eye-line me-1"></i>Show Completed';
                       this.classList.remove('btn-outline-secondary');
                       this.classList.add('btn-secondary');
                       console.log('👁️ Button state: Show Completed (hiding completed items)');
                   } else {
                       this.innerHTML = '<i class="ri-eye-off-line me-1"></i>Hide Completed';
                       this.classList.remove('btn-secondary');
                       this.classList.add('btn-outline-secondary');
                       console.log('🙈 Button state: Hide Completed (showing all items)');
                   }
                   
                   // Apply filter to inventory table (wait for status to be loaded)
                   waitForStatusLoaded(() => {
                       filterCompletedItems();
                   });
               });
           } else {
               console.warn('⚠️ Hide Completed button not found');
           }
           
           // Function to wait for status to be loaded
           function waitForStatusLoaded(callback, maxAttempts = 10) {
               let attempts = 0;
               
               function checkStatus() {
                   attempts++;
                   const statusElements = document.querySelectorAll('.status-service-info');
                   let loadingCount = 0;
                   let completedCount = 0;
                   
                   statusElements.forEach(element => {
                       const text = element.textContent.toLowerCase();
                       if (text.includes('loading')) {
                           loadingCount++;
                       } else if (text.includes('completed')) {
                           completedCount++;
                       }
                   });
                   
                   console.log(`🔍 Status check attempt ${attempts}: ${loadingCount} loading, ${completedCount} completed, ${statusElements.length} total`);
                   
                   if (loadingCount === 0 && statusElements.length > 0) {
                       console.log('✅ All status loaded, proceeding with filter');
                       callback();
                   } else if (attempts < maxAttempts) {
                       setTimeout(checkStatus, 1000);
                   } else {
                       console.warn('⚠️ Status loading timeout, proceeding anyway');
                       callback();
                   }
               }
               
               checkStatus();
           }
           
           // Make filter functions globally accessible
           window.applyCompletedFilter = function() {
               if (hideCompleted) {
                   console.log('🔄 Reapplying completed filter after status update');
                   waitForStatusLoaded(() => {
                       filterCompletedItems();
                   });
               }
           };
           
           // Function to filter completed items
           function filterCompletedItems() {
               if (window.inventoryTable && typeof window.inventoryTable.draw === 'function') {
                   console.log('🔍 Applying completed filter:', hideCompleted);
                   
                   // Clear existing search functions first
                   clearCompletedFilter();
                   
                   if (hideCompleted) {
                       // Add new custom search function for DataTables
                       const searchFunction = function(settings, data, dataIndex) {
                           // Only apply to inventory table
                           if (settings.nTable.id !== 'inventoryTable') {
                               return true;
                           }
                           
                           // Get the actual DOM element to check real status
                           const row = settings.aoData[dataIndex];
                           if (row && row.nTr) {
                               const statusCell = row.nTr.querySelector('.status-service-info');
                               if (statusCell) {
                                   const statusText = statusCell.textContent.toLowerCase();
                                   const isCompleted = statusText.includes('completed');
                                   
                                   // Debug first few rows
                                   if (dataIndex < 3) {
                                       console.log(`📊 Row ${dataIndex} DOM filter check:`, {
                                           statusText: statusText.trim(),
                                           isCompleted: isCompleted,
                                           willShow: !isCompleted
                                       });
                                   }
                                   
                                   return !isCompleted;
                               }
                           }
                           
                           // Fallback to data array method
                           const statusCell = data[7] || '';
                           const statusText = statusCell.toLowerCase();
                           const isCompleted = statusText.includes('completed') || 
                                             statusText.includes('success') ||
                                             statusText.includes('bg-success');
                           
                           return !isCompleted;
                       };
                       
                       $.fn.dataTable.ext.search.push(searchFunction);
                       console.log('➕ Added filter function');
                   } else {
                       console.log('🔄 Showing all items (no filter)');
                   }
                   
                   // Redraw the table to apply the filter
                   window.inventoryTable.draw();
                   
                   // Get current visible rows count
                   setTimeout(() => {
                       const info = window.inventoryTable.page.info();
                       console.log('✅ Filter applied:', {
                           totalRecords: info.recordsTotal,
                           filteredRecords: info.recordsDisplay,
                           hideCompleted: hideCompleted
                       });
                   }, 100);
               } else {
                   console.warn('⚠️ Inventory table not ready for filtering');
               }
           }
           
           // Remove the search function when not needed
           function clearCompletedFilter() {
               const originalLength = $.fn.dataTable.ext.search.length;
               
               // Remove our custom search function
               $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) {
                   const fnString = fn.toString();
                   return !fnString.includes('hideCompleted') && !fnString.includes('isCompleted');
               });
               
               const newLength = $.fn.dataTable.ext.search.length;
               console.log(`🧹 Cleared filters: ${originalLength} → ${newLength}`);
           }
       }
   });
   </script>

</body>
</html>

