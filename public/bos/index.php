<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>BOS Inventory Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="BOS Inventory Management System" name="description" />
    <meta content="BOS" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons CSS -->
    <link href="../../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App CSS -->
    <link href="../../assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- DataTables CSS -->
    <link href="../../assets/libs/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/libs/datatables/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />

    <style>
        :root {
            --primary-color:rgb(33, 59, 192);
            --primary-color-rgb: 64, 81, 137;
            --primary-hover: #364574;
            --secondary-color: #74788d;
            --light-gray: #f8f9fa;
            --border-color: #e3ebf0;
            --text-primary: #495057;
            --text-secondary: #74788d;
            --text-muted: #adb5bd;
            --success-color: #0ab39c;
            --warning-color: #f7b84b;
            --danger-color: #f06548;
            --white: #ffffff;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f0f0f0;
            color: var(--text-primary);
            line-height: 1.6;
            font-size: 15px;
        }

                 .container {
             max-width: 1400px;
             margin: 0 auto;
             padding: 0.75rem;
             
             
         }

                 .page-header {
             text-align: center;
             margin-bottom: 0.75rem;
             padding: 0.75rem;
             background: var(--white);
             border-radius: var(--radius-md);
             box-shadow: 10px 10px 20px #babecc, -10px -10px 20px #ffffff;
         }

         .page-title {
             font-size: 1.5rem;
             font-weight: 700;
             color: var(--primary-color);
             margin: 0;
             text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
             line-height: 1.2;
         }

         .page-subtitle {
             font-size: 0.8rem;
             color: var(--text-secondary);
             margin-top: 0.15rem;
             font-weight: 500;
             line-height: 1.1;
         }

                 /* Stats Container - Single Row Layout */
         .stats-container {
             display: grid;
             grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
             gap: 0.75rem;
             margin-bottom: 0.75rem;
         }

        .stat-widget {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: 10px 10px 20px #babecc, -10px -10px 20px #ffffff;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .stat-widget:hover {
            box-shadow: 12px 12px 24px #babecc, -12px -12px 24px #ffffff;
            transform: translateY(-2px);
        }

                 .widget-header {
             display: flex;
             align-items: center;
             justify-content: center;
             gap: 0.3rem;
             padding: 0.6rem 1rem;
             background: var(--secondary-color)!important;
             color: white;
             font-weight: 600;
             font-size: 0.75rem;
         }

         .widget-icon {
             width: 14px;
             height: 14px;
         }

         .widget-title {
             font-size: 0.75rem;
             font-weight: 600;
         }

         .widget-content {
             padding: 0.5rem 0.5rem;
             text-align: center;
             display: flex;
             flex-direction: column;
             justify-content: center;
             align-items: center;
             min-height: 60px;
         }

                 .stat-value {
             font-size: 1.5rem;
             font-weight: 700;
             color: var(--primary-color);
             line-height: 0.9;
             margin: 0;
         }

         .stat-unit {
             font-size: 0.75rem;
             color: var(--text-muted);
             font-weight: 500;
         }

         .stat-subtitle {
             font-size: 0.65rem;
             color: var(--text-muted);
             text-align: center;
             margin: 0.1rem 0;
             line-height: 1.1;
         }

                 .stat-trend {
             display: flex;
             align-items: center;
             justify-content: center;
             gap: 0.2rem;
             padding: 0.15rem 0.4rem;
             background: rgba(var(--primary-color-rgb), 0.1);
             border-radius: var(--radius-sm);
             font-size: 0.6rem;
             font-weight: 500;
             color: var(--primary-color);
             margin-top: 0.15rem;
         }

         .trend-icon {
             width: 10px;
             height: 10px;
         }

        .stat-status {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: var(--radius-md);
            background: var(--light-gray);
            margin-top: 0.5rem;
        }

                 /* Chart Widget - Horizontal Bars */
         .chart-widget .widget-content {
             padding: 0.5rem 0.5rem;
             justify-content: center;
             min-height: 60px;
         }

                 .distribution-bars {
             display: flex;
             flex-direction: column;
             gap: 0.4rem;
             margin-top: 0.2rem;
             width: 100%;
             padding: 0.5rem 0.5rem;
         }

         .bar-item {
             display: flex;
             flex-direction: column;
             gap: 0.2rem;
         }

        .bar-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

                 .bar-label {
             font-size: 0.65rem;
             font-weight: 500;
             color: var(--text-secondary);
         }

         .bar-value {
             font-size: 0.65rem;
             font-weight: 600;
             color: var(--text-primary);
         }

         .bar-container {
             height: 6px;
             background: var(--light-gray);
             border-radius: 3px;
             overflow: hidden;
         }

        .bar {
            height: 100%;
            border-radius: 4px;
            transition: width 0.8s ease;
        }

        .bar-excellent {
            background: #10b981;
        }

        .bar-good {
            background: #f59e0b;
        }

        .bar-warning {
            background: #ef4444;
        }



        .stat-status.excellent {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .stat-status.good {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .stat-status.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .stat-status.critical {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .stat-value.updating {
            animation: pulse 0.6s ease-in-out;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

                 .controls {
             display: flex;
             justify-content: space-between;
             align-items: center;
             margin-bottom: 0.75rem;
             gap: 0.5rem;
             flex-wrap: wrap;
         }

                 .btn {
             padding: 0.4rem 0.8rem;
             border-radius: var(--radius-sm);
             font-weight: 600;
             font-size: 0.75rem;
             border: none;
             cursor: pointer;
             transition: all 0.2s ease;
             display: inline-flex;
             align-items: center;
             gap: 0.3rem;
             position: relative;
             overflow: hidden;
         }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--secundary-color);
            color: white;
            box-shadow: 5px 5px 10px #babecc, -5px -5px 10px #ffffff;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 7px 7px 14px #babecc, -7px -7px 14px #ffffff;
        }

        .btn-outline-secondary {
            background: transparent;
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
        }

        .btn-outline-secondary:hover {
            background: var(--light-gray);
            border-color: var(--primary-color);
            color: var(--text-primary);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
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
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1rem;
            display: none;
        }

        .error.show {
            display: block;
        }

                 .table-container {
             background: var(--white);
             border-radius: var(--radius-lg);
             box-shadow: 10px 10px 20px #babecc, -10px -10px 20px #ffffff;
             
             overflow: hidden;
             padding: 0.75rem;
         }

         .table-header {
             text-align: center;
             margin-bottom: 0.75rem;
             padding-bottom: 0.5rem;
             border-bottom: 2px solid var(--border-color);
         }

         .table-title {
             font-size: 1.1rem;
             font-weight: 700;
             color: var(--primary-color);
             margin: 0;
             display: flex;
             align-items: center;
             justify-content: center;
             gap: 0.4rem;
             line-height: 1.2;
         }

         .table-icon {
             width: 1.1rem;
             height: 1.1rem;
             color: var(--primary-color);
         }

         .table-subtitle {
             font-size: 0.7rem;
             color: var(--text-secondary);
             margin: 0.15rem 0 0 0;
             font-weight: 500;
             line-height: 1.1;
         }

        .table-wrapper {
            margin: -0.5rem 1.5rem;
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 0px solid var(--border-color);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            position: relative;
        }

        /* Custom scrollbar for webkit browsers */
        .table-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .table-wrapper::-webkit-scrollbar-track {
           /* background: #f1f1f1;*/
            border-radius: 4px;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: var(--primary-hover);
        }

         /* Custom table header styles - CarWash style */
         .table thead th {
             background-color: #f8f9fa;
             border-bottom: 2px solid #dee2e6;
             font-weight: 600;
             font-size: 0.75rem;
             text-align: center !important;
             vertical-align: middle !important;
             color: var(--text-primary);
             padding: 0.75rem 0.5rem;
             line-height: 1.2;
         }

         /* Custom table body styles - CarWash style */
         .table tbody td {
             text-align: center !important;
             vertical-align: middle !important;
             padding: 0.75rem 0.5rem !important;
             line-height: 1.4;
             transition: background-color 0.2s ease;
         }

         /* Left align only the first column (row number) for better readability */
         .table thead th:first-child,
         .table tbody td:first-child {
             text-align: left !important;
         }

         /* Table row hover effects - CarWash style */
         .table tbody tr {
             transition: all 0.2s ease;
         }

         .table tbody tr:hover {
             background-color: rgba(64, 81, 137, 0.08) !important;
             transform: translateY(-1px);
             box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
         }

         /* Remove row number badge styling */
         .row-number {
             font-weight: 600;
             color: var(--text-primary);
             font-size: 0.65rem;
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

         /* COMMENTED OUT - Custom table styles to see Bootstrap classes */
         /*
         .table {
             margin: 0;
             font-size: 0.95rem;
             border-collapse: separate;
             border-spacing: 0;
             width: 100%;
             min-width: 800px;
             line-height: 1.5;
         }



         .table thead th:first-child {
             border-top-left-radius: var(--radius-md);
         }

         .table thead th:last-child {
             border-top-right-radius: var(--radius-md);
         }

         .table tbody tr {
             transition: all 0.2s ease;
             border-bottom: 1px solid #f1f3f6;
         }

         .table tbody tr:hover {
             background-color: rgba(64, 81, 137, 0.03);
             transform: scale(1.002);
             box-shadow: 5px 5px 15px #babecc, -5px -5px 15px #ffffff;
             border-radius: 8px;
         }

         .table tbody td {
             padding: 1.25rem 1.25rem;
             vertical-align: middle;
             border: none;
             color: var(--text-primary);
             font-weight: 500;
             transition: all 0.2s ease;
         }

         .table tbody tr:last-child td:first-child {
             border-bottom-left-radius: var(--radius-md);
         }

         .table tbody tr:last-child td:last-child {
             border-bottom-right-radius: var(--radius-md);
         }
         */

                 /* Days badge styling - CarWash style */
         .badge {
             font-size: 0.75em;
             font-weight: 600;
             padding: 0.35em 0.65em;
             border-radius: 0.375rem;
             text-align: center;
             display: inline-block;
             line-height: 1;
             transition: all 0.15s ease-in-out;
         }

        .badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Override Bootstrap badge colors for better contrast and consistency */
        .badge.bg-success {
            background-color: var(--success-color) !important;
            color: white;
        }

        .badge.bg-warning {
            background-color: var(--warning-color) !important;
            color: white;
        }

        .badge.bg-danger {
            background-color: var(--danger-color) !important;
            color: white;
        }

                 /* Stock number styling */
         .stock-number {
             font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
             background: var(--primary-color);
             color: white;
             padding: 0.35em 0.65em;
             border-radius: 0.375rem;
             font-size: 0.75em;
             font-weight: 600;
             letter-spacing: 0.5px;
             transition: all 0.15s ease-in-out;
             line-height: 1;
             display: inline-block;
         }

        .stock-number:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

                 /* Vehicle styling */
         .vehicle-info {
             font-weight: 500;
             color: var(--text-primary);
             font-size: 1.1rem;
             line-height: 1;
         }

        /* Notes preview styling */
        .notes-preview {
            color: var(--text-secondary);
            font-size: 0.85rem;
            line-height: 1.4;
            cursor: default;
            font-style: italic;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            background: rgba(116, 120, 141, 0.05);
        }

        .notes-preview.has-content {
            cursor: help;
            border: 1px solid rgba(116, 120, 141, 0.2);
            background: rgba(116, 120, 141, 0.08);
            transition: all 0.2s ease;
        }

        .notes-preview.has-content:hover {
            background: rgba(116, 120, 141, 0.12);
            border-color: rgba(116, 120, 141, 0.3);
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

        /* Top Bar for authenticated users */
        .top-bar {
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 0;
            box-shadow: 5px 5px 10px #babecc, -5px -5px 10px #ffffff;
            position: sticky;
            top: 0;
            z-index: 1000;
            display: none;
            transition: all 0.3s ease;
        }

        .top-bar.show {
            display: block;
            animation: slideDown 0.5s ease;
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .top-bar-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .top-bar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-sm);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 0.9rem;
            box-shadow: 2px 2px 4px #babecc, -2px -2px 4px #ffffff;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .top-bar-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .top-bar-btn {
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .top-bar-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* Debug info styling */
        .debug-info {
            background: var(--white);
            color: var(--text-primary);
            padding: 1rem;
            margin: 1rem 0;
            border-radius: var(--radius-sm);
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.8rem;
            border: 1px solid var(--border-color);
            box-shadow: 5px 5px 10px #babecc, -5px -5px 10px #ffffff;
            display: none;
        }

        .debug-info.show {
            display: block;
        }

        .debug-info pre {
            margin: 0;
            white-space: pre-wrap;
        }

                 /* Desktop enhancements */
         @media (min-width: 1024px) {
             .container {
                 padding: 1rem;
             }
             
             .page-header {
                 padding: 1rem;
             }

             .table-wrapper {
                 margin: 2rem 5rem;
             }
             
             .page-title {
                 font-size: 1.75rem;
             }

             .stat-value {
                 font-size: 1.75rem;
             }

             .widget-content {
                 padding: 0.75rem;
                 min-height: 70px;
             }
             
             .table thead th {
                 font-size: 0.75rem;
                 padding: 0.5rem 0.375rem;
             }
             
             .table tbody td {
                 padding: 0.5rem 0.375rem !important;
             }
             
             .badge {
                 font-size: 0.7em;
                 padding: 0.3em 0.6em;
             }
             
             .stock-number {
                 font-size: 0.7em;
                 padding: 0.3em 0.6em;
             }
             
             .vehicle-info {
                 font-size: 0.8rem;
             }
         }

                 /* Responsive design */
         @media (max-width: 768px) {
             .container {
                 padding: 0.75rem;
                 margin: 0.5rem;
             }

             .table-wrapper {
                 margin: -0.5rem 0.5rem;
             }

             .page-title {
                 font-size: 1.5rem;
             }

             .page-header {
                 padding: 0.75rem;
                 margin-bottom: 0.75rem;
             }

             .stats-container {
                 grid-template-columns: repeat(2, 1fr);
                 gap: 0.75rem;
                 margin-bottom: 0.75rem;
             }
             
             .stat-widget .widget-content {
                 padding: 0.4rem 0.4rem;
                 min-height: 50px;
             }
             
             .stat-value {
                 font-size: 1.5rem;
             }

             .controls {
                 margin-bottom: 0.75rem;
             }
             
             .distribution-bars {
                 gap: 0.4rem;
                 padding: 0.9rem 0.9rem;
             }
             
             .bar-container {
                 height: 5px;
             }
             
             /* Table responsive improvements */
             .table-container {
                 padding: 0.75rem;
                 margin: 0 -0.25rem;
             }
             
             .table {
                 font-size: 0.75rem;
                 min-width: 600px;
             }
             
             .table thead th {
                 padding: 0.4rem 0.3rem;
                 font-size: 0.7rem;
             }
             
             .table tbody td {
                 padding: 0.4rem 0.3rem !important;
             }
             
             .badge {
                 font-size: 0.65em;
                 padding: 0.25em 0.5em;
             }
             
             .stock-number {
                 font-size: 0.65em;
                 padding: 0.25em 0.5em;
             }
             
             .vehicle-info {
                 font-size: 0.75rem;
             }
             
             .date-with-badge {
                 font-size: 0.7rem;
             }
             
             /* Horizontal scroll indicator */
             .table-wrapper::after {
                 content: "← Desliza para ver más →";
                 display: block;
                 text-align: center;
                 padding: 0.4rem;
                 font-size: 0.65rem;
                 color: var(--text-muted);
                 background: rgba(116, 120, 141, 0.05);
                 font-style: italic;
             }
         }

                 @media (max-width: 480px) {
             .table-wrapper {
                 margin: -0.5rem 0.25rem;
             }

             .page-title {
                 font-size: 1.25rem;
             }

             .page-header {
                 padding: 0.5rem;
                 margin-bottom: 0.5rem;
             }

             .stats-container {
                 grid-template-columns: 1fr;
                 gap: 0.5rem;
                 margin-bottom: 0.5rem;
             }

             .stat-widget .widget-content {
                 padding: 0.4rem;
                 min-height: 45px;
             }

             .stat-value {
                 font-size: 1.25rem;
             }
             
             .table-container {
                 padding: 0.5rem;
                 margin: 0;
             }
             
             .table {
                 font-size: 0.7rem;
                 min-width: 550px;
             }
             
             .table thead th {
                 padding: 0.4rem 0.25rem;
                 font-size: 0.55rem;
             }
             
             .table tbody td {
                 padding: 0.2rem 0.2rem !important;
             }
             
             .badge {
                 font-size: 0.55rem;
                 padding: 0.15rem 0.3rem;
                 min-width: 20px;
             }
             
             .stock-number {
                 font-size: 0.55rem;
                 padding: 0.15rem 0.3rem;
             }
             
             .vehicle-info {
                 font-size: 0.6rem;
             }

             .date-with-badge {
                 font-size: 0.75rem;
             }

             .notes-short {
                 max-width: 120px;
                 font-size: 0.75rem;
             }

             .row-number {
                 font-size: 0.75rem;
             }

             .badge {
                 font-size: 0.6em;
                 padding: 0.2em 0.4em;
             }
             
             .stock-number {
                 font-size: 0.6em;
                 padding: 0.2em 0.4em;
             }
             
             .vehicle-info {
                 font-size: 0.7rem;
             }

             .table {
                 font-size: 0.75rem;
                 min-width: 600px;
             }
             
             .table thead th {
                 padding: 0.35rem 0.25rem;
                 font-size: 0.65rem;
             }
             
             .table tbody td {
                 padding: 0.35rem 0.25rem !important;
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

    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i data-feather="truck" style="width: 2rem; height: 2rem;"></i>
                BOS Inventory Management
            </h1>
            <p class="page-subtitle">Real-time vehicle tracking system</p>
        </div>

        <!-- Error Container -->
        <div id="errorContainer" class="error"></div>

        <!-- Stats & Analytics Widgets Row -->
        <div class="stats-container">
            <!-- Total Records Widget -->
            <div class="stat-widget">
                <div class="widget-header">
                    <i data-feather="database" class="widget-icon"></i>
                    <span class="widget-title">Total Vehicles</span>
                </div>
                <div class="widget-content">
                    <div class="stat-value" id="totalRecords">0</div>
                    <div class="stat-subtitle">vehicles in detail</div>
                    <div class="stat-trend" id="recordsTrend">
                        <i data-feather="trending-up" class="trend-icon"></i>
                        <span class="trend-text">Active tracking</span>
                    </div>
                </div>
            </div>

            <!-- Average Days Widget (Only for authenticated users) -->
            <div id="avgDaysWidget" class="stat-widget" style="display: none;">
                <div class="widget-header">
                    <i data-feather="clock" class="widget-icon"></i>
                    <span class="widget-title">Average Days in Detail</span>
                </div>
                <div class="widget-content">
                    <div class="stat-value" id="avgDaysValue">0.0</div>
                    <div class="stat-subtitle">days average</div>
                    <div class="stat-trend" id="avgDaysStatus">
                        <i data-feather="activity" class="trend-icon"></i>
                        <span class="trend-text">Calculating...</span>
                    </div>
                </div>
            </div>

            <!-- Days Distribution Chart Widget (Only for authenticated users) -->
            <div id="daysDistributionWidget" class="stat-widget chart-widget" style="display: none;">
                <div class="widget-header">
                    <i data-feather="bar-chart-2" class="widget-icon"></i>
                    <span class="widget-title">Days Distribution</span>
                </div>
                <div class="widget-content">
                    <div class="stat-subtitle">vehicle breakdown</div>
                    <div class="distribution-bars" id="distributionBars">
                        <div class="bar-item">
                            <div class="bar-info">
                                <span class="bar-label">0-1 Days</span>
                                <span class="bar-value" id="excellentCount">0</span>
                            </div>
                            <div class="bar-container">
                                <div class="bar bar-excellent" id="excellentBar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="bar-item">
                            <div class="bar-info">
                                <span class="bar-label">2-4 Days</span>
                                <span class="bar-value" id="goodCount">0</span>
                            </div>
                            <div class="bar-container">
                                <div class="bar bar-good" id="goodBar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="bar-item">
                            <div class="bar-info">
                                <span class="bar-label">5+ Days</span>
                                <span class="bar-value" id="warningCount">0</span>
                            </div>
                            <div class="bar-container">
                                <div class="bar bar-warning" id="warningBar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Update Widget -->
            <div class="stat-widget">
                <div class="widget-header">
                    <i data-feather="refresh-cw" class="widget-icon"></i>
                    <span class="widget-title">Last Update</span>
                </div>
                <div class="widget-content">
                    <div class="stat-value" id="lastUpdateStat">--:--</div>
                    <div class="stat-subtitle">real-time sync</div>
                    <div class="stat-trend" id="updateStatus">
                        <i data-feather="wifi" class="trend-icon"></i>
                        <span class="trend-text">Connected</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="controls">
            <button id="refreshBtn" class="btn btn-primary">
                <i data-feather="refresh-cw"></i>
                Refresh
            </button>
            <button id="exportBtn" class="btn btn-outline-secondary">
                <i data-feather="download"></i>
                Export
            </button>
            <button id="debugColumnBtn" class="btn btn-warning" style="display: none;">
                <i data-feather="tool"></i>
                Debug Column
            </button>
        </div>



        <!-- Table -->
        <div class="table-container">
            <div class="table-header">
                <h3  class="table-title">
                    <i data-feather="list" class="table-icon"></i>
                    Vehicle Inventory Detail Report
                </h3>
                <p class="table-subtitle">Real-time tracking of vehicles in Detail Department</p>
            </div>
            <div class="table-wrapper">
                                    <table id="inventoryTable" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                                         <thead class="table-light">
                         <tr>
                             <th width="50">#</th>
                             <th>Date in Detail</th>
                             <th id="daysInDetailColumn" style="display: none;">Days in Detail</th>
                             <th># Keys</th>
                             <th>Vehicle & Stock</th>
                             <th style="display: none;">Write Up Date</th>
                             <th>Notes</th>
                         </tr>
                     </thead>
                    <tbody>
                        <!-- DataTables will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="../../assets/libs/jquery/jquery.min.js"></script>
    <script src="../../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/libs/feather-icons/feather.min.js"></script>
    <script src="../../assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="../../assets/libs/datatables/dataTables.bootstrap5.min.js"></script>
    <script src="../../assets/libs/datatables/dataTables.responsive.min.js"></script>
    


   <script>
        // ========================================
        // BOS INVENTORY MANAGEMENT SYSTEM
        // ========================================

class InventoryManager {
    constructor() {
        // Configuration
        this.debugMode = new URLSearchParams(window.location.search).get('debug') === 'true';
        this.pollingInterval = 30000; // 30 seconds
        this.maxRetries = 3;
        this.retryCount = 0;

        // State
        this.inventoryData = [];
        this.dataTable = null;
        this.pollingTimer = null;
        this.isAuthenticated = false;
        this.userInfo = null;

        
        // Initialization
        this.init();
    }

    async init() {
        try {
            console.log('Initializing BOS inventory manager...');
            
            // Wait for DOM to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.initializeSystem());
            } else {
                this.initializeSystem();
            }
        } catch (error) {
            console.error('Failed to initialize inventory manager:', error);
            this.showError('Failed to initialize system: ' + error.message);
        }
    }

    async initializeSystem() {
        await this.checkAuthentication();
        this.setupEventListeners();
        this.initializeDataTables();
        
        // Initialize analytics (for authenticated users)
        if (this.isAuthenticated) {
            this.initializeDistributionBars();
        }
        
        this.loadInventoryData();
        this.startPolling();
    }

    setupEventListeners() {
        // Refresh button
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.loadInventoryData();
            });
        }
        
        // Export button
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => {
                this.exportData();
            });
        }

        // Debug column button (only show in debug mode)
        const debugColumnBtn = document.getElementById('debugColumnBtn');
        if (debugColumnBtn) {
            debugColumnBtn.addEventListener('click', () => this.debugColumnState());
            debugColumnBtn.style.display = this.debugMode ? 'inline-flex' : 'none';
        }
    }

    initializeDataTables() {
        console.log('Initializing DataTables...');
        
        this.dataTable = $('#inventoryTable').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            responsive: true,
            autoWidth: false,
            order: [[1, 'desc']], // Order by Date in Detail (descending)
                         columnDefs: [
                 {
                     targets: 0,
                     className: 'text-center row-number',
                     orderable: false,
                     searchable: false,
                     width: '50px'
                 },
                 {
                     targets: 1,
                     className: 'text-center',
                     width: '150px'
                 },
                 {
                     targets: 2, // Days in Detail column (hidden)
                     visible: false,
                     className: 'text-center',
                     width: '100px'
                 },
                 {
                     targets: 3, // # Keys
                     className: 'text-center',
                     width: '80px'
                 },
                 {
                     targets: 4, // Vehicle & Stock (combined)
                     className: 'text-center',
                     width: '200px'
                 },
                 {
                     targets: 5, // Write Up Date (hidden)
                     visible: false,
                     className: 'text-center',
                     width: '120px'
                 },
                 {
                     targets: 6, // Notes
                     className: 'text-left notes-column',
                     orderable: false,
                     width: 'auto'
                 }
             ],
            language: {
                processing: 'Loading data...',
                lengthMenu: 'Show _MENU_ entries',
                zeroRecords: 'No vehicles found in detail',
                info: 'Showing _START_ to _END_ of _TOTAL_ vehicles',
                infoEmpty: 'No vehicles available',
                infoFiltered: '(filtered from _MAX_ total vehicles)',
                search: 'Search vehicles:',
                paginate: {
                    first: 'First',
                    last: 'Last',
                    next: 'Next',
                    previous: 'Previous'
                }
            },
                         drawCallback: function() {
                 this.api().column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                     cell.innerHTML = '<span class="row-number">' + (i + 1) + '</span>';
                 });
                 
                 if (typeof feather !== 'undefined') {
                     feather.replace();
                 }
             },
            initComplete: function() {
                console.log('DataTables initialized successfully');
            }
        });
    }

    // === DATA LOADING ===

    async loadInventoryData(showLoadingIndicator = true) {
        if (showLoadingIndicator) {
            this.showLoading(true);
        }
        this.hideError();

        try {
            const url = this.buildApiUrl();
            const response = await this.fetchWithRetry(url);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${await response.text()}`);
            }

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || 'Unknown error occurred');
            }

            this.inventoryData = result.data || [];

            if (this.dataTable) {
                this.updateDataTable();
            }

            this.updateLastUpdate();
            this.updateStats();
            
            // Update charts (always calculate, but only show for authenticated users)
            this.updateDistributionBars();
            
            // Force column visibility update after data is loaded
            if (this.isAuthenticated) {
                setTimeout(() => this.toggleDaysInDetailColumn(), 500);
            }
            
            this.retryCount = 0;

        } catch (error) {
            this.handleLoadError(error);
        } finally {
            if (showLoadingIndicator) {
                this.showLoading(false);
            }
        }
    }

    buildApiUrl() {
        const params = new URLSearchParams();
        if (this.debugMode) {
            params.append('debug', 'true');
        }
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('refresh') === 'true') {
            params.append('refresh', 'true');
        }
        const queryString = params.toString();
        return `./get_inventory.php${queryString ? '?' + queryString : ''}`;
    }

    async fetchWithRetry(url, retries = 3) {
        for (let i = 0; i < retries; i++) {
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    cache: 'no-cache'
                });
                return response;
            } catch (error) {
                if (i === retries - 1) throw error;
                await new Promise(resolve => setTimeout(resolve, 1000 * (i + 1)));
            }
        }
    }

    // === DATATABLES METHODS ===

    updateDataTable() {
        if (!this.dataTable) return;
        
        try {
            // Clear existing data
            this.dataTable.clear();
            
            // Add new rows
            this.inventoryData.forEach((row, index) => {
                const dateInDetail = row[0] || '';
                const daysInDetail = this.calculateDaysInDetail(dateInDetail);
                
                                 const rowData = [
                     '', // Row number (will be populated by drawCallback)
                     this.formatDateWithBadge(dateInDetail, daysInDetail), // Date in Detail with badge
                     this.formatDaysInDetail(daysInDetail), // Days in Detail (hidden)
                     row[1] || '', // Keys
                     this.formatVehicleAndStock(row[3] || '', row[2] || ''), // Vehicle & Stock combined
                     this.formatDate(row[4] || ''), // Write Up Date (hidden)
                     this.formatNotesShort(row[5] || '') // Notes (short with tooltip)
                 ];
                this.dataTable.row.add(rowData);
            });
            
            // Redraw the table
            this.dataTable.draw();
        } catch (error) {
            console.error('Error updating DataTable:', error);
        }
    }

    // Helper methods for formatting data
    parseDate(dateString) {
        if (!dateString) return null;
        
        try {
            let date;
            
            // Handle different date formats
            if (dateString.includes('/')) {
                // Handle MM/DD or MM/DD/YY or MM/DD/YYYY format
                const parts = dateString.split('/');
                if (parts.length === 2) {
                    // MM/DD format - assume current year
                    const currentYear = new Date().getFullYear();
                    date = new Date(currentYear, parseInt(parts[0]) - 1, parseInt(parts[1]));
                } else if (parts.length === 3) {
                    // MM/DD/YY or MM/DD/YYYY format
                    let year = parseInt(parts[2]);
                    
                    // Handle 2-digit years
                    if (year < 100) {
                        // If year is 00-30, assume 20xx, otherwise 19xx
                        year = year <= 30 ? 2000 + year : 1900 + year;
                    }
                    
                    // If year is clearly wrong (like 2001 for current data), use current year
                    const currentYear = new Date().getFullYear();
                    if (year < currentYear - 1) {
                        year = currentYear;
                    }
                    
                    date = new Date(year, parseInt(parts[0]) - 1, parseInt(parts[1]));
                }
            } else {
                // Try parsing as standard date format
                date = new Date(dateString);
                
                // If year is clearly wrong, adjust to current year
                const currentYear = new Date().getFullYear();
                if (date.getFullYear() < currentYear - 1) {
                    date.setFullYear(currentYear);
                }
            }
            
            if (isNaN(date.getTime())) return null;
            return date;
        } catch (error) {
            console.error('Error parsing date:', dateString, error);
            return null;
        }
    }

    calculateDaysInDetail(dateString) {
        if (!dateString) return 0;
        
        try {
            const detailDate = this.parseDate(dateString);
            if (!detailDate) return 0;
            
            const now = new Date();
            
            // Reset time to avoid timezone issues
            detailDate.setHours(0, 0, 0, 0);
            now.setHours(0, 0, 0, 0);
            
            const diffTime = now - detailDate;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            
            // Debug logging
            if (this.debugMode) {
                console.log(`Date: ${dateString} -> Parsed: ${detailDate.toDateString()} -> Days: ${diffDays}`);
            }
            
            return Math.max(0, diffDays);
        } catch (error) {
            console.error('Error calculating days in detail:', error);
            return 0;
        }
    }

    formatDaysInDetail(days) {
        let badgeClass = 'bg-success';
        let label = `${days}d`;
        
        if (days >= 0 && days <= 1) {
            badgeClass = 'bg-success';
        } else if (days >= 2 && days <= 4) {
            badgeClass = 'bg-warning';
        } else if (days >= 5) {
            badgeClass = 'bg-danger';
        }
        
        return `<span class="badge ${badgeClass}" data-days="${days}">${label}</span>`;
    }

    formatStockNumber(stockNumber) {
        if (!stockNumber) return '';
        return `<span class="stock-number">${stockNumber}</span>`;
    }

    formatVehicle(vehicle) {
        if (!vehicle) return '';
        return `<span class="vehicle-info">${vehicle}</span>`;
    }

         formatNotes(notes) {
         if (!notes) return '';
         const hasContent = notes.trim().length > 0;
         const className = hasContent ? 'notes-preview has-content' : 'notes-preview';
         const displayText = notes.length > 50 ? notes.substring(0, 50) + '...' : notes;
         return `<span class="${className}">${displayText}</span>`;
     }

     formatDateWithBadge(dateString, daysInDetail) {
         if (!dateString) return '';
         const formattedDate = this.formatDate(dateString);
         const daysBadge = this.formatDaysInDetail(daysInDetail);
         
         return `<div class="date-with-badge">
             <div>${formattedDate}</div>
             <div>${daysBadge}</div>
         </div>`;
     }

     formatVehicleAndStock(vehicle, stockNumber) {
         if (!vehicle && !stockNumber) return '';
         
         const stockBadge = stockNumber ? this.formatStockNumber(stockNumber) : '';
         const vehicleInfo = vehicle ? `<div class="vehicle-info">${vehicle}</div>` : '';
         
         return `<div class="vehicle-stock-container">
             ${stockBadge}
             ${vehicleInfo}
         </div>`;
     }

     formatNotesShort(notes) {
         if (!notes) return '';
         const trimmedNotes = notes.trim();
         if (trimmedNotes.length === 0) return '';
         
         const shortText = trimmedNotes.length > 20 ? trimmedNotes.substring(0, 20) + '...' : trimmedNotes;
         return `<span class="notes-short" title="${trimmedNotes}">${shortText}</span>`;
     }

    formatDate(dateString) {
        if (!dateString) return '';
        
        try {
            const date = this.parseDate(dateString);
            if (!date) return dateString;
            
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        } catch (error) {
            console.error('Error formatting date:', dateString, error);
            return dateString;
        }
    }

    // === POLLING ===

    startPolling() {
        console.log('Setting up polling for updates');
        
        // Clear any existing polling
        if (this.pollingTimer) {
            clearInterval(this.pollingTimer);
        }
        
        // Set up new polling
        this.pollingTimer = setInterval(() => {
            this.loadInventoryData(false);
        }, this.pollingInterval);
        
        console.log('Polling started');
    }

    // === EXPORT ===

    exportData() {
        if (!this.inventoryData || this.inventoryData.length === 0) {
            alert('No data to export');
            return;
        }
        
                 // Create CSV content
         const headers = ['Row #', 'Date in Detail', 'Days in Detail', '# Keys', 'Stock #', 'Vehicle', 'Notes'];
         let csvContent = headers.join(',') + '\n';
        
        this.inventoryData.forEach((row, index) => {
            const dateInDetail = row[0] || '';
            const daysInDetail = this.calculateDaysInDetail(dateInDetail);
            
                         const csvRow = [
                 index + 1, // Row number
                 dateInDetail,
                 daysInDetail,
                 row[1] || '', // Keys
                 row[2] || '', // Stock
                 row[3] || '', // Vehicle
                 row[5] || ''  // Notes (skipping Write Up Date)
             ].map(field => {
                // Escape quotes and wrap in quotes if contains comma or quotes
                const stringField = String(field);
                if (stringField.includes(',') || stringField.includes('"') || stringField.includes('\n')) {
                    return '"' + stringField.replace(/"/g, '""') + '"';
                }
                return stringField;
            }).join(',');
            csvContent += csvRow + '\n';
        });
        
        // Create and download file
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `bos_inventory_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // === STATS UPDATE ===

    updateStats() {
        const totalRecords = this.inventoryData ? this.inventoryData.length : 0;
        
        const totalEl = document.getElementById('totalRecords');
        if (totalEl) {
            totalEl.textContent = totalRecords.toLocaleString();
        }
    }

    updateLastUpdate() {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        
        const lastUpdateStatEl = document.getElementById('lastUpdateStat');

        if (lastUpdateStatEl) {
            lastUpdateStatEl.textContent = timeString;
        }
        
        // Update average days if user is authenticated
        if (this.isAuthenticated) {
            this.updateAverageDays();
        }
    }

    // === CHARTS & ANALYTICS ===
    
    initializeDistributionBars() {
        console.log('📊 Distribution bars initialized');
    }

    updateDistributionBars() {
        console.log('📊 updateDistributionBars called');
        console.log('📊 inventoryData length:', this.inventoryData.length);
        console.log('📊 isAuthenticated:', this.isAuthenticated);
        
        if (!this.inventoryData.length) {
            console.warn('⚠️ No inventory data available for distribution bars');
            return;
        }

        // Calculate distribution
        const distribution = { excellent: 0, good: 0, warning: 0 };
        
        this.inventoryData.forEach((item, index) => {
            const dateInDetail = item[0] || ''; // First column is date
            const days = this.calculateDaysInDetail(dateInDetail);
            if (this.debugMode) {
                console.log(`📊 Vehicle ${index + 1}: date="${dateInDetail}", calculated days=${days}`);
            }
            
            if (days >= 0 && days <= 1) {
                distribution.excellent++;
            } else if (days >= 2 && days <= 4) {
                distribution.good++;
            } else if (days >= 5) {
                distribution.warning++;
            }
        });

        const total = distribution.excellent + distribution.good + distribution.warning;

        // Update counts and bar widths
        const excellentCount = document.getElementById('excellentCount');
        const goodCount = document.getElementById('goodCount');
        const warningCount = document.getElementById('warningCount');
        
        const excellentBar = document.getElementById('excellentBar');
        const goodBar = document.getElementById('goodBar');
        const warningBar = document.getElementById('warningBar');

        if (excellentCount) excellentCount.textContent = distribution.excellent;
        if (goodCount) goodCount.textContent = distribution.good;
        if (warningCount) warningCount.textContent = distribution.warning;

        // Calculate percentages for bar widths
        const excellentPercent = total > 0 ? (distribution.excellent / total) * 100 : 0;
        const goodPercent = total > 0 ? (distribution.good / total) * 100 : 0;
        const warningPercent = total > 0 ? (distribution.warning / total) * 100 : 0;

        // Animate bars
        if (excellentBar) {
            setTimeout(() => excellentBar.style.width = `${excellentPercent}%`, 100);
        }
        if (goodBar) {
            setTimeout(() => goodBar.style.width = `${goodPercent}%`, 200);
        }
        if (warningBar) {
            setTimeout(() => warningBar.style.width = `${warningPercent}%`, 300);
        }

        console.log('📊 Distribution calculated:', distribution);
        console.log('📊 Total vehicles:', total);
        console.log('📊 Percentages - Excellent:', excellentPercent, 'Good:', goodPercent, 'Warning:', warningPercent);
        
        if (this.debugMode) {
            console.log('📊 Distribution bars updated:', distribution);
        }
    }

    // === AUTHENTICATION & AVERAGE CALCULATION ===
    
    async checkAuthentication() {
        try {
            // Add debug parameter if in debug mode
            const url = this.debugMode ? './check_auth.php?debug=true' : './check_auth.php';
            
            console.log('🔐 Checking authentication...', url);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                cache: 'no-cache',
                timeout: 10000 // 10 second timeout
            });
            
            console.log('🔐 Response status:', response.status, response.statusText);
            
            if (response.ok) {
                const result = await response.json();
                this.isAuthenticated = result.authenticated || false;
                this.userInfo = result.user || null;
                
                console.log('🔐 Authentication result:', {
                    authenticated: this.isAuthenticated,
                    method: result.method || 'Unknown',
                    user: this.userInfo ? this.userInfo.username : 'None'
                });
                
                if (this.debugMode) {
                    console.log('🔍 Full authentication response:', result);
                    
                    // Show debug info
                    this.showDebugInfo(result);
                }
                
                // Update UI based on authentication
                this.updateAuthenticationUI();
                
            } else {
                console.warn('❌ Authentication check failed:', response.status, response.statusText);
                
                // Try to get error details
                try {
                    const errorText = await response.text();
                    console.error('❌ Error response body:', errorText);
                } catch (e) {
                    console.error('❌ Could not read error response');
                }
                
                this.isAuthenticated = false;
                this.updateAuthenticationUI();
            }
        } catch (error) {
            console.error('❌ Network error checking authentication:', error);
            
            // Show user-friendly error if in debug mode
            if (this.debugMode) {
                this.showError('Authentication check failed: ' + error.message);
            }
            
            this.isAuthenticated = false;
            this.updateAuthenticationUI();
        }
    }

    updateAuthenticationUI() {
        console.log('🔐 updateAuthenticationUI called - isAuthenticated:', this.isAuthenticated);
        
        // Show/hide analytics widgets based on authentication
        const avgDaysWidget = document.getElementById('avgDaysWidget');
        const daysDistributionWidget = document.getElementById('daysDistributionWidget');
        
        if (avgDaysWidget) {
            avgDaysWidget.style.display = this.isAuthenticated ? 'block' : 'none';
            console.log('🔐 avgDaysWidget display:', avgDaysWidget.style.display);
        }
        
        if (daysDistributionWidget) {
            daysDistributionWidget.style.display = this.isAuthenticated ? 'block' : 'none';
            console.log('🔐 daysDistributionWidget display:', daysDistributionWidget.style.display);
        }
        
        // Show/hide Days in Detail column based on authentication
        this.toggleDaysInDetailColumn();
        
        // Show/hide top bar based on authentication
        const topBar = document.getElementById('topBar');
        if (topBar) {
            if (this.isAuthenticated) {
                topBar.classList.add('show');
                this.updateUserInfo();
            } else {
                topBar.classList.remove('show');
            }
        }
        
        // Show debug toggle if in debug mode
        const debugToggle = document.getElementById('debugToggle');
        if (debugToggle && this.debugMode) {
            debugToggle.style.display = 'flex';
            debugToggle.addEventListener('click', () => {
                const debugInfo = document.getElementById('debugInfo');
                if (debugInfo) {
                    debugInfo.classList.toggle('show');
                }
            });
        }
    }

         toggleDaysInDetailColumn() {
         console.log('🔐 toggleDaysInDetailColumn called - isAuthenticated:', this.isAuthenticated);
         console.log('🔐 DataTable exists:', !!this.dataTable);
         
         if (!this.dataTable) {
             console.warn('⚠️ DataTable not initialized yet, scheduling retry...');
             setTimeout(() => this.toggleDaysInDetailColumn(), 1000);
             return;
         }
         
         try {
             // The days badge is now shown in the Date in Detail column for authenticated users
             // We can show/hide the badge via CSS or just keep it always visible since it's integrated
             console.log('🔐 Days badge is now integrated in Date column');
             
             // Force table redraw to ensure proper formatting
             this.dataTable.draw();
             
         } catch (error) {
             console.error('❌ Error toggling Days in Detail column:', error);
         }
     }

    debugColumnState() {
        console.log('🐛 === DEBUG COLUMN STATE ===');
        console.log('🐛 isAuthenticated:', this.isAuthenticated);
        console.log('🐛 DataTable exists:', !!this.dataTable);
        
        if (this.dataTable) {
            const column = this.dataTable.column(2);
            console.log('🐛 Column 2 visible:', column.visible());
            console.log('🐛 Column 2 data:', column.data().toArray().slice(0, 5));
        }
        
        const header = document.getElementById('daysInDetailColumn');
        console.log('🐛 Header exists:', !!header);
        if (header) {
            console.log('🐛 Header display:', header.style.display);
            console.log('🐛 Header computed display:', window.getComputedStyle(header).display);
        }
        
        const avgWidget = document.getElementById('avgDaysWidget');
        const distWidget = document.getElementById('daysDistributionWidget');
        console.log('🐛 avgDaysWidget display:', avgWidget ? avgWidget.style.display : 'not found');
        console.log('🐛 daysDistributionWidget display:', distWidget ? distWidget.style.display : 'not found');
        
        console.log('🐛 inventoryData length:', this.inventoryData.length);
        console.log('🐛 === END DEBUG ===');
        
        // Force toggle
        this.toggleDaysInDetailColumn();
    }

    updateUserInfo() {
        if (!this.userInfo) return;
        
        const userName = document.getElementById('userName');
        const userRole = document.getElementById('userRole');
        const userAvatar = document.getElementById('userAvatar');
        
        if (userName && this.userInfo.username) {
            userName.textContent = this.userInfo.username;
        }
        
        if (userRole && this.userInfo.groups && this.userInfo.groups.length > 0) {
            userRole.textContent = this.userInfo.groups[0];
        }
        
        if (userAvatar && this.userInfo.username) {
            userAvatar.textContent = this.userInfo.username.charAt(0).toUpperCase();
        }
    }

    showDebugInfo(authResult) {
        const debugContent = document.getElementById('debugContent');
        if (debugContent) {
            const debugData = {
                timestamp: new Date().toISOString(),
                authenticated: this.isAuthenticated,
                user_info: this.userInfo,
                auth_response: authResult,
                current_url: window.location.href,
                user_agent: navigator.userAgent
            };
            
            debugContent.textContent = JSON.stringify(debugData, null, 2);
        }
    }
    
    calculateAverageDays() {
        if (!this.inventoryData || this.inventoryData.length === 0) {
            return 0;
        }
        
        let totalDays = 0;
        let validEntries = 0;
        
        this.inventoryData.forEach(row => {
            const dateInDetail = row[0]; // First column is date
            if (dateInDetail) {
                const days = this.calculateDaysInDetail(dateInDetail);
                if (days >= 0) {
                    totalDays += days;
                    validEntries++;
                }
            }
        });
        
        if (validEntries === 0) return 0;
        
        const average = totalDays / validEntries;
        return Math.round(average * 10) / 10; // Round to 1 decimal place
    }
    
    updateAverageDays() {
        if (!this.isAuthenticated) return;
        
        const avgDaysValue = document.getElementById('avgDaysValue');
        const avgDaysWidget = document.getElementById('avgDaysWidget');
        const avgDaysStatus = document.getElementById('avgDaysStatus');
        
        if (avgDaysValue && avgDaysWidget) {
            const average = this.calculateAverageDays();
            const currentValue = parseFloat(avgDaysValue.textContent) || 0;
            
            // Only update if value has changed
            if (Math.abs(average - currentValue) > 0.1) {
                // Add animation class
                avgDaysValue.classList.add('updating');
                
                // Update the value
                setTimeout(() => {
                    avgDaysValue.textContent = average.toFixed(1);
                    
                    // Remove animation class after animation completes
                    setTimeout(() => {
                        avgDaysValue.classList.remove('updating');
                    }, 600);
                }, 100);
            } else if (currentValue === 0) {
                // First time setting the value
                avgDaysValue.textContent = average.toFixed(1);
            }
            
            // Update status indicator (now in trend format)
            if (avgDaysStatus) {
                this.updateAverageStatusTrend(average, avgDaysStatus);
            }
            
            // Show the widget if it's hidden
            if (avgDaysWidget.style.display === 'none') {
                avgDaysWidget.style.display = 'block';
                
                // Add a subtle fade-in animation
                avgDaysWidget.style.opacity = '0';
                avgDaysWidget.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    avgDaysWidget.style.transition = 'all 0.6s ease';
                    avgDaysWidget.style.opacity = '1';
                    avgDaysWidget.style.transform = 'translateY(0)';
                }, 150);
            }
            
            if (this.debugMode) {
                console.log('Average days updated:', average, 'Previous:', currentValue);
            }
        }
    }

    updateAverageStatus(average, statusElement) {
        let statusText = '';
        let statusClass = '';
        
        if (average <= 1.5) {
            statusText = '🚀 Excellent';
            statusClass = 'excellent';
        } else if (average <= 3.0) {
            statusText = '✅ Good';
            statusClass = 'good';
        } else if (average <= 5.0) {
            statusText = '⚠️ Attention';
            statusClass = 'warning';
        } else {
            statusText = '🚨 Critical';
            statusClass = 'critical';
        }
        
        // Remove all status classes
        statusElement.className = 'avg-status';
        statusElement.classList.add(statusClass);
        statusElement.textContent = statusText;
        
        // Add a subtle animation when status changes
        statusElement.style.transform = 'scale(0.9)';
        setTimeout(() => {
            statusElement.style.transition = 'transform 0.3s ease';
            statusElement.style.transform = 'scale(1)';
        }, 100);
    }

    updateAverageStatusTrend(average, trendElement) {
        let statusText = '';
        let iconName = 'activity';

        if (average <= 1.5) {
            statusText = 'Excellent';
            iconName = 'trending-up';
        } else if (average <= 3.0) {
            statusText = 'Good';
            iconName = 'check-circle';
        } else if (average <= 5.0) {
            statusText = 'Attention';
            iconName = 'alert-triangle';
        } else {
            statusText = 'Critical';
            iconName = 'alert-circle';
        }

        // Update the trend text
        const trendText = trendElement.querySelector('.trend-text');
        const trendIcon = trendElement.querySelector('.trend-icon');
        
        if (trendText) {
            trendText.textContent = statusText;
        }
        
        if (trendIcon) {
            // Update the icon
            trendIcon.setAttribute('data-feather', iconName);
            // Re-initialize feather icons for this element
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        // Add a subtle animation when status changes
        trendElement.style.transform = 'scale(0.95)';
        setTimeout(() => {
            trendElement.style.transform = 'scale(1)';
        }, 150);
    }

    // === ERROR HANDLING ===
    
    handleLoadError(error) {
        console.error('Error loading inventory data:', error);
        this.showError(`Failed to load inventory data: ${error.message}`);
        
        if (this.retryCount < this.maxRetries) {
            this.retryCount++;
            console.log(`Retrying... (${this.retryCount}/${this.maxRetries})`);
            setTimeout(() => {
                this.loadInventoryData();
            }, 2000 * this.retryCount);
        } else {
            console.error('Max retries reached. Please refresh the page.');
            this.showError('Max retries reached. Please refresh the page manually.');
        }
    }

    showError(message) {
        const errorContainer = document.getElementById('errorContainer');
        if (errorContainer) {
            errorContainer.textContent = message;
            errorContainer.classList.add('show');
        }
    }

    hideError() {
        const errorContainer = document.getElementById('errorContainer');
        if (errorContainer) {
            errorContainer.classList.remove('show');
        }
    }

    showLoading(show) {
        // Use custom loader instead of DataTables processing
        const tableContainer = document.querySelector('.table-container');
        if (tableContainer) {
            if (show) {
                if (!document.getElementById('customLoader')) {
                    const loader = document.createElement('div');
                    loader.id = 'customLoader';
                    loader.style.cssText = `
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: rgba(255, 255, 255, 0.95);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 1000;
                        border-radius: var(--radius-lg);
                    `;
                    loader.innerHTML = `
                        <div style="text-align: center; color: var(--text-secondary);">
                            <div style="width: 2.5rem; height: 2.5rem; border: 3px solid var(--border-color); border-radius: 50%; border-top-color: var(--primary-color); animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
                            <div style="font-weight: 500; font-size: 0.875rem;">Loading data...</div>
                        </div>
                    `;
                    tableContainer.style.position = 'relative';
                    tableContainer.appendChild(loader);
                }
            } else {
                const loader = document.getElementById('customLoader');
                if (loader) {
                    loader.remove();
                }
            }
        }
    }
}

// Initialize the inventory manager when the page loads
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && typeof feather !== 'undefined') {
        console.log('Starting BOS inventory manager...');
        window.inventoryManager = new InventoryManager();
        
        // Initialize feather icons
        feather.replace();
    } else {
        console.error('Required libraries not loaded');
    }
});
   </script>

</body>
</html>