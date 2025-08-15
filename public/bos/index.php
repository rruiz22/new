<?php
// Start session before any HTML output
session_start();

// Check if user is authenticated for staff-only features
// Default to true for initial load, JavaScript will handle the real check
$isAuthenticated = true; // Changed to true by default
$userType = 'staff';

// Look for CodeIgniter session cookie
$ciSessionFound = false;
foreach ($_COOKIE as $name => $value) {
    if (strpos($name, 'ci_session') !== false) {
        $ciSessionFound = true;
        break;
    }
}

// If no CI session cookie found, check for other session indicators
if (!$ciSessionFound) {
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
    
    // If no session indicators, set as guest but still show interface
    // JavaScript will handle the final authentication state
    if (!$sessionAuthFound) {
        $userType = 'guest';
        // Keep $isAuthenticated = true for initial UI display
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
            'App.total_stock_items' => 'Total Stock Items',
            'App.recent_items' => 'Recent Items',
            'App.moderate_items' => 'Moderate Items',
            'App.aged_items' => 'Aged Items',
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
            'App.status_no_status_yet' => 'No Status Yet'
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

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-color-rgb: 37, 99, 235;
            --primary-hover: #1d4ed8;
            --primary-light: #dbeafe;
            --secondary-color: #64748b;
            --secondary-light: #f1f5f9;
            --accent-color: #06b6d4;
            --light-gray: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --success-color: #10b981;
            --success-light: #d1fae5;
            --warning-color: #f59e0b;
            --warning-light: #fef3c7;
            --danger-color: #ef4444;
            --danger-light: #fee2e2;
            --white: #ffffff;
            --background: #f8fafc;
            --surface: #ffffff;
            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07), 0 2px 4px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1), 0 10px 10px rgba(0, 0, 0, 0.04);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --radius-2xl: 20px;
            --neumorphism-light: 8px 8px 16px #d1d9e6, -8px -8px 16px #ffffff;
            --neumorphism-light-hover: 12px 12px 20px #d1d9e6, -12px -12px 20px #ffffff;
            --neumorphism-inset: inset 3px 3px 6px #d1d9e6, inset -3px -3px 6px #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--text-primary);
            line-height: 1.6;
            font-size: 14px;
            margin: 0;
            min-height: 100vh;
            font-weight: 400;
            letter-spacing: -0.01em;
        }

                 .container {
             max-width: 1440px;
             margin: 0 auto;
             padding: 1.5rem;
             position: relative;
         }

                 .page-header {
             text-align: center;
             margin-bottom: 2rem;
             padding: 2rem;
             background: var(--surface);
             border-radius: var(--radius-2xl);
             box-shadow: var(--shadow-lg);
             position: relative;
             overflow: hidden;
         }

         .page-header::before {
             content: '';
             position: absolute;
             top: 0;
             left: 0;
             right: 0;
             height: 4px;
             background: linear-gradient(90deg, var(--primary-color) 0%, var(--accent-color) 100%);
         }

         .page-title {
             font-size: 2.25rem;
             font-weight: 800;
             color: var(--primary-color);
             margin: 0;
             text-shadow: none;
             line-height: 1.1;
             letter-spacing: -0.02em;
             display: flex;
             align-items: center;
             justify-content: center;
             gap: 0.75rem;
         }

         .page-subtitle {
             font-size: 1rem;
             color: var(--text-secondary);
             margin-top: 0.5rem;
             font-weight: 500;
             line-height: 1.4;
             opacity: 0.8;
         }

                 /* Stats Container - Modern Grid Layout */
         .stats-container {
             display: grid;
             grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
             gap: 1.5rem;
             margin-bottom: 2rem;
         }

        .stat-widget {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .stat-widget::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--accent-color) 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-widget:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            border-color: var(--primary-light);
        }

        .stat-widget:hover::before {
            transform: scaleX(1);
        }

                 .widget-header {
             display: flex;
             align-items: center;
             justify-content: center;
             gap: 0.5rem;
             padding: 1rem 1.5rem 0.75rem;
             background: transparent;
             color: var(--text-secondary);
             font-weight: 600;
             font-size: 0.875rem;
             letter-spacing: 0.025em;
             text-transform: uppercase;
         }

         .widget-icon {
             width: 18px;
             height: 18px;
             opacity: 0.7;
         }

         .widget-title {
             font-size: 0.875rem;
             font-weight: 600;
         }

         .widget-content {
             padding: 0.75rem 1.5rem 1.5rem;
             text-align: center;
             display: flex;
             flex-direction: column;
             justify-content: center;
             align-items: center;
             min-height: 100px;
         }

                 .stat-value {
             font-size: 2.5rem;
             font-weight: 800;
             color: var(--primary-color);
             line-height: 1;
             margin: 0.25rem 0;
             letter-spacing: -0.02em;
         }

         .stat-unit {
             font-size: 0.875rem;
             color: var(--text-muted);
             font-weight: 500;
             margin-top: 0.25rem;
         }

         .stat-subtitle {
             font-size: 0.875rem;
             color: var(--text-secondary);
             text-align: center;
             margin: 0.5rem 0;
             line-height: 1.3;
             font-weight: 500;
         }

                 .stat-trend {
             display: flex;
             align-items: center;
             justify-content: center;
             gap: 0.375rem;
             padding: 0.5rem 1rem;
             background: var(--primary-light);
             border-radius: var(--radius-lg);
             font-size: 0.75rem;
             font-weight: 600;
             color: var(--primary-color);
             margin-top: 0.75rem;
             border: 1px solid rgba(var(--primary-color-rgb), 0.1);
         }

         .trend-icon {
             width: 14px;
             height: 14px;
         }

        .stat-status {
            display: inline-block;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-lg);
            background: var(--secondary-light);
            margin-top: 0.75rem;
            border: 1px solid var(--border-color);
        }

                 /* Chart Widget - Modern Design */
         .chart-widget .widget-content {
             padding: 0.75rem 1.5rem 1.5rem;
             justify-content: center;
             min-height: 140px;
         }

                 .distribution-bars {
             display: flex;
             flex-direction: column;
             gap: 1rem;
             margin-top: 0.75rem;
             width: 100%;
             padding: 0;
         }

         .bar-item {
             display: flex;
             flex-direction: column;
             gap: 0.5rem;
         }

        .bar-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

                 .bar-label {
             font-size: 0.875rem;
             font-weight: 600;
             color: var(--text-primary);
         }

         .bar-value {
             font-size: 0.875rem;
             font-weight: 700;
             color: var(--primary-color);
             background: var(--primary-light);
             padding: 0.25rem 0.5rem;
             border-radius: var(--radius-sm);
         }

         .bar-container {
             height: 8px;
             background: var(--light-gray);
             border-radius: var(--radius-sm);
             overflow: hidden;
             box-shadow: var(--shadow-xs);
         }

        .bar {
            height: 100%;
            border-radius: var(--radius-sm);
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .bar-excellent {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }

        .bar-good {
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        }

        .bar-warning {
            background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
        }



        .stat-status.excellent {
            background: var(--success-light);
            color: var(--success-color);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .stat-status.good {
            background: var(--primary-light);
            color: var(--primary-color);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .stat-status.warning {
            background: var(--warning-light);
            color: var(--warning-color);
            border-color: rgba(245, 158, 11, 0.2);
        }

        .stat-status.critical {
            background: var(--danger-light);
            color: var(--danger-color);
            border-color: rgba(239, 68, 68, 0.2);
        }

        .stat-value.updating {
            animation: valueUpdate 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes valueUpdate {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

                 /* Modern Controls */
         .controls {
             display: flex;
             justify-content: space-between;
             align-items: center;
             margin-bottom: 2rem;
             gap: 1rem;
             flex-wrap: wrap;
         }

                 .btn {
             padding: 0.75rem 1.5rem;
             border-radius: var(--radius-lg);
             font-weight: 600;
             font-size: 0.875rem;
             border: none;
             cursor: pointer;
             transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
             display: inline-flex;
             align-items: center;
             gap: 0.5rem;
             position: relative;
             text-decoration: none;
             letter-spacing: 0.025em;
             min-height: 44px;
         }

        .btn:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, var(--primary-hover) 0%, var(--primary-color) 100%);
        }

        .btn-outline-secondary {
            background: var(--surface);
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .btn-outline-secondary:hover {
            background: var(--light-gray);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }







        .avg-status.excellent {
            background: var(--success-color);
            color: white;
            box-shadow: 3px 3px 6px #babecc, -3px -3px 6px #ffffff;
        }

        .avg-status.good {
            background: #22c55e;
            color: white;
            box-shadow: 3px 3px 6px #babecc, -3px -3px 6px #ffffff;
        }

        .avg-status.warning {
            background: var(--warning-color);
            color: white;
            box-shadow: 3px 3px 6px #babecc, -3px -3px 6px #ffffff;
        }

        .avg-status.critical {
            background: var(--danger-color);
            color: white;
            box-shadow: 3px 3px 6px #babecc, -3px -3px 6px #ffffff;
        }

        /* Animation for when average updates */
        .avg-value.updating {
            animation: pulse 0.6s ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .error {
            background: linear-gradient(135deg, var(--danger-light) 0%, #fef2f2 100%);
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
            padding: 1rem 1.5rem;
            border-radius: var(--radius-lg);
            margin-bottom: 1.5rem;
            display: none;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }

        .error.show {
            display: block;
            animation: slideInDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

                 /* Modern Table Container */
         .table-container {
             background: var(--surface);
             border-radius: var(--radius-2xl);
             box-shadow: var(--shadow-lg);
             overflow: hidden;
             padding: 2rem;
             border: 1px solid var(--border-color);
             position: relative;
         }

         .table-container::before {
             content: '';
             position: absolute;
             top: 0;
             left: 0;
             right: 0;
             height: 4px;
             background: linear-gradient(90deg, var(--primary-color) 0%, var(--accent-color) 100%);
         }

         .table-header {
             text-align: center;
             margin-bottom: 2rem;
             padding-bottom: 1.5rem;
             border-bottom: 2px solid var(--border-color);
             position: relative;
         }

         .table-title {
             font-size: 1.5rem;
             font-weight: 700;
             color: var(--primary-color);
             margin: 0;
             display: flex;
             align-items: center;
             justify-content: center;
             gap: 0.75rem;
             line-height: 1.2;
             letter-spacing: -0.01em;
         }

         .table-icon {
             width: 1.5rem;
             height: 1.5rem;
             color: var(--primary-color);
         }

         .table-subtitle {
             font-size: 1rem;
             color: var(--text-secondary);
             margin: 0.75rem 0 0 0;
             font-weight: 500;
             line-height: 1.4;
             opacity: 0.8;
         }

        .table-wrapper {
            margin: 0 -1rem;
            border-radius: var(--radius-xl);
            overflow: hidden;
            border: 0px solid ;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            position: relative;
            background: var(--surface);
        }

        /* Modern scrollbar styling */
        .table-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .table-wrapper::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: 3px;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: var(--primary-hover);
        }

         /* Modern table header styles */
         .table thead th {
             background: linear-gradient(135deg, var(--light-gray) 0%, #ffffff 100%);
             border-bottom: 2px solid var(--border-color);
             font-weight: 700;
             font-size: 0.875rem;
             text-align: center !important;
             vertical-align: middle !important;
             color: var(--text-primary);
             padding: 1rem 0.75rem;
             line-height: 1.3;
             letter-spacing: 0.025em;
             text-transform: uppercase;
             font-size: 0.75rem;
         }

         /* Modern table body styles */
         .table tbody td {
             text-align: center !important;
             vertical-align: middle !important;
             padding: 1rem 0.75rem !important;
             line-height: 1.5;
             transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
             border-bottom: 1px solid var(--border-color);
         }

         /* Left align first column for better readability */
         .table thead th:first-child,
         .table tbody td:first-child {
             text-align: left !important;
         }

         /* Modern table row hover effects */
         .table tbody tr {
             transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
         }

         .table tbody tr:hover {
             background: linear-gradient(135deg, rgba(37, 99, 235, 0.03) 0%, rgba(6, 182, 212, 0.03) 100%) !important;
             transform: translateY(-1px);
             box-shadow: var(--shadow-md);
         }

         /* Row number styling */
         .row-number {
             font-weight: 700;
             color: var(--primary-color);
             font-size: 0.875rem;
         }

         /* Combined vehicle and stock styling */
         .vehicle-stock-container {
             display: flex;
             flex-direction: column;
             align-items: center;
             gap: 0.5rem;
         }

         /* Date with days badge styling */
         .date-with-badge {
             display: flex;
             flex-direction: column;
             align-items: center;
             gap: 0.25rem;
             font-size: 0.875rem;
         }

         /* Notes tooltip styling */
         .notes-short {
             position: relative;
             cursor: help;
             max-width: 200px;
             white-space: nowrap;
             overflow: hidden;
             text-overflow: ellipsis;
             display: inline-block;
             font-size: 0.875rem;
         }

         .notes-short[title]:hover::after {
             content: attr(title);
             position: absolute;
             bottom: 100%;
             left: 50%;
             transform: translateX(-50%);
             background: #333;
             color: white;
             padding: 0.5rem;
             border-radius: 4px;
             font-size: 0.8rem;
             white-space: normal;
             width: 300px;
             z-index: 1000;
             box-shadow: 0 2px 8px rgba(0,0,0,0.2);
             margin-bottom: 5px;
         }

         .notes-short[title]:hover::before {
             content: '';
             position: absolute;
             bottom: 100%;
             left: 50%;
             transform: translateX(-50%);
             border: 5px solid transparent;
             border-top-color: #333;
             margin-bottom: -5px;
             z-index: 1000;
         }

        

         /* Modern badge styling */
         .badge {
             font-size: 0.75em;
             font-weight: 700;
             padding: 0.5em 0.75em;
             border-radius: var(--radius-lg);
             text-align: center;
             display: inline-block;
             line-height: 1;
             transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
             letter-spacing: 0.025em;
             text-transform: uppercase;
             font-size: 0.6875rem;
         }

        .badge:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* Modern badge colors */
        .badge.bg-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%) !important;
            color: white;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%) !important;
            color: white;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%) !important;
            color: white;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

                 /* Modern stock number styling */
         .stock-number {
             font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', 'Consolas', monospace;
             background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
             color: white;
             padding: 0.5em 0.75em;
             border-radius: var(--radius-lg);
             font-size: 0.75em;
             font-weight: 700;
             letter-spacing: 0.5px;
             transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
             line-height: 1;
             display: inline-block;
             text-transform: uppercase;
             box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
         }

        .stock-number:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.4);
        }

                 /* Modern vehicle styling */
         .vehicle-info {
             font-weight: 600;
             color: var(--text-primary);
             font-size: 0.875rem;
             line-height: 1.3;
         }

        /* Modern notes preview styling */
        .notes-preview {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
            cursor: default;
            font-style: italic;
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-md);
            background: var(--light-gray);
            border: 1px solid var(--border-color);
        }

        .notes-preview.has-content {
            cursor: help;
            background: var(--secondary-light);
            border-color: var(--secondary-color);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .notes-preview.has-content:hover {
            background: var(--primary-light);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* Row number styling */
        .row-number-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: var(--text-muted);
            color: white;
            border-radius: 50%;
            font-size: 1.5rem;
            font-weight: 700;
            box-shadow: 3px 3px 6px #babecc, -3px -3px 6px #ffffff;
            transition: all 0.2s ease;
        }

        .row-number-badge:hover {
            transform: translateY(-1px);
            box-shadow: 4px 4px 8px #babecc, -4px -4px 8px #ffffff;
        }

        /* Loading spinner animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Status loading animation */
        .ri-loader-4-line {
            display: inline-block;
            animation: spin 1s linear infinite;
        }

        /* Top Bar for authenticated users - Modern Design */
        .top-bar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: var(--shadow-lg);
            position: sticky;
            top: 0;
            z-index: 1000;
            display: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .top-bar.show {
            display: block;
            animation: slideDown 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .top-bar-content {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .top-bar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.01em;
        }

        .top-bar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0.8;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1.25rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1rem;
            box-shadow: var(--shadow-md);
        }

        .user-details {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-role {
            font-size: 0.75rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .top-bar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .top-bar-btn {
            padding: 0.75rem 1.25rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: var(--radius-lg);
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(10px);
        }

        .top-bar-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Debug info styling */
        .debug-info {
            background: var(--surface);
            color: var(--text-primary);
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: var(--radius-xl);
            font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', 'Consolas', monospace;
            font-size: 0.8125rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            display: none;
        }

        .debug-info.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .debug-info pre {
            margin: 0;
            white-space: pre-wrap;
            line-height: 1.5;
        }

                 /* Desktop enhancements */
         @media (min-width: 1024px) {
             .container {
                 padding: 2rem;
             }
             
             .page-header {
                 padding: 2.5rem;
                 margin-bottom: 2.5rem;
             }

             .table-wrapper {
                 margin: 0 -1.5rem;
             }
             
             .page-title {
                 font-size: 2.75rem;
             }

             .stat-value {
                 font-size: 3rem;
             }

             .widget-content {
                 padding: 1rem 1.5rem 1.5rem;
                 min-height: 120px;
             }
             
             .table thead th {
                 font-size: 0.875rem;
                 padding: 1.25rem 1rem;
             }
             
             .table tbody td {
                 padding: 1.25rem 1rem !important;
             }
             
             .badge {
                 font-size: 0.75em;
                 padding: 0.5em 0.75em;
             }
             
             .stock-number {
                 font-size: 0.75em;
                 padding: 0.5em 0.75em;
             }
             
             .vehicle-info {
                 font-size: 0.875rem;
             }

             .stats-container {
                 grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                 gap: 2rem;
             }
         }

                 /* Responsive design - Tablet */
         @media (max-width: 768px) {
             .container {
                 padding: 1rem;
                 margin: 0.5rem;
             }

             .table-wrapper {
                 margin: 0 -0.75rem;
             }

             .page-title {
                 font-size: 2rem;
             }

             .page-header {
                 padding: 1.5rem;
                 margin-bottom: 1.5rem;
             }

             .stats-container {
                 grid-template-columns: repeat(2, 1fr);
                 gap: 1rem;
                 margin-bottom: 1.5rem;
             }
             
             .stat-widget .widget-content {
                 padding: 0.75rem 1rem 1rem;
                 min-height: 80px;
             }
             
             .stat-value {
                 font-size: 2rem;
             }

             .controls {
                 margin-bottom: 1.5rem;
             }
             
             .distribution-bars {
                 gap: 0.75rem;
                 padding: 0;
             }
             
             .bar-container {
                 height: 6px;
             }
             
             /* Table responsive improvements */
             .table-container {
                 padding: 1.5rem;
                 margin: 0 -0.25rem;
             }
             
             .table {
                 font-size: 0.8125rem;
                 min-width: 700px;
             }
             
             .table thead th {
                 padding: 0.875rem 0.5rem;
                 font-size: 0.75rem;
             }
             
             .table tbody td {
                 padding: 0.875rem 0.5rem !important;
             }
             
             .badge {
                 font-size: 0.6875em;
                 padding: 0.375em 0.625em;
             }
             
             .stock-number {
                 font-size: 0.6875em;
                 padding: 0.375em 0.625em;
             }
             
             .vehicle-info {
                 font-size: 0.8125rem;
             }
             
             .date-with-badge {
                 font-size: 0.8125rem;
             }
             
             /* Horizontal scroll indicator */
             .table-wrapper::after {
                 content: "← Scroll to see more →";
                 display: block;
                 text-align: center;
                 padding: 0.75rem;
                 font-size: 0.75rem;
                 color: var(--text-muted);
                 background: var(--light-gray);
                 font-style: italic;
                 font-weight: 500;
             }
         }

                 /* Mobile responsive design */
         @media (max-width: 480px) {
             .table-wrapper {
                 margin: 0 -0.5rem;
             }

             .page-title {
                 font-size: 1.75rem;
             }

             .page-header {
                 padding: 1rem;
                 margin-bottom: 1rem;
             }

             .stats-container {
                 grid-template-columns: 1fr;
                 gap: 1rem;
                 margin-bottom: 1rem;
             }

             .stat-widget .widget-content {
                 padding: 0.75rem 1rem 1rem;
                 min-height: 70px;
             }

             .stat-value {
                 font-size: 1.75rem;
             }
             
             .table-container {
                 padding: 1rem;
                 margin: 0;
             }
             
             .table {
                 font-size: 0.75rem;
                 min-width: 580px;
             }
             
             .table thead th {
                 padding: 0.75rem 0.375rem;
                 font-size: 0.6875rem;
             }
             
             .table tbody td {
                 padding: 0.75rem 0.375rem !important;
             }
             
             .badge {
                 font-size: 0.625em;
                 padding: 0.25em 0.5em;
                 min-width: 24px;
             }
             
             .stock-number {
                 font-size: 0.625em;
                 padding: 0.25em 0.5em;
             }
             
             .vehicle-info {
                 font-size: 0.75rem;
             }

             .date-with-badge {
                 font-size: 0.8125rem;
             }

             .notes-short {
                 max-width: 140px;
                 font-size: 0.8125rem;
             }

             .row-number {
                 font-size: 0.8125rem;
             }

             .top-bar-content {
                 padding: 0 1rem;
                 flex-direction: column;
                 gap: 1rem;
             }

             .top-bar-left {
                 flex-direction: column;
                 gap: 0.5rem;
                 text-align: center;
             }

             .user-info {
                 padding: 0.5rem 1rem;
             }

             .user-avatar {
                 width: 32px;
                 height: 32px;
             }
         }

        /* Enhanced animations and interactions */
        .fade-in {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading states */
        .loading-shimmer {
            background: linear-gradient(90deg, var(--light-gray) 25%, var(--border-color) 50%, var(--light-gray) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        /* Focus management for accessibility */
        .btn:focus-visible,
        .top-bar-btn:focus-visible {
            outline: 2px solid var(--accent-color);
            outline-offset: 2px;
        }

        /* Enhanced table loading state */
        .table-loading {
            position: relative;
            overflow: hidden;
        }

        .table-loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: loading-sweep 1.5s infinite;
        }

        @keyframes loading-sweep {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Smooth transitions for all interactive elements */
        * {
            transition-property: background-color, border-color, color, box-shadow, transform;
            transition-duration: 0.2s;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Print styles */
        @media print {
            .top-bar,
            .controls,
            .btn {
                display: none !important;
            }
            
            .container {
                max-width: none;
                padding: 0;
            }
            
            .table-container {
                box-shadow: none;
                border: 1px solid #000;
            }
        }

        /* Staff-only elements */
        .staff-only {
            display: none !important;
        }

        /* Modern Container Styles */
        .dashboard-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.1);
            border: none;
            overflow: hidden;
        }

        .dashboard-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            padding: 2rem;
        }

        .dashboard-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .dashboard-subtitle {
            color: #6b7280;
            font-size: 1rem;
            font-weight: 500;
            margin: 0;
        }

        .dashboard-body {
            background: #ffffff;
            padding: 2rem;
        }

        /* Enhanced Card Styles */
        .modern-card {
            background: #ffffff;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .modern-card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: none;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .modern-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .modern-card-title i {
            margin-right: 0.75rem;
            font-size: 1.3rem;
        }

        .modern-card-subtitle {
            color: #64748b;
            font-size: 0.875rem;
            margin: 0.25rem 0 0 0;
            font-weight: 500;
        }

        .modern-card-body {
            padding: 2rem;
        }

        /* Staff Container Styles */
        .staff-container {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(245, 158, 11, 0.1);
            border: none;
            overflow: hidden;
        }

        .staff-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            padding: 2rem;
        }

        .staff-title {
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        /* Mini Average Days Widget */
        .avg-days-mini-widget {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            color: #1e293b;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .avg-days-mini-widget:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .avg-days-value {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }

        .avg-number {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
        }

        .avg-label {
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.9;
        }

        /* Table improvements */
        #inventoryTable th {
            vertical-align: middle;
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
            background-color: #f9fafb !important;
        }

        #inventoryTable td {
            vertical-align: middle;
            padding: 0.75rem 0.5rem;
        }

        .form-check {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .form-check-input {
            margin: 0;
        }

        /* Hide Actions column completely */
        #inventoryTable th:nth-child(9),
        #inventoryTable td:nth-child(9) {
            display: none !important;
        }

        /* Styles from vehicles_content.php */
        .stats-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background: #ffffff;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .stats-mini {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .stats-mini:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            border-color: #3b82f6;
            background-color: #f8fafc;
        }

        .stats-mini.active {
            border-color: #3b82f6;
            background-color: #eff6ff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .stats-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-right: 0.75rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .stats-content h6 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .filter-widget .filter-indicator {
            position: absolute;
            top: 8px;
            right: 8px;
            opacity: 0;
            transition: opacity 0.3s ease;
            color: #6b7280;
            font-size: 0.8rem;
        }

        .filter-widget:hover .filter-indicator,
        .filter-widget.active .filter-indicator {
            opacity: 1;
        }

        .filter-widget.active .filter-indicator {
            color: #3b82f6;
        }

        .filter-active {
            background-color: #eff6ff !important;
            border-left: 4px solid #3b82f6 !important;
        }

        .filter-widget:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Average Days Widget - Medium */
        .avg-days-widget-medium {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 120px;
        }

        .avg-days-widget-medium:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
        }

        .widget-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .widget-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #ffffff;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .widget-title {
            flex: 1;
        }

        .widget-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        .widget-range {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
        }

        .progress-section {
            margin-bottom: 12px;
        }

        .progress-bar-container {
            position: relative;
        }

        .progress-bar-track {
            height: 8px;
            background: #f1f5f9;
            border-radius: 4px;
            position: relative;
            overflow: visible;
            margin-bottom: 8px;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: all 0.8s ease;
            width: 0%;
        }

        .progress-indicator {
            position: absolute;
            top: -8px;
            transform: translateX(-50%);
            transition: left 0.8s ease;
            left: 0%;
        }

        .indicator-value {
            background: #1e293b;
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .indicator-arrow {
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid #1e293b;
            margin: 0 auto;
        }

        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.6875rem;
            color: #64748b;
            font-weight: 500;
        }

        .widget-status {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            text-align: center;
        }

        /* Dynamic colors for widgets */
        .widget-icon.excellent,
        .progress-bar-fill.excellent {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .widget-icon.good,
        .progress-bar-fill.good {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .widget-icon.poor,
        .progress-bar-fill.poor {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .widget-icon.neutral,
        .progress-bar-fill.neutral {
            background: linear-gradient(135deg, #6b7280, #4b5563);
        }

        /* Days badge styling */
        .days-badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
            border-radius: 12px;
            font-weight: 600;
            text-transform: lowercase;
        }

        /* Duplicate Stock Alert Styling */
        .duplicate-alert,
        [id^="duplicate-icon-"] {
            animation: pulse-warning 2s infinite;
            filter: drop-shadow(0 0 2px rgba(245, 158, 11, 0.5));
            position: relative;
            z-index: 10;
        }

        .duplicate-alert:hover,
        [id^="duplicate-icon-"]:hover {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }

        @keyframes pulse-warning {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }

        /* Custom tooltip for duplicate alerts */
        .duplicate-alert::after {
            content: attr(title);
            position: absolute;
            bottom: 130%;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            min-width: max-content;
        }

        .duplicate-alert::before {
            content: '';
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.9);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .duplicate-alert:hover::after,
        .duplicate-alert:hover::before {
            opacity: 1;
            visibility: visible;
        }

        .duplicate-alert:hover::after {
            transform: translateX(-50%) translateY(-2px);
        }

        /* Duplicate stock row highlighting */
        .inventory-row.has-duplicate {
            background-color: #fef3cd !important;
            border-left: 3px solid #ffc107 !important;
        }

        .inventory-row.has-duplicate:hover {
            background-color: #fff3cd !important;
        }

        /* Center align table headers */
        #inventoryTable thead th,
        #inventoryOrdersTable thead th,
        #allOrdersTable thead th {
            text-align: center !important;
            vertical-align: middle !important;
        }

        /* Center align specific columns content */
        #inventoryTable tbody td:nth-child(2), /* Date in Detail */
        #inventoryTable tbody td:nth-child(3), /* Day in This Step */
        #inventoryTable tbody td:nth-child(4), /* Keys */
        #inventoryTable tbody td:nth-child(5), /* Stock Number */
        #inventoryTable tbody td:nth-child(8), /* Status */
        #inventoryTable tbody td:nth-child(9)  /* Actions */
        {
            text-align: center !important;
            vertical-align: middle !important;
        }

        /* Hidden loading indicator */
        .dataTables_processing {
            display: none !important;
        }

        /* Alternative: Very discrete loading in table header */
        .dataTables_wrapper .dataTables_processing {
            position: absolute !important;
            top: -2px !important;
            right: 5px !important;
            left: auto !important;
            width: auto !important;
            height: auto !important;
            margin: 0 !important;
            padding: 2px 6px !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            font-size: 0.6rem !important;
            color: rgba(108, 117, 125, 0.5) !important;
            z-index: 1001 !important;
            opacity: 0.3 !important;
        }

        .inventory-row.selected {
            background-color: #eff6ff !important;
        }

        .conversion-badge {
            background: #e0f2fe;
            color: #0277bd;
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
        }

        /* Status column styling */
        .status-service-info,
        [id^="status-info-"] {
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Stock column styling */
        [id^="stock-"] {
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Enhanced stock number styling - text only */
        .stock-number-enhanced {
            font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', 'Consolas', monospace;
            color: var(--primary-color);
            font-size: 1.25em;
            font-weight: 900;
            letter-spacing: 1.5px;
            line-height: 1;
            display: inline-block;
            text-transform: uppercase;
            text-shadow: 0 1px 2px rgba(37, 99, 235, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stock-number-enhanced:hover {
            color: var(--primary-hover);
            text-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
            transform: scale(1.05);
        }

        /* Enhanced date styling */
        .date-enhanced {
            font-size: 1.1em;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: 0.5px;
        }

        /* Status-based row background colors */
        .status-pending {
            background-color: rgba(245, 158, 11, 0.08) !important;
            border-left: 4px solid #f59e0b !important;
        }

        .status-pending:hover {
            background-color: rgba(245, 158, 11, 0.12) !important;
        }

        .status-in-progress {
            background-color: rgba(59, 130, 246, 0.08) !important;
            border-left: 4px solid #3b82f6 !important;
        }

        .status-in-progress:hover {
            background-color: rgba(59, 130, 246, 0.12) !important;
        }

        .status-completed {
            background-color: rgba(16, 185, 129, 0.08) !important;
            border-left: 4px solid #10b981 !important;
        }

        .status-completed:hover {
            background-color: rgba(16, 185, 129, 0.12) !important;
        }

        .status-cancelled {
            background-color: rgba(239, 68, 68, 0.08) !important;
            border-left: 4px solid #ef4444 !important;
        }

        .status-cancelled:hover {
            background-color: rgba(239, 68, 68, 0.12) !important;
        }

        .status-no-status {
            background-color: rgba(107, 114, 128, 0.05) !important;
            border-left: 4px solid #6b7280 !important;
        }

        .status-no-status:hover {
            background-color: rgba(107, 114, 128, 0.08) !important;
        }

        /* Ensure status rows have smooth transitions */
        #inventoryTable tbody tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Mobile responsive table - Horizontal scroll with minimal padding */
        @media (max-width: 768px) {
            /* Reduce container padding to minimum */
            .container {
                padding: 0.25rem !important;
            }
            
            /* Reduce card padding */
            .modern-card {
                margin: 0.25rem;
                border-radius: 8px;
            }
            
            .modern-card-body {
                padding: 0.5rem !important;
            }
            
            /* Table wrapper for horizontal scroll */
            .table-responsive {
                margin: 0 -0.5rem;
                padding: 0;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                border-radius: 8px;
            }
            
            /* Enable horizontal scroll for table */
            #inventoryTable {
                min-width: 800px; /* Ensure table is wide enough to scroll */
                font-size: 0.75rem;
                margin: 0;
            }
            
            /* Keep table structure intact for horizontal scroll */
            #inventoryTable thead th {
                padding: 0.5rem 0.25rem !important;
                font-size: 0.7rem;
                white-space: nowrap;
                min-width: 80px;
            }
            
            #inventoryTable tbody td {
                padding: 0.5rem 0.25rem !important;
                font-size: 0.75rem;
                white-space: nowrap;
                vertical-align: middle;
            }
            
            /* Stock number column wider */
            #inventoryTable th:nth-child(5),
            #inventoryTable td:nth-child(5) {
                min-width: 100px;
            }
            
            /* Vehicle column wider */
            #inventoryTable th:nth-child(6),
            #inventoryTable td:nth-child(6) {
                min-width: 120px;
                white-space: normal;
                line-height: 1.2;
            }
            
            /* Notes column */
            #inventoryTable th:nth-child(7),
            #inventoryTable td:nth-child(7) {
                min-width: 100px;
                max-width: 150px;
                white-space: normal;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            /* Status column */
            #inventoryTable th:nth-child(8),
            #inventoryTable td:nth-child(8) {
                min-width: 100px;
            }
            
            /* Adjust badges for mobile */
            #inventoryTable .badge {
                font-size: 0.6rem;
                padding: 0.25rem 0.5rem;
            }
            
            /* Stock number styling for mobile */
            .stock-number-enhanced {
                font-size: 1rem !important;
            }
            
            /* Date styling for mobile */
            .date-enhanced {
                font-size: 0.9rem !important;
            }
            
            /* Days badge styling */
            .days-badge {
                font-size: 0.6rem;
                padding: 0.2rem 0.4rem;
            }
            
            /* Horizontal scroll indicator */
            .table-responsive::after {
                content: "← Desliza para ver más →";
                display: block;
                text-align: center;
                padding: 0.5rem;
                font-size: 0.7rem;
                color: #6b7280;
                background: #f8fafc;
                font-style: italic;
                border-top: 1px solid #e5e7eb;
            }
            
            /* Hide scroll indicator when not needed */
            .table-responsive:not(.scrollable)::after {
                display: none;
            }
        }
        
        /* Tablet responsive adjustments */
        @media (min-width: 769px) and (max-width: 1024px) {
            #inventoryTable {
                font-size: 0.85rem;
            }
            
            #inventoryTable th,
            #inventoryTable td {
                padding: 0.75rem 0.5rem !important;
            }
            
            /* Adjust badge sizes for tablet */
            #inventoryTable .badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.6rem;
            }
        }

        /* Filter button styles */
        #hideCompletedBtn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #hideCompletedBtn.btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        #hideCompletedBtn.btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        /* Mobile adjustments for filter controls */
        @media (max-width: 768px) {
            .modern-card-header .d-flex.gap-2 {
                flex-direction: column;
                gap: 0.5rem !important;
            }
            
            .modern-card-header .btn {
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
            }
            
            /* Stack buttons vertically on mobile */
            .modern-card-header .col-auto {
                margin-top: 1rem;
            }
        }

        /* Improved mobile card styling */
        @media (max-width: 768px) {
            #inventoryTable tbody tr {
                margin-bottom: 1.5rem;
                padding: 1.25rem;
                border-radius: 16px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }
            
            /* Enhanced mobile labels */
            #inventoryTable tbody td::before {
                font-weight: 700;
                color: #475569;
                font-size: 0.75rem;
                width: 38%;
                padding-right: 0.75rem;
            }
            
            /* Mobile status styling */
            #inventoryTable tbody td:nth-child(8) {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }
            
            /* Mobile stock number enhancement */
            #inventoryTable tbody td:nth-child(5) .stock-number-enhanced {
                font-size: 1.1em;
                font-weight: 800;
            }
        }
    </style>
</head>

<body>
    <!-- Top Bar for authenticated users -->
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
                <div class="user-info">
                    <div class="user-avatar" id="userAvatar">U</div>
                    <div class="user-details">
                        <div class="user-name" id="userName">User</div>
                        <div class="user-role" id="userRole">Staff</div>
                    </div>
                </div>
                <div class="top-bar-actions">
                    <a href="../../" class="top-bar-btn">
                        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                        Back to App
                    </a>
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

    <div class="container">
        <div class="dashboard-container mb-4">
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="dashboard-title">
                                <i class="ri-dashboard-3-line me-2"></i>
                                Inventory Management Dashboard
                                <span id="syncIndicator" class="badge bg-success ms-2" style="font-size: 0.75rem; font-weight: 500;">
                                    <i class="ri-wifi-line me-1"></i>Live Sync
                                </span>
                            </h4>
                            <p class="dashboard-subtitle">
                                 • Auto-refresh every 30s
                                <br>
                                <span id="lastRefreshInfo" style="font-size: 0.7rem; opacity: 0.7;">
                                    Last refresh: Loading...
                                </span>
                            </p>
        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-body">
            

    <!-- Filter Widgets Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modern-card-title">
                                <i class="ri-bar-chart-line"></i>
                                <?= lang('App.inventory_stats') ?>
                            </h5>
                            <p class="modern-card-subtitle"><?= lang('App.click_to_filter') ?></p>
                </div>
                        <div class="avg-days-mini-widget">
                            <div class="avg-days-value" id="avgDaysCompact">
                                <span class="avg-number" id="avgDaysNumber">0</span>
                                <span class="avg-label">Avg Days</span>
                    </div>
                </div>
            </div>
                </div>
                <div class="modern-card-body">
                    <!-- Inventory Stats - Interactive Filter Widgets -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="stats-mini filter-widget" data-filter="" role="button" tabindex="0">
                                <div class="stats-icon bg-primary">
                                    <i class="ri-car-line"></i>
                    </div>
                                <div class="stats-content">
                                    <h6 class="mb-0" id="totalInventoryItems">0</h6>
                                    <small class="text-muted"><?= lang('App.total_stock_items') ?></small>
                </div>
                                <div class="filter-indicator">
                                    <i class="ri-eye-line"></i>
            </div>
                </div>
                            </div>
                        <div class="col-md-3">
                            <div class="stats-mini filter-widget" data-filter="0-1" role="button" tabindex="0">
                                <div class="stats-icon bg-success" id="recentItemsIcon">
                                    <i class="ri-calendar-check-line"></i>
                            </div>
                                <div class="stats-content">
                                    <h6 class="mb-0" id="recentItems">0</h6>
                                    <small class="text-muted"><?= lang('App.recent_items') ?></small>
                        </div>
                                <div class="filter-indicator">
                                    <i class="ri-filter-line"></i>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-mini filter-widget" data-filter="2-5" role="button" tabindex="0">
                                <div class="stats-icon bg-warning" id="moderateItemsIcon">
                                    <i class="ri-calendar-line"></i>
                            </div>
                                <div class="stats-content">
                                    <h6 class="mb-0" id="moderateItems">0</h6>
                                    <small class="text-muted"><?= lang('App.moderate_items') ?></small>
                            </div>
                                <div class="filter-indicator">
                                    <i class="ri-filter-line"></i>
                        </div>
                    </div>
                </div>
                        <div class="col-md-3">
                            <div class="stats-mini filter-widget" data-filter="6+" role="button" tabindex="0">
                                <div class="stats-icon bg-danger" id="agedItemsIcon">
                                    <i class="ri-calendar-close-line"></i>
            </div>
                                <div class="stats-content">
                                    <h6 class="mb-0" id="agedItems">0</h6>
                                    <small class="text-muted"><?= lang('App.aged_items') ?></small>
                </div>
                                <div class="filter-indicator">
                                    <i class="ri-filter-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>


    <!-- Inventory Table Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="modern-card-title">
                                <i class="ri-table-line"></i>
                                <?= lang('App.inventory_table') ?>
                            </h5>
                            <p class="modern-card-subtitle"><?= lang('App.detailed_inventory_view') ?></p>
                </div>
                <div class="col-auto">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="hideCompletedBtn">
                            <i class="ri-eye-off-line me-1"></i>
                            Hide Completed
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="refreshInventoryBtn">
                            <i class="ri-refresh-line me-1"></i>
                            <?= lang('App.refresh_inventory') ?>
                        </button>
                    </div>
        </div>

            </div>
                            </div>
                <div class="modern-card-body">
                    <div class="table-responsive">
                        <table id="inventoryTable" class="table table-hover align-middle" style="width:100%">
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
                                    <th class="text-center" style="display: none !important;"><?= lang('App.actions') ?></th>
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
                    </div>

    <!-- Staff Management Container - Staff Only -->
    <div class="staff-container mb-4 <?php echo $staffOnlyClass; ?>" <?php echo $staffOnlyStyle; ?>>
        <div class="staff-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="staff-title">
                        <i class="ri-admin-line me-2"></i>
                        Staff Management Tools
                    </h4>
                    <p class="dashboard-subtitle">Advanced tools for staff members only</p>
    </div>
            </div>
        </div>
        <div class="dashboard-body">
            
            <!-- Orders from Inventory Section - Staff Only -->
    <div class="row mb-4 <?php echo $staffOnlyClass; ?>" <?php echo $staffOnlyStyle; ?>>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">
                                <i class="ri-file-list-3-line me-2"></i>
                                <?= lang('App.orders_from_inventory') ?>
                            </h5>
                            <p class="text-muted small mb-0"><?= lang('App.created_from_inventory') ?></p>
                </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-primary" id="refreshInventoryOrdersBtn">
                                <i class="ri-refresh-line me-1"></i>
                                <?= lang('App.refresh') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="inventoryOrdersTable" class="table table-hover align-middle" style="width:100%">
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
        </div>

    <!-- All Orders Section - Staff Only -->
    <div class="row mb-4 <?php echo $staffOnlyClass; ?>" <?php echo $staffOnlyStyle; ?>>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">
                                <i class="ri-list-check-3 me-2"></i>
                                All Orders
                            </h5>
                            <p class="text-muted small mb-0">All orders with source indicators</p>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-primary" id="refreshAllOrdersBtn">
                                <i class="ri-refresh-line me-1"></i>
                Refresh
            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="allOrdersTable" class="table table-hover align-middle" style="width:100%">
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
    </div>

    </div>

   <script>
// Pass authentication status to JavaScript
window.isAuthenticated = <?php echo $isAuthenticated ? 'true' : 'false'; ?>;
window.userType = '<?php echo $userType; ?>';
</script>

    <?php include 'partials/vendor-scripts.php'; ?>
    


   <!-- Include the vehicles inventory JavaScript -->
   <script src="js/vehicles-inventory.js"></script>
   
   <script>
   // Mobile responsive and completed filter functionality
   document.addEventListener('DOMContentLoaded', function() {
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
           
           // Debug function to test filter manually
           // Make filter functions globally accessible
           window.applyCompletedFilter = function() {
               if (hideCompleted) {
                   console.log('🔄 Reapplying completed filter after status update');
                   waitForStatusLoaded(() => {
                       filterCompletedItems();
                   });
               }
           };
           
           window.testCompletedFilter = function() {
               console.log('🧪 Testing completed filter manually');
               console.log('Current hideCompleted state:', hideCompleted);
               console.log('DataTables search functions count:', $.fn.dataTable.ext.search.length);
               
               if (window.inventoryTable) {
                   const info = window.inventoryTable.page.info();
                   console.log('Table info:', info);
                   
                   // Check DOM status elements
                   const statusElements = document.querySelectorAll('.status-service-info');
                   console.log(`Found ${statusElements.length} status elements:`);
                   
                   statusElements.forEach((element, index) => {
                       if (index < 5) { // Show first 5
                           console.log(`Status ${index}:`, element.textContent.trim());
                       }
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
           
           // Mobile responsive enhancements - Horizontal scroll detection
           function setupMobileScrollIndicator() {
               const tableWrapper = document.querySelector('.table-responsive');
               const table = document.getElementById('inventoryTable');
               
               if (tableWrapper && table && window.innerWidth <= 768) {
                   // Check if horizontal scroll is needed
                   const isScrollable = table.scrollWidth > tableWrapper.clientWidth;
                   
                   if (isScrollable) {
                       tableWrapper.classList.add('scrollable');
                   } else {
                       tableWrapper.classList.remove('scrollable');
                   }
               }
           }
           
           // Setup mobile enhancements
           setTimeout(setupMobileScrollIndicator, 1000);
           
           // Re-run on window resize
           let resizeTimeout;
           window.addEventListener('resize', function() {
               clearTimeout(resizeTimeout);
               resizeTimeout = setTimeout(setupMobileScrollIndicator, 250);
           });
       }
   });
   </script>
   
   <script>
   // Legacy authentication check for backward compatibility
   document.addEventListener('DOMContentLoaded', async function() {
       try {
           // Check authentication via existing auth system
           const response = await fetch('./check_auth.php', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    cache: 'no-cache'
                });
            
            if (response.ok) {
                const result = await response.json();
               window.isAuthenticated = result.authenticated || false;
               window.userInfo = result.user || null;
                
                console.log('🔐 Authentication result:', {
                   authenticated: window.isAuthenticated,
                   method: result.method || 'Unknown'
               });
               
               // Mark auth check as completed
               window.authCheckCompleted = true;
                
                // Update UI based on authentication
               updateAuthenticationUI();
               
               // Update top bar if authenticated
               const topBar = document.getElementById('topBar');
               if (topBar && window.isAuthenticated) {
                   topBar.classList.add('show');
                   
                   // Update user info if available
                   if (window.userInfo) {
                       const userName = document.getElementById('userName');
                       const userRole = document.getElementById('userRole');
                       const userAvatar = document.getElementById('userAvatar');
                       
                       if (userName && window.userInfo.username) {
                           userName.textContent = window.userInfo.username;
                       }
                       
                       if (userRole && window.userInfo.groups && window.userInfo.groups.length > 0) {
                           userRole.textContent = window.userInfo.groups[0];
                       }
                       
                       if (userAvatar && window.userInfo.username) {
                           userAvatar.textContent = window.userInfo.username.charAt(0).toUpperCase();
                       }
            }
        }
        
        // Show debug toggle if in debug mode
               const debugMode = new URLSearchParams(window.location.search).get('debug') === 'true';
        const debugToggle = document.getElementById('debugToggle');
               if (debugToggle && debugMode) {
            debugToggle.style.display = 'flex';
            debugToggle.addEventListener('click', () => {
                const debugInfo = document.getElementById('debugInfo');
                if (debugInfo) {
                    debugInfo.classList.toggle('show');
                           
                           // Show debug info
        const debugContent = document.getElementById('debugContent');
        if (debugContent) {
            const debugData = {
                timestamp: new Date().toISOString(),
                                   authenticated: window.isAuthenticated,
                                   user_info: window.userInfo,
                                   auth_response: result,
                current_url: window.location.href,
                user_agent: navigator.userAgent
            };
            
            debugContent.textContent = JSON.stringify(debugData, null, 2);
                }
            }
        });
               }
        } else {
               console.warn('❌ Authentication check failed:', response.status, response.statusText);
               window.isAuthenticated = false;
               window.authCheckCompleted = true;
               updateAuthenticationUI();
           }
    } catch (error) {
           console.error('❌ Network error checking authentication:', error);
           window.isAuthenticated = false;
           window.authCheckCompleted = true;
           updateAuthenticationUI();
    }
});
   </script>

</body>
</html>