<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-center">
                    <i data-feather="calendar" class="icon-sm me-1"></i>
                    <?= lang('App.week_view') ?> <span id="weekOrderCount"></span> - <?= date('M j', strtotime('monday this week')) ?> to <?= date('M j, Y', strtotime('sunday this week')) ?>
                </h4>
                <div class="flex-shrink-0">
                    <button id="refreshWeekTable" class="btn btn-secondary btn-sm me-2">
                        <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                        <?= lang('App.refresh') ?>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="week-orders-table" class="table table-borderless table-hover table-nowrap align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><?= lang('App.order_id') ?></th>
                                <th scope="col"><?= lang('App.stock') ?></th>
                                <th scope="col"><?= lang('App.client') ?></th>
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
/* Same styles as other content files */
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

#week-orders-table {
    width: 100% !important;
}

#week-orders-table_wrapper {
    width: 100% !important;
}

#week-orders-table thead th {
    width: auto !important;
}

.dataTables_wrapper {
    width: 100% !important;
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

/* Center table headers */
#week-orders-table thead th {
    text-align: center !important;
}

/* Order count styling in title */
#weekOrderCount {
    font-weight: 600;
    margin-left: 0.25rem;
    transition: color 0.3s ease;
}

/* Clickeable row styling */
#week-orders-table tbody tr {
    cursor: pointer !important;
    transition: background-color 0.15s ease !important;
}

#week-orders-table tbody tr:hover {
    background-color: rgba(64, 81, 137, 0.05) !important;
}

#week-orders-table tbody tr:hover td {
    background-color: transparent !important;
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
</style>

<script>
// Wait for DataTables to be available
function waitForDataTablesOnWeek(callback) {
    if (typeof $ !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForDataTablesOnWeek(callback);
        }, 100);
    }
}

waitForDataTablesOnWeek(function() {

    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    let weekOrdersTable;
    let isInitializing = false;

    // Get current week date range (Monday to Sunday)
    function getCurrentWeekRange() {
        const now = new Date();
        const dayOfWeek = now.getDay();
        const monday = new Date(now);
        
        // Adjust to get Monday (0 = Sunday, 1 = Monday, etc.)
        const daysToMonday = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
        monday.setDate(now.getDate() + daysToMonday);
        
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        
        // Format to YYYY-MM-DD
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };
        
        return {
            start: formatDate(monday),
            end: formatDate(sunday)
        };
    }

    // Function to update the order count in title
    function updateWeekOrderCount(recordsFiltered) {
        const countElement = document.getElementById('weekOrderCount');
        const badgeElement = document.getElementById('weekOrdersBadge');
        
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
    function initializeWeekDataTable() {
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
        const tableElement = document.getElementById('week-orders-table');
        if (!tableElement) {
            console.error('❌ Table #week-orders-table not found');
            isInitializing = false;
            return;
        }

        try {
            // Destroy existing table if it exists
            if ($.fn.DataTable.isDataTable('#week-orders-table')) {
                $('#week-orders-table').DataTable().destroy();
                $('#week-orders-table').off();
            }

            // Force table width before initialization
            $('#week-orders-table').css('width', '100%');
            $('.table-responsive').css('width', '100%');

            const weekRange = getCurrentWeekRange();

            weekOrdersTable = $('#week-orders-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                scrollX: false,
                autoWidth: false,
                columnDefs: [
                    { width: "15%", targets: 0, className: "text-center" }, // Order ID
                    { width: "20%", targets: 1, className: "text-center" }, // Stock
                    { width: "25%", targets: 2, className: "text-center" }, // Client
                    { width: "12%", targets: 3, className: "text-center" }, // Date
                    { width: "13%", targets: 4, className: "text-center" }, // Status
                    { width: "15%", targets: 5, orderable: false, searchable: false, className: "text-center" } // Actions
                ],
                ajax: {
                    url: '<?= base_url('sales_orders/all_content') ?>',
                    type: 'POST',
                    data: function(d) {
                        // Filter for current week's orders
                        d.date_from_filter = weekRange.start;
                        d.date_to_filter = weekRange.end;
                        
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
                        console.error('Error loading week data:', error, xhr.responseText);
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
                                html += `<div class="text-muted small">VIN: ${row.vin}</div>`;
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
                            if (data && data !== 'N/A') {
                                return data; // Return the HTML directly since it's already formatted
                            }
                            return '<div class="text-center"><span class="text-muted">N/A</span></div>';
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            const statusClasses = {
                                'completed': 'status-indicator status-completed',
                                'pending': 'status-indicator status-pending',
                                'cancelled': 'status-indicator status-cancelled',
                                'processing': 'status-indicator status-processing',
                                'in_progress': 'status-indicator status-processing'
                            };

                            const cssClass = statusClasses[data] || 'status-indicator';
                            const displayText = data.charAt(0).toUpperCase() + data.slice(1).replace('_', ' ');

                            return `<span class="${cssClass}">${displayText}</span>`;
                        },
                        orderable: false
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex justify-content-center gap-2 action-buttons">
                                    <a href="<?= base_url('sales_orders/view/') ?>${data}" class="link-primary fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.view_sales_order') ?>">
                                        <i class="ri-eye-fill"></i>
                                    </a>
                                    <a href="#" class="link-success fs-15 edit-order-btn" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.edit_sales_order') ?>">
                                        <i class="ri-edit-fill"></i>
                                    </a>
                                    <a href="#" class="link-danger fs-15 delete-order-btn" data-id="${data}" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= lang('App.delete') ?>">
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
                    processing: `
                        <div class="datatable-loading-overlay">
                            <div class="datatable-loading-content">
                                <div class="spinner-border text-primary me-2" role="status">
                                    <span class="visually-hidden"><?= lang('App.loading') ?>...</span>
                                </div>
                                <span class="datatable-loading-text"><?= lang('App.loading_orders') ?>...</span>
                            </div>
                        </div>
                    `,
                    lengthMenu: "<?= lang('App.show') ?> _MENU_ <?= lang('App.entries') ?>",
                    zeroRecords: `
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i data-feather="calendar" class="icon-lg text-muted"></i>
                            </div>
                            <h5 class="text-muted">No orders scheduled this week</h5>
                            <p class="text-muted mb-3">Plan your week by scheduling orders.</p>
                            <button onclick="openNewOrderModal()" class="btn btn-primary btn-sm">
                                <i data-feather="plus-circle" class="icon-sm me-1"></i> <?= lang('App.add_sales_order') ?>
                            </button>
                        </div>
                    `,
                    info: "<?= lang('App.showing') ?> _START_ <?= lang('App.to') ?> _END_ <?= lang('App.of') ?> _TOTAL_ <?= lang('App.orders') ?>",
                    infoEmpty: "<?= lang('App.no_orders_scheduled_week') ?>",
                    infoFiltered: "",
                    search: "<?= lang('App.search_orders') ?>:",
                    paginate: {
                        first: "<?= lang('App.first') ?>",
                        last: "<?= lang('App.last') ?>",
                        next: "<?= lang('App.next') ?>",
                        previous: "<?= lang('App.previous') ?>"
                    }
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                rowCallback: function(row, data, index) {
                    // Apply CSS class to the row based on order status and timing
                    if (data.row_class && data.row_class !== '') {
                        $(row).addClass(data.row_class);
                    }
                },
                initComplete: function(settings, json) {
                    
                    const table = this.api();

                    setTimeout(() => {
                        table.columns.adjust();
                        $('#week-orders-table').css('width', '100%');
                    }, 50);

                    setTimeout(() => {
                        table.columns.adjust().draw();
                        $('#week-orders-table').css('width', '100%');
                    }, 150);

                    setTimeout(() => {
                        table.columns.adjust();
                        $('#week-orders-table').css('width', '100%');
                        $('.dataTables_wrapper').css('width', '100%');
                    }, 300);

                    // Update order count in title - try multiple sources for count
                    let count = 0;
                    if (json.recordsFiltered !== undefined) {
                        count = json.recordsFiltered;
                    } else if (json.recordsTotal !== undefined) {
                        count = json.recordsTotal;
                    } else if (json.data && Array.isArray(json.data)) {
                        count = json.data.length;
                    }
                    
                    updateWeekOrderCount(count);
                },
                drawCallback: function(settings) {
                    // Update order count in title
                    const api = this.api();
                    const pageInfo = api.page.info();
                    
                    
                    // Try multiple sources for the count
                    let count = 0;
                    if (pageInfo.recordsDisplay !== undefined) {
                        count = pageInfo.recordsDisplay;
                    } else if (pageInfo.recordsFiltered !== undefined) {
                        count = pageInfo.recordsFiltered;
                    } else if (pageInfo.recordsTotal !== undefined) {
                        count = pageInfo.recordsTotal;
                    } else {
                        // Fallback: count visible rows
                        count = api.rows({ page: 'all' }).count();
                    }
                    
                    updateWeekOrderCount(count);

                    // Initialize Feather icons after each redraw
                    if (typeof feather !== 'undefined') {
                        setTimeout(() => {
                            feather.replace();
                        }, 50);
                    }

                    // Initialize tooltips
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.map(function (tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    }

                    // Add row click event listeners
                    $('#week-orders-table tbody tr').off('click').on('click', function(e) {
                        // Don't trigger row click if clicking on action buttons or links
                        if ($(e.target).closest('.action-buttons').length > 0 || 
                            $(e.target).closest('a').length > 0 || 
                            $(e.target).hasClass('badge')) {
                            return;
                        }
                        
                        // Get the order ID from the row data
                        const table = $('#week-orders-table').DataTable();
                        const rowData = table.row(this).data();
                        if (rowData && rowData.id) {
                            window.location.href = `<?= base_url('sales_orders/view/') ?>${rowData.id}`;
                        }
                    });

                    // Ensure table uses full width on every draw
                    $('#week-orders-table').css('width', '100%');
                    $('.dataTables_wrapper').css('width', '100%');
                }
            });

            isInitializing = false;

        } catch (error) {
            console.error('❌ Error initializing Week DataTable:', error);
            isInitializing = false;
        }
    }

    // Refresh function
    $('#refreshWeekTable').on('click', function() {
        if (weekOrdersTable) {
            weekOrdersTable.ajax.reload(null, false);
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
        initializeWeekDataTable();
    }, 500);

    // Additional Feather icons initialization
    setTimeout(() => {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 1200);

});
</script>
