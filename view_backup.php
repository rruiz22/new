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

/* Vehicle Photos Modal Styles - Glassmorphism Effect */
#vehiclePhotosModal .modal-backdrop {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

#vehiclePhotosModal .modal-content {
    border: none;
    border-radius: 0;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(255, 255, 255, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    overflow: hidden;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

#vehiclePhotosModal .modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.5rem 2rem;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 0;
    position: relative;
    flex-shrink: 0;
}

#vehiclePhotosModal .modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
}

#vehiclePhotosModal .modal-title {
    color: #ffffff;
    font-weight: 600;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

#vehiclePhotosModal .btn-close {
    filter: invert(1);
    opacity: 0.8;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

#vehiclePhotosModal .btn-close:hover {
    opacity: 1;
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

#vehiclePhotosModal .modal-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.5rem 2rem;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 0;
    position: relative;
    flex-shrink: 0;
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
    color: rgba(255, 255, 255, 0.8) !important;
    font-weight: 500;
}

#vehiclePhotosModal .modal-footer .btn {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    font-weight: 500;
}

#vehiclePhotosModal .modal-footer .btn-outline-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.3);
}

#vehiclePhotosModal .modal-footer .btn-outline-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-2px);
}

#vehiclePhotosModal .modal-footer .btn-secondary {
    background: rgba(108, 117, 125, 0.3);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.2);
}

#vehiclePhotosModal .modal-footer .btn-secondary:hover {
    background: rgba(108, 117, 125, 0.5);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

#vehiclePhotosModal .modal-body {
    flex-grow: 1;
    padding: 0;
    display: flex;
    flex-direction: column;
}

#vehiclePhotosCarousel {
    height: 100%;
    min-height: auto;
    position: relative;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 0;
    flex-grow: 1;
}

#vehiclePhotosCarousel .carousel-inner {
    height: 100%;
    border-radius: 0;
    overflow: hidden;
}

#vehiclePhotosCarousel .carousel-item {
    height: 100%;
    position: relative;
}

#vehiclePhotosCarousel .carousel-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.02) 0%,
        transparent 50%,
        rgba(255, 255, 255, 0.02) 100%
    );
    pointer-events: none;
    z-index: 1;
}

#vehiclePhotosCarousel .carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: rgba(248, 250, 252, 0.1);
    filter: brightness(1.05) contrast(1.1);
    transition: all 0.4s ease;
}

#vehiclePhotosCarousel .carousel-item.active img {
    filter: brightness(1.1) contrast(1.15);
}

/* Glassmorphism Carousel Controls */
#vehiclePhotosCarousel .carousel-control-prev,
#vehiclePhotosCarousel .carousel-control-next {
    width: 60px;
    height: 60px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0.8;
    margin: 0 20px;
}

#vehiclePhotosCarousel .carousel-control-prev {
    left: 20px;
}

#vehiclePhotosCarousel .carousel-control-next {
    right: 20px;
}

#vehiclePhotosCarousel .carousel-control-prev:hover,
#vehiclePhotosCarousel .carousel-control-next:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    opacity: 1;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 
        0 10px 30px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

#vehiclePhotosCarousel .carousel-control-prev-icon,
#vehiclePhotosCarousel .carousel-control-next-icon {
    width: 24px;
    height: 24px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
}

/* Glassmorphism Carousel Indicators */
#vehiclePhotosCarousel .carousel-indicators {
    bottom: 20px;
    background: rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 25px;
    padding: 8px 20px;
    margin-bottom: 0;
    width: auto;
    left: 50%;
    transform: translateX(-50%);
    box-shadow: 
        0 10px 30px rgba(0, 0, 0, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

#vehiclePhotosCarousel .carousel-indicators [data-bs-target] {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.4);
    margin: 0 4px;
    background-color: transparent;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

#vehiclePhotosCarousel .carousel-indicators [data-bs-target]::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translate(-50%, -50%);
}

#vehiclePhotosCarousel .carousel-indicators .active {
    border-color: rgba(255, 255, 255, 0.8);
    transform: scale(1.2);
    box-shadow: 
        0 0 20px rgba(255, 255, 255, 0.3),
        0 0 0 2px rgba(255, 255, 255, 0.1);
}

#vehiclePhotosCarousel .carousel-indicators .active::before {
    width: 8px;
    height: 8px;
}

#vehiclePhotosCarousel .carousel-indicators [data-bs-target]:hover {
    border-color: rgba(255, 255, 255, 0.7);
    transform: scale(1.1);
}

/* Glass overlay effects */
.glass-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.1) 0%,
        rgba(255, 255, 255, 0.05) 50%,
        rgba(255, 255, 255, 0.1) 100%
    );
    pointer-events: none;
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

@media (max-width: 576px) {
    .vehicle-photos-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    #vehiclePhotosCarousel {
        height: 40vh;
        min-height: 250px;
    }
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
                            <span class="badge bg-info text-white px-2 px-sm-3 py-1 py-sm-2" style="font-size: 0.75rem; font-size-sm: 0.85rem;">
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

    <!-- Main Content Grid - Responsive Layout -->
    <div class="row g-3 g-lg-4">
        <!-- Main Content Area (Wide Blocks) - Left Side -->
        <div class="col-12 col-lg-8 col-xl-9 order-2 order-lg-1">
            
            <!-- Vehicle Photos Section - Wide Content -->
            <div class="card mb-4">
                <div class="card-header d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                    <h4 class="card-title mb-0 flex-grow-1 service-orders-card-title">
                        <i data-feather="camera" class="icon-sm me-1"></i>
                        <span class="d-none d-sm-inline"><?= lang('App.vehicle_photos') ?></span>
                        <span class="d-sm-none">Photos</span>
                    </h4>
                    <div class="flex-shrink-0 d-flex flex-wrap gap-2">
                        <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
                        <button class="btn btn-outline-secondary btn-sm" onclick="editPhotosUrl()">
                            <i data-feather="edit-3" class="icon-sm me-1"></i>
                            <span class="d-none d-md-inline"><?= lang('App.edit_photos_url') ?></span>
                            <span class="d-md-none">Edit</span>
                        </button>
                        <?php endif; ?>
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshVehiclePhotos()">
                            <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                            <span class="d-none d-md-inline"><?= lang('App.refresh') ?></span>
                            <span class="d-md-none">Refresh</span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="vehiclePhotosContainer">
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
                    <div class="flex-shrink-0">
                        <small class="text-muted d-none d-md-inline">
                            <i data-feather="mouse-pointer" class="icon-xs me-1"></i>
                            <?= lang('App.click_row_to_view_details') ?>
                        </small>
                        <small class="text-muted d-md-none">
                            Tap to view
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($vehicle['services'])): ?>
                    <!-- Debug info - remove this later -->
                    <?php if (ENVIRONMENT === 'development'): ?>
                    <div class="alert alert-info small mb-3">
                        <strong>Debug:</strong> Found <?= count($vehicle['services'] ?? []) ?> service(s)<br>
                        <?php if (!empty($vehicle['services'])): ?>
                            <?php foreach ($vehicle['services'] as $idx => $service): ?>
                                <div class="mt-2 p-2 border border-secondary rounded">
                                    <strong>Service <?= $idx + 1 ?>:</strong><br>
                                    <strong>ID:</strong> <?= $service['id'] ?? 'N/A' ?><br>
                                    <strong>Order Number:</strong> <?= $service['order_number'] ?? 'N/A' ?><br>
                                    <strong>Direct Service Name:</strong> <?= $service['service_name'] ?? 'NULL' ?><br>
                                    <strong>Direct Service Color:</strong> <?= $service['service_color'] ?? 'NULL' ?><br>
                                    <strong>Services Field:</strong> <?= $service['services'] ?? 'NULL' ?><br>
                                    <strong>Order Services Count:</strong> <?= count($service['order_services'] ?? []) ?><br>
                                    <?php if (!empty($service['order_services'])): ?>
                                        <strong>Order Services:</strong> <?= json_encode($service['order_services']) ?><br>
                                    <?php endif; ?>
                                    <strong>Status:</strong> <?= $service['status'] ?? 'N/A' ?><br>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover align-middle mb-0 service-orders-table dt-responsive" id="serviceHistoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none d-sm-table-cell"><?= lang('App.order_number') ?></th>
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
                                                <span class="fw-medium text-break"><?= esc($service['order_number']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Order #<?= $service['id'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- Mobile: Show order number inline -->
                                            <div class="d-sm-none">
                                                <small class="text-muted d-block">
                                                    <?php if (!empty($service['order_number'])): ?>
                                                        <?= esc($service['order_number']) ?>
                                                    <?php else: ?>
                                                        Order #<?= $service['id'] ?>
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
                                        <td colspan="4" class="text-center py-4">
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
            
        </div>

        <!-- Sidebar (Narrow Blocks) - Right Side -->
        <div class="col-12 col-lg-4 col-xl-3 order-1 order-lg-2">
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
                            <?= esc($vehicle['vehicle']) ?>
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
        </div>
    </div>


    
    <!-- Location History Section - Full Width -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card">
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
                            <div id="locationHistoryMap" class="location-history-map"></div>
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
    </div>
</div>

<!-- Edit Photos URL Modal -->
<div class="modal fade" id="editPhotosUrlModal" tabindex="-1" aria-labelledby="editPhotosUrlModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPhotosUrlModalLabel">
                    <i class="ri-link me-2"></i>
                    <?= lang('App.edit_photos_url') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="photosUrlForm">
                    <div class="mb-3">
                        <label for="photosUrlInput" class="form-label">
                            <i class="ri-cloud-line me-1"></i>
                            <?= lang('App.photos_storage_url') ?>
                        </label>
                        <textarea class="form-control" id="photosUrlInput" rows="6" 
                                  placeholder="Enter photo URLs (one per line):&#10;&#10;✅ WORKING FORMATS:&#10;• Direct images: https://example.com/photo.jpg&#10;• Google Drive: https://drive.google.com/uc?id=FILE_ID&#10;• Imgur: https://i.imgur.com/photo.jpg&#10;&#10;❌ NOT WORKING:&#10;• Google Drive folders&#10;• Password protected URLs&#10;• URLs requiring login"
                                  maxlength="2000"></textarea>
                        <div class="form-text">
                            <i class="ri-information-line me-1"></i>
                            <?= lang('App.photos_url_help_v2') ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="ri-lightbulb-line me-1"></i>
                                    <?= lang('App.supported_url_types') ?>
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong class="text-success">✅ <?= lang('App.working_urls') ?>:</strong>
                                        <ul class="small mb-2">
                                            <li><?= lang('App.direct_image_urls') ?></li>
                                            <li><?= lang('App.google_drive_direct') ?></li>
                                            <li><?= lang('App.dropbox_direct') ?></li>
                                            <li><?= lang('App.imgur_direct') ?></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <strong class="text-warning">❌ <?= lang('App.not_working_urls') ?>:</strong>
                                        <ul class="small mb-0">
                                            <li><?= lang('App.dynamic_list_pages') ?></li>
                                            <li><?= lang('App.dynamic_galleries') ?></li>
                                            <li><?= lang('App.password_protected') ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-eye-line me-1"></i>
                            <?= lang('App.current_url') ?>
                        </label>
                        <div class="input-group">
                            <textarea class="form-control" id="currentPhotosUrl" rows="3" readonly></textarea>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyCurrentUrl()">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <?= lang('App.cancel') ?>
                </button>
                <button type="button" class="btn btn-primary" onclick="savePhotosUrl()">
                    <i class="ri-save-line me-1"></i>
                    <?= lang('App.save_url') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Vehicle Photos Carousel Modal -->
<div class="modal fade" id="vehiclePhotosModal" tabindex="-1" aria-labelledby="vehiclePhotosModalLabel" aria-hidden="true">
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
                <div id="vehiclePhotosCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carouselContent">
                        <!-- Photos will be loaded here -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#vehiclePhotosCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#vehiclePhotosCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    <div class="carousel-indicators" id="carouselIndicators">
                        <!-- Indicators will be loaded here -->
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

<!-- S3 Upload Modal -->
<div class="modal fade" id="s3UploadModal" tabindex="-1" aria-labelledby="s3UploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="s3UploadModalLabel">
                    <i class="ri-cloud-upload-line me-2"></i>
                    Upload Photos to AWS S3
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
<!-- Leaflet JS for Location History Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Vehicle NFC Location Tracking JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Load location history on page load
    loadLocationHistory();
    
    // Load vehicle photos on page load
    loadVehiclePhotos();
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
                        <p class="text-muted">This vehicle hasn't been tracked yet.<br>Use the NFC code to start tracking.</p>
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
                    <div class="badge bg-primary text-white rounded-circle me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                        ${location.spot_number}
                    </div>
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

// Load vehicle photos from custom URL
// Load vehicle photos - Try S3 first, then custom URLs as fallback
async function loadVehiclePhotos() {
    const container = document.getElementById('vehiclePhotosContainer');
    if (!container) return;
    
    // Get VIN last 6 characters from current URL
    const urlPath = window.location.pathname;
    const vinLast6 = urlPath.split('/').pop();
    
    try {
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Loading photos from AWS S3...</p>
                <small class="text-muted">Checking S3 bucket first...</small>
            </div>
        `;
        
        // STEP 1: Try to get photos from S3 first
        console.log('🔄 Attempting to load photos from S3...');
        try {
            const s3Response = await fetch(`<?= base_url('vehicles/s3-photos/') ?>${vinLast6}`);
            const s3Data = await s3Response.json();
            
            console.log('📊 S3 response:', s3Data);
            
            if (s3Data.success && s3Data.photos && s3Data.photos.length > 0) {
                console.log('✅ Found photos in S3:', s3Data.photos.length);
                
                // Convert S3 photos to expected format
                vehiclePhotos = s3Data.photos.map((photo, index) => ({
                    id: photo.id || index + 1,
                    filename: photo.name,
                    url: photo.url,
                    thumbnail: photo.thumbnail,
                    title: `Photo ${index + 1}`,
                    description: photo.name,
                    uploadDate: photo.uploaded_at,
                    source: 'S3'
                }));
                
                displayVehiclePhotos(vehiclePhotos, 'S3');
                return; // Success with S3, exit function
            } else {
                console.log('❌ No photos found in S3, trying custom URLs...');
            }
        } catch (s3Error) {
            console.warn('⚠️ S3 photos failed, falling back to custom URLs:', s3Error);
        }
        
        // STEP 2: Fallback to custom URL system
        container.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Checking custom photo URLs...</p>
                <small class="text-muted">S3 bucket empty, checking custom URLs...</small>
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
                    vehiclePhotos = photos;
                    displayVehiclePhotos(photos);
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
    
    // Check for Google Drive direct download URLs (these ARE direct)
    if (lowerUrl.includes('drive.google.com/uc?') && lowerUrl.includes('id=')) {
        console.log(`✅ Detected Google Drive direct download URL: ${url}`);
        return true;
    }
    
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
        
        if (url.includes('drive.google.com')) {
            photos = await extractPhotosFromGoogleDrive(doc, url);
        } else if (url.includes('dropbox.com')) {
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

// Show message when no URL is set (for staff to configure)
function showNoUrlSetMessage() {
    const container = document.getElementById('vehiclePhotosContainer');
    const isStaff = <?= json_encode(auth()->user() && in_array(auth()->user()->user_type, ['staff', 'admin'])) ?>;
    
    if (isStaff) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="ri-link-unlink display-4 text-muted mb-3"></i>
                <h6><?= lang('App.no_photos_url_set') ?></h6>
                <p class="text-muted mb-3"><?= lang('App.no_photos_url_help_staff') ?></p>
                <button class="btn btn-primary" onclick="editPhotosUrl()">
                    <i class="ri-link me-1"></i>
                    <?= lang('App.set_photos_url') ?>
                </button>
            </div>
        `;
    } else {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="ri-image-line display-4 text-muted mb-3"></i>
                <h6><?= lang('App.no_photos_available') ?></h6>
                <p class="text-muted mb-0"><?= lang('App.no_photos_url_help_user') ?></p>
            </div>
        `;
    }
}

// Extract photos from Google Drive
async function extractPhotosFromGoogleDrive(doc, baseUrl) {
    console.log('🔍 Attempting to extract photos from Google Drive...');
    const photos = [];
    
    try {
        // Google Drive folder URLs don't work with CORS proxy due to authentication
        if (baseUrl.includes('/drive/folders/')) {
            console.log('❌ Google Drive folder URLs are not supported via CORS proxy');
            console.log('💡 Suggestion: Use direct download links instead');
            console.log('   Format: https://drive.google.com/uc?id=FILE_ID');
            return [];
        }
        
        // For Google Drive file view URLs, try to extract the file ID and convert to direct download
        if (baseUrl.includes('/file/d/')) {
            const fileIdMatch = baseUrl.match(/\/file\/d\/([a-zA-Z0-9_-]+)/);
            if (fileIdMatch) {
                const fileId = fileIdMatch[1];
                
                // Try multiple Google Drive direct URL formats
                const directUrls = [
                    `https://drive.google.com/uc?id=${fileId}`,
                    `https://drive.google.com/uc?export=view&id=${fileId}`,
                    `https://drive.google.com/thumbnail?id=${fileId}&sz=w800-h600`
                ];
                
                console.log(`🔄 Converting Google Drive view URL for file ID: ${fileId}`);
                
                for (let i = 0; i < directUrls.length; i++) {
                    const testUrl = directUrls[i];
                    console.log(`🧪 Testing Google Drive URL format ${i + 1}: ${testUrl}`);
                    
                    const isImage = await testImageUrl(testUrl);
                    if (isImage) {
                        photos.push({
                            id: 1,
                            filename: `google_drive_${fileId}.jpg`,
                            url: testUrl,
                            thumbnail: testUrl,
                            title: 'Google Drive Image',
                            description: 'Image from Google Drive',
                            uploadDate: new Date().toISOString()
                        });
                        console.log(`✅ Successfully converted Google Drive URL (format ${i + 1})`);
                        break;
                    } else {
                        console.log(`❌ Format ${i + 1} failed - URL not accessible or not an image`);
                    }
                }
                
                if (photos.length === 0) {
                    console.log('❌ All Google Drive URL formats failed. Possible reasons:');
                    console.log('   • File is not public (sharing permissions required)');
                    console.log('   • File is not an image');
                    console.log('   • Google Drive requires authentication');
                    console.log('💡 Suggestion: Make sure the file is publicly shared');
                }
            }
        }
        
        // Look for any direct download links in the HTML
        const links = doc.querySelectorAll('a[href*="drive.google.com/uc"]');
        links.forEach((link, index) => {
            const href = link.href;
            if (href.includes('id=')) {
                photos.push({
                    id: photos.length + 1,
                    filename: `google_drive_image_${index + 1}.jpg`,
                    url: href,
                    thumbnail: href,
                    title: link.textContent || `Google Drive Image ${index + 1}`,
                    description: 'Image from Google Drive',
                    uploadDate: new Date().toISOString()
                });
            }
        });
        
    } catch (error) {
        console.error('❌ Error extracting from Google Drive:', error);
    }
    
    console.log(`📊 Google Drive extraction result: ${photos.length} photos found`);
    return photos;
}

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
const PHOTOS_PER_PAGE = 9;

// Display vehicle photos in the grid with pagination
function displayVehiclePhotos(photos, source = '') {
    const container = document.getElementById('vehiclePhotosContainer');
    
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
    
    const sourceIcon = currentPhotoSource === 'S3' ? 'ri-cloud-line' : 'ri-link';
    const sourceColor = currentPhotoSource === 'S3' ? 'text-success' : 'text-info';
    
    let photosHtml = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="mb-0">
                    ${allVehiclePhotos.length} Photo${allVehiclePhotos.length !== 1 ? 's' : ''} Available
                    ${currentPhotoSource ? `<small class="badge bg-light ${sourceColor} ms-2">
                        <i class="${sourceIcon} me-1"></i>${currentPhotoSource}
                    </small>` : ''}
                </h6>
                <small class="text-muted">
                    ${hasMorePhotos ? `Showing ${photosToShow} of ${allVehiclePhotos.length} photos • ` : ''}
                    Click any photo to view in fullscreen
                </small>
            </div>
            <div>
                <button class="btn btn-outline-primary btn-sm" onclick="openPhotosModal(0)">
                    <i data-feather="maximize-2" class="icon-sm me-1"></i>
                    View Gallery
                </button>
                ${currentPhotoSource === 'S3' ? `
                    <button class="btn btn-outline-success btn-sm ms-1" onclick="uploadToS3()">
                        <i class="ri-upload-cloud-2-line icon-sm me-1"></i>
                        Upload More
                    </button>
                ` : ''}
            </div>
        </div>
        <div class="vehicle-photos-grid" id="photosGrid">
    `;
    
    visiblePhotos.forEach((photo, index) => {
        photosHtml += `
            <div class="photo-thumbnail" onclick="openPhotosModal(${index})" title="${photo.title}">
                <img src="${photo.thumbnail}" alt="${photo.title}" 
                     onerror="this.style.display='none'; this.parentNode.classList.add('photo-error-placeholder')"
                     loading="lazy">
                <div class="photo-overlay">
                    <i class="ri-eye-line photo-overlay-icon"></i>
                </div>
                ${currentPhotoSource === 'S3' ? `
                    <div class="photo-source-badge">
                        <i class="ri-cloud-line"></i>
                    </div>
                ` : ''}
            </div>
        `;
    });
    
    photosHtml += '</div>';
    
    // Add Load More button if there are more photos
    if (hasMorePhotos) {
        const remainingPhotos = allVehiclePhotos.length - photosToShow;
        photosHtml += `
            <div class="text-center mt-4" id="loadMoreSection">
                <button class="btn btn-outline-primary" onclick="loadMorePhotos()" id="loadMoreBtn">
                    <i class="ri-add-line me-2"></i>
                    Load More Photos
                    <span class="badge bg-light text-dark ms-2">${remainingPhotos}</span>
                </button>
                <div class="text-muted mt-2" style="font-size: 0.875rem;">
                    <i class="ri-information-line me-1"></i>
                    ${remainingPhotos} more photo${remainingPhotos !== 1 ? 's' : ''} available
                </div>
            </div>
        `;
    }
    
    container.innerHTML = photosHtml;
    photosShownCount = photosToShow;
    
    // Initialize feather icons for the new content
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Load more photos function
function loadMorePhotos() {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const originalText = loadMoreBtn.innerHTML;
    
    // Show loading state
    loadMoreBtn.innerHTML = '<i class="ri-loader-4-line spin-icon me-2"></i>Loading...';
    loadMoreBtn.disabled = true;
    
    // Simulate brief loading delay for better UX
    setTimeout(() => {
        renderPhotoGrid();
        
        // Scroll to the new photos smoothly
        const photosGrid = document.getElementById('photosGrid');
        if (photosGrid) {
            const newPhotos = photosGrid.children;
            if (newPhotos.length > PHOTOS_PER_PAGE) {
                const targetPhoto = newPhotos[photosShownCount - PHOTOS_PER_PAGE];
                if (targetPhoto) {
                    targetPhoto.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
            }
        }
        
        // Show toast notification
        const remainingPhotos = allVehiclePhotos.length - photosShownCount;
        const loadedPhotos = Math.min(PHOTOS_PER_PAGE, allVehiclePhotos.length - (photosShownCount - PHOTOS_PER_PAGE));
        
        if (remainingPhotos === 0) {
            showToast('success', `All ${allVehiclePhotos.length} photos are now loaded!`);
        } else {
            showToast('info', `Loaded ${loadedPhotos} more photos. ${remainingPhotos} remaining.`);
        }
        
    }, 300); // Brief delay for loading animation
}

// Show no photos message with context-aware options
function showNoPhotosMessage() {
    const container = document.getElementById('vehiclePhotosContainer');
    const isStaff = <?= json_encode(auth()->user() && in_array(auth()->user()->user_type, ['staff', 'admin'])) ?>;
    const hasConfiguredUrl = window.currentPhotosUrl && window.currentPhotosUrl.trim() !== '';
    
    let messageContent = '';
    
    if (hasConfiguredUrl) {
        // URLs are configured but no photos found
        const isGoogleDrive = window.currentPhotosUrl && window.currentPhotosUrl.includes('drive.google.com');
        
        let helpMessage = '<?= lang('App.no_photos_found_help') ?>';
        let suggestions = '';
        
        if (isGoogleDrive) {
            helpMessage = 'The Google Drive URL could not be accessed or does not contain valid images.';
            suggestions = `
                <div class="alert alert-warning mt-3 mb-3" style="font-size: 0.875rem; text-align: left;">
                    <strong><i class="ri-information-line me-1"></i> Google Drive Requirements:</strong><br>
                    • File must be <strong>publicly shared</strong> (Anyone with the link can view)<br>
                    • File must be an image format (JPG, PNG, GIF, WebP, etc.)<br>
                    • Use direct download format: <br>
                      <code style="font-size: 0.8rem;">https://drive.google.com/uc?id=FILE_ID</code><br>
                    • Folder URLs are not supported
                </div>
            `;
        }
        
        messageContent = `
            <div class="no-photos-message">
                <i class="ri-image-2-line" style="font-size: 2.5rem; color: #6c757d; margin-bottom: 1rem;"></i>
                <h6><?= lang('App.no_photos_found') ?></h6>
                <p class="text-muted mb-3">${helpMessage}</p>
                ${suggestions}
                <div class="d-flex gap-2 justify-content-center">
                    ${isStaff ? `
                        <button class="btn btn-outline-primary btn-sm" onclick="editPhotosUrl()">
                            <i class="ri-edit-line me-1"></i>
                            <?= lang('App.edit_photos_url') ?>
                        </button>
                    ` : ''}
                </div>
            </div>
        `;
    } else {
        // No URLs configured
        if (isStaff) {
            messageContent = `
                <div class="no-photos-message">
                    <i class="ri-link-unlink" style="font-size: 2.5rem; color: #6c757d; margin-bottom: 1rem;"></i>
                    <h6><?= lang('App.no_photos_url_set') ?></h6>
                    <p class="text-muted mb-3"><?= lang('App.no_photos_url_help_staff') ?></p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-primary btn-sm" onclick="editPhotosUrl()">
                            <i class="ri-link me-1"></i>
                            <?= lang('App.set_photos_url') ?>
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
                ${isStaff ? `
                    <button class="btn btn-outline-primary btn-sm" onclick="editPhotosUrl()">
                        <i class="ri-edit-line me-1"></i>
                        <?= lang('App.edit_photos_url') ?>
                    </button>
                ` : ''}
                <button class="btn btn-outline-secondary btn-sm" onclick="showDemoPhotos()">
                    <i class="ri-image-line me-1"></i>
                    <?= lang('App.show_demo_photos') ?>
                </button>
            </div>
        </div>
    `;
}

// Open photos modal at specific index
function openPhotosModal(photoIndex = 0) {
    if (!vehiclePhotos || vehiclePhotos.length === 0) {
        console.error('No photos available to display');
        return;
    }
    
    currentPhotoIndex = photoIndex;
    
    // Populate carousel content
    const carouselContent = document.getElementById('carouselContent');
    const carouselIndicators = document.getElementById('carouselIndicators');
    const photoCounter = document.getElementById('photoCounter');
    
    let carouselHtml = '';
    let indicatorsHtml = '';
    
    vehiclePhotos.forEach((photo, index) => {
        const isActive = index === photoIndex ? 'active' : '';
        
        carouselHtml += `
            <div class="carousel-item ${isActive}">
                <img src="${photo.url}" alt="${photo.title}" 
                     onerror="this.style.display='none'; this.parentNode.classList.add('carousel-error-placeholder')"
                     class="d-block w-100">
            </div>
        `;
        
        indicatorsHtml += `
            <button type="button" data-bs-target="#vehiclePhotosCarousel" 
                    data-bs-slide-to="${index}" ${index === photoIndex ? 'class="active" aria-current="true"' : ''}
                    aria-label="Photo ${index + 1}"></button>
        `;
    });
    
    carouselContent.innerHTML = carouselHtml;
    carouselIndicators.innerHTML = indicatorsHtml;
    photoCounter.textContent = `${photoIndex + 1} of ${vehiclePhotos.length}`;
    
    // Update modal title
    const modalTitle = document.getElementById('vehiclePhotosModalLabel');
    modalTitle.innerHTML = `
        <i class="ri-camera-line me-2"></i>
        Vehicle Photos - ${vehiclePhotos[photoIndex].title}
    `;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('vehiclePhotosModal'));
    modal.show();
    
    // Update counter when carousel slides
    const carousel = document.getElementById('vehiclePhotosCarousel');
    carousel.addEventListener('slide.bs.carousel', function(event) {
        currentPhotoIndex = event.to;
        photoCounter.textContent = `${event.to + 1} of ${vehiclePhotos.length}`;
        
        // Update modal title
        modalTitle.innerHTML = `
            <i class="ri-camera-line me-2"></i>
            Vehicle Photos - ${vehiclePhotos[event.to].title}
        `;
    });
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

// Edit photos URL (Staff/Admin only)
function editPhotosUrl() {
    const modal = new bootstrap.Modal(document.getElementById('editPhotosUrlModal'));
    
    // Get VIN last 6 characters from current URL
    const urlPath = window.location.pathname;
    const vinLast6 = urlPath.split('/').pop();
    
    // Fetch current URL from server first
    fetch(`<?= base_url('vehicles/photos-url/') ?>${vinLast6}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const currentUrlInput = document.getElementById('currentPhotosUrl');
                const photosUrlInput = document.getElementById('photosUrlInput');
                
                const currentUrl = data.photos_url || '';
                
                // Set values correctly
                currentUrlInput.value = currentUrl;
                photosUrlInput.value = currentUrl;
                
                // Store for future use
                window.currentPhotosUrl = currentUrl;
            } else {
                console.warn('Could not fetch current photos URL:', data.error);
                // Set empty values if can't fetch
                document.getElementById('currentPhotosUrl').value = '';
                document.getElementById('photosUrlInput').value = '';
            }
            
            modal.show();
        })
        .catch(error => {
            console.error('Error fetching current photos URL:', error);
            // Set empty values on error
            document.getElementById('currentPhotosUrl').value = '';
            document.getElementById('photosUrlInput').value = '';
            modal.show();
        });
}

// Save photos URL
async function savePhotosUrl() {
    const photosUrlInput = document.getElementById('photosUrlInput');
    const newUrl = photosUrlInput.value.trim();
    
    // Get VIN last 6 characters from current URL
    const urlPath = window.location.pathname;
    const vinLast6 = urlPath.split('/').pop();
    
    try {
        // Show loading state
        const saveBtn = document.querySelector('[onclick="savePhotosUrl()"]');
        const originalContent = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="ri-loader-4-line spin-icon me-1"></i>Saving...';
        saveBtn.disabled = true;
        
        // Save the URL
        const response = await fetch(`<?= base_url('vehicles/photos-url/') ?>${vinLast6}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `photos_url=${encodeURIComponent(newUrl)}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update stored URL
            window.currentPhotosUrl = newUrl;
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editPhotosUrlModal'));
            modal.hide();
            
            // Show success message
            showToast('success', data.message || '<?= lang('App.photos_url_saved') ?>');
            
            // Reload photos with new URL
            loadVehiclePhotos();
            
        } else {
            throw new Error(data.error || 'Failed to save URL');
        }
        
        // Reset button
        saveBtn.innerHTML = originalContent;
        saveBtn.disabled = false;
        
    } catch (error) {
        console.error('Error saving photos URL:', error);
        showToast('error', error.message || '<?= lang('App.error_saving_url') ?>');
        
        // Reset button
        const saveBtn = document.querySelector('[onclick="savePhotosUrl()"]');
        saveBtn.innerHTML = '<i class="ri-save-line me-1"></i><?= lang('App.save_url') ?>';
        saveBtn.disabled = false;
    }
}

// Copy current URL to clipboard
function copyCurrentUrl() {
    const currentUrlInput = document.getElementById('currentPhotosUrl');
    
    if (!currentUrlInput.value) {
        showToast('warning', '<?= lang('App.no_url_to_copy') ?>');
        return;
    }
    
    currentUrlInput.select();
    currentUrlInput.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        
        // Show success feedback
        const copyBtn = document.querySelector('[onclick="copyCurrentUrl()"]');
        const originalContent = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="ri-check-line"></i>';
        copyBtn.className = 'btn btn-success';
        
        setTimeout(() => {
            copyBtn.innerHTML = originalContent;
            copyBtn.className = 'btn btn-outline-secondary';
        }, 2000);
        
        showToast('success', '<?= lang('App.url_copied') ?>');
        
    } catch (err) {
        console.error('Failed to copy URL: ', err);
        showToast('error', '<?= lang('App.copy_failed') ?>');
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
                    <i class="ri-cloud-off-line" style="font-size: 4rem; color: #6c757d; margin-bottom: 1rem;"></i>
                    <h5>No Photos in AWS S3</h5>
                    <p class="text-muted mb-4">
                        This vehicle doesn't have photos in your S3 bucket yet.<br>
                        Upload photos directly to AWS S3 for best performance.
                    </p>
                </div>
                
                <div class="d-flex gap-2 justify-content-center mb-4">
                    <button class="btn btn-success" onclick="openS3UploadModal()">
                        <i class="ri-upload-cloud-2-line me-2"></i>
                        Upload Photos to S3
                    </button>
                    <button class="btn btn-outline-secondary" onclick="editPhotosUrl()">
                        <i class="ri-link me-2"></i>
                        Or Set Custom URL
                    </button>
                </div>
                
                <div class="alert alert-info" style="max-width: 500px; margin: 0 auto; text-align: left;">
                    <div class="d-flex">
                        <i class="ri-information-line me-2 mt-1"></i>
                        <div>
                            <strong>S3 Benefits:</strong><br>
                            <small>
                                • Faster loading times<br>
                                • Automatic thumbnails<br>
                                • Secure cloud storage<br>
                                • Easy management
                            </small>
                        </div>
                    </div>
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

// Open S3 upload modal
function openS3UploadModal() {
    const modal = new bootstrap.Modal(document.getElementById('s3UploadModal'));
    
    // Reset modal content
    const modalBody = document.getElementById('s3UploadModalBody');
    const modalFooter = document.getElementById('s3UploadModalFooter');
    
    modalBody.innerHTML = `
        <div class="mb-3">
            <h6><i class="ri-cloud-upload-line me-2"></i>Upload Photos to AWS S3</h6>
            <p class="text-muted small mb-3">Select multiple photos to upload directly to your S3 bucket.</p>
        </div>
        
        <div class="mb-3">
            <label for="s3PhotoFiles" class="form-label">Select Photos</label>
            <input type="file" class="form-control" id="s3PhotoFiles" multiple accept="image/*">
            <small class="text-muted">Supported: JPG, PNG, GIF, WebP (Max: 10MB each)</small>
        </div>
        
        <div id="selectedFiles" class="mt-3" style="display: none;">
            <h6>Selected Files:</h6>
            <div id="filesList" class="list-group"></div>
        </div>
        
        <div id="uploadProgress" style="display: none;">
            <div class="progress mb-2">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="text-muted" id="uploadStatus">Preparing upload...</small>
        </div>
    `;
    
    modalFooter.innerHTML = `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="startUploadBtn" onclick="startS3Upload()" disabled>
            <i class="ri-upload-2-line me-2"></i>Upload to S3
        </button>
    `;
    
    modal.show();
    
    // Handle file selection
    document.getElementById('s3PhotoFiles').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const selectedFilesDiv = document.getElementById('selectedFiles');
        const filesList = document.getElementById('filesList');
        const uploadBtn = document.getElementById('startUploadBtn');
        
        if (files.length > 0) {
            filesList.innerHTML = '';
            files.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                fileItem.innerHTML = `
                    <div>
                        <i class="ri-image-line me-2"></i>
                        <span>${file.name}</span>
                        <small class="text-muted ms-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                        <i class="ri-close-line"></i>
                    </button>
                `;
                filesList.appendChild(fileItem);
            });
            
            selectedFilesDiv.style.display = 'block';
            uploadBtn.disabled = false;
        } else {
            selectedFilesDiv.style.display = 'none';
            uploadBtn.disabled = true;
        }
    });
}

// Start S3 upload process
async function startS3Upload() {
    const fileInput = document.getElementById('s3PhotoFiles');
    const files = Array.from(fileInput.files);
    
    if (files.length === 0) {
        showToast('warning', 'Please select files to upload');
        return;
    }
    
    const progressDiv = document.getElementById('uploadProgress');
    const progressBar = progressDiv.querySelector('.progress-bar');
    const statusText = document.getElementById('uploadStatus');
    const uploadBtn = document.getElementById('startUploadBtn');
    
    // Show progress and disable button
    progressDiv.style.display = 'block';
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="ri-loader-4-line spin-icon me-2"></i>Uploading...';
    
    // Get VIN from URL
    const urlPath = window.location.pathname;
    const vinLast6 = urlPath.split('/').pop();
    
    try {
        const formData = new FormData();
        
        // Add files to form data
        files.forEach((file, index) => {
            formData.append('photos[]', file);
        });
        
        // Add VIN
        formData.append('vin', vinLast6);
        
        statusText.textContent = `Uploading ${files.length} file(s) to S3...`;
        
        // Upload to S3 endpoint
        const response = await fetch(`<?= base_url('vehicles/upload-s3-photos/') ?>${vinLast6}`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            progressBar.style.width = '100%';
            statusText.textContent = `Successfully uploaded ${result.total_uploaded} photo(s)!`;
            
            // Show success message
            showToast('success', `${result.total_uploaded} photos uploaded to S3 successfully!`);
            
            // Close modal after delay
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('s3UploadModal'));
                modal.hide();
                
                // Reload photos to show new uploads
                loadVehiclePhotos();
            }, 2000);
            
        } else {
            throw new Error(result.error || 'Upload failed');
        }
        
    } catch (error) {
        console.error('S3 upload error:', error);
        progressBar.style.width = '0%';
        statusText.textContent = 'Upload failed';
        showToast('error', error.message || 'Failed to upload photos to S3');
        
        // Re-enable button
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="ri-upload-2-line me-2"></i>Upload to S3';
    }
}

// Legacy function for backward compatibility
function uploadToS3() {
    openS3UploadModal();
}

// Toast notification function using Toastify
function showToast(type, message) {
    if (typeof Toastify !== 'undefined') {
        const colors = {
            'success': '#10b981',
            'error': '#ef4444', 
            'warning': '#f59e0b',
            'info': '#3b82f6'
        };
        
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            style: {
                background: colors[type] || colors.info,
            }
        }).showToast();
    } else {
        // Fallback to alert if Toastify is not available
        alert(message);
    }
}
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 