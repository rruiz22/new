
<?php
// Get the base URL dynamically
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$appPath = str_replace('/public/bos', '', dirname($_SERVER['SCRIPT_NAME']));
$fullBaseUrl = $baseUrl . $appPath;
?>

<!-- App favicon -->
<link rel="shortcut icon" href="<?= $fullBaseUrl ?>/assets/images/favicon.ico">

<!-- Bootstrap Css -->
<link href="<?= $fullBaseUrl ?>/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="<?= $fullBaseUrl ?>/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="<?= $fullBaseUrl ?>/assets/css/app.min.css" rel="stylesheet" type="text/css" />
<!-- DataTables -->
<link href="<?= $fullBaseUrl ?>/assets/libs/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $fullBaseUrl ?>/assets/libs/datatables/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!-- SweetAlert2 -->
<link href="<?= $fullBaseUrl ?>/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<!-- Node Waves -->
<link href="<?= $fullBaseUrl ?>/assets/libs/node-waves/waves.min.css" rel="stylesheet" type="text/css" />

<!-- Custom styles for clean flat dashboard -->
<style>
/* Clean flat dashboard styles */
.dashboard-widgets .card {
    border: 1px solid #e9ecef;
    box-shadow: none;
    border-radius: 4px;
    background: #fff;
}

.dashboard-widgets .card-body {
    padding: 1.25rem;
}

/* Clean flat inventory stats */
.inventory-stats-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #495057;
}

.inventory-stats-card .card-body {
    position: relative;
}

/* Flat metric cards */
.metric-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    transition: all 0.2s ease;
}

.metric-card:hover {
    box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    transform: translateY(-1px);
}

.metric-card .metric-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #212529;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.metric-card .metric-label {
    font-size: 0.8rem;
    color: #495057;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.metric-card .metric-label i {
    color: #007bff;
}

.metric-card .metric-change {
    font-size: 0.75rem;
    font-weight: 500;
}

/* Compact service tables */
.metric-card .service-table {
    font-size: 0.75rem;
    margin-top: 0.5rem;
    margin-bottom: 0;
}

.metric-card .service-table th {
    font-size: 0.7rem;
    padding: 0.4rem 0.5rem;
    background: #f8f9fa;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    color: #6c757d;
}

.metric-card .service-table td {
    padding: 0.4rem 0.5rem;
    border-top: 1px solid #f1f3f4;
    vertical-align: middle;
}

.metric-card .table-responsive {
    border-radius: 4px;
    border: none;
    max-height: 120px;
    overflow-y: auto;
}

/* Vehicle Years Chart Styles */
.years-chart {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    height: 160px;
    padding: 1rem 0.5rem;
    gap: 0.5rem;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
}

.year-bar-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 0;
    height: 100%;
    justify-content: flex-end;
}

.year-bar {
    width: 100%;
    max-width: 40px;
    background: #e9ecef;
    border-radius: 2px 2px 0 0;
    position: relative;
    min-height: 10px;
    order: 1;
}

.year-bar-fill {
    width: 100%;
    height: 100%;
    border-radius: 2px 2px 0 0;
    transition: all 0.3s ease;
}

/* Different colors for each bar based on position */
.year-bar-container:nth-child(1) .year-bar-fill {
    background: linear-gradient(180deg, #28a745 0%, #1e7e34 100%); /* Green */
}

.year-bar-container:nth-child(2) .year-bar-fill {
    background: linear-gradient(180deg, #007bff 0%, #0056b3 100%); /* Blue */
}

.year-bar-container:nth-child(3) .year-bar-fill {
    background: linear-gradient(180deg, #ffc107 0%, #e0a800 100%); /* Yellow */
}

.year-bar-container:nth-child(4) .year-bar-fill {
    background: linear-gradient(180deg, #dc3545 0%, #bd2130 100%); /* Red */
}

.year-bar-container:nth-child(5) .year-bar-fill {
    background: linear-gradient(180deg, #6f42c1 0%, #5a32a3 100%); /* Purple */
}

.year-bar-container:nth-child(n+6) .year-bar-fill {
    background: linear-gradient(180deg, #6c757d 0%, #545b62 100%); /* Gray for additional bars */
}

.year-bar-container:hover .year-bar-fill {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.year-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: #495057;
    text-align: center;
    margin-top: 0.25rem;
    line-height: 1;
    order: 2;
}

.year-count {
    font-size: 0.65rem;
    color: #6c757d;
    text-align: center;
    line-height: 1;
    margin-top: 0.1rem;
    order: 3;
}

.metric-change.positive { color: #28a745; }
.metric-change.negative { color: #dc3545; }
.metric-change.neutral { color: #6c757d; }

/* Minimalist cards */
.minimal-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    overflow: hidden;
    transition: all 0.2s ease;
    margin-bottom: 2rem;
}

.minimal-card:hover {
    border-color: #007bff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.minimal-card-header {
    background: #f8f9fa;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.minimal-card-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.minimal-card-icon {
    color: #6c757d;
    font-size: 1rem;
}

.minimal-card-body {
    padding: 1.25rem;
}

/* Service tables */
.service-table-container {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    max-height: 350px;
}

.service-table-container .table-responsive {
    max-height: 250px;
    overflow-y: auto;
}

.service-table-header {
    background: #f8f9fa;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e9ecef;
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.service-table {
    width: 100%;
    margin-bottom: 0;
}

.service-table th,
.service-table td {
    padding: 0.75rem 1.25rem;
    border-top: 1px solid #f1f3f4;
    font-size: 0.875rem;
    vertical-align: middle;
}

.service-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #495057;
    border-top: none;
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.service-table tbody tr {
    transition: background-color 0.15s ease;
}

.service-table tbody tr:hover {
    background-color: #f8f9fa;
}

.service-table tbody tr:last-child td {
    border-bottom: none;
}

/* Status badges - flat design */
.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 3px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.recent { background: #d4edda; color: #155724; }
.status-badge.moderate { background: #fff3cd; color: #856404; }
.status-badge.aged { background: #f8d7da; color: #721c24; }
.status-badge.completed { background: #d1ecf1; color: #0c5460; }

/* Chart containers - flat */
.chart-container {
    position: relative;
    height: 250px;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 1rem;
}

/* Clean grid layout */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

/* Empty state styling */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: #dee2e6;
}

/* Table improvements */
.service-table td strong {
    color: #212529;
    font-weight: 600;
}

.service-table .text-muted {
    color: #6c757d !important;
    font-style: italic;
}

/* Inventory statistics section */
.inventory-stats-section {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 1.75rem;
    margin-bottom: 2.5rem;
    border: 1px solid #e9ecef;
}

.filter-widget {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 1rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.filter-widget:hover {
    border-color: #007bff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.filter-widget.active {
    border-color: #007bff;
    background: #f8f9ff;
}

/* Main inventory table card */
.inventory-table-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 2rem;
}

.inventory-table-header {
    background: #f8f9fa;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: between;
}

/* Add padding to table container for better spacing */
.inventory-table-card .table-responsive {
    padding: 0 1.25rem 1.25rem 1.25rem;
    margin: 0;
}

/* Ensure table takes full width within padded container */
.inventory-table-card .table-responsive table {
    margin-bottom: 0;
}

/* Apply same spacing to all table cards */
.minimal-card .table-responsive {
    padding-left: 1rem;
    padding-right: 1rem;
}

/* Orders tables spacing (staff only) */
.minimal-card:has(#inventoryOrdersTable) .table-responsive,
.minimal-card:has(#allOrdersTable) .table-responsive {
    padding: 0 1.25rem 1rem 1.25rem;
}

/* Section spacing */
.row {
    margin-bottom: 1.5rem;
}

.row.mb-4 {
    margin-bottom: 2.5rem !important;
}

/* Page Loading Overlay */
.page-loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(2px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.page-loading-overlay.hidden {
    opacity: 0;
    visibility: hidden;
}

.loading-content {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    max-width: 300px;
    width: 90%;
}

.loading-spinner {
    margin-bottom: 1.5rem;
}

.loading-spinner .spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3rem;
}

.loading-text h5 {
    color: #495057;
    font-weight: 600;
}

.loading-text p {
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Animate the spinner */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-spinner .spinner-border {
    animation: spin 1s linear infinite;
}

/* Service Status Chart Styles */
.status-chart {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 1rem 0.5rem;
}

.status-bar-container {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.status-bar-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
}

.status-bar-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-bar {
    height: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    position: relative;
    min-width: 20px;
    flex: 1;
    transition: all 0.3s ease;
}

.status-bar-fill {
    width: 100%;
    height: 100%;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.status-bar-count {
    font-size: 0.7rem;
    color: #6c757d;
    white-space: nowrap;
    min-width: 60px;
    text-align: right;
}

.status-bar-container:hover .status-bar {
    transform: scaleY(1.1);
}

/* Compact Service Summary Container */
.service-summary-container {
    background: #fff;
    border-radius: 6px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
}

.service-summary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e9ecef;
}

.service-summary-title {
    font-size: 1rem;
    font-weight: 600;
    color: #212529;
    margin: 0;
}

.service-summary-stats {
    display: flex;
    gap: 1.5rem;
}

.summary-stat {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #6c757d;
}

.summary-stat i {
    font-size: 0.9rem;
}

.summary-stat.inventory i { color: #6f42c1; }
.summary-stat.recent i { color: #28a745; }
.summary-stat.attention i { color: #dc3545; }
.summary-stat.completed i { color: #007bff; }

/* Grids unificados */
.service-summary-grid,
.analytics-sections-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.service-summary-section {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    padding-bottom: 0.375rem;
    border-bottom: 1px solid #dee2e6;
}

.section-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
    flex-shrink: 0;
}

/* Colores de iconos unificados con el tema */
.section-icon.overview { background: #6f42c1; }
.section-icon.recent { background: #10b981; }
.section-icon.attention { background: #ef4444; }
.section-icon.completed { background: #2563eb; }
.section-icon.analytics { background: #06b6d4; }

.section-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #495057;
    letter-spacing: 0.025em;
    margin: 0;
}

.section-table {
    max-height: 140px;
    overflow-y: auto;
}

.compact-table {
    width: 100%;
    font-size: 0.7rem;
    margin: 0;
}

.compact-table th {
    font-size: 0.65rem;
    padding: 0.25rem 0.375rem;
    background: #fff;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border: none;
    color: #6c757d;
    position: sticky;
    top: 0;
    z-index: 1;
}

.compact-table td {
    padding: 0.25rem 0.375rem;
    border-top: 1px solid #f1f3f4;
    vertical-align: middle;
    line-height: 1.2;
}

.compact-table tbody tr:hover {
    background: rgba(0,123,255,0.05);
}

/* Inventory Overview Section */
.inventory-overview-section {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
    margin-bottom: 1rem;
}

.overview-metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 0.5rem;
    margin-top: 0.375rem;
}

.overview-metric {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: #fff;
    border-radius: 4px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.overview-metric:hover {
    background: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

.metric-icon {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.9rem;
    flex-shrink: 0;
}

/* Colores de métricas unificados */
.metric-icon.total { background: #6f42c1; }
.metric-icon.recent { background: #10b981; }
.metric-icon.moderate { background: #f59e0b; }
.metric-icon.aged { background: #ef4444; }
.metric-icon.average { background: #2563eb; }

.metric-info {
    flex: 1;
    min-width: 0;
}

.metric-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.1rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.metric-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #212529;
    line-height: 1;
    margin-bottom: 0.1rem;
}

.metric-subtitle {
    font-size: 0.6rem;
    color: #6c757d;
    line-height: 1.1;
    margin-top: 0.125rem;
}

/* Analytics Sections - Heredan estilos unificados */
.analytics-sections-grid {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    margin-top: 0.75rem;
}

.section-count {
    background: #e9ecef;
    color: #495057;
    font-size: 0.65rem;
    font-weight: 600;
    padding: 0.2rem 0.4rem;
    border-radius: 12px;
    min-width: 20px;
    text-align: center;
    margin-left: auto;
}

.analytics-content {
    padding: 0.5rem 0 0 0;
}

.status-chart-container,
.years-chart-container {
    margin-bottom: 0.5rem;
    max-height: 180px;
    overflow: hidden;
}

.chart-legend {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.7rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
    flex-shrink: 0;
}

.legend-color.in-progress { background: #ffc107; }
.legend-color.needs-attention { background: #dc3545; }

.legend-color.year-old { background: #28a745; }
.legend-color.year-mid { background: #007bff; }
.legend-color.year-recent { background: #ffc107; }
.legend-color.year-new { background: #dc3545; }

.legend-label {
    font-weight: 600;
    color: #495057;
    flex: 1;
}

.legend-value {
    font-weight: 600;
    color: #212529;
    font-size: 0.65rem;
}

/* Consolidated Inventory Overview Card Styles */
.inventory-overview-card {
    background: #fff;
    border-radius: 6px;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
}

.overview-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-top: 0.75rem;
}

.overview-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 4px;
    border: none;
    transition: all 0.2s ease;
}

.overview-item:hover {
    background: #e9ecef;
    transform: none;
    box-shadow: none;
}

.overview-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1rem;
    flex-shrink: 0;
}

.overview-icon.total {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.overview-icon.recent {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.overview-icon.moderate {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
}

.overview-icon.aged {
    background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
}

.overview-icon.average {
    background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
}

.overview-content {
    flex: 1;
    min-width: 0;
}

.overview-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.1rem;
}

.overview-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #212529;
    line-height: 1;
    margin-bottom: 0.1rem;
}

.overview-subtitle {
    font-size: 0.7rem;
    color: #6c757d;
    line-height: 1.1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .overview-stats {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .overview-item {
        padding: 0.75rem;
        gap: 0.75rem;
    }
    
    .overview-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .overview-value {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .overview-stats {
        gap: 0.75rem;
    }
    
    .overview-item {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
        padding: 1rem 0.75rem;
    }
    
    .service-summary-container {
        padding: 1rem;
    }
    
    .service-summary-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .service-summary-stats {
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .service-summary-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .service-summary-section {
        padding: 0.5rem;
    }
    
    .section-header {
        gap: 0.375rem;
    }
    
    .section-icon {
        width: 18px;
        height: 18px;
        font-size: 0.65rem;
    }
    
    .section-title {
        font-size: 0.7rem;
    }
    
    .compact-table {
        font-size: 0.65rem;
    }
    
    .compact-table th,
    .compact-table td {
        padding: 0.2rem 0.25rem;
    }
    
    .inventory-overview-section {
        padding: 0.5rem;
    }
    
    .overview-metrics-grid {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .overview-metric {
        padding: 0.5rem;
        gap: 0.5rem;
    }
    
    .metric-icon {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    .metric-value {
        font-size: 1.1rem;
    }
    
    .metric-label {
        font-size: 0.65rem;
    }
    
    .metric-subtitle {
        font-size: 0.6rem;
    }
    
    .analytics-sections-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .section-count {
        font-size: 0.6rem;
        padding: 0.15rem 0.3rem;
    }
    
    .chart-legend {
        gap: 0.25rem;
    }
    
    .legend-item {
        font-size: 0.65rem;
        gap: 0.375rem;
    }
    
    .legend-color {
        width: 10px;
        height: 10px;
    }
    
    .legend-value {
        font-size: 0.6rem;
    }
}
    
    .service-table th,
    .service-table td {
        padding: 0.5rem 0.75rem;
        font-size: 0.8125rem;
    }
    
    .minimal-card-header,
    .service-table-header {
        padding: 0.75rem 1rem;
    }
    
    .minimal-card-body {
        padding: 1rem;
    }
    
    .minimal-card {
        margin-bottom: 1.5rem;
    }
    
    .inventory-stats-section {
        padding: 1.25rem;
        margin-bottom: 2rem;
    }
</style>
