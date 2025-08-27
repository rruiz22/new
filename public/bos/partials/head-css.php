<?php
// Get the base URL dynamically
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$appPath = str_replace('/public/bos', '', dirname($_SERVER['SCRIPT_NAME']));
$fullBaseUrl = $baseUrl . $appPath;
?>

<!-- App favicon -->
<link rel="shortcut icon" href="<?= $fullBaseUrl ?>/assets/images/favicon.ico">

<!-- Bootstrap 5 CSS from CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<!-- Remix Icons -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- BOS Notion-Style Enterprise CSS -->
<link href="css/bos-notion-enterprise.css" rel="stylesheet">

<!-- Velzon Theme - Modern Dashboard Styles -->
<style>
:root {
    --vz-primary: #405189;
    --vz-secondary: #74788d;
    --vz-success: #0ab39c;
    --vz-info: #299cdb;
    --vz-warning: #f7b84b;
    --vz-danger: #f06548;
    --vz-light: #f3f6f9;
    --vz-dark: #212529;
    --vz-gray-100: #f8f9fa;
    --vz-gray-200: #e9ecef;
    --vz-gray-300: #dee2e6;
    --vz-gray-600: #6c757d;
    --vz-gray-700: #495057;
    --vz-border-radius: 0.5rem;
    --vz-box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    --vz-box-shadow-lg: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Global Styles */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
    background: linear-gradient(135deg, var(--vz-light) 0%, #e3e8f0 100%) !important;
    color: var(--vz-gray-700) !important;
    line-height: 1.6;
}

/* Enhanced Top Bar */
.top-bar {
    background: linear-gradient(135deg, var(--vz-primary) 0%, #2d3748 100%) !important;
    box-shadow: var(--vz-box-shadow-lg) !important;
    border: none !important;
}

.top-bar-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1rem 2rem;
}

.top-bar-logo {
    color: white !important;
    font-weight: 700 !important;
    font-size: 1.25rem !important;
}

/* Cards with Velzon Style */
.card, .minimal-card {
    border: none !important;
    border-radius: var(--vz-border-radius) !important;
    box-shadow: var(--vz-box-shadow) !important;
    background: white !important;
    transition: all 0.3s ease !important;
}

.card:hover, .minimal-card:hover {
    box-shadow: var(--vz-box-shadow-lg) !important;
    transform: translateY(-2px) !important;
}

/* Modern Metric Cards */
.metric-card {
    background: white !important;
    border: none !important;
    border-radius: var(--vz-border-radius) !important;
    box-shadow: var(--vz-box-shadow) !important;
    padding: 1.5rem !important;
    transition: all 0.3s ease !important;
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
    background: linear-gradient(90deg, var(--vz-primary), var(--vz-info));
}

.metric-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: var(--vz-box-shadow-lg) !important;
}

.metric-value {
    font-size: 2rem !important;
    font-weight: 700 !important;
    color: var(--vz-dark) !important;
    margin: 0.5rem 0 !important;
}

.metric-label {
    font-size: 0.75rem !important;
    color: var(--vz-gray-600) !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
}

/* Enhanced Buttons */
.btn {
    border-radius: var(--vz-border-radius) !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    border: none !important;
}

.btn-primary {
    background: linear-gradient(135deg, var(--vz-primary) 0%, #2d3748 100%) !important;
    box-shadow: var(--vz-box-shadow) !important;
}

.btn-primary:hover {
    transform: translateY(-1px) !important;
    box-shadow: var(--vz-box-shadow-lg) !important;
}

.btn-success {
    background: linear-gradient(135deg, var(--vz-success) 0%, #059669 100%) !important;
}

.btn-warning {
    background: linear-gradient(135deg, var(--vz-warning) 0%, #d97706 100%) !important;
}

.btn-danger {
    background: linear-gradient(135deg, var(--vz-danger) 0%, #dc2626 100%) !important;
}

.btn-outline-primary {
    border: 2px solid var(--vz-primary) !important;
    color: var(--vz-primary) !important;
}

.btn-outline-primary:hover {
    background: var(--vz-primary) !important;
    color: white !important;
}

/* Modern Tables */
.table {
    border-radius: var(--vz-border-radius) !important;
    overflow: hidden !important;
    box-shadow: var(--vz-box-shadow) !important;
    background: white !important;
}

.table thead th {
    background: linear-gradient(135deg, var(--vz-gray-100) 0%, var(--vz-gray-200) 100%) !important;
    border-bottom: 2px solid var(--vz-gray-300) !important;
    font-weight: 600 !important;
    color: var(--vz-gray-700) !important;
    text-transform: uppercase !important;
    font-size: 0.75rem !important;
    letter-spacing: 1px !important;
}

.table tbody tr:hover {
    background: rgba(64, 81, 137, 0.05) !important;
}

/* Status Badges */
.status-badge {
    padding: 0.375rem 0.75rem !important;
    border-radius: 50px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

.status-badge.recent {
    background: linear-gradient(135deg, var(--vz-success) 0%, #059669 100%) !important;
    color: white !important;
}

.status-badge.moderate {
    background: linear-gradient(135deg, var(--vz-warning) 0%, #d97706 100%) !important;
    color: white !important;
}

.status-badge.aged {
    background: linear-gradient(135deg, var(--vz-danger) 0%, #dc2626 100%) !important;
    color: white !important;
}

/* Filter Widgets */
.filter-widget {
    background: white !important;
    border: 1px solid var(--vz-gray-200) !important;
    border-radius: var(--vz-border-radius) !important;
    padding: 1.5rem !important;
    transition: all 0.3s ease !important;
    cursor: pointer !important;
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
    background: var(--vz-primary);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.filter-widget:hover {
    border-color: var(--vz-primary) !important;
    transform: translateY(-2px) !important;
    box-shadow: var(--vz-box-shadow-lg) !important;
}

.filter-widget:hover::before {
    transform: scaleX(1);
}

.filter-widget.active {
    border-color: var(--vz-primary) !important;
    background: rgba(64, 81, 137, 0.05) !important;
}

.filter-widget.active::before {
    transform: scaleX(1);
}

/* Page Headers */
.page-title-box h4 {
    color: var(--vz-dark) !important;
    font-weight: 700 !important;
    font-size: 1.5rem !important;
}

/* Service Summary Container */
.service-summary-container {
    background: white !important;
    border-radius: var(--vz-border-radius) !important;
    box-shadow: var(--vz-box-shadow) !important;
    padding: 2rem !important;
    margin-bottom: 2rem !important;
}

.service-summary-header {
    border-bottom: 2px solid var(--vz-gray-200) !important;
    padding-bottom: 1rem !important;
    margin-bottom: 1.5rem !important;
}

.service-summary-title {
    color: var(--vz-dark) !important;
    font-weight: 700 !important;
    font-size: 1.25rem !important;
}

/* Overview Metrics */
.overview-metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.overview-metric {
    background: white !important;
    border: 1px solid var(--vz-gray-200) !important;
    border-radius: var(--vz-border-radius) !important;
    padding: 1.5rem !important;
    transition: all 0.3s ease !important;
    position: relative;
    overflow: hidden;
}

.overview-metric::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--vz-primary), var(--vz-info));
}

.overview-metric:hover {
    transform: translateY(-2px) !important;
    box-shadow: var(--vz-box-shadow-lg) !important;
}

.metric-icon {
    width: 48px !important;
    height: 48px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: white !important;
    font-size: 1.25rem !important;
    margin-bottom: 1rem !important;
}

.metric-icon.total {
    background: linear-gradient(135deg, var(--vz-primary) 0%, #2d3748 100%) !important;
}

.metric-icon.recent {
    background: linear-gradient(135deg, var(--vz-success) 0%, #059669 100%) !important;
}

.metric-icon.moderate {
    background: linear-gradient(135deg, var(--vz-warning) 0%, #d97706 100%) !important;
}

.metric-icon.aged {
    background: linear-gradient(135deg, var(--vz-danger) 0%, #dc2626 100%) !important;
}

.metric-icon.average {
    background: linear-gradient(135deg, var(--vz-info) 0%, #0369a1 100%) !important;
}

/* Modern Analytics Grid */
.analytics-sections-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.service-summary-section {
    background: white !important;
    border-radius: var(--vz-border-radius) !important;
    box-shadow: var(--vz-box-shadow) !important;
    overflow: hidden !important;
    transition: all 0.3s ease !important;
}

.service-summary-section:hover {
    box-shadow: var(--vz-box-shadow-lg) !important;
    transform: translateY(-2px) !important;
}

.section-header {
    background: linear-gradient(135deg, var(--vz-gray-100) 0%, var(--vz-gray-200) 100%) !important;
    padding: 1.25rem !important;
    border-bottom: 1px solid var(--vz-gray-300) !important;
}

.section-icon {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: white !important;
    font-size: 1rem !important;
}

.section-icon.analytics {
    background: linear-gradient(135deg, var(--vz-info) 0%, #0369a1 100%) !important;
}

.section-title {
    font-weight: 600 !important;
    color: var(--vz-dark) !important;
    margin: 0 !important;
}

/* Loading Overlay */
.page-loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.page-loading-overlay.hidden {
    opacity: 0;
    visibility: hidden;
}

.loading-content {
    text-align: center;
    background: white;
    padding: 2rem;
    border-radius: var(--vz-border-radius);
    box-shadow: var(--vz-box-shadow-lg);
    max-width: 300px;
}

.loading-spinner .spinner-border {
    width: 3rem;
    height: 3rem;
    color: var(--vz-primary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .overview-metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .analytics-sections-grid {
        grid-template-columns: 1fr;
    }
    
    .service-summary-container {
        padding: 1.25rem !important;
    }
    
    .metric-card {
        padding: 1.25rem !important;
    }
    
    .metric-value {
        font-size: 1.5rem !important;
    }
}

@media (max-width: 480px) {
    .top-bar-content {
        padding: 1rem !important;
        flex-direction: column !important;
        gap: 1rem;
    }
    
    .filter-widget {
        padding: 1rem !important;
    }
    
    .metric-card {
        padding: 1rem !important;
    }
    
    .overview-metric {
        padding: 1rem !important;
    }
}
</style>
