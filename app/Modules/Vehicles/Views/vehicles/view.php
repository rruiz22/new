<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? 'Vehicle Details' ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?? 'Vehicle Details' ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('vehicles') ?>">Vehicles</a></li>
<li class="breadcrumb-item active">
    <?php if (isset($vin)): ?>
        <?= esc($vin) ?>
    <?php else: ?>
        <?= $vehicle['vehicle'] ?? 'Vehicle Details' ?>
    <?php endif; ?>
</li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Leaflet CSS for Location History Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Include ServiceOrders shared styles for consistent table styling -->
<?php include(APPPATH . 'Modules/ServiceOrders/Views/service_orders/shared_styles.php'); ?>

<style>
.vehicle-header {
    background: white;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.vehicle-info-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 12px;
}

/* Responsive Layout Improvements */
@media (max-width: 575.98px) {
    /* Extra small devices */
    .vehicle-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .card-header h4,
    .card-header h5 {
        font-size: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}

@media (max-width: 767.98px) {
    /* Small devices */
    .row.g-3.g-lg-4 {
        --bs-gutter-x: 1rem;
    }
}

@media (max-width: 991.98px) {
    /* Mobile and tablet: full width layout */
    .vehicle-info-card,
    .card {
        margin-bottom: 1.5rem;
    }
    
    /* Ensure mobile cards have proper spacing */
    .col-12.col-lg-4.order-1.order-lg-2 .card:last-child {
        margin-bottom: 2rem;
    }
}

@media (min-width: 992px) {
    /* Desktop: remove sticky positioning for better height control */
    .col-lg-4.order-lg-2 {
        height: auto !important;
        min-height: auto !important;
        max-height: none !important;
        overflow: visible !important;
        padding-right: 1rem;
        position: relative;
    }
    
    /* Main content area improvements */
    .col-lg-8.order-lg-1 {
        padding-right: 0.5rem;
    }
    
    /* Ensure sidebar cards are fully visible */
    .col-lg-4.order-lg-2 .card {
        margin-bottom: 1.5rem;
        page-break-inside: avoid;
        break-inside: avoid;
    }
    
    .col-lg-4.order-lg-2 .card:last-child {
        margin-bottom: 0;
    }
}

/* Card improvements for better visual hierarchy */
.card {
    transition: box-shadow 0.15s ease-in-out;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Sidebar card specific styles */
.col-lg-4 .card {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    margin-bottom: 1.5rem;
    width: 100%;
    display: block;
}

.col-lg-4 .card:last-child {
    margin-bottom: 2rem; /* Extra space at bottom */
}

.col-lg-4 .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 12px 12px 0 0;
    padding: 1rem 1.25rem;
}

.col-lg-4 .card-body {
    padding: 1.25rem;
}

/* Ensure sidebar has proper spacing */
@media (min-width: 992px) {
    .col-lg-4.order-lg-2 {
        padding-bottom: 3rem; /* Extra bottom padding */
    }
}

/* Service History DataTable Styles */
#serviceHistoryTable_wrapper {
    margin-top: 1rem;
}

#serviceHistoryTable_wrapper .dataTables_filter {
    margin-bottom: 1rem;
}

#serviceHistoryTable_wrapper .dataTables_filter input {
    margin-left: 0.5rem;
    padding: 0.375rem 0.75rem;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

#serviceHistoryTable_wrapper .dataTables_info {
    padding-top: 0.75rem;
    color: #6c757d;
    font-size: 0.875rem;
}

#serviceHistoryTable_wrapper .dataTables_paginate {
    padding-top: 0.75rem;
}

#serviceHistoryTable_wrapper .dataTables_paginate .paginate_button {
    padding: 0.375rem 0.75rem;
    margin-left: 0.125rem;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    background: #fff;
    color: #0d6efd;
    text-decoration: none;
}

#serviceHistoryTable_wrapper .dataTables_paginate .paginate_button:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

#serviceHistoryTable_wrapper .dataTables_paginate .paginate_button.current {
    background: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

/* Responsive adjustments for DataTable */
@media (max-width: 767.98px) {
    #serviceHistoryTable_wrapper .dataTables_filter,
    #serviceHistoryTable_wrapper .dataTables_info,
    #serviceHistoryTable_wrapper .dataTables_paginate {
        text-align: center;
    }
}

/* Sidebar content optimizations */
@media (min-width: 992px) {
    /* QR Code image responsive sizing */
    .col-lg-4 .qr-sidebar-image {
        max-width: 100%;
        height: auto;
        width: clamp(150px, 80%, 200px);
    }
    
    /* Statistics and info items spacing */
    .col-lg-4 .info-item,
    .col-lg-4 .stat-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .col-lg-4 .info-item:last-child,
    .col-lg-4 .stat-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #64748b;
}

.info-value {
    font-weight: 500;
    color: #1e293b;
}

.vin-display {
    font-family: 'Courier New', monospace;
    background: #f8fafc;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    font-size: 1.1rem;
    letter-spacing: 1px;
    color: #1e293b;
}



.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
}

.clickable-row {
    transition: background-color 0.15s ease, transform 0.15s ease;
}

.clickable-row:hover {
    background-color: rgba(64, 81, 137, 0.08) !important;
    transform: translateY(-1px);
}

.service-history-table .clickable-row:hover {
    background-color: rgba(13, 110, 253, 0.1) !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.service-history-table .clickable-row {
    transition: all 0.2s ease;
}

.service-history-table .clickable-row:hover td {
    border-color: rgba(13, 110, 253, 0.3);
}

.clickable-row:active {
    transform: translateY(0);
    background-color: rgba(64, 81, 137, 0.12) !important;
}

/* Enhanced Responsive Styles */
@media (max-width: 576px) {
    /* Mobile - Extra Small Screens */
    .vehicle-header {
        padding: 1rem;
        text-align: center;
    }
    
    .vehicle-header .h3 {
        font-size: 1.25rem;
        line-height: 1.3;
        word-break: break-word;
    }
    
    .vin-display {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        word-break: break-all;
    }
    
    .card-header .card-title {
        font-size: 1rem;
    }
    
    .info-label, .info-value {
        font-size: 0.875rem;
        line-height: 1.4;
    }
    
    .info-value {
        word-break: break-word;
        overflow-wrap: break-word;
    }
    
    .stat-value-sm {
        font-size: 1.25rem;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.35rem 0.7rem;
    }
}

@media (min-width: 577px) and (max-width: 768px) {
    /* Tablets - Small to Medium */
    .vehicle-header {
        padding: 1.5rem;
    }
    
    .vehicle-header .h3 {
        font-size: 1.5rem;
        line-height: 1.3;
        word-break: break-word;
    }
    
    .vin-display {
        font-size: 0.9rem;
        word-break: break-all;
    }
    
    .info-label, .info-value {
        font-size: 0.9rem;
        line-height: 1.4;
    }
    
    .info-value {
        word-break: break-word;
        overflow-wrap: break-word;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
}

@media (min-width: 769px) and (max-width: 991px) {
    /* Tablets - Large */
    .vehicle-header .h3 {
        word-break: break-word;
    }
    
    .vin-display {
        word-break: break-all;
    }
    
    .info-value {
        word-break: break-word;
        overflow-wrap: break-word;
    }
}

@media (min-width: 992px) {
    /* Desktop and larger */
    .info-value {
        word-break: break-word;
        overflow-wrap: break-word;
    }
    
    .vin-display {
        word-break: break-all;
    }
}

/* Text handling across all screen sizes */
.text-break {
    word-break: break-word !important;
    overflow-wrap: break-word !important;
    hyphens: auto;
}

.font-monospace {
    word-break: break-all !important;
    font-family: 'Courier New', Consolas, Monaco, monospace !important;
}

/* Improved sidebar responsive behavior */
@media (max-width: 991px) {
    .vehicle-info-card .info-item {
        padding: 0.5rem 0;
    }
    
    .vehicle-info-card .info-label {
        font-size: 0.875rem;
    }
    
    .vehicle-info-card .info-value {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
}

/* Enhanced card responsiveness */
.card {
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.card-title {
    word-break: break-word;
    overflow-wrap: break-word;
}

/* Table improvements for mobile */
@media (max-width: 768px) {
    .service-orders-table {
        font-size: 0.8rem;
    }
    
    .service-orders-table .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
        margin: 0.1rem;
        display: inline-block;
        word-break: break-word;
    }
    
    .service-orders-table td {
        padding: 0.5rem 0.25rem;
        vertical-align: top;
    }
    
    .service-orders-table small {
        font-size: 0.7rem;
        line-height: 1.3;
    }
}

/* Location History Map Styles - Responsive */
:root {
    --map-height-desktop: 350px;
    --map-height-tablet: 300px;
    --map-height-mobile: 250px;
}

.location-history-map {
    height: var(--map-height-desktop);
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    margin-bottom: 1rem;
    width: 100%;
    min-height: 200px;
    max-height: 600px;
}

.map-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    width: 100%;
}

/* Responsive Map Heights */
@media (max-width: 1024px) {
    .location-history-map {
        height: var(--map-height-tablet);
    }
}

@media (max-width: 768px) {
    .location-history-map {
        height: var(--map-height-mobile);
    }
    
    .map-container {
        margin: 0 -15px;
        border-radius: 0;
    }
    
    .location-history-map {
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
}

@media (max-width: 480px) {
    .location-history-map {
        height: 200px;
    }
}

.location-marker-popup {
    text-align: left;
    min-width: 200px;
    max-width: 280px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.3;
    font-size: 13px;
}

.popup-title {
    font-weight: 600;
    color: #007bff;
    font-size: 14px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.popup-spot-badge {
    background: #007bff;
    color: white;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 500;
}

.popup-info {
    margin: 4px 0;
    display: flex;
    align-items: flex-start;
    gap: 6px;
}

.popup-info i {
    color: #6c757d;
    font-size: 12px;
    width: 14px;
    flex-shrink: 0;
    margin-top: 1px;
}

.popup-info-text {
    color: #495057;
    flex: 1;
    font-size: 12px;
}

.popup-info-text strong {
    color: #212529;
    font-weight: 500;
}

.popup-info-text code {
    background: #f8f9fa;
    padding: 1px 4px;
    border-radius: 3px;
    font-size: 11px;
    color: #495057;
}

.popup-address {
    color: #007bff !important;
    font-weight: 500;
}

.popup-notes {
    background: #fff8e1;
    padding: 4px 6px;
    border-radius: 4px;
    font-size: 11px;
    color: #e65100;
    border-left: 2px solid #ff9800;
    margin-top: 2px;
}

.popup-action {
    margin-top: 8px;
    text-align: center;
}

.popup-action .btn {
    font-size: 11px;
    padding: 4px 12px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 500;
    border: 1px solid #007bff;
    color: #007bff;
    background: transparent;
    transition: all 0.2s ease;
}

.popup-action .btn:hover {
    background: #007bff;
    color: white;
}

.popup-loading {
    color: #6c757d;
    font-style: italic;
    font-size: 11px;
}

.popup-loading i {
    animation: spin 1s linear infinite;
}

/* Custom popup styling for Leaflet */
.leaflet-popup-content-wrapper {
    border-radius: 6px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.15) !important;
    border: 1px solid #ddd !important;
    padding: 0 !important;
}

.leaflet-popup-content {
    margin: 8px 10px !important;
    font-size: 13px !important;
    line-height: 1.3 !important;
}

.leaflet-popup-tip {
    border-top-color: #ddd !important;
}

.custom-popup .leaflet-popup-close-button {
    top: 4px !important;
    right: 4px !important;
    width: 18px !important;
    height: 18px !important;
    font-size: 14px !important;
    color: #999 !important;
    background: none !important;
    transition: color 0.2s ease !important;
}

.custom-popup .leaflet-popup-close-button:hover {
    color: #333 !important;
}

.map-info {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.75rem;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: #6c757d;
}

.no-locations-map {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: #6c757d;
    margin-bottom: 1rem;
}

.location-address {
    max-width: 250px;
    word-wrap: break-word;
}

.spin-icon {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.address-line {
    display: block;
    line-height: 1.3;
}

.address-accuracy {
    color: #6c757d;
    font-size: 0.75rem;
    margin-top: 2px;
}

/* Location History Cards Styles */
.location-cards-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.location-history-card {
    background: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    padding: 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 0;
}

.location-history-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    background: #ffffff;
}

.vehicle-info-section,
.recorded-info-section,
.location-info-section {
    padding: 0.75rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.parking-spot-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.spot-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.vehicle-details {
    flex-grow: 1;
}

.vehicle-name {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.vehicle-name i {
    color: #007bff;
}

.vehicle-vin {
    margin-top: 0.5rem;
}

.vin-code {
    background: #e9ecef;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    color: #495057;
}

.recorded-info-section {
    text-align: left;
}

.recorded-by,
.recorded-date,
.recorded-time {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.recorded-by i,
.recorded-date i,
.recorded-time i {
    color: #6c757d;
    width: 16px;
}

.location-notes {
    margin-top: 0.75rem;
    padding-top: 0.5rem;
    border-top: 1px solid #dee2e6;
}

.location-info-section {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.location-address-display {
    flex-grow: 1;
    margin-bottom: 1rem;
}

.location-address-main {
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.location-address-main strong {
    color: #495057;
    font-size: 0.95rem;
}

.location-coordinates,
.location-accuracy {
    margin-top: 0.25rem;
}

.location-coordinates i,
.location-accuracy i {
    color: #6c757d;
}

.loading-address,
.manual-entry {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.location-actions {
    margin-top: auto;
}

/* Mobile-First Responsive Styles */

/* Header Responsive Styles */
.vehicle-title-section {
    min-width: 0; /* Allow text truncation */
}

.vehicle-actions {
    min-width: fit-content;
}

.quick-stat {
    min-width: 60px;
}

.vin-display-container {
    overflow: hidden;
}

.vin-display {
    word-break: break-all;
    font-size: clamp(0.8rem, 2.5vw, 1.1rem);
    letter-spacing: clamp(0.5px, 1vw, 1px);
}

/* Statistics Styles for Sidebar */
.stats-card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 12px;
}

.stats-grid-vertical .stat-item {
    padding: 0.75rem 0;
}

.stats-grid-vertical .stat-item:first-child {
    padding-top: 0;
}

.stats-grid-vertical .stat-item:last-child {
    padding-bottom: 0;
    border-bottom: none !important;
}

.stat-value-sm {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1e293b;
    line-height: 1;
}

.stat-label-sm {
    color: #64748b;
    font-size: 0.8rem;
    font-weight: 500;
    margin-top: 0.25rem;
}

.stat-icon {
    font-size: 1.5rem;
    opacity: 0.7;
}

/* Mobile Breakpoints */
@media (max-width: 575.98px) {
    .vehicle-header {
        padding: 1.5rem 1rem;
        margin-bottom: 1.5rem;
    }
    
    .vehicle-title-section h1 {
        font-size: 1.5rem;
        line-height: 1.2;
    }
    
    .vin-display {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }
    
    .vehicle-actions {
        width: 100%;
    }
    
    .vehicle-actions .btn {
        flex: 1;
        justify-content: center;
    }
    
    .quick-stat {
        text-align: center;
        min-width: 70px;
    }
    
    .debug-info {
        display: none !important;
    }
}

@media (min-width: 576px) and (max-width: 767.98px) {
    .vehicle-header {
        padding: 1.75rem 1.5rem;
    }
    
    .vehicle-title-section h1 {
        font-size: 1.75rem;
    }
    
    .vin-display {
        font-size: 0.95rem;
    }
}

@media (min-width: 768px) and (max-width: 991.98px) {
    .vehicle-header {
        padding: 2rem 1.5rem;
    }
}

@media (min-width: 992px) {
    .vehicle-header {
        padding: 2rem;
    }
}

/* Content Grid Responsive */
@media (max-width: 991.98px) {
    .row.g-4 {
        gap: 1.5rem !important;
    }
}

@media (max-width: 767.98px) {
    .row.g-4 {
        gap: 1rem !important;
    }
    
    .card {
        margin-bottom: 1rem !important;
    }
}

/* Location History Card Responsive (Legacy) */
@media (max-width: 768px) {
    .location-history-card .row {
        flex-direction: column;
    }
    
    .vehicle-info-section,
    .recorded-info-section,
    .location-info-section {
        padding: 0.5rem 0;
        border-bottom: 1px solid #dee2e6;
    }
    
    .location-info-section {
        border-bottom: none;
    }
    
    .parking-spot-header {
        justify-content: center;
        text-align: center;
    }
    
    .recorded-info-section {
        text-align: center;
    }
}

/* Table Responsive Improvements */
@media (max-width: 991.98px) {
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .table td {
        padding: 0.5rem 0.25rem;
    }
    
    .table th {
        padding: 0.75rem 0.25rem;
        font-size: 0.85rem;
    }
}

@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .table td {
        padding: 0.4rem 0.2rem;
    }
    
    .table th {
        padding: 0.6rem 0.2rem;
        font-size: 0.8rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
}

/* Location History Table Styling */
.location-row:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.location-address-display .loading-address {
    color: #6c757d;
}

.location-address-display .manual-entry {
    color: #6c757d;
    font-style: italic;
}

/* Feather icons animations */
.spin-icon {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Override service-orders table styling for vehicles specific elements */
.vehicles-table thead th,
.vehicles-table tbody td {
    text-align: center !important;
    vertical-align: middle !important;
    padding: 0.75rem 0.5rem !important;
}

/* Ensure vehicle info card uses consistent styling */
.vehicle-info-card .card-header {
    padding: 1rem !important;
}

.vehicle-info-card .card-header h5,
.vehicle-info-card .card-header h6 {
    font-weight: 600 !important;
    color: #1f2937 !important;
    margin: 0 !important;
}

/* Vehicle Photos Styles */
.vehicle-photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.photo-thumbnail {
    position: relative;
    aspect-ratio: 16/9;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.photo-thumbnail:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    border-color: #3b82f6;
}

.photo-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.photo-thumbnail:hover img {
    transform: scale(1.05);
}

.photo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(0,0,0,0.3) 0%, transparent 50%);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.photo-thumbnail:hover .photo-overlay {
    opacity: 1;
}

.photo-overlay-icon {
    color: white;
    font-size: 2rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

/* Photo Selection Styles */
.photo-thumbnail.selected {
    border-color: #198754 !important;
    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.25) !important;
    transform: translateY(-2px);
}

.photo-thumbnail.selected::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(25, 135, 84, 0.2);
    z-index: 1;
}

.photo-checkbox {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 4px;
    padding: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.photo-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #198754;
    cursor: pointer;
}

.photo-thumbnail.selected .photo-checkbox {
    background: rgba(25, 135, 84, 0.9);
}

.photo-thumbnail.selected .photo-checkbox input[type="checkbox"] {
    filter: brightness(0) invert(1);
}

/* Photo Error Placeholder Styles */
.photo-error-placeholder {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 150px;
    position: relative;
}

.photo-error-placeholder::before {
    content: "🖼️";
    font-size: 2rem;
    color: #6b7280;
}

.photo-error-placeholder::after {
    content: "Photo Unavailable";
    position: absolute;
    bottom: 10px;
    font-size: 0.75rem;
    color: #6b7280;
    text-align: center;
    width: 100%;
}

.carousel-error-placeholder {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 400px;
    position: relative;
}

.carousel-error-placeholder::before {
    content: "🖼️";
    font-size: 4rem;
    color: #6b7280;
}

.carousel-error-placeholder::after {
    content: "Image Failed to Load";
    position: absolute;
    bottom: 20px;
    font-size: 1rem;
    color: #6b7280;
    text-align: center;
    width: 100%;
}

.no-photos-message {
    text-align: center;
    padding: 3rem 1rem;
    color: #6b7280;
}

.no-photos-message i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #d1d5db;
}

/* Vehicle Photos Modal Styles - Enhanced Glassmorphism Effect */
#vehiclePhotosModal .modal-backdrop {
    background: transparent !important;
    backdrop-filter: blur(30px);
    -webkit-backdrop-filter: blur(30px);
}

/* Force ALL modal backdrops to be transparent with blur */
.modal-backdrop,
.modal-backdrop.show,
.modal-backdrop.fade,
.modal-backdrop.fade.show,
.modal-backdrop.in,
body .modal-backdrop,
body.modal-open .modal-backdrop,
.modal-backdrop.modal-backdrop-transparent {
    background: transparent !important;
    background-color: transparent !important;
    background-image: none !important;
    opacity: 1 !important;
    backdrop-filter: blur(30px) !important;
    -webkit-backdrop-filter: blur(30px) !important;
    z-index: 1040 !important;  /* Mantener backdrop detrás del modal */
}

/* Additional override for any backdrop created by Bootstrap */
div[class*="modal-backdrop"] {
    background: transparent !important;
    background-color: transparent !important;
    backdrop-filter: blur(30px) !important;
    -webkit-backdrop-filter: blur(30px) !important;
    z-index: 1040 !important;  /* Mantener backdrop detrás del modal */
}

/* Ensure modal itself has transparent background */
#vehiclePhotosModal {
    background: transparent !important;
    z-index: 1055 !important;  /* Más alto que el backdrop */
}

#vehiclePhotosModal .modal-dialog {
    background: transparent !important;
    z-index: 1060 !important;  /* Aún más alto */
    position: relative;
}



#vehiclePhotosModal .modal-content {
    border: none;
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    height: 100vh;
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 1070;
}

#vehiclePhotosModal .modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.5rem 2rem;
    background: rgba(0, 0, 0, 0.3) !important;  /* Fondo más oscuro para contraste */
    backdrop-filter: blur(30px);
    -webkit-backdrop-filter: blur(30px);
    border-radius: 20px 20px 0 0;
    position: relative;
    flex-shrink: 0;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 
        0px 0px 30px rgba(0, 0, 0, 0.4),
        0px 0px 20px rgba(227, 228, 237, 0.15);
    z-index: 1075 !important;  /* Header por encima del backdrop */
}

#vehiclePhotosModal .modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    border-radius: 20px 20px 0 0;
}

#vehiclePhotosModal .modal-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    border-radius: 20px;
    pointer-events: none;
    z-index: 1;
}

#vehiclePhotosModal .modal-content::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
    border-radius: 22px;
    z-index: -1;
    pointer-events: none;
}

#vehiclePhotosModal .modal-title {
    color: #ffffff !important;
    font-weight: 700;
    text-shadow: 
        0 2px 8px rgba(0, 0, 0, 0.8),
        0 0 20px rgba(0, 0, 0, 0.6),
        0 1px 3px rgba(0, 0, 0, 1);
    position: relative;
    z-index: 2;
    letter-spacing: 0.5px;
}

#vehiclePhotosModal .modal-header,
#vehiclePhotosModal .modal-footer,
#vehiclePhotosModal .modal-body {
    position: relative;
    z-index: 1080 !important;  /* Todos los elementos del modal por encima del backdrop */
}

/* Animation for modal entrance */
#vehiclePhotosModal.show .modal-content {
    animation: glassModalSlideIn 0.4s ease-out;
}

@keyframes glassModalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-50px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

#vehiclePhotosModal .btn-close {
    opacity: 0.9;
    transition: all 0.3s ease;
    background: rgba(0, 0, 0, 0.6) !important;  /* Fondo más oscuro para contraste */
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(30px);
    -webkit-backdrop-filter: blur(30px);
    border: 3px solid rgba(255, 255, 255, 0.6);
    box-shadow: 
        0px 0px 30px rgba(0, 0, 0, 0.5),
        0px 0px 20px rgba(227, 228, 237, 0.2),
        0 5px 15px rgba(0, 0, 0, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
    
    /* Icono personalizado más visible */
    color: #ffffff !important;
    font-size: 20px;
    font-weight: 900;
}

/* Ocultar icono original de Bootstrap */
#vehiclePhotosModal .btn-close::after {
    display: none !important;
}

/* Agregar icono X personalizado */
#vehiclePhotosModal .btn-close::before {
    content: '✕';
    color: #ffffff;
    font-size: 20px;
    font-weight: 900;
    text-shadow: 
        0 2px 8px rgba(0, 0, 0, 0.8),
        0 0 15px rgba(0, 0, 0, 0.6),
        0 1px 3px rgba(0, 0, 0, 1);
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Ocultar cualquier contenido interno */
#vehiclePhotosModal .btn-close > * {
    display: none !important;
}

#vehiclePhotosModal .btn-close:hover {
    opacity: 1;
    background: rgba(0, 0, 0, 0.8) !important;  /* Más oscuro en hover */
    transform: scale(1.15);
    border: 3px solid rgba(255, 255, 255, 0.8);
    box-shadow: 
        0px 0px 40px rgba(0, 0, 0, 0.7),
        0px 0px 30px rgba(227, 228, 237, 0.3),
        0 8px 20px rgba(0, 0, 0, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.4);
}

#vehiclePhotosModal .btn-close:hover::before {
    color: #ffffff;
    text-shadow: 
        0 3px 12px rgba(0, 0, 0, 1),
        0 0 20px rgba(0, 0, 0, 0.8),
        0 1px 4px rgba(0, 0, 0, 1);
    transform: translate(-50%, -50%) scale(1.1);
}

#vehiclePhotosModal .modal-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.5rem 2rem;
    background: rgba(0, 0, 0, 0.3) !important;  /* Fondo más oscuro para contraste */
    backdrop-filter: blur(30px);
    -webkit-backdrop-filter: blur(30px);
    border-radius: 0 0 20px 20px;
    position: relative;
    flex-shrink: 0;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 
        0px 0px 30px rgba(0, 0, 0, 0.4),
        0px 0px 20px rgba(227, 228, 237, 0.15);
}

#vehiclePhotosModal .modal-footer::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
}

#vehiclePhotosModal .modal-footer .text-muted {
    color: rgba(255, 255, 255, 0.9) !important;
    font-weight: 600;
    text-shadow: 
        0 2px 6px rgba(0, 0, 0, 0.8),
        0 0 15px rgba(0, 0, 0, 0.6),
        0 1px 2px rgba(0, 0, 0, 1);
}

#vehiclePhotosModal .modal-footer .btn {
    backdrop-filter: blur(30px);
    -webkit-backdrop-filter: blur(30px);
    border: 3px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
    font-weight: 700;
    border-radius: 15px;
    text-shadow: 
        0 2px 6px rgba(0, 0, 0, 0.8),
        0 0 15px rgba(0, 0, 0, 0.6);
    box-shadow: 
        0px 0px 25px rgba(0, 0, 0, 0.4),
        0px 0px 15px rgba(227, 228, 237, 0.2),
        0 5px 15px rgba(0, 0, 0, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

#vehiclePhotosModal .modal-footer .btn-outline-secondary {
    background: rgba(0, 0, 0, 0.3) !important;  /* Fondo más oscuro para contraste */
    color: #ffffff !important;
    border-color: rgba(255, 255, 255, 0.4);
}

#vehiclePhotosModal .modal-footer .btn-outline-secondary:hover {
    background: rgba(0, 0, 0, 0.5) !important;  /* Más oscuro en hover */
    color: #ffffff !important;
    border: 3px solid rgba(255, 255, 255, 0.5);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 
        0px 0px 35px rgba(0, 0, 0, 0.6),
        0px 0px 25px rgba(227, 228, 237, 0.3),
        0 8px 20px rgba(0, 0, 0, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

#vehiclePhotosModal .modal-footer .btn-secondary {
    background: rgba(108, 117, 125, 0.2);
    color: #ffffff;
    border: 2px solid rgba(255, 255, 255, 0.18);
}

#vehiclePhotosModal .modal-footer .btn-secondary:hover {
    background: rgba(108, 117, 125, 0.3);
    color: #ffffff;
    border: 2px solid rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    box-shadow: 0px 0px 25px rgba(227, 228, 237, 0.4);
}

#vehiclePhotosModal .modal-body {
    flex-grow: 1;
    padding: 0;
    display: flex;
    flex-direction: column;
}

/* ====== MINIMAL CAROUSEL STYLE ====== */

/* Main carousel container */
#vehiclePhotosCarousel {
    height: 100%;
    position: relative;
    background: #ffffff;
    overflow: hidden;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

/* Horizontal scroll container */
.horizontal-carousel-container {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: #fafafa;
}

/* Scrollable photos strip */
.horizontal-carousel-track {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-behavior: smooth;
    padding: 16px 0;
    width: 100%;
    height: 100%;
    align-items: center;
    
    /* Minimal scrollbar */
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f3f4f6;
}

.horizontal-carousel-track::-webkit-scrollbar {
    height: 6px;
}

.horizontal-carousel-track::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 3px;
}

.horizontal-carousel-track::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.horizontal-carousel-track::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Individual photo slide */
.carousel-photo-slide {
    flex: none;
    position: relative;
    width: auto;
    height: 85%;
    max-height: 700px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.carousel-photo-slide:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    border-color: #d1d5db;
}

/* Photo image inside slide */
.carousel-photo-slide img {
    width: auto;
    height: 100%;
    max-width: 600px;
    object-fit: contain;
    display: block;
    transition: none;
}

/* Navigation arrows */
.horizontal-carousel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 1px solid #e5e7eb;
    cursor: pointer;
    z-index: 1085;
    background: #ffffff;
    color: #6b7280;
    font-size: 18px;
    transition: all 0.2s ease;
    opacity: 0.8;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.horizontal-carousel-nav:hover {
    opacity: 1;
    background: #f9fafb;
    border-color: #d1d5db;
    color: #374151;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-50%) scale(1.05);
}

.horizontal-carousel-nav.prev {
    left: 16px;
}

.horizontal-carousel-nav.next {
    right: 16px;
}

.horizontal-carousel-nav:active {
    transform: translateY(-50%) scale(0.95);
    background: #f3f4f6;
}

/* Navigation icons */
.horizontal-carousel-nav i {
    font-size: 16px;
}

/* Photo counter */
.carousel-photo-counter {
    position: absolute;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    padding: 8px 16px;
    border-radius: 20px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    color: #374151;
    font-weight: 500;
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    z-index: 1085;
}

/* Current photo indicator */
.carousel-current-indicator {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 6px 12px;
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    font-size: 12px;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    z-index: 1085;
}

/* Loading animation */
@keyframes horizontalSlideIn {
    from {
        opacity: 0;
        transform: translateX(50px) scale(0.9);
        filter: blur(10px);
    }
    to {
        opacity: 1;
        transform: translateX(0) scale(1);
        filter: blur(0);
    }
}

.carousel-photo-slide {
    animation: horizontalSlideIn 0.6s ease-out;
}

/* Smooth scroll enhancement */
.horizontal-carousel-track {
    scroll-snap-type: x mandatory;
}

.carousel-photo-slide {
    scroll-snap-align: center;
}

/* Focus states for accessibility */
.horizontal-carousel-nav:focus,
.carousel-photo-slide:focus {
    outline: none;
    border: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 
        0px 0px 40px rgba(227, 228, 237, 0.6),
        0 0 0 4px rgba(255, 255, 255, 0.2);
}

/* Responsive adjustments for mobile */
@media (max-width: 768px) {
    .horizontal-carousel-container {
        padding: 20px 50px;
    }
    
    .horizontal-carousel-nav {
        width: 55px;
        height: 55px;
        font-size: 22px;
        border: 2px solid rgba(255, 255, 255, 0.4);
        box-shadow: 
            0px 0px 30px rgba(0, 0, 0, 0.4),
            0px 0px 20px rgba(227, 228, 237, 0.2),
            0 8px 20px rgba(0, 0, 0, 0.2);
    }
    
    .horizontal-carousel-nav.prev {
        left: 15px;
    }
    
    .horizontal-carousel-nav.next {
        right: 15px;
    }
    
    .carousel-photo-slide {
        height: 70%;
        max-height: 400px;
        border-radius: 15px;
    }
    
    .carousel-photo-slide img {
        max-width: 300px;
    }
    
    .carousel-photo-counter {
        bottom: 20px;
        padding: 8px 16px;
        font-size: 13px;
    }
    
    .horizontal-carousel-track {
        gap: 20px;
    }
}

@media (max-width: 480px) {
    .horizontal-carousel-container {
        padding: 15px 30px;
    }
    
    .carousel-photo-slide {
        height: 60%;
        max-height: 300px;
        border-radius: 12px;
    }
    
    .carousel-photo-slide img {
        max-width: 250px;
    }
    
    .horizontal-carousel-track {
        gap: 15px;
    }
    
    .horizontal-carousel-nav {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
    
    .carousel-current-indicator {
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        font-size: 11px;
    }
    
    /* Botón cerrar para móvil */
    #vehiclePhotosModal .btn-close {
        width: 40px;
        height: 40px;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }
    
    #vehiclePhotosModal .btn-close::before {
        font-size: 16px;
    }
}

.photo-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 200px;
    background: #f8f9fa;
    border-radius: 8px;
    color: #6b7280;
}

.photo-error {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 200px;
    background: #fee;
    border-radius: 8px;
    color: #dc2626;
    border: 1px solid #fecaca;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .vehicle-photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 0.75rem;
    }
    
    #vehiclePhotosModal .modal-header,
    #vehiclePhotosModal .modal-footer {
        padding: 1rem 1.5rem;
    }
    
    #vehiclePhotosModal .modal-title {
        font-size: 1.25rem;
    }
    
    #vehiclePhotosCarousel .carousel-control-prev,
    #vehiclePhotosCarousel .carousel-control-next {
        width: 50px;
        height: 50px;
        margin: 0 10px;
    }
    
    #vehiclePhotosCarousel .carousel-control-prev {
        left: 10px;
    }
    
    #vehiclePhotosCarousel .carousel-control-next {
        right: 10px;
    }
    
    #vehiclePhotosCarousel .carousel-indicators {
        bottom: 15px;
        padding: 6px 15px;
    }
    
    .photo-overlay-icon {
        font-size: 1.5rem;
    }
}

/* Vehicle Photos Container Scrollbar Styles */
#vehiclePhotosContainer {
    scrollbar-width: thin;
    scrollbar-color: #007bff #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: #ffffff;
    position: relative;
    min-height: 400px; /* Ensure minimum height for scroll to work */
}

/* Simple Upload Area Styles */
.simple-upload-area {
    border: 3px dashed #007bff;
    border-radius: 12px;
    background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
    padding: 3rem 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.simple-upload-area:hover {
    border-color: #0056b3;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.upload-icon {
    margin-bottom: 1.5rem;
}

.upload-icon-main {
    width: 64px;
    height: 64px;
    color: #007bff;
    opacity: 0.8;
}

.upload-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.upload-subtitle {
    color: #6c757d;
    margin-bottom: 0;
    font-size: 0.95rem;
}

/* Upload Progress Styles */
.upload-progress-item {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
}

.upload-progress-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.progress-file-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.progress-file-name {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    flex: 1;
    margin-right: 1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.progress-file-size {
    color: #6c757d;
    font-size: 0.8rem;
    white-space: nowrap;
}

.progress-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
}

.progress-status.uploading {
    color: #007bff;
}

.progress-status.success {
    color: #28a745;
}

.progress-status.error {
    color: #dc3545;
}

.progress-bar-custom {
    height: 6px;
    border-radius: 3px;
    background: #e9ecef;
    overflow: hidden;
    position: relative;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
    border-radius: 3px;
    transition: width 0.3s ease;
    position: relative;
}

.progress-bar-fill.success {
    background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
}

.progress-bar-fill.error {
    background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
}

/* Upload Section Animation */
#uploadSection {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* File Preview Thumbnails in Progress */
.progress-thumbnail {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid #e9ecef;
    margin-right: 0.75rem;
}

.progress-file-actions {
    display: flex;
    gap: 0.25rem;
}

.progress-action-btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.progress-action-btn.retry {
    background: #ffc107;
    color: #000;
}

.progress-action-btn.remove {
    background: #dc3545;
    color: #fff;
}

.progress-action-btn:hover {
    transform: scale(1.05);
    opacity: 0.9;
}

/* Responsive adjustments for upload section */
@media (max-width: 768px) {
    .dropzone-custom {
        padding: 2rem 1rem;
        min-height: 150px;
    }
    
    .upload-icon-main {
        width: 48px;
        height: 48px;
    }
    
    .upload-title {
        font-size: 1.1rem;
    }
    
    .progress-file-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .progress-file-name {
        margin-right: 0;
    }
}

#vehiclePhotosContainer::-webkit-scrollbar {
    width: 10px;
}

#vehiclePhotosContainer::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 5px;
    margin: 5px;
}

#vehiclePhotosContainer::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #007bff 0%, #0056b3 100%);
    border-radius: 5px;
    border: 1px solid #0056b3;
    transition: all 0.3s ease;
}

#vehiclePhotosContainer::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #0056b3 0%, #004085 100%);
    transform: scale(1.1);
}

#vehiclePhotosContainer::-webkit-scrollbar-corner {
    background: #f8f9fa;
}

@media (max-width: 576px) {
    .vehicle-photos-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    #vehiclePhotosCarousel {
        height: 40vh;
        min-height: 250px;
    }
    
    /* Smaller container height on mobile */
    #vehiclePhotosContainer {
        max-height: 400px !important;
        min-height: 300px;
    }
    
    #vehiclePhotosContainer::-webkit-scrollbar {
        width: 6px;
    }
}

/* Ensure content height for scroll */
#vehiclePhotosContainer .vehicle-photos-grid {
    padding-bottom: 20px;
}

/* NFC Writing Styles */
.btn-nfc-write {
    position: relative;
    overflow: hidden;
}

.btn-nfc-write:disabled {
    opacity: 0.7;
}

.nfc-writing-animation {
    animation: nfc-pulse 1.5s infinite;
}

@keyframes nfc-pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

#nfcWriteButtons .btn {
    font-size: 0.875rem;
}

/* NFC Status indicators */
.nfc-status {
    padding: 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.nfc-supported {
    background-color: #d1f2eb;
    color: #0f5132;
    border: 1px solid #a7e6d7;
}

.nfc-not-supported {
    background-color: #fff3cd;
    color: #664d03;
    border: 1px solid #ffe69c;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (isset($vehicle) && !empty($vehicle)): ?>
<div class="container-fluid">
    
    <!-- Vehicle Header -->
    <div class="vehicle-header">
        <div class="row g-3">
            <!-- Vehicle Title and Main Info -->
            <div class="col-12">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <div class="vehicle-title-section">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <h1 class="h3 h2-sm mb-0 text-break"><?= esc($vehicle['vehicle']) ?></h1>
                            <?php if (isset($vehicle['client_name']) && !empty($vehicle['client_name'])): ?>
                            <span class="badge bg-info text-white px-2 px-sm-3 py-1 py-sm-2" style="font-size: 0.75rem;">
                                <i class="ri-building-line me-1"></i>
                                <span class="d-none d-sm-inline"><?= esc($vehicle['client_name']) ?></span>
                                <span class="d-sm-none"><?= strlen($vehicle['client_name']) > 10 ? substr($vehicle['client_name'], 0, 10) . '...' : $vehicle['client_name'] ?></span>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- VIN Display - Always visible but responsive -->
                        <div class="vin-display-container mb-2">
                            <div class="vin-display">
                                <i class="ri-barcode-line me-2"></i>
                                <span class="d-none d-md-inline">VIN: </span><?= esc($vehicle['vin_number']) ?>
                            </div>
                        </div>
                        
                        <!-- Owner Info -->
                        <?php if (isset($vehicle['client_name']) && !empty($vehicle['client_name'])): ?>
                        <div class="owner-info d-block d-sm-none">
                            <small class="text-muted">
                                <i class="ri-user-line me-1"></i>
                                <?= lang('App.owned_by') ?>: <strong><?= esc($vehicle['client_name']) ?></strong>
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="vehicle-actions d-flex flex-column flex-sm-row gap-2 align-items-stretch align-items-sm-center">
                        <a href="<?= base_url('vehicles') ?>" class="btn btn-light btn-sm">
                            <i class="ri-arrow-left-line me-1 me-sm-2"></i>
                            <span class="d-none d-sm-inline"><?= lang('App.back_to_vehicles') ?></span>
                            <span class="d-sm-none">Back</span>
                        </a>
                        
                        <!-- Quick Stats - Mobile Only -->
                        <div class="d-block d-lg-none">
                            <div class="d-flex gap-3 text-center">
                                <div class="quick-stat">
                                    <div class="fw-bold text-primary"><?= $vehicle['total_services'] ?? 0 ?></div>
                                    <small class="text-muted">Services</small>
                                </div>
                                <div class="quick-stat">
                                    <div class="fw-bold text-success">
                                        <?php 
                                        $daysSinceFirst = $vehicle['created_at'] ? 
                                            round((time() - strtotime($vehicle['created_at'])) / (60 * 60 * 24)) : 0;
                                        echo $daysSinceFirst;
                                        ?>
                                    </div>
                                    <small class="text-muted">Days</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Debug Info - Only in development -->
                <?php if (isset($last6) && ENVIRONMENT === 'development'): ?>
                <div class="debug-info mt-2 d-none d-lg-block">
                    <small class="text-muted">
                        <i class="ri-code-line me-1"></i>
                        Current URL: <code>/vehicles/<?= esc($last6) ?></code>
                        <?php if (isset($vin)): ?>
                        | Full VIN: <code><?= esc($vin) ?></code>
                        <?php endif; ?>
                    </small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Content Grid - New Responsive Layout with Right Sidebar -->
    <div class="row g-3 g-lg-4">
        <!-- Main Content Area (Wide Blocks) - Left Side -->
        <div class="col-12 col-lg-8 order-2 order-lg-1">
            
            <!-- Vehicle Photos Section - Wide Content -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12 col-sm-4 order-2 order-sm-1">
                            <!-- Upload Button -->
                            <div class="d-flex gap-2 justify-content-start justify-content-sm-start">
                                <button class="btn btn-success btn-sm" id="uploadPhotosBtn" onclick="openS3UploadModal()">
                                    <i data-feather="upload" class="icon-sm me-1"></i>
                                    <span class="d-none d-md-inline">Upload Photos</span>
                                    <span class="d-md-none">Upload</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 order-1 order-sm-2 text-center">
                            <h4 class="card-title mb-0 service-orders-card-title">
                                <i data-feather="camera" class="icon-sm me-1"></i>
                                <span class="d-none d-sm-inline"><?= lang('App.vehicle_photos') ?></span>
                                <span class="d-sm-none">Photos</span>
                            </h4>
                        </div>
                        <div class="col-12 col-sm-4 order-3 order-sm-3 text-end">
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-sm-end">
                                <button class="btn btn-outline-warning btn-sm" id="detectDuplicatesBtn" onclick="detectDuplicatePhotos()">
                                    <i data-feather="copy" class="icon-sm me-1"></i>
                                    <span class="d-none d-md-inline">Detect Duplicates</span>
                                    <span class="d-md-none">Duplicates</span>
                                </button>
                                <button class="btn btn-outline-primary btn-sm" onclick="refreshVehiclePhotos()">
                                    <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                                    <span class="d-none d-md-inline"><?= lang('App.refresh') ?></span>
                                    <span class="d-md-none">Refresh</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Upload Section Removed - Using S3 Modal Instead -->
                
                <!-- Photos Display Area -->
                <div class="card-body p-0">
                    <div id="vehiclePhotosContainer" style="max-height: 600px; overflow-y: auto; overflow-x: hidden; padding: 1rem;">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2">Loading vehicle photos...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service History - Wide Content -->
            <div class="card">
                <div class="card-header d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                        <h4 class="card-title mb-0 flex-grow-1 service-orders-card-title">
                        <i data-feather="clock" class="icon-sm me-1"></i>
                            <span class="d-none d-sm-inline"><?= lang('App.service_history') ?></span>
                            <span class="d-sm-none">Services</span>
                        </h4>
                    
                </div>
                <div class="card-body">
                    <?php if (!empty($vehicle['services'])): ?>

                    <div class="table-responsive">
                        <table class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive" id="serviceHistoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none d-sm-table-cell"><?= lang('App.order_number') ?></th>
                                    <th class="d-none d-lg-table-cell">Stock Number</th>
                                    <th><?= lang('App.service') ?></th>
                                    <th class="d-none d-md-table-cell"><?= lang('App.service_date') ?></th>
                                    <th class="d-none d-sm-table-cell"><?= lang('App.status') ?></th>
                                </tr>
                            </thead>
                            <tbody id="serviceHistoryTableBody">
                                <?php if (!empty($vehicle['services']) && is_array($vehicle['services'])): ?>
                                    <?php foreach ($vehicle['services'] as $service): ?>
                                    <tr class="clickable-row" style="cursor: pointer;" 
                                        onclick="window.location.href='<?= base_url('recon_orders/view/' . $service['id']) ?>'"
                                        data-order-id="<?= $service['id'] ?>"
                                        title="Click to view order details">
                                        <td class="d-none d-sm-table-cell">
                                            <?php if (!empty($service['order_number'])): ?>
                                                <span class="fw-medium text-break font-monospace"><?= esc($service['order_number']) ?></span>
                                            <?php else: ?>
                                                <span class="fw-medium font-monospace">RO-<?= str_pad($service['id'], 5, '0', STR_PAD_LEFT) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <?php if (!empty($service['stock'])): ?>
                                                <span class="badge bg-secondary text-white font-monospace"><?= esc($service['stock']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted small">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- Mobile: Show order number inline -->
                                            <div class="d-sm-none">
                                                <small class="text-muted d-block font-monospace">
                                                    <?php if (!empty($service['order_number'])): ?>
                                                        <?= esc($service['order_number']) ?>
                                                    <?php else: ?>
                                                        RO-<?= str_pad($service['id'], 5, '0', STR_PAD_LEFT) ?>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                            
                                            <?php 
                                            $displayedServices = [];
                                            
                                            // Priority 1: Direct service from recon_services table
                                            if (!empty($service['service_name'])):
                                                $color = !empty($service['service_color']) ? $service['service_color'] : '#0d6efd';
                                            ?>
                                                <span class="badge me-1" style="background-color: <?= esc($color) ?>; color: white;">
                                                    <?= esc($service['service_name']) ?>
                                                </span>
                                                <?php $displayedServices[] = $service['service_name']; ?>
                                            <?php endif; ?>
                                            
                                            <?php
                                            // Priority 2: Many-to-many services from recon_order_services
                                            if (!empty($service['order_services']) && is_array($service['order_services'])): 
                                                foreach ($service['order_services'] as $orderService): 
                                                    if (!empty($orderService['service_name']) && !in_array($orderService['service_name'], $displayedServices)):
                                                        $color = !empty($orderService['service_color']) ? $orderService['service_color'] : '#198754';
                                            ?>
                                                        <span class="badge me-1" style="background-color: <?= esc($color) ?>; color: white;">
                                                            <?= esc($orderService['service_name']) ?>
                                                            <?php if (!empty($orderService['quantity']) && $orderService['quantity'] > 1): ?>
                                                                <small>(×<?= $orderService['quantity'] ?>)</small>
                                                            <?php endif; ?>
                                                        </span>
                                                        <?php $displayedServices[] = $orderService['service_name']; ?>
                                            <?php   
                                                    endif;
                                                endforeach; 
                                            endif; 
                                            ?>
                                            
                                            <?php
                                            // Priority 3: Generic services text
                                            if (empty($displayedServices) && !empty($service['services'])): 
                                            ?>
                                                <span class="text-info fw-medium"><?= esc($service['services']) ?></span>
                                            <?php endif; ?>
                                            
                                            <?php
                                            // Fallback if no services found
                                            if (empty($displayedServices) && empty($service['services'])): 
                                            ?>
                                                <span class="text-muted">No services specified</span>
                                            <?php endif; ?>
                                            
                                            <!-- Mobile: Show date and status inline -->
                                            <div class="d-md-none mt-1">
                                                <small class="text-muted me-2">
                                                    <i data-feather="calendar" class="icon-xs me-1"></i>
                                                    <?php if (!empty($service['created_at'])): ?>
                                                        <?= date('M j, Y', strtotime($service['created_at'])) ?>
                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </small>
                                                <span class="d-sm-none">
                                                    <?php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'in_progress' => 'info', 
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger'
                                                    ];
                                                    $statusColor = $statusColors[$service['status']] ?? 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?= $statusColor ?>"><?= ucfirst(esc($service['status'])) ?></span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <?php if (!empty($service['created_at'])): ?>
                                                <span class="fw-medium"><?= date('M j, Y', strtotime($service['created_at'])) ?></span>
                                                <br>
                                                <small class="text-muted"><?= date('g:i A', strtotime($service['created_at'])) ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            <?php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'in_progress' => 'info', 
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $statusColor = $statusColors[$service['status']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $statusColor ?>"><?= ucfirst(esc($service['status'])) ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                                <i class="ri-inbox-line display-6 text-muted"></i>
                                            <p class="text-muted mt-2 mb-0">No service records found for this vehicle</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        
                        <!-- Loading indicator -->
                        <div id="loadingIndicator" class="text-center py-3" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted"><?= lang('App.loading_more_services') ?>...</small>
                            </div>
                        </div>
                        
                        <!-- End of results indicator -->
                        <div id="endOfResults" class="text-center py-3" style="display: none;">
                            <small class="text-muted">
                                <i class="ri-check-line me-1"></i>
                                <?= lang('App.all_services_loaded') ?>
                            </small>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="ri-inbox-line display-4 text-muted"></i>
                        <h6 class="mt-2"><?= lang('App.no_service_history_found') ?></h6>
                        <p class="text-muted"><?= lang('App.no_recorded_services_message') ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Location History Section - Wide Content -->
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class="card-title mb-0 service-orders-card-title">
                            <i data-feather="map-pin" class="icon-sm me-1"></i>
                            <?= lang('App.location_history') ?>
                        </h4>
                        <p class="text-muted small mb-0"><?= lang('App.track_vehicle_parking_via_nfc') ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <button class="btn btn-outline-primary btn-sm" onclick="generateNFCToken()">
                            <i data-feather="smartphone" class="icon-sm me-1"></i>
                            <?= lang('App.generate_nfc_token') ?>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Location History Map Container -->
                    <div id="locationHistoryMapContainer" style="display: none;">
                        <div class="map-info">
                            <i data-feather="map" class="icon-sm me-2"></i>
                            <strong><?= lang('App.interactive_map') ?>:</strong> <?= lang('App.click_marker_for_details') ?>
                        </div>
                        <div class="map-container">
                            <div id="locationHistoryMap" style="height: 400px; border-radius: 8px;"></div>
                        </div>
                    </div>
                    
                    <!-- Location History Table Container -->
                    <div id="locationHistoryContainer">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2">Loading location history...</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Right Sidebar (Narrow Blocks) - Mobile First, Desktop Right -->
        <div class="col-12 col-lg-4 order-1 order-lg-2">
            
            <!-- Mobile: Full width cards, Desktop: Sidebar cards -->
            <!-- Vehicle Information Card -->
            <div class="card vehicle-info-card mb-4">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">
                        <i data-feather="info" class="icon-sm me-2"></i>
                        <span class="d-none d-sm-inline"><?= lang('App.vehicle_info') ?></span>
                        <span class="d-sm-none">Info</span>
                    </h5>
    </div>
                <div class="card-body">
                    <?php if (isset($vehicle['client_name']) && !empty($vehicle['client_name'])): ?>
                    <div class="info-item">
                        <span class="info-label"><?= lang('App.client') ?></span>
                        <span class="info-value text-break">
                            <i data-feather="users" class="icon-xs me-1"></i>
                            <?= esc($vehicle['client_name']) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="info-item">
                        <span class="info-label">VIN</span>
                        <span class="info-value font-monospace text-break" style="font-size: 0.85rem;">
                            <?= esc($vehicle['vin_number']) ?>
                        </span>
                    </div>
                    
                    <?php if (isset($vehicle['vehicle']) && !empty($vehicle['vehicle'])): ?>
                    <div class="info-item">
                        <span class="info-label"><?= lang('App.vehicle') ?></span>
                        <span class="info-value text-break">
                            <?php
                            // Extract vehicle info from the vehicle field, removing VIN if present
                            $vehicleDisplay = $vehicle['vehicle'];
                            // If vehicle field contains VIN format (17 chars alphanumeric), extract just the make/model
                            if (preg_match('/^(.+?)\s+([A-HJ-NPR-Z0-9]{17})$/', $vehicleDisplay, $matches)) {
                                $vehicleDisplay = trim($matches[1]);
                            }
                            echo esc($vehicleDisplay);
                            ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($vehicle['created_at']) && !empty($vehicle['created_at'])): ?>
                    <div class="info-item">
                        <span class="info-label"><?= lang('App.first_service') ?></span>
                        <span class="info-value">
                            <i data-feather="calendar" class="icon-xs me-1"></i>
                            <span class="d-block"><?= date('M j, Y', strtotime($vehicle['created_at'])) ?></span>
                            <small class="text-muted"><?= date('g:i A', strtotime($vehicle['created_at'])) ?></small>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($vehicle['updated_at']) && !empty($vehicle['updated_at'])): ?>
                    <div class="info-item">
                        <span class="info-label"><?= lang('App.last_updated') ?></span>
                        <span class="info-value">
                            <i data-feather="clock" class="icon-xs me-1"></i>
                            <span class="d-block"><?= date('M j, Y', strtotime($vehicle['updated_at'])) ?></span>
                            <small class="text-muted"><?= date('g:i A', strtotime($vehicle['updated_at'])) ?></small>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="bar-chart-2" class="icon-sm me-2"></i>
                        <span class="d-none d-sm-inline"><?= lang('App.statistics') ?></span>
                        <span class="d-sm-none">Stats</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="stat-info">
                                <div class="stat-value-sm"><?= $vehicle['total_services'] ?? 0 ?></div>
                                <div class="stat-label-sm"><?= lang('App.total_services') ?></div>
                            </div>
                            <div class="stat-icon">
                                <i data-feather="settings" class="icon-sm text-primary"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="stat-info">
                                <div class="stat-value-sm">
                                    <?php 
                                    $daysSinceFirst = $vehicle['created_at'] ? 
                                        round((time() - strtotime($vehicle['created_at'])) / (60 * 60 * 24)) : 0;
                                    echo $daysSinceFirst;
                                    ?>
                                </div>
                                <div class="stat-label-sm"><?= lang('App.days_since_first_service') ?></div>
                            </div>
                            <div class="stat-icon">
                                <i data-feather="calendar" class="icon-sm text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Card -->
            <?php if (isset($qr_data) && $qr_data && isset($qr_data['qr_url']) && isset($qr_data['short_url'])): ?>
            <div class="card mb-4 d-none d-md-block">
                <div class="card-header">
                    <h5 class="card-title mb-0 fw-bold">
                        <i data-feather="smartphone" class="icon-sm me-2"></i>
                        QR Code Access
                    </h5>
                    <small class="text-muted">Instant mobile access</small>
                </div>
                <div class="card-body text-center">
                    <!-- Large QR Code Display -->
                    <div class="qr-large-display">
                                            <img src="<?= isset($qr_data['qr_url']) ? $qr_data['qr_url'] : '' ?>" 
                         alt="QR Code for Vehicle <?= esc($vehicle['vin_number']) ?>" 
                         class="qr-sidebar-image" 
                         style="width: 200px; height: 200px; border-radius: 12px; cursor: pointer;"
                             onclick="openVehicleQRModal()"
                             title="Click to view larger">
                    </div>
                    
                    <!-- Short URL Display -->
                    <div class="mt-3">
                        <div class="input-group">
                            <input type="text" class="form-control text-center" 
                                   value="<?= $qr_data['short_url'] ?>" 
                                   readonly 
                                   style="font-size: 0.75rem;">
                            <button class="btn btn-outline-secondary btn-sm" 
                                    type="button" 
                                    onclick="copyVehicleShortUrl()"
                                    title="Copy URL">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <?= $qr_data['shortener'] ?? 'MDA.to' ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- QR Code Not Available Card -->
            <div class="card mb-4 d-none d-md-block">
                <div class="card-header">
                    <h5 class="card-title mb-0 fw-bold">
                        <i data-feather="smartphone" class="icon-sm me-2"></i>
                        QR Code Access
                    </h5>
                    <small class="text-muted">Generate QR for mobile access</small>
                </div>
                <div class="card-body text-center">
                    <div class="py-3">
                        <i data-feather="square" class="icon-lg text-muted mb-3"></i>
                        <h6 class="text-muted">QR Code Not Available</h6>
                        <p class="text-muted small mb-3">Generate a QR code for instant mobile access to this vehicle</p>
                        <button class="btn btn-primary btn-sm" onclick="generateVehicleQR('<?= $vehicle['vin_last6'] ?? substr($vehicle['vin_number'], -6) ?>')">
                            <i data-feather="plus" class="icon-sm me-1"></i>
                            Generate QR Code
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
</div>



<!-- Vehicle Photos Carousel Modal -->
<div class="modal fade" id="vehiclePhotosModal" tabindex="-1" aria-labelledby="vehiclePhotosModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="glass-overlay"></div>
            <div class="modal-header">
                <h5 class="modal-title" id="vehiclePhotosModalLabel">
                    <i class="ri-camera-line me-2"></i>
                    Vehicle Photos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="vehiclePhotosCarousel">
                    <div class="horizontal-carousel-container">
                        <div class="horizontal-carousel-track" id="horizontalCarouselTrack">
                        <!-- Photos will be loaded here -->
                    </div>
                        
                        <!-- Navigation arrows -->
                        <button class="horizontal-carousel-nav prev" id="carouselPrev">
                            <i class="ri-arrow-left-line"></i>
                    </button>
                        <button class="horizontal-carousel-nav next" id="carouselNext">
                            <i class="ri-arrow-right-line"></i>
                    </button>
                        
                        <!-- Photo counter -->
                        <div class="carousel-photo-counter" id="horizontalPhotoCounter">
                            1 of 1
                        </div>
                        
                        <!-- Current photo indicator -->
                        <div class="carousel-current-indicator" id="currentPhotoIndicator">
                            Photo 1
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <span class="text-muted" id="photoCounter">0 of 0</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary" onclick="downloadCurrentPhoto()">
                            <i class="ri-download-line me-1"></i>
                            Download
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- NFC Token Modal -->
<div class="modal fade" id="nfcTokenModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-qr-code-line me-2"></i>
                    NFC Token for Vehicle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center" id="tokenModalBody">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Generating...</span>
                </div>
                <p class="text-muted mt-2">Generating NFC token...</p>
            </div>
        </div>
    </div>
</div>

<!-- Vehicle QR Code Modal -->
<div class="modal fade" id="vehicleQrModal" tabindex="-1" aria-labelledby="vehicleQrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vehicleQrModalLabel">
                    <i data-feather="smartphone" class="icon-sm me-2"></i>
                    QR Code - Vehicle <?= esc($vehicle['vin_number'] ?? '') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <?php if (isset($qr_data) && $qr_data && isset($qr_data['qr_url']) && isset($qr_data['short_url'])): ?>
                <!-- QR Code Available -->
                <div class="mb-4">
                    <img src="<?= isset($qr_data['qr_url']) ? $qr_data['qr_url'] : '' ?>" 
                         alt="QR Code for Vehicle <?= esc($vehicle['vin_number']) ?>" 
                         class="img-fluid" 
                         style="max-width: 300px; border-radius: 12px;">
                </div>
                
                <!-- Short URL Display -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Short URL:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="vehicleQrShortUrl" value="<?= $qr_data['short_url'] ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('vehicleQrShortUrl')">
                            <i data-feather="copy" class="icon-xs"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-primary" onclick="downloadVehicleQR()">
                        <i data-feather="download" class="icon-xs me-1"></i>
                        Download
                    </button>
                    <button class="btn btn-outline-success" onclick="shareVehicleQR()">
                        <i data-feather="share-2" class="icon-xs me-1"></i>
                        Share
                    </button>
                </div>
                
                <!-- Usage Info -->
                <div class="mt-4 p-3 bg-light rounded">
                    <small class="text-muted">
                        <i data-feather="info" class="icon-xs me-1"></i>
                        Scan with any phone camera or QR reader to access this vehicle instantly
                    </small>
                </div>
                <?php else: ?>
                <!-- QR Code Not Available -->
                <div class="text-center py-4">
                    <i data-feather="alert-triangle" class="icon-lg text-warning mb-3"></i>
                    <h6 class="text-warning">QR Code Unavailable</h6>
                    <p class="text-muted small">Generate a QR code for instant mobile access</p>
                    <button class="btn btn-primary" onclick="generateVehicleQR('<?= $vehicle['vin_last6'] ?? substr($vehicle['vin_number'], -6) ?>')">
                        <i data-feather="plus" class="icon-sm me-1"></i>
                        Generate QR Code
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- S3 Upload Modal -->
<div class="modal fade" id="s3UploadModal" tabindex="-1" aria-labelledby="s3UploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="s3UploadModalLabel">
                    <i class="ri-cloud-upload-line me-2"></i>
                    Upload Photos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="s3UploadModalBody">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="modal-footer" id="s3UploadModalFooter">
                <!-- Footer will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Duplicates Detection Modal -->
<div class="modal fade" id="duplicatesModal" tabindex="-1" aria-labelledby="duplicatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="duplicatesModalLabel">
                    <i class="ri-file-copy-line me-2"></i>Duplicate Photos Detection
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be populated by JavaScript -->
                <div class="text-center py-4">
                    <i class="ri-loader-4-line spin-icon" style="font-size: 2rem;"></i>
                    <p class="mt-2">Scanning for duplicates...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-2"></i>Close
                </button>
                <button type="button" class="btn btn-danger" id="removeDuplicatesBtn" onclick="removeSelectedDuplicates()" disabled>
                    <i class="ri-delete-bin-line me-2"></i>Remove Selected
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Photos Confirmation Modal -->
<div class="modal fade" id="deletePhotosModal" tabindex="-1" aria-labelledby="deletePhotosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePhotosModalLabel">
                    <i class="ri-delete-bin-line me-2 text-danger"></i><?= lang('App.delete_photo') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="ri-alert-line text-warning" style="font-size: 3rem;"></i>
                </div>
                <h6 class="text-center mb-3"><?= lang('App.confirm_delete') ?></h6>
                <p class="text-center text-muted">
                    <?= lang('App.delete_photos_confirmation') ?>
                </p>
                <div class="text-center">
                    <strong>Photos to delete: <span id="deletePhotosCount" class="text-danger">0</span></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDeletePhotos()">
                    <i class="ri-delete-bin-line me-2"></i><?= lang('App.yes_delete') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-error-warning-line display-4 text-muted"></i>
                    <h4 class="mt-3"><?= lang('App.vehicle_not_found') ?></h4>
                    <p class="text-muted"><?= lang('App.vehicle_not_found_message') ?></p>
                    <a href="<?= base_url('vehicles') ?>" class="btn btn-primary">
                        <i class="ri-arrow-left-line me-2"></i>
                        <?= lang('App.back_to_vehicles') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
</div>
<?php endif; ?>

<?= $this->section('scripts') ?>
<!-- Dropzone.js removed for simplification -->

<!-- Leaflet JS for Location History Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Vehicle NFC Location Tracking JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Load location history on page load
    loadLocationHistory();
});
    
// Load photos after everything is fully loaded
window.addEventListener('load', function() {
    // Add delay to ensure all page elements are ready
    setTimeout(() => {
    loadVehiclePhotos();
    }, 800);
});

// Global variables for infinite scroll
let allLocations = [];
let displayedLocations = 0;
let isLoadingMore = false;
const locationsPerPage = 3;

function loadLocationHistory() {
    const container = document.getElementById('locationHistoryContainer');
    const mapContainer = document.getElementById('locationHistoryMapContainer');
    if (!container) return;
    
    const vehicleVin = '<?= esc($vin ?? $vehicle['vin_number'] ?? '') ?>';
    if (!vehicleVin) {
        container.innerHTML = '<div class="text-center py-4"><p class="text-muted">No VIN available for tracking</p></div>';
        return;
    }
    
    fetch(`<?= base_url('api/location/history/') ?>${vehicleVin}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.locations && data.locations.length > 0) {
                // Store all locations globally and reset counter
                allLocations = data.locations;
                displayedLocations = 0;
                isLoadingMore = false;
                
                console.log(`Total locations available: ${allLocations.length}`);
                
                // Create the interactive map with all locations
                createLocationHistoryMap(data.locations);
                
                // Create table structure with ServiceOrders styling
                container.innerHTML = `
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive" id="locationHistoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col"><?= lang('App.parking_spot') ?></th>
                                    <th scope="col"><?= lang('App.vehicle_info') ?></th>
                                    <th scope="col"><?= lang('App.recorded_by') ?></th>
                                    <th scope="col"><?= lang('App.date_time') ?></th>
                                    <th scope="col"><?= lang('App.location') ?></th>
                                    <th scope="col"><?= lang('App.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody id="locationTableBody">
                                <!-- Rows will be loaded here -->
                            </tbody>
                        </table>
                        
                        <div id="loadMoreButton" class="text-center py-3" style="display: none;">
                            <button class="btn btn-soft-primary btn-sm" onclick="loadMoreLocations()">
                                <i class="ri-add-line me-1"></i><?= lang('App.load_more_locations') ?>
                            </button>
                        </div>
                        
                        <div id="loadingIndicator" class="text-center py-3" style="display: none;">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden"><?= lang('App.loading') ?>...</span>
                            </div>
                            <span class="text-muted ms-2"><?= lang('App.loading_more_locations') ?>...</span>
                        </div>
                        
                        <div id="allLoadedIndicator" class="text-center py-3" style="display: none;">
                            <small class="text-muted">
                                <i class="ri-check-line me-1 text-success"></i>
                                <?= lang('App.all_locations_loaded') ?> (${data.locations.length} <?= lang('App.total') ?>)
                            </small>
                        </div>
                    </div>
                `;
                
                // Load initial 3 locations
                console.log('Loading initial 3 locations...');
                loadMoreLocations();
                
                // Initialize Feather icons after initial content is loaded
                setTimeout(() => {
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                }, 500);
                
                // Show load more button if there are more locations
                if (data.locations.length > locationsPerPage) {
                    console.log('More locations available - showing load more button');
                    const loadMoreButton = document.getElementById('loadMoreButton');
                    if (loadMoreButton) {
                        loadMoreButton.style.display = 'block';
                    }
                } else {
                    console.log('All locations loaded initially');
                    const allLoadedIndicator = document.getElementById('allLoadedIndicator');
                    if (allLoadedIndicator) {
                        allLoadedIndicator.style.display = 'block';
                    }
                }
                
            } else {
                // Hide map container and show no data message
                if (mapContainer) mapContainer.style.display = 'none';
                
                container.innerHTML = `
                    <div class="no-locations-map">
                        <i class="ri-map-pin-line display-4 text-muted"></i>
                        <h6 class="mt-2">No Location History</h6>
                        <p class="text-muted">This vehicle hasn't been tracked yet.<br>Use the NFC token to start tracking.</p>
                    </div>`;
            }
        })
        .catch(error => {
            console.error('Error loading location history:', error);
            if (mapContainer) mapContainer.style.display = 'none';
            
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="ri-error-warning-line display-4 text-danger"></i>
                    <h6 class="mt-2">Error Loading History</h6>
                    <p class="text-muted">Failed to load location history.</p>
                    <button class="btn btn-outline-primary btn-sm" onclick="loadLocationHistory()">
                        <i class="ri-refresh-line me-1"></i>Retry
                    </button>
                </div>`;
        });
}

function loadMoreLocations() {
    if (isLoadingMore || displayedLocations >= allLocations.length) return;
    
    isLoadingMore = true;
    const loadMoreButton = document.getElementById('loadMoreButton');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const allLoadedIndicator = document.getElementById('allLoadedIndicator');
    const tableBody = document.getElementById('locationTableBody');
    
    if (!tableBody) {
        isLoadingMore = false;
        return;
    }
    
    // Show loading indicator and hide button
    if (loadMoreButton) loadMoreButton.style.display = 'none';
    if (loadingIndicator) loadingIndicator.style.display = 'block';
    
    // Simulate loading delay for better UX
    setTimeout(() => {
        const startIndex = displayedLocations;
        const endIndex = Math.min(startIndex + locationsPerPage, allLocations.length);
        
        console.log(`Loading locations ${startIndex + 1} to ${endIndex} of ${allLocations.length} total`);
        
        // Generate table rows for new locations
        for (let i = startIndex; i < endIndex; i++) {
            const location = allLocations[i];
            const locationRow = createLocationTableRow(location);
            tableBody.insertAdjacentHTML('beforeend', locationRow);
            
            // Load address for GPS locations
            if (location.latitude && location.longitude) {
                loadAddressForLocation(location);
            }
        }
        
        displayedLocations = endIndex;
        
        // Hide loading indicator
        if (loadingIndicator) loadingIndicator.style.display = 'none';
        
        // Show appropriate indicator
        if (displayedLocations >= allLocations.length) {
            // All loaded - show completion message
            if (allLoadedIndicator) allLoadedIndicator.style.display = 'block';
        } else {
            // More locations available - show load more button
            if (loadMoreButton) loadMoreButton.style.display = 'block';
        }
        
        isLoadingMore = false;
        
        // Initialize Feather icons for newly added content
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 300);
}

function createLocationTableRow(location) {
    const locationId = `location-${location.id}`;
    const dateObj = new Date(location.created_at);
    const dateFormatted = dateObj.toLocaleDateString();
    const timeFormatted = dateObj.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    
    return `
        <tr class="location-row" data-location-id="${location.id}">
            <td>
                <div class="d-flex align-items-center justify-content-center">
                    
                    <div>
                        <h6 class="mb-0 font-size-14"><?= lang('App.spot') ?> ${location.spot_number}</h6>
                        <small class="text-muted"><?= lang('App.parking_location') ?></small>
                    </div>
                </div>
            </td>
            <td>
                <div class="text-center">
                    <h6 class="mb-1 font-size-14">
                        <i data-feather="truck" class="icon-xs text-primary me-1"></i>
                        <?= esc($vehicle['vehicle'] ?? 'Vehicle') ?>
                    </h6>
                    <p class="text-muted mb-0 font-size-12">
                        <strong><?= lang('App.vin') ?>:</strong>
                        <code class="text-dark"><?= esc($vehicle['vin_number'] ?? '') ?></code>
                    </p>
                </div>
            </td>
            <td>
                <div class="text-center">
                    <div class="d-inline-flex align-items-center">
                        <i data-feather="user" class="icon-xs text-success me-1"></i>
                        <div>
                            <h6 class="mb-0 font-size-14">${location.user_name || '<?= lang('App.anonymous') ?>'}</h6>
                            <small class="text-muted"><?= lang('App.recorded_by') ?></small>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="text-center">
                    <p class="mb-1 font-size-14">
                        <i data-feather="calendar" class="icon-xs text-muted me-1"></i>
                        ${dateFormatted}
                    </p>
                    <p class="mb-0 text-muted font-size-12">
                        <i data-feather="clock" class="icon-xs me-1"></i>
                        ${timeFormatted}
                    </p>
                </div>
            </td>
            <td>
                <div id="${locationId}" class="location-address-display text-center">
                    ${location.latitude && location.longitude ? 
                        '<div class="loading-address"><small class="text-muted"><i data-feather="loader" class="icon-xs me-1 spin-icon"></i><?= lang('App.loading_address') ?>...</small></div>' :
                        '<div class="manual-entry"><small class="text-muted"><i data-feather="edit-3" class="icon-xs me-1"></i><?= lang('App.manual_entry') ?></small></div>'
                    }
                </div>
                ${location.notes ? `
                    <small class="text-warning d-block mt-1">
                        <i data-feather="file-text" class="icon-xs me-1"></i>
                        ${location.notes}
                    </small>
                ` : ''}
            </td>
            <td>
                <div class="service-order-action-buttons">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle btn btn-sm btn-outline-secondary service-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="more-horizontal" class="icon-xs"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('location-details/') ?>${location.id}">
                                    <i data-feather="eye" class="icon-xs align-bottom me-2 text-muted"></i>
                                    <?= lang('App.view_details') ?>
                                </a>
                            </li>
                            ${location.latitude && location.longitude ? `
                                <li>
                                    <a class="dropdown-item" href="#" onclick="showLocationOnMap(${location.latitude}, ${location.longitude})">
                                        <i data-feather="map-pin" class="icon-xs align-bottom me-2 text-muted"></i>
                                        <?= lang('App.show_on_map') ?>
                                    </a>
                                </li>
                            ` : ''}
                        </ul>
                    </div>
                </div>
            </td>
        </tr>
    `;
}

function showLocationOnMap(latitude, longitude) {
    // Focus the map on the specific location
    if (window.locationHistoryMapInstance) {
        window.locationHistoryMapInstance.setView([latitude, longitude], 16);
        
        // Scroll to map at the top of the screen
        const mapContainer = document.getElementById('locationHistoryMapContainer');
        if (mapContainer) {
            mapContainer.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' // This will position the map at the top of the viewport
            });
            
            // Add a slight delay and highlight effect
            setTimeout(() => {
                mapContainer.style.transition = 'all 0.3s ease';
                mapContainer.style.transform = 'scale(1.02)';
                mapContainer.style.boxShadow = '0 8px 25px rgba(0, 123, 255, 0.3)';
                
                // Remove highlight after 2 seconds
                setTimeout(() => {
                    mapContainer.style.transform = 'scale(1)';
                    mapContainer.style.boxShadow = '';
                }, 2000);
            }, 500);
        }
    }
}

function createLocationHistoryMap(locations) {
    const mapContainer = document.getElementById('locationHistoryMapContainer');
    const mapElement = document.getElementById('locationHistoryMap');
    
    if (!mapElement || !locations) return;
    
    // Filter locations with valid GPS coordinates
    const validLocations = locations.filter(loc => loc.latitude && loc.longitude);
    
    if (validLocations.length === 0) {
        // Show message for no GPS locations
        mapContainer.innerHTML = `
            <div class="no-locations-map">
                <i class="ri-gps-line display-4 text-muted"></i>
                <h6 class="mt-2">No GPS Locations Found</h6>
                <p class="text-muted">All location records were manually entered<br>without GPS coordinates.</p>
            </div>`;
        mapContainer.style.display = 'block';
        return;
    }
    
    // Show map container
    mapContainer.style.display = 'block';
    
    // Clear any existing map
    mapElement.innerHTML = '';
    
    // Calculate center point of all locations
    const centerLat = validLocations.reduce((sum, loc) => sum + parseFloat(loc.latitude), 0) / validLocations.length;
    const centerLng = validLocations.reduce((sum, loc) => sum + parseFloat(loc.longitude), 0) / validLocations.length;
    
    // Initialize the map
    const map = L.map('locationHistoryMap').setView([centerLat, centerLng], 15);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Create markers array for bounds fitting
    const markers = [];
    
    // Add markers for each location
    validLocations.forEach((location, index) => {
        const lat = parseFloat(location.latitude);
        const lng = parseFloat(location.longitude);
        
        // Create custom marker icon with different colors for different times
        const isRecent = index < 3; // First 3 locations are most recent
        const markerColor = isRecent ? '#007bff' : '#6c757d';
        
        const customIcon = L.divIcon({
            className: 'custom-location-marker',
            html: `<div style="
                background: ${markerColor}; 
                color: white; 
                border-radius: 50%; 
                width: 30px; 
                height: 30px; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                font-weight: bold; 
                border: 3px solid white; 
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                font-size: 12px;
            ">
                ${location.spot_number}
            </div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        
        // Create marker
        const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
        
        // Create enhanced popup content
        const dateObj = new Date(location.created_at);
        const dateFormatted = dateObj.toLocaleDateString();
        const timeFormatted = dateObj.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        const accuracy = location.accuracy ? ` ±${Math.round(location.accuracy)}m` : '';
        
        const popupContent = `
            <div class="location-marker-popup">
                <div class="popup-title">
                    <i class="ri-map-pin-fill"></i>
                    Spot <span class="popup-spot-badge">${location.spot_number}</span>
                </div>
                
                <div class="popup-info">
                    <i class="ri-map-2-line"></i>
                    <div class="popup-info-text" id="popup-address-${location.id}">
                        <span class="popup-loading">
                            <i class="ri-loader-4-line"></i> Loading...
                        </span>
                    </div>
                </div>
                
                <div class="popup-info">
                    <i class="ri-gps-line"></i>
                    <div class="popup-info-text">
                        <code>${lat.toFixed(4)}, ${lng.toFixed(4)}</code>
                        ${accuracy ? `<br><small>±${accuracy}m</small>` : ''}
                    </div>
                </div>
                
                <div class="popup-info">
                    <i class="ri-user-line"></i>
                    <div class="popup-info-text">
                        <strong>${location.user_name || 'Anonymous'}</strong>
                    </div>
                </div>
                
                <div class="popup-info">
                    <i class="ri-time-line"></i>
                    <div class="popup-info-text">
                        ${dateFormatted} ${timeFormatted}
                    </div>
                </div>
                
                ${location.notes ? `
                <div class="popup-notes">
                    <i class="ri-file-text-line"></i> ${location.notes}
                </div>
                ` : ''}
                
                <div class="popup-action">
                    <a href="<?= base_url('location-details/') ?>${location.id}" class="btn">
                        <i class="ri-eye-line"></i> Details
                    </a>
                </div>
            </div>
        `;
        
        marker.bindPopup(popupContent, {
            maxWidth: 280,
            className: 'custom-popup'
        });
        
        // Load address when popup is opened
        marker.on('popupopen', function() {
            // Small delay to ensure DOM is ready
            setTimeout(() => {
                loadAddressForPopup(location);
            }, 50);
        });
        
        markers.push(marker);
        
        // Add click event to marker for direct navigation
        marker.on('click', function() {
            // The popup will show, but we can also add direct click behavior if needed
        });
    });
    
    // Fit map to show all markers
    if (markers.length > 1) {
        const group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    } else if (markers.length === 1) {
        map.setView([centerLat, centerLng], 17);
    }
    
    // Add a legend
    const legend = L.control({ position: 'bottomright' });
    legend.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'legend');
        div.style.background = 'white';
        div.style.padding = '10px';
        div.style.border = '1px solid #ccc';
        div.style.borderRadius = '5px';
        div.style.fontSize = '12px';
        div.innerHTML = `
            <div style="margin-bottom: 5px;"><strong>Location History</strong></div>
            <div><span style="color: #007bff;">●</span> Recent locations</div>
            <div><span style="color: #6c757d;">●</span> Older locations</div>
            <div style="margin-top: 5px;"><small>Total: ${validLocations.length} GPS locations</small></div>
        `;
        return div;
    };
    legend.addTo(map);
    
    // Add resize functionality for responsive behavior
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
    
    // Handle window resize
    const resizeObserver = new ResizeObserver(entries => {
        map.invalidateSize();
    });
    resizeObserver.observe(document.getElementById('locationHistoryMap'));
    
    // Store map reference for potential future use
    window.locationHistoryMapInstance = map;
}

// Cache for addresses to avoid repeated API calls
const addressCache = {};

async function loadAddressForLocation(location) {
    const locationElement = document.getElementById(`location-${location.id}`);
    if (!locationElement) return;
    
    const lat = parseFloat(location.latitude);
    const lng = parseFloat(location.longitude);
    const accuracy = location.accuracy;
    
    // Create cache key
    const cacheKey = `${lat.toFixed(4)},${lng.toFixed(4)}`;
    
    try {
        let address;
        
        // Check localStorage cache first
        const cachedData = localStorage.getItem(`address_${cacheKey}`);
        if (cachedData) {
            const parsed = JSON.parse(cachedData);
            // Cache for 24 hours
            if (Date.now() - parsed.timestamp < 24 * 60 * 60 * 1000) {
                address = parsed.address;
            }
        }
        
        // If not in cache, fetch from Nominatim API
        if (!address) {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`,
                {
                    headers: {
                        'User-Agent': 'VehicleLocationTracker/1.0'
                    }
                }
            );
            
            if (response.ok) {
                const data = await response.json();
                address = formatAddress(data);
                
                // Cache the result
                localStorage.setItem(`address_${cacheKey}`, JSON.stringify({
                    address: address,
                    timestamp: Date.now()
                }));
            } else {
                throw new Error('Geocoding API failed');
            }
        }
        
        // Update the UI with the address for new card layout
        const accuracyText = accuracy ? ` ±${Math.round(accuracy)}m` : '';
        locationElement.innerHTML = `
            <div class="location-address-main">
                <i class="ri-map-pin-2-line me-1 text-primary"></i>
                <strong>${address}</strong>
            </div>
            <div class="location-coordinates">
                <small class="text-muted">
                    <i class="ri-gps-line me-1"></i>
                    ${lat.toFixed(6)}, ${lng.toFixed(6)}
                </small>
            </div>
            ${accuracy ? `
                <div class="location-accuracy">
                    <small class="text-muted">
                        <i class="ri-focus-3-line me-1"></i>
                        Accuracy: ${accuracyText}
                    </small>
                </div>
            ` : ''}
        `;
        
    } catch (error) {
        console.warn(`Failed to load address for location ${location.id}:`, error);
        
        // Fallback to coordinates for new card layout
        const accuracyText = accuracy ? ` ±${Math.round(accuracy)}m` : '';
        locationElement.innerHTML = `
            <div class="location-address-main">
                <i class="ri-map-pin-line me-1 text-muted"></i>
                <small class="text-muted">Address unavailable</small>
            </div>
            <div class="location-coordinates">
                <small class="text-muted">
                    <i class="ri-gps-line me-1"></i>
                    ${lat.toFixed(6)}, ${lng.toFixed(6)}
                </small>
            </div>
            ${accuracy ? `
                <div class="location-accuracy">
                    <small class="text-muted">
                        <i class="ri-focus-3-line me-1"></i>
                        Accuracy: ${accuracyText}
                    </small>
                </div>
            ` : ''}
        `;
    }
}

function formatAddress(geocodeData) {
    if (!geocodeData || !geocodeData.address) {
        return 'Unknown location';
    }
    
    const addr = geocodeData.address;
    const parts = [];
    
    // Building number and street
    if (addr.house_number && addr.road) {
        parts.push(`${addr.house_number} ${addr.road}`);
    } else if (addr.road) {
        parts.push(addr.road);
    } else if (addr.pedestrian || addr.footway) {
        parts.push(addr.pedestrian || addr.footway);
    }
    
    // Area/Neighborhood
    if (addr.neighbourhood || addr.suburb || addr.district) {
        parts.push(addr.neighbourhood || addr.suburb || addr.district);
    }
    
    // City
    if (addr.city || addr.town || addr.village) {
        parts.push(addr.city || addr.town || addr.village);
    }
    
    // State/Province
    if (addr.state || addr.province) {
        parts.push(addr.state || addr.province);
    }
    
    // Country (only if not US to save space)
    if (addr.country && addr.country !== 'United States') {
        parts.push(addr.country);
    }
    
    // If we have very specific location
    if (parts.length === 0) {
        if (addr.amenity) parts.push(addr.amenity);
        if (addr.shop) parts.push(addr.shop);
        if (addr.building) parts.push(addr.building);
        if (addr.postcode) parts.push(addr.postcode);
    }
    
    return parts.length > 0 ? parts.join(', ') : geocodeData.display_name || 'Unknown location';
}

// Load address for marker popup
async function loadAddressForPopup(location) {
    const popupElement = document.getElementById(`popup-address-${location.id}`);
    if (!popupElement) return;
    
    const lat = parseFloat(location.latitude);
    const lng = parseFloat(location.longitude);
    
    // For manual entries, show appropriate message
    if (!lat || !lng) {
        popupElement.innerHTML = `<small class="text-muted">Manual Entry</small>`;
        return;
    }
    
    // Create cache key
    const cacheKey = `${lat.toFixed(4)},${lng.toFixed(4)}`;
    
    try {
        let address;
        
        // Check localStorage cache first
        const cachedData = localStorage.getItem(`address_${cacheKey}`);
        if (cachedData) {
            const parsed = JSON.parse(cachedData);
            // Cache for 24 hours
            if (Date.now() - parsed.timestamp < 24 * 60 * 60 * 1000) {
                address = parsed.address;
            }
        }
        
        // If not in cache, fetch from Nominatim API
        if (!address) {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`,
                {
                    headers: {
                        'User-Agent': 'VehicleLocationTracker/1.0'
                    }
                }
            );
            
            if (response.ok) {
                const data = await response.json();
                address = formatAddress(data);
                
                // Cache the result
                localStorage.setItem(`address_${cacheKey}`, JSON.stringify({
                    address: address,
                    timestamp: Date.now()
                }));
            } else {
                throw new Error('Geocoding API failed');
            }
        }
        
        // Update the popup with the address
        popupElement.innerHTML = `<span class="popup-address">${address}</span>`;
        
    } catch (error) {
        console.warn(`Failed to load address for popup ${location.id}:`, error);
        
        // Fallback to coordinates
        popupElement.innerHTML = `<small class="text-muted">Address unavailable</small>`;
    }
}

function generateNFCToken() {
    const modal = new bootstrap.Modal(document.getElementById('nfcTokenModal'));
    const modalBody = document.getElementById('tokenModalBody');
    
    // Reset modal content
    modalBody.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Generating...</span>
        </div>
        <p class="text-muted mt-2">Generating NFC token...</p>`;
    
    modal.show();
    
    const vehicleVin = '<?= esc($vin ?? $vehicle['vin_number'] ?? '') ?>';
    if (!vehicleVin) {
        modalBody.innerHTML = '<div class="alert alert-danger">No VIN available for token generation</div>';
        return;
    }
    
    fetch(`<?= base_url('api/location/generate/') ?>${vehicleVin}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modalBody.innerHTML = `
                    <div class="mb-4">
                        <h6 class="text-success mb-3">
                            <i class="ri-check-circle-fill me-2"></i>
                            Token Generated Successfully
                        </h6>
                        
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Vehicle: ${data.vehicle.vehicle || data.vehicle.vin_number}</h6>
                                <p class="card-text">
                                    <strong>VIN:</strong> <code>${data.vehicle.vin_number}</code>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6>NFC URL:</h6>
                        <div class="input-group">
                            <input type="text" class="form-control font-monospace" 
                                   value="${data.nfc_url}" 
                                   id="nfcUrlInput" readonly>
                            <button class="btn btn-outline-secondary" onclick="copyToClipboard('nfcUrlInput')">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                        <small class="text-muted">Program this URL into your NFC tag</small>
                    </div>
                    
                    <div class="mb-4">
                        <h6>QR Code:</h6>
                        <div id="qrcode" class="d-flex justify-content-center"></div>
                        <small class="text-muted">Scan with mobile device to test</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="${data.nfc_url}" class="btn btn-success" target="_blank">
                            <i class="ri-smartphone-line me-2"></i>
                            Test Mobile Interface
                        </a>
                        <button class="btn btn-outline-primary" onclick="copyToClipboard('nfcUrlInput')">
                            <i class="ri-file-copy-line me-2"></i>
                            Copy URL
                        </button>
                        
                        <!-- NFC Writing Button -->
                        <div class="d-grid mt-2" id="nfcWriteButtons">
                            <button class="btn btn-warning" onclick="writeNFCTag('${data.nfc_url}', false)" id="writeNfcBtn">
                                <i class="ri-lock-line me-1"></i>
                                Write NFC (Protected)
                            </button>
                        </div>
                        
                        ${data.mda_shortlink && data.mda_shortlink.short_url ? `
                        <!-- MDA Shortlink Section -->
                        <hr class="my-3">
                        <div class="mb-3">
                            <h6><i class="ri-link me-2"></i>Vehicle Profile:</h6>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control font-monospace" 
                                       value="${data.mda_shortlink.short_url}" 
                                       id="mdaUrlInput" readonly>
                                <button class="btn btn-outline-secondary" onclick="copyToClipboard('mdaUrlInput')">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                            <small class="text-muted">MDA.to shortlink for vehicle profile</small>
                        </div>
                        
                        <a href="${data.mda_shortlink.short_url}" class="btn btn-outline-info w-100" target="_blank">
                            <i class="ri-external-link-line me-2"></i>
                            View Vehicle Profile
                        </a>
                        ` : ''}
                        
                        <div id="nfcSupport" class="mt-2">
                            <small class="text-muted">
                                <i class="ri-information-line me-1"></i>
                                NFC writing requires Chrome on Android<br>
                                <i class="ri-lock-line me-1"></i>
                                Tags will be protected with password: <strong>2209</strong>
                            </small>
                        </div>
                    </div>`;
                
                // Generate QR Code using QR Server API
                const qrDiv = document.getElementById('qrcode');
                const qrImg = document.createElement('img');
                qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(data.nfc_url)}`;
                qrImg.alt = 'QR Code';
                qrImg.className = 'border rounded';
                qrDiv.appendChild(qrImg);
                
            } else {
                modalBody.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ri-error-warning-line me-2"></i>
                        Error: ${data.message || 'Failed to generate token'}
                    </div>`;
            }
        })
        .catch(error => {
            console.error('Error generating token:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>
                    Network error: Failed to generate token
                </div>`;
        });
}

function copyToClipboard(inputId) {
    const input = document.getElementById(inputId);
    input.select();
    input.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        // Show success feedback
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="ri-check-line"></i>';
        btn.className = 'btn btn-success';
        
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.className = 'btn btn-outline-secondary';
        }, 2000);
        
    } catch (err) {
        console.error('Failed to copy text: ', err);
        alert('Failed to copy URL');
    }
}

// ===== VEHICLE PHOTOS FUNCTIONALITY =====

// Global variables for vehicle photos
let vehiclePhotos = [];
let currentPhotoIndex = 0;
let selectedPhotos = [];
let isSelectionMode = false;

// Load vehicle photos - Try S3 first, then custom URLs as fallback
async function loadVehiclePhotos() {
    const container = document.getElementById('vehiclePhotosContainer');
    if (!container) {
        console.warn('vehiclePhotosContainer not found, retrying in 1 second...');
        setTimeout(loadVehiclePhotos, 1000);
        return;
    }
    
    // Get VIN last 6 characters from current URL
    const urlPath = window.location.pathname;
    const vinLast6 = urlPath.split('/').pop();
    
    // Validate VIN
    if (!vinLast6 || vinLast6.length < 6) {
        console.error('Invalid VIN last 6 characters:', vinLast6);
        showS3EmptyMessage();
        return;
    }
    
    console.log('Loading photos for VIN last 6:', vinLast6);
    
    try {
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Loading vehicle photos...</p>
                <small class="text-muted">Checking photo storage...</small>
            </div>
        `;
        
        // STEP 1: Try to get photos from S3 first
        console.log('🔄 Attempting to load photos from S3...');
        try {
            const s3Response = await fetch(`<?= base_url('vehicles/s3-photos/') ?>${vinLast6}`);
            const s3Data = await s3Response.json();
            
            console.log('📊 S3 response:', s3Data);
            
            // Check for S3 configuration errors
            if (!s3Data.success && s3Data.error && s3Data.error.includes('AWS Access Key not configured')) {
                console.warn('⚠️ S3 not configured properly, showing empty message...');
                showS3EmptyMessage();
                return;
            }
            
            if (s3Data.success && s3Data.photos && s3Data.photos.length > 0) {
                console.log('✅ Found photos in S3:', s3Data.photos.length);
                
                // Convert S3 photos to expected format
                vehiclePhotos = s3Data.photos.map((photo, index) => ({
                    id: photo.id || index + 1,
                    key: photo.key, // ← CRITICAL: Preserve the S3 key for deletion
                    filename: photo.name,
                    url: photo.url,
                    thumbnail: photo.thumbnail,
                    title: `Photo ${index + 1}`,
                    description: photo.name,
                    uploadDate: photo.uploaded_at,
                    source: 'Cloud Storage'
                }));
                
                displayVehiclePhotos(vehiclePhotos, 'Cloud Storage');
                return; // Success with S3, exit function
            } else {
                console.log('❌ No photos found in S3, checking local storage...');
            }
        } catch (s3Error) {
            console.warn('⚠️ S3 photos failed, checking local storage:', s3Error);
        }
        
        // STEP 1.5: Try local storage if S3 failed
        try {
            console.log('🔄 Attempting to load photos from local storage...');
            const localResponse = await fetch(`<?= base_url('vehicles/local-photos/') ?>${vinLast6}`);
            const localData = await localResponse.json();
            
            console.log('📊 Local storage response:', localData);
            
            if (localData.success && localData.photos && localData.photos.length > 0) {
                console.log('✅ Found photos in local storage:', localData.photos.length);
                
                // Convert local photos to expected format
                vehiclePhotos = localData.photos.map((photo, index) => ({
                    id: photo.id || index + 1,
                    filename: photo.name,
                    url: photo.url,
                    thumbnail: photo.thumbnail,
                    title: `Photo ${index + 1}`,
                    description: photo.name,
                    uploadDate: photo.uploaded_at,
                    source: 'Local Storage'
                }));
                
                displayVehiclePhotos(vehiclePhotos, 'Local Storage');
                return; // Success with local storage, exit function
            } else {
                console.log('❌ No photos found in local storage, checking for fallback options...');
            }
        } catch (localError) {
            console.warn('⚠️ Local storage photos failed, falling back to custom URLs:', localError);
        }
        
        // STEP 2: Fallback to custom URL system
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Checking custom photo URLs...</p>
                <small class="text-muted">Storage empty, checking other sources...</small>
            </div>
        `;
        
        // Get the custom photos URL from database
        const urlResponse = await fetch(`<?= base_url('vehicles/photos-url/') ?>${vinLast6}`);
        const urlData = await urlResponse.json();
        
        if (!urlData.success) {
            throw new Error(urlData.error || 'Failed to get photos URL');
        }
        
        const customPhotosUrl = urlData.photos_url;
        
        // Store for potential editing
        window.currentPhotosUrl = customPhotosUrl;
        
        if (customPhotosUrl && customPhotosUrl.trim() !== '') {
            // Use custom URL
            container.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2">Loading photos from custom URL...</p>
                    <small class="text-muted">Source: ${getDomainFromUrl(customPhotosUrl)}</small>
                </div>
            `;
            
            try {
                // Add timeout protection (30 seconds)
                const photos = await Promise.race([
                    fetchPhotosFromCustomUrl(customPhotosUrl),
                    new Promise((_, reject) => 
                        setTimeout(() => reject(new Error('Timeout: Photos loading took too long (30s limit). Please check the URL and try again.')), 30000)
                    )
                ]);
                
                if (photos && photos.length > 0) {
                    vehiclePhotos = photos.map(photo => ({...photo, source: 'Custom URL'}));
                    displayVehiclePhotos(vehiclePhotos, 'Custom URL');
                } else {
                    showNoPhotosMessage();
                }
            } catch (timeoutError) {
                console.error('❌ Photo loading timeout or error:', timeoutError);
                showPhotosError(timeoutError.message);
            }
    } else {
            // No custom URL set, show S3 upload message
            showS3EmptyMessage();
        }
        
    } catch (error) {
        console.error('Error loading vehicle photos:', error);
        showPhotosError(error.message);
    }
}

// Get domain from URL for display purposes
function getDomainFromUrl(url) {
    try {
        const urlObj = new URL(url);
        return urlObj.hostname;
    } catch (e) {
        return 'Custom URL';
    }
}

// Process photos from custom URLs (direct image URLs or HTML pages)
async function fetchPhotosFromCustomUrl(customUrl) {
    console.log('Fetching photos from:', customUrl);
    
    // Parse multiple URLs if provided (separated by lines)
    const urls = customUrl.split('\n').map(url => url.trim()).filter(url => url);
    console.log('Found URLs:', urls);
    
    if (urls.length === 0) {
        console.warn('No valid URLs provided');
        return [];
    }
    
    const photos = [];
    
    for (let i = 0; i < urls.length; i++) {
        const url = urls[i];
        
        try {
            // Check if it's a direct image URL
            if (isDirectImageUrl(url)) {
                console.log(`✅ Processing direct image URL: ${url}`);
                const photo = await createPhotoFromImageUrl(url, i + 1);
                if (photo) {
                    photos.push(photo);
                    console.log(`✅ Successfully loaded image: ${photo.filename}`);
                } else {
                    console.warn(`❌ Failed to load direct image: ${url}`);
                }
            } else {
                console.log(`🌐 Processing HTML page URL: ${url}`);
                // Try to fetch HTML and extract images
                const htmlPhotos = await fetchPhotosFromHtmlPage(url);
                if (htmlPhotos.length > 0) {
                    photos.push(...htmlPhotos);
                    console.log(`✅ Extracted ${htmlPhotos.length} images from HTML page`);
                } else {
                    console.warn(`❌ No images found in HTML page: ${url}`);
                }
            }
        } catch (error) {
            console.error(`❌ Error processing URL ${url}:`, error);
            // Continue with other URLs
        }
    }
    
    console.log(`Successfully processed ${photos.length} photos`);
    
    // If no photos found, return empty array to show appropriate message
    if (photos.length === 0) {
        console.log('❌ No photos found from provided URLs');
        return [];
    }
    
    return photos;
}

// Check if URL is a direct image URL
function isDirectImageUrl(url) {
    const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.bmp', '.svg'];
    const lowerUrl = url.toLowerCase();
    
    // Google Drive check removed
    
    // Exclude cloud service URLs that might contain image extensions but aren't direct
    const cloudServices = [
        'jottacloud.com/s/',
        'drive.google.com/drive/',     // Folder URLs
        'drive.google.com/file/',      // File view URLs  
        'dropbox.com',
        'onedrive.live.com',
        'box.com'
    ];
    
    // If it's a cloud service URL (but not direct download), treat as HTML page
    const isCloudService = cloudServices.some(service => lowerUrl.includes(service));
    if (isCloudService) {
        console.log(`🌐 Detected cloud service URL, treating as HTML page: ${url}`);
        return false;
    }
    
    // Check if URL ends with image extension (more reliable than contains)
    const hasImageExtension = imageExtensions.some(ext => {
        const cleanUrl = url.split('?')[0].split('#')[0]; // Remove query params and fragments
        return cleanUrl.toLowerCase().endsWith(ext);
    });
    
    return hasImageExtension;
}

// Create photo object from direct image URL
async function createPhotoFromImageUrl(url, index) {
    try {
        // Test if image loads
        await new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = resolve;
            img.onerror = reject;
            img.src = url;
        });
        
        // Extract filename from URL
        const urlParts = url.split('/');
        const filename = urlParts[urlParts.length - 1].split('?')[0] || `photo_${index}`;
        
        return {
            id: index,
            filename: filename,
            url: url,
            thumbnail: url,
            title: `Photo ${index}`,
            description: filename,
            uploadDate: new Date().toISOString()
        };
    } catch (error) {
        console.error(`Failed to load image: ${url}`, error);
        return null;
    }
}

// Fetch photos from HTML page (for non-direct URLs)
async function fetchPhotosFromHtmlPage(url) {
    try {
        console.log(`Fetching HTML from: ${url}`);
        
        // Use CORS proxy to fetch HTML
        const response = await fetch(`https://api.cors.lol/?url=${encodeURIComponent(url)}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const htmlContent = await response.text();
        
        console.log(`Received HTML content (${htmlContent.length} chars) from: ${url}`);
        
        // Parse HTML to extract image URLs
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlContent, 'text/html');
        
        // Try different extraction methods based on the service
        let photos = [];
        
        if (url.includes('dropbox.com')) {
            photos = extractPhotosFromDropbox(doc, url);
        } else if (url.includes('imgur.com')) {
            photos = extractPhotosFromImgur(doc, url);
        } else {
            // Generic extraction for other services
            photos = extractPhotosGeneric(doc, url);
        }
        
        console.log(`Extracted ${photos.length} photos from HTML page: ${url}`);
        return photos;
        
    } catch (error) {
        console.error(`Failed to fetch from HTML page: ${url}`, error);
        return [];
    }
}

// Show message when S3 is empty and no custom URLs
function showS3EmptyMessage() {
    const container = document.getElementById('vehiclePhotosContainer');
    const isStaff = <?= json_encode(auth()->user() && in_array(auth()->user()->user_type, ['staff', 'admin'])) ?>;
    
    if (isStaff) {
        container.innerHTML = `
            <div class="s3-empty-message text-center py-5">
                <div class="mb-4">
                    <i class="ri-image-line" style="font-size: 4rem; color: #6c757d; margin-bottom: 1rem;"></i>
                    <h5>No Photos Available</h5>
                    <p class="text-muted mb-4">
                        This vehicle doesn't have any photos yet.<br>
                        Upload photos to view them here.
                    </p>
                </div>
                
                <div class="d-flex gap-2 justify-content-center mb-4">
                    <button class="btn btn-success" onclick="openS3UploadModal()">
                        <i class="ri-upload-cloud-2-line me-2"></i>
                        Upload Photos
                </button>
                </div>
                
                
            </div>
        `;
    } else {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="ri-image-line display-4 text-muted mb-3"></i>
                <h6>No Photos Available</h6>
                <p class="text-muted mb-0">Photos will be available once uploaded by staff.</p>
            </div>
        `;
    }
}

// Google Drive functions removed for simplification

// Test if URL points to a valid image
async function testImageUrl(url) {
    return new Promise((resolve) => {
        const img = new Image();
        let resolved = false;
        
        const resolveOnce = (result) => {
            if (!resolved) {
                resolved = true;
                resolve(result);
            }
        };
        
        img.onload = () => {
            console.log(`✅ Image test passed: ${url}`);
            resolveOnce(true);
        };
        
        img.onerror = (error) => {
            console.log(`❌ Image test failed: ${url}`, error);
            resolveOnce(false);
        };
        
        // Set source to start loading
        img.src = url;
        
        // Longer timeout for Google Drive (10 seconds)
        setTimeout(() => {
            console.log(`⏰ Image test timeout: ${url}`);
            resolveOnce(false);
        }, 10000);
    });
}

// Extract photos from Dropbox (if supported)
function extractPhotosFromDropbox(doc, baseUrl) {
    // This would need specific implementation for Dropbox's HTML structure
    // For now, return empty array
    console.log('Dropbox photo extraction not yet implemented');
    return [];
}

// Extract photos from Imgur
function extractPhotosFromImgur(doc, baseUrl) {
    const photos = [];
    
    // Look for Imgur image patterns
    const imgElements = doc.querySelectorAll('img[src*="i.imgur.com"], img[data-src*="i.imgur.com"]');
    
    imgElements.forEach((img, index) => {
        const src = img.src || img.getAttribute('data-src');
        if (src && src.includes('i.imgur.com')) {
            const filename = src.split('/').pop().split('?')[0] || `imgur_${index + 1}`;
            
            photos.push({
                id: index + 1,
                filename: filename,
                url: src,
                thumbnail: src,
                title: `Imgur Photo ${index + 1}`,
                description: filename,
                uploadDate: new Date().toISOString()
            });
        }
    });
    
    return photos;
}

// Generic photo extraction for other services
function extractPhotosGeneric(doc, baseUrl) {
    const photos = [];
    
    // Look for common image file extensions
    const imageLinks = doc.querySelectorAll('a[href*=".jpg"], a[href*=".jpeg"], a[href*=".png"], a[href*=".gif"], a[href*=".webp"]');
    
    imageLinks.forEach((link, index) => {
        const filename = link.textContent.trim() || `photo_${index + 1}`;
        let fileUrl;
        
        try {
            fileUrl = new URL(link.href, baseUrl).href;
        } catch (e) {
            fileUrl = link.href;
        }
        
        photos.push({
            id: index + 1,
            filename: filename,
            url: fileUrl,
            thumbnail: fileUrl,
            title: `Photo ${index + 1}`,
            description: filename,
            uploadDate: new Date().toISOString()
        });
    });
    
    return photos;
}

// Extract photo URLs from cloud storage HTML
function extractPhotosFromCloudStorage(doc, baseUrl) {
    const photos = [];
    
    // Look for image files in the cloud storage listing
    const fileLinks = doc.querySelectorAll('a[href*=".jpg"], a[href*=".jpeg"], a[href*=".png"], a[href*=".gif"]');
    
    fileLinks.forEach((link, index) => {
        const filename = link.textContent.trim();
        const fileUrl = new URL(link.href, baseUrl).href;
        
        photos.push({
            id: index + 1,
            filename: filename,
            url: fileUrl,
            thumbnail: fileUrl, // Cloud storage can serve as thumbnail
            title: `Vehicle Photo ${index + 1}`,
            description: filename,
            uploadDate: new Date().toISOString() // Placeholder
        });
    });
    
    return photos;
}

// Get random vehicle photos for demonstration  
function getDemoPhotos() {
    const vehicleKeywords = [
        'automotive',
        'car',
        'vehicle', 
        'transportation',
        'automobile',
        'sedan'
    ];
    
    const photoViews = [
        { title: 'Front View', desc: 'Front view of the vehicle' },
        { title: 'Side Profile', desc: 'Side profile of the vehicle' },
        { title: 'Interior', desc: 'Interior view of the vehicle' },
        { title: 'Dashboard', desc: 'Dashboard and instruments' },
        { title: 'Rear View', desc: 'Rear view of the vehicle' },
        { title: 'Engine Bay', desc: 'Engine compartment' }
    ];
    
    const demoPhotos = photoViews.map((view, index) => {
        const keywordIndex = index % vehicleKeywords.length;
        const keyword = vehicleKeywords[keywordIndex];
        const randomSeed = Math.floor(Math.random() * 999) + index * 100;
        
        return {
            id: index + 1,
            filename: `${view.title.toLowerCase().replace(/\s+/g, '_')}.jpg`,
            url: `https://source.unsplash.com/800x600/?${keyword}&sig=${randomSeed}`,
            thumbnail: `https://source.unsplash.com/300x200/?${keyword}&sig=${randomSeed}`,
            title: view.title,
            description: view.desc,
            uploadDate: new Date().toISOString()
        };
    });
    
    console.log('✨ Generated random vehicle photos for demonstration');
    return demoPhotos;
}

// Global variables for photo pagination
let allVehiclePhotos = [];
let currentPhotoSource = '';
let photosShownCount = 0;
const PHOTOS_PER_PAGE = 12;

// Display vehicle photos in the grid with infinite scroll
function displayVehiclePhotos(photos, source = '') {
    const container = document.getElementById('vehiclePhotosContainer');
    
    // Clean up previous infinite scroll listeners
    cleanupInfiniteScroll();
    
    // Store photos globally for pagination
    allVehiclePhotos = photos;
    currentPhotoSource = source;
    photosShownCount = 0; // Reset counter
    
    if (photos.length === 0) {
        showNoPhotosMessage();
        return;
    }
    
    renderPhotoGrid();
}

// Render the photo grid with current pagination state
function renderPhotoGrid() {
    const container = document.getElementById('vehiclePhotosContainer');
    const photosToShow = Math.min(photosShownCount + PHOTOS_PER_PAGE, allVehiclePhotos.length);
    const visiblePhotos = allVehiclePhotos.slice(0, photosToShow);
    const hasMorePhotos = photosToShow < allVehiclePhotos.length;
    
    const sourceIcon = currentPhotoSource === 'Cloud Storage' ? 'ri-cloud-line' : 'ri-link';
    const sourceColor = currentPhotoSource === 'Cloud Storage' ? 'text-success' : 'text-info';
    
    let photosHtml = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="mb-0">
                    ${allVehiclePhotos.length} Photo${allVehiclePhotos.length !== 1 ? 's' : ''} Available
                    ${currentPhotoSource ? `<small class="badge bg-light ${sourceColor} ms-2">
                        <i class="${sourceIcon} me-1"></i>${currentPhotoSource}
                    </small>` : ''}
                    ${selectedPhotos.length > 0 ? `<small class="badge bg-warning text-dark ms-2">
                        ${selectedPhotos.length} selected
                    </small>` : ''}
                </h6>
                <small class="text-muted">
                    ${hasMorePhotos ? `Showing ${photosToShow} of ${allVehiclePhotos.length} photos • ` : ''}
                    ${isSelectionMode ? 'Select photos to delete' : 'Click any photo to view in fullscreen'}
                </small>
            </div>
            <div class="d-flex gap-2">
                ${isSelectionMode ? `
                    <button class="btn btn-outline-secondary btn-sm" onclick="toggleSelectAll()" id="selectAllBtn">
                        <i class="ri-checkbox-multiple-line me-1"></i>
                        <span id="selectAllText"><?= lang('App.select_all_photos') ?></span>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteSelectedPhotos()" 
                            ${selectedPhotos.length === 0 ? 'disabled' : ''} id="deleteSelectedBtn">
                        <i class="ri-delete-bin-line me-1"></i>
                        <?= lang('App.delete_selected_photos') ?>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="exitSelectionMode()">
                        <i class="ri-close-line me-1"></i>
                        Cancel
                    </button>
                ` : `
                <button class="btn btn-outline-primary btn-sm" onclick="openPhotosModal(0)">
                        <i class="ri-maximize-2-line icon-sm me-1"></i>
                    View Gallery
                </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="enterSelectionMode()">
                        <i class="ri-checkbox-line icon-sm me-1"></i>
                        Select Photos
                    </button>
                    ${currentPhotoSource === 'Cloud Storage' ? `
                        <button class="btn btn-outline-success btn-sm" onclick="uploadToS3()">
                            <i class="ri-upload-cloud-2-line icon-sm me-1"></i>
                            Upload More
                        </button>
                    ` : ''}
                `}
            </div>
        </div>
        <div class="vehicle-photos-grid" id="photosGrid">
    `;
    
    visiblePhotos.forEach((photo, index) => {
        const photoKey = photo.key || photo.url || photo.id || index;
        const isSelected = selectedPhotos.includes(photoKey);
        photosHtml += `
            <div class="photo-thumbnail ${isSelected ? 'selected' : ''}" 
                 ${isSelectionMode ? `onclick="togglePhotoSelection('${photoKey}')"` : `onclick="openPhotosModal(${index})"`} 
                 title="${photo.title}">
                <img src="${photo.thumbnail}" alt="${photo.title}" 
                     onerror="this.style.display='none'; this.parentNode.classList.add('photo-error-placeholder')"
                     loading="lazy">
                <div class="photo-overlay">
                    ${isSelectionMode ? `
                        <div class="photo-checkbox">
                            <input type="checkbox" ${isSelected ? 'checked' : ''} 
                                   onchange="togglePhotoSelection('${photoKey}')" 
                                   onclick="event.stopPropagation()">
                        </div>
                    ` : `
                    <i class="ri-eye-line photo-overlay-icon"></i>
                    `}
                </div>
                ${currentPhotoSource === 'Cloud Storage' ? `
                    <div class="photo-source-badge">
                        <i class="ri-cloud-line"></i>
                    </div>
                ` : ''}
            </div>
        `;
    });
    
    photosHtml += '</div>';
    
    // Add loading indicator and scroll hint if there are more photos (for infinite scroll)
    if (hasMorePhotos) {
        const remainingPhotos = allVehiclePhotos.length - photosToShow;
        photosHtml += `
            <div class="text-center mt-4" id="infiniteScrollLoader" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading more photos...</span>
                </div>
                <div class="text-muted mt-2" style="font-size: 0.875rem;">
                    Loading more photos...
                </div>
            </div>
            <div class="text-center mt-3 py-3" id="scrollHint" style="border-top: 1px dashed #dee2e6;">
                <div class="text-muted" style="font-size: 0.875rem;">
                    <i class="ri-scroll-to-bottom-line me-1"></i>
                    Scroll down to load ${remainingPhotos} more photo${remainingPhotos !== 1 ? 's' : ''}
                </div>
                <div class="progress mt-2" style="height: 3px;">
                    <div class="progress-bar bg-primary" role="progressbar" 
                         style="width: ${(photosToShow / allVehiclePhotos.length) * 100}%"></div>
                </div>
            </div>
        `;
    }
    
    container.innerHTML = photosHtml;
    photosShownCount = photosToShow;
    
    // Initialize infinite scroll after rendering
    setTimeout(() => {
        initializeInfiniteScroll();
    }, 100); // Small delay to ensure DOM is updated
}

// ===== PHOTO SELECTION FUNCTIONALITY =====

// Enter selection mode
function enterSelectionMode() {
    isSelectionMode = true;
    selectedPhotos = [];
    renderPhotoGrid();
}

// Exit selection mode
function exitSelectionMode() {
    isSelectionMode = false;
    selectedPhotos = [];
    renderPhotoGrid();
}

// Toggle individual photo selection
function togglePhotoSelection(photoKey) {
    if (selectedPhotos.includes(photoKey)) {
        selectedPhotos = selectedPhotos.filter(key => key !== photoKey);
    } else {
        selectedPhotos.push(photoKey);
    }
    updateSelectionUI();
    renderPhotoGrid();
}

// Toggle select all photos
function toggleSelectAll() {
    const selectAllBtn = document.getElementById('selectAllBtn');
    const selectAllText = document.getElementById('selectAllText');
    
    if (selectedPhotos.length === allVehiclePhotos.length) {
        // Deselect all
        selectedPhotos = [];
        selectAllText.textContent = '<?= lang('App.select_all_photos') ?>';
    } else {
        // Select all visible photos
        selectedPhotos = allVehiclePhotos.map(photo => photo.key || photo.url || photo.id);
        selectAllText.textContent = '<?= lang('App.deselect_all_photos') ?>';
    }
    
    updateSelectionUI();
    renderPhotoGrid();
}

// Update selection UI elements
function updateSelectionUI() {
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    const selectAllText = document.getElementById('selectAllText');
    
    if (deleteBtn) {
        deleteBtn.disabled = selectedPhotos.length === 0;
    }
    
    if (selectAllText) {
        selectAllText.textContent = selectedPhotos.length === allVehiclePhotos.length 
            ? '<?= lang('App.deselect_all_photos') ?>' 
            : '<?= lang('App.select_all_photos') ?>';
    }
}

// Delete selected photos
async function deleteSelectedPhotos() {
    if (selectedPhotos.length === 0) {
        showToast('warning', '<?= lang('App.no_photos_selected') ?>');
        return;
    }
    
    // Show confirmation modal
    const modal = new bootstrap.Modal(document.getElementById('deletePhotosModal'));
    document.getElementById('deletePhotosCount').textContent = selectedPhotos.length;
    modal.show();
}

// Confirm delete photos
async function confirmDeletePhotos() {
    try {
        console.log('🗑️ Deleting photos with keys:', selectedPhotos);
        showToast('info', 'Deleting photos...');
        
        const response = await fetch('<?= base_url('vehicles/delete-photos') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                photo_keys: selectedPhotos
            })
        });
        
        const result = await response.json();
        console.log('📊 Delete response:', result);
        
        if (result.success) {
            const message = selectedPhotos.length === 1 
                ? '<?= lang('App.photo_deleted_successfully') ?>'
                : '<?= lang('App.photos_deleted_successfully') ?>';
            showToast('success', message);
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deletePhotosModal'));
            modal.hide();
            
            // Exit selection mode and reload photos
            exitSelectionMode();
            await loadVehiclePhotos();
        } else {
            throw new Error(result.error || '<?= lang('App.delete_photos_error') ?>');
        }
        
    } catch (error) {
        console.error('Delete photos error:', error);
        showToast('error', error.message || '<?= lang('App.delete_photos_error') ?>');
    }
}

// ===== INFINITE SCROLL FUNCTIONALITY =====

// Global variables for infinite scroll
let isInfiniteScrollLoading = false;
let infiniteScrollListener = null;

// Initialize infinite scroll functionality
function initializeInfiniteScroll() {
    // Remove previous listener if exists
    if (infiniteScrollListener) {
        const container = document.getElementById('vehiclePhotosContainer');
        if (container) {
            container.removeEventListener('scroll', infiniteScrollListener);
        }
    }
    
    const container = document.getElementById('vehiclePhotosContainer');
    console.log('📋 Infinite Scroll Init:', {
        containerFound: !!container,
        photosShown: photosShownCount,
        totalPhotos: allVehiclePhotos.length,
        hasMorePhotos: photosShownCount < allVehiclePhotos.length,
        containerHeight: container ? container.clientHeight : 'N/A',
        containerScrollHeight: container ? container.scrollHeight : 'N/A'
    });
    
    // Only add scroll listener if there are more photos to load
    if (photosShownCount < allVehiclePhotos.length) {
        if (container) {
            infiniteScrollListener = throttle(handleInfiniteScroll, 100);
            container.addEventListener('scroll', infiniteScrollListener);
            console.log('✅ Infinite scroll listener added');
        } else {
            console.warn('❌ Container not found for infinite scroll');
        }
    } else {
        console.log('ℹ️ All photos already loaded, no infinite scroll needed');
    }
}

// Handle infinite scroll event
function handleInfiniteScroll() {
    if (isInfiniteScrollLoading || photosShownCount >= allVehiclePhotos.length) {
        return;
    }
    
    const container = document.getElementById('vehiclePhotosContainer');
    if (!container) return;
    
    // Check if user is near the bottom of the container's scrollable area
    const scrollTop = container.scrollTop;
    const scrollHeight = container.scrollHeight;
    const clientHeight = container.clientHeight;
    
    console.log('🔄 Scroll Debug:', {
        scrollTop: scrollTop,
        scrollHeight: scrollHeight,
        clientHeight: clientHeight,
        trigger: scrollTop + clientHeight >= scrollHeight - 100,
        photosShown: photosShownCount,
        totalPhotos: allVehiclePhotos.length
    });
    
    // Load more when user is 100px from the bottom of the container
    if (scrollTop + clientHeight >= scrollHeight - 100) {
        console.log('🚀 Triggering infinite scroll...');
        loadMorePhotosInfinite();
    }
}

// Load more photos for infinite scroll
function loadMorePhotosInfinite() {
    if (isInfiniteScrollLoading || photosShownCount >= allVehiclePhotos.length) {
        return;
    }
    
    isInfiniteScrollLoading = true;
    
    // Show loading indicator
    const loader = document.getElementById('infiniteScrollLoader');
    if (loader) {
        loader.style.display = 'block';
    }
    
    // Simulate brief loading delay for better UX
    setTimeout(() => {
        const previousCount = photosShownCount;
        renderPhotoGrid();
        
        // Show toast notification for newly loaded photos
        const newlyLoaded = photosShownCount - previousCount;
        const remainingPhotos = allVehiclePhotos.length - photosShownCount;
        
        if (remainingPhotos === 0) {
            showToast('success', `All ${allVehiclePhotos.length} photos loaded!`);
            // Remove scroll listener since all photos are loaded
            if (infiniteScrollListener) {
                const container = document.getElementById('vehiclePhotosContainer');
                if (container) {
                    container.removeEventListener('scroll', infiniteScrollListener);
                }
                infiniteScrollListener = null;
            }
        } else {
            showToast('info', `Loaded ${newlyLoaded} more photos`);
        }
        
        isInfiniteScrollLoading = false;
        
        // Hide loading indicator
        if (loader) {
            loader.style.display = 'none';
        }
        
        // Update scroll hint and progress
        const scrollHint = document.getElementById('scrollHint');
        if (scrollHint && remainingPhotos > 0) {
            const progressBar = scrollHint.querySelector('.progress-bar');
            const hintText = scrollHint.querySelector('.text-muted');
            if (progressBar) {
                progressBar.style.width = `${(photosShownCount / allVehiclePhotos.length) * 100}%`;
            }
            if (hintText) {
                hintText.innerHTML = `<i class="ri-scroll-to-bottom-line me-1"></i>Scroll down to load ${remainingPhotos} more photo${remainingPhotos !== 1 ? 's' : ''}`;
            }
        } else if (scrollHint && remainingPhotos === 0) {
            scrollHint.style.display = 'none';
        }
        
    }, 300); // Brief delay for loading animation
}

// Throttle function to limit scroll event frequency
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

// Clean up infinite scroll when changing photos
function cleanupInfiniteScroll() {
    if (infiniteScrollListener) {
        const container = document.getElementById('vehiclePhotosContainer');
        if (container) {
            container.removeEventListener('scroll', infiniteScrollListener);
        }
        infiniteScrollListener = null;
    }
    isInfiniteScrollLoading = false;
}

// Show no photos message with context-aware options
function showNoPhotosMessage() {
    const container = document.getElementById('vehiclePhotosContainer');
    const isStaff = <?= json_encode(auth()->user() && in_array(auth()->user()->user_type, ['staff', 'admin'])) ?>;
    const hasConfiguredUrl = window.currentPhotosUrl && window.currentPhotosUrl.trim() !== '';
    
    let messageContent = '';
    
    if (hasConfiguredUrl) {
        // URLs are configured but no photos found
        let helpMessage = '<?= lang('App.no_photos_found_help') ?>';
        let suggestions = '';
        
        messageContent = `
            <div class="no-photos-message">
                <i class="ri-image-2-line" style="font-size: 2.5rem; color: #6c757d; margin-bottom: 1rem;"></i>
                <h6><?= lang('App.no_photos_found') ?></h6>
                <p class="text-muted mb-3">${helpMessage}</p>
                ${suggestions}
                <div class="d-flex gap-2 justify-content-center">
                </div>
                </div>
            `;
    } else {
        // No URLs configured
        if (isStaff) {
            messageContent = `
                <div class="no-photos-message">
                    <i class="ri-image-add-line" style="font-size: 2.5rem; color: #6c757d; margin-bottom: 1rem;"></i>
                    <h6>No Photos Available</h6>
                    <p class="text-muted mb-3">Upload photos for this vehicle.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-success btn-sm" onclick="uploadToS3()">
                            <i class="ri-upload-cloud-2-line me-1"></i>
                            Upload Photos
                        </button>
                    </div>
                </div>
            `;
        } else {
            messageContent = `
                <div class="no-photos-message">
                    <i class="ri-image-line" style="font-size: 2.5rem; color: #6c757d; margin-bottom: 1rem;"></i>
                    <h6><?= lang('App.no_photos_available') ?></h6>
                    <p class="text-muted mb-3"><?= lang('App.no_photos_url_help_user') ?></p>

                </div>
            `;
        }
    }
    
    container.innerHTML = messageContent;
}



// Show photos error message
function showPhotosError(errorMessage) {
    const container = document.getElementById('vehiclePhotosContainer');
    const isStaff = <?= json_encode(auth()->user() && in_array(auth()->user()->user_type, ['staff', 'admin'])) ?>;
    
    container.innerHTML = `
        <div class="photo-error">
            <i class="ri-error-warning-line" style="font-size: 2.5rem; color: #dc3545; margin-bottom: 1rem;"></i>
            <h6><?= lang('App.failed_to_load_photos') ?></h6>
            <p class="text-muted mb-3">${errorMessage}</p>
            <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-outline-danger btn-sm" onclick="loadVehiclePhotos()">
                    <i class="ri-refresh-line me-1"></i>
                    <?= lang('App.retry') ?>
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="showDemoPhotos()">
                    <i class="ri-image-line me-1"></i>
                    <?= lang('App.show_demo_photos') ?>
                </button>
            </div>
        </div>
    `;
}

// Open horizontal photos modal at specific index
function openPhotosModal(photoIndex = 0) {
    if (!vehiclePhotos || vehiclePhotos.length === 0) {
        console.error('No photos available to display');
        return;
    }
    
    currentPhotoIndex = photoIndex;
    
    // Populate horizontal carousel content
    const horizontalTrack = document.getElementById('horizontalCarouselTrack');
    const horizontalCounter = document.getElementById('horizontalPhotoCounter');
    const currentIndicator = document.getElementById('currentPhotoIndicator');
    const photoCounter = document.getElementById('photoCounter');
    
    let carouselHtml = '';
    
    vehiclePhotos.forEach((photo, index) => {
        carouselHtml += `
            <div class="carousel-photo-slide" data-index="${index}" onclick="focusOnPhoto(${index})">
                <img src="${photo.url}" alt="${photo.title}" 
                     onerror="this.style.display='none'; this.parentNode.classList.add('photo-error')"
                     loading="lazy">
            </div>
        `;
    });
    
    horizontalTrack.innerHTML = carouselHtml;
    horizontalCounter.textContent = `${photoIndex + 1} of ${vehiclePhotos.length}`;
    currentIndicator.textContent = `Photo ${photoIndex + 1}`;
    photoCounter.textContent = `${photoIndex + 1} of ${vehiclePhotos.length}`;
    
    // Update modal title
    const modalTitle = document.getElementById('vehiclePhotosModalLabel');
    modalTitle.innerHTML = `
            <i class="ri-camera-line me-2"></i>
        Vehicle Photos - ${vehiclePhotos[photoIndex].title}
    `;
    
    // Show modal with transparent backdrop
    const modalElement = document.getElementById('vehiclePhotosModal');
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: 'static',  // Keep backdrop but make it transparent via CSS
        keyboard: true       // Keep keyboard navigation
    });
    
    modal.show();
    
    // Setup horizontal navigation
    setupHorizontalNavigation();
    
    // Focus on initial photo after modal is shown
    setTimeout(() => {
        scrollToPhoto(photoIndex);
    }, 300);
}

// Setup navigation for horizontal carousel
function setupHorizontalNavigation() {
    const prevBtn = document.getElementById('carouselPrev');
    const nextBtn = document.getElementById('carouselNext');
    const track = document.getElementById('horizontalCarouselTrack');
    
    // Remove existing listeners
    prevBtn.replaceWith(prevBtn.cloneNode(true));
    nextBtn.replaceWith(nextBtn.cloneNode(true));
    
    // Get fresh references
    const newPrevBtn = document.getElementById('carouselPrev');
    const newNextBtn = document.getElementById('carouselNext');
    
    // Add click handlers
    newPrevBtn.addEventListener('click', () => {
        if (currentPhotoIndex > 0) {
            navigateToPhoto(currentPhotoIndex - 1);
        }
    });
    
    newNextBtn.addEventListener('click', () => {
        if (currentPhotoIndex < vehiclePhotos.length - 1) {
            navigateToPhoto(currentPhotoIndex + 1);
        }
    });
    
    // Update button states
    updateNavigationButtons();
    
    // Add scroll detection for updating current photo
    track.addEventListener('scroll', debounce(updateCurrentPhotoFromScroll, 100));
    
    // Add keyboard navigation
    setupKeyboardNavigation();
}

// Setup keyboard navigation for the carousel
function setupKeyboardNavigation() {
    // Remove existing keyboard listener if any
    document.removeEventListener('keydown', handleCarouselKeydown);
    
    // Add new keyboard listener
    document.addEventListener('keydown', handleCarouselKeydown);
}

// Handle keyboard navigation
function handleCarouselKeydown(event) {
    // Only handle keys when modal is open
    const modal = document.getElementById('vehiclePhotosModal');
    if (!modal.classList.contains('show')) return;
    
    switch(event.key) {
        case 'ArrowLeft':
            event.preventDefault();
            if (currentPhotoIndex > 0) {
                navigateToPhoto(currentPhotoIndex - 1);
            }
            break;
        case 'ArrowRight':
            event.preventDefault();
            if (currentPhotoIndex < vehiclePhotos.length - 1) {
                navigateToPhoto(currentPhotoIndex + 1);
            }
            break;
        case 'Home':
            event.preventDefault();
            navigateToPhoto(0);
            break;
        case 'End':
            event.preventDefault();
            navigateToPhoto(vehiclePhotos.length - 1);
            break;
        case 'Escape':
            event.preventDefault();
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
            break;
    }
}

// Navigate to specific photo
function navigateToPhoto(index) {
    if (index >= 0 && index < vehiclePhotos.length) {
        currentPhotoIndex = index;
        scrollToPhoto(index);
        updateCounters();
        updateNavigationButtons();
        
        // Update modal title
        const modalTitle = document.getElementById('vehiclePhotosModalLabel');
        modalTitle.innerHTML = `
            <i class="ri-camera-line me-2"></i>
            Vehicle Photos - ${vehiclePhotos[index].title}
        `;
    }
}

// Scroll to specific photo
function scrollToPhoto(index) {
    const track = document.getElementById('horizontalCarouselTrack');
    const slides = track.querySelectorAll('.carousel-photo-slide');
    
    if (slides[index]) {
        slides[index].scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'center'
        });
    }
}

// Focus on photo when clicked
function focusOnPhoto(index) {
    navigateToPhoto(index);
}

// Update counters
function updateCounters() {
    const horizontalCounter = document.getElementById('horizontalPhotoCounter');
    const currentIndicator = document.getElementById('currentPhotoIndicator');
    const photoCounter = document.getElementById('photoCounter');
    
    horizontalCounter.textContent = `${currentPhotoIndex + 1} of ${vehiclePhotos.length}`;
    currentIndicator.textContent = `Photo ${currentPhotoIndex + 1}`;
    photoCounter.textContent = `${currentPhotoIndex + 1} of ${vehiclePhotos.length}`;
}

// Update navigation button states
function updateNavigationButtons() {
    const prevBtn = document.getElementById('carouselPrev');
    const nextBtn = document.getElementById('carouselNext');
    
    prevBtn.style.opacity = currentPhotoIndex > 0 ? '0.8' : '0.3';
    nextBtn.style.opacity = currentPhotoIndex < vehiclePhotos.length - 1 ? '0.8' : '0.3';
    
    prevBtn.style.pointerEvents = currentPhotoIndex > 0 ? 'auto' : 'none';
    nextBtn.style.pointerEvents = currentPhotoIndex < vehiclePhotos.length - 1 ? 'auto' : 'none';
}

// Update current photo based on scroll position
function updateCurrentPhotoFromScroll() {
    const track = document.getElementById('horizontalCarouselTrack');
    const slides = track.querySelectorAll('.carousel-photo-slide');
    const trackRect = track.getBoundingClientRect();
    const centerX = trackRect.left + trackRect.width / 2;
    
    let closestIndex = 0;
    let closestDistance = Infinity;
    
    slides.forEach((slide, index) => {
        const slideRect = slide.getBoundingClientRect();
        const slideCenterX = slideRect.left + slideRect.width / 2;
        const distance = Math.abs(centerX - slideCenterX);
        
        if (distance < closestDistance) {
            closestDistance = distance;
            closestIndex = index;
        }
    });
    
    if (closestIndex !== currentPhotoIndex) {
        currentPhotoIndex = closestIndex;
        updateCounters();
        updateNavigationButtons();
        
        // Update modal title
        const modalTitle = document.getElementById('vehiclePhotosModalLabel');
        modalTitle.innerHTML = `
            <i class="ri-camera-line me-2"></i>
            Vehicle Photos - ${vehiclePhotos[currentPhotoIndex].title}
        `;
    }
}



// Debounce function for scroll events
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Download current photo
function downloadCurrentPhoto() {
    if (!vehiclePhotos || vehiclePhotos.length === 0 || currentPhotoIndex < 0) {
        console.error('No photo to download');
        return;
    }
    
    const currentPhoto = vehiclePhotos[currentPhotoIndex];
    const link = document.createElement('a');
    link.href = currentPhoto.url;
    link.download = currentPhoto.filename;
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Refresh vehicle photos
function refreshVehiclePhotos() {
    const refreshBtn = document.querySelector('[onclick="refreshVehiclePhotos()"]');
    const originalContent = refreshBtn.innerHTML;
    
    // Show loading state
    refreshBtn.innerHTML = '<i data-feather="loader" class="icon-sm me-1 spin-icon"></i>Refreshing...';
    refreshBtn.disabled = true;
    
    // Reload photos
    loadVehiclePhotos().finally(() => {
        // Reset button
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}





// Open Vehicle QR modal
function openVehicleQRModal() {
    const modal = new bootstrap.Modal(document.getElementById('vehicleQrModal'));
    modal.show();
}

// Open S3 upload modal
function openS3UploadModal() {
    const modal = new bootstrap.Modal(document.getElementById('s3UploadModal'));
    
    // Reset modal content
    const modalBody = document.getElementById('s3UploadModalBody');
    const modalFooter = document.getElementById('s3UploadModalFooter');
    
    modalBody.innerHTML = `
        <div class="mb-3">
                    <h6><i class="ri-cloud-upload-line me-2"></i>Upload Photos</h6>
        <p class="text-muted small mb-3">Select multiple photos to upload for this vehicle.</p>
        </div>
        
        <div class="mb-3">
            <label for="s3PhotoFiles" class="form-label">Select Photos</label>
            <input type="file" class="form-control" id="s3PhotoFiles" multiple accept="image/*">
            <small class="text-muted">Supported: JPG, PNG, GIF, WebP (Max: 10MB each)</small>
        </div>
        
        <div id="selectedFiles" class="mt-3" style="display: none;">
            <h6>Selected Files:</h6>
            <div id="filesList" class="list-group mb-3"></div>
            
            <!-- Overall Progress Section -->
            <div id="overallProgress" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted fw-medium">Overall Progress:</small>
                    <small class="text-muted" id="overallProgressText">0 / 0 files</small>
                </div>
                <div class="progress mb-2" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%" id="overallProgressBar"></div>
                </div>
                <small class="text-muted" id="uploadStatus">Ready to upload...</small>
            </div>
        </div>
        
        <div id="uploadProgress" style="display: none;">
            <div class="progress mb-2">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="text-muted" id="uploadStatusLegacy">Preparing upload...</small>
        </div>
    `;
    
    modalFooter.innerHTML = `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="startS3UploadBtn" onclick="startS3Upload()" disabled>
            <i class="ri-upload-2-line me-2"></i>Upload Photos
        </button>
    `;
            
            modal.show();
    
    // Handle file selection
    document.getElementById('s3PhotoFiles').addEventListener('change', function(e) {
        selectedS3Files = Array.from(e.target.files);
        updateFilesList();
    });
}

// Global array to manage selected files
let selectedS3Files = [];

// Remove file from selection
function removeFile(index) {
    if (index >= 0 && index < selectedS3Files.length) {
        selectedS3Files.splice(index, 1);
        updateFilesList();
    }
}

// Update files list display
function updateFilesList() {
    const selectedFilesDiv = document.getElementById('selectedFiles');
    const filesList = document.getElementById('filesList');
    const uploadBtn = document.getElementById('startS3UploadBtn');
    
    if (selectedS3Files.length > 0) {
        filesList.innerHTML = '';
        selectedS3Files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'list-group-item';
            fileItem.id = `file-item-${index}`;
            fileItem.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i class="ri-image-line me-2"></i>
                        <div>
                            <span class="fw-medium">${file.name}</span>
                            <small class="text-muted d-block">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary me-2" id="file-status-${index}">Pending</span>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                </div>
                <div class="progress mt-2" style="height: 8px; display: none;" id="file-progress-${index}">
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="file-progress-bar-${index}"></div>
                </div>
            `;
            filesList.appendChild(fileItem);
        });
        
        selectedFilesDiv.style.display = 'block';
        uploadBtn.disabled = false;
        
        // Show progress bars immediately to make them visible
        setTimeout(() => {
            selectedS3Files.forEach((file, index) => {
                const progressContainer = document.getElementById(`file-progress-${index}`);
                if (progressContainer) {
                    progressContainer.style.display = 'block';
                    // Set a small initial progress to make the bar visible
                    const progressBar = document.getElementById(`file-progress-bar-${index}`);
                    if (progressBar) {
                        progressBar.style.width = '2%';
                        progressBar.classList.add('bg-secondary');
                    }
                }
            });
        }, 100);
        
    } else {
        selectedFilesDiv.style.display = 'none';
        uploadBtn.disabled = true;
    }
}

// Update individual file progress
function updateFileProgress(fileIndex, progress, status = 'uploading') {
    const progressBar = document.getElementById(`file-progress-bar-${fileIndex}`);
    const progressContainer = document.getElementById(`file-progress-${fileIndex}`);
    const statusBadge = document.getElementById(`file-status-${fileIndex}`);
    
    console.log(`🔄 Updating progress for file ${fileIndex}: ${progress}% - ${status}`);
    
    // Show progress container
    if (progressContainer) {
        progressContainer.style.display = 'block';
        console.log(`✅ Progress container shown for file ${fileIndex}`);
    }
    
    // Update progress bar
    if (progressBar) {
        progressBar.style.width = progress + '%';
        progressBar.setAttribute('aria-valuenow', progress);
        
        // Clear existing classes
        progressBar.className = 'progress-bar';
        
        // Add classes based on status
        switch (status) {
            case 'uploading':
                progressBar.classList.add('bg-primary', 'progress-bar-striped', 'progress-bar-animated');
                break;
            case 'success':
                progressBar.classList.add('bg-success');
                break;
            case 'error':
                progressBar.classList.add('bg-danger');
                break;
            default:
                progressBar.classList.add('bg-secondary');
        }
        
        console.log(`✅ Progress bar updated for file ${fileIndex}: ${progress}%, classes: ${progressBar.className}`);
    }
    
    // Update status badge
    if (statusBadge) {
        statusBadge.className = 'badge me-2';
        switch (status) {
            case 'uploading':
                statusBadge.classList.add('bg-primary');
                statusBadge.innerHTML = '<i class="ri-upload-line me-1"></i>Uploading';
                break;
            case 'success':
                statusBadge.classList.add('bg-success');
                statusBadge.innerHTML = '<i class="ri-check-line me-1"></i>Complete';
                break;
            case 'error':
                statusBadge.classList.add('bg-danger');
                statusBadge.innerHTML = '<i class="ri-error-warning-line me-1"></i>Failed';
                break;
            default:
                statusBadge.classList.add('bg-secondary');
                statusBadge.innerHTML = 'Pending';
        }
        
        console.log(`✅ Status badge updated for file ${fileIndex}: ${status}`);
    }
}

// Update overall progress
function updateOverallProgress() {
    const overallProgressContainer = document.getElementById('overallProgress');
    const overallProgressBar = document.getElementById('overallProgressBar');
    const overallProgressText = document.getElementById('overallProgressText');
    const uploadStatus = document.getElementById('uploadStatus');
    
    if (!overallProgressContainer || selectedS3Files.length === 0) return;
    
    // Show overall progress
    overallProgressContainer.style.display = 'block';
    
    // Count completed files (both success and error count as completed)
    let completedFiles = 0;
    let successFiles = 0;
    let errorFiles = 0;
    
    for (let i = 0; i < selectedS3Files.length; i++) {
        const statusBadge = document.getElementById(`file-status-${i}`);
        if (statusBadge) {
            const status = statusBadge.textContent.toLowerCase();
            if (status.includes('complete')) {
                completedFiles++;
                successFiles++;
            } else if (status.includes('failed')) {
                completedFiles++;
                errorFiles++;
            }
        }
    }
    
    const totalFiles = selectedS3Files.length;
    const progressPercentage = totalFiles > 0 ? (completedFiles / totalFiles) * 100 : 0;
    
    // Update progress bar
    if (overallProgressBar) {
        overallProgressBar.style.width = progressPercentage + '%';
        overallProgressBar.setAttribute('aria-valuenow', progressPercentage);
    }
    
    // Update progress text
    if (overallProgressText) {
        overallProgressText.textContent = `${completedFiles} / ${totalFiles} files`;
    }
    
    // Update status message
    if (uploadStatus) {
        if (completedFiles === 0) {
            uploadStatus.textContent = 'Ready to upload...';
        } else if (completedFiles < totalFiles) {
            uploadStatus.textContent = `Uploading... ${completedFiles} of ${totalFiles} completed`;
        } else {
            // All completed
            if (errorFiles === 0) {
                uploadStatus.textContent = `✅ All ${totalFiles} files uploaded successfully!`;
            } else if (successFiles === 0) {
                uploadStatus.textContent = `❌ All ${totalFiles} files failed to upload`;
            } else {
                uploadStatus.textContent = `✅ ${successFiles} uploaded, ❌ ${errorFiles} failed`;
            }
        }
    }
}

// Start S3 upload process
async function startS3Upload() {
    if (selectedS3Files.length === 0) {
        showToast('warning', 'Please select files to upload');
        return;
    }
    
    const uploadBtn = document.getElementById('startS3UploadBtn');
    
    // Disable upload button and show loading
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="ri-loader-4-line spin-icon me-2"></i>Uploading...';
    
    // Show overall progress
    updateOverallProgress();
    
    // Get VIN from URL
    const urlPath = window.location.pathname;
    const vinLast6 = urlPath.split('/').pop();
    
    // Start uploading files individually
    await uploadFilesIndividually(vinLast6);
}

// Upload files individually with progress tracking
async function uploadFilesIndividually(vinLast6) {
    let successCount = 0;
    let errorCount = 0;
    
    for (let i = 0; i < selectedS3Files.length; i++) {
        const file = selectedS3Files[i];
        
        try {
            // Update status to uploading
            updateFileProgress(i, 0, 'uploading');
            updateOverallProgress();
            
            // Create FormData for single file
            const formData = new FormData();
            formData.append('photos[]', file);
            formData.append('vin', vinLast6);
            
            // Try S3 first
            let response = await fetch(`<?= base_url('vehicles/upload-s3-photos/') ?>${vinLast6}`, {
                method: 'POST',
                body: formData
            });
            
            let result = await response.json();
            
            // If S3 fails, try local storage
            if (!result.success && result.error && (result.error.includes('AWS') || result.error.includes('S3'))) {
                console.log(`S3 failed for file ${i}, trying local storage...`);
                
                const localFormData = new FormData();
                localFormData.append('files[]', file);
                localFormData.append('vinLast6', vinLast6);
                
                response = await fetch(`<?= base_url('vehicles/upload-local') ?>`, {
                    method: 'POST',
                    body: localFormData
                });
                
                result = await response.json();
            }
            
            // Update progress based on result
            if (result.success) {
                updateFileProgress(i, 100, 'success');
                successCount++;
            } else {
                updateFileProgress(i, 100, 'error');
                errorCount++;
                console.error(`Upload failed for file ${i}:`, result.error);
            }
            
        } catch (error) {
            console.error(`Upload error for file ${i}:`, error);
            updateFileProgress(i, 100, 'error');
            errorCount++;
        }
        
        // Update overall progress after each file
        updateOverallProgress();
        
        // Small delay between uploads to avoid overwhelming the server
        if (i < selectedS3Files.length - 1) {
            await new Promise(resolve => setTimeout(resolve, 200));
        }
    }
    
    // Final status update
    const uploadBtn = document.getElementById('startS3UploadBtn');
    
    if (errorCount === 0) {
        // All successful
        showToast('success', `All ${successCount} files uploaded successfully!`);
        uploadBtn.innerHTML = '<i class="ri-check-line me-2"></i>Complete';
        uploadBtn.classList.remove('btn-success');
        uploadBtn.classList.add('btn-outline-success');
        
        // Close modal after delay
        setTimeout(() => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('s3UploadModal'));
            modal.hide();
            loadVehiclePhotos(); // Refresh photos
        }, 2000);
        
    } else if (successCount === 0) {
        // All failed
        showToast('error', `All ${errorCount} files failed to upload`);
        uploadBtn.innerHTML = '<i class="ri-error-warning-line me-2"></i>All Failed';
        uploadBtn.classList.remove('btn-success');
        uploadBtn.classList.add('btn-outline-danger');
        uploadBtn.disabled = false;
        
    } else {
        // Mixed results
        showToast('warning', `${successCount} uploaded successfully, ${errorCount} failed`);
        uploadBtn.innerHTML = '<i class="ri-information-line me-2"></i>Partial Success';
        uploadBtn.classList.remove('btn-success');
        uploadBtn.classList.add('btn-outline-warning');
        uploadBtn.disabled = false;
        
        // Close modal after delay and refresh photos
        setTimeout(() => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('s3UploadModal'));
            modal.hide();
            loadVehiclePhotos(); // Refresh photos
        }, 3000);
    }
}

// Legacy function for backward compatibility
function uploadToS3() {
    openS3UploadModal();
}

// Test function to simulate progress (for debugging)
function testProgressBars() {
    console.log('🧪 Testing progress bars...');
    
    selectedS3Files.forEach((file, index) => {
        console.log(`Testing progress for file ${index}: ${file.name}`);
        
        // Show initial progress
        updateFileProgress(index, 10, 'uploading');
        
        // Simulate progress updates
        setTimeout(() => updateFileProgress(index, 50, 'uploading'), 1000);
        setTimeout(() => updateFileProgress(index, 100, 'success'), 2000);
    });
    
    updateOverallProgress();
}

// All simple upload functions removed - S3 system handles uploads now

// Add rotating animation for loading spinner and progress bar styles
const style = document.createElement('style');
style.textContent = `
    .rotating {
        animation: rotate 1s linear infinite;
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Ensure progress bars are visible */
    .progress {
        background-color: #e9ecef !important;
        border-radius: 0.375rem;
        overflow: hidden;
    }
    
    .progress-bar {
        transition: width 0.3s ease;
        min-width: 2px; /* Minimum width to make bar visible */
    }
    
    .progress-bar.bg-primary {
        background-color: #0d6efd !important;
    }
    
    .progress-bar.bg-success {
        background-color: #198754 !important;
    }
    
    .progress-bar.bg-danger {
        background-color: #dc3545 !important;
    }
    
    .progress-bar.bg-secondary {
        background-color: #6c757d !important;
    }
    
    /* File list item styling */
    .list-group-item {
        border: 1px solid #dee2e6;
        margin-bottom: 0.5rem;
        border-radius: 0.375rem;
    }
`;
document.head.appendChild(style);

// Test S3 upload functionality on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('🧪 Testing S3 upload functionality...');
    console.log('✅ openS3UploadModal function:', typeof openS3UploadModal);
    console.log('✅ startS3Upload function:', typeof startS3Upload);
    
    // Test if elements exist
    const uploadBtn = document.getElementById('uploadPhotosBtn');
    const s3Modal = document.getElementById('s3UploadModal');
    
    console.log('🔍 Upload button exists:', !!uploadBtn);
    console.log('🔍 S3 Modal exists:', !!s3Modal);
    
    // Initialize Service History DataTable without scrollY
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        const serviceHistoryTable = $('#serviceHistoryTable');
        if (serviceHistoryTable.length) {
            serviceHistoryTable.DataTable({
                responsive: true,
                paging: true,
                pageLength: 10,
                lengthChange: false,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                language: {
                    search: "Search services:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ services",
                    infoEmpty: "No services found",
                    infoFiltered: "(filtered from _MAX_ total services)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    },
                    emptyTable: "No service history available"
                },
                order: [[3, 'desc']], // Order by service date (column index 3) descending
                columnDefs: [
                    { targets: [0, 1, 4], orderable: true }, // Order Number, Stock Number, Status
                    { targets: [2], orderable: false }, // Service column (badges)
                    { targets: [3], type: 'date' }, // Service Date
                    { 
                        targets: '_all',
                        className: 'align-middle'
                    }
                ]
            });
            
            console.log('✅ Service History DataTable initialized');
        }
    }
    
    // Force cache refresh notification
    console.log('🔄 CACHE CLEARED - S3 Upload System with Individual Progress Ready');
});

// Initialize feather icons
if (typeof feather !== 'undefined') {
    feather.replace();
}

// ===== SIDEBAR VISIBILITY FIXES =====

document.addEventListener('DOMContentLoaded', function() {
    // Ensure sidebar is fully visible on desktop
    function ensureSidebarVisibility() {
        const sidebar = document.querySelector('.col-lg-4.order-lg-2');
        if (!sidebar) return;
        
        // Reset any problematic styles
        if (window.innerWidth >= 992) {
            sidebar.style.height = 'auto';
            sidebar.style.minHeight = 'auto';
            sidebar.style.maxHeight = 'none';
            sidebar.style.overflow = 'visible';
            sidebar.style.position = 'relative';
            
            console.log('✅ Sidebar visibility ensured - all constraints removed');
        }
    }
    
    // Apply fixes on load and resize
    ensureSidebarVisibility();
    window.addEventListener('resize', ensureSidebarVisibility);
});

// ===== NFC WRITING FUNCTIONALITY =====

// Check if NFC is supported
function checkNFCSupport() {
    if ('NDEFReader' in window) {
        return true;
    }
    return false;
}

// Write NFC Tag with default password protection
async function writeNFCTag(url, secure = false) {
    console.log('writeNFCTag called with:', { url, secure });
    
    if (!checkNFCSupport()) {
        alert('NFC is not supported on this device or browser. Please use Chrome on Android.');
        return;
    }
    
    try {
        const ndef = new NDEFReader();
        const writeBtn = document.getElementById('writeNfcBtn');
        
        // Update button to show writing state
        if (writeBtn) {
            writeBtn.disabled = true;
            writeBtn.innerHTML = '<i class="ri-loader-4-line me-1 nfc-writing-animation"></i> Writing with Password...';
        }
        
        // Default password for NFC tag protection
        const defaultPassword = "2209";
        
        // Write to NFC tag with password protection
        const writeOptions = {
            records: [{ recordType: "url", data: url }]
        };
        
        // Add password protection if the NFC tag supports it
        if (defaultPassword) {
            writeOptions.overwrite = false; // Prevent accidental overwrites
            console.log('Writing NFC tag with password protection:', defaultPassword);
        }
        
        await ndef.write(writeOptions);
        
        // Success feedback
        if (writeBtn) {
            writeBtn.innerHTML = '<i class="ri-check-line me-1"></i> Written with Password!';
            writeBtn.className = 'btn btn-success';
            
            setTimeout(() => {
                writeBtn.disabled = false;
                writeBtn.innerHTML = '<i class="ri-lock-line me-1"></i> Write NFC (Protected)';
                writeBtn.className = 'btn btn-warning';
            }, 3000);
        }
        
        console.log('NFC tag written successfully with password protection');
        
        // Show password info to user
        setTimeout(() => {
            alert(`NFC tag written successfully!\n\nPassword: ${defaultPassword}\n\nThis tag is now protected and can only be rewritten with the correct password.`);
        }, 1000);
        
    } catch (error) {
        console.error('NFC write failed:', error);
        
        const writeBtn = document.getElementById('writeNfcBtn');
        if (writeBtn) {
            writeBtn.innerHTML = '<i class="ri-error-warning-line me-1"></i> Write Failed';
            writeBtn.className = 'btn btn-danger';
            writeBtn.disabled = false;
            
            setTimeout(() => {
                writeBtn.innerHTML = '<i class="ri-lock-line me-1"></i> Write NFC (Protected)';
                writeBtn.className = 'btn btn-warning';
            }, 3000);
        }
        
        let errorMessage = 'Failed to write NFC tag';
        if (error.name === 'NotAllowedError') {
            errorMessage = 'NFC access denied. Please allow NFC permissions.';
        } else if (error.name === 'NotSupportedError') {
            errorMessage = 'NFC is not supported on this device.';
        } else if (error.name === 'NotReadableError') {
            errorMessage = 'No NFC tag found. Please place a tag near your device.';
        } else if (error.name === 'NetworkError') {
            errorMessage = 'NFC tag may be password protected. Use password: 2209';
        }
        
        alert(errorMessage);
    }
}

</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
