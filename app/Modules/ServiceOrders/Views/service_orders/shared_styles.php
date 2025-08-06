<style>
/* =================================================================
   SERVICE ORDERS - ESTILOS COMPARTIDOS (VERSIÓN SIMPLIFICADA)
   Enfoque minimalista para asegurar funcionalidad responsive
   ================================================================= */

/* RESET Y BASE PARA TÍTULO RESPONSIVE */
.service-orders-card-title {
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
    
    .service-orders-card-title {
        font-size: clamp(0.9rem, 4vw, 1.1rem) !important;
        padding: 0.5rem !important;
    }
}

@media (max-width: 480px) {
    .service-orders-card-title {
        font-size: clamp(0.8rem, 5vw, 1rem) !important;
        padding: 0.25rem !important;
    }
    
    .card-header.d-flex {
        padding: 0.5rem !important;
    }
}

/* TABLA Y CONTENIDO */
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

/* DATATABLES RESPONSIVE CLASSES */
.service-orders-table .never {
    display: table-cell !important;
}

.service-orders-table .desktop {
    display: table-cell !important;
}

.service-orders-table .tablet-l {
    display: table-cell !important;
}

.service-orders-table .tablet-p {
    display: table-cell !important;
}

.service-orders-table .mobile-l {
    display: table-cell !important;
}

.service-orders-table .mobile-p {
    display: table-cell !important;
}

/* RESPONSIVE DATATABLES BREAKPOINTS */
@media screen and (max-width: 1024px) {
    .service-orders-table .desktop {
        display: none !important;
    }
}

@media screen and (max-width: 768px) {
    .service-orders-table .tablet-l {
        display: none !important;
    }
}

@media screen and (max-width: 640px) {
    .service-orders-table .tablet-p {
        display: none !important;
    }
}

@media screen and (max-width: 480px) {
    .service-orders-table .mobile-l {
        display: none !important;
    }
}

@media screen and (max-width: 320px) {
    .service-orders-table .mobile-p {
        display: none !important;
    }
}

/* RESPONSIVE DETAILS STYLING */
.service-orders-table td.child {
    background-color: #f8f9fa !important;
    padding: 1rem !important;
    border-top: 1px solid #e9ecef !important;
}

.service-orders-table td.child ul {
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
}

.service-orders-table td.child ul li {
    padding: 0.25rem 0 !important;
    border-bottom: 1px solid #e9ecef !important;
}

.service-orders-table td.child ul li:last-child {
    border-bottom: none !important;
}

.service-orders-table td.child ul li strong {
    color: #495057 !important;
    font-weight: 600 !important;
    display: inline-block !important;
    min-width: 80px !important;
}

/* FILAS CLICKEABLES */
.service-orders-table tbody tr {
    cursor: pointer !important;
    transition: background-color 0.2s ease !important;
}

.service-orders-table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.08) !important;
}

.service-orders-table tbody tr.row-selected {
    background-color: rgba(13, 110, 253, 0.15) !important;
}

/* BOTONES DE ACCIÓN */
.service-order-action-buttons {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 4px !important;
    width: 100% !important;
}

.service-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 20px !important;
    height: 20px !important;
    border-radius: 4px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    border: 1px solid transparent !important;
}

.service-btn-view {
    color: #0ea5e9 !important;
    background-color: rgba(14, 165, 233, 0.1) !important;
    border-color: rgba(14, 165, 233, 0.2) !important;
}

.service-btn-view:hover {
    color: #0284c7 !important;
    background-color: rgba(14, 165, 233, 0.2) !important;
    border-color: rgba(14, 165, 233, 0.3) !important;
    text-decoration: none !important;
}

.service-btn-edit {
    color: #10b981 !important;
    background-color: rgba(16, 185, 129, 0.1) !important;
    border-color: rgba(16, 185, 129, 0.2) !important;
}

.service-btn-edit:hover {
    color: #059669 !important;
    background-color: rgba(16, 185, 129, 0.2) !important;
    border-color: rgba(16, 185, 129, 0.3) !important;
    text-decoration: none !important;
}

.service-btn-delete {
    color: #ef4444 !important;
    background-color: rgba(239, 68, 68, 0.1) !important;
    border-color: rgba(239, 68, 68, 0.2) !important;
}

.service-btn-delete:hover {
    color: #dc2626 !important;
    background-color: rgba(239, 68, 68, 0.2) !important;
    border-color: rgba(239, 68, 68, 0.3) !important;
    text-decoration: none !important;
}

/* INFORMACIÓN DEL CLIENTE */
.service-client-info {
    max-width: 220px !important;
    text-align: left !important;
    margin: 0 auto !important;
}

.service-client-name {
    font-weight: 500 !important;
    color: #1f2937 !important;
    font-size: 0.875rem !important;
    line-height: 1.2 !important;
}

.service-contact-name {
    font-size: 0.75rem !important;
    color: #6b7280 !important;
    margin-top: 2px !important;
    line-height: 1.1 !important;
}

.service-vehicle-info {
    font-size: 0.75rem !important;
    color: #6b7280 !important;
    margin-top: 2px !important;
    max-width: 200px !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    line-height: 1.1 !important;
}

.service-service-info {
    font-size: 0.75rem !important;
    color: #3b82f6 !important;
    margin-top: 2px !important;
    max-width: 200px !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    line-height: 1.1 !important;
}

/* ORDER ID Y VIN */
.service-order-id {
    font-weight: 600 !important;
    color: #405189 !important;
    font-size: 0.875rem !important;
    font-family: 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', monospace !important;
    display: inline-block !important;
    text-align: center !important;
}

.service-vin {
    font-family: 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', monospace !important;
    font-size: 0.75rem !important;
    color: #6b7280 !important;
    max-width: 120px !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    display: inline-block !important;
    text-align: center !important;
}

/* TIME BADGES */
.service-time-badge {
    font-size: 0.7rem !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 4px !important;
    font-weight: 500 !important;
    display: inline-block !important;
    text-align: center !important;
    min-width: 60px !important;
}

.service-time-today {
    background-color: #dcfce7 !important;
    color: #16a34a !important;
}

.service-time-tomorrow {
    background-color: #fef3c7 !important;
    color: #d97706 !important;
}

.service-time-overdue {
    background-color: #fee2e2 !important;
    color: #dc2626 !important;
}

.service-time-normal {
    background-color: #f3f4f6 !important;
    color: #6b7280 !important;
}

/* STATUS BADGES */
.service-orders-table .badge {
    display: inline-block !important;
    text-align: center !important;
    min-width: 70px !important;
}

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

/* RESPONSIVE PARA TABLETS Y MÓVILES */
@media (max-width: 768px) {
    .service-client-info {
        max-width: 180px !important;
    }
    
    .service-vehicle-info,
    .service-service-info {
        max-width: 150px !important;
    }
    
    .service-vin {
        max-width: 100px !important;
    }
    
    .service-orders-table tbody td {
        padding: 0.5rem 0.25rem !important;
        font-size: 0.85rem !important;
    }
    
    .service-orders-table thead th {
        padding: 0.5rem 0.25rem !important;
        font-size: 0.8rem !important;
    }
}

@media (max-width: 576px) {
    .service-client-info {
        max-width: 150px !important;
    }
    
    .service-orders-table tbody td,
    .service-orders-table thead th {
        padding: 0.375rem 0.125rem !important;
        font-size: 0.75rem !important;
    }
}

/* =================================================================
   SERVICE ORDERS TABLE - REORGANIZED FORMAT STYLES
   ================================================================= */

/* Order ID Column Styles */
.order-id-cell {
    padding: 6px 8px !important;
    text-align: center !important;
    vertical-align: middle !important;
    min-width: 140px !important;
}

.order-id-cell > div {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
    width: 100% !important;
}

.order-number {
    font-weight: 600 !important;
    color: #1e40af !important;
    font-size: 0.85rem !important;
    margin-bottom: 3px !important;
    display: block !important;
    text-align: center !important;
    width: 100% !important;
}

.client-info {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 4px !important;
    color: #6b7280 !important;
    font-size: 0.8rem !important;
    text-align: center !important;
    width: 100% !important;
}

.client-info i {
    color: #9ca3af !important;
    font-size: 0.7rem !important;
}

/* TAG/RO Column Styles */
.tag-ro-cell {
    padding: 6px 8px !important;
    text-align: center !important;
    vertical-align: middle !important;
    min-width: 110px !important;
}

.tag-ro-cell > div {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 2px !important;
    text-align: center !important;
    width: 100% !important;
}

.tag-info, .ro-info {
    margin: 2px 0 !important;
    font-size: 0.8rem !important;
    padding: 2px 6px !important;
    border-radius: 4px !important;
    font-weight: 500 !important;
    display: inline-block !important;
    min-width: 50px !important;
    text-align: center !important;
}

.tag-info {
    background-color: #f0f9ff !important;
    color: #0369a1 !important;
    border: 1px solid #e0f2fe !important;
    transition: all 0.2s ease !important;
}

.tag-info:hover {
    background-color: #e0f2fe !important;
    color: #0284c7 !important;
    border-color: #bae6fd !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 4px rgba(3, 105, 161, 0.1) !important;
}

.ro-info {
    background-color: #faf5ff !important;
    color: #7c2d92 !important;
    border: 1px solid #f3e8ff !important;
    transition: all 0.2s ease !important;
}

.ro-info:hover {
    background-color: #f3e8ff !important;
    color: #6b21a8 !important;
    border-color: #e9d5ff !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 4px rgba(124, 45, 146, 0.1) !important;
}

.tag-info.empty, .ro-info.empty {
    background-color: #f9fafb !important;
    color: #9ca3af !important;
    border: 1px solid #e5e7eb !important;
    font-style: italic !important;
}

/* User info third line in TAG/RO column */
.user-info {
    margin: 1px 0 !important;
    font-size: 0.7rem !important;
    color: #6b7280 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 3px !important;
    text-align: center !important;
}

.user-info i {
    font-size: 0.7rem !important;
    color: #9ca3af !important;
}

/* Vehicle Column Styles */
.vehicle-cell {
    padding: 6px 8px !important;
    text-align: left !important;
    vertical-align: middle !important;
    min-width: 200px !important;
}

.vehicle-name {
    font-weight: 500 !important;
    color: #111827 !important;
    font-size: 0.85rem !important;
    margin-bottom: 2px !important;
    line-height: 1.1 !important;
}

.vin-info {
    font-family: 'Courier New', monospace !important;
    font-size: 0.75rem !important;
    color: #6b7280 !important;
    margin-bottom: 3px !important;
}

.instructions-badge {
    display: inline-block !important;
    background-color: #fbbf24 !important;
    color: #92400e !important;
    padding: 2px 8px !important;
    border-radius: 12px !important;
    font-size: 0.7rem !important;
    font-weight: 500 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
}

.instructions-badge:hover {
    background-color: #f59e0b !important;
    color: #78350f !important;
}

/* Due Column Styles */
.due-cell {
    padding: 6px 8px !important;
    text-align: center !important;
    vertical-align: middle !important;
    min-width: 110px !important;
}

.due-time {
    font-weight: 600 !important;
    color: #1f2937 !important;
    font-size: 0.85rem !important;
    margin-bottom: 2px !important;
    display: block !important;
}

.due-date {
    color: #6b7280 !important;
    font-size: 0.8rem !important;
    font-weight: 400 !important;
}

/* Status Column Styles */
.status-cell {
    padding: 6px 8px !important;
    text-align: center !important;
    vertical-align: middle !important;
    min-width: 100px !important;
}

.status-badge {
    display: inline-block !important;
    padding: 4px 10px !important;
    border-radius: 14px !important;
    font-size: 0.75rem !important;
    font-weight: 500 !important;
    text-transform: capitalize !important;
    min-width: 70px !important;
}

/* Actions Column Styles */
.actions-cell {
    padding: 6px 8px !important;
    text-align: center !important;
    vertical-align: middle !important;
    min-width: 120px !important;
}

.action-buttons {
    display: flex !important;
    justify-content: center !important;
    gap: 6px !important;
    align-items: center !important;
}

/* General table compacting */
.service-orders-table tbody tr {
    height: auto !important;
}

.service-orders-table tbody td {
    line-height: 1.1 !important;
}

/* Action buttons hover effects - consistent with Sales Orders */
.action-buttons .link-primary:hover {
    color: #0056b3 !important;
}

.action-buttons .link-success:hover {
    color: #0a6e2a !important;
}

.action-buttons .link-danger:hover {
    color: #b30000 !important;
}

/* Icon sizing to match Sales Orders */
.action-buttons .fs-15 {
    font-size: 15px !important;
}

/* Comments Icon Styles */
.tag-info-row {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 0.5rem !important;
}

.comments-icon-container {
    flex-shrink: 0 !important;
}

.comments-icon {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.25rem !important;
    padding: 0.125rem 0.375rem !important;
    background-color: #3b82f6 !important;
    color: white !important;
    border-radius: 0.375rem !important;
    text-decoration: none !important;
    font-size: 0.75rem !important;
    transition: all 0.2s ease !important;
    border: 1px solid #3b82f6 !important;
}

.comments-icon:hover {
    background-color: #2563eb !important;
    border-color: #2563eb !important;
    color: white !important;
    text-decoration: none !important;
    transform: scale(1.05) !important;
}

.comments-icon i {
    font-size: 0.875rem !important;
}

.comment-count {
    font-weight: 600 !important;
    font-size: 0.75rem !important;
    line-height: 1 !important;
}

/* Notes Icon Styles */
.notes-icon {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.25rem !important;
    padding: 0.125rem 0.375rem !important;
    background-color: #059669 !important;
    color: white !important;
    border-radius: 0.375rem !important;
    text-decoration: none !important;
    font-size: 0.75rem !important;
    transition: all 0.2s ease !important;
    border: 1px solid #059669 !important;
}

.notes-icon:hover {
    background-color: #047857 !important;
    border-color: #047857 !important;
    color: white !important;
    text-decoration: none !important;
    transform: scale(1.05) !important;
}

.notes-icon i {
    font-size: 0.875rem !important;
}

.note-count {
    font-weight: 600 !important;
    font-size: 0.75rem !important;
    line-height: 1 !important;
}

/* Custom tooltip styles for comments preview */
.comment-preview-tooltip {
    max-width: 300px !important;
    background-color: #1f2937 !important;
    color: white !important;
    border-radius: 0.5rem !important;
    padding: 0.75rem !important;
    font-size: 0.875rem !important;
    line-height: 1.4 !important;
}

/* Override Bootstrap tooltip styles when using our custom class */
.tooltip.comments-preview-tooltip .tooltip-inner {
    max-width: 300px !important;
    background-color: #1f2937 !important;
    color: white !important;
    border-radius: 0.5rem !important;
    padding: 0.75rem !important;
    font-size: 0.875rem !important;
    text-align: left !important;
}

.comment-preview-item {
    margin-bottom: 0.5rem !important;
    padding-bottom: 0.5rem !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.comment-preview-item:last-child {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
    border-bottom: none !important;
}

.comment-preview-user {
    font-weight: 600 !important;
    color: #60a5fa !important;
    margin-bottom: 0.25rem !important;
}

.comment-preview-text {
    color: #d1d5db !important;
    margin-bottom: 0.25rem !important;
}

.comment-preview-time {
    color: #9ca3af !important;
    font-size: 0.75rem !important;
}

/* Notes preview tooltip styles */
.note-preview-tooltip {
    max-width: 300px !important;
    background-color: #1f2937 !important;
    color: white !important;
    border-radius: 0.5rem !important;
    padding: 0.75rem !important;
    font-size: 0.875rem !important;
    line-height: 1.4 !important;
}

/* Override Bootstrap tooltip styles when using our custom notes class */
.tooltip.notes-preview-tooltip .tooltip-inner {
    max-width: 300px !important;
    background-color: #1f2937 !important;
    color: white !important;
    border-radius: 0.5rem !important;
    padding: 0.75rem !important;
    font-size: 0.875rem !important;
    text-align: left !important;
}

.note-preview-item {
    margin-bottom: 0.5rem !important;
    padding-bottom: 0.5rem !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.note-preview-item:last-child {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
    border-bottom: none !important;
}

/* Internal Notes Badge Styles */
.internal-notes-badge {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.25rem !important;
    padding: 0.125rem 0.375rem !important;
    background-color: #6b7280 !important;
    color: white !important;
    border-radius: 0.375rem !important;
    text-decoration: none !important;
    font-size: 0.7rem !important;
    transition: all 0.2s ease !important;
    border: 1px solid #6b7280 !important;
    margin-left: 0.25rem !important;
}

.internal-notes-badge:hover {
    background-color: #4b5563 !important;
    border-color: #4b5563 !important;
    color: white !important;
    text-decoration: none !important;
    transform: scale(1.05) !important;
}

.internal-notes-badge.has-notes {
    background-color: #dc2626 !important;
    border-color: #dc2626 !important;
    color: white !important;
}

.internal-notes-badge.has-notes:hover {
    background-color: #b91c1c !important;
    border-color: #b91c1c !important;
    color: white !important;
}

.internal-notes-badge i {
    font-size: 0.75rem !important;
}

.note-preview-author {
    font-weight: 600 !important;
    color: #a7f3d0 !important;
    margin-bottom: 0.25rem !important;
}

.note-preview-text {
    color: #d1fae5 !important;
    margin-bottom: 0.25rem !important;
}

.note-preview-time {
    color: #9ca3af !important;
    font-size: 0.75rem !important;
}

/* Table Row Hover Effect */
.service-orders-table tbody tr:hover {
    background-color: #f9fafb !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
    transition: all 0.2s ease !important;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .vehicle-cell {
        min-width: 160px !important;
    }

    .order-id-cell {
        min-width: 120px !important;
    }

    .vehicle-name {
        font-size: 0.85rem !important;
    }
}

@media (max-width: 768px) {
    .order-id-cell, .tag-ro-cell, .vehicle-cell, .due-cell, .status-cell, .actions-cell {
        padding: 8px 4px !important;
    }

    .vehicle-cell {
        min-width: 140px !important;
    }

    .due-cell {
        min-width: 90px !important;
    }

    .action-buttons {
        gap: 4px !important;
    }

    .action-buttons .fs-15 {
        font-size: 14px !important;
    }
}
</style>

<script>
// =================================================================
// SERVICE ORDERS - JAVASCRIPT COMPARTIDO SIMPLIFICADO
// =================================================================

window.serviceOrdersInitialized = window.serviceOrdersInitialized || {};

window.handleTabTableInitialization = function(tabId) {
    
    // Prevent multiple simultaneous initializations
    if (window.serviceOrdersInitializing && window.serviceOrdersInitializing[tabId]) {

        return;
    }
    
    // Mark as initializing
    window.serviceOrdersInitializing = window.serviceOrdersInitializing || {};
    window.serviceOrdersInitializing[tabId] = true;

    // Check if already initialized
    if (window.serviceOrdersInitialized && window.serviceOrdersInitialized[tabId]) {

        window.serviceOrdersInitializing[tabId] = false;
        return;
    }
    
    setTimeout(() => {
        try {

            // Initialize serviceOrdersInitialized if not exists
            window.serviceOrdersInitialized = window.serviceOrdersInitialized || {};

            switch(tabId) {
                case '#today-orders-tab':
                    if (typeof initTodayServiceOrdersTable === 'function') {

                        initTodayServiceOrdersTable();
                        window.serviceOrdersInitialized[tabId] = true;
                    }
                    break;
                case '#tomorrow-orders-tab':
                    if (typeof initTomorrowServiceOrdersTable === 'function') {

                        initTomorrowServiceOrdersTable();
                        window.serviceOrdersInitialized[tabId] = true;
                    }
                    break;
                case '#pending-orders-tab':
                    if (typeof initPendingServiceOrdersTable === 'function') {

                        initPendingServiceOrdersTable();
                        window.serviceOrdersInitialized[tabId] = true;
                    }
                    break;
                case '#week-orders-tab':
                    if (typeof initWeekServiceOrdersTable === 'function') {

                        initWeekServiceOrdersTable();
                        window.serviceOrdersInitialized[tabId] = true;
                    }
                    break;
                case '#all-orders-tab':
                    if (typeof initAllServiceOrdersTable === 'function') {

                        initAllServiceOrdersTable();
                        window.serviceOrdersInitialized[tabId] = true;
                    }
                    break;
                case '#services-tab':
                    if (typeof initServicesTable === 'function') {

                        initServicesTable();
                        window.serviceOrdersInitialized[tabId] = true;
                    }
                    break;
                case '#deleted-orders-tab':
                    if (typeof initializeDeletedOrdersTable === 'function') {

                        initializeDeletedOrdersTable();
                        window.serviceOrdersInitialized[tabId] = true;
                    }
                    break;
                default:

            }
        } catch (error) {
            console.error('❌ Error initializing table for', tabId, ':', error);
            window.serviceOrdersInitialized[tabId] = false;
        } finally {
            // Clear the initializing flag
            window.serviceOrdersInitializing[tabId] = false;
        }
    }, 100);
};

window.reinitializeAllServiceTables = function() {

    // Clear all initialization flags
    window.serviceOrdersInitialized = {};
    window.serviceOrdersInitializing = {};
    
    const activeTab = document.querySelector('.nav-link.active');
    if (activeTab) {
        const tabId = activeTab.getAttribute('href');

        window.handleTabTableInitialization(tabId);
    }
};

// Function to refresh all service orders tables without reloading the page
window.refreshAllServiceOrdersTables = function() {

    try {
        // Refresh individual tables if they exist
        if (window.todayServiceOrdersTable && $.fn.DataTable.isDataTable('#today-service-orders-table')) {

            window.todayServiceOrdersTable.ajax.reload(null, false);
        }

        if (window.tomorrowServiceOrdersTable && $.fn.DataTable.isDataTable('#tomorrow-service-orders-table')) {

            window.tomorrowServiceOrdersTable.ajax.reload(null, false);
        }

        if (window.weekServiceOrdersTable && $.fn.DataTable.isDataTable('#week-service-orders-table')) {

            window.weekServiceOrdersTable.ajax.reload(null, false);
        }

        if (window.allServiceOrdersTable && $.fn.DataTable.isDataTable('#all-service-orders-table')) {

            window.allServiceOrdersTable.ajax.reload(null, false);
        }

        if (window.deletedOrdersTable && $.fn.DataTable.isDataTable('#deletedOrdersTable')) {

            window.deletedOrdersTable.ajax.reload(null, false);
        }

        if (window.pendingServiceOrdersTable && $.fn.DataTable.isDataTable('#pending-service-orders-table')) {

            window.pendingServiceOrdersTable.ajax.reload(null, false);
        }

        // Update dashboard badges
        window.refreshDashboardBadges();

    } catch (error) {
        console.error('❌ Error refreshing tables:', error);
    }
};

window.ServiceOrdersColumnHelpers = {

    // Generate Order ID column (Order Number + Client)
    generateOrderIdColumn: function() {
        return {
            data: null,
            className: 'order-id-cell',
            responsivePriority: 1,
            render: function(data, type, row) {
                let html = '<div>';
                // Row 1: Order Number with Internal Notes Badge
                html += '<div class="order-number">SER-' + String(row.id).padStart(5, '0');
                
                // Add internal notes badge if order has internal notes (only for staff users)
                <?php if (auth()->user() && auth()->user()->user_type === 'staff'): ?>
                if (row.internal_notes_count && parseInt(row.internal_notes_count) > 0) {
                    const notesCount = parseInt(row.internal_notes_count);
                    const notesBadgeClass = notesCount > 0 ? 'internal-notes-badge has-notes' : 'internal-notes-badge';
                    
                    html += '<a href="#" class="' + notesBadgeClass + '" ';
                    html += 'data-bs-toggle="tooltip" data-bs-placement="top" title="Loading notes..." ';
                    html += 'data-order-id="' + row.id + '" ';
                    html += 'onmouseenter="loadNotesTooltip(this, ' + row.id + ')" ';
                    html += 'onclick="event.preventDefault(); event.stopPropagation(); window.location.href=\'<?= base_url() ?>service_orders/view/' + row.id + '#internal-notes-card\';">';
                    html += '<i class="ri-file-lock-line"></i>' + notesCount;
                    html += '</a>';
                }
                <?php endif; ?>
                
                html += '</div>';
                // Row 2: Client with building icon
                html += '<div class="client-info">';
                html += '<i class="ri-building-line"></i>';
                html += '<span>' + (row.client_name || 'N/A') + '</span>';
                html += '</div>';
                html += '</div>';
                return html;
            }
        };
    },

    // Generate TAG/RO column
    generateTagRoColumn: function() {
        return {
            data: null,
            className: 'tag-ro-cell',
            responsivePriority: 3,
            render: function(data, type, row) {
                let html = '<div>';
                // Row 1: TAG Number with Comments Icon
                html += '<div class="tag-info-row">';
                html += '<div class="tag-info' + (!row.tag_number || row.tag_number === '' ? ' empty' : '') + '">';
                if (row.tag_number && row.tag_number !== '') {
                    // Extract number only, removing any existing TAG prefix
                    const tagNumber = row.tag_number.replace(/^TAG[-]?/i, '');
                    html += 'TAG-' + tagNumber;
                } else {
                    html += 'TAG--';
                }
                html += '</div>';
                
                // Icons container (Comments and Notes)
                if ((row.comments_count && row.comments_count > 0) || (row.notes_count && row.notes_count > 0)) {
                    html += '<div class="icons-container" style="display: flex; gap: 0.25rem;">';
                    
                    // Comments Icon (only show if there are comments)
                    if (row.comments_count && row.comments_count > 0) {
                        html += '<a href="#" class="comments-icon" data-order-id="' + row.id + '" data-bs-toggle="tooltip" data-bs-placement="right" title="Loading comments...">';
                        html += '<i class="ri-chat-3-line"></i>';
                        html += '<span class="comment-count">' + row.comments_count + '</span>';
                        html += '</a>';
                    }
                    
                    // Notes Icon (only show if there are notes)
                    if (row.notes_count && row.notes_count > 0) {
                        html += '<a href="#" class="notes-icon" data-order-id="' + row.id + '" data-bs-toggle="tooltip" data-bs-placement="right" title="Loading notes...">';
                        html += '<i class="ri-sticky-note-line"></i>';
                        html += '<span class="note-count">' + row.notes_count + '</span>';
                        html += '</a>';
                    }
                    
                    html += '</div>';
                }
                html += '</div>';
                
                // Row 2: RO Number
                html += '<div class="ro-info' + (!row.ro_number || row.ro_number === '' ? ' empty' : '') + '">';
                if (row.ro_number && row.ro_number !== '') {
                    // Extract number only, removing any existing RO prefix
                    const roNumber = row.ro_number.replace(/^RO[-]?/i, '');
                    html += 'RO-' + roNumber;
                } else {
                    html += 'RO--';
                }
                html += '</div>';
                // Row 3: User Information (Contact/Salesperson)
                html += '<div class="user-info">';
                if (row.salesperson_name && row.salesperson_name !== '' && row.salesperson_name !== 'N/A') {
                    html += '<i class="ri-user-line"></i>';
                    html += '<span>' + row.salesperson_name + '</span>';
                } else if (row.contact_name && row.contact_name !== '' && row.contact_name !== 'N/A') {
                    html += '<i class="ri-user-line"></i>';
                    html += '<span>' + row.contact_name + '</span>';
                } else {
                    html += '<i class="ri-user-line"></i>';
                    html += '<span>No contact</span>';
                }
                html += '</div>';
                html += '</div>';
                return html;
            }
        };
    },

    // Generate Vehicle column (Vehicle + VIN + Instructions)
    generateVehicleColumn: function() {
        return {
            data: null,
            className: 'vehicle-cell',
            responsivePriority: 2,
            render: function(data, type, row) {
                let html = '<div>';
                // Row 1: Vehicle Information
                html += '<div class="vehicle-name">' + (row.vehicle || 'N/A') + '</div>';
                // Row 2: VIN (last 8 characters)
                if (row.vin && row.vin !== '') {
                    const vinDisplay = row.vin.length > 8 ? row.vin.slice(-8) : row.vin;
                    html += '<div class="vin-info">VIN: ' + vinDisplay + '</div>';
                } else {
                    html += '<div class="vin-info">VIN: N/A</div>';
                }
                // Row 3: Instructions Badge (if available)
                if (row.instructions && row.instructions.trim() !== '') {
                    html += '<div class="instructions-badge" data-bs-toggle="tooltip" data-bs-placement="top" title="' + 
                           row.instructions.replace(/"/g, '&quot;') + '">';
                    html += '<i class="ri-information-line me-1"></i>Instructions';
                    html += '</div>';
                }
                html += '</div>';
                return html;
            }
        };
    },

    // Generate Due column (Time + Date)
    generateDueColumn: function(dateContext = 'general') {
        return {
            data: null,
            className: 'due-cell',
            responsivePriority: 4,
            render: function(data, type, row) {
                let html = '<div>';
                // Row 1: Time
                if (row.time) {
                    const timeStr = new Date('2000-01-01T' + row.time).toLocaleTimeString('en-US', { 
                        hour: 'numeric', 
                        minute: '2-digit', 
                        hour12: true 
                    });
                    html += '<div class="due-time">' + timeStr + '</div>';
                } else {
                    html += '<div class="due-time">N/A</div>';
                }
                // Row 2: Date
                if (row.date) {
                    // Parse date safely to avoid timezone issues
                    const dateParts = row.date.split('-');
                    const dateObj = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
                    
                    // Always show formatted date (no "Today" or "Tomorrow" text)
                    const formattedDate = dateObj.toLocaleDateString('en-US', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                    html += '<div class="due-date">' + formattedDate + '</div>';
                } else {
                    html += '<div class="due-date">N/A</div>';
                }
                html += '</div>';
                return html;
            }
        };
    },

    // Generate Status column
    generateStatusColumn: function() {
        return {
            data: null,
            className: 'status-cell',
            responsivePriority: 5,
            render: function(data, type, row) {
                const status = row.status || 'pending';
                const statusColors = {
                    'pending': 'bg-warning',
                    'processing': 'bg-info',
                    'in_progress': 'bg-primary',
                    'completed': 'bg-success',
                    'cancelled': 'bg-danger'
                };
                const colorClass = statusColors[status] || 'bg-secondary';
                return '<span class="badge ' + colorClass + ' status-badge">' + 
                       status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ') + '</span>';
            }
        };
    },

    // Generate Actions column
    generateActionsColumn: function(baseUrl = '', showDelete = true) {
        return {
            data: null,
            className: 'actions-cell',
            responsivePriority: 1,
            orderable: false,
            render: function(data, type, row) {
                let html = '<div class="d-flex justify-content-center gap-2 action-buttons">';
                html += '<a href="' + baseUrl + 'service_orders/view/' + row.id + '" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="View">';
                html += '<i class="ri-eye-fill"></i>';
                html += '</a>';
                html += '<a href="#" class="link-success fs-15 edit-service-order-btn" data-id="' + row.id + '" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">';
                html += '<i class="ri-edit-fill"></i>';
                html += '</a>';
                if (showDelete) {
                    html += '<a href="#" class="link-danger fs-15 delete-service-order-btn" data-id="' + row.id + '" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">';
                    html += '<i class="ri-delete-bin-line"></i>';
                    html += '</a>';
                }
                html += '</div>';
                return html;
            }
        };
    },

    // Generate standard columns array for active orders
    generateStandardColumns: function(baseUrl = '', dateContext = 'general', showDelete = true) {
        return [
            this.generateOrderIdColumn(),
            this.generateTagRoColumn(),
            this.generateVehicleColumn(),
            this.generateDueColumn(dateContext),
            this.generateStatusColumn(),
            this.generateActionsColumn(baseUrl, showDelete)
        ];
    },

    // Generate deleted orders columns (different actions)
    generateDeletedOrdersColumns: function(baseUrl = '') {
        return [
            this.generateOrderIdColumn(),
            this.generateTagRoColumn(),
            this.generateVehicleColumn(),
            this.generateDueColumn(),
            this.generateStatusColumn(),
            {
                data: null,
                className: 'due-cell',
                responsivePriority: 4,
                render: function(data, type, row) {
                    if (row.deleted_at) {
                        // Handle both date and datetime formats safely
                        let dateObj;
                        if (row.deleted_at.includes(' ')) {
                            // DateTime format (YYYY-MM-DD HH:MM:SS)
                            dateObj = new Date(row.deleted_at.replace(' ', 'T'));
                        } else {
                            // Date format (YYYY-MM-DD)
                            const dateParts = row.deleted_at.split('-');
                            dateObj = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
                        }
                        const formattedDate = dateObj.toLocaleDateString('en-US', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });
                        return '<div class="due-date">' + formattedDate + '</div>';
                    }
                    return '<div class="due-date">N/A</div>';
                }
            },
            {
                data: null,
                className: 'actions-cell',
                responsivePriority: 1,
                orderable: false,
                render: function(data, type, row) {
                    let html = '<div class="d-flex justify-content-center gap-2 action-buttons">';
                    html += '<a href="#" class="link-success fs-15" onclick="restoreOrder(' + row.id + ')" data-bs-toggle="tooltip" data-bs-placement="top" title="Restore">';
                    html += '<i class="ri-restart-line"></i>';
                    html += '</a>';
                    html += '<a href="' + baseUrl + 'service_orders/view/' + row.id + '" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="View">';
                    html += '<i class="ri-eye-fill"></i>';
                    html += '</a>';
                    html += '<a href="#" class="link-danger fs-15" onclick="permanentDeleteOrder(' + row.id + ')" data-bs-toggle="tooltip" data-bs-placement="top" title="Permanent Delete">';
                    html += '<i class="ri-delete-bin-fill"></i>';
                    html += '</a>';
                    html += '</div>';
                    return html;
                }
            }
        ];
    },

    // Standard drawCallback function
    standardDrawCallback: function() {
        // Initialize tooltips
        if (typeof $ !== 'undefined') {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
        // Initialize feather icons if available
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        // Initialize clickable rows
        this.initializeClickableRows();
        // Initialize comments tooltips and click handlers
        this.initializeCommentsHandlers();
    },

    // Initialize clickable rows functionality
    initializeClickableRows: function() {
        if (typeof $ === 'undefined') return;
        
        // Remove existing click handlers to avoid duplicates
        $('.service-orders-table tbody').off('click', 'tr');
        
        // Add click handler for table rows
        $('.service-orders-table tbody').on('click', 'tr', function(e) {
            // Don't trigger if clicking on action buttons or links
            if ($(e.target).closest('.action-buttons, .actions-cell, a, button, .comments-icon, .notes-icon').length > 0) {
                return;
            }
            
            // Get the DataTable instance
            const table = $(this).closest('table').DataTable();
            const rowData = table.row(this).data();
            
            if (rowData && rowData.id) {
                // Add visual feedback
                $(this).addClass('row-selected');
                setTimeout(() => {
                    $(this).removeClass('row-selected');
                }, 200);
                
                // Navigate to view page
                const baseUrl = '<?= base_url() ?>';
                window.location.href = baseUrl + 'service_orders/view/' + rowData.id;
            }
        });
    },

    // Initialize comments and notes handlers
    initializeCommentsHandlers: function() {
        if (typeof $ === 'undefined') return;
        
        const self = this;
        
        // Remove existing handlers to avoid duplicates
        $('.service-orders-table').off('click', '.comments-icon');
        $('.service-orders-table').off('mouseenter', '.comments-icon');
        $('.service-orders-table').off('click', '.notes-icon');
        $('.service-orders-table').off('mouseenter', '.notes-icon');
        
        // Click handler for comments icon
        $('.service-orders-table').on('click', '.comments-icon', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const orderId = $(this).data('order-id');
            if (orderId) {
                const baseUrl = '<?= base_url() ?>';
                window.location.href = baseUrl + 'service_orders/view/' + orderId + '#comments';
            }
        });

        // Click handler for notes icon
        $('.service-orders-table').on('click', '.notes-icon', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const orderId = $(this).data('order-id');
            if (orderId) {
                const baseUrl = '<?= base_url() ?>';
                window.location.href = baseUrl + 'service_orders/view/' + orderId + '#notes';
            }
        });
        
        // Hover handler for dynamic tooltip with comments preview
        $('.service-orders-table').on('mouseenter', '.comments-icon', function() {
            const $icon = $(this);
            const orderId = $icon.data('order-id');
            
            if (!orderId) return;
            
            // Prevent multiple requests
            if ($icon.data('loading') === true) return;
            $icon.data('loading', true);
            
            // Dispose any existing tooltip
            $icon.tooltip('dispose');
            
            // Show loading tooltip
            $icon.tooltip({
                title: 'Loading comments...',
                placement: 'right',
                trigger: 'manual',
                html: false
            }).tooltip('show');
            
            // Fetch comments preview
            const fetchUrl = '<?= base_url() ?>service_orders/getCommentsPreview/' + orderId;
            console.log('🔄 Fetching comments preview from:', fetchUrl);
            
            fetch(fetchUrl)
                .then(response => {
                    console.log('📨 Response status:', response.status);
                    console.log('📨 Response ok:', response.ok);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Comments preview data received:', data);
                    
                    if (data.success) {
                        let tooltipContent = '<div class="comment-preview-tooltip">';
                        
                        if (data.preview_comments && data.preview_comments.length > 0) {
                            data.preview_comments.forEach(comment => {
                                tooltipContent += '<div class="comment-preview-item">';
                                tooltipContent += '<div class="comment-preview-user">' + (comment.user_name || 'Unknown User') + '</div>';
                                tooltipContent += '<div class="comment-preview-text">' + (comment.comment || 'No comment text') + '</div>';
                                tooltipContent += '<div class="comment-preview-time">' + (comment.created_at || '') + '</div>';
                                tooltipContent += '</div>';
                            });
                            
                            if (data.total_comments && data.total_comments > 3) {
                                tooltipContent += '<div style="text-align: center; margin-top: 0.5rem; color: #9ca3af; font-size: 0.75rem;">';
                                tooltipContent += 'Click to see all ' + data.total_comments + ' comments';
                                tooltipContent += '</div>';
                            }
                        } else {
                            tooltipContent += '<div style="text-align: center; color: #9ca3af; padding: 0.5rem;">No comments available</div>';
                        }
                        
                        tooltipContent += '</div>';
                        
                        // Dispose loading tooltip and create new one with content
                        $icon.tooltip('dispose');
                        $icon.tooltip({
                            title: tooltipContent,
                            html: true,
                            placement: 'right',
                            trigger: 'manual',
                            container: 'body',
                            customClass: 'comments-preview-tooltip'
                        });
                        
                        // Show the new tooltip
                        $icon.tooltip('show');
                        
                        // Auto-hide after a delay when mouse leaves
                        $icon.one('mouseleave', function() {
                            setTimeout(() => {
                                $icon.tooltip('hide');
                            }, 300);
                        });
                        
                    } else {
                        $icon.tooltip('dispose');
                        $icon.tooltip({
                            title: data.message || 'Error loading comments',
                            placement: 'right',
                            trigger: 'manual'
                        }).tooltip('show');
                        
                        setTimeout(() => {
                            $icon.tooltip('hide');
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error loading comments preview:', error);
                    
                    $icon.tooltip('dispose');
                    $icon.tooltip({
                        title: 'Error loading comments',
                        placement: 'right',
                        trigger: 'manual'
                    }).tooltip('show');
                    
                    setTimeout(() => {
                        $icon.tooltip('hide');
                    }, 2000);
                })
                .finally(() => {
                    $icon.data('loading', false);
                });
        });

        // Hover handler for dynamic tooltip with notes preview
        $('.service-orders-table').on('mouseenter', '.notes-icon', function() {
            const $icon = $(this);
            const orderId = $icon.data('order-id');
            
            if (!orderId) return;
            
            // Prevent multiple requests
            if ($icon.data('loading') === true) return;
            $icon.data('loading', true);
            
            // Dispose any existing tooltip
            $icon.tooltip('dispose');
            
            // Show loading tooltip
            $icon.tooltip({
                title: 'Loading notes...',
                placement: 'right',
                trigger: 'manual',
                html: false
            }).tooltip('show');
            
            // Fetch notes preview
            const fetchUrl = '<?= base_url() ?>service_orders/getNotesPreview/' + orderId;
            console.log('🔄 Fetching notes preview from:', fetchUrl);
            
            fetch(fetchUrl)
                .then(response => {
                    console.log('📨 Notes response status:', response.status);
                    console.log('📨 Notes response ok:', response.ok);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Notes preview data received:', data);
                    
                    if (data.success) {
                        let tooltipContent = '<div class="note-preview-tooltip">';
                        
                        if (data.preview_notes && data.preview_notes.length > 0) {
                            data.preview_notes.forEach(note => {
                                tooltipContent += '<div class="note-preview-item">';
                                tooltipContent += '<div class="note-preview-author">' + (note.author_name || 'Unknown Author') + '</div>';
                                tooltipContent += '<div class="note-preview-text">' + (note.note || 'No note text') + '</div>';
                                tooltipContent += '<div class="note-preview-time">' + (note.created_at || '') + '</div>';
                                tooltipContent += '</div>';
                            });
                            
                            if (data.total_notes && data.total_notes > 3) {
                                tooltipContent += '<div style="text-align: center; margin-top: 0.5rem; color: #9ca3af; font-size: 0.75rem;">';
                                tooltipContent += 'Click to see all ' + data.total_notes + ' notes';
                                tooltipContent += '</div>';
                            }
                        } else {
                            tooltipContent += '<div style="text-align: center; color: #9ca3af; padding: 0.5rem;">No notes available</div>';
                        }
                        
                        tooltipContent += '</div>';
                        
                        // Dispose loading tooltip and create new one with content
                        $icon.tooltip('dispose');
                        $icon.tooltip({
                            title: tooltipContent,
                            html: true,
                            placement: 'right',
                            trigger: 'manual',
                            container: 'body',
                            customClass: 'notes-preview-tooltip'
                        });
                        
                        // Show the new tooltip
                        $icon.tooltip('show');
                        
                        // Auto-hide after a delay when mouse leaves
                        $icon.one('mouseleave', function() {
                            setTimeout(() => {
                                $icon.tooltip('hide');
                            }, 300);
                        });
                        
                    } else {
                        $icon.tooltip('dispose');
                        $icon.tooltip({
                            title: data.message || 'Error loading notes',
                            placement: 'right',
                            trigger: 'manual'
                        }).tooltip('show');
                        
                        setTimeout(() => {
                            $icon.tooltip('hide');
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error loading notes preview:', error);
                    
                    $icon.tooltip('dispose');
                    $icon.tooltip({
                        title: 'Error loading notes',
                        placement: 'right',
                        trigger: 'manual'
                    }).tooltip('show');
                    
                    setTimeout(() => {
                        $icon.tooltip('hide');
                    }, 2000);
                })
                .finally(() => {
                    $icon.data('loading', false);
                });
        });
    }
};

// Function to refresh dashboard badges
window.refreshDashboardBadges = function() {
    // Only refresh if we're on the dashboard tab or if dashboard functions are available
    if (typeof loadDashboardData === 'function') {
        loadDashboardData();
    } else {
        // Fallback: manually fetch and update badges
        fetch('<?= base_url('/service_orders/dashboard-data') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update badges
                    const todayBadge = document.getElementById('todayOrdersBadge');
                    const tomorrowBadge = document.getElementById('tomorrowOrdersBadge');
                    const pendingBadge = document.getElementById('pendingOrdersBadge');
                    const weekBadge = document.getElementById('weekOrdersBadge');
                    
                    if (todayBadge) {
                        todayBadge.textContent = data.todayCount || 0;
                        todayBadge.style.display = data.todayCount > 0 ? 'inline' : 'none';
                    }
                    
                    if (tomorrowBadge) {
                        tomorrowBadge.textContent = data.tomorrowCount || 0;
                        tomorrowBadge.style.display = data.tomorrowCount > 0 ? 'inline' : 'none';
                    }
                    
                    if (pendingBadge) {
                        pendingBadge.textContent = data.pendingCount || 0;
                        pendingBadge.style.display = data.pendingCount > 0 ? 'inline' : 'none';
                    }
                    
                    if (weekBadge) {
                        weekBadge.textContent = data.weekCount || 0;
                        weekBadge.style.display = data.weekCount > 0 ? 'inline' : 'none';
                    }
                    
                    // Update dashboard counts if elements exist
                    const todayCount = document.getElementById('todayOrdersCount');
                    const pendingCount = document.getElementById('pendingOrdersCount');
                    const weekCount = document.getElementById('weekOrdersCount');
                    const totalCount = document.getElementById('totalOrdersCount');
                    
                    if (todayCount) todayCount.textContent = data.todayCount || 0;
                    if (pendingCount) pendingCount.textContent = data.pendingCount || 0;
                    if (weekCount) weekCount.textContent = data.weekCount || 0;
                    if (totalCount) totalCount.textContent = data.totalCount || 0;
                }
            })
            .catch(error => {
                console.error('Error refreshing dashboard badges:', error);
            });
    }
};


// Global function to handle notes tooltips
window.loadNotesTooltip = function(element, orderId) {
    const $icon = $(element);
    
    if (!orderId) return;
    
    // Prevent multiple requests
    if ($icon.data('loading') === true) return;
    $icon.data('loading', true);
    
    // Dispose any existing tooltip
    $icon.tooltip('dispose');
    
    // Show loading tooltip
    $icon.tooltip({
        title: 'Loading notes...',
        placement: 'top',
        trigger: 'manual',
        html: false
    }).tooltip('show');
    
    // Fetch notes preview
    const fetchUrl = '<?= base_url() ?>service_orders/getNotesPreview/' + orderId;
    
    fetch(fetchUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                let tooltipContent = '<div class="note-preview-tooltip">';
                
                if (data.preview_notes && data.preview_notes.length > 0) {
                    data.preview_notes.forEach(note => {
                        tooltipContent += '<div class="note-preview-item">';
                        tooltipContent += '<div class="note-preview-author">' + (note.author_name || 'Unknown Author') + '</div>';
                        tooltipContent += '<div class="note-preview-text">' + (note.note || 'No note text') + '</div>';
                        tooltipContent += '<div class="note-preview-time">' + (note.created_at || '') + '</div>';
                        tooltipContent += '</div>';
                    });
                    
                    if (data.total_notes && data.total_notes > 3) {
                        tooltipContent += '<div style="text-align: center; margin-top: 0.5rem; color: #9ca3af; font-size: 0.75rem;">';
                        tooltipContent += 'Click to see all ' + data.total_notes + ' notes';
                        tooltipContent += '</div>';
                    }
                } else {
                    tooltipContent += '<div style="text-align: center; color: #9ca3af; padding: 0.5rem;">No notes available</div>';
                }
                
                tooltipContent += '</div>';
                
                // Dispose loading tooltip and create new one with content
                $icon.tooltip('dispose');
                $icon.tooltip({
                    title: tooltipContent,
                    html: true,
                    placement: 'top',
                    trigger: 'manual',
                    container: 'body',
                    customClass: 'notes-preview-tooltip'
                });
                
                // Show the new tooltip
                $icon.tooltip('show');
                
                // Auto-hide after a delay when mouse leaves
                $icon.one('mouseleave', function() {
                    setTimeout(() => {
                        $icon.tooltip('hide');
                    }, 500);
                });
                
                // Add click handler to redirect to notes section
                $icon.off('click').on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Hide tooltip before redirecting
                    $icon.tooltip('hide');
                    
                    // Navigate to the service order view with notes section
                    const baseUrl = '<?= base_url() ?>';
                    window.location.href = baseUrl + 'service_orders/view/' + orderId + '#internal-notes-card';
                });
                
            } else {
                $icon.tooltip('dispose');
                $icon.tooltip({
                    title: data.message || 'Error loading notes',
                    placement: 'top',
                    trigger: 'manual'
                }).tooltip('show');
                
                setTimeout(() => {
                    $icon.tooltip('hide');
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error loading notes preview:', error);
            
            $icon.tooltip('dispose');
            $icon.tooltip({
                title: 'Error loading notes',
                placement: 'top',
                trigger: 'manual'
            }).tooltip('show');
            
            setTimeout(() => {
                $icon.tooltip('hide');
            }, 2000);
        })
        .finally(() => {
            $icon.data('loading', false);
        });
};
</script> 
