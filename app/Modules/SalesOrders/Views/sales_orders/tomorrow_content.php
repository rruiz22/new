<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-none">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center">
                    <i data-feather="calendar" class="icon-sm me-1"></i>
                    <?= lang('App.tomorrow_orders') ?> <span id="tomorrowOrderCount"></span> - <?= date('l, F j, Y', strtotime('+1 day')) ?>
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshTomorrowTable" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-container overflow-hidden">
                    <table id="tomorrow-orders-table" class="table table-borderless table-hover table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><?= lang('App.order_id') ?></th>
                                <th scope="col"><?= lang('App.stock') ?></th>
                                <th scope="col"><?= lang('App.vehicle') ?></th>
                                <th scope="col"><?= lang('App.due') ?></th>
                                <th scope="col"><?= lang('App.status') ?></th>
                                <th scope="col"><?= lang('App.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los datos se cargarán vía AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Same styles as today_content.php */
.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--bs-body-color);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 767.98px) {
    .card-title {
        font-size: 1.25rem;
    }
}

@media (max-width: 575.98px) {
    .card-title {
        font-size: 1.1rem;
    }
}

/* Table Container Styling */
.table-container {
    width: 100%;
    max-width: 100%;
    position: relative;
    padding: 1rem;
}

/* Force DataTable to use full width and prevent overflow */
#tomorrow-orders-table {
    width: 100% !important;
    max-width: 100% !important;
    table-layout: auto !important;
}

#tomorrow-orders-table_wrapper {
    width: 100% !important;
    max-width: 100% !important;
    overflow: hidden !important;
}

#tomorrow-orders-table thead th {
    width: auto !important;
    max-width: none !important;
    white-space: nowrap;
}

.dataTables_wrapper {
    width: 100% !important;
    max-width: 100% !important;
}

.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #e3ebf0 !important;
    border-radius: 6px !important;
    padding: 8px 12px !important;
    transition: all 0.15s ease-in-out !important;
    background-color: #fff !important;
}

.dataTables_wrapper .dataTables_length select:hover,
.dataTables_wrapper .dataTables_filter input:hover {
    border-color: #405189 !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.1) !important;
}

.dataTables_wrapper .dataTables_length select:focus,
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #405189 !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25) !important;
    outline: 0 !important;
}

.dataTables_wrapper .dataTables_info {
    padding-top: 0.75rem !important;
    color: #64748b !important;
    font-size: 0.875rem !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.375rem 0.75rem !important;
    margin: 0 2px !important;
    border: 1px solid #e3ebf0 !important;
    border-radius: 6px !important;
    color: #64748b !important;
    text-decoration: none !important;
    transition: all 0.15s ease-in-out !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    border-color: #405189 !important;
    background-color: #405189 !important;
    color: #fff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    border-color: #405189 !important;
    background-color: #405189 !important;
    color: #fff !important;
}

.link-primary {
    color: #405189 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-primary:hover {
    color: #2c3e50 !important;
}

.link-success {
    color: #0ab39c !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-success:hover {
    color: #087f69 !important;
}

.link-danger {
    color: #f06548 !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
}

.link-danger:hover {
    color: #d63384 !important;
}

.fs-15 {
    font-size: 15px !important;
}

.hstack {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
}

.gap-2 {
    gap: 0.5rem !important;
}

.flex-wrap {
    flex-wrap: wrap !important;
}

/* Elegant DataTable Loading Styling - Contained within table */
.dataTables_processing {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: rgba(255, 255, 255, 0.1) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 12px !important;
    z-index: 100 !important;
    align-items: center !important;
    justify-content: center !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
    color: #405189 !important;
    font-size: 1.1rem !important;
    font-weight: 500 !important;
    letter-spacing: 0.5px !important;
    animation: elegantFadeIn 0.4s ease-out !important;
}

/* Only show as flex when DataTables makes it visible */
.dataTables_processing[style*="display: block"] {
    display: flex !important;
}

.dataTables_processing::before {
    content: '';
    width: 50px;
    height: 50px;
    border: 3px solid rgba(64, 81, 137, 0.1);
    border-top: 3px solid #405189;
    border-radius: 50%;
    animation: elegantSpin 1s linear infinite;
    margin-right: 1rem;
    flex-shrink: 0;
}

@keyframes elegantFadeIn {
    from {
        opacity: 0;
        
        -webkit-
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        
        -webkit-
        transform: scale(1);
    }
}

@keyframes elegantSpin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Center table headers */
#tomorrow-orders-table thead th {
    text-align: center !important;
}

/* Order count styling in title */
#tomorrowOrderCount {
    font-weight: 600;
    margin-left: 0.25rem;
    transition: color 0.3s ease;
}

/* Clickeable row styling */
#tomorrow-orders-table tbody tr {
    cursor: pointer !important;
    transition: background-color 0.15s ease !important;
}

#tomorrow-orders-table tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.05) !important;
}

#tomorrow-orders-table tbody tr:hover td {
    background-color: transparent !important;
}

/* Status dropdown styling */
.status-dropdown {
    border: 1px solid #e3ebf0 !important;
    border-radius: 4px !important;
    transition: all 0.15s ease !important;
    font-weight: 500 !important;
    text-align: center !important;
    text-align-last: center !important;
}

.status-dropdown:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25) !important;
}

/* Status dropdown color classes */
.status-pending {
    background-color: #fff3cd !important;
    border-color: #ffecb5 !important;
    color: #664d03 !important;
}

. {
    background-color: #cff4fc !important;
    border-color: #b8daff !important;
    color: #055160 !important;
}

.status-in_progress {
    background-color: #d1ecf1 !important;
    border-color: #b8daff !important;
    color: #0c5460 !important;
}

.status-completed {
    background-color: #d1e7dd !important;
    border-color: #badbcc !important;
    color: #0f5132 !important;
}

.status-cancelled {
    background-color: #f8d7da !important;
    border-color: #f5c6cb !important;
    color: #721c24 !important;
}

/* Force status column styling */
.dataTables_wrapper tbody td:has(.status-dropdown) {
    text-align: center !important;
    vertical-align: middle !important;
    padding: 8px !important;
}

.dataTables_wrapper .status-dropdown {
    width: 120px !important;
    margin: 0 auto !important;
    text-align: center !important;
    text-align-last: center !important;
    display: block !important;
}

/* Prevent action buttons from triggering row click */
.action-buttons {
    position: relative;
    z-index: 10;
}

/* Tooltip content styling */
.tooltip-inner {
    max-width: 350px !important;
    text-align: left !important;
}

.action-buttons a {
    position: relative;
    z-index: 11;
}

/* Comments Badge Styles */
.comments-badge {
    display: inline-flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: #64748b;
    text-decoration: none;
    transition: all 0.2s ease;
    margin-left: 0.25rem;
}

.comments-badge:hover {
    background: #405189;
    border-color: #405189;
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(64, 81, 137, 0.2);
}

.comments-badge.has-comments {
    background: #ecfdf5;
    border-color: #10b981;
    color: #059669;
}

.comments-badge.has-comments:hover {
    background: #10b981;
    border-color: #10b981;
    color: #fff;
}

.comments-badge i {
    font-size: 0.7rem;
    margin-right: 0.25rem;
}

/* Internal Notes Badge Styles */
.internal-notes-badge {
    display: inline-flex;
    align-items: center;
    background: #fef3f2;
    border: 1px solid #fecaca;
    border-radius: 6px;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: #dc2626;
    text-decoration: none;
    transition: all 0.2s ease;
    margin-left: 0.25rem;
}

.internal-notes-badge:hover {
    background: #dc2626;
    border-color: #dc2626;
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
}

.internal-notes-badge.has-notes {
    background: #fef2f2;
    border-color: #ef4444;
    color: #dc2626;
}

.internal-notes-badge.has-notes:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: #fff;
}

.internal-notes-badge i {
    font-size: 0.7rem;
    margin-right: 0.25rem;
}

/* Duplicate Indicator Styles */
.duplicate-indicator {
    display: inline-flex;
    align-items: center;
    padding: 0.125rem 0.375rem;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    vertical-align: middle;
    cursor: pointer;
}

/* CRITICAL: Ensure status columns maintain styling even with duplicate badges */
.dataTables_wrapper tbody tr:has(.duplicate-indicator) td:has(.status-dropdown) {
    text-align: center !important;
    vertical-align: middle !important;
    padding: 8px !important;
}

.dataTables_wrapper tbody tr:has(.duplicate-indicator) .status-dropdown {
    width: 120px !important;
    margin: 0 auto !important;
    text-align: center !important;
    text-align-last: center !important;
    display: block !important;
}

.duplicate-indicator i {
    font-size: 0.6rem;
    margin-right: 0.125rem;
}

.duplicate-indicator small {
    font-size: 0.6rem;
    font-weight: 600;
    margin-left: 0.125rem;
}

.stock-duplicate {
    background: #fff3cd;
    border: 1px solid #ffecb5;
    color: #997404;
}

.stock-duplicate:hover {
    background: #ffecb5;
    color: #664d03;
    transform: scale(1.05);
}

.vin-duplicate {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.vin-duplicate:hover {
    background: #f5c6cb;
    color: #491217;
    transform: scale(1.05);
}
</style>

<script>
// Wait for DataTables to be available
function waitForDataTablesOnTomorrow(callback) {
    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForDataTablesOnTomorrow(callback);
        }, 100);
    }
}

waitForDataTablesOnTomorrow(function() {

    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    let tomorrowOrdersTable;
    let isInitializing = false;

    // Get tomorrow's date in YYYY-MM-DD format
    function getTomorrowDate() {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const year = tomorrow.getFullYear();
        const month = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const day = String(tomorrow.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Function to update the order count in title
    function updateTomorrowOrderCount(recordsFiltered) {
        const countElement = document.getElementById('tomorrowOrderCount');
        const badgeElement = document.getElementById('tomorrowOrdersBadge');
        
        if (countElement) {
            if (recordsFiltered > 0) {
                countElement.textContent = `(${recordsFiltered})`;
                countElement.style.color = '#0ab39c'; // Green color for positive count
            } else {
                countElement.textContent = '(0)';
                countElement.style.color = '#64748b'; // Muted color for zero count
            }
        }

        // Update the badge in the tab navigation
        if (badgeElement) {
            if (recordsFiltered > 0) {
                badgeElement.textContent = recordsFiltered;
                badgeElement.classList.add('show');
                badgeElement.style.display = 'inline-block';
            } else {
                badgeElement.style.display = 'none';
                badgeElement.classList.remove('show');
            }
        }
    }

    // DataTable Configuration
    function initializeTomorrowDataTable() {
        if (isInitializing) {
            return;
        }

        isInitializing = true;

        // Check dependencies
        if (typeof $ === 'undefined') {
            console.error('❌ jQuery not available');
            isInitializing = false;
            return;
        }

        if (typeof $.fn.DataTable === 'undefined') {
            console.error('❌ DataTables not available');
            isInitializing = false;
            return;
        }

        // Check table element exists
        const tableElement = document.getElementById('tomorrow-orders-table');
        if (!tableElement) {
            console.error('❌ Table #tomorrow-orders-table not found');
            isInitializing = false;
            return;
        }

        try {
            // Destroy existing table if it exists
            if ($.fn.DataTable.isDataTable('#tomorrow-orders-table')) {
                $('#tomorrow-orders-table').DataTable().destroy();
                $('#tomorrow-orders-table').off();
            }

            // Force table width before initialization
            $('#tomorrow-orders-table').css({'width': '100%', 'max-width': '100%'});
            $('.table-container').css({'width': '100%', 'max-width': '100%', 'overflow': 'hidden'});

            const tomorrowDate = getTomorrowDate();

            tomorrowOrdersTable = $('#tomorrow-orders-table').DataTable({
                processing: false,
                serverSide: true,
                responsive: false,
                scrollX: false,
                autoWidth: false,
                columnDefs: [
                    { className: "text-center", targets: [0, 1, 2, 3, 4, 5] },
                    { orderable: false, searchable: false, targets: 5 }
                ],
                ajax: {
                    url: '<?= base_url('sales_orders/all_content') ?>',
                    type: 'POST',
                    data: function(d) {
                        // Auto-filter for tomorrow's date
                        d.date_from_filter = tomorrowDate;
                        d.date_to_filter = tomorrowDate;
                        
                        // Add global client filter if available
                        const globalClientFilter = getCurrentClientFilter ? getCurrentClientFilter() : '';
                        d.client_filter = globalClientFilter || '';
                        
                        // Clear other filters to avoid interference from other tabs
                        d.status_filter = '';
                        d.contact_filter = '';

                        // Add CSRF token
                        d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                        
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Error loading tomorrow data:', error, xhr.responseText);
                        let errorMessage = '<?= lang('App.error_loading_data') ?>';

                        if (xhr.status === 403) {
                            errorMessage = '<?= lang('App.access_denied') ?>';
                        } else if (xhr.status === 500) {
                            errorMessage = '<?= lang('App.server_error') ?>';
                        } else if (xhr.status === 404) {
                            errorMessage = '<?= lang('App.service_not_found') ?>';
                        }

                        showToast('error', errorMessage);
                    }
                },
                columns: [
                    {
                        data: 'order_id',
                        render: function(data, type, row) {
                            let html = `<div>`;
                            html += `<a href="<?= base_url('sales_orders/view/') ?>${row.id}" class="fw-medium text-primary text-decoration-none">${data}</a>`;
                            
                            // Add comments badge if order has comments
                            if (row.comments_count && parseInt(row.comments_count) > 0) {
                                const commentCount = parseInt(row.comments_count);
                                const badgeClass = commentCount > 0 ? 'comments-badge has-comments' : 'comments-badge';
                                
                                // Create simple tooltip text
                                let tooltip = '';
                                if (row.comments && row.comments.length > 0) {
                                    // Create a simple text tooltip with comments
                                    const commentsPreview = row.comments.slice(0, 2).map(comment => 
                                        `${comment.author_name}: ${comment.comment.substring(0, 50)}${comment.comment.length > 50 ? '...' : ''}`
                                    ).join(' | ');
                                    
                                    tooltip = `${commentCount} ${commentCount === 1 ? 'comentario' : 'comentarios'} - ${commentsPreview}`;
                                    if (commentCount > 2) {
                                        tooltip += ` | +${commentCount - 2} más...`;
                                    }
                                } else {
                                    // Fallback to simple count
                                    const commentText = `<?= lang('App.comment_count') ?>`;
                                    tooltip = commentCount === 1 ? 
                                        commentText.replace('{0}', commentCount).split('|')[0] :
                                        commentText.replace('{0}', commentCount).split('|')[1];
                                }
                                
                                html += `<a href="<?= base_url('sales_orders/view/') ?>${row.id}#comments" class="${badgeClass}" 
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="${tooltip}">
                                    <i class="ri-message-2-line"></i>${commentCount}
                                </a>`;
                            }
                            
                            // Add internal notes badge if order has internal notes (only for staff users)
                            <?php if (auth()->user() && auth()->user()->user_type === 'staff'): ?>
                            if (row.internal_notes_count && parseInt(row.internal_notes_count) > 0) {
                                const notesCount = parseInt(row.internal_notes_count);
                                const notesBadgeClass = notesCount > 0 ? 'internal-notes-badge has-notes' : 'internal-notes-badge';
                                
                                // Create simple tooltip text for internal notes
                                let notesTooltip = '';
                                if (row.internal_notes && row.internal_notes.length > 0) {
                                    // Create a simple text tooltip with notes
                                    const notesPreview = row.internal_notes.slice(0, 2).map(note => 
                                        `${note.author_name}: ${note.content.substring(0, 50)}${note.content.length > 50 ? '...' : ''}`
                                    ).join(' | ');
                                    
                                    notesTooltip = `${notesCount} ${notesCount === 1 ? 'nota interna' : 'notas internas'} - ${notesPreview}`;
                                    if (notesCount > 2) {
                                        notesTooltip += ` | +${notesCount - 2} más...`;
                                    }
                                } else {
                                    // Fallback to simple count
                                    notesTooltip = `${notesCount} ${notesCount === 1 ? 'nota interna' : 'notas internas'}`;
                                }
                                
                                html += `<a href="<?= base_url('sales_orders/view/') ?>${row.id}#notes-pane" class="${notesBadgeClass}" 
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="${notesTooltip}">
                                    <i class="ri-file-lock-line"></i>${notesCount}
                                </a>`;
                            }
                            <?php endif; ?>
                            
                            // Add client name below order ID with business icon
                            if (row.client_name && row.client_name !== 'N/A') {
                                html += `<div class="text-muted small mt-1">
                                    <i class="ri-building-line me-1"></i>${row.client_name}
                                </div>`;
                            }
                            
                            html += `</div>`;
                            return html;
                        }
                    },
                    {
                        data: 'stock',
                        render: function(data, type, row) {
                            let html = `<div><span class="fw-medium">${data}</span>`;
                            
                            // Add duplicate indicators row (Stock duplicates)
                            let duplicatesRow = '<div class="duplicate-indicators mt-1">';
                            let hasDuplicates = false;
                            
                            // Stock duplicate indicator
                            if (row.stock_duplicates && parseInt(row.stock_duplicates) > 0) {
                                duplicatesRow += `<span class="stock-duplicate duplicate-indicator me-1" 
                                    onclick="showDuplicateOrdersModal('stock', '${row.stock}', ${row.id})" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?= lang('App.stock_duplicate_tooltip') ?>">
                                    <i class="ri-archive-line"></i> ${row.stock_duplicates}
                                </span>`;
                                hasDuplicates = true;
                            }
                            
                            duplicatesRow += '</div>';
                            
                            if (hasDuplicates) {
                                html += duplicatesRow;
                            }
                            
                            if (row.salesperson_name && row.salesperson_name !== 'N/A') {
                                html += `<div class="text-muted small"><?= lang('App.sales') ?>: ${row.salesperson_name}</div>`;
                            }
                            html += `</div>`;
                            return html;
                        }
                    },
                    {
                        data: 'client_name',
                        render: function(data, type, row) {
                            let html = `<div><span class="fw-medium">${row.vehicle || data}</span>`;
                            if (row.vin) {
                                html += `<div class="text-muted small">VIN: ${row.vin}`;
                                
                                // Add VIN duplicate indicator right after VIN (only show when 2+ additional duplicates)
                                if (row.vin_duplicates && parseInt(row.vin_duplicates) > 1 && row.vin) {
                                    const count = row.vin_duplicates;
                                    const tooltip = `Total of ${count + 1} orders with same VIN`;
                                    
                                    html += `<span class="duplicate-indicator vin-duplicate ms-1" 
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="${tooltip}"
                                        onclick="showDuplicateOrdersModal('vin', '${row.vin}', ${row.id}); event.stopPropagation();">
                                        <i class="ri-file-copy-line"></i><small>${count}</small>
                                    </span>`;
                                }
                                
                                html += `</div>`;
                            }
                            
                            // Add tooltip for instructions if available (removed client info)
                            if (row.instructions && row.instructions.trim() !== '') {
                                let tooltipContent = `<strong><?= lang('App.instructions') ?>:</strong><br>${row.instructions.replace(/\n/g, '<br>')}`;
                                
                                html += `<div class="mt-1">
                                    <span class="badge bg-warning-subtle text-warning-emphasis cursor-pointer" 
                                          data-bs-toggle="tooltip" 
                                          data-bs-placement="top" 
                                          data-bs-html="true"
                                          title="${tooltipContent.replace(/"/g, '&quot;')}"
                                          style="font-size: 0.7rem; border: 1px solid rgba(255, 193, 7, 0.3);">
                                        <i class="ri-file-text-line"></i> <?= lang('App.instructions') ?>
                                    </span>
                                </div>`;
                            }
                            
                            html += `</div>`;
                            return html;
                        }
                    },
                    {
                        data: 'due',
                        render: function(data, type, row) {
                            // For display, create styled due date with subtle colors
                            if (type === 'display') {
                                let dateValue = row.date;
                                let timeValue = row.time;
                                
                                if (!dateValue || dateValue === 'N/A') {
                                    return '<div class="text-center"><span class="text-muted small">No date set</span></div>';
                                }
                                
                                // Parse the date and time
                                const today = new Date();
                                today.setHours(0, 0, 0, 0);
                                
                                const orderDate = new Date(dateValue + (timeValue ? ' ' + timeValue : ''));
                                const isToday = orderDate.toDateString() === today.toDateString();
                                const isTomorrow = orderDate.toDateString() === new Date(today.getTime() + 24 * 60 * 60 * 1000).toDateString();
                                const isPast = orderDate < today;
                                const isUrgent = !isPast && orderDate.getTime() - today.getTime() < 2 * 60 * 60 * 1000;
                                
                                let html = '<div class="text-center">';
                                
                                // Format date part with subtle styling
                                const formattedDate = orderDate.toLocaleDateString('en-US', { 
                                    month: 'short', 
                                    day: 'numeric',
                                    year: orderDate.getFullYear() !== today.getFullYear() ? 'numeric' : undefined
                                });
                                
                                // Format time part
                                const formattedTime = timeValue ? 
                                    orderDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : 
                                    '';
                                
                                // Date with subtle color coding
                                if (isPast) {
                                    html += `<div class="text-danger-emphasis fw-medium" style="font-size: 0.9rem;">${formattedDate}</div>`;
                                } else if (isToday) {
                                    html += `<div class="text-warning-emphasis fw-medium" style="font-size: 0.9rem;">${formattedDate}</div>`;
                                } else if (isTomorrow) {
                                    html += `<div class="text-info-emphasis fw-medium" style="font-size: 0.9rem;">${formattedDate}</div>`;
                                } else {
                                    html += `<div class="text-body-emphasis fw-medium" style="font-size: 0.9rem;">${formattedDate}</div>`;
                                }
                                
                                // Time with icon
                                if (formattedTime) {
                                    let timeColor = 'text-muted';
                                    if (isPast) timeColor = 'text-danger-emphasis';
                                    else if (isUrgent) timeColor = 'text-warning-emphasis';
                                    
                                    html += `<div class="${timeColor}" style="font-size: 1rem; font-weight: 500; line-height: 1.2;">
                                        <i class="ri-time-line" style="font-size: 0.9rem;"></i> ${formattedTime}
                                    </div>`;
                                }
                                
                                // Status indicator with subtle text
                                if (isPast) {
                                    html += '<div class="text-danger" style="font-size: 0.85rem; font-weight: 600;">Overdue</div>';
                                } else if (isUrgent) {
                                    html += '<div class="text-warning" style="font-size: 0.85rem; font-weight: 600;">Urgent</div>';
                                } else if (isToday) {
                                    html += '<div class="text-warning" style="font-size: 0.85rem; font-weight: 600;">Today</div>';
                                } else if (isTomorrow) {
                                    html += '<div class="text-info" style="font-size: 0.85rem; font-weight: 600;">Tomorrow</div>';
                                }
                                
                                html += '</div>';
                                return html;
                            }
                            
                            // For sorting and other operations, return the raw data
                            return data || '';
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            // Handle undefined/null status data
                            if (!data || typeof data !== 'string') {
                                data = 'pending'; // Default to pending if status is missing
                            }
                            
                            if (type === 'display') {
                                const statusOptions = [
                                    {value: 'pending', label: 'Pending', class: 'warning'},
                                    {value: 'in_progress', label: 'In Progress', class: 'primary'},
                                    {value: 'completed', label: 'Completed', class: 'success'},
                                    {value: 'cancelled', label: 'Cancelled', class: 'danger'}
                                ];

                                let options = '';
                                statusOptions.forEach(option => {
                                    const selected = option.value === data ? 'selected' : '';
                                    options += `<option value="${option.value}" ${selected}>${option.label}</option>`;
                                });

                                return `
                                    <select class="form-select form-select-sm status-dropdown status-${data}" 
                                            data-order-id="${row.id}" 
                                            style="width: 120px; font-size: 11px;">
                                        ${options}
                                    </select>
                                `;
                            }
                            return data || 'pending';
                        },
                        orderable: false
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex justify-content-center gap-2 action-buttons">
                                    <a href="#" class="link-primary fs-15 btn-view" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.view_sales_order') ?>">
                                        <i class="ri-eye-fill"></i>
                                    </a>
                                    <a href="#" class="link-success fs-15 btn-edit" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.edit_sales_order') ?>">
                                        <i class="ri-edit-fill"></i>
                                    </a>
                                    <a href="#" class="link-danger fs-15 btn-delete" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.delete') ?>">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [], // No client-side ordering - let server handle the sorting
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                language: {
                    url: function() {
                        const currentLang = document.documentElement.lang || 
                                           localStorage.getItem('locale') || 
                                           '<?= session()->get('locale') ?? 'en' ?>';
                                            
                        const baseUrl = window.baseUrl || '<?= base_url() ?>';
                                            
                        const langMap = {
                            'es': baseUrl + 'assets/libs/datatables/i18n/es-ES.json',
                            'pt': baseUrl + 'assets/libs/datatables/i18n/pt-BR.json',
                            'en': baseUrl + 'assets/libs/datatables/i18n/en-US.json'
                        };
                                            
                        return langMap[currentLang] || langMap['en'];
                    }(),
                    processing: "<?= lang('App.loading') ?>...",
                    lengthMenu: "<?= lang('App.show') ?> _MENU_ <?= lang('App.entries') ?>",
                    zeroRecords: `
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="calendar" class="icon-lg text-muted"></i>
                            </div>
                            <h5 class="text-muted">No orders scheduled for tomorrow</h5>
                            <p class="text-muted mb-3">Plan ahead and schedule orders for tomorrow.</p>
                            <button onclick="openNewOrderModal()" class="btn btn-primary btn-sm">
                                <i data-feather="plus-circle" class="icon-sm me-1"></i> <?= lang('App.add_sales_order') ?>
                            </button>
                        </div>
                    `,
                    info: "<?= lang('App.showing') ?> _START_ <?= lang('App.to') ?> _END_ <?= lang('App.of') ?> _TOTAL_ <?= lang('App.orders') ?>",
                    infoEmpty: "<?= lang('App.no_orders_scheduled_tomorrow') ?>",
                    infoFiltered: "",
                    search: "<?= lang('App.search_orders') ?>:",
                    paginate: {
                        first: "<?= lang('App.first') ?>",
                        last: "<?= lang('App.last') ?>",
                        next: "<?= lang('App.next') ?>",
                        previous: "<?= lang('App.previous') ?>"
                    }
                },
                dom: '<"row align-items-center mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>rt<"row align-items-center mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7 d-flex justify-content-end"p>>',
                rowCallback: function(row, data, index) {
                    // Apply CSS class to the row based on order status and timing
                    if (data.row_class && data.row_class !== '') {
                        $(row).addClass(data.row_class);
                    }
                },
                initComplete: function(settings, json) {
                    // Update order count from initial data
                    const count = json.recordsFiltered || 0;
                    updateTomorrowOrderCount(count);
                    
                    // Single column adjustment without redraw
                    setTimeout(() => {
                        this.api().columns.adjust();
                    }, 100);
                },
                drawCallback: function(settings) {
                    // Update order count in title
                    const api = this.api();
                    const pageInfo = api.page.info();
                    const count = pageInfo.recordsDisplay || 0;
                    updateTomorrowOrderCount(count);

                    // Only refresh icons and tooltips - avoid operations that trigger redraws
                    setTimeout(() => {
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                        
                        // Apply row status classes
                        $('#tomorrow-orders-table tbody tr').each(function() {
                            const $row = $(this);
                            const $statusDropdown = $row.find('.status-dropdown');
                            
                            if ($statusDropdown.length > 0) {
                                const status = $statusDropdown.val();
                                $row.removeClass('order-row-pending order-row-in-progress order-row-completed order-row-cancelled');
                                if (status) {
                                    $row.addClass(`order-row-${status.replace('_', '-')}`);
                                }
                            }
                        });
                        
                        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                            tooltipTriggerList.map(function (tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl);
                            });
                        }
                    }, 10);

                    // Status change event listeners are handled globally in index.php
                    
                    // Add row click event listeners
                    $('#tomorrow-orders-table tbody tr').off('click').on('click', function(e) {
                        // Don't trigger row click if clicking on action buttons, links, or dropdowns
                        if ($(e.target).closest('.action-buttons').length > 0 || 
                            $(e.target).closest('a').length > 0 || 
                            $(e.target).hasClass('badge') ||
                            $(e.target).hasClass('status-dropdown') ||
                            $(e.target).closest('select').length > 0) {
                            return;
                        }
                        
                        // Get the order ID from the row data and open view modal
                        const table = $('#tomorrow-orders-table').DataTable();
                        const rowData = table.row(this).data();
                        if (rowData && rowData.id) {
                            if (typeof window.openViewModal === 'function') {
                                window.openViewModal(rowData.id);
                            } else {
                                window.location.href = `<?= base_url('sales_orders/view/') ?>${rowData.id}`;
                            }
                        }
                    });

                    // Setup action button handlers  
                    $('#tomorrow-orders-table .btn-view').off('click.tomorrowTable').on('click.tomorrowTable', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const orderId = $(this).data('id');
                        if (typeof window.openViewModal === 'function') {
                            window.openViewModal(orderId);
                        } else {
                            window.location.href = `<?= base_url('sales_orders/view/') ?>${orderId}`;
                        }
                    });
                    
                    $('#tomorrow-orders-table .btn-edit').off('click.tomorrowTable').on('click.tomorrowTable', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const orderId = $(this).data('id');
                        if (typeof window.openEditModal === 'function') {
                            window.openEditModal(orderId);
                        } else {
                            console.error('Edit modal not available');
                        }
                    });
                    
                    $('#tomorrow-orders-table .btn-delete').off('click.tomorrowTable').on('click.tomorrowTable', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const orderId = $(this).data('id');
                        if (typeof window.deleteOrder === 'function') {
                            window.deleteOrder(orderId);
                        } else {
                            console.error('Delete function not available');
                        }
                    });

                    // Ensure table uses full width on every draw
                    $('#tomorrow-orders-table').css({'width': '100%', 'max-width': '100%'});
                    $('.dataTables_wrapper').css({'width': '100%', 'max-width': '100%'});
                }
            });

            isInitializing = false;

        } catch (error) {
            console.error('❌ Error initializing Tomorrow DataTable:', error);
            isInitializing = false;
        }
    }

    // Refresh function
    $('#refreshTomorrowTable').on('click', function() {
        if (tomorrowOrdersTable) {
            tomorrowOrdersTable.ajax.reload(null, false);
            showToast('success', '<?= lang('App.table_refreshed') ?>');
        }
    });

    // Global functions
    window.openNewOrderModal = function() {
        try {
            if (typeof openModalForNewOrder === 'function') {
                openModalForNewOrder();
            } else if (typeof window.openModalForNewOrder === 'function') {
                window.openModalForNewOrder();
            } else {
                console.warn('openModalForNewOrder function not available');
                showToast('info', 'Please use the main Add Order button');
            }
        } catch (error) {
            console.error('Error in openNewOrderModal:', error);
            showToast('error', 'Error opening order form');
        }
    };

    // Duplicate Orders Modal Function
    function showDuplicateOrdersModal(field, value, currentOrderId = null) {
        console.log('🔍 showDuplicateOrdersModal called (Tomorrow):', { field, value, currentOrderId });
        
        // Prevent multiple calls
        if (window.modalInProgress) {
            console.log('⚠️ Modal already in progress, ignoring call');
            return;
        }
        window.modalInProgress = true;
        
        // CRITICAL: Temporarily suspend global filter operations
        window.globalFiltersTemporarilySuspended = true;
        console.log('🚫 Global filters temporarily suspended for modal');
        
        // Stop any running dashboard timers that might interfere
        if (window.dashboardFilterWatcher) {
            console.log('⏸️ Pausing dashboard filter watcher');
            clearInterval(window.dashboardFilterWatcher);
            window.dashboardFilterWatcher = null;
        }
        
        const modal = document.getElementById('duplicateOrdersModal');
        const modalContent = document.getElementById('duplicateOrdersContent');
        
        if (!modal || !modalContent) {
            console.error('❌ Modal elements not found');
            window.modalInProgress = false;
            window.globalFiltersTemporarilySuspended = false;
            return;
        }
        
        // Reset modal content
        modalContent.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden"><?= lang('App.loading') ?>...</span>
                </div>
                <p class="mt-2 mb-0 text-muted small"><?= lang('App.loading_duplicate_orders') ?>...</p>
            </div>
        `;
        
        // Show modal first
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
        console.log('✅ Modal.show() called');
        
        // CRITICAL: Use setTimeout to separate AJAX execution from modal show
        setTimeout(() => {
            console.log('⏰ Executing AJAX after modal show...');
            
            const csrf_token = '<?= csrf_hash() ?>';
            
            $.ajax({
                url: '<?= base_url('sales_orders/getDuplicateOrders') ?>',
                type: 'POST',
                data: { field, value, current_order_id: currentOrderId, csrf_token },
                success: function(response) {
                    console.log('✅ AJAX Success:', response);
                    modalContent.innerHTML = response;
                    
                    // Force visibility after content update
                    modalContent.style.display = 'block';
                    modalContent.style.visibility = 'visible';
                    modalContent.style.opacity = '1';
                    
                    // Initialize tooltips for new content
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        const tooltips = modalContent.querySelectorAll('[data-bs-toggle="tooltip"]');
                        tooltips.forEach(el => new bootstrap.Tooltip(el));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ AJAX Error:', { xhr, status, error });
                    modalContent.innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            <i class="ri-error-warning-line me-2"></i>
                            <?= lang('App.error_loading_duplicates') ?>
                        </div>
                    `;
                }
            });
        }, 300);
        
        // Clean up when modal closes
        modal.addEventListener('hidden.bs.modal', function() {
            console.log('🧹 Modal closed, cleaning up...');
            window.modalInProgress = false;
            window.globalFiltersTemporarilySuspended = false;
            
            // Restart dashboard sync if it was running
            if (typeof startDashboardSync === 'function') {
                console.log('🔄 Restarting dashboard sync...');
                startDashboardSync();
            }
        }, { once: true });
    }
    
    // Make function globally available
    window.showDuplicateOrdersModal = showDuplicateOrdersModal;

    // Toast function
    // Toast function
function showToast(type, message) {
        if (typeof Toastify !== 'undefined') {
            const colors = {
                success: "#28a745",
                error: "#dc3545", 
                info: "#17a2b8",
                warning: "#ffc107"
            };
            
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                style: {
                    background: colors[type] || colors.info,
                }
            }).showToast();
        }
    }

    // Initialize DataTable with delay
    setTimeout(() => {
        initializeTomorrowDataTable();
    }, 500);

    // Additional Feather icons initialization
    setTimeout(() => {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 1200);

    // Initialize enhanced table indicators after DataTable is ready
    setTimeout(() => {
        if (typeof EnhancedTableIndicators !== 'undefined') {
            EnhancedTableIndicators.initializeForContainer(document);
            console.log('✨ Enhanced table indicators initialized for tomorrow orders');
        }
    }, 1000);

});
</script>
