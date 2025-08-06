<style>
/* =================================================================
   VEHICLES MODULE - SHARED STYLES (SERVICE ORDERS PATTERN)
   Consistent styling with ServiceOrders module
   ================================================================= */

/* RESET Y BASE PARA TÍTULO RESPONSIVE */
.vehicles-dashboard-card-title {
    font-size: clamp(0.8rem, 2.5vw, 1.25rem) !important;
    font-weight: 600 !important;
    color: #1f2937 !important;
    text-align: center !important;
    margin: 0 !important;
    padding: 0.25rem 0.5rem !important;
    line-height: 1.2 !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
    hyphens: auto !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

/* CONTENEDOR DEL HEADER SIMPLIFICADO */
.card-header.d-flex {
    display: flex !important;
    flex-wrap: wrap !important;
    justify-content: space-between !important;
    align-items: center !important;
    gap: 0.5rem !important;
    padding: 1rem !important;
}

.card-header .flex-grow-1 {
    flex: 1 1 60% !important;
    min-width: 200px !important;
    text-align: center !important;
}

.card-header .flex-shrink-0 {
    flex: 0 0 auto !important;
}

/* RESPONSIVE BREAKPOINTS SIMPLIFICADOS */
@media (max-width: 768px) {
    .card-header.d-flex {
        flex-direction: column !important;
        text-align: center !important;
        padding: 0.75rem !important;
        gap: 1rem !important;
    }
    
    .card-header .flex-grow-1,
    .card-header .flex-shrink-0 {
        flex: none !important;
        width: 100% !important;
        text-align: center !important;
    }
    
    .vehicles-dashboard-card-title {
        font-size: clamp(0.9rem, 4vw, 1.1rem) !important;
        padding: 0.5rem !important;
    }
}

@media (max-width: 480px) {
    .vehicles-dashboard-card-title {
        font-size: clamp(0.8rem, 5vw, 1rem) !important;
        padding: 0.25rem !important;
    }
    
    .card-header.d-flex {
        padding: 0.5rem !important;
    }
}

/* TABLA Y CONTENIDO */
.service-orders-table {
    position: relative !important;
    width: 100% !important;
    table-layout: fixed !important;
}

/* DataTables wrapper - prevent extra space */
.dataTables_wrapper {
    width: 100% !important;
    overflow-x: auto !important;
}

.dataTables_wrapper .dataTables_scroll {
    width: 100% !important;
}

.dataTables_wrapper .dataTables_scrollBody {
    width: 100% !important;
}

.dataTables_wrapper .dataTables_scrollHead {
    width: 100% !important;
}

.dataTables_wrapper .dataTables_scrollHeadInner {
    width: 100% !important;
}

.dataTables_wrapper .dataTables_scrollHeadInner table {
    width: 100% !important;
}

/* Specific column width enforcement */
.service-orders-table .vehicle-info-col {
    width: 30% !important;
    max-width: 30% !important;
    min-width: 250px !important;
}

.service-orders-table .client-col {
    width: 20% !important;
    max-width: 20% !important;
    min-width: 150px !important;
}

.service-orders-table .service-history-col {
    width: 30% !important;
    max-width: 30% !important;
    min-width: 200px !important;
}

.service-orders-table .status-col {
    width: 20% !important;
    max-width: 20% !important;
    min-width: 120px !important;
}

/* Additional table spacing fixes */
.service-orders-table thead,
.service-orders-table tbody {
    width: 100% !important;
}

.service-orders-table tr {
    width: 100% !important;
    display: table-row !important;
}

.service-orders-table th,
.service-orders-table td {
    box-sizing: border-box !important;
    padding: 0.75rem 0.5rem !important;
}

/* Prevent any extra space after last column */
.service-orders-table th:last-child,
.service-orders-table td:last-child {
    border-right: none !important;
}

/* Force DataTables to respect column widths */
.dataTables_wrapper .dataTables_scrollBody table {
    width: 100% !important;
    table-layout: fixed !important;
}

/* Remove any potential margin/padding from DataTables wrapper */
.dataTables_wrapper .dataTables_scrollBody {
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
}

/* =================================================================
   LOCATION HISTORY TABLE - 6 COLUMNS SPECIFIC STYLES (view.php)
   ================================================================= */

.location-history-table {
    width: 100% !important;
    table-layout: fixed !important;
}

.location-history-table th,
.location-history-table td {
    box-sizing: border-box !important;
    padding: 0.75rem 0.5rem !important;
}

/* Default behavior for most columns - truncate */
.location-history-table th:nth-child(1),
.location-history-table td:nth-child(1),
.location-history-table th:nth-child(4),
.location-history-table td:nth-child(4),
.location-history-table th:nth-child(6),
.location-history-table td:nth-child(6) {
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
}

/* Allow text wrapping for content-heavy columns */
.location-history-table th:nth-child(2),
.location-history-table td:nth-child(2),
.location-history-table th:nth-child(3),
.location-history-table td:nth-child(3),
.location-history-table th:nth-child(5),
.location-history-table td:nth-child(5) {
    white-space: normal !important;
    overflow: visible !important;
    word-wrap: break-word !important;
}

/* 6-column layout for location history table */
.location-history-table th:nth-child(1),
.location-history-table td:nth-child(1) {
    width: 15% !important; /* Parking Spot */
    max-width: 15% !important;
    min-width: 120px !important;
}

.location-history-table th:nth-child(2),
.location-history-table td:nth-child(2) {
    width: 20% !important; /* Vehicle Info */
    max-width: 20% !important;
    min-width: 160px !important;
}

.location-history-table th:nth-child(3),
.location-history-table td:nth-child(3) {
    width: 15% !important; /* Recorded By */
    max-width: 15% !important;
    min-width: 120px !important;
}

.location-history-table th:nth-child(4),
.location-history-table td:nth-child(4) {
    width: 15% !important; /* Date & Time */
    max-width: 15% !important;
    min-width: 120px !important;
}

.location-history-table th:nth-child(5),
.location-history-table td:nth-child(5) {
    width: 20% !important; /* Location */
    max-width: 20% !important;
    min-width: 160px !important;
}

.location-history-table th:nth-child(6),
.location-history-table td:nth-child(6) {
    width: 15% !important; /* Actions */
    max-width: 15% !important;
    min-width: 120px !important;
}

/* Location history table content styling */
.location-history-table .spot-number-display {
    font-size: 1rem !important;
    font-weight: 700 !important;
}

.location-history-table .user-info-display {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.5rem !important;
}

.location-history-table .user-avatar {
    background: #f8f9fa !important;
    border-radius: 50% !important;
    width: 32px !important;
    height: 32px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.location-history-table .location-action-buttons {
    white-space: nowrap !important;
}

.location-history-table .location-action-buttons .d-flex {
    justify-content: center !important;
    gap: 0.25rem !important;
}

/* Responsive behavior for location history table */
@media (max-width: 1200px) {
    .location-history-table th:nth-child(5),
    .location-history-table td:nth-child(5) {
        display: none !important; /* Hide Location column on medium screens */
    }
    
    /* Redistribute widths when location column is hidden */
    .location-history-table th:nth-child(1),
    .location-history-table td:nth-child(1) { width: 18% !important; }
    
    .location-history-table th:nth-child(2),
    .location-history-table td:nth-child(2) { width: 25% !important; }
    
    .location-history-table th:nth-child(3),
    .location-history-table td:nth-child(3) { width: 20% !important; }
    
    .location-history-table th:nth-child(4),
    .location-history-table td:nth-child(4) { width: 20% !important; }
    
    .location-history-table th:nth-child(6),
    .location-history-table td:nth-child(6) { width: 17% !important; }
}

@media (max-width: 768px) {
    .location-history-table th:nth-child(3),
    .location-history-table td:nth-child(3) {
        display: none !important; /* Also hide Recorded By on small screens */
    }
    
    /* Redistribute widths when recorded by is also hidden */
    .location-history-table th:nth-child(1),
    .location-history-table td:nth-child(1) { width: 20% !important; }
    
    .location-history-table th:nth-child(2),
    .location-history-table td:nth-child(2) { width: 35% !important; }
    
    .location-history-table th:nth-child(4),
    .location-history-table td:nth-child(4) { width: 25% !important; }
    
    .location-history-table th:nth-child(6),
    .location-history-table td:nth-child(6) { width: 20% !important; }
}

.service-orders-table thead th {
    text-align: center !important;
    vertical-align: middle !important;
    font-size: 0.875rem !important;
    font-weight: 600 !important;
    padding: 0.75rem 0.5rem !important;
}

.service-orders-table tbody td {
    text-align: center !important;
    vertical-align: middle !important;
    padding: 0.75rem 0.5rem !important;
}

.service-orders-table tbody tr {
    position: relative !important;
}

/* Action buttons removed - Rows are now clickable */

/* =================================================================
   VEHICLES TABLE - COLUMN SPECIFIC STYLES
   ================================================================= */

/* Vehicle Info Column Styles */
.vehicle-info-cell {
    padding: 6px 8px !important;
    text-align: center !important;
    vertical-align: middle !important;
    min-width: 200px !important;
}

.vehicle-info-cell > div {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
    width: 100% !important;
}

.vehicle-info-cell .vehicle-name {
    font-weight: 600 !important;
    color: #1e40af !important;
    font-size: 0.85rem !important;
    margin-bottom: 3px !important;
    display: block !important;
    text-align: center !important;
    width: 100% !important;
    line-height: 1.2 !important;
}

.vehicle-info-cell .vin-info {
    font-family: 'Courier New', monospace !important;
    font-size: 0.68rem !important;
    color: #6b7280 !important;
    margin-bottom: 3px !important;
    display: block !important;
    text-align: center !important;
    letter-spacing: 0.5px !important;
    word-break: break-all !important;
    line-height: 1.2 !important;
}

.vehicle-info-cell .location-badge {
    display: inline-block !important;
    background-color: #0ea5e9 !important;
    color: white !important;
    padding: 2px 8px !important;
    border-radius: 12px !important;
    font-size: 0.7rem !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
}

.vehicle-info-cell .location-badge:hover {
    background-color: #0284c7 !important;
    transform: scale(1.05) !important;
}

/* Client Column Styles (Simplified) */
.client-cell {
    padding: 6px 8px !important;
    text-align: center !important;
    vertical-align: middle !important;
    min-width: 140px !important;
}

.client-cell .client-info {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    color: #374151 !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    text-align: center !important;
    width: 100% !important;
}

.client-cell .client-info i {
    color: #9ca3af !important;
    font-size: 0.8rem !important;
}

/* Service History Column Styles */
.service-history-cell {
    padding: 6px 8px !important;
    text-align: center !important;
    vertical-align: middle !important;
    min-width: 140px !important;
}

.service-history-cell > div {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 2px !important;
    text-align: center !important;
    width: 100% !important;
}

.service-history-cell .total-services {
    margin-bottom: 3px !important;
}

.service-history-cell .total-services .badge {
    display: inline-block !important;
    padding: 4px 10px !important;
    border-radius: 14px !important;
    font-size: 0.75rem !important;
    font-weight: 500 !important;
    min-width: 70px !important;
    cursor: help !important;
    transition: all 0.2s ease !important;
}

.service-history-cell .total-services .badge:hover {
    transform: scale(1.05) !important;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3) !important;
}

.service-history-cell .first-service,
.service-history-cell .last-service {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 4px !important;
    font-size: 0.7rem !important;
    color: #6b7280 !important;
    margin-bottom: 1px !important;
    text-align: center !important;
    width: 100% !important;
}

.service-history-cell .first-service i,
.service-history-cell .last-service i {
    font-size: 0.7rem !important;
    color: #9ca3af !important;
}

/* Velzon Service Tooltip Styles */
.velzon-service-tooltip .tooltip-inner {
    background-color: #ffffff !important;
    color: #495057 !important;
    border: 1px solid #e9ecef !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    padding: 12px 16px !important;
    max-width: 300px !important;
    font-size: 0.8rem !important;
    text-align: left !important;
}

.velzon-service-tooltip .tooltip-arrow::before {
    border-right-color: #e9ecef !important;
}

.service-tooltip-content {
    text-align: left !important;
}

.service-tooltip-content .tooltip-header {
    font-weight: 600 !important;
    color: #374151 !important;
    margin-bottom: 8px !important;
    padding-bottom: 6px !important;
    border-bottom: 1px solid #f1f5f9 !important;
    font-size: 0.85rem !important;
}

.service-tooltip-content .service-summary {
    margin-bottom: 8px !important;
}

.service-tooltip-content .summary-item {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    margin-bottom: 4px !important;
    font-size: 0.75rem !important;
    color: #6b7280 !important;
}

.service-tooltip-content .summary-item i {
    color: #3b82f6 !important;
    font-size: 0.7rem !important;
    width: 12px !important;
    text-align: center !important;
}

.service-tooltip-content .summary-item strong {
    color: #374151 !important;
}

.service-tooltip-content .tooltip-footer {
    margin-top: 8px !important;
    padding-top: 6px !important;
    border-top: 1px solid #f1f5f9 !important;
    font-size: 0.7rem !important;
    color: #9ca3af !important;
    font-style: italic !important;
    text-align: center !important;
}

.service-tooltip-content .no-services {
    color: #9ca3af !important;
    font-size: 0.75rem !important;
    font-style: italic !important;
    text-align: center !important;
    margin-top: 4px !important;
}

/* Status Column Styles */
.status-cell {
    padding: 6px 8px !important;
    text-align: center !important;
    vertical-align: middle !important;
    min-width: 100px !important;
}

.status-cell .status-badge {
    display: inline-block !important;
    padding: 4px 10px !important;
    border-radius: 14px !important;
    font-size: 0.75rem !important;
    font-weight: 500 !important;
    text-transform: capitalize !important;
    min-width: 70px !important;
}

/* Removed Actions Column - Rows are now clickable */

/* DATATABLES CUSTOMIZATION */
.service-orders-table_wrapper .dataTables_length select,
.service-orders-table_wrapper .dataTables_filter input {
    border: 1px solid #e5e7eb !important;
    border-radius: 6px !important;
    padding: 0.375rem 0.75rem !important;
    font-size: 0.875rem !important;
    transition: all 0.2s ease !important;
}

.service-orders-table_wrapper .dataTables_length select:focus,
.service-orders-table_wrapper .dataTables_filter input:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    outline: none !important;
}

.service-orders-table_wrapper .dataTables_paginate .paginate_button {
    padding: 0.375rem 0.75rem !important;
    margin: 0 0.125rem !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 6px !important;
    color: #6b7280 !important;
    background: #ffffff !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
}

.service-orders-table_wrapper .dataTables_paginate .paginate_button:hover {
    background: #3b82f6 !important;
    border-color: #3b82f6 !important;
    color: #ffffff !important;
}

.service-orders-table_wrapper .dataTables_paginate .paginate_button.current {
    background: #3b82f6 !important;
    border-color: #3b82f6 !important;
    color: #ffffff !important;
}

.service-orders-table_wrapper .dataTables_info {
    color: #6b7280 !important;
    font-size: 0.875rem !important;
}

/* Row selection and clickable rows */
.service-orders-table tbody tr {
    cursor: pointer !important;
    transition: background-color 0.2s ease !important;
}

.service-orders-table tbody tr:hover {
    background-color: rgba(59, 130, 246, 0.08) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.service-orders-table tbody tr.row-selected {
    background-color: rgba(13, 110, 253, 0.15) !important;
}

/* Clickable row indicator */
.service-orders-table tbody tr::after {
    content: '' !important;
    position: absolute !important;
    left: 0 !important;
    top: 0 !important;
    bottom: 0 !important;
    width: 3px !important;
    background-color: transparent !important;
    transition: background-color 0.2s ease !important;
}

.service-orders-table tbody tr:hover::after {
    background-color: #3b82f6 !important;
}

/* General table compacting */
.service-orders-table tbody tr {
    height: auto !important;
}

.service-orders-table tbody td {
    line-height: 1.1 !important;
}

/* VIN Code Styling */
.vehicle-vin-code {
    font-family: 'Courier New', monospace !important;
    font-weight: 600 !important;
    color: #1e40af !important;
    background-color: #f0f9ff !important;
    padding: 2px 6px !important;
    border-radius: 4px !important;
    font-size: 0.75rem !important;
    border: 1px solid #e0f2fe !important;
}

/* Service Count Badge */
.vehicle-service-count {
    font-weight: 600 !important;
    color: #1e40af !important;
    font-size: 0.9rem !important;
}

/* Location Tracking Badge */
.location-tracking-badge {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.25rem !important;
    padding: 0.125rem 0.5rem !important;
    background-color: #0ea5e9 !important;
    color: white !important;
    border-radius: 0.375rem !important;
    font-size: 0.75rem !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
}

.location-tracking-badge:hover {
    background-color: #0284c7 !important;
    transform: scale(1.05) !important;
}

/* RESPONSIVE PARA TABLETS Y MÓVILES */
@media (max-width: 768px) {
    .vehicle-info-cell,
    .client-cell,
    .service-history-cell {
        min-width: 120px !important;
    }
    
    .service-orders-table tbody td {
        padding: 0.5rem 0.25rem !important;
        font-size: 0.85rem !important;
    }
    
    .service-orders-table thead th {
        padding: 0.5rem 0.25rem !important;
        font-size: 0.8rem !important;
    }
    
    .vehicle-info-cell .vehicle-name {
        font-size: 0.8rem !important;
    }
    
    .vehicle-info-cell .vin-info {
        font-size: 0.62rem !important;
    }
    
    .client-cell .client-info {
        font-size: 0.8rem !important;
    }
    
    .service-history-cell .total-services .badge {
        font-size: 0.65rem !important;
        padding: 2px 6px !important;
    }
}

@media (max-width: 576px) {
    .vehicle-info-cell,
    .client-cell,
    .service-history-cell {
        min-width: 100px !important;
    }
    
    .service-orders-table tbody td,
    .service-orders-table thead th {
        padding: 0.375rem 0.125rem !important;
        font-size: 0.75rem !important;
    }
    
    .vehicle-info-cell .vehicle-name {
        font-size: 0.75rem !important;
    }
    
    .vehicle-info-cell .vin-info {
        font-size: 0.58rem !important;
    }
}

/* Dropdown styling removed - No longer needed */

/* Column helper consistent spacing */
.vehicle-info-cell, .client-cell, .service-history-cell, .status-cell {
    padding: 8px 10px !important;
}

@media (max-width: 768px) {
    .vehicle-info-cell, .client-cell, .service-history-cell, .status-cell {
        padding: 6px 8px !important;
    }
}

@media (max-width: 576px) {
    .vehicle-info-cell, .client-cell, .service-history-cell, .status-cell {
        padding: 4px 6px !important;
    }
}

/* Enhanced Service History Table Styles - Velzon Theme */
.service-history-card {
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    background: var(--bs-body-bg, #ffffff);
    transition: all 0.15s ease-in-out;
    overflow: hidden;
}

.service-history-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    border-color: var(--bs-primary, #0d6efd);
}

.service-history-header {
    background: var(--bs-light, #f8f9fa);
    border-bottom: 1px solid var(--bs-border-color, #dee2e6);
    padding: 1.25rem 1.5rem;
    position: relative;
}

.service-history-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--bs-primary, #0d6efd) 0%, var(--bs-info, #0dcaf0) 100%);
}

.service-history-thead {
    background: var(--bs-gray-50, #f8f9fa);
    border-bottom: 1px solid var(--bs-border-color, #dee2e6);
}

.service-history-thead th {
    border: none;
    padding: 1rem 0.75rem;
    font-weight: 600;
    color: var(--bs-emphasis-color, #495057);
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    position: relative;
}

.service-orders-table {
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 0;
}

.service-history-row {
    background: #ffffff;
    border-bottom: 1px solid #dee2e6;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
}

.service-history-row:hover {
    background: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.service-history-row:hover .stock-code {
    background: #1976d2;
    color: #fff;
    border-color: #1976d2;
}

.service-history-row:last-child {
    border-bottom: none;
}

/* Column Specific Styles - Equitable Distribution */
.service-orders-table {
    table-layout: fixed !important;
    width: 100% !important;
}

.order-column {
    width: 18% !important;
    padding: 1rem 0.75rem !important;
}

.stock-column {
    width: 15% !important;
    padding: 1rem 0.75rem !important;
}

.service-column {
    width: 35% !important;
    padding: 1rem 0.75rem !important;
}

.date-column {
    width: 17% !important;
    padding: 1rem 0.75rem !important;
}

.status-column {
    width: 15% !important;
    padding: 1rem 0.75rem !important;
}

/* Content overflow handling */
.service-column {
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}

.service-column .badge {
    display: inline-block !important;
    margin: 0.125rem 0.25rem 0.125rem 0 !important;
    white-space: nowrap !important;
    font-size: 0.75rem !important;
}

.order-column,
.stock-column,
.date-column,
.status-column {
    overflow: hidden !important;
    white-space: nowrap !important;
    text-overflow: ellipsis !important;
}

.status-column .service-status {
    width: 100% !important;
    font-size: 0.75rem !important;
    padding: 0.4rem 0.6rem !important;
    min-width: unset !important;
}

/* Responsive column adjustments */
@media (max-width: 1199px) {
    /* When stock column is hidden, redistribute */
    .order-column {
        width: 22% !important;
    }
    
    .service-column {
        width: 40% !important;
    }
    
    .date-column {
        width: 20% !important;
    }
    
    .status-column {
        width: 18% !important;
    }
}

@media (max-width: 767px) {
    /* When date column is also hidden */
    .order-column {
        width: 30% !important;
    }
    
    .service-column {
        width: 45% !important;
    }
    
    .status-column {
        width: 25% !important;
    }
}

@media (max-width: 575px) {
    /* When status column is also hidden */
    .order-column {
        width: 35% !important;
    }
    
    .service-column {
        width: 65% !important;
    }
}

/* Order Info Styles */
.order-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.order-number {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
}

.module-name {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
}

/* Stock Code Styles - Simple & Elegant */
.stock-code {
    background: #e3f2fd !important;
    color: #1565c0 !important;
    padding: 0.375rem 0.75rem !important;
    border-radius: 0.375rem !important;
    font-size: 0.8rem !important;
    border: 1px solid #90caf9 !important;
    font-family: 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', monospace !important;
    font-weight: 500 !important;
    display: inline-block !important;
    transition: all 0.2s ease !important;
    min-width: 80px !important;
    text-align: center !important;
}

.stock-code:hover {
    background: #bbdefb !important;
    border-color: #42a5f5 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 4px rgba(21, 101, 192, 0.2) !important;
}

/* Service Date Styles */
.service-date {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.date-main {
    font-weight: 500;
    color: #495057;
    font-size: 0.875rem;
}

.date-time {
    font-size: 0.75rem;
    color: #6c757d;
}

/* Service Status Styles - Simple & Elegant */
.service-status {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.375rem !important;
    padding: 0.5rem 0.875rem !important;
    border-radius: 0.375rem !important;
    font-size: 0.8rem !important;
    font-weight: 500 !important;
    text-transform: capitalize !important;
    border: 1px solid transparent !important;
    transition: all 0.2s ease !important;
    min-width: 100px !important;
    justify-content: center !important;
    text-align: center !important;
}

.service-status:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.service-status .status-icon {
    width: 14px !important;
    height: 14px !important;
    stroke-width: 2 !important;
}

/* Simple Status Colors */
.status-pending {
    background-color: #fff3cd !important;
    color: #856404 !important;
    border-color: #ffeaa7 !important;
}

.status-pending:hover {
    background-color: #ffc107 !important;
    color: #fff !important;
    border-color: #ffc107 !important;
}

.status-in-progress {
    background-color: #d1ecf1 !important;
    color: #0c5460 !important;
    border-color: #bee5eb !important;
}

.status-in-progress:hover {
    background-color: #17a2b8 !important;
    color: #fff !important;
    border-color: #17a2b8 !important;
}

.status-completed {
    background-color: #d4edda !important;
    color: #155724 !important;
    border-color: #c3e6cb !important;
}

.status-completed:hover {
    background-color: #28a745 !important;
    color: #fff !important;
    border-color: #28a745 !important;
}

.status-cancelled {
    background-color: #f8d7da !important;
    color: #721c24 !important;
    border-color: #f5c6cb !important;
}

.status-cancelled:hover {
    background-color: #dc3545 !important;
    color: #fff !important;
    border-color: #dc3545 !important;
}

.status-default {
    background-color: #e9ecef !important;
    color: #495057 !important;
    border-color: #ced4da !important;
}

.status-default:hover {
    background-color: #6c757d !important;
    color: #fff !important;
    border-color: #6c757d !important;
}

/* Ensure badge classes also get the status styles */
.badge.status-pending {
    background-color: #fff3cd !important;
    color: #856404 !important;
    border: 1px solid #ffeaa7 !important;
}

.badge.status-in-progress {
    background-color: #d1ecf1 !important;
    color: #0c5460 !important;
    border: 1px solid #bee5eb !important;
}

.badge.status-completed {
    background-color: #d4edda !important;
    color: #155724 !important;
    border: 1px solid #c3e6cb !important;
}

.badge.status-cancelled {
    background-color: #f8d7da !important;
    color: #721c24 !important;
    border: 1px solid #f5c6cb !important;
}

.badge.status-default {
    background-color: #e9ecef !important;
    color: #495057 !important;
    border: 1px solid #ced4da !important;
}

/* Stock code in no-stock state */
.badge.bg-light.text-muted.border {
    background-color: #f8f9fa !important;
    color: #6c757d !important;
    border: 1px solid #dee2e6 !important;
    padding: 0.375rem 0.75rem !important;
    font-size: 0.8rem !important;
}

/* Mobile Specific Styles - Velzon Theme */
.mobile-order-info {
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--bs-border-color, #dee2e6);
    background: var(--bs-gray-50, #f8f9fa);
    padding: 0.75rem;
    border-radius: 0.375rem;
    margin: 0 -0.75rem 0.75rem -0.75rem;
}

.mobile-order-number {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.module-badge {
    background: var(--bs-primary-bg-subtle, #cfe2ff);
    color: var(--bs-primary-text-emphasis, #052c65);
    padding: 0.1875rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.6875rem;
    font-weight: 500;
    margin-left: 0.5rem;
    border: 1px solid var(--bs-primary-border-subtle, #9ec5fe);
}

.mobile-stock {
    margin-top: 0.375rem;
    display: flex;
    align-items: center;
}

.mobile-stock .stock-code {
    font-size: 0.75rem !important;
    padding: 0.25rem 0.5rem !important;
    background: #e3f2fd !important;
    color: #1565c0 !important;
    border: 1px solid #90caf9 !important;
}

/* Empty State Styles */
.empty-state, .empty-state-main {
    padding: 2rem 1rem;
}

.empty-state i, .empty-state-main i {
    opacity: 0.5;
}

.table-title {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
}

/* Responsive Adjustments */
@media (max-width: 1200px) {
    .stock-column {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .date-column {
        display: none !important;
    }
    
    .service-history-header {
        padding: 1rem;
    }
    
    .order-column, .service-column, .status-column {
        padding: 0.75rem 0.5rem;
    }
}

@media (max-width: 576px) {
    .status-column {
        display: none !important;
    }
    
    .service-history-header {
        padding: 0.75rem;
    }
    
    .order-column, .service-column {
        padding: 0.5rem 0.375rem;
    }
}

/* Force cache refresh - Version 3.0 - Equitable Column Distribution */
</style> 